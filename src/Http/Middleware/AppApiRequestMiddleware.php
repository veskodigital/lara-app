<?php

namespace WooSignal\LaraApp\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\UserDevice;
use App\Models\AppAPIRequest;

class AppApiRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $path = $request->path();

        if (!is_string($request->header('X-DMeta'))) {
            abort(401);
        }

        $dMeta = json_decode($request->header('X-DMeta'), true);

        if (isset($dMeta['device']) && $dMeta['device'] == 'preview') {
            return $next($request);
        }
        
        if (!auth()->user()->subscribed('primary')) {
            $start = \Carbon\Carbon::now()->startOfMonth();
            $end = \Carbon\Carbon::now()->endOfMonth();

            if ($request->appProject->apiRequests()
                        ->where('api_app_requests.created_at', '>=', $start->format('Y-m-d H:m:i'))
                        ->where('api_app_requests.created_at', '<=', $end->format('Y-m-d H:m:i'))
                        ->count() >= auth()->user()->apiPlanLimit()) {
                abort(401);
            }
        }

        if (!empty($dMeta)) {
            
            // get device
            $device = UserDevice::firstOrCreate(
                ['uuid' => $dMeta['uuid']],
                [
                    'uuid' => $dMeta['uuid'],
                    'model' => $dMeta['model'],
                    'brand' => $dMeta['brand'],
                    'os' => $dMeta['os'],
                    'version' => $dMeta['version'],
                    'app_project_id' => $request->appProject->id,
                    'is_active' => 1
                ]
            );
            
            if ($device->is_active != 1) {
                abort(401);
            }
            
            // create api request
            $appAPIRequest = AppAPIRequest::create(
                [
                    'user_device_id' => $device->id, 
                    'app_key' => $request->appProject->app_key, 
                    'path' => $path, 
                    'ip' => $request->ip()
                ]
            );

            if ($appAPIRequest) {
                $request->request->add(['device' => $device]);
                return $next($request);
            }
        }

        abort(401);
    }
}
