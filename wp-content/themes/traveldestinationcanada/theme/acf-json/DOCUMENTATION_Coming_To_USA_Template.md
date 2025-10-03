# ACF Fields Documentation - Coming to USA Template

## 📋 Tổng quan

File ACF JSON này được tạo cho template **"Coming to USA"** với cấu trúc linh hoạt cho phép tạo nhiều loại content sections khác nhau.

---

## 🗂️ Cấu trúc Fields

### **1. Hero Banner (`hero_banner_coming`)**
**Type:** Group  
**Description:** Banner hero ở đầu trang

#### Sub-fields:
- **Title** (text) - Required
  - Heading chính của hero banner
  - Default: "Coming to USA"
  
- **Sub Title** (textarea)
  - Mô tả ngắn dưới title
  
- **Background Image** (image) - Required
  - Ảnh nền cho hero banner
  - Recommended size: 1920x800px
  - Formats: jpg, jpeg, png, webp

---

### **2. Introduction Section (`intro_section_coming`)**
**Type:** Group  
**Description:** Phần giới thiệu hiển thị sau breadcrumb (tương ứng với `basic-content-top`)

#### Sub-fields:
- **Title** (text)
  - Tiêu đề section
  - Default: "New Canada Strong Pass!"
  
- **Subtitle** (WYSIWYG - basic toolbar)
  - Subtitle có format HTML
  - Không upload media
  
- **Content** (WYSIWYG - full toolbar)
  - Nội dung chính
  - Cho phép upload media
  
- **Button** (link)
  - CTA button
  - Return format: array (url, title, target)

---

### **3. Content Sections (`sections`)**
**Type:** Repeater  
**Description:** Các sections động với 4 loại layout khác nhau

#### Common Fields (tất cả layouts):
- **Section Title** (text) - Required
  - Tiêu đề section
  - Tự động generate anchor slug cho subnav
  
- **Layout Type** (select) - Required
  - Chọn kiểu layout cho section này

#### Layout Options:

#### **3.1. Basic Content** (`basic-content`)
**Layout:** Image Left (1 col) + Text Right (2 cols)  
**Grid:** 1:2 (xl:grid-cols-3)

**Fields:**
- `content_basic_layout` (group)
  - **Subtitle** (text)
  - **Content** (WYSIWYG - full)
  - **Image** (image)
    - Recommended: 600x600px
    - Hiển thị bên trái, text bên phải

**Conditional Logic:**
```
IF layout == 'basic-content'
```

---

#### **3.2. Basic Content Large Image** (`basic-content-largeImage`)
**Layout:** Text Left (1 col) + Large Image Right (2 cols)  
**Grid:** 1:2 (xl:grid-cols-3)

**Fields:**
- `content_basic_layout` (group) - SAME as Basic Content
  - **Subtitle** (text)
  - **Content** (WYSIWYG - full)
  - **Image** (image)
    - Recommended: 1200x800px
    - Hiển thị bên phải (2 columns), text bên trái

**Conditional Logic:**
```
IF layout == 'basic-content' OR layout == 'basic-content-largeImage'
```

**Note:** Cả 2 layouts này dùng chung group `content_basic_layout` vì có cùng cấu trúc fields

---

#### **3.3. Two Column Content** (`basic-content-two-column`)
**Layout:** Text Left + Text Right (2 columns)  
**Grid:** 2 equal columns

**Fields:**
- `content_2_column_layout` (group)
  - **Subtitle** (text)
  - **Content Left Column** (WYSIWYG - full)
  - **Content Right Column** (WYSIWYG - full)

**Conditional Logic:**
```
IF layout == 'basic-content-two-column'
```

**Use case:** So sánh 2 thông tin, chia content thành 2 phần rõ ràng

---

#### **3.4. Grid Content** (`basic-grid-content`)
**Layout:** 2x2 Grid với icons  
**Grid:** lg:grid-cols-2 (max 4 items)

**Fields:**
- `content_grid_layout` (repeater - max 4 items)
  - **Title** (text) - Required
  - **Subtitle** (text)
  - **Icon/Image** (image)
    - Recommended: 200x200px (square)
    - Formats: jpg, jpeg, png, webp, svg
    - Hiển thị góc trên bên phải
  - **Content** (WYSIWYG - basic)

**Conditional Logic:**
```
IF layout == 'basic-grid-content'
```

**Use case:** Hiển thị 4 features/services với icons

---

### **4. Bottom Call-to-Action (`bottom_cta_coming`)**
**Type:** Group  
**Description:** Section CTA cuối trang (tương ứng với `basic-content-bottom`)

