<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $headerName
     * @return mixed
     */
    public function handle($request, Closure $next, $headerName = 'X-name')
    {
        $response = $next($request);

        $response->headers->set($headerName, config('app.name'));


        return $response;
    }
}
