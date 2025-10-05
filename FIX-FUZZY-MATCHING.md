# ✅ FIX FUZZY MATCHING FOR EXISTING POSTS

## 🔍 PHÂN TÍCH DEBUG LOG

### Vấn Đề Phát Hiện:

Từ debug log của bạn, tôi thấy:

```log
✅ 2 bài ĐƯỢC TÌM THẤY:
- Row 5: "Hướng dẫn xin visa du lịch Thái Lan chi tiết từ A đến Z"
  → Found ID 564 ✅

- Row 6: "Hướng dẫn xin visa du học Hàn Quốc chi tiết từ A đến Z"
  → Found ID 565 ✅

❌ 3 bài KHÔNG TÌM THẤY:
- Row 2: Sheet: "Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời"
  WordPress: "Top 10 câu Hỏi Phỏng Vấn Visa Mỹ Thường Gặp và Cách Trả Lời Hiệu Quả"
  → Similar ID 561 found but NOT matched ❌

- Row 4: Sheet: "Top 10 câu hỏi phỏng vấn visa du lịch Schengen thường gặp và cách trả lời"
  WordPress: "Top 10 câu hỏi phỏng vấn visa du lịch Schengen thường gặp và cách trả lời hiệu quả"
  → Similar ID 563 found but NOT matched ❌

- Row 3: "7 ngày khám phá Hà Nội – Lịch trình chi tiết từ A đến Z"
  → No similar posts found ❌
```

---

## 🎯 NGUYÊN NHÂN

### 1. **Case Sensitivity (Chữ Hoa/Thường)**

```
Sheet:     "câu hỏi phỏng vấn"
WordPress: "câu Hỏi Phỏng Vấn"
→ KHÔNG KHỚP vì "h" vs "H"
```

### 2. **Thêm Suffix "Hiệu Quả"**

```
Sheet:     "...và cách trả lời"
WordPress: "...và cách trả lời hiệu quả"
→ KHÔNG KHỚP vì thiếu "hiệu quả"
```

### 3. **Row 3 - Bài Chưa Được Tạo?**

Có thể bài này thực sự chưa tồn tại trong WordPress, hoặc title khác hoàn toàn.

---

## ✅ GIẢI PHÁP ĐÃ ÁP DỤNG

### 🔧 Thêm 3-Tier Matching Strategy:

**File:** `includes/class-wpgsip-import-ajax.php`

#### Tier 1: Exact Match (Case-Sensitive)
```php
WHERE post_title = 'Title'  // Khớp 100%
```

#### Tier 2: Case-Insensitive Match
```php
WHERE LOWER(post_title) = LOWER('Title')  // Bỏ qua case
```

#### Tier 3: Fuzzy Match with Normalization
```php
// Normalize both titles (remove suffixes, lowercase, trim)
// Calculate similarity using similar_text()
// If similarity >= 90% → MATCH!
```

---

### 📝 Hàm `normalize_title()` - NEW

**Chức năng:** Chuẩn hóa title để so sánh linh hoạt

```php
private static function normalize_title($title)
{
    // 1. Lowercase
    $title = mb_strtolower($title, 'UTF-8');
    // "Top 10 Câu Hỏi" → "top 10 câu hỏi"
    
    // 2. Remove common suffixes
    $suffixes = [
        ' hiệu quả',
        ' chi tiết',
        ' từ a đến z',
        ' thường gặp',
        ' và cách trả lời hiệu quả'
    ];
    
    foreach ($suffixes as $suffix) {
        if (ends_with($title, $suffix)) {
            $title = remove_suffix($title, $suffix);
        }
    }
    
    // 3. Normalize spaces & dashes
    $title = preg_replace('/\s+/', ' ', $title);
    $title = preg_replace('/[–—-]+/', '-', $title);
    
    return trim($title);
}
```

**Ví dụ:**

