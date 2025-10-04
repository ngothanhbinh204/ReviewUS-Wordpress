<?php

/**
 * Mobile Menu Walker with Nested Dropdowns
 *
 * @package _tw
 */

class Mobile_Menu_Walker extends Walker_Nav_Menu
{
	private $parent_titles = array();
	private $overlay_header_added = array();

	/**
	 * Get parent title for current item
	 */
	private function get_parent_title($item)
	{
		if (isset($this->parent_titles[$item->menu_item_parent])) {
			return $this->parent_titles[$item->menu_item_parent];
		}
		return '';
	}

	/**
	 * Start Level - handle the dropdown container for mobile (full screen overlay)
	 */
	function start_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);

		if ($depth === 0) {
			// First level - full screen overlay
			$parent_id = $this->current_parent ? $this->current_parent->ID : 0;
			$parent_title = $this->current_parent ? apply_filters('the_title', $this->current_parent->title, $this->current_parent->ID) : '';

			$output .= "\n$indent<div class=\"mobile-submenu-overlay h-screen fixed inset-0 overflow-hidden overscroll-contain bg-white z-50 hidden\" data-parent=\"$parent_id\">\n";
			$output .= "$indent\t<div class=\"h-full overflow-y-auto\">\n";

			// Add header with back button at the start of overlay
			$output .= "$indent\t\t<div class=\"sticky top-0 bg-white z-10\">\n";
			$output .= "$indent\t\t\t<button class=\"mobile-submenu-back w-full flex items-center px-4 py-6 text-white bg-primary bg-primary flex items-center text-white shadow-[-1px_4px_8px_rgba(0,0,0,0.25)]\">\n";
			$output .= "$indent\t\t\t\t<svg class=\"h-5 w-5 mr-3\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">\n";
			$output .= "$indent\t\t\t\t\t<path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 19l-7-7 7-7\"></path>\n";
			$output .= "$indent\t\t\t\t</svg>\n";
			$output .= "$indent\t\t\t\t<span class=\"text-sm\">Go Back</span>\n";
			$output .= "$indent\t\t\t</button>\n";
			$output .= "$indent\t\t\t<h2 class=\"break-words text-[26px] font-bold leading-tight lg:text-[32px] 2xl:text-[36px] border-grey/20 mb-6 border-b-2 px-6 py-6\">" . esc_html($parent_title) . "</h2>\n";
			$output .= "$indent\t\t</div>\n";

			// bổ sung thêm nút   
			// <button id="mobileMenuBtn"
			//     class="text-white hover:text-gray-200 p-2 rounded-md transition-colors duration-200">
			//     <svg id="menuIcon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			//         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
			//             d="M4 6h16M4 12h16M4 18h16"></path>
			//     </svg>
			//     <svg id="closeIcon" class="h-6 w-6 hidden" fill="none" stroke="currentColor"
			//         viewBox="0 0 24 24">
			//         <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
			//             d="M6 18L18 6M6 6l12 12"></path>
			//     </svg>
			// </button>

			// Mark that header has been added for this parent
			$this->overlay_header_added[$parent_id] = true;
		} elseif ($depth === 1) {
			// Second level - nested inside first level
			$output .= "\n$indent<div class=\"mobile-submenu-nested hidden\">\n";
		}
	}

	/**
	 * End Level - close the dropdown container
	 */
	function end_lvl(&$output, $depth = 0, $args = null)
	{
		$indent = str_repeat("\t", $depth);
		if ($depth === 0) {
			$output .= "$indent\t</div>\n";
			$output .= "$indent</div>\n";
		} else {
			$output .= "$indent</div>\n";
		}
	}

	private $current_parent = null;

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
			// Store current parent for start_lvl
			if ($has_children) {
				$this->current_parent = $item;
			}

			// Top level mobile menu items
			$output .= $indent . '<div' . $id_attr . $class_names . '>';

			if ($has_children) {
				// Store parent title for children
				$this->parent_titles[$item->ID] = apply_filters('the_title', $item->title, $item->ID);

				// Parent item with submenu - clickable to open full screen
				$output .= '<button class="mobile-submenu-open w-full flex items-center justify-between px-6 py-4 font-bold border-b border-red-600 text-primary" data-submenu="submenu-' . $item->ID . '">';
				$output .= '<span class="underline text-primary">' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
				$output .= '<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
				$output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>';
				$output .= '</svg>';
				$output .= '</button>';
			} else {
				// Simple link
				$output .= '<a' . $attributes . ' class="block px-6 py-4 font-medium border-b border-red-600 text-primary text-base">';
				$output .= apply_filters('the_title', $item->title, $item->ID);
				$output .= '</a>';
			}
		} elseif ($depth === 1) {
			// Second level items in overlay (no duplicate header)
			$output .= $indent . '<div' . $id_attr . $class_names . '>';

			if ($has_children) {
				// Second level with third level children - expandable
				$output .= '<button class="mobile-submenu-expand w-full flex items-center justify-between text-primary px-6 py-3 text-lg font-semibold">';
				$output .= '<span>' . apply_filters('the_title', $item->title, $item->ID) . '</span>';
				$output .= '<svg class="h-4 w-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
				$output .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>';
				$output .= '</svg>';
				$output .= '</button>';
			} else {
				// Simple second level link
				$output .= '<a' . $attributes . ' class="text-primary block px-6 py-3 text-sm">';
				$output .= apply_filters('the_title', $item->title, $item->ID);
				$output .= '</a>';
			}
		} else {
			// Third level items - nested under second level
			$output .= $indent . '<a' . $attributes . ' class="text-base hover:text-primary block px-12 py-2 text-sm">';
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