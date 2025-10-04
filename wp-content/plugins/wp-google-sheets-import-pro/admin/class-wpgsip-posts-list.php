<?php

/**
 * Posts list helper class
 *
 * @package WP_Google_Sheets_Import_Pro
 */

class WPGSIP_Posts_List
{

    /**
     * Get imported posts
     */
    public static function get_posts($args = array())
    {
        $defaults = array(
            'post_type' => 'post',
            'posts_per_page' => 20,
            'paged' => 1,
            'meta_query' => array(
                array(
                    'key' => 'imported_from_gs',
                    'value' => '1',
                    'compare' => '='
                )
            ),
            'orderby' => 'modified',
            'order' => 'DESC',
        );

        $args = wp_parse_args($args, $defaults);
        return new WP_Query($args);
    }

    /**
     * Get post import info
     */
    public static function get_post_import_info($post_id)
    {
        return array(
            'sheet_row_id' => get_post_meta($post_id, 'gs_sheet_row_id', true),
            'tenant_id' => get_post_meta($post_id, 'gs_tenant_id', true),
            'last_sync' => get_post_meta($post_id, 'gs_last_sync', true),
            'original_data' => get_post_meta($post_id, 'gs_original_data', true),
        );
    }
}
