Suites / Communities (GET)
-------------
Retrieve Suites and Communities (also known as Parties) from your account. Suites and Communities can be used as triggers for automations via the `suite_party_joined` trigger type.

**Note:** This endpoint is also accessible via `/communities` as an alias.

#### GET Suites
```
GET /suites(/:id)
```
Retrieve a specific suite/community by id or retrieve a list of all suites/communities in the account.

**Query Parameters:**

Parameter | type | required | description
--- | --- | --- | ---
type | enum | no | Filter by type: "suite" (or "community"), "party". If not specified, returns all types.


#### Get a specific suite by ID
```
GET /suites/123
```

Sample response:
```
{
    "id" : "123",
    "name" : "My Community",
    "suite_type" : "suite",
    "suite_key" : "abc123xyz",
    "description" : "Welcome to our community!",
    "status" : "",
    "create_date" : "2024-01-15 10:30:00",
    "update_date" : "2024-06-20 14:45:00"
}
```

#### Get all suites/communities
```
GET /suites
```

Sample response:
```
[
    {
        "id" : "123",
        "name" : "My Community",
        "suite_type" : "suite",
        "suite_key" : "abc123xyz",
        "description" : "Welcome to our community!",
        "status" : "",
        "create_date" : "2024-01-15 10:30:00",
        "update_date" : "2024-06-20 14:45:00"
    },
    {
        "id" : "456",
        "name" : "VIP Members Party",
        "suite_type" : "party",
        "suite_key" : "def456uvw",
        "description" : "Exclusive party for VIP members",
        "status" : "",
        "create_date" : "2024-03-01 09:00:00",
        "update_date" : "2024-05-15 11:30:00"
    }
]
```

#### Get only communities (suites)
```
GET /suites?type=suite
```
or
```
GET /suites?type=community
```

#### Get only parties
```
GET /suites?type=party
```

---

### Response Fields

Field | type | description
--- | --- | ---
id | string | Unique identifier for the suite/community
name | string | Display name of the suite/community
suite_type | enum | "suite" for communities, "party" for parties
suite_key | string | Unique key used in URLs and references
description | string | Description of the suite/community (may be null)
status | string | Status of the suite ("" for active, "party_pending_launch" for parties awaiting launch)
create_date | datetime | When the suite was created
update_date | datetime | When the suite was last updated

---

### Using Suites with Automations

The `id` returned from this endpoint can be used as the `suite_party_id` parameter when creating automations with the `suite_party_joined` trigger:

```
POST /automations
{
    "item_type" : "suite_party",
    "suite_party_id" : 123,
    "template_type" : "email",
    "template_id" : 456,
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 30
}
```

This automation will send an email template 30 minutes after a contact joins the specified suite/community.
