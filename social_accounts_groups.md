Social Account Groups (POST / GET)
-------------
Create, update, and retrieve Social Account Groups. Only works for Admin users. 

```
POST /social_account_groups(/:id)
``` 


Attribute | type | required | description
--- | --- | --- | ---
id | integer | yes if updating, no if creating new | The id of the Social Account Group.
group_name | string | yes | The name of the Social Account Group.


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

#### GET Social Account Groups
```
GET /social_account_groups/:id
```
GET a list of your Social Account Groups, search for a Social Account Groups by name, or a get specific Social Account Groups by id. If no Social Account Groups is found, the response will be code 422 with the message "No Social Account Groups was found."

Sample responses
```   
GET /social_account_groups
{ 
  [
    "0" : [
      'id' : 123
      'group_name' : 'All Social Accounts',
      'create_date' : '2021-09-14 21:45:44'
    ]
  ]
}

GET /social_account_groups?query=social
{ 
  [
    "0" : [
      'id' : 123
      'group_name' : 'All Social Accounts',
      'create_date' : '2021-09-14 21:45:44'
    ]
  ]
}

GET /social_account_groups/123
{ 
  'id' : 123
  'group_name' : 'All Social Accounts',
  'create_date' : '2021-09-14 21:45:44'
}

```
