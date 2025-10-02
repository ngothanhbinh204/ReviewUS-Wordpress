<?php
get_header();

// Get ACF fields
$banner_images = get_field('banner_images');
$intro_title = get_field('intro_title');
$intro_content = get_field('intro_content');
$featured_things_to_do = get_field('featured_things_to_do');
$map_shortcode = get_field('map_shortcode');
$itinerary_section = get_field('itinerary_section');
$travel_packages_section = get_field('travel_packages_section');
$related_destinations = get_field('related_destination');
$slider_items = get_field('slider_item');

?>

<!-- Banner Section -->
<section class="section_sliderbanner">
	<div class="swiper bannerPlaceToGo">
		<div class="swiper-wrapper">
			<?php if ($banner_images && is_array($banner_images)): ?>
				<?php foreach ($banner_images as $image): ?>
					<div class="swiper-slide">
						<div
							class="relative flex flex-col min-h-[100vw] md:min-h-[90vw] lg:min-h-[640px] sm:py-16 justify-center py-16">
							<div class="box_img absolute inset-0 overflow-hidden">
								<img class="h-full w-full object-cover" src="<?php echo esc_url($image['url']); ?>"
									alt="<?php echo esc_attr($image['alt'] ?: get_the_title()); ?>">
							</div>
							<div class="absolute inset-0 z-10 bg-gradient-to-b from-[rgba(0,0,0,0)] to-[rgba(0,0,0,0.6)]"></div>
							<div class="lg:px-8 md:px-0 px-4 relative xl:px-24 z-30">
								<h1
									class="break-words text-center uppercase z-10 text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
									<?php the_title(); ?>
								</h1>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php else: ?>
				<!-- Fallback nếu không có banner images -->
				<div class="swiper-slide">
					<div
						class="relative flex flex-col min-h-[100vw] md:min-h-[90vw] lg:min-h-[640px] sm:py-16 justify-center py-16">
						<div class="box_img absolute inset-0 overflow-hidden">
							<?php if (has_post_thumbnail()): ?>
								<?php the_post_thumbnail('full', ['class' => 'h-full w-full object-cover']); ?>
							<?php else: ?>
								<img class="h-full w-full object-cover" src="/wp-content/uploads/2025/09/banner_section.webp"
									alt="">
							<?php endif; ?>
						</div>
						<div class="absolute inset-0 z-10 bg-gradient-to-b from-[rgba(0,0,0,0)] to-[rgba(0,0,0,0.6)]"></div>
						<div class="lg:px-8 md:px-0 px-4 relative xl:px-24 z-30">
							<h1
								class="break-words text-center uppercase z-10 text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
								<?php the_title(); ?>
							</h1>
						</div>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<?php if ($banner_images && count($banner_images) > 1): ?>
			<button class="swiper-placetogobanner-button-prev prev_cus_dark left-5 top-1/2 -translate-y-1/2 xl:left-20">
				<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
					height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd"
						d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
						clip-rule="evenodd"></path>
				</svg>
			</button>

			<button class="swiper-placetogobanner-button-next next_cus_dark right-5 top-1/2 -translate-y-1/2 xl:right-20">
				<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
					height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
					<path fill-rule="evenodd"
						d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
						clip-rule="evenodd"></path>
				</svg>
			</button>
		<?php endif; ?>
	</div>
</section>
<div class="bg-light-grey">
	<?php if (function_exists('rank_math_the_breadcrumbs')): ?>
		<div
			class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto flex flex-wrap items-center gap-x-4 py-2">
			<?php rank_math_the_breadcrumbs(); ?>
		</div>
	<?php endif; ?>
</div>



