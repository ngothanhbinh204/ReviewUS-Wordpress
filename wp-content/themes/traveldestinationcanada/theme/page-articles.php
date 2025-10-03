<?php

/**
 * Template Name: Articles - Things To Do
 * Description: Page template for filtering Things To Do by taxonomy
 */

get_header();

?>

<?php
$article_image   = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: get_template_directory_uri() . '/wp-content/uploads/2025/09/banner_section.webp'; // Fallback image
$article_image_caption = get_post(get_the_ID())->post_excerpt; // Lấy caption của ảnh đại diện
$article_title   = get_the_title();
?>
<section class="c-herobanner relative z-0">
    <div
        class="c-herobanner__slide relative flex flex-col h-[100vh] md:h-[90vh] lg:h-[50vh] sm:py-16 justify-end py-12 md:py-16 pt-40 md:pt-52">
        <div class="absolute inset-0 overflow-hidden ">
            <div class="relative h-full w-full">
                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20"></div>
                <figure class="relative h-full w-full -z-[1]">
                    <img src="<?= esc_url($article_image) ?>" alt="<?= esc_attr($article_image_caption) ?>"
                        class="object-cover w-full h-full" />
                </figure>
            </div>
        </div>
        <div class="relative z-30 px-4 md:px-0 lg:px-8 xl:px-24">
            <div class="c-container px-4 md:px-16 2xl:px-20 text-white">
                <div class="c-grid grid grid-cols-2 gap-x-8 md:grid-cols-12 lg:gap-x-12">
                    <div class="text-left sm:px-10 3xl:col-span-6 col-span-2 md:col-span-12 lg:col-span-8 lg:px-0">
                        <h1
                            class="uppercase break-words text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
                            <?= esc_html($article_title) ?>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php
// include breadcrumb
get_template_part('template-parts/layout/breadcrumb', 'breadcumbData');
?>

<?php
// Include Articles Filter Section with dynamic taxonomy configuration
get_template_part('template-parts/layout/articles', 'dataArticles');
?>

<?php
get_template_part('template-parts/layout/section-followUs', 'slide');
?>

<?php
get_footer();
?>
