<?php

namespace VipeCloud\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Base Test Case for VipeCloud API Tests
 *
 * Provides common functionality for all API tests including:
 * - HTTP request methods
 * - Resource tracking and cleanup
 * - Assertion helpers
 * - Test data generation
 */
abstract class BaseTestCase extends TestCase
{
    protected array $config;
    protected array $createdResources = [];
    protected string $baseUrl;
    protected string $authHeader;
    protected int $requestCount = 0;

    protected function setUp(): void
    {
        parent::setUp();

        $this->config = API_CONFIG;
        $this->baseUrl = $this->config['api_base_url'];

        // Create auth header
        $auth = base64_encode($this->config['user_email'] . ':' . $this->config['api_key']);
        $this->authHeader = 'Basic ' . $auth;

        $this->createdResources = [];
        $this->requestCount = 0;
    }

    protected function tearDown(): void
    {
        // Cleanup created resources in reverse order
        if ($this->config['cleanup_on_success'] ||
            ($this->config['cleanup_on_failure'] && $this->hasFailed())) {
            $this->cleanupResources();
        }

        parent::tearDown();
    }

    /**
     * Make an HTTP request to the API
     */
    protected function makeRequest(
        string $method,
        string $endpoint,
        ?array $data = null,
        array $extraHeaders = []
    ): array {
        // Respect rate limits
        if ($this->requestCount > 0 && $this->config['delay_between_requests'] > 0) {
            usleep($this->config['delay_between_requests']);
        }
        $this->requestCount++;

        $url = $this->baseUrl . $endpoint;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        $headers = array_merge([
            'Authorization: ' . $this->authHeader,
            'Accept: application/json',
            'Content-Type: application/json',
        ], $extraHeaders);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        if ($data !== null && in_array($method, ['POST', 'PUT', 'PATCH'])) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new \RuntimeException("cURL Error: $error");
        }

        $decoded = json_decode($response, true);

        return [
            'status_code' => $httpCode,
            'body' => $decoded ?? $response,
            'raw' => $response,
        ];
    }

    /**
     * Track a created resource for cleanup
     */
    protected function trackResource(string $type, $id, array $metadata = []): void
    {
        if (!$this->config['track_resources']) {
            return;
        }

        $this->createdResources[$type][] = [
            'id' => $id,
            'metadata' => $metadata,
            'created_at' => microtime(true),
        ];

        // Also track globally
        $GLOBALS['test_resources'][$type][] = $id;
    }

    /**
     * Clean up all tracked resources
     */
    protected function cleanupResources(): void
    {
        // Cleanup in reverse order of creation
        $resourceTypes = array_reverse(array_keys($this->createdResources));

        foreach ($resourceTypes as $type) {
            $resources = array_reverse($this->createdResources[$type]);

            foreach ($resources as $resource) {
                try {
                    $this->deleteResource($type, $resource['id']);

                    // Remove from global tracker
                    $key = array_search($resource['id'], $GLOBALS['test_resources'][$type]);
                    if ($key !== false) {
                        unset($GLOBALS['test_resources'][$type][$key]);
                    }
                } catch (\Exception $e) {
                    echo sprintf(
                        "Warning: Failed to cleanup %s #%s: %s\n",
                        $type,
                        $resource['id'],
                        $e->getMessage()
                    );
                }
            }
        }
    }

    /**
     * Delete a resource based on type
     */
    protected function deleteResource(string $type, $id): void
    {
        $endpoints = [
            'contacts' => '/contacts',
            'contact_lists' => '/contact_lists',
            'email_templates' => '/email_templates',
            'text_templates' => '/text_templates',
            'series_templates' => '/series_templates',
            'series_template_steps' => '/series_template_steps',
            'automations' => '/automations',
            'tags' => '/tags',
            'files' => '/files',
            'products' => '/products',
            'stories' => '/stories',
            'account_groups' => '/account_groups',
            'account_group_posts' => '/account_group_post',
            'social_posts' => '/social_post',
        ];

        if (!isset($endpoints[$type])) {
            return; // Resource type doesn't support deletion
        }

        $this->makeRequest('DELETE', $endpoints[$type] . '/' . $id);
    }

    /**
     * Generate test data from seed with timestamp replacement
     */
    protected function getTestData(string $type, array $overrides = []): array
    {
        if (!isset($this->config['seed_data'][$type])) {
            return $overrides;
        }

        $data = $this->config['seed_data'][$type];

        // Replace {timestamp} placeholder with unique identifier
        $timestamp = microtime(true);
        $uniqueId = str_replace('.', '', $timestamp);

        array_walk_recursive($data, function (&$value) use ($timestamp, $uniqueId) {
            if (is_string($value)) {
                $value = str_replace('{timestamp}', $uniqueId, $value);
            }
        });

        return array_merge($data, $overrides);
    }

    /**
     * Assert successful response
     */
    protected function assertSuccessResponse(array $response, int $expectedCode = 200): void
    {
        $this->assertEquals(
            $expectedCode,
            $response['status_code'],
            sprintf(
                "Expected status %d but got %d. Response: %s",
                $expectedCode,
                $response['status_code'],
                json_encode($response['body'])
            )
        );
    }

    /**
     * Assert error response
     */
    protected function assertErrorResponse(array $response, int $expectedCode = 422): void
    {
        $this->assertEquals($expectedCode, $response['status_code']);

        if (is_array($response['body'])) {
            $this->assertArrayHasKey('status', $response['body']);
            $this->assertEquals('error', $response['body']['status']);
        }
    }

    /**
     * Check if test has failed
     */
    protected function hasFailed(): bool
    {
        return $this->getStatus() === \PHPUnit\Runner\BaseTestRunner::STATUS_FAILURE
            || $this->getStatus() === \PHPUnit\Runner\BaseTestRunner::STATUS_ERROR;
    }

    /**
     * Sleep to respect rate limits
     */
    protected function respectRateLimit(int $multiplier = 1): void
    {
        if ($this->config['delay_between_requests'] > 0) {
            usleep($this->config['delay_between_requests'] * $multiplier);
        }
    }
}
