# Google Sheet Template Example

## Cấu trúc sheet chuẩn cho WP Google Sheets Import Pro

### Sheet Structure

Create a Google Sheet with the following columns (starting from row 1):

| A | B | C | D | E | F |
|---|---|---|---|---|---|
| Outline | Meta Title | Meta Description | Keyword | STATUS | Content |

### Example Data (Row 2 onwards)

#### Row 2:
- **A2 (Outline):**
```
Giới thiệu về du lịch Việt Nam
- Địa điểm nổi tiếng: Hạ Long, Sapa, Phú Quốc
- Ẩm thực: Phở, Bún chả, Bánh mì
- Văn hóa: Lễ hội, truyền thống
- Tips du lịch: Thời điểm tốt nhất, ngân sách
```

- **B2 (Meta Title):**
```
Du Lịch Việt Nam 2025: Hướng Dẫn Đầy Đủ Từ A-Z
```

- **C2 (Meta Description):**
```
Khám phá du lịch Việt Nam với hướng dẫn chi tiết về địa điểm, ẩm thực, văn hóa và tips hữu ích cho chuyến đi hoàn hảo năm 2025.
```

- **D2 (Keyword):**
```
du lịch việt nam, tour việt nam, địa điểm du lịch
```

- **E2 (STATUS):**
```
04/10/2025
```

- **F2 (Content):**
```html
<h2>Du Lịch Việt Nam - Điểm Đến Hấp Dẫn Châu Á</h2>

<p>Việt Nam là một trong những điểm đến du lịch hấp dẫn nhất Đông Nam Á...</p>

<h3>Địa Điểm Nổi Tiếng</h3>
<ul>
  <li><strong>Vịnh Hạ Long</strong> - Di sản thiên nhiên thế giới</li>
  <li><strong>Sapa</strong> - Thị trấn sương mù với ruộng bậc thang</li>
  <li><strong>Phú Quốc</strong> - Đảo ngọc với bãi biển tuyệt đẹp</li>
</ul>

...
```

#### Row 3 (Example with empty content - will trigger n8n):
- **A3:** 
```
10 món ăn đường phố Hà Nội phải thử
- Phở bò/gà
- Bún chả
- Bánh mì
- Chả cá Lã Vọng
...
```

- **B3:** `Ẩm Thực Đường Phố Hà Nội: 10 Món Ăn Không Thể Bỏ Qua`
- **C3:** `Khám phá 10 món ăn đường phố ngon nhất Hà Nội với giá cả phải chăng và địa chỉ cụ thể để thưởng thức.`
- **D3:** `ăn vặt hà nội, món ăn đường phố, ẩm thực việt nam`
- **E3:** `04/10/2025`
- **F3:** `[Leave empty - n8n will generate content from Outline]`

### Google Sheets Settings

**Important settings for your sheet:**

1. **Sharing:**
   - Share with service account email: `your-service-account@project-id.iam.gserviceaccount.com`
   - Permission: Viewer or Editor
   - Notification: Not required

2. **Range in Plugin Settings:**
   - If data starts from row 2: `Sheet1!A2:F`
   - If data starts from row 2 and you have 100 rows: `Sheet1!A2:F101`
   - Dynamic range (all data): `Sheet1!A2:F`

3. **Sheet ID (from URL):**
   ```
   https://docs.google.com/spreadsheets/d/1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms/edit
                                          ↑ This is your Sheet ID ↑
   ```

### Column Descriptions

#### A - Outline
- Purpose: Content outline for AI generation (if F is empty)
- Format: Bullet points, structured text
- Required: Yes (if Content is empty)
- Used by: n8n webhook to generate full content

#### B - Meta Title
- Purpose: Post title and SEO title
- Format: Plain text, 50-60 characters optimal
- Required: Yes
- Becomes: WordPress post_title, Yoast/Rank Math title

#### C - Meta Description
- Purpose: Post excerpt and meta description
- Format: Plain text, 150-160 characters optimal
- Required: Yes
- Becomes: post_excerpt, Yoast/Rank Math description

#### D - Keyword
- Purpose: Main keyword(s) for SEO and tags
- Format: Comma-separated keywords
- Required: Optional
- Becomes: Post tags, Yoast/Rank Math focus keyword

#### E - STATUS
- Purpose: Row status for filtering
- Format: Free text (date, status code, etc.)
- Required: Optional
- Used for: Conditional import (skip certain statuses)

#### F - Content
- Purpose: Full post content
- Format: HTML or plain text
- Required: Yes (or empty for n8n generation)
- Becomes: post_content
- If empty: Plugin triggers n8n webhook, waits, then refetches

### Tips for Best Results

1. **HTML Formatting:**
   - Use proper HTML tags: `<h2>`, `<h3>`, `<p>`, `<ul>`, `<ol>`
   - Include internal/external links: `<a href="...">`
   - Add images: `<img src="..." alt="...">`

2. **SEO Optimization:**
   - Meta Title: Include main keyword, max 60 chars
   - Meta Description: Compelling description, 150-160 chars
   - Keywords: 3-5 relevant keywords

3. **Content Quality:**
   - Minimum 500 words for SEO
   - Use headings (H2, H3) for structure
   - Include relevant images
   - Add internal links

4. **Status Management:**
   - Use dates for scheduling: `04/10/2025`
   - Use flags: `READY`, `DRAFT`, `SKIP`
   - Leave blank for all rows to import

### n8n Integration

If using n8n for content generation:

1. **Outline (Column A) should include:**
   - Main topic
   - Key points to cover
   - Target word count
   - Tone/style preferences

2. **n8n Workflow should:**
   - Receive webhook from plugin
   - Generate content based on outline
   - Update cell F with generated content
   - Return success status

3. **Wait Time in Plugin:**
   - Set based on content generation time
   - Recommended: 20-30 seconds
   - Max: 120 seconds

### Testing Your Sheet

Before full import:

1. ✅ Create 1-2 test rows
2. ✅ Share with service account
3. ✅ Copy Sheet ID
4. ✅ Set Range: `Sheet1!A2:F`
5. ✅ Test Connection in plugin
6. ✅ Load Preview
7. ✅ Import test rows
8. ✅ Check WordPress posts

### Common Mistakes to Avoid

❌ Not sharing sheet with service account
❌ Wrong Sheet ID or Range
❌ Missing required columns (A, B, C)
❌ Content too short (< 100 words)
❌ Not testing before bulk import
❌ Forgetting to enable n8n if using it

### Example Sheet URL

You can create a copy of example sheet:
```
[Create your own sheet based on this template]
```

### Batch Import Recommendations

For large datasets:

- **< 50 rows:** Import all at once (batch size: 10-20)
- **50-200 rows:** Use batch import (batch size: 10)
- **> 200 rows:** Use scheduled import (daily/hourly)

### Troubleshooting

**Issue:** "Failed to fetch data"
- Check: Sheet shared? Correct ID? Valid range?

**Issue:** "Content is empty"
- Check: Column F has data? n8n working?

**Issue:** "Import timeout"
- Solution: Reduce batch size, increase wait time

---

**Ready to start?**
1. Create sheet with structure above
2. Add your data
3. Follow INSTALL.md to configure plugin
4. Import and enjoy! 🚀
