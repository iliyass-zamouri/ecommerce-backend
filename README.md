# ecommerce - API

## About This Project

This project is an Ecommerce Laravel Rest API that can be linked to any FrontEnd WebApp.
## Features

- [...] Authentication: Laravel Sanctum - Token based auth system.
- [...] Generating pdf invoices.
- [...] Add to cart.
- [...] Search/Filter options.
- [...] Suggestions (suggest other products).
- [...] Mailing.
- [...] Coupon codes.

## Modeling

### users
✔ migration
✔ model
✔ factory (faker)
- id
- first_name
- last_name
- email
- password
  
### products 
✔ migration
✔ model
✔ factory (faker)
- id
- slug
- label
- description
- gender
- mark_id
- category_id

### colors
- id
- product_id
- color

### photos
✔ migration
✔ model
- id
- label
- file_name
- product_id

### categories
✔ migration
✔ model
- id 
- slug
- label

### marks 
✔ migration
✔ model
- id 
- label
- logo
### specifications 
✔ migration
✔ model
- id
- size
- product_id
- price

### carts 
✔ migration
✔ model
- id
- product_id
- user_id
### wishlists
✔ migration
✔ model
- id
- product_id
- user_id 
### subscriptions 
✔ migration
✔ model
- id
- user_id
- token 
### orders
- id
- note
- invoice
- coupon
- shipping
- address
### payments 
- id
- order_id
  
---------------------------------------------
Routes:
```javascript

// Public Routes :
GET: /products
GET: /categories
GET: /products/{slug}

// Login Routes:
POST: /login
POST: /register

// User Routes: prefixed with (/user) 
// for getting user personal data:
GET: / 
// for updating data:
POST: /  
// to add product to cart
POST: /cart
// to update cart
POST: /cart/update
// to get cart details
GET: /cart
// to delete product from cart:
GET: /cart/delete/{id}
// to wipe all cart (empty up)
GET: /cart/wipe

// Admin Routes: prefixed with (/admin)
// personal data
GET: / 
// updating the personal data
POST: / 
// adding a product
POST: /products
// deleting a product
GET: /products/delete/{id}
// adding a photo to a product
POST: /products/photos
// deleting a photo from a product
GET: /products/photos/delete/{id}
// adding a category
POST: /categories
// deleting a category
GET: /categories/delete/{id}
// adding a mark
POST: /marks
// deleting a mark 
GET: /marks/delete/5
```
---------------------------------------------
## Contributing

Feel free to pull-request into the project, and contribute in adding, modifying...


## License

This is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
