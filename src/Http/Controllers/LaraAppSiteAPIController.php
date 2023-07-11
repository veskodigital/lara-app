<?php

namespace WooSignal\LaraApp\Http\Controllers;

use WooSignal\LaraApp\Http\Controllers\Controller;
use WooSignal\LaraApp\Http\Requests\UserSnapRequest;
use WooSignal\LaraApp\Http\Services\LaUserDeviceService;

/**
 * Class LaraAppSiteAPIController
 *
 * @property LaUserDeviceService $laUserDeviceService
 * @package WooSignal\LaraApp\Http\Controllers
 */
class LaraAppSiteAPIController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    	$this->laUserDeviceService = resolve(LaUserDeviceService::class);
    }

    /**
     * Get user snapshot
     *
     * @return Response
     */
    public function getUserSnap(UserSnapRequest $request)
    {
        $when = $request->when;
		$response = $this->laUserDeviceService->sendUserSnap($when);
		return response()->json($response);
    }
}
