# 🚀 QUICK START GUIDE - 5 Minutes Setup

## Step 1: Activate Plugin (30 seconds)
```
WordPress Admin → Plugins → WP Google Sheets Import Pro → Activate
```

## Step 2: Get Google Credentials (2 minutes)

### Option A: Service Account (Recommended)
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create project → Enable "Google Sheets API"
3. Create Credentials → Service Account → Download JSON
4. Copy the service account email (in JSON file)

### Option B: API Key (For public sheets)
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create Credentials → API Key
3. Copy the API key

## Step 3: Share Your Google Sheet (30 seconds)
1. Open your Google Sheet
2. Click **Share** button
3. Add service account email (from JSON) or make public
4. Grant "Viewer" or "Editor" access

## Step 4: Configure Plugin (1 minute)

```
WordPress Admin → GS Import Pro → Settings
```

**Enter:**
- **Google Sheet ID**: `1BxiMVs0XRA5nFMdKvBdBZjgmUUqptlbs74OgvE2upms`
  *(Get from URL between /d/ and /edit)*
  
- **Sheet Range**: `Sheet1!A2:F`
  *(Your data range)*
  
- **Service Account JSON**: `{paste entire JSON content}`
  *(The whole JSON file content)*

**Click:** Save Settings → Test Connection

✅ Success? You're ready!

## Step 5: First Import (1 minute)

```
WordPress Admin → GS Import Pro → Import
```

1. Click **Load Preview** (verify data)
2. Click **Start Import**
3. Wait for completion
4. Check **Imported Posts**

---

## 📊 Your Google Sheet Must Have This Structure:

| A | B | C | D | E | F |
|---|---|---|---|---|---|
| Outline | Meta Title | Meta Description | Keyword | STATUS | Content |

**Example Row:**
```
A: "Introduction to WordPress plugins - what they are, how to install..."
B: "WordPress Plugins: Complete Guide 2025"
C: "Learn everything about WordPress plugins with step-by-step guide..."
D: "wordpress plugins, wp plugins"
E: "10/04/2025"
F: "[Full HTML content here]"
```

---

## 🔗 Optional: n8n Webhook (For AI Content Generation)

If Column F (Content) is empty, plugin can trigger n8n to generate it:

### Setup n8n:
1. Create workflow with Webhook node
2. Add AI node (ChatGPT/Claude) to generate content from Column A
3. Update Google Sheet Column F with generated content
4. Activate workflow and copy webhook URL

### Configure in Plugin:
```
Settings → n8n Webhook Configuration
→ Enable: ✓
→ Webhook URL: [your n8n URL]
→ Wait Time: 20 seconds
→ Save
```

**How it works:**
1. Plugin finds empty content (Column F)
2. Sends outline (Column A) to n8n webhook
3. Waits configured time
4. Refetches sheet to get generated content
5. Creates WordPress post with new content

---

## ✅ Success Checklist

After first import, verify:

- [ ] Posts created in WordPress
- [ ] Titles match Meta Title (Column B)
- [ ] Content matches Column F
- [ ] Tags created from Keywords (Column D)
- [ ] SEO fields populated (if using Yoast/Rank Math)
- [ ] Check Dashboard statistics
- [ ] Review Logs for errors

---

## 🎯 Common Settings

### For Daily Auto Import:
```
Settings → Scheduled Import
→ Enable: ✓
→ Frequency: Daily
→ Save
```

### For Better Performance:
```
Settings → Import Options
→ Batch Size: 10
→ Cache Duration: 300
→ Save
```

---

## 🆘 Quick Troubleshooting

### ❌ "Failed to fetch data"
→ Check: Sheet shared? Correct Sheet ID? Valid JSON?

### ❌ "Import timeout"
→ Solution: Reduce batch size to 5-10

### ❌ "Content is empty"
→ Check: Column F has data? Or n8n enabled?

### ❌ "Permission denied"
→ Check: Service account has access? Sheet shared?

---

## 📚 Need More Help?

- **Quick Guide (Vietnamese):** `HUONG-DAN.md`
- **Detailed Install:** `INSTALL.md`
- **Developer Docs:** `DEVELOPER.md`
- **Sheet Template:** `SHEET-TEMPLATE.md`
- **Full Summary:** `SUMMARY.md`

---

## 🎉 That's It!

You're now importing posts from Google Sheets! 

**Next:**
- Import more posts
- Set up scheduled imports
- Configure n8n (optional)
- Customize with hooks

**Enjoy your automated content workflow! 🚀**

---

**Need the 30-second version?**

1. Activate plugin
2. Get Google credentials
3. Share sheet
4. Paste settings
5. Import!

**Done!** ✅
