Account Group Post (GET / POST / PUT / PATCH / DELETE)
-------------
Create, update, retrieve, and delete account group posts.

#### Create Account Group Post
```
POST /account_group_post
```

#### Update Account Group Post
```
PUT /account_group_post/:id
PATCH /account_group_post/:id
```

Attribute | type | required | description 
--- | --- | --- | --- 
account_group_id | integer | yes | The id for the account group of the user to make the post (social_group_id is still accepted).
comment_data | array | yes | The comment_data array can support three keys: comment (string), images (array of URL strings), and link (string). It is required to have at least one of the comment or the images keys in your post.
schedule_data | array | yes | The schedule_data array requires four keys: scheduled_date (string, formatted YYYY-MM-DD), hour (string, no leading zero), min (string, no leading zero), and ampm (string).

Sample body when creating an Account Group Post. (The /social_group_post endpoint is still supported.)

```   
{
    "account_group_id" : 123, //social_group_id is still supported
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


#### GET Account Group Post
```
GET /account_group_post
GET /account_group_post/:id
```
Retrieve the most recent 50 account group posts, or a single account group post by id. (The /social_group_post endpoint is still supported.)
```   
{ 
    [
      {
        "account_group_id" : 123,
        "social_group_id" : 123, //still supported
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

#### DELETE Account Group Post
```
DELETE /account_group_post/:id
```
Delete a account group post by id. (The /social_group_post endpoint is still supported.)
```   
{ 
    "status" : "success"
}
```
