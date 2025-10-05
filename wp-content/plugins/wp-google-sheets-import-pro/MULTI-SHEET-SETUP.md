# 🔄 MULTI-SHEET SETUP FOR THING TO DO

## 📊 OVERVIEW

The plugin now supports **2 separate Google Sheets** for different post types:
- **Sheet 1**: Blog Posts (Post)
- **Sheet 2**: Things To Do (thing_to_do CPT)

This architecture provides:
✅ **Clear separation** of different content types with different taxonomies
✅ **No confusion** between category/tags vs provinces/themes/seasons
✅ **Scalability** for multi-site projects
✅ **Better organization** for content writers

---

## 🏗️ ARCHITECTURE

### Why Separate Sheets?

**Problem with Single Sheet:**
```
❌ Single Sheet with CPT Column:
┌────┬──────────────┬──────┬──────────┬──────┐
│ ID │ Title        │ CPT  │ Category │ Tags │  ← CONFUSING!
├────┼──────────────┼──────┼──────────┼──────┤
│ 2  │ Visa guide   │ post │ Visa     │ Tips │  ← Category = Post Category
│ 3  │ Toronto      │ thing│ Ontario  │ City │  ← Category = Province! WRONG TAXONOMY!
└────┴──────────────┴──────┴──────────┴──────┘

Issues:
- Column "Category" means different things for different rows
- Easy to mix up taxonomies
- Province ≠ Category (different taxonomy)
- Themes ≠ Tags (different taxonomy)
```

**Solution with 2 Sheets:**
```
✅ Sheet 1 "Post1" (Blog Posts):
┌────┬──────────────┬──────────┬──────┐
│ ID │ Title        │ Category │ Tags │
├────┼──────────────┼──────────┼──────┤
│ 2  │ Visa guide   │ Visa     │ Tips │
│ 3  │ Top 10 tips  │ Travel   │ Tips │
└────┴──────────────┴──────────┴──────┘

✅ Sheet 2 "ThingToDo1" (Things To Do):
┌────┬──────────────┬──────────┬────────┬─────────┐
│ ID │ Title        │ Province │ Themes │ Seasons │
├────┼──────────────┼──────────┼────────┼─────────┤
│ 2  │ Toronto      │ Ontario  │ City   │ Summer  │
│ 3  │ Niagara      │ Ontario  │ Water  │ All     │
└────┴──────────────┴──────────┴────────┴─────────┘

Benefits:
- Clear taxonomy separation
- No confusion for content writers
- Each sheet optimized for its content type
- Easy to scale to multi-site
```

---

## 📝 GOOGLE SHEETS SETUP

### Step 1: Create 2 Separate Sheets

In your Google Spreadsheet, create 2 sheets (tabs):

#### Sheet 1: "Post1" (Blog Posts)

**Column Structure (A-I):**
```
A: outline          - H1/H2 content structure
B: meta_title       - SEO title
C: meta_description - SEO description  
D: keyword          - Focus keyword
E: status           - publish/draft (date format: 01/10/2025)
F: content          - Full post content
G: CPT              - post (always "post" for this sheet)
H: category         - Post categories (comma-separated)
I: tags             - Post tags (comma-separated)
```

**Example Data:**
```
| A: outline                          | B: meta_title              | C: meta_description       | ... | H: category | I: tags        |
|-------------------------------------|----------------------------|---------------------------|-----|-------------|----------------|
| H1: Hướng dẫn xin visa Mỹ 2025...  | Hướng dẫn xin visa Mỹ...  | Tất cả thông tin cần...  | ... | Visa Mỹ     | Tips, Travel   |
| H1: Top 10 câu hỏi phỏng vấn...    | Top 10 câu hỏi...          | Danh sách câu hỏi...     | ... | Visa Mỹ     | Interview, FAQ |
```

---

#### Sheet 2: "ThingToDo1" (Things To Do)

