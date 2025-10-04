<?php

/**
 * Dashboard page
 */

if (!defined('ABSPATH')) {
    exit;
}

$tenant_manager = new WPGSIP_Tenant_Manager();
$logger = new WPGSIP_Logger();
$settings = new WPGSIP_Settings();

$current_tenant_id = $tenant_manager->get_current_tenant_id();
$stats = $logger->get_statistics($current_tenant_id, 30);
$recent_logs = $logger->get_recent_logs(10, $current_tenant_id);

// Get imported posts count
global $wpdb;
$imported_posts_count = $wpdb->get_var(
    "SELECT COUNT(DISTINCT pm.post_id) 
    FROM {$wpdb->postmeta} pm 
    INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
    WHERE pm.meta_key = 'imported_from_gs' 
    AND p.post_status != 'trash'"
);

// Get next scheduled import
$cron = new WPGSIP_Cron();
$next_run = $cron->get_next_run();
?>

<div class="wrap">
    <h1><?php esc_html_e('WP Google Sheets Import Pro - Dashboard', 'wp-gs-import-pro'); ?></h1>

    <div class="wpgsip-dashboard">
        <!-- Statistics Cards -->
        <div class="wpgsip-stats-grid">
            <div class="wpgsip-stat-card">
                <div class="wpgsip-stat-icon">📝</div>
                <div class="wpgsip-stat-content">
                    <h3><?php echo esc_html($imported_posts_count); ?></h3>
                    <p><?php esc_html_e('Imported Posts', 'wp-gs-import-pro'); ?></p>
                </div>
            </div>

            <div class="wpgsip-stat-card">
                <div class="wpgsip-stat-icon">✅</div>
                <div class="wpgsip-stat-content">
                    <h3><?php echo esc_html($stats['success']); ?></h3>
                    <p><?php esc_html_e('Successful Imports (30d)', 'wp-gs-import-pro'); ?></p>
                </div>
            </div>

            <div class="wpgsip-stat-card">
                <div class="wpgsip-stat-icon">❌</div>
                <div class="wpgsip-stat-content">
                    <h3><?php echo esc_html($stats['error']); ?></h3>
                    <p><?php esc_html_e('Errors (30d)', 'wp-gs-import-pro'); ?></p>
                </div>
            </div>

            <div class="wpgsip-stat-card">
                <div class="wpgsip-stat-icon">⏭️</div>
                <div class="wpgsip-stat-content">
                    <h3><?php echo esc_html($stats['skipped']); ?></h3>
                    <p><?php esc_html_e('Skipped (30d)', 'wp-gs-import-pro'); ?></p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="wpgsip-section">
            <h2><?php esc_html_e('Quick Actions', 'wp-gs-import-pro'); ?></h2>
            <div class="wpgsip-quick-actions">
                <a href="<?php echo admin_url('admin.php?page=wpgsip-import'); ?>" class="button button-primary button-large">
                    <?php esc_html_e('Start Import', 'wp-gs-import-pro'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=wpgsip-settings'); ?>" class="button button-secondary button-large">
                    <?php esc_html_e('Settings', 'wp-gs-import-pro'); ?>
                </a>
                <a href="<?php echo admin_url('admin.php?page=wpgsip-posts'); ?>" class="button button-secondary button-large">
                    <?php esc_html_e('View Imported Posts', 'wp-gs-import-pro'); ?>
                </a>
            </div>
        </div>

        <!-- Scheduled Import Info -->
        <?php if ($settings->get('auto_import_enabled')): ?>
            <div class="wpgsip-section">
                <h2><?php esc_html_e('Scheduled Import', 'wp-gs-import-pro'); ?></h2>
                <p>
                    <?php if ($next_run): ?>
                        <?php printf(
                            esc_html__('Next scheduled import: %s', 'wp-gs-import-pro'),
                            '<strong>' . esc_html($next_run) . '</strong>'
                        ); ?>
                    <?php else: ?>
                        <?php esc_html_e('No scheduled import configured.', 'wp-gs-import-pro'); ?>
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>

        <!-- Recent Activity -->
        <div class="wpgsip-section">
            <h2><?php esc_html_e('Recent Activity', 'wp-gs-import-pro'); ?></h2>
            <?php if (!empty($recent_logs)): ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php esc_html_e('Date', 'wp-gs-import-pro'); ?></th>
                            <th><?php esc_html_e('Action', 'wp-gs-import-pro'); ?></th>
                            <th><?php esc_html_e('Status', 'wp-gs-import-pro'); ?></th>
                            <th><?php esc_html_e('Message', 'wp-gs-import-pro'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_logs as $log): ?>
                            <tr>
                                <td><?php echo esc_html($log->created_at); ?></td>
                                <td><?php echo esc_html($log->action); ?></td>
                                <td>
                                    <span class="wpgsip-status-badge wpgsip-status-<?php echo esc_attr($log->status); ?>">
                                        <?php echo esc_html($log->status); ?>
                                    </span>
                                </td>
                                <td><?php echo esc_html($log->message); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p>
                    <a href="<?php echo admin_url('admin.php?page=wpgsip-logs'); ?>">
                        <?php esc_html_e('View All Logs →', 'wp-gs-import-pro'); ?>
                    </a>
                </p>
            <?php else: ?>
                <p><?php esc_html_e('No recent activity.', 'wp-gs-import-pro'); ?></p>
            <?php endif; ?>
        </div>

        <!-- Import Activity Chart -->
        <div class="wpgsip-section">
            <h2><?php esc_html_e('Import Activity (Last 30 Days)', 'wp-gs-import-pro'); ?></h2>
            <canvas id="wpgsip-activity-chart" width="400" height="100"></canvas>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        // Simple activity chart using canvas
        var canvas = document.getElementById('wpgsip-activity-chart');
        if (canvas) {
            var ctx = canvas.getContext('2d');
            var data = <?php echo json_encode(array_reverse($stats['by_date'])); ?>;

            // Simple bar chart
            var maxCount = Math.max(...data.map(d => d.count));
            var barWidth = canvas.width / data.length;

            data.forEach(function(item, index) {
                var barHeight = (item.count / maxCount) * (canvas.height - 20);
                ctx.fillStyle = '#2271b1';
                ctx.fillRect(index * barWidth, canvas.height - barHeight, barWidth - 2, barHeight);

                // Draw count
                ctx.fillStyle = '#000';
                ctx.font = '10px Arial';
                ctx.fillText(item.count, index * barWidth + 5, canvas.height - barHeight - 5);
            });
        }
    });
</script>