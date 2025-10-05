# ✅ ĐÃ FIX 2 VẤN ĐỀ CHÍNH

## 1️⃣ VẤN ĐỀ: Chỉ Lấy Được 7 Columns

### ❌ TRƯỚC ĐÂY (HARDCODED):

```php
// File: includes/class-wpgsip-google-sheets.php
// Hàm: fetch_data()

$data[] = array(
    'row_id' => $index + 2,
    'outline' => $row[0] ?? '',
    'meta_title' => $row[1] ?? '',
    'meta_description' => $row[2] ?? '',
    'keyword' => $row[3] ?? '',
    'status' => $row[4] ?? '',
    'content' => $row[5] ?? ''
    // ❌ THIẾU: CPT, category, tags (columns H, I, J)
);
```

**Vấn đề:** Code chỉ map 7 columns (0-6), không lấy columns mới!

---

### ✅ SAU KHI FIX (DYNAMIC):

```php
// File: includes/class-wpgsip-google-sheets.php
// Hàm: fetch_data()

// Define column mapping
$column_mapping = array(
    0 => 'row_id',           // A
    1 => 'outline',          // B  
    2 => 'meta_title',       // C
    3 => 'meta_description', // D
    4 => 'keyword',          // E
    5 => 'status',           // F
    6 => 'content',          // G
    7 => 'CPT',              // H ← MỚI
    8 => 'category',         // I ← MỚI
    9 => 'tags',             // J ← MỚI
);

// Map ALL available columns dynamically
foreach ($row as $col_index => $col_value) {
    $col_name = $column_mapping[$col_index] ?? 'column_' . $col_index;
    $row_data[$col_name] = $col_value ?? '';
}
```

**Giải pháp:** 
- ✅ Tự động map TẤT CẢ columns có trong sheet
- ✅ Hỗ trợ mở rộng thêm columns trong tương lai
- ✅ Nếu column không có trong mapping, đặt tên tự động: `column_7`, `column_8`...

---

## 2️⃣ LOGIC KIỂM TRA BÀI VIẾT ĐÃ TỒN TẠI

### 📋 Đã Tạo File: `EXISTING-POST-LOGIC.md`

File này giải thích chi tiết:

### A. Cách Kiểm Tra Bài Viết Đã Tồn Tại:

```php
// File: includes/class-wpgsip-import-ajax.php
// Hàm: find_existing_post_by_title()

private static function find_existing_post_by_title($title, $post_type = 'post')
{
    global $wpdb;
    
    $post_id = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} 
        WHERE post_title = %s          ← So sánh CHÍNH XÁC
        AND post_type = %s             ← Phân biệt post vs thing_to_do
        AND post_status != 'trash'     ← Loại trừ bài trong Trash
        LIMIT 1",                      ← Lấy bài đầu tiên
        $title,
        $post_type
    ));
    
    return $post_id ? intval($post_id) : false;
}
```

**Tiêu chí:**
- ✅ So sánh `post_title` **CHÍNH XÁC 100%** (case-sensitive)
- ✅ Kiểm tra đúng `post_type` (post hoặc thing_to_do)
- ✅ Loại trừ bài trong Trash
- ✅ Nếu có nhiều bài cùng title → lấy bài có ID nhỏ nhất

---

### B. Logic UPDATE vs CREATE:

```php
// File: includes/class-wpgsip-import-ajax.php
// Hàm: import_single_row()

// 1. Tìm bài viết đã tồn tại
$existing_post_id = self::find_existing_post_by_title($row['meta_title'], $post_type);

// 2. Kiểm tra và quyết định hành động
if ($existing_post_id) {
    // ✅ UPDATE existing post
    $post_id = self::update_post_with_taxonomy(
        $importer, 
        $existing_post_id, 
        $row, 
        $post_type, 
        $taxonomy_data, 
        $taxonomy_manager
    );
    $action = 'updated';
    $message = "Row X: Updated Post ID 123";
    
} else {
    // ✅ CREATE new post
    $post_id = self::create_post_with_taxonomy(
        $importer, 
        $row, 
        $post_type, 
        $taxonomy_data, 
        $taxonomy_manager
    );
    $action = 'created';
    $message = "Row X: Created Post ID 456";
}
```

