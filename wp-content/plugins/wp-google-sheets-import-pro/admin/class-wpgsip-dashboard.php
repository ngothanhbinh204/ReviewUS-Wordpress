<?php

/**
 * Dashboard helper class
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Dashboard
{

    /**
     * Get dashboard statistics
     */
    public static function get_statistics($tenant_id = null, $days = 30)
    {
        $logger = new WPGSIP_Logger();
        return $logger->get_statistics($tenant_id, $days);
    }

    /**
     * Get imported posts count
     */
    public static function get_imported_posts_count($tenant_id = null)
    {
        global $wpdb;

        $query = "SELECT COUNT(DISTINCT pm.post_id) 
                  FROM {$wpdb->postmeta} pm 
                  INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID 
                  WHERE pm.meta_key = 'imported_from_gs' 
                  AND p.post_status != 'trash'";

        if ($tenant_id !== null) {
            $query .= $wpdb->prepare(" AND EXISTS (
                SELECT 1 FROM {$wpdb->postmeta} pm2 
                WHERE pm2.post_id = pm.post_id 
                AND pm2.meta_key = 'gs_tenant_id' 
                AND pm2.meta_value = %s
            )", $tenant_id);
        }

        return $wpdb->get_var($query);
    }

    /**
     * Get recent activity
     */
    public static function get_recent_activity($tenant_id = null, $limit = 10)
    {
        $logger = new WPGSIP_Logger();
        return $logger->get_recent_logs($limit, $tenant_id);
    }
}
