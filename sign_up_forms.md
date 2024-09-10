Sign Up Forms (GET)
-------------------------------------

#### GET list of existing sign up forms a user has in VipeCloud
```
GET /sign_up_forms(/:id)
```
Returns an individual record or array of the sign up forms that a user has in VipeCloud.
```   
{ 
  [
    {  
      "contact_list_id" : "123",
      "contact_list_name" : "New Customers",  
      "create_date" : "2014-09-03 08:30:39",
      "all_count": 16 //note that bounces, unsubscribes, and verified undeliverable contacts are automatically removed
      "source" : "manual"
    },
    {
    ...
    }
  ]
}
```

If an ID is given, then the contacts_master_ids of contacts within that sign up form will be returned. Please note that while the id says contact_list_id, the returned items are sign_up_forms. They are the same objects within our database.

```   
{  
    "contact_list_id" : "123",
    "contact_list_name" : "New Customers",  
    "create_date" : "2014-09-03 08:30:39",
    "all_count": 16 //note that bounces, unsubscribes, and verified undeliverable contacts are automatically removed
    "source" : "manual"
    "contacts_master_ids": [
        123,
        456,
        789
    ]
},
```