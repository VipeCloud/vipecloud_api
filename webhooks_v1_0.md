
VipeCloud Webhooks v1.0
=============

1. [Overview](#overview)
2. [Webhook Events](#events)
3. [Configuring Your Settings](#configure)
4. [Receiving Webhooks](#receiving)
5. [Responding To Webhooks](#responding)
6. [Verifying Webhooks](#verify)


<a name="#overview"></a>Overview
-------------
Use webhooks to be notified about events that happen in your VipeCloud account. 
Our v1.0 webhook api currently supports all email events in your account.


<a name="#events"></a>Webhook Events
-------------
Below is a list of the webhook events we current support.
  * email_delivered
  * email_open
  * email_click
  * email_bounce
  * email_unsubscribe

Sample webhook event payload
```   
{
  "vc_user" : "road.runner@acme.com",
  "vc_share" : "1234567890",
  "vc_event" : "evt_abcdefghijklmkop",
  "event_type" : "email_open",
  "email_to" : "wile.e.coyote@acme.com",
  "email_from" : "road.runner@acme.com",
  "email_subject" : "Beep Beep",
  "timestamp" : "123456789", //NOTE - this parameter is appended to your webhook URL and not in the POST body itself
  "first_open" => 1,  //NOTE - this parameter is only included for email_open events
  "url" : "www.acme.com", //NOTE - this parameter is only included for email_click events
  "content_title" : "Acme Website" //NOTE - this parameter is only included in a click on an email attachment
}
```

<a name="#configure"></a>Configure Your Settings
-------------
Webhooks are setup in the <a href="https://v.vipecloud.com/account_settings/webhooks">Webhooks</a> 
sub-section of your Account Settings. Add your endpoint and test with our sample payload send.

<a name="#receiving"></a>Receiving Webhooks
-------------
Webhook data is sent as JSON in the POST request body. 
The full event details are included and can be used directly, after parsing the JSON.

<a name="#responding"></a>Responding to Webhooks
-------------
To acknowledge receipt of a webhook, your endpoint should return a 200 HTTP status code. 
Any other information you return in the request headers or request body will be ignored. 
Any response code other than 200 will indicate to VipeCloud that you did not receive the webhook. 
This does mean that a URL redirection or a "Not Modified" response will be treated as a failure.

When a webhook is not successfully received for any reason, VipeCloud will notify you via email. Upon
sending a new successful test webhook, we'll re-send any webhooks which did not receive the 200 response.

<a name="#verify"></a>Verifying Webhooks
-------------
As an extra security measure, we include a signature parameter in the URL we send to your webhook.
The signature is a base64 encoded concatenation of the request path, including the timestamp parameter,
based on the shared secret. (i.e. https://www.acme.com/webhook?timestamp=TIMESTAMP).

Your shared secret can be found here: https://v.vipecloud.com/account_settings/webhook after you setup your webhook url.

Below is sample PHP code to generate the signature. Compare the signature we send with the one you generate
to verify the event.
```php
<?php

function authenticateSignature($app){
  $path = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]"; //sample might be https://www.acme.com?timestamp=123456789&signature=ASDFGHJKL
  $clean_path = explode("&", $path); //remove the signature from the URL
  $encoded_sig = $app->request()->params('signature');
  $expected_sig = hash_hmac('sha256', $clean_path[0], $secret,$raw=true); //use the shared secret we provide in your Webhook settings
  $decoded_enc_sig = base64_decode(strtr($encoded_sig, '-_','+/')); 
  if($expected_sig === $decoded_enc_sig){
    return true;
  }
  else{
    return false;
  }
}

?>
```
