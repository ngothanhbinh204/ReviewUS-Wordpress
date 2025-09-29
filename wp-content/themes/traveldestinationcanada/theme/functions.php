<?php

/**
 * _tw functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package _tw
 */

if (! defined('_TW_VERSION')) {
	/*
	 * Set the theme’s version number.
	 *
	 * This is used primarily for cache busting. If you use `npm run bundle`
	 * to create your production build, the value below will be replaced in the
	 * generated zip file with a timestamp, converted to base 36.
	 */
	define('_TW_VERSION', '0.1.0');
}

if (! defined('_TW_TYPOGRAPHY_CLASSES')) {
	/*
	 * Set Tailwind Typography classes for the front end, block editor and
	 * classic editor using the constant below.
	 *
	 * For the front end, these classes are added by the `_tw_content_class`
	 * function. You will see that function used everywhere an `entry-content`
	 * or `page-content` class has been added to a wrapper element.
	 *
	 * For the block editor, these classes are converted to a JavaScript array
	 * and then used by the `./javascript/block-editor.js` file, which adds
	 * them to the appropriate elements in the block editor (and adds them
	 * again when they’re removed.)
	 *
	 * For the classic editor (and anything using TinyMCE, like Advanced Custom
	 * Fields), these classes are added to TinyMCE’s body class when it
	 * initializes.
	 */
	define(
		'_TW_TYPOGRAPHY_CLASSES',
		'prose prose-neutral max-w-none prose-a:text-primary'
	);
}

