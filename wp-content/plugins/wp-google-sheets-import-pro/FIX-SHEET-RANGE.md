# 🔧 FIX: Google Sheet Range - Missing Category & Tags Columns

## 🐛 Vấn Đề

### Debug Log Hiển Thị:
```
Sheet columns: Array
(
    [0] => row_id
    [1] => outline
    [2] => meta_title
    [3] => meta_description
    [4] => keyword
    [5] => status
    [6] => content
)
```

**Chỉ có 7 columns!** Nhưng Google Sheet có 10 columns:
- A: row_id
- B: outline  
- C: meta_title
- D: meta_description
- E: keyword
- F: STATUS
- G: Content
- **H: CPT** ❌ Missing!
- **I: category** ❌ Missing!
- **J: tags** ❌ Missing!

## 🔍 Nguyên Nhân

### Sheet Range Quá Hẹp
```php
$sheet_range = $this->settings['sheet_range'] ?? 'Sheet1!A2:F';
                                                            ^^
                                                    Chỉ đến column F!
```

**Range `A2:F`** nghĩa là:
- ✅ Lấy từ row 2 trở đi
- ✅ Lấy từ column A đến column F (6 columns)
- ❌ **KHÔNG lấy** columns G, H, I, J

### Kết Quả:
- Google Sheets API chỉ fetch 6 columns đầu
- Columns `CPT`, `category`, `tags` không được fetch
- `detect_taxonomy_columns()` không tìm thấy taxonomy columns
- → Return empty arrays

## ✅ Giải Pháp

### Fix: Mở Rộng Range
```php
// Before: A2:F (6 columns)
$sheet_range = $this->settings['sheet_range'] ?? 'Sheet1!A2:F';

// After: A2:I (9 columns) hoặc A2:J (10 columns)
$sheet_range = $this->settings['sheet_range'] ?? 'Sheet1!A2:I';
```

**Tôi đã update thành `A2:I`** để lấy 9 columns (A-I):
- A: row_id
- B: outline
- C: meta_title
- D: meta_description
- E: keyword
- F: STATUS
- G: Content
- H: CPT ✅
- I: category ✅
- (Bạn có column J: tags không? Nếu có thì dùng `A2:J`)

### Files Modified

**File: `includes/class-wpgsip-google-sheets.php`**

Changed in 3 places:
1. `fetch_data()` method - line 75
2. `test_connection()` method - line 132
3. `clear_cache()` method - line 160

```php
// All changed from:
'Sheet1!A2:F'

// To:
'Sheet1!A2:I'
```

## 📊 Expected Results

### After Fix - Sheet Columns Will Show:
```
Sheet columns: Array
(
    [0] => row_id
    [1] => outline
    [2] => meta_title
    [3] => meta_description
    [4] => keyword
    [5] => status
    [6] => content
    [7] => CPT          ✅ NEW!
    [8] => category     ✅ NEW!
    [9] => tags         ✅ NEW! (if you have column J)
)
```

### Detected Taxonomies Will Show:
```
Detected taxonomies: Array
(
    [category] => Array
        (
            [label] => Categories
            [column_name] => category
            [has_data] => true  ✅ Now has data!
        )

    [post_tag] => Array
        (
            [label] => Tags
            [column_name] => tags
            [has_data] => true  ✅ Now has data!
        )
)
```

### Console Log Will Show:
```javascript
{
    "taxonomies": {
        "category": {
            "label": "Categories",
            "column_name": "category",
            "has_data": true  ✅
        },
        "post_tag": {
            "label": "Tags",
            "column_name": "tag",
            "has_data": true  ✅
        }
    },
    "available_terms": {
        "category": [{term_id: 1, name: "Visa Mỹ"}],
        "post_tag": [{term_id: 1, name: "Tips bổ ích"}, ...]
    }
}
```

## 🎯 Google Sheets Range Format

### Understanding Range Notation:
```
Sheet1!A2:I
│      │ │
│      │ └── End column (I)
│      └──── Start row (2) - Skip header
└──────────── Sheet name
```

### Common Ranges:
```
A1:Z         - All columns A-Z, from row 1
A2:Z         - All columns A-Z, skip header (from row 2)
A2:F         - Columns A-F only, from row 2 (6 columns)
A2:I         - Columns A-I, from row 2 (9 columns) ✅ Current
A2:J         - Columns A-J, from row 2 (10 columns)
A:Z          - All rows, all columns (no limit)
```

### Your Google Sheet Structure:
```
Row 1: [Headers]
       A      B       C          D              E       F       G       H    I         J
Row 2: row_id outline meta_title meta_description keyword STATUS Content CPT category tags
Row 3: 1      ...     ...        ...            ...     ...     ...     post Cat1     Tag1
Row 4: 2      ...     ...        ...            ...     ...     ...     post Cat2     Tag2
```

**Need range:** `Sheet1!A2:J` (10 columns) hoặc `Sheet1!A2:I` (9 columns)

## 🧪 Testing Steps

### 1. Clear Browser Cache
- Press `Ctrl+Shift+Delete`
- Clear cached images and files
- Or use Incognito mode

### 2. Go to Import Page
- WordPress Admin → WP GS Import Pro → Import

### 3. Click "Load Preview"
- Button will fetch fresh data from Google Sheets
- With new range `A2:I`

### 4. Check Browser Console (F12)
```javascript
console.log(response.data);

// Should see:
taxonomies: Object {
    category: {...},
    post_tag: {...}
}
// NOT empty arrays anymore!
```

### 5. Check Debug Log
```bash
# View log
cat wp-content/debug.log

# Should see:
Sheet columns: Array (
    [7] => CPT
    [8] => category
    [9] => tags  # if column J exists
)

Detected taxonomies: Array (
    [category] => ...
    [post_tag] => ...
)
```

### 6. Check Preview Table
- Should see **Categories column** with dropdown
- Should see **Tags column** with dropdown
- Dropdowns should have terms from WordPress

## 💡 Important Notes

### 1. Nếu có column J (tags)
Update range thành `A2:J`:
```php
$sheet_range = $this->settings['sheet_range'] ?? 'Sheet1!A2:J';
```

### 2. Column Names Must Match
Taxonomy manager tìm theo tên columns:
```php
'column_names' => array('category', 'categories')  // Case-insensitive
'column_names' => array('tag', 'tags', 'post_tag')
```

Nếu sheet có tên khác (e.g., `danh_muc`), cần update `column_names` trong taxonomy-manager.

### 3. Cache Issue
Nếu vẫn không thấy columns mới:
1. Hard refresh browser (Ctrl+F5)
2. WordPress cache có thể lưu old data (15 phút)
3. Wait 15 mins hoặc clear transients manually

### 4. Check Sheet Permissions
Make sure service account có quyền đọc columns H, I, J.

## 📋 Checklist

- [x] Changed range from `A2:F` to `A2:I`
- [x] Updated in 3 methods (fetch_data, test_connection, clear_cache)
- [ ] Test: Go to Import page
- [ ] Test: Click "Load Preview"
- [ ] Verify: Check debug log shows 9 columns
- [ ] Verify: Console shows taxonomies as objects
- [ ] Verify: Preview table has category & tags dropdowns

## 🚀 Status

✅ **Range updated to A2:I**  
✅ **Will now fetch 9 columns (including CPT, category)**  
⏳ **Need to test: Reload Import page and click "Load Preview"**  

---

**Next:** Sau khi test, nếu bạn có column J (tags), update range thành `A2:J`!
