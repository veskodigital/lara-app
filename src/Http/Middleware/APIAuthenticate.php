<?php

namespace WooSignal\LaraApp\Http\Middleware;

use WooSignal\LaraApp\Models\LaUserDevice;
use WooSignal\LaraApp\Models\LaAppRequest;

class APIAuthenticate
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|null
     */
    public function handle($request, $next)
    {
        $dmeta = $request->header('X-DMETA');
        $dmeta = json_decode($dmeta, true);

        $pushToken = '';
        if (isset($dmeta['push_token'])) {
            $pushToken = $dmeta['push_token'];
        }

        $user = $request->user();
        $device = LaUserDevice::updateOrCreate(
            [
                'uuid' => $dmeta['uuid']
            ],
            [
                'la_user_id' => $user->id,
                'name' => $dmeta['model'],
                'version' => $dmeta['version'],
                'push_token' => $pushToken,
                'display_name' => $dmeta['brand'],
                'uuid' => $dmeta['uuid']
            ]
        );

        if ($device->wasRecentlyCreated) {
            $device->update([
                'push_settings' => json_encode([
                    'observer_created_user' => true,
                    'yesterday_user_created_count' => true,
                    'weekly_user_created_count' => true,
                    'monthly_user_created_count' => true
                ]),
                'is_active' => 1,
            ]);
        }

        if (empty($device)) {
            return abort(403, 'Unable to create device');
        }

        $laAppRequest = LaAppRequest::create([
            'la_user_device_id' => $device->id,
            'request_type' => $request->getRequestUri(),
            'ip' => $request->ip(),
        ]); 

        $request->request->add(['udevice' => $device, 'app_request' => $laAppRequest]);
        
        return $next($request);
    }
}
