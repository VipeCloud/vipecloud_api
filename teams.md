Teams
-----------
Get a list of teams that you own.

<details>
  <summary><code>SCHEMA</code></summary>
  <br>
  
  Key | Description | Required | Type | Default | Valid Values 
  --- | --- | --- | --- | --- | ---
  <code>id</code> | The team id. | - | Integer | - | -
  <code>name</code> | The team name. | Yes | String (Max length 100) | - | -
  
</details>
<hr>
<details>
  <summary><code>GET</code> <b>/teams</b> Search teams</summary>
  <br>
  Search your teams by team name <code>name</code>

  A sample query might be <code>/teams?name=My</code>

  A successful response will be a status 200 and return an array of up to 100 products.
  
  ```json
  [
    {
        "id" : 123
        "name" : "My First Team",
    },
    ...
  ]
  ```
</details>
