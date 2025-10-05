# WP Google Sheets Import Pro - Content Processing Guide

## 📝 Content Formatting Overview

Plugin tự động xử lý nội dung từ Google Sheets thành HTML chuẩn SEO với các tính năng:

- ✅ Tự động convert H1:, H2:, H3:,... thành heading tags
- ✅ Format **bold**, *italic*, __underline__, ~~strikethrough~~
- ✅ Xử lý danh sách (-, *, •, 1., 2.,...)
- ✅ Loại bỏ nội dung trong dấu ngoặc vuông [...]
- ✅ Tự động extract Meta Description và H1 cho SEO
- ✅ Tự động thêm Table of Contents (TOC)
- ✅ Format blockquotes, code blocks, links

## 📋 Cấu Trúc Nội Dung Trong Google Sheets

### Ví Dụ Content (Cột F):

```
Meta description (Tối ưu hóa): Khám phá cách trả lời mười câu hỏi phỏng vấn visa Mỹ phổ biến nhất nhằm tăng cơ hội thành công.

H1: [Cập nhật] Top 10 câu Hỏi Phỏng Vấn Visa Mỹ Thường Gặp

Visa Mỹ là một trong những thị thực quốc tế được nhiều người khao khát. **Quá trình xin visa thường không đơn giản**, đặc biệt là phần phỏng vấn lắm thử thách.

H2: Câu hỏi 1: Mục đích chuyến đi của bạn là gì?

Khi tham gia phỏng vấn xin visa đi Mỹ, câu hỏi đầu tiên bạn thường gặp chính là về mục đích chuyến đi.

- **Giữ câu trả lời ngắn gọn, dù bạn đang đi du lịch, học tập hay công tác.**
- Ví dụ: "Tôi muốn thăm quan các địa danh nổi tiếng"
- **Tóm tắt rõ ràng kế hoạch**

**Tips để trả lời tốt:**
- Trung thực và cụ thể là chìa khóa
- Nhấn mạnh sự chuẩn bị
- Khẳng định mọi thứ đã sẵn sàng

H2: Câu hỏi 2: Bạn sẽ ở lại bao lâu?

Thời gian lưu trú dự kiến cần tương thích với mục đích chuyến đi.

1. Nêu rõ thời gian dự định
2. Đảm bảo khớp với thông tin hồ sơ
3. Cam kết quay về đúng hạn

> Lưu ý: Luôn trung thực với thời gian lưu trú của bạn.
```

### Output HTML Sau Khi Xử Lý:

```html
<h1>Top 10 câu Hỏi Phỏng Vấn Visa Mỹ Thường Gặp</h1>

<p>Visa Mỹ là một trong những thị thực quốc tế được nhiều người khao khát. <strong>Quá trình xin visa thường không đơn giản</strong>, đặc biệt là phần phỏng vấn lắm thử thách.</p>

<div class="wpgsip-toc">
    <h2>Nội dung bài viết</h2>
    <ul>
        <li><a href="#heading-1">Câu hỏi 1: Mục đích chuyến đi của bạn là gì?</a></li>
        <li><a href="#heading-2">Câu hỏi 2: Bạn sẽ ở lại bao lâu?</a></li>
    </ul>
</div>

<h2 id="heading-1">Câu hỏi 1: Mục đích chuyến đi của bạn là gì?</h2>

<p>Khi tham gia phỏng vấn xin visa đi Mỹ, câu hỏi đầu tiên bạn thường gặp chính là về mục đích chuyến đi.</p>

<ul>
    <li><strong>Giữ câu trả lời ngắn gọn, dù bạn đang đi du lịch, học tập hay công tác.</strong></li>
    <li>Ví dụ: "Tôi muốn thăm quan các địa danh nổi tiếng"</li>
    <li><strong>Tóm tắt rõ ràng kế hoạch</strong></li>
</ul>

<p><strong>Tips để trả lời tốt:</strong></p>
<ul>
    <li>Trung thực và cụ thể là chìa khóa</li>
    <li>Nhấn mạnh sự chuẩn bị</li>
    <li>Khẳng định mọi thứ đã sẵn sàng</li>
</ul>

<h2 id="heading-2">Câu hỏi 2: Bạn sẽ ở lại bao lâu?</h2>

<p>Thời gian lưu trú dự kiến cần tương thích với mục đích chuyến đi.</p>

<ol>
    <li>Nêu rõ thời gian dự định</li>
    <li>Đảm bảo khớp với thông tin hồ sơ</li>
    <li>Cam kết quay về đúng hạn</li>
</ol>

<blockquote>
    <p>Lưu ý: Luôn trung thực với thời gian lưu trú của bạn.</p>
</blockquote>
```

## 🎯 Quy Tắc Xử Lý Content

### 1. Headings (Tiêu đề)

**Input:**
```
H1: Tiêu đề chính
H2: Tiêu đề phụ
H3: Tiêu đề nhỏ
```

**Output:**
```html
<h1>Tiêu đề chính</h1>
<h2>Tiêu đề phụ</h2>
<h3>Tiêu đề nhỏ</h3>
```

- Hỗ trợ H1 đến H6
- Nội dung trong [dấu ngoặc vuông] sẽ bị loại bỏ
- H1 sẽ được dùng làm title nếu Meta Title trống

### 2. Text Formatting

| Input | Output | Mô tả |
|-------|--------|-------|
| `**bold text**` | `<strong>bold text</strong>` | In đậm |
| `*italic text*` | `<em>italic text</em>` | In nghiêng |
| `__underline__` | `<u>underline</u>` | Gạch chân |
| `~~strikethrough~~` | `<del>strikethrough</del>` | Gạch ngang |
| `` `code` `` | `<code>code</code>` | Code inline |

### 3. Lists (Danh sách)

**Unordered List:**
```
- Item 1
- Item 2
* Item 3
• Item 4
```

**Output:**
```html
<ul>
    <li>Item 1</li>
    <li>Item 2</li>
    <li>Item 3</li>
    <li>Item 4</li>
</ul>
```

**Ordered List:**
```
1. First item
2. Second item
3. Third item
```

**Output:**
```html
<ol>
    <li>First item</li>
    <li>Second item</li>
    <li>Third item</li>
</ol>
```

### 4. Blockquotes (Trích dẫn)

**Input:**
```
> This is a quote
> Another line of quote
```

**Output:**
```html
<blockquote>
    <p>This is a quote</p>
</blockquote>
<blockquote>
    <p>Another line of quote</p>
</blockquote>
```

### 5. Links

**Markdown Style:**
```
[Link text](https://example.com)
```

**Plain URL:**
```
https://example.com
```

**Output:**
```html
<a href="https://example.com" target="_blank" rel="noopener">Link text</a>
<a href="https://example.com" target="_blank" rel="noopener">https://example.com</a>
```

### 6. Meta Description

**Input:**
```
Meta description: Đây là mô tả SEO của bài viết
Meta description (Tối ưu hóa): Mô tả khác
```

**Xử lý:**
- Tự động extract và lưu vào post excerpt
- Tự động fill vào Yoast SEO / Rank Math
- Không hiển thị trong nội dung bài viết

### 7. Nội Dung Trong Dấu Ngoặc Vuông

**Input:**
```
H1: [Cập nhật 2025] Tiêu đề bài viết
Đây là [nội dung không cần thiết] văn bản
```

**Output:**
```html
<h1>Tiêu đề bài viết</h1>
<p>Đây là văn bản</p>
```

- **Tất cả nội dung trong [ ] sẽ bị loại bỏ**
- Hữu ích để thêm notes, tags không muốn hiển thị

## ⚙️ Cấu Hình Content Processing

### Bật/Tắt Content Processing

Vào **GS Import Pro → Settings → Content Processing Options**:

1. **Enable Content Processing**: ✅
   - Bật để tự động format content
   - Tắt để giữ nguyên content như trong sheet

2. **Enable Table of Contents**: ✅
   - Tự động thêm TOC vào đầu bài
   - Chỉ thêm nếu đủ số lượng headings

3. **TOC Minimum Headings**: 3
   - Số heading tối thiểu để hiển thị TOC
   - Mặc định: 3

4. **TOC Title**: "Nội dung bài viết"
   - Tiêu đề của Table of Contents
   - Có thể custom theo ngôn ngữ

### Dùng Code (Hooks)

```php
// Tắt content processing cho một tenant
add_filter('wpgsip_before_create_post', function($post_data, $row, $tenant_id) {
    // Raw content without processing
    $post_data['post_content'] = $row['content'];
    return $post_data;
}, 10, 3);

// Custom content processor
add_filter('wpgsip_process_content', function($content, $row, $tenant_id) {
    // Your custom processing
    return $content;
}, 10, 3);

// Modify TOC options
add_filter('wpgsip_toc_options', function($options, $tenant_id) {
    $options['title'] = 'Contents';
    $options['min_headings'] = 5;
    return $options;
}, 10, 2);
```

## 🎨 Custom Styling

### CSS cho Table of Contents

Mặc định plugin đã có CSS, nhưng bạn có thể override:

```css
/* Custom TOC styles */
.wpgsip-toc {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 12px;
    padding: 30px;
}

.wpgsip-toc h2 {
    color: white;
    border-bottom: 2px solid rgba(255,255,255,0.3);
}

.wpgsip-toc ul li a {
    color: white;
}

.wpgsip-toc ul li a:hover {
    color: #f0f0f0;
}
```

### CSS cho Content

```css
/* Custom content styles */
.entry-content h2 {
    color: #2c3e50;
    border-left: 4px solid #3498db;
    padding-left: 15px;
}

.entry-content strong {
    background: linear-gradient(to right, #f39c12, #e74c3c);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
```

## 📊 Use Cases

### Use Case 1: Blog Content

**Sheet Structure:**
```
Meta description: SEO description here

H1: Main Blog Title

Introduction paragraph with **important keywords**.

H2: Section 1
Content for section 1...

H2: Section 2
- Point 1
- Point 2
```

**Result:** 
- Fully formatted blog post
- Auto TOC
- SEO optimized

### Use Case 2: Product Documentation

**Sheet Structure:**
```
H1: Product Name - User Guide

H2: Installation
1. Download the package
2. Extract files
3. Run installer

H2: Configuration
- Edit config.php
- Set database credentials

H2: Troubleshooting
> Note: Always backup before updating
```

**Result:**
- Professional documentation
- Clear structure
- Easy navigation with TOC

### Use Case 3: FAQ Page

**Sheet Structure:**
```
H1: Frequently Asked Questions

H2: Question 1: How to...?
Answer with **bold important info**.

H2: Question 2: What is...?
Answer here.
```

**Result:**
- Clean FAQ layout
- Auto TOC for quick navigation

## 🐛 Troubleshooting

### Content không được format

**Nguyên nhân:**
- Content Processing bị tắt trong settings
- Lỗi cú pháp trong content

**Giải pháp:**
1. Kiểm tra Settings → Content Processing Options
2. Đảm bảo "Enable Content Processing" được check
3. Test lại với content đơn giản

### TOC không hiển thị

**Nguyên nhân:**
- Không đủ số lượng headings
- TOC bị tắt

**Giải pháp:**
1. Kiểm tra "Enable Table of Contents"
2. Giảm "TOC Minimum Headings" xuống
3. Thêm nhiều H2, H3 vào content

### Nội dung [bracketed] vẫn hiển thị

**Nguyên nhân:**
- Dùng ngoặc vuông đặc biệt ([], 【】, etc.)
- Content processing disabled

**Giải pháp:**
- Dùng dấu ngoặc vuông standard: [ ]
- Enable content processing

## 🚀 Advanced Tips

### 1. Combine với n8n

Dùng n8n để:
- Generate content từ AI
- Tự động format content theo template
- Auto-add headings structure

### 2. Batch Processing

Với nhiều bài viết:
- Tạo template content trong sheet
- Copy/paste và điều chỉnh
- Import tất cả cùng lúc

### 3. Content Reusability

Tạo library các đoạn content formatted:
- Call-to-action boxes
- Tips sections
- Warning blocks

Copy/paste vào các bài viết khác nhau.

## 📚 API Reference

### WPGSIP_Content_Processor Methods

```php
$processor = new WPGSIP_Content_Processor();

// Process full content
$result = $processor->process_for_seo($raw_content);
// Returns: ['content', 'meta_description', 'title', 'excerpt']

// Extract meta description only
$meta = $processor->extract_meta_description($raw_content);

// Extract H1 only
$h1 = $processor->extract_h1($raw_content);

// Add TOC
$content_with_toc = $processor->add_table_of_contents($html_content, [
    'min_headings' => 3,
    'title' => 'Table of Contents'
]);

// Generate excerpt
$excerpt = $processor->generate_excerpt($html_content, 160);
```

## 📄 License

GPL v2 or later

---

**Questions?** Check DEVELOPER.md for more technical details.
