<?php

namespace WooSignal\LaraApp\Http\Controllers;

use WooSignal\LaraApp\Http\Controllers\Controller;
use WooSignal\LaraApp\Http\Middleware\Authenticate;
use Illuminate\Http\Request;

class LaraAppController extends Controller
{

	public function __construct()
	{
		$this->middleware(Authenticate::class);
	}

	/**
     * Index page for linking LaraApp
     *
     * @return void
     */
    public function index(Request $request)
    {
        $payloadInfo = [
            'name' => config('laraapp.app_name', 'Laravel App'),
            'url' => $request->getSchemeAndHttpHost(),
            'path' => config('laraapp.path', 'lara-app'),
            'login' => $request->getSchemeAndHttpHost() . '/' . config('laraapp.path', 'lara-app') . '/auth/login',
        ];

        return view('lara-app::link-app', compact('payloadInfo'));
    }
}