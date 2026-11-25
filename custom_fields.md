Custom Fields (GET)
-------------------------------------
Get your account's custom fields.

#### GET account custom fields
```http
GET /custom_fields
```
The response to this GET will be an array of your account custom fields.
```json
{ 
  [
    {
      "id" : 1, //the custom field id
      "item_type" : "contact",
      "field_type" : "Text", //can be any of the input field types VipeCloud supports
      "field_name" : "My Custom Field",
      "options" : "", //will only have values for dropdowns and picklists. Will be an array of your options 
    }
  ]
}
```

#### Lookup fields
A Lookup field (`field_type` "Lookup") points at one contact, account,
opportunity or user of your account, chosen when the field is set up in
VipeCloud. Its value is that target's id: a contact id, an account id, an
opportunity id, or the user id of a user in your account.

`GET /contacts` returns the stored id. A value written to a Lookup field
(contact create or update, or an automation's "custom_field" action) is stored
only when it is an id the authenticated user may set:

- **contact, account, opportunity:** a live record of that type that the
  authenticated user can access.
- **user:** an active user of your account. Any user of the account may set
  any active user, the same as choosing a record owner. A deleted or paused
  user, or a user of another account, is not stored.

When a user is deleted and their records are transferred to a teammate, user
Lookup values that named them are moved to that teammate. Values still naming
a deleted user when their data is purged, or a user who moves to another
account, are cleared.

#### Errors

HTTP | `message` | When
--- | --- | ---
500 | "Unable to read custom field options. Please retry." | The Dropdown/Picklist/Link option read failed server-side. This is a dependency-read failure, not a bad request — retry.
