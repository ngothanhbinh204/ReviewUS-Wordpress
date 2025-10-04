# WP Google Sheets Import Pro - Hướng Dẫn Nhanh (Tiếng Việt)

## 📋 Tổng Quan

Plugin WordPress chuyên nghiệp để nhập bài viết từ Google Sheets với tích hợp n8n webhook để tự động tạo nội dung. Thiết kế hỗ trợ multi-tenant và có khả năng mở rộng cao.

## 🚀 Cài Đặt Nhanh

### Bước 1: Cài Đặt Plugin

```bash
cd wp-content/plugins/wp-google-sheets-import-pro
composer install
```

Sau đó kích hoạt plugin trong WordPress Admin.

### Bước 2: Cấu Hình Google Sheets

1. **Tạo Service Account:**
   - Truy cập [Google Cloud Console](https://console.cloud.google.com/)
   - Tạo project mới
   - Bật Google Sheets API
   - Tạo Service Account và tải JSON credentials

2. **Chia Sẻ Sheet:**
   - Mở Google Sheet của bạn
   - Share với email service account (trong file JSON)
   - Quyền: Viewer hoặc Editor

3. **Lấy Thông Tin:**
   - Sheet ID: Lấy từ URL (phần giữa `/d/` và `/edit`)
   - Range: Ví dụ `Sheet1!A2:F` (từ hàng 2, cột A đến F)

### Bước 3: Cấu Hình Plugin

1. Vào **GS Import Pro → Settings**
2. Dán các thông tin:
   - **Google Sheet ID**
   - **Sheet Range** 
   - **Service Account JSON** (toàn bộ nội dung file)
3. Click **Save Settings**
4. Click **Test Connection** để kiểm tra

### Bước 4: Cấu Hình n8n (Tùy Chọn)

Nếu muốn tự động tạo nội dung:

1. **Tạo Workflow trong n8n:**
   - Thêm node Webhook (POST)
   - Thêm logic tạo nội dung (AI: ChatGPT/Claude)
   - Cập nhật Google Sheet với nội dung mới
   - Kích hoạt workflow

2. **Cấu Hình trong Plugin:**
   - Bật "Enable n8n Webhook"
   - Dán Webhook URL
   - Đặt thời gian chờ (20-30 giây)
   - Save Settings

## 📊 Cấu Trúc Google Sheet

Cấu trúc bảng phải theo định dạng:

| A (Outline) | B (Meta Title) | C (Meta Description) | D (Keyword) | E (STATUS) | F (Content) |
|-------------|----------------|---------------------|-------------|------------|-------------|
| Dàn ý bài 1 | Tiêu đề SEO 1 | Mô tả SEO 1 | từ khóa 1 | 01/10/2025 | Nội dung đầy đủ |
| Dàn ý bài 2 | Tiêu đề SEO 2 | Mô tả SEO 2 | từ khóa 2 | 02/10/2025 | Nội dung đầy đủ |

**Giải thích:**
- **A (Outline)**: Dàn ý để tạo nội dung qua n8n
- **B (Meta Title)**: Tiêu đề bài viết và SEO title
- **C (Meta Description)**: Mô tả ngắn và meta description
- **D (Keyword)**: Từ khóa chính (có thể nhiều từ, phân cách bằng dấu phẩy)
- **E (STATUS)**: Trạng thái tùy chọn để lọc
- **F (Content)**: Nội dung bài viết đầy đủ

**Lưu ý:**
- Nếu cột F (Content) trống và n8n được bật → Plugin sẽ trigger webhook để tạo nội dung
- Sau khi trigger, plugin chờ thời gian đã cấu hình rồi fetch lại sheet để lấy content mới

## 🎯 Sử Dụng

### Nhập Thủ Công

1. Vào **GS Import Pro → Import**
2. Click **Load Preview** để xem trước dữ liệu
3. Click **Start Import** để bắt đầu
4. Theo dõi tiến trình và kết quả

### Nhập Tự Động (Scheduled)

1. Vào **GS Import Pro → Settings**
2. Bật **Enable Automatic Import**
3. Chọn tần suất (Hourly/Daily/Weekly)
4. Save Settings

Plugin sẽ tự động import theo lịch đã cấu hình.

### Xem Bài Viết Đã Nhập

Vào **GS Import Pro → Imported Posts** để xem danh sách các bài đã import.

### Xem Logs

Vào **GS Import Pro → Logs** để xem chi tiết hoạt động import, lỗi, và thông báo.

## ⚙️ Tính Năng Nâng Cao

### Multi-Tenant / Multi-Site

Plugin hỗ trợ WordPress Multisite tự động. Mỗi site sẽ có cấu hình riêng.

Đối với custom multi-tenant, sử dụng hook:

```php
add_filter('wpgsip_custom_tenant_id', function($tenant_id) {
    // Xác định tenant dựa trên subdomain
    $host = $_SERVER['HTTP_HOST'];
    if (preg_match('/^(\w+)\.domain\.com$/', $host, $matches)) {
        return $matches[1];
    }
    return 'default';
});
```

### Tùy Chỉnh Import

Sử dụng hooks để tùy chỉnh:

```php
// Bỏ qua hàng theo điều kiện
add_filter('wpgsip_should_skip_row', function($skip, $row, $tenant_id) {
    if ($row['status'] === 'SKIP') {
        return true;
    }
    return $skip;
}, 10, 3);

// Thêm xử lý sau khi tạo bài
add_action('wpgsip_after_create_post', function($post_id, $row, $tenant_id) {
    // Thêm custom field
    update_post_meta($post_id, 'custom_field', $row['custom_data']);
}, 10, 3);
```

### Tích Hợp SEO

Plugin tự động tương thích với:
- **Yoast SEO**: Tự động điền meta title, description, focus keyword
- **Rank Math**: Tự động điền các trường SEO tương ứng

Không cần cấu hình gì thêm!

## 🔧 Cài Đặt Chi Tiết

### Google Sheets

- **Sheet ID**: ID của bảng tính Google
- **Sheet Range**: Phạm vi dữ liệu (A1 notation)
- **Service Account JSON**: Credentials để truy cập API
- **Google API Key**: Thay thế cho Service Account (cho public sheets)

### n8n Webhook

- **Enable**: Bật/tắt webhook
- **Webhook URL**: URL endpoint của n8n
- **Wait Time**: Thời gian chờ sau khi trigger (5-120 giây)

### Import Options

- **Post Status**: Trạng thái bài viết mặc định (publish/draft/pending)
- **Skip Status Filter**: Bỏ qua hàng có STATUS khớp giá trị này
- **Batch Size**: Số hàng xử lý mỗi lần (1-100)
- **Cache Duration**: Thời gian cache dữ liệu sheet (0-3600 giây)

### Scheduled Import

- **Enable**: Bật nhập tự động
- **Frequency**: Tần suất (Hourly/Twice Daily/Daily/Weekly)

## 🐛 Xử Lý Lỗi

### Lỗi Kết Nối Google Sheets

**Triệu chứng:** "Failed to fetch data from Google Sheets"

**Giải pháp:**
1. Kiểm tra Sheet ID và Range đúng chưa
2. Đảm bảo sheet đã share với service account email
3. Kiểm tra Service Account JSON hợp lệ
4. Thử click "Test Connection"

### Lỗi Timeout

**Triệu chứng:** Import bị dừng giữa chừng

**Giải pháp:**
1. Giảm Batch Size xuống (ví dụ: 5-10)
2. Tăng PHP `max_execution_time`
3. Sử dụng Scheduled Import thay vì manual

### Lỗi n8n Webhook

**Triệu chứng:** "Webhook connection failed"

**Giải pháp:**
1. Kiểm tra URL webhook đúng chưa
2. Đảm bảo n8n workflow đã được activate
3. Test webhook endpoint trực tiếp
4. Kiểm tra firewall/network access

### Content Trống

**Triệu chứng:** Bài viết được tạo nhưng không có nội dung

**Giải pháp:**
1. Kiểm tra cột F trong sheet có dữ liệu chưa
2. Nếu dùng n8n, tăng Wait Time lên
3. Kiểm tra n8n workflow có cập nhật sheet thành công không
4. Xem Logs để biết chi tiết lỗi

## 📈 Tips & Best Practices

### Hiệu Suất

1. **Cache**: Đặt cache duration hợp lý (300-600 giây)
2. **Batch Size**: Với sheet lớn, dùng batch 10-20
3. **Scheduled Import**: Chạy vào giờ ít traffic
4. **Clean Logs**: Định kỳ xóa logs cũ (90+ ngày)

### Bảo Mật

1. **Không commit** Service Account JSON vào Git
2. **Giới hạn quyền** sheet chỉ mức cần thiết
3. **HTTPS**: Dùng HTTPS cho webhook URLs
4. **Backup**: Backup database trước khi import lớn

### Workflow Tối Ưu

1. **Test trước**: Dùng Preview để kiểm tra dữ liệu
2. **Nhập nhỏ**: Test với vài hàng trước
3. **Monitor logs**: Theo dõi logs để phát hiện lỗi sớm
4. **Regular sync**: Dùng scheduled import để sync thường xuyên

## 🆘 Hỗ Trợ

Nếu gặp vấn đề:

1. Kiểm tra **Logs** trong plugin
2. Bật WP_DEBUG để xem lỗi chi tiết
3. Kiểm tra PHP error logs
4. Review INSTALL.md và DEVELOPER.md
5. Liên hệ support

## 📝 Changelog

### Version 1.0.0
- Phát hành đầu tiên
- Tích hợp Google Sheets API
- Hỗ trợ n8n webhook
- Batch import với AJAX
- Multi-tenant architecture
- Dashboard và analytics
- Scheduled imports
- SEO plugin compatibility

## 📄 License

GPL v2 or later

---

**Lưu ý:** Plugin này được thiết kế để mở rộng. Bạn có thể tùy chỉnh thêm các tính năng qua hooks và filters. Xem DEVELOPER.md để biết thêm chi tiết về API và cách mở rộng plugin.
