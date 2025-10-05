<?php
/**
 * Template part for displaying single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

?>


<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <header class="entry-header">
        <div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto">
            <div class="block lg:hidden">
                <h1
                    class=" entry-title break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
                    <?php the_title(); ?>
                </h1>
                <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
                    <p class="mb-3 last:mb-0 empty:hidden">
                        <?php the_excerpt(); ?>
                    </p>
                </div>
                <div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start"></div>
            </div>
            <div class="hidden gap-8 lg:grid lg:grid-cols-2 lg:items-start xl:grid-cols-3">
                <div class="self-center lg:col-span-2 xl:col-span-3">
                    <div data-projection-id="306" style="opacity: 1; transform: none;">
                        <h1
                            class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-black">
                            <?php the_title(); ?>
                        </h1>
                        <div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
                            <p class="mb-3 last:mb-0 empty:hidden">
                                <?php the_excerpt(); ?>
                            </p>
                        </div>
                        <div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start"></div>
                    </div>
                </div>
            </div>
        </div>
    </header><!-- .entry-header -->


    <div
        <?php _tw_content_class('c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto relative z-0 text-left my-16 lg:my-24'); ?>>
        <?php
		the_content();

		wp_link_pages(
			array(
				'before' => '<div>' . __('Pages:', '_tw'),
				'after'  => '</div>',
			)
		);
		?>
    </div><!-- .entry-content -->

    <?php if (get_edit_post_link()) : ?>
    <footer class="entry-footer">
        <?php
			edit_post_link(
				sprintf(
					wp_kses(
						/* translators: %s: Name of current post. Only visible to screen readers. */
						__('Edit <span class="sr-only">%s</span>', '_tw'),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					get_the_title()
				)
			);
			?>
    </footer><!-- .entry-footer -->
    <?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->