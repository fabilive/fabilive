<?php

namespace App\Http\Controllers\User;

use App\Models\Order;
use App\Models\Product;

class OrderController extends UserBaseController
{
    public function orders()
    {
        $user = $this->user;

        $orders = Order::with('deliveryRider')
            ->where('user_id', $user->id)
            ->latest('id')
            ->get();

        // dd($orders);
        return view('user.order.index', compact('user', 'orders'));
    }

    public function ordertrack()
    {
        $user = $this->user;

        return view('user.order-track', compact('user'));
    }

    public function trackload($id)
    {
        $user = $this->user;
        $order = $user->orders()->where('order_number', '=', $id)->first();
        $datas = ['Pending', 'Processing', 'On Delivery', 'Completed'];

        return view('load.track-load', compact('order', 'datas'));

    }

    public function order($id)
    {
        $user = $this->user;
        $order = $user->orders()->whereId($id)->firstOrFail();
        $raw_cart = $order->cart;
        $cart = json_decode($raw_cart, true);
        if ($cart === null && ! empty($raw_cart)) {
            try {
                if (strpos($raw_cart, 'a:') === 0 || strpos($raw_cart, 'O:') === 0) {
                    $cart = unserialize($raw_cart);
                    if (is_object($cart)) {
                        $cart = json_decode(json_encode($cart), true);
                    }
                }
            } catch (\Exception $e) {
            }
        }
        if (! is_array($cart)) {
            $cart = ['items' => []];
        }
        if (! isset($cart['items'])) {
            if (! empty($cart) && is_array(reset($cart))) {
                $cart = ['items' => $cart];
            } else {
                $cart['items'] = [];
            }
        }
        foreach ($cart['items'] as $k => $v) {
            if (is_array($v) && ! isset($v['item'])) {
                $cart['items'][$k]['item'] = $v;
            }
        }

        return view('user.order.details', compact('user', 'order', 'cart'));
    }

    public function orderdownload($slug, $id)
    {
        $user = $this->user;
        $order = Order::where('order_number', '=', $slug)->first();
        $prod = Product::findOrFail($id);
        if (! isset($order) || $prod->type == 'Physical' || $order->user_id != $user->id) {
            return redirect()->back();
        }

        return response()->download(public_path('assets/files/'.$prod->file));
    }

    public function orderprint($id)
    {
        $user = $this->user;
        $order = Order::findOrfail($id);
        $raw_cart = $order->cart;
        $cart = json_decode($raw_cart, true);
        if ($cart === null && ! empty($raw_cart)) {
            try {
                if (strpos($raw_cart, 'a:') === 0 || strpos($raw_cart, 'O:') === 0) {
                    $cart = unserialize($raw_cart);
                    if (is_object($cart)) {

                        $cart = json_decode(json_encode($cart), true);
                    }
                }
            } catch (\Exception $e) {
            }
        }
        if (! is_array($cart)) {
            $cart = ['items' => []];
        }
        if (! isset($cart['items'])) {
            if (! empty($cart) && is_array(reset($cart))) {
                $cart = ['items' => $cart];
            } else {
                $cart['items'] = [];
            }
        }
        foreach ($cart['items'] as $k => $v) {
            if (is_array($v) && ! isset($v['item'])) {
                $cart['items'][$k]['item'] = $v;
            }
        }

        return view('user.order.print', compact('user', 'order', 'cart'));
    }

    public function trans()
    {
        $id = $_GET['id'];
        $trans = $_GET['tin'];
        $order = Order::findOrFail($id);
        $order->txnid = $trans;
        $order->update();
        $data = $order->txnid;

        return response()->json($data);
    }
}
