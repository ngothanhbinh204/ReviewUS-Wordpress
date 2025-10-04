<?php

/**
 * Logging functionality
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Logger
{

    /**
     * Log an event
     */
    public function log($data)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_import_logs';

        $log_data = array(
            'tenant_id' => $data['tenant_id'] ?? null,
            'blog_id' => $data['blog_id'] ?? get_current_blog_id(),
            'action' => $data['action'] ?? 'unknown',
            'post_id' => $data['post_id'] ?? null,
            'sheet_row_id' => $data['sheet_row_id'] ?? null,
            'status' => $data['status'] ?? 'info',
            'message' => $data['message'] ?? '',
            'data' => $data['data'] ?? null,
            'created_at' => current_time('mysql'),
        );

        return $wpdb->insert(
            $table,
            $log_data,
            array('%s', '%d', '%s', '%d', '%s', '%s', '%s', '%s', '%s')
        );
    }

    /**
     * Get logs
     */
    public function get_logs($page = 1, $per_page = 20, $tenant_id = null, $status = null)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_import_logs';

        $offset = ($page - 1) * $per_page;

        $where = array('1=1');
        $where_values = array();

        if ($tenant_id !== null) {
            $where[] = 'tenant_id = %s';
            $where_values[] = $tenant_id;
        }

        if ($status !== null) {
            $where[] = 'status = %s';
            $where_values[] = $status;
        }

        $where_clause = implode(' AND ', $where);

        // Get total count
        $total_query = "SELECT COUNT(*) FROM $table WHERE $where_clause";
        if (!empty($where_values)) {
            $total_query = $wpdb->prepare($total_query, ...$where_values);
        }
        $total = $wpdb->get_var($total_query);

        // Get logs
        $logs_query = "SELECT * FROM $table WHERE $where_clause ORDER BY created_at DESC LIMIT %d OFFSET %d";
        $logs_query = $wpdb->prepare($logs_query, ...[...$where_values, $per_page, $offset]);
        $logs = $wpdb->get_results($logs_query);

        return array(
            'logs' => $logs,
            'total' => $total,
            'page' => $page,
            'per_page' => $per_page,
            'total_pages' => ceil($total / $per_page),
        );
    }

    /**
     * Get logs by post ID
     */
    public function get_logs_by_post($post_id)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_import_logs';

        $logs = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table WHERE post_id = %d ORDER BY created_at DESC",
            $post_id
        ));

        return $logs;
    }

    /**
     * Get recent logs
     */
    public function get_recent_logs($limit = 10, $tenant_id = null)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_import_logs';

        if ($tenant_id !== null) {
            $logs = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table WHERE tenant_id = %s ORDER BY created_at DESC LIMIT %d",
                $tenant_id,
                $limit
            ));
        } else {
            $logs = $wpdb->get_results($wpdb->prepare(
                "SELECT * FROM $table ORDER BY created_at DESC LIMIT %d",
                $limit
            ));
        }

        return $logs;
    }

    /**
     * Get log statistics
     */
    public function get_statistics($tenant_id = null, $days = 30)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_import_logs';

        $date_from = date('Y-m-d H:i:s', strtotime("-$days days"));

        $where = "created_at >= %s";
        $where_values = array($date_from);

        if ($tenant_id !== null) {
            $where .= " AND tenant_id = %s";
            $where_values[] = $tenant_id;
        }

        $stats = array(
            'total' => 0,
            'success' => 0,
            'error' => 0,
            'skipped' => 0,
            'by_date' => array(),
        );

        // Total counts
        $query = "SELECT status, COUNT(*) as count FROM $table WHERE $where GROUP BY status";
        $results = $wpdb->get_results($wpdb->prepare($query, ...$where_values));

        foreach ($results as $row) {
            $stats['total'] += $row->count;
            if ($row->status === 'success') {
                $stats['success'] = $row->count;
            } elseif ($row->status === 'error') {
                $stats['error'] = $row->count;
            } elseif ($row->status === 'skipped') {
                $stats['skipped'] = $row->count;
            }
        }

        // By date
        $query = "SELECT DATE(created_at) as date, COUNT(*) as count FROM $table WHERE $where GROUP BY DATE(created_at) ORDER BY date DESC";
        $by_date = $wpdb->get_results($wpdb->prepare($query, ...$where_values));

        $stats['by_date'] = $by_date;

        return $stats;
    }

    /**
     * Clear old logs
     */
    public function clear_old_logs($days = 90)
    {
        global $wpdb;
        $table = $wpdb->prefix . 'wpgsip_import_logs';

        $date_threshold = date('Y-m-d H:i:s', strtotime("-$days days"));

        return $wpdb->query($wpdb->prepare(
            "DELETE FROM $table WHERE created_at < %s",
            $date_threshold
        ));
    }
}
