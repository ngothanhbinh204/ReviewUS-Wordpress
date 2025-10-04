<?php

/**
 * Import page
 */

if (!defined('ABSPATH')) {
    exit;
}

$tenant_manager = new WPGSIP_Tenant_Manager();
$current_tenant_id = $tenant_manager->get_current_tenant_id();
$settings = new WPGSIP_Settings();
$tenant_settings = $settings->get_tenant_settings($current_tenant_id);
?>

<div class="wrap">
    <h1><?php esc_html_e('Import from Google Sheets', 'wp-gs-import-pro'); ?></h1>

    <?php if (empty($tenant_settings['sheet_id'])): ?>
        <div class="notice notice-error">
            <p>
                <?php esc_html_e('Google Sheet ID is not configured. Please configure it in settings first.', 'wp-gs-import-pro'); ?>
                <a href="<?php echo admin_url('admin.php?page=wpgsip-settings'); ?>">
                    <?php esc_html_e('Go to Settings', 'wp-gs-import-pro'); ?>
                </a>
            </p>
        </div>
    <?php else: ?>

        <div class="wpgsip-import-page">
            <!-- Import Settings -->
            <div class="wpgsip-section">
                <h2><?php esc_html_e('Import Configuration', 'wp-gs-import-pro'); ?></h2>
                <table class="form-table">
                    <tr>
                        <th><?php esc_html_e('Google Sheet ID', 'wp-gs-import-pro'); ?></th>
                        <td><code><?php echo esc_html($tenant_settings['sheet_id']); ?></code></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Sheet Range', 'wp-gs-import-pro'); ?></th>
                        <td><code><?php echo esc_html($tenant_settings['sheet_range']); ?></code></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('n8n Webhook', 'wp-gs-import-pro'); ?></th>
                        <td>
                            <?php if ($tenant_settings['n8n_enabled']): ?>
                                <span class="wpgsip-status-badge wpgsip-status-success">
                                    <?php esc_html_e('Enabled', 'wp-gs-import-pro'); ?>
                                </span>
                            <?php else: ?>
                                <span class="wpgsip-status-badge wpgsip-status-error">
                                    <?php esc_html_e('Disabled', 'wp-gs-import-pro'); ?>
                                </span>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>

            <!-- Preview Section -->
            <div class="wpgsip-section">
                <h2><?php esc_html_e('Preview Data', 'wp-gs-import-pro'); ?></h2>
                <p>
                    <button type="button" id="wpgsip-preview-btn" class="button button-secondary">
                        <?php esc_html_e('Load Preview', 'wp-gs-import-pro'); ?>
                    </button>
                </p>

                <div id="wpgsip-preview-container" style="display: none;">
                    <div id="wpgsip-preview-loading" class="wpgsip-loading" style="display: none;">
                        <span class="spinner is-active"></span>
                        <?php esc_html_e('Loading data...', 'wp-gs-import-pro'); ?>
                    </div>

                    <div id="wpgsip-preview-data"></div>
                </div>
            </div>

            <!-- Import Section -->
            <div class="wpgsip-section">
                <h2><?php esc_html_e('Start Import', 'wp-gs-import-pro'); ?></h2>
                <p><?php esc_html_e('This will import all rows from the Google Sheet. Existing posts will be updated, new posts will be created.', 'wp-gs-import-pro'); ?></p>

                <p>
                    <button type="button" id="wpgsip-import-btn" class="button button-primary button-large">
                        <?php esc_html_e('Start Import', 'wp-gs-import-pro'); ?>
                    </button>
                </p>

                <div id="wpgsip-import-progress" style="display: none;">
                    <div class="wpgsip-progress-bar">
                        <div class="wpgsip-progress-fill" style="width: 0%"></div>
                    </div>
                    <p id="wpgsip-import-status"></p>
                </div>

                <div id="wpgsip-import-results" style="display: none;">
                    <h3><?php esc_html_e('Import Results', 'wp-gs-import-pro'); ?></h3>
                    <div id="wpgsip-import-summary"></div>
                    <div id="wpgsip-import-messages"></div>
                </div>
            </div>
        </div>

    <?php endif; ?>
</div>

