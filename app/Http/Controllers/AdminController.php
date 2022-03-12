<?php

namespace App\Http\Controllers;

use App\City;
use App\Models\Category;
use App\Models\Photo;
use App\Models\Specification;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class AdminController extends Controller
{

    public function storeProduct(Request $request)
    {
        // validating the request
        $request->validate([
            'label' => 'required|string',
            'description' => 'required|string',
            'gender' => 'required|string',
            'mark_id' => 'required',
            'specifications' => 'array',
            'category_id' => 'string'
        ]);

        // creating new product record in the db
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
                'size' => $spec['size'],
                'product_id' => $product->id,
                'price' => $spec['price'],
            ]);
        }

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $product
        ];

        // returning the response with status code of 201
        return response($response,201);

    }

    public function addPhotoToProduct(Request $request)
    {

        // validating the id and the image file types, dimensions & size
        $request->validate([
            'product_id' => 'required|integer',
            'label' => 'required|string',
//            'photo' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048|dimensions:min_width=800,min_height=495,max_width=800,max_height=495',
             'photo' => 'mimes:jpeg,jpg,png,webp|max:2048'
        ]);

        // Getting the city with requested id
        $product = Product::find($request->product_id);

        // Getting the file from the request into a File object
        $file = $request->file('photo');

        $name = Str::slug($request->label);
        // Changing the file name to the generated id and adding the file extension
        $filename = $name.'.'.File::extension($file->getClientOriginalName());

        // Uploading the file to the public path under the photos folder
        $file->move(public_path().'/photos/products', $filename);


        // Uploading the photo
        $photo = Photo::create([
            'label' => $request->label,
            'file_name' => public_path().'/photos/products/' . $filename,
            'product_id' => $product->id,
        ]);

        // Returning the response and the photo data
        return response(['status' => 'success', 'data' => $photo ], 201);

    }

    // add new category of products
    public function addCategory(Request $request){

        //validating the request params
        $request->validate([
            'label' => $request->label
        ]);

        // creating the category
        $category = Category::create([
            'slug' => $request->slug == null ? Str::slug($request->label) : $request->slug,
            'label' => $request->label,
        ]);

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $category
        ];

        // returning a response with status code of 201
        return response($response, 201);

    }

}
