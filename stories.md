Stories (POST / GET)
-------------
Create, update, and retrieve Stories for your users. 

```
POST /stories/:id
``` 

A note on chapters: when sending a POST to a Story, the chapter order is defined by your submitted array of chapters. If you are updating a Story, any previously existing Story chapters not included in your POST will be deleted.


Story Attribute | type | required | description
--- | --- | --- | ---
id | integer | yes if updating, no if creating new | The id of the story.
title | string | yes | The title of your story. Text string.
description | string | yes | The description for your story. HTML allowed. Empty value also allowed.
call_to_action | string | yes | The call to action for your story. HTML allowed. Empty value also allowed (though not suggested).
bg_music | integer | no | The id of the music track.
chapters | array | yes | Your story chapters. See Chapter Attributes below.
update_video | integer | no | Set to 1 if you want to encode the story as a video.

Chapter Attribute | type | required | description
--- | --- | --- | ---
id | integer | yes if updating, no if creating new | The id of the chapter.
type | string | yes | Supported values are file:image or vcvideo.
img_src | string | yes | Link to image or video thumbnail.
display_length | float | yes | Length of time to display this chapter. E.g. 5 or 5.2
asset_key | string | yes if type is vcvideo | The asset_key of the VipeCloud hosted video.


Sample POST body

```   
{
  'title' : 'API Story',
  'description' : 'API story description',
  'call_to_action' : 'Click me now!',
  'chapters' => [
    [
      'type' => 'file:image',
      'img_src' => 'https:\/\/c.vipecloud.com\/view_file\/a91tm36w6nco80844c8cwwk004rxx007kf24g',
      'display_length' => 5
    ]
  ]
}
```

Sample response
```
{
    'id' : 123
    'title' : 'API Story',
    'description' : 'API story description',
    'call_to_action' : 'Click me now!',
    'story_key' : 'ABCDEF12345',
    'video_url' : 'https://v.vipecloud.com/vf/story_video/ABCDEF12345.mp4',
    'bg_music' : 0,
    'chapters' : [
      [
        'chapter_order' : 1,
        'chapter_data' : [
          'type' : 'file:image',
          'img_src' : 'https://myimageurl.com',
          'display_length' : 5
        ]
      ]
    ]
}
```

#### GET Stories
```
GET /stories/:id
```
GET a list of your Stories, search for a Story by title, or a get specific Story by id. If no story is found, the response will be code 422 with the message "No story was found."

A note on access: in the API we only return stories *owned* by the user. We do not return stories the user can access via Shared With Me.

Sample responses
```   
GET /stories
{ 
  [
    "0" : [
      'id' : 123
      'title' : 'API Story',
      'description' : 'API story description',
      'call_to_action' : 'Click me now!'
      'story_key' : 'ABCDEF12345',
      'video_url' : 'https://v.vipecloud.com/vf/story_video/ABCDEF12345.mp4',
      'bg_music' : 0
    ]
  ]
}

GET /stories?query=story
{ 
  [
    "0" : [
      'id' : 123
      'title' : 'API Story',
      'description' : 'API story description',
      'call_to_action' : 'Click me now!',
      'story_key' : 'ABCDEF12345',
      'video_url' : 'https://v.vipecloud.com/vf/story_video/ABCDEF12345.mp4',
      'bg_music' : 0
    ]
  ]
}

GET /stories/123
{ 
  'id' : 123
  'title' : 'API Story',
  'description' : 'API story description',
  'call_to_action' : 'Click me now!',
  'story_key' : 'ABCDEF12345',
  'video_url' : 'https://v.vipecloud.com/vf/story_video/ABCDEF12345.mp4',
  'bg_music' : 0,
  'chapters' : [
    [
      'chapter_order' : 1,
      'chapter_data' : [
        'type' : 'file:image',
        'img_src' : 'https://myimageurl.com',
        'display_length' : 5
      ]
    ]
  ]
}

```
