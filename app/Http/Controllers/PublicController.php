<?php

namespace App\Http\Controllers;

use App\Mail\Subscribe;
use App\Models\Category;
use App\Models\Mark;
use App\Models\Product;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class PublicController extends Controller
{

    public function allProducts()
    {
        // getting all the products with thier all the relations
        $products = Product::allInfo()->get();

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $products
        ];
        // returning the response
        return response(
            $response, 200
        );
    }

    public function showProduct($slug)
    {
        // finding the prodct with the given slug
        $product = Product::where('slug' , $slug)->allInfo()->get()->first();

        if($product == null) {
            // constructing a response for the error
            $response = [
                'status' => 'error',
                'msg' => 'product with given slug not found'
            ];
            return response($response, 400);
        }

        // constructing a response for success
        $response = [
            'status' => 'success',
            'data' => $product
        ];

        // returning the response
        return response($response, 200);

    }

    public function allCategories()
    {
        // getting all the categories
        $categories = Category::all();

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $categories
        ];
        // returning the response
        return response(
            $response, 200
        );
    }
    public function productsbyCategory($slug)
    {

        $category = Category::where('slug', $slug)->with('products')->get()->first();
        // getting the product of the requested category
        // $category->products = $category->products()->get();

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $category
        ];
        // returning the response
        return response(
            $response, 200
        );
    }
    public function allMarks()
    {

        // getting all the marks
        $marks = Mark::all();
        // returning the response
        return response(['status' => 'success', 'data' => $marks ], 200);

    }
    public function productsByMarks($slug)
    {
        // getting the mark, with products
        $mark = Mark::where('slug', $slug)->with('products')->get()->first();
        // getting the product of the mark
        // $mark->products = $mark->products()->get();
        // returning a response
        return response(['status' => 'success', 'data' => $mark ], 200);
    }
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:subscriptions'
        ]);


        if ($validator->fails()) {
            return response(['status' => 'error', 'msg' => $validator->errors()], 422);
        }

        $subscribed = Subscription::where('email', $request->email)->get();

        if($subscribed->count() != 0){
            return response([
                'status' => 'error',
                'msg' => 'Email already has been subscribed'
            ], 200);
        }

        $subscriber = Subscription::create([
                'email' => $request->email,
                'token' => uniqid()
            ]
        );

        $user = new \stdClass();
        $user->email = $request->email;
        $user->first_name = $request->email;

        if ($subscriber) {
            Mail::to($user->email)->send(new Subscribe($user));
            return response([
                'success' => true,
                'message' => "Thank you for subscribing to our email, please check your inbox"
            ],200);
        }
    }
}