**Column Structure (A-I):**
```
A: outline          - H1/H2 content structure
B: meta_title       - SEO title
C: meta_description - SEO description
D: keyword          - Focus keyword
E: status           - publish/draft (date format: 01/10/2025)
F: content          - Full content
G: province         - Province/Territory (Provinces & Territories taxonomy)
H: themes           - Thing themes (Thing Themes taxonomy)
I: seasons          - Seasons (Seasons taxonomy)
```

**Example Data:**
```
| A: outline                     | B: meta_title           | C: meta_description    | ... | G: province | H: themes  | I: seasons    |
|--------------------------------|-------------------------|------------------------|-----|-------------|------------|---------------|
| H1: CN Tower Toronto           | CN Tower - Toronto...   | Iconic tower with...   | ... | Ontario     | City, View | Summer, Fall  |
| H1: Niagara Falls              | Niagara Falls Guide     | Stunning waterfall...  | ... | Ontario     | Water, Nature | All Seasons |
```

**Important Notes:**
- **Province** (Column G): Must match existing terms in "Provinces & Territories" taxonomy
  - Examples: Ontario, British Columbia, Quebec, Alberta, etc.
- **Themes** (Column H): Must match terms in "Thing Themes" taxonomy
  - Examples: City, Nature, Water, Mountain, Beach, Culture, etc.
- **Seasons** (Column I): Must match terms in "Seasons" taxonomy
  - Examples: Spring, Summer, Fall, Winter, All Seasons
- Use **comma-separated** values for multiple items: `Ontario, Quebec` or `City, Culture`

---

### Step 2: Configure Sheet Ranges in WordPress

Navigate to: **WP GS Import Pro → Settings**

#### Setting 1: Blog Posts Sheet Range
```
Field: "Blog Posts Sheet Range"
Value: Post1!A2:I

Description:
Columns: A=outline, B=meta_title, C=meta_description, D=keyword, 
         E=status, F=content, G=CPT, H=category, I=tags
```

#### Setting 2: Thing To Do Sheet Range
```
Field: "Thing To Do Sheet Range"
Value: ThingToDo1!A2:I

Description:
Columns: A=outline, B=meta_title, C=meta_description, D=keyword,
         E=status, F=content, G=province, H=themes, I=seasons
```

**Range Notation Explained:**
- `Post1` or `ThingToDo1`: Sheet name (tab name)
- `A2`: Start from row 2 (row 1 is headers)
- `I`: Read up to column I (9 columns total)

---

## 🔧 PLUGIN BEHAVIOR

### Automatic Sheet Selection

When you select **Import For** on the Import page:

#### Option 1: "Post" Selected
```
✅ Automatic Actions:
1. Display sheet range: Post1!A2:I
2. Show column mapping: A=outline, B=meta_title, ... H=category, I=tags
3. Fetch data from "Post1" sheet
4. Load taxonomies: category, post_tag
5. Create dropdown for: Categories, Tags
```

#### Option 2: "Thing To Do" Selected
```
✅ Automatic Actions:
1. Display sheet range: ThingToDo1!A2:I
2. Show column mapping: A=outline, B=meta_title, ... G=province, H=themes, I=seasons
3. Fetch data from "ThingToDo1" sheet
4. Load taxonomies: provinces_territories, thing_themes, seasons
5. Create dropdown for: Provinces & Territories, Themes, Seasons
```

---

## 🎯 IMPORT WORKFLOW

### For Blog Posts

1. **Select Post Type:**
   - Navigate to: WP GS Import Pro → Import
   - Select: "Import For" → **Post**
   - Sheet range updates automatically to: `Post1!A2:I`

2. **Load Preview:**
   - Click "Load Preview" button
   - Table shows:
     - Row ID, Title (truncated), Status, Content size
     - Categories dropdown (from "category" column)
     - Tags dropdown (from "tags" column)
     - Action badge: ✏️ Update or ➕ Create

3. **Select Categories & Tags:**
   - For each row, multi-select from dropdowns
   - Or use default selection at top for all rows
   - Sheet values pre-selected if they exist

4. **Import:**
   - Check items to import
   - Click "Import Selected Items"
   - Plugin imports to WordPress Posts with category/tag taxonomies

