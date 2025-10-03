<?php

/**
 * ACF Fields for Archive Destination - ĐÚNG CÁCH
 * Copy code này vào functions.php
 */

add_action('acf/include_fields', function () {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(array(
		'key' => 'group_archive_destination_options',
		'title' => 'Places To Go Archive Settings',
		'fields' => array(
			array(
				'key' => 'field_archive_hero_banner',
				'label' => 'Hero Banner',
				'name' => 'hero_banner_text',
				'aria-label' => '',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'layout' => 'table',
				'pagination' => 0,
				'min' => 0,
				'max' => 0,
				'collapsed' => '',
				'button_label' => 'Add Slide',
				'rows_per_page' => 20,
				'sub_fields' => array(
					array(
						'key' => 'field_archive_hero_image',
						'label' => 'Image',
						'name' => 'image',
						'aria-label' => '',
						'type' => 'image',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
						'library' => 'all',
						'min_width' => '',
						'min_height' => '',
						'min_size' => '',
						'max_width' => '',
						'max_height' => '',
						'max_size' => '',
						'mime_types' => '',
						'preview_size' => 'medium',
						'parent_repeater' => 'field_archive_hero_banner',
					),
					array(
						'key' => 'field_archive_hero_title',
						'label' => 'Title',
						'name' => 'title',
						'aria-label' => '',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'maxlength' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'parent_repeater' => 'field_archive_hero_banner',
					),
				),
			),
			array(
				'key' => 'field_archive_intro',
				'label' => 'Introduction Content',
				'name' => 'intro_content',
				'aria-label' => '',
				'type' => 'wysiwyg',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'default_value' => '',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
				'delay' => 0,
			),
			array(
				'key' => 'field_archive_slider_items',
				'label' => 'Slider Items (More to see & Inspiring Adventures)',
				'name' => 'slider_item_places_to_go',
				'aria-label' => '',
				'type' => 'repeater',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'layout' => 'block',
				'pagination' => 0,
				'min' => 0,
				'max' => 0,
				'collapsed' => '',
				'button_label' => 'Add Slider Section',
				'rows_per_page' => 20,
				'sub_fields' => array(
					array(
						'key' => 'field_archive_slider_title',
						'label' => 'Title',
						'name' => 'title',
						'aria-label' => '',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'maxlength' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'parent_repeater' => 'field_archive_slider_items',
					),
					array(
						'key' => 'field_archive_slider_subtitle',
						'label' => 'Subtitle',
						'name' => 'subtitle',
						'aria-label' => '',
						'type' => 'text',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'default_value' => '',
						'maxlength' => '',
						'placeholder' => '',
						'prepend' => '',
						'append' => '',
						'parent_repeater' => 'field_archive_slider_items',
					),
					array(
						'key' => 'field_archive_slider_button',
						'label' => 'Button Direction',
						'name' => 'button_direction',
						'aria-label' => '',
						'type' => 'link',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'array',
						'parent_repeater' => 'field_archive_slider_items',
					),
					array(
						'key' => 'field_archive_slider_posts',
						'label' => 'Items (Posts/Destinations)',
						'name' => 'item_slider',
						'aria-label' => '',
						'type' => 'relationship',
						'instructions' => '',
						'required' => 0,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'post_type' => array(
							'destination',
							'thing_to_do',
							'post',
						),
						'post_status' => array('publish'),
						'taxonomy' => '',
						'filters' => array('search', 'post_type'),
						'return_format' => 'object',
						'min' => '',
						'max' => '',
						'elements' => '',
						'bidirectional' => 0,
						'bidirectional_target' => array(),
						'parent_repeater' => 'field_archive_slider_items',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					'value' => 'archive-destination-settings',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));
});

if (function_exists('acf_add_options_page')) {
	acf_add_options_page(array(
		'page_title' => 'Archive Destination Settings',
		'menu_title' => 'Archive Destination',
		'menu_slug' => 'archive-destination-settings',
		'capability' => 'edit_posts',
		'parent_slug' => 'edit.php?post_type=destination',
		'position' => false,
		'icon_url' => false,
	));
}





/**
 * ACF Fields for Archive Thing To Do - ĐÚNG CÁCH
 * Copy code này vào functions.php (hoặc file plugin/theme)
 */
add_action('acf/include_fields', function () {
	if (!function_exists('acf_add_local_field_group')) {
		return;
	}

	acf_add_local_field_group(array(
		// Đổi key để tránh trùng lặp
		'key' => 'group_archive_thing_to_do_options',
		'title' => 'Thing To Do Archive Settings', // Đổi tiêu đề

		// Giữ nguyên cấu trúc các trường Hero Banner, Intro, Slider Items
		'fields' => array(
			// Trường 1: Hero Banner (Repeater)
			array(
				'key' => 'field_thing_to_do_hero_banner',
				'label' => 'Hero Banner',
				'name' => 'hero_banner_thing_to_do', // Đổi tên trường chính
				'type' => 'repeater',
				'layout' => 'table',
				'button_label' => 'Add Slide',
				'sub_fields' => array(
					array(
						'key' => 'field_thing_to_do_hero_image',
						'label' => 'Image',
						'name' => 'image',
						'type' => 'image',
						'return_format' => 'array',
					),
					array(
						'key' => 'field_thing_to_do_hero_title',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
					),
				),
			),

			// Trường 2: Introduction Content (WYSIWYG)
			array(
				'key' => 'field_thing_to_do_archive_intro',
				'label' => 'Introduction Content',
				'name' => 'intro_content_thing_to_do', // Đổi tên trường chính
				'type' => 'wysiwyg',
				'tabs' => 'all',
				'toolbar' => 'full',
				'media_upload' => 1,
			),

			array(
				'key' => 'field_thing_to_do_archive_slider_items',
				'label' => 'Slider Items (More to see & Inspiring Adventures)',
				'name' => 'slider_item_thing_to_do',
				'type' => 'repeater',
				'layout' => 'block',
				'button_label' => 'Add Slider Section',
				'sub_fields' => array(

					// --- Giữ nguyên các trường Title, Subtitle, Button Direction ---
					array(
						'key' => 'field_thing_to_do_archive_slider_title',
						'label' => 'Title',
						'name' => 'title',
						'type' => 'text',
					),
					array(
						'key' => 'field_thing_to_do_archive_slider_subtitle',
						'label' => 'Subtitle',
						'name' => 'subtitle',
						'type' => 'text',
					),
					array(
						'key' => 'field_thing_to_do_archive_slider_button',
						'label' => 'Button Direction',
						'name' => 'button_direction',
						'type' => 'link',
						'return_format' => 'array',
					),

					// **********************************************
					// ************ PHẦN SỬA ĐỔI CHÍNH **************
					// **********************************************

					// TRƯỜNG 1: Lựa chọn Loại Taxonomy
					array(
						'key' => 'field_taxonomy_type_select',
						'label' => 'Chọn Loại Phân Loại',
						'name' => 'taxonomy_type_select',
						'type' => 'select',
						'instructions' => 'Chọn loại Taxonomy bạn muốn lọc bài viết.',
						'required' => 1,
						'choices' => array(
							'thing_themes' => 'Chủ đề (Themes)',
							'provinces_territories' => 'Tỉnh/Vùng lãnh thổ (Provinces/Territories)',
							'seasons' => 'Mùa (Seasons)',
						),
						'default_value' => 'thing_themes',
						'return_format' => 'value',
						'multiple' => 0,
						'parent_repeater' => 'field_thing_to_do_archive_slider_items',
					),

					// TRƯỜNG 2: Chọn Terms cho 'thing_themes' (Chủ đề)
					array(
						'key' => 'field_thing_themes_select',
						'label' => 'Chọn Chủ đề (Themes)',
						'name' => 'selected_themes',
						'type' => 'taxonomy',
						'taxonomy' => 'thing_themes',
						'field_type' => 'checkbox',
						'return_format' => 'object', // Trả về Term Objects
						'multiple' => 1,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_taxonomy_type_select',
									'operator' => '==',
									'value' => 'thing_themes',
								),
							),
						),
						'parent_repeater' => 'field_thing_to_do_archive_slider_items',
					),

					// TRƯỜNG 3: Chọn Terms cho 'provinces_territories' (Địa điểm)
					array(
						'key' => 'field_provinces_select',
						'label' => 'Chọn Địa điểm (Provinces/Territories)',
						'name' => 'selected_provinces',
						'type' => 'taxonomy',
						'taxonomy' => 'provinces_territories',
						'field_type' => 'checkbox',
						'return_format' => 'object',
						'multiple' => 1,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_taxonomy_type_select',
									'operator' => '==',
									'value' => 'provinces_territories',
								),
							),
						),
						'parent_repeater' => 'field_thing_to_do_archive_slider_items',
					),

					// TRƯỜNG 4: Chọn Terms cho 'seasons' (Mùa)
					array(
						'key' => 'field_seasons_select',
						'label' => 'Chọn Mùa (Seasons)',
						'name' => 'selected_seasons',
						'type' => 'taxonomy',
						'taxonomy' => 'seasons',
						'field_type' => 'checkbox',
						'return_format' => 'object',
						'multiple' => 1,
						'conditional_logic' => array(
							array(
								array(
									'field' => 'field_taxonomy_type_select',
									'operator' => '==',
									'value' => 'seasons',
								),
							),
						),
						'parent_repeater' => 'field_thing_to_do_archive_slider_items',
					),
				),
			),
		),

		// Cấu hình vị trí hiển thị nhóm trường ACF
		'location' => array(
			array(
				array(
					'param' => 'options_page',
					'operator' => '==',
					// QUAN TRỌNG: Phải khớp với 'menu_slug' của Options Page ở bước 1
					'value' => 'archive-thing-to-do-settings',
				),
			),
		),

		// Cài đặt chung
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
	));
});

// Tạo Options Page cho Archive Thing To Do
if (function_exists('acf_add_options_page')) {
	acf_add_options_page(array(
		'page_title' => 'Archive Thing To Do Settings', // Tiêu đề trang
		'menu_title' => 'Archive Thing To Do',          // Tên hiển thị trong menu
		'menu_slug'  => 'archive-thing-to-do-settings', // Slug duy nhất cho trang
		'capability' => 'edit_posts',
		// Đặt làm trang con của menu CPT 'thing_to_do'
		'parent_slug' => 'edit.php?post_type=thing_to_do',
		'position'   => false,
		'icon_url'   => false,
	));
}
