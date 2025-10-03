<?php

/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default. Please note that
 * this is the WordPress construct of pages: specifically, posts with a post
 * type of `page`.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

get_header();
?>

<section id="primary">
    <main id="main">
        <section class="c-herobanner relative z-0">
            <div class="relative flex flex-col h-[70vw] md:h-[80vw] lg:h-[60vh] justify-end py-10 ">

                <?php
				$page_title = get_the_title();

				$featured_image_url = '/wp-content/uploads/2025/10/PowerPoint20_Crop-DC-AB-DJI_0027.webp';
				$featured_image_alt = '';

				if (has_post_thumbnail()) {
					// Lấy ID của ảnh
					$thumbnail_id = _tw_post_thumbnail();

					// Lấy URL ảnh với kích thước 'full'
					$image_attributes = wp_get_attachment_image_src($thumbnail_id, 'full');
					if ($image_attributes) {
						$featured_image_url = $image_attributes[0]; // [0] là URL
					}

					// Lấy Alt Text
					$featured_image_alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
				}
				?>

                <?php if ($featured_image_url) : ?>
                <div class="absolute inset-0 overflow-hidden">
                    <div class="relative h-full w-full">
                        <figure class="relative h-full w-full">
                            <img src="<?php echo esc_url($featured_image_url); ?>"
                                alt="<?php echo esc_attr($featured_image_alt ? $featured_image_alt : $page_title); ?>"
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
                                    class="uppercase title_font_canada break-words text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-white">
                                    <?php echo esc_html($page_title); ?>
                                </h1>
                            </div>
                        </div>
                    </div>
                </div>
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
        <?php
		/* Start the Loop */
		while (have_posts()) :
			the_post();

			get_template_part('template-parts/content/content', 'page');

			// If comments are open, or we have at least one comment, load
			// the comment template.
			if (comments_open() || get_comments_number()) {
				comments_template();
			}

		endwhile; // End of the loop.
		?>

    </main><!-- #main -->
</section><!-- #primary -->

<?php
get_footer();