---

### For Things To Do

1. **Select Post Type:**
   - Navigate to: WP GS Import Pro → Import
   - Select: "Import For" → **Thing To Do**
   - Sheet range updates automatically to: `ThingToDo1!A2:I`

2. **Load Preview:**
   - Click "Load Preview" button
   - Table shows:
     - Row ID, Title (truncated), Status, Content size
     - **Provinces & Territories** dropdown (from "province" column)
     - **Themes** dropdown (from "themes" column)
     - **Seasons** dropdown (from "seasons" column)
     - Action badge: ✏️ Update or ➕ Create

3. **Select Taxonomies:**
   - For each row, multi-select from dropdowns
   - **Provinces**: Ontario, British Columbia, Quebec, etc.
   - **Themes**: City, Nature, Water, Culture, etc.
   - **Seasons**: Spring, Summer, Fall, Winter, All Seasons
   - Sheet values pre-selected if they exist

4. **Import:**
   - Check items to import
   - Click "Import Selected Items"
   - Plugin imports to thing_to_do CPT with correct taxonomies

---

## 🔍 EXISTING POST DETECTION

### How It Works

The plugin uses **3-tier matching** to find existing posts:

#### Tier 1: Exact Match
```php
// Exact title match (fastest)
WHERE post_title = 'Hướng dẫn xin visa Mỹ 2025'
AND post_type = 'thing_to_do'
```

#### Tier 2: Case-Insensitive Match
```php
// Handles case differences
WHERE LOWER(post_title) = LOWER('hướng dẫn xin visa mỹ 2025')
AND post_type = 'thing_to_do'
```

#### Tier 3: Fuzzy Match with Normalization
```php
// Handles suffixes and variations
// Normalizes both titles:
//   - Removes: "hiệu quả", "chi tiết", "từ A đến Z"
//   - Converts to lowercase
//   - Normalizes spaces/dashes
// Calculates similarity with similar_text()
// Returns match if >= 90% similar
```

**What This Means:**
- ✅ **Update existing posts** instead of creating duplicates
- ✅ **Handles case variations**: "CN Tower" vs "cn tower"
- ✅ **Handles suffixes**: "Guide" vs "Guide - Complete"
- ✅ **Shows correct action badge**: ✏️ Update or ➕ Create

---

## 🚀 MULTI-SITE SCALABILITY

### Single Site Structure (Current)
```
WordPress Site:
├── Google Sheet: "ReviewUS Content"
│   ├── Sheet: "Post1" (Blog Posts)
│   │   └── Range: Post1!A2:I
│   └── Sheet: "ThingToDo1" (Things To Do)
│       └── Range: ThingToDo1!A2:I
```

### Multi-Site Structure (Future)

#### Site 1: Canada Reviews
```
WordPress Site: canada.example.com
├── Google Sheet: "Canada Content"
│   ├── Sheet: "CanadaBlog" (Blog Posts)
│   │   └── Range: CanadaBlog!A2:I
│   └── Sheet: "CanadaThings" (Things To Do)
│       └── Range: CanadaThings!A2:I
```

#### Site 2: USA Reviews
```
WordPress Site: usa.example.com
├── Google Sheet: "USA Content"
│   ├── Sheet: "USABlog" (Blog Posts)
│   │   └── Range: USABlog!A2:I
│   └── Sheet: "USAThings" (Things To Do)
│       └── Range: USAThings!A2:I
```

**Benefits:**
- ✅ Each site has its own Google Sheet
- ✅ Separate content management per site
- ✅ Same plugin, different sheet configurations
- ✅ No content conflicts between sites

---

## 🛠️ TROUBLESHOOTING

### Issue 1: Preview Shows "No data found in sheet"

**Possible Causes:**
1. Sheet name doesn't match range
2. Range starts at wrong row
3. Sheet is empty
4. Permission issues

