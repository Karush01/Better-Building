<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\App;

use Closure;

class HttpsProtocol
{
    /**
     * Handle an incoming request.
     * if site in prodaction its transfer to https
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->secure()  && App::environment() === 'production') {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
