Text Templates (GET / POST / PUT / PATCH / DELETE)
-------------
If you are migrating from another text sending provider and have more text templates than you can manually transfer over, you can import them using the /text_templates endpoint.

#### Create Text Template
```
POST /text_templates
```

#### Update Text Template
```
PUT /text_templates/:id
PATCH /text_templates/:id
``` 

Body params

```
{
  "title" : "My Text Template", //required and used to identify the contact
  "message" : "Hi there, this is my text template mesage....", //required. HTML is *not* allowed.
  "images" : ["https://imageurl1.com","https://imageurl2.com"], //optional, array of image urls
}
```

Sample response
```
{
    "status": "success",
    "text_template_id": 123
}
```

#### GET Templates(s)
```
GET /text_templates
```
GET a list of your text templates, search for an text template by title, or a get specific template by id. If no  template is found, the response will be code 422 with the message "No template was found."

A note on access: in the API we only return text templates *owned* by the user. We do not return email templates the user can access via Shared With Me.

Sample responses
```   
GET /text_templates
{ 
  [
    "0" : [
      "text_template_id" : 123
      "title" : "This is my title",
      "update_date" : 2020-01-05 04:00:11
    ]
  ]
}

GET /text_templates?query=title
{ 
  [
    "0" : [
      "text_template_id" : 123
      "title" : "This is my title",
      "update_date" : 2020-01-05 04:00:11
    ]
  ]
}

GET /text_templates/123
{ 
  "text_template_id" : 123
  "title" : "This is my title",
  "message" : "text of your text template here",
  "images" : ["https://imageurl1.com","https://imageurl2.com"], //array of image urls
}

```

#### DELETE Text Template
```
DELETE /text_templates/:id
```
Delete a text template by ID. Returns status of success upon successful deletion.
