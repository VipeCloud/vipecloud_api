User Reputation (GET)
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
