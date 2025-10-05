# ✅ FIX EXISTING POST DETECTION

## 🔍 VẤN ĐỀ ĐÃ PHÁT HIỆN

### Nguyên Nhân Root Cause:

**Vấn đề:** Plugin luôn hiển thị "➕ Create" thay vì "✏️ Update" cho bài viết đã tồn tại.

**Root Cause:** MISMATCH giữa title dùng để TẠO post vs title dùng để TÌM KIẾM post!

```
❌ LOGIC CŨ (SAI):
┌─────────────────────────────────────────────────────────────┐
│ 1. CREATE POST:                                             │
│    - Lấy outline (Column A): "H1: Title..."                │
│    - Extract H1 → post_title = "Title..."                   │
│    - WordPress post được tạo với title "Title..."           │
│                                                              │
│ 2. FIND EXISTING POST:                                      │
│    - Tìm bằng meta_title (Column B): "Meta description..." │
│    - Không tìm thấy → Tạo mới → DUPLICATE!                 │
└─────────────────────────────────────────────────────────────┘
```

**Kết quả:**
- WordPress có post với title: `"Hướng dẫn xin visa du lịch Thái Lan chi tiết 2025"`
- Plugin tìm kiếm: `"Meta description (Tối ưu hóa): Khám phá cách..."`
- KHÔNG KHỚP → Tạo bài mới → DUPLICATE!

---

## ✅ GIẢI PHÁP ĐÃ ÁP DỤNG

### 1️⃣ Thêm Hàm `extract_post_title()`

**File:** `includes/class-wpgsip-import-ajax.php`

**Chức năng:** Extract title theo ĐÚNG logic của importer (process_for_seo)

```php
private static function extract_post_title($row)
{
    // 1. TRY: Extract H1 from outline
    if (!empty($row['outline'])) {
        // Pattern: "H1: Title text H2:..."
        if (preg_match('/^H1:\s*(.+?)(?:\s+H2:|$)/is', $outline, $matches)) {
            $title = trim($matches[1]);
            return $title; // ← ĐÚNG với logic importer!
        }
        
        // Fallback: First line of outline
        $first_line = trim($lines[0]);
        $first_line = preg_replace('/^H1:\s*/i', '', $first_line);
        if (!empty($first_line)) {
            return $first_line;
        }
    }
    
    // 2. FALLBACK: meta_title
    return $row['meta_title'];
}
```

**Logic:**
- ✅ Priority 1: Extract H1 từ outline (giống importer)
- ✅ Priority 2: First line của outline
- ✅ Priority 3: Fallback to meta_title

---

### 2️⃣ Cập Nhật `ajax_import_preview_enhanced()`

**Trước đây:**
```php
// ❌ SAI: Dùng meta_title để tìm
$title = $row['meta_title'];
$existing_post_id = self::find_existing_post_by_title($title, $post_type);
```

**Sau khi fix:**
```php
// ✅ ĐÚNG: Dùng actual title (extracted từ outline)
$actual_title = self::extract_post_title($row);
$existing_post_id = self::find_existing_post_by_title($actual_title, $post_type);
```

---

### 3️⃣ Cập Nhật `import_single_row()`

**Trước đây:**
```php
// ❌ SAI: Dùng meta_title
$existing_post_id = self::find_existing_post_by_title($row['meta_title'], $post_type);
```

**Sau khi fix:**
```php
// ✅ ĐÚNG: Extract title theo logic importer
$actual_post_title = self::extract_post_title($row);
$existing_post_id = self::find_existing_post_by_title($actual_post_title, $post_type);
```

---

## 🎯 LOGIC THỐNG NHẤT

```
✅ LOGIC MỚI (ĐÚNG):
┌─────────────────────────────────────────────────────────────┐
│ EXTRACT TITLE (extract_post_title):                         │
│    1. Try: Extract H1 from outline                          │
│    2. Fallback: First line of outline                       │
│    3. Fallback: meta_title                                  │
│                                                              │
│ PREVIEW (ajax_import_preview_enhanced):                     │
│    - Use extract_post_title() → "Title..."                  │
│    - Search WordPress → FOUND!                              │
│    - Display: ✏️ Update                                     │
│                                                              │
│ IMPORT (import_single_row):                                 │
│    - Use extract_post_title() → "Title..."                  │
│    - Search WordPress → FOUND!                              │
│    - Action: UPDATE (not create)                            │
│                                                              │
│ CREATE POST (importer):                                     │
│    - Process content → Extract H1 → "Title..."             │
│    - WordPress post_title = "Title..."                      │
└─────────────────────────────────────────────────────────────┘
```

**Kết quả:** TẤT CẢ đều dùng CÙNG 1 title → KHỚP → Update đúng!

---

## 🧪 TESTING

### Bước 1: Clear Cache

```bash
# Xóa WordPress transients cache
# Trong WordPress Admin hoặc chờ 5 phút
```

### Bước 2: Load Preview

1. Vào **WP GS Import Pro → Import**
2. Click **"Load Preview"**
3. Kiểm tra **Action column**

**Expected Results:**

| Row | Title | Action | Ghi Chú |
|-----|-------|--------|---------|
| 2 | Hướng dẫn xin visa... | ✏️ **Update** | Bài viết đã tồn tại |
| 3 | Top 10 câu hỏi phỏng vấn... | ✏️ **Update** | Bài viết đã tồn tại |
| 5 | Bài viết hoàn toàn mới | ➕ **Create** | Chưa có trong WP |

---

### Bước 3: Check Debug Log

Mở `wp-content/debug.log`, tìm log mới nhất:

