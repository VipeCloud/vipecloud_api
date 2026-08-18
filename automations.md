Automations (POST / GET)
-------------
Create, update, and retrieve Automations (formerly AutoResponders) from your user accounts. NOTE - /autoresponders will continue to work for the time being.

#### POST Automations

```http
POST /automations(/:id)
```

Attribute | type | required | description
--- | --- | --- | ---
item_type | enum | no | "contact_list" (default), "contact", "opportunity", or "suite_party". Selects the trigger container — see [item_type reference](#item_type-reference) below.
contact_list_id | integer | cond. | Required when item_type is "contact_list" (or not specified).
suite_party_id | integer | cond. | Required when item_type is "suite_party".
stage_id | integer | cond. | Required when item_type is "opportunity". Creating or editing an opportunity automation requires account permission above the Standard role.
template_type | enum | yes | The action to perform. See [Actions](#actions-template_type) below for the full list and per-item_type availability.
template_id | integer | cond. | Required for template-backed actions ("email", "series", "text", "social", "task", "opportunity"). Not used for "cancel_*", "custom_field", "no_template", or any of the other actions listed below.
template_ids | integer[] | cond. | Required on item_type "contact_list" when template_type is "cancel_email", "cancel_text", or "cancel_series" (or send `cancel_template_ids` instead — see below). On other item_types this is not enforced as a required field, but a cancel_* automation without it will have no template ids to act on. Contains the template ids (of the matching type) whose scheduled sends/steps should be canceled when the automation fires.
cancel_template_ids | integer[] | no | Accepted as an alias for `template_ids`, on any item_type, when `template_ids` is not present in the same request. If both are present, `template_ids` wins.
action_custom_field_id | integer | cond. | Required when template_type is "custom_field". Must be a "contact"-type custom field — an "opportunity"-type custom field is rejected even on an opportunity automation.
action_custom_field_value | string/array | cond. | Required when template_type is "custom_field". For Checkbox fields, must be the string "true"/"false" **or** a JSON boolean (`true`/`false`) — both are accepted and normalized to the string form before storage. For Dropdown/Picklist fields, must be a valid option value (or an array of valid values).
action_contact_list_id | integer | cond. | Required when template_type is "add_to_list" or "remove_from_list". The destination list.
stage_moves | array | cond. | Required when template_type is "move_opp_stage". A JSON array (or JSON-encoded string) of `{pipeline_id, stage_id, loss_reason_id}` objects — `loss_reason_id` is optional and only meaningful when the target stage is a lost-type stage. Duplicate `pipeline_id`s keep the first valid pair; unmatched pairs are dropped silently.
target_stage_id | integer | cond. | Required when template_type is "move_opp_stage_delayed". Only valid when trigger_type is "opp_stage_change".
target_loss_reason_id | integer | no | Optional with "move_opp_stage_delayed". Only applied when the target stage is a lost-type stage; stored in the response's `automation_data.loss_reason_id`.
watch_target_user_id | integer | no | Optional with "add_to_watchlist". Must be a user visible to the caller (their own id is always allowed); when omitted, the action defaults to the run-context user at execution time.
ai_scheduler_contact_list_id | integer | cond. | Required when template_type is "ai_scheduling". The contact list backing the scheduler to book.
ai_text_template_id | integer | cond. | Required when template_type is "ai_scheduling". Must contain the AI Booking Agent merge tag.
ab_num_brands | integer | no | Optional with "ai_brand_matching". Clamped to 1-10; defaults to 3.
ab_notify_inapp | boolean | no | Optional with "ai_brand_matching".
ab_send_email | boolean | no | Optional with "ai_brand_matching".
acr_auto_update_fields | boolean | no | Optional with "ai_contact_research".
acr_log_task | boolean | no | Optional with "ai_contact_research".
acr_task_type_id | integer | no | Optional with "ai_contact_research". Ignored (silently) if it doesn't resolve to a task type owned by this account.
acr_bookmark_task | boolean | no | Optional with "ai_contact_research".
acr_notify_inapp | boolean | no | Optional with "ai_contact_research".
trigger_type | enum | cond. | The firing condition. See [Triggers](#triggers-trigger_type) below — required for every item_type except "suite_party", where it is set automatically.

#### item_type reference

`item_type` selects the trigger container. It defaults to `"contact_list"` when omitted or not a string — this is unchanged, backwards-compatible behavior.

item_type | Required field | Notes
--- | --- | ---
contact_list (default) | contact_list_id | Today's default. The add-to-list loop guard (see Errors) only runs on this item_type.
contact | none — contact_list_id is stored as 0 | Account-wide; not scoped to a list. Intended for "custom_field", "text_opt_out", and "email_unsubscribe" triggers.
opportunity | stage_id | Requires account permission above the Standard role (perm > 250), otherwise 403.
suite_party | suite_party_id | trigger_type is forced to "suite_party_joined" — do not send trigger_type for this item_type.

Two item_types visible in the VipeCloud web app are **not yet available** through this endpoint and return `501`: `email_parsed` (automations attached to an inbox-sync rule) and `fb_lead_ad` (automations attached to a Facebook Lead Ad form). Support is planned for a future API release.

`item_type` is not cross-validated against `trigger_type` — the pairings in the Triggers table below reflect the supported/intended combinations (what the web app builds), not an enforced allowlist. Sending an unsupported pairing is not guaranteed to be rejected.

#### Actions (template_type)

Every item_type additionally accepts every template-backed action (`email`, `series`, `text`, `social`, `task`, `opportunity`) through its default case — those aren't repeated as a fourth "allowed on" column value below.

template_type | Required fields | contact | contact_list | opportunity | suite_party
--- | --- | --- | --- | --- | ---
email, series, text, social, task, opportunity | template_id | Yes | Yes | Yes | Yes
no_template | none | No | No | Yes | No
cancel_email, cancel_text, cancel_series | template_ids (see above) — requires account permission above the Standard role (perm > 250) | Yes | Yes | Yes | Yes
custom_field | action_custom_field_id, action_custom_field_value | Yes | Yes | Yes | Yes
ai_scheduling | ai_scheduler_contact_list_id, ai_text_template_id | Yes | Yes | Yes | Yes
ai_brand_matching | none required (optional ab_num_brands/ab_notify_inapp/ab_send_email) | Yes | Yes | No | No
ai_contact_research | none required (optional acr_* fields) | Yes | Yes | No | No
add_to_list, remove_from_list | action_contact_list_id | Yes | Yes | Yes | Yes
move_opp_stage | stage_moves | Yes | No | Yes | No
move_opp_stage_delayed | target_stage_id | No | No | Yes | No
add_to_watchlist | none required (optional watch_target_user_id) | Yes | Yes | Yes | Yes
remove_from_watchlist | none | Yes | Yes | Yes | Yes

`custom_field` note: the field referenced by `action_custom_field_id` must have `item_type = "contact"` regardless of which automation item_type you're building — an opportunity-type custom field is always rejected, even inside an item_type "opportunity" automation.

#### Triggers (trigger_type)

trigger_type | Required companion fields | Typical item_type | Notes
--- | --- | --- | ---
contact | delay_days, delay_hours, delay_min | contact_list | Fires when a contact is added to the list. Optionally accepts contact_trigger_hours (1/0) plus the contact_trigger_from_/contact_trigger_to_ hour/min/ampm window fields.
recurring | recurring_day, recurring_hour, recurring_min, recurring_ampm | contact_list | No leading zeros. Optionally weekday_only can be set to "on".
custom_field | custom_field_id, hour, min, ampm | contact_list, contact | This is the trigger-side field — a different parameter from the action's action_custom_field_id.
sign_up_form | delay_days, delay_hours, delay_min | contact_list | Fires on sign-up form completion. Reachable today; previously undocumented.
scheduler_completion | delay_days, delay_hours, delay_min | contact_list | Fires when a scheduler meeting is booked. Reachable today; previously undocumented.
meeting_reminder_follow_up | delay_days, delay_hours, delay_min, send_before_or_after ("before" or "after") | contact_list | contact_trigger_hours is ignored for this trigger (no time-of-day window). Reachable today; previously undocumented.
opp_stage_change | delay_days, delay_hours, delay_min; optional opp_stage_change_add_list (a contact_list_id owned by this account) and cancel_primary_contact_series ("on") | opportunity | Used together with item_type "opportunity"'s required stage_id.
suite_party_joined | delay_days, delay_hours, delay_min | suite_party | Set automatically when item_type is "suite_party" — do not send trigger_type yourself.
text_opt_out | scope ("user"/"account"/"contact_list"), plus scope_user_id (scope="user") or scope_contact_list_id (scope="contact_list") | contact | Non-admins are always forced to scope="user" targeting themselves, regardless of what is submitted. An admin's scope_user_id must belong to their own account or it is silently ignored and scope falls back to the caller.
email_unsubscribe | scope ("user"/"account"/"contact_list"), plus scope_user_id (scope="user") or scope_contact_list_id (scope="contact_list") | contact | Same scoping rules as text_opt_out. **Not the same as the `email_unsubscribe` webhook event** documented in [Webhooks v1.0](webhooks_v1_0.md#webhook-events) — the webhook notifies your endpoint when a contact unsubscribes from email; this automation trigger fires an automation action (e.g. add to list, cancel a series) when that happens. They are two separate features that share a name.

A missing or unrecognized `trigger_type` is rejected — see the Errors table.

```http
POST /automations(/:id)
```
Sample body when creating a standard Contact List Automation.

```json
{
    "contact_list_id" : 123,
    "template_type" : "email",
    "template_id" : 456,
    "trigger_type" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0
}
```

```http
POST /automations(/:id)
```
Sample body when creating a Suite Party Joined Automation. This automation fires when a contact joins the specified suite/community.

```json
{
    "item_type" : "suite_party",
    "suite_party_id" : 789,
    "template_type" : "email",
    "template_id" : 456,
    "delay_days" : 0,
    "delay_hours" : 1,
    "delay_min" : 0
}
```

```http
POST /automations(/:id)
```
Sample body when creating a Custom Field Action Automation. This automation sets a custom field value on the contact when triggered.

```json
{
    "contact_list_id" : 123,
    "template_type" : "custom_field",
    "action_custom_field_id" : 456,
    "action_custom_field_value" : "Active Customer",
    "trigger_type" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0
}
```

```http
POST /automations(/:id)
```
Sample body when creating a Checkbox Custom Field Action Automation. A JSON boolean (`true`) is also accepted for `action_custom_field_value` on a Checkbox field.

```json
{
    "contact_list_id" : 123,
    "template_type" : "custom_field",
    "action_custom_field_id" : 789,
    "action_custom_field_value" : "true",
    "trigger_type" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0
}
```

```http
POST /automations(/:id)
```
Sample body when creating an Automation for canceling templates. Upon being added to the list, if the added contact has a scheduled email set to go out based on the templates 1234, 5678, or 9012, those scheduled sends will be canceled. Requires account permission above the Standard role.

```json
{
    "contact_list_id" : 123,
    "template_type" : "cancel_email",
    "trigger_type" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0,
    "template_ids" : [
        1234, 5678, 9012
    ]
}
```

```http
POST /automations
```
Sample body for an Opportunity Stage Change automation that moves a contact's opportunities to a new stage a fixed number of days later. `item_type` "opportunity" requires account permission above the Standard role.

```json
{
    "item_type" : "opportunity",
    "stage_id" : 55,
    "template_type" : "move_opp_stage_delayed",
    "target_stage_id" : 60,
    "trigger_type" : "opp_stage_change",
    "delay_days" : 3,
    "delay_hours" : 0,
    "delay_min" : 0
}
```

```http
POST /automations
```
Sample body for an account-wide Text Opt Out automation, scoped to a single contact list. Adds the contact to a suppression list when they text-opt-out.

```json
{
    "item_type" : "contact",
    "template_type" : "add_to_list",
    "action_contact_list_id" : 321,
    "trigger_type" : "text_opt_out",
    "scope" : "contact_list",
    "scope_contact_list_id" : 123
}
```

#### GET Automations
```http
GET /automations(/:id)
```
Retrieve automations by id or retrieve a list of all automations in the user's account.

Sample response to get a contact list automation by id. GET /automations/123
```json
{
    "id" : 123,
    "contact_list_id" : 123,
    "contact_list_name" : "My contact list",
    "template_type" : "email",
    "template_id" : 456,
    "template_title" : "My email template",
    "trigger" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0,
    "schedule_data" : null,
    "weekday_only" : null,
    "item_type" : "contact_list"
}
```

Sample response for a suite_party_joined automation. GET /automations/456
```json
{
    "id" : 456,
    "contact_list_id" : 0,
    "template_type" : "email",
    "template_id" : 789,
    "template_title" : "Welcome to our community",
    "trigger" : "suite_party_joined",
    "delay_days" : 0,
    "delay_hours" : 1,
    "delay_min" : 0,
    "schedule_data" : "{\"suite_party_id\":\"123\",\"contact_trigger_hours\":0}",
    "weekday_only" : null,
    "item_type" : "suite_party",
    "suite_name" : "My Community",
    "suite_type" : "community"
}
```

Sample response for a custom_field action automation. GET /automations/789
```json
{
    "id" : 789,
    "contact_list_id" : 123,
    "contact_list_name" : "My contact list",
    "template_type" : "custom_field",
    "template_id" : 456,
    "trigger" : "contact",
    "delay_days" : 0,
    "delay_hours" : 0,
    "delay_min" : 0,
    "schedule_data" : null,
    "weekday_only" : null,
    "item_type" : "contact_list",
    "automation_data" : {
        "custom_field_value" : "Active Customer",
        "field_type" : "Dropdown"
    }
}
```

Sample response for an opportunity item_type automation. GET /automations/901. Unlike other item_types, `schedule_data` is returned as a decoded object (not a JSON-encoded string) here, and the response additionally includes `stage_info` and `pipeline_info` (the full target stage/pipeline records) plus `contact_list_name` (populated only when `schedule_data.opp_stage_change_add_list` is set).

```json
{
    "id" : 901,
    "contact_list_id" : 0,
    "template_type" : "move_opp_stage_delayed",
    "template_id" : 0,
    "trigger" : "opp_stage_change",
    "delay_days" : 3,
    "delay_hours" : 0,
    "delay_min" : 0,
    "schedule_data" : {
        "stage_id" : "55",
        "opp_stage_change_add_list" : 0,
        "cancel_primary_contact_series" : 0,
        "contact_trigger_hours" : 0
    },
    "weekday_only" : null,
    "item_type" : "opportunity",
    "contact_list_name" : "",
    "stage_info" : { "opportunity_stage_id" : 55, "pipeline_id" : 12 }, //full opportunity_stage row, additional columns omitted here
    "pipeline_info" : { "pipeline_id" : 12 }, //full pipeline row, additional columns omitted here
    "automation_data" : {
        "target_stage_id" : 60
    }
}
```

Sample response to get all automations. GET /automations
```json
[
    {
        "id" : 123,
        "contact_list_id" : 123,
        "contact_list_name" : "My contact list",
        "template_type" : "email",
        "template_id" : 456,
        "template_title" : "My email template",
        "trigger" : "contact",
        "delay_days" : 0,
        "delay_hours" : 0,
        "delay_min" : 0,
        "schedule_data" : null,
        "weekday_only" : null,
        "item_type" : "contact_list"
    },
    {
        "id" : 456,
        "contact_list_id" : 0,
        "template_type" : "series",
        "template_id" : 789,
        "template_title" : "Onboarding Series",
        "trigger" : "suite_party_joined",
        "delay_days" : 0,
        "delay_hours" : 0,
        "delay_min" : 30,
        "schedule_data" : "{\"suite_party_id\":\"123\"}",
        "weekday_only" : null,
        "item_type" : "suite_party"
    }
]
```

#### Errors

HTTP | `message` | When
--- | --- | ---
501 | "The '{item_type}' item_type is supported in the web app but is not yet available via the API. It is planned for a future API release." | item_type is "email_parsed" or "fb_lead_ad"
422 | "Unsupported item_type '{item_type}'." | item_type is any other unrecognized value
422 | "Creating or updating an Automation requires a contact_list_id, template_type, and (template_id if not a cancel template_type or template_ids array if a cancel type)." | item_type "contact_list" is missing a required field
503 | "Could not verify contact list. Please try again." | item_type "contact_list": the ownership lookup itself failed — retry, your input may be valid
422 | "Contact list not found." | item_type "contact_list": contact_list_id doesn't belong to your account — also returned by add_to_list/remove_from_list when action_contact_list_id doesn't belong to your account
422 | "That action is not available for this automation type." | template_type is not in the item_type's action allowlist (see [Actions](#actions-template_type))
403 | "You do not have permission to create cancel automations." | template_type is cancel_email/cancel_text/cancel_series and your account permission is at or below the Standard role
422 | "This add-to-list action would create a loop with another automation. Adjust the trigger or destination so no chain of add-to-list automations can return to its starting list." | template_type "add_to_list" on item_type "contact_list" would close a cycle in your account's add-to-list automation graph
403 | "You do not have permission to create or edit opportunity automations." | item_type "opportunity" and your account permission is at or below the Standard role
422 | "Creating or updating an Automation requires a stage_id and template_type." | item_type "opportunity" is missing a required field
503 | "Could not verify opportunity stage. Please try again." | item_type "opportunity": the stage lookup itself failed — retry
422 | "Opportunity stage not found." | item_type "opportunity": stage_id doesn't belong to your account
422 | "Creating a suite_party automation requires suite_party_id and template_type." | item_type "suite_party" is missing a required field
503 | "Could not verify suite/community. Please try again." | item_type "suite_party": the community lookup itself failed — retry
422 | "Suite/Community not found." | item_type "suite_party": suite_party_id doesn't belong to your account
422 | "custom_field template_type requires action_custom_field_id and action_custom_field_value." | template_type "custom_field" is missing a required field
503 | "Could not verify custom field. Please try again." | template_type "custom_field": the field lookup itself failed — retry
422 | "Custom field not found." | template_type "custom_field": action_custom_field_id doesn't belong to your account
422 | "Custom field must be a contact field type." | template_type "custom_field": the referenced field's item_type isn't "contact"
422 | "Checkbox custom field value must be 'true' or 'false'." | template_type "custom_field" on a Checkbox field with any other action_custom_field_value
503 | "Could not validate custom field options. Please try again." | template_type "custom_field" on a Dropdown/Picklist field: the option-list read itself failed — retry
422 | "Invalid value for dropdown/picklist custom field." | template_type "custom_field" on a Dropdown/Picklist field with a value not in the field's option list
403 | "AI Booking Agent is not enabled" | template_type "ai_scheduling" and AI Booking Agent isn't enabled for this account
422 | "Scheduler is required" | template_type "ai_scheduling" is missing ai_scheduler_contact_list_id
422 | "Text template is required" | template_type "ai_scheduling" is missing ai_text_template_id
503 | "Could not verify scheduler. Please try again." | template_type "ai_scheduling": the scheduler lookup itself failed — retry
422 | "Invalid scheduler" | template_type "ai_scheduling": ai_scheduler_contact_list_id doesn't resolve to a scheduler on your account
422 | "Text template must contain %VC_BOOKING_AGENT% merge tag" | template_type "ai_scheduling": ai_text_template_id doesn't reference a template containing the required merge tag
403 | "Vipe AI is not enabled for this account." | template_type "ai_brand_matching" and AI Brand Matching isn't enabled
503 | "Could not verify brand documents. Please try again." | template_type "ai_brand_matching": the brand-document lookup itself failed — retry
422 | "No brand documents found. Upload at least one brand document at Vipe AI > Matching before using AI Brand Matching in automations." | template_type "ai_brand_matching" and no brand documents exist for this account
422 | "Vipe AI is not enabled for this account." | template_type "ai_contact_research" and AI Contact Research isn't enabled
422 | "Please choose a contact list." | template_type "add_to_list"/"remove_from_list" is missing action_contact_list_id
422 | "You cannot assign this action to that user." | template_type "add_to_watchlist": watch_target_user_id isn't visible to the calling user
422 | "Please add at least one pipeline and stage." | template_type "move_opp_stage": stage_moves is missing, not an array, or empty
422 | "No valid pipeline/stage pairs were provided." | template_type "move_opp_stage": every supplied pair failed validation (unowned stage, wrong pipeline, duplicate pipeline)
422 | "The stage \"X\" requires a loss reason. Choose one for this action." | template_type "move_opp_stage" or "move_opp_stage_delayed" targets a lost-type stage configured to require a loss reason, and none of the supplied loss_reason_id values belong to that stage
422 | "Please choose a target stage." | template_type "move_opp_stage_delayed" is missing target_stage_id
422 | "Target stage not found." | template_type "move_opp_stage_delayed": target_stage_id doesn't belong to your account
422 | "This action is only available for the Opportunity Stage Change trigger." | template_type "move_opp_stage_delayed" used with any trigger_type other than "opp_stage_change"
422 | "Template not found." | template_type isn't one of the recognized action names above and doesn't resolve as a valid template_id for its own type
422 | "Failed to create automation." | POST with no id: trigger_type is missing or not one of the recognized values in the Triggers table
422 | "Automation not found or update failed." | POST with an id (update): the automation doesn't exist/isn't yours, or trigger_type is missing or not recognized