<!-- Intro Content Section -->
<?php if ($intro_title || $intro_content): ?>
	<section class="section_c">
		<div class="container_c md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto">
			<div class=" gap-8 lg:grid lg:grid-cols-2 lg:items-start xl:grid-cols-3">
				<div class="lg:col-span-2 self-center xl:col-span-3">
					<?php if ($intro_title): ?>
						<h2
							class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] last:mb-0 text-center text-primary">
							<?php echo esc_html($intro_title); ?>
						</h2>
					<?php endif; ?>

					<?php if ($intro_content): ?>
						<div
							class="text-base space-y-3 lg:space-y-4 leading-[26px] xl:text-lg xl:leading-[28px] mb-6 mt-6 last:mb-0">
							<?php echo wp_kses_post($intro_content); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<!-- Featured Child Destinations Section -->
<?php
$current_destination_id = get_the_ID();

// Query các Destination con của Destination hiện tại
$args = array(
	'post_type'      => 'destination',
	'posts_per_page' => 2, // lấy 2 cái thôi như yêu cầu
	'meta_query'     => array(
		array(
			'key'     => 'parent_destination',
			'value'   => $current_destination_id,
			'compare' => '=', // so sánh chính xác
		),
	),
);

$child_destinations = new WP_Query($args);

if ($child_destinations->have_posts()) :
	$destination_index = 0;
	while ($child_destinations->have_posts()) : $child_destinations->the_post();
		$destination_index++;
		$destination_title   = get_the_title();
		$destination_excerpt = get_the_excerpt();
		$destination_link    = get_permalink();
		$destination_image   = get_the_post_thumbnail_url(get_the_ID(), 'large');
		$destination_image_caption = get_post(get_the_ID())->post_excerpt; // caption


		// Alternating layout
		$is_first    = ($destination_index == 1);
		$section_class = $is_first ? 'ThingToDoFirst' : 'ThingToDoSecond';
		$image_class   = $is_first ? 'img_parallax_toRight' : 'img_parallax_toLeft';
		$order_class   = $is_first ? '' : 'order-2';
?>

		<section class="section_c <?php echo $section_class; ?>">
			<div>
				<div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
					<!-- Mobile Layout -->
					<div class="block lg:hidden text-justify">
						<h2
							class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
							<?php echo esc_html($destination_title); ?>
						</h2>
						<?php if ($destination_image): ?>
							<div class="relative mb-4 last:mb-0">
								<figure class="aspect-4/3 relative">
									<img src="<?php echo esc_url($destination_image); ?>"
										alt="<?php echo esc_attr($destination_title); ?>" class="object-cover h-full w-full" />
								</figure>
								<figcaption class="absolute bottom-0 right-0 px-4 py-2 text-xs text-white">Tourism
									<?php echo esc_html($destination_title); ?></figcaption>
							</div>
						<?php endif; ?>
						<?php if ($destination_excerpt): ?>
							<p class="text-base text-start leading-[26px] xl:text-lg xl:leading-[28px]">
								<?php echo wp_kses_post($destination_excerpt); ?></p>
						<?php endif; ?>
						<a class="button_primary" href="<?php echo esc_url($destination_link); ?>">
							Explore <?php echo esc_html($destination_title); ?>
						</a>
					</div>

					<!-- Desktop Layout -->
					<div class="hidden lg:grid lg:grid-cols-3 gap-8">
						<div class="col-span-2 <?php echo $order_class; ?>">
							<?php if ($destination_image): ?>
								<figure class="aspect-4/3 relative">
									<img src="<?php echo esc_url($destination_image); ?>"
										alt="<?php echo esc_attr($destination_title); ?>" class="object-cover w-full h-full" />
								</figure>
							<?php endif; ?>
						</div>
						<div class="self-center col-span-1">
							<div>
								<h2
									class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
									<?php echo esc_html($destination_title); ?></h2>
								<div class="text-base text-start leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
									<p class="mb-3 last:mb-0 empty:hidden">
										<?php echo wp_kses_post($destination_excerpt); ?>
									</p>
								</div>
								<div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start">
									<a class="button_primary" href="<?php echo esc_url($destination_link); ?>">
										Explore <?php echo esc_html($destination_title); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
		</section>

<?php
	endwhile;
	wp_reset_postdata();
endif;
?>


