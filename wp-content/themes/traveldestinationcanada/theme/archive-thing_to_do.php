<?php


get_header();

?>

<?php

// include section-hero
$hero_banner_text = get_field('hero_banner_thing_to_do', 'option');



if ($hero_banner_text && is_array($hero_banner_text) && count($hero_banner_text) > 0):
?>
<section class="c-herobanner relative z-0">
    <?php if (count($hero_banner_text) > 1): ?>
    <div class="swiper heroSwiper">
        <div class="swiper-wrapper">
            <?php foreach ($hero_banner_text as $slide):
						$slide_image = isset($slide['image']) ? $slide['image'] : null;
						$slide_title = isset($slide['title']) ? $slide['title'] : '';
					?>
            <div class="swiper-slide">
                <div
                    class="relative flex flex-col min-h-[100vw] md:min-h-[90vw] lg:min-h-[640px] sm:py-16 justify-end py-12 md:py-16 pt-40 md:pt-52">
                    <?php if ($slide_image): ?>
                    <div class="absolute inset-0 overflow-hidden">
                        <div class="relative h-full w-full">
                            <figure class="relative h-full w-full">
                                <img src="<?php echo esc_url($slide_image['url']); ?>"
                                    alt="<?php echo esc_attr($slide_image['alt'] ? $slide_image['alt'] : $slide_title); ?>"
                                    class="object-cover w-full h-full" />
                            </figure>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="relative z-30 px-4 md:px-0 lg:px-8 xl:px-24">
                        <div class="c-container px-4 md:px-16 2xl:px-20 text-white">
                            <div class="c-grid grid grid-cols-2 gap-x-8 md:grid-cols-12 lg:gap-x-12">
                                <div
                                    class="text-left sm:px-10 3xl:col-span-6 col-span-2 md:col-span-12 lg:col-span-8 lg:px-0">
                                    <h1
                                        class="uppercase break-words text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
                                        <?php echo esc_html($slide_title); ?>
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <button class="swiper--hero-button-prev prev_cus_dark left-5 top-1/2 -translate-y-1/2 xl:left-20">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
        <button class="swiper--hero-button-next next_cus_dark right-5 top-1/2 -translate-y-1/2 xl:right-20">
            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 20 20" aria-hidden="true"
                height="1em" width="1em" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                    clip-rule="evenodd"></path>
            </svg>
        </button>
    </div>
    <?php else:
			$slide = $hero_banner_text[0];
			$slide_image = isset($slide['image']) ? $slide['image'] : null;
			$slide_title = isset($slide['title']) ? $slide['title'] : '';
		?>
    <div
        class="relative flex flex-col min-h-[100vw] md:min-h-[90vw] lg:min-h-[640px] sm:py-16 justify-end py-12 md:py-16 pt-40 md:pt-52">
        <?php if ($slide_image): ?>
        <div class="absolute inset-0 overflow-hidden">
            <div class="relative h-full w-full">
                <figure class="relative h-full w-full">
                    <img src="<?php echo esc_url($slide_image['url']); ?>"
                        alt="<?php echo esc_attr($slide_image['alt'] ? $slide_image['alt'] : $slide_title); ?>"
                        class="object-cover w-full h-full" />
                </figure>
            </div>
        </div>
        <?php endif; ?>
        <div class="relative z-30 px-4 md:px-0 lg:px-8 xl:px-24">
            <div class="c-container px-4 md:px-16 2xl:px-20 text-white">
                <div class="c-grid grid grid-cols-2 gap-x-8 md:grid-cols-12 lg:gap-x-12">
                    <div class="text-left sm:px-10 3xl:col-span-6 col-span-2 md:col-span-12 lg:col-span-8 lg:px-0">
                        <h1
                            class="uppercase break-words text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
                            <?php echo esc_html($slide_title); ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<?php
// include breadcrumb
get_template_part('template-parts/layout/breadcrumb', 'breadcumbData');
?>

<!-- Basic Content -->
<?php
$intro_content = get_field('intro_content_thing_to_do', 'option');


if ($intro_content):
?>
<section class="relative z-0 text-left my-16 lg:my-24">
    <div>
        <div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto">
            <div class="">
                <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
                    <?php echo wp_kses_post($intro_content); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php
