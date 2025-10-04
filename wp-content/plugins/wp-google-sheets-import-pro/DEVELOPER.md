# WP Google Sheets Import Pro - Developer Documentation

## Architecture Overview

### Plugin Structure

```
wp-google-sheets-import-pro/
├── admin/                      # Admin area functionality
│   ├── class-wpgsip-admin.php
│   ├── class-wpgsip-dashboard.php
│   ├── class-wpgsip-posts-list.php
│   └── views/                  # Admin page templates
│       ├── dashboard.php
│       ├── import.php
│       ├── posts.php
│       ├── settings.php
│       └── logs.php
├── assets/                     # Static assets
│   ├── css/
│   │   └── admin.css
│   └── js/
│       └── admin.js
├── includes/                   # Core functionality
│   ├── class-wpgsip-activator.php
│   ├── class-wpgsip-core.php
│   ├── class-wpgsip-cron.php
│   ├── class-wpgsip-deactivator.php
│   ├── class-wpgsip-google-sheets.php
│   ├── class-wpgsip-importer.php
│   ├── class-wpgsip-logger.php
│   ├── class-wpgsip-settings.php
│   ├── class-wpgsip-tenant-manager.php
│   └── class-wpgsip-webhook.php
├── vendor/                     # Composer dependencies
├── wp-google-sheets-import-pro.php  # Main plugin file
├── composer.json
├── uninstall.php
└── README.md
```

### Core Classes

#### WPGSIP_Core
Main plugin class that orchestrates all functionality.
- Initializes all components
- Registers hooks and filters
- Handles AJAX requests

#### WPGSIP_Google_Sheets
Google Sheets API integration.
- Authenticates with Service Account
- Fetches data from sheets
- Caches data using transients
- Provides connection testing

#### WPGSIP_Importer
Import functionality.
- Batch import processing
- Creates/updates posts
- Handles webhook triggers
- Manages post metadata

#### WPGSIP_Webhook
n8n webhook integration.
- Triggers content generation
- Waits for response
- Refetches updated data

#### WPGSIP_Tenant_Manager
Multi-tenant support.
- Manages tenant configurations
- Switches between sites (Multisite)
- Maps domains to tenants

#### WPGSIP_Logger
Logging system.
- Records import activities
- Provides statistics
- Stores detailed error information

#### WPGSIP_Settings
Settings management.
- Global settings
- Tenant-specific settings
- Settings API integration

#### WPGSIP_Cron
Scheduled imports.
- WP-Cron integration
- Automatic import execution
- Schedule management

## Database Schema

### wp_wpgsip_import_logs

Stores import activity logs.

```sql
CREATE TABLE wp_wpgsip_import_logs (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    tenant_id varchar(255) DEFAULT NULL,
    blog_id bigint(20) DEFAULT NULL,
    action varchar(50) NOT NULL,
    post_id bigint(20) DEFAULT NULL,
    sheet_row_id varchar(100) DEFAULT NULL,
    status varchar(20) NOT NULL,
    message text,
    data longtext,
    created_at datetime NOT NULL,
    PRIMARY KEY (id),
    KEY tenant_id (tenant_id),
    KEY blog_id (blog_id),
    KEY post_id (post_id),
    KEY status (status),
    KEY created_at (created_at)
);
```

### wp_wpgsip_tenants

Stores tenant configurations for multi-site.

```sql
CREATE TABLE wp_wpgsip_tenants (
    id bigint(20) NOT NULL AUTO_INCREMENT,
    tenant_id varchar(255) NOT NULL,
    tenant_name varchar(255) NOT NULL,
    domain varchar(255) DEFAULT NULL,
    blog_id bigint(20) DEFAULT NULL,
    sheet_id varchar(255) DEFAULT NULL,
    sheet_range varchar(100) DEFAULT NULL,
    webhook_url text,
    settings longtext,
    status varchar(20) DEFAULT 'active',
    created_at datetime NOT NULL,
    updated_at datetime NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY tenant_id (tenant_id),
    KEY domain (domain),
    KEY blog_id (blog_id),
    KEY status (status)
);
```