**Solutions:**
```
✅ Check sheet name:
   - Blog Posts: Should be "Post1"
   - Things To Do: Should be "ThingToDo1"
   
✅ Check range format:
   - Correct: Post1!A2:I
   - Wrong: Post1!A1:I (includes headers)
   - Wrong: Post 1!A2:I (space in name)
   
✅ Check data exists:
   - At least 1 row of data (row 2+)
   - All required columns (A-I)
   
✅ Check permissions:
   - Service account has read access
   - Sheet is shared with service account email
```

---

### Issue 2: Wrong Taxonomies Show in Dropdowns

**Possible Causes:**
1. Wrong post type selected
2. Cache not cleared

**Solutions:**
```
✅ Verify post type:
   - For Blog Posts: Select "Post"
   - For Things To Do: Select "Thing To Do"
   
✅ Clear cache:
   - Wait 5-15 minutes for cache to expire
   - OR go to Settings → Click "Test Connection"
   
✅ Check sheet range display:
   - Should update when post type changes
   - Should show correct column mapping
```

---

### Issue 3: Taxonomies Not Assigned After Import

**Possible Causes:**
1. Terms don't exist in WordPress
2. Term names don't match exactly
3. No terms selected in dropdowns

**Solutions:**
```
✅ Create terms first in WordPress:
   - Go to: Posts → Categories (for posts)
   - Go to: Things To Do → Provinces & Territories (for things)
   - Go to: Things To Do → Themes
   - Go to: Things To Do → Seasons
   
✅ Match term names exactly:
   - Sheet: "Ontario" → WordPress: "Ontario" ✅
   - Sheet: "ontario" → WordPress: "Ontario" ❌ (case-sensitive)
   
✅ Use comma-separated for multiple:
   - Sheet: "Ontario, Quebec"
   - Sheet: "City, Nature, Water"
```

---

### Issue 4: Action Shows "Create" But Post Exists

**Possible Causes:**
1. Title mismatch between sheet H1 and WordPress title
2. Post type mismatch
3. Cache issue

**Solutions:**
```
✅ Check title format:
   - Sheet must have: H1: Your Title Here
   - WordPress title must match H1 extracted title
   - Check debug log for similarity %
   
✅ Verify post type:
   - Ensure searching in correct post type
   - Post vs thing_to_do are separate
   
✅ Lower similarity threshold:
   - Edit class-wpgsip-import-ajax.php line ~359
   - Change: if ($similarity >= 90)
   - To: if ($similarity >= 85)
```

---

## 📋 BEST PRACTICES

### 1. Sheet Naming Convention
```
✅ Good:
   - Post1, Post2, Post3 (for different batches)
   - ThingToDo1, ThingToDo2 (for different batches)
   
❌ Avoid:
   - "Blog Posts" (spaces cause issues)
   - "Thing To Do" (spaces)
   - "Posts & Things" (special characters)
```

### 2. Column Order Consistency
```
✅ Always maintain order:
   - A: outline
   - B: meta_title
   - C: meta_description
   - D: keyword
   - E: status
   - F: content
   - G: CPT/province
   - H: category/themes
   - I: tags/seasons
   
❌ Don't change column order mid-project!
```

### 3. Content Writer Workflow
```
✅ Recommended:
   1. Writer A: Edits "Post1" sheet only (blog posts)
   2. Writer B: Edits "ThingToDo1" sheet only (things to do)
   3. No confusion about taxonomies
   
❌ Avoid:
   1. Mixed content in single sheet
   2. Switching between sheets mid-writing
   3. Copying between post types without adjusting taxonomies
```

### 4. Testing Before Production
```
✅ Test workflow:
   1. Create test sheet with 2-3 rows
   2. Import to staging site
   3. Verify taxonomies assigned correctly
   4. Check existing post detection works
   5. Verify update vs create behavior
   6. Then move to production
```

---

## 🎓 TECHNICAL IMPLEMENTATION

### Files Modified

#### 1. `admin/views/settings.php`
- Added "Thing To Do Sheet Range" setting field
- Shows column mapping for each post type

