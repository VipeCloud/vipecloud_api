Products (coming soon)
-----------
Create, update, get, and delete products (or services). 

<details>
  <summary><code>SCHEMA</code></summary>
  <br>
  
  Key | Description | Required | Type | Default | Valid Values 
  --- | --- | --- | --- | --- | ---
  <code>name</code> | The product name. | Yes | String (Max length 100) | - | -
  <code>description</code> | The description of the product. | No | Text | - | -
  <code>code</code> | The code or id of your product. If one is not provided, it will be created for you. | No | String (Max length 100) | - | - 
  <code>list_price</code> | The list price of your product or service. Supports 2 decimals (e.g. 10.00). | No | Float | - | _
  <code>visibility</code> | Determine which users in your account can use this product. | No | String or Array | <code>all_users</code> | <ul><li><code>all_users</code></li><li><code>cur_user</code></li><li>Array of team_ids</li></ul>
  <code>parent_ref</code> | An id of the product category that is the parent of this product. | No | Integer | - | -
  <code>product_type</code> | Type of product offered. | No | String | <code>Service</code> | <ul><li><code>Service</code></li><li><code>Digital</code></li><li><code>Inventory</code></li><li><code>Donation</code></li><li><code>Category</code></li></ul>
  <code>taxable</code> | Is the product taxable? | No | Boolean | <code>false</code> | <ul><li><code>true</code></li><li><code>false</code></li></ul>
  <code>tax_rate</code> | The tax rate. Supports up to one decimal point of a percentage (e.g. 9.1%) | Yes if <code>taxable</code> is <code>true</code> | Float | - | -
  <code>image_url</code> | A url to an image of the product or service. Used on Sign Up Forms. Square is best. | No | String (Max length 100) | - | -
  
  
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
  <summary><code>GET</code> <b>/products</b> Search products</summary>
  <br>
  Coming Soon
  <br>
</details>

<details>
  <summary><code>GET</code> <b>/products/{id}</b> Get product details</summary>
  <br>
  Coming Soon
  <br>
</details>

<details>
  <summary><code>DELETE</code> <b>/products/{id}</b> Delete a product</summary>
  <br>
  Coming Soon
  <br>
</details>
