<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\JsonResponse;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Seller $seller
     * @return JsonResponse
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;

        return $this->showAll($products);
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function store()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function update()
    {

    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function destroy()
    {

    }
}
