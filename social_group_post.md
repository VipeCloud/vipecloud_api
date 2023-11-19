Social Group Post (POST / GET / DELETE)
-------------
Create, update, retrieve, and delete social group posts for groups of your users' social accounts.

#### POST Social Group Post

Attribute | type | required | description 
--- | --- | --- | --- 
social_group_id | integer | yes | The id for the social group of the user to make the post.
comment_data | array | yes | The comment_data array can support three keys: comment (string), images (array of URL strings), and link (string). It is required to have at least one of the comment or the images keys in your post.
schedule_data | array | yes | The schedule_data array requires four keys: scheduled_date (string, formatted YYYY-MM-DD), hour (string, no leading zero), min (string, no leading zero), and ampm (string).

```
POST /social_group_post(/:id)
``` 
Sample body when creating a Social Group Post.

```   
{
    "social_group_id" : 123,
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


#### GET Social Group Post
```
GET /social_group_post(/:id)
```
Retrieve the most recent 50 social group posts or a single social group post by id.
```   
{ 
    [
      {
        "social_group_id" : 123,
        "comment_data" : [
            "comment" : "Hello World!",
            "images" : [],
            "link" : ""
        ],
        "account_data" : [
            "group_ids" : ["1"],
            "account_ids" : ["1"]
        ]
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

#### DELETE Social Group Post
```
DELETE /social_group_post/:id
```
Delete a social group post by id.
```   
{ 
    "status" : "success"
}
```
