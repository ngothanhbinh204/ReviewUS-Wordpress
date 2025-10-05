# ✅ HOÀN THÀNH: Taxonomy Dropdowns trong Preview Table

## 🎯 Yêu Cầu Đã Thực Hiện

User yêu cầu:
> "Tôi muốn trên table khi Preview sẽ hiển thị thêm các cột để chọn danh mục và tags cho từng bài viết nữa, cho trực quan"

## ✨ Những Gì Đã Làm

### 1. **Frontend Changes (JavaScript)**

#### File: `assets/js/import.js`

**a) Cập nhật `renderPreview()`:**
- ✅ Thay đổi hiển thị taxonomy columns từ **text** → **dropdown**
- ✅ Thêm widths cho columns để layout đẹp hơn
- ✅ Gọi method `renderTaxonomyDropdown()` cho mỗi cell
- ✅ Store `availableTerms` và `taxonomies` trong object

**b) Thêm method `renderTaxonomyDropdown()`:**
- ✅ Tạo multi-select dropdown cho mỗi row
- ✅ Auto-select terms dựa trên giá trị từ Google Sheet
- ✅ Match cả term name và term slug (case-insensitive)
- ✅ Hiển thị text "From sheet: ..." phía dưới dropdown
- ✅ Attributes: `data-row-id`, `data-taxonomy` để identify

**c) Cập nhật `startImport()`:**
- ✅ Thu thập `rowTaxonomies` từ tất cả dropdowns
- ✅ Structure: `{rowId: {taxonomy: [termIds]}}`
- ✅ Pass vào `runBatchImport()`

**d) Cập nhật `runBatchImport()`:**
- ✅ Thêm parameter `rowTaxonomies`
- ✅ Send qua AJAX với key `row_taxonomies`
- ✅ Maintain qua recursive calls

**e) Fix AJAX actions:**
- ✅ Change `wpgsip_import_preview` → `wpgsip_import_preview_enhanced`
- ✅ Change `wpgsip_import_execute_selective` → `wpgsip_import_selective`

### 2. **Backend Changes (PHP)**

#### File: `includes/class-wpgsip-import-ajax.php`

**a) Method `ajax_import_selective()`:**
- ✅ Nhận parameter `$row_taxonomies` từ POST
- ✅ Logic ưu tiên 3 tầng:
  1. **Row-specific selection** (dropdown) - Priority 1
  2. **Sheet column value** - Priority 2  
  3. **Default taxonomies** - Priority 3
- ✅ Pass taxonomy data cho mỗi row import

#### File: `includes/class-wpgsip-taxonomy-manager.php`

**a) Cập nhật `assign_taxonomies()`:**
- ✅ Detect input type: array of IDs vs array of names vs string
- ✅ Handle term IDs từ dropdown: `is_numeric($terms_input[0])`
- ✅ Handle term names: call `get_or_create_term()`
- ✅ Handle comma-separated strings từ sheet

**b) Thêm method `get_or_create_term()`:**
- ✅ Search by name first
- ✅ Search by slug if not found
- ✅ Create new term if doesn't exist
- ✅ Return term ID or false

### 3. **Styling (CSS)**

#### File: `assets/css/admin.css`

**Thêm styles:**
```css
.wpgsip-row-taxonomy-select {
    width: 100%;
    max-width: 200px;
    height: 60px;           /* Hiển thị 3-4 options */
    font-size: 12px;
    padding: 4px;
    border: 1px solid #8c8f94;
    border-radius: 3px;
}

.wpgsip-row-taxonomy-select:focus {
    border-color: #2271b1;   /* WordPress blue */
    box-shadow: 0 0 0 1px #2271b1;
}

.wpgsip-preview-table td {
    vertical-align: top;      /* Align content to top */
    padding: 10px;
}
```

### 4. **Documentation**

**Tạo file mới:**
- ✅ `TAXONOMY-DROPDOWNS-GUIDE.md` - Hướng dẫn chi tiết 250+ dòng

