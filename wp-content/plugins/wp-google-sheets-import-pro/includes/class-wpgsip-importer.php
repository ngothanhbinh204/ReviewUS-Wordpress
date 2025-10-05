<?php

/**
 * Import functionality
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Importer
{

    /**
     * Tenant ID
     */
    private $tenant_id;

    /**
     * Settings
     */
    private $settings;

    /**
     * Logger
     */
    private $logger;

    /**
     * Google Sheets
     */
    private $google_sheets;

    /**
     * Webhook
     */
    private $webhook;

    /**
     * Content Processor
     */
    private $content_processor;

    /**
     * Constructor
     */
    public function __construct($tenant_id = 'default')
    {
        $this->tenant_id = $tenant_id;
        $settings_manager = new WPGSIP_Settings();
        $this->settings = $settings_manager->get_tenant_settings($tenant_id);
        $this->logger = new WPGSIP_Logger();
        $this->google_sheets = new WPGSIP_Google_Sheets($tenant_id);
        $this->webhook = new WPGSIP_Webhook($tenant_id);
        $this->content_processor = new WPGSIP_Content_Processor();
    }

    /**
     * Import all data
     */
    public function import_all()
    {
        try {
            $data = $this->google_sheets->fetch_data(false); // Force fresh data
            $results = array(
                'total' => count($data),
                'created' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => 0,
                'messages' => array(),
            );

            foreach ($data as $row) {
                $result = $this->import_row($row);

                if ($result['status'] === 'created') {
                    $results['created']++;
                } elseif ($result['status'] === 'updated') {
                    $results['updated']++;
                } elseif ($result['status'] === 'skipped') {
                    $results['skipped']++;
                } else {
                    $results['errors']++;
                }

                $results['messages'][] = $result['message'];
            }

            return $results;
        } catch (Exception $e) {
            throw new Exception(__('Import failed: ', 'wp-gs-import-pro') . $e->getMessage());
        }
    }

    /**
     * Import batch
     */
    public function import_batch($batch = 0, $batch_size = 10)
    {
        try {
            $data = $this->google_sheets->fetch_data(false);
            $total = count($data);
            $offset = $batch * $batch_size;
            $batch_data = array_slice($data, $offset, $batch_size);

            $results = array(
                'batch' => $batch,
                'total' => $total,
                'processed' => 0,
                'created' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => 0,
                'messages' => array(),
                'has_more' => ($offset + $batch_size) < $total,
            );

            foreach ($batch_data as $row) {
                $result = $this->import_row($row);
                $results['processed']++;

                if ($result['status'] === 'created') {
                    $results['created']++;
                } elseif ($result['status'] === 'updated') {
                    $results['updated']++;
                } elseif ($result['status'] === 'skipped') {
                    $results['skipped']++;
                } else {
                    $results['errors']++;
                }

                $results['messages'][] = $result['message'];
            }

            return $results;
        } catch (Exception $e) {
            throw new Exception(__('Batch import failed: ', 'wp-gs-import-pro') . $e->getMessage());
        }
    }

    /**
     * Import single row
     */
    public function import_row($row)
    {
        try {
            // Check if row should be skipped
            if ($this->should_skip_row($row)) {
                $this->logger->log(array(
                    'tenant_id' => $this->tenant_id,
                    'action' => 'import',
                    'sheet_row_id' => $row['row_id'],
                    'status' => 'skipped',
                    'message' => __('Row skipped based on status filter', 'wp-gs-import-pro'),
                ));

                return array(
                    'status' => 'skipped',
                    'message' => sprintf(__('Row %d: Skipped', 'wp-gs-import-pro'), $row['row_id']),
                );
            }

            // Trigger content generation if needed
            if (empty($row['content']) && $this->settings['n8n_enabled']) {
                $webhook_result = $this->webhook->trigger_content_generation($row);

                if ($webhook_result['success']) {
                    // Wait and refetch
                    $row = $this->webhook->wait_and_refetch($row['row_id']);
                }
            }

            // Check if content is still empty
            if (empty($row['content'])) {
                $this->logger->log(array(
                    'tenant_id' => $this->tenant_id,
                    'action' => 'import',
                    'sheet_row_id' => $row['row_id'],
                    'status' => 'error',
                    'message' => __('Content is empty after webhook trigger', 'wp-gs-import-pro'),
                ));

                return array(
                    'status' => 'error',
                    'message' => sprintf(__('Row %d: Content is empty', 'wp-gs-import-pro'), $row['row_id']),
                );
            }

            // Check if post exists
            $existing_post_id = $this->find_existing_post($row);

            if ($existing_post_id) {
                // Update existing post
                $post_id = $this->update_post($existing_post_id, $row);
                $action = 'updated';
                $message = sprintf(__('Row %d: Updated post ID %d', 'wp-gs-import-pro'), $row['row_id'], $post_id);
            } else {
                // Create new post
                $post_id = $this->create_post($row);
                $action = 'created';
                $message = sprintf(__('Row %d: Created post ID %d', 'wp-gs-import-pro'), $row['row_id'], $post_id);
            }

            // Log success
            $this->logger->log(array(
                'tenant_id' => $this->tenant_id,
                'action' => 'import',
                'post_id' => $post_id,
                'sheet_row_id' => $row['row_id'],
                'status' => 'success',
                'message' => $message,
                'data' => json_encode($row),
            ));

            return array(
                'status' => $action,
                'message' => $message,
                'post_id' => $post_id,
            );
        } catch (Exception $e) {
            $error_message = sprintf(__('Row %d: Error - %s', 'wp-gs-import-pro'), $row['row_id'], $e->getMessage());

            $this->logger->log(array(
                'tenant_id' => $this->tenant_id,
                'action' => 'import',
                'sheet_row_id' => $row['row_id'],
                'status' => 'error',
                'message' => $error_message,
            ));

            return array(
                'status' => 'error',
                'message' => $error_message,
            );
        }
    }

    /**
     * Check if row should be skipped
     */
    private function should_skip_row($row)
    {
        $skip_filter = $this->settings['skip_status_filter'] ?? '';

        if (empty($skip_filter)) {
            return false;
        }

        // Custom filter hook
        $should_skip = apply_filters('wpgsip_should_skip_row', false, $row, $this->tenant_id);

        return $should_skip;
    }
    
    /**
     * Public wrapper for should_skip_row (used by import AJAX)
     */
    public function should_skip_row_public($row)
    {
        return $this->should_skip_row($row);
    }

    /**
     * Find existing post
     */
    private function find_existing_post($row)
    {
        global $wpdb;

        // First, try to find by sheet row ID meta
        $post_id = $wpdb->get_var($wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta} 
            WHERE meta_key = 'gs_sheet_row_id' 
            AND meta_value = %s 
            LIMIT 1",
            $row['row_id']
        ));

        if ($post_id) {
            return $post_id;
        }

        // Try to find by title (exact match)
        $title = sanitize_text_field($row['meta_title']);
        if (!empty($title)) {
            $post_id = $wpdb->get_var($wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} 
                WHERE post_title = %s 
                AND post_type = 'post' 
                AND post_status != 'trash'
                LIMIT 1",
                $title
            ));

            if ($post_id) {
                return $post_id;
            }
        }

        return null;
    }

    /**
     * Create new post
     * @param array $row Row data from sheet
     * @param string $post_type Post type to create (post, thing_to_do, etc.)
     */
    private function create_post($row, $post_type = 'post')
    {
        $post_status = $this->settings['post_status'] ?? 'publish';
        
        // Check if content processing is enabled (default: true)
        $enable_processing = isset($this->settings['enable_content_processing']) ? $this->settings['enable_content_processing'] : true;

        $post_content = $row['content'];
        $post_title = $row['meta_title'];
        $post_excerpt = $row['meta_description'];
        $processed_data = array(); // Initialize to avoid undefined variable error

        if ($enable_processing) {
            // Process content with Content Processor
            $processed_data = $this->content_processor->process_for_seo($row['content']);

            // Use processed title if available, fallback to meta_title
            $post_title = !empty($processed_data['title']) ? $processed_data['title'] : $row['meta_title'];
            
            // Use processed meta description if available, fallback to row meta_description
            $post_excerpt = !empty($processed_data['meta_description']) ? $processed_data['meta_description'] : $row['meta_description'];
            
            // Use processed content
            $post_content = $processed_data['content'];
            
            // Add Table of Contents if enabled
            if (!empty($this->settings['enable_toc'])) {
                $toc_options = array(
                    'min_headings' => $this->settings['toc_min_headings'] ?? 3,
                    'title' => $this->settings['toc_title'] ?? 'Nội dung bài viết'
                );
                $post_content = $this->content_processor->add_table_of_contents($post_content, $toc_options);
            }
        }

        $post_data = array(
            'post_title' => sanitize_text_field($post_title),
            'post_content' => wp_kses_post($post_content),
            'post_excerpt' => sanitize_textarea_field($post_excerpt),
            'post_status' => $post_status,
            'post_type' => $post_type,  // Use parameter instead of hardcoded 'post'
            'post_author' => get_current_user_id() ?: 1,
        );

        // Custom hook before creating post
        $post_data = apply_filters('wpgsip_before_create_post', $post_data, $row, $this->tenant_id);

        $post_id = wp_insert_post($post_data, true);

        if (is_wp_error($post_id)) {
            throw new Exception($post_id->get_error_message());
        }

        // Add metadata (pass processed data for SEO fields)
        $this->update_post_meta($post_id, $row, $processed_data);

        // Handle tags/keywords
        $this->handle_keywords($post_id, $row['keyword']);

        // Custom hook after creating post
        do_action('wpgsip_after_create_post', $post_id, $row, $this->tenant_id);

        return $post_id;
    }

    /**
     * Update existing post
     * @param int $post_id Post ID to update
     * @param array $row Row data from sheet
     * @param string $post_type Post type (for consistency, not used in update)
     */
    private function update_post($post_id, $row, $post_type = 'post')
    {
        // Check if content processing is enabled (default: true)
        $enable_processing = isset($this->settings['enable_content_processing']) ? $this->settings['enable_content_processing'] : true;

        $post_content = $row['content'];
        $post_title = $row['meta_title'];
        $post_excerpt = $row['meta_description'];
        $processed_data = array(); // Initialize to avoid undefined variable error

        if ($enable_processing) {
            // Process content with Content Processor
            $processed_data = $this->content_processor->process_for_seo($row['content']);

            // Use processed title if available, fallback to meta_title
            $post_title = !empty($processed_data['title']) ? $processed_data['title'] : $row['meta_title'];
            
            // Use processed meta description if available, fallback to row meta_description
            $post_excerpt = !empty($processed_data['meta_description']) ? $processed_data['meta_description'] : $row['meta_description'];
            
            // Use processed content
            $post_content = $processed_data['content'];
            
            // Add Table of Contents if enabled
            if (!empty($this->settings['enable_toc'])) {
                $toc_options = array(
                    'min_headings' => $this->settings['toc_min_headings'] ?? 3,
                    'title' => $this->settings['toc_title'] ?? 'Nội dung bài viết'
                );
                $post_content = $this->content_processor->add_table_of_contents($post_content, $toc_options);
            }
        }

        $post_data = array(
            'ID' => $post_id,
            'post_title' => sanitize_text_field($post_title),
            'post_content' => wp_kses_post($post_content),
            'post_excerpt' => sanitize_textarea_field($post_excerpt),
        );

        // Custom hook before updating post
        $post_data = apply_filters('wpgsip_before_update_post', $post_data, $row, $this->tenant_id);

        $result = wp_update_post($post_data, true);

        if (is_wp_error($result)) {
            throw new Exception($result->get_error_message());
        }

        // Update metadata (pass processed data for SEO fields)
        $this->update_post_meta($post_id, $row, $processed_data);

        // Handle tags/keywords
        $this->handle_keywords($post_id, $row['keyword']);

        // Custom hook after updating post
        do_action('wpgsip_after_update_post', $post_id, $row, $this->tenant_id);

        return $post_id;
    }

    /**
     * Update post metadata
     */
    private function update_post_meta($post_id, $row, $processed_data = array())
    {
        // Mark as imported
        update_post_meta($post_id, 'imported_from_gs', true);
        update_post_meta($post_id, 'gs_sheet_row_id', $row['row_id']);
        update_post_meta($post_id, 'gs_tenant_id', $this->tenant_id);
        update_post_meta($post_id, 'gs_last_sync', current_time('mysql'));

        // Determine SEO values (use processed if available, otherwise use row data)
        $seo_title = !empty($processed_data['title']) ? $processed_data['title'] : $row['meta_title'];
        $seo_description = !empty($processed_data['meta_description']) ? $processed_data['meta_description'] : $row['meta_description'];

        // Yoast SEO support
        if (defined('WPSEO_VERSION')) {
            update_post_meta($post_id, '_yoast_wpseo_title', $seo_title);
            update_post_meta($post_id, '_yoast_wpseo_metadesc', $seo_description);
            update_post_meta($post_id, '_yoast_wpseo_focuskw', $row['keyword']);
        }

        // Rank Math SEO support
        if (defined('RANK_MATH_VERSION')) {
            update_post_meta($post_id, 'rank_math_title', $seo_title);
            update_post_meta($post_id, 'rank_math_description', $seo_description);
            update_post_meta($post_id, 'rank_math_focus_keyword', $row['keyword']);
        }

        // Store original data
        update_post_meta($post_id, 'gs_original_data', json_encode($row));
    }

    /**
     * Handle keywords as tags
     */
    private function handle_keywords($post_id, $keywords)
    {
        if (empty($keywords)) {
            return;
        }

        $tags = array_map('trim', explode(',', $keywords));
        wp_set_post_tags($post_id, $tags, false);
    }
}