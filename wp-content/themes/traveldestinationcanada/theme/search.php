<?php

/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package _tw
 */

get_header();
?>

<section id="primary" class="py-12 lg:py-20 bg-gray-50">
	<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
		<main id="main">

			<?php if (have_posts()) : ?>

				<!-- Search Header -->
				<header class="mb-8 lg:mb-12">
					<div class="max-w-3xl">
						<h1 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
							<?php esc_html_e('Search Results', '_tw'); ?>
						</h1>
						<p class="text-lg text-gray-600">
							<?php
							printf(
								esc_html(_n(
									'Found %1$s result for "%2$s"',
									'Found %1$s results for "%2$s"',
									$wp_query->found_posts,
									'_tw'
								)),
								'<span class="font-semibold text-primary">' . number_format_i18n($wp_query->found_posts) . '</span>',
								'<span class="font-semibold text-gray-900">' . get_search_query() . '</span>'
							);
							?>
						</p>

						<!-- Search Form -->
						<div class="mt-6">
							<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="relative">
								<input type="search"
									name="s"
									value="<?php echo get_search_query(); ?>"
									placeholder="<?php esc_attr_e('Refine your search...', '_tw'); ?>"
									class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm">
								<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
									<svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
									</svg>
								</div>
							</form>
						</div>
					</div>
				</header>

				<!-- Search Results Grid -->
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
					<?php
					// Start the Loop.
					while (have_posts()) :
						the_post();
						$post_type = get_post_type();
					?>
						<!-- Search Result Card -->
						<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden'); ?>>
							<?php if (has_post_thumbnail()) : ?>
								<div class="aspect-w-16 aspect-h-9 overflow-hidden">
									<a href="<?php the_permalink(); ?>" class="block">
										<?php the_post_thumbnail('medium_large', array('class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300')); ?>
									</a>
								</div>
							<?php endif; ?>

							<div class="p-6">
								<!-- Post Type Badge -->
								<div class="flex items-center justify-between mb-3">
									<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary text-white">
										<?php echo esc_html(ucfirst($post_type)); ?>
									</span>
									<time datetime="<?php echo get_the_date('c'); ?>" class="text-sm text-gray-500">
										<?php echo get_the_date(); ?>
									</time>
								</div>

								<!-- Title -->
								<h2 class="text-xl font-bold text-gray-900 mb-3 hover:text-primary transition-colors duration-200">
									<a href="<?php the_permalink(); ?>" class="line-clamp-2">
										<?php the_title(); ?>
									</a>
								</h2>

								<!-- Excerpt -->
								<?php if (has_excerpt() || get_the_content()) : ?>
									<div class="text-gray-600 text-sm mb-4 line-clamp-3">
										<?php echo wp_trim_words(get_the_excerpt() ?: get_the_content(), 20, '...'); ?>
									</div>
								<?php endif; ?>

								<!-- Read More Link -->
								<a href="<?php the_permalink(); ?>"
									class="inline-flex items-center text-primary font-semibold text-sm hover:text-red-700 transition-colors duration-200">
									<?php esc_html_e('Read More', '_tw'); ?>
									<svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
									</svg>
								</a>
							</div>
						</article>
					<?php
					endwhile;
					?>
				</div>

				<!-- Pagination -->
				<div class="mt-12">
					<?php
					the_posts_pagination(array(
						'mid_size' => 2,
						'prev_text' => sprintf(
							'<svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg> %s',
							__('Previous', '_tw')
						),
						'next_text' => sprintf(
							'%s <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
							__('Next', '_tw')
						),
						'class' => 'flex justify-center items-center space-x-2',
					));
					?>
				</div>

			<?php else : ?>

				<!-- No Results -->
				<div class="max-w-2xl mx-auto text-center py-12">
					<div class="mb-8">
						<svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
						</svg>
					</div>

					<h1 class="text-3xl font-bold text-gray-900 mb-4">
						<?php esc_html_e('No results found', '_tw'); ?>
					</h1>

					<p class="text-lg text-gray-600 mb-8">
						<?php
						printf(
							esc_html__('Sorry, no results were found for "%s". Please try again with different keywords.', '_tw'),
							'<span class="font-semibold">' . get_search_query() . '</span>'
						);
						?>
					</p>

					<!-- Search Form -->
					<div class="max-w-xl mx-auto">
						<form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>" class="relative">
							<input type="search"
								name="s"
								placeholder="<?php esc_attr_e('Try another search...', '_tw'); ?>"
								class="w-full pl-12 pr-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent shadow-sm text-lg">
							<div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
								<svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
								</svg>
							</div>
						</form>
					</div>

					<!-- Suggestions -->
					<div class="mt-12">
						<h3 class="text-lg font-semibold text-gray-900 mb-4">
							<?php esc_html_e('Suggestions:', '_tw'); ?>
						</h3>
						<ul class="text-left max-w-md mx-auto space-y-2 text-gray-600">
							<li class="flex items-start">
								<svg class="h-5 w-5 text-primary mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
								</svg>
								<?php esc_html_e('Check your spelling', '_tw'); ?>
							</li>
							<li class="flex items-start">
								<svg class="h-5 w-5 text-primary mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
								</svg>
								<?php esc_html_e('Try more general keywords', '_tw'); ?>
							</li>
							<li class="flex items-start">
								<svg class="h-5 w-5 text-primary mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
									<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
								</svg>
								<?php esc_html_e('Use different keywords', '_tw'); ?>
							</li>
						</ul>
					</div>
				</div>

			<?php endif; ?>
		</main><!-- #main -->
	</div>
</section><!-- #primary -->

<?php
get_footer();
