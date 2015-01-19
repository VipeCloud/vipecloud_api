VipeCloud API v1.0 (still in beta)
=============
1. [Overview](#overview)
2. [Generating the Signature](#generating-the-signature)
3. [Working with Iframed Modals](#working-with-iframed-modals)
4. [User Management (POST/GET)](#user-management-post--get)
5. [Contact Lists (POST/GET)](#contact-lists-post--get)
6. [User Reputation (GET)](#user-reputation-get)
7. [Send Trackable Email Widget (IFRAME)](#send-trackable-email-widget-iframe)
8. [Contact List Performance (GET)](#contact-list-performance-get)
9. [Manage Email Templates (IFRAME/GET)](#manage-email-templates-iframe--get)
10. [Email Sharing Settings (IFRAME)](#email-sharing-settings-iframe)
11. [Email History (GET)](#email-history-get)
12. [Scheduled Emails (IFRAME/GET)](#scheduled-emails-iframe--get)
13. [Phone Calls (IFRAME)](#phone-calls-iframe)



<a name="#overview"></a>Overview
-------------
#### Apply to be a VipeCloud Partner
   * Learn about our partnership programs and [apply here](http://partners.vipecloud.com).

#### General Information
   * All requests must by made from HTTPS
   * All data sent should be JSON encoded (all data received will be JSON encoded)
   * Base URL for these functions: https://v.vipecloud.com/api/v1.0/{partner_api_slug}
   * The api_key included in VipeCloud API calls is a unique user identifier for one of your users. This could be an install_id, user_key, or whatever. We use the term api_key.

#### Responses
   * 200 for success
   * 422 for incorrect post
   * 500 which is most likely a VipeCloud error


<a name="#generating-the-signature"></a>Generating the Signature
-------------
The signature is a base64 encoded concatenation of the request path based on the shared secret (i.e. https://v.vipecloud.com/api/v1.0/{partner_api_slug}/users/{api_key}).

Your shared secret can be found here: https://v.vipecloud.com/settings/affiliate after having been approved as a VipeCloud partner. 

Sample PHP code to generate the signature
```php
<?php

function generateSignature($action,$api_key){
    $path = "https://v.vipecloud.com/api/v1.0/{partner_api_slug}/" . $action . "/" . $api_key;
    $signature = hash_hmac('sha256', $path, SHARED_SECRET, $raw=true);
    $encoded_sig = base64_encode(strtr($signature, '-_','+/')); 
    return array('signature' => $encoded_sig);
}

?>
```

<a name="#working-with-iframed-modals"></a>Working with Iframed Modals
-------------
For all iframes that will be opened via modals (e.g. Send Trackable Email, Create Template, etc), we use postMessage on the successful completion of the modal task, so you can close the modal.

Note that postMessage is compatible with IE 8+, FF 3.0+, Chrome 1.0+, Safari 4.0+, Opera 9.5+

Here is a sample script to catch the postMessage:
```
<script>

  function receiveMessage(event){
  
    //security check that message is coming from VC domain
    if (event.origin !== "https://v.vipecloud.com"){
      return false;
    }
    
    if(event.data == 'success'){
      //close modal
    }
    
    return false;
  };

  addEventListener("message", receiveMessage, false);
  
</script>
```


<a name="#user-management-post--get"></a>User Management (POST / GET)
-------------
### POST - Create, edit, and delete users
Include ALL USERS within the account that should actively have an integration with VipeCloud. For all users included:
* 1. A new user account in VC will be created if it doesn't exist, and updated if it does exist. 
* 2. For each new user, an integration between VC and your application will be created.
* 3. For any users previously submitted and connected to this account_id, but not included in this POST, their user account and VipeCloud integration with your app will be deactivated. 
* 4. The account in VC will be updated to include all of the submitted users (and exclude any users previously submitted but not included)

IMPORTANT: the api_key for the FIRST user must match the api_key in the URL.

```
POST /users/API_KEY?signature=SIGNATURE
``` 

Body params

```   
{
  "developer_mode" : true/false, //optional param for staging environment api_key check
  "developer_local" : true/false, //optional param that does *not* validate your user's api_key
  "account_id" : "123XYZ",
  "users" : [
    {
      "user_email" : "wiley.coyote@acme.com",  
      "first_name" : "Wiley",  
      "last_name" : "Coyote",
      "company" : "Acme",
      "phone" : "555-555-5555",
      "api_key" : "123XYZ",
      "unique_id" : "123456",
      "account_id" : "123XYZ",
      "timezone" : "America/Los_Angeles", //olson timezone string
      "is_admin" : 0/1
    },
    {
      ...
    }
  ]  
}
```
### GET all currently active users for the integration

```
GET /users/API_KEY?signature=SIGNATURE
``` 
The response to this GET will be a list of the currently active VipeCloud users for an account within your integration.
```
{
  "account_id" : "123XYZ",
  "users" : [
    {
      "user_email"  : "wiley.coyote@acme.com", 
      "unique_id" : "123456",
      "is_admin" : 0/1
    },
    {
      ...
    }
  ]
}
```

<a name="#contact-lists-post--get"></a>Contact Lists (POST / GET)
-------------------------------------
VipeCloud supports creating new contact lists and adding contacts to existing lists (while not duplicating contacts in a list). This endpoint can be implemented in several ways:
* 1. Users create a named list in VipeCloud and they can send to that list at a later time or the Send Trackable Email modal can be launched on successful completion of the list creation to send a mail merge right then.
* 2. If a user has an existing list they can add contacts to an existing list, and even have the share modal launched to send a mass email to the newly updated list.
* 3. Keep users inside of your application for mass emailing! 


#### Create new / update existing Contact List in VipeCloud
```
POST /contact_list/API_KEY?signature=SIGNATURE
```
If a list key is present, contacts will be added to that list. If no list key is present, a list name must be present. Creating an "empty" list - a list with a list_name and no contacts is allowed. VipeCloud will check for and not add duplicates to this list. VipeCloud will also not add contacts that have unsubscribed from this user or bounced.
```   
{ 
 "list_key" : "LIST_KEY/FALSE",
 "list_name" : "LIST_NAME",
 "contacts" : [
    {
      "first_name"  : "Wiley",  // required
      "email" : "wiley.coyote@acme.com", // required
      "last_name" : "Coyote",
      "phone" : "1234567890",
      "company" : "Acme"
    },
    {
    ...
    }
 ]
}
```
The response to this POST will be a status of success or error. On success the list_key will be included in addition to a *count* of successful emails added to the list and a *list* of emails that were not added, with a message detailing why the contact wasn't added.
```
{
  "status" : "success",
  "list_key" : "123XYZ",
  "contacts_added" : 123,
  "contacts_not_added": [
    {
      "email":"wiley.e.coyoto@gmail.com",
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
GET /contact_list/API_KEY?signature=SIGNATURE
```
Returns an array of the lists that a user has in VipeCloud
```   
{ 
  [
    {  
      "list_name" : "New Customers",  
      "list_key" : "123XYZ",
      "create_date" : "2014-09-03 08:30:39",
      "list_size": 16
    },
    {
    ...
    }
  ]
}
```
After creating/updating a list the Send Trackable Email widget can be launched with the list pre-selected as the destination. 

<a name="#user-reputation-get"></a>User Reputation (GET)
-------------------------------------
Access a user's email sending reputation in VipeCloud

#### GET a user's email reputation
```
GET /user_reputation/API_KEY?signature=SIGNATURE
```
The response to this GET will be an integer between 0 and 100.
```   
{ 
  "reputation" : 100
}
```

<a name="#send-trackable-email-widget-iframe"></a>Send Trackable Email Widget (IFRAME)
--------------------
This widget will allow all VipeCloud enabled users to send a trackable email from within your application.

ENDPOINT: /send_trackable_email/{api_key}?signature={signature}

Optional Params:
* dest_email
* dest_first_name
* list_key
* crm_obj & obj_id- If you have a crm_obj, at this time we are only setup to handle 'deal' as an object. If you use a deal crm_obj and deal_id, we will attribute the email activity to the deal_id instead of person.
* reply_to_email - add the email_id of the email the user is replying to and we will append the original email into the message

Sample iframe for the widget
```
<iframe src="{URL}" width="660" height="560" frameborder="0"></iframe>
```


<a name="#contact-list-performance-get"></a>Contact List Performance (GET)
--------------------
Receive a history of contact list shares and their performance for a user

#### GET entire history of contact list shares for a user
```
GET /contact_list_performance/API_KEY?signature=SIGNATURE
```
The response to this GET will be an array of list shares for the user as outlined below.
```   
{ 
  [
    {
      "list_share_id" : 12345,
      "create_date" : 1421095100,  //Unix timestamp
      "contact_list_name" : "Mass Email Part 1",
      "subject" : "Subject goes here",
      "message" : "Message text goes here",
      "template_title" : "Template title goes here",
      "contacts_in_list" : 123,
      "deliver" : 118,
      "bounce" : 2,
      "unsubscribe" : 3,
      "uniq_open" : 33,
      "click" : 10
    },
    {
    ...
    }
  ]
}
```

#### GET detail/s for an action of a list share
```
GET /contact_list_performance/API_KEY?signature=SIGNATURE&id=[list_share_id]&a=[action]
```
Actions supported include uniq_open, click, bounce, unsubscribe, and deliver.

The response to this GET will be an array of the list send actions for the user as outlined below. Clicks will include an additional "url" field for the link that was clicked.
```   
{ 
  [
    {
      "email_address" : "roadrunner@acme.com",
      "action_date" : 1421095100, //unix timestamp of the most recent action
      "total_actions" : 12, //count of the total number of actions
      "url" : "https://www.vipecloud.com" //only included for the click action
    },
    {
    ...
    }
  ]
}
```


<a name="#manage-email-templates-iframe--get"></a>Manage Email Templates (IFRAME / GET)
--------------------
Enable users to manage email templates.

#### Iframe to create new / update existing email templates in VipeCloud

* ENDPOINT: /email_template_iframe/{api_key}?signature={signature}
* Creating templates: use the base URL with "&type=[type]" to create a template of type personal, team, or reply. Note that to create a team template user must be admin user
* Editing templates: use the base URL with "&id=[email_template_id]&action=[action]" to edit, create_a_copy, delete, or add_to_team

```
<iframe src="{URL}" width="600" height="530" frameborder="0"></iframe>
```

#### GET a user's email templates by type OR a specific email template by id
GET all templates at the root level for a particular type (personal, reply, or team)
```
GET /email_templates/API_KEY?signature=SIGNATURE&type=[type]
```
GET all email templates within a folder a particular type (folder_id is the email_template_id for an email template where is_folder = 1)
```
GET /email_templates/API_KEY?signature=SIGNATURE&type=[type]&folder_id=[folder_id]
```
GET an email template by id
```
GET /email_templates/API_KEY?signature=SIGNATURE&id=[email_template_id]
```
* Requesting templates by type or by type & folder_id returns an array
* Requesting a specific template returns data about the template

```   
{ 
  [ 
    {  
      "email_template_id" : 123, 
      "create_date" : 1421095100, //Unix timestamp
      "update_date" : 1421095100, //Unix timestamp provided if exists (otherwise null)
      "title" : "Template Title",
      "subject" : "Template email subject", //not included for reply templates
      "copy" : "Template email message",
      "team_name" : "Team Name" //only included for team templates
      "is_folder" : 1/0,
      "is_default" : 1/0 //only included for reply templates
    },
    {
    ...
    }
  ]
}
```


<a name="#email-sharing-settings-iframe"></a>Email Sharing Settings (IFRAME)
--------------------
Page where users can edit default sharing settings, including their signature

Sample iframe for email sharing settings
*ENDPOINT: /email_sharing_settings_iframe/{api_key}?signature={signature}
```
<iframe src="{URL}" width="600" height="530" frameborder="0"></iframe>
```

<a name="#email-history-get"></a>Email History (GET)
--------------------
#### GET a user's email history 
GET the last 30 days of a user's email history (default)
```
GET /email_history/API_KEY?signature=SIGNATURE
```
GET the a user's entire email history
```
GET /email_history/API_KEY?signature=SIGNATURE&from=all
```
Search a user's entire email history
```
GET /email_history/API_KEY?signature=SIGNATURE&query=[query]
```
GET the a user's email history by date range (unix timestamps)
```
GET /email_history/API_KEY?signature=SIGNATURE&from=1421095000&to=1421095100
```
Sample response array
```   
{ 
  [ 
    {  
      "email_id" : 123, 
      "to" : "roadrunner@acme.com",
      "create_date" : 1421095100, //Unix timestamp
      "subject" : "Email subject",
      "message" : "Email message",
      "opens" : 3,
      "clicks" : 1,
      "status" : "delivered" // can be delivered/bounced/unsubscribed
    },
    {
    ...
    }
  ]
}
```

<a name="#scheduled-emails-iframe--get"></a>Scheduled Emails (IFRAME / GET)
--------------------
Users can view / manage scheduled emails

Iframe to change or cancel a scheduled email

* ENDPOINT: /scheduled_emails_iframe/{api_key}?signature={signature}
* Params: &scheduled_email_id=[send_later_id]&action=[change/cancel]
```
<iframe src="{URL}" width="600" height="530" frameborder="0"></iframe>
```
#### GET a user's scheduled emails
GET the a user's entire email history
```
GET /scheduled_emails/API_KEY?signature=SIGNATURE&from=all
```
Note that the default limit for number of responses is 500. To increase that limit add &limit=XXX to the GET.

Sample response array
```   
{ 
  [ 
    {  
      "scheduled_email_id" : 123, 
      "to" : "roadrunner@acme.com", //string that allows for multiple email addresses
      "scheduled_date" : 1421095100, //Unix timestamp
      "subject" : "Scheduled email subject"
    },
    {
    ...
    }
  ]
}
```

<a name="#phone-calls-iframe"></a>Phone Calls (IFRAME)
--------------------
VipeCloud users can make phone calls from their browser, from directly inside your application, and VipeCloud can record the call and log the phone call activity. This is a pay-as-you-go feature, at an average cost of $0.03 per minute for US calls. An admin can setup a credit card for their entire team, or require each team member to pay with their own credit card.

NOTES: 
* The contact params in the url are different than above. ContactEmail, ContactName, and ContactPhone are utilized in this call as optional params that will prefill the calling information.
* The first time /phone_call is called for every user, we will need to setup 1) payment and 2) a verified caller_id. An admin can setup an account-wide credit card, so we suggest launching the /phone_call modal as part of the setup process to give them the option to do so. (otherwise, each team member will have to enter a credit card to make phone calls).

Sample iframe for the phone call widget
* ENDPOINT: /phone_call/{api_key}?signature={signature}
* Params: &ContactEmail={dest_email}&ContactName={dest_name}&ContactPhone={dest_phone}
```
<iframe src="{URL}" width="600" height="530" frameborder="0"></iframe>
```