**Quy trình:**
1. Tìm bài viết theo `meta_title`
2. Nếu tìm thấy → **UPDATE** bài viết đó
3. Nếu không tìm thấy → **CREATE** bài viết mới

---

### C. Dữ Liệu Được UPDATE:

Khi UPDATE bài viết, những thứ sau được cập nhật:

#### 📝 Post Data:
- ✅ `post_title` (title)
- ✅ `post_content` (nội dung - đã format bởi Content Processor)
- ✅ `post_status` (trạng thái)
- ✅ `post_excerpt` (meta description)

#### 🏷️ Post Meta:
- ✅ `_wpgsip_keyword` (SEO keyword)
- ✅ `_wpgsip_meta_description` (meta description)
- ✅ `_yoast_wpseo_focuskw` (Yoast focus keyword)
- ✅ `_yoast_wpseo_metadesc` (Yoast meta description)
- ✅ `_wpgsip_last_updated` (thời gian update)
- ✅ `_wpgsip_row_id` (row ID trong sheet)

#### 📂 Taxonomies:
- ✅ Categories (REPLACE tất cả)
- ✅ Tags (REPLACE tất cả)
- ✅ Custom taxonomies (REPLACE tất cả)

⚠️ **Lưu ý:** Taxonomies bị **THAY THẾ HOÀN TOÀN**, không merge!

---

## 🧪 HƯỚNG DẪN TEST

### Bước 1: Xóa Cache

```powershell
# Cách 1: Trong WordPress Admin
WP GS Import Pro → Settings → Click "Test Connection"

# Cách 2: Chờ 15 phút (cache tự hết hạn)

# Cách 3: Trực tiếp database (nếu có phpMyAdmin)
DELETE FROM wp_options 
WHERE option_name LIKE '_transient_wpgsip_data_%' 
OR option_name LIKE '_transient_timeout_wpgsip_data_%';
```

---

### Bước 2: Kiểm Tra Settings

1. Vào **WordPress Admin**
2. **WP GS Import Pro → Settings**
3. Kiểm tra trường **"Sheet Range"**:
   - Phải là: `Post1!A2:I` (9 columns)
   - Hoặc: `Post1!A2:J` (10 columns nếu có column J)
   - **KHÔNG PHẢI**: `Sheet1!A2:I` (sai tên sheet)
4. Click **"Save Settings"**

---

### Bước 3: Load Preview

1. Vào **WP GS Import Pro → Import**
2. Click **"Load Preview"** button
3. Mở **Console (F12)**
4. Kiểm tra response data:

```javascript
// Console log sẽ hiển thị:
{
    count: 5,
    data: [
        {
            row_id: 2,
            outline: "...",
            meta_title: "...",
            meta_description: "...",
            keyword: "...",
            status: "...",
            content: "...",
            CPT: "post",           // ← MỚI!
            category: "Visa Mỹ",   // ← MỚI!
            tags: "Tips bổ ích"    // ← MỚI!
        },
        // ...more rows
    ],
    taxonomies: {
        category: {
            label: "Categories",
            column_name: "category",
            has_data: true
        },
        post_tag: {
            label: "Tags",
            column_name: "tags",
            has_data: true
        }
    },
    available_terms: {
        category: [...],
        post_tag: [...]
    }
}
```

---

### Bước 4: Kiểm Tra Debug Log

Mở file: `wp-content/debug.log`

Tìm dòng gần nhất có text `"Sheet columns:"`:

