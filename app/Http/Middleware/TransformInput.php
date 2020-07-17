<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Validation\ValidationException;
use function GuzzleHttp\Psr7\str;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param $transformer
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInput = [];

        foreach ($request->request->all() as $item => $value) {
            $transformedInput[$transformer::originalAttribute($item)] = $value;
        }

        $request->replace($transformedInput);

        $response = $next($request);

        if (isset($response->exception) && $response->exception instanceof ValidationException) {
            $data = $response->getData();

            $transformedErrors = [];

            foreach ($data->error as $key => $value) {
                $transformedField = $transformer::transformedAttribute($key);

                $transformedErrors[$transformedField] = str_replace($key, $transformedField, $value);
            }

            $data->error = $transformedErrors;

            $response->setData($data);
        }

        return $response;
    }
}
