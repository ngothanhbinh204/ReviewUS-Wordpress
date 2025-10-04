<?php

/**
 * Uninstall script
 * Fired when the plugin is uninstalled.
 *
 * @package WP_Google_Sheets_Import_Pro
 */

// If uninstall not called from WordPress, exit
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

global $wpdb;

// Delete plugin options
delete_option('wpgsip_settings');
delete_option('wpgsip_version');

// Delete transients
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_wpgsip_%'");
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_timeout_wpgsip_%'");

// Optionally delete import logs table (uncomment if you want to remove all data)
// $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpgsip_import_logs");

// Optionally delete tenants table (uncomment if you want to remove all data)
// $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wpgsip_tenants");

// Clear scheduled cron
$timestamp = wp_next_scheduled('wpgsip_scheduled_import');
if ($timestamp) {
    wp_unschedule_event($timestamp, 'wpgsip_scheduled_import');
}

// Optionally delete all imported post meta (uncomment if you want to remove all data)
// $wpdb->query("DELETE FROM {$wpdb->postmeta} WHERE meta_key IN ('imported_from_gs', 'gs_sheet_row_id', 'gs_tenant_id', 'gs_last_sync', 'gs_original_data')");

// Clear any cached data
wp_cache_flush();
