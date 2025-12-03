Sign Up Forms (GET)
-------------------------------------

#### GET list of existing sign up forms a user has in VipeCloud
```
GET /sign_up_forms(/:id)
```
Returns an individual record or array of the sign up forms that a user has in VipeCloud.
```
{
  [
    {
      "contact_list_id" : "123",
      "contact_list_name" : "New Customers",
      "create_date" : "2014-09-03 08:30:39",
      "all_count": 16 //note that bounces, unsubscribes, and verified undeliverable contacts are automatically removed
      "source" : "manual"
    },
    {
    ...
    }
  ]
}
```

If an ID is given, then the contacts_master_ids of contacts within that sign up form will be returned. Please note that while the id says contact_list_id, the returned items are sign_up_forms. They are the same objects within our database.

```
{
    "contact_list_id" : "123",
    "contact_list_name" : "New Customers",
    "create_date" : "2014-09-03 08:30:39",
    "all_count": 16 //note that bounces, unsubscribes, and verified undeliverable contacts are automatically removed
    "source" : "manual"
    "contacts_master_ids": [
        123,
        456,
        789
    ]
},
```

#### GET Sign Up Form Fields
```
GET /sign_up_forms/:id/fields
```
Returns the configured form fields for a sign up form. These are the fields that contacts will fill out when signing up through the form.

Sample response:
```
{
    "status": "success",
    "contact_list_id": 123,
    "fields": [
        {
            "slug": "first_name",
            "label": "First Name",
            "type": "text",
            "required": true,
            "order": 1
        },
        {
            "slug": "last_name",
            "label": "Last Name",
            "type": "text",
            "required": false,
            "order": 2
        },
        {
            "slug": "email",
            "label": "Email",
            "type": "email",
            "required": true,
            "order": 3
        },
        {
            "slug": "custom_field_123",
            "label": "Company Size",
            "type": "select",
            "required": false,
            "order": 4,
            "options": ["1-10", "11-50", "51-200", "200+"]
        }
    ]
}
```

Example error response (form not found):
```
{
    "status": "error",
    "message": "Sign up form not found."
}
```

Example error response (no active sign up form):
```
{
    "status": "error",
    "message": "This contact list does not have an active sign up form."
}
```