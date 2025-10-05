# Troubleshooting Import Error 500

## Problem
When clicking "Start Import", getting:
```
POST http://reviewus.local/wp-admin/admin-ajax.php 500 (Internal Server Error)
```

## Root Cause
The error was caused by `undefined variable $processed_data` in both `create_post()` and `update_post()` methods when content processing was disabled.

## Fix Applied
✅ Added `$processed_data = array();` initialization before the `if ($enable_processing)` check in both methods.

## Steps to Resolve

### 1. Run Debug Test
Access: http://reviewus.local/wp-content/plugins/wp-google-sheets-import-pro/debug-test.php

**Expected Results:**
- ✅ All classes should exist
- ✅ Content Processor test should pass
- ✅ Settings should load
- ✅ AJAX handlers should be registered

**If you see errors:** Copy the error messages for debugging.

### 2. Clear Cache
Access: http://reviewus.local/wp-content/plugins/wp-google-sheets-import-pro/clear-cache.php

This clears all plugin transients and caches.

### 3. Enable Debug Logging

Edit `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
```

### 4. Check PHP Error Log

**Local by Flywheel Location:**
```
C:\Users\ngoba\Local Sites\reviewus\logs\php\error.log
```

**WordPress Debug Log:**
```
C:\Users\ngoba\Local Sites\reviewus\app\public\wp-content\debug.log
```

### 5. Test Import Again

1. Go to **GS Import Pro → Import**
2. Click **Load Preview** (should show 4 rows)
3. If preview works, click **Start Import**
4. Check Console (F12) for any JavaScript errors
5. Check debug.log for PHP errors

### 6. Verify Settings

Go to **GS Import Pro → Settings** and ensure:

**Content Processing Options:**
- ☑️ Enable Content Processing (checked by default)
- ☐ Enable Table of Contents (optional)
- TOC Minimum Headings: 3
- TOC Title: "Nội dung bài viết"

Click **Save Settings** to ensure all new settings are saved.

## Common Issues & Solutions

### Issue 1: Class Not Found

**Error:**
```
Fatal error: Class 'WPGSIP_Content_Processor' not found
```

**Solution:**
```bash
cd wp-content/plugins/wp-google-sheets-import-pro
composer dump-autoload
```

### Issue 2: Undefined Variable

**Error:**
```
Notice: Undefined variable: processed_data
```

**Solution:**
Already fixed in latest code. Make sure you have the updated `class-wpgsip-importer.php`.

### Issue 3: Memory Limit

**Error:**
```
Fatal error: Allowed memory size exhausted
```

**Solution:**
Add to wp-config.php:
```php
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');
```

### Issue 4: Max Execution Time

**Error:**
```
Maximum execution time exceeded
```

**Solution:**
Reduce batch size in Settings:
- Current: 10
- Try: 5 or 3

### Issue 5: AJAX 0 Response

**Symptom:**
```
admin-ajax.php returns: 0
```

**Causes:**
1. PHP fatal error (check error log)
2. Nonce verification failed (clear cookies)
3. Insufficient permissions (must be admin)
4. Plugin not activated

**Solution:**
1. Check error logs
2. Clear browser cookies
3. Re-login as admin
4. Deactivate and reactivate plugin

## Debugging Checklist

- [ ] Run debug-test.php - all checks pass
- [ ] Clear cache with clear-cache.php
- [ ] Enable WP_DEBUG and WP_DEBUG_LOG
- [ ] Check error logs for PHP errors
- [ ] Verify settings saved (go to Settings, click Save)
- [ ] Clear browser cache and cookies
- [ ] Test with Load Preview first
- [ ] Check Console (F12) for JavaScript errors
- [ ] Try reducing batch size to 1 for testing

## Manual Import Test

If automated import fails, test manually:

```php
// Add to functions.php temporarily
add_action('init', function() {
    if (!is_admin() || !isset($_GET['test_import'])) return;
    
    $importer = new WPGSIP_Importer('default');
    
    $test_row = array(
        'row_id' => 'manual-test-1',
        'meta_title' => 'Manual Test Post',
        'meta_description' => 'This is a test',
        'keyword' => 'test',
        'status' => '',
        'content' => "H1: Test Title\n\nThis is **bold** text."
    );
    
    try {
        $result = $importer->import_row($test_row);
        echo '<pre>';
        print_r($result);
        echo '</pre>';
        die();
    } catch (Exception $e) {
        echo 'Error: ' . $e->getMessage();
        die();
    }
});
```

Access: `http://reviewus.local/?test_import=1`

## Still Having Issues?

1. **Copy error messages** from:
   - Browser Console (F12 → Console tab)
   - PHP error log
   - WordPress debug.log
   - debug-test.php output

2. **Provide context:**
   - What step are you on?
   - What did you click?
   - What was the expected result?
   - What actually happened?

3. **Share:**
   - Error messages
   - Screenshots if helpful
   - Browser/system info

## Prevention

To avoid similar issues in future:

1. **Always run debug-test.php** after major code changes
2. **Enable WP_DEBUG** during development
3. **Check error logs** regularly
4. **Test with small data** first (1-2 rows)
5. **Keep backups** before bulk imports

## Quick Reference

**Debug URLs:**
- Debug Test: `/wp-content/plugins/wp-google-sheets-import-pro/debug-test.php`
- Clear Cache: `/wp-content/plugins/wp-google-sheets-import-pro/clear-cache.php`

**Log Files:**
- PHP Error: `C:\Users\ngoba\Local Sites\reviewus\logs\php\error.log`
- WP Debug: `wp-content/debug.log`

**Important Files:**
- Import Logic: `includes/class-wpgsip-importer.php`
- Content Processor: `includes/class-wpgsip-content-processor.php`
- AJAX Handler: `includes/class-wpgsip-core.php`
- Settings: `includes/class-wpgsip-settings.php`

---

**Updated:** October 5, 2025  
**Version:** 1.0.0
