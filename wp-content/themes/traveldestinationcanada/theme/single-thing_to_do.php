<?php get_header(); ?>

<main id="primary" class="site-main">
    <article>
        <div class="pt-6">
            <div class=" px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto my-16 lg:my-24">
                <h1
                    class="break-words title_font_canada text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-primary 3xl:px-12 text-center">
                    <?php the_title(); ?>
                </h1>

                <div class="3xl:px-12 mt-8 first:mt-0">
                    <div class="aspect-16/5 banner_single_thing mt-8">
                        <?php if (_tw_post_thumbnail()):
						?>
                        <img alt="<?php _tw_post_thumbnail(); ?>" loading="lazy" decoding="async" data-nimg="fill"
                            class="object-cover" src="<?php echo esc_url($header_image['url']); ?>">
                        <?php endif; ?>

                    </div>
                </div>
                <div class="3xl:px-12 mt-4">
                    <div class="bg-white">
                        <?php
						// include breadcrumb
						get_template_part('template-parts/layout/breadcrumb', 'breadcumbData');
						?>
                    </div>
                </div>
            </div>
            <div
                class="wrapper_content_single px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto my-16 lg:my-24">
                <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] 3xl:pl-12">
                    <?php the_content(); ?>
                </div>
            </div>

            <?php
			// Query random Things To Do (max 9 items, exclude current post)
			$random_things_args = array(
				'post_type' => 'thing_to_do',
				'posts_per_page' => 9,
				'post_status' => 'publish',
				'post__not_in' => array(get_the_ID()), // Exclude current post
				'orderby' => 'rand', // Random order
			);
			$random_things_query = new WP_Query($random_things_args);
			$random_things_posts = array();

			if ($random_things_query->have_posts()) {
				while ($random_things_query->have_posts()) {
					$random_things_query->the_post();
					$random_things_posts[] = get_post();
				}
				wp_reset_postdata();
			}

			$slide_config = [
				'id' => 'adventures_slide',
				'title' => "Get inspired",
				'sub_title' => "Other articles you might enjoy",
				'link' => true,
				'loop' => false,
				'posts' => $random_things_posts, // Add random posts to config
			];
			set_query_var('slide_config', $slide_config);
			get_template_part('template-parts/layout/section-slide4', 'slide');
			?>
        </div>
    </article>
</main>

<?php get_footer(); ?>
