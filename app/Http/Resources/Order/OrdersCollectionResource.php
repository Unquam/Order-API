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
                'total_amount' => $query['total_amount'],
                'products' => json_decode($query['products'], false, 512, JSON_THROW_ON_ERROR),
                'status' => $query['status'],
                'created_at' => Carbon::parse($query['created_at'])->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::parse($query['updated_at'])->format('Y-m-d H:i:s')
            ];
        });
    }
}
