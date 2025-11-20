<?php

/**
 * VipeCloud API Test Configuration
 *
 * Copy this file to config.php and fill in your credentials.
 * DO NOT commit config.php to version control.
 */

return [
    // API Configuration
    'api_base_url' => 'https://v.vipecloud.com/api/v4.0',

    // Authentication - use a dedicated test account
    'user_email' => 'your-test-account@example.com',
    'api_key' => 'your-api-key-here',

    // Test Data Prefixes (for easy identification and cleanup)
    'test_prefix' => '[API_TEST]',
    'test_timestamp' => date('Y-m-d_H-i-s'),

    // Test Configuration
    'cleanup_on_success' => true,  // Clean up resources after successful tests
    'cleanup_on_failure' => false, // Keep resources after failed tests for debugging
    'delay_between_requests' => 100000, // microseconds (0.1s) to respect rate limits

    // Resource Tracking
    'track_resources' => true, // Track created resources for cleanup

    // Test Data Seeds (deterministic data for replicable tests)
    'seed_data' => [
        'contact' => [
            'first_name' => 'Test',
            'last_name' => 'Contact',
            'email' => 'test.contact.{timestamp}@vipetest.local',
            'mobile_phone' => '5551234567',
            'company_name' => 'VipeTest Corp',
        ],
        'email_template' => [
            'title' => 'Test Email Template {timestamp}',
            'subject' => 'Test Subject',
            'copy' => '<p>This is a test email template created at {timestamp}</p>',
        ],
        'contact_list' => [
            'contact_list_name' => 'Test Contact List {timestamp}',
        ],
        'tag' => [
            'tag_name' => 'Test Tag {timestamp}',
        ],
        'file' => [
            'file_name' => 'Test File {timestamp}',
            'file_url' => 'https://via.placeholder.com/150',
        ],
    ],
];
