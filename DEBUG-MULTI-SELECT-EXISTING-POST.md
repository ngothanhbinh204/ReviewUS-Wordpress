# 🐛 DEBUG & FIX: Multi-Select Taxonomies + Existing Post Check

## ✅ ĐÃ SỬA 2 VẤN ĐỀ

### 1️⃣ Multi-Select cho Taxonomies
### 2️⃣ Debug Existing Post Check (luôn hiện Create thay vì Update)

---

## 🔍 VẤN ĐỀ 1: MULTI-SELECT TAXONOMIES

### Hiện Tượng:
- ✅ Dropdown đã có attribute `multiple`
- ❌ Nhưng chỉ có thể chọn 1 giá trị
- ❌ Hoặc chọn nhiều nhưng không được submit đúng

### Nguyên Nhân:
JavaScript event handler dùng `.wpgsip-row-taxonomy` nhưng class thực tế là `.wpgsip-row-taxonomy-select`

### Đã Sửa:

**File:** `assets/js/import.js` (dòng 61-77)

**TRƯỚC:**
```javascript
// Taxonomy selection delegation
$(document).on('change', '.wpgsip-row-taxonomy', function() {  // ❌ Class sai
    var rowId = $(this).data('row-id');
    var taxonomy = $(this).data('taxonomy');
    var value = $(this).val();  // ❌ Không handle array
    
    if (!self.taxonomyData[rowId]) {
        self.taxonomyData[rowId] = {};
    }
    self.taxonomyData[rowId][taxonomy] = value;  // ❌ Single value
});
```

**SAU KHI FIX:**
```javascript
// Taxonomy selection delegation - handle multi-select
$(document).on('change', '.wpgsip-row-taxonomy-select', function() {  // ✅ Class đúng
    var rowId = $(this).data('row-id');
    var taxonomy = $(this).data('taxonomy');
    var selectedValues = $(this).val(); // ✅ Returns array for multi-select
    
    if (!self.taxonomyData[rowId]) {
        self.taxonomyData[rowId] = {};
    }
    // Store as array of term IDs
    self.taxonomyData[rowId][taxonomy] = selectedValues || [];  // ✅ Array
    
    console.log('Taxonomy selection changed:', {
        rowId: rowId,
        taxonomy: taxonomy,
        selectedValues: selectedValues  // ✅ Log để debug
    });
});
```

**Cải tiến:**
- ✅ Fix class selector: `.wpgsip-row-taxonomy` → `.wpgsip-row-taxonomy-select`
- ✅ Handle array: `selectedValues` là array khi multi-select
- ✅ Console log để debug: Xem giá trị được chọn trong Console (F12)

---

## 🔍 VẤN ĐỀ 2: EXISTING POST CHECK

### Hiện Tượng:
- ❌ Tất cả rows đều hiện "➕ Create" 
- ❌ Không bao giờ hiện "✏️ Update"
- ❌ Import luôn tạo bài mới, không update bài cũ

### Nguyên Nhân (Giả Thuyết):

#### Giả thuyết A: Title không khớp

**Sheet `meta_title`:**
```
"H1: Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời H2: Câu hỏi 1: Mục đích chuyến đi của bạn là gì?"
```

**WordPress `post_title` thực tế:**
```
"Top 10 câu hỏi phỏng vấn visa Mỹ"
```

→ **KHÔNG KHỚP** vì sheet có thêm "H1:", "H2:", "thường gặp và cách trả lời"

#### Giả thuyết B: Content Processor đang thay đổi title

```php
// File: includes/class-wpgsip-importer.php (line 320)
$post_title = !empty($processed_data['title']) 
    ? $processed_data['title']   // ← Title từ Content Processor
    : $row['meta_title'];         // ← Title từ sheet
```

→ Khi tạo post, dùng `$processed_data['title']` (đã xử lý)  
→ Khi tìm existing post, dùng `$row['meta_title']` (chưa xử lý)  
→ **MISMATCH!**

### Đã Thêm Debug Logging:

