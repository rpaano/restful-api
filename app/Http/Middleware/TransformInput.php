<?php

namespace App\Http\Middleware;

use Closure;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $transform
     * @return mixed
     */
    public function handle($request, Closure $next, $transform)
    {
        $transformedInput = [];

        foreach ($request->request->all() as $item => $value) {
            $transformedInput[$transform::originalAttribute($item)] = $value;
        }

        $request->replace($transformedInput);

        return $next($request);
    }
}
