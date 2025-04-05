Automations (POST / GET)
-------------
Create, update, and retrieve Automations (formerly AutoResponders) from your user accounts. NOTE - /autoresponders will continue to work for the time being.

#### POST Automations

Attribute | type | required | description 
--- | --- | --- | --- 
contact_list_id | integer | yes | Contact list id 
template_type | enum | yes | "email" or "series" or "text" or "cancel_series" or "cancel_email" or "cancel_text" 
template_id | integer | yes | The id of the email or series template 
trigger_type | enum | yes | "contact" or "recurring" or "custom_field"<br>Trigger details:<br>- If "contact", must also include "delay_days", "delay_hours", and "delay_min" parameters. May also include "contact_trigger_hours" (1/0), "contact_trigger_from_hour" (1-12), "contact_trigger_from_min" (0-59), "contact_trigger_from_ampm" (am/pm), "contact_trigger_to_hour" (1-12), "contact_trigger_to_min" (0-59), "contact_trigger_to_ampm" (am/pm)<br>- If "recurring" must also include "day", "hour", "min", and "ampm" parameters (no leading 0's). Optionally "weekday_only" can be set to "on".<br>- If "custom_field" must also include "custom_field_id", "hour", "min", and "ampm" parameters. 
template_ids | integer[] | cond. | Required if template_type is in ["cancel_series", "cancel_email", "cancel_text"]. Contains a list of ids for the specified template type (email template ids for "cancel_email", text template ids for "cancel_text", etc. to cancel (if any scheduled items - or steps in the case of series - based off of the template ids are active for the user) when the automation is fired).


```
POST /automations(/:id)
``` 
Sample body when creating an Automation.

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

```
POST /automations(/:id)
``` 
Sample body when creating an Automation for canceling templates. Upon being added to the list, if the added contact has a scheduled email set to go out based on the templates 1234, 5678, or 9012, those scheduled sends will be canceled.

```   
{
    "contact_list_id" : 123,
    "template_type" : "cancel_email",
    "template_id" : 123,
    "trigger" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0
    "template_ids" : [
        1234, 5678, 9012
    ]
}
```


#### GET Automations
```
GET /automations(/:id)
```
Retrieve automations by id or retrieve a list of all automations in the user's account. 

Sample response to get automations by id. GET /automations/123
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

Sample response to get all automations. GET /automations
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
