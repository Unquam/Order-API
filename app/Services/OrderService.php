<?php

namespace App\Services;


use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class OrderService
{
    /**
     * @var Order
     */
    public Order $model;

    /**
     * @param  Order  $order
     */
    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    /**
     * @param string|null $status
     * @return Collection|array
     */
    public function getAllOrders(string $status = null): Collection|array
    {
        $query = $this->model->select([
            'id', 'order_number', 'user_id','total_amount',
            'status', 'products', 'created_at', 'updated_at'
        ]);

       // Check if status is not null
        if (!is_null($status)) {
            $query->where('status', $status);
        }

        return $query->get();
    }

    /**
     * @param array $params
     * @return Model|bool
     * @throws \JsonException
     */
    public function createOrderByRequest(array $params): Model|bool
    {
        try {
            //No logic create with user_id (no auth), just create the order
            return $this->model->create($params);
        } catch (\Throwable $exception) {
            logger('Order not created in OrderService method createOrderByRequest: '
                . Carbon::now()->format('Y-m-d H:i') .
                ' - ' . json_encode($params, JSON_THROW_ON_ERROR) . ' - ' . $exception->getMessage());
            return false;
        }
    }

    /**
     * @param $orderNumber
     * @return null|Model
     */
    public function getOrderByOrderNumber($orderNumber): null|Model
    {
        return $this->model->where('order_number', $orderNumber)->first();
    }
}