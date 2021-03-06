<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use Illuminate\Http\JsonResponse;

class SellerBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Seller $seller
     * @return JsonResponse
     */
    public function index(Seller $seller)
    {
        $buyers = $seller->products()
            ->whereHas("transactions")
            ->with('transactions.buyer')
            ->get()
            ->pluck("transactions")
            ->collapse()
            ->pluck("buyer")
            ->unique("id")
            ->values()
        ;

        return $this->showAll($buyers);
    }
}
