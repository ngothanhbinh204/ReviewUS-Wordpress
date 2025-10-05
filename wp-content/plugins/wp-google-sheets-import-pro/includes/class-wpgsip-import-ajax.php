<?php
/**
 * Enhanced Import AJAX Handlers
 * 
 * Additional AJAX handlers for selective import and taxonomy management
 *
 * @package    WP_Google_Sheets_Import_Pro
 * @subpackage WP_Google_Sheets_Import_Pro/includes
 */

if (!defined('ABSPATH')) {
    exit;
}

class WPGSIP_Import_Ajax
{
    /**
     * Enhanced import preview with post type and taxonomy detection
     */
    public static function ajax_import_preview_enhanced()
    {
        check_ajax_referer('wpgsip_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'wp-gs-import-pro')));
        }
        
        $tenant_id = isset($_POST['tenant_id']) ? sanitize_text_field($_POST['tenant_id']) : 'default';
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
        
        try {
            $google_sheets = new WPGSIP_Google_Sheets($tenant_id);
            // Pass post_type to fetch_data to use correct sheet range
            $data = $google_sheets->fetch_data(false, $post_type);
            
            if (empty($data)) {
                wp_send_json_error(array('message' => __('No data found in sheet', 'wp-gs-import-pro')));
            }
            
            $taxonomy_manager = new WPGSIP_Taxonomy_Manager();
            
            // Debug: Log sheet structure
            error_log('Sheet columns: ' . print_r(array_keys($data[0]), true));
            
            // Detect taxonomies in sheet
            $detected_taxonomies = $taxonomy_manager->detect_taxonomy_columns($data[0], $post_type);
            
            // Debug: Log detected taxonomies
            error_log('Detected taxonomies: ' . print_r($detected_taxonomies, true));
            
            // ALWAYS show taxonomy selectors even if not in sheet
            // Get available taxonomies for post type
            $all_taxonomies = $taxonomy_manager->get_taxonomies_for_post_type($post_type);
            
            // If no columns detected, add all available taxonomies as empty
            if (empty($detected_taxonomies)) {
                foreach ($all_taxonomies as $slug => $info) {
                    $detected_taxonomies[$slug] = array(
                        'label' => $info['label'],
                        'column_name' => $info['column_names'][0], // Use first column name as default
                        'has_data' => false
                    );
                }
            }
            
            // Get available terms for each taxonomy
            $available_terms = array();
            foreach ($all_taxonomies as $slug => $info) {
                $terms = $taxonomy_manager->get_terms_for_taxonomy($slug);
                if (!empty($terms)) {
                    $available_terms[$slug] = array_map(function($term) {
                        return array(
                            'term_id' => $term->term_id,
                            'name' => $term->name,
                            'slug' => $term->slug
                        );
                    }, $terms);
                } else {
                    $available_terms[$slug] = array(); // Empty array if no terms
                }
            }
            
            // Check for existing posts
            foreach ($data as &$row) {
                // Extract the ACTUAL title that will be used when creating/updating post
                $actual_title = self::extract_post_title($row);
                
                if (empty($actual_title)) {
                    $row['existing_post'] = false;
                    continue;
                }
                
                $existing_post_id = self::find_existing_post_by_title($actual_title, $post_type);
                
                if ($existing_post_id) {
                    $existing_post = get_post($existing_post_id);
                    $row['existing_post'] = array(
                        'id' => $existing_post_id,
                        'title' => $existing_post->post_title,
                        'status' => $existing_post->post_status,
                        'url' => get_permalink($existing_post_id)
                    );
                } else {
                    $row['existing_post'] = false;
                }
            }
            
            // Force objects instead of arrays for JavaScript
            $response = array(
                'count' => count($data),
                'data' => $data,
                'taxonomies' => empty($detected_taxonomies) ? new stdClass() : $detected_taxonomies,
                'available_terms' => empty($available_terms) ? new stdClass() : $available_terms,
                'post_type' => $post_type
            );
            
            error_log('Final response taxonomies: ' . print_r($response['taxonomies'], true));
            error_log('Final response available_terms: ' . print_r($response['available_terms'], true));
            
            wp_send_json_success($response);
            
        } catch (Exception $e) {
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }

    /**
     * Selective import execution
     */
    public static function ajax_import_selective()
    {
        check_ajax_referer('wpgsip_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Permission denied', 'wp-gs-import-pro')));
        }
        
        $tenant_id = isset($_POST['tenant_id']) ? sanitize_text_field($_POST['tenant_id']) : 'default';
        $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
        $row_ids = isset($_POST['row_ids']) ? array_map('sanitize_text_field', $_POST['row_ids']) : array();
        $default_taxonomies = isset($_POST['default_taxonomies']) ? $_POST['default_taxonomies'] : array();
        $row_taxonomies = isset($_POST['row_taxonomies']) ? $_POST['row_taxonomies'] : array();
        
        if (empty($row_ids)) {
            wp_send_json_error(array('message' => __('No rows selected', 'wp-gs-import-pro')));
        }
        
        try {
            $google_sheets = new WPGSIP_Google_Sheets($tenant_id);
            // Pass post_type to fetch_data to use correct sheet range
            $all_data = $google_sheets->fetch_data(false, $post_type);
            
            // Filter only selected rows
            $selected_data = array_filter($all_data, function($row) use ($row_ids) {
                return in_array($row['row_id'], $row_ids);
            });
            
            if (empty($selected_data)) {
                wp_send_json_error(array('message' => __('Selected rows not found', 'wp-gs-import-pro')));
            }
            
            $importer = new WPGSIP_Importer($tenant_id);
            $taxonomy_manager = new WPGSIP_Taxonomy_Manager();
            
            $results = array(
                'processed' => 0,
                'created' => 0,
                'updated' => 0,
                'skipped' => 0,
                'errors' => 0,
                'messages' => array(),
            );
            
            foreach ($selected_data as $row) {
                // Priority 1: Use row-specific taxonomies if selected in dropdown
                $taxonomy_data = array();
                if (isset($row_taxonomies[$row['row_id']]) && !empty($row_taxonomies[$row['row_id']])) {
                    $taxonomy_data = $row_taxonomies[$row['row_id']];
                } else {
                    // Priority 2: Extract from sheet column
                    $taxonomy_data = $taxonomy_manager->extract_from_row($row, $post_type);
                }
                
                // Priority 3: Use default taxonomies for empty taxonomies
                foreach ($default_taxonomies as $taxonomy => $terms) {
                    if (empty($taxonomy_data[$taxonomy]) && !empty($terms)) {
                        $taxonomy_data[$taxonomy] = $terms;
                    }
                }
                
                $result = self::import_single_row($importer, $row, $post_type, $taxonomy_data, $taxonomy_manager);
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
            
            wp_send_json_success($results);
            
        } catch (Exception $e) {
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }

    /**
     * Import single row with post type and taxonomy support
     */
    private static function import_single_row($importer, $row, $post_type, $taxonomy_data, $taxonomy_manager)
    {
        try {
            // Check if should skip
            if (method_exists($importer, 'should_skip_row_public')) {
                if ($importer->should_skip_row_public($row)) {
                    return array(
                        'status' => 'skipped',
                        'message' => sprintf(__('Row %s: Skipped by filter', 'wp-gs-import-pro'), $row['row_id'])
                    );
                }
            }
            
            // Check if content is empty (skip if empty)
            if (empty($row['content'])) {
                return array(
                    'status' => 'skipped',
                    'message' => sprintf(__('Row %s: Content is empty', 'wp-gs-import-pro'), $row['row_id'])
                );
            }
            
            // Get the actual post title that will be used (same logic as importer)
            $actual_post_title = self::extract_post_title($row);
            
            // Check if post exists using the ACTUAL title that will be created
            $existing_post_id = self::find_existing_post_by_title($actual_post_title, $post_type);
            
            if ($existing_post_id) {
                // Update existing post
                $post_id = self::update_post_with_taxonomy($importer, $existing_post_id, $row, $post_type, $taxonomy_data, $taxonomy_manager);
                $action = 'updated';
                $post_type_label = $post_type === 'thing_to_do' ? 'Thing To Do' : 'Post';
                $message = sprintf(__('Row %s: Updated %s ID %d', 'wp-gs-import-pro'), $row['row_id'], $post_type_label, $post_id);
            } else {
                // Create new post
                $post_id = self::create_post_with_taxonomy($importer, $row, $post_type, $taxonomy_data, $taxonomy_manager);
                $action = 'created';
                $post_type_label = $post_type === 'thing_to_do' ? 'Thing To Do' : 'Post';
                $message = sprintf(__('Row %s: Created %s ID %d', 'wp-gs-import-pro'), $row['row_id'], $post_type_label, $post_id);
            }
            
            return array(
                'status' => $action,
                'message' => $message,
                'post_id' => $post_id,
            );
            
        } catch (Exception $e) {
            return array(
                'status' => 'error',
                'message' => sprintf(__('Row %s: Error - %s', 'wp-gs-import-pro'), $row['row_id'], $e->getMessage())
            );
        }
    }

    /**
     * Create post with taxonomy support
     */
    private static function create_post_with_taxonomy($importer, $row, $post_type, $taxonomy_data, $taxonomy_manager)
    {
        // Use reflection to access private method create_post
        $reflection = new ReflectionClass($importer);
        $method = $reflection->getMethod('create_post');
        $method->setAccessible(true);
        
        // Pass post_type parameter to create_post method
        $post_id = $method->invoke($importer, $row, $post_type);
        
        // Assign taxonomies
        if (!empty($taxonomy_data)) {
            $taxonomy_manager->assign_taxonomies($post_id, $taxonomy_data, $post_type);
        }
        
        return $post_id;
    }

    /**
     * Update post with taxonomy support
     */
    private static function update_post_with_taxonomy($importer, $post_id, $row, $post_type, $taxonomy_data, $taxonomy_manager)
    {
        // Use reflection to access private method update_post
        $reflection = new ReflectionClass($importer);
        $method = $reflection->getMethod('update_post');
        $method->setAccessible(true);
        
        // Pass post_type parameter to update_post method
        $post_id = $method->invoke($importer, $post_id, $row, $post_type);
        
        // Assign taxonomies
        if (!empty($taxonomy_data)) {
            $taxonomy_manager->assign_taxonomies($post_id, $taxonomy_data, $post_type);
        }
        
        return $post_id;
    }

    /**
     * Find existing post by title and post type
     */
    private static function find_existing_post_by_title($title, $post_type = 'post')
    {
        global $wpdb;
        
        // Debug: Log what we're searching for
        error_log('🔍 Searching for existing post:');
        error_log('  Title: ' . $title);
        error_log('  Post Type: ' . $post_type);
        
        // Try 1: Exact match (case-sensitive)
        $post_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} 
            WHERE post_title = %s 
            AND post_type = %s 
            AND post_status != 'trash'
            LIMIT 1",
            $title,
            $post_type
        ));
        
        if ($post_id) {
            error_log('  ✅ Found existing post ID: ' . $post_id . ' (exact match)');
            return intval($post_id);
        }
        
        // Try 2: Case-insensitive match
        $post_id = $wpdb->get_var($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} 
            WHERE LOWER(post_title) = LOWER(%s) 
            AND post_type = %s 
            AND post_status != 'trash'
            LIMIT 1",
            $title,
            $post_type
        ));
        
        if ($post_id) {
            error_log('  ✅ Found existing post ID: ' . $post_id . ' (case-insensitive match)');
            return intval($post_id);
        }
        
        // Try 3: Fuzzy match - check similarity with existing posts
        $normalized_title = self::normalize_title($title);
        error_log('  🔍 Normalized search title: ' . $normalized_title);
        
        $similar_posts = $wpdb->get_results($wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts} 
            WHERE post_type = %s 
            AND post_status != 'trash'
            LIMIT 20",
            $post_type
        ));
        
        $best_match = null;
        $best_similarity = 0;
        
        foreach ($similar_posts as $post) {
            $normalized_post_title = self::normalize_title($post->post_title);
            
            // Calculate similarity percentage
            $similarity = 0;
            similar_text($normalized_title, $normalized_post_title, $similarity);
            
            if ($similarity > $best_similarity) {
                $best_similarity = $similarity;
                $best_match = $post;
            }
            
            // If similarity >= 90%, consider it a match
            if ($similarity >= 90) {
                error_log('  ✅ Found existing post ID: ' . $post->ID . ' (fuzzy match ' . round($similarity, 1) . '% similar)');
                error_log('  📝 WordPress title: ' . $post->post_title);
                error_log('  📝 Normalized WP: ' . $normalized_post_title);
                return intval($post->ID);
            }
        }
        
        error_log('  ❌ No existing post found (best match: ' . round($best_similarity, 1) . '%)');
        
        // Debug: Show top 3 similar posts
        if ($similar_posts) {
            error_log('  📋 Top similar posts:');
            $top_posts = array_slice($similar_posts, 0, 3);
            foreach ($top_posts as $post) {
                $sim = 0;
                similar_text($normalized_title, self::normalize_title($post->post_title), $sim);
                error_log('    - ID ' . $post->ID . ' (' . round($sim, 1) . '%): ' . $post->post_title);
            }
        }
        
        return false;
    }
    
    /**
     * Extract post title using same logic as importer
     * This ensures we search for existing posts using the SAME title that will be created
     */
  private static function extract_post_title($row)
{
    if (!empty($row['outline'])) {
        $outline = $row['outline'];

        // ✅ Kiểm tra xem có H1 trong outline không
        $has_h1 = preg_match('/\bH1\s*:/i', $outline);

        if ($has_h1) {
            // Tìm H1: "H1: Title text"
            if (preg_match('/H1:\s*(.+?)(?:\s+H2:|$)/is', $outline, $matches)) {
                $title = trim($matches[1]);
                // Xóa phần H2 hoặc heading khác phía sau
                $title = preg_replace('/\s+H\d+:.*$/is', '', $title);

                if (!empty($title)) {
                    error_log('📝 Extracted title from outline H1: ' . $title);
                    return $title;
                }
            }
        } else {
            // ⚡ Không có H1 trong outline → ưu tiên meta_title ngay
            if (!empty($row['meta_title'])) {
                error_log('📝 Outline không có H1, dùng meta_title: ' . $row['meta_title']);
                return $row['meta_title'];
            }

            // Nếu vẫn không có meta_title, fallback về dòng đầu tiên (nếu hợp lệ)
            $lines = explode("\n", $outline);
            $first_line = trim($lines[0]);
            if (!empty($first_line) && strlen($first_line) < 200) {
                error_log('📝 Outline không có H1 & meta_title, dùng dòng đầu: ' . $first_line);
                return $first_line;
            }
        }
    }

    // 🪶 Fallback cuối cùng nếu outline rỗng
    if (!empty($row['meta_title'])) {
        error_log('📝 Outline rỗng, fallback meta_title: ' . $row['meta_title']);
        return $row['meta_title'];
    }

    error_log('⚠️ No title found in row');
    return '';
}

    
    /**
     * Normalize title for fuzzy matching
     * Removes common suffixes, extra spaces, and converts to lowercase
     */
    private static function normalize_title($title)
    {
        // Convert to lowercase for case-insensitive comparison
        $title = mb_strtolower($title, 'UTF-8');
        
        // Remove common Vietnamese suffixes that might differ
        $suffixes = array(
            ' hiệu quả',
            ' chi tiết',
            ' từ a đến z',
            ' từ a - z',
            ' – từ a đến z',
            ' - từ a đến z',
            ' thường gặp',
            ' và cách trả lời hiệu quả',
        );
        
        foreach ($suffixes as $suffix) {
            if (mb_substr($title, -mb_strlen($suffix)) === $suffix) {
                $title = mb_substr($title, 0, -mb_strlen($suffix));
            }
        }
        
        // Normalize spaces and punctuation
        $title = preg_replace('/\s+/', ' ', $title);
        $title = preg_replace('/[–—-]+/', '-', $title); // Normalize dashes
        $title = trim($title);
        
        return $title;
    }
}