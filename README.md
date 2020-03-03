VipeCloud API v3.1
=============

1. [Overview](#overview)
2. [Authentication](#authentication)

Endpoints
-------------
1. [Users (GET)](#user-get)
2. [Contacts (POST/GET)](#contacts-post--get)
3. [Custom Fields (GET)](#custom-fields-get)
4. [Contact Lists (POST/GET)](#contact-lists-post--get)
5. [User Reputation (GET)](#user-reputation-get)
6. [Log Completed Task (POST)](#log-completed-task-post)
7. [Email Templates (POST/GET)](#email-templates-post--get)
8. [Files (POST/GET)](#files-post--get)
9. [Emails (POST)](#emails-post)
10. [Tags (POST/GET/DELETE)](#tags-post--get--delete)
11. [User Merge Tags (POST/GET)](#user-merge-tags-post--get)
12. [AutoResponders (POST/GET)](#autoresponders-post--get)


<a name="#overview"></a>Overview
-------------
#### What can VipeCloud's API do for you?
   * Connect proprietary or other 3rd party systems to VipeCloud

#### General Information
   * Your account must gain authorization to use the VipeCloud API. Email support@vipecloud.com to request authorization
   * All requests must by made from HTTPS
   * All data sent should be JSON encoded (all data received will be JSON encoded)
   * Base URL for these functions: https://v.vipecloud.com/api/v3.1
   * API usage is currently throttled at 10 calls per 2 seconds per user
   
#### Interested in receiving webhooks?
   * Learn about our webhooks API: [Webhooks v1.0](webhooks_v1_0.md)

#### Responses
   * 200 for success
   * 422 for incorrect post
   * 500 which is most likely a VipeCloud error


<a name="#authentication"></a>Authentication
-------------
Authorization is a Basic header of a base64 encoded concatenation of your VipeCloud user email and an active user API Key. API keys are managed in the Setup section of your VipeCloud account.

Sample PHP curl header to add to your POST
```php
<?php

$auth = base64_encode($user_email.":".$api_key);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth", "Accept: application/json"));

?>
```

<a name="#user-get"></a>Users (GET)
-------------
### GET all active users within your account

```
GET /users
``` 
The response to this GET will be a list of the currently active VipeCloud users in your account. Users displayed are based on the visibility permission of the authenticated user. For example, an Admin user will see all your account users, a Manager will see their team members, and a Member will only see themselves.
```
{ 
  [
    { 
      "first_name"  : "Wiley", 
      "last_name"  : "Coyote", 
      "email"  : "wiley.coyote@acme.com",
      "phone"  : "123-456-7890",
      "company_name" : "Acme",
      "api_keys" : ["123456XYZ"],
      "user_role" : 'Admin'
    },
    {
      ...
    }
  ]
}
```

<a name="#contacts-post--get"></a>Contacts (POST / GET)
-------------------------------------

#### Create new / update existing Contacts in VipeCloud
```
POST /contacts(/:id)
```
When POSTing to /contacts, the body can either be an individual contact record or an array of contact records. If you are updating existing contacts, it is recommended that you include a contacts_master_id parameter for the contact. If not, the system will search for existing contacts based on the unique setting for your contact email address (account-wide, per user, or none). If submitting an array of contact records to create or update, first_name and email are always required.

You can, optionally, include a "contact_lists" parameter to your contact POST body. If you do, we will assume the contact_list_ids you submit represent the ENTIRETY of the contact lists the contact should be a part of. We will compare your POSTed contact_list_ids to any existing contact_lists for the contact. If the contact is part of contact lists not in your POST they will be removed from the list. And if contacts in your POST are not on the list they will be added. To remove a contact from ALL contact lists they are on, submit "0" as the contact_list_id (e.g. contact_lists : ["0"]).

Sample post body below.
```   
{ 
   "first_name" : "Road", //required
   "last_name" : "Runner", 
   "email" : "email", //required
   "title" : "Evader",
   "website" : "www.acme.com", 
   "work_phone" : "1234567890", 
   "mobile_phone" : "1234567891", 
   "direct_phone" : "1234567892", 
   "phone" : "1234567893", 
   "company_name" : "Acme", 
   "address1" : "123 Acme Street", 
   "address2" : "", 
   "city" : "Disneyland", 
   "state" : "CA", 
   "zip" : "12345", 
   "country" : "USA", 
   "unsubscribe" : 0,
   "personal_linkedin_url" : "https://www.linkedin.com/...",
   "personal_twitter_url" : "https://www.twitter.com/...",
   "personal_facebook_url" : "https://www.facebook.com/...",
   "tags" : ["Speedy"], 
   "verify" : 0, //if this is 1 AND you are an enterprise user AND this user has less than 10K verifications this month, we will verify the contact's email address on import
   "custom_fields" : [ //an array of the custom fields. Key value is the custom field id.
      id : "value" 
   ],
   "contact_lists" : [ //an array of contact_list_ids.
      "0" => 123,
      "1" => 1234
   ]
}
```

#### GET Contacts
```
GET /contacts(/:id)
```
If no id, returns an array of the contacts for the authenticated user. If id, returns the details for a contact. If you only require certain contact parameters, append them (comma-separated) as a "to_get" parameter: 
```
E.g. GET /contacts/123?to_get=contacts_master_id,first_name 
```

Full contact record:
```   
{ 
  [
    { 
     "contacts_master_id" : 123,
     "first_name" : "Road", 
     "last_name" : "Runner", 
     "email" : "email", 
     "title" : "Evader",
     "website" : "www.acme.com", 
     "work_phone" : "1234567890", 
     "mobile_phone" : "1234567891", 
     "direct_phone" : "1234567892", 
     "phone" : "1234567893", 
     "company_name" : "Acme", 
     "address1" : "123 Acme Street", 
     "address2" : "", 
     "city" : "Disneyland", 
     "state" : "CA", 
     "zip" : "12345", 
     "country" : "USA", 
     "personal_linkedin_url" : "https://www.linkedin.com/...",
     "personal_twitter_url" : "https://www.twitter.com/...",
     "personal_facebook_url" : "https://www.facebook.com/...",
     "tags" : ["Speedy"],
     "contact_lists" : [
        "0" => [
            "contact_list_id" : 123,
            "contact_list_name" : "First list"
        ]
     ]
    },
    {
    ...
    }
  ]
}
```

<a name="#custom-fields-get"></a>Custom Fields (GET)
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


<a name="#contact-lists-post--get"></a>Contact Lists (POST / GET)
-------------------------------------

#### Create new / update existing Contact List in VipeCloud
```
POST /contact_lists(/:id)
```
If creating a new list, a list name must be present. Creating an "empty" list - a list with a list_name and no contacts is allowed. 

When POSTing contacts to an existing list, we will assume the contacts you submit represent the ENTIRETY of the contact list. We will compare your POSTed contacts to any existing contacts on the list. If contacts on the list are not in your POST they will be removed from the list. And if contacts in your POST are not on the list they will be added.

Note that VipeCloud will not add contacts that have unsubscribed from any user in your account, bounced, or have an email which has verified as undeliverable. 

When submitting contacts include the contacts_master_id of the contact record. 
```   
{ 
 "contact_list_name" : "My First List", //required if creating a new list. Will overwrite existing name if "id" provided
 "contacts" : [
    {
      "contacts_master_id" : 123
    },
    {
      ...
    }
 ]
}
```
The response to this POST will be a status of success or error. On success the contact_list_id will be included in addition to a *count* of successful emails added to the list and a *list* of emails that were not added, with a message detailing why the contact wasn't added.
```
{
  "status" : "success",
  "contact_list_id" : 123,
  "contacts_added" : 123,
  "contacts_not_added": [
    {
      "contacts_master_id": 123,
      "message":"Contact unsubscribed."
    },
    {
      ...
    }
  ]
}
```

#### GET list of existing Contact Lists a user has in VipeCloud
```
GET /contact_lists(/:id)
```
Returns an individual record or array of the lists that a user has in VipeCloud. Optionally add the parameter hide_system_lists to the url to remove system lists from the result (e.g. /contact_lists?hide_system_lists=1)
```   
{ 
  [
    {  
      "contact_list_id" : "123",
      "contact_list_name" : "New Customers",  
      "create_date" : "2014-09-03 08:30:39",
      "active_count": 16 //note that bounces, unsubscribes, and verified undeliverable contacts are automatically removed
      "source" : "upload_csv"
    },
    {
    ...
    }
  ]
}
```

<a name="#user-reputation-get"></a>User Reputation (GET)
-------------------------------------
Access a user's email sending reputation in VipeCloud. Note that if a user's email sending reputation drops below 70, they are not allowed to send email for 21 days. This is designed to protect your domain and ability to deliver email, as well as ours.

#### GET a user's email reputation
```
GET /user_reputation
```
The response to this GET will be an integer between 0 and 100.
```   
{ 
  "reputation" : 100
}
```

<a name="#log-completed-task-post"></a>Log Completed Task (POST)
-------------

```
POST /tasks
``` 

Body params

```   
{
  "contact_email" : "road.runner@acme.com", //required and used to identify the contact
  "subject" : "Started Trial", //required
  "activity_type" : "their_activity", //required. Can be their_activity or your_activity
  "details" : "Created trial via website", 
  "contact_tags" : ["BusinessTrial","Annual"], //VipeCloud will create a contact if it doesn't exist. Values must be array
  "source" : "Website signup", //free form
  "company_tags" : ["BusinessTrial","Annual"] //VipeCloud will create a company if it doesn't exist. Values must be array
}
```

Sample response
```
{
    "status": "success",
    "contact_tags": "BusinessTrial,Annual",
    "contact_email": "road.runner@acme.com",
    "contact_first_name": "Road",
    "contact_company": "Acme"
}
```


<a name="#email-templates-post--get"></a>Email Templates (POST / GET)
-------------
If you are migrating from another email sending provider and more email templates than you can manually transfer over, you can import them using the /email_templates endpoint.

```
POST /email_templates
``` 

Body params

```   
{
  "title" : "My Email Template", //required and used to identify the contact
  "subject" : "My Email Subject", //required
  "copy" : "Hi there, this is my email template copy....", //required. HTML is allowed.
  "landing_page" : 1, //optional, can be a 1 or a 0. If a 1 we will respond with a link to the landing page
  "email_template_id" : 123, //optional, can be used to update one of your existing email templates
}
```

Sample response
```
{
    "status": "success",
    "email_template_id": 123,
    "landing_page": "link goes here" //only if you enable a landing page for the email template
}
```

#### GET Templates(s)
```
GET /email_templates
```
GET a list of your email templates, search for an email template by title, or a get specific template by id. If no email template is found, the response will be code 422 with the message "No template was found."

A note on access: in the API we only return email templates *owned* by the user. We do not return email templates the user can access via Shared With Me.

Sample responses
```   
GET /email_templates
{ 
  [
    "0" : [
      "email_template_id" : 123
      "title" : "This is my title",
      "update_date" : 2020-01-05 04:00:11
    ]
  ]
}

GET /email_templates?query=title
{ 
  [
    "0" : [
      "email_template_id" : 123
      "title" : "This is my title",
      "update_date" : 2020-01-05 04:00:11
    ]
  ]
}

GET /email_templates/123
{ 
  "email_template_id" : 123
  "title" : "This is my title",
  "subject" : "This is my subject",
  "copy" : "<div>html of your email template here</div>"
}

```


<a name="#files-post--get"></a>Files (POST / GET)
-------------
Add, update, and retrieve files from your user accounts. If you are migrating from another system and have more files than you can manually transfer over (images, videos, documents, etc.), you can import them using the /files endpoint.

#### POST File(s)

```
POST /files(/:id)
``` 

Sample body when *creating* a new file

```   
{
  "file_url" : "https://www.linktofile.com", //required
  "file_name" : "My File", //required
  "thumb_url" : "https://whatever.com" //required if importing a video
}
```

Sample response
```
{
    "status": "success",
    "id" : "123",
    "download_link": "link goes here",
    "trackable_link": "link goes here" //this is a trackable VipeCloud link
    "thumb_url": "link goes here" //only included for video file uploads
}
```

Sample body when *updating* an existing file (e.g. POST to /files/123 ). You can update the file_name or tag_ids associated with a file.

```   
{
  "file_name" : "My File",
  "tag_ids" : ["1","2"]
}
```

Sample response
```
{
    "id": "123",
    "file_name" : "My File",
    "download_link": "link goes here",
    "trackable_link": "link goes here" //this is a trackable VipeCloud link
    "create_date": "2020-01-28 23:03:23", 
    "tag_ids" : ["1","2"]
}
```



#### GET File
```
GET /files(/:id)
```
Retrieve files by id or search your account for files by file_name (url encoded) or tag_id. Responses are limited to a maximum of 50 files. If no file is found, the response will be code 422 with the message "No file was found."

Sample response to get file by id. GET /files/123
```   
{ 
    "id": "123",
    "file_name" : "My File",
    "download_link": "link goes here",
    "trackable_link": "link goes here" //this is a trackable VipeCloud link
    "create_date": "2020-01-28 23:03:23", 
    "tag_ids" : ["1","2"]
}
```

Sample response to get files by file_name (url encoded) or tag_id. Array of files is returned. GET /files?file_name=My%20File or /files?tag_id=1
```   
{ 
    [
      "id": "123",
      "file_name" : "My File",
      "download_link": "link goes here",
      "trackable_link": "link goes here" //this is a trackable VipeCloud link
      "create_date": "2020-01-28 23:03:23", 
      "tag_ids" : ["1","2"]
    ],...
}
```

<a name="#emails-post"></a>Emails (POST)
-------------
Send emails via a POST. 

#### POST Email(s)

```
POST /emails
``` 

Attribute | type | required | description
--- | --- | --- | ---
emails | string | yes or contact_list_id | Comma or semicolon separated string of emails.
cc_emails | string | no | Comma or semicolon separated string of emails.
contact_list_id | integer | yes or emails | Required param if sending to contact list. Include emails OR contact_list_id.
subject | string | yes or email_template_id | Required if no email_template_id.
message | string | yes or email_template_id | Required if no email_template_id.
email_template_id | integer | yes or subject and message | Send email to email template. Replaces requirement for subject and message.
filters | array | no | Filter contacts within a contact list at the time of send. If you include the filters parameter our system will create a new, system-generated list based on which contacts meet your filters within the contact_list_id that is also submitted. Each group of filters within the filters array must include a field_type (standard or custom), id (if standard a slug, if custom the custom_field_id), an operator (accepted values include equals, less_than, greater_than, less_than_or_equal_to, or greater_than_or_equal_to), and a value. Contacts that meet all filters within any of the filter groups will be added to the system-generated list.
test_filters | boolean | no | Will test your filters and NOT send the email. Will return the number of contacts in your original list and after your filters have been applied.


Sample body. 

```   
{
  "emails":"roadrunner@acme.com",
  "cc_emails":"wile.e.coyote@acme.com",
  "contact_list_id": 12345,
  "email_template_id": 67890,
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
  "test_filters" : true
}
```
Sample 200 responses:

Post Type | Response
--- | ---
Specific emails | `{"emails":[{"email":"roadrunner@acme.com","status":"correct"},{"email":"wile.e.coyote@acme.com","status":"correct"}]}`
Contact list | `{"emails":"queue"}`
Test filters | `{"original_list_contacts": 100, "filtered_list_contacts" : 15, "view_filtered_contacts" : link_to_system_generated_contact_list }`

Sample 422 responses:

Sample response messages
--- |
No filters submitted.
You don't have access to that contact list.
No contacts in contact list, so not processing email.
You don't have access to that email template.
All filters are required to have field_type, id, operator, and value parameters.
ABCXYZ is not a valid contact standard field.
ABCXYZ is not a valid operator.


<a name="#tags-post--get--delete"></a>Tags (POST / GET / DELETE)
-------------
Create, update, retrieve, and delete tags for your users. Tags utilized via the API can be attributed to files. 

#### POST Tag(s)

```
POST /tags(/:id)
``` 

Sample body when *creating* a new tag

```   
{
  "tag_name" : "My Tag Name", //required
}
```

Sample response
```
{
    "status": "success",
    "tag_id" : "123"
}
```

Sample body when *updating* an existing tag (e.g. POST to /tags/123 ). You can update the tag_name.

```   
{
  "tag_name" : "My Tag Name 2", //required
}
```

Sample response
```
{
    "status": "success",
    "tag_id" : "123",
    "tag_name" : "My Tag Name 2"
}
```

#### GET Tag
```
GET /tags(/:id)
```
Retrieve tags by id or as a group. If no tag is found, the response will be code 422 with the message "No tag was found."

Sample response to get tag by id. GET /tags/123
```   
{     
    "tag_id" : "123",
    "tag_name" : "My Tag Name 2"
}
```

Sample response to get tags with no id. Array of tags is returned. GET /tags
```   
{ 
    [
      "id": "123",
      "tag_name" : "My Tag Name 2"
    ],...
}
```

#### DELETE Tag
```
DLETE /tags/:id
```
Delete tag by id. If no tag is found, the response will be code 422 with the message "No tag was found."

Sample response to delete tag by id. DELETE /tags/123
```   
{     
    "status" : "success
}
```

<a name="#user-merge-tags-post--get"></a>User Merge Tags (POST / GET)
-------------
Update and retrieve user_merge_tags from your user accounts. Create the user_merge_tag for the account in your parent user account.

#### POST User Merge Tag

```
POST /user_merge_tags(/:id)
``` 

Sample body when updating a merge tag value.

```   
{
  "value" : "New Value"
}
```

Sample response
```
{
    "id": "123",
    "merge_tag" : "%YOUR_USER_MERGE_TAG%",
    "value" : "New Value"
}
```

#### GET User Merge Tags
```
GET /user_merge_tags(/:id)
```
Retrieve user merge tags by id or retrieve a list of all user_merge_tags in your account. 

Sample response to get user_merge_tag by id. GET /user_merge_tags/123
```   
{ 
    "id": "123",
    "merge_tag" : "%YOUR_USER_MERGE_TAG%",
    "value" : "New Value"
}
```

Sample response to get all user merge tags. GET /user_merge_tags
```   
{ 
    [
      "id": "123",
      "merge_tag" : "%YOUR_USER_MERGE_TAG%",
      "value" : "New Value"
    ],...
}
```

<a name="#autoresponders-post--get"></a>AutoResponders (POST / GET)
-------------
Create, update, and retrieve AutoResponders from your user accounts. 

#### POST AutoResponder

Attribute | type | required | description 
--- | --- | --- | --- 
id | integer | yes if update / no if create | AutoResponder id 
contact_list_id | integer | yes | Contact list id 
template_type | enum | yes | "email" or "series" 
contact_list_id | integer | yes | The id of the email or series template 
trigger | enum | yes | "contact" or "recurring" or "custom_field"<br>Trigger details:<br>- If "contact" must also include "delay_days", "delay_hours", and "delay_min" paramenters.<br>- If "recurring" must also include "day", "hour", "min", and "ampm" parameters. Optionally "weekday_only" can be set to "on".<br>- If "custom_field" must also include "custom_field_id", "hour", "min", and "ampm" parameters.


```
POST /autoresponders(/:id)
``` 
Sample body when creating an AutoResponder.

```   
{
    "contact_list_id" : 123,
    "contact_list_name" : "My contact list",
    "template_type" : "email",
    "template_id" : 123,
    "template_title" : "My email template",
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
Retrieve autoresponders by id or retrieve a list of all autoresponder in the user's account. 

Sample response to get user_merge_tag by id. GET /autoresponders/123
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

Sample response to get all user merge tags. GET /autoresponders
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












