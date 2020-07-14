<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

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
     * @param Request $request
     * @param Seller $seller
     * @param Product $product
     * @return JsonResponse
     * @throws ValidationException
     * @throws \HttpException
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $rules = [
            'quantity' => 'integer|min:1',
            'status'   => 'in:'. Product::AVAILABLE_PRODUCT . ', ' . Product::UNAVAILABLE_PRODUCT,
            'image'    => 'image',
        ];

        $this->validate($request, $rules);

        $this->checkSeller($seller, $product);

        $product->fill($request->only(['name', 'description', 'quantity',]));

        if ($request->has('status')){
            $product->status = $request->status;

            if ($product->is_Available() && $product->categories()->count() == 0){
                return $this->errorResponse('An active product must have at least one active product', 409);
            }
        }

        if ($product->isClean()){
            return $this->errorResponse('You need to specific a different value to update', 422);
        }

        $product->save();

        return $this->showOne($product);
    }

    /**
     * Display a listing of the resource.
     *
     * @return void
     */
    public function destroy()
    {

    }

    public function checkSeller(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id){
            throw new HttpException(422, 'The specified seller is not the actual seller of the product.');
        }
    }
}