**Expected Output:**
```log
🔍 Searching for existing post:
  Title: Hướng dẫn xin visa du lịch Thái Lan chi tiết 2025
  Post Type: post
📝 Extracted title from outline H1: Hướng dẫn xin visa du lịch Thái Lan chi tiết 2025
  ✅ Found existing post ID: 123
```

**Nếu thấy:**
```log
📝 Using meta_title as fallback: Meta description (Tối ưu hóa)...
❌ No existing post found
```
→ Outline không có H1, cần kiểm tra cấu trúc sheet!

---

### Bước 4: Test Import

1. **Select 1 row** có Action = "✏️ Update"
2. Click **"Import Selected Items"**
3. Kiểm tra kết quả:
   - ✅ Message: "Row X: **Updated** Post ID 123"
   - ✅ KHÔNG có post mới được tạo
   - ✅ Post cũ được cập nhật content

---

## 📊 VERIFICATION CHECKLIST

- [ ] **Preview loads:** No errors in console
- [ ] **Action badges correct:**
  - ✏️ Update: Cho bài viết đã tồn tại
  - ➕ Create: Cho bài viết mới
- [ ] **Debug log shows:**
  - `📝 Extracted title from outline H1: [title]`
  - `✅ Found existing post ID: [id]` (cho bài đã tồn tại)
- [ ] **Import works:**
  - Update: Không tạo duplicate
  - Create: Tạo bài mới đúng
- [ ] **No duplicates:** Check WordPress → Posts (không có bài trùng)

---

## 🔍 TROUBLESHOOTING

### Issue 1: Vẫn Hiển Thị "Create" Cho Bài Đã Tồn Tại

**Triệu chứng:** Action vẫn là "➕ Create"

**Nguyên nhân có thể:**

1. **Outline không có H1:**
```
Check: Xem Column A (outline) có bắt đầu bằng "H1:" không?

❌ Wrong: "Meta description..."
✅ Right: "H1: Title text H2: Subtitle..."
```

2. **H1 title khác với WordPress post_title:**
```
Sheet outline: "H1: Hướng dẫn xin visa Thái Lan"
WordPress post_title: "Hướng dẫn xin visa Thái Lan chi tiết 2025"
→ KHÔNG KHỚP → Tạo mới
```

**Giải pháp:**
- Check debug log xem title extracted là gì
- So sánh với WordPress post_title (vào Edit Post)
- Adjust H1 trong sheet để khớp

---

### Issue 2: Debug Log Không Hiển Thị Title Extraction

**Triệu chứng:** Không thấy log `📝 Extracted title...`

**Nguyên nhân:** Cache chưa clear

**Giải pháp:**
```php
// Clear transients manually
// Hoặc chờ 5-15 phút để cache expire
```

---

### Issue 3: Regex Không Match H1

**Triệu chứng:** Log hiển thị `📝 Using meta_title as fallback`

**Nguyên nhân:** H1 pattern trong outline khác với regex

**Debug:**
```
Check outline structure:
- "H1: Title" ✅ MATCH
- "H1:Title" ✅ MATCH (no space)
- "h1: Title" ✅ MATCH (case-insensitive)
- "Title H2: Subtitle" ❌ NO MATCH (missing H1:)
```

**Giải pháp:** Adjust outline format trong sheet

---

## 💡 ADDITIONAL NOTES

### Column Structure Reminder:

```
Google Sheet Structure:
Column A: outline - "H1: Title H2: Subtitle..."
Column B: meta_title - "Meta description (Tối ưu hóa)..."
Column C: meta_description
...
```

### Title Extraction Priority:

```
1. H1 from outline ← HIGHEST (matches importer logic)
2. First line of outline
3. meta_title ← FALLBACK
```

### WordPress Post Title Source:

```
When creating post, importer:
1. Process content with process_for_seo()
2. Extract H1 from outline → $processed_data['title']
3. Use extracted H1 as post_title
```

**Vậy nên:** `find_existing_post_by_title()` cũng PHẢI dùng cùng extracted title!

---

## 🎯 EXPECTED BEHAVIOR

### Scenario 1: Bài Viết Đã Tồn Tại

```
Sheet:
  Row 2: outline = "H1: Hướng dẫn xin visa Thái Lan..."
  
WordPress:
  Post ID 123: post_title = "Hướng dẫn xin visa Thái Lan..."
  
Plugin:
  1. Extract title from outline H1 → "Hướng dẫn xin visa Thái Lan..."
  2. Search WordPress → FOUND ID 123
  3. Preview: ✏️ Update
  4. Import: UPDATE post 123 (no duplicate)
```

### Scenario 2: Bài Viết Mới

```
Sheet:
  Row 5: outline = "H1: Kinh nghiệm du lịch Nhật Bản..."
  
WordPress:
  No post with this title
  
Plugin:
  1. Extract title from outline H1 → "Kinh nghiệm du lịch Nhật Bản..."
  2. Search WordPress → NOT FOUND
  3. Preview: ➕ Create
  4. Import: CREATE new post
```

---

## 📂 FILES CHANGED

### 1. `includes/class-wpgsip-import-ajax.php`

**Changes:**
- ✅ Added `extract_post_title()` function (lines ~365-405)
- ✅ Updated `ajax_import_preview_enhanced()` line ~84
- ✅ Updated `import_single_row()` line ~237

**Total:** ~50 lines changed/added

---

## ✅ SUCCESS CRITERIA

- [ ] **Preview:** Action badges show "✏️ Update" for existing posts
- [ ] **Import:** No duplicates created
- [ ] **Debug log:** Shows extracted title matching WordPress post_title
- [ ] **Consistency:** Same title used for search and create

---

**✅ DONE! TEST VÀ BÁO KẾT QUẢ!**

**File created:** 2025-10-06  
**Version:** 1.0  
**Status:** Ready for testing
