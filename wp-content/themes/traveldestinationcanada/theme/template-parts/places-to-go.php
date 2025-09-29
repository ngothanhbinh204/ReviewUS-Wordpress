<?php

/**
 * Template Name: Places To Go Single Template
 */

get_header()
?>
<section class="section_sliderbanner">
	<div class="swiper bannerPlaceToGo">
		<div class="swiper-wrapper ">
			<?php for ($i = 0; $i < 3; $i++): ?>
				<div class="swiper-slide">
					<div
						class="relative flex flex-col min-h-[100vw] md:min-h-[90vw] lg:min-h-[640px] sm:py-16 justify-center py-16">
						<div class="box_img absolute inset-0 overflow-hidden">
							<img class="h-full w-full object-cover" src="/wp-content/uploads/2025/09/banner_section.webp"
								alt="">
						</div>
						<div class="absolute inset-0 z-10 bg-gradient-to-b from-[rgba(0,0,0,0)] to-[rgba(0,0,0,0.6)]"></div>
						<div class="lg:px-8 md:px-0 px-4 relative xl:px-24 z-30">
							<h1
								class="break-words text-center uppercase z-10 text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
								Brish Columbia</h1>
						</div>

					</div>
				</div>

			<?php endfor; ?>
		</div>


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
	</div>


</section>

<section class="section_c">
	<div class="container_c md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto">
		<div class=" gap-8 lg:grid lg:grid-cols-2 lg:items-start xl:grid-cols-3">
			<div class="lg:col-span-2 self-center xl:col-span-3">
				<h2
					class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] last:mb-0 text-center text-primary">
					Where nature is nurtured
				</h2>
				<div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6 mt-6 last:mb-0">
					<p class="mb-3 last:mb-0 empty:hidden">Don’t challenge British Columbia to a nature contest. The
						westernmost
						province has an unfair advantage: this is where the Pacific Ocean is met by bustling city and
						towering
						forest; where soaring snow-capped mountains give way to picturesque valleys, and where lively
						urban
						life
						blends in beautifully with the nature that surrounds it
						<br>
						<br>
						Don’t challenge British Columbia to a nature contest. The
						westernmost
						province has an unfair advantage: this is where the Pacific Ocean is met by bustling city and
						towering
						forest; where soaring snow-capped mountains give way to picturesque valleys, and where lively
						urban
						life
						blends in beautifully with the nature that surrounds it
					</p>
				</div>
			</div>
		</div>

	</div>
</section>

