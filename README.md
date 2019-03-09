VipeCloud API v3.1
=============

1. [Overview](#overview)
2. [Authentication](#authentication)

Back end endpoints
-------------
1. [Users (GET)](#user-get)
2. [Contacts (POST/GET)](#contacts-post--get)
3. [Contact Lists (POST/GET)](#contact-lists-post--get)
4. [User Reputation (GET)](#user-reputation-get)
5. [Trigger Trackable Email (POST)](#trigger-trackable-email-post)
6. [Log Completed Task (POST)](#log-completed-task-post)


<a name="#overview"></a>Overview
-------------
#### What can VipeCloud's API do for you?
   * Connect proprietary or other 3rd party systems to VipeCloud.

#### General Information
   * All requests must by made from HTTPS
   * All data sent should be JSON encoded (all data received will be JSON encoded)
   * Base URL for these functions: https://v.vipecloud.com/api/v3.1/vipecloud
   
#### Agencies and large accounts
   * Base URL for these functions: https://v.vipecloud.com/api/v3.1/{agency_api_slug}
   * [Contact us](mailto:support@vipecloud.com) for more information about multi-user and multi-account access to VipeCloud's API.
   
#### Interested in receiving webhooks?
   * Learn about our webhooks API: [Webhooks v1.0](webhooks_v1_0.md)

#### Responses
   * 200 for success
   * 422 for incorrect post
   * 500 which is most likely a VipeCloud error


<a name="#authentication"></a>Authentication
-------------
For individual user access Authorization is a Basic header of a base64 encoded concatenation of your VipeCloud user email and an active user API Key. API keys can be managed in the Setup section of VipeCloud.

Sample PHP curl header to add to your POST
```php
<?php

$auth = base64_encode($user_email.":".$api_key);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth", "Accept: application/json"));

?>
```

#### Agencies and large accounts
  * Agencies and large accounts authenticate by adding an {api_key} route and a signature via a signature based on a shared secret. 
  * The signature is a base64 encoded concatenation of the request path based on the shared secret (E.g. https://v.vipecloud.com/api/v3.1/{agency_api_slug}/users/{api_key}?timestamp=TIMESTAMP).
  * The {api_key} is the individual user's API Key which is generated in their VipeCloud account.
  * All calls must have the signature appended in order to authenticate. E.g. /users/API_KEY?timestamp=TIMESTAMP&signature=SIGNATURE

Sample PHP code to create the signature
```php
<?php

function generateSignature($action,$api_key){
    $path = "https://v.vipecloud.com/api/v3.1/{agency_api_slug}/" . $action . "/" . $api_key . "?timestamp=".time();
    $signature = hash_hmac('sha256', $path, SHARED_SECRET, $raw=true);
    $encoded_sig = base64_encode(strtr($signature, '-_','+/')); 
    return array('signature' => $encoded_sig);
}

?>
```

<a name="#users"></a>Users (GET)
-------------
### GET all active users within your account

```
GET /users
``` 
The response to this GET will be a list of the currently active VipeCloud users for an account within your integration. Users displayed are based on the visibility permission of the authenticated user. For example, an Admin user will see all users, a Manager will see their team members, and a Member will only see themselves.
```
{
  "account_id" : "123",
  "users" : [
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
POST /contacts
```

Body params
```   
{ 
 "first_name" : "Road", //required
 "last_name" : "Runner", 
 "email" : "email", //required
 "website" : "www.acme.com", 
 "work_phone" : "1234567890", 
 "mobile_phone" : "1234567890", 
 "company_name" : "Acme", 
 "tags" : ["Speedy"], 
 "address1" : "123 Acme Street", 
 "address2" : "", 
 "city" : "Disneyland", 
 "state" : "CA", 
 "zip" : "12345", 
 "country" : "USA", 
}
```

#### GET list of existing Contacts a user has in VipeCloud
```
GET /contacts
```
Returns an array of the contacts that a user has in VipeCloud
```   
{ 
  [
    { 
     "first_name" : "Road", 
     "last_name" : "Runner", 
     "email" : "email", 
     "website" : "www.acme.com", 
     "work_phone" : "1234567890", 
     "mobile_phone" : "1234567890", 
     "company_name" : "Acme", 
     "tags" : ["Speedy"], 
     "address1" : "123 Acme Street", 
     "address2" : "", 
     "city" : "Disneyland", 
     "state" : "CA", 
     "zip" : "12345", 
     "country" : "USA", 
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
POST /contact_list
```
If a list key is present, contacts will be added to that list. If no list key is present, a list name must be present. Creating an "empty" list - a list with a list_name and no contacts is allowed. VipeCloud will check for and not add duplicates to this list. VipeCloud will also not add contacts that have unsubscribed from this user or bounced.

If you use a deal crm_obj and obj_id we will attribute the email activity to the appropriate crm_obj and save your API a call by not needing to search for the obj id.

NOTE - if the number of contacts being submitted is greater than 10,000 then submit a link to a CSV file.
```   
{ 
 "list_key" : "LIST_KEY/FALSE",
 "list_name" : "LIST_NAME",
 "csv_url" : "{url to csv file}", //only for POSTs with greater than 10,000 contacts
 "contacts" : [
    {
      "first_name"  : "Wiley",  // required
      "email" : "wiley.coyote@acme.com", // required
      "last_name" : "Coyote",
      "phone" : "1234567890",
      "company" : "Acme",
      "crm_obj" : "123XYZ",
      "obj_id" : "123XYZ"
    },
    {
    ...
    }
 ]
}
```
The response to this POST will be a status of success or error. On success the list_key will be included in addition to a *count* of successful emails added to the list and a *list* of emails that were not added, with a message detailing why the contact wasn't added.

NOTE - contacts not added will be displayed in contact list performance as well.
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
GET /contact_list
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

<a name="#user-reputation-get"></a>User Reputation (GET)
-------------------------------------
Access a user's email sending reputation in VipeCloud

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


<a name="#trigger-trackable-email-post"></a>Trigger Trackable Email (POST)
-------------
Send a trackable email via a POST. 

```
POST /trigger_trackable_email
``` 

Body params

```   
{
  "emails":"roadrunner@acme.com", //comma or semicolon separated string of emails
  "cc_emails":"wile.e.coyote@acme.com", //comma or semicolon separated string of emails
  "person_id":"ABC123", //optional param used to write share and engagement activities
  "deal_id":"XYZ789", //optional param used to write share and engagement activities. person_id req'd for deal_id to work
  "subject":"Can you point me in the right direction?", //req'd if no email_template_id
  "message":"<p>Dear Wiley,</p><p>Good luck catching me.</p><p>-Road Runner</p>", //req'd if no email_template_id
  "open_alert": 1 //can be 1 (yes open alert) or 0 (no open alert). Defaults to 0 if not provided.
}
```
A sample 200 response from above would look like:
```
{"emails":[{"email":"roadrunner@acme.com","status":"correct"},{"email":"wile.e.coyote@acme.com","status":"correct"}]}
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


