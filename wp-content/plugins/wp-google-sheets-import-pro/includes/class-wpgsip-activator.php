<?php

/**
 * Fired during plugin activation
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Activator
{

    /**
     * Plugin activation
     */
    public static function activate()
    {
        global $wpdb;

        // Create import logs table
        $logs_table = $wpdb->prefix . 'wpgsip_import_logs';
        $charset_collate = $wpdb->get_charset_collate();

        $sql_logs = "CREATE TABLE IF NOT EXISTS $logs_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            tenant_id varchar(255) DEFAULT NULL,
            blog_id bigint(20) DEFAULT NULL,
            action varchar(50) NOT NULL,
            post_id bigint(20) DEFAULT NULL,
            sheet_row_id varchar(100) DEFAULT NULL,
            status varchar(20) NOT NULL,
            message text,
            data longtext,
            created_at datetime NOT NULL,
            PRIMARY KEY  (id),
            KEY tenant_id (tenant_id),
            KEY blog_id (blog_id),
            KEY post_id (post_id),
            KEY status (status),
            KEY created_at (created_at)
        ) $charset_collate;";

        // Create tenant mapping table
        $tenant_table = $wpdb->prefix . 'wpgsip_tenants';

        $sql_tenant = "CREATE TABLE IF NOT EXISTS $tenant_table (
            id bigint(20) NOT NULL AUTO_INCREMENT,
            tenant_id varchar(255) NOT NULL,
            tenant_name varchar(255) NOT NULL,
            domain varchar(255) DEFAULT NULL,
            blog_id bigint(20) DEFAULT NULL,
            sheet_id varchar(255) DEFAULT NULL,
            sheet_range varchar(100) DEFAULT NULL,
            webhook_url text,
            settings longtext,
            status varchar(20) DEFAULT 'active',
            created_at datetime NOT NULL,
            updated_at datetime NOT NULL,
            PRIMARY KEY  (id),
            UNIQUE KEY tenant_id (tenant_id),
            KEY domain (domain),
            KEY blog_id (blog_id),
            KEY status (status)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_logs);
        dbDelta($sql_tenant);

        // Set default options
        $default_options = array(
            'sheet_id' => '',
            'sheet_range' => 'Sheet1!A2:F',
            'n8n_webhook_url' => '',
            'n8n_enabled' => false,
            'n8n_wait_time' => 20,
            'post_status' => 'publish',
            'category_mapping' => array(),
            'skip_status_filter' => '',
            'enable_multisite' => is_multisite(),
            'enable_custom_tenant' => false,
            'service_account_json' => '',
            'cache_duration' => 300,
            'batch_size' => 10,
        );

        if (!get_option('wpgsip_settings')) {
            add_option('wpgsip_settings', $default_options);
        }

        // Create default tenant for single site
        if (!is_multisite()) {
            self::create_default_tenant();
        }

        // Schedule cron if not exists
        if (!wp_next_scheduled('wpgsip_scheduled_import')) {
            wp_schedule_event(time(), 'daily', 'wpgsip_scheduled_import');
        }

        // Set plugin version
        update_option('wpgsip_version', WPGSIP_VERSION);

        // Flush rewrite rules
        flush_rewrite_rules();
    }

    /**
     * Create default tenant for single site
     */
    private static function create_default_tenant()
    {
        global $wpdb;
        $tenant_table = $wpdb->prefix . 'wpgsip_tenants';

        $existing = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $tenant_table WHERE tenant_id = %s",
            'default'
        ));

        if (!$existing) {
            $wpdb->insert(
                $tenant_table,
                array(
                    'tenant_id' => 'default',
                    'tenant_name' => get_bloginfo('name'),
                    'domain' => get_site_url(),
                    'blog_id' => get_current_blog_id(),
                    'status' => 'active',
                    'created_at' => current_time('mysql'),
                    'updated_at' => current_time('mysql'),
                ),
                array('%s', '%s', '%s', '%d', '%s', '%s', '%s')
            );
        }
    }
}
