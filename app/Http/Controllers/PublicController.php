<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Database\Factories\UserFactory;
use Illuminate\Http\Request;

class PublicController extends Controller
{

    public function index()
    {
        UserFactory::times(5);
        $products = Product::allInfo()->get();
        $response = [
            'status' => 'success',
            'data' => $products
        ];
        return response(
            $response, 200
        );
    }

    public function show($slug)
    {
        $product = Product::where('slug' , $slug)->allInfo()->get()->first();
        $response = [
            'status' => 'success',
            'data' => $product
            ];
        return response($response, 200);
    }

}
