Unsubscribes (POST)
-------------
Manually add unsubscribed email addresses for a user via a POST. 

#### POST Unsubscribe

```
POST /unsubscribes
``` 

Attribute | type | required | description
--- | --- | --- | ---
email | string | yes | The email address to add to the unsubscribe list for this user.

Sample body. 

```   
{
  "email": "wiley.e.coyote@acme.com"
}
```

Sample 200 response below. Note that if an email is already unsubscribed for this user the response will still be a 200 but the status will say 'already_unsubscribed'.
```   
{
  "email": "wiley.e.coyote@acme.com",
  "status": "success"
}
```