**File:** `includes/class-wpgsip-import-ajax.php` (dòng 308-357)

```php
private static function find_existing_post_by_title($title, $post_type = 'post')
{
    global $wpdb;
    
    // Debug: Log what we're searching for
    error_log('🔍 Searching for existing post:');
    error_log('  Title: ' . $title);
    error_log('  Post Type: ' . $post_type);
    
    $post_id = $wpdb->get_var($wpdb->prepare(
        "SELECT ID FROM {$wpdb->posts} 
        WHERE post_title = %s 
        AND post_type = %s 
        AND post_status != 'trash'
        LIMIT 1",
        $title,
        $post_type
    ));
    
    // Debug: Log result
    if ($post_id) {
        error_log('  ✅ Found existing post ID: ' . $post_id);
    } else {
        error_log('  ❌ No existing post found');
        
        // Try to find similar posts for debugging
        $similar = $wpdb->get_results($wpdb->prepare(
            "SELECT ID, post_title FROM {$wpdb->posts} 
            WHERE post_title LIKE %s 
            AND post_type = %s 
            AND post_status != 'trash'
            LIMIT 5",
            '%' . $wpdb->esc_like($title) . '%',
            $post_type
        ));
        
        if ($similar) {
            error_log('  📋 Similar posts found:');
            foreach ($similar as $post) {
                error_log('    - ID ' . $post->ID . ': ' . $post->post_title);
            }
        } else {
            error_log('  📋 No similar posts found either');
        }
    }
    
    return $post_id ? intval($post_id) : false;
}
```

**Công dụng:**
- ✅ Log title đang tìm kiếm
- ✅ Log post type
- ✅ Log kết quả (found/not found)
- ✅ Nếu không tìm thấy, tìm các bài viết SIMILAR (LIKE search)
- ✅ Hiển thị 5 bài viết gần đúng nhất để so sánh

---

## 🧪 HƯỚNG DẪN DEBUG

### Bước 1: Clear Cache

```powershell
# Xóa file debug.log cũ để dễ đọc
Remove-Item "C:\Users\ngoba\Local Sites\reviewus\app\public\wp-content\debug.log"
```

Hoặc chỉ cần mở file và xóa nội dung cũ.

---

### Bước 2: Test Multi-Select

1. **Reload Import page:** `Ctrl + F5`
2. **Load Preview**
3. **Chọn 1 row** (checkbox)
4. **Trong dropdown Categories:**
   - Click vào dropdown
   - **Ctrl + Click** (Windows) hoặc **Cmd + Click** (Mac) để chọn NHIỀU categories
   - Ví dụ: Chọn cả "Uncategorized" và "Visa Mỹ"
5. **Trong dropdown Tags:**
   - Chọn nhiều tags: "7 ngày khám phá", "câu hỏi phỏng vấn", "Tips bổ ích"
6. **Mở Console (F12)**
7. **Thay đổi selection** → xem console log:

```javascript
Taxonomy selection changed: {
    rowId: "2",
    taxonomy: "category",
    selectedValues: ["1", "42"]  // ✅ Array với 2 IDs
}

Taxonomy selection changed: {
    rowId: "2",
    taxonomy: "post_tag",
    selectedValues: ["43", "42", "51"]  // ✅ Array với 3 IDs
}
```

**Expected:** Console phải log ra ARRAY với nhiều term IDs

---

### Bước 3: Test Existing Post Check

#### 3.1. Tạo Test Post trong WordPress

1. **Vào WordPress Admin → Posts → Add New**
2. **Tạo bài viết với title CHÍNH XÁC từ sheet:**
   - Ví dụ: `"H1: Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời H2: Câu hỏi 1: Mục đích chuyến đi của bạn là gì?"`
   - **HOẶC**: Title ngắn gọn: `"Top 10 câu hỏi phỏng vấn visa Mỹ"`
3. **Publish** bài viết
4. **Copy title CHÍNH XÁC** (Ctrl+C từ title field)

#### 3.2. Load Preview Again

1. **Vào Import page**
2. **Click "Load Preview"**
3. **Kiểm tra:** Row đó có hiện "✏️ Update" không?