// Get slider items from ACF (Thing To Do specific fields)
$slider_items = get_field('slider_item_thing_to_do', 'option');
if ($slider_items && is_array($slider_items) && count($slider_items) > 0):
	foreach ($slider_items as $index => $slider):
		$slider_title = isset($slider['title']) ? $slider['title'] : '';
		$slider_subtitle = isset($slider['subtitle']) ? $slider['subtitle'] : '';
		$slider_button = isset($slider['button_direction']) ? $slider['button_direction'] : null;

		// Lấy loại taxonomy được chọn
		$taxonomy_type = isset($slider['taxonomy_type_select']) ? $slider['taxonomy_type_select'] : 'thing_themes';

		// Lấy terms được chọn dựa trên taxonomy type
		$selected_terms = array();
		if ($taxonomy_type === 'thing_themes' && isset($slider['selected_themes'])) {
			$selected_terms = $slider['selected_themes'];
		} elseif ($taxonomy_type === 'provinces_territories' && isset($slider['selected_provinces'])) {
			$selected_terms = $slider['selected_provinces'];
		} elseif ($taxonomy_type === 'seasons' && isset($slider['selected_seasons'])) {
			$selected_terms = $slider['selected_seasons'];
		}

		if (empty($slider_subtitle) || empty($selected_terms)) {
			continue;
		}

		// Chuyển đổi terms thành format cho section-slide4 (giống phần Articles & guides)
		$term_items = [];
		if (is_array($selected_terms)) {
			foreach ($selected_terms as $term) {
				if (is_object($term) && isset($term->term_id)) {
					// Lấy thumbnail từ ACF cho term
					$thumbnail = get_field('thumbnail', $taxonomy_type . '_' . $term->term_id);
					$term_link = get_term_link($term);

					// Tạo object giống post object để section-slide4 hiểu được
					$term_item = new stdClass();
					$term_item->ID = $term->term_id;
					$term_item->post_title = $term->name;
					$term_item->post_excerpt = $term->description;
					$term_item->post_type = 'theme_term';
					// Link đến trang Articles với query parameter phù hợp
					$articles_page_url = home_url('/things-to-do/articles');
					$param_name = $taxonomy_type === 'thing_themes' ? 'themes' : ($taxonomy_type === 'provinces_territories' ? 'provinces' : 'seasons');
					$term_item->guid = add_query_arg($param_name, $term->slug, $articles_page_url);
					$term_item->thumbnail = $thumbnail;
					$term_items[] = $term_item;
				}
			}
		}

		// Nếu không có term items, bỏ qua slider này
		if (empty($term_items)) {
			continue;
		}

		set_query_var('slide_config', array(
			'id' => 'slide_' . $index,
			'title' => $slider_title,
			'sub_title' => $slider_subtitle,
			'link' => $slider_button && isset($slider_button['url']) ? $slider_button['url'] : false,
			'link_text' => $slider_button && isset($slider_button['title']) ? $slider_button['title'] : 'Get inspired',
			'link_target' => $slider_button && isset($slider_button['target']) ? $slider_button['target'] : '',
			'posts' => $term_items,
			'loop' => false
		));

		get_template_part('template-parts/layout/section-slide4', 'slide');
	endforeach;
endif;
?>

<?php

$raw_terms = get_terms([
	'taxonomy' => 'thing_themes',
	'hide_empty' => true,
]);

// Khởi tạo mảng
$theme_items = [];

if (!empty($raw_terms) && !is_wp_error($raw_terms)):
	foreach ($raw_terms as $term) {
		// Lấy thumbnail từ ACF cho term (sửa từ "thumnail" thành "thumbnail")
		$thumbnail = get_field('thumbnail', 'thing_themes_' . $term->term_id);
		$term_link = get_term_link($term);

		// Tạo object giống post object để section-slide4 hiểu được
		$theme_item = new stdClass();
		$theme_item->ID = $term->term_id;
		$theme_item->post_title = $term->name;
		$theme_item->post_excerpt = $term->description;
		$theme_item->post_type = 'theme_term';
		// Link đến trang Articles với query parameter
		$articles_page_url = home_url('/things-to-do/articles');
		$theme_item->guid = add_query_arg('themes', $term->slug, $articles_page_url);
		$theme_item->thumbnail = $thumbnail; // Thumbnail từ ACF		$theme_items[] = $theme_item;
	}
endif;

// Hiển thị slider nếu có themes
if (!empty($theme_items)):
	set_query_var('slide_config', [
		'id' => 'articles_guides_slide',
		'title' => "Articles & guides",
		'sub_title' => "Undeniably Canadian experiences",
		'link' => '/things-to-do/',
		'link_text' => 'Explore all',
		'posts' => $theme_items,
		'loop' => false,
	]);

	get_template_part('template-parts/layout/section-slide4', 'slide');
endif;
?>

<?php
get_template_part('template-parts/layout/section-followUs', 'slide');
?>


<?php
get_footer();
?>
