<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserLoginRequest;
use App\Models\User;
use Exception;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Login user
     * @param UserLoginRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function loginUser(UserLoginRequest $request) {
        $data = $request->all();
        $user = User::where('email', $data['email'])->first();

        if(!Auth::attempt($data)) {
            return response()->json([
                'status' => 0,
                'message'=> 'Incorrect email or password'
            ], 401);
        }

        if(!$user->hasVerifiedEmail()) {
            return response()->json([
                'status' => 1,
                'message'=> 'Please verify email first',
                'token' => $user->createToken("API Token")->plainTextToken
            ], 200);
        }

        return response()->json([
            'status' => 1,
            'message'=> 'User Logged in successfully',
            'user' => $user,
            'token' => $user->createToken("API Token")->plainTextToken
        ], 200);
    }

    /**
     * Re-send verification email
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendVerificationEmail(Request $request){
        try{
            if($request->user()->hasVerifiedEmail()) {
                return response()->json([
                    'status' => 1,
                    'message'=> 'Already verified',
                ], 200);
            }

            $request->user()->sendEmailVerificationNotification();

            return response()->json([
                'status' => 1,
                'message'=> 'Email verification link sent',
            ], 200);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function verify(EmailVerificationRequest $request) {
        if($request->user()->hasVerifiedEmail()){
            return response()->json([
                'status' => 1,
                'message'=> 'Email has already been verified',
            ], 200);
        }

        if($request->user()->markEmailAsVerified()) {
            return response()->json([
                'status' => 1,
                'message'=> 'Email has been verified',
            ], 200);
        }
    }

    public function verificationNotice() {
        return response()->json([
            'status' => 0,
            'message'=> 'Email not verified. Please verify email first',
        ], 200);
    }
}
