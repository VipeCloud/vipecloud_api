Social Connect (POST)
-------------
Invite people to authorize you to post to their social accounts on their behalf. The workflow presented here is to 1) send an email notification to a contact to invite them to authorize their social account. 2) They receive the email with the link and authorize. 3) You will receive a notification email upon completion (or failure) of authorization. In your notification there will be a link you can go to in order to select which account to connect. 

NOTE: This can be used as first time authorization AND refreshing existing authorization. 

#### POST Social Connect

```
POST /social_connect
``` 

Attribute | type | required | description
--- | --- | --- | ---
contacts_master_id | integer | yes OR first_name and email | The contact you want to authorize you to post on their behalf.
first_name | string | yes with email OR contacts_master_id | The first name of the contact.
email | string | yes with first_name OR contacts_master_id | The email of the contact.
social_network | string | yes | Only "facebook" is support at this time (by using "facebook", Instagram is by default also support).
email_template_id | integer | no | The email template to send to the contact for authorization. Must include a merge tag link of %SOCIAL_CONNECT_LINK%.
subject | string | no |In lieu of an email_template_id (or using our default template if email_template_id, subject, and message are not provide), this is the subject of the invite email.
message | string | no |In lieu of an email_template_id (or using our default template if email_template_id, subject, and message are not provide), this is the message of the invite email. Must include a merge tag link of %SOCIAL_CONNECT_LINK%.



Sample body. 

```   
{
  "first_name": "Bugs",
  "email" : "bugsbunny@acme.com",
  "social_network" : "facebook"
}
```

Sample 200 response below.
```   
{
  "status": "success"
}
```
