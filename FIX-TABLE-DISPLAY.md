# ✅ FIX HIỂN THỊ TABLE PREVIEW

## 🔍 VẤN ĐỀ

Screenshot cho thấy:
- ❌ Cột **Title** đang hiển thị toàn bộ nội dung dài của bài viết (hàng trăm ký tự)
- ❌ Nội dung bị tràn ra ngoài, không có truncate
- ❌ Column mapping sai: đang map `row_id` vào column A, nhưng thực tế column A là `outline`

## 🎯 NGUYÊN NHÂN

### 1. Column Mapping Sai:

**TRƯỚC ĐÂY:**
```php
$column_mapping = array(
    0 => 'row_id',           // ❌ SAI: Column A không phải row_id
    1 => 'outline',          
    2 => 'meta_title',
    3 => 'meta_description',
    4 => 'keyword',
    5 => 'status',
    6 => 'content',
    7 => 'CPT',
    8 => 'category',
    9 => 'tags',
);
```

**Cấu trúc thực tế trong Google Sheet của bạn:**
```
Column A: outline (dàn ý bài viết)
Column B: meta_title (tiêu đề)
Column C: meta_description (mô tả)
Column D: keyword (từ khóa)
Column E: STATUS (trạng thái)
Column F: Content (nội dung chi tiết)
Column G: CPT (custom post type)
Column H: category (danh mục)
Column I: tags (thẻ tag)
```

→ Do map sai nên `meta_title` lấy được giá trị của `outline` (nội dung dài), làm table hiển thị sai!

---

## ✅ GIẢI PHÁP ĐÃ ÁP DỤNG

### 1️⃣ Sửa Column Mapping (PHP)

**File:** `includes/class-wpgsip-google-sheets.php`

**SAU KHI FIX:**
```php
// Map columns from Google Sheet
// Based on your sheet structure: outline, meta_title, meta_description, keyword, STATUS, Content, CPT, category, tags
$column_mapping = array(
    0 => 'outline',          // Column A (outline)
    1 => 'meta_title',       // Column B (meta_title) ✅ ĐÚNG!
    2 => 'meta_description', // Column C (meta_description)
    3 => 'keyword',          // Column D (keyword)
    4 => 'status',           // Column E (STATUS)
    5 => 'content',          // Column F (Content)
    6 => 'CPT',              // Column G (CPT)
    7 => 'category',         // Column H (category)
    8 => 'tags',             // Column I (tags)
);

// Note: row_id sẽ được auto-generate từ index + 2
$row_data = array(
    'row_id' => $index + 2, // Auto-generate: A2 = row 2, A3 = row 3...
);
```

**Thay đổi:**
- ✅ Column A (0) = `outline` (trước đây là `row_id`)
- ✅ Column B (1) = `meta_title` (trước đây là `outline`)
- ✅ `row_id` được auto-generate từ row number thay vì lấy từ sheet

---

### 2️⃣ Thêm Text Truncation & HTML Escape (JavaScript)

**File:** `assets/js/import.js`

**TRƯỚC ĐÂY:**
```javascript
html += '<td><strong>' + row.meta_title + '</strong>';
html += '<td>' + (row.content ? '✅ Yes' : '❌ Empty') + '</td>';
```

**VẤN ĐỀ:**
- Không có truncation → text dài tràn ra
- Không escape HTML → có thể gây lỗi hiển thị

**SAU KHI FIX:**
```javascript
// Display title with truncation
var title = row.meta_title || row.outline || '-';
var displayTitle = title.length > 100 ? title.substring(0, 100) + '...' : title;
html += '<td><strong>' + self.escapeHtml(displayTitle) + '</strong>';

// Display content status with character count
var hasContent = row.content && row.content.trim().length > 0;
var contentPreview = '';
if (hasContent) {
    contentPreview = '✅ Yes (' + row.content.length + ' chars)';
} else {
    contentPreview = '❌ Empty';
}
html += '<td>' + contentPreview + '</td>';
```

**Cải tiến:**
- ✅ **Truncate title** > 100 ký tự thành "Title text..." 
- ✅ **Escape HTML** để tránh lỗi hiển thị HTML tags
- ✅ **Show character count** cho content (ví dụ: "✅ Yes (1523 chars)")
- ✅ **Fallback logic**: Nếu `meta_title` rỗng → dùng `outline` → dùng "-"

---

### 3️⃣ Thêm Hàm escapeHtml

**File:** `assets/js/import.js`

```javascript
// Escape HTML to prevent XSS and display issues
escapeHtml: function(text) {
    if (!text) return '';
    var map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return String(text).replace(/[&<>"']/g, function(m) { return map[m]; });
}
```

**Công dụng:**
- Chuyển `<h2>Title</h2>` thành `&lt;h2&gt;Title&lt;/h2&gt;`
- Tránh HTML injection
- Hiển thị đúng text có ký tự đặc biệt

---

## 🧪 TESTING

### Bước 1: Clear Cache

```powershell
# Option 1: Trong WordPress Admin
WP GS Import Pro → Settings → Click "Test Connection"

# Option 2: Chờ 5 phút (cache sẽ hết hạn)
```

### Bước 2: Reload Import Page

1. Vào **WordPress Admin**
2. **WP GS Import Pro → Import**
3. **Hard refresh**: `Ctrl + F5` (Windows) hoặc `Cmd + Shift + R` (Mac)
4. Click **"Load Preview"**

### Bước 3: Kiểm Tra Kết Quả

