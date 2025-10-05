# Logic Kiểm Tra & Update Bài Viết Đã Tồn Tại

## 📋 Tóm Tắt

Plugin sử dụng **`meta_title`** để kiểm tra xem bài viết đã tồn tại hay chưa. Nếu tồn tại → **UPDATE**, nếu không → **CREATE NEW**.

---

## 🔍 1. Kiểm Tra Bài Viết Đã Tồn Tại

### Hàm: `find_existing_post_by_title()`

**File:** `includes/class-wpgsip-import-ajax.php` (dòng 308-325)

```php
private static function find_existing_post_by_title($title, $post_type = 'post')
{
    global $wpdb;
    
    $post_id = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} 
        WHERE post_title = %s 
        AND post_type = %s 
        AND post_status != 'trash'
        LIMIT 1",
        $title,
        $post_type
    ));
    
    return $post_id ? intval($post_id) : false;
}
```

### Tiêu Chí Kiểm Tra:

1. ✅ **So sánh CHÍNH XÁC** `post_title` với `meta_title` từ Google Sheet
2. ✅ **Kiểm tra `post_type`** - chỉ tìm trong cùng post type (post hoặc thing_to_do)
3. ✅ **Loại trừ bài viết trong Trash** - chỉ tìm bài active
4. ✅ **Lấy bài viết đầu tiên** - nếu có nhiều bài cùng tên, lấy ID đầu tiên

### Ví Dụ:

| Google Sheet `meta_title` | WordPress `post_title` | Kết Quả |
|---------------------------|------------------------|---------|
| "Hướng dẫn xin visa Mỹ" | "Hướng dẫn xin visa Mỹ" | ✅ FOUND - UPDATE |
| "Hướng dẫn xin visa Mỹ" | "hướng dẫn xin visa mỹ" | ❌ NOT FOUND - CREATE |
| "Hướng dẫn xin visa Mỹ" | "Hướng dẫn xin visa Mỹ 2024" | ❌ NOT FOUND - CREATE |

⚠️ **Lưu ý:** So sánh **CASE-SENSITIVE** và **EXACT MATCH** - phải khớp 100%!

---

## 🔄 2. Logic UPDATE Bài Viết

### Hàm: `import_single_row()`

**File:** `includes/class-wpgsip-import-ajax.php` (dòng 211-259)

```php
// Check if post exists
$existing_post_id = self::find_existing_post_by_title($row['meta_title'], $post_type);

if ($existing_post_id) {
    // UPDATE existing post
    $post_id = self::update_post_with_taxonomy($importer, $existing_post_id, $row, $post_type, $taxonomy_data, $taxonomy_manager);
    $action = 'updated';
} else {
    // CREATE new post
    $post_id = self::create_post_with_taxonomy($importer, $row, $post_type, $taxonomy_data, $taxonomy_manager);
    $action = 'created';
}
```

### Điều Kiện UPDATE:

| Trường Hợp | Hành Động | Ghi Chú |
|------------|-----------|---------|
| `meta_title` **khớp** với bài viết cũ | **UPDATE** | Cập nhật nội dung & taxonomies |
| `meta_title` **KHÔNG khớp** | **CREATE NEW** | Tạo bài viết mới |
| `content` **rỗng** | **SKIP** | Không import/update |
| `status` = filter value | **SKIP** | Dựa theo cài đặt "Skip Status Filter" |

---

## 📝 3. Dữ Liệu Được UPDATE

### Hàm: `update_post_with_taxonomy()`

**File:** `includes/class-wpgsip-import-ajax.php` (dòng 285-301)

Khi UPDATE bài viết, các trường sau được cập nhật:

### A. Nội Dung Post (từ Importer):

