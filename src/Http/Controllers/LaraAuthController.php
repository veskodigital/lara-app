<?php

namespace VeskoDigital\LaraApp\Http\Controllers;

use VeskoDigital\LaraApp\Http\Controllers\Controller;
use VeskoDigital\LaraApp\Http\Requests\LoginRequest;
use VeskoDigital\LaraApp\Models\LaUser;
use Hash;

class LaraAuthController extends Controller
{

	/**
     * Login method which checks the database for a given user and returns mixed
     *
     * @return mixed
     */
	public function login(LoginRequest $request)
	{
		$valid = $request->validated();
		$email = $valid['email'];
		$password = $valid['password'];
		$LaUser = LaUser::where('email','=', $email)->where('is_active', 1)->first();

		if (!is_null($LaUser) && (Hash::check($password, $LaUser->password))) {
			return response()->json([
				'status' => 200,
				'token' => $LaUser->apiToken()
			]);
		}
		return response()->json(['status' => 500]);
	}
}
