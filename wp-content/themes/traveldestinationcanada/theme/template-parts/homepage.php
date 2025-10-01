<?php

/**
 * Template Name: Homepage Template
 */

get_header()
?>

<div class="homepage">
    <!-- Banner Section -->
    <section
        class="section_hero c-herobanner__slide mb-[96px] relative flex flex-col min-h-[100vw] md:min-h-[90vw] lg:min-h-[640px] px-8 md:px-[104px] lg:px-[176px] sm:py-16 justify-center py-12 md:py-16 pt-40 md:pt-52">
        <div class="absolute inset-0 overflow-hidden">
            <div class="relative h-full w-full">
                <figure class="relative h-full w-full"><img src="/wp-content/uploads/2025/09/banner_section.webp"
                        alt="Travel Destination Canada Banner"
                        class="absolute inset-0 h-full w-full object-cover object-center">
                </figure>
            </div>
        </div>
        <div class="container z-10">
            <h1
                class=" text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] lg:max-w-[300px] 2xl:text-[90px] font-main text-white">
                Canada, naturally.</h1>
            <p class="text-white leading-[26px] xl:text-lg xl:leading-[28px] mt-4">We don't plan incredible around here.
                It just kind of happens.</p>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button_primary">Learn More</a>
        </div>
    </section>

    <?php
	// include earth-UI
	// get_template_part('template-parts/layout/earth-UI', 'data');

	// do_shortcode('[canada_globe height="800px" show_controls="true" show_legend="true"]')
	// get_template_part('template-parts/layout/territories-map-ninhthuan');

	?>

    <?php

	// echo do_shortcode('[canada_globe height="800px" show_controls="true" show_legend="true"]')

	?>

    <?php
	echo do_shortcode('[usa_interactive_map height="700px" initial_zoom="4" center_lat="39.8283" center_lng="-98.5795"]');
	?>

    <?php
	set_query_var('slide_config', [
		'id' => 'See_slide',
		'title' => "See",
		'sub_title' => "Natural wonders",
		'link' => "#",
	]);

	get_template_part('template-parts/layout/section-slide4', 'slide');
	?>

    <?php
	set_query_var('slide_config', [
		'id' => 'Experience_slide',
		// 'title' => get_field('slide_title'),
		'title' => "Experience",
		'sub_title' => "Wellness
",
		'link' => "#",
	]);

	get_template_part('template-parts/layout/section-slide4', 'slide');
	?>

    <?php
	// include slide4
	get_template_part('template-parts/layout/section-slideZoomCenter', 'dataSlider');
	?>


    <!--
    <section class="natural wellness relative z-0 c-carousel--num-slide-4 bg-light-grey py-8 lg:py-16 my-16 lg:my-24">
        <div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto">
            <div class="items-end justify-between md:flex">
                <div class="mb-2 md:mb-0 md:max-w-[70%] xl:max-w-none">
                    <p class="text-base leading-[26px] xl:text-lg xl:leading-[28px] text-mid-grey mb-1 font-semibold">
                        Experience</p>
                    <h3
                        class="break-words text-[36px] font-bold leading-tight lg:text-[48px] 2xl:text-[52px] text-primary !leading-none">
                        Wellness</h3>
                </div><a
                    class="inline-block cursor-pointer rounded-sm px-5 py-3 transition-colors text-primary group pl-0 pr-5 text-lg font-semibold text-left gtm-cta gtm-readmore md:!py-1 lg:!py-2 gtm-cta"
                    href="https://travel.destinationcanada.com/en-us/things-to-do/articles?themes=natural%20attractions"><span
                        class="flex items-center gap-3">Discover more<svg stroke="currentColor" fill="currentColor"
                            stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                            class="anim--default will-change-transform group-hover:translate-x-1" height="1.25rem"
                            width="1.25rem" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg></span></a>
            </div>
        </div>
        <div class="px-6 lg:px-12 mt-4 md:mt-6 lg:mt-7 xl:mt-12">
            <div class="swiper SliderNatural">
                <div class="swiper-wrapper">
                    <?php for ($i = 0; $i < 4; $i++): ?>
                    <div class="swiper-slide">
                        <div
                            class="flex flex-col box_natural relative overflow-hidden w-auto rounded h-[66vh] sm:h-[60vw] lg:h-[32vw]">
                            <div class="img_box relative h-full w-full">
                                <img class="object-cover"
                                    src="/wp-content/uploads/2025/09/DC2018_Clara_Amfo-03975-min-1.webp"
                                    alt="">
                            </div>
                            <div class="title">
                                <h4 class="break-words text-[20px] font-bold leading-tight lg:text-[22px] mt-2">
                                    <a class="hover:text-primary group transition-all duration-150 ease-linear"
                                        href="#">
                                        An adventure in the Canadian Rockies
                                    </a>
                                </h4>
                            </div>
                        </div>
                    </div>
                    <?php endfor; ?>
                </div>

            </div>

        </div>
    </section> -->

    <?php
	// include section-followUs
	get_template_part('template-parts/layout/section-followUs', 'gallery');
	?>

</div>
<?php
get_footer();
//