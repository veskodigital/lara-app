<?php

namespace VeskoDigital\LaraApp\Http\Middleware;

use VeskoDigital\LaraApp\LaraApp;

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
