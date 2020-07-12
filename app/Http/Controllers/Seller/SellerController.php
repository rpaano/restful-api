<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Seller;
use Illuminate\Http\JsonResponse;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $sellers = Seller::has('products')->get();

        return  response()->json([
            'data' => $sellers,
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
        $seller = Seller::has('products')->findOrFail($id);

        return  response()->json([
            'data' => $seller,
        ], 200);
    }
}
