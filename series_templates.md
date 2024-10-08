Series Templates (POST / GET)
-------------
Create, update, and retrieve Series Templates from your user accounts. Use in conjunction with the /series_template_steps endpoint.

#### POST Series Template

Attribute | type | required | description 
--- | --- | --- | --- 
title | string | yes | The title of your series template
steps | array | yes | Array of series step ids
cancel_all_inbound | boolean | no | Optionally set a flag to cancel following steps of a series if the user receives an inbound email from the series recipient. This requires the user to have connected their inbox to VipeCloud to work.
cancel_all_if_joined_suite | integer | no | Optionally set the suite_id to cancel following steps of a series if the recipient joins the suite. This requires the user to have the Community Add-On and be the owner of the Suite.

```
POST /series_templates(/:id)
``` 
Sample body when creating an Series Template.

```   
{
    "title" : "My Series Template",
    "steps" : ["123","124","125"]
}
```


#### GET Series Templates
```
GET /series_templates(/:id)
```
Retrieve series templates by id or retrieve a list of all series templates in the user's account. To refine the return to a particular folder within a user's account, a "folder_id" query parameter can be provided. By default, all templates regardless of folders are returned via the endpoint, however if folder_id=0 is provided then only templates in the user's base folder are returned.

Sample response to get series templates by id. GET /series_templates/123
```   
{ 
    "id" : 123,
    "title" : "My Series Template",
    "update_date" : "2019-07-24 23:00:31"
}
```

Sample response to get all series templates. GET /series_templates
```   
{ 
    [
      "id" : 123,
      "title" : "My Series Template",
      "update_date" : "2019-07-24 23:00:31"
    ],...
}
```
