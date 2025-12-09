Automations (POST / GET)
-------------
Create, update, and retrieve Automations (formerly AutoResponders) from your user accounts. NOTE - /autoresponders will continue to work for the time being.

#### POST Automations

Attribute | type | required | description
--- | --- | --- | ---
item_type | enum | no | "contact_list" (default) or "suite_party". Determines the trigger source type.
contact_list_id | integer | cond. | Required when item_type is "contact_list" (or not specified). The contact list id that triggers the automation.
suite_party_id | integer | cond. | Required when item_type is "suite_party". The suite/community id that triggers the automation when contacts join.
template_type | enum | yes | "email" or "series" or "text" or "custom_field" or "cancel_series" or "cancel_email" or "cancel_text"
template_id | integer | cond. | Required when template_type is "email", "series", or "text". The id of the template to send.
action_custom_field_id | integer | cond. | Required when template_type is "custom_field". The id of the custom field to update. Must be a "contact" type custom field 
action_custom_field_value | string/array | cond. | Required when template_type is "custom_field". The value to set. For Checkbox fields, must be "true" or "false". For Dropdown/Picklist fields, must be a valid option value.
trigger_type | enum | cond. | "contact" or "recurring" or "custom_field" or "suite_party_joined"<br>Trigger details:<br>- If "contact", must also include "delay_days", "delay_hours", and "delay_min" parameters. May also include "contact_trigger_hours" (1/0), "contact_trigger_from_hour" (1-12), "contact_trigger_from_min" (0-59), "contact_trigger_from_ampm" (am/pm), "contact_trigger_to_hour" (1-12), "contact_trigger_to_min" (0-59), "contact_trigger_to_ampm" (am/pm)<br>- If "recurring" must also include "day", "hour", "min", and "ampm" parameters (no leading 0's). Optionally "weekday_only" can be set to "on".<br>- If "custom_field" must also include "custom_field_id", "hour", "min", and "ampm" parameters.<br>- If "suite_party_joined" is used automatically when item_type is "suite_party".
template_ids | integer[] | cond. | Required if template_type is in ["cancel_series", "cancel_email", "cancel_text"]. Contains a list of ids for the specified template type (email template ids for "cancel_email", text template ids for "cancel_text", etc. to cancel (if any scheduled items - or steps in the case of series - based off of the template ids are active for the user) when the automation is fired).


```
POST /automations(/:id)
```
Sample body when creating a standard Contact List Automation.

```
{
    "contact_list_id" : 123,
    "template_type" : "email",
    "template_id" : 456,
    "trigger_type" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0
}
```

```
POST /automations(/:id)
```
Sample body when creating a Suite Party Joined Automation. This automation fires when a contact joins the specified suite/community.

```
{
    "item_type" : "suite_party",
    "suite_party_id" : 789,
    "template_type" : "email",
    "template_id" : 456,
    "delay_days" : 0,
    "delay_hours" : 1,
    "delay_min" : 0
}
```

```
POST /automations(/:id)
```
Sample body when creating a Custom Field Action Automation. This automation sets a custom field value on the contact when triggered.

```
{
    "contact_list_id" : 123,
    "template_type" : "custom_field",
    "action_custom_field_id" : 456,
    "action_custom_field_value" : "Active Customer",
    "trigger_type" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0
}
```

```
POST /automations(/:id)
```
Sample body when creating a Checkbox Custom Field Action Automation.

```
{
    "contact_list_id" : 123,
    "template_type" : "custom_field",
    "action_custom_field_id" : 789,
    "action_custom_field_value" : "true",
    "trigger_type" : "contact",
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
    "trigger_type" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0,
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

Sample response to get a contact list automation by id. GET /automations/123
```
{
    "id" : 123,
    "contact_list_id" : 123,
    "contact_list_name" : "My contact list",
    "template_type" : "email",
    "template_id" : 456,
    "template_title" : "My email template",
    "trigger" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0,
    "schedule_data" : null,
    "weekday_only" : null,
    "item_type" : "contact_list"
}
```

Sample response for a suite_party_joined automation. GET /automations/456
```
{
    "id" : 456,
    "contact_list_id" : 0,
    "template_type" : "email",
    "template_id" : 789,
    "template_title" : "Welcome to our community",
    "trigger" : "suite_party_joined",
    "delay_days" : 0,
    "delay_hours" : 1,
    "delay_min" : 0,
    "schedule_data" : "{\"suite_party_id\":\"123\",\"contact_trigger_hours\":0}",
    "weekday_only" : null,
    "item_type" : "suite_party",
    "suite_name" : "My Community",
    "suite_type" : "community"
}
```

Sample response for a custom_field action automation. GET /automations/789
```
{
    "id" : 789,
    "contact_list_id" : 123,
    "contact_list_name" : "My contact list",
    "template_type" : "custom_field",
    "template_id" : 456,
    "trigger" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0,
    "schedule_data" : null,
    "weekday_only" : null,
    "item_type" : "contact_list",
    "automation_data" : {
        "custom_field_value" : "Active Customer",
        "field_type" : "Dropdown"
    }
}
```

Sample response to get all automations. GET /automations
```
[
    {
        "id" : 123,
        "contact_list_id" : 123,
        "contact_list_name" : "My contact list",
        "template_type" : "email",
        "template_id" : 456,
        "template_title" : "My email template",
        "trigger" : "contact",
        "delay_days" : 0,
        "delay_hours" : 0,
        "delay_min" : 0,
        "schedule_data" : null,
        "weekday_only" : null,
        "item_type" : "contact_list"
    },
    {
        "id" : 456,
        "contact_list_id" : 0,
        "template_type" : "series",
        "template_id" : 789,
        "template_title" : "Onboarding Series",
        "trigger" : "suite_party_joined",
        "delay_days" : 0,
        "delay_hours" : 0,
        "delay_min" : 30,
        "schedule_data" : "{\"suite_party_id\":\"123\"}",
        "weekday_only" : null,
        "item_type" : "suite_party"
    }
]
```
