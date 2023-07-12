<?php

namespace VeskoDigital\LaraApp\Http\Controllers;

use VeskoDigital\LaraApp\Http\Controllers\Controller;
use VeskoDigital\LaraApp\Http\Requests\UserSnapRequest;
use VeskoDigital\LaraApp\Http\Services\LaUserDeviceService;

/**
 * Class LaraAppSiteAPIController
 *
 * @property LaUserDeviceService $laUserDeviceService
 * @package VeskoDigital\LaraApp\Http\Controllers
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