**Nội dung:**
- 🎯 Tính năng mới
- 📊 Cách hoạt động
- 🖱️ Cách sử dụng từng bước
- 💡 Ưu tiên taxonomy (3 tầng)
- 🎨 UI features
- 🔧 Technical details
- 📝 Google Sheet format examples
- 🆚 So sánh trước vs sau
- 🎯 Use cases thực tế
- ⚠️ Lưu ý quan trọng
- 🐛 Troubleshooting
- 🚀 Best practices

## 🔄 Flow Hoàn Chỉnh

### Preview Flow:
```
User clicks "Load Preview"
    ↓
AJAX: wpgsip_import_preview_enhanced
    ↓
Backend: fetch data + detect taxonomies + get available terms
    ↓
Return: {data, taxonomies, available_terms}
    ↓
Frontend: renderPreview()
    ↓
For each taxonomy column:
    renderTaxonomyDropdown()
        ↓
    - Create <select multiple>
    - Add all available terms as <option>
    - Auto-select based on sheet value
    - Add "From sheet: ..." text
    ↓
User sees dropdowns in table
```

### Import Flow:
```
User selects rows + adjusts dropdowns
    ↓
Click "Import Selected Items"
    ↓
Frontend: startImport()
    ↓
Collect rowTaxonomies from all .wpgsip-row-taxonomy-select
    ↓
AJAX: wpgsip_import_selective
    ↓
Backend: ajax_import_selective()
    ↓
For each row:
    Priority 1: Use rowTaxonomies[rowId] if exists
    Priority 2: Extract from sheet column
    Priority 3: Use default_taxonomies
    ↓
import_single_row() → assign_taxonomies()
    ↓
Check if term IDs (numeric) or term names
    ↓
Assign to post via wp_set_object_terms()
```

## 📊 Data Structure

### JavaScript (Frontend)
```javascript
// Row taxonomies collected from dropdowns
rowTaxonomies = {
    "1": {
        "category": ["12", "15"],      // Term IDs
        "post_tag": ["23", "45", "67"]
    },
    "2": {
        "category": ["12"],
        "post_tag": ["23"]
    }
}
```

### PHP (Backend)
```php
// After receiving from AJAX
$row_taxonomies = [
    '1' => [
        'category' => ['12', '15'],
        'post_tag' => ['23', '45', '67']
    ],
    '2' => [
        'category' => ['12'],
        'post_tag' => ['23']
    ]
];
```

## 🎨 UI Preview

### Before (Old):
```
| Categories          | Tags              |
|---------------------|-------------------|
| Du lịch, Ẩm thực   | Hà Nội, Món ngon  |  ← Just text
| Công nghệ           | AI, ML            |
```

### After (New):
```
| Categories ▼                      | Tags ▼                          |
|-----------------------------------|---------------------------------|
| [☑️ Du lịch  ☑️ Ẩm thực      ▼]  | [☑️ Hà Nội  ☑️ Món ngon    ▼]  |
| From sheet: Du lịch, Ẩm thực     | From sheet: Hà Nội, Món ngon    |
|                                   |                                 |
| [☑️ Công nghệ                 ▼]  | [☑️ AI  ☑️ ML               ▼]  |
| From sheet: Công nghệ             | From sheet: AI, ML              |
```

## ✅ Features Implemented

1. **✅ Multi-select Dropdowns:**
   - Ctrl/Cmd + Click để chọn nhiều
   - Height 60px (3-4 options visible)
   - Width 200px (readable)

2. **✅ Auto-Selection:**
   - Parse giá trị từ sheet (comma-separated)
   - Match với term name (case-insensitive)
   - Match với term slug
   - Auto-select matched terms

3. **✅ Visual Feedback:**
   - "From sheet: ..." text dưới dropdown
   - Focus state với border xanh
   - WordPress-style UI

