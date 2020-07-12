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

        return  response()->json([
            'data' => $buyers,
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id)
    {
        $buyer = Buyer::has('transactions')->findOrFail($id);

        return  response()->json([
            'data' => $buyer,
        ], 200);
    }
}
