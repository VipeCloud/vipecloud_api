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
