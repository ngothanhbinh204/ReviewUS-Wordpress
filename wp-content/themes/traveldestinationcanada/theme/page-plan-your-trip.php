<?php

/**
 * Template Name: Page Plan Your Trip
 */
get_header();

// Get ACF fields
$banner_images = get_field('banner_images');
$intro_section = get_field('intro_section_plan_trip');
$featured_pages = get_field('featured_pages');
$bottom_cta = get_field('bottom_cta_section');

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
									class="break-words title_font_canada text-center uppercase z-10 text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
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
<?php if ($intro_section): ?>
	<section class="section_c">
		<div class="container_c md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto">
			<div class=" gap-8 lg:grid lg:grid-cols-2 lg:items-start xl:grid-cols-3">
				<div class="lg:col-span-2 self-center xl:col-span-3">
					<?php if (!empty($intro_section['title'])): ?>
						<h2
							class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] last:mb-0 text-center text-primary">
							<?php echo esc_html($intro_section['title']); ?>
						</h2>
					<?php endif; ?>

					<?php if (!empty($intro_section['content'])): ?>
						<div
							class="text-base space-y-3 lg:space-y-4 leading-[26px] xl:text-lg xl:leading-[28px] mb-6 mt-6 last:mb-0">
							<?php echo wp_kses_post($intro_section['content']); ?>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>

<?php
// Featured Pages Section
if ($featured_pages && is_array($featured_pages)) :
	$page_index = 0;
	foreach ($featured_pages as $featured_page) :
		$page_index++;

		// Get page ID from page_link field
		// It should return post ID as integer with return_format='id'
		// But fallback to url_to_postid() if it returns URL string
		$page_id = $featured_page['page'];

		// If page_id is a URL string, convert to ID
		if (is_string($page_id) && filter_var($page_id, FILTER_VALIDATE_URL)) {
			$page_id = url_to_postid($page_id);
		}

		if (!$page_id) continue;

		// Get page data
		$page_title = get_the_title($page_id);
		$page_link = get_permalink($page_id);

		// Use custom excerpt or fallback to page excerpt
		$page_excerpt = !empty($featured_page['custom_excerpt'])
			? $featured_page['custom_excerpt']
			: get_the_excerpt($page_id);

		// Use custom image or fallback to featured image
		if (!empty($featured_page['custom_image'])) {
			$page_image = $featured_page['custom_image']['url'];
		} else {
			$page_image = get_the_post_thumbnail_url($page_id, 'large');
		}

		// Button text
		$button_text = !empty($featured_page['button_text'])
			? $featured_page['button_text']
			: 'Explore ' . $page_title;

		// Alternating layout
		$is_first = ($page_index == 1);
		$section_class = $is_first ? 'ThingToDoFirst' : 'ThingToDoSecond';
		$order_class = $is_first ? '' : 'order-2';
?>

		<section class="section_c <?php echo $section_class; ?>">
			<div>
				<div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
					<!-- Mobile Layout -->
					<div class="block lg:hidden text-justify">
						<h2
							class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
							<?php echo esc_html($page_title); ?>
						</h2>
						<?php if ($page_image): ?>
							<div class="relative mb-4 last:mb-0">
								<figure class="aspect-4/3 relative">
									<img src="<?php echo esc_url($page_image); ?>" alt="<?php echo esc_attr($page_title); ?>"
										class="object-cover h-full w-full" />
								</figure>
								<figcaption class="absolute bottom-0 right-0 px-4 py-2 text-xs text-white">Tourism
									<?php echo esc_html($page_title); ?></figcaption>
							</div>
						<?php endif; ?>
						<?php if ($page_excerpt): ?>
							<p class="text-base text-start leading-[26px] xl:text-lg xl:leading-[28px]">
								<?php echo wp_kses_post($page_excerpt); ?></p>
						<?php endif; ?>
						<a class="button_primary" href="<?php echo esc_url($page_link); ?>">
							<?php echo esc_html($button_text); ?>
						</a>
					</div>

					<!-- Desktop Layout -->
					<div class="hidden lg:grid lg:grid-cols-3 gap-8">
						<div class="col-span-2 <?php echo $order_class; ?>">
							<?php if ($page_image): ?>
								<figure class="aspect-4/3 relative">
									<img src="<?php echo esc_url($page_image); ?>" alt="<?php echo esc_attr($page_title); ?>"
										class="object-cover w-full h-full" />
								</figure>
							<?php endif; ?>
						</div>
						<div class="self-center col-span-1">
							<div>
								<h2
									class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
									<?php echo esc_html($page_title); ?></h2>
								<div class="text-base text-start leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
									<p class="mb-3 last:mb-0 empty:hidden">
										<?php echo wp_kses_post($page_excerpt); ?>
									</p>
								</div>
								<div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start">
									<a class="button_primary" href="<?php echo esc_url($page_link); ?>">
										<?php echo esc_html($button_text); ?>
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
		</section>

<?php
	endforeach;
endif;
?>


<!-- Bottom CTA Section -->
<?php if ($bottom_cta): ?>
	<section
		class=" min-h-screen lg:min-h-0 flex flex-col items-center justify-center lg:aspect-2/1 2xl:aspect-8/3 lg:h-auto relative z-0">
		<div class="relative z-20 w-full py-8">
			<div class="container px-4 md:px-16 2xl:px-0 max-w-1440 2xl:mx-auto">
				<div class="grid grid-cols-2 gap-x-8 md:grid-cols-12 lg:gap-x-12">
					<div class="col-span-2 md:col-span-12 lg:col-span-8 text-center lg:col-start-3">
						<?php if (!empty($bottom_cta['title'])): ?>
							<div>
								<h2
									class="break-words text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-main text-white">
									<?php echo esc_html($bottom_cta['title']); ?>
								</h2>
							</div>
						<?php endif; ?>
						<div class="mt-6">
							<div class="break-words text-[20px] font-bold leading-tight lg:text-[22px] text-white">
								<p class="mb-3 last:mb-0 empty:hidden text_base_white">
									<?php echo wp_kses_post($bottom_cta['content']); ?></p>
							</div>
						</div>

						<?php if (!empty($bottom_cta['button'])): ?>
							<div class="mt-9 lg:mt-8">
								<a class="button_primary partner" href="<?php echo esc_url($bottom_cta['button']['url']); ?>"
									<?php echo !empty($bottom_cta['button']['target']) ? 'target="' . esc_attr($bottom_cta['button']['target']) . '"' : ''; ?>>
									<span class="flex items-center gap-3">
										<?php echo esc_html($bottom_cta['button']['title']); ?>
										<svg width="32" height="32" fill="inherit" viewBox="0 0 16 16"
											xmlns="http://www.w3.org/2000/svg"
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
										</svg>
									</span>
								</a>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
		<div class="absolute inset-0 z-10 overflow-hidden">
			<div class="absolute inset-0 scale-125">
				<div class="absolute inset-0 will-change-transform"
					style="transform: translateY(-21.0453px) translateZ(0px);">
					<div class="absolute inset-0 z-20 bg-black opacity-25"></div>
					<?php if (!empty($bottom_cta['background_image'])): ?>
						<img alt="" loading="lazy" decoding="async" data-nimg="fill" class="h-full w-full object-cover"
							src="<?php echo esc_url($bottom_cta['background_image']['url']); ?>">
					<?php else: ?>
						<img alt="" loading="lazy" decoding="async" data-nimg="fill" class="h-full w-full object-cover"
							src="/wp-content/uploads/2025/09/banner_section.webp">
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>


<?php
get_footer();
?>
