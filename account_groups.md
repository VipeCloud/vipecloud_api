Account Groups (GET / POST / PUT / PATCH / DELETE)
-------------
Create, update, retrieve, and delete Account Groups. Only works for Admin users.

#### Create Account Group
```
POST /account_groups
```

#### Update Account Group
```
PUT /account_groups/:id
PATCH /account_groups/:id
```

The /social_account_groups endpoint is still supported for backwards compatibility.

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
GET /account_groups
GET /account_groups/:id
```
GET a list of all your Account Groups, search for Account Groups by name (query param), or get a specific Account Group by id. If no Account Group is found, the response will be code 422 with the message "No Account Group was found."

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

#### DELETE Account Group
```
DELETE /account_groups/:id
```
Delete an account group by ID. Returns status of success upon successful deletion.
