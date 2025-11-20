<?php

/**
 * PHPUnit Bootstrap File for VipeCloud API Tests
 */

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Define test directory
define('TEST_DIR', __DIR__);

// Load configuration
if (!file_exists(TEST_DIR . '/config.php')) {
    throw new RuntimeException(
        'Configuration file not found. Copy config.example.php to config.php and configure your credentials.'
    );
}

$config = require TEST_DIR . '/config.php';
define('API_CONFIG', $config);

// Autoload test classes
spl_autoload_register(function ($class) {
    $prefix = 'VipeCloud\\Tests\\';
    $base_dir = TEST_DIR . '/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Initialize resource tracker
$GLOBALS['test_resources'] = [
    'contacts' => [],
    'contact_lists' => [],
    'email_templates' => [],
    'text_templates' => [],
    'series_templates' => [],
    'series_template_steps' => [],
    'automations' => [],
    'tags' => [],
    'files' => [],
    'products' => [],
    'stories' => [],
    'account_groups' => [],
    'account_group_posts' => [],
    'social_posts' => [],
];

// Register shutdown function for final cleanup
register_shutdown_function(function () {
    if (API_CONFIG['track_resources']) {
        echo "\n\n=== Final Cleanup Report ===\n";
        foreach ($GLOBALS['test_resources'] as $type => $resources) {
            if (!empty($resources)) {
                echo sprintf("⚠️  %d %s resource(s) may need manual cleanup\n", count($resources), $type);
            }
        }
    }
});

echo "VipeCloud API v4.0 Test Suite Bootstrap Complete\n";
echo "Base URL: " . API_CONFIG['api_base_url'] . "\n";
echo "Test Prefix: " . API_CONFIG['test_prefix'] . "\n\n";
