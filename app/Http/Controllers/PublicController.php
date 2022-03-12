<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;

class PublicController extends Controller
{

    public function allProducts()
    {
        $products = Product::allInfo()->get();
        $response = [
            'status' => 'success',
            'data' => $products
        ];
        return response(
            $response, 200
        );
    }

    public function showProduct($slug)
    {
        $product = Product::where('slug' , $slug)->allInfo()->get()->first();
        $response = [
            'status' => 'success',
            'data' => $product
            ];
        return response($response, 200);
    }

    public function allCategories()
    {
        $categories = Category::all();
        $response = [
            'status' => 'success',
            'data' => $categories
        ];
        return response(
            $response, 200
        );
    }
}
