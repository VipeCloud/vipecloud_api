
VipeCloud API for Users v2.0
=============

1. [Overview](#overview)
2. [Authentication](#authentication)

Back end enpoints
-------------
1. [Log Completed Task (POST)](#log-completed-task-post)
2. [Create Contact (POST)](#create-contact-post)



<a name="#overview"></a>Overview
-------------
#### What can VipeCloud's API do for you?
   * Connect your ecosytem to VipeCloud. 
   * Add tasks from trigger events in other systems you use and create contacts (which will also automatically create companies).

#### General Information
   * All requests must by made from HTTPS
   * All data sent should be JSON encoded (all data received will be JSON encoded)
   * Base URL for these functions: https://v.vipecloud.com/api/v2.0/vipecloud

#### Responses
   * 200 for success
   * 422 for incorrect post
   * 500 which is most likely a VipeCloud error


<a name="#authentication"></a>Generating the Signature
-------------
The signature is an Authorization header of a base64 encoded concatenation of your VipeCloud username and password.


Sample PHP code to generate the signature
```php
<?php

function generateSignature($action,$api_key){
    $path = "https://v.vipecloud.com/api/v1.0/{partner_api_slug}/" . $action . "/" . $api_key . "?timestamp=".time();
    $signature = hash_hmac('sha256', $path, SHARED_SECRET, $raw=true);
    $encoded_sig = base64_encode(strtr($signature, '-_','+/')); 
    return array('signature' => $encoded_sig);
}

?>
```

<a name="#log-completed-task-post"></a>Log Completed Task (POST)
-------------
### POST - Create task

```
POST /users/API_KEY?timestamp=TIMESTAMP&signature=SIGNATURE
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


<a name="#create-contact-post"></a>Create Contact (POST)
-------------------------------------
VipeCloud supports creating new contact lists and adding contacts to existing lists (while not duplicating contacts in a list). This endpoint can be implemented in several ways:
* 1. Users create a named list in VipeCloud and they can send to that list at a later time or the Send Trackable Email modal can be launched on successful completion of the list creation to send a mail merge right then.
* 2. If a user has an existing list they can add contacts to an existing list, and even have the share modal launched to send a mass email to the newly updated list.
* 3. Keep users inside of your application for mass emailing! 


#### Create new / update existing Contact List in VipeCloud
```
POST /contact_list/API_KEY?timestamp=TIMESTAMP&signature=SIGNATURE
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
