# Installation Guide

## Prerequisites

- WordPress 6.0 or higher
- PHP 8.0 or higher
- Composer installed on your server
- Google Cloud Project with Sheets API enabled
- (Optional) n8n instance for webhook integration

## Step 1: Download & Install Plugin

1. Download the plugin files or clone from repository
2. Upload to `/wp-content/plugins/wp-google-sheets-import-pro/`
3. Or install via WordPress admin (if packaged as ZIP)

## Step 2: Install Dependencies

Open terminal/command prompt and navigate to the plugin directory:

```bash
cd /path/to/wordpress/wp-content/plugins/wp-google-sheets-import-pro
composer install
```

This will install the Google API Client library and dependencies.

## Step 3: Activate Plugin

1. Log in to WordPress admin
2. Go to **Plugins** → **Installed Plugins**
3. Find "WP Google Sheets Import Pro"
4. Click **Activate**

## Step 4: Configure Google Sheets API

### Create Service Account

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select existing
3. Enable **Google Sheets API**:
   - Go to APIs & Services → Library
   - Search "Google Sheets API"
   - Click Enable
4. Create Service Account:
   - Go to APIs & Services → Credentials
   - Click "Create Credentials" → "Service Account"
   - Name your service account
   - Skip optional permissions
   - Click Done
5. Create JSON Key:
   - Click on the created service account
   - Go to "Keys" tab
   - Click "Add Key" → "Create new key"
   - Choose JSON format
   - Download the JSON file

### Share Google Sheet

1. Open your Google Sheet
2. Click **Share** button
3. Add the service account email (found in JSON file: `client_email`)
4. Grant "Viewer" or "Editor" access
5. Click Send

### Get Sheet ID and Range

1. Open your Google Sheet
2. Copy the Sheet ID from URL:
   ```
   https://docs.google.com/spreadsheets/d/[SHEET_ID]/edit
   ```
3. Note your data range (e.g., `Sheet1!A2:F`)

## Step 5: Configure Plugin

1. Go to **GS Import Pro** → **Settings**
2. **Google Sheets Configuration:**
   - Paste Sheet ID
   - Enter Sheet Range (e.g., `Sheet1!A2:F`)
   - Paste entire Service Account JSON content
3. **Import Options:**
   - Choose default post status
   - Set batch size (recommended: 10-20)
   - Configure cache duration
4. Click **Save Settings**
5. Click **Test Google Sheets Connection** to verify

## Step 6: Configure n8n Webhook (Optional)

### Set Up n8n Workflow

1. Create a new workflow in n8n
2. Add a **Webhook** node:
   - Set to POST method
   - Copy webhook URL
3. Add logic to:
   - Receive outline and metadata
   - Generate content using AI (e.g., OpenAI, Claude)
   - Update Google Sheet with generated content
4. Activate workflow

### Configure in Plugin

1. Go to **GS Import Pro** → **Settings**
2. **n8n Webhook Configuration:**
   - Check "Enable n8n Webhook"
   - Paste webhook URL
   - Set wait time (20-30 seconds recommended)
3. Click **Save Settings**

## Step 7: Set Up Scheduled Import (Optional)

1. Go to **GS Import Pro** → **Settings**
2. **Scheduled Import:**
   - Check "Enable Automatic Import"
   - Choose frequency (hourly/daily/weekly)
3. Click **Save Settings**

## Step 8: First Import

1. Go to **GS Import Pro** → **Import**
2. Click **Load Preview** to verify data
3. Review the preview data
4. Click **Start Import**
5. Monitor progress

## Verification

After successful import:

1. Go to **GS Import Pro** → **Dashboard** to see statistics
2. Check **Imported Posts** to see imported content
3. Review **Logs** for any errors or warnings

## Troubleshooting

### "Please run composer install"
- SSH into your server
- Navigate to plugin directory
- Run `composer install`

### "Failed to fetch data from Google Sheets"
- Verify service account JSON is correct
- Check that sheet is shared with service account email
- Verify Sheet ID and Range are correct

### "Webhook connection failed"
- Verify n8n webhook URL is accessible
- Check that n8n workflow is activated
- Test webhook endpoint manually

### Import timeouts
- Reduce batch size in settings
- Increase PHP max_execution_time
- Use scheduled imports for large datasets

## Server Requirements

### Minimum:
- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.3+
- PHP memory_limit: 128M
- PHP max_execution_time: 60 seconds

### Recommended:
- PHP 8.1+
- MySQL 8.0+ or MariaDB 10.6+
- PHP memory_limit: 256M or higher
- PHP max_execution_time: 300 seconds
- PHP extensions: curl, json, mbstring

## Security Recommendations

1. Keep plugin updated
2. Use strong Service Account credentials
3. Don't share Service Account JSON publicly
4. Use HTTPS for webhook connections
5. Restrict plugin access to trusted administrators
6. Regularly review import logs

## Next Steps

- Customize import behavior using hooks
- Set up multi-tenant configuration (if needed)
- Configure SEO plugins (Yoast/Rank Math)
- Set up monitoring for scheduled imports

For more information, see README.md and plugin documentation.
