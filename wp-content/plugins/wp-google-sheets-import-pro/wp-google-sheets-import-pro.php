<?php

/**
 * Plugin Name: WP Google Sheets Import Pro
 * Plugin URI: https://example.com/wp-google-sheets-import-pro
 * Description: Import and manage WordPress posts from Google Sheets with n8n webhook integration. Designed for multi-tenant scalability.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://example.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wp-gs-import-pro
 * Domain Path: /languages
 * Requires at least: 6.0
 * Requires PHP: 8.0
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Plugin constants
define('WPGSIP_VERSION', '1.0.0');
define('WPGSIP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('WPGSIP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPGSIP_PLUGIN_BASENAME', plugin_basename(__FILE__));

// Autoloader
require_once WPGSIP_PLUGIN_DIR . 'vendor/autoload.php';

// Core classes
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-activator.php';
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-deactivator.php';
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-core.php';
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-settings.php';
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-google-sheets.php';
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-importer.php';
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-webhook.php';
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-tenant-manager.php';
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-logger.php';
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-cron.php';

// Admin classes
if (is_admin()) {
    require_once WPGSIP_PLUGIN_DIR . 'admin/class-wpgsip-admin.php';
    require_once WPGSIP_PLUGIN_DIR . 'admin/class-wpgsip-dashboard.php';
    require_once WPGSIP_PLUGIN_DIR . 'admin/class-wpgsip-posts-list.php';
}

/**
 * Activation hook
 */
function activate_wpgsip()
{
    WPGSIP_Activator::activate();
}
register_activation_hook(__FILE__, 'activate_wpgsip');

/**
 * Deactivation hook
 */
function deactivate_wpgsip()
{
    WPGSIP_Deactivator::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivate_wpgsip');

/**
 * Initialize the plugin
 */
function run_wpgsip()
{
    $plugin = new WPGSIP_Core();
    $plugin->run();
}

// Check if Composer dependencies are loaded
if (file_exists(WPGSIP_PLUGIN_DIR . 'vendor/autoload.php')) {
    run_wpgsip();
} else {
    add_action('admin_notices', function () {
?>
        <div class="notice notice-error">
            <p><?php esc_html_e('WP Google Sheets Import Pro: Please run "composer install" in the plugin directory.', 'wp-gs-import-pro'); ?></p>
        </div>
<?php
    });
}
