<?php

/**
 * Logs page
 */

if (!defined('ABSPATH')) {
    exit;
}

$logger = new WPGSIP_Logger();
$tenant_manager = new WPGSIP_Tenant_Manager();
$current_tenant_id = $tenant_manager->get_current_tenant_id();

$page = isset($_GET['log_page']) ? absint($_GET['log_page']) : 1;
$per_page = 50;

$logs_data = $logger->get_logs($page, $per_page, $current_tenant_id);
$logs = $logs_data['logs'];
$total = $logs_data['total'];
$total_pages = $logs_data['total_pages'];
?>

<div class="wrap">
    <h1><?php esc_html_e('Import Logs', 'wp-gs-import-pro'); ?></h1>

    <p class="description">
        <?php printf(esc_html__('Total logs: %d', 'wp-gs-import-pro'), $total); ?>
    </p>

    <?php if (!empty($logs)): ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th style="width: 140px;"><?php esc_html_e('Date', 'wp-gs-import-pro'); ?></th>
                    <th style="width: 100px;"><?php esc_html_e('Action', 'wp-gs-import-pro'); ?></th>
                    <th style="width: 80px;"><?php esc_html_e('Status', 'wp-gs-import-pro'); ?></th>
                    <th style="width: 80px;"><?php esc_html_e('Post ID', 'wp-gs-import-pro'); ?></th>
                    <th style="width: 80px;"><?php esc_html_e('Row ID', 'wp-gs-import-pro'); ?></th>
                    <th><?php esc_html_e('Message', 'wp-gs-import-pro'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?php echo esc_html(date('Y-m-d H:i:s', strtotime($log->created_at))); ?></td>
                        <td><?php echo esc_html($log->action); ?></td>
                        <td>
                            <span class="wpgsip-status-badge wpgsip-status-<?php echo esc_attr($log->status); ?>">
                                <?php echo esc_html($log->status); ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($log->post_id): ?>
                                <a href="<?php echo get_edit_post_link($log->post_id); ?>" target="_blank">
                                    <?php echo esc_html($log->post_id); ?>
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td><?php echo esc_html($log->sheet_row_id ?: '-'); ?></td>
                        <td><?php echo esc_html($log->message); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php
        // Pagination
        if ($total_pages > 1) {
            $page_links = paginate_links(array(
                'base' => add_query_arg('log_page', '%#%'),
                'format' => '',
                'prev_text' => __('&laquo;', 'wp-gs-import-pro'),
                'next_text' => __('&raquo;', 'wp-gs-import-pro'),
                'total' => $total_pages,
                'current' => $page
            ));

            if ($page_links) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . $page_links . '</div></div>';
            }
        }
        ?>

    <?php else: ?>
        <p><?php esc_html_e('No logs found.', 'wp-gs-import-pro'); ?></p>
    <?php endif; ?>

    <hr>

    <h2><?php esc_html_e('Maintenance', 'wp-gs-import-pro'); ?></h2>
    <p>
        <button type="button" id="wpgsip-clear-logs" class="button button-secondary">
            <?php esc_html_e('Clear Old Logs (90+ days)', 'wp-gs-import-pro'); ?>
        </button>
    </p>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#wpgsip-clear-logs').on('click', function() {
            if (!confirm('<?php esc_html_e('Are you sure you want to delete logs older than 90 days?', 'wp-gs-import-pro'); ?>')) {
                return;
            }

            var btn = $(this);
            btn.prop('disabled', true).text('<?php esc_html_e('Clearing...', 'wp-gs-import-pro'); ?>');

            // This would need an AJAX handler to be implemented
            alert('<?php esc_html_e('This feature will be available in a future update.', 'wp-gs-import-pro'); ?>');
            btn.prop('disabled', false).text('<?php esc_html_e('Clear Old Logs (90+ days)', 'wp-gs-import-pro'); ?>');
        });
    });
</script>