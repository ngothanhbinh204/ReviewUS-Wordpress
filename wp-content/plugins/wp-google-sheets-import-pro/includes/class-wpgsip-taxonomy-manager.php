    <?php
    /**
     * Taxonomy Manager
     * 
     * Manages taxonomies for both posts and thing_to_do custom post type
     *
     * @package    WP_Google_Sheets_Import_Pro
     * @subpackage WP_Google_Sheets_Import_Pro/includes
     */

    if (!defined('ABSPATH')) {
        exit;
    }

    class WPGSIP_Taxonomy_Manager
    {
        /**
         * Get available taxonomies for a post type
         *
         * @param string $post_type Post type name
         * @return array List of taxonomies
         */
        public function get_taxonomies_for_post_type($post_type = 'post')
        {
            $taxonomies = array();
            
            if ($post_type === 'thing_to_do') {
                $taxonomies = array(
                    'provinces_territories' => array(
                        'label' => __('Provinces & Territories', 'wp-gs-import-pro'),
                        'slug' => 'provinces_territories',
                        'hierarchical' => true,
                        'column_names' => array('province', 'provinces_territories', 'province_territory')
                    ),
                    'thing_themes' => array(
                        'label' => __('Themes', 'wp-gs-import-pro'),
                        'slug' => 'thing_themes',
                        'hierarchical' => true,
                        'column_names' => array('theme', 'themes', 'thing_themes')
                    ),
                    'seasons' => array(
                        'label' => __('Seasons', 'wp-gs-import-pro'),
                        'slug' => 'seasons',
                        'hierarchical' => false,
                        'column_names' => array('season', 'seasons')
                    ),
                );
            } else {
                // Standard post taxonomies
                $taxonomies = array(
                    'category' => array(
                        'label' => __('Categories', 'wp-gs-import-pro'),
                        'slug' => 'category',
                        'hierarchical' => true,
                        'column_names' => array('category', 'categories')
                    ),
                    'post_tag' => array(
                        'label' => __('Tags', 'wp-gs-import-pro'),
                        'slug' => 'post_tag',
                        'hierarchical' => false,
                        'column_names' => array('tag', 'tags', 'post_tag')
                    ),
                );
            }
                // error_log('WPGSIP -> Taxonomies loaded for ' . $post_type . ': ' . print_r($taxonomies, true));

            return apply_filters('wpgsip_available_taxonomies', $taxonomies, $post_type);
        }

        /**
         * Get all terms for a taxonomy
         *
         * @param string $taxonomy Taxonomy slug
         * @return array List of terms
         */
       public function get_terms_for_taxonomy($taxonomy)
{
    // 🧩 Debug kiểm tra taxonomy có tồn tại không
    if (!taxonomy_exists($taxonomy)) {
        error_log("❌ Taxonomy '{$taxonomy}' does not exist in WP.");
        return array();
    } else {
        error_log("✅ Taxonomy '{$taxonomy}' exists.");
    }

    $terms = get_terms(array(
        'taxonomy' => $taxonomy,
        'hide_empty' => false,
    ));
    
    if (is_wp_error($terms)) {
        error_log("⚠️ Error getting terms for '{$taxonomy}': " . $terms->get_error_message());
        return array();
    }
    
    error_log("✅ Loaded " . count($terms) . " terms for taxonomy '{$taxonomy}'.");
    return $terms;
}


        /**
         * Assign taxonomies to a post
         *
         * @param int $post_id Post ID
         * @param array $taxonomy_data Taxonomy data from sheet or manual selection
         * @param string $post_type Post type
         */
        public function assign_taxonomies($post_id, $taxonomy_data, $post_type = 'post')
        {
            $available_taxonomies = $this->get_taxonomies_for_post_type($post_type);
            
            foreach ($available_taxonomies as $taxonomy_slug => $taxonomy_info) {
                $terms_to_assign = array();
                
                // Check if data exists for this taxonomy
                if (isset($taxonomy_data[$taxonomy_slug]) && !empty($taxonomy_data[$taxonomy_slug])) {
                    $terms_input = $taxonomy_data[$taxonomy_slug];
                    
                    // Handle array (term IDs from dropdown)
                    if (is_array($terms_input)) {
                        // Check if first element is numeric (term IDs)
                        if (!empty($terms_input) && is_numeric($terms_input[0])) {
                            // Direct term IDs from dropdown
                            $terms_to_assign = array_map('intval', $terms_input);
                        } else {
                            // Array of term names
                            foreach ($terms_input as $term_name) {
                                if (empty($term_name)) {
                                    continue;
                                }
                                $term_id = $this->get_or_create_term($term_name, $taxonomy_slug);
                                if ($term_id) {
                                    $terms_to_assign[] = $term_id;
                                }
                            }
                        }
                    } elseif (is_string($terms_input)) {
                        // Handle comma-separated string values
                        $terms_input = array_map('trim', explode(',', $terms_input));
                        
                        foreach ($terms_input as $term_name) {
                            if (empty($term_name)) {
                                continue;
                            }
                            $term_id = $this->get_or_create_term($term_name, $taxonomy_slug);
                            if ($term_id) {
                                $terms_to_assign[] = $term_id;
                            }
                        }
                    }
                }
                
                // Assign terms to post
                if (!empty($terms_to_assign)) {
                    wp_set_object_terms($post_id, $terms_to_assign, $taxonomy_slug, false);
                }
            }
        }
        
        /**
         * Get term ID by name, create if doesn't exist
         *
         * @param string $term_name Term name
         * @param string $taxonomy_slug Taxonomy slug
         * @return int|false Term ID or false on error
         */
        private function get_or_create_term($term_name, $taxonomy_slug)
        {
            // Check if term exists by name
            $term = get_term_by('name', $term_name, $taxonomy_slug);
            
            if (!$term) {
                // Check by slug
                $term = get_term_by('slug', sanitize_title($term_name), $taxonomy_slug);
            }
            
            if (!$term) {
                // Create new term
                $new_term = wp_insert_term(
                    $term_name,
                    $taxonomy_slug,
                    array(
                        'slug' => sanitize_title($term_name)
                    )
                );
                
                if (!is_wp_error($new_term)) {
                    return intval($new_term['term_id']);
                }
                return false;
            }
            
            return intval($term->term_id);
        }

        /**
         * Extract taxonomy data from sheet row
         *
         * @param array $row Row data from Google Sheets
         * @param string $post_type Post type
         * @return array Extracted taxonomy data
         */
        public function extract_from_row($row, $post_type = 'post')
        {
            $taxonomy_data = array();
            $available_taxonomies = $this->get_taxonomies_for_post_type($post_type);
            
            foreach ($available_taxonomies as $taxonomy_slug => $taxonomy_info) {
                // Check various possible column names
                foreach ($taxonomy_info['column_names'] as $column_name) {
                    if (isset($row[$column_name]) && !empty($row[$column_name])) {
                        $taxonomy_data[$taxonomy_slug] = $row[$column_name];
                        break;
                    }
                }
            }
            
            return $taxonomy_data;
        }

        /**
         * Get taxonomy dropdown HTML
         *
         * @param string $taxonomy Taxonomy slug
         * @param array $selected Selected term IDs
         * @param array $args Additional arguments
         * @return string HTML dropdown
         */
        public function get_taxonomy_dropdown($taxonomy, $selected = array(), $args = array())
        {
            $defaults = array(
                'name' => "taxonomy[{$taxonomy}][]",
                'id' => "taxonomy-{$taxonomy}",
                'class' => 'wpgsip-taxonomy-select',
                'multiple' => true,
            );

            
            $args = wp_parse_args($args, $defaults);
            $terms = $this->get_terms_for_taxonomy($taxonomy);

            if (empty($terms)) {
                error_log("No terms found for taxonomy '{$taxonomy}'.");
            } else {
                error_log("Loaded " . count($terms) . " terms for taxonomy '{$taxonomy}'.");
            }

            if (empty($terms)) {
                return '<p class="description">' . __('No terms available. They will be created when you import.', 'wp-gs-import-pro') . '</p>';
            }
            
            $html = '<select name="' . esc_attr($args['name']) . '" id="' . esc_attr($args['id']) . '" class="' . esc_attr($args['class']) . '"';
            
            if ($args['multiple']) {
                $html .= ' multiple';
            }
            
            $html .= '>';
            
            foreach ($terms as $term) {
                $is_selected = in_array($term->term_id, $selected) ? ' selected' : '';
                $html .= '<option value="' . esc_attr($term->term_id) . '"' . $is_selected . '>' . esc_html($term->name) . '</option>';
            }
            
            $html .= '</select>';
            
            return $html;
        }

        /**
         * Check if taxonomy column exists in sheet
         *
         * @param array $row Sample row from sheet
         * @param string $post_type Post type
         * @return array Taxonomies found in sheet
         */
        public function detect_taxonomy_columns($row, $post_type = 'post')
        {
            $found_taxonomies = array();
            $available_taxonomies = $this->get_taxonomies_for_post_type($post_type);
            
            foreach ($available_taxonomies as $taxonomy_slug => $taxonomy_info) {
                foreach ($taxonomy_info['column_names'] as $column_name) {
                    if (isset($row[$column_name])) {
                        $found_taxonomies[$taxonomy_slug] = array(
                            'label' => $taxonomy_info['label'],
                            'column_name' => $column_name,
                            'has_data' => !empty($row[$column_name])
                        );
                        break;
                    }
                }
            }
            
            return $found_taxonomies;
        }
    }