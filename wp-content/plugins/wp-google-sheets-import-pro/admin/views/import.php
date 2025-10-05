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
                        <th><?php esc_html_e('Import For', 'wp-gs-import-pro'); ?></th>
                        <td>
                            <select id="wpgsip-post-type" class="regular-text">
                                <option value="post"><?php esc_html_e('Post', 'wp-gs-import-pro'); ?></option>
                                <option value="thing_to_do"><?php esc_html_e('Thing To Do', 'wp-gs-import-pro'); ?></option>
                            </select>
                            <p class="description">
                                <?php esc_html_e('Select the post type for import', 'wp-gs-import-pro'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Google Sheet ID', 'wp-gs-import-pro'); ?></th>
                        <td><code><?php echo esc_html($tenant_settings['sheet_id']); ?></code></td>
                    </tr>
                    <tr>
                        <th><?php esc_html_e('Sheet Range', 'wp-gs-import-pro'); ?></th>
                        <td>
                            <code id="wpgsip-current-sheet-range"><?php echo esc_html($tenant_settings['sheet_range']); ?></code>
                            <input type="hidden" id="wpgsip-post-sheet-range" value="<?php echo esc_attr($tenant_settings['sheet_range'] ?? 'Post1!A2:I'); ?>">
                            <input type="hidden" id="wpgsip-thing-sheet-range" value="<?php echo esc_attr($tenant_settings['thing_to_do_sheet_range'] ?? 'ThingToDo1!A2:I'); ?>">
                            <p class="description" id="wpgsip-sheet-columns-desc">
                                <?php esc_html_e('Columns: A=outline, B=meta_title, C=meta_description, D=keyword, E=status, F=content, G=CPT, H=category, I=tags', 'wp-gs-import-pro'); ?>
                            </p>
                        </td>
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
            
            <!-- Taxonomy Selection (shown after preview) -->
            <div class="wpgsip-section" id="wpgsip-taxonomy-section" style="display: none;">
                <h2><?php esc_html_e('Taxonomy Settings', 'wp-gs-import-pro'); ?></h2>
                <div id="wpgsip-taxonomy-options"></div>
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
                <p><?php esc_html_e('Select specific items to import or update. Existing posts will be updated based on title match.', 'wp-gs-import-pro'); ?></p>

                <p>
                    <label>
                        <input type="checkbox" id="wpgsip-select-all">
                        <?php esc_html_e('Select All', 'wp-gs-import-pro'); ?>
                    </label>
                    &nbsp;&nbsp;
                    <span id="wpgsip-selected-count" style="font-weight: bold;">0</span> 
                    <?php esc_html_e('items selected', 'wp-gs-import-pro'); ?>
                </p>

                <p>
                    <button type="button" id="wpgsip-import-btn" class="button button-primary button-large" disabled>
                        <?php esc_html_e('Import Selected Items', 'wp-gs-import-pro'); ?>
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
    // Initialize import variables for external script
    var wpgsipImport = wpgsipImport || {};
    wpgsipImport.tenantId = '<?php echo esc_js($current_tenant_id); ?>';
    wpgsipImport.i18n = {
        noSelection: '<?php esc_html_e('Please select at least one item to import', 'wp-gs-import-pro'); ?>',
        confirmImport: '<?php esc_html_e('Import %d selected items?', 'wp-gs-import-pro'); ?>',
        taxonomyHelp: '<?php esc_html_e('Select default taxonomies for items that don\'t have them in the sheet:', 'wp-gs-import-pro'); ?>'
    };
</script>