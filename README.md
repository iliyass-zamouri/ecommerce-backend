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
Routes so far:
```javascript
// Public Routes :
GET: /products
GET: /categories
GET: /products/{slug}
```
---------------------------------------------
## Contributing

Feel free to pull-request into the project, and contribute in adding, modifying...


## License

This is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
