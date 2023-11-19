Tags (POST / GET / DELETE)
-------------
Create, update, retrieve, and delete tags for your users. Tags utilized via the API can be attributed to files. 

#### POST Tag(s)

```
POST /tags(/:id)
``` 

Sample body when *creating* a new tag

```   
{
  "tag_name" : "My Tag Name", //required
}
```

Sample response
```
{
    "status": "success",
    "tag_id" : "123"
}
```

Sample body when *updating* an existing tag (e.g. POST to /tags/123 ). You can update the tag_name.

```   
{
  "tag_name" : "My Tag Name 2", //required
}
```

Sample response
```
{
    "status": "success",
    "tag_id" : "123",
    "tag_name" : "My Tag Name 2"
}
```

#### GET Tag
```
GET /tags(/:id)
```
Retrieve tags by id or as a group. If no tag is found, the response will be code 422 with the message "No tag was found."

Sample response to get tag by id. GET /tags/123
```   
{     
    "tag_id" : "123",
    "tag_name" : "My Tag Name 2"
}
```

Sample response to get tags with no id. Array of tags is returned. GET /tags
```   
{ 
    [
      "id": "123",
      "tag_name" : "My Tag Name 2"
    ],...
}
```

#### DELETE Tag
```
DLETE /tags/:id
```
Delete tag by id. If no tag is found, the response will be code 422 with the message "No tag was found."

Sample response to delete tag by id. DELETE /tags/123
```   
{     
    "status" : "success
}
```
