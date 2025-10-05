<?php
/**
 * Clear WPGSIP Cache Script
 * 
 * Run this to clear WordPress transients cache for WPGSIP
 */

define('ABSPATH', __DIR__ . '/');
require_once 'wp-load.php';

global $wpdb;

// Delete all WPGSIP transients
$result = $wpdb->query(
    "DELETE FROM $wpdb->options 
    WHERE option_name LIKE '_transient_wpgsip_data_%' 
    OR option_name LIKE '_transient_timeout_wpgsip_data_%'"
);

echo "✅ Cleared WPGSIP cache transients\n";
echo "📊 Deleted $result rows\n\n";

echo "═══════════════════════════════════════════════════════\n";
echo "✅ ĐÃ SỬA FILE class-wpgsip-google-sheets.php\n";
echo "═══════════════════════════════════════════════════════\n\n";

echo "📝 Thay đổi:\n";
echo "  ✅ Đổi từ HARDCODE 7 columns → DYNAMIC mapping\n";
echo "  ✅ Hỗ trợ tất cả columns: A-J (10 columns)\n";
echo "  ✅ Tự động map: row_id, outline, meta_title, meta_description, keyword, status, content, CPT, category, tags\n\n";

echo "📋 Cấu trúc dữ liệu mới:\n";
echo "  • Column A (0): row_id\n";
echo "  • Column B (1): outline\n";
echo "  • Column C (2): meta_title\n";
echo "  • Column D (3): meta_description\n";
echo "  • Column E (4): keyword\n";
echo "  • Column F (5): status\n";
echo "  • Column G (6): content\n";
echo "  • Column H (7): CPT (Custom Post Type indicator)\n";
echo "  • Column I (8): category\n";
echo "  • Column J (9): tags\n\n";

echo "🧪 BÂY GIỜ HÃY TEST:\n";
echo "═══════════════════════════════════════════════════════\n";
echo "1. Vào WordPress Admin\n";
echo "2. WP GS Import Pro → Settings\n";
echo "3. Kiểm tra 'Sheet Range': Post1!A2:I (hoặc A2:J nếu có column J)\n";
echo "4. Click 'Save Settings'\n";
echo "5. WP GS Import Pro → Import\n";
echo "6. Click 'Load Preview'\n";
echo "7. Mở Console (F12) → Xem response data\n";
echo "8. Kiểm tra wp-content/debug.log\n\n";

echo "🎯 KẾT QUẢ MONG ĐỢI:\n";
echo "═══════════════════════════════════════════════════════\n";
echo "Debug log sẽ hiển thị:\n";
echo "  Sheet columns: Array\n";
echo "  (\n";
echo "      [0] => row_id\n";
echo "      [1] => outline\n";
echo "      [2] => meta_title\n";
echo "      [3] => meta_description\n";
echo "      [4] => keyword\n";
echo "      [5] => status\n";
echo "      [6] => content\n";
echo "      [7] => CPT          ← MỚI!\n";
echo "      [8] => category     ← MỚI!\n";
echo "      [9] => tags         ← MỚI!\n";
echo "  )\n\n";

echo "Console Response sẽ có:\n";
echo "  - data[0].CPT: 'post' hoặc giá trị từ sheet\n";
echo "  - data[0].category: 'Visa Mỹ' hoặc giá trị từ sheet\n";
echo "  - data[0].tags: 'Tips bổ ích' hoặc giá trị từ sheet\n\n";

echo "🔍 KIỂM TRA LOGIC BÀI VIẾT ĐÃ TỒN TẠI:\n";
echo "═══════════════════════════════════════════════════════\n";
echo "✅ Đã tạo file: EXISTING-POST-LOGIC.md\n";
echo "📖 Đọc file này để hiểu:\n";
echo "  • Cách plugin kiểm tra bài viết đã tồn tại\n";
echo "  • Tiêu chí so sánh (meta_title exact match)\n";
echo "  • Logic UPDATE vs CREATE NEW\n";
echo "  • Dữ liệu nào được update\n";
echo "  • Test cases và edge cases\n\n";

echo "✅ DONE! Hãy test ngay!\n";
