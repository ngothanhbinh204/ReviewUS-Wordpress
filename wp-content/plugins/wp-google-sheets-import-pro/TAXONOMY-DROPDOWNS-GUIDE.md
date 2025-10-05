# Hướng Dẫn: Chọn Category & Tags Cho Từng Bài Viết

## 🎯 Tính Năng Mới

Bạn có thể **chọn category và tags riêng cho từng bài viết** ngay trong bảng preview, thay vì phải chọn chung cho tất cả.

## 📊 Cách Hoạt Động

### 1. **Google Sheet có cột category/tags**
```
meta_title         | category              | tags
Bài viết 1         | Du lịch, Ẩm thực    | Hà Nội, Món ngon
Bài viết 2         | Công nghệ             | AI, Machine Learning
```

Khi Load Preview → Dropdowns sẽ **tự động chọn** các terms phù hợp dựa trên giá trị từ sheet.

### 2. **Google Sheet KHÔNG có cột category/tags**
```
meta_title         | content
Bài viết 1         | Nội dung về du lịch...
Bài viết 2         | Nội dung về công nghệ...
```

Bạn có thể **chọn thủ công** category/tags cho từng bài viết trong dropdown.

## 🖱️ Cách Sử Dụng

### Bước 1: Load Preview
1. Truy cập: **WP GS Import Pro → Import**
2. Chọn Post Type: **Post** (hoặc Thing To Do)
3. Click **"Load Preview"**

### Bước 2: Xem Bảng Preview
Bảng sẽ hiển thị các cột:
- ☑️ **Checkbox** - Chọn bài để import
- **Row** - Số thứ tự
- **Title** - Tiêu đề bài viết
- **Status** - Trạng thái từ sheet
- **Content** - Có nội dung hay không
- **📂 Categories** - Dropdown multi-select
- **🏷️ Tags** - Dropdown multi-select
- **Action** - Create/Update badge

### Bước 3: Chọn Taxonomies
Trong mỗi hàng, bạn có 2 cột dropdown:

#### **Categories Dropdown:**
- Hiển thị tất cả categories có sẵn trong WordPress
- Có thể chọn nhiều categories (Ctrl+Click hoặc Cmd+Click)
- Nếu sheet có cột "category", giá trị sẽ được auto-select
- Hiển thị text gốc từ sheet phía dưới: `From sheet: Du lịch, Ẩm thực`

#### **Tags Dropdown:**
- Hiển thị tất cả tags có sẵn trong WordPress
- Có thể chọn nhiều tags
- Tự động match với giá trị từ sheet nếu có

### Bước 4: Import
1. ☑️ **Check các rows** bạn muốn import
2. **Điều chỉnh categories/tags** nếu cần (có thể thêm, bớt, hoặc giữ nguyên)
3. Click **"Import Selected Items"**

## 💡 Ưu Tiên Taxonomy

Hệ thống xử lý taxonomy theo thứ tự ưu tiên:

### **Ưu tiên 1: Dropdown Selection** (Cao nhất)
→ Giá trị bạn chọn trong dropdown sẽ được sử dụng

### **Ưu tiên 2: Sheet Column Value**
→ Nếu không chọn gì, dùng giá trị từ cột "category" hoặc "tags" trong sheet

### **Ưu tiên 3: Default Selection** (Thấp nhất)
→ Nếu không có 1 và 2, dùng default taxonomy ở phần "Taxonomy Options" (nếu có)

## 🎨 UI Features

### Auto-Select từ Sheet
Nếu sheet có:
```
category: Du lịch, Ẩm thực
```
Dropdown sẽ tự động select 2 terms: **Du lịch** và **Ẩm thực**

### Hiển thị Original Value
Phía dưới dropdown sẽ hiển thị:
```
From sheet: Du lịch, Ẩm thực
```
→ Giúp bạn biết giá trị gốc từ sheet là gì

### Visual Cues
- **Dropdown có border xanh** khi focus
- **Multi-select** - giữ Ctrl/Cmd để chọn nhiều
- **Height: 60px** - Hiển thị 3-4 options cùng lúc
- **Width: 200px** - Đủ rộng để đọc term names

## 🔧 Technical Details

### Cách Matching
Khi auto-select từ sheet, hệ thống:
1. **Split** giá trị bằng dấu phẩy: `"Du lịch, Ẩm thực"` → `["Du lịch", "Ẩm thực"]`
2. **Trim** spaces: `" Du lịch "` → `"Du lịch"`
3. **Match** (case-insensitive):
   - By term name: `"Du lịch"` matches term with name "Du lịch"
   - By term slug: `"du-lich"` matches term with slug "du-lich"

### Term Creation
- Nếu term chưa tồn tại → **Tự động tạo mới**
- Slug được auto-generate: `"Du lịch"` → `"du-lich"`

## 📝 Google Sheet Format

### Ví dụ 1: Categories và Tags riêng biệt
```
Row | meta_title              | category              | tags
1   | Du lịch Hà Nội         | Du lịch, Việt Nam    | Hà Nội, Phố cổ
2   | Món ngon Sài Gòn       | Ẩm thực, Du lịch     | Sài Gòn, Street Food
3   | Công nghệ AI           | Công nghệ             | AI, Machine Learning
```

### Ví dụ 2: Combined taxonomy column
```
Row | meta_title              | category    | tags
1   | Bài viết A             | Du lịch     | Tag1, Tag2, Tag3
2   | Bài viết B             | Ẩm thực     | Tag4, Tag5
```

