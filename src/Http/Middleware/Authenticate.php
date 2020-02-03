<?php

namespace WooSignal\LaraApp\Http\Middleware;

use WooSignal\LaraApp\LaraApp;

class Authenticate
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
        return LaraApp::check($request) ? $next($request) : abort(403);
    }
}
