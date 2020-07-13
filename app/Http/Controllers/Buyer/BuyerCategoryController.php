<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\JsonResponse;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @param Buyer $buyer
     * @return JsonResponse
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories')
            ->get()
            ->pluck('product.categories')
            ->collapse()
                 /*  many to many to remove the multiple array
                        "data": {
                                "data": [
                                    [
                                        {
                                            "id": 1,
                                            "name": "sunt",
                                            "description": "Voluptatem voluptatem consequatur illo. Deserunt laudantium officia aut alias non.",
                                            "deleted_at": null,
                                            "created_at": "2020-07-12T14:21:03.000000Z",
                                            "updated_at": "2020-07-12T14:21:03.000000Z",
                                            "pivot": {
                                                "product_id": 158,
                                                "category_id": 1
                                            }
                                        },
                                        {
                                            "id": 4,
                                            "name": "corrupti",
                                            "description": "Eius molestiae rem dignissimos ipsa impedit excepturi. Totam eum et dolor temporibus.",
                                            "deleted_at": null,
                                            "created_at": "2020-07-12T14:21:03.000000Z",
                                            "updated_at": "2020-07-12T14:21:03.000000Z",
                                            "pivot": {
                                                "product_id": 158,
                                                "category_id": 4
                                            }
                                        }
                                    ]
                                 ]
                             }    */
            ->unique('id') //one to many to be sure there are no repeating ids
            ->values()          //one to many to insure no to empty array
        ;

        return $this->showAll($categories);
    }
}
