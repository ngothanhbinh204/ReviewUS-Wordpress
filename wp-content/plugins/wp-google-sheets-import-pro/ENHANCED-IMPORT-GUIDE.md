# Enhanced Import System - Implementation Guide

## ✅ Completed

### 1. Taxonomy Manager Class
**File:** `includes/class-wpgsip-taxonomy-manager.php`
- ✅ Detects taxonomies for post and thing_to_do
- ✅ Auto-creates terms if not exist
- ✅ Supports: provinces_territories, thing_themes, seasons
- ✅ Extracts taxonomy data from sheet columns

### 2. Enhanced Import View
**File:** `admin/views/import.php`
- ✅ Post type selector (Post / Thing To Do)
- ✅ Taxonomy section for manual selection
- ✅ Select All checkbox
- ✅ Selected count display
- ✅ Import button disabled until selection

### 3. Import JavaScript
**File:** `assets/js/import.js`
- ✅ Selective row import
- ✅ Post type switching
- ✅ Taxonomy detection and UI
- ✅ Batch import with selected rows only
- ✅ Shows create vs update status

## 🔧 TODO - Required Steps

### Step 1: Update Main Plugin File
Add taxonomy manager to autoload:

```php
// wp-google-sheets-import-pro.php
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-taxonomy-manager.php';
```

Place AFTER `class-wpgsip-tenant-manager.php` and BEFORE `class-wpgsip-importer.php`

### Step 2: Update Importer Class
**File:** `includes/class-wpgsip-importer.php`

Add property:
```php
private $taxonomy_manager;
```

In constructor:
```php
$this->taxonomy_manager = new WPGSIP_Taxonomy_Manager();
```

Update `create_post()` and `update_post()` methods to accept `$post_type`:
```php
private function create_post($row, $post_type = 'post', $taxonomy_data = array())
{
    // ... existing code ...
    
    $post_data = array(
        'post_title' => sanitize_text_field($post_title),
        'post_content' => wp_kses_post($post_content),
        'post_excerpt' => sanitize_textarea_field($post_excerpt),
        'post_status' => $post_status,
        'post_type' => $post_type,  // Changed from 'post'
        'post_author' => get_current_user_id() ?: 1,
    );
    
    // ... after wp_insert_post ...
    
    // Assign taxonomies
    if (!empty($taxonomy_data)) {
        $this->taxonomy_manager->assign_taxonomies($post_id, $taxonomy_data, $post_type);
    }
    
    return $post_id;
}
```

### Step 3: Add New AJAX Handlers
**File:** `includes/class-wpgsip-core.php`

In `define_admin_hooks()` add:
```php
add_action('wp_ajax_wpgsip_import_preview', array($this, 'ajax_import_preview_enhanced'));
add_action('wp_ajax_wpgsip_import_execute_selective', array($this, 'ajax_import_selective'));
```