**Cột Title phải hiển thị:**
```
✅ "H1: Top 10 câu hỏi phỏng vấn visa Mỹ..." (truncated)
   Existing: [tên bài viết cũ nếu có]

❌ KHÔNG PHẢI: "Meta description (Tối ưu hóa): Khám phá cách trả lời mười câu hỏi phỏng vấn..." (nội dung dài)
```

**Cột Content phải hiển thị:**
```
✅ Yes (1523 chars)
hoặc
❌ Empty
```

### Bước 4: Verify Debug Log

Mở `wp-content/debug.log`, tìm dòng "WPGSIP: Fetching data":

```log
[06-Oct-2025 11:00:00 UTC] WPGSIP: Fetching data from Sheet ID [your-sheet-id] Range Post1!A2:I
```

**Kiểm tra:**
- ✅ Range = `Post1!A2:I` (đúng tên sheet)
- ✅ Sheet ID đúng

---

## 📊 EXPECTED RESULTS

### Preview Table Structure:

| ☑ | Row | Title | Status | Content | Categories | Tags | Action |
|---|-----|-------|--------|---------|------------|------|--------|
| ☐ | 2 | **H1: Top 10 câu hỏi phỏng vấn...** | Meta description | ✅ Yes (1523 chars) | [Dropdown: Uncategorized ▼] | [Dropdown: 7 ngày khám phá ▼] | ➕ Create |
| ☐ | 3 | **H2: Câu hỏi 1: Mục đích...** | Meta description | ✅ Yes (987 chars) | [Dropdown: Visa Mỹ ▼] | [Dropdown: Tips bổ ích ▼] | ✏️ Update |

**Chi tiết:**
- ✅ **Title:** Hiển thị tối đa 100 ký tự + "..."
- ✅ **Content:** Hiển thị status + số ký tự
- ✅ **Categories/Tags:** Dropdown với terms từ WordPress
- ✅ **Action:** "➕ Create" (mới) hoặc "✏️ Update" (đã tồn tại)

---

## 🔧 TROUBLESHOOTING

### Vấn Đề 1: Title Vẫn Hiển Thị Sai

**Triệu chứng:** Title vẫn là nội dung dài (outline)

**Nguyên nhân:** Cache chưa clear

**Giải pháp:**
```php
// Thực hiện trong browser console (F12)
// Hoặc chờ 5-15 phút để cache tự hết hạn

// Cách 1: Hard refresh
Ctrl + F5

// Cách 2: Clear browser cache
Ctrl + Shift + Del → Clear cache

// Cách 3: Test Connection trong Settings
WP GS Import Pro → Settings → Test Connection button
```

---

### Vấn Đề 2: Cột Row_ID Không Đúng

**Triệu chứng:** Row ID không match với row number trong sheet

**Giải pháp:** 
- `row_id` = `index + 2` vì sheet bắt đầu từ A2
- Row 1 (header) bị skip
- Row 2 (first data) = row_id 2
- Row 3 = row_id 3...

---

### Vấn Đề 3: HTML Tags Hiển Thị Trong Title

**Triệu chứng:** Thấy `<h2>Title</h2>` thay vì "Title"

**Giải pháp:** Function `escapeHtml()` đã được thêm để xử lý

---

## 📂 FILES ĐÃ SỬA

### 1. `includes/class-wpgsip-google-sheets.php`
**Changes:**
- ✅ Sửa `$column_mapping` array
- ✅ Column A = `outline` (không phải `row_id`)
- ✅ Column B = `meta_title` (title đúng)
- ✅ `row_id` auto-generate từ index

**Lines changed:** ~102-111

---

### 2. `assets/js/import.js`
**Changes:**
- ✅ Thêm text truncation cho title (max 100 chars)
- ✅ Thêm character count cho content
- ✅ Thêm HTML escape với `escapeHtml()` function
- ✅ Thêm fallback logic: `meta_title || outline || '-'`

**Lines changed:** 
- ~151-166 (renderPreview function)
- ~437-449 (escapeHtml function - NEW)

---

## 🎯 VERIFICATION CHECKLIST

- [ ] **Cache cleared:** Wait 5 mins hoặc click Test Connection
- [ ] **Hard refresh:** Ctrl + F5 on Import page
- [ ] **Title correct:** Shows `meta_title` (max 100 chars), NOT full content
- [ ] **Content shows char count:** "✅ Yes (1523 chars)"
- [ ] **No HTML tags visible:** All HTML escaped properly
- [ ] **Categories/Tags dropdowns:** Show available terms
- [ ] **Action badge correct:** "➕ Create" or "✏️ Update"
- [ ] **Console no errors:** Press F12 → no red errors
- [ ] **Debug log shows correct range:** `Post1!A2:I`

---

## 🚀 NEXT STEPS

1. **Test Import:** Select 1-2 rows → Import → Verify posts created/updated
2. **Check WordPress Admin:** Posts → Verify title, content, taxonomies
3. **Debug if needed:** Check `wp-content/debug.log` for errors
4. **Report back:** Let me know if issues persist

---

## 💡 ADDITIONAL NOTES

### Column A vs Row ID:

**Google Sheet Structure:**
```
     A          B            C                D
1 | outline  | meta_title | meta_description | keyword ...
2 | "H1:..." | "Top 10..." | "Meta desc..."   | "visa" ...
3 | "H2:..." | "Câu hỏi..." | "Meta desc..."   | "phỏng vấn" ...
```

**Row ID Logic:**
- Row 1 = HEADER (skipped by range A2:I)
- Row 2 = First data → `row_id = 2`
- Row 3 = Second data → `row_id = 3`
- Row ID ≠ Column A value (Column A = outline content)

---

**✅ DONE! Hãy test và báo kết quả!**

---

**File created:** 2025-10-06  
**Version:** 1.0  
**Status:** Ready for testing