<section class="section_c">
	<div>
		<div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
			<div class="block lg:hidden">
				<h2
					class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
					Vancouver</h2>
				<div class="relative mb-4 last:mb-0">
					<figure class="aspect-4/3 relative"><img alt="Two cyclists biking outdoors in Vancouver "
							loading="lazy" decoding="async" data-nimg="fill" class="object-cover" sizes="100vw"
							src="/wp-content/uploads/2025/09/vancouver_tile_credit_julian_apse.webp"
							style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
					</figure>
					<figcaption class="absolute bottom-0 right-0 px-4 py-2 text-xs text-white">Julian Apse</figcaption>
				</div>
				<div class="text-base text-start leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
					<p class="mb-3 last:mb-0 empty:hidden"><span>Where the skyline of glass towers reflects the beauty
							of the surrounding ocean and coastal mountains. Where you can swim at the beach and explore
							a mountain in the same day. Where the cuisine is world-class and the nightlife electrifying.
							Vancouver is definitely spectacular by nature.</span></p>
				</div>
				<div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start"><a
						class="inline-block cursor-pointer rounded-sm px-5 py-3 transition-colors bg-primary hover:bg-link font-medium text-white text-left gtm-cta"
						href="https://travel.destinationcanada.com/en-us/places-to-go/british-columbia/vancouver"><span
							class="flex items-center gap-3">Explore Vancouver</span></a></div>
			</div>
			<div class="hidden gap-8 lg:grid lg:grid-cols-2 lg:items-start xl:grid-cols-3">
				<div class="relative mb-4 overflow-hidden last:mb-0 xl:col-span-2" style="opacity: 1;">
					<div class="image-parallax-wrapper">
						<div class="scale-110 img_parallax_toRight">
							<div class="will-change-transform"
								style="transform: translateX(-9.15344px) translateZ(0px);">
								<figure class="aspect-4/3 relative"><img
										alt="Two cyclists biking outdoors in Vancouver " loading="lazy" decoding="async"
										data-nimg="fill" class="object-cover"
										sizes="(max-width: 767px) 90vw, (max-width: 1023px) 50vw, 55vw"
										src="/wp-content/uploads/2025/09/vancouver_tile_credit_julian_apse.webp"
										style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
								</figure>
							</div>
						</div>
					</div>
					<figcaption class="absolute bottom-0 right-0 px-4 py-2 text-xs text-white">Julian Apse</figcaption>
				</div>
				<div class="self-center lg:col-span-1 xl:col-span-1">
					<div style="opacity: 1; transform: none;">
						<h2
							class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
							Vancouver</h2>
						<div class="text-base text-start leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
							<p class="mb-3 last:mb-0 empty:hidden"><span>Where the skyline of glass towers reflects the
									beauty of the surrounding ocean and coastal mountains. Where you can swim at the
									beach and explore a mountain in the same day. Where the cuisine is world-class and
									the nightlife electrifying. Vancouver is definitely spectacular by nature.</span>
							</p>
						</div>
						<div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start">
							<a class="button_primary"
								href="https://travel.destinationcanada.com/en-us/places-to-go/british-columbia/vancouver"><span
									class="flex items-center gap-3">Explore Vancouver</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section_c">
	<div>
		<div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
			<div class="block lg:hidden">
				<h2
					class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
					Vancouver</h2>
				<div class="relative mb-4 last:mb-0">
					<figure class="aspect-4/3 relative"><img alt="Two cyclists biking outdoors in Vancouver "
							loading="lazy" decoding="async" data-nimg="fill" class="object-cover" sizes="100vw"
							src="/wp-content/uploads/2025/09/vancouver_tile_credit_julian_apse.webp"
							style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
					</figure>
					<figcaption class="absolute bottom-0 right-0 px-4 py-2 text-xs text-white">Julian Apse</figcaption>
				</div>
				<div class="text-base text-start leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
					<p class="mb-3 last:mb-0 empty:hidden"><span>Where the skyline of glass towers reflects the beauty
							of the surrounding ocean and coastal mountains. Where you can swim at the beach and explore
							a mountain in the same day. Where the cuisine is world-class and the nightlife electrifying.
							Vancouver is definitely spectacular by nature.</span></p>
				</div>
				<div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start"><a
						class="inline-block cursor-pointer rounded-sm px-5 py-3 transition-colors bg-primary hover:bg-link font-medium text-white text-left gtm-cta"
						href="https://travel.destinationcanada.com/en-us/places-to-go/british-columbia/vancouver"><span
							class="flex items-center gap-3">Explore Vancouver</span></a></div>
			</div>
			<div class="hidden gap-8 lg:grid lg:grid-cols-2 lg:items-start xl:grid-cols-3">
				<div class="relative mb-4 overflow-hidden last:mb-0 xl:col-span-2 order-2" style="opacity: 1;">
					<div class="image-parallax-wrapper">
						<div class="scale-110 img_parallax_toLeft">
							<div class="will-change-transform"
								style="transform: translateX(-9.15344px) translateZ(0px);">
								<figure class="aspect-4/3 relative"><img
										alt="Two cyclists biking outdoors in Vancouver " loading="lazy" decoding="async"
										data-nimg="fill" class="object-cover"
										src="/wp-content/uploads/2025/09/vancouver_tile_credit_julian_apse.webp"
										style="position: absolute; height: 100%; width: 100%; inset: 0px; color: transparent;">
								</figure>
							</div>
						</div>
					</div>
					<figcaption class="absolute bottom-0 right-0 px-4 py-2 text-xs text-white">Julian Apse</figcaption>
				</div>
				<div class="self-center lg:col-span-1 xl:col-span-1">
					<div style="opacity: 1; transform: none;">
						<h2
							class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
							Vancouver</h2>
						<div class="text-base text-start leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
							<p class="mb-3 last:mb-0 empty:hidden"><span>Where the skyline of glass towers reflects the
									beauty of the surrounding ocean and coastal mountains. Where you can swim at the
									beach and explore a mountain in the same day. Where the cuisine is world-class and
									the nightlife electrifying. Vancouver is definitely spectacular by nature.</span>
							</p>
						</div>
						<div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start">
							<a class="button_primary"
								href="https://travel.destinationcanada.com/en-us/places-to-go/british-columbia/vancouver"><span
									class="flex items-center gap-3">Explore Vancouver</span></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="section_c map-wrapper" data-map-id="46">
	<div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">

		<div class="map-filters"></div>

		<?php echo do_shortcode('[mapster_wp_map id="46"]'); ?>

	</div>
</section>


<?php
set_query_var('slide_config', [
	'id' => 'thingsToDo_slide',
	// 'title' => get_field('slide_title'),
	'title' => "Things To Do",
	'sub_title' => "Inspiring local experiences",
	'link' => "#",
	// 'items' => "Title Demo", // repeater ACF
	'loop' => true,
]);

get_template_part('template-parts/layout/section-slide3', 'slide');
?>

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
set_query_var('slide_config', [
	'id' => 'thingsToDo_slide',
	// 'title' => get_field('slide_title'),
	'title' => "Title Demo",
	'sub_title' => "Sub Title Demo",
	'link' => "#",
	// 'items' => "Title Demo", // repeater ACF
	'loop' => true,
	'perView' => 3,
]);

get_template_part('template-parts/layout/section-slide3', 'slide');
?>


<?php
// include section-followUs
get_template_part('template-parts/layout/section-followUs', 'gallery');
?>

<?php
get_footer();
?>