Contact Lists (POST / GET)
-------------------------------------

#### Create new / update existing Contact List in VipeCloud
```
POST /contact_lists(/:id)
```
If creating a new list, a list name must be present. Creating an "empty" list - a list with a list_name and no contacts is allowed. 

When POSTing contacts to an existing list, we will assume the contacts you submit represent the ENTIRETY of the contact list. We will compare your POSTed contacts to any existing contacts on the list. If contacts on the list are not in your POST they will be removed from the list. And if contacts in your POST are not on the list they will be added. If the "skip_autoresponders" key is provided and set to true, then autoresponders for the given contact list will NOT run for contacts added during this call.

Note that VipeCloud will not add contacts that have unsubscribed from any user in your account, bounced, or have an email which has verified as undeliverable. 

When submitting contacts include the contacts_master_id of the contact record. 
```   
{ 
 "contact_list_name" : "My First List", //required if creating a new list. Will overwrite existing name if "id" provided
 "skip_autoresponders" : true // Optional parameter for POSTing to an existing contact list. If provided and set to true, autoresponders attached to this list will not run
 "contacts" : [
    {
      "contacts_master_id" : 123
    },
    {
      ...
    }
 ]
}
```
The response to this POST will be a status of success or error. On success the contact_list_id will be included in addition to a *count* of successful emails added to the list and a *list* of emails that were not added, with a message detailing why the contact wasn't added.
```
{
  "status" : "success",
  "contact_list_id" : 123,
  "contacts_added" : 123,
  "contacts_not_added": [
    {
      "contacts_master_id": 123,
      "message":"Contact unsubscribed."
    },
    {
      ...
    }
  ]
}
```

#### GET list of existing Contact Lists a user has in VipeCloud
```
GET /contact_lists(/:id)
```
Returns an individual record or array of the lists that a user has in VipeCloud. Optionally add the parameter hide_system_lists to the url to remove system lists from the result (e.g. /contact_lists?hide_system_lists=1)
```   
{ 
  [
    {  
      "contact_list_id" : "123",
      "contact_list_name" : "New Customers",  
      "create_date" : "2014-09-03 08:30:39",
      "all_count": 16 //note that bounces, unsubscribes, and verified undeliverable contacts are automatically removed
      "source" : "upload_csv"
    },
    {
    ...
    }
  ]
}
```
