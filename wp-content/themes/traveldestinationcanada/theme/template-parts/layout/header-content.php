<?php

/**
 * Template part for displaying the header content
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package _tw
 */

?>

<header id="masthead">

    <!-- <div>
		<?php
		if (is_front_page()) :
		?>
			<h1><?php bloginfo('name'); ?></h1>
			<?php
		else :
			?>
			<p><a href="<?php echo esc_url(home_url('/')); ?>" rel="home"><?php bloginfo('name'); ?></a></p>
			<?php
		endif;

		$_tw_description = get_bloginfo('description', 'display');
		if ($_tw_description || is_customize_preview()) :
			?>
			<p><?php echo $_tw_description; ?></p>
		<?php endif; ?>
	</div> -->

    <!-- <nav id="site-navigation" aria-label="<?php esc_attr_e('Main Navigation', '_tw'); ?>">
		<button aria-controls="primary-menu" aria-expanded="false"><?php esc_html_e('Primary Menu', '_tw'); ?></button>

		<?php
		wp_nav_menu(
			array(
				'theme_location' => 'menu-1',
				'menu_id'        => 'primary-menu',
				'items_wrap'     => '<ul id="%1$s" class="%2$s" aria-label="submenu">%3$s</ul>',
			)
		);
		?>
	</nav> -->
    <!-- New Header with Mega Menu -->
    <div id="main-header" class="site-header bg-primary shadow-lg fixed top-0 z-50 w-full transition-transform">
        <nav class="header_main relative">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
                                <?php
								$custom_logo_id = get_theme_mod('custom_logo');
								if ($custom_logo_id) {
									$logo = wp_get_attachment_image_src($custom_logo_id, 'full');
									echo '<img class="h-8 w-auto" src="' . esc_url($logo[0]) . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
								} else {
									// Fallback to site name if no logo
									echo '<span class="text-white text-xl font-bold">' . esc_html(get_bloginfo('name')) . '</span>';
								}
								?>
                            </a>
                        </div>
                    </div>

                    <!-- Desktop Main Menu -->
                    <div class="hidden lg:block">
                        <div class="flex items-center space-x-1">
                            <?php
							wp_nav_menu(array(
								'theme_location' => 'menu-1',
								'container' => false,
								'menu_class' => 'flex items-center space-x-1 list-none',
								'walker' => new Custom_Menu_Walker(),
								'depth' => 3,
							));
							?>
                        </div>
                    </div>

                    <!-- Search Icon -->
                    <div class="hidden lg:block">
                        <button id="searchBtn"
                            class="text-white hover:text-gray-200 p-2 rounded-full transition-colors duration-200">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="lg:hidden">
                        <button id="mobileMenuBtn"
                            class="text-white hover:text-gray-200 p-2 rounded-md transition-colors duration-200">
                            <svg id="menuIcon" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                            <svg id="closeIcon" class="h-6 w-6 hidden" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="lg:hidden bg-primary border-t border-red-600 hidden">
                <div class="px-4 py-4 space-y-1">
                    <?php
					wp_nav_menu(array(
						'theme_location' => 'menu-1',
						'container' => false,
						'menu_class' => 'mobile-menu space-y-1',
						'walker' => new Mobile_Menu_Walker(),
						'depth' => 3,
					));
					?>

                    <!-- Mobile Search -->
                    <div class="pt-4 border-t border-red-600">
                        <button
                            class="text-white hover:text-gray-200 flex items-center w-full px-3 py-2 text-sm font-medium">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Search
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Search Overlay -->
        <div id="searchOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden">
            <div class="flex items-start justify-center pt-20">
                <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-2xl mx-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Search</h3>
                        <button id="closeSearch" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <form role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="relative">
                            <input type="search" name="s" placeholder="Search destinations, activities..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



</header><!-- #masthead -->