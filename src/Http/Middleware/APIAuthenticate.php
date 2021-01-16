<?php

namespace WooSignal\LaraApp\Http\Middleware;

use WooSignal\LaravelFCM\Models\LaraAppUser;
use WooSignal\LaravelFCM\Models\LaUserDevice;
use WooSignal\LaravelFCM\Models\LaAppRequest;

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
        $token = $request->bearerToken();
        $laUser = LaraAppUser::where('app_token', '=', $token)->where('is_active', 1)->first();

        if (!is_null($laUser)) {
            $dmeta = $request->header('X-DMETA');
            $dmeta = json_decode($dmeta, true);
            $pushToken = '';
            if (isset($dmeta['push_token'])) {
                $pushToken = $dmeta['push_token'];
            }
            $device = LaUserDevice::updateOrCreate(
                [
                    'uuid' => $dmeta['uuid'],
                    'push_token' => $pushToken,
                ],
                [
                    'la_user_id' => $laUser->id,
                    'name' => $dmeta['model'],
                    'version' => $dmeta['version'],
                    'push_token' => $pushToken,
                    'push_settings' => json_encode(['newUsers' => true, 'errors' => true]),
                    'is_active' => 1,
                    'display_name' => $dmeta['brand'],
                    'uuid' => $dmeta['uuid']
                ]
            );

            if (!is_null($device)) {
                $laAppRequest = LaAppRequest::create([
                    'device_id' => $device->id,
                    'request_type' => 'app_request',
                    'ip' => $request->ip(),
                ]); 
            }
            $request->request->add(['udevice' => $device]);
            return $next($request);
        }

        return abort(403);
    }
}
