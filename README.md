VipeCloud API v3.1
=============

1. [Overview](#overview)
2. [Authentication](#authentication)

Endpoints
-------------
1. [Users (GET)](#user-get)
2. [Contacts (POST/GET)](#contacts-post--get)
3. [Contact Lists (POST/GET)](#contact-lists-post--get)
4. [User Reputation (GET)](#user-reputation-get)
5. [Log Completed Task (POST)](#log-completed-task-post)
6. [Email Templates (POST)](#email-templates-post)
7. [Import Files (POST)](#import-files-post)


<a name="#overview"></a>Overview
-------------
#### What can VipeCloud's API do for you?
   * Connect proprietary or other 3rd party systems to VipeCloud.

#### General Information
   * Your account must gain authorization to use the VipeCloud API. Email support@vipecloud.com to request authorization.
   * All requests must by made from HTTPS
   * All data sent should be JSON encoded (all data received will be JSON encoded)
   * Base URL for these functions: https://v.vipecloud.com/api/v3.1
   
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

<a name="#users"></a>Users (GET)
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

<a name="#contacts-post--get"></a>Contacts (POST/GET)
-------------------------------------

#### Create new / update existing Contacts in VipeCloud
```
POST /contacts(/:id)
```

Body params
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
   "personal_linkedin_url" : "https://www.linkedin.com/...",
   "personal_twitter_url" : "https://www.twitter.com/...",
   "personal_facebook_url" : "https://www.facebook.com/...",
   "tags" : ["Speedy"], 
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
    },
    {
    ...
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
Returns an individual record or array of the lists that a user has in VipeCloud
```   
{ 
  [
    {  
      "contact_list_id" : "123",
      "contact_list_name" : "New Customers",  
      "create_date" : "2014-09-03 08:30:39",
      "active_count": 16 //note that bounces, unsubscribes, and verified undeliverable contacts are automatically removed
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


<a name="#email-templates"></a>Email Templates (POST)
-------------
If you are migrating from another email sending provider and more email templates than you can manually transfer over, you can import them using the /email_templates endpoint.

**Important Note About Enabling HTML Builder**. All VipeCloud emails can be edited as HTML in our standard rich text editor. We also support an "HTML Builder" which is a drag and drop interface for creating beautiful, newsletter-like email templates. Our HTML Builder is powered by [Stripo](https://www.stripo.email). In order to enable the HTML Builder for an imported email template, your HTML **MUST** be formatted per the [Stripo requirements listed here](https://stripo.email/blog/advanced-option-email-templates-adaptation-stripo-builder/).

If you enable the HTML Builder you will also need to include the CSS of your Stripo formatted email. If you don't have a separate CSS file, their [blank template here](https://github.com/ardas/stripo-plugin/blob/master/Public-Templates/Basic-Templates/Empty-Template/Empty-Template.css) will most likely work. 

```
POST /html_templates
``` 

Body params

```   
{
  "title" : "My Email Template", //required and used to identify the contact
  "subject" : "My Email Subject", //required
  "copy" : "Hi there, this is my email template copy....", //required. HTML is allowed.
  "enable_html_builder" : 1, //optional, can be a 1 or a 0. 
  "css" : "css here", //required if enable_html_builder is 1
  "landing_page" : 1, //optional, can be a 1 or a 0. If a 1 we will respond with a link to the landing page
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

<a name="#import-files"></a>Import Files (POST)
-------------
If you are migrating from another system and have more files than you can manually transfer over (images, videos, documents, etc.), you can import them using the /files endpoint.

Note that this endpoint is throttled based on file size.

```
POST /files
``` 

Body params

```   
{
  "file_url" : "https://www.linktofile.com", //required
  "file_name" : "My File", //required
  "file_size" : 12345, //required. Size is in bytes
  "file_type" : "image/jpeg" //required. Mimetype of the file.
}
```

Sample response
```
{
    "status": "success",
    "cdn_link": "link goes here",
    "trackable_link": "link goes here" //this is a trackable vipecloud link
}
```




