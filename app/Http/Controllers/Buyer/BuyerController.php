<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $buyers = Buyer::has('transactions')->get();

        return  $this->showAll($buyers);
    }

    /**
     * Display the specified resource.
     *
     * @param Buyer $buyer
     * @return JsonResponse
     */
    public function show(Buyer $buyer)
    {
//        $buyer = Buyer::has('transactions')->findOrFail($id);

        return  $this->showOne($buyer);
    }
}
