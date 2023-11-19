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
