Products (coming soon)
-----------
Create, update, get, and delete products (or services). 

<details>
  <summary><code>SCHEMA</code></summary>
  <br>
  
  Key | Description | Required | Type | Default | Valid Values 
  --- | --- | --- | --- | --- | ---
  <code>name</code> | The product name. | Yes | String (Max Length 100) | - | -
  <code>description</code> | The description of the product. | No | Text | - | -
  <code>code</code> | The code or id of your product. If one is not provided, it will be created for you. | No | String (Max Length 100) | - | - 
  <code>list_price</code> | The list price of your product or service. Supports 2 decimals (e.g. 10.00). | No | Float | - | _
  <code>visibility</code> | Determine which users in your account can use this product. | No | String or Array | <code>all_users</code> | <ul><li><code>all_users</code></li><li><code>cur_user</code></li><li>Array of team_ids <code>[1,2,3]</code></li></ul>
  
  <br>
</details>

<details>
  <summary><code>POST</code> <b>/products</b> Create a product</summary>
  <br>
  Coming Soon
  <br>
</details>

<details>
  <summary><code>PUT</code> <b>/products/{id}</b> Update a product</summary>
  <br>
  Coming Soon
  <br>
</details>

<details>
  <summary><code>GET</code> <b>/products(/{id})</b> Get a product or Get all products</summary>
  <br>
  Coming Soon
  <br>
</details>
