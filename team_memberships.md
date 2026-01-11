Team Memberships (GET / POST / DELETE)
-------------

Manage team memberships for users within your VipeCloud account. **Only Admin users can use these endpoints.**

---

### GET team memberships

```
GET /team_memberships
```

Retrieve team memberships filtered by either user or team.

#### Access Control
- Only Admin users can access this endpoint
- Users and teams must belong to the authenticated user's account

#### Query Parameters

One of the following is required:

Parameter | Type | Description
--- | --- | ---
user_id | integer | Get all team memberships for a specific user
team_id | integer | Get all members of a specific team

#### Sample Request - By User

```
GET /team_memberships?user_id=12345
```

Sample response:
```json
{
  "status": "success",
  "data": [
    {
      "team_id": 100,
      "team_name": "Sales Team",
      "user_id": 12345,
      "first_name": "Wiley",
      "last_name": "Coyote",
      "email": "wiley.coyote@acme.com"
    },
    {
      "team_id": 200,
      "team_name": "Marketing Team",
      "user_id": 12345,
      "first_name": "Wiley",
      "last_name": "Coyote",
      "email": "wiley.coyote@acme.com"
    }
  ]
}
```

#### Sample Request - By Team

```
GET /team_memberships?team_id=100
```

Sample response:
```json
{
  "status": "success",
  "data": [
    {
      "team_id": 100,
      "team_name": "Sales Team",
      "user_id": 12345,
      "first_name": "Wiley",
      "last_name": "Coyote",
      "email": "wiley.coyote@acme.com"
    },
    {
      "team_id": 100,
      "team_name": "Sales Team",
      "user_id": 12346,
      "first_name": "Road",
      "last_name": "Runner",
      "email": "road.runner@acme.com"
    }
  ]
}
```

#### Error Responses

Status | Message | Description
--- | --- | ---
403 | "Only admins can manage team memberships." | The authenticated user is not an Admin
422 | "Please provide user_id or team_id parameter." | Neither user_id nor team_id was provided
422 | "User not found in your account." | The specified user doesn't exist or belongs to a different account
422 | "Team not found in your account." | The specified team doesn't exist or belongs to a different account

---

### POST add user to team

```
POST /team_memberships
```

Add a user to a team within your VipeCloud account.

#### Access Control
- Only Admin users can access this endpoint
- Both the user and team must belong to the authenticated user's account

#### Request Parameters

Attribute | Type | Required | Description
--- | --- | --- | ---
user_id | integer | **yes** | The ID of the user to add to the team
team_id | integer | **yes** | The ID of the team to add the user to

#### Sample Request Body

```json
{
  "user_id": 12345,
  "team_id": 100
}
```

#### Success Response (200)

```json
{
  "status": "success",
  "message": "User added to team successfully."
}
```

#### Error Responses

Status | Message | Description
--- | --- | ---
403 | "Only admins can manage team memberships." | The authenticated user is not an Admin
422 | "Both user_id and team_id are required." | One or both required fields are missing
422 | "User not found in your account." | The specified user doesn't exist or belongs to a different account
422 | "Team not found in your account." | The specified team doesn't exist or belongs to a different account
500 | "Failed to add user to team." | An internal error occurred while adding the user to the team

#### Notes
- If the user is already a member of the team, this endpoint will succeed without creating a duplicate membership
- Adding a user to a team does not send any notification to the user

---

### DELETE remove user from team

```
DELETE /team_memberships
```

Remove a user from a team within your VipeCloud account.

#### Access Control
- Only Admin users can access this endpoint
- Both the user and team must belong to the authenticated user's account

#### Request Parameters

Attribute | Type | Required | Description
--- | --- | --- | ---
user_id | integer | **yes** | The ID of the user to remove from the team
team_id | integer | **yes** | The ID of the team to remove the user from

#### Sample Request Body

```json
{
  "user_id": 12345,
  "team_id": 100
}
```

#### Success Response (200)

```json
{
  "status": "success",
  "message": "User removed from team successfully."
}
```

#### Error Responses

Status | Message | Description
--- | --- | ---
403 | "Only admins can manage team memberships." | The authenticated user is not an Admin
422 | "Both user_id and team_id are required." | One or both required fields are missing
422 | "User not found in your account." | The specified user doesn't exist or belongs to a different account
422 | "Team not found in your account." | The specified team doesn't exist or belongs to a different account
500 | "Failed to remove user from team." | An internal error occurred while removing the user from the team

#### Notes
- If the user is not a member of the team, this endpoint will succeed without error
- Removing a user from a team does not send any notification to the user
- Removing a user from all teams does not affect their ability to log in or use VipeCloud

---

### Use Cases

#### Onboarding a New User to Multiple Teams

After creating a user via `POST /users`, add them to the appropriate teams:

```bash
# Create the user
curl -X POST "https://v.vipecloud.com/api/v3.1/users" \
  -H "Authorization: Basic {base64(email:apikey)}" \
  -H "Content-Type: application/json" \
  -d '{"first_name":"John","last_name":"Smith","email":"john.smith@acme.com"}'

# Response includes user_id: 12345

# Add to Sales Team
curl -X POST "https://v.vipecloud.com/api/v3.1/team_memberships" \
  -H "Authorization: Basic {base64(email:apikey)}" \
  -H "Content-Type: application/json" \
  -d '{"user_id":12345,"team_id":100}'

# Add to Marketing Team
curl -X POST "https://v.vipecloud.com/api/v3.1/team_memberships" \
  -H "Authorization: Basic {base64(email:apikey)}" \
  -H "Content-Type: application/json" \
  -d '{"user_id":12345,"team_id":200}'
```

**Note:** You can also add users to teams at creation time by including the `teams` parameter in the `POST /users` request body.

#### Auditing Team Membership

List all teams a specific user belongs to:

```bash
curl "https://v.vipecloud.com/api/v3.1/team_memberships?user_id=12345" \
  -H "Authorization: Basic {base64(email:apikey)}"
```

List all members of a specific team:

```bash
curl "https://v.vipecloud.com/api/v3.1/team_memberships?team_id=100" \
  -H "Authorization: Basic {base64(email:apikey)}"
```

#### Transferring a User Between Teams

Remove from old team and add to new team:

```bash
# Remove from old team
curl -X DELETE "https://v.vipecloud.com/api/v3.1/team_memberships" \
  -H "Authorization: Basic {base64(email:apikey)}" \
  -H "Content-Type: application/json" \
  -d '{"user_id":12345,"team_id":100}'

# Add to new team
curl -X POST "https://v.vipecloud.com/api/v3.1/team_memberships" \
  -H "Authorization: Basic {base64(email:apikey)}" \
  -H "Content-Type: application/json" \
  -d '{"user_id":12345,"team_id":200}'
```