4. **✅ Smart Priority:**
   - User selection → Sheet value → Default
   - Flexible và intuitive

5. **✅ Term Creation:**
   - Auto-create terms nếu chưa tồn tại
   - Proper slug generation

6. **✅ Per-Row Selection:**
   - Mỗi row có dropdown riêng
   - Có thể chọn khác nhau cho mỗi bài

## 🧪 Testing Checklist

### ✅ Manual Tests:
- [x] PHP syntax check passed
- [ ] Load preview with category/tags columns
- [ ] See dropdowns in table
- [ ] Auto-select works for sheet values
- [ ] Can manually change selections
- [ ] Import with selected taxonomies
- [ ] Verify terms assigned correctly
- [ ] Test with empty dropdowns
- [ ] Test with new term names
- [ ] Test priority system

### Test Cases:

**Case 1: Sheet có columns**
```
Sheet: category="Du lịch, Ẩm thực"
Expected: Dropdown auto-selects both terms
Action: Import
Result: Post has 2 categories
```

**Case 2: Override sheet value**
```
Sheet: category="Du lịch"
Action: Add "Ẩm thực" in dropdown
Result: Post has both categories (override)
```

**Case 3: Empty sheet column**
```
Sheet: No category column
Action: Select "Du lịch" in dropdown
Result: Post has selected category
```

**Case 4: New term**
```
Sheet: category="Term mới chưa tồn tại"
Expected: Dropdown doesn't auto-select
Action: Leave as is, import
Result: New term created automatically
```

## 📁 Modified Files Summary

```
✏️ assets/js/import.js                    (+80 lines)
    - renderPreview() updated
    - renderTaxonomyDropdown() added
    - startImport() updated
    - runBatchImport() updated
    - AJAX actions fixed

✏️ assets/css/admin.css                   (+25 lines)
    - .wpgsip-row-taxonomy-select styles
    - Focus states
    - Table cell alignment

✏️ includes/class-wpgsip-import-ajax.php  (+15 lines)
    - Receive row_taxonomies
    - 3-tier priority logic
    - Pass to importer

✏️ includes/class-wpgsip-taxonomy-manager.php  (+60 lines)
    - assign_taxonomies() enhanced
    - get_or_create_term() added
    - Handle term IDs vs names

📄 TAXONOMY-DROPDOWNS-GUIDE.md            (NEW +250 lines)
    - Complete user guide
```

## 🎉 Benefits

**Trước đây:**
- ❌ Chỉ xem được giá trị text
- ❌ Không biết term có tồn tại không
- ❌ Phải edit sau khi import
- ❌ Không thể override sheet values

**Bây giờ:**
- ✅ Dropdown interactive
- ✅ Thấy tất cả terms có sẵn
- ✅ Chọn trước khi import
- ✅ Override được sheet values
- ✅ Auto-match với existing terms
- ✅ Visual feedback rõ ràng
- ✅ Tiết kiệm thời gian

## 🚀 Next Steps

1. **Test với real data:**
   - Tạo vài categories/tags trong WordPress
   - Add 2 cột vào Google Sheet
   - Load preview
   - Verify dropdowns + auto-select
   - Import và check results

2. **Nếu có issue:**
   - Check browser console (F12)
   - Check PHP error log
   - Verify AJAX responses
   - Check term assignments in DB

3. **Enhancement ideas:**
   - Add "Create new term" option in dropdown
   - Batch update taxonomy for all selected rows
   - Color-code auto-selected vs manual
   - Add taxonomy preview before import

---

## ✅ Status: READY TO TEST

Tất cả code đã được implement và syntax-checked. 
Giờ bạn có thể test bằng cách:

1. **Reload WordPress admin** (Ctrl+F5)
2. **Go to Import page**
3. **Click "Load Preview"**
4. **Xem các cột category/tags có dropdown không**
5. **Test chọn và import**

Chúc may mắn! 🎉
