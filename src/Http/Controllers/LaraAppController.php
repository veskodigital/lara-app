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
        $url = $request->getSchemeAndHttpHost();
        $url = config('app.url');
        
        $payloadInfo = [
            'name' => config('app.name', 'Laravel App'),
            'url' => $url,
            'path' => config('laraapp.path', 'lara-app'),
            'login' => $url . '/' . config('laraapp.path', 'lara-app') . '/auth/login',
        ];

        return view('lara-app::link-app', compact('payloadInfo'));
    }
}