#### 2. `includes/class-wpgsip-google-sheets.php`
- Updated `fetch_data()` method to accept `$post_type` parameter
- Auto-selects sheet range: `sheet_range` or `thing_to_do_sheet_range`
- Dynamic column mapping based on post type:
  - `post`: Maps to CPT, category, tags
  - `thing_to_do`: Maps to province, themes, seasons

#### 3. `includes/class-wpgsip-import-ajax.php`
- Updated `ajax_import_preview_enhanced()` to pass `$post_type` to `fetch_data()`
- Updated `ajax_import_selective()` to pass `$post_type` to `fetch_data()`

#### 4. `admin/views/import.php`
- Added hidden inputs for both sheet ranges
- Added dynamic column description display
- Sheet range updates when post type changes

#### 5. `assets/js/import.js`
- Added event handler for post type change
- Dynamically updates displayed sheet range
- Updates column description based on post type

---

## 📊 COLUMN MAPPING REFERENCE

### Blog Posts (post)
```php
$column_mapping = array(
    0 => 'outline',          // A: H1/H2 structure
    1 => 'meta_title',       // B: SEO title
    2 => 'meta_description', // C: SEO desc
    3 => 'keyword',          // D: Focus keyword
    4 => 'status',           // E: publish/draft
    5 => 'content',          // F: Full content
    6 => 'CPT',              // G: Always "post"
    7 => 'category',         // H: Post categories
    8 => 'tags',             // I: Post tags
);
```

### Things To Do (thing_to_do)
```php
$column_mapping = array(
    0 => 'outline',          // A: H1/H2 structure
    1 => 'meta_title',       // B: SEO title
    2 => 'meta_description', // C: SEO desc
    3 => 'keyword',          // D: Focus keyword
    4 => 'status',           // E: publish/draft
    5 => 'content',          // F: Full content
    6 => 'province',         // G: Provinces & Territories
    7 => 'themes',           // H: Thing Themes
    8 => 'seasons',          // I: Seasons
);
```

---

## ✅ SUCCESS CHECKLIST

### Initial Setup
- [ ] Created 2 separate sheets in Google Spreadsheet
- [ ] Named sheets: "Post1" and "ThingToDo1"
- [ ] Added headers in row 1
- [ ] Added sample data in row 2+
- [ ] Configured "Blog Posts Sheet Range" in Settings
- [ ] Configured "Thing To Do Sheet Range" in Settings
- [ ] Tested connection (Settings → Test Connection)

### Content Entry
- [ ] Blog post data in "Post1" sheet
- [ ] Thing to do data in "ThingToDo1" sheet
- [ ] H1 format correct in outline column
- [ ] Status column has dates in format: 01/10/2025
- [ ] Content column has full content
- [ ] Taxonomy columns have comma-separated values

### Import Testing
- [ ] Selected "Post" → Load Preview shows blog posts
- [ ] Selected "Thing To Do" → Load Preview shows things to do
- [ ] Sheet range updates automatically
- [ ] Column description updates automatically
- [ ] Correct taxonomy dropdowns appear
- [ ] Action badges show correct: Update vs Create
- [ ] Import creates posts with correct taxonomies
- [ ] Existing posts update instead of duplicate

---

## 🎉 CONCLUSION

This multi-sheet architecture provides:

✅ **Clarity**: Each post type has dedicated sheet with appropriate columns
✅ **Scalability**: Easy to add more post types or multi-site
✅ **Maintainability**: Content writers don't confuse taxonomies
✅ **Flexibility**: Each sheet can have different column structures
✅ **Reliability**: Existing post detection works per post type

**Next Steps:**
1. Create your 2 sheets in Google Spreadsheet
2. Configure both sheet ranges in Settings
3. Test with 2-3 sample rows
4. Verify taxonomies assigned correctly
5. Start importing your content!

For more help, see:
- `FIX-FUZZY-MATCHING.md` - Existing post detection
- `FIX-EXISTING-POST-DETECTION.md` - Title matching logic
- `FIX-TABLE-DISPLAY.md` - Column mapping details
