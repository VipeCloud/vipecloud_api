Sign Up Forms (GET)
-------------------------------------

### GET list of sign up forms

```
GET /sign_up_forms
```

Returns an array of the sign up forms that a user has in VipeCloud.

#### Response

```json
[
  {
    "contact_list_id": 123,
    "contact_list_name": "New Customers",
    "create_date": "2024-09-03 08:30:39",
    "all_count": 16,
    "source": "manual",
    "is_synced_list": 0
  },
  {
    ...
  }
]
```

**Note:** The `all_count` excludes bounces, unsubscribes, and verified undeliverable contacts.

---

### GET a specific sign up form

```
GET /sign_up_forms/:id
```

Returns a single sign up form's information along with the `contacts_master_ids` of contacts who have submitted the form.

#### Response

```json
{
  "contact_list_id": 123,
  "contact_list_name": "New Customers",
  "create_date": "2024-09-03 08:30:39",
  "all_count": 16,
  "source": "manual",
  "is_synced_list": 0,
  "contacts_master_ids": [
    123,
    456,
    789
  ]
}
```

**Note:** While the response uses `contact_list_id`, sign up forms and contact lists share the same underlying data structure in our database.

---

### GET sign up form submission data

```
GET /sign_up_forms/:id/data?contacts_master_id=:contacts_master_id
```
OR
```
GET /sign_up_forms/:id/data/:contacts_master_id
```

Returns the actual data submitted by a specific contact through a sign up form. This includes all form field values that were submitted.

#### Query Parameters

Parameter | Type | Required | Description
--- | --- | --- | ---
contacts_master_id | integer | no | The contact's master ID to retrieve submissions for
submitted_after | string | no | Only return submissions after this datetime (UTC). Accepts `YYYY-MM-DD` or `YYYY-MM-DD HH:MM:SS` format.

#### Sample Request - Get Contact's Submissions

```
GET /sign_up_forms/123/data?contacts_master_id=456
```

#### Sample Request - Filter by Date

```
GET /sign_up_forms/123/data?contacts_master_id=456&submitted_after=2024-01-01
```

#### Sample Request - Filter by Datetime

```
GET /sign_up_forms/123/data?contacts_master_id=456&submitted_after=2024-09-01 00:00:00
```

#### Success Response (200)

```json
{
  "status": "success",
  "sign_up_form_id": 123,
  "sign_up_form_name": "New Customers",
  "data": [
    {
      "response_id": 789,
      "contacts_master_id": 456,
      "submitted_at": "2024-09-03 08:30:39",
      "fields": [
        {
          "name": "first_name",
          "value": "John",
          "type": "Text"
        },
        {
          "name": "last_name",
          "value": "Doe",
          "type": "Text"
        },
        {
          "name": "email",
          "value": "john.doe@example.com",
          "type": "Email"
        },
        {
          "name": "liquid_assets",
          "value": "$100,000 - $250,000",
          "type": "Dropdown",
          "custom_field_id": 528
        }
      ]
    },
    {
      "response_id": 790,
      "contacts_master_id": 456,
      "submitted_at": "2024-10-15 14:22:11",
      "fields": [
        ...
      ]
    }
  ]
}
```

#### Response Fields

Field | Type | Description
--- | --- | ---
status | string | "success" or "error"
sign_up_form_id | integer | The ID of the sign up form
sign_up_form_name | string | The name of the sign up form
data | array | Array of form submission responses

#### Response Data Fields

Field | Type | Description
--- | --- | ---
response_id | integer | Unique identifier for this form submission
contacts_master_id | integer | The contact's master ID
submitted_at | string | Timestamp when the form was submitted in UTC (YYYY-MM-DD HH:MM:SS)
fields | array | Array of field values submitted

#### Field Object

Field | Type | Description
--- | --- | ---
name | string | The field name/identifier
value | string | The value submitted for this field
type | string | The field type (Text, Email, Dropdown, Phone, etc.)
custom_field_id | integer | (Optional) The custom field ID if this field maps to a custom field

#### Error Responses

Status | Message | Description
--- | --- | ---
422 | "Sign up form not found." | The specified sign up form ID does not exist or you don't have access to it
422 | "This is not a sign up form." | The specified ID belongs to a contact list, not a sign up form
422 | "Invalid submitted_after format. Use YYYY-MM-DD or YYYY-MM-DD HH:MM:SS (UTC)." | The `submitted_after` parameter is not a valid date format

#### Notes

