VipeCloud API
=============

Overview
-------------
#### What can VipeCloud's API do for you?
   * Connect proprietary or other 3rd party systems to VipeCloud

#### Current Version
   * v3.1

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


Authentication
-------------
Authorization is a Basic header of a base64 encoded concatenation of your VipeCloud user email and an active user API Key. API keys are managed in the Setup section of your VipeCloud account.

Sample PHP curl header to add to your POST
```php
<?php

$auth = base64_encode($user_email.":".$api_key);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth", "Accept: application/json"));

?>
```


Next Step: [Summary of Endpoints](SUMMARY.md)







