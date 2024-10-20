<?php

namespace App\Jobs;

use App\Events\OrderStatusUpdated;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class UpdateOrderStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $orders = Order::whereIn('status', ['pending','paid'])->get();

        foreach ($orders as $order) {
            $response = Http::get('http://order-api.test/api/v1/orders/' . $order->order_number);

            if ($response->successful()) {
                $data = $response->json();

                //Check if status is different
                if (isset($data['status']) && $data['status'] !== $order->status) {
                    $oldStatus = $order->status;

                    // Update order status
                    $order->status = $data['status'];
                    $order->save();

                    // Generate event
                    event(new OrderStatusUpdated($order, $oldStatus));
                }
            }
        }
    }
}