#### Sub-fields:
- **Title** (text)
  - Default: "Coming to USA"
  
- **Content** (textarea)
  - Mô tả ngắn
  
- **Button** (link)
  - CTA button
  - Return format: array

---

## 🎯 Location Rules

```json
"location": [
    [
        {
            "param": "page_template",
            "operator": "==",
            "value": "template-parts/coming-to-usa.php"
        }
    ]
]
```

Field group này chỉ hiển thị khi:
- **Page Template** = "Coming to USA Template"

---

## 🚫 Hidden Fields

Các fields WordPress mặc định bị ẩn:
- ✅ The Content (không cần vì dùng ACF)
- ✅ Excerpt
- ✅ Discussion/Comments
- ✅ Revisions
- ✅ Author
- ✅ Format
- ✅ Page Attributes
- ✅ Featured Image (không cần vì có hero banner)
- ✅ Categories/Tags

---

## 📦 Import Instructions

### **Method 1: ACF Sync (Recommended)**

1. **Copy file JSON:**
   ```
   Coming_To_USA_Template.json
   ```
   Vào thư mục:
   ```
   /wp-content/themes/[your-theme]/acf-json/
   ```

2. **Sync trong WordPress:**
   - Custom Fields → Tools → Sync
   - Tìm "Coming to USA - Template Fields"
   - Click "Sync"

### **Method 2: Manual Import**

1. **Copy toàn bộ nội dung JSON**
2. **WordPress Admin:**
   - Custom Fields → Tools
   - Tab "Import Field Groups"
   - Paste JSON content
   - Click "Import JSON"

---

## 🎨 Visual Layout Guide

### **Page Structure:**

```
┌─────────────────────────────────────┐
│      Hero Banner (full width)       │ ← hero_banner_coming
├─────────────────────────────────────┤
│          Breadcrumb                  │
├─────────────────────────────────────┤
│          Subnav Menu                 │ ← Auto-generated từ sections
├─────────────────────────────────────┤
│     Introduction Section             │ ← intro_section_coming
│   (Title + Subtitle + Content)      │
├─────────────────────────────────────┤
│                                      │
│        Dynamic Sections              │ ← sections (repeater)
│     (4 layout types available)      │
│                                      │
├─────────────────────────────────────┤
│    Bottom CTA (centered)             │ ← bottom_cta_coming
└─────────────────────────────────────┘
```

### **Layout Type Visuals:**

#### **1. Basic Content:**
```
┌──────────────────────────────────┐
│  ┌────┐  ┌────────────────────┐ │
│  │IMG │  │     TITLE          │ │
│  │    │  │     Subtitle       │ │
│  │    │  │     Content...     │ │
│  └────┘  └────────────────────┘ │
│  (1 col)     (2 cols)           │
└──────────────────────────────────┘
```

#### **2. Basic Content Large Image:**
```
┌──────────────────────────────────┐
│  ┌────────────┐  ┌────────────┐ │
│  │   TITLE    │  │            │ │
│  │   Subtitle │  │  LARGE     │ │
│  │   Content..│  │  IMAGE     │ │
│  └────────────┘  │            │ │
│    (1 col)       └────────────┘ │
│                     (2 cols)    │
└──────────────────────────────────┘
```

#### **3. Two Column Content:**
```
┌──────────────────────────────────┐
│           TITLE                   │
│           Subtitle                │
├─────────────────┬────────────────┤
│  Content Left   │ Content Right  │
│  Column         │ Column         │
│                 │                │
└─────────────────┴────────────────┘
```

#### **4. Grid Content:**
```
┌──────────────────────────────────┐
│           TITLE                   │
├─────────────────┬────────────────┤
│  ┌──────────┐  │  ┌──────────┐  │
│  │Title [🔧]│  │  │Title [🔧]│  │
│  │Subtitle  │  │  │Subtitle  │  │
│  │Content...│  │  │Content...│  │
│  └──────────┘  │  └──────────┘  │
├─────────────────┼────────────────┤
│  ┌──────────┐  │  ┌──────────┐  │
│  │Title [🔧]│  │  │Title [🔧]│  │
│  │Subtitle  │  │  │Subtitle  │  │
│  │Content...│  │  │Content...│  │
│  └──────────┘  │  └──────────┘  │
└─────────────────┴────────────────┘
```

---

## 💻 Code Examples

