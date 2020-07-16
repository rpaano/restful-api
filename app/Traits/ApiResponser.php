<?php

namespace App\Traits;

use App\Transformers\UserTransformer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

trait ApiResponser
{
    private function successResponse($data, $code){
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code){
        return response()->json([
            'error' => $message,
            'code'  => $code,
        ], $code);
    }

    protected function showAll(Collection $collection, $code = 200)
    {
        if ($collection->isEmpty()) {
            return $this->successResponse([
                'data' => $collection
            ], $code);
        }

        $transformer = $collection->first()->transformer;

        $collection = $this->sortData($collection);

        $collection = $this->transformData($collection, $transformer);

        return $this->successResponse(
            $collection
        , $code);
    }

    protected function showOne(Model $instance, $code = 200)
    {
        $transformer = $instance->transformer;

        $instance = $this->transformData($instance, $transformer);

        return $this->successResponse([
            'data' => $instance
        ], $code);
    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse([
            'data' => $message
        ], $code);
    }

    protected function sortData(Collection $collection)
    {
        if (request()->has("sort_by")) {
            $attribute = request()->sort_by;

            $collection = $collection->sortBy->{$attribute};
        }
        return $collection;
    }

    public function transformData($data, $transformer)
    {
        $transformation = fractal($data, $transformer);

        return $transformation->toArray();
    }
}
