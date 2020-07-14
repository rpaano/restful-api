<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

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
     * @param Request $request
     * @param User $seller
     * @return JsonResponse|void
     * @throws ValidationException
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name'        => 'required',
            'description' => 'required',
            'quantity'    => 'required|integer|min:1',
            'image'       => 'required|image',
        ];

        $this->validate($request, $rules);

        $data = $request->only('name', 'description', 'quantity', 'image');

        
        $data['image']     = '1.png';
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);

        return $this->showOne($product);
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
