# TAXONOMY REDIRECT TO FILTER PAGE

## Ngày cập nhật: October 4, 2025

---

## 🎯 MỤC ĐÍCH

Redirect các taxonomy archive URLs sang trang filter với query parameters để tối ưu UX và SEO.

---

## ✅ HOẠT ĐỘNG

### URL Mapping:

| **Old URL (Taxonomy Archive)** | **New URL (Filter Page)** |
|--------------------------------|---------------------------|
| `/theme/history-heritage/` | `/things-to-do/articles/?themes=history-heritage` |
| `/theme/outdoor-adventure/` | `/things-to-do/articles/?themes=outdoor-adventure` |
| `/province/british-columbia/` | `/things-to-do/articles/?provinces=british-columbia` |
| `/season/summer/` | `/things-to-do/articles/?seasons=summer` |

---

## 🔧 CODE IMPLEMENTATION

### Function: `redirect_taxonomy_to_filter_page()`

**Location:** `functions.php` (lines ~748-778)

```php
function redirect_taxonomy_to_filter_page()
{
    // Only run on taxonomy archives
    if (!is_tax()) {
        return;
    }

    $queried_object = get_queried_object();

    // Taxonomy mapping
    $redirect_taxonomies = array(
        'thing_themes' => 'themes',
        'provinces_territories' => 'provinces',
        'seasons' => 'seasons',
    );

    if (isset($redirect_taxonomies[$queried_object->taxonomy])) {
        $taxonomy_name = $queried_object->taxonomy;
        $term_slug = $queried_object->slug;
        $query_param = $redirect_taxonomies[$taxonomy_name];

        // Build redirect URL
        $redirect_url = home_url('/things-to-do/articles/');
        $redirect_url = add_query_arg($query_param, $term_slug, $redirect_url);

        // 301 redirect
        wp_redirect($redirect_url, 301);
        exit;
    }
}
add_action('template_redirect', 'redirect_taxonomy_to_filter_page');
```

---

## 📋 TAXONOMY MAPPING TABLE

| Taxonomy Internal Name | URL Slug | Query Parameter | Example Term |
|------------------------|----------|-----------------|--------------|
| `thing_themes` | `/theme/` | `themes` | `history-heritage` |
| `provinces_territories` | `/province/` | `provinces` | `british-columbia` |
| `seasons` | `/season/` | `seasons` | `summer` |

---

## 🎨 HOW IT WORKS

### Step-by-Step Flow:

1. **User clicks taxonomy link**: `/theme/history-heritage/`
2. **WordPress loads taxonomy archive**: Triggers `template_redirect` hook
3. **Function checks**: Is this a taxonomy archive? → Yes
4. **Function gets**: 
   - Taxonomy: `thing_themes`
   - Term slug: `history-heritage`
5. **Function maps**: 
   - `thing_themes` → query param `themes`
6. **Function builds URL**:
   ```php
   home_url('/things-to-do/articles/') + '?themes=history-heritage'
   ```
7. **301 Redirect**: User lands on `/things-to-do/articles/?themes=history-heritage`
8. **Filter page loads**: With pre-selected filter "History & Heritage"

---

## 🔍 SEO BENEFITS

### 301 Permanent Redirect:
- ✅ Passes SEO link juice
- ✅ Tells search engines this is permanent
- ✅ Avoids duplicate content issues
- ✅ Consolidates ranking signals

### URL Structure:
```
Before: /theme/history-heritage/          (Archive template)
After:  /things-to-do/articles/?themes=... (Filter page with state)
```

**Benefits:**
- Single page for all filtering (better UX)
- Centralized content (better SEO)
- Shareable URLs with filter state
- Consistent navigation experience

---

## 🧪 TESTING

### Test Case 1: Theme Taxonomy
```bash
# Navigate to:
http://reviewus.local/theme/history-heritage/

# Expected Result:
→ Redirects to: http://reviewus.local/things-to-do/articles/?themes=history-heritage
→ Status: 301 Moved Permanently
→ Filter "History & Heritage" is pre-selected
```

### Test Case 2: Province Taxonomy
```bash
# Navigate to:
http://reviewus.local/province/british-columbia/

# Expected Result:
→ Redirects to: http://reviewus.local/things-to-do/articles/?provinces=british-columbia
→ Status: 301 Moved Permanently
→ Filter "British Columbia" is pre-selected
```

### Test Case 3: Season Taxonomy
```bash
# Navigate to:
http://reviewus.local/season/summer/

# Expected Result:
→ Redirects to: http://reviewus.local/things-to-do/articles/?seasons=summer
→ Status: 301 Moved Permanently
→ Filter "Summer" is pre-selected
```

### Test Case 4: Multiple Parameters (Manual)
```bash
# Navigate to:
http://reviewus.local/things-to-do/articles/?themes=outdoor-adventure&provinces=british-columbia&seasons=summer

# Expected Result:
→ No redirect (already on filter page)
→ Multiple filters pre-selected
→ Results filtered by all 3 criteria
```

---

## 🛠️ CONFIGURATION

### Adding More Taxonomies:

Để thêm taxonomy khác vào redirect system:

```php
$redirect_taxonomies = array(
    'thing_themes' => 'themes',
    'provinces_territories' => 'provinces',
    'seasons' => 'seasons',
    
    // Add new taxonomy here:
    'new_taxonomy_name' => 'query_param_name',
);
```

**Example - Add "Activities" taxonomy:**
```php
$redirect_taxonomies = array(
    'thing_themes' => 'themes',
    'provinces_territories' => 'provinces',
    'seasons' => 'seasons',
    'activities' => 'activity',  // New!
);
```

### Changing Target Page:

Để đổi trang đích (hiện tại là `/things-to-do/articles/`):

```php
// Line ~768 in functions.php
$redirect_url = home_url('/things-to-do/articles/');

// Change to:
$redirect_url = home_url('/your-new-page/');
```

---

## 🐛 TROUBLESHOOTING

### Issue: Redirect không hoạt động
**Solutions:**
1. Flush rewrite rules:
   ```
   WordPress Admin → Settings → Permalinks → Save Changes
   ```
2. Clear browser cache (Ctrl + Shift + R)
3. Check if taxonomy exists:
   ```php
   // Add debug code temporarily
   error_log('Taxonomy: ' . $queried_object->taxonomy);
   error_log('Term slug: ' . $queried_object->slug);
   ```

### Issue: Redirect loop
**Cause:** Filter page URL có taxonomy slug trong URL
**Solution:** Đảm bảo filter page không có conflicting slug

### Issue: 404 on filter page
**Cause:** Page "things-to-do-articles" không tồn tại
**Solution:** 
1. Tạo page với slug `things-to-do-articles`
2. Hoặc đổi `$redirect_url` sang page khác

### Issue: Query parameters không hoạt động
**Cause:** Filter page JavaScript chưa đọc URL params
**Solution:** Kiểm tra JavaScript filter initialization:
```javascript
// Check if URL params are read on page load
const urlParams = new URLSearchParams(window.location.search);
const themes = urlParams.get('themes');
console.log('Theme filter:', themes);
```

---

## 📊 ANALYTICS TRACKING

### Recommended: Track redirects với Google Analytics

```php
// Optional: Add GA event before redirect
function redirect_taxonomy_to_filter_page()
{
    // ... existing code ...
    
    // Track redirect event (if using GA4)
    ?>
    <script>
        gtag('event', 'taxonomy_redirect', {
            'taxonomy': '<?php echo $taxonomy_name; ?>',
            'term': '<?php echo $term_slug; ?>',
            'redirect_to': '<?php echo $redirect_url; ?>'
        });
    </script>
    <?php
    
    wp_redirect($redirect_url, 301);
    exit;
}
```

---

## 🔗 RELATED FILES

### Files Modified:
- `functions.php` - Added redirect function

### Files Required:
- Page: `things-to-do-articles` (slug: `things-to-do-articles`)
- JavaScript filter logic (to read URL params)

### Dependencies:
- WordPress core: `is_tax()`, `get_queried_object()`, `wp_redirect()`
- Rewrite rules must be flushed after adding new taxonomies

---

## 🚀 NEXT STEPS (OPTIONAL)

### 1. Pre-select Filters on Page Load
Đảm bảo filter UI hiển thị đúng filters từ URL params:

```javascript
// In filter page JavaScript
const urlParams = new URLSearchParams(window.location.search);

// Pre-select theme filter
const themeParam = urlParams.get('themes');
if (themeParam) {
    selectFilter('themes', themeParam);
}

// Pre-select province filter
const provinceParam = urlParams.get('provinces');
if (provinceParam) {
    selectFilter('provinces', provinceParam);
}

// Pre-select season filter
const seasonParam = urlParams.get('seasons');
if (seasonParam) {
    selectFilter('seasons', seasonParam);
}
```

### 2. Update Taxonomy Links in Menu
Nếu có taxonomy links trong menu, update chúng:

```php
// Add filter to modify menu item URLs
add_filter('wp_nav_menu_objects', 'modify_taxonomy_menu_links', 10, 2);

function modify_taxonomy_menu_links($items, $args) {
    foreach ($items as $item) {
        if ($item->type === 'taxonomy' && $item->object === 'thing_themes') {
            $term = get_term($item->object_id);
            if ($term) {
                $item->url = home_url('/things-to-do/articles/?themes=' . $term->slug);
            }
        }
    }
    return $items;
}
```

### 3. Canonical URLs
Đảm bảo SEO tốt với canonical tags:

```php
// In filter page template
add_action('wp_head', 'add_filter_page_canonical');

function add_filter_page_canonical() {
    if (is_page('things-to-do-articles')) {
        $current_url = home_url($_SERVER['REQUEST_URI']);
        echo '<link rel="canonical" href="' . esc_url($current_url) . '" />';
    }
}
```

---

## 📞 NOTES

- **301 Redirect**: Permanent redirect, tốt cho SEO
- **Query Parameters**: Có thể bookmark và share
- **Filter State**: Được preserve trong URL
- **Browser History**: Users có thể back/forward
- **Performance**: Minimal overhead, chỉ check on taxonomy pages

---

**Last Updated:** October 4, 2025
**Version:** 1.0.0
**Status:** ✅ Production Ready