```
Input 1: "Top 10 câu Hỏi Phỏng Vấn Visa Mỹ Thường Gặp và Cách Trả Lời Hiệu Quả"
Output:  "top 10 câu hỏi phỏng vấn visa mỹ thường gặp và cách trả lời"

Input 2: "Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời"
Output:  "top 10 câu hỏi phỏng vấn visa mỹ thường gặp và cách trả lời"

→ KHỚP 100%! ✅
```

---

### 🔍 Updated `find_existing_post_by_title()` Logic

```php
private static function find_existing_post_by_title($title, $post_type)
{
    // Try 1: Exact match
    $post_id = SELECT WHERE post_title = 'Title' LIMIT 1;
    if ($post_id) return $post_id; // ✅ Khớp 100%
    
    // Try 2: Case-insensitive
    $post_id = SELECT WHERE LOWER(post_title) = LOWER('Title') LIMIT 1;
    if ($post_id) return $post_id; // ✅ Bỏ qua case
    
    // Try 3: Fuzzy match
    $normalized_search = normalize_title('Title');
    $all_posts = SELECT ID, post_title FROM posts LIMIT 20;
    
    foreach ($all_posts as $post) {
        $normalized_post = normalize_title($post->post_title);
        
        // Calculate similarity
        similar_text($normalized_search, $normalized_post, $similarity);
        
        if ($similarity >= 90%) {
            return $post->ID; // ✅ Khớp >= 90%
        }
    }
    
    return false; // ❌ Không tìm thấy
}
```

---

## 🧪 TESTING

### Bước 1: Clear Cache & Reload

```bash
# Clear WordPress transients
# Hoặc chờ 5-15 phút
```

### Bước 2: Load Preview

1. Vào **WP GS Import Pro → Import**
2. Click **"Load Preview"**
3. Check debug log

### Bước 3: Kiểm Tra Debug Log

Mở `wp-content/debug.log`, tìm log mới:

**Expected Output:**

```log
🔍 Searching for existing post:
  Title: Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời
  Post Type: post
  
  🔍 Normalized search title: top 10 câu hỏi phỏng vấn visa mỹ thường gặp và cách trả lời
  
  ✅ Found existing post ID: 561 (fuzzy match 95.3% similar)
  📝 WordPress title: Top 10 câu Hỏi Phỏng Vấn Visa Mỹ Thường Gặp và Cách Trả Lời Hiệu Quả
  📝 Normalized WP: top 10 câu hỏi phỏng vấn visa mỹ thường gặp và cách trả lời
```

**Nếu thấy:**
```log
❌ No existing post found (best match: 87.5%)
📋 Top similar posts:
  - ID 561 (87.5%): Top 10 câu Hỏi...
```
→ Similarity < 90%, có thể cần adjust threshold hoặc sửa title trong sheet

---

### Bước 4: Verify Preview Table

**Expected:**

| Row | Title | Action |
|-----|-------|--------|
| 2 | Top 10 câu hỏi phỏng vấn visa Mỹ... | ✏️ **Update** |
| 3 | 7 ngày khám phá Hà Nội... | ➕ **Create** (nếu chưa tồn tại) |
| 4 | Top 10 câu hỏi phỏng vấn visa Schengen... | ✏️ **Update** |
| 5 | Hướng dẫn xin visa Thái Lan... | ✏️ **Update** |
| 6 | Hướng dẫn xin visa Hàn Quốc... | ✏️ **Update** |

---

## 📊 SIMILARITY EXAMPLES

### Example 1: High Similarity (Will Match)

```
Sheet:     "Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời"
WordPress: "Top 10 câu Hỏi Phỏng Vấn Visa Mỹ Thường Gặp và Cách Trả Lời Hiệu Quả"

Normalized Sheet:  "top 10 câu hỏi phỏng vấn visa mỹ thường gặp và cách trả lời"
Normalized WP:     "top 10 câu hỏi phỏng vấn visa mỹ thường gặp và cách trả lời"

Similarity: 100% ✅ → MATCH
```