- All timestamps are in **UTC timezone**
- The `submitted_after` parameter filters for submissions strictly after the specified datetime (not inclusive)
- A contact can submit a form multiple times, so multiple response records may exist for the same `contacts_master_id`
- Responses are returned in descending order by submission date (most recent first)
- The `custom_field_id` is only included for fields that map to custom fields in your account
- Custom fields can be retrieved via the `/custom_fields` endpoint to get field labels and other metadata

---

### Use Cases

#### Get Form Submissions for a Specific Contact

Retrieve all form submission data for a specific contact:

```bash
curl "https://v.vipecloud.com/api/v3.1/sign_up_forms/123/data?contacts_master_id=456" \
  -H "Authorization: Basic {base64(email:apikey)}"
```

#### Get Recent Submissions Only

Filter to only show submissions after a specific date (useful for syncing or polling):

```bash
curl "https://v.vipecloud.com/api/v3.1/sign_up_forms/123/data?contacts_master_id=456&submitted_after=2024-01-01" \
  -H "Authorization: Basic {base64(email:apikey)}"
```

#### Get Submissions After a Specific Timestamp

For more precise filtering, include the time (in UTC):

```bash
curl "https://v.vipecloud.com/api/v3.1/sign_up_forms/123/data?contacts_master_id=456&submitted_after=2024-09-15%2014:30:00" \
  -H "Authorization: Basic {base64(email:apikey)}"
```

**Note:** URL encode spaces as `%20` in the datetime value.

#### Workflow: Find Contact's Form Data

1. Get the list of sign up forms:
   ```
   GET /sign_up_forms
   ```

2. Get contacts who submitted a specific form:
   ```
   GET /sign_up_forms/123
   ```

3. Get the submission data for a specific contact:
   ```
   GET /sign_up_forms/123/data?contacts_master_id=456
   ```

#### Matching Custom Fields

When the response includes `custom_field_id`, you can use the Custom Fields endpoint to get additional metadata:

```bash
curl "https://v.vipecloud.com/api/v3.1/custom_fields" \
  -H "Authorization: Basic {base64(email:apikey)}"
```

This returns custom field definitions including labels, types, and dropdown options.

---

### GET /sign_up_forms/:id/fields

Returns the full schema of a sign-up form: form-level metadata, submittability state, and per-field constraints. Use this endpoint to discover what to send in `POST /sign_up_forms/:id/data`.

```
GET /sign_up_forms/:id/fields
```

**Sample response** (200 OK):

```json
{
  "status": "success",
  "contact_list_id": 123,
  "form": {
    "title": "Newsletter Signup",
    "description": "Sign up for our weekly digest",
    "action_text": "Subscribe",
    "captcha_required": true,
    "scheduler_attached": false,
    "payment_required": false,
    "active": true,
    "form_disabled": false,
    "submission_limit_reached": false,
    "form_submittable": true
  },
  "fields": [
    {
      "slug": "email",
      "name": "Email",
      "type": "email",
      "required": true,
      "standard": true,
      "custom_field": false,
      "validation": "email",
      "max_length": 255,
      "min_length": null,
      "default_value": null,
      "options": null
    },
    {
      "slug": "favorite_color",
      "name": "Favorite Color",
      "type": "Dropdown",
      "required": false,
      "standard": false,
      "custom_field": true,
      "validation": null,
      "max_length": null,
      "min_length": null,
      "default_value": null,
      "options": ["red", "green", "blue"]
    }
  ]
}
```

**Form fields**

| Field | Description |
|---|---|
| `form.title` | Form title shown to end users |
| `form.description` | HTML description shown above the form |
| `form.action_text` | Submit button label |
| `form.captcha_required` | Whether the rendered web form shows a captcha. The API submission endpoint **bypasses** captcha for authenticated requests, so this is informational only. |
| `form.scheduler_attached` | If true, the form has a scheduler attached. `POST /sign_up_forms/:id/data` will reject. |
| `form.payment_required` | If true, the form takes payment. `POST /sign_up_forms/:id/data` will reject. |
| `form.active` | Whether the contact list has `active_sign_up = 1`. |
| `form.form_disabled` | Whether the form has been disabled. |
| `form.submission_limit_reached` | Whether the form's `sign_up_limit` has been reached. |
| `form.form_submittable` | Aggregate boolean: true iff active && !form_disabled && !scheduler_attached && !payment_required && !submission_limit_reached. Use this to short-circuit a POST. |

**Per-field keys**

