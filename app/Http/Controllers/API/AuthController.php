<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;

class AuthController extends Controller
{
    /**
     * Create User
     * @param Request $request
     * @return User 
     */
    function register(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'name' => 'required|string',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'number' => 'numeric|unique:users,number',
                'reference_id' => 'numeric',
                'role_id' => 'numeric',
                'gender' => 'numeric'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'number' => $request->number,
                'reference_id' => $request->reference_id,
                'role_id' => $request->role_id,
                'gender' => $request->gender
            ]);

            return response()->json([
                'status' => true,
                'message' => 'User registered successfully!',
                'token' => $user->createToken('token')->accessToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Login The User
     * @param Request $request
     * @return User
     */
    function login(Request $request)
    {
        try {
            $validateUser = Validator::make($request->all(), 
            [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if($validateUser->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'validation error',
                    'errors' => $validateUser->errors()
                ], 401);
            }

            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Check your credentials.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User logged in successfully!',
                'token' => $user->createToken('token')->accessToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }

    function logout(Request $request)
	{
		$user = Auth::guard('api')->user();

		if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);

		DB::table('personal_access_tokens')
            ->where('tokenable_id', $user->id)
            ->delete();

		return response()->json(['message' => "logged out successfully!"], 200);
	}

    function checkUser(Request $request)
	{
        $user = Auth::guard('api')->user();

        if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);
		else
			return response()->json($user);
	}

    function edit(Request $request)
    {
        $validator = $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email',
            'number' => 'string|unique:users,number',
            'current_password' => 'string',
            'new_password' => 'string',
            'gender' => 'integer',
            'reference_id' => 'string'
        ]);

        $validate_user = Auth::guard('api')->user();
        if (!$validate_user)
			return response()->json(['message' => "unauthorized user"], 401);
        $user = User::findOrFail($validate_user->id);
        
        if($request->has('name'))
            $user->name = $request->name;
        if($request->has('email'))
            $user->email = $request->email;
        if ($request->has('number'))
            $user->number = $request->number;
        if($request->has('current_password') && $request->has('new_password')){
            if (Hash::check($request->current_password, $user->password) == false)
                return response()->json(['message' => "current password is incorrect"], 422);
            else
               $user->password = bcrypt($request->new_password);
        }
        if($request->has('gender'))
            $user->gender = $request->gender;
        if($request->has('reference_id') && $user->role_id == 1)
            $user->reference_id = $request->reference_id;
        
        $user->save();
        return response()->json($user);
    }
}