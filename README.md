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
- id
- first_name
- last_name
- email
- password
  
### products 
- id
- label
- description
- mark
### categories
- id 
- label
### marks 
- id 
- label
- logo
### specifications 
- id
- size
- price
### carts 
- id
- product_id
- user_id
### wishlists 
- id
- product_id
- user_id 
### subscriptions 
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

## Contributing

Feel free to pull-request into the project, and contribute in adding, modifying...


## License

This is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
