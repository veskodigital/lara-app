<?php

namespace WooSignal\LaraApp\Http\Controllers;

use WooSignal\LaraApp\Http\Controllers\Controller;
use WooSignal\LaraApp\Models\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use WooSignal\LaraApp\Models\LaraAppUser;
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
		$hasUser = LaraAppUser::where('email','=', $email)->where('is_active', 1)->first();

		if (!is_null($hasUser) && (Hash::check($password, $hasUser->password))) {
			return response()->json(['status' => 200, 'token' => $hasUser->app_token]);
		}
		return response()->json(['status' => 500]);
	}
}