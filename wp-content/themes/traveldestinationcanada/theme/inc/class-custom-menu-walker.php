<?php

/**
 * Custom Menu Walker for Mega Menu with Click Events and Tabs
 *
 * @package _tw
 */

class Custom_Menu_Walker extends Walker_Nav_Menu
{
	private $current_parent = null;
	private $has_tabs = false;

	/**
	 * Start Level - handle the dropdown container
	 */
	function start_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);

		if ($depth === 0) {
			// First level dropdown - check if children have grandchildren (tabs)
			$parent_id = $this->current_parent ? $this->current_parent->ID : 'default';

			// Check if any child has children (to determine if we need tabs)
			$this->has_tabs = $this->check_has_grandchildren($this->current_parent);

			$output .= "\n$indent<div class=\"mega-menu hidden absolute left-0 top-full w-full bg-white shadow-xl z-50 border-t-4 border-primary\" data-menu=\"{$parent_id}\">\n";
			$output .= "$indent\t<div class=\"max-w-7xl min-h-[50vh] mx-auto px-4 sm:px-6 lg:px-8 py-10\">\n";

			if ($this->has_tabs) {
				// Has tabs - show tab navigation
				$output .= "$indent\t\t<div class=\"border-b border-gray-200 mb-6\">\n";
				$output .= "$indent\t\t\t<nav class=\"flex space-x-8 overflow-x-auto\" id=\"mega-menu-tabs-{$parent_id}\">\n";
				$output .= "$indent\t\t\t</nav>\n";
				$output .= "$indent\t\t</div>\n";
				$output .= "$indent\t\t<div class=\"tab-content\" id=\"mega-menu-content-{$parent_id}\">\n";
			} else {
				// No tabs - simple grid of items
				$output .= "$indent\t\t<div class=\"grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6\">\n";
			}
		} elseif ($depth === 1 && $this->has_tabs) {
			// Second level with tabs - create tab panels
			$tab_id = $this->current_parent ? $this->current_parent->ID : 'default';
			$output .= "\n$indent\t\t\t<div class=\"tab-panel hidden\" data-tab=\"{$tab_id}\">\n";
			$output .= "$indent\t\t\t\t<div class=\"grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6\">\n";
		}
	}

	/**
	 * Check if current item has grandchildren
	 */
	private function check_has_grandchildren($item)
	{
		if (!$item) {
			return false;
		}

		// Get all menu items from the same menu
		$locations = get_nav_menu_locations();
		$menu_id = 0;

		// Find the menu ID from locations
		foreach ($locations as $location => $id) {
			$menu_items = wp_get_nav_menu_items($id);
			if (!$menu_items) continue;

			foreach ($menu_items as $menu_item) {
				if ($menu_item->ID == $item->ID) {
					$menu_id = $id;
					break 2;
				}
			}
		}

		if (!$menu_id) {
			return false;
		}

		$menu_items = wp_get_nav_menu_items($menu_id);

		// Check if we got a valid array
		if (!is_array($menu_items) || empty($menu_items)) {
			return false;
		}

		foreach ($menu_items as $menu_item) {
			if ($menu_item->menu_item_parent == $item->ID) {
				// Found a child, check if it has children
				foreach ($menu_items as $potential_grandchild) {
					if ($potential_grandchild->menu_item_parent == $menu_item->ID) {
						return true; // Has grandchildren
					}
				}
			}
		}
		return false;
	}

	/**
	 * End Level - close the dropdown container
	 */
	function end_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);

		if ($depth === 0) {
			if ($this->has_tabs) {
				$output .= "$indent\t\t</div>\n"; // Close tab-content
			} else {
				$output .= "$indent\t\t</div>\n"; // Close grid
			}
			$output .= "$indent\t</div>\n"; // Close container
			$output .= "$indent</div>\n"; // Close mega-menu
		} elseif ($depth === 1 && $this->has_tabs) {
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
			// Second level items
			$this->current_parent = $item;

			if ($this->has_tabs) {
				// Has grandchildren - create tab button
				$tab_title = apply_filters('the_title', $item->title, $item->ID);
				$output .= $indent . '<div class="tab-data hidden" data-tab-id="' . $item->ID . '" data-tab-title="' . esc_attr($tab_title) . '"></div>';
			} else {
				// No grandchildren - render as card directly
				$menu_image = get_menu_item_image($item->ID, $item->object_id, $item->object, 'medium');

				$output .= $indent . '<div class="menu-card group cursor-pointer">';
				$output .= '<a' . $attributes . ' class="block h-full">';
				$output .= '<div class="relative overflow-hidden rounded-lg bg-white hover:shadow-md transition-all duration-300 border border-gray-200">';

				if ($menu_image) {
					$output .= '<div class="aspect-w-16 aspect-h-9 overflow-hidden">';
					$output .= '<img src="' . esc_url($menu_image) . '" alt="' . esc_attr($item->title) . '" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">';
					$output .= '</div>';
				}

				$output .= '<div class="p-4">';
				$output .= '<h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors duration-200 line-clamp-1">';
				$output .= apply_filters('the_title', $item->title, $item->ID);
				$output .= '</h3>';

				// if (!empty($item->description)) {
				// 	$output .= '<p class="mt-2 text-sm text-gray-600 line-clamp-2">' . esc_html($item->description) . '</p>';
				// }

				$output .= '</div>';
				$output .= '</div>';
				$output .= '</a>';
			}
		} else {
			// Third level - content cards (only when has_tabs is true)
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
			$output .= '<h3 class="text-lg font-semibold text-gray-900 group-hover:text-primary transition-colors duration-200 line-clamp-1">';
			$output .= apply_filters('the_title', $item->title, $item->ID);
			$output .= '</h3>';

			// Optional description
			// if (!empty($item->description)) {
			// 	$output .= '<p class="mt-2 text-sm text-gray-600 line-clamp-2">' . esc_html($item->description) . '</p>';
			// }

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
		} elseif ($depth === 1 && !$this->has_tabs) {
			// Close the card div for level 1 items without tabs
			$output .= "</div>\n";
		} elseif ($depth === 2) {
			$output .= "</div>\n";
		}
	}
}