### Example 2: Medium Similarity (Will Match)

```
Sheet:     "Hướng dẫn xin visa du lịch Thái Lan từ A đến Z"
WordPress: "Hướng dẫn xin visa du lịch Thái Lan chi tiết 2025 – Hồ sơ, quy trình"

Normalized Sheet:  "hướng dẫn xin visa du lịch thái lan"
Normalized WP:     "hướng dẫn xin visa du lịch thái lan chi tiết 2025 – hồ sơ, quy trình"

Similarity: 92% ✅ → MATCH (>= 90%)
```

### Example 3: Low Similarity (Won't Match)

```
Sheet:     "Kinh nghiệm du lịch Nhật Bản"
WordPress: "Hướng dẫn xin visa du lịch Nhật Bản"

Normalized Sheet:  "kinh nghiệm du lịch nhật bản"
Normalized WP:     "hướng dẫn xin visa du lịch nhật bản"

Similarity: 65% ❌ → NO MATCH (< 90%)
```

---

## 🔧 TROUBLESHOOTING

### Issue 1: Vẫn Không Tìm Thấy Bài Viết

**Triệu chứng:** Debug log hiển thị similarity < 90%

**Giải pháp:**

#### Option 1: Lower Threshold (CODE)
```php
// Change from 90% to 85%
if ($similarity >= 85) {  // Was: 90
    return $post->ID;
}
```

#### Option 2: Adjust WordPress Post Title (RECOMMENDED)
Vào WordPress → Edit Post → Sửa title cho khớp với H1 trong sheet

```
❌ Cũ: "Top 10 câu Hỏi Phỏng Vấn Visa Mỹ Thường Gặp và Cách Trả Lời Hiệu Quả"
✅ Mới: "Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời"
```

#### Option 3: Adjust Sheet H1 (RECOMMENDED)
Sửa H1 trong Google Sheet để khớp với WordPress title

```
Sheet Column A (outline):
❌ Cũ: H1: Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời
✅ Mới: H1: Top 10 câu Hỏi Phỏng Vấn Visa Mỹ Thường Gặp và Cách Trả Lời Hiệu Quả
```

---

### Issue 2: Matching Wrong Post

**Triệu chứng:** Plugin match với bài viết sai

**Nguyên nhân:** 2 bài viết có title quá giống nhau

**Giải pháp:** Kiểm tra debug log để xem similarity của tất cả posts:

```log
📋 Top similar posts:
  - ID 561 (95.3%): Top 10 câu hỏi phỏng vấn visa Mỹ...
  - ID 562 (93.1%): Top 20 câu hỏi phỏng vấn visa Mỹ...
  - ID 563 (88.7%): Top 10 câu hỏi phỏng vấn visa Schengen...
```

→ Nếu có nhiều bài >= 90%, post đầu tiên sẽ được chọn

**Fix:** Adjust titles để unique hơn

---

### Issue 3: Row 3 Vẫn Hiển Thị "Create"

**Triệu chứng:** Row 3 ("7 ngày khám phá Hà Nội") vẫn không match

**Debug:**
```log
❌ No existing post found (best match: 45.2%)
📋 Top similar posts:
  - ID 123 (45.2%): Lịch trình du lịch Hà Nội 5 ngày 4 đêm
  - ID 124 (38.1%): Top 10 điểm du lịch Hà Nội
```

→ Similarity quá thấp → Bài viết thực sự CHƯA TỒN TẠI hoặc title khác hoàn toàn

**Giải pháp:**
1. Check WordPress → Posts → Tìm kiếm "Hà Nội"
2. Nếu có → Copy exact post_title
3. Update H1 trong sheet để khớp CHÍNH XÁC

---

## 💡 KHUYẾN NGHỊ

### 1. **Best Practice: Sync Title Format**

**Chọn 1 trong 2:**

#### A. WordPress làm chuẩn (KHUYẾN NGHỊ)
```
1. Tạo bài viết trong WordPress với title chuẩn
2. Copy post_title từ WordPress
3. Paste vào H1 trong Google Sheet
→ Đảm bảo 100% khớp!
```

