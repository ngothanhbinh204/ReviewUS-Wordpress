<?php

/**
 * Posts listing page
 */

if (!defined('ABSPATH')) {
    exit;
}

$tenant_manager = new WPGSIP_Tenant_Manager();
$current_tenant_id = $tenant_manager->get_current_tenant_id();

// Get imported posts
$paged = isset($_GET['paged']) ? absint($_GET['paged']) : 1;
$per_page = 20;

$args = array(
    'post_type' => 'post',
    'posts_per_page' => $per_page,
    'paged' => $paged,
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

$query = new WP_Query($args);
$total_posts = $query->found_posts;
$total_pages = $query->max_num_pages;
?>

<div class="wrap">
    <h1><?php esc_html_e('Imported Posts', 'wp-gs-import-pro'); ?></h1>

    <p class="description">
        <?php printf(esc_html__('Showing %d imported posts', 'wp-gs-import-pro'), $total_posts); ?>
    </p>

    <?php if ($query->have_posts()): ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('ID', 'wp-gs-import-pro'); ?></th>
                    <th><?php esc_html_e('Title', 'wp-gs-import-pro'); ?></th>
                    <th><?php esc_html_e('Status', 'wp-gs-import-pro'); ?></th>
                    <th><?php esc_html_e('Sheet Row', 'wp-gs-import-pro'); ?></th>
                    <th><?php esc_html_e('Last Synced', 'wp-gs-import-pro'); ?></th>
                    <th><?php esc_html_e('Actions', 'wp-gs-import-pro'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php while ($query->have_posts()): $query->the_post();
                    $post_id = get_the_ID();
                    $sheet_row_id = get_post_meta($post_id, 'gs_sheet_row_id', true);
                    $last_sync = get_post_meta($post_id, 'gs_last_sync', true);
                ?>
                    <tr>
                        <td><?php echo esc_html($post_id); ?></td>
                        <td>
                            <strong><?php the_title(); ?></strong>
                        </td>
                        <td>
                            <span class="wpgsip-status-badge wpgsip-status-<?php echo esc_attr(get_post_status()); ?>">
                                <?php echo esc_html(get_post_status()); ?>
                            </span>
                        </td>
                        <td><?php echo esc_html($sheet_row_id ?: '-'); ?></td>
                        <td><?php echo esc_html($last_sync ? date('Y-m-d H:i', strtotime($last_sync)) : '-'); ?></td>
                        <td>
                            <a href="<?php echo get_edit_post_link($post_id); ?>" class="button button-small">
                                <?php esc_html_e('Edit', 'wp-gs-import-pro'); ?>
                            </a>
                            <a href="<?php echo get_permalink($post_id); ?>" class="button button-small" target="_blank">
                                <?php esc_html_e('View', 'wp-gs-import-pro'); ?>
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php
        // Pagination
        if ($total_pages > 1) {
            $page_links = paginate_links(array(
                'base' => add_query_arg('paged', '%#%'),
                'format' => '',
                'prev_text' => __('&laquo;', 'wp-gs-import-pro'),
                'next_text' => __('&raquo;', 'wp-gs-import-pro'),
                'total' => $total_pages,
                'current' => $paged
            ));

            if ($page_links) {
                echo '<div class="tablenav"><div class="tablenav-pages">' . $page_links . '</div></div>';
            }
        }
        ?>

    <?php else: ?>
        <p><?php esc_html_e('No imported posts found.', 'wp-gs-import-pro'); ?></p>
    <?php endif; ?>

    <?php wp_reset_postdata(); ?>
</div>