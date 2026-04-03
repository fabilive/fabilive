<?php

namespace App\Http\Controllers\Api\Rider;

use App\Models\DeliveryRider;
use Illuminate\Http\Request;

class OrderController
{
    protected $rider;

    public function __construct()
    {
        $this->rider = auth('rider-api')->user();
    }

    public function orders(Request $request)
    {
        $status_filters = ['delivered', 'pending', 'rejected', 'accepted'];
        $perPage = $request->get('per_page', 10);

        $orders = DeliveryRider::with(['order', 'pickup', 'vendor'])
            ->where('rider_id', $this->rider->id)
            ->when(in_array($request->query('status'), $status_filters), function ($q) use ($request) {
                $q->where('status', $request->query('status'));
            })
            ->paginate($perPage);

        return response()->json([
            'data' => $orders->getCollection()->map(fn ($order) => $this->transformOrder($order)),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
                'last_page' => $orders->lastPage(),
            ],
        ]);
    }

    public function showOrder($id)
    {
        $order = DeliveryRider::with(['order', 'pickup', 'vendor'])
            ->where('rider_id', $this->rider->id)
            ->where('id', $id)
            ->first();
        if (! $order) {
            return response()->json(['message' => 'Record not found '], 404);
        }

        return response()->json(['data' => $this->transformOrder($order)]);
    }

    public function orderAccept($id)
    {
        $order = DeliveryRider::where('rider_id', $this->rider->id)->where('id', $id)->first();

        if (! $order) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($order->status != 'pending') {
            return response()->json(['message' => 'The Order should be in Pending state. Current status is: '.$order->status], 400);
        }

        $order->status = 'accepted';
        $order->save();

        return response()->json(['message' => 'Order accepted successfully']);
    }

    public function orderReject($id)
    {
        $order = DeliveryRider::where('rider_id', $this->rider->id)->where('id', $id)->first();

        if (! $order) {
            return response()->json(['message' => 'Record not found'], 404);
        }

        if ($order->status != 'pending') {
            return response()->json(['message' => 'The Order should be in Pending state. Current status is: '.$order->status], 400);
        }

        $order->status = 'rejected';
        $order->save();

        return response()->json(['message' => 'Order rejected successfully']);
    }

    public function orderComplete($id)
    {
        $order = DeliveryRider::where('rider_id', $this->rider->id)->where('id', $id)->first();

        if (! $order) {
            return response()->json(['message' => 'Record not found'], 404);
        }
        if ($order->status != 'accepted') {
            return response()->json(['message' => 'The Order should be in Accepted state. Current status is: '.$order->status], 400);
        }
        $order->status = 'delivered';
        $order->save();

        if ($order->order) {
            $updateData = ['status' => 'delivered'];
            if ($order->order->method === 'Cash On Delivery') {
                $updateData['payment_status'] = 'Completed';
            }
            $order->order->update($updateData);
        }

        return response()->json(['message' => 'Order delivered successfully']);

    }

    private function transformOrder($order)
    {
        $order_detail = [
            'id' => $order['order']['id'],
            'method' => $order['order']['method'],
            'totalQty' => $order['order']['totalQty'],
            'pay_amount' => $order['order']['pay_amount'],
            'payment_status' => $order['order']['payment_status'],
            'order_number' => $order['order']['order_number'],
            'status' => $order['order']['status'],
            'created_at' => $order['order']['created_at'],
            'shipping_cost' => $order['order']['shipping_cost'],
            'currency_sign' => $order['order']['currency_sign'],
            'currency_name' => $order['order']['currency_name'],
            'currency_value' => $order['order']['currency_value'],
        ];

        $order_items = json_decode($order['order']['cart'], true);
        $order_items = array_values(array_map(function ($item) {
            return [
                'quantity' => $item['qty'],
                'price' => $item['price'],
                'item' => [
                    'title' => $item['item']['name'],
                    'photo' => $item['item']['photo'],
                    'link' => $item['item']['link'],
                ],
            ];
        }, $order_items['items']));

        $customer = [
            'customer_name' => $order['order']['customer_name'],
            'customer_email' => $order['order']['customer_email'],
            'customer_phone' => $order['order']['customer_phone'],
            'customer_address' => $order['order']['customer_address'],
            'customer_city' => $order['order']['customer_city'],
            'customer_country' => $order['order']['customer_country'],
            'customer_state' => $order['order']['customer_state'],
        ];

        return [
            'id' => $order['id'],
            'status' => $order['status'],
            'phone_number' => $order['phone_number'],
            'order' => $order_detail,
            'order_items' => $order_items,
            'customer' => $customer,
            'pickup' => $order['pickup'] ? [
                'id' => $order['pickup']['id'],
                'location' => $order['pickup']['location'],
            ] : null,
            'vendor' => $order['vendor'] ? [
                'id' => $order['vendor']['id'],
                'name' => $order['vendor']['name'],
                'shop_name' => $order['vendor']['shop_name'],
                'shop_phone' => $order['vendor']['phone'],
                'shop_email' => $order['vendor']['email'],
                'shop_address' => $order['vendor']['shop_address'],
            ] : null,
        ];
    }
}
