Series Template Steps (POST / GET)
-------------
Create, update, and retrieve Series Template Steps from your user accounts. Use in conjunction with the /series_templates endpoint.

#### POST Series Template Step

Attribute | type | required | description 
--- | --- | --- | --- 
delay_days | integer | yes | The number of days to delay this step after the prior step. Note that the first step in a series template will always have the delay days set to 0, regardless of this value.
email_template_id | integer | yes | Email template id
series_template_id | integer | no | Note the system will automatically assign this value when the series template step id is included in a POST to /series_templates. IMPORTANT: a series template step can only be associated with ONE series template. 
action | string | no | This value will ALWAYS be set to "send a new email" for series template steps created via the API.
hour | integer | no | Optionally set the hour of the day this step will process. Disregarded for the first step. No leading zero.
min | integer | no | Optionally set the minute of the hour this step will process. Disregarded for the first step. No leading zero.
ampm | enum "am" or "pm" | no | Optionally set the am or pm of the day this step will process. Disregarded for the first step. NOTE to set the time for the step to process EACH of the hour, min, and ampm parameters need to be set.
weekday | boolean | no | Optionally set the step to only process on a weekday.

```
POST /series_template_steps(/:id)
``` 
Sample body when creating a Series Template Step. NOTE - if you UPDATE a series template step ALREADY associated with a series template you must ALSO update the series template to save the step changes.

```   
{
    "delay_days" : 7,
    "email_template_id" : 123,
    "hour" : 12,
    "min" : 0,
    "ampm" : "pm",
    "weekday" : true
}
```


#### GET Series Template Steps
```
GET /series_template_steps(/:id)
```
Retrieve series template steps by id, by series_template_id, or retrieve a list of all series template steps in the user's account. 

Sample response to get series template steps by id. GET /series_template_steps/123 or /series_template_steps?series_template_id=123
```   
{ 
    "id" : 123,
    "series_template_id" : 123,
    "delay_days" : 7,
    "action" : "send a new email",
    "email_template_id" : 123,
    "weekday" : true,
    "hour" : 12,
    "min" : 0,
    "ampm" : "pm"
}
```

Sample response to get all series template steps. GET /series_template_steps
```   
{ 
    [
      "id" : 123,
      "series_template_id" : 123,
      "delay_days" : 7,
      "action" : "send a new email",
      "email_template_id" : 123,
      "weekday" : true,
      "hour" : 12,
      "min" : 0,
      "ampm" : "pm"
    ],...
}
```
