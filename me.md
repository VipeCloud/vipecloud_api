Me (GET)
-------------
### GET the currently authenticated user

```
GET /me
``` 
Returns the currently authenticated user. A the "digital_business_card_link" query parameter can be appended to the call with a value of 1 
in order return a digital_business_card_link for that user if it is configured and active. an activation_link query parameter can also be appended 
to the call with a value of 1 in order to return the 
```
{ 
  "first_name"  : "Wiley", 
  "last_name"  : "Coyote", 
  "email"  : "wiley.coyote@acme.com",
  "phone"  : "123-456-7890",
  "company_name" : "Acme",
  "api_keys" : ["123456XYZ"],
  "user_role" : 'Admin'
  "digital_business_card_link" : "https://example_url.com/bcard/XXXX/first_name" //Link to the user's digital business card (if available, requested, and active)
  "activation_link" : "example_url.com/activation_link_path" // Activiation link if requested and is currently inactive and ready to be activated.
}
```
