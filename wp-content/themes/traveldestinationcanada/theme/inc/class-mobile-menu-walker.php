<?php
/**
 * Mobile Menu Walker with Nested Dropdowns
 *
 * @package _tw
 */

class Mobile_Menu_Walker extends Walker_Nav_Menu
{
    /**
     * Start Level - handle the dropdown container for mobile
     */
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $submenu_class = ($depth === 0) ? 'mobile-submenu-level-1' : 'mobile-submenu-level-2';
        $output .= "\n$indent<div class=\"mobile-submenu {$submenu_class} ml-4 mt-2 space-y-1 hidden max-h-0 overflow-hidden transition-all duration-300\">\n";
    }

    /**
     * End Level - close the dropdown container
     */
    function end_lvl(&$output, $depth = 0, $args = null)
    {
        $indent = str_repeat("\t", $depth);
        $output .= "$indent</div>\n";
    }

    /**
     * Start Element - handle individual menu items for mobile
     */
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $indent = str_repeat("\t", $depth);

        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $classes[] = 'menu-item-' . $item->ID;

        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args));
        $class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

        $id_attr = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args);
        $id_attr = $id_attr ? ' id="' . esc_attr($id_attr) . '"' : '';

        $attributes = !empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) . '"' : '';
        $attributes .= !empty($item->target)     ? ' target="' . esc_attr($item->target) . '"' : '';
        $attributes .= !empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn) . '"' : '';
        $attributes .= !empty($item->url)        ? ' href="'   . esc_attr($item->url) . '"' : '';

        $has_children = in_array('menu-item-has-children', $classes);

        if ($depth === 0) {
            // Top level mobile menu items
            $output .= $indent . '<div' . $id_attr . $class_names . '>';
            
            if ($has_children) {
                // Parent item with submenu
                $output .= '<div class="flex items-center justify-between">';
                $output .= '<span class="text-white block px-3 py-2 text-sm font-medium flex-1">';
                $output .= apply_filters('the_title', $item->title, $item->ID);
                $output .= '</span>';
                $output .= '<button class="mobile-submenu-toggle text-white hover:text-gray-200 px-2 py-2" data-target="submenu-' . $item->ID . '">';
                $output .= '<svg class="h-4 w-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                $output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
                $output .= '</svg>';
                $output .= '</button>';
                $output .= '</div>';
            } else {
                // Simple link
                $output .= '<a' . $attributes . ' class="text-white hover:text-gray-200 block px-3 py-2 text-sm font-medium">';
                $output .= apply_filters('the_title', $item->title, $item->ID);
                $output .= '</a>';
            }
            
        } elseif ($depth === 1) {
            // Second level items - also can have children
            $output .= $indent . '<div' . $id_attr . $class_names . '>';
            
            if ($has_children) {
                // Second level with third level children
                $output .= '<div class="flex items-center justify-between border-l-2 border-red-400 ml-2">';
                $output .= '<span class="text-white block px-3 py-2 text-sm font-medium flex-1">';
                $output .= apply_filters('the_title', $item->title, $item->ID);
                $output .= '</span>';
                $output .= '<button class="mobile-submenu-toggle text-white hover:text-gray-200 px-2 py-2" data-target="submenu-' . $item->ID . '">';
                $output .= '<svg class="h-3 w-3 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                $output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
                $output .= '</svg>';
                $output .= '</button>';
                $output .= '</div>';
            } else {
                // Simple second level link
                $output .= '<a' . $attributes . ' class="text-white hover:text-gray-200 block px-3 py-2 text-sm font-medium border-l-2 border-red-400 ml-2">';
                $output .= apply_filters('the_title', $item->title, $item->ID);
                $output .= '</a>';
            }
            
        } else {
            // Third level items
            $output .= $indent . '<a' . $attributes . ' class="text-white hover:text-gray-200 block px-3 py-2 text-xs font-medium border-l-2 border-red-300 ml-4 bg-red-600 bg-opacity-20">';
            $output .= apply_filters('the_title', $item->title, $item->ID);
            $output .= '</a>';
        }
    }

    /**
     * End Element - close individual menu items
     */
    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        if ($depth <= 1) {
            $output .= "</div>\n";
        }
    }
}