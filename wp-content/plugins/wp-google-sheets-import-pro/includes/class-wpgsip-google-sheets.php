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
     */
    public function fetch_data($use_cache = true)
    {
        $sheet_id = $this->settings['sheet_id'] ?? '';
        $sheet_range = $this->settings['sheet_range'] ?? 'Sheet1!A2:F';

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

            // Parse data into structured format
            $data = array();
            foreach ($values as $index => $row) {
                $data[] = array(
                    'row_id' => $index + 2, // +2 because we start from A2
                    'outline' => $row[0] ?? '',
                    'meta_title' => $row[1] ?? '',
                    'meta_description' => $row[2] ?? '',
                    'keyword' => $row[3] ?? '',
                    'status' => $row[4] ?? '',
                    'content' => $row[5] ?? '',
                );
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
            $sheet_range = $this->settings['sheet_range'] ?? 'Sheet1!A2:F';
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
        $sheet_range = $this->settings['sheet_range'] ?? 'Sheet1!A2:F';
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
