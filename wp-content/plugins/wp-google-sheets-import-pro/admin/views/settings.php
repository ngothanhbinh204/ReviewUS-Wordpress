<?php

/**
 * Settings page
 */

if (!defined('ABSPATH')) {
    exit;
}

$settings = new WPGSIP_Settings();
$all_settings = $settings->get_all();
$cron = new WPGSIP_Cron();
$next_run = $cron->get_next_run();
?>

<div class="wrap">
    <h1><?php esc_html_e('Settings', 'wp-gs-import-pro'); ?></h1>

    <form method="post" action="">
        <?php wp_nonce_field('wpgsip_settings_nonce'); ?>

        <h2 class="title"><?php esc_html_e('Google Sheets Configuration', 'wp-gs-import-pro'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="sheet_id"><?php esc_html_e('Google Sheet ID', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="sheet_id" id="sheet_id"
                        value="<?php echo esc_attr($all_settings['sheet_id'] ?? ''); ?>" class="regular-text">
                    <p class="description">
                        <?php esc_html_e('The ID from your Google Sheet URL. Example: 1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="sheet_range"><?php esc_html_e('Blog Posts Sheet Range', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="sheet_range" id="sheet_range"
                        value="<?php echo esc_attr($all_settings['sheet_range'] ?? 'Post1!A2:I'); ?>"
                        class="regular-text">
                    <p class="description">
                        <?php esc_html_e('Range for Blog Posts. Columns: A=outline, B=meta_title, C=meta_description, D=keyword, E=status, F=content, G=CPT, H=category, I=tags', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label
                        for="thing_to_do_sheet_range"><?php esc_html_e('Thing To Do Sheet Range', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="thing_to_do_sheet_range" id="thing_to_do_sheet_range"
                        value="<?php echo esc_attr($all_settings['thing_to_do_sheet_range'] ?? 'ThingToDo1!A2:I'); ?>"
                        class="regular-text">
                    <p class="description">
                        <?php esc_html_e('Range for Things To Do. Columns: A=outline, B=meta_title, C=meta_description, D=keyword, E=status, F=content, G=province, H=themes, I=seasons', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label
                        for="service_account_json"><?php esc_html_e('Service Account JSON', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <textarea name="service_account_json" id="service_account_json" rows="10"
                        class="large-text code"><?php echo esc_textarea($all_settings['service_account_json'] ?? ''); ?></textarea>
                    <p class="description">
                        <?php esc_html_e('Paste your Google Service Account JSON credentials here. Leave empty if using API key or public sheet.', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label
                        for="google_api_key"><?php esc_html_e('Google API Key (Optional)', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="google_api_key" id="google_api_key"
                        value="<?php echo esc_attr($all_settings['google_api_key'] ?? ''); ?>" class="regular-text">
                    <p class="description">
                        <?php esc_html_e('Alternative to service account for public sheets.', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>
        </table>

        <h2 class="title"><?php esc_html_e('n8n Webhook Configuration', 'wp-gs-import-pro'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="n8n_enabled"><?php esc_html_e('Enable n8n Webhook', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="n8n_enabled" id="n8n_enabled" value="1"
                        <?php checked(!empty($all_settings['n8n_enabled'])); ?>>
                    <label
                        for="n8n_enabled"><?php esc_html_e('Trigger content generation when content is empty', 'wp-gs-import-pro'); ?></label>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="n8n_webhook_url"><?php esc_html_e('n8n Webhook URL', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="url" name="n8n_webhook_url" id="n8n_webhook_url"
                        value="<?php echo esc_attr($all_settings['n8n_webhook_url'] ?? ''); ?>" class="regular-text">
                    <p class="description">
                        <?php esc_html_e('Full URL to your n8n webhook endpoint.', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="n8n_wait_time"><?php esc_html_e('Wait Time (seconds)', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="number" name="n8n_wait_time" id="n8n_wait_time"
                        value="<?php echo esc_attr($all_settings['n8n_wait_time'] ?? 20); ?>" min="5" max="120"
                        class="small-text">
                    <p class="description">
                        <?php esc_html_e('How long to wait after triggering webhook before refetching content (5-120 seconds).', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>
        </table>

        <h2 class="title"><?php esc_html_e('Import Options', 'wp-gs-import-pro'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="post_status"><?php esc_html_e('Default Post Status', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <select name="post_status" id="post_status">
                        <option value="publish"
                            <?php selected($all_settings['post_status'] ?? 'publish', 'publish'); ?>>
                            <?php esc_html_e('Published', 'wp-gs-import-pro'); ?></option>
                        <option value="draft" <?php selected($all_settings['post_status'] ?? 'publish', 'draft'); ?>>
                            <?php esc_html_e('Draft', 'wp-gs-import-pro'); ?></option>
                        <option value="pending"
                            <?php selected($all_settings['post_status'] ?? 'publish', 'pending'); ?>>
                            <?php esc_html_e('Pending Review', 'wp-gs-import-pro'); ?></option>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label
                        for="skip_status_filter"><?php esc_html_e('Skip Status Filter', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="skip_status_filter" id="skip_status_filter"
                        value="<?php echo esc_attr($all_settings['skip_status_filter'] ?? ''); ?>" class="regular-text">
                    <p class="description">
                        <?php esc_html_e('Skip rows where STATUS column matches this value. Leave empty to import all rows.', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="batch_size"><?php esc_html_e('Batch Size', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="number" name="batch_size" id="batch_size"
                        value="<?php echo esc_attr($all_settings['batch_size'] ?? 10); ?>" min="1" max="100"
                        class="small-text">
                    <p class="description">
                        <?php esc_html_e('Number of rows to process in each batch (1-100).', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label
                        for="cache_duration"><?php esc_html_e('Cache Duration (seconds)', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="number" name="cache_duration" id="cache_duration"
                        value="<?php echo esc_attr($all_settings['cache_duration'] ?? 300); ?>" min="0" max="3600"
                        class="small-text">
                    <p class="description">
                        <?php esc_html_e('How long to cache Google Sheets data (0-3600 seconds).', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>
        </table>

        <h2 class="title"><?php esc_html_e('Content Processing Options', 'wp-gs-import-pro'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label
                        for="enable_content_processing"><?php esc_html_e('Enable Content Processing', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="enable_content_processing" id="enable_content_processing" value="1"
                        <?php checked(!empty($all_settings['enable_content_processing']) ? $all_settings['enable_content_processing'] : true); ?>>
                    <label
                        for="enable_content_processing"><?php esc_html_e('Automatically format content with headings, lists, and SEO optimization', 'wp-gs-import-pro'); ?></label>
                    <p class="description">
                        <?php esc_html_e('Converts H1:, H2:, **, -, etc. into proper HTML tags', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="enable_toc"><?php esc_html_e('Enable Table of Contents', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="enable_toc" id="enable_toc" value="1"
                        <?php checked(!empty($all_settings['enable_toc'])); ?>>
                    <label
                        for="enable_toc"><?php esc_html_e('Automatically add Table of Contents to posts', 'wp-gs-import-pro'); ?></label>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label
                        for="toc_min_headings"><?php esc_html_e('TOC Minimum Headings', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="number" name="toc_min_headings" id="toc_min_headings"
                        value="<?php echo esc_attr($all_settings['toc_min_headings'] ?? 3); ?>" min="1" max="10"
                        class="small-text">
                    <p class="description">
                        <?php esc_html_e('Minimum number of headings required to show TOC (1-10)', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="toc_title"><?php esc_html_e('TOC Title', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="text" name="toc_title" id="toc_title"
                        value="<?php echo esc_attr($all_settings['toc_title'] ?? 'Nội dung bài viết'); ?>"
                        class="regular-text">
                    <p class="description">
                        <?php esc_html_e('Title for the Table of Contents', 'wp-gs-import-pro'); ?>
                    </p>
                </td>
            </tr>
        </table>

        <h2 class="title"><?php esc_html_e('Scheduled Import', 'wp-gs-import-pro'); ?></h2>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label
                        for="auto_import_enabled"><?php esc_html_e('Enable Automatic Import', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="auto_import_enabled" id="auto_import_enabled" value="1"
                        <?php checked(!empty($all_settings['auto_import_enabled'])); ?>>
                    <label
                        for="auto_import_enabled"><?php esc_html_e('Run import automatically on schedule', 'wp-gs-import-pro'); ?></label>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label
                        for="auto_import_frequency"><?php esc_html_e('Import Frequency', 'wp-gs-import-pro'); ?></label>
                </th>
                <td>
                    <select name="auto_import_frequency" id="auto_import_frequency">
                        <option value="hourly"
                            <?php selected($all_settings['auto_import_frequency'] ?? 'daily', 'hourly'); ?>>
                            <?php esc_html_e('Hourly', 'wp-gs-import-pro'); ?></option>
                        <option value="twicedaily"
                            <?php selected($all_settings['auto_import_frequency'] ?? 'daily', 'twicedaily'); ?>>
                            <?php esc_html_e('Twice Daily', 'wp-gs-import-pro'); ?></option>
                        <option value="daily"
                            <?php selected($all_settings['auto_import_frequency'] ?? 'daily', 'daily'); ?>>
                            <?php esc_html_e('Daily', 'wp-gs-import-pro'); ?></option>
                        <option value="weekly"
                            <?php selected($all_settings['auto_import_frequency'] ?? 'daily', 'weekly'); ?>>
                            <?php esc_html_e('Weekly', 'wp-gs-import-pro'); ?></option>
                    </select>
                    <?php if ($next_run): ?>
                    <p class="description">
                        <?php printf(esc_html__('Next scheduled import: %s', 'wp-gs-import-pro'), '<strong>' . esc_html($next_run) . '</strong>'); ?>
                    </p>
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Save Settings', 'wp-gs-import-pro'), 'primary', 'wpgsip_save_settings'); ?>
    </form>

    <hr>

    <h2><?php esc_html_e('Test Connection', 'wp-gs-import-pro'); ?></h2>
    <p>
        <button type="button" id="wpgsip-test-connection" class="button button-secondary">
            <?php esc_html_e('Test Google Sheets Connection', 'wp-gs-import-pro'); ?>
        </button>
    </p>
    <div id="wpgsip-test-result"></div>
</div>

<script>
jQuery(document).ready(function($) {
    $('#wpgsip-test-connection').on('click', function() {
        var btn = $(this);
        btn.prop('disabled', true).text('Testing...');
        $('#wpgsip-test-result').html('<span class="spinner is-active"></span>');

        $.ajax({
            url: wpgsipAdmin.ajaxUrl,
            type: 'POST',
            data: {
                action: 'wpgsip_test_connection',
                nonce: wpgsipAdmin.nonce,
                sheet_id: $('#sheet_id').val(),
                sheet_range: $('#sheet_range').val()
            },
            success: function(response) {
                btn.prop('disabled', false).text('Test Google Sheets Connection');

                if (response.success) {
                    $('#wpgsip-test-result').html('<div class="notice notice-success"><p>' +
                        response.data.message + '</p></div>');
                } else {
                    $('#wpgsip-test-result').html('<div class="notice notice-error"><p>' +
                        response.data.message + '</p></div>');
                }
            },
            error: function() {
                btn.prop('disabled', false).text('Test Google Sheets Connection');
                $('#wpgsip-test-result').html(
                    '<div class="notice notice-error"><p>Connection test failed</p></div>'
                );
            }
        });
    });
});
</script>