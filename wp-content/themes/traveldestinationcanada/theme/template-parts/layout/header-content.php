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



    <!-- New Header  -->


    <div id="main-header" class="site-header bg-primary shadow-lg fixed top-0 z-20 w-full transition-transform">
        <nav class="header_main ">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img class="h-8 w-auto"
                                src="http://destinationcanada.local/wp-content/uploads/2025/06/ke-logo.webp" alt="Logo">
                        </div>
                    </div>

                    <!-- Main Menu Items -->
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-8">
                            <div class="relative group">
                                <button id="placesToGoBtn"
                                    class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium flex items-center transition-colors duration-200">
                                    Places to go
                                    <svg class="ml-1 h-4 w-4 transition-transform duration-200 group-hover:rotate-180"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                            </div>
                            <a href="#"
                                class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium transition-colors duration-200">Things
                                to do</a>
                            <a href="#"
                                class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium transition-colors duration-200">Plan
                                your trip</a>
                            <a href="#"
                                class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium transition-colors duration-200">Travel
                                packages</a>
                            <a href="#"
                                class="text-white hover:text-gray-200 px-3 py-2 text-sm font-medium transition-colors duration-200">Wildfire
                                guidance</a>
                        </div>
                    </div>

                    <!-- Search Icon -->
                    <div class="hidden md:block">
                        <button class="text-white hover:text-gray-200 p-2">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Mobile menu button -->
                    <div class="md:hidden">
                        <button id="mobileMenuBtn" class="text-white hover:text-gray-200 p-2">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>
    </div>
    <!-- Dropdown Menu -->
    <div id="dropdownMenu" class="hidden bg-white shadow-xl border-t-2 border-primary">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Tab Navigation -->
            <div class="mb-8">
                <nav class="flex space-x-1 bg-gray-100 p-1 rounded-lg">
                    <button class="tab-btn active px-6 py-3 text-sm font-medium rounded-md transition-all duration-200"
                        data-tab="western">Western Canada</button>
                    <button class="tab-btn px-6 py-3 text-sm font-medium rounded-md transition-all duration-200"
                        data-tab="prairies">The Prairies of Canada</button>
                    <button class="tab-btn px-6 py-3 text-sm font-medium rounded-md transition-all duration-200"
                        data-tab="central">Central Canada</button>
                    <button class="tab-btn px-6 py-3 text-sm font-medium rounded-md transition-all duration-200"
                        data-tab="atlantic">Atlantic Canada</button>
                    <button class="tab-btn px-6 py-3 text-sm font-medium rounded-md transition-all duration-200"
                        data-tab="northern">Northern Canada</button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div id="tabContent">
                <!-- Western Canada -->
                <div class="tab-content active" data-content="western">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/417142/pexels-photo-417142.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="British Columbia"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">British Columbia</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1796505/pexels-photo-1796505.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Alberta"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Alberta</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1616403/pexels-photo-1616403.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Vancouver"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Vancouver</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1004584/pexels-photo-1004584.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Victoria"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Victoria</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/2577274/pexels-photo-2577274.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Calgary"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Calgary</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1796505/pexels-photo-1796505.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Edmonton"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Edmonton</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- The Prairies of Canada -->
                <div class="tab-content" data-content="prairies">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1308940/pexels-photo-1308940.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Saskatchewan"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Saskatchewan</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1308940/pexels-photo-1308940.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Manitoba"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Manitoba</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1308940/pexels-photo-1308940.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Winnipeg"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Winnipeg</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Central Canada -->
                <div class="tab-content" data-content="central">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/374870/pexels-photo-374870.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Toronto"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Toronto</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1616403/pexels-photo-1616403.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Ottawa"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Ottawa</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/374815/pexels-photo-374815.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Montreal"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Montreal</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Atlantic Canada -->
                <div class="tab-content" data-content="atlantic">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1770809/pexels-photo-1770809.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Nova Scotia"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Nova Scotia</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1770809/pexels-photo-1770809.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="New Brunswick"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">New Brunswick</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1770809/pexels-photo-1770809.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Newfoundland"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Newfoundland</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Northern Canada -->
                <div class="tab-content" data-content="northern">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1666021/pexels-photo-1666021.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Yukon"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Yukon</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1666021/pexels-photo-1666021.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Northwest Territories"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Northwest Territories</h3>
                                </div>
                            </div>
                        </div>

                        <div class="destination-card group cursor-pointer">
                            <div
                                class="relative overflow-hidden rounded-lg shadow-md transition-all duration-300 group-hover:shadow-xl group-hover:-translate-y-1">
                                <img src="https://images.pexels.com/photos/1666021/pexels-photo-1666021.jpeg?auto=compress&cs=tinysrgb&w=400&h=250&fit=crop"
                                    alt="Nunavut"
                                    class="w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                <div class="absolute bottom-4 left-4">
                                    <h3 class="text-white text-xl font-semibold">Nunavut</h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View All Link -->
            <div class="mt-8 text-center">
                <a href="#"
                    class="inline-flex items-center px-6 py-3 border border-primary text-primary hover:bg-primary hover:text-white rounded-lg font-medium transition-colors duration-200">
                    View all destinations
                    <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobileMenu" class="hidden md:hidden fixed inset-0 z-50 bg-black bg-opacity-50">
        <div class="fixed inset-y-0 right-0 max-w-xs w-full bg-white shadow-xl">
            <div class="flex items-center justify-between p-4 border-b">
                <h2 class="text-lg font-semibold">Menu</h2>
                <button id="closeMobileMenu" class="p-2 text-gray-400 hover:text-gray-600">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>
            <div class="p-4 space-y-4">
                <a href="#" class="block text-gray-700 hover:text-primary font-medium">Places to go</a>
                <a href="#" class="block text-gray-700 hover:text-primary font-medium">Things to do</a>
                <a href="#" class="block text-gray-700 hover:text-primary font-medium">Plan your trip</a>
                <a href="#" class="block text-gray-700 hover:text-primary font-medium">Travel packages</a>
                <a href="#" class="block text-gray-700 hover:text-primary font-medium">Wildfire guidance</a>
            </div>
        </div>
    </div>


</header><!-- #masthead -->
