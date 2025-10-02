<?php

/**
 * Template Name: Homepage Template
 */

get_header();

// Get ACF fields for homepage
$banner_hero = get_field('banner_hero');
$banner_title = get_field('title');
$banner_subtitle = get_field('sub_title');
$slider_items = get_field('slider_item');
$gallery = get_field('gallery');
?>

<div class="homepage">
    <!-- Banner Hero Section -->
    <section
        class="section_hero c-herobanner__slide mb-[96px] relative flex flex-col min-h-[100vw] md:min-h-[90vw] lg:min-h-[640px] px-8 md:px-[104px] lg:px-[176px] sm:py-16 justify-center py-12 md:py-16 pt-40 md:pt-52">
        <div class="absolute inset-0 overflow-hidden">
            <div class="relative h-full w-full">
                <figure class="relative h-full w-full">
                    <?php if ($banner_hero && isset($banner_hero['url'])): ?>
                    <img src="<?php echo esc_url($banner_hero['url']); ?>"
                        alt="<?php echo esc_attr($banner_hero['alt'] ?: 'Travel Destination Banner'); ?>"
                        class="absolute inset-0 h-full w-full object-cover object-center">
                    <?php else: ?>
                    <img src="/wp-content/uploads/2025/09/banner_section.webp" alt="Travel Destination Banner"
                        class="absolute inset-0 h-full w-full object-cover object-center">
                    <?php endif; ?>
                </figure>
            </div>
        </div>
        <div class="container z-10">
            <h1
                class="text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] lg:max-w-[300px] 2xl:text-[90px] font-main text-white">
                <?php echo $banner_title ? esc_html($banner_title) : 'USA, naturally.'; ?>
            </h1>
            <p class="text-white leading-[26px] xl:text-lg xl:leading-[28px] mt-4">
                <?php echo $banner_subtitle ? esc_html($banner_subtitle) : "We don't plan incredible around here. It just kind of happens."; ?>
            </p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button_primary">Learn More</a>
        </div>
    </section>

    <!-- Interactive Map Section -->
    <?php
	// echo do_shortcode('[usa_interactive_map height="700px" initial_zoom="4" center_lat="39.8283" center_lng="-98.5795"]');
	?>

    <!-- Dynamic Slider Sections from ACF Repeater -->
    <?php if ($slider_items && is_array($slider_items) && count($slider_items) > 0): ?>
    <?php foreach ($slider_items as $index => $slider): ?>
    <?php
			// Get data from repeater with fallbacks
			$slider_title = isset($slider['title']) ? $slider['title'] : '';
			$slider_subtitle = isset($slider['subtitle']) ? $slider['subtitle'] : '';
			$slider_button = isset($slider['button_direction']) ? $slider['button_direction'] : null;
			$slider_posts = isset($slider['item_slider']) ? $slider['item_slider'] : array();

			// Skip if no title and no posts
			if (empty($slider_title) && empty($slider_posts)) {
				continue;
			}

			// Set query vars for template part
			set_query_var('slide_config', array(
				'id' => 'slide_' . $index,
				'title' => $slider_title,
				'sub_title' => $slider_subtitle,
				'link' => $slider_button && isset($slider_button['url']) ? $slider_button['url'] : false,
				'link_text' => $slider_button && isset($slider_button['title']) ? $slider_button['title'] : 'Get inspired',
				'link_target' => $slider_button && isset($slider_button['target']) ? $slider_button['target'] : '',
				'posts' => $slider_posts,
				'loop' => false
			));

			// Load template part
			get_template_part('template-parts/layout/section-slide4', 'slide');
			?>
    <?php endforeach; ?>
    <?php else: ?>
    <!-- Fallback: Default sections if no ACF data -->
    <?php
		set_query_var('slide_config', [
			'id' => 'See_slide',
			'title' => "See",
			'sub_title' => "Natural wonders",
			'link' => "#",
			'posts' => array(),
			'loop' => false
		]);
		get_template_part('template-parts/layout/section-slide4', 'slide');

		set_query_var('slide_config', [
			'id' => 'Experience_slide',
			'title' => "Experience",
			'sub_title' => "Wellness",
			'link' => "#",
			'posts' => array(),
			'loop' => false
		]);
		get_template_part('template-parts/layout/section-slide4', 'slide');
		?>
    <?php endif; ?>

    <!-- Slider Video - Gallery Section -->
    <?php
	get_template_part('template-parts/layout/section-slideZoomCenter', 'dataSlider');
	?>

    <!-- Follow Us Section -->
    <?php
	// Pass gallery data to template
	if ($gallery && is_array($gallery) && count($gallery) > 0) {
		set_query_var('gallery_images', $gallery);
	}
	get_template_part('template-parts/layout/section-followUs', 'gallery');
	?>

</div>
<?php
get_footer();