```php
// File: includes/class-wpgsip-importer.php
private function update_post($post_id, $row)
{
    // 1. Process content with Content Processor
    $content_processor = new WPGSIP_Content_Processor();
    $processed_content = $content_processor->process($row['content']);
    
    // 2. Update post data
    $post_data = array(
        'ID' => $post_id,
        'post_title' => $row['meta_title'],           // ✅ Title
        'post_content' => $processed_content,          // ✅ Content (formatted)
        'post_status' => 'publish',                    // ✅ Status
        'post_excerpt' => $row['meta_description'],    // ✅ Excerpt
    );
    
    wp_update_post($post_data);
    
    // 3. Update post meta
    update_post_meta($post_id, '_wpgsip_keyword', $row['keyword']);              // ✅ SEO Keyword
    update_post_meta($post_id, '_wpgsip_meta_description', $row['meta_description']); // ✅ Meta Description
    update_post_meta($post_id, '_yoast_wpseo_focuskw', $row['keyword']);         // ✅ Yoast Focus Keyword
    update_post_meta($post_id, '_yoast_wpseo_metadesc', $row['meta_description']); // ✅ Yoast Meta Description
    update_post_meta($post_id, '_wpgsip_last_updated', current_time('mysql'));   // ✅ Last Updated Time
    update_post_meta($post_id, '_wpgsip_row_id', $row['row_id']);               // ✅ Sheet Row ID
}
```

### B. Taxonomies (Categories, Tags):

```php
// File: includes/class-wpgsip-taxonomy-manager.php
public function assign_taxonomies($post_id, $taxonomy_data, $post_type)
{
    foreach ($taxonomy_data as $taxonomy => $terms) {
        // Replace all existing terms with new ones
        wp_set_object_terms($post_id, $terms, $taxonomy, false); // false = REPLACE mode
    }
}
```

⚠️ **Lưu ý:** `wp_set_object_terms()` với tham số `$append = false` sẽ **THAY THẾ** toàn bộ terms cũ bằng terms mới!

---

## 🎯 4. Quy Trình UPDATE Chi Tiết

### Bước 1: Kiểm Tra Bài Viết

```
Google Sheet Row:
┌─────────────────────────────────────────────┐
│ meta_title: "Hướng dẫn xin visa Mỹ"       │
│ content: "Nội dung mới..."                 │
│ category: "Visa Mỹ"                        │
│ tags: "Tips bổ ích, Visa du lịch"        │
└─────────────────────────────────────────────┘
                    ↓
         find_existing_post_by_title()
                    ↓
┌─────────────────────────────────────────────┐
│ WordPress Database:                         │
│ ID: 123                                     │
│ post_title: "Hướng dẫn xin visa Mỹ"       │
│ post_type: "post"                           │
│ post_status: "publish"                      │
└─────────────────────────────────────────────┘
         ✅ FOUND → UPDATE Mode
```

### Bước 2: Update Nội Dung

```
Before Update:
┌─────────────────────────────────────────────┐
│ ID: 123                                     │
│ post_title: "Hướng dẫn xin visa Mỹ"       │
│ post_content: "Nội dung cũ..."             │
│ categories: [1 (Uncategorized)]             │
│ tags: []                                    │
└─────────────────────────────────────────────┘

After Update:
┌─────────────────────────────────────────────┐
│ ID: 123                                     │
│ post_title: "Hướng dẫn xin visa Mỹ"       │
│ post_content: "Nội dung mới... (processed)" │
│ categories: [42 (Visa Mỹ)]                 │
│ tags: [43 (Tips bổ ích), 44 (Visa du lịch)] │
│ _wpgsip_last_updated: "2025-10-06 10:30:00" │
└─────────────────────────────────────────────┘
```

---

## 🔥 5. Trường Hợp Đặc Biệt

### A. Title Bị Trùng Nhau

**Vấn đề:** Nếu có 2 bài viết cùng tên "Hướng dẫn xin visa Mỹ"

**Giải pháp:**
```sql
-- Chỉ lấy bài viết ĐẦU TIÊN (LIMIT 1)
SELECT ID FROM wp_posts 
WHERE post_title = 'Hướng dẫn xin visa Mỹ' 
LIMIT 1
```

→ Bài viết có ID nhỏ nhất sẽ được update

### B. Content Rỗng

**Vấn đề:** Row có `content` = ""

**Giải pháp:**
```php
if (empty($row['content'])) {
    return array(
        'status' => 'skipped',
        'message' => 'Row X: Content is empty'
    );
}
```

→ **SKIP** không import/update

### C. Bài Viết Trong Trash

**Vấn đề:** Bài viết đã xóa vào Trash

**Giải pháp:**
```sql
-- Loại trừ post_status = 'trash'
WHERE post_status != 'trash'
```

