<?php

/**
 * Tenant management for multi-site support
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Tenant_Manager
{

    /**
     * Get current tenant ID
     */
    public function get_current_tenant_id()
    {
        // Check if multisite
        if (is_multisite()) {
            return 'site_' . get_current_blog_id();
        }

        // Check for custom tenant mapping (by domain)
        $custom_tenant = apply_filters('wpgsip_custom_tenant_id', null);
        if ($custom_tenant !== null) {
            return $custom_tenant;
        }

        // Default tenant
        return 'default';
    }

    /**
     * Get all tenants
     */
    public function get_all_tenants()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_tenants';

        $tenants = $wpdb->get_results(
            "SELECT * FROM $table WHERE status = 'active' ORDER BY created_at DESC"
        );

        return $tenants;
    }

    /**
     * Get tenant by ID
     */
    public function get_tenant($tenant_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_tenants';

        $tenant = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE tenant_id = %s",
            $tenant_id
        ));

        return $tenant;
    }

    /**
     * Create or update tenant
     */
    public function save_tenant($data)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_tenants';

        $tenant_id = $data['tenant_id'] ?? '';

        if (empty($tenant_id)) {
            return false;
        }

        $existing = $this->get_tenant($tenant_id);

        $tenant_data = array(
            'tenant_name' => $data['tenant_name'] ?? '',
            'domain' => $data['domain'] ?? '',
            'blog_id' => $data['blog_id'] ?? null,
            'sheet_id' => $data['sheet_id'] ?? '',
            'sheet_range' => $data['sheet_range'] ?? '',
            'webhook_url' => $data['webhook_url'] ?? '',
            'settings' => isset($data['settings']) ? json_encode($data['settings']) : '',
            'status' => $data['status'] ?? 'active',
            'updated_at' => current_time('mysql'),
        );

        if ($existing) {
            // Update
            return $wpdb->update(
                $table,
                $tenant_data,
                array('tenant_id' => $tenant_id),
                array('%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s'),
                array('%s')
            );
        } else {
            // Insert
            $tenant_data['tenant_id'] = $tenant_id;
            $tenant_data['created_at'] = current_time('mysql');

            return $wpdb->insert(
                $table,
                $tenant_data,
                array('%s', '%s', '%s', '%d', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
            );
        }
    }

    /**
     * Delete tenant
     */
    public function delete_tenant($tenant_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_tenants';

        return $wpdb->update(
            $table,
            array('status' => 'deleted', 'updated_at' => current_time('mysql')),
            array('tenant_id' => $tenant_id),
            array('%s', '%s'),
            array('%s')
        );
    }

    /**
     * Get tenant by domain
     */
    public function get_tenant_by_domain($domain)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_tenants';

        $tenant = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE domain = %s AND status = 'active'",
            $domain
        ));

        return $tenant;
    }

    /**
     * Get tenant by blog ID (for multisite)
     */
    public function get_tenant_by_blog_id($blog_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_tenants';

        $tenant = $wpdb->get_row($wpdb->prepare(
            "SELECT * FROM $table WHERE blog_id = %d AND status = 'active'",
            $blog_id
        ));

        return $tenant;
    }

    /**
     * Switch to tenant context (for multisite)
     */
    public function switch_to_tenant($tenant_id)
    {
        $tenant = $this->get_tenant($tenant_id);

        if ($tenant && $tenant->blog_id && is_multisite()) {
            switch_to_blog($tenant->blog_id);
            return true;
        }

        return false;
    }

    /**
     * Restore original blog (for multisite)
     */
    public function restore_current_blog()
    {
        if (is_multisite()) {
            restore_current_blog();
        }
    }
}