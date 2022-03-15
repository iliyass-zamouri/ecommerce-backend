<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Specification;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function info()
    {
        // getting authenticated user
        $user = Auth::user();

        // checking for auth status
        if($user == null){

            // constructing a response for error
            $response = [
                'status' => 'unauthenticated',
                'msg' => 'Authentication is required to access this resource'
            ];
            return response($response, 403);

        }

        // constructing a response for success
        $response = [
            'status' => 'success',
            'data' => $user
        ];

        // returing user info
        return response($response, 200);

    }

    public function update(Request $request)
    {
        // getting the authenticated user
        $user = Auth::user();

        // checking if the response will change the email
        if($request->email != null)
        {
            // validating the email
            $request->validate(['email' => 'string']);

            // checking if another user with this email already exists
            // getting users where email match email passed in the request
            // and the id is diffrent from the authenticated user
            $usedEmail = User::where('email' == $request->email)->where('id', '<>' , $user->id)->get();

            // checking the list if there is a record
            if($usedEmail->count() != 0){
                // returing an error response
                return response([
                    'status' => 'error',
                    'msg' => 'email already been used'
                ], 403);
            }
            // else changing the email to the request email
            $user->email = $request->email;
        }

        // checking if the response will change the the phone number
        if($request->phone != null)
        {
            // validating the email
            $request->validate(['phone' => 'string']);

            // checking if another user with this phone number already exists
            // getting users where the phone number match phone number passed in the request
            // and the id is diffrent from the authenticated user
            $usedEmail = User::where('phone' , $request->phone)->where('id', '<>' , $user->id)->get();

            // checking the list if there is a record
            if($usedEmail->count() != 0){
                // returing an error response
                return response([
                    'status' => 'error',
                    'msg' => 'phone number already been used'
                ], 403);
            }
            // else changing the phone number to the request phone number
            $user->phone = $request->phone;
        }

        // checking if the response will change the first name
        if($request->first_name != null){
            // validating the first name
            $request->validate(['first_name' => 'string']);
            // changing the last name of the user to the requested one
            $user->first_name = $request->first_name;
        }
        // checking if the response will change the first name
        if($request->last_name != null){
            // validating the last name
            $request->validate(['last_name' => 'string']);
            // changing the last name of the user to the requested one
            $user->last_name = $request->last_name;
        }

        // saving the user with the new info
        $user->save();

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $user
        ];
        // returning the response
        return response($response, 201);

    }

    public function addToCart(Request $request)
    {

        // validating the request
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer',
            'specification_id' => 'required|integer'
        ]);

        // getting the product
        $product = Product::find($request->product_id);

        // checking id the product deosnt exists
        if($product == null){
            // returning a not found response
            return response(['status' => 'error', 'msg'=> 'product deosnt exist'], 404);
        }

        // checking if product already in cart
        $exists = \App\Models\Cart::where('product_id', $product->id)->where('user_id', Auth::user()->id)->get();
        if($exists->count() != 0){
            return response(['status' => 'error', 'msg' => 'Product is already in the cart']);
        }

        // getting the Specification
        $specification = Specification::where( 'id', $request->specification_id )->where('product_id', $request->product_id)->get()->first();

        // checking whether the specification is linked to the product
        if($specification == null){
            return response(['status' => 'error', 'msg' => 'specification deosnt match product'], 404);
        }

        // adding to cart
        Cart::add($product->id, $product->label, $request->quantity, $specification->price);

        // adding it to the database
        $cart = \App\Models\Cart::create([
            'product_id' => $product->id,
            'user_id' => Auth::user()->id,
            'specification_id' => $request->specification_id,
            'quantity' => $request->quantity
        ]);
        return response([
            'status' => 'success',
            'data' => $cart->with('product')->with('specification')->get()
        ], 201);

    }
    public function updateCart(Request $request)
    {

        // validating the requested product
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        // getting the cart
        $cart = \App\Models\Cart::userCart()->where('product_id' , $request->product_id);

        // checking id the product deosnt exists
        if($cart == null){
            // returning a not found response
            return response(['status' => 'error', 'msg'=> 'product deosnt exist in the cart'], 404);
        }

        // validating the quantity, if it's not null, then updating the value of the cart.
        if($request->quantity != null){
            $request->validate(['quantity' => 'required|integer']);
            $cart->quantity = $request->quantity;
        }

        // validating the specification id, if it's not null, then updating the value of the cart.
        if($request->specification_id != null){
            $request->validate(['specification_id' => 'required|integer']);
            $cart->specification_id = $request->specification_id;
        }

        // getting the Specification
        $specification = Specification::where( 'id', $request->specification_id )->where('product_id', $request->product_id)->get()->first();

        // checking whether the specification is linked to the product
        if($specification == null){
            return response(['status' => 'error', 'msg' => 'specification deosnt match product'], 404);
        }

        // saving the cart to the database
        $cart->save();

        // returning a response with the new cart details
        return response([
            'status' => 'success',
            'data' => $cart->with('product')->with('specification')->get()
        ], 201);

    }

    public function getCart()
    {

        // getting the cart of the current logged in user
        $cart = \App\Models\Cart::userCart()->with('specification')->with('product')->get();

        // returning a response
        return response([
            'status' => 'success',
            'cartdb' => $cart,
            'cartcstm' => Cart::content()
        ]);
    }
    public function wipeCart()
    {

        // deleting all entries of the current logged in user from the cats table
        $delete = \App\Models\Cart::userCart()->delete();

        // returning a response
        return response(['status' => 'success', 'data' => "$delete Deleted records" ], 200);

    }
    public function deleteProductFromCart(Product $product)
    {

        // deleting the product from the cart linked to the user
        $result = \App\Models\Cart::userCart()->where('product_id' ,$product->id)->get()->first()->delete();

        // returning a response
        return response([
            'status' => 'success',
            'data' => $result,
            ], 200);

    }
}