```log
[06-Oct-2025 10:30:00 UTC] Sheet columns: Array
(
    [0] => row_id
    [1] => outline
    [2] => meta_title
    [3] => meta_description
    [4] => keyword
    [5] => status
    [6] => content
    [7] => CPT          ← NẾU THẤY DÒNG NÀY = ĐÃ FIX!
    [8] => category     ← NẾU THẤY DÒNG NÀY = ĐÃ FIX!
    [9] => tags         ← NẾU THẤY DÒNG NÀY = ĐÃ FIX!
)
```

---

### Bước 5: Test Import

1. Chọn **1-2 rows** trong preview table
2. Chọn **categories/tags** từ dropdowns
3. Click **"Import Selected Items"**
4. Kiểm tra kết quả:
   - ✅ Post được tạo hoặc update
   - ✅ Categories và tags được assign đúng
   - ✅ Check trong WordPress Admin → Posts

---

## 📊 TEST CASES

### Test Case 1: Lấy Đủ Columns

**Expected:**
- Debug log hiển thị **10 columns** (row_id → tags)
- Console response có `data[0].CPT`, `data[0].category`, `data[0].tags`

**Nếu KHÔNG thấy:**
- ❌ Settings → Sheet Range sai
- ❌ Cache chưa clear
- ❌ Google Sheet không có columns H, I, J

---

### Test Case 2: UPDATE Bài Viết Đã Tồn Tại

**Setup:**
1. Tạo 1 bài viết trong WordPress: `"Test Update Post"`
2. Trong Google Sheet, thêm row với `meta_title` = `"Test Update Post"`
3. Import row đó

**Expected:**
- ✅ Bài viết cũ được UPDATE (không tạo bài mới)
- ✅ Content thay đổi theo sheet
- ✅ Categories/tags thay đổi theo selection

**Verify:**
- Vào Edit Post → check "Last Modified" date
- Check post_id không đổi

---

### Test Case 3: CREATE Bài Viết Mới

**Setup:**
1. Trong Google Sheet, thêm row với `meta_title` = `"Brand New Post"`
2. Import row đó

**Expected:**
- ✅ Bài viết MỚI được tạo
- ✅ Post ID mới
- ✅ Taxonomies được assign đúng

---

### Test Case 4: Title Trùng Nhau (Case-Sensitive)

**Setup:**
1. WordPress có: `"Test Post"` (chữ T viết hoa)
2. Sheet có: `"test post"` (chữ thường)
3. Import

**Expected:**
- ✅ Tạo bài viết MỚI (vì title khác nhau do case-sensitive)
- Kết quả: 2 bài viết với title khác nhau

---

## 🎯 CHECKLIST

- [ ] **File đã sửa:** `includes/class-wpgsip-google-sheets.php`
- [ ] **Change:** Từ hardcode 7 columns → dynamic mapping 10 columns
- [ ] **Settings:** Sheet Range = `Post1!A2:I` (đúng tên sheet)
- [ ] **Cache:** Đã clear hoặc chờ 15 phút
- [ ] **Test:** Load Preview → thấy CPT, category, tags trong response
- [ ] **Test:** Import → taxonomies được assign đúng
- [ ] **Doc:** Đọc file `EXISTING-POST-LOGIC.md` để hiểu logic update

---

## 📚 FILES ĐÃ TẠO/SỬA

1. ✅ **`includes/class-wpgsip-google-sheets.php`**
   - Sửa hàm `fetch_data()` - dynamic column mapping

2. ✅ **`EXISTING-POST-LOGIC.md`** (MỚI)
   - Giải thích chi tiết logic kiểm tra & update bài viết
   - 8 sections với examples, test cases, edge cases

3. ✅ **`FIX-SUMMARY.md`** (file này)
   - Tóm tắt 2 vấn đề đã fix
   - Hướng dẫn test từng bước

---

## 💡 NEXT STEPS

1. **Test ngay:** Load Preview và kiểm tra Console response
2. **Verify:** Debug log có đủ 10 columns
3. **Import:** Test tạo mới và update bài viết
4. **Report:** Báo kết quả hoặc lỗi (nếu có)

---

**✅ DONE! HÃY TEST VÀ BÁO KẾT QUẢ!**