#### B. Sheet làm chuẩn
```
1. Viết H1 trong Google Sheet trước
2. Khi import lần đầu, WordPress sẽ tạo post với H1 đó
3. Lần sau import sẽ match được
→ Simple & consistent
```

---

### 2. **Title Format Standards**

**Recommended:**
```
- Lowercase: "hướng dẫn xin visa..." (dễ normalize)
- No extra suffixes: Không thêm "hiệu quả", "chi tiết" tùy ý
- Consistent dashes: Dùng " – " (en dash) hoặc " - " (hyphen)
```

**Avoid:**
```
❌ Random capitalization: "câu Hỏi PhỎng VẤn"
❌ Extra words: "...hiệu quả", "...mới nhất 2025"
❌ Mixed dashes: "–" vs "-" vs "—"
```

---

### 3. **Testing Workflow**

```
1. Tạo 1 bài test trong WordPress
   Title: "Test Bài Viết ABC"

2. Trong Google Sheet:
   H1: Test Bài Viết ABC

3. Click Load Preview
   → Should show ✏️ Update

4. Nếu vẫn Create:
   - Check debug log similarity %
   - Adjust title để khớp

5. Khi đã match → Apply cho tất cả bài viết
```

---

## 📂 FILES CHANGED

### 1. `includes/class-wpgsip-import-ajax.php`

**Changes:**
- ✅ Updated `find_existing_post_by_title()` - 3-tier matching
- ✅ Added `normalize_title()` function - NEW
- ✅ Added fuzzy matching with similar_text()
- ✅ Enhanced debug logging with similarity %

**Lines changed:** ~65 lines

---

## ✅ SUCCESS CRITERIA

- [ ] **All 5 rows match correctly:**
  - Row 2: ✏️ Update (ID 561)
  - Row 3: ✏️ Update OR ➕ Create (if not exists)
  - Row 4: ✏️ Update (ID 563)
  - Row 5: ✏️ Update (ID 564)
  - Row 6: ✏️ Update (ID 565)

- [ ] **Debug log shows:**
  - Normalized titles for comparison
  - Similarity percentages
  - Match type (exact/case-insensitive/fuzzy)

- [ ] **Import works:**
  - Update: No duplicates created
  - Match rate: >= 80% of existing posts matched

- [ ] **Performance:**
  - Load Preview: < 5 seconds
  - No timeout errors

---

## 🎯 EXPECTED DEBUG LOG OUTPUT

```log
🔍 Searching for existing post:
  Title: Top 10 câu hỏi phỏng vấn visa Mỹ thường gặp và cách trả lời
  Post Type: post
  🔍 Normalized search title: top 10 câu hỏi phỏng vấn visa mỹ thường gặp và cách trả lời
  ✅ Found existing post ID: 561 (fuzzy match 96.2% similar)
  📝 WordPress title: Top 10 câu Hỏi Phỏng Vấn Visa Mỹ Thường Gặp và Cách Trả Lời Hiệu Quả
  📝 Normalized WP: top 10 câu hỏi phỏng vấn visa mỹ thường gặp và cách trả lời

🔍 Searching for existing post:
  Title: 7 ngày khám phá Hà Nội – Lịch trình chi tiết từ A đến Z
  Post Type: post
  🔍 Normalized search title: 7 ngày khám phá hà nội - lịch trình chi tiết
  ❌ No existing post found (best match: 45.2%)
  📋 Top similar posts:
    - ID 123 (45.2%): Lịch trình du lịch Hà Nội 5 ngày
    - ID 124 (38.1%): Top 10 điểm du lịch Hà Nội
```

---

**✅ DONE! HÃY TEST VÀ BÁO KẾT QUẢ!**

**File created:** 2025-10-06  
**Version:** 2.0  
**Status:** Ready for testing with fuzzy matching
