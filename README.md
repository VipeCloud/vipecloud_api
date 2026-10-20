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
   * 403 when a limited API key is used outside its limits (see [Key limits](#key-limits))
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

Key limits
-------------
An API key can be limited to some of what its user can do: open **Limits** next to the key (Setup → Integrations → API keys), tick the permissions the key needs, and choose how widely it may use each one. A key can never do more than its user. A key with no limits keeps everything its user can do.

A limited key used outside its limits returns **403**:

```json
{
   "status" : "error",
   "message" : "This API key is not allowed to do this. Ask the key's owner to widen its limits."
}
```

Reading an endpoint needs its view permission (for example *View contacts* for `GET /contacts`); creating, changing or deleting needs its change permission (*Edit contacts*), and sending needs the matching send permission (*Send email* for `POST /emails`). `GET /me` is always allowed.


Next Step: [Summary of Endpoints](SUMMARY.md)