<!-- Map Section -->
<section class="section_c map-wrapper" data-map-id="46">
	<div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
		<div class="map-filters"></div>

		<?php if ($map_shortcode): ?>
			<?php echo do_shortcode($map_shortcode); ?>
		<?php else: ?>
			<?php echo do_shortcode('[mapster_wp_map id="46"]'); ?>
		<?php endif; ?>
	</div>
</section>


<?php if ($slider_items && is_array($slider_items) && count($slider_items) > 0): ?>
	<?php foreach ($slider_items as $index => $slider): ?>
		<?php
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
		get_template_part('template-parts/layout/section-slide3', 'slide');
		?>
	<?php endforeach; ?>

<?php endif; ?>

<section
	class=" min-h-screen lg:min-h-0 flex flex-col items-center justify-center lg:aspect-2/1 2xl:aspect-8/3 lg:h-auto relative z-0">
	<div class="relative z-20 w-full py-8">
		<div class="container px-4 md:px-16 2xl:px-0 max-w-1440 2xl:mx-auto">
			<div class="grid grid-cols-2 gap-x-8 md:grid-cols-12 lg:gap-x-12">
				<div class="col-span-2 md:col-span-12 lg:col-span-8 text-center lg:col-start-3">
					<div>
						<h2
							class="break-words text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-main text-white">
							Itineraries</h2>
					</div>
					<div class="mt-6">
						<div class="break-words text-[20px] font-bold leading-tight lg:text-[22px] text-white">
							<p class="mb-3 last:mb-0 empty:hidden text_base_white">Don’t miss the iconic spots and
								hidden gems. No
								matter where you go in British Columbia, there are amazing itinerary ideas for you to
								explore.</p>
						</div>
					</div>
					<div class="mt-9 lg:mt-8" style="opacity: 1; transform: none;">
						<a target="_blank" class="button_primary partner"
							href="https://www.hellobc.com/travel-ideas/road-trips/">
							<span class="flex items-center gap-3">Visit Hello BC<svg width="32" height="32"
									fill="inherit" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"
									class="relative bottom-[1px] h-3 w-3 fill-current">
									<g clip-path="url(#clip0_3048_45488)">
										<path
											d="M13.6837 11.4373H15.992V16.0004H0V0.00305176H4.53895V2.3193C3.97994 2.3193 3.4343 2.338 2.88867 2.31395H2.29756C2.31093 5.7389 2.31093 9.73289 2.29756 13.1578C2.29756 13.5719 2.29756 13.6921 2.29756 13.6921C5.72651 13.6815 10.2628 13.6815 13.6917 13.6921L13.6864 13.1525C13.6623 12.6022 13.681 12.0518 13.681 11.4373H13.6837Z">
										</path>
										<path
											d="M15.9999 7.99599H13.7051V4.25046C11.782 6.17399 9.92305 8.03339 8.06413 9.88746L6.42188 8.10286C8.25404 6.2755 10.2199 4.31191 12.1832 2.35365H8.01599V0H15.9999V7.99599Z">
										</path>
									</g>
									<defs>
										<clipPath id="clip0_3048_45488">
											<rect width="32" height="32"></rect>
										</clipPath>
									</defs>
								</svg></span></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="absolute inset-0 z-10 overflow-hidden">
		<div class="absolute inset-0 scale-125">
			<div class="absolute inset-0 will-change-transform"
				style="transform: translateY(-21.0453px) translateZ(0px);">
				<div class="absolute inset-0 z-20 bg-black opacity-25">
				</div>
				<img alt="" loading="lazy" decoding="async" data-nimg="fill" class="h-full w-full object-cover"
					src="/wp-content/uploads/2025/09/banner_section.webp">
			</div>
		</div>
	</div>
</section>

