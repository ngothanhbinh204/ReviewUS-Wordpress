# Testing Enhanced Import Features

## ✅ Completed Integration Steps

### 1. Frontend Components
- ✅ Created `assets/js/import.js` with selective import logic
- ✅ Updated `admin/views/import.php` with post type selector and checkboxes
- ✅ Added CSS styles in `assets/css/admin.css` for enhanced UI
- ✅ Enqueued `import.js` in `admin/class-wpgsip-admin.php`

### 2. Backend Components
- ✅ Created `includes/class-wpgsip-taxonomy-manager.php` for taxonomy handling
- ✅ Created `includes/class-wpgsip-import-ajax.php` with two new AJAX handlers:
  - `ajax_import_preview_enhanced()` - Enhanced preview with taxonomy detection
  - `ajax_import_selective()` - Import only selected rows
- ✅ Added `should_skip_row_public()` method to `class-wpgsip-importer.php`
- ✅ Registered AJAX actions in `includes/class-wpgsip-core.php`
- ✅ Added requires in main plugin file `wp-google-sheets-import-pro.php`

## 🧪 Testing Procedure

### Step 1: Clear Cache
```powershell
# Clear browser cache (Ctrl+Shift+Delete in browser)
# Or open in incognito/private mode
```

### Step 2: Access Import Page
1. Go to WordPress Admin → WP GS Import Pro → Import
2. **Verify UI Elements:**
   - [ ] Post Type dropdown shows (Post, Thing To Do)
   - [ ] Load Preview button is visible
   - [ ] Selected Items Count shows "0 rows selected"

### Step 3: Test with "Post" Type
1. Select "Post" from post type dropdown
2. Click "Load Preview"
3. **Expected Results:**
   - [ ] Preview table displays with checkbox column
   - [ ] Each row has a checkbox
   - [ ] "Action" column shows badges (Create/Update)
   - [ ] "Select All" checkbox in header works
   - [ ] Selected count updates when checking boxes
   - [ ] Taxonomy section shows "Categories" and "Tags" dropdowns

### Step 4: Test with "Thing To Do" Type
1. Select "Thing To Do" from post type dropdown
2. Click "Load Preview"
3. **Expected Results:**
   - [ ] Checkboxes appear in preview
   - [ ] Taxonomy section shows:
     - Provinces/Territories dropdown
     - Thing Themes dropdown
     - Seasons dropdown
   - [ ] If sheet has matching columns (e.g., "province", "theme", "season"), they're auto-detected
   - [ ] Selected count updates when checking rows

### Step 5: Test Selective Import
1. Check 2-3 rows from preview table
2. Verify selected count shows "X rows selected"
3. (Optional) Select taxonomy terms from dropdowns
4. Click "Import Selected Items" button
5. **Expected Results:**
   - [ ] Progress bar appears and animates
   - [ ] Only selected rows are imported
   - [ ] Success message shows: "X created, Y updated"
   - [ ] Preview refreshes showing updated actions

### Step 6: Verify Database
1. Go to Posts → All Posts (or Things To Do)
2. **Check:**
   - [ ] Only selected items were created/updated
   - [ ] Post type is correct
   - [ ] Taxonomies are assigned (check post edit page)
   - [ ] Content is properly formatted (H1, H2, lists, TOC)

## 🔍 Troubleshooting

### Checkboxes Don't Appear
**Cause:** JavaScript not loaded
**Fix:**
```powershell
# Check browser console for errors (F12)
# Verify import.js is enqueued:
# View page source → search for "import.js"
```

### "No data found in sheet" Error
**Cause:** Sheet credentials or range incorrect
**Fix:**
1. Go to Settings → Test Connection
2. Verify green success message
3. Check sheet range includes data

### AJAX Error "Permission denied"
**Cause:** Nonce verification failed
**Fix:**
1. Reload the page (Ctrl+F5)
2. Try in incognito mode
3. Check if logged in as admin

### Taxonomies Not Assigned
**Cause:** Terms don't exist yet
**Fix:**
1. Go to Posts → Categories (or relevant taxonomy)
2. Create terms manually first
3. Or enable auto-create in taxonomy manager

### Import Creates Instead of Updates
**Cause:** Title matching not finding existing posts
**Fix:**
- Check if post titles in sheet exactly match existing posts
- Verify post type matches (can't update "post" with "thing_to_do")

## 📊 Test Data Format

### Google Sheet Columns for "Post"
```
meta_title | meta_description | content | category | tags
```

### Google Sheet Columns for "Thing To Do"
```
meta_title | meta_description | content | province | theme | season
```

## ✨ Expected Features Working

1. **Post Type Selection:**
   - Dropdown switches between Post and Thing To Do
   - Preview updates when post type changes

2. **Checkbox Selection:**
   - Individual row selection
   - Select All functionality
   - Selected count display
   - Import button enables/disables

3. **Taxonomy Detection:**
   - Automatically finds taxonomy columns in sheet
   - Shows appropriate dropdowns for post type
   - Allows manual term selection
   - Supports both auto and manual assignment

4. **Selective Import:**
   - Imports only checked rows
   - Shows progress for selected items only
   - Updates action badges after import
   - Preserves unchecked rows in preview

5. **Duplicate Prevention:**
   - Checks existing posts by title + post type
   - Updates if found, creates if not
   - Shows accurate Create/Update badges

## 📝 Known Limitations

1. **Column Name Matching:**
   - Column names must match exactly (case-insensitive)
   - For provinces: use "province" or "provinces_territories"
   - For themes: use "theme" or "thing_themes"
   - For seasons: use "season" or "seasons"

2. **Term Creation:**
   - New terms are auto-created if they don't exist
   - Hierarchical taxonomies (categories) need parent terms created first

3. **Batch Size:**
   - Processes 5 rows at a time to prevent timeouts
   - Larger imports may take longer

## 🎯 Next Steps After Testing

If all tests pass:
1. Document custom post type requirements for users
2. Add filter hooks for custom taxonomy mappings
3. Consider adding taxonomy term import from separate sheet

If tests fail:
1. Check browser console for JavaScript errors
2. Enable WordPress debug mode (WP_DEBUG in wp-config.php)
3. Check PHP error log for backend issues
4. Verify all files were saved correctly
