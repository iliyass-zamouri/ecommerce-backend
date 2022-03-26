<?php

namespace App\Http\Controllers;

use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class PasswordController extends Controller
{

    public function resetForm($token)
    {
        return view('password-reset',  compact('token'));
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status == Password::RESET_LINK_SENT) {
            return response([
                'status' => 'success',
                'message' => __($status)
            ]);
        }

        throw ValidationException::withMessages([
            'status' => 'error',
            'message' => [trans($status)],
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => ['required', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            $response = [
                'status' => 'success',
                'message'=> 'Password reset successfully'
            ];
            return $request->acceptsHtml() ? view('message', $response) : response($response, 200);
        } else {
            $response = [
                'status' => 'error',
                'message'=> __($status)
            ];
            return $request->acceptsHtml() ? view('message', $response) : response($response, 500);
        }


    }

}
