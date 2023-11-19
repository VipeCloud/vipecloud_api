Contacts (POST / GET / DELETE)
-------------------------------------

#### Create new / update existing Contacts in VipeCloud
```
POST /contacts(/:id)
```
When POSTing to /contacts, the body can either be an individual contact record or an array of contact records. If you are updating existing contacts, it is recommended that you include a contacts_master_id parameter for the contact. If not, the system will search for existing contacts based on the unique setting for your contact email address (account-wide, per user, or none). If submitting an array of contact records to create, first_name and either email or mobile_phone are always required. If a contacts_master_id is given for a contact to update, the requirement of first_name and either email or mobile_phone is not required. 

You can, optionally, include a "contact_lists" parameter to your contact POST body. If you do, we will assume the contact_list_ids you submit represent the ENTIRETY of the contact lists the contact should be a part of. We will compare your POSTed contact_list_ids to any existing contact_lists for the contact. If the contact is part of contact lists not in your POST they will be removed from the list. And if contacts in your POST are not on the list they will be added. To remove a contact from ALL contact lists they are on, submit "0" as the contact_list_id (e.g. contact_lists : ["0"]).

Sample post body below.
```   
{ 
   "first_name" : "Road", //required
   "last_name" : "Runner", 
   "email" : "email", //email or mobile_phone required
   "title" : "Evader",
   "website" : "www.acme.com", 
   "work_phone" : "1234567890", 
   "mobile_phone" : "1234567891", //email or mobile_phone required
   "direct_phone" : "1234567892", 
   "phone" : "1234567893", 
   "company_name" : "Acme", 
   "address1" : "123 Acme Street", 
   "address2" : "", 
   "city" : "Disneyland", 
   "state" : "CA", 
   "zip" : "12345", 
   "country" : "USA", 
   "unsubscribe" : 0,
   "personal_linkedin_url" : "https://www.linkedin.com/...",
   "personal_twitter_url" : "https://www.twitter.com/...",
   "personal_facebook_url" : "https://www.facebook.com/...",
   "tags" : ["Speedy"], 
   "verify" : 0, //if this is 1 AND you are an enterprise user AND this user has less than 10K verifications this month, we will verify the contact's email address on import
   "custom_fields" : [ //an array of the custom fields. Key value is the custom field id.
      id : "value" 
   ],
   "contact_lists" : [ //an array of contact_list_ids.
      "0" => 123,
      "1" => 1234
   ],
   "add_to_contact_lists" : [ 123, 1234 ], //optional parameter that accepts an array of contact_list_ids to add the contact directly to a set of lists (the user must own the contact list)
   "remove_from_contact_lists" : [ 12345, 12346 ] //optional parameter that accepts an array of contact_list_ids to remove the contact directly from a set of lists (the user must own the contact list)
}
```

#### GET Contacts
```
GET /contacts(/:id)
```
If no id, returns an array of the contacts for the authenticated user. If id, returns the details for a contact. If you only require certain contact parameters, append them (comma-separated) as a "to_get" parameter: 
```
E.g. GET /contacts/123?to_get=contacts_master_id,first_name 
```

Full contact record:
```   
{ 
  [
    { 
     "contacts_master_id" : 123,
     "first_name" : "Road", 
     "last_name" : "Runner", 
     "email" : "email", 
     "title" : "Evader",
     "website" : "www.acme.com", 
     "work_phone" : "1234567890", 
     "mobile_phone" : "1234567891", 
     "direct_phone" : "1234567892", 
     "phone" : "1234567893", 
     "company_name" : "Acme", 
     "address1" : "123 Acme Street", 
     "address2" : "", 
     "city" : "Disneyland", 
     "state" : "CA", 
     "zip" : "12345", 
     "country" : "USA", 
     "personal_linkedin_url" : "https://www.linkedin.com/...",
     "personal_twitter_url" : "https://www.twitter.com/...",
     "personal_facebook_url" : "https://www.facebook.com/...",
     "tags" : ["Speedy"],
     "contact_lists" : [
        "0" => [
            "contact_list_id" : 123,
            "contact_list_name" : "First list"
        ]
     ]
    },
    {
    ...
    }
  ]
}
```

#### DELETE Contacts
```
DELETE /contacts/:id
```
If the contact cannot be found, or has already been deleted, a 422 will be returned with the message 'No contact was found'. Upon successful deletion of a contact, a status of success will be returned.

Example error response
```
{
  "status" : "error",
  "message" : "No contact was found."
}
```

Example Success Response
```
{ "status" : "success" }
```
