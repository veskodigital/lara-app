<?php

namespace WooSignal\LaraApp\Http\Controllers;

use Symfony\Component\Console\Exception\RuntimeException;
use WooSignal\LaravelFCM\Http\Controllers\Controller;
use Illuminate\Http\Request;
// use WooSignal\LaravelFCM\Http\Requests\PushTokenRequest;
use WooSignal\LaravelFCM\Http\Requests\PushNotificationsUpdateRequest;
use Artisan;
use File;

class LaraAPIController extends Controller
{

    private $userDevice;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->userDevice = $request->input('udevice');
            
            return $next($request);
        });
    }

    /**
     * Stores the users push token
     *
     * @return mixed
     */
    public function storeToken(Request $request)
    {
        $valid = $request->validated();
        $pushToken = $valid['push_token'];

        if (!is_null($this->userDevice)) {
            $this->userDevice->update([
                'push_token' => $pushToken
            ]);

            return response()->json(['status' => 200]);
        }
        return response()->json(['status' => 500]);
    }
}