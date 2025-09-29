<?php
/**
 * Custom Menu Walker for Mega Menu with Click Events and Tabs
 *
 * @package _tw
 */

class Custom_Menu_Walker extends Walker_Nav_Menu
{
    private $current_parent = null;
    private $tab_items = array();
    
    /**
     * Start Level - handle the dropdown container
     */
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            // First level dropdown - mega menu container with tabs
            $parent_id = $this->current_parent ? $this->current_parent->ID : 'default';
            $output .= "\n$indent<div class=\"mega-menu hidden fixed left-0 top-full w-full bg-white shadow-xl z-50 border-t-4 border-primary\" data-menu=\"{$parent_id}\">\n";
            $output .= "$indent\t<div class=\"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8\">\n";
            
            // Tab navigation container (will be populated dynamically)
            $output .= "$indent\t\t<div class=\"border-b border-gray-200 mb-6\">\n";
            $output .= "$indent\t\t\t<nav class=\"flex space-x-8 overflow-x-auto\" id=\"mega-menu-tabs-{$parent_id}\">\n";
            $output .= "$indent\t\t\t</nav>\n";
            $output .= "$indent\t\t</div>\n";
            
            // Content area for tab panels
            $output .= "$indent\t\t<div class=\"tab-content\" id=\"mega-menu-content-{$parent_id}\">\n";
            
        } elseif ($depth === 1) {
            // Second level - create tab panels
            $tab_id = $this->current_parent ? $this->current_parent->ID : 'default';
            $output .= "\n$indent\t\t\t<div class=\"tab-panel hidden\" data-tab=\"{$tab_id}\">\n";
            $output .= "$indent\t\t\t\t<div class=\"grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6\">\n";
        }
    }

    /**
     * End Level - close the dropdown container
     */
    function end_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            $output .= "$indent\t\t</div>\n"; // Close tab-content
            $output .= "$indent\t</div>\n"; // Close container
            $output .= "$indent</div>\n"; // Close mega-menu
        } elseif ($depth === 1) {
            $output .= "$indent\t\t\t\t</div>\n"; // Close grid
            $output .= "$indent\t\t\t</div>\n"; // Close tab-panel
        }
    }

    /**
     * Start Element - handle individual menu items
     */
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = str_repeat("\t", $depth);
        
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;
        $has_children = in_array('menu-item-has-children', $classes);

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id_attr = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id_attr = $id_attr ? ' id="' . esc_attr($id_attr) . '"' : '';

        $attributes = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"' : '';

        if ($depth === 0) {
            // Top level menu items with click functionality
            $this->current_parent = $item;
            
            $output .= $indent . '<li' . $id_attr . $class_names . '>';
            $output .= '<div class="relative mega-menu-trigger">';
            
            if ($has_children) {
                // Clickable dropdown trigger
                $output .= '<button class="mega-menu-btn text-white hover:text-gray-200 px-3 py-2 text-sm font-medium flex items-center transition-colors duration-200" data-menu-target="' . $item->ID . '" aria-expanded="false">';
                $output .= apply_filters('the_title', $item->title, $item->ID);
                $output .= '<svg class="ml-1 h-4 w-4 transition-transform duration-200 mega-menu-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                $output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
                $output .= '</svg>';
                $output .= '</button>';
            } else {
                // Simple link
                $output .= '<a' . $attributes . ' class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium transition-colors duration-200">';
                $output .= apply_filters('the_title', $item->title, $item->ID);
                $output .= '</a>';
            }
            
        } elseif ($depth === 1) {
            // Second level - tab buttons (will be moved to tab nav by JS)
            $this->current_parent = $item;
            $tab_title = apply_filters('the_title', $item->title, $item->ID);
            
            // Create a data attribute for JS to pick up
            $output .= $indent . '<div class="tab-data hidden" data-tab-id="' . $item->ID . '" data-tab-title="' . esc_attr($tab_title) . '"></div>';
            
        } else {
            // Third level - content cards
            $menu_image = get_menu_item_image($item->ID, $item->object_id, $item->object, 'medium');
            
            $output .= $indent . '<div class="menu-card group cursor-pointer">';
            $output .= '<a' . $attributes . ' class="block h-full">';
            $output .= '<div class="relative overflow-hidden rounded-lg bg-white shadow-md hover:shadow-lg transition-all duration-300 border border-gray-200">';

            // Image
            if ($menu_image) {
                $output .= '<div class="aspect-w-16 aspect-h-9 overflow-hidden">';
                $output .= '<img src="' . esc_url($menu_image) . '" alt="' . esc_attr($item->title) . '" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">';
                $output .= '</div>';
            }

            // Content
            $output .= '<div class="p-4">';
            $output .= '<h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors duration-200">';
            $output .= apply_filters('the_title', $item->title, $item->ID);
            $output .= '</h3>';

            // Optional description
            if (!empty($item->description)) {
                $output .= '<p class="mt-2 text-sm text-gray-600 line-clamp-2">' . esc_html($item->description) . '</p>';
            }

            $output .= '</div>';
            $output .= '</div>';
            $output .= '</a>';
        }
    }

    /**
     * End Element - close individual menu items
     */
    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if ($depth === 0) {
            $output .= "</div></li>\n";
        } elseif ($depth === 2) {
            $output .= "</div>\n";
        }
    }
}