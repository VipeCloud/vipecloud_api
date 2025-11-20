Social Post (GET / POST / PUT / PATCH / DELETE)
-------------
Create, update, retrieve, and delete social posts for your users.

#### Create Social Post
```
POST /social_post
```

#### Update Social Post
```
PUT /social_post/:id
PATCH /social_post/:id
```

Attribute | type | required | description 
--- | --- | --- | --- 
social_account_id | integer | yes | The id for the social account of the user to make the post.
comment_data | array | yes | The comment_data array can support three keys: comment (string), images (array of URL strings), and link (string). It is required to have at least one of the comment or the images keys in your post.
schedule_data | array | yes | The schedule_data array requires four keys: scheduled_date (string, formatted YYYY-MM-DD), hour (string, no leading zero), min (string, no leading zero), and ampm (string).

Sample body when creating a Social Post.

```   
{
    "social_account_id" : 123,
    "comment_data" : [
        "comment" : "Hello World!",
        "images" : ["https://www.imagehere.com/my_png.png"],
        "link" : ""
    ],
    "schedule_data" : [
        "scheduled_date" : "2020-03-23",
        "hour" : "5",
        "min" : "0",
        "ampm" : "pm"
    ]
}
```


#### GET Social Post
```
GET /social_post
GET /social_post/:id
```
Retrieve the most recent 50 social posts, or a single social post by id.
```   
{ 
    [
      {
        "social_account_id" : 123,
        "comment_data" : [
            "comment" : "Hello World!",
            "images" : [],
            "link" : ""
        ],
        "schedule_data" : [
            "scheduled_date" : "2020-03-23",
            "hour" : "5",
            "min" : "0",
            "ampm" : "pm"
        ]
      }
    ]
}
```

#### DELETE Social Post
```
DELETE /social_post/:id
```
Delete a social post by id.
```   
{ 
    "status" : "success"
}
```
