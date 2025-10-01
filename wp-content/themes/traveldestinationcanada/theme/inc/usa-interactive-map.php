<?php

/**
 * Interactive Map Component for USA Destinations
 * Multi-site architecture: Country > Region > State > City > Attraction
 *
 * @package _tw
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register ACF Fields for Destination Post Type
 */
function register_destination_map_fields()
{
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_destination_map',
            'title' => 'Destination Map Data',
            'fields' => array(
                // Destination Level (hierarchy)
                array(
                    'key' => 'field_destination_level',
                    'label' => 'Destination Level',
                    'name' => 'destination_level',
                    'type' => 'select',
                    'choices' => array(
                        'country' => 'Country (USA)',
                        'region' => 'Region (West, South, Midwest, Northeast)',
                        'state' => 'State',
                        'city' => 'City',
                        'attraction' => 'Attraction/POI',
                    ),
                    'default_value' => 'state',
                    'instructions' => 'Select the hierarchical level of this destination',
                    'required' => 1,
                ),

                // Parent Destination
                array(
                    'key' => 'field_parent_destination',
                    'label' => 'Parent Destination',
                    'name' => 'parent_destination',
                    'type' => 'post_object',
                    'post_type' => array('destination'),
                    'allow_null' => 1,
                    'instructions' => 'Select parent destination (e.g., California for Los Angeles)',
                ),

                // Featured Flag
                array(
                    'key' => 'field_is_featured',
                    'label' => 'Featured on Main Map',
                    'name' => 'is_featured',
                    'type' => 'true_false',
                    'default_value' => 0,
                    'instructions' => 'Show this destination in the permanent sidebar on the main map',
                    'ui' => 1,
                ),

                // Featured Order
                array(
                    'key' => 'field_featured_order',
                    'label' => 'Featured Order',
                    'name' => 'featured_order',
                    'type' => 'number',
                    'default_value' => 0,
                    'instructions' => 'Order in featured list (lower numbers appear first)',
                    'conditional_logic' => array(
                        array(
                            array(
                                'field' => 'field_is_featured',
                                'operator' => '==',
                                'value' => '1',
                            ),
                        ),
                    ),
                ),

                // GeoJSON Boundary
                array(
                    'key' => 'field_map_geojson',
                    'label' => 'GeoJSON Boundary',
                    'name' => 'map_geojson',
                    'type' => 'textarea',
                    'instructions' => 'GeoJSON data for territory boundary (Polygon or MultiPolygon)',
                    'required' => 0,
                    'rows' => 4,
                ),

                // Center Coordinates
                array(
                    'key' => 'field_map_coordinates',
                    'label' => 'Center Coordinates',
                    'name' => 'map_coordinates',
                    'type' => 'text',
                    'instructions' => 'Format: longitude,latitude (e.g., -118.2437,34.0522)',
                    'required' => 0,
                    'placeholder' => '-118.2437,34.0522',
                ),

                // Zoom Level
                array(
                    'key' => 'field_map_zoom_level',
                    'label' => 'Zoom Level',
                    'name' => 'map_zoom_level',
                    'type' => 'number',
                    'instructions' => 'Zoom level when territory is selected (4-12)',
                    'default_value' => 6,
                    'min' => 4,
                    'max' => 12,
                    'step' => 0.5,
                ),

                // Map Color
                array(
                    'key' => 'field_map_color',
                    'label' => 'Map Color',
                    'name' => 'map_color',
                    'type' => 'color_picker',
                    'instructions' => 'Color for territory on map (hex code)',
                    'default_value' => '#dc2626',
                ),

                // Points of Interest
                array(
                    'key' => 'field_points_of_interest',
                    'label' => 'Points of Interest',
                    'name' => 'points_of_interest',
                    'type' => 'repeater',
                    'layout' => 'table',
                    'button_label' => 'Add Point',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_poi_name',
                            'label' => 'Name',
                            'name' => 'name',
                            'type' => 'text',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'field_poi_coordinates',
                            'label' => 'Coordinates',
                            'name' => 'coordinates',
                            'type' => 'text',
                            'instructions' => 'Format: longitude,latitude',
                            'required' => 1,
                        ),
                        array(
                            'key' => 'field_poi_description',
                            'label' => 'Description',
                            'name' => 'description',
                            'type' => 'textarea',
                            'rows' => 2,
                        ),
                        array(
                            'key' => 'field_poi_image',
                            'label' => 'Image',
                            'name' => 'image',
                            'type' => 'image',
                            'return_format' => 'array',
                            'preview_size' => 'thumbnail',
                        ),
                    ),
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'destination',
                    ),
                ),
            ),
            'menu_order' => 1,
            'position' => 'normal',
            'style' => 'default',
        ));
    }
}
add_action('acf/init', 'register_destination_map_fields');