### Post Meta Keys

- `imported_from_gs` (boolean) - Flag for imported posts
- `gs_sheet_row_id` (string) - Sheet row identifier
- `gs_tenant_id` (string) - Tenant identifier
- `gs_last_sync` (datetime) - Last sync timestamp
- `gs_original_data` (JSON) - Original row data

## Hooks & Filters

### Actions

#### wpgsip_loaded
Fired when plugin is fully initialized.

```php
add_action('wpgsip_loaded', function() {
    // Plugin is ready
});
```

#### wpgsip_before_create_post
Before creating a new post.

```php
add_action('wpgsip_before_create_post', function($post_data, $row, $tenant_id) {
    // Modify $post_data before post creation
}, 10, 3);
```

#### wpgsip_after_create_post
After creating a new post.

```php
add_action('wpgsip_after_create_post', function($post_id, $row, $tenant_id) {
    // Do something after post creation
}, 10, 3);
```

#### wpgsip_before_update_post
Before updating an existing post.

```php
add_action('wpgsip_before_update_post', function($post_data, $row, $tenant_id) {
    // Modify $post_data before post update
}, 10, 3);
```

#### wpgsip_after_update_post
After updating an existing post.

```php
add_action('wpgsip_after_update_post', function($post_id, $row, $tenant_id) {
    // Do something after post update
}, 10, 3);
```

### Filters

#### wpgsip_should_skip_row
Control which rows to skip during import.

```php
add_filter('wpgsip_should_skip_row', function($should_skip, $row, $tenant_id) {
    // Skip rows with specific status
    if ($row['status'] === 'SKIP') {
        return true;
    }
    return $should_skip;
}, 10, 3);
```

#### wpgsip_webhook_payload
Modify webhook payload before sending.

```php
add_filter('wpgsip_webhook_payload', function($payload, $row, $tenant_id) {
    // Add custom fields to webhook
    $payload['custom_field'] = 'value';
    return $payload;
}, 10, 3);
```

#### wpgsip_custom_tenant_id
Define custom tenant identification.

```php
add_filter('wpgsip_custom_tenant_id', function($tenant_id) {
    // Identify tenant by subdomain
    $host = $_SERVER['HTTP_HOST'];
    if (preg_match('/^(\w+)\.example\.com$/', $host, $matches)) {
        return $matches[1];
    }
    return $tenant_id;
});
```

## API Reference

### WPGSIP_Google_Sheets

#### fetch_data($use_cache = true)
Fetch data from Google Sheets.

```php
$sheets = new WPGSIP_Google_Sheets('default');
$data = $sheets->fetch_data();
```

#### refresh_data()
Clear cache and fetch fresh data.

```php
$data = $sheets->refresh_data();
```

#### test_connection($sheet_id, $sheet_range)
Test connection to Google Sheets.

```php
$result = $sheets->test_connection('sheet_id', 'Sheet1!A2:F');
```

### WPGSIP_Importer

#### import_all()
Import all rows from sheet.

```php
$importer = new WPGSIP_Importer('default');
$result = $importer->import_all();
```

#### import_batch($batch, $batch_size)
Import a batch of rows.

```php
$result = $importer->import_batch(0, 10);
```

#### import_row($row)
Import a single row.

```php
$result = $importer->import_row($row_data);
```

### WPGSIP_Webhook

#### trigger_content_generation($row_data)
Trigger n8n webhook.

```php
$webhook = new WPGSIP_Webhook('default');
$result = $webhook->trigger_content_generation($row_data);
```

#### wait_and_refetch($row_id)
Wait and refetch updated content.

```php
$updated_row = $webhook->wait_and_refetch(123);
```

### WPGSIP_Logger

#### log($data)
Log an event.

```php
$logger = new WPGSIP_Logger();
$logger->log(array(
    'tenant_id' => 'default',
    'action' => 'import',
    'status' => 'success',
    'message' => 'Import completed',
));
```

#### get_logs($page, $per_page, $tenant_id, $status)
Get logs with pagination.

```php
$logs = $logger->get_logs(1, 20, 'default', 'error');
```

#### get_statistics($tenant_id, $days)
Get import statistics.

```php
$stats = $logger->get_statistics('default', 30);
```

## Multi-Tenant Implementation

### WordPress Multisite

Plugin automatically detects Multisite:

```php
if (is_multisite()) {
    $tenant_id = 'site_' . get_current_blog_id();
}
```

### Custom Tenant Mapping

Use filter to define custom tenant logic:

```php
add_filter('wpgsip_custom_tenant_id', function($tenant_id) {
    // Example: Map by subdomain
    $parts = explode('.', $_SERVER['HTTP_HOST']);
    if (count($parts) > 2) {
        return $parts[0]; // subdomain
    }
    return 'default';
});
```

Create tenant in database:

```php
$tenant_manager = new WPGSIP_Tenant_Manager();
$tenant_manager->save_tenant(array(
    'tenant_id' => 'client1',
    'tenant_name' => 'Client 1',
    'domain' => 'client1.example.com',
    'sheet_id' => 'sheet_id_here',
    'sheet_range' => 'Sheet1!A2:F',
    'webhook_url' => 'https://n8n.example.com/webhook/client1',
));
```

## Testing

### Test Google Sheets Connection

```php
$sheets = new WPGSIP_Google_Sheets('default');
try {
    $result = $sheets->test_connection();
    echo $result['message'];
} catch (Exception $e) {
    echo $e->getMessage();
}
```

### Test Webhook

```php
$webhook = new WPGSIP_Webhook('default');
try {
    $result = $webhook->test_webhook();
    echo $result['message'];
} catch (Exception $e) {
    echo $e->getMessage();
}
```

### Test Import (Dry Run)

```php
// Get data without importing
$sheets = new WPGSIP_Google_Sheets('default');
$data = $sheets->fetch_data();
var_dump($data);
```

## Security Best Practices

1. **Nonce Verification**: All AJAX requests verify nonces
2. **Capability Checks**: Only `manage_options` users can access
3. **Input Sanitization**: All user input is sanitized
4. **Output Escaping**: All output is properly escaped
5. **Prepared Statements**: All database queries use prepared statements
6. **Service Account Security**: Store JSON securely, don't commit to repo

## Performance Optimization

1. **Caching**: Sheet data cached using transients
2. **Batch Processing**: Large imports processed in batches
3. **Database Indexes**: Tables have proper indexes
4. **AJAX**: Prevents timeouts on large imports
5. **Selective Loading**: Admin assets only load on plugin pages

## Extending the Plugin

### Add Custom Column Mapping

```php
add_filter('wpgsip_before_create_post', function($post_data, $row, $tenant_id) {
    // Map custom column to custom field
    if (isset($row['custom_column'])) {
        add_post_meta($post_data['ID'], 'custom_field', $row['custom_column']);
    }
    return $post_data;
}, 10, 3);
```

### Add Custom Post Type Support

```php
add_filter('wpgsip_before_create_post', function($post_data, $row, $tenant_id) {
    // Change post type based on row data
    if (isset($row['type']) && $row['type'] === 'product') {
        $post_data['post_type'] = 'product';
    }
    return $post_data;
}, 10, 3);
```

### Custom Import Logic

```php
add_action('wpgsip_after_create_post', function($post_id, $row, $tenant_id) {
    // Add custom taxonomy terms
    if (isset($row['category'])) {
        wp_set_post_terms($post_id, explode(',', $row['category']), 'category');
    }
}, 10, 3);
```

## Troubleshooting

### Enable Debug Logging

Add to wp-config.php:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

Check logs in `wp-content/debug.log`.

### Common Issues

1. **Composer not installed**: Run `composer install`
2. **Permission denied**: Check file permissions
3. **Timeout errors**: Reduce batch size in settings
4. **Memory errors**: Increase PHP memory_limit

## Support

For support or questions, please check:
- Plugin documentation
- WordPress.org support forum
- GitHub issues (if open source)

## License

GPL v2 or later
