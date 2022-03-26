<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
class VerificationController extends Controller
{

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'send');
    }
    public function send()
    {
        if (Auth::user()->hasVerifiedEmail()) {
            return response([
                'status' => 'error',
                'message' => 'Email already been verified'
            ]);
        }

        Auth::user()->sendEmailVerificationNotification();

        return response([
            'status' => 'success',
            'message' => 'verification-link-sent'
        ]);
    }
    public function verify(Request $request)
    {
        $user = User::findOrFail($request->id);

        if ($user->email_verified_at) {
            return view('message', [
                'status' => 'error',
                'message' => 'Email already been verified'
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
        }

        return view('message',[
            'status' => 'success',
            'message'=>'Email has been verified'
        ]);
    }

//    public function verify(\Illuminate\Foundation\Auth\EmailVerificationRequest $request)
//    {

//        if (!$request->hasValidSignature()) {
//            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
//        }

//        $user = User::find($user_id);
//
//        if (!$user->hasVerifiedEmail()) {
//            $user->markEmailAsVerified();
//        }
//
//        if ($user->markEmailAsVerified()) {
//            event(new Verified($user));
//        }

//        if ($request->user()->hasVerifiedEmail()) {
//            return response([
//                'status' => 'success',
//                'msg' => 'Email already verified'
//            ]);
//        }
//
//        if ($request->user()->markEmailAsVerified()) {
//            event(new Verified($request->user()));
//        }

//        return response([
//            'status' => 'success',
//            'msg'=>'Email has been verified'
//        ]);
//    }

}
