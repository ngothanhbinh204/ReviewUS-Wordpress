<?php

/**
 * Fired during plugin deactivation
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Deactivator
{

    /**
     * Plugin deactivation
     */
    public static function deactivate()
    {
        // Clear scheduled cron
        $timestamp = wp_next_scheduled('wpgsip_scheduled_import');
        if ($timestamp) {
            wp_unschedule_event($timestamp, 'wpgsip_scheduled_import');
        }

        // Clear transients
        global $wpdb;
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wpgsip_%'");
        $wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_wpgsip_%'");

        // Flush rewrite rules
        flush_rewrite_rules();
    }
}