#### 3.3. Check Debug Log

**Mở file:** `wp-content/debug.log`

**Tìm dòng:** `🔍 Searching for existing post:`

**Ví dụ log:**

```log
[06-Oct-2025 12:00:00 UTC] 🔍 Searching for existing post:
[06-Oct-2025 12:00:00 UTC]   Title: H1: Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời H2: Câu hỏi 1: Mục đích chuyến đi của bạn là gì?
[06-Oct-2025 12:00:00 UTC]   Post Type: post
[06-Oct-2025 12:00:00 UTC]   ❌ No existing post found
[06-Oct-2025 12:00:00 UTC]   📋 Similar posts found:
[06-Oct-2025 12:00:00 UTC]     - ID 123: Top 10 câu hỏi phỏng vấn visa Mỹ
[06-Oct-2025 12:00:00 UTC]     - ID 124: H1: 7 ngày khám phá Hà Nội
```

**Phân tích:**
- ❌ **Exact match failed:** Title từ sheet không khớp 100%
- ✅ **Similar found:** Có bài viết gần đúng (ID 123)
- **Nguyên nhân:** Title từ sheet quá dài, có "H1:", "H2:", WordPress title ngắn hơn

---

## 🛠️ GIẢI PHÁP CHO VẤN ĐỀ 2

### Option A: Sửa Title trong Sheet (KHUYẾN NGHỊ)

**Trong Google Sheet:**
- Cột `meta_title` (Column B) chỉ nên chứa **TITLE NGẮN GỌN**
- Ví dụ: `"Top 10 câu hỏi phỏng vấn visa Mỹ"`
- **KHÔNG NÊN:** `"H1: Top 10 câu hỏi... H2: Câu hỏi 1..."`

**Lý do:**
- `meta_title` = **WordPress post title** (hiển thị trong list posts)
- `outline` (Column A) = **Dàn ý chi tiết** (có thể dài)
- `content` (Column F) = **Nội dung đầy đủ** (có H1, H2, paragraphs...)

### Option B: Sử dụng Fuzzy Matching (PHỨC TẠP)

Thay đổi logic so sánh từ **exact match** sang **similarity match**:

```php
// Thay vì:
WHERE post_title = %s

// Dùng:
WHERE post_title LIKE %s
// Hoặc dùng SOUNDEX, LEVENSHTEIN distance...
```

**Nhược điểm:**
- Có thể match nhầm bài viết khác
- Performance chậm hơn

### Option C: Dùng Custom Field để Track

Lưu `row_id` từ sheet vào post meta:

```php
update_post_meta($post_id, '_wpgsip_row_id', $row['row_id']);

// Khi tìm existing post:
$existing_post_id = get_posts([
    'post_type' => $post_type,
    'meta_key' => '_wpgsip_row_id',
    'meta_value' => $row['row_id'],
    'posts_per_page' => 1,
    'fields' => 'ids'
]);
```

**Ưu điểm:**
- Chính xác 100%
- Không phụ thuộc vào title

**Nhược điểm:**
- Cần import lần đầu để set meta
- Nếu xóa post meta thì mất tracking

---

## 📊 TEST RESULTS CHECKLIST

### Multi-Select Test:

- [ ] **Dropdown allows multiple selection:** Hold Ctrl/Cmd to select
- [ ] **Console logs array:** `selectedValues: ["1", "42", "43"]`
- [ ] **Import successful:** Post được tạo với NHIỀU categories/tags
- [ ] **WordPress admin verify:** Post → Edit → check Categories & Tags metabox

### Existing Post Test:

- [ ] **Created test post:** With EXACT title from sheet
- [ ] **Preview shows Update badge:** "✏️ Update" thay vì "➕ Create"
- [ ] **Debug log shows found:** `✅ Found existing post ID: 123`
- [ ] **Import updates post:** Content & taxonomies được update, không tạo bài mới

### If Not Found:

- [ ] **Debug log shows title searched:** Copy title từ log
- [ ] **Debug log shows similar posts:** Compare với WordPress post titles
- [ ] **Identify mismatch:** Title từ sheet vs WordPress có khác gì?
- [ ] **Fix title in sheet:** Hoặc implement Option C (custom field tracking)

---

## 🎯 EXPECTED BEHAVIOR SAU KHI FIX

### Multi-Select:

```javascript
// Console log khi chọn:
Taxonomy selection changed: {
    rowId: "2",
    taxonomy: "category", 
    selectedValues: ["1", "42"]  // ✅ Nhiều IDs
}
```

```php
// PHP receives:
row_taxonomies[2][category] = [1, 42]
row_taxonomies[2][post_tag] = [43, 44, 45]
```

```
// WordPress post after import:
Categories: Uncategorized, Visa Mỹ  ✅
Tags: 7 ngày khám phá, câu hỏi phỏng vấn, Tips bổ ích  ✅
```

### Existing Post Check:

```log
// Debug log:
🔍 Searching for existing post:
  Title: Top 10 câu hỏi phỏng vấn visa Mỹ
  Post Type: post
  ✅ Found existing post ID: 123
```

```
// Preview table:
| Row | Title | Action |
|-----|-------|--------|
| 2   | Top 10 câu hỏi... | ✏️ Update |  ← NOT "➕ Create"
```

```
// After import:
✅ Updated Post ID 123  ← NOT "Created Post ID 456"
```

---

## 💡 TROUBLESHOOTING

### Multi-Select vẫn không work:

**Check:**
1. Browser có support multi-select? (IE cũ có thể không support)
2. Console có lỗi JavaScript không?
3. `$(this).val()` có return array không? (log ra để xem)

**Fix tạm:**
Dùng plugin Select2 hoặc Chosen để enhance multi-select:
```javascript
$('.wpgsip-row-taxonomy-select').select2({
    width: '100%',
    placeholder: 'Select...'
});
```

### Existing Post vẫn không tìm thấy:

**Check debug log và:**

1. **Title có CHÍNH XÁC 100% không?**
   - Space thừa? `"Title "` vs `"Title"`
   - Line break? `"Title\n"` vs `"Title"`
   - Special characters? `"Café"` vs `"Cafe"`

2. **Post type có đúng không?**
   - Sheet có cột CPT? Giá trị là gì?
   - Code có check đúng post type không?

3. **Post status?**
   - Post có bị Draft/Trash không?
   - Query có WHERE `post_status != 'trash'`

---

## 📝 FILES ĐÃ SỬA

### 1. `includes/class-wpgsip-import-ajax.php`
**Changes:**
- ✅ Thêm extensive debug logging trong `find_existing_post_by_title()`
- ✅ Log: title, post type, found/not found
- ✅ Log: similar posts nếu không tìm thấy exact match
- **Lines:** 308-357

### 2. `assets/js/import.js`
**Changes:**
- ✅ Fix class selector: `.wpgsip-row-taxonomy` → `.wpgsip-row-taxonomy-select`
- ✅ Handle array cho multi-select: `selectedValues = $(this).val()`
- ✅ Store as array: `rowTaxonomies[rowId][taxonomy] = selectedValues || []`
- ✅ Add console.log để debug
- **Lines:** 61-77

---

## 🚀 ACTION ITEMS

1. **Clear debug.log:** Xóa hoặc rename file cũ
2. **Test multi-select:** Follow steps trên, check console
3. **Check debug log:** Tìm `🔍 Searching for existing post:`
4. **Analyze mismatch:** So sánh title từ log với WordPress
5. **Quyết định giải pháp:**
   - **Option A:** Sửa title trong sheet (KHUYẾN NGHỊ)
   - **Option B:** Implement fuzzy matching
   - **Option C:** Use custom field tracking
6. **Report back:** Paste debug log để tôi phân tích

---

**✅ DONE! HÃY TEST VÀ BÁO KẾT QUẢ!**

**File created:** 2025-10-06  
**Version:** 1.0  
**Status:** Debug mode enabled - waiting for test results
