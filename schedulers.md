Schedulers (GET / POST / PUT / PATCH / DELETE)
-------------------------------------

Schedulers allow contacts to book meetings with you based on your availability. They integrate with VipeCloud, Google, or Microsoft 365 calendars.

#### Create new Schedulers in VipeCloud
```
POST /schedulers
```
When POSTing to /schedulers, the body should contain the scheduler configuration. Required fields are `name` and `slug` (must be unique).

Sample post body below.
```
{
   "name" : "My Meeting Scheduler", // required - internal name
   "slug" : "my-meeting", // required - unique URL slug
   "title" : "Book a Meeting with Me", // display title
   "is_active" : true, // whether scheduler is active
   "calendar_type" : "vipecloud", // vipecloud, google, or microsoft365
   "calendar_id" : 123, // ID of the calendar to use
   "event_type" : "one_on_one", // one_on_one or group
   "duration" : 30, // meeting duration in minutes
   "daily_availability" : { // availability by day of week
      "monday" : {
         "start" : "09:00",
         "end" : "17:00",
         "breaks" : [
            { "start" : "12:00", "end" : "13:00" }
         ]
      },
      "tuesday" : {
         "start" : "09:00",
         "end" : "17:00"
      }
      // ... other days
   },
   "minimum_notice_check" : "on", // on or off
   "notice_amount" : 24,
   "notice_type" : "hours", // minutes, hours, or days
   "buffer_time_check" : "on", // on or off
   "buffer_before" : 15, // buffer before meetings (minutes)
   "buffer_after" : 15, // buffer after meetings (minutes)
   "daily_limit" : 5, // max meetings per day, or "not_set"
   "schedule_option" : "set_days", // set_days or date_range
   "days_dropdown" : 30, // days into future (for set_days)
   "start_date" : "2024-01-01", // start date (for date_range)
   "end_date" : "2024-12-31" // end date (for date_range)
}
```

Sample success response:
```
{
   "status" : "success",
   "id" : 123,
   "slug" : "my-meeting",
   "url" : "https://v.vipecloud.com/schedulers/view/my-meeting"
}
```

#### Update existing Schedulers in VipeCloud
```
PUT /schedulers/:id
PATCH /schedulers/:id
```
To update an existing scheduler, use PUT or PATCH with the scheduler ID in the URL path. You can provide only the fields you want to change.

Sample update body:
```
{
   "name" : "Updated Meeting Name",
   "is_active" : false,
   "duration" : 60
}
```

Sample success response:
```
{
   "status" : "success",
   "id" : 123,
   "slug" : "my-meeting",
   "url" : "https://v.vipecloud.com/schedulers/view/my-meeting"
}
```

#### GET Schedulers
```
GET /schedulers(/:id)
```
If no id, returns an array of the schedulers for the authenticated user. If id, returns the details for a specific scheduler.

**Query Parameters:**
- `query` - Search filter for scheduler name or title
- `page` - Page number for pagination (default: 1)
- `length` - Number of items per page (default: 20, max: 1000)
- `sort_by` - Field name to sort by (e.g., 'id', 'name', 'create_date', 'update_date')
- `sort_direction` - Sort direction: 'ASC' or 'DESC' (default: 'DESC')

```
E.g. GET /schedulers?query=meeting&page=1&length=10
```

Sample response for list:
```
[
   {
      "id" : 123,
      "name" : "My Meeting Scheduler",
      "slug" : "my-meeting",
      "title" : "Book a Meeting with Me",
      "is_active" : 1,
      "calendar_type" : "vipecloud",
      "calendar_name" : "My Calendar (VipeCloud)",
      "event_type" : "one_on_one",
      "duration" : 30,
      "url" : "https://v.vipecloud.com/schedulers/view/my-meeting",
      "daily_availability" : { ... },
      "create_date" : "2024-01-15 10:30:00",
      "update_date" : "2024-01-20 14:45:00"
   },
   {
      ...
   }
]
```

