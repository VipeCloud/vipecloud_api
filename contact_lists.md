Contact Lists (POST / GET)
-------------------------------------

#### Create new / update existing Contact List in VipeCloud
```
POST /contact_lists(/:id)
```
If creating a new list, a list name must be present. Creating an "empty" list - a list with a list_name and no contacts is allowed. 

When POSTing contacts to an existing list, we will assume the contacts you submit represent the ENTIRETY of the contact list if using the 'contacts' parameter. We will compare your POSTed contacts to any existing contacts on the list. If contacts on the list are not in your POST they will be removed from the list. And if contacts in your POST are not on the list they will be added. The 'add_contacts' parameter can be used if you simply want to add contacts to the list, without submitting the entirety of the contact list. Each call may only include ONE of 'contacts' or 'add_contacts'. If both are provided, then the call will fail. If the "skip_autoresponders" key is provided and set to true, then autoresponders for the given contact list will NOT run for contacts added during this call. If neither the 'add_contacts' parameter or the 'contacts' parameters are provided, then no contacts will be added/removed by the call. We've added a parameter to the response body, 'worked_contacts', which is a boolean indicating whether or not contact addition/subtraction calculations occurred during the call. We've added this functionality to allow for editing contact_list data without having to worry about all contacts being removed accidentally, and removing the need to send the entirety of the contact list if you just wanted to change the name or a setting.

Note that VipeCloud will not add contacts that have unsubscribed from any user in your account, bounced, or have an email which has verified as undeliverable. 

When submitting contacts include the contacts_master_id of the contact record, or use this endpoint as a way to create contacts and add them to the list. 
- To create a contact using this endpoint, the same requirements have to be met as creating a contact via the /contacts endpoint (first_name and either mobile_phone or email). Additionally, all account required fields have to be present on the contact, and at least 1 unique field must be present.

"Synced lists": A parameter "is_synced_list" can be found on returned contact_lists, as well as put in POST calls to the contact_lists API. By setting "is_synced_list" to 1, The user will see a badge indicating that the contact list is a synced list, and they will no longer be able to direclty add/remove contacts from the contact lists page. This should make it significantly easier to users from accidentally messing with a contact list that isn't the source of truth for that data.

```   
{ 
 "contact_list_name" : "My First List", //required if creating a new list. Will overwrite existing name if "id" provided
 "skip_automations" : true // Optional parameter for POSTing to an existing contact list. If provided and set to true, autoresponders attached to this list will not run
 "skip_autoresponders" : true // Old parameter - replaced by skip_automations but still has the same functionality
 "is_synced_list" : true //Optional Parameter for setting whether or not the user sees this list is a synced list, which means contacts cannot be added/removed directly
 "contacts" : [
    {
      "contacts_master_id" : 123
    },
    {
      "email": "example@example.com",
      "first_name" : "Jane"
    },
    {
      "mobile_phone": "1234567890",
      "first_name" : "John"
    }
 ],
 // Can only submit EITHER 'contacts', 'add_contacts', or neither in a single call. If BOTH are passed, then the call will fail
 "add_contacts": [
    {
      "contacts_master_id" : 123
    },
    {
      "email": "example@example.com",
      "first_name" : "Jane"
    },
    {
      "mobile_phone": "1234567890",
      "first_name" : "John"
    }
 ]
}
```

An example of a call which does NOT empty out the list

POST /contact_lists/1234
```
{
  "is_synced_list": 1
  "contact_list_name" : "Updated List Name"
}
```

The response to this POST will be a status of success or error. On success the contact_list_id will be included in addition to a *count* of successful emails added to the list, the net change, contacts removed. Additionally, a *list* of contacts that were not added, with a message detailing why the contact wasn't added.

If contact data without a contacts_master_id is input into the list, these will be split up into two additional *lists*: contacts_not_created and contacts_created. Created contacts witin the created_contacts body parameter will mirror the data sent in to the API, along with an appended "contacts_master_id" parameter. contacts_not_created will mirror the data sent into the api, indicating that this data could not be used.

We now also return the data for contacts that were removed, that way those contacts_master_id's can be used for subsequent calls. An example of this would be calling a POST /contact_list/(/:id) endpoint for a list, and then making an additional call for any contacts removed to add them to another list via a second POST /contact_list(/:id) endpoint with the "add_contacts" parameter

#### Example Post Response 
```
{
  "status" : "success",
  "contact_list_id" : 123,
  "contacts_added" : 123,
  "is_synced_list" : 1,
  "worked_contacts" : 1,
  "contacts_not_added": [
    {
      "contacts_master_id": 123,
      "message":"Contact unsubscribed."
    },
    {
      ...
    }
  ],
  "contacts_removed": 0,
  "net_change": "0", // could be +X, -X
  "contacts_not_created": [
    {
      "first_name": "Jane"
    }
  ],
  "contacts_created": [
    {
      "email": "example@example.com",
      "first_name": "Jane",
      "contacts_master_id": 12345
    },
    {
      ...
    }
  ],
  // Only returns CMIDs
  "contacts_removed_data": [
    {
      "contacts_master_id":12345
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
      "is_synced_list": 1
      "source" : "upload_csv"
    },
    {
    ...
    }
  ]
}
```
