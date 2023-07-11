<?php

namespace WooSignal\LaraApp\Http\Controllers;

use WooSignal\LaraApp\Http\Controllers\Controller;
use WooSignal\LaraApp\Http\Requests\LoginRequest;
use WooSignal\LaraApp\Models\LaUser;
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