if (! function_exists('_tw_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function _tw_setup()
	{
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on _tw, use a find and replace
		 * to change '_tw' to the name of your theme in all the template files.
		 */
		load_theme_textdomain('_tw', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		// This theme uses wp_nav_menu() in two locations.
		register_nav_menus(
			array(
				'menu-1' => __('Primary', '_tw'),
				'menu-2' => __('Footer Menu', '_tw'),
			)
		);

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support('customize-selective-refresh-widgets');

		// Add support for editor styles.
		add_theme_support('editor-styles');

		// Enqueue editor styles.
		add_editor_style('style-editor.css');
		add_editor_style('style-editor-extra.css');

		// Add support for responsive embedded content.
		add_theme_support('responsive-embeds');

		// Add support for custom logo
		add_theme_support('custom-logo', array(
			'height'      => 50,
			'width'       => 200,
			'flex-height' => true,
			'flex-width'  => true,
		));

		// Remove support for block templates.
		remove_theme_support('block-templates');
	}
endif;
add_action('after_setup_theme', '_tw_setup');

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function _tw_widgets_init()
{
	register_sidebar(
		array(
			'name'          => __('Footer', '_tw'),
			'id'            => 'sidebar-1',
			'description'   => __('Add widgets here to appear in your footer.', '_tw'),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action('widgets_init', '_tw_widgets_init');

/**
 * Enqueue scripts and styles.
 */
function _tw_scripts()
{
	wp_enqueue_style('_tw-style', get_stylesheet_uri(), array(), _TW_VERSION);
	wp_enqueue_style('header-menu-style', get_template_directory_uri() . '/css/header-menu.css', array(), _TW_VERSION);
	wp_enqueue_script('jquery');

	wp_enqueue_script('_tw-script', get_template_directory_uri() . '/js/script.min.js', array('jquery'), _TW_VERSION, true);
	wp_localize_script('_tw-script', 'things_ajax', array(
		'ajax_url' => admin_url('admin-ajax.php'),
		'nonce'    => wp_create_nonce('things_filter_nonce'),
	));

	// Enqueue header menu script
	wp_enqueue_script('header-menu', get_template_directory_uri() . '/js/header-menu.js', array(), _TW_VERSION, true);

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}
}
add_action('wp_enqueue_scripts', '_tw_scripts');

/**
 * Enqueue the block editor script.
 */
function _tw_enqueue_block_editor_script()
{
	$current_screen = function_exists('get_current_screen') ? get_current_screen() : null;

	if (
		$current_screen &&
		$current_screen->is_block_editor() &&
		'widgets' !== $current_screen->id
	) {
		wp_enqueue_script(
			'_tw-editor',
			get_template_directory_uri() . '/js/block-editor.min.js',
			array(
				'wp-blocks',
				'wp-edit-post',
			),
			_TW_VERSION,
			true
		);
		wp_add_inline_script('_tw-editor', "tailwindTypographyClasses = '" . esc_attr(_TW_TYPOGRAPHY_CLASSES) . "'.split(' ');", 'before');
	}
}
add_action('enqueue_block_assets', '_tw_enqueue_block_editor_script');

/**
 * Add the Tailwind Typography classes to TinyMCE.
 *
 * @param array $settings TinyMCE settings.
 * @return array
 */
function _tw_tinymce_add_class($settings)
{
	$settings['body_class'] = _TW_TYPOGRAPHY_CLASSES;
	return $settings;
}
add_filter('tiny_mce_before_init', '_tw_tinymce_add_class');

/**
 * Limit the block editor to heading levels supported by Tailwind Typography.
 *
 * @param array  $args Array of arguments for registering a block type.
 * @param string $block_type Block type name including namespace.
 * @return array
 */
function _tw_modify_heading_levels($args, $block_type)
{
	if ('core/heading' !== $block_type) {
		return $args;
	}

	// Remove <h1>, <h5> and <h6>.
	$args['attributes']['levelOptions']['default'] = array(2, 3, 4);

	return $args;
}
add_filter('register_block_type_args', '_tw_modify_heading_levels', 10, 2);

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Custom Menu Walker for Mega Menu.
 */
require get_template_directory() . '/inc/class-custom-menu-walker.php';

/**
 * Mobile Menu Walker.
 */
require get_template_directory() . '/inc/class-mobile-menu-walker.php';

/**
 * Get menu item image with fallbacks
 */
function get_menu_item_image($menu_item_id, $object_id = null, $object_type = null, $size = 'medium')
{
	// Try to get image from ACF menu item field
	$menu_image = get_field('menu_item_image', 'nav_menu_' . $menu_item_id);

	// If no menu image and we have object info, try to get from the linked post/page
	if (!$menu_image && $object_id && $object_type) {
		if (in_array($object_type, ['destination', 'page', 'post', 'thing_to_do'])) {
			// Try featured image first
			$menu_image = get_the_post_thumbnail_url($object_id, $size);

			// If no featured image, try ACF field
			if (!$menu_image) {
				$menu_image = get_field('featured_image', $object_id);
				if (is_array($menu_image)) {
					$menu_image = $menu_image['sizes'][$size] ?? $menu_image['url'];
				}
			}
		}
	}

	// Fallback to default image
	if (!$menu_image) {
		$menu_image = get_template_directory_uri() . '/assets/images/default-destination.svg';
	}

	return $menu_image;
}

/**
 * Add body class for menu styling
 */
function add_mega_menu_body_class($classes)
{
	$classes[] = 'has-mega-menu';
	return $classes;
}
add_filter('body_class', 'add_mega_menu_body_class');

// include fonts
function custom_enqueue_tilt_effect()
{
	// CDN
	// wp_enqueue_script('vanilla-tilt', 'https://cdn.jsdelivr.net/npm/vanilla-tilt@1.7.2/dist/vanilla-tilt.min.js', [], null, true);
	// wp_enqueue_script('loader-spinner', 'https://cdnjs.cloudflare.com/ajax/libs/loaders.css/0.1.2/loaders.min.css', [], null, true);
	// wp_enqueue_script('aos-js', get_stylesheet_directory_uri() . '/libs/aos/aos.js', [], null, true);
	// add Swiper SLider
	wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', [], null, true);
	wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', [], null, 'all');
	// <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
	// <script src="https://cdn.jsdelivr.net/npm/globe.gl@2.24/dist/globe.gl.min.js"></script>

	wp_enqueue_script('three-js', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js', [], null, true);
	wp_enqueue_script('globe-gl-js', 'https://cdn.jsdelivr.net/npm/globe.gl@2.24.3/dist/globe.gl.min.js', [], null, true);

	// import GSAP
	wp_enqueue_script('gsap-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js', [], null, true);
	wp_enqueue_script('scrollTrigger-js', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js', [], null, true);

	// wp_enqueue_script('aos-css', get_stylesheet_directory_uri() . '/libs/aos/style-aos.css', [], null, true);
	// wp_enqueue_style('aos-css', 'https://unpkg.com/aos@2.3.1/dist/aos.css', [], null, 'all');


	// Load custom script
	// wp_enqueue_script('swiper-custom', get_stylesheet_directory_uri() . '/js/splide_cus.js', [], null, true);

	// wp_enqueue_script('custom-tilt', get_stylesheet_directory_uri() . '/js/custom-tilt.js', ['vanilla-tilt'], null, true);
	// wp_enqueue_script('custom-style', get_stylesheet_directory_uri() . '/inc/css/custom-style.css', null, true);
}
add_action('wp_enqueue_scripts', 'custom_enqueue_tilt_effect');

// function enqueue_things_filter_scripts()
// {
// 	wp_enqueue_script('things-filter-script', get_template_directory_uri() . '/js/script.min.js', ['jquery'], '0.1.0', true);

// 	wp_localize_script('things-filter-script', 'things_ajax', array(
// 		'ajax_url' => admin_url('admin-ajax.php'),
// 		'nonce' => wp_create_nonce('things_filter_nonce'),
// 	));
// }
// add_action('wp_enqueue_scripts', 'enqueue_things_filter_scripts');

// AJAX handler cho việc lọc things to do
function handle_filter_things_to_do()
{
	// Verify nonce
	if (!wp_verify_nonce($_POST['nonce'], 'things_filter_nonce')) {
		wp_send_json_error('Security check failed');
	}

	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$posts_per_page = 3;

	$filters = $_POST['filters'];

	// Build tax query
	$tax_query = array('relation' => 'AND');

	if (!empty($filters['territories'])) {
		$territory_values = array_column($filters['territories'], 'value');
		$tax_query[] = array(
			'taxonomy' => 'category-territory',
			'field' => 'slug',
			'terms' => $territory_values,
			'operator' => 'IN'
		);
	}

	if (!empty($filters['themes'])) {
		$theme_values = array_column($filters['themes'], 'value');
		$tax_query[] = array(
			'taxonomy' => 'category-theme',
			'field' => 'slug',
			'terms' => $theme_values,
			'operator' => 'IN'
		);
	}

	if (!empty($filters['seasons'])) {
		$season_values = array_column($filters['seasons'], 'value');
		$tax_query[] = array(
			'taxonomy' => 'category-season',
			'field' => 'slug',
			'terms' => $season_values,
			'operator' => 'IN'
		);
	}

	// Query things to do
	$args = array(
		'post_type' => 'thing-to-do',
		'posts_per_page' => $posts_per_page,
		'post_status' => 'publish',
		'paged' => $page,
		'tax_query' => $tax_query
	);

	$query = new WP_Query($args);

	ob_start();
	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			$thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'medium');
			if (!$thumbnail) {
				$thumbnail = 'http://destinationcanada.local/wp-content/uploads/2025/06/banner_section.webp';
			}
?>
			<div class="card relative h-full card_thing">
				<a class=" inset-0 mb-5 block overflow-hidden last:mb-0" href="#">
					<div class="relative">
						<figure class="relative aspect-square has_effect">
							<?php if ($thumbnail): ?>
								<img alt="" loading="lazy" decoding="async" class="object-cover anim--hover-image h-full"
									src="http://destinationcanada.local/wp-content/uploads/2025/06/banner_section.webp">
							<?php endif; ?>

							<figcaption class="absolute bottom-0 right-0 px-4 py-2 text-xs text-white">
								Destination BC</figcaption>
						</figure>
					</div>
				</a>
				<div class="mb-4 last:mb-0">
					<h3 class="break-words text-[22px]  font-bold leading-tight lg:text-[24px] 2xl:text-[28px]">
						<a class="primary2 group transition-all duration-150 ease-linear" href="<?php the_permalink(); ?>">
							<?php the_title(); ?></a>
					</h3>
					<!-- Display Content excerpt -->
					<!-- <div class="excerpt"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></div> -->

				</div>
			</div>
<?php
		}
	} else {
		echo '<div class="no-results">No things to do found matching your criteria :(.</div>';
	}

	$html = ob_get_clean();
	wp_reset_postdata();

	wp_send_json_success(array(
		'html' => $html,
		'count' => $query->found_posts,
		'max_num_pages' => $query->max_num_pages,
		'current_page' => $page,
		'current_shown' => ($page - 1) * $posts_per_page + $query->post_count,
	));
}

add_action('wp_ajax_filter_things_to_do', 'handle_filter_things_to_do');
add_action('wp_ajax_nopriv_filter_things_to_do', 'handle_filter_things_to_do');


// Custom Post Type: Destination
function create_destination_cpt()
{

	$labels = array(
		'name'                  => _x('Destinations', 'Post Type General Name', 'textdomain'),
		'singular_name'         => _x('Destination', 'Post Type Singular Name', 'textdomain'),
		'menu_name'             => __('Destinations', 'textdomain'),
		'name_admin_bar'        => __('Destination', 'textdomain'),
		'archives'              => __('Destination Archives', 'textdomain'),
		'attributes'            => __('Destination Attributes', 'textdomain'),
		'parent_item_colon'     => __('Parent Destination:', 'textdomain'),
		'all_items'             => __('All Destinations', 'textdomain'),
		'add_new_item'          => __('Add New Destination', 'textdomain'),
		'add_new'               => __('Add New', 'textdomain'),
		'new_item'              => __('New Destination', 'textdomain'),
		'edit_item'             => __('Edit Destination', 'textdomain'),
		'update_item'           => __('Update Destination', 'textdomain'),
		'view_item'             => __('View Destination', 'textdomain'),
		'view_items'            => __('View Destinations', 'textdomain'),
		'search_items'          => __('Search Destination', 'textdomain'),
		'not_found'             => __('Not found', 'textdomain'),
		'not_found_in_trash'    => __('Not found in Trash', 'textdomain'),
	);

	$args = array(
		'label'                 => __('Destination', 'textdomain'),
		'description'           => __('Tourist destinations like provinces, cities, or regions', 'textdomain'),
		'labels'                => $labels,
		'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
		'hierarchical'          => true, // Cho phép Parent/Child giống Page
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-location-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'rewrite'               => array(
			'slug' => 'places-to-go', // URL: /places-to-go/british-columbia/
			'with_front' => false
		),
		'show_in_rest'          => true, // Hỗ trợ Gutenberg + API
	);

	register_post_type('destination', $args);
}
add_action('init', 'create_destination_cpt', 0);


// Register Custom Post Type: Thing To Do
function create_thing_to_do_cpt()
{

	$labels = array(
		'name'                  => _x('Things To Do', 'Post Type General Name', 'textdomain'),
		'singular_name'         => _x('Thing To Do', 'Post Type Singular Name', 'textdomain'),
		'menu_name'             => __('Things To Do', 'textdomain'),
		'name_admin_bar'        => __('Thing To Do', 'textdomain'),
		'archives'              => __('Thing To Do Archives', 'textdomain'),
		'attributes'            => __('Thing To Do Attributes', 'textdomain'),
		'all_items'             => __('All Things To Do', 'textdomain'),
		'add_new_item'          => __('Add New Thing To Do', 'textdomain'),
		'add_new'               => __('Add New', 'textdomain'),
		'new_item'              => __('New Thing To Do', 'textdomain'),
		'edit_item'             => __('Edit Thing To Do', 'textdomain'),
		'update_item'           => __('Update Thing To Do', 'textdomain'),
		'view_item'             => __('View Thing To Do', 'textdomain'),
		'view_items'            => __('View Things To Do', 'textdomain'),
		'search_items'          => __('Search Thing To Do', 'textdomain'),
		'not_found'             => __('Not found', 'textdomain'),
		'not_found_in_trash'    => __('Not found in Trash', 'textdomain'),
	);

	$args = array(
		'label'                 => __('Thing To Do', 'textdomain'),
		'description'           => __('Activities, attractions, or experiences related to a destination', 'textdomain'),
		'labels'                => $labels,
		'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'revisions'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 6,
		'menu_icon'             => 'dashicons-tickets-alt',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'rewrite'               => array(
			'slug' => 'things-to-do', // URL: /things-to-do/festival-in-vancouver/
			'with_front' => false
		),
		'show_in_rest'          => true,
	);

	register_post_type('thing_to_do', $args);
}
add_action('init', 'create_thing_to_do_cpt', 0);


// Register section - feild for metabox
