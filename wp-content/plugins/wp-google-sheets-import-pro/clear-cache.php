<?php
/**
 * Clear Plugin Cache
 * 
 * Access: http://reviewus.local/wp-content/plugins/wp-google-sheets-import-pro/clear-cache.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Must be admin
if (!current_user_can('manage_options')) {
    die('Access denied. Please login as admin.');
}

echo "<h1>Clear WPGSIP Cache</h1>";

global $wpdb;

// Clear transients
$deleted = $wpdb->query(
    "DELETE FROM {$wpdb->options} 
    WHERE option_name LIKE '_transient_wpgsip_%' 
    OR option_name LIKE '_transient_timeout_wpgsip_%'"
);

echo "<p>✅ Deleted {$deleted} cached items</p>";

// Clear object cache if available
if (function_exists('wp_cache_flush')) {
    wp_cache_flush();
    echo "<p>✅ Flushed object cache</p>";
}

echo "<h2>Done!</h2>";
echo "<p><a href='" . admin_url('admin.php?page=wpgsip-import') . "'>Go to Import Page</a></p>";