| Field | Description |
|---|---|
| `slug` | Unique field key. Use this as the key in `POST /data` `fields` body. |
| `name` | Human-readable label. |
| `type` | Field type (text, email, phone, url, numeric, Dropdown, Picklist, etc.). |
| `required` | Whether the field is required. |
| `validation` | Format validator (email, phone, url, numeric) or null. |
| `max_length`/`min_length` | Length bounds, or null if unlimited. |
| `default_value` | Default value, or null. |
| `options` | Array of allowed values for Dropdown/Picklist; null otherwise. |
| `standard` | True for built-in fields (email, first_name, etc.). |
| `custom_field` | Custom field ID, or false. |

**Errors**

| HTTP | When |
|---|---|
| 401 | Authentication failed |
| 404 | Form not found or not owned by your account |

**Example**

```bash
curl https://v.vipecloud.com/api/v3.1/sign_up_forms/123/fields \
  -H "Authorization: Basic $(echo -n 'you@example.com:YOUR_API_KEY' | base64)"
```

---

### POST /sign_up_forms/:id/data

> **Authorization required:** This endpoint requires support team approval. Contact VipeCloud support to request access (`api_sign_up_form_submissions_enabled` account flag) before this endpoint will accept submissions.
>
> **Captcha:** Bypassed automatically for authenticated API submissions.
>
> **Scope limitation:** Sign-up forms with payment processors or attached schedulers cannot be submitted via this endpoint.

Submit a new entry to a sign-up form. Creates or updates the contact in the associated contact list and triggers the form's automations (unless `skip_automations` is true).

```
POST /sign_up_forms/:id/data
```

**Body**

```json
{
  "fields": {
    "email": "jane@example.com",
    "first_name": "Jane",
    "favorite_color": "blue",
    "custom_field_456": "anything"
  },
  "skip_automations": false
}
```

| Field | Required | Description |
|---|---|---|
| `fields` | yes | Object mapping field slugs (from `GET /fields`) to values |
| `skip_automations` | no | If true, suppresses notifications and automations triggered by this form. Default false. |

**Sample success response** (200 OK):

```json
{
  "status": "success",
  "contacts_master_id": 9876,
  "created": true
}
```

`created: true` means a new contact was created. `created: false` means an existing contact (matched by email or another unique field) was updated.

**Errors**

| HTTP | `message` | Notes |
|---|---|---|
| 401 | Authentication failed | |
| 403 | "Sign-up form submission via the API is not enabled for your account. Please contact VipeCloud support to request access." | Account flag missing or 0 |
| 404 | "Sign-up form not found." | Form does not exist or is not owned by your account |
| 422 | "This sign-up form is not currently accepting submissions." | `active_sign_up = 0` |
| 422 | "This sign-up form is disabled." | `form_disabled = 1` |
| 422 | "This sign-up form has a scheduler attached and cannot be submitted via the V3.1 API." | Scheduler attached |
| 422 | "This sign-up form requires payment and cannot be submitted via the API." | Payment processor configured |
| 422 | "This sign-up form has reached its submission limit." | `sign_up_limit` reached |
| 422 | "Validation failed." | Per-field validation; see `errors[]` |

**Multi-error validation response** — all per-field errors are returned at once:

```json
{
  "status": "error",
  "message": "Validation failed.",
  "errors": [
    {"slug": "email", "message": "Email is required."},
    {"slug": "phone", "message": "Phone must be a valid US phone number."},
    {"slug": "favorite_color", "message": "Value must be one of: red, green, blue."}
  ]
}
```

**Examples**

Basic submission:

```bash
curl https://v.vipecloud.com/api/v3.1/sign_up_forms/123/data \
  -H "Authorization: Basic $(echo -n 'you@example.com:KEY' | base64)" \
  -H "Content-Type: application/json" \
  -X POST -d '{
    "fields": {"email": "jane@example.com", "first_name": "Jane"}
  }'
```

Submission without automations:

```bash
curl https://v.vipecloud.com/api/v3.1/sign_up_forms/123/data \
  -H "Authorization: Basic $(echo -n 'you@example.com:KEY' | base64)" \
  -H "Content-Type: application/json" \
  -X POST -d '{
    "fields": {"email": "jane@example.com"},
    "skip_automations": true
  }'
```

Handling a multi-error 422:

```bash
curl -i https://v.vipecloud.com/api/v3.1/sign_up_forms/123/data \
  -H "Authorization: Basic $(echo -n 'you@example.com:KEY' | base64)" \
  -H "Content-Type: application/json" \
  -X POST -d '{ "fields": {} }'
# HTTP/1.1 422 Unprocessable Entity
# {"status":"error","message":"Validation failed.","errors":[{"slug":"email","message":"Email is required."}]}
```
