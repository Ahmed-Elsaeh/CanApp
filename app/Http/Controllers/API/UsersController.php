<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use stdClass;
use Storage;

class UsersController extends Controller
{
    function register(Request $request)
	{

		$validator = $request->validate([
			'name' => 'required|string',
			'email' => 'required|email|unique:users',
			'number' => 'string|unique:users',
			'password' => 'required|string',
			'gender' => 'numeric',
			'role_id' => 'numeric'
		]);


		$user = new User();
		$user->name = $request->get('name');
		$user->email = $request->get('email');
		$user->password = bcrypt($request->get('password'));
		$user->number = $request->get('number');
		$user->gender = $request->get('gender');
		$user->role_id = $request->get('role_id');
		$user->reference_id = $request->get('reference_id');
		
		$user->save();

		return response()->json($user);
	}

    function login(Request $request)
	{

		$email = request('email');
		$phone = request('phone');
		$password = request('password');
		$obj = new stdClass();

		if (Auth::guard('web')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')]) || Auth::guard('web')->attempt(['phone' => $phone, 'password' => $password])) {

			$user = Auth::guard('web')->user();
			if ($user->suspended == 1)
				return response()->json(['message' => 'unauthorized user'], 401);

			$obj->message = 'success';
			$obj->user = $user;
			return response()->json($obj);
		} else {
			$obj->message = 'fails';
			$obj->errors = "credentials aren't valid, please check your data";
			return response()->json($obj, 401);
		}
	}

	function logout(Request $request)
	{
		$user = Auth::guard('api')->user();

		if (!$user)
			return response()->json(['message' => "unauthorized user"], 401);

		DB::table('oauth_access_tokens')
			->where('user_id', $user->id)
			->update([
				'revoked' => true
			]);

		return response()->json(['message' => "logged out successfully!"], 200);
	}

}
