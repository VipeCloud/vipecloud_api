Texts (POST)
-------------
Send texts via a POST. 

#### POST Text(s)

```
POST /texts
``` 

Attribute | type | required | description
--- | --- | --- | ---
contacts_master_id | integer | yes or contact_list_id | The id of the contact you are texting
contact_list_id | integer | yes or contacts_master_id | Required param if sending to contact list. Include contacts_master_id OR contact_list_id.
message | string | yes or email_template_id | Required if no text_template_id.
text_template_id | integer | yes or message | Send text with text template. Replaces requirement for message.
filters | array | no | Filter contacts within a contact list at the time of send. If you include the filters parameter our system will create a new, system-generated list based on which contacts meet your filters within the contact_list_id that is also submitted. Each group of filters within the filters array must include a field_type (standard or custom), id (if standard a slug, if custom the custom_field_id), an operator (accepted values include equals, less_than, greater_than, less_than_or_equal, or greater_than_or_equal), and a value. Contacts that meet all filters within any of the filter groups will be added to the system-generated list.
test_filters | boolean | no | Will test your filters and NOT send the text. Will return the number of contacts in your original list and after your filters have been applied.
filtered_list_name | string | no | Optional name of the filterd contact list created
schedule_data | array | no | The schedule_data array requires four keys: scheduled_date (string formmated as YYYY-MM-DD), hour (string, no leading 0), min (string, no leading 0), and ampm (string).


Sample body. 

```   
{
  "contact_list_id": 12345,
  "text_template_id": 67890,
  "filters":[
    "0" : [
      "0" : [
        "field_type" : "standard",
        "id" : "first_name", 
        "operator" : "equals",
        "value" : "Wiley"
      ],
      "1" : [
        "field_type" : "custom",
        "id" : "123", 
        "operator" : "greater_than",
        "value" : "1000"
      ]
    ]
  ],
  "test_filters" : true,
  "schedule_data" : [
      "scheduled_date" : "2020-06-30",
      "hour" : "12",
      "min" : "15",
      "ampm" : "pm"
  ]
}
```
Sample 200 responses:

Post Type | Response
--- | ---
Specific contact | `{"contact":{"contacts_master_id":"123","status":"correct"}}`
Contact list | `{"texts":"queue"}`
Test filters | `{"original_list_contacts": 100, "filtered_list_contacts" : 15, "view_filtered_contacts" : link_to_system_generated_contact_list }`
Scheduled email | `{"texts":"scheduled"}`

Sample 422 responses:

Sample response messages
--- |
No filters submitted.
You don't have access to that contact list.
No contacts in contact list, so not processing text.
You don't have access to that text template.
All filters are required to have field_type, id, operator, and value parameters.
ABCXYZ is not a valid contact standard field.
ABCXYZ is not a valid operator.
