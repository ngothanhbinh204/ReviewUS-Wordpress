<?php
/**
 * Quick Class Check
 * Access: http://reviewus.local/wp-content/plugins/wp-google-sheets-import-pro/class-check.php
 */

// Load WordPress
require_once('../../../wp-load.php');

echo "<h1>Class Loading Check</h1>";

$classes_to_check = array(
    'WPGSIP_Content_Processor' => 'includes/class-wpgsip-content-processor.php',
    'WPGSIP_Importer' => 'includes/class-wpgsip-importer.php',
    'WPGSIP_Settings' => 'includes/class-wpgsip-settings.php',
    'WPGSIP_Google_Sheets' => 'includes/class-wpgsip-google-sheets.php',
    'WPGSIP_Core' => 'includes/class-wpgsip-core.php',
);

echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Class Name</th><th>Status</th><th>File Path</th></tr>";

foreach ($classes_to_check as $class => $file) {
    $file_path = plugin_dir_path(__FILE__) . $file;
    $file_exists = file_exists($file_path);
    $class_exists = class_exists($class);
    
    $status = $class_exists ? '✅ Loaded' : '❌ Not Found';
    $file_status = $file_exists ? '✅ Exists' : '❌ Missing';
    
    echo "<tr>";
    echo "<td><strong>{$class}</strong></td>";
    echo "<td>{$status}</td>";
    echo "<td>{$file_status}</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h2>Test Content Processor</h2>";

if (class_exists('WPGSIP_Content_Processor')) {
    try {
        $processor = new WPGSIP_Content_Processor();
        echo "<p>✅ Successfully created WPGSIP_Content_Processor instance</p>";
        
        // Quick test
        $test = $processor->process("H1: Test\n\n**Bold** text");
        echo "<p>✅ Process method works</p>";
        echo "<pre>" . htmlspecialchars($test) . "</pre>";
        
    } catch (Exception $e) {
        echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>❌ WPGSIP_Content_Processor class not available</p>";
}

echo "<h2>Plugin Info</h2>";
echo "<p>Plugin Dir: " . plugin_dir_path(__FILE__) . "</p>";
echo "<p>WordPress Version: " . get_bloginfo('version') . "</p>";
echo "<p>PHP Version: " . phpversion() . "</p>";