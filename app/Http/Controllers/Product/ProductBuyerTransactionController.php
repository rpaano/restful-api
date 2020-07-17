<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\Transaction;
use App\Transformers\ProductTransformer;
use App\Transformers\UserTransformer;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ProductBuyerTransactionController extends ApiController
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware("transform.input:". ProductTransformer::class)->only('store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param Product $product
     * @param User $buyer
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request, Product $product, User $buyer)
    {
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];

        $this->validate($request, $rules);

        if ($buyer->id == $product->seller_id){
            return $this->errorResponse("The buyer must be different from the seller", 409);
        }

        if (!$buyer->isVerified()){
            return $this->errorResponse("Must be a verified user", 409);
        }

        if (!$product->seller->is_Verified()){
            return $this->errorResponse("Must be a verified user", 409);
        }

        if (!$product->is_Available()){
            return $this->errorResponse("This product is not available", 409);
        }

        if ($product->quantity < $request->quantity){
            return $this->errorResponse("This product is not enough quantity in stock", 409);
        }

        return DB::transaction(function () use ($request, $product, $buyer){
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);

            return $this->showOne($transaction, 201);
        });
    }
}
