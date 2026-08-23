# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Repository Overview

**This repo is for the v3.1 docs.** It contains the public-facing markdown documentation for VipeCloud API v3.1 — no implementation code. Customers integrate against the documented contract here.

This repository is public. Treat every file in it — including this one — as customer-readable.

## Repository Structure

- Each markdown file documents a specific API resource (e.g., `contacts.md`, `automations.md`, `sign_up_forms.md`)
- `README.md` — Entry point with overview, authentication, and version information
- `SUMMARY.md` — Index of all available endpoints

## API Documentation Structure

All endpoint documentation follows this pattern:
- HTTP method and endpoint path
- Request parameters (often in table format with: Attribute, type, required, description)
- Sample request body (JSON)
- Sample response body (JSON)
- Common response codes: 200 (success), 422 (validation and many not-found cases under v3.1's contract), 500 (server error)

## Key API Concepts

### Authentication
- Basic Authentication with base64-encoded `user_email:api_key`
- API keys managed in Setup section of the VipeCloud account
- Base URL: `https://v.vipecloud.com/api/v3.1`
- Rate limit: 10 calls per 2 seconds per user
- v3.1 accepts `agent_api`-type keys or basic `api`-type keys

### Conventions
- v3.1 uses `POST /endpoint(/:id)` for both create and update.
- v3.1 returns `200 OK` for successful sign-up form submission and successful POSTs in general; this is part of the locked contract — see "Stability tier" below.

## Stability tier

**The v3.1 API contract is locked for stability.** Existing customers depend on specific response shapes, status codes, and message text. Any change to v3.1 documentation that alters a customer-visible contract requires explicit user approval — even if the change matches the implementation more closely than the current docs do.

This applies to:
- **Response message text** — exact strings in `message` fields. Don't reword "Sign-up form not found." to "This sign up form was not found." even if the latter sounds better.
- **Status codes** — v3.1 uses `200 OK` for successful POSTs and historically returns `422` for some cases. Don't normalize these silently.
- **Field shapes** — array vs object, snake_case vs the older mixed-case keys, presence/absence of optional fields.

What's safe to update without approval:
- New endpoints or new fields added to existing endpoints (purely additive).
- Clarifying prose, examples, and parameter descriptions that don't change behavior.
- Documenting behavior that already exists in the implementation but was missing from the docs (e.g., the recent `required` default-to-true normalization — the docs added a clarification of behavior already in the implementation).

When in doubt, ask.

## Webhooks
- Webhook events: email_delivered, email_open, email_click, email_bounce, email_unsubscribe
- HMAC-SHA256 signature verification with shared secret
- Requires 200 HTTP response code for acknowledgment

## Test Suite

The integration test suite lives in the implementation repo (sibling of this docs repo). v3.1-specific tests include:

- `SignUpFormFieldsApiV31Test.php` — `GET /sign_up_forms/:id/fields` schema response
- `SignUpFormSubmissionApiV31Test.php` — `POST /sign_up_forms/:id/data` happy/error paths
- `SignUpFormDiscoveryApiV31Test.php` — discovery-style end-to-end against an existing form

These tests target v3.1 by way of `SignUpFormFixtures::makeV31Request()`, which sets the v3.1 base URL and auth header for the duration of each call. See the implementation repo's `tests/README.md` for running.

## Common Tasks

When updating API documentation:
1. Maintain consistent formatting with existing endpoint files.
2. Use markdown tables for parameter documentation.
3. Include sample request/response JSON with proper formatting.
4. Update `SUMMARY.md` if adding new endpoints.
5. **Source-of-truth check**: cross-reference the implementation to confirm the docs match real behavior — v3.1 has historically drifted from its docs because the stability rule discourages silent updates.
6. If adding new endpoints, consider adding corresponding tests in the implementation repo's test suite.

## Never commit real customer or employee PII

**This repository is PUBLIC** — anything committed here is world-readable, so this rule is absolute.

No real names, email addresses, phone numbers or postal addresses of
customers or employees in ANY tracked file — code, comments, examples, sample
requests/responses, SQL, docs, plan files, QA sheets, *or commit messages*.
Internal numeric ids (`account_master_id`, `contact_list_id`, `user_id`) are fine
and usually necessary; it is the human-identifying values that must never land.

- Use synthetic identities: seeded test accounts, the designated smoke
  identities, or obvious placeholders (`jane.doe@example.com`, `[REDACTED]`).
- **A bug report is the highest-risk moment.** Customer names arrive in the
  prompt from a ticket and flow straight into the artifact describing the fix.
  Translate them to ids at the START of the investigation and work in ids after.
- **Verification never needs names.** Assert a per-person outcome in aggregate
  or by id instead of listing people — it is PII-free *and* a stronger check,
  because it covers every row rather than the handful someone reported.
- **It is not cheaply reversible.** `git push --force` makes the old commits
  unreachable but does NOT erase them: the host keeps serving them by SHA until
  support runs a GC on request. A rewrite also forces every collaborator to
  `git reset --hard`. Prevention is the only cheap control.

(Source: 2026-08-23 — customer names from a bug report were committed to a vc3
QA sheet and a SQL migration, and removing them required rewriting `main`.)

## Documentation Standards

- All requests and responses use JSON encoding.
- All API calls must use HTTPS.
- Parameter tables include: Attribute name, type, required (yes/no/conditional), description.
- Code examples use `curl` for portability (the older v3.1 docs sometimes use PHP — both are acceptable, but new examples should use `curl`).
- Conditional requirements should explain trigger conditions in the description column.

## Git Workflow

- Origin: `https://github.com/VipeCloud/vipecloud_api.git`
- Default branch: typically `master` (verify with `git branch` before pushing).
