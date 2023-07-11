<?php

namespace WooSignal\LaraApp\Http\Middleware;

class SiteAuthenticate
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
    	if (empty($token)) {
    		abort(403);
    	}

    	$laraAppToken = config('laraapp.appkey', '');
        if (empty($laraAppToken)) {
            abort(403);
        }
        
    	if ($token != $laraAppToken) {
    		abort(403);
    	}
        
        return $next($request);
    }
}
