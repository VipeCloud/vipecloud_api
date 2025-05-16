User Auth (POST / GET)
-------------
By sending in the authentication token to this endpoint, it will return the user associated with the token, or failure if the token is incorrect/invalid. Both POST and GET requests return the same information.

#### POST/GET User Auth
```
POST/GET /user_auth
```

Sample Header:

Authorization: Bearer ymd40a2.....

#### Responses

##### Sample Success
```   
{ 
    "status":"success",
    "userEmail": "example@vipecloud.com"
}
```

##### Sample Failures
```   
{ 
    "status":"error",
    "message": "Your VipeCloud account is inactive. Please activate to use our API."
}
```
```   
{ 
    "status":"error",
    "message": "Your VipeCloud account is not yet authorized to use our API..."
}
```
```   
{ 
    "status":"error",
    "message": "The User USER_EMAIL was not authenticated."
}
```