<script>
    jQuery(document).ready(function($) {
        var tenantId = '<?php echo esc_js($current_tenant_id); ?>';

        // Preview button
        $('#wpgsip-preview-btn').on('click', function() {
            $('#wpgsip-preview-container').show();
            $('#wpgsip-preview-loading').show();
            $('#wpgsip-preview-data').html('');

            $.ajax({
                url: wpgsipAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wpgsip_import_preview',
                    nonce: wpgsipAdmin.nonce,
                    tenant_id: tenantId
                },
                success: function(response) {
                    $('#wpgsip-preview-loading').hide();

                    if (response.success) {
                        var html = '<p><strong>' + response.data.count + ' rows found</strong></p>';
                        html += '<table class="wp-list-table widefat fixed striped">';
                        html += '<thead><tr>';
                        html += '<th>Row</th><th>Meta Title</th><th>Keyword</th><th>Status</th><th>Has Content</th>';
                        html += '</tr></thead><tbody>';

                        $.each(response.data.data.slice(0, 10), function(i, row) {
                            html += '<tr>';
                            html += '<td>' + row.row_id + '</td>';
                            html += '<td>' + row.meta_title + '</td>';
                            html += '<td>' + row.keyword + '</td>';
                            html += '<td>' + row.status + '</td>';
                            html += '<td>' + (row.content ? '✅' : '❌') + '</td>';
                            html += '</tr>';
                        });

                        html += '</tbody></table>';

                        if (response.data.count > 10) {
                            html += '<p><em>Showing first 10 rows only...</em></p>';
                        }

                        $('#wpgsip-preview-data').html(html);
                    } else {
                        $('#wpgsip-preview-data').html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
                    }
                },
                error: function() {
                    $('#wpgsip-preview-loading').hide();
                    $('#wpgsip-preview-data').html('<div class="notice notice-error"><p>Failed to load preview</p></div>');
                }
            });
        });

        // Import button
        $('#wpgsip-import-btn').on('click', function() {
            if (!confirm('Are you sure you want to start the import?')) {
                return;
            }

            $(this).prop('disabled', true);
            $('#wpgsip-import-progress').show();
            $('#wpgsip-import-results').hide();

            runBatchImport(0);
        });

        function runBatchImport(batch) {
            $.ajax({
                url: wpgsipAdmin.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'wpgsip_import_execute',
                    nonce: wpgsipAdmin.nonce,
                    tenant_id: tenantId,
                    batch: batch,
                    batch_size: 10
                },
                success: function(response) {
                    if (response.success) {
                        var result = response.data;
                        var progress = ((batch * 10 + result.processed) / result.total) * 100;

                        $('.wpgsip-progress-fill').css('width', progress + '%');
                        $('#wpgsip-import-status').text('Processing batch ' + (batch + 1) + '... (' + result.processed + '/' + result.total + ')');

                        if (result.has_more) {
                            runBatchImport(batch + 1);
                        } else {
                            showResults(result);
                        }
                    } else {
                        $('#wpgsip-import-status').html('<div class="notice notice-error"><p>' + response.data.message + '</p></div>');
                        $('#wpgsip-import-btn').prop('disabled', false);
                    }
                },
                error: function() {
                    $('#wpgsip-import-status').html('<div class="notice notice-error"><p>Import failed</p></div>');
                    $('#wpgsip-import-btn').prop('disabled', false);
                }
            });
        }

        function showResults(result) {
            $('#wpgsip-import-progress').hide();
            $('#wpgsip-import-results').show();
            $('#wpgsip-import-btn').prop('disabled', false);

            var html = '<p><strong>Import completed!</strong></p>';
            html += '<ul>';
            html += '<li>Created: ' + result.created + '</li>';
            html += '<li>Updated: ' + result.updated + '</li>';
            html += '<li>Skipped: ' + result.skipped + '</li>';
            html += '<li>Errors: ' + result.errors + '</li>';
            html += '</ul>';

            $('#wpgsip-import-summary').html(html);

            if (result.messages && result.messages.length > 0) {
                var messagesHtml = '<h4>Messages:</h4><ul>';
                $.each(result.messages, function(i, msg) {
                    messagesHtml += '<li>' + msg + '</li>';
                });
                messagesHtml += '</ul>';
                $('#wpgsip-import-messages').html(messagesHtml);
            }
        }
    });
</script>