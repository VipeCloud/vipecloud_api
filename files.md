Files (POST / GET)
-------------
Add, update, and retrieve files from your user accounts. If you are migrating from another system and have more files than you can manually transfer over (images, videos, documents, etc.), you can import them using the /files endpoint.

#### POST File(s)

```
POST /files(/:id)
``` 

Sample body when *creating* a new file

```   
{
  "file_url" : "https://www.linktofile.com", //required
  "file_name" : "My File", //required
  "thumb_url" : "https://whatever.com" //required if importing a video
}
```

Sample response
```
{
    "status": "success",
    "id" : "123",
    "download_link": "link goes here",
    "trackable_link": "link goes here" //this is a trackable VipeCloud link
    "thumb_url": "link goes here" //only included for video file uploads
}
```

Sample body when *updating* an existing file (e.g. POST to /files/123 ). You can update the file_name or tag_ids associated with a file.

```   
{
  "file_name" : "My File",
  "tag_ids" : ["1","2"]
}
```

Sample response
```
{
    "id": "123",
    "file_name" : "My File",
    "download_link": "link goes here",
    "trackable_link": "link goes here" //this is a trackable VipeCloud link
    "create_date": "2020-01-28 23:03:23", 
    "tag_ids" : ["1","2"]
}
```



#### GET File
```
GET /files(/:id)
```
Retrieve files by id or search your account for files by file_name (url encoded) or tag_id. Responses are limited to a maximum of 50 files. If no file is found, the response will be code 422 with the message "No file was found."

Sample response to get file by id. GET /files/123
```   
{ 
    "id": "123",
    "file_name" : "My File",
    "download_link": "link goes here",
    "trackable_link": "link goes here" //this is a trackable VipeCloud link
    "create_date": "2020-01-28 23:03:23", 
    "tag_ids" : ["1","2"]
}
```

Sample response to get files by file_name (url encoded) or tag_id. Array of files is returned. GET /files?file_name=My%20File or /files?tag_id=1
```   
{ 
    [
      "id": "123",
      "file_name" : "My File",
      "download_link": "link goes here",
      "trackable_link": "link goes here" //this is a trackable VipeCloud link
      "create_date": "2020-01-28 23:03:23", 
      "tag_ids" : ["1","2"]
    ],...
}
```
