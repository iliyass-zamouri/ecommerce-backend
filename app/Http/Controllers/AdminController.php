<?php

namespace App\Http\Controllers;

use App\City;
use App\Models\Category;
use App\Models\Mark;
use App\Models\Photo;
use App\Models\Specification;
use App\Models\User;
use Illuminate\Http\Request;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
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
            'mark_id' => 'integer',
            'specifications' => 'array',
            'category_id' => 'integer'
        ]);

        // checking if the slug already been used
        $exists = Product::where('slug', $request->slug == null ? Str::slug($request->label) : $request->slug)->get();
        // checking if it has been found (slug)
        if($exists->count() != 0){
            // returning an error response
            return response(['status' => 'error', 'msg' => 'slug already been used'], 200);
        }

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
        foreach($request->specifications as $spec)
        {
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

    public function updateProduct(Request $request)
    {
        // validating the request
        $request->validate([
            'label' => 'required|string',
            'description' => 'required|string',
            'gender' => 'required|string',
            'mark_id' => 'integer',
            'specifications' => 'array',
            'category_id' => 'integer'
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
        foreach($request->specifications as $spec)
        {
            Specification::create([
                'size' => $spec['size'],
                'product_id' => $product->id,
                'price' => $spec['price'],
            ]);
        }

        // constructing a response with the product in it
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
            // 'photo' => 'required|image|mimes:jpg,png,jpeg,webp|max:2048|dimensions:min_width=800,min_height=495,max_width=800,max_height=495',
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

    public function deletePhotoFromProduct(Photo $photo)
    {

        // checking wether the photo has been found
        if($photo == null){
            return response([
                'status' => 'error',
                'msg' => 'photo not found'
            ], 404);
        }

        // Checking if the file exists in the path directory under the photos folder
        if(File::exists($photo->file_name)){
            // deleting the file from the public folder
            File::delete($photo->file_name);
        }

        // Delete the entry that belongs to the image file
        $result = $photo->delete();

        // returning a response back
        return response([
            'status' => 'success',
            'data' => $result
        ], 200);

    }

    public function deleteProduct(Product $product)
    {

        // checking wether the product has been found
        if($product == null){
            return response([
                'status' => 'error',
                'msg' => 'product not found'
            ], 404);
        }

        // deleting the product based on the params,
        // the route is passing a {product} as id,
        // the controller should auto find the product.
        $deleted = $product->delete();

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $deleted
        ];

        // returning the response
        return response($response, 200);

    }

    // add new category of products
    public function storeCategory(Request $request)
    {

        //validating the request params
        $request->validate([
            'label' => 'required|string',
        ]);

        // validating the slug if it exists
        if($request->slug != null){
            $request->validate(['slug', 'required|string']);
        }

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
    public function deleteCategory(Category $category)
    {

        // checking wether the category has been found
        if($category == null){
            return response([
                'status' => 'error',
                'msg' => 'category not found'
            ], 404);
        }

        // deleting the product based on the params,
        // the route is passing a {category} as id,
        // the controller should auto find the product.
        $deleted = $category->delete();

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $deleted
        ];

        // returning the response
        return response($response, 200);

    }

    // add new mark of products
    public function storeMark(Request $request){

        //validating the request params
        $request->validate([
            'label' => 'required|string',
            'logo' => 'mimes:jpeg,jpg,png,webp|max:2048'
        ]);

        // validating the slug if it exists
        if($request->slug != null){
            $request->validate(['slug', 'required|string']);
        }

        // Getting the file from the request into a File object
        $file = $request->file('photo');

        $name = $request->slug == null ? Str::slug($request->label) : $request->slug;
        // Changing the file name to the generated id and adding the file extension
        $filename = $name.'.'.File::extension($file->getClientOriginalName());

        // Uploading the file to the public path under the photos folder
        $file->move(public_path().'/photos/marks', $filename);


        // creating the category
        $mark = Mark::create([
            'slug' => $request->slug == null ? Str::slug($request->label) : $request->slug,
            'label' => $request->label,
            'logo' => public_path().'/photos/marks/' . $filename,
        ]);

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $mark
        ];

        // returning a response with status code of 201
        return response($response, 201);

    }
    public function deleteMark(Mark $mark)
    {
        // checking wether the category has been found
        if($mark == null){
            return response([
                'status' => 'error',
                'msg' => 'mark not found'
            ], 404);
        }

        // deleting the product based on the params,
        // the route is passing a {category} as id,
        // the controller should auto find the product.
        $deleted = $mark->delete();

        // constructing a response
        $response = [
            'status' => 'success',
            'data' => $deleted
        ];

        // returning the response
        return response($response, 200);

    }






    // admin data private methods
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

        if(!$user){
            return response([
                'status' => 'unauthenticated',
                'msg' => 'Authentification is required'
            ], 404);
        }

        // checking if the user deosnt exists or the password deosnt matches
        if (!Hash::check($request->password, $user->password)) {
            // returning the response
            return response([
                'status' => 'error',
                'msg' => 'Password incorrect'
            ], 404);
        }

        // checking if the response will change the email
        if($request->email != null)
        {
            // validating the email
            $request->validate(['email' => 'string']);

            // checking if another user with this email already exists
            // getting users where email match email passed in the request
            // and the id is diffrent from the authenticated user
            $usedEmail = User::where('email' , $request->email)->where('id', '<>' , $user->id)->get();

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

    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
        return request()->ip(); // it will return server ip when no client ip found
    }

}