Add new methods:
```php
public function ajax_import_preview_enhanced()
{
    check_ajax_referer('wpgsip_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Permission denied'));
    }
    
    $tenant_id = isset($_POST['tenant_id']) ? sanitize_text_field($_POST['tenant_id']) : 'default';
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
    
    try {
        $google_sheets = new WPGSIP_Google_Sheets($tenant_id);
        $data = $google_sheets->fetch_data(false);
        
        $taxonomy_manager = new WPGSIP_Taxonomy_Manager();
        
        // Detect taxonomies in sheet
        $detected_taxonomies = array();
        $available_terms = array();
        
        if (!empty($data)) {
            $detected_taxonomies = $taxonomy_manager->detect_taxonomy_columns($data[0], $post_type);
            
            // Get available terms for each taxonomy
            foreach ($detected_taxonomies as $slug => $info) {
                $available_terms[$slug] = $taxonomy_manager->get_terms_for_taxonomy($slug);
            }
        }
        
        // Check for existing posts
        foreach ($data as &$row) {
            $existing_post = $this->find_existing_post_by_title($row['meta_title'], $post_type);
            $row['existing_post'] = $existing_post;
        }
        
        wp_send_json_success(array(
            'count' => count($data),
            'data' => $data,
            'taxonomies' => $detected_taxonomies,
            'available_terms' => $available_terms,
            'post_type' => $post_type
        ));
        
    } catch (Exception $e) {
        wp_send_json_error(array('message' => $e->getMessage()));
    }
}

public function ajax_import_selective()
{
    check_ajax_referer('wpgsip_nonce', 'nonce');
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => 'Permission denied'));
    }
    
    $tenant_id = isset($_POST['tenant_id']) ? sanitize_text_field($_POST['tenant_id']) : 'default';
    $post_type = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
    $row_ids = isset($_POST['row_ids']) ? array_map('sanitize_text_field', $_POST['row_ids']) : array();
    $default_taxonomies = isset($_POST['default_taxonomies']) ? $_POST['default_taxonomies'] : array();
    
    try {
        $google_sheets = new WPGSIP_Google_Sheets($tenant_id);
        $all_data = $google_sheets->fetch_data(false);
        
        // Filter only selected rows
        $selected_data = array_filter($all_data, function($row) use ($row_ids) {
            return in_array($row['row_id'], $row_ids);
        });
        
        $importer = new WPGSIP_Importer($tenant_id);
        $taxonomy_manager = new WPGSIP_Taxonomy_Manager();
        
        $results = array(
            'processed' => 0,
            'created' => 0,
            'updated' => 0,
            'skipped' => 0,
            'errors' => 0,
            'messages' => array(),
        );
        
        foreach ($selected_data as $row) {
            // Extract taxonomy data from row
            $taxonomy_data = $taxonomy_manager->extract_from_row($row, $post_type);
            
            // Merge with default taxonomies
            foreach ($default_taxonomies as $taxonomy => $terms) {
                if (empty($taxonomy_data[$taxonomy]) && !empty($terms)) {
                    $taxonomy_data[$taxonomy] = $terms;
                }
            }
            
            $result = $importer->import_row_with_type($row, $post_type, $taxonomy_data);
            $results['processed']++;
            
            if ($result['status'] === 'created') {
                $results['created']++;
            } elseif ($result['status'] === 'updated') {
                $results['updated']++;
            } elseif ($result['status'] === 'skipped') {
                $results['skipped']++;
            } else {
                $results['errors']++;
            }
            
            $results['messages'][] = $result['message'];
        }
        
        wp_send_json_success($results);
        
    } catch (Exception $e) {
        wp_send_json_error(array('message' => $e->getMessage()));
    }
}

private function find_existing_post_by_title($title, $post_type = 'post')
{
    global $wpdb;
    
    $post = $wpdb->get_row($wpdb->prepare(
        "SELECT ID, post_title FROM {$wpdb->posts} 
        WHERE post_title = %s 
        AND post_type = %s 
        AND post_status != 'trash'
        LIMIT 1",
        $title,
        $post_type
    ));
    
    if ($post) {
        return array(
            'id' => $post->ID,
            'title' => $post->post_title
        );
    }
    
    return false;
}
```

### Step 4: Add Import Method with Post Type
**File:** `includes/class-wpgsip-importer.php`

Add new method:
```php
public function import_row_with_type($row, $post_type = 'post', $taxonomy_data = array())
{
    try {
        // ... same validation as import_row() ...
        
        // Check if post exists
        $existing_post_id = $this->find_existing_post($row);
        
        if ($existing_post_id) {
            $post_id = $this->update_post_with_type($existing_post_id, $row, $post_type, $taxonomy_data);
            $action = 'updated';
            $message = sprintf(__('Row %d: Updated %s ID %d', 'wp-gs-import-pro'), $row['row_id'], $post_type, $post_id);
        } else {
            $post_id = $this->create_post_with_type($row, $post_type, $taxonomy_data);
            $action = 'created';
            $message = sprintf(__('Row %d: Created %s ID %d', 'wp-gs-import-pro'), $row['row_id'], $post_type, $post_id);
        }
        
        // Log success
        $this->logger->log(array(
            'tenant_id' => $this->tenant_id,
            'action' => 'import',
            'post_id' => $post_id,
            'sheet_row_id' => $row['row_id'],
            'status' => 'success',
            'message' => $message,
            'data' => json_encode(array_merge($row, array('post_type' => $post_type))),
        ));
        
        return array(
            'status' => $action,
            'message' => $message,
            'post_id' => $post_id,
        );
        
    } catch (Exception $e) {
        // ... error handling ...
    }
}

private function create_post_with_type($row, $post_type = 'post', $taxonomy_data = array())
{
    // Copy create_post() code and modify post_type
    // Add taxonomy assignment at the end
}

private function update_post_with_type($post_id, $row, $post_type = 'post', $taxonomy_data = array())
{
    // Copy update_post() code
    // Add taxonomy assignment at the end
}
```

### Step 5: Enqueue New JavaScript
**File:** `admin/class-wpgsip-admin.php`

