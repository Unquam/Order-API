<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\CreateOrder;
use App\Http\Requests\Order\GetOrderByStatus;
use App\Http\Resources\Order\CreateOrderResource;
use App\Http\Resources\Order\OrderCollectionResource;
use App\Http\Resources\Order\OrdersCollectionResource;
use App\Services\OrderService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{
    /**
     * @return void
     */
    public function __construct(private readonly OrderService $orderService)
    {
    }

    /**
     * @param GetOrderByStatus $request
     * @return JsonResponse
     */
    public function getOrders(GetOrderByStatus $request): JsonResponse
    {
        try {
            //Get all Orders
            $orders = $this->orderService->getAllOrders(status: $request->input('status'));

            //Check if collection is empty
            if (!$orders->count()) {
                return response()->json(['message' => 'Empty Orders'], Response::HTTP_NOT_FOUND);
            }

            //Return response
            return response()->json((new OrdersCollectionResource(resource: $orders))->resolve(), Response::HTTP_OK);
        } catch (\Throwable $exception) {
            if (env('APP_ENV') !== 'local' && app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }

            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param CreateOrder $request
     * @return JsonResponse
     */
    public function createOrder(CreateOrder $request): JsonResponse
    {
        try {
            // Create order
            $order = $this->orderService->createOrderByRequest(params: $request->validated());

            // Check if order is not created
            if ($order === false) {
                logger('Order not created: ' . Carbon::now()->format('Y-m-d H:i') .
                    ' - ' . json_encode($request->validated(), JSON_THROW_ON_ERROR));
                return response()->json(['success' => false, 'message' => 'Order not created'], Response::HTTP_NOT_FOUND);
            }

            // Return response
            return response()->json(['success' => true, 'message' => 'Order created successfully',
                'data' => (new CreateOrderResource(resource: $order))->resolve()], Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            if (env('APP_ENV') !== 'local' && app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }

            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param string $orderNumber
     * @return JsonResponse
     */
    public function getOrder(string $orderNumber): JsonResponse
    {
        try {
            //Get Order by order number
            $order = $this->orderService->getOrderByOrderNumber(orderNumber: $orderNumber);

            //Check if order is null
            if (is_null($order)) {
                return response()->json(['message' => 'Order not found'], Response::HTTP_NOT_FOUND);
            }

            //Return response
            return response()->json((new OrderCollectionResource(resource: $order))->resolve(), Response::HTTP_OK);
        } catch (\Throwable $exception) {
            if (env('APP_ENV') !== 'local' && app()->bound('sentry')) {
                app('sentry')->captureException($exception);
            }

            return response()->json(['message' => $exception->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
