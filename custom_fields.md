Custom Fields (GET)
-------------------------------------
Get your account's custom fields.

#### GET account custom fields
```
GET /custom_fields
```
The response to this GET will be an array of your account custom fields.
```
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

#### Visibility
Account admins can restrict individual custom fields (and the categories that
group them) to specific teams or to the record owner. The response above
reflects the visibility rules configured for the authenticated user:

- **Admin users** see every active custom field.
- **Non-admin users** see only fields visible to them. A field hidden from the
  authenticated user by a "Specific Teams" rule (and the user is not a member
  of any listed team) is omitted entirely from the response.
- **"Record Owner Only"** fields are returned in this list, but their values
  on `GET /contacts?to_get=custom_fields` are only included for contacts the
  authenticated user owns.

If a field appears for an admin and not for a non-admin user on the same
account, the field is restricted. Have an admin review the field's
visibility settings in the account's Customize area.
