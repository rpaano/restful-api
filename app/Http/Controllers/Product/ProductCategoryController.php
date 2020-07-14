<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Product $product
     * @return JsonResponse|void
     */
    public function index(Product $product)
    {
        $categories = $product->categories;

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @param Category $category
     * @return JsonResponse
     */
    public function update(Request $request, Product $product, Category $category)
    {
        /*
         *  attach               => add to the existing connections
         *  sync                 => remove all existing connections and attach the new one
         *  syncWithoutDetaching => add to existing connection without duplicating
         */

        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Product $product
     * @param Category $category
     * @return JsonResponse|Response
     */
    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id)){
            return $this->errorResponse("The specific category is not a category of this product", 404);
        }

        $product->categories()->detach($category->id);

        return $this->showOne($product);
    }
}
