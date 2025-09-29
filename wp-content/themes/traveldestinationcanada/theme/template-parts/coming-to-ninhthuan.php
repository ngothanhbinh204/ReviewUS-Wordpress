<?php

/**
 * Template Name: Coming to NinhThuan Template
 */

get_header();
$post_id = get_the_ID();
$sections = get_field('sections', $post_id);
?>

<!-- Banner Hero  -->

<?php
$dataBanner = [
	[
		'title' => 'Coming to Ninh Thuận',
		'sub_title' => 'How far is a km? What are the regulations pertaining to customs and duty? And why is the iced tea so sweet? Get all the info you need to prepare for Canada.',
		'image' => [
			'url' => 'http://destinationcanada.local/wp-content/uploads/2025/06/banner_section.webp',
			'alt' => 'Alt Places To Go'
		],
	],
];
get_template_part('template-parts/layout/section-banner-coming', null, [
	'slides' => $dataBanner,
]);
?>



<?php get_template_part('template-parts/layout/breadcrumb', 'dataBreadcrumb'); ?>

<?php get_template_part('template-parts/layout/sections/section-subnav'); ?>

<?php get_template_part('template-parts/layout/sections/basic-content-top', 'dataBreadcrumb'); ?>


<?php if (have_rows('sections')): ?>
<?php while (have_rows('sections')): the_row();
		$layout = get_sub_field('layout');

		$section_data = [
			'title' => get_sub_field('title'),
			'slug' => sanitize_title(get_sub_field('title')),
		];

		if ($layout === 'basic-content') {
			$section_data['sub_title'] = get_sub_field('content_basic_layout')['sub_title'];
			$section_data['content'] = get_sub_field('content_basic_layout')['content'];
			$section_data['image'] = get_sub_field('content_basic_layout')['image'];
		}

		if ($layout === 'basic-content-two-column') {
			$section_data['sub_title'] = get_sub_field('content_2_column_layout')['sub_title'];
			$section_data['content_left'] = get_sub_field('content_2_column_layout')['content_left'];
			$section_data['content_right'] = get_sub_field('content_2_column_layout')['content_right'];
		}

		if ($layout === 'basic-grid-content') {
			$section_data['items'] = get_sub_field('content_grid_layout');
		}

		get_template_part("template-parts/layout/sections/{$layout}", null, ['section' => $section_data]);
	endwhile; ?>
<?php endif; ?>

<?php get_template_part('template-parts/layout/sections/basic-content-bottom', null, [
	'title'   => 'Coming to Canada',
	'content' => 'Why is the iced tea so sweet? What are the regulations pertaining to customs and duty?
',
	'button'  => [
		'url'   => 'https://travel.destinationcanada.com/en-us/coming-to-canada',
		'title' => 'More information',
	],
]);
?>


<?php

get_footer();
?>
