<?php

/**
 * Template Name: Things To Do Articles
 */

get_header()

?>


<?php
// include section-hero
// Truyền xuống template
$slides = get_field('hero_slides');
set_query_var('slideHero_config', $slides);
get_template_part('template-parts/layout/section-hero', 'dataHero');
?>

<?php
// include breadcrumb
// $slides = get_field('hero_slides');
// set_query_var('slideHero_config', $slides);
get_template_part('template-parts/layout/breadcrumb', 'breadcumbData');
?>

<?php
set_query_var('slide_config', [
	'id' => 'Arts_slide',
	'title' => "More to see",
	'sub_title' => "Discover incredible destinations",
	'link' => true,
	'loop' => false,
]);

get_template_part('template-parts/layout/articles', 'dataArticles');
?>


<?php
set_query_var('slide_config', [
	'id' => 'adventures_slide',
	'title' => "Inspiring adventures",
	'sub_title' => "Incredible travel stories & guides
",
	'link' => true,
	'loop' => false,
]);

get_template_part('template-parts/layout/section-slide4', 'slide');
?>

<?php
get_footer();
?>