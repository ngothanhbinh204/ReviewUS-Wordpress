<?php
/**
 * Debug Test Script for WPGSIP
 * 
 * Access: http://reviewus.local/wp-content/plugins/wp-google-sheets-import-pro/debug-test.php
 */

// Load WordPress
require_once('../../../wp-load.php');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>WPGSIP Debug Test</h1>";

// Test 1: Check if classes exist
echo "<h2>1. Class Check</h2>";
$classes = [
    'WPGSIP_Core',
    'WPGSIP_Settings',
    'WPGSIP_Importer',
    'WPGSIP_Google_Sheets',
    'WPGSIP_Content_Processor',
    'WPGSIP_Logger',
    'WPGSIP_Webhook',
    'WPGSIP_Tenant_Manager'
];

foreach ($classes as $class) {
    if (class_exists($class)) {
        echo "✅ {$class} exists<br>";
    } else {
        echo "❌ {$class} NOT found<br>";
    }
}

// Test 2: Check Content Processor
echo "<h2>2. Content Processor Test</h2>";
try {
    if (class_exists('WPGSIP_Content_Processor')) {
        $processor = new WPGSIP_Content_Processor();
        
        $test_content = "H1: Test Title\n\nThis is **bold** text.\n\nH2: Section 1\n\n- Item 1\n- Item 2";
        
        echo "<h3>Input:</h3>";
        echo "<pre>" . htmlspecialchars($test_content) . "</pre>";
        
        $processed = $processor->process_for_seo($test_content);
        
        echo "<h3>Output:</h3>";
        echo "<pre>" . htmlspecialchars(print_r($processed, true)) . "</pre>";
        
        echo "✅ Content Processor works!<br>";
    } else {
        echo "❌ Content Processor class not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Content Processor error: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Test 3: Check Settings
echo "<h2>3. Settings Test</h2>";
try {
    if (class_exists('WPGSIP_Settings')) {
        $settings = new WPGSIP_Settings();
        $all_settings = $settings->get_all();
        
        echo "Current settings:<br>";
        echo "<pre>" . htmlspecialchars(print_r($all_settings, true)) . "</pre>";
        echo "✅ Settings loaded<br>";
    } else {
        echo "❌ Settings class not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Settings error: " . $e->getMessage() . "<br>";
}

// Test 4: Check Tenant Settings
echo "<h2>4. Tenant Settings Test</h2>";
try {
    if (class_exists('WPGSIP_Settings')) {
        $settings = new WPGSIP_Settings();
        $tenant_settings = $settings->get_tenant_settings('default');
        
        echo "Tenant settings for 'default':<br>";
        echo "<pre>" . htmlspecialchars(print_r($tenant_settings, true)) . "</pre>";
        echo "✅ Tenant settings loaded<br>";
    }
} catch (Exception $e) {
    echo "❌ Tenant settings error: " . $e->getMessage() . "<br>";
}

// Test 5: Simulate Import
echo "<h2>5. Import Simulation</h2>";
try {
    if (class_exists('WPGSIP_Importer')) {
        $importer = new WPGSIP_Importer('default');
        echo "✅ Importer initialized<br>";
        
        // Test with dummy data
        $test_row = array(
            'row_id' => 'test-1',
            'meta_title' => 'Test Post Title',
            'meta_description' => 'Test post description',
            'keyword' => 'test, keyword',
            'status' => '',
            'content' => "H1: Test Article\n\nThis is a **test** article.\n\nH2: Section 1\n\n- Point 1\n- Point 2"
        );
        
        echo "Test row data:<br>";
        echo "<pre>" . htmlspecialchars(print_r($test_row, true)) . "</pre>";
        
        echo "⚠️ Skipping actual import (test mode)<br>";
    } else {
        echo "❌ Importer class not found<br>";
    }
} catch (Exception $e) {
    echo "❌ Importer error: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Test 6: AJAX Handler Check
echo "<h2>6. AJAX Handlers</h2>";
$ajax_actions = [
    'wpgsip_import_preview',
    'wpgsip_import_execute',
    'wpgsip_test_connection',
    'wpgsip_get_logs'
];

foreach ($ajax_actions as $action) {
    if (has_action("wp_ajax_$action")) {
        echo "✅ {$action} is registered<br>";
    } else {
        echo "❌ {$action} NOT registered<br>";
    }
}

echo "<h2>Debug Complete</h2>";
echo "<p>If you see errors above, copy them and share for fixing.</p>";