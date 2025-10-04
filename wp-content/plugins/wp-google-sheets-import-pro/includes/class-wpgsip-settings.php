<?php

/**
 * Settings management
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Settings
{

    /**
     * Option name
     */
    private $option_name = 'wpgsip_settings';

    /**
     * Settings cache
     */
    private $settings = null;

    /**
     * Get all settings
     */
    public function get_all()
    {
        if ($this->settings === null) {
            $this->settings = get_option($this->option_name, array());
        }
        return $this->settings;
    }

    /**
     * Get single setting
     */
    public function get($key, $default = null)
    {
        $settings = $this->get_all();
        return isset($settings[$key]) ? $settings[$key] : $default;
    }

    /**
     * Set single setting
     */
    public function set($key, $value)
    {
        $settings = $this->get_all();
        $settings[$key] = $value;
        return update_option($this->option_name, $settings);
    }

    /**
     * Update multiple settings
     */
    public function update($new_settings)
    {
        $settings = $this->get_all();
        $settings = array_merge($settings, $new_settings);
        $this->settings = $settings;
        return update_option($this->option_name, $settings);
    }

    /**
     * Delete setting
     */
    public function delete($key)
    {
        $settings = $this->get_all();
        if (isset($settings[$key])) {
            unset($settings[$key]);
            $this->settings = $settings;
            return update_option($this->option_name, $settings);
        }
        return false;
    }

    /**
     * Get tenant-specific settings
     */
    public function get_tenant_settings($tenant_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_tenants';

        $tenant = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE tenant_id = %s",
            $tenant_id
        ));

        // Get global settings first
        $global_settings = $this->get_all();

        if ($tenant) {
            $tenant_settings = !empty($tenant->settings) ? json_decode($tenant->settings, true) : array();

            // Merge: Start with global, override with tenant-specific if set
            $merged_settings = array_merge($global_settings, $tenant_settings);

            // Add tenant-specific fields if they exist (not null)
            if (!empty($tenant->sheet_id)) {
                $merged_settings['sheet_id'] = $tenant->sheet_id;
            }
            if (!empty($tenant->sheet_range)) {
                $merged_settings['sheet_range'] = $tenant->sheet_range;
            }
            if (!empty($tenant->webhook_url)) {
                $merged_settings['n8n_webhook_url'] = $tenant->webhook_url;
            }

            return $merged_settings;
        }

        // If no tenant record, return global settings
        return $global_settings;
    }

    /**
     * Save tenant settings
     */
    public function save_tenant_settings($tenant_id, $settings)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_tenants';

        $data = array();
        if (isset($settings['sheet_id'])) {
            $data['sheet_id'] = $settings['sheet_id'];
        }
        if (isset($settings['sheet_range'])) {
            $data['sheet_range'] = $settings['sheet_range'];
        }
        if (isset($settings['n8n_webhook_url'])) {
            $data['webhook_url'] = $settings['n8n_webhook_url'];
        }

        // Store other settings as JSON
        $custom_settings = array_diff_key($settings, array(
            'sheet_id' => '',
            'sheet_range' => '',
            'n8n_webhook_url' => '',
        ));

        if (!empty($custom_settings)) {
            $data['settings'] = json_encode($custom_settings);
        }

        $data['updated_at'] = current_time('mysql');

        return $wpdb->update(
            $table,
            $data,
            array('tenant_id' => $tenant_id),
            array('%s', '%s', '%s', '%s', '%s'),
            array('%s')
        );
    }
}
