<?php

namespace Tests\Feature;

use App\Jobs\UpdateOrderStatusJob;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test
     * @throws \JsonException
     */
    public function it_can_create_an_order(): void
    {
        $orderData = [
            'total_amount' => 200.00,
            'products' => json_encode([
                [
                    'product_name' => 'Test Product 1',
                    'quantity' => 2,
                    'price' => 100.00,
                ],
                [
                    'product_name' => 'Test Product 2',
                    'quantity' => 1,
                    'price' => 100.00,
                ],
            ], JSON_THROW_ON_ERROR),
        ];

        $response = $this->postJson('/api/v1/orders', $orderData);

        $response->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            'total_amount' => '200.00',
            'products' => json_encode($orderData['products'], JSON_THROW_ON_ERROR)
        ]);
    }

    /** @test
     * @throws \JsonException
     */
    public function it_can_update_order_status(): void
    {
        $orderData = [
            'total_amount' => 200.00,
            'products' => json_encode([
                [
                    'product_name' => 'Test Product 1',
                    'quantity' => 2,
                    'price' => 100.00,
                ],
                [
                    'product_name' => 'Test Product 2',
                    'quantity' => 1,
                    'price' => 100.00,
                ],
            ], JSON_THROW_ON_ERROR),
        ];

        $order = Order::factory()->create($orderData);

        Http::fake([
            'http://order-api.test/api/v1/orders/' . $order->order_number => Http::sequence()->push(['status' => 'pending'])
        ]);

        // Start the job
        // $job = new UpdateOrderStatusJob();
        // $job->handle();

        $order->refresh();
        $this->assertEquals('pending', $order->status);
    }

    /** @test */
    /** @test
     * @throws \JsonException
     */
    public function it_can_handle_api_errors(): void
    {
        $orderData = [
            'total_amount' => 200.00,
            'products' => json_encode([
                [
                    'product_name' => 'Test Product 1',
                    'quantity' => 2,
                    'price' => 100.00,
                ],
                [
                    'product_name' => 'Test Product 2',
                    'quantity' => 1,
                    'price' => 100.00,
                ],
            ], JSON_THROW_ON_ERROR),
        ];

        $order = Order::factory()->create($orderData);

        Http::fake([
            'http://order-api.test/api/v1/orders/' . $order->order_number => Http::sequence()->push(['error' => 'Order not found'], 404)
        ]);

        // Start the job
        // $job = new UpdateOrderStatusJob();
        // $job->handle();

        $this->assertEquals('pending', $order->status);
        logger('Error getting data about product', ['order_number' => $order->order_number]);
    }
}
