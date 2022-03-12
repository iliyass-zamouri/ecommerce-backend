<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

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
}
