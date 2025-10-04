<?php

/**
 * Core plugin class
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Core
{

    /**
     * Plugin settings
     */
    private $settings;

    /**
     * Tenant manager
     */
    private $tenant_manager;

    /**
     * Logger
     */
    private $logger;

    /**
     * Initialize the plugin
     */
    public function __construct()
    {
        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load dependencies
     */
    private function load_dependencies()
    {
        $this->settings = new WPGSIP_Settings();
        $this->tenant_manager = new WPGSIP_Tenant_Manager();
        $this->logger = new WPGSIP_Logger();
    }

    /**
     * Set plugin locale
     */
    private function set_locale()
    {
        add_action('plugins_loaded', array($this, 'load_plugin_textdomain'));
    }

    /**
     * Load plugin text domain
     */
    public function load_plugin_textdomain()
    {
        load_plugin_textdomain(
            'wp-gs-import-pro',
            false,
            dirname(WPGSIP_PLUGIN_BASENAME) . '/languages/'
        );
    }

    /**
     * Define admin hooks
     */
    private function define_admin_hooks()
    {
        if (is_admin()) {
            $admin = new WPGSIP_Admin();
            add_action('admin_menu', array($admin, 'add_admin_menu'));
            add_action('admin_enqueue_scripts', array($admin, 'enqueue_styles'));
            add_action('admin_enqueue_scripts', array($admin, 'enqueue_scripts'));

            // AJAX handlers
            add_action('wp_ajax_wpgsip_import_preview', array($this, 'ajax_import_preview'));
            add_action('wp_ajax_wpgsip_import_execute', array($this, 'ajax_import_execute'));
            add_action('wp_ajax_wpgsip_get_logs', array($this, 'ajax_get_logs'));
            add_action('wp_ajax_wpgsip_test_connection', array($this, 'ajax_test_connection'));
        }
    }

    /**
     * Define public hooks
     */
    private function define_public_hooks()
    {
        // Register cron schedules
        add_filter('cron_schedules', array($this, 'add_cron_schedules'));
        add_action('wpgsip_scheduled_import', array($this, 'run_scheduled_import'));
    }

    /**
     * Add custom cron schedules
     */
    public function add_cron_schedules($schedules)
    {
        $schedules['weekly'] = array(
            'interval' => 604800,
            'display' => __('Once Weekly', 'wp-gs-import-pro')
        );
        return $schedules;
    }

    /**
     * Run scheduled import
     */
    public function run_scheduled_import()
    {
        $cron = new WPGSIP_Cron();
        $cron->run_auto_import();
    }

    /**
     * AJAX: Import preview
     */
    public function ajax_import_preview()
    {
        check_ajax_referer('wpgsip_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'wp-gs-import-pro')));
        }

        try {
            $tenant_id = isset($_POST['tenant_id']) ? sanitize_text_field($_POST['tenant_id']) : 'default';
            $google_sheets = new WPGSIP_Google_Sheets($tenant_id);
            $data = $google_sheets->fetch_data();

            wp_send_json_success(array(
                'data' => $data,
                'count' => count($data),
            ));
        } catch (Exception $e) {
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }

    /**
     * AJAX: Execute import
     */
    public function ajax_import_execute()
    {
        check_ajax_referer('wpgsip_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'wp-gs-import-pro')));
        }

        try {
            $tenant_id = isset($_POST['tenant_id']) ? sanitize_text_field($_POST['tenant_id']) : 'default';
            $batch = isset($_POST['batch']) ? intval($_POST['batch']) : 0;
            $batch_size = isset($_POST['batch_size']) ? intval($_POST['batch_size']) : 10;

            $importer = new WPGSIP_Importer($tenant_id);
            $result = $importer->import_batch($batch, $batch_size);

            wp_send_json_success($result);
        } catch (Exception $e) {
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }

    /**
     * AJAX: Get logs
     */
    public function ajax_get_logs()
    {
        check_ajax_referer('wpgsip_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'wp-gs-import-pro')));
        }

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $per_page = isset($_POST['per_page']) ? intval($_POST['per_page']) : 20;
        $tenant_id = isset($_POST['tenant_id']) ? sanitize_text_field($_POST['tenant_id']) : null;

        $logs = $this->logger->get_logs($page, $per_page, $tenant_id);

        wp_send_json_success($logs);
    }

    /**
     * AJAX: Test connection
     */
    public function ajax_test_connection()
    {
        check_ajax_referer('wpgsip_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error(array('message' => __('Insufficient permissions', 'wp-gs-import-pro')));
        }

        try {
            $sheet_id = isset($_POST['sheet_id']) ? sanitize_text_field($_POST['sheet_id']) : '';
            $sheet_range = isset($_POST['sheet_range']) ? sanitize_text_field($_POST['sheet_range']) : '';

            $google_sheets = new WPGSIP_Google_Sheets('default');
            $result = $google_sheets->test_connection($sheet_id, $sheet_range);

            wp_send_json_success($result);
        } catch (Exception $e) {
            wp_send_json_error(array('message' => $e->getMessage()));
        }
    }

    /**
     * Run the plugin
     */
    public function run()
    {
        // Plugin is initialized
        do_action('wpgsip_loaded');
    }
}
