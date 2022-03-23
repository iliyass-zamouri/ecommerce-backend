<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {

        // validating the request [email, password]
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // getting the user with email match email passed in the request
        $user= User::where('email', $request->email)->first();

        // checking if the user deosnt exists or the password deosnt matches
        if (!$user || !Hash::check($request->password, $user->password)) {

            // returning the response
            return response([
                'status' => 'error',
                'message' => 'Login or password are incorrects'
            ], 404);

        }

        // generating a new token for the user
        $token = $user->createToken('my-app-token')->plainTextToken;

        // constructing a response with the user, token
        $response = [
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ];

        // returning the response
        return response($response, 200);
    }

    public function register(Request $request)
    {
        // validating the request
        $fields = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'phone' => 'required|string|unique:users,phone',
            'password' => 'required|string|confirmed'
        ]);
        // create new user record in the db
        $user = User::create([
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'phone' => $fields['phone'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        // generating a token for the new user
        $token = $user->createToken('my-app-token')->plainTextToken;

        // constructing a response
        $response = [
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ];

        // returning the response with status code of 201
        return response($response , 201);
    }


    public function sendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return [
                'message' => 'Already Verified'
            ];
        }

        $request->user()->sendEmailVerificationNotification();

        return response(['status' => 'verification-link-sent']);
    }

    public function verify(EmailVerificationRequest $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response([
                'status' => 'success',
                'message' => 'Email already verified'
            ]);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return [
            'message'=>'Email has been verified'
        ];
    }
}
