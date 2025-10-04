# WP Google Sheets Import Pro

Import and manage WordPress posts from Google Sheets with n8n webhook integration. Designed for scalability and multi-tenant support.

## Features

- 📊 **Google Sheets Integration**: Import posts directly from Google Sheets
- 🔗 **n8n Webhook Support**: Trigger content generation when content is empty
- 🔄 **Smart Import**: Create new posts or update existing ones based on unique identifiers
- 📅 **Scheduled Imports**: Automatic imports on hourly, daily, or weekly schedules
- 🏢 **Multi-Tenant Ready**: Built with multi-site and custom tenant mapping support
- 📝 **SEO Support**: Compatible with Yoast SEO and Rank Math
- 📊 **Dashboard & Analytics**: View import statistics and activity logs
- ⚡ **Batch Processing**: Import large datasets efficiently with AJAX-based batch processing

## Requirements

- WordPress 6.0 or higher
- PHP 8.0 or higher
- Composer (for installing dependencies)

## Installation

1. Download or clone this plugin to your WordPress plugins directory
2. Navigate to the plugin directory in terminal/command prompt
3. Run `composer install` to install dependencies
4. Activate the plugin through the WordPress admin panel
5. Go to **GS Import Pro > Settings** to configure

## Configuration

### Google Sheets Setup

1. Create a Google Cloud Project
2. Enable Google Sheets API
3. Create a Service Account and download the JSON credentials
4. Share your Google Sheet with the service account email
5. In plugin settings, paste the Service Account JSON
6. Enter your Google Sheet ID and Range (e.g., `Sheet1!A2:F`)

**Sheet Structure:**
- Column A: Outline (used for content generation)
- Column B: Meta Title (post title)
- Column C: Meta Description (post excerpt/SEO description)
- Column D: Keyword (post tags/SEO keyword)
- Column E: STATUS (optional status filter)
- Column F: Content (full post content)

### n8n Webhook Setup (Optional)

If you want to automatically generate content from outlines:

1. Create an n8n workflow with a webhook trigger
2. Configure the workflow to:
   - Receive the outline and metadata
   - Generate content using AI (ChatGPT, Claude, etc.)
   - Update the Google Sheet with generated content
3. Copy the webhook URL
4. In plugin settings:
   - Enable n8n webhook
   - Paste the webhook URL
   - Set wait time (how long to wait for content generation)

## Usage

### Manual Import

1. Go to **GS Import Pro > Import**
2. Click **Load Preview** to see data from your sheet
3. Click **Start Import** to begin importing
4. Monitor progress and view results

### Scheduled Import

1. Go to **GS Import Pro > Settings**
2. Enable **Automatic Import**
3. Choose import frequency
4. Save settings

### View Imported Posts

Go to **GS Import Pro > Imported Posts** to see all posts imported from Google Sheets.

### View Logs

Go to **GS Import Pro > Logs** to see detailed import activity logs.

## Multi-Tenant Support

The plugin is designed to support multiple sites/tenants:

### WordPress Multisite

The plugin automatically detects WordPress Multisite and creates separate tenant configurations for each site.

### Custom Tenant Mapping

For custom multi-tenant setups (not using WP Multisite):

1. Use the `wpgsip_custom_tenant_id` filter to define tenant identification
2. Create tenant mappings in the `wp_wpgsip_tenants` table
3. Each tenant can have its own Google Sheet and settings

## Hooks & Filters

### Actions

- `wpgsip_loaded` - Fired when plugin is fully loaded
- `wpgsip_before_create_post` - Before creating a new post
- `wpgsip_after_create_post` - After creating a new post
- `wpgsip_before_update_post` - Before updating a post
- `wpgsip_after_update_post` - After updating a post

### Filters

- `wpgsip_should_skip_row` - Control which rows to skip during import
- `wpgsip_webhook_payload` - Modify webhook payload before sending
- `wpgsip_custom_tenant_id` - Define custom tenant identification

## Troubleshooting

### Google Sheets Connection Issues

- Ensure the Google Sheet is shared with the service account email
- Verify the Sheet ID and Range are correct
- Check that the Service Account JSON is valid

### Import Failures

- Check the Logs page for detailed error messages
- Verify that your sheet has the correct column structure
- Ensure PHP memory limit is sufficient for large imports

### n8n Webhook Issues

- Test the webhook URL is accessible
- Check wait time is sufficient for content generation
- Verify webhook returns updated content to the sheet

## Changelog

### 1.0.0
- Initial release
- Google Sheets integration
- n8n webhook support
- Batch import functionality
- Multi-tenant architecture
- Dashboard and analytics
- Scheduled imports
- SEO plugin compatibility

## Support

For support, please contact the plugin author or visit the support forum.

## License

GPL v2 or later
