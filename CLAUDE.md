# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository Overview

This is an API documentation repository for VipeCloud API v4.0. It contains markdown files documenting REST API endpoints but no implementation code. The repository serves as the public-facing API reference for developers integrating with VipeCloud.

## Repository Structure

- Each markdown file documents a specific API resource (e.g., `contacts.md`, `automations.md`, `users.md`)
- `README.md` - Entry point with overview, authentication, and version information
- `SUMMARY.md` - Index of all available endpoints
- `webhooks_v1_0.md` - Webhooks API documentation

## API Documentation Structure

All endpoint documentation follows this pattern:
- HTTP method and endpoint path
- Request parameters (often in table format with: Attribute, type, required, description)
- Sample request body (JSON)
- Sample response body (JSON)
- Common response codes: 200 (success), 422 (incorrect post), 500 (server error)

## Key API Concepts

### Authentication
- Uses Basic Authentication with base64 encoded `user_email:api_key`
- Bearer token authentication available for `/user_auth` endpoint
- API keys managed in Setup section of VipeCloud account
- Base URL: `https://v.vipecloud.com/api/v4.0`
- Rate limit: 10 calls per 2 seconds per user

### Versioning
- Current version: v4.0
- Previous endpoints like `/autoresponders` and `/social_account_groups` maintained for backwards compatibility
- v4.0 follows standard REST conventions with proper HTTP methods (GET, POST, PUT/PATCH, DELETE)

### v4.0 REST Conventions
- **GET** - Retrieve resources (`GET /endpoint` or `GET /endpoint/:id`)
- **POST** - Create new resources (`POST /endpoint`)
- **PUT/PATCH** - Update existing resources (`PUT /endpoint/:id` or `PATCH /endpoint/:id`)
- **DELETE** - Delete resources (`DELETE /endpoint/:id`)

### Breaking Changes from v3.1
- v3.1 used `POST /endpoint(/:id)` for both create and update
- v4.0 uses separate methods: `POST /endpoint` for create, `PUT/PATCH /endpoint/:id` for update
- Users endpoint (`/users`) no longer supports POST/PUT operations - GET only
- DELETE operations now more widely supported across endpoints

### Webhooks
- Webhook events: email_delivered, email_open, email_click, email_bounce, email_unsubscribe
- HMAC-SHA256 signature verification with shared secret
- Requires 200 HTTP response code for acknowledgment

## Git Workflow

- Main branch: `master`
- Current branch: `version_4`
- Origin: `https://github.com/VipeCloud/vipecloud_api.git`

## Common Tasks

When updating API documentation:
1. Maintain consistent formatting with existing endpoint files
2. Use markdown tables for parameter documentation
3. Include sample request/response JSON with proper formatting
4. Update `SUMMARY.md` if adding new endpoints
5. Ensure backwards compatibility notes are included when deprecating endpoints

## Documentation Standards

- All requests and responses use JSON encoding
- All API calls must use HTTPS
- Parameter tables include: Attribute name, type, required (yes/no/conditional), description
- Code examples use PHP by default (legacy codebase standard)
- Conditional requirements should explain trigger conditions in description column
