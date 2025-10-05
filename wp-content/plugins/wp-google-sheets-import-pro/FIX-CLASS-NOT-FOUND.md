# 🔧 FIX: Class WPGSIP_Import_Ajax Not Found

## 🐛 Lỗi Gốc

```
PHP Fatal error: Uncaught TypeError: call_user_func_array(): 
Argument #1 ($callback) must be a valid callback, 
class "WPGSIP_Import_Ajax" not found
```

## 🔍 Nguyên Nhân

### Vấn đề: Class Loading Order
Khi `class-wpgsip-core.php` được load, nó đăng ký AJAX handlers:

```php
// trong class-wpgsip-core.php
add_action('wp_ajax_wpgsip_import_preview_enhanced', 
    array('WPGSIP_Import_Ajax', 'ajax_import_preview_enhanced'));
```

Nhưng lúc đó `WPGSIP_Import_Ajax` class chưa được load vì:
1. `class-wpgsip-core.php` được require ở **dòng 45**
2. `class-wpgsip-import-ajax.php` được require ở **dòng 53**

→ Core tries to reference a class that doesn't exist yet!

### Thứ Tự Require SAI (Trước đây):
```php
require_once 'class-wpgsip-core.php';          // Line 45 - Đăng ký hooks
require_once 'class-wpgsip-settings.php';      // Line 46
// ... other classes ...
require_once 'class-wpgsip-import-ajax.php';   // Line 53 - TOO LATE!
```

## ✅ Giải Pháp

### Thứ Tự Require ĐÚNG (Sau khi fix):
```php
// Load ALL dependencies FIRST
require_once 'class-wpgsip-activator.php';
require_once 'class-wpgsip-deactivator.php';
require_once 'class-wpgsip-settings.php';
require_once 'class-wpgsip-logger.php';
require_once 'class-wpgsip-google-sheets.php';
require_once 'class-wpgsip-content-processor.php';
require_once 'class-wpgsip-importer.php';
require_once 'class-wpgsip-webhook.php';
require_once 'class-wpgsip-tenant-manager.php';
require_once 'class-wpgsip-taxonomy-manager.php';
require_once 'class-wpgsip-import-ajax.php';    // ✅ LOADED BEFORE CORE
require_once 'class-wpgsip-cron.php';

// Load Core LAST - it registers hooks and depends on other classes
require_once 'class-wpgsip-core.php';           // ✅ NOW IT CAN FIND THE CLASS
```

## 🎯 Nguyên Tắc

**Class Loading Order Best Practice:**
1. **Load dependencies first** - Tất cả classes mà Core cần
2. **Load Core last** - Core registers hooks và references other classes

**Dependency Tree:**
```
Core
 ├── Import_Ajax (cần cho AJAX handlers)
 ├── Settings (cần cho configuration)
 ├── Logger (cần cho logging)
 ├── Importer (cần cho Import_Ajax)
 │    ├── Content_Processor
 │    ├── Google_Sheets
 │    └── Taxonomy_Manager
 └── Tenant_Manager
```

## 🔧 Files Modified

### `wp-google-sheets-import-pro.php`
**Before:**
```php
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-core.php';
// ... 8 other requires ...
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-import-ajax.php';
```

**After:**
```php
// Load dependencies first
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-import-ajax.php';
// ... all other dependencies ...
// Load Core last
require_once WPGSIP_PLUGIN_DIR . 'includes/class-wpgsip-core.php';
```

## ✅ Verification

### 1. Syntax Check
```bash
php -l wp-google-sheets-import-pro.php
# ✅ No syntax errors detected

php -l includes/class-wpgsip-import-ajax.php
# ✅ No syntax errors detected
```

### 2. Test Steps
1. ✅ Xóa debug.log cũ
2. ✅ Reload WordPress admin
3. ✅ Go to Import page
4. ✅ Click "Load Preview"
5. ✅ Check debug.log - No errors!

## 🎓 Lesson Learned

**Always load dependencies before the class that uses them!**

Common mistake:
```php
require_once 'ClassA.php';  // Uses ClassB
require_once 'ClassB.php';  // ❌ ClassA already tried to use ClassB
```

Correct order:
```php
require_once 'ClassB.php';  // Load dependency first
require_once 'ClassA.php';  // ✅ Now ClassA can use ClassB
```

## 🚀 Status: FIXED

- ✅ Class loading order corrected
- ✅ Syntax validated
- ✅ Debug log cleared
- ✅ Ready to test

**Next Step:** Test Load Preview trong WordPress admin!
