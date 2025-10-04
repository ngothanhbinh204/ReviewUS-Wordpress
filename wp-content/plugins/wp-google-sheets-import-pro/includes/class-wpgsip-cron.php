<?php

/**
 * Cron job management
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Cron
{

    /**
     * Settings
     */
    private $settings;

    /**
     * Logger
     */
    private $logger;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->settings = new WPGSIP_Settings();
        $this->logger = new WPGSIP_Logger();
    }

    /**
     * Run automatic import
     */
    public function run_auto_import()
    {
        // Check if auto import is enabled
        if (!$this->settings->get('auto_import_enabled', false)) {
            return;
        }

        $this->logger->log(array(
            'action' => 'cron',
            'status' => 'info',
            'message' => __('Starting scheduled import', 'wp-gs-import-pro'),
        ));

        try {
            // Get all active tenants
            $tenant_manager = new WPGSIP_Tenant_Manager();
            $tenants = $tenant_manager->get_all_tenants();

            foreach ($tenants as $tenant) {
                $this->import_for_tenant($tenant->tenant_id);
            }

            $this->logger->log(array(
                'action' => 'cron',
                'status' => 'success',
                'message' => sprintf(__('Scheduled import completed for %d tenants', 'wp-gs-import-pro'), count($tenants)),
            ));
        } catch (Exception $e) {
            $this->logger->log(array(
                'action' => 'cron',
                'status' => 'error',
                'message' => __('Scheduled import failed: ', 'wp-gs-import-pro') . $e->getMessage(),
            ));
        }
    }

    /**
     * Import for specific tenant
     */
    private function import_for_tenant($tenant_id)
    {
        try {
            $tenant_manager = new WPGSIP_Tenant_Manager();

            // Switch to tenant context if needed
            $tenant_manager->switch_to_tenant($tenant_id);

            // Run import
            $importer = new WPGSIP_Importer($tenant_id);
            $result = $importer->import_all();

            $this->logger->log(array(
                'tenant_id' => $tenant_id,
                'action' => 'cron_import',
                'status' => 'success',
                'message' => sprintf(
                    __('Imported %d posts (Created: %d, Updated: %d, Skipped: %d, Errors: %d)', 'wp-gs-import-pro'),
                    $result['total'],
                    $result['created'],
                    $result['updated'],
                    $result['skipped'],
                    $result['errors']
                ),
                'data' => json_encode($result),
            ));

            // Restore original blog
            $tenant_manager->restore_current_blog();
        } catch (Exception $e) {
            $this->logger->log(array(
                'tenant_id' => $tenant_id,
                'action' => 'cron_import',
                'status' => 'error',
                'message' => __('Import failed for tenant: ', 'wp-gs-import-pro') . $e->getMessage(),
            ));

            // Restore original blog
            if (isset($tenant_manager)) {
                $tenant_manager->restore_current_blog();
            }
        }
    }

    /**
     * Schedule next run
     */
    public function schedule($frequency = 'daily')
    {
        // Clear existing schedule
        $timestamp = wp_next_scheduled('wpgsip_scheduled_import');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'wpgsip_scheduled_import');
        }

        // Schedule new event
        if ($frequency !== 'disabled') {
            wp_schedule_event(time(), $frequency, 'wpgsip_scheduled_import');
        }
    }

    /**
     * Get next scheduled run time
     */
    public function get_next_run()
    {
        $timestamp = wp_next_scheduled('wpgsip_scheduled_import');
        return $timestamp ? date('Y-m-d H:i:s', $timestamp) : null;
    }
}
