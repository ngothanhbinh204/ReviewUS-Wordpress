<?php

/**
 * Template Name: Coming to USA Template
 */

get_header();
$post_id = get_the_ID();

// Get ACF fields
$hero_banner = get_field('hero_banner_coming', $post_id);
$intro_section = get_field('intro_section_coming', $post_id);
$sections = get_field('sections', $post_id);
$bottom_cta = get_field('bottom_cta_coming', $post_id);
?>

<!-- Banner Hero  -->
<?php
if ($hero_banner):
	$dataBanner = [
		[
			'title' => $hero_banner['title'] ?? 'Coming to USA',
			'sub_title' => $hero_banner['sub_title'] ?? '',
			'image' => $hero_banner['image'] ?? [
				'url' => get_template_directory_uri() . '/assets/images/default-banner.jpg',
				'alt' => 'Coming to USA'
			],
		],
	];
	get_template_part('template-parts/layout/section-banner-coming', null, [
		'slides' => $dataBanner,
	]);
endif;
?>

<?php get_template_part('template-parts/layout/breadcrumb', 'dataBreadcrumb'); ?>

<?php get_template_part('template-parts/layout/sections/section-subnav'); ?>

<!-- Introduction Section -->
<?php if ($intro_section && !empty($intro_section['title'])): ?>
	<section class="relative z-0 text-left my-16 lg:my-24">
		<div class="px-4 md:px-16 2xl:px-20 min_1600_px0 max-w-screen-xl xl:mx-auto">
			<div class="block lg:hidden">
				<h2
					class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-primary">
					<?= esc_html($intro_section['title']) ?>
				</h2>
				<?php if (!empty($intro_section['sub_title'])): ?>
					<div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
						<?= $intro_section['sub_title'] ?>
					</div>
				<?php endif; ?>
				<?php if (!empty($intro_section['content'])): ?>
					<div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
						<?= $intro_section['content'] ?>
					</div>
				<?php endif; ?>
				<?php if (!empty($intro_section['button'])): ?>
					<div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start">
						<a target="<?= esc_attr($intro_section['button']['target'] ?? '_self') ?>" class="button_primary"
							href="<?= esc_url($intro_section['button']['url']) ?>">
							<span class="flex items-center gap-3">
								<?= esc_html($intro_section['button']['title']) ?>
							</span>
						</a>
					</div>
				<?php endif; ?>
			</div>
			<div class="hidden gap-8 lg:grid lg:grid-cols-2 lg:items-start xl:grid-cols-3">
				<div class="self-center lg:col-span-2 xl:col-span-3">
					<h2
						class="break-words text-[32px] font-bold leading-tight lg:text-[38px] 2xl:text-[42px] after:content-[''] after:block after:w-[50px] after:mt-[0.3em] after:mb-[0.5em] after:h-[3px] after:bg-secondary mb-6 last:mb-0 text-left text-primary">
						<?= esc_html($intro_section['title']) ?>
					</h2>
					<?php if (!empty($intro_section['sub_title'])): ?>
						<div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
							<?= $intro_section['sub_title'] ?>
						</div>
					<?php endif; ?>
					<?php if (!empty($intro_section['content'])): ?>
						<div class="text-base leading-[26px] xl:text-lg xl:leading-[28px] mb-6 last:mb-0">
							<?= $intro_section['content'] ?>
						</div>
					<?php endif; ?>
					<?php if (!empty($intro_section['button'])): ?>
						<div class="mb-6 flex flex-wrap items-start gap-4 last:mb-0 justify-start">
							<a target="<?= esc_attr($intro_section['button']['target'] ?? '_self') ?>" class="button_primary"
								href="<?= esc_url($intro_section['button']['url']) ?>">
								<span class="flex items-center gap-3">
									<?= esc_html($intro_section['button']['title']) ?>
								</span>
							</a>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</section>
<?php endif; ?>


<!-- Dynamic Content Sections -->
<?php if (have_rows('sections')): ?>
	<?php while (have_rows('sections')): the_row();
		$layout = get_sub_field('layout');

		$section_data = [
			'title' => get_sub_field('title'),
			'slug' => sanitize_title(get_sub_field('title')),
		];

		if ($layout === 'basic-content' || $layout === 'basic-content-largeImage') {
			$basic_content = get_sub_field('content_basic_layout');
			$section_data['sub_title'] = $basic_content['sub_title'] ?? '';
			$section_data['content'] = $basic_content['content'] ?? '';
			$section_data['image'] = $basic_content['image'] ?? null;
		}

		if ($layout === 'basic-content-two-column') {
			$two_col_content = get_sub_field('content_2_column_layout');
			$section_data['sub_title'] = $two_col_content['sub_title'] ?? '';
			$section_data['content_left'] = $two_col_content['content_left'] ?? '';
			$section_data['content_right'] = $two_col_content['content_right'] ?? '';
		}

		if ($layout === 'basic-grid-content') {
			$section_data['items'] = get_sub_field('content_grid_layout') ?? array();
		}

		get_template_part("template-parts/layout/sections/{$layout}", null, ['section' => $section_data]);
	endwhile; ?>
<?php endif; ?>

<!-- Bottom CTA Section -->
<?php
if ($bottom_cta && !empty($bottom_cta['title'])):
	get_template_part('template-parts/layout/sections/basic-content-bottom', null, [
		'title'   => $bottom_cta['title'] ?? 'Coming to USA',
		'content' => $bottom_cta['content'] ?? '',
		'button'  => $bottom_cta['button'] ?? null,
	]);
endif;
?>


<?php

get_footer();
?>
