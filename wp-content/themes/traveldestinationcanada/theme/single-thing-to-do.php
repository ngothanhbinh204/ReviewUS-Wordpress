<?php get_header(); ?>

<main id="primary" class="site-main">
	<article>
		<div class="pt-6">
			<div class=" px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto my-16 lg:my-24">
				<h1
					class="break-words text-[11vw] font-bold leading-none sm:text-[50px] lg:text-[75px] 2xl:text-[90px] font-alt text-primary 3xl:px-12 text-center">
					<?php the_title(); ?>
				</h1>

				<div class="3xl:px-12 mt-8 first:mt-0">
					<div class="aspect-16/5 banner_single_thing mt-8">
						<?php if (_tw_post_thumbnail()):
						?>
							<img alt="<?php _tw_post_thumbnail(); ?>" loading="lazy" decoding="async"
								data-nimg="fill" class="object-cover"
								src="<?php echo esc_url($header_image['url']); ?>">
						<?php endif; ?>

					</div>
				</div>
				<div class="3xl:px-12 mt-4">
					<div class="bg-white">
						<div
							class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-2xl 2xl:mx-auto flex flex-wrap items-center gap-x-4 py-2">
							<div class="flex items-center gap-4">
								<p class="text-sm leading-[24px] xl:text-base xl:leading-[24px]">
									<a class="text-link underline gtm-breadcrumbs"
										href="<?php echo esc_url(get_field('breadcrumb_home_url')); ?>">
										<?php echo esc_html(get_field('breadcrumb_home_text')); ?>
									</a>
								</p>
								<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 320 512"
									class="w-2 fill-[#757575]" height="1em" width="1em"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
									</path>
								</svg>
							</div>
							<div class="flex items-center gap-4">
								<p class="text-sm leading-[24px] xl:text-base xl:leading-[24px]">
									<a class="text-link underline gtm-breadcrumbs"
										href="<?php echo esc_url(get_field('breadcrumb_category_url')); ?>">
										<?php echo esc_html(get_field('breadcrumb_category_text')); ?>
									</a>
								</p>
								<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 320 512"
									class="w-2 fill-[#757575]" height="1em" width="1em"
									xmlns="http://www.w3.org/2000/svg">
									<path
										d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z">
									</path>
								</svg>
							</div>
							<p class="text-sm leading-[24px] xl:text-base xl:leading-[24px]">
								<?php the_title(); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="wrapper_content_single px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto my-16 lg:my-24">
				<div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] 3xl:pl-12 xl:pr-44">
					<?php the_content(); ?>
				</div>
			</div>

			<?php
			$slide_config = [
				'id' => 'adventures_slide',
				'title' => get_field('slide_title'),
				'sub_title' => get_field('slide_sub_title'),
				'link' => true,
				'loop' => false,
			];
			set_query_var('slide_config', $slide_config);
			get_template_part('template-parts/layout/section-slide4', 'slide');
			?>
		</div>
	</article>
</main>

<?php get_footer(); ?>
