Users (GET / POST)
-------------
### GET all active users within your account

```
GET /users
```
The response to this GET will be a list of the currently active VipeCloud users in your account. Users displayed are based on the visibility permission of the authenticated user. For example, an Admin user will see all your account users, a Manager will see their team members, and a Member will only see themselves.
```json
{
  [
    {
      "user_id" : 12345,
      "account_master_id" : 100,
      "first_name" : "Wiley",
      "last_name" : "Coyote",
      "name" : "Wiley Coyote",
      "email" : "wiley.coyote@acme.com",
      "phone" : "123-456-7890",
      "company" : "Acme",
      "photo_url" : "",
      "perm" : 350,
      "product" : "vipecloud",
      "timezone" : "America/New_York",
      "api_keys" : ["123456XYZ"],
      "user_role" : "Admin",
      "team_memberships" : [
        { "team_id": 100, "team_name": "Sales Team" }
      ]
    },
    {
      ...
    }
  ]
}
```

```
GET /users/:id
```

Getting an individual user will return a single user's information if the calling user has the authorization to view that record.

Optional query parameters:
- `activation_link=true` - Include the user's activation link in the response (useful for newly created users who haven't set their password yet)

```json
{
  "user_id" : 12345,
  "account_master_id" : 100,
  "first_name" : "Wiley",
  "last_name" : "Coyote",
  "name" : "Wiley Coyote",
  "email" : "wiley.coyote@acme.com",
  "phone" : "123-456-7890",
  "company" : "Acme",
  "photo_url" : "",
  "perm" : 350,
  "product" : "vipecloud",
  "timezone" : "America/New_York",
  "api_keys" : ["123456XYZ"],
  "user_role" : "Admin",
  "team_memberships" : [
    { "team_id": 100, "team_name": "Sales Team" }
  ],
  "activation_link" : "https://v.vipecloud.com/activate_user/...", // Only if activation_link=true
  "digital_business_card_link" : "https://v.vipecloud.com/bcard/XXXX/first_name" // If configured
}
```

---

### POST create a new user

```
POST /users
```

Create a new user within your VipeCloud account. **Only account owners can use this endpoint.**

#### Access Control
- Only account owners can create users via this endpoint
- The authenticated API user must be the account owner

#### Request Parameters

Attribute | Type | Required | Description
--- | --- | --- | ---
first_name | string | **yes** | First name of the new user
last_name | string | **yes** | Last name of the new user
email | string | **yes** | Email address (must be unique across all VipeCloud accounts)
user_role | enum | no | The role for the new user: `Admin` or `Member`. Default: `Member`
company | string | no | Company name. For non-agency accounts, defaults to the account owner's company name. For agency accounts, this will be blank if not provided.
phone_office | string | no | Office phone number
mobile_phone | string | no | Mobile phone number
title | string | no | Job title
timezone | string | no | Timezone (e.g., "America/New_York", "America/Los_Angeles")
website | string | no | Website URL
address | string | no | Street address (line 1)
address1 | string | no | Street address (line 2)
city | string | no | City
state | string | no | State/Province
zip | string | no | ZIP/Postal code
country | string | no | Country
send_user_activation_email | boolean | no | Whether to send the welcome/activation email to the new user. Default: `true`
teams | array | no | Array of team IDs to add the user to upon creation (e.g., `[123, 456]`)
mergetag_{id} | string | no | Set user merge tag values. Use the format `mergetag_{merge_tag_id}` (e.g., `mergetag_123: "value"`)

#### Sample Request Body (minimal)
```json
{
  "first_name" : "Wiley",
  "last_name" : "Coyote",
  "email" : "wiley.coyote@acme.com"
}
```

#### Sample Request Body (full)
```json
{
  "first_name" : "Wiley",
  "last_name" : "Coyote",
  "email" : "wiley.coyote@acme.com",
  "phone_office" : "123-456-7890",
  "mobile_phone" : "123-456-7891",
  "company" : "Acme Corp",
  "title" : "Sales Manager",
  "user_role" : "Member",
  "timezone" : "America/New_York",
  "website" : "https://acme.com",
  "address" : "123 Main St",
  "address1" : "Suite 100",
  "city" : "New York",
  "state" : "NY",
  "zip" : "10001",
  "country" : "USA",
  "send_user_activation_email" : true,
  "teams" : [123, 456],
  "mergetag_789" : "Custom Value"
}
```

#### Success Response (200)

The response matches the same format as `GET /users/:id`, including the activation link:

```json
{
  "user_id" : 12345,
  "account_master_id" : 100,
  "first_name" : "Wiley",
  "last_name" : "Coyote",
  "name" : "Wiley Coyote",
  "email" : "wiley.coyote@acme.com",
  "phone" : "123-456-7890",
  "company" : "Acme Corp",
  "photo_url" : "",
  "perm" : 300,
  "product" : "vipecloud",
  "timezone" : "America/New_York",
  "api_keys" : ["abc123xyz"],
  "user_role" : "Member",
  "team_memberships" : [
    { "team_id": 123, "team_name": "Sales Team" },
    { "team_id": 456, "team_name": "Marketing Team" }
  ],
  "activation_link" : "https://v.vipecloud.com/activate_user/abc123?tk=xyz789",
  "digital_business_card_link" : "https://v.vipecloud.com/bcard/12345/wiley"
}
```

#### Error Responses

Status | Message | Description
--- | --- | ---
403 | "Only account owners can create users via API." | The authenticated user is not the account owner
422 | "Invalid request body format." | The request body is not valid JSON
422 | "Missing required field: first_name, last_name, or email." | One or more required fields are missing
422 | "Invalid email address provided." | The email format is invalid
422 | "This email belongs to a deleted user within your account." | The email was previously used by a deleted user in your account
422 | "This email is unavailable. Please contact support." | The email is in use by another account or is otherwise unavailable
422 | "You don't have enough seats available for this product." | Your account has reached its user seat limit
500 | "Failed to create user." | User creation failed unexpectedly
500 | "Error creating user, please contact support." | An exception occurred during user creation

#### What Happens When You Create a User

When a user is created via this endpoint, the following actions occur automatically:

1. **User Registration** - A new user record is created with the provided information
4. **Team Memberships** - If `teams` is provided, the user is added to those teams
5. **Activation Email** - If `send_user_activation_email` is true, a welcome email with activation link is sent
6. **API Key** - For agency accounts, an API key is automatically generated
7. **System Folders** - Default system folders are created for the user
8. **Contact Record** - A contact record is created for the user (if one doesn't exist)
9. **Digital Business Card** - If your account has business card settings applied to all users, one is created
10. **Sign-Up Form Copies** - Any "all users" sign-up form automations are applied