/**
 * REST API Endpoints
 */
function register_map_rest_endpoints()
{
    // Get all destinations
    register_rest_route('tw/v1', '/map/destinations', array(
        'methods' => 'GET',
        'callback' => 'get_map_destinations_data',
        'permission_callback' => '__return_true',
    ));

    // Get single destination
    register_rest_route('tw/v1', '/map/destination/(?P<id>\d+)', array(
        'methods' => 'GET',
        'callback' => 'get_single_destination_data',
        'permission_callback' => '__return_true',
    ));

    // Get featured destinations
    register_rest_route('tw/v1', '/map/featured', array(
        'methods' => 'GET',
        'callback' => 'get_featured_destinations_data',
        'permission_callback' => '__return_true',
    ));
}
add_action('rest_api_init', 'register_map_rest_endpoints');

/**
 * Get all destinations
 */
function get_map_destinations_data($request)
{
    $args = array(
        'post_type' => 'destination',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'orderby' => 'title',
        'order' => 'ASC',
    );

    $destinations = get_posts($args);
    $map_data = array();

    foreach ($destinations as $destination) {
        $geojson = get_field('map_geojson', $destination->ID);
        $coordinates_str = get_field('map_coordinates', $destination->ID);
        $coordinates = null;

        if ($coordinates_str) {
            $coords = explode(',', $coordinates_str);
            if (count($coords) === 2) {
                $coordinates = array(
                    'lng' => floatval(trim($coords[0])),
                    'lat' => floatval(trim($coords[1])),
                );
            }
        }

        $thumbnail = get_the_post_thumbnail_url($destination->ID, 'large');

        $data = array(
            'id' => $destination->ID,
            'title' => $destination->post_title,
            'slug' => $destination->post_name,
            'excerpt' => wp_trim_words($destination->post_excerpt, 20),
            'thumbnail' => $thumbnail,
            'url' => get_permalink($destination->ID),
            'coordinates' => $coordinates,
            'zoom_level' => get_field('map_zoom_level', $destination->ID) ?: 6,
            'destination_level' => get_field('destination_level', $destination->ID) ?: 'state',
            'parent_destination' => get_field('parent_destination', $destination->ID),
            'is_featured' => get_field('is_featured', $destination->ID) ?: false,
            'featured_order' => get_field('featured_order', $destination->ID) ?: 0,
            'map_color' => get_field('map_color', $destination->ID) ?: '#dc2626',
            'geojson' => $geojson ? json_decode($geojson) : null,
        );

        $map_data[] = $data;
    }

    return new WP_REST_Response($map_data, 200);
}

/**
 * Get featured destinations for sidebar
 */
