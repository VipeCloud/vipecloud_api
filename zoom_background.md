Zoom Background (POST)
-------------
Create and upload custom virtual backgrounds with a QR Code to your Zoom account. 

NOTE: This endpoint is in BETA and requires invite only access.

#### POST Zoom Background

```
POST /zoom_background
``` 

Attribute | type | required | description
--- | --- | --- | ---
title | string | yes | The title of the image to add to your Zoom virtual background library.
action | enum (create_preview, upload_image_url) | yes | Which virtual background action is this endpoint taking?
image_url | string | yes | The image you want to use as your background. Will be cropped to 16x9 if it is not submitted in those dimensions.
url | string | no | The url destination for the QR Code.
url_location | enum (top-right (default), top-left, bottom-right, bottom-left) | yes if url parameter provided | The location of the QR Code on the image
text | array | no | An array of up to 3 strings for 3 rows of text (e.g. Your Name, Your Title, Your Company Name)
text_location | enum (top-right, top-left (default), bottom-right, bottom-left) | yes if text parameter provided | The location of your text on your image.
font_color | enum (white (default) or black) | yes if text parameter provided | The color of the text overlay.



Sample body. 

```   
{
  "title": "My First Background",
  "action" : "create_preview",
  "image_url" : "https://myimageurl.com", //a 16x9 image
  "url" : "https://vipecloud.com",
  "url_location" : "top-right"
}
```

Sample 200 response below.
```   
{
  "status": "success",
  "background_url" : "https://vipecloud.com/your_zoom_virtual_background"
}
```
