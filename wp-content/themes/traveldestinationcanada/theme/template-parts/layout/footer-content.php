<?php

/**
 * Template part for displaying the footer content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

// Get ACF options
$socials = get_field('socials', 'option');
$footer_logo = get_field('footer_logo', 'option');
$footer_top_links = get_field('footer_top_links', 'option');
$international_websites = get_field('footer_international_websites', 'option');
$footer_bottom = get_field('footer_bottom_section', 'option');

?>

<footer id="colophon" class="bg_gray">
    <div class="c-container px-4 md:px-16 2xl:px-20 3xl:px-0 max-w-screen-xl xl:mx-auto py-16 text-white">

        <!-- Footer Logo -->
        <?php if ($footer_logo) : ?>
        <div class="relative mx-auto mb-12 aspect-video w-full max-w-[200px]">
            <img alt="<?php echo esc_attr($footer_logo['alt'] ?: get_bloginfo('name')); ?>" loading="lazy"
                decoding="async" class="object-contain"
                style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                src="<?php echo esc_url($footer_logo['url']); ?>">
        </div>
        <?php endif; ?>

        <!-- Top Links Section + International Websites Switcher -->
        <div
            class="mb-11 flex flex-col items-center empty:hidden lg:flex-row lg:items-start lg:gap-x-6 xl:mb-14 lg:justify-between">

            <!-- Top Links (ACF or WordPress Menu) -->
            <?php if (!empty($footer_top_links['show_top_links']) && !empty($footer_top_links['top_links_list'])) : ?>
            <nav
                class="flex flex-wrap justify-center gap-x-5 empty:hidden lg:justify-start lg:only:mx-auto lg:only:justify-center">
                <?php foreach ($footer_top_links['top_links_list'] as $link) : ?>
                <a class="text-lg xl:text-xl block font-medium" href="<?php echo esc_url($link['link_url']); ?>"
                    <?php if (!empty($link['link_target'])) : ?>target="_blank" rel="noopener noreferrer"
                    <?php endif; ?>>
                    <?php echo esc_html($link['link_text']); ?>
                </a>
                <?php endforeach; ?>
            </nav>
            <?php elseif (has_nav_menu('menu-2')) : ?>
            <!-- Fallback to WordPress Menu -->
            <?php
				wp_nav_menu(array(
					'theme_location' => 'menu-2',
					'container' => 'nav',
					'container_class' => 'flex flex-wrap justify-center gap-x-5 empty:hidden lg:justify-start lg:only:mx-auto lg:only:justify-center',
					'menu_class' => '',
					'items_wrap' => '%3$s',
					'link_before' => '<span class="text-lg xl:text-xl block font-medium">',
					'link_after' => '</span>',
					'depth' => 1,
				));
				?>
            <?php endif; ?>

            <!-- International Websites Switcher -->
            <?php if (!empty($international_websites['show_website_switcher']) && !empty($international_websites['international_websites'])) : ?>
            <div class="mt-4 flex flex-col items-center lg:mt-0 lg:flex-row lg:flex-nowrap lg:items-baseline">
                <?php if (!empty($international_websites['switcher_label'])) : ?>
                <p class="text-lg xl:text-xl mr-4 text-center font-medium md:whitespace-nowrap md:text-left">
                    <?php echo esc_html($international_websites['switcher_label']); ?>
                </p>
                <?php endif; ?>

                <div class="relative inline-block">
                    <select
                        class="text_juane inline-flex items-center whitespace-nowrap font-medium uppercase bg-transparent border border-white/20 rounded px-4 py-2 pr-8 cursor-pointer hover:border-white/40 transition-colors"
                        aria-label="International websites list"
                        onchange="if(this.value) window.location.href = this.value">
                        <option value="">
                            <?php echo esc_html($international_websites['switcher_placeholder'] ?: 'Select...'); ?>
                        </option>
                        <?php foreach ($international_websites['international_websites'] as $website) : ?>
                        <option value="<?php echo esc_url($website['website_url']); ?>">
                            <?php echo esc_html($website['website_name']); ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Social Media Links -->
        <?php if (!empty($socials)) : ?>
        <div class="mb-11 flex flex-wrap justify-center gap-5 xl:mb-14">
            <div class="flex gap-4 text-canadianRedFlag">
                <?php if (!empty($socials) && is_array($socials)): ?>
                <?php foreach ($socials as $social): ?>
                <?php
							$social_url = isset($social['url']) ? $social['url'] : '#';
							$social_icon = isset($social['icon']) ? $social['icon'] : null;

							// Skip if no URL
							if (empty($social_url) || $social_url === '#') {
								continue;
							}
							?>
                <a target="_blank" class="group icon_social" href="<?php echo esc_url($social_url); ?>">
                    <?php if ($social_icon && isset($social_icon['url'])): ?>
                    <img src="<?php echo esc_url($social_icon['url']); ?>"
                        alt="<?php echo esc_attr($social_icon['alt'] ?: 'Social Media'); ?>" width="24" height="24"
                        class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                    <?php else: ?>
                    <!-- Fallback: Default Facebook SVG -->
                    <svg width="24px" height="24px" fill="#ffffff" aria-labelledby="SocialIconTitle"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 23"
                        class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                        <title id="SocialIconTitle">Social Media</title>
                        <path
                            d="M9.85592 4.83063H7.59492C6.73217 4.83063 6.62309 5.1678 6.62309 6.05038V8.15272H9.96501L9.63776 11.812H6.62309V22.7798H2.30934V11.812H0.0483398V8.04363H2.30934V5.1678C2.30934 2.40105 3.70759 0.963135 6.94042 0.963135H9.84601V4.84055L9.85592 4.83063Z">
                        </path>
                    </svg>
                    <?php endif; ?>
                </a>
                <?php endforeach; ?>
                <?php else: ?>
                <!-- Fallback: Default social icons if no ACF data -->
                <a target="_blank" class="group icon_social" href="https://www.facebook.com/">
                    <svg width="24px" height="24px" fill="#ffffff" aria-labelledby="FacebookIconTitle"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 10 23"
                        class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                        <title id="FacebookIconTitle">Facebook</title>
                        <path
                            d="M9.85592 4.83063H7.59492C6.73217 4.83063 6.62309 5.1678 6.62309 6.05038V8.15272H9.96501L9.63776 11.812H6.62309V22.7798H2.30934V11.812H0.0483398V8.04363H2.30934V5.1678C2.30934 2.40105 3.70759 0.963135 6.94042 0.963135H9.84601V4.84055L9.85592 4.83063Z">
                        </path>
                    </svg>
                </a>
                <a target="_blank" class="group icon_social" href="https://www.instagram.com/">
                    <svg width="24px" height="24px" fill="#ffffff" aria-labelledby="InstagramIconTitle"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                        <title id="InstagramIconTitle">Instagram</title>
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z" />
                    </svg>
                </a>
                <a target="_blank" class="group icon_social" href="https://www.twitter.com/">
                    <svg width="24px" height="24px" fill="#ffffff" aria-labelledby="TwitterIconTitle"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        class="anim--default relative z-20 will-change-transform scale-100 group-hover:scale-90 group-hover:delay-0">
                        <title id="TwitterIconTitle">Twitter</title>
                        <path
                            d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                    </svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Bottom Section -->
        <div class="flex flex-col items-center gap-x-4 xl:flex-row xl:items-end xl:justify-between font-medium">

            <!-- Footer Menu (WordPress Menu - Legal/Utility Links) -->
            <?php if (has_nav_menu('menu-2')) : ?>
            <nav class="mb-5 flex flex-wrap justify-center gap-x-5 xl:mb-0 xl:justify-start list-none">
                <?php
					wp_nav_menu(array(
						'theme_location' => 'menu-2',
						'container' => false,
						'menu_class' => '',
						'items_wrap' => '%3$s',
						'link_before' => '<span class="block text-[13px] xl:text-[14px]">',
						'link_after' => '</span>',
						'depth' => 1,
					));
					?>

                <!-- Cookies Settings Button -->
                <?php if (!empty($footer_bottom['cookies_text'])) : ?>
                <button class="block text-[13px] xl:text-[14px] text-white hover:text-yellow-400 transition-colors"
                    onclick="/* Add your cookie consent function here */" type="button">
                    <?php echo esc_html($footer_bottom['cookies_text']); ?>
                </button>
                <?php endif; ?>
            </nav>
            <?php endif; ?>

            <!-- Official Text + Canada Logo -->
            <div class="flex flex-shrink-0 flex-col items-center gap-4 xl:ml-auto xl:flex-row xl:items-end xl:gap-8">
                <?php if (!empty($footer_bottom['official_text'])) : ?>
                <span class="text-[11px] xl:text-[14px]">
                    <?php echo esc_html($footer_bottom['official_text']); ?>
                </span>
                <?php endif; ?>

                <?php if (!empty($footer_bottom['canada_logo'])) : ?>
                <img alt="<?php echo esc_attr($footer_bottom['canada_logo']['alt'] ?: 'Canada Logo'); ?>" loading="lazy"
                    width="145" height="35" decoding="async" class="relative mt-3 xl:-top-[5px] xl:mt-0"
                    style="color:transparent" src="<?php echo esc_url($footer_bottom['canada_logo']['url']); ?>">
                <?php endif; ?>
            </div>
        </div>
    </div>

</footer>