In `enqueue_scripts()` method:
```php
// On import page, enqueue enhanced import script
if (isset($_GET['page']) && $_GET['page'] === 'wpgsip-import') {
    wp_enqueue_script(
        'wpgsip-import',
        WPGSIP_PLUGIN_URL . 'assets/js/import.js',
        array('jquery'),
        WPGSIP_VERSION,
        true
    );
    
    wp_localize_script('wpgsip-import', 'wpgsipImport', array(
        'tenantId' => $tenant_manager->get_current_tenant_id(),
        'i18n' => array(
            'noSelection' => __('Please select at least one item to import', 'wp-gs-import-pro'),
            'confirmImport' => __('Import %d selected items?', 'wp-gs-import-pro'),
            'taxonomyHelp' => __('Select default taxonomies for items that don\'t have them in the sheet:', 'wp-gs-import-pro'),
        )
    ));
}
```

### Step 6: Update import.php Script Section
Replace the existing `<script>` section with:
```php
<script>
    var wpgsipImport = wpgsipImport || {};
    wpgsipImport.tenantId = '<?php echo esc_js($current_tenant_id); ?>';
</script>
```

Remove all the inline jQuery code (it's now in import.js).

### Step 7: Add CSS Styles
**File:** `assets/css/admin.css`

Add at the end:
```css
/* Import page enhancements */
.wpgsip-preview-table .check-column {
    width: 40px;
}

.wpgsip-row-checkbox {
    cursor: pointer;
}

.wpgsip-action-badge {
    padding: 3px 8px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: bold;
}

.wpgsip-action-create {
    background: #d4edda;
    color: #155724;
}

.wpgsip-action-update {
    background: #fff3cd;
    color: #856404;
}

.wpgsip-taxonomy-select {
    width: 100%;
    max-width: 400px;
    min-height: 100px;
}

#wpgsip-selected-count {
    color: #2271b1;
    font-size: 14px;
}
```

## 🧪 Testing Steps

1. **Test Post Type Switching:**
   - Change between Post and Thing To Do
   - Verify preview updates correctly

2. **Test Selective Import:**
   - Load preview
   - Check 2-3 rows
   - Import only selected
   - Verify only those imported

3. **Test Taxonomy Detection:**
   - Add province, theme, season columns to Google Sheet
   - Verify auto-detection
   - Test auto-creation of terms

4. **Test Manual Taxonomy Selection:**
   - Import without taxonomy columns
   - Select taxonomies manually
   - Verify assignment

5. **Test Update vs Create:**
   - Import new items → should show "Create"
   - Re-import same items → should show "Update"
   - Verify no duplicates

## 📝 Google Sheet Format

### For Thing To Do:
| Row | Meta Title | Meta Description | Keyword | Province | Theme | Season | Content |
|-----|------------|-----------------|---------|----------|-------|--------|---------|
| 1 | Activity 1 | Description | hiking | Ontario | Adventure | Summer | Full content... |
| 2 | Activity 2 | Description | ski | Quebec | Winter Sports | Winter | Full content... |

### Column Name Variations (Auto-detected):
- **Province:** province, provinces_territories, province_territory
- **Theme:** theme, themes, thing_themes
- **Season:** season, seasons

## 🎯 Benefits

1. **No Duplicates:** Checks by title before creating
2. **Flexible:** Works with or without taxonomy columns
3. **Selective:** Import only what you need
4. **Fast:** Cached preview, batch processing
5. **Clear:** Shows create vs update status
6. **Safe:** Confirmation before import

## 📚 Files Modified/Created

### Created:
- ✅ `includes/class-wpgsip-taxonomy-manager.php`
- ✅ `assets/js/import.js`

### Modified:
- ⏳ `wp-google-sheets-import-pro.php` (add require)
- ⏳ `includes/class-wpgsip-importer.php` (add methods)
- ⏳ `includes/class-wpgsip-core.php` (add AJAX handlers)
- ⏳ `admin/class-wpgsip-admin.php` (enqueue script)
- ✅ `admin/views/import.php` (UI updates)
- ⏳ `assets/css/admin.css` (styles)

## ⚠️ Important Notes

1. **Backup first:** Always backup before testing
2. **Test with 1-2 rows first:** Don't import 100 rows immediately
3. **Clear cache:** Use clear-cache.php after code changes
4. **Check logs:** View import logs for any errors
5. **Taxonomy order:** Ensure taxonomies are registered before import

---

**Status:** Partially Implemented
**Next:** Complete Steps 1-7 above
**ETA:** 2-3 hours development + testing
