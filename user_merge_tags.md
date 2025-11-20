User Merge Tags (GET / PUT / PATCH)
-------------
Update and retrieve user_merge_tags from your user accounts. Create the user_merge_tag for the account in your parent user account.

#### Update User Merge Tag
```
PUT /user_merge_tags/:id
PATCH /user_merge_tags/:id
```

Sample body when updating a merge tag value.

```   
{
  "value" : "New Value"
}
```

Sample response
```
{
    "id": "123",
    "merge_tag" : "%YOUR_USER_MERGE_TAG%",
    "value" : "New Value"
}
```

#### GET User Merge Tags
```
GET /user_merge_tags
GET /user_merge_tags/:id
```
Retrieve a user merge tag by id, or retrieve a list of all user_merge_tags in your account. 

Sample response to get user_merge_tag by id. GET /user_merge_tags/123
```   
{ 
    "id": "123",
    "merge_tag" : "%YOUR_USER_MERGE_TAG%",
    "value" : "New Value"
}
```

Sample response to get all user merge tags. GET /user_merge_tags
```   
{ 
    [
      "id": "123",
      "merge_tag" : "%YOUR_USER_MERGE_TAG%",
      "value" : "New Value"
    ],...
}
```