### **Get Hero Banner:**
```php
$hero_banner = get_field('hero_banner_coming');
if ($hero_banner) {
    echo '<h1>' . esc_html($hero_banner['title']) . '</h1>';
    echo '<p>' . esc_html($hero_banner['sub_title']) . '</p>';
    if ($hero_banner['image']) {
        echo '<img src="' . esc_url($hero_banner['image']['url']) . '">';
    }
}
```

### **Get Introduction Section:**
```php
$intro = get_field('intro_section_coming');
if ($intro && !empty($intro['title'])) {
    echo '<h2>' . esc_html($intro['title']) . '</h2>';
    echo '<div>' . $intro['sub_title'] . '</div>'; // Already sanitized WYSIWYG
    echo '<div>' . $intro['content'] . '</div>';
    
    if ($intro['button']) {
        echo '<a href="' . esc_url($intro['button']['url']) . '">';
        echo esc_html($intro['button']['title']);
        echo '</a>';
    }
}
```

### **Loop Through Sections:**
```php
if (have_rows('sections')) {
    while (have_rows('sections')) {
        the_row();
        
        $layout = get_sub_field('layout');
        $title = get_sub_field('title');
        $slug = sanitize_title($title);
        
        switch ($layout) {
            case 'basic-content':
            case 'basic-content-largeImage':
                $content = get_sub_field('content_basic_layout');
                // Use $content['sub_title'], $content['content'], $content['image']
                break;
                
            case 'basic-content-two-column':
                $content = get_sub_field('content_2_column_layout');
                // Use $content['content_left'], $content['content_right']
                break;
                
            case 'basic-grid-content':
                $items = get_sub_field('content_grid_layout');
                // Loop through $items
                break;
        }
    }
}
```

### **Get Bottom CTA:**
```php
$bottom_cta = get_field('bottom_cta_coming');
if ($bottom_cta && !empty($bottom_cta['title'])) {
    echo '<h2>' . esc_html($bottom_cta['title']) . '</h2>';
    echo '<p>' . esc_html($bottom_cta['content']) . '</p>';
    
    if ($bottom_cta['button']) {
        echo '<a href="' . esc_url($bottom_cta['button']['url']) . '">';
        echo esc_html($bottom_cta['button']['title']);
        echo '</a>';
    }
}
```

---

## 🔍 Subnav Auto-Generation

Subnav menu tự động tạo từ section titles:

```php
$sections = get_field('sections');
if ($sections && is_array($sections)) {
    foreach ($sections as $section) {
        $slug = sanitize_title($section['title']);
        $label = $section['title'];
        
        echo '<a href="#' . esc_attr($slug) . '">';
        echo esc_html($label);
        echo '</a>';
    }
}
```

**Output URLs:**
```
#section-1-slug
#section-2-slug
#section-3-slug
```

---

## ✅ Checklist After Import

- [ ] Import JSON vào ACF
- [ ] Tạo page mới hoặc chọn page existing
- [ ] Assign template "Coming to USA Template"
- [ ] Fill tất cả required fields:
  - [ ] Hero Banner Title
  - [ ] Hero Banner Image
  - [ ] At least 1 section with title
- [ ] Preview page
- [ ] Check subnav links
- [ ] Test responsive (mobile/tablet/desktop)
- [ ] Verify all images load correctly

---

## 🐛 Troubleshooting

### **Fields không hiển thị:**
1. Check template đã assign đúng chưa
2. Clear cache (if using caching plugin)
3. Re-sync ACF fields

### **Images không hiển thị:**
1. Check image URL trong ACF
2. Verify file tồn tại trong uploads folder
3. Check file permissions

### **Subnav không hoạt động:**
1. Check JavaScript loaded (script.js)
2. Verify section IDs match anchors
3. Check scroll behavior trong CSS

### **Layout bị lỗi:**
1. Check conditional logic trong ACF
2. Verify correct template part được load
3. Check grid CSS classes (Tailwind)

---

## 📚 Resources

- **ACF Documentation:** https://www.advancedcustomfields.com/resources/
- **Template Files Location:** `/wp-content/themes/[theme]/template-parts/`
- **Section Components:** `/template-parts/layout/sections/`

---

## 🔄 Version History

- **v1.0** (2025-10-04)
  - Initial release
  - 4 layout types
  - Full responsive support
  - Auto subnav generation

---

## 📝 Notes

- All WYSIWYG fields output raw HTML (already sanitized by ACF)
- Image fields return array format
- Link fields return array with: url, title, target
- Subnav anchors auto-generated from sanitize_title()
- Max 4 items for grid layout (optimal 2x2 display)

---

**Created by:** AI Assistant  
**Date:** October 4, 2025  
**Template Version:** 1.0