→ Sẽ tạo bài viết **MỚI** thay vì restore bài cũ

---

## ⚙️ 6. Cấu Hình & Điều Chỉnh

### A. Thay Đổi Tiêu Chí So Sánh

**Nếu muốn so sánh KHÔNG phân biệt chữ hoa/thường:**

```php
// File: includes/class-wpgsip-import-ajax.php
// Dòng 310-320

private static function find_existing_post_by_title($title, $post_type = 'post')
{
    global $wpdb;
    
    $post_id = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} 
        WHERE LOWER(post_title) = LOWER(%s)  -- Thêm LOWER() để case-insensitive
        AND post_type = %s 
        AND post_status != 'trash'
        LIMIT 1",
        $title,
        $post_type
    ));
    
    return $post_id ? intval($post_id) : false;
}
```

### B. Thêm Fuzzy Matching (tìm gần đúng)

**Nếu muốn tìm title tương tự (90% khớp):**

```php
// Sử dụng LIKE hoặc SOUNDEX
WHERE post_title LIKE CONCAT('%', %s, '%')
```

### C. Update Mode Options

**Hiện tại:** REPLACE all fields  
**Có thể thêm:** MERGE mode (giữ lại data cũ nếu data mới rỗng)

```php
// Example: Chỉ update nếu có data mới
if (!empty($row['meta_title'])) {
    $post_data['post_title'] = $row['meta_title'];
}
```

---

## 📊 7. Testing Checklist

### Test Case 1: Update Bài Viết Đã Tồn Tại
- [ ] Tạo bài viết với title: "Test Post 1"
- [ ] Trong Google Sheet, thêm row với `meta_title` = "Test Post 1"
- [ ] Import → Kiểm tra bài viết cũ được update (không tạo bài mới)
- [ ] Verify: Content, categories, tags được update đúng

### Test Case 2: Tạo Bài Viết Mới
- [ ] Trong Google Sheet, thêm row với `meta_title` = "Test Post 2" (chưa tồn tại)
- [ ] Import → Kiểm tra bài viết mới được tạo
- [ ] Verify: Post ID mới, taxonomies được assign đúng

### Test Case 3: Title Giống Nhau Nhưng Case Khác
- [ ] WordPress: "Test Post" (chữ T viết hoa)
- [ ] Sheet: "test post" (chữ thường)
- [ ] Import → Kiểm tra: Tạo bài mới hay update bài cũ?
- [ ] **Expected:** Tạo bài MỚI (vì case-sensitive)

### Test Case 4: Content Rỗng
- [ ] Sheet row có `meta_title` nhưng `content` = ""
- [ ] Import → Kiểm tra bài viết bị SKIP
- [ ] Verify: Log message "Content is empty"

### Test Case 5: Bài Viết Trong Trash
- [ ] Tạo bài "Test Post 3" → Xóa vào Trash
- [ ] Sheet có row với `meta_title` = "Test Post 3"
- [ ] Import → Kiểm tra: Tạo bài MỚI (không restore bài trong trash)

---

## 🎯 8. Kết Luận

### ✅ Logic Hiện Tại:

1. **Tìm bài viết:** So sánh `meta_title` (exact match, case-sensitive)
2. **Nếu tìm thấy:** UPDATE toàn bộ content + taxonomies
3. **Nếu không tìm thấy:** CREATE bài viết mới
4. **Nếu content rỗng:** SKIP không import

### 🔧 Điểm Cần Lưu Ý:

- ⚠️ So sánh title **chính xác 100%** - nếu khác 1 ký tự sẽ tạo bài mới
- ⚠️ Taxonomies bị **THAY THẾ** hoàn toàn khi update
- ⚠️ Nếu có nhiều bài cùng title, chỉ update bài có ID nhỏ nhất

### 💡 Đề Xuất Cải Tiến:

1. Thêm option cho user chọn update mode (REPLACE vs MERGE)
2. Thêm fuzzy matching để tìm title tương tự
3. Log chi tiết hơn về fields nào được update
4. Backup data cũ trước khi update

---

**File này giải thích:** Logic kiểm tra & update bài viết đã tồn tại trong plugin WP Google Sheets Import Pro.

**Người tạo:** GitHub Copilot  
**Ngày:** 2025-10-06  
**Version:** 1.0
