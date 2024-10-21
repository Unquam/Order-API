<?php

namespace App\Http\Resources\Order;

use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class OrdersCollectionResource extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray(Request $request): array|JsonSerializable|Arrayable
    {
        return $this->collection->map(function($query) {
            return [
                'id' => $query['id'],
                'order_number' => $query['order_number'],
                'user_id' => $query['user_id'],
                'total_amount' => $query['total_amount'],
                'products' => collect($query['products'])->map(function($product) {
                    return [
                        'product_name' => $product['product_name'],
                        'quantity' => $product['quantity'],
                        'price' => $product['price']
                    ];
                }),
                'status' => $query['status'],
                'created_at' => Carbon::parse($query['created_at'])->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($query['updated_at'])->format('Y-m-d H:i:s')
            ];
        });
    }
}