function get_featured_destinations_data($request)
{
    $args = array(
        'post_type' => 'destination',
        'posts_per_page' => -1,
        'post_status' => 'publish',
        'meta_query' => array(
            array(
                'key' => 'is_featured',
                'value' => '1',
                'compare' => '=',
            ),
        ),
        'orderby' => 'meta_value_num',
        'meta_key' => 'featured_order',
        'order' => 'ASC',
    );

    $destinations = get_posts($args);
    $featured_data = array();

    foreach ($destinations as $destination) {
        $coordinates_str = get_field('map_coordinates', $destination->ID);
        $coordinates = null;

        if ($coordinates_str) {
            $coords = explode(',', $coordinates_str);
            if (count($coords) === 2) {
                $coordinates = array(
                    'lng' => floatval(trim($coords[0])),
                    'lat' => floatval(trim($coords[1])),
                );
            }
        }

        $featured_data[] = array(
            'id' => $destination->ID,
            'title' => $destination->post_title,
            'thumbnail' => get_the_post_thumbnail_url($destination->ID, 'medium'),
            'excerpt' => wp_trim_words($destination->post_excerpt, 15),
            'coordinates' => $coordinates,
            'zoom_level' => get_field('map_zoom_level', $destination->ID) ?: 6,
            'destination_level' => get_field('destination_level', $destination->ID),
        );
    }

    return new WP_REST_Response($featured_data, 200);
}

/**
 * Get single destination with POIs
 */
function get_single_destination_data($request)
{
    $destination_id = $request->get_param('id');
    $destination = get_post($destination_id);

    if (!$destination || $destination->post_type !== 'destination') {
        return new WP_Error('not_found', 'Destination not found', array('status' => 404));
    }

    $coordinates_str = get_field('map_coordinates', $destination_id);
    $coordinates = null;

    if ($coordinates_str) {
        $coords = explode(',', $coordinates_str);
        if (count($coords) === 2) {
            $coordinates = array(
                'lng' => floatval(trim($coords[0])),
                'lat' => floatval(trim($coords[1])),
            );
        }
    }

    $points = array();
    $pois = get_field('points_of_interest', $destination_id);

    if ($pois) {
        foreach ($pois as $poi) {
            $poi_coords_str = $poi['coordinates'];
            $poi_coords = explode(',', $poi_coords_str);

            if (count($poi_coords) === 2) {
                $points[] = array(
                    'name' => $poi['name'],
                    'coordinates' => array(
                        'lng' => floatval(trim($poi_coords[0])),
                        'lat' => floatval(trim($poi_coords[1])),
                    ),
                    'description' => $poi['description'] ?? '',
                    'image' => $poi['image'] ?? null,
                );
            }
        }
    }

    $data = array(
        'id' => $destination->ID,
        'title' => $destination->post_title,
        'content' => apply_filters('the_content', $destination->post_content),
        'excerpt' => wp_trim_words($destination->post_excerpt, 30),
        'thumbnail' => get_the_post_thumbnail_url($destination_id, 'large'),
        'url' => get_permalink($destination_id),
        'coordinates' => $coordinates,
        'zoom_level' => get_field('map_zoom_level', $destination_id) ?: 6,
        'destination_level' => get_field('destination_level', $destination_id),
        'map_color' => get_field('map_color', $destination_id) ?: '#dc2626',
        'geojson' => json_decode(get_field('map_geojson', $destination_id)),
        'points_of_interest' => $points,
    );

    return new WP_REST_Response($data, 200);
}

/**
 * Enqueue Map Assets
 */
function enqueue_interactive_map_assets()
{
    // Mapbox GL JS
    wp_enqueue_style(
        'mapbox-gl-css',
        'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css',
        array(),
        '2.15.0'
    );

    wp_enqueue_script(
        'mapbox-gl-js',
        'https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js',
        array(),
        '2.15.0',
        true
    );

    // Custom map script
    wp_enqueue_script(
        'usa-interactive-map',
        get_template_directory_uri() . '/js/usa-interactive-map.js',
        array('mapbox-gl-js'),
        _TW_VERSION,
        true
    );

    // Custom map styles
    wp_enqueue_style(
        'usa-interactive-map-css',
        get_template_directory_uri() . '/css/usa-interactive-map.css',
        array('mapbox-gl-css'),
        _TW_VERSION
    );

    // Localize script with API data
    wp_localize_script('usa-interactive-map', 'USAMapConfig', array(
        'apiUrl' => rest_url('tw/v1/map'),
        'nonce' => wp_create_nonce('wp_rest'),
        'mapboxToken' => get_option('mapbox_access_token', ''),
    ));
}
add_action('wp_enqueue_scripts', 'enqueue_interactive_map_assets');

/**
 * Shortcode for Interactive Map
 */
