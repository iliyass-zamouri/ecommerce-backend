# Ecommerce - Backend (API)

## About This Project

This project is an Ecommerce Laravel Rest API that can be linked to any FrontEnd WebApp.
## Features

- [✔] Authentication: Laravel Sanctum - Token based auth system.
- [...] Generating pdf invoices.
- [✔] Add to cart.
- [...] Search/Filter options.
- [...] Suggestions (suggest other products).
- [✔] Mailing.
- [...] Coupon codes.

## Modeling

### users
✔ migration
✔ model
✔ factory (faker)
- id
- first_name
- last_name
- phone
- email
- password
- is_admin

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
- slug
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
//-----------------//
// Public Routes:: //
//-----------------//

// subscribe to the newsletter
POST: /subscribe
// getting all the products,
// this will include pagination later.
GET: /products
// getting all the categories
GET: /categories
// to get all the product within a category
GET: /categories/[slug]
// to get the product details, this will include 
// suggestions later. ( based on a traking system,
// that i will add when forking this repo )
GET: /products/[slug]
// get all the marks
GET: /marks
GET: /marks/[slug]

//----------------//
// Login Routes:: //
//----------------//

POST: /login
POST: /register

//-------------------------------------//
// User Routes:: prefixed with (/user) //
//-------------------------------------//

// for getting user personal data:
GET: / 
// for updating data:
POST: /  
// subscribe to the newsletter
GET: /subscribe
// to add product to cart
POST: /cart
// to update cart
POST: /cart/update
// to get cart details
GET: /cart
// to delete product from cart:
GET: /cart/[id]/delete
// to wipe all cart (empty up)
GET: /cart/wipe

//---------------------------------------//
// Admin Routes:: prefixed with (/admin) //
//---------------------------------------//

// personal data
GET: / 
// updating the personal data
POST: / 
// adding a product
POST: /products
// deleting a product
GET: /products/[id]/delete
// adding a photo to a product
POST: /products/photos
// deleting a photo from a product
GET: /products/photos/[id]/delete
// adding a category
POST: /categories
// deleting a category
GET: /categories/[id]/delete
// adding a mark
POST: /marks
// deleting a mark 
GET: /marks/[id]/delete
// adding a product to auth. user's wishlist
GET: /wishlist/[slug]
// getting auth. user's wishlist
GET: /wishlist
// deleting all auth. user's products from the wishlist
GET: /wishlist/wipe
// deleting a product from auth. user's wishlist
GET: /wishlist/[slug]/delete
```
##Mailing

#### make mail class 
```
php artisan make:mail Subscribe --markdown=emails.subscribe
```

####configuring the smtp to use gmail based email:

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.googlemail.com
MAIL_PORT=465
MAIL_USERNAME=[email]@gmail.com
MAIL_PASSWORD=[password]
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=[email]@[host].com
MAIL_FROM_NAME="${APP_NAME}"
```

- See how to configure email class in [Subscribe] class.
- Check the markdown in views/email/subscribe.blade.php.
- Check [PublicController] function [subscribe]. 
- activate the unsecured apps in the google account settings!!
---------------------------------------------
## Contributing

Feel free to pull-request into the project, and contribute in adding, modifying...


## License

This is an open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
