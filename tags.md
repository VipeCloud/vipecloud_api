Tags (GET / POST / PUT / PATCH / DELETE)
-------------
Create, update, retrieve, and delete tags for your users. Tags utilized via the API can be attributed to files.

#### Create Tag
```
POST /tags
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

#### Update Tag
```
PUT /tags/:id
PATCH /tags/:id
```

Sample body when *updating* an existing tag. You can update the tag_name.

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
GET /tags
GET /tags/:id
```
Retrieve a tag by id or all tags. If no tag is found, the response will be code 422 with the message "No tag was found."

**Pagination and Sorting:** When retrieving all tags (no id), you can use `page`, `length`, `sort_by`, and `sort_direction` query parameters to paginate and sort results. See [README](README.md#pagination-and-sorting) for details.

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
DELETE /tags/:id
```
Delete a tag by id. If no tag is found, the response will be code 422 with the message "No tag was found."

Sample response to delete tag by id. DELETE /tags/123
```   
{     
    "status" : "success
}
```