<section class="my-16 lg:my-24">
	<div class="container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto ">
		<header class="mb-6 lg:mb-12">
			<div>
				<p class="text-base leading-[26px] xl:text-lg xl:leading-[28px]">Travel packages</p>
				<div class="mb-4 flex flex-wrap items-center justify-between last:mb-0 lg:flex-nowrap lg:gap-6">
					<h2
						class="break-words text-[36px] font-bold leading-tight lg:text-[48px] 2xl:text-[52px] mt-2 first:mt-0">
						Great regional escapes</h2><a
						class="inline-block cursor-pointer rounded-sm px-5 py-3 transition-colors text-primary group pl-0 pr-5 text-lg font-semibold text-left gtm-cta gtm-readmore lg:whitespace-nowrap gtm-cta"
						href="https://travel.destinationcanada.com/en-us/offers?locations=british%20columbia"><span
							class="flex items-center gap-3">View Packages<svg stroke="currentColor" fill="currentColor"
								stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
								class="anim--default will-change-transform group-hover:translate-x-1" height="1.25rem"
								width="1.25rem" xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd"
									d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
									clip-rule="evenodd"></path>
							</svg></span></a>
				</div>
			</div>
		</header>
		<div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-x-4 gap-y-8 md:gap-8">
			<?php for ($i = 0; $i < 3; $i++): ?>
				<div class="relative h-full">
					<a target="_blank" class="inset-0 mb-5 block overflow-hidden last:mb-0" href="https://landsby.ca">
						<div class="relative">
							<figure class="relative figure_img aspect-square">
								<img alt="" loading="lazy" decoding="async" class="object-cover h-full"
									src="/wp-content/uploads/2025/09/banner_section.webp">
							</figure>
							<div class="absolute left-0 top-0 w-full">
								<img alt="Landsby" loading="lazy" decoding="async" class="!h-[80px] !w-auto p-2 bg-white"
									src="/wp-content/uploads/2025/09/banner_section.webp">
							</div>
						</div>
					</a>
					<div class="overflow-hidden max-h-[300px]">
						<div class="mb-4 last:mb-0">
							<h3 class="break-words font-bold leading-tight text-[22px] lg:text-[24px] 2xl:text-[28px]">
								<a class="primary2 group transition-all duration-150 ease-linear" target="_blank"
									href="https://landsby.ca/tours/a-taste-of-indigenous-culture-journey-through-bcs-interior/?utm_source=DC&amp;utm_medium=portal&amp;utm_campaign=foodanddrink">A
									Taste of Indigenous Culture: Journey
									Through BC’s Interior<span class="whitespace-nowrap">&nbsp;&nbsp;<svg width="0.7em"
											height="0.7em" fill="inherit" viewBox="0 0 16 16"
											xmlns="http://www.w3.org/2000/svg"
											class="inline transition-all duration-150 ease-linear group-hover:fill-tertiary">
											<title id="ExternalLinkIconId">External Link Title</title>
											<g clip-path="url(#clip0_3048_45488)">
												<path
													d="M13.6837 11.4373H15.992V16.0004H0V0.00305176H4.53895V2.3193C3.97994 2.3193 3.4343 2.338 2.88867 2.31395H2.29756C2.31093 5.7389 2.31093 9.73289 2.29756 13.1578C2.29756 13.5719 2.29756 13.6921 2.29756 13.6921C5.72651 13.6815 10.2628 13.6815 13.6917 13.6921L13.6864 13.1525C13.6623 12.6022 13.681 12.0518 13.681 11.4373H13.6837Z">
												</path>
												<path
													d="M15.9999 7.99599H13.7051V4.25046C11.782 6.17399 9.92305 8.03339 8.06413 9.88746L6.42188 8.10286C8.25404 6.2755 10.2199 4.31191 12.1832 2.35365H8.01599V0H15.9999V7.99599Z">
												</path>
											</g>
											<defs>
												<clipPath id="clip0_3048_45488">
													<rect width="0.7em" height="0.7em"></rect>
												</clipPath>
											</defs>
										</svg></span></a>
							</h3>
							<p class="p_base_gray">From USD $3,225 per person
							</p>
							<p class="p_base_gray">5 Days</p>
						</div>
					</div>
				</div>
			<?php endfor; ?>

		</div>
	</div>
</section>

<?php
// include section-followUs
get_template_part('template-parts/layout/section-followUs', 'gallery');
?>

<?php
get_footer();
?>
