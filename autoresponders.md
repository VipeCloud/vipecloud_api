AutoResponders (POST / GET)
-------------
Create, update, and retrieve AutoResponders from your user accounts. 

#### POST AutoResponder

Attribute | type | required | description 
--- | --- | --- | --- 
contact_list_id | integer | yes | Contact list id 
template_type | enum | yes | "email" or "series" 
template_id | integer | yes | The id of the email or series template 
trigger | enum | yes | "contact" or "recurring" or "custom_field"<br>Trigger details:<br>- If "contact" must also include "delay_days", "delay_hours", and "delay_min" parameters. May also include "contact_trigger_hours" (1/0), "contact_trigger_from_hour" (1-12), "contact_trigger_from_min" (0-59), "contact_trigger_from_ampm" (am/pm), "contact_trigger_to_hour" (1-12), "contact_trigger_to_min" (0-59), "contact_trigger_to_ampm" (am/pm)<br>- If "recurring" must also include "day", "hour", "min", and "ampm" parameters (no leading 0's). Optionally "weekday_only" can be set to "on".<br>- If "custom_field" must also include "custom_field_id", "hour", "min", and "ampm" parameters.


```
POST /autoresponders(/:id)
``` 
Sample body when creating an AutoResponder.

```   
{
    "contact_list_id" : 123,
    "template_type" : "email",
    "template_id" : 123,
    "trigger" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0
}
```



#### GET AutoResponders
```
GET /autoresponders(/:id)
```
Retrieve autoresponders by id or retrieve a list of all autoresponders in the user's account. 

Sample response to get autoresponders by id. GET /autoresponders/123
```   
{ 
    "id" : 123,
    "contact_list_id" : 123,
    "contact_list_name" : "My contact list",
    "template_type" : "email",
    "template_id" : 123,
    "template_title" : "My email template",
    "trigger" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0,
    "schedule_data" : null,
    "weekday_only" : null
}
```

Sample response to get all autoresponders. GET /autoresponders
```   
{ 
    [
      "id" : 123,
      "contact_list_id" : 123,
      "contact_list_name" : "My contact list",
      "template_type" : "email",
      "template_id" : 123,
      "template_title" : "My email template",
      "trigger" : "contact",
      "delay_days" : 0,
      "delay_hours" : 0,
      "delay_min" : 0,
      "schedule_data" : null,
      "weekday_only" : null
    ],...
}
```