## 🆚 So Sánh: Trước vs Sau

### ❌ Trước đây:
- Chỉ hiển thị **text** từ sheet trong cột
- Không thể thay đổi
- Phải edit sau khi import
- Không biết terms có tồn tại không

### ✅ Bây giờ:
- Hiển thị **dropdown interactive**
- Chọn terms từ danh sách có sẵn
- Thấy tất cả categories/tags trong WordPress
- Auto-select dựa trên sheet value
- Thay đổi ngay trước khi import
- Visual feedback (From sheet: ...)

## 🎯 Use Cases

### Use Case 1: Override Sheet Values
**Tình huống:** Sheet có `category: "Du lịch"`, nhưng bạn muốn thêm "Ẩm thực"

**Giải pháp:**
1. Dropdown đã auto-select "Du lịch"
2. Ctrl+Click thêm "Ẩm thực"
3. Import → Post có 2 categories

### Use Case 2: Sheet Không Có Taxonomy
**Tình huống:** Sheet chỉ có `meta_title` và `content`, không có cột `category`

**Giải pháp:**
1. Dropdown rỗng (không có selection)
2. Chọn thủ công category cho từng row
3. Hoặc dùng Default Taxonomy ở phần dưới

### Use Case 3: Bulk Assign với Override
**Tình huống:** Muốn tất cả posts có category "Du lịch", nhưng một vài posts cần thêm "Ẩm thực"

**Giải pháp:**
1. Set Default Taxonomy = "Du lịch" (phần Taxonomy Options)
2. Override riêng cho rows cần "Ẩm thực" bằng dropdown

## ⚠️ Lưu Ý

1. **Multi-select:**
   - Hold **Ctrl** (Windows) hoặc **Cmd** (Mac) để chọn nhiều
   - Click without Ctrl → Deselect tất cả và chỉ chọn cái mới

2. **Auto-select chỉ match exact:**
   - `"Du lịch"` trong sheet → Match với term name "Du lịch"
   - `"du-lich"` trong sheet → Match với term slug "du-lich"
   - `"dulịch"` (typo) → Tạo term mới "dulịch"

3. **Case-insensitive:**
   - `"du lịch"` = `"Du Lịch"` = `"DU LỊCH"`

4. **Comma-separated:**
   - Dùng dấu phẩy để ngăn cách: `"Cat1, Cat2, Cat3"`
   - Spaces sẽ được trim tự động

## 🐛 Troubleshooting

### Dropdown không hiển thị terms
**Nguyên nhân:** Chưa có categories/tags trong WordPress

**Giải pháp:**
1. Go to Posts → Categories
2. Tạo ít nhất 1 category
3. Reload preview page

### Auto-select không đúng
**Nguyên nhân:** Term name trong sheet không match chính xác với WordPress

**Kiểm tra:**
- Sheet: `"Du lịch"` vs WordPress: `"Du Lịch"` (case khác)
- Sheet: `"du lich"` (có space) vs WP: `"du-lich"` (có dash)

**Giải pháp:** Chọn thủ công trong dropdown

### Term bị tạo trùng
**Nguyên nhân:** Viết sai chính tả hoặc có space thừa

**Ví dụ:**
- Sheet row 1: `"Du lịch"` → Tạo term "Du lịch"
- Sheet row 2: `"Du lịch "` (có space) → Tạo term mới "Du lịch "

**Giải pháp:** Làm sạch data trong sheet trước khi import

## 🚀 Best Practices

1. **Tạo terms trước:**
   - Tạo tất cả categories/tags trong WordPress trước
   - Import sẽ nhanh hơn và chính xác hơn

2. **Kiểm tra preview:**
   - Xem auto-select có đúng không
   - Adjust trước khi import

3. **Consistent naming:**
   - Dùng cùng 1 format trong sheet
   - VD: "Du lịch" (có dấu) thay vì mix "Du lich" / "Du lịch"

4. **Test với 1-2 rows:**
   - Check 1-2 rows đầu
   - Import thử
   - Verify taxonomies
   - Import toàn bộ nếu OK

## 📸 Screenshot Guide

### Preview Table với Dropdowns:
```
☑️ | Row | Title          | Status | Content | Categories ▼      | Tags ▼            | Action
---|-----|----------------|--------|---------|-------------------|-------------------|--------
☑️ | 1   | Du lịch HN     | Pub    | ✅ Yes  | [Du lịch     ▼]  | [Hà Nội      ▼]  | ➕ Create
☑️ | 2   | Món ngon SG    | Draft  | ✅ Yes  | [Ẩm thực     ▼]  | [Sài Gòn     ▼]  | ✏️ Update
   | 3   | Công nghệ AI   | Pub    | ✅ Yes  | [Công nghệ   ▼]  | [AI, ML      ▼]  | ➕ Create
```

### Category Dropdown (Expanded):
```
╔════════════════════════╗
║ ☑️ Du lịch             ║
║ ☑️ Ẩm thực             ║
║ ☐ Công nghệ            ║
║ ☐ Giải trí             ║
║ ☐ Thể thao             ║
╚════════════════════════╝
From sheet: Du lịch, Ẩm thực
```

---

✅ **Tính năng này giúp bạn:**
- Tiết kiệm thời gian (không cần edit sau import)
- Linh hoạt hơn (override được sheet values)
- Trực quan hơn (thấy tất cả options)
- Chính xác hơn (auto-match với terms có sẵn)
