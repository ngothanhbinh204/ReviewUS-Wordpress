<?php

/**
 * n8n Webhook integration
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Webhook
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
     * Constructor
     */
    public function __construct($tenant_id = 'default')
    {
        $this->tenant_id = $tenant_id;
        $settings_manager = new WPGSIP_Settings();
        $this->settings = $settings_manager->get_tenant_settings($tenant_id);
    }

    /**
     * Trigger content generation
     */
    public function trigger_content_generation($row_data)
    {
        $webhook_url = $this->settings['n8n_webhook_url'] ?? '';
        $n8n_enabled = $this->settings['n8n_enabled'] ?? false;

        if (!$n8n_enabled || empty($webhook_url)) {
            return array(
                'success' => false,
                'message' => __('n8n webhook is not configured or disabled', 'wp-gs-import-pro')
            );
        }

        // Prepare payload
        $payload = array(
            'tenant_id' => $this->tenant_id,
            'row_id' => $row_data['row_id'],
            'outline' => $row_data['outline'],
            'meta_title' => $row_data['meta_title'],
            'meta_description' => $row_data['meta_description'],
            'keyword' => $row_data['keyword'],
            'sheet_id' => $this->settings['sheet_id'] ?? '',
        );

        // Add custom data hook
        $payload = apply_filters('wpgsip_webhook_payload', $payload, $row_data, $this->tenant_id);

        try {
            $response = wp_remote_post($webhook_url, array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
                'body' => json_encode($payload),
                'timeout' => 30,
                'sslverify' => true,
            ));

            if (is_wp_error($response)) {
                throw new Exception($response->get_error_message());
            }

            $status_code = wp_remote_retrieve_response_code($response);
            $body = wp_remote_retrieve_body($response);

            if ($status_code >= 200 && $status_code < 300) {
                return array(
                    'success' => true,
                    'message' => __('Webhook triggered successfully', 'wp-gs-import-pro'),
                    'response' => json_decode($body, true),
                );
            } else {
                throw new Exception(sprintf(__('Webhook returned status code %d', 'wp-gs-import-pro'), $status_code));
            }
        } catch (Exception $e) {
            return array(
                'success' => false,
                'message' => __('Failed to trigger webhook: ', 'wp-gs-import-pro') . $e->getMessage(),
            );
        }
    }

    /**
     * Wait and refetch content after webhook trigger
     */
    public function wait_and_refetch($row_id)
    {
        $wait_time = intval($this->settings['n8n_wait_time'] ?? 20);

        // Sleep for configured wait time
        sleep($wait_time);

        // Refetch data from Google Sheets
        $google_sheets = new WPGSIP_Google_Sheets($this->tenant_id);
        $row_data = $google_sheets->get_row($row_id);

        return $row_data;
    }

    /**
     * Test webhook connection
     */
    public function test_webhook($webhook_url = null)
    {
        if ($webhook_url === null) {
            $webhook_url = $this->settings['n8n_webhook_url'] ?? '';
        }

        if (empty($webhook_url)) {
            throw new Exception(__('Webhook URL is required', 'wp-gs-import-pro'));
        }

        $payload = array(
            'test' => true,
            'tenant_id' => $this->tenant_id,
            'message' => 'Test connection from WP Google Sheets Import Pro',
        );

        try {
            $response = wp_remote_post($webhook_url, array(
                'headers' => array(
                    'Content-Type' => 'application/json',
                ),
                'body' => json_encode($payload),
                'timeout' => 15,
            ));

            if (is_wp_error($response)) {
                throw new Exception($response->get_error_message());
            }

            $status_code = wp_remote_retrieve_response_code($response);

            if ($status_code >= 200 && $status_code < 300) {
                return array(
                    'success' => true,
                    'message' => __('Webhook connection successful', 'wp-gs-import-pro'),
                );
            } else {
                throw new Exception(sprintf(__('Webhook returned status code %d', 'wp-gs-import-pro'), $status_code));
            }
        } catch (Exception $e) {
            throw new Exception(__('Webhook test failed: ', 'wp-gs-import-pro') . $e->getMessage());
        }
    }
}
