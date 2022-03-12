<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use App\Models\Specification;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public function storeProduct(Request $request)
    {

        $request->validate([
            'slug' => 'string',
            'label' => 'required|string',
            'description' => 'required|string',
            'gender' => 'required|string',
            'mark_id' => 'required',
            'specification' => 'array',
            'photos' => 'array',
            'category_id' => 'string'
        ]);

        $product = Product::create([
            'slug' => $request->slug == null ? Str::slug($request->label) : $request->slug,
            'label' => $request->label,
            'description' => $request->description,
            'gender' => $request->gender,
            'mark_id' => $request->mark_id,
            'category_id' => $request->category_id
        ]);

        // looping specifications insertion [size, price]
        foreach($request->specifications as $spec){
            Specification::create([
                'size' => $spec->size,
                'product_id' => $product->id,
                'price' => $spec->price,
            ]);
        }

        $response = [
            'status' => 'success',
            'data' => $product
        ];

        return response($response,201);

    }

}
