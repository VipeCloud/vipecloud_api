VipeCloud API
=============

Overview
-------------
#### What can VipeCloud's API do for you?
   * Connect proprietary or other 3rd party systems to VipeCloud

#### Current Version
   * v4.0

#### General Information
   * Your account must gain authorization to use the VipeCloud API. Email support@vipecloud.com to request authorization
   * All requests must by made from HTTPS
   * All data sent should be JSON encoded (all data received will be JSON encoded)
   * Base URL for these functions: https://v.vipecloud.com/api/v4.0
   * API usage is currently throttled at 10 calls per 2 seconds per user
   
#### Interested in receiving webhooks?
   * Learn about our webhooks API: [Webhooks v1.0](webhooks_v1_0.md)

#### Testing the API
   * Comprehensive test suite available: [See Testing Guide](TESTING.md)
   * Test suite location: `../vc3/tests/`

#### Responses
   * 200 for success
   * 422 for incorrect post
   * 500 which is most likely a VipeCloud error


Authentication
-------------
Authorization is a Basic header of a base64 encoded concatenation of your VipeCloud user email and an active user API Key. API keys are managed in the Setup section of your VipeCloud account.

Sample PHP curl header to add to your POST
```php
<?php

$auth = base64_encode($user_email.":".$api_key);
curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic $auth", "Accept: application/json"));

?>
```

REST Conventions (v4.0)
-------------
VipeCloud API v4.0 follows standard REST conventions using HTTP methods:

* **GET** - Retrieve resources
  * `GET /endpoint` - Retrieve all resources
  * `GET /endpoint/:id` - Retrieve a specific resource by ID
* **POST** - Create new resources
  * `POST /endpoint` - Create a new resource
* **PUT/PATCH** - Update existing resources
  * `PUT /endpoint/:id` - Update a specific resource by ID
  * `PATCH /endpoint/:id` - Partially update a specific resource by ID
* **DELETE** - Delete resources
  * `DELETE /endpoint/:id` - Delete a specific resource by ID

**Note:** Not all endpoints support all HTTP methods. See individual endpoint documentation for supported operations.

Pagination and Sorting
-------------
All GET endpoints that return lists of resources support pagination and sorting through query parameters:

* **page** - The page number to retrieve (default: 1)
* **length** - Number of items per page (default: 20, max: 1000)
* **sort_by** - Field name to sort by (e.g., 'id', 'create_date', 'title')
* **sort_direction** - Sort direction, either 'ASC' or 'DESC' (default: 'ASC')

**Examples:**
```
GET /contacts?page=2&length=50
GET /contacts?sort_by=create_date&sort_direction=DESC
GET /email_templates?page=1&length=10&sort_by=title&sort_direction=ASC
```

**Note:** These parameters only affect GET requests that return lists. Individual resource requests (GET /endpoint/:id) are not affected.


Next Step: [Summary of Endpoints](SUMMARY.md)







