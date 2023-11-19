Social Accounts (GET)
-------------------------------------
Retrieve a user's connected social accounts.

#### GET account custom fields
```
GET /social_accounts(/:id)
```
The response to this GET will be an array of the user's social accounts or the details of a single account.
```   
{ 
  [
    {
      "id" : 1, //the custom field id
      "slug" => "facebook", //lowercase string of the social network
      "account_name" => "Page - VipeCloud", //the name the user has given this social account
      "status" => "", //no status means the account is in good standing. Status of "authorized" is a Facebook group that has been authorized to connect by the user in VipeCloud, but has not yet had the VipeCloud app added to the Facebook group.
      "create_date" => "2020-03-22 16:45:37"
    }
  ]
}
```
