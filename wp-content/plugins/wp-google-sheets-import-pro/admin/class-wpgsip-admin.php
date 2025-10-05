<?php

/**
 * Admin area functionality
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Admin
{

    /**
     * Settings
     */
    private $settings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->settings = new WPGSIP_Settings();
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu()
    {
        add_menu_page(
            __('GS Import Pro', 'wp-gs-import-pro'),
            __('GS Import Pro', 'wp-gs-import-pro'),
            'manage_options',
            'wpgsip-dashboard',
            array($this, 'render_dashboard_page'),
            'dashicons-cloud-upload',
            30
        );

        add_submenu_page(
            'wpgsip-dashboard',
            __('Dashboard', 'wp-gs-import-pro'),
            __('Dashboard', 'wp-gs-import-pro'),
            'manage_options',
            'wpgsip-dashboard',
            array($this, 'render_dashboard_page')
        );

        add_submenu_page(
            'wpgsip-dashboard',
            __('Import', 'wp-gs-import-pro'),
            __('Import', 'wp-gs-import-pro'),
            'manage_options',
            'wpgsip-import',
            array($this, 'render_import_page')
        );

        add_submenu_page(
            'wpgsip-dashboard',
            __('Imported Posts', 'wp-gs-import-pro'),
            __('Imported Posts', 'wp-gs-import-pro'),
            'manage_options',
            'wpgsip-posts',
            array($this, 'render_posts_page')
        );

        add_submenu_page(
            'wpgsip-dashboard',
            __('Settings', 'wp-gs-import-pro'),
            __('Settings', 'wp-gs-import-pro'),
            'manage_options',
            'wpgsip-settings',
            array($this, 'render_settings_page')
        );

        add_submenu_page(
            'wpgsip-dashboard',
            __('Logs', 'wp-gs-import-pro'),
            __('Logs', 'wp-gs-import-pro'),
            'manage_options',
            'wpgsip-logs',
            array($this, 'render_logs_page')
        );
    }

    /**
     * Enqueue admin styles
     */
    public function enqueue_styles($hook)
    {
        if (strpos($hook, 'wpgsip') === false) {
            return;
        }

        wp_enqueue_style(
            'wpgsip-admin',
            WPGSIP_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            WPGSIP_VERSION
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_scripts($hook)
    {
        if (strpos($hook, 'wpgsip') === false) {
            return;
        }

        wp_enqueue_script(
            'wpgsip-admin',
            WPGSIP_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            WPGSIP_VERSION,
            true
        );

        wp_localize_script('wpgsip-admin', 'wpgsipAdmin', array(
            'ajaxUrl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('wpgsip_nonce'),
            'strings' => array(
                'importing' => __('Importing...', 'wp-gs-import-pro'),
                'importComplete' => __('Import complete!', 'wp-gs-import-pro'),
                'importFailed' => __('Import failed. Please check the logs.', 'wp-gs-import-pro'),
                'confirmDelete' => __('Are you sure you want to delete this?', 'wp-gs-import-pro'),
            ),
        ));
        
        // Enqueue enhanced import script on import page
        if (isset($_GET['page']) && $_GET['page'] === 'wpgsip-import') {
            wp_enqueue_script(
                'wpgsip-import',
                WPGSIP_PLUGIN_URL . 'assets/js/import.js',
                array('jquery', 'wpgsip-admin'),
                WPGSIP_VERSION,
                true
            );
        }
    }

    /**
     * Render dashboard page
     */
    public function render_dashboard_page()
    {
        require_once WPGSIP_PLUGIN_DIR . 'admin/views/dashboard.php';
    }

    /**
     * Render import page
     */
    public function render_import_page()
    {
        require_once WPGSIP_PLUGIN_DIR . 'admin/views/import.php';
    }

    /**
     * Render posts page
     */
    public function render_posts_page()
    {
        require_once WPGSIP_PLUGIN_DIR . 'admin/views/posts.php';
    }

    /**
     * Render settings page
     */
    public function render_settings_page()
    {
        // Handle form submission
        if (isset($_POST['wpgsip_save_settings'])) {
            check_admin_referer('wpgsip_settings_nonce');

            $new_settings = array(
                'sheet_id' => sanitize_text_field($_POST['sheet_id'] ?? ''),
                'sheet_range' => sanitize_text_field($_POST['sheet_range'] ?? ''),
                'n8n_webhook_url' => esc_url_raw($_POST['n8n_webhook_url'] ?? ''),
                'n8n_enabled' => isset($_POST['n8n_enabled']),
                'n8n_wait_time' => intval($_POST['n8n_wait_time'] ?? 20),
                'post_status' => sanitize_text_field($_POST['post_status'] ?? 'publish'),
                'skip_status_filter' => sanitize_text_field($_POST['skip_status_filter'] ?? ''),
                'cache_duration' => intval($_POST['cache_duration'] ?? 300),
                'batch_size' => intval($_POST['batch_size'] ?? 10),
                'auto_import_enabled' => isset($_POST['auto_import_enabled']),
                'auto_import_frequency' => sanitize_text_field($_POST['auto_import_frequency'] ?? 'daily'),
                'service_account_json' => wp_unslash($_POST['service_account_json'] ?? ''),
                'google_api_key' => sanitize_text_field($_POST['google_api_key'] ?? ''),
            );

            // Save to global settings
            $this->settings->update($new_settings);

            // Also save to current tenant (for multi-tenant support)
            $tenant_manager = new WPGSIP_Tenant_Manager();
            $current_tenant_id = $tenant_manager->get_current_tenant_id();
            $this->settings->save_tenant_settings($current_tenant_id, $new_settings);

            // Update cron schedule
            $cron = new WPGSIP_Cron();
            $cron->schedule($new_settings['auto_import_frequency']);

            echo '<div class="notice notice-success"><p>' . __('Settings saved successfully.', 'wp-gs-import-pro') . '</p></div>';
        }

        require_once WPGSIP_PLUGIN_DIR . 'admin/views/settings.php';
    }

    /**
     * Render logs page
     */
    public function render_logs_page()
    {
        require_once WPGSIP_PLUGIN_DIR . 'admin/views/logs.php';
    }
}