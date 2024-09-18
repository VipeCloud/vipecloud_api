Account Groups (POST / GET)
-------------
Create, update, and retrieve Account Groups. Only works for Admin users. 

```
POST /account_groups(/:id)
``` 
The /social_account_groups(/:id) endpoint is still supported.

Attribute | type | required | description
--- | --- | --- | ---
id | integer | yes if updating, no if creating new | The id of the Account Group.
group_name | string | yes | The name of the Account Group.


Sample POST body

```   
{
  'group_name' : 'All Social Accounts'
}
```

Sample response
```
{
    'id' : 123
    'group_name' : 'All Social Accounts',
    'create_date' : '2021-09-14 21:45:44'
}
```

#### GET Account Groups
```
GET /account_groups/:id
```
GET a list of your Account Groups, search for a Account Groups by name, or a get specific Account Groups by id. If no Account Group is found, the response will be code 422 with the message "No Account Group was found." (The /social_account_groups(/:id) endpoint is still supported.)

Sample responses
```   
GET /account_groups
{ 
  [
    "0" : [
      'id' : 123
      'group_name' : 'All Social Accounts',
      'create_date' : '2021-09-14 21:45:44'
    ]
  ]
}

GET /account_groups?query=social
{ 
  [
    "0" : [
      'id' : 123
      'group_name' : 'All Social Accounts',
      'create_date' : '2021-09-14 21:45:44'
    ]
  ]
}

GET /account_groups/123
{ 
  'id' : 123
  'group_name' : 'All Social Accounts',
  'create_date' : '2021-09-14 21:45:44'
}

```
