<?php

namespace App\Transformers;

use App\Product;
use League\Fractal\TransformerAbstract;

class ProductTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];

    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];

    /**
     * A Fractal transformer.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {
        return [
            'identifier' => (int)$product->id,
            'title' => (int)$product->title,
            'details' => (int)$product->description,
            'stock' => (int)$product->quantity,
            'situation' => (int)$product->status,
            'photo' => asset($product->image),
            'seller' => $product->seller_id,
            'creationDate' => $product->created_at,
            'lastedDate' => $product->updated_at,
            'deletedDate' => isset($product->deleted_at) ? (string)$product->deleted_at : null,
        ];
    }

    public static function originalAttribute($index)
    {
        $attributes = [
            'identifier' => 'id',
            'title' => 'name',
            'details' => 'description',
            'stock' => 'quantity',
            'situation' => 'status',
            'photo' => 'image',
            'seller' => 'seller_id',
            'creationDate' => 'created_at',
            'lastedDate' => 'updated_at',
            'deletedDate' => 'deleted_at',
        ];

        return isset($attributes[$index]) ? $attributes[$index] : null;
    }
}
