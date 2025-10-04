# Changelog

## Version 1.0.0 (2025-10-04)

### Initial Release

**Features:**
- Google Sheets API integration with Service Account authentication
- Import posts from Google Sheets with configurable column mapping
- n8n webhook integration for automatic content generation
- Batch processing with AJAX for large datasets
- Smart post detection (create new or update existing)
- Multi-tenant architecture for future scalability
- WordPress Multisite support
- Scheduled imports (hourly, daily, weekly)
- Import activity dashboard with statistics
- Detailed logging system
- SEO plugin support (Yoast SEO, Rank Math)
- Imported posts management interface
- Configurable post status, batch size, cache duration
- Test connection feature for Google Sheets

**Database:**
- `wp_wpgsip_import_logs` - Import activity logs
- `wp_wpgsip_tenants` - Tenant configuration for multi-site support

**Admin Pages:**
- Dashboard with statistics and recent activity
- Import page with preview and batch processing
- Imported Posts listing
- Settings page with comprehensive configuration
- Logs viewer with pagination

**Hooks & Filters:**
- `wpgsip_loaded` - Plugin initialization complete
- `wpgsip_before_create_post` / `wpgsip_after_create_post`
- `wpgsip_before_update_post` / `wpgsip_after_update_post`
- `wpgsip_should_skip_row` - Custom row filtering
- `wpgsip_webhook_payload` - Webhook payload modification
- `wpgsip_custom_tenant_id` - Custom tenant identification

**Security:**
- Nonce verification for all AJAX requests
- Capability checks (manage_options)
- Input sanitization and validation
- SQL injection prevention with prepared statements
- XSS protection with proper escaping

**Performance:**
- Transient caching for Google Sheets data
- Optimized database queries with indexes
- Batch processing to prevent timeouts
- Configurable cache duration

**Compatibility:**
- WordPress 6.0+
- PHP 8.0+
- Google API Client 2.15+
- Works with Yoast SEO
- Works with Rank Math SEO
- WordPress Multisite compatible
