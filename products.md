Products (coming soon)
-----------
Create, update, get, and delete products (or services). 

<details>
  <summary><code>POST</code> /products Create a product</summary>

  Attribute | type | required | description 
  --- | --- | --- | --- 
  name | varchar(100) | yes | The product name.
  description | text | no | The description of the product.
  code | varchar(100) | no | The code or id of your product. If one is not provided, it will be created for you.
  list_price | float | no | The list price of your product or service. Supports 2 decimals (e.g. 10.00).
  visibility | varchar(100) | no | Accepted values are "all_users" (default), "cur_user", or an array of team_ids (e.g. [1,2,3]) and the team_ids must be teams that the authenticated user owns.
  parent_ref | int | no | An id of the product category that is the parent of this product.
  product_type | enum('Service','Inventory','Digital','Donation','Category') | no | The product type. Defaults to Service if not provided.
  taxable | boolean | no | Is the product taxable? Defaults to No if not provided.
  tax_rate | float | yes if taxable is true | The tax rate. Supports up to one decimal point of a percentage (e.g. 9.1%)
  image_url | varchar(100) | no | A url to an image of the product or service. Used on Sign Up Forms. Square is best.
  
</details>
