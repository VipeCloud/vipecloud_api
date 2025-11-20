Users (GET)
-------------
**Note:** In v4.0, the Users endpoint only supports GET operations. POST and PUT operations for user management are no longer available via the API.

### GET all active users within your account

```
GET /users
``` 
The response to this GET will be a list of the currently active VipeCloud users in your account. Users displayed are based on the visibility permission of the authenticated user. For example, an Admin user will see all your account users, a Manager will see their team members, and a Member will only see themselves.
```
{ 
  [
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

```
GET /users/:id
```

Getting an individual user will return a single user's information if the calling user has the authorization to view that record. Additionally, the "digital_business_card_link" parameter will be returned if the card is configured and active.
```
{ 
  "first_name"  : "Wiley", 
  "last_name"  : "Coyote", 
  "email"  : "wiley.coyote@acme.com",
  "phone"  : "123-456-7890",
  "company_name" : "Acme",
  "api_keys" : ["123456XYZ"],
  "user_role" : 'Admin'
  "digital_business_card_link" : "https://v.vipecloud.com/bcard/XXXX/first_name" //Link to the user's digital business card (if available)
}

```

### POST create a new user

**DEPRECATED in v4.0:** This endpoint is no longer available in API v4.0.

<details>
<summary>Legacy v3.1 documentation (not available in v4.0)</summary>

NOTE: this endpoint was in beta and required invite-only access

```
POST /users
```
Only authenticated Admin users could use this endpoint.

Attribute | type | required | description
--- | --- | --- | ---
first_name | string | yes | First name.
last_name | string | yes | Last name.
email | string | yes | Email address (this is the email the user will send email from).
phone | string | yes | Phone office.
company_name | string | yes | Company name.
user_role | enum (Admin,Manager,Member) | yes | The role the new user will have within your account.

Sample post body:
```
{ 
  "first_name"  : "Wiley", 
  "last_name"  : "Coyote", 
  "email"  : "wiley.coyote@acme.com",
  "phone"  : "123-456-7890",
  "company_name" : "Acme",
  "user_role" : 'Member'
}
```

Sample 200 response:
```
{ 
  "first_name"  : "Wiley", 
  "last_name"  : "Coyote", 
  "email"  : "wiley.coyote@acme.com",
  "phone"  : "123-456-7890",
  "company_name" : "Acme",
  "user_role" : 'Member',
  "api_keys" : ["123456XYZ"] //save this to authenticate directly into this user's account
}
```

</details>

### PUT update a user

**DEPRECATED in v4.0:** This endpoint is no longer available in API v4.0.

<details>
<summary>Legacy v3.1 documentation (not available in v4.0)</summary>

NOTE: this endpoint was in beta and required invite-only access

```
PUT /users
``` 

Attribute | type | required | description
--- | --- | --- | ---
first_name | string | no | First name.
last_name | string | no | Last name.
email | string | no | Email address (this is the email the user will send email from).
phone | string | no | Phone office.
company_name | no | yes | Company name.
user_role | enum (Admin,Manager,Member) | no | The role the new user will have within your account.

Sample body:
```
{ 
  "first_name"  : "Elmer" 
}
```

Sample 200 response:
```
{
  "first_name"  : "Elmer",
  "last_name"  : "Coyote",
  "email"  : "wiley.coyote@acme.com",
  "phone"  : "123-456-7890",
  "company_name" : "Acme",
  "user_role" : 'Member',
  "api_keys" : ["123456XYZ"] //save this to authenticate directly into this user's account
}
```

</details>
