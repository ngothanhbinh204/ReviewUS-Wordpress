<?php

/**
 * Custom Menu Walker for Mega Menu with Click Events and Tabs
 *
 * @package _tw
 */

class Custom_Menu_Walker extends Walker_Nav_Menu
{
    private $current_parent = null;
    private $submenu_items = array();
    
    /**
     * Start Level - handle the dropdown container
     */
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);

        if ($depth === 0) {
            // First level dropdown - mega menu container with tabs
            $parent_id = $this->current_parent ? $this->current_parent->ID : 'default';
            $output .= "\n$indent<div class=\"mega-menu hidden fixed left-0 top-full w-full bg-white shadow-xl z-50 border-t-3 border-primary\" data-menu=\"{$parent_id}\">\n";
            $output .= "$indent\t<div class=\"max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8\">\n";
            
            // Tab navigation for second level items
            $output .= "$indent\t\t<div class=\"border-b border-gray-200 mb-6\">\n";
            $output .= "$indent\t\t\t<nav class=\"flex space-x-8 overflow-x-auto\" aria-label=\"Tabs\">\n";
            // Tabs will be populated by JavaScript
            $output .= "$indent\t\t\t</nav>\n";
            $output .= "$indent\t\t</div>\n";
            
            // Content area for tab panels
            $output .= "$indent\t\t<div class=\"tab-content\">\n";
        } else {
            // Deeper levels - tab panels
            $output .= "\n$indent<div class=\"tab-panel hidden\" data-tab=\"{$this->current_parent->ID}\">\n";
            $output .= "$indent\t<div class=\"grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6\">\n";
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
        } else {
            $output .= "$indent\t</div>\n"; // Close grid
            $output .= "$indent</div>\n"; // Close tab-panel
        }
    }

    /**
     * Start Element - handle individual menu items
     */
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = str_repeat("\t", $depth);
        $this->current_item = $item;

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        // Get the menu item image using helper function
        $menu_image = get_menu_item_image($item->ID, $item->object_id, $item->object, 'medium');

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id = $id ? ' id="' . esc_attr($id) . '"' : '';

        $attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
        $attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
        $attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"' : '';

        if ($depth === 0) {
            // Top level menu items
            $has_children = in_array('menu-item-has-children', $classes);
            $group_class = $has_children ? 'group' : '';

            $output .= $indent . '<li' . $id . $class_names . '>';
            $output .= '<div class="relative ' . $group_class . '">';
            $output .= '<button class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium flex items-center transition-colors duration-200">';
            $output .= apply_filters('the_title', $item->title, $item->ID);

            if ($has_children) {
                $output .= '<svg class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                $output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
                $output .= '</svg>';
            }

            $output .= '</button>';
        } else {
            // Sub menu items - display as cards
            $output .= $indent . '<div class="menu-card group cursor-pointer">';
            $output .= '<a' . $attributes . ' class="block h-full">';
            $output .= '<div class="relative overflow-hidden rounded-lg bg-white shadow-md hover:shadow-lg transition-shadow duration-300">';

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
        } else {
            $output .= "</div>\n";
        }
    }
}
