<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreationRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Create New User function
     * @param UserCreationRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function createUser(UserCreationRequest $request){
        try {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            $user= User::create($data);

            $user->sendEmailVerificationNotification();

            return response()->json([
                'status' => 1,
                'message'=> 'User Created Successfully',
                'user' => $user,
                'token' => $user->createToken("API Token")->plainTextToken
            ], 200);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Fetch all Users
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUsers(){
        try {
            $users= User::all();

            return response()->json([
                'status' => 1,
                'message'=> 'Here is the list of users',
                'users' => $users,
            ], 200);

        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Fetch one user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUser($userId) {
        try {
            $user= User::find($userId);

            if($user) {
                return response()->json([
                    'status' => 1,
                    'message'=> 'Here is the user you looked for',
                    'user' => $user,
                ], 200);
            }

            return response()->json([
                'status' => 1,
                'message'=> 'User not found',
            ], 200);


        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Delete a user
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteUser($userId){
        try {
            $user= User::find($userId);

            if($user) {
                $user->delete();
                return response()->json([
                    'status' => 1,
                    'message'=> 'User has been deleted successfully',
                    'user' => $user,
                ], 200);
            }

            return response()->json([
                'status' => 1,
                'message'=> 'User not found',
            ], 200);


        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