function usa_interactive_map_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'height' => '600px',
        'initial_zoom' => '4',
        'center_lat' => '39.8283',
        'center_lng' => '-98.5795',
    ), $atts);

    ob_start();
?>
    <div class="usa-interactive-map-wrapper" style="height: <?php echo esc_attr($atts['height']); ?>;">

        <!-- Permanent Featured Destinations Sidebar (Right) -->
        <div id="map-featured-sidebar" class="map-featured-sidebar">
            <div class="map-featured-sidebar-header">
                <h3>Explore USA</h3>
                <p class="text-sm text-gray-600">Click to discover destinations</p>
            </div>
            <div id="map-featured-list" class="map-featured-list">
                <!-- Will be populated by JavaScript -->
            </div>
        </div>

        <!-- Map Container -->
        <div id="usa-map" class="usa-map"
            data-initial-zoom="<?php echo esc_attr($atts['initial_zoom']); ?>"
            data-center-lat="<?php echo esc_attr($atts['center_lat']); ?>"
            data-center-lng="<?php echo esc_attr($atts['center_lng']); ?>">
        </div>

        <!-- Info Panel (Left) - Appears when clicking territory -->
        <div id="map-info-panel" class="map-info-panel hidden">
            <div class="map-info-panel-content">
                <button class="map-info-panel-close" aria-label="Close">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <div class="map-info-panel-image">
                    <img src="" alt="" class="w-full h-64 object-cover">
                </div>

                <div class="map-info-panel-body p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4"></h2>
                    <div class="text-gray-600 mb-6"></div>

                    <div class="map-info-panel-actions">
                        <a href="#" class="btn btn-primary inline-flex items-center">
                            Explore Destination
                            <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="map-info-panel-pois mt-6">
                        <h3 class="text-lg font-semibold mb-3">Points of Interest</h3>
                        <div class="poi-list space-y-2"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="map-loading" class="map-loading">
            <div class="map-loading-spinner"></div>
            <p>Loading map...</p>
        </div>

        <!-- Mobile Territory Selector -->
        <div class="map-mobile-selector lg:hidden">
            <select id="map-territory-select" class="w-full p-3 border border-gray-300 rounded-lg">
                <option value="">Select a destination...</option>
            </select>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('usa_interactive_map', 'usa_interactive_map_shortcode');
// Backward compatibility
add_shortcode('canada_interactive_map', 'usa_interactive_map_shortcode');

/**
 * Add Mapbox Token Setting
 */
function add_mapbox_settings_page()
{
    add_options_page(
        'Mapbox Settings',
        'Mapbox',
        'manage_options',
        'mapbox-settings',
        'render_mapbox_settings_page'
    );
}
add_action('admin_menu', 'add_mapbox_settings_page');

/**
 * Admin Notice for Map Setup
 */
function usa_map_admin_notice()
{
    if (!get_option('mapbox_access_token')) {
        $settings_url = admin_url('options-general.php?page=mapbox-settings');
    ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <strong>USA Interactive Map:</strong>
                Mapbox token not configured.
                <a href="<?php echo esc_url($settings_url); ?>">Configure now</a>
            </p>
        </div>
    <?php
    }
}
add_action('admin_notices', 'usa_map_admin_notice');

function render_mapbox_settings_page()
{
    if (isset($_POST['mapbox_token'])) {
        check_admin_referer('mapbox_settings');
        update_option('mapbox_access_token', sanitize_text_field($_POST['mapbox_token']));
        echo '<div class="notice notice-success"><p>Settings saved!</p></div>';
    }

    $token = get_option('mapbox_access_token', '');
    ?>
    <div class="wrap">
        <h1>Mapbox Settings</h1>
        <form method="post">
            <?php wp_nonce_field('mapbox_settings'); ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Mapbox Access Token</th>
                    <td>
                        <input type="text" name="mapbox_token" value="<?php echo esc_attr($token); ?>" class="regular-text" />
                        <p class="description">Get your token from <a href="https://account.mapbox.com/" target="_blank">Mapbox</a></p>
                    </td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
<?php
}
