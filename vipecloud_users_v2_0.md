
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


<a name="#authentication"></a>Authentication
-------------
The signature is an Authorization header of a base64 encoded concatenation of your VipeCloud username and password.

Sample PHP curl header to add to your POST
```php
<?php

$auth = base64_encode($username.":".$password);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth", "Accept: application/json"));

?>
```

<a name="#log-completed-task-post"></a>Log Completed Task (POST)
-------------

```
POST /tasks
``` 

Body params

```   
{
  "contact_email" : "road.runner@acme.com", //required
  "subject" : "Started Trial", //required
  "activity_type" : "their_activity", //required. Can be their_activity or your_activity
  "details" : "Created trial via website", //optional
  "contact_tags" : ["BusinessTrial","Annual"], //optional. VipeCloud will create a contact if it doesn't exist. Values must be array
  "source" : "Website signup", //optional
  "company_tags" : ["BusinessTrial","Annual"] //optional. VipeCloud will create a company if it doesn't exist. Values must be array
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


<a name="#create-contact-post"></a>Create Contact (POST)
-------------------------------------

```
POST /contacts
```

Body params
```   
{ 
 "first_name" : "Road", //required
 "last_name" : "Runner", //optional
 "email" : "email", //required
 "website" : "www.acme.com", //optional
 "work_phone" : "1234567890", //optional
 "mobile_phone" : "1234567890", //optional
 "company_name" : "Acme", //optional
 "tags" : ["Speedy"], //optional. Array
 "address1" : "123 Acme Street", //optional
 "address2" : "", //optional
 "city" : "Disneyland", //optional
 "state" : "CA", //optional
 "zip" : "12345", //optional
 "country" : "USA", //optional
}
```
