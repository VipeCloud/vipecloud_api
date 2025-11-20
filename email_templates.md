Email Templates (GET / POST / PUT / PATCH / DELETE)
-------------
If you are migrating from another email sending provider and more email templates than you can manually transfer over, you can import them using the /email_templates endpoint. You can also edit your email templates with a PUT or PATCH, and delete them with DELETE.

#### POST Template
```
POST /email_templates
``` 

Body params

```
{
  "title" : "My Email Template", //required and used to identify the contact
  "subject" : "My Email Subject", //required
  "copy" : "Hi there, this is my email template copy....", //required. HTML is allowed.
  "landing_page" : 1, //optional, can be a 1 or a 0. If a 1 we will respond with a link to the landing page
}
```

Sample response
```
{
    "status": "success",
    "email_template_id": 123,
    "landing_page": "link goes here" //only if you enable a landing page for the email template
}
```

#### PUT/PATCH Template
```
PUT /email_templates/:id
PATCH /email_templates/:id
```

Body params

```   
{
  "title" : "My Email Template", //optional
  "subject" : "My Email Subject", //optional
  "copy" : "Hi there, this is my email template copy....", //optional. HTML is allowed.
  "landing_page" : 1, //optional, can be a 1 or a 0. If a 1 we will respond with a link to the landing page
}
```

Sample response
```
{
    "status": "success",
    "email_template_id": 123,
    "landing_page": "link goes here" //only if you enable a landing page for the email template
}
```

#### GET Template(s)
```
GET /email_templates
```
GET a list of your email templates, search for an email template by title, or a get specific template by id. If no email template is found, the response will be code 422 with the message "No template was found."

A note on access: in the API we only return email templates *owned* by the user. We do not return email templates the user can access via Shared With Me.

Sample responses
```   
GET /email_templates
{ 
  [
    "0" : [
      "email_template_id" : 123
      "title" : "This is my title",
      "update_date" : 2020-01-05 04:00:11
    ]
  ]
}

GET /email_templates?query=title
{ 
  [
    "0" : [
      "email_template_id" : 123
      "title" : "This is my title",
      "update_date" : 2020-01-05 04:00:11
    ]
  ]
}

GET /email_templates/123
{ 
  "email_template_id" : 123
  "title" : "This is my title",
  "subject" : "This is my subject",
  "copy" : "<div>html of your email template here</div>"
}

```

#### DELETE Template
```
DELETE /email_templates/:id
```
Delete an email template by ID. Returns status of success upon successful deletion.
