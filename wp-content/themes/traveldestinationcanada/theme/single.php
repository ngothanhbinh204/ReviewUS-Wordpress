<?php get_header(); ?>

<main id="primary" class="site-main">
    <article>
        <div class="pt-6">
            <div class="px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto my-16 lg:my-24">
                <h1
                    class="break-words title_font_canada text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-primary 3xl:px-12 text-center">
                    <?php the_title(); ?>
                </h1>

                <div class="3xl:px-12 mt-8 first:mt-0">
                    <div class="lg:aspect-16/5 banner_single_post mt-8 relative overflow-hidden rounded-xl">
                        <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('full', [
            'class' => 'object-cover w-full h-full',
            'loading' => 'lazy',
            'decoding' => 'async',
        ]); ?>
                        <?php else : ?>
                        <img src="<?php echo esc_url('/wp-content/uploads/2025/09/banner_section.webp'); ?>"
                            alt="<?php echo esc_attr(get_the_title()); ?>" class="object-cover w-full h-full"
                            loading="lazy" decoding="async" />
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
                <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] 3xl:pl-12 prose max-w-none">
                    <?php the_content(); ?>
                </div>
            </div>

            <?php
            $related_args = [
                'post_type' => get_post_type(),
                'posts_per_page' => 9,
                'post_status' => 'publish',
                'post__not_in' => [get_the_ID()],
                'orderby' => 'rand',
            ];
            $related_query = new WP_Query($related_args);
            $related_posts = [];

            if ($related_query->have_posts()) {
                while ($related_query->have_posts()) {
                    $related_query->the_post();
                    $related_posts[] = get_post();
                }
                wp_reset_postdata();
            }

            $slide_config = [
                'id' => 'related_posts_slide',
                'title' => "You might also like",
                'sub_title' => "Other posts worth reading",
                'link' => true,
                'loop' => false,
                'posts' => $related_posts,
            ];

            set_query_var('slide_config', $slide_config);
            get_template_part('template-parts/layout/section-slide4', 'slide');
            ?>
        </div>
    </article>
</main>

<?php get_footer(); ?>