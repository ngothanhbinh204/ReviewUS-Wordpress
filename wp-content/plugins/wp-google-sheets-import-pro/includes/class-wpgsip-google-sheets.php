<?php

/**
 * Google Sheets integration
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Google_Sheets
{

    /**
     * Settings
     */
    private $settings;

    /**
     * Tenant ID
     */
    private $tenant_id;

    /**
     * Google Client
     */
    private $client;

    /**
     * Constructor
     */
    public function __construct($tenant_id = 'default')
    {
        $this->tenant_id = $tenant_id;
        $settings_manager = new WPGSIP_Settings();
        $this->settings = $settings_manager->get_tenant_settings($tenant_id);
        $this->init_client();
    }

    /**
     * Initialize Google Client
     */
    private function init_client()
    {
        try {
            $this->client = new Google_Client();
            $this->client->setApplicationName('WP Google Sheets Import Pro');
            $this->client->setScopes([Google_Service_Sheets::SPREADSHEETS_READONLY]);

            // Check for service account JSON
            $service_account_json = $this->settings['service_account_json'] ?? '';

            if (!empty($service_account_json)) {
                // Service account authentication
                $credentials = json_decode($service_account_json, true);
                if ($credentials) {
                    $this->client->setAuthConfig($credentials);
                }
            } else {
                // For public sheets or API key authentication
                $api_key = $this->settings['google_api_key'] ?? '';
                if (!empty($api_key)) {
                    $this->client->setDeveloperKey($api_key);
                }
            }
        } catch (Exception $e) {
            error_log('WPGSIP: Failed to initialize Google Client - ' . $e->getMessage());
        }
    }

    /**
     * Fetch data from Google Sheet
     * @param bool $use_cache Whether to use cache
     * @param string $post_type Post type (post or thing_to_do) to determine which sheet range to use
     */
    public function fetch_data($use_cache = true, $post_type = 'post')
    {
        $sheet_id = $this->settings['sheet_id'] ?? '';
        
        // Select sheet range based on post type
        if ($post_type === 'thing_to_do') {
            $sheet_range = $this->settings['thing_to_do_sheet_range'] ?? 'ThingToDo1!A2:I';
        } else {
            $sheet_range = $this->settings['sheet_range'] ?? 'Post1!A2:I';
        }
        
        error_log('WPGSIP: Fetching data from Sheet ID ' . $sheet_id . ' Range ' . $sheet_range);

        if (empty($sheet_id)) {
            throw new Exception(__('Sheet ID is not configured', 'wp-gs-import-pro'));
        }

        // Check cache
        $cache_key = 'wpgsip_data_' . md5($this->tenant_id . $sheet_id . $sheet_range);
        if ($use_cache) {
            $cached_data = get_transient($cache_key);
            if ($cached_data !== false) {
                return $cached_data;
            }
        }

        try {
            $service = new Google_Service_Sheets($this->client);
            $response = $service->spreadsheets_values->get($sheet_id, $sheet_range);
            $values = $response->getValues();

            if (empty($values)) {
                return array();
            }

            // Map columns from Google Sheet - DYNAMIC based on post_type
            if ($post_type === 'thing_to_do') {
                // Thing To Do structure: outline, meta_title, meta_description, keyword, STATUS, Content, province, themes, seasons
                $column_mapping = array(
                    0 => 'outline',          // Column A (outline)
                    1 => 'meta_title',       // Column B (meta_title)
                    2 => 'meta_description', // Column C (meta_description)
                    3 => 'keyword',          // Column D (keyword)
                    4 => 'status',           // Column E (STATUS)
                    5 => 'content',          // Column F (Content)
                    6 => 'province',         // Column G (province - Provinces & Territories taxonomy)
                    7 => 'themes',           // Column H (themes - Thing Themes taxonomy)
                    8 => 'seasons',          // Column I (seasons - Seasons taxonomy)
                );
            } else {
                // Blog Post structure: outline, meta_title, meta_description, keyword, STATUS, Content, CPT, category, tags
                $column_mapping = array(
                    0 => 'outline',          // Column A (outline)
                    1 => 'meta_title',       // Column B (meta_title)
                    2 => 'meta_description', // Column C (meta_description)
                    3 => 'keyword',          // Column D (keyword)
                    4 => 'status',           // Column E (STATUS)
                    5 => 'content',          // Column F (Content)
                    6 => 'CPT',              // Column G (CPT - Custom Post Type)
                    7 => 'category',         // Column H (category)
                    8 => 'tags',             // Column I (tags)
                );
            }

            // Parse data into structured format - DYNAMIC approach
            $data = array();
            foreach ($values as $index => $row) {
                $row_data = array(
                    'row_id' => $index + 2, // +2 because we start from A2
                );
                
                // Map all available columns dynamically
                foreach ($row as $col_index => $col_value) {
                    $col_name = $column_mapping[$col_index] ?? 'column_' . $col_index;
                    $row_data[$col_name] = $col_value ?? '';
                }
                
                // Ensure required fields exist even if empty
                $required_fields = array('outline', 'meta_title', 'meta_description', 'keyword', 'status', 'content', 'CPT', 'category', 'tags');
                foreach ($required_fields as $field) {
                    if (!isset($row_data[$field])) {
                        $row_data[$field] = '';
                    }
                }
                
                $data[] = $row_data;
            }

            // Cache for configured duration
            $cache_duration = intval($this->settings['cache_duration'] ?? 300);
            set_transient($cache_key, $data, $cache_duration);

            return $data;
        } catch (Exception $e) {
            throw new Exception(__('Failed to fetch data from Google Sheets: ', 'wp-gs-import-pro') . $e->getMessage());
        }
    }

    /**
     * Test connection to Google Sheets
     */
    public function test_connection($sheet_id = null, $sheet_range = null)
    {
        if ($sheet_id === null) {
            $sheet_id = $this->settings['sheet_id'] ?? '';
        }
        if ($sheet_range === null) {
            $sheet_range = $this->settings['sheet_range'] ?? 'Post1!A2:I';
        }

        if (empty($sheet_id)) {
            throw new Exception(__('Sheet ID is required', 'wp-gs-import-pro'));
        }

        try {
            $service = new Google_Service_Sheets($this->client);
            $response = $service->spreadsheets_values->get($sheet_id, $sheet_range);
            $values = $response->getValues();

            return array(
                'success' => true,
                'rows' => count($values),
                'message' => sprintf(__('Successfully connected. Found %d rows.', 'wp-gs-import-pro'), count($values))
            );
        } catch (Exception $e) {
            throw new Exception(__('Connection failed: ', 'wp-gs-import-pro') . $e->getMessage());
        }
    }

    /**
     * Refresh data (clear cache and fetch)
     */
    public function refresh_data()
    {
        $sheet_id = $this->settings['sheet_id'] ?? '';
        $sheet_range = $this->settings['sheet_range'] ?? 'Post1!A2:I';
        $cache_key = 'wpgsip_data_' . md5($this->tenant_id . $sheet_id . $sheet_range);
        delete_transient($cache_key);
        return $this->fetch_data(false);
    }

    /**
     * Get single row by ID
     */
    public function get_row($row_id)
    {
        $data = $this->fetch_data();
        foreach ($data as $row) {
            if ($row['row_id'] == $row_id) {
                return $row;
            }
        }
        return null;
    }
}