Sample response for single scheduler:
```
{
   "id" : 123,
   "name" : "My Meeting Scheduler",
   "slug" : "my-meeting",
   "title" : "Book a Meeting with Me",
   "is_active" : 1,
   "calendar_type" : "vipecloud",
   "calendar_id" : 456,
   "calendar_name" : "My Calendar (VipeCloud)",
   "event_type" : "one_on_one",
   "duration" : 30,
   "url" : "https://v.vipecloud.com/schedulers/view/my-meeting",
   "daily_availability" : {
      "monday" : { "start" : "09:00", "end" : "17:00" },
      "tuesday" : { "start" : "09:00", "end" : "17:00" }
   },
   "minimum_notice_check" : "on",
   "notice_amount" : 24,
   "notice_type" : "hours",
   "buffer_time_check" : "on",
   "buffer_before" : 15,
   "buffer_after" : 15,
   "daily_limit" : 5,
   "schedule_option" : "set_days",
   "days_dropdown" : 30,
   "create_date" : "2024-01-15 10:30:00",
   "update_date" : "2024-01-20 14:45:00"
}
```

#### GET Scheduler Availability
```
GET /schedulers/:slug/availability
```
Returns available time slots for a scheduler based on the scheduler's configuration, the owner's calendar, and any existing events.

Sample response:
```
{
   "status" : "success",
   "timezone" : "America/New_York",
   "minimum_notice" : 24,
   "minimum_notice_unit" : "hours",
   "daily_limit" : 5,
   "buffer_before" : 15,
   "duration" : 30,
   "buffer_after" : 15,
   "available_time_slots" : {
      "2024-01-22" : [
         { "start" : "09:00", "end" : "09:30" },
         { "start" : "09:30", "end" : "10:00" },
         { "start" : "10:00", "end" : "10:30" }
      ],
      "2024-01-23" : [
         { "start" : "09:00", "end" : "09:30" },
         { "start" : "14:00", "end" : "14:30" }
      ]
   }
}
```

#### DELETE Schedulers
```
DELETE /schedulers/:id
```
Deletes a scheduler and its associated automations. If the scheduler cannot be found, or has already been deleted, a 404 will be returned.

Example error response:
```
{
   "status" : "error",
   "message" : "Scheduler not found."
}
```

Example success response:
```
{ "status" : "success" }
```

#### Scheduler Fields Reference

| Field | Type | Description |
|-------|------|-------------|
| id | integer | Unique scheduler ID |
| name | string | Internal name for the scheduler |
| slug | string | Unique URL slug |
| title | string | Display title shown to contacts |
| is_active | boolean | Whether the scheduler is active |
| calendar_type | string | Calendar type: vipecloud, google, or microsoft365 |
| calendar_id | integer | ID of the connected calendar |
| calendar_name | string | Display name of the calendar |
| event_type | string | Meeting type: one_on_one or group |
| duration | integer | Meeting duration in minutes |
| daily_availability | object | Availability settings by day of week |
| minimum_notice_check | string | Whether minimum notice is enabled (on/off) |
| notice_amount | integer | Minimum notice amount |
| notice_type | string | Minimum notice unit: minutes, hours, days |
| buffer_time_check | string | Whether buffer time is enabled (on/off) |
| buffer_before | integer | Buffer time before meetings (minutes) |
| buffer_after | integer | Buffer time after meetings (minutes) |
| daily_limit | integer/string | Max meetings per day, or "not_set" |
| schedule_option | string | Date mode: set_days or date_range |
| days_dropdown | integer | Days into future (for set_days) |
| start_date | string | Start date YYYY-MM-DD (for date_range) |
| end_date | string | End date YYYY-MM-DD (for date_range) |
| url | string | Full URL to the scheduler booking page |
| create_date | datetime | When the scheduler was created |
| update_date | datetime | When the scheduler was last updated |
