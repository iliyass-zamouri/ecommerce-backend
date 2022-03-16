<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Mark;
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
    public function productsbyCategory(Category $category)
    {

        // getting the product of the requested category
        $category->products = $category->products()->get();

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
    public function productsByMarks(Mark $mark)
    {
        // getting the product of the mark
        $mark->products = $mark->products()->get();
        // returning a response
        return response(['status' => 'success', 'data' => $mark ], 200);
    }
}
