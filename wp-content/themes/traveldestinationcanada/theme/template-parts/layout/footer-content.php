<?php

/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

?>

<footer id="colophon">

	<!-- <?php if (is_active_sidebar('sidebar-1')) : ?>
		<aside role="complementary" aria-label="<?php esc_attr_e('Footer', '_tw'); ?>">
			<?php dynamic_sidebar('sidebar-1'); ?>
		</aside>
	<?php endif; ?>

	<?php if (has_nav_menu('menu-2')) : ?>
		<nav aria-label="<?php esc_attr_e('Footer Menu', '_tw'); ?>">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'menu-2',
					'menu_class'     => 'footer-menu',
					'depth'          => 1,
				)
			);
			?>
		</nav>
	<?php endif; ?>

	<div>
		<?php
		$_tw_blog_info = get_bloginfo('name');
		if (! empty($_tw_blog_info)) :
		?>
			<a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a>,
		<?php
		endif;

		printf(
			'<a href="%1$s">proudly powered by %2$s</a>.',
			esc_url(__('https://wordpress.org/', '_tw')),
			'WordPress'
		);
		?>
	</div> -->

	<div class="bg_gray">
		<div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto py-16 text-white">
			<div class="relative mx-auto mb-12 aspect-video w-full max-w-[200px]"><img alt="Logo" loading="lazy"
					decoding="async" data-nimg="fill" class="object-contain"
					style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
					src="http://destinationcanada.local/wp-content/uploads/2025/06/ke-logo.webp"></div>
			<div
				class="mb-11 flex flex-col items-center empty:hidden lg:flex-row lg:items-start lg:gap-x-6 xl:mb-14 lg:justify-between">
				<nav
					class="flex flex-wrap justify-center gap-x-5 empty:hidden lg:justify-start lg:only:mx-auto lg:only:justify-center">
					<a class="text-lg xl:text-xl block font-medium"
						href="https://businessevents.destinationcanada.com/">Business Events</a><a
						class="text-lg xl:text-xl block font-medium"
						href="https://www.destinationcanada.com/en">Corporate</a><a
						class="text-lg xl:text-xl block font-medium"
						href="https://media.destinationcanada.com/en-CA">Media</a><a
						class="text-lg xl:text-xl block font-medium"
						href="https://www.destinationcanada.com/en/csp">Trade</a><a
						class="text-lg xl:text-xl block font-medium"
						href="https://brand.destinationcanada.com/en/visual-library">Visual Library</a>
				</nav>
				<div class="mt-4 flex flex-col items-center lg:mt-0 lg:flex-row lg:flex-nowrap lg:items-baseline">
					<p class="text-lg xl:text-xl mr-4 text-center font-medium md:whitespace-nowrap md:text-left">Switch
						to traveller website from:</p><button type="button" role="combobox"
						aria-controls="radix-:Rabem:" aria-expanded="false" aria-autocomplete="none" dir="ltr"
						data-state="closed" data-placeholder="" aria-label="International websites list"
						class="text_juane inline-flex items-center whitespace-nowrap font-medium uppercase"><span>Select...</span><span
							aria-hidden="true" class="text-white"><svg stroke="currentColor" fill="currentColor"
								stroke-width="0" viewBox="0 0 20 20" aria-hidden="true" height="1em" width="1em"
								xmlns="http://www.w3.org/2000/svg">
								<path fill-rule="evenodd"
									d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
									clip-rule="evenodd"></path>
							</svg></span></button>
				</div>
			</div>
			<div class=" mb-11 flex flex-wrap justify-center gap-5 xl:mb-14">
				<?php for ($i = 0; $i < 3; $i++): ?>
					<a target="_blank" class="group icon_social" href="https://www.facebook.com/ExploreCanada/"><svg
							width="24px" height="24px" fill="#ffffff" aria-labelledby="FacebookIconIconTitleId"
							xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 23"
							class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
							<title id="FacebookIconIconTitleId">Facebook Icon</title>
							<path
								d="M9.85592 4.83063H7.59492C6.73217 4.83063 6.62309 5.1678 6.62309 6.05038V8.15272H9.96501L9.63776 11.812H6.62309V22.7798H2.30934V11.812H0.0483398V8.04363H2.30934V5.1678C2.30934 2.40105 3.70759 0.963135 6.94042 0.963135H9.84601V4.84055L9.85592 4.83063Z">
							</path>
						</svg>
					</a>
				<?php endfor; ?>
			</div>
			<div class="flex flex-col items-center gap-x-4 xl:flex-row xl:items-end xl:justify-between font-medium">
				<nav class="mb-5 flex flex-wrap justify-center gap-x-5 xl:mb-0 xl:justify-start"><a
						class="block text-[13px] xl:text-[14px]"
						href="https://travel.destinationcanada.com/en-us/privacy-policy">Privacy Policy</a><a
						class="block text-[13px] xl:text-[14px]"
						href="https://travel.destinationcanada.com/en-us/terms-use">Terms of Use</a><a
						class="block text-[13px] xl:text-[14px]"
						href="https://travel.destinationcanada.com/en-us/sitemap">Sitemap</a><a
						class="block text-[13px] xl:text-[14px]"
						href="https://travel.destinationcanada.com/en-us/web-accessibility-statement">Web
						Accessibility</a><button class="block text-[13px] xl:text-[14px] text-white">Cookies
						Settings</button></nav>
				<div
					class="flex flex-shrink-0 flex-col items-center gap-4 xl:ml-auto xl:flex-row xl:items-end xl:gap-8">
					<span class="text-[11px] xl:text-[14px]">An official site of Destination Canada 2025</span><img
						alt="Canada Logo" loading="lazy" width="145" height="35" decoding="async" data-nimg="1"
						class="relative mt-3 xl:-top-[5px] xl:mt-0" style="color:transparent"
						src="http://destinationcanada.local/wp-content/uploads/2025/06/canada-logo.webp">
				</div>
			</div>
		</div>
	</div>

</footer>