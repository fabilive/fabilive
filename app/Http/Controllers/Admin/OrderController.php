<?php

namespace App\Http\Controllers\Admin;

use App\Classes\GeniusMailer;
use App\Helpers\PriceHelper;
use App\Models\AffliateBonus;
use App\Models\Cart;
use App\Models\DeliveryRider;
use App\Models\Generalsetting;
use App\Models\Order;
use App\Models\OrderTrack;
use App\Models\Package;
use App\Models\Product;
use App\Models\Rider;
use App\Models\RiderServiceArea;
use App\Models\Shipping;
use App\Models\User;
use App\Models\WalletLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables as Datatables;

class OrderController extends AdminBaseController
{
    //*** GET Request
    public function orders(Request $request)
    {
        if ($request->status == 'pending') {
            return view('admin.order.pending');
        } elseif ($request->status == 'processing') {
            return view('admin.order.processing');
        } elseif ($request->status == 'completed') {
            return view('admin.order.completed');
        } elseif ($request->status == 'declined') {
            return view('admin.order.declined');
        } else {
            return view('admin.order.index');
        }
    }

    public function processing()
    {
        return view('admin.order.processing');
    }

    public function completed()
    {
        return view('admin.order.completed');
    }

    public function declined()
    {
        return view('admin.order.declined');
    }

    // public function datatables($status)
    // {
    //     if ($status == 'pending') {
    //         $datas = Order::where('status', '=', 'pending')->latest('id')->get();
    //     } elseif ($status == 'processing') {
    //         $datas = Order::where('status', '=', 'processing')->latest('id')->get();
    //     } elseif ($status == 'completed') {
    //         $datas = Order::where('status', '=', 'completed')->latest('id')->get();
    //     } elseif ($status == 'declined') {
    //         $datas = Order::where('status', '=', 'declined')->latest('id')->get();
    //     } else {
    //         $datas = Order::latest('id')->get();
    //     }
    //     return Datatables::of($datas)
    //         ->editColumn('id', function (Order $data) {
    //             $id = '<a href="' . route('admin-order-invoice', $data->id) . '">' . $data->order_number . '</a>';
    //             return $id;
    //         })
    //         ->editColumn('pay_amount', function (Order $data) {
    //             return PriceHelper::showOrderCurrencyPrice((($data->pay_amount + $data->wallet_price) * $data->currency_value), $data->currency_sign);
    //         })
    //         ->addColumn('action', function (Order $data) {
    //             $orders = '<a href="javascript:;" data-href="' . route('admin-order-edit', $data->id) . '" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> ' . __('Delivery Status') . '</a>';
    //             return '<div class="godropdown"><button class="go-dropdown-toggle">' . __('Actions') . '<i class="fas fa-chevron-down"></i></button><div class="action-list"><a href="' . route('admin-order-show', $data->id) . '" > <i class="fas fa-eye"></i> ' . __('View Details') . '</a><a href="javascript:;" class="send" data-email="' . $data->customer_email . '" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> ' . __('Send') . '</a><a href="javascript:;" data-href="' . route('admin-order-track', $data->id) . '" class="track" data-toggle="modal" data-target="#modal1"><i class="fas fa-truck"></i> ' . __('Track Order') . '</a>' . $orders . '</div></div>';
    //         })
    //         ->rawColumns(['id', 'action'])
    //         ->toJson(); //--- Returning Json Data To Client Side
    // }

    public function datatables($status)
    {
        $defaultCurrency = \App\Models\Currency::where('is_default', 1)->first();
        if (!$defaultCurrency) {
            $defaultCurrency = \App\Models\Currency::where('name', 'CFA')->first() ?? \App\Models\Currency::first();
        }
        
        $currValue = $defaultCurrency ? $defaultCurrency->value : 1;
        $currSign = $defaultCurrency ? $defaultCurrency->sign : 'CFA';

        if ($status == 'pending') {
            $datas = Order::where('status', '=', 'pending')->latest('id')->get();
        } elseif ($status == 'processing') {
            $datas = Order::where('status', '=', 'processing')->latest('id')->get();
        } elseif ($status == 'completed') {
            $datas = Order::where('status', '=', 'completed')->latest('id')->get();
        } elseif ($status == 'declined') {
            $datas = Order::where('status', '=', 'declined')->latest('id')->get();
        } else {
            $datas = Order::latest('id')->get();
        }

        return Datatables::of($datas)
            ->editColumn('id', function (Order $data) {
                $id = '<a href="'.route('admin-order-invoice', $data->id).'">'.$data->order_number.'</a>';

                return $id;
            })
            ->editColumn('pay_amount', function (Order $data) use ($currValue, $currSign) {
                return PriceHelper::showOrderCurrencyPrice(
                    (($data->pay_amount + $data->wallet_price) * $currValue),
                    $currSign
                );
            })
            ->addColumn('commission', function (Order $data) use ($currValue, $currSign) {
                return PriceHelper::showOrderCurrencyPrice(
                    ($data->commission * $currValue),
                    $currSign
                );
            })
            ->addColumn('action', function (Order $data) {
                $orders = '<a href="javascript:;" data-href="'.route('admin-order-edit', $data->id).'" class="delivery" data-toggle="modal" data-target="#modal1"><i class="fas fa-dollar-sign"></i> '.__('Delivery Status').'</a>';

                return '<div class="godropdown">
                        <button class="go-dropdown-toggle">'.__('Actions').'<i class="fas fa-chevron-down"></i></button>
                        <div class="action-list">
                            <a href="'.route('admin-order-show', $data->id).'"><i class="fas fa-eye"></i> '.__('View Details').'</a>
                            <a href="javascript:;" class="send" data-email="'.$data->customer_email.'" data-toggle="modal" data-target="#vendorform"><i class="fas fa-envelope"></i> '.__('Send').'</a>
                            <a href="javascript:;" data-href="'.route('admin-order-track', $data->id).'" class="track" data-toggle="modal" data-target="#modal1"><i class="fas fa-truck"></i> '.__('Track Order').'</a>'
                                .$orders.
                            '</div>
                    </div>';
            })
            ->rawColumns(['id', 'action'])
            ->toJson();
    }

    // public function show($id)
    // {
    //     $order = Order::findOrFail($id);
    //     $cart = json_decode($order->cart, true);
    //     $currency = \App\Models\Currency::where('is_default', 1)->first();
    //     return view('admin.order.details', compact('order', 'cart','currency'));
    // }

    public function show($id)
    {
        $order = Order::with(['customerCity', 'shippingCity'])->findOrFail($id);
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
        $currency = \App\Models\Currency::where('is_default', 1)->first();

        return view('admin.order.details', compact('order', 'cart', 'currency'));
    }

    public function invoice($id)
    {
        $order = Order::findOrFail($id);
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

        return view('admin.order.invoice', compact('order', 'cart'));
    }

    public function emailsub(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
        if ($gs->is_smtp == 1) {
            $data = [
                'to' => $request->to,
                'subject' => $request->subject,
                'body' => $request->message,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        } else {
            $data = 0;
            $headers = 'From: '.$gs->from_name.'<'.$gs->from_email.'>';
            $mail = mail($request->to, $request->subject, $request->message, $headers);
            if ($mail) {
                $data = 1;
            }
        }

        return response()->json($data);
    }

    public function printpage($id)
    {
        $order = Order::findOrFail($id);
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

        return view('admin.order.print', compact('order', 'cart'));
    }

    public function license(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $cart = json_decode($order->cart, true);
        $cart['items'][$request->license_key]['license'] = $request->license;
        $new_cart = json_encode($cart);
        $order->cart = $new_cart;
        $order->update();
        $msg = __('Successfully Changed The License Key.');

        return redirect()->back()->with('license', $msg);
    }

    public function edit($id)
    {
        $data = Order::find($id);

        return view('admin.order.delivery', compact('data'));
    }

    //*** POST Request
    //     public function update(Request $request, $id)
    //     {
    //         $data = Order::findOrFail($id);
    //         $input = $request->all();
    //         if ($request->has('status')) {
    //             if ($data->status == "Completed") {
    //                 $input['status'] = "Completed";
    //                 $data->update($input);
    //                 $msg = __('Status Updated Successfully.');
    //                 return response()->json($msg);
    //             } else {
    //                 if ($input['status'] == "Completed") {
    //                     if ($data->payment_status == 'Paid' && $data->is_shipping == 1)

    //                     {
    //     $vendor_ids = json_decode($data->vendor_ids, true);

    //     foreach ($vendor_ids as $vendor_id) {
    //         // Get rider assigned to this vendor for this order
    //         $deliveryRider = DeliveryRider::where('order_id', $data->id)
    //                                       ->where('vendor_id', $vendor_id)
    //                                       ->first();

    //                             if ($deliveryRider) {
    //             $rider = Rider::find($deliveryRider->rider_id);
    //             if ($rider) {
    //                 // $rider->balance += $data->shipping_cost;
    //                 $vendor_ids = json_decode($data->vendor_ids, true);
    //                 $shipping_ids = json_decode($data->vendor_shipping_id, true);

    //                     foreach ($vendor_ids as $vendor_id) {
    //                     $deliveryRider = DeliveryRider::where('order_id', $data->id)
    //                                   ->where('vendor_id', $vendor_id)
    //                                   ->first();

    //     if ($deliveryRider) {
    //         $rider = Rider::find($deliveryRider->rider_id);
    //         if ($rider && isset($shipping_ids[$vendor_id])) {
    //             $shipping = Shipping::find($shipping_ids[$vendor_id]);
    //             if ($shipping) {
    //                 $rider->balance += $shipping->price;
    //                 $rider->save();
    //             }
    //         }
    //     }
    // }
    //                 $rider->save();
    //             }
    //         }
    //     }
    // }

    // //                          {
    // //     $vendor_ids = json_decode($data->vendor_ids, true);
    // //     $shipping_ids = json_decode($data->vendor_shipping_id, true);

    // //     foreach ($vendor_ids as $vendor_id) {
    // //         // Find the delivery record for this vendor and order
    // //         $deliveryRider = \App\Models\DeliveryRider::where('order_id', $data->id)
    // //                                                   ->where('vendor_id', $vendor_id)
    // //                                                   ->first();

    // //         if ($deliveryRider) {
    // //             $rider = \App\Models\Rider::find($deliveryRider->rider_id);

    // //             // Only proceed if rider exists and shipping info is available
    // //             if ($rider && isset($shipping_ids[$vendor_id])) {
    // //                 $shipping = \App\Models\Shipping::find($shipping_ids[$vendor_id]);

    // //                 if ($shipping) {
    // //                     // ✅ Add shipping price to rider's balance
    // //                     $rider->balance += $shipping->price;
    // //                     $rider->save();
    // //                 }
    // //             }
    // //         }
    // //     }
    // // }

    //                     if ($data->vendor_ids) {
    //                         $vendor_ids = json_decode($data->vendor_ids, true);
    //                         foreach ($vendor_ids as $vendor) {
    //                             $deliveryRider = DeliveryRider::where('order_id', $data->id)->where('vendor_id', $vendor)->first();
    //                             if ($deliveryRider) {
    //                                 $rider = Rider::findOrFail($deliveryRider->rider_id);
    //                                 $service_area = RiderServiceArea::findOrFail($deliveryRider->service_area_id);
    //                                 $rider->balance += $service_area->price;
    //                                 // $rider->balance += $data->shipping_cost;
    //                                 $rider->update();
    //                             }
    //                         }
    //                     }
    //                     foreach ($data->vendororders as $vorder) {
    //                         $uprice = User::find($vorder->user_id);
    //                         $uprice->current_balance = $uprice->current_balance + $vorder->price;
    //                         $vorder->status = 'completed';
    //                         $vorder->update();
    //                         $uprice->update();
    //                         $uprice->update();
    //                     }
    //                     if ($data->is_shipping == 1) {
    //                         $vendor_ids = json_decode($data->vendor_ids, true);
    //                         $shipping_ids = json_decode($data->vendor_shipping_id, true);
    //                         $packaging_ids = json_decode($data->vendor_packing_id, true);
    //                         foreach ($vendor_ids as $vendor) {
    //                             $vendor = User::findOrFail($vendor);
    //                             if ($vendor) {
    //                                 $shpping_id = $shipping_ids[$vendor->id];
    //                                 $packaging_id = $packaging_ids[$vendor->id];
    //                                 $shipping = Shipping::findOrFail($shpping_id);
    //                                 $packaging = Package::findOrFail($packaging_id);
    //                                 $extra = 0;
    //                                 if ($shipping) {
    //                                     $extra += $shipping->price;
    //                                 }
    //                                 if ($packaging) {
    //                                     $extra += $packaging->price;
    //                                 }
    //                                 $vendor->current_balance = $vendor->current_balance + $extra;
    //                                 if ($data->method == 'Cash On Delivery') {
    //                                     $vendor->admin_commission += $extra;
    //                                 }
    //                                 $vendor->update();
    //                             }
    //                         }
    //                     }
    //                     if (User::where('id', $data->affilate_user)->exists()) {
    //                         $auser = User::where('id', $data->affilate_user)->first();
    //                         $auser->affilate_income += $data->affilate_charge;
    //                         $auser->update();
    //                         $affiliate_bonus = new AffliateBonus();
    //                         $affiliate_bonus->refer_id = $auser->id;
    //                         $affiliate_bonus->bonus =  $data->affilate_charge;
    //                         $affiliate_bonus->type = 'Order';
    //                         $affiliate_bonus->user_id = $data->user_id;
    //                         $affiliate_bonus->save();
    //                     }
    //                     if ($data->affilate_users != null) {
    //                         $ausers = json_decode($data->affilate_users, true);
    //                         foreach ($ausers as $auser) {
    //                             $user = User::find($auser['user_id']);
    //                             if ($user) {
    //                                 $user->affilate_income += $auser['charge'];
    //                                 $user->update();
    //                             }
    //                         }
    //                     }
    //                     $maildata = [
    //                         'to' => $data->customer_email,
    //                         'subject' => 'Your order ' . $data->order_number . ' is Confirmed!',
    //                         'body' => "Hello " . $data->customer_name . "," . "\n Thank you for shopping with us. We are looking forward to your next visit.",
    //                     ];
    //                     $mailer = new GeniusMailer();
    //                     $mailer->sendCustomMail($maildata);
    //                 }
    //                 if ($input['status'] == "declined") {
    //                     if ($data->user_id != 0) {
    //                         if ($data->wallet_price != 0) {
    //                             $user = User::find($data->user_id);
    //                             if ($user) {
    //                                 $user->balance = $user->balance + $data->wallet_price;
    //                                 $user->save();
    //                             }
    //                         }
    //                     }
    //                     $cart = json_decode($data->cart, true);
    //                     foreach ($cart->items as $prod) {
    //                         $x = (string)$prod['stock'];
    //                         if ($x != null) {
    //                             $product = Product::findOrFail($prod['item']['id']);
    //                             $product->stock = $product->stock + $prod['qty'];
    //                             $product->update();
    //                         }
    //                     }
    //                     foreach ($cart->items as $prod) {
    //                         $x = (string)$prod['size_qty'];
    //                         if (!empty($x)) {
    //                             $product = Product::findOrFail($prod['item']['id']);
    //                             $x = (int)$x;
    //                             $temp = $product->size_qty;
    //                             $temp[$prod['size_key']] = $x;
    //                             $temp1 = implode(',', $temp);
    //                             $product->size_qty =  $temp1;
    //                             $product->update();
    //                         }
    //                     }
    //                     $maildata = [
    //                         'to' => $data->customer_email,
    //                         'subject' => 'Your order ' . $data->order_number . ' is Declined!',
    //                         'body' => "Hello " . $data->customer_name . "," . "\n We are sorry for the inconvenience caused. We are looking forward to your next visit.",
    //                     ];
    //                     $mailer = new GeniusMailer();
    //                     $mailer->sendCustomMail($maildata);
    //                 }
    //                 $data->update($input);
    //                 if ($request->track_text) {
    //                     $title = ucwords($request->status);
    //                     $ck = OrderTrack::where('order_id', '=', $id)->where('title', '=', $title)->first();
    //                     if ($ck) {
    //                         $ck->order_id = $id;
    //                         $ck->title = $title;
    //                         $ck->text = $request->track_text;
    //                         $ck->update();
    //                     } else {
    //                         $data = new OrderTrack;
    //                         $data->order_id = $id;
    //                         $data->title = $title;
    //                         $data->text = $request->track_text;
    //                         $data->save();
    //                     }
    //                 }
    //                 $msg = __('Status Updated Successfully.');
    //                 return response()->json($msg);
    //             }
    //         }
    //         $data->update($input);
    //         $msg = __('Data Updated Successfully.');
    //         return redirect()->back()->with('success', $msg);
    //     }

    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            $data = Order::lockForUpdate()->findOrFail($id);
            $input = $request->all();
            if ($request->has('status')) {
                if ($input['status'] == 'completed') {

                    // Custom Referral Unlock Logic
                    if ($data->user_id) {
                        $lockedReferral = \App\Models\CustomReferral::where('referred_id', $data->user_id)
                            ->where('status', 'locked')
                            ->first();

                        if ($lockedReferral && now()->lessThanOrEqualTo($lockedReferral->expires_at)) {
                            // Sum all completed orders by this user
                            $totalSpent = \App\Models\Order::where('user_id', $data->user_id)
                                ->where('status', 'completed')
                                ->sum('pay_amount');

                            $totalSpent += $data->pay_amount;

                            if ($totalSpent >= 10000) {
                                $lockedReferral->status = 'unlocked';
                                $lockedReferral->save();

                                $referrer = \App\Models\User::find($lockedReferral->referrer_id);
                                if ($referrer) {
                                    $referrer->balance += $lockedReferral->amount;
                                    $referrer->save();

                                    \App\Models\WalletLedger::create([
                                        'user_id' => $referrer->id,
                                        'amount' => $lockedReferral->amount,
                                        'type' => 'referral_bonus',
                                        'status' => 'completed',
                                        'reference' => 'C-REF-'.$lockedReferral->id,
                                        'details' => 'Custom referral bonus unlocked for user ID: '.$data->user_id,
                                    ]);
                                }
                            }
                        }
                    }

                    $cart = json_decode($data->cart, true);
                    if (! empty($cart['items'])) {
                        foreach ($cart['items'] as $item) {
                            $productId = $item['item']['id'];
                            $productFee = $item['delivery_fee'] ?? 0;
                            $deliveryRiders = DeliveryRider::where([
                                'order_id' => $data->id,
                                'product_id' => $productId,
                            ])->get();
                            foreach ($deliveryRiders as $deliveryRider) {
                                $rider = Rider::lockForUpdate()->find($deliveryRider->rider_id);
                                if ($rider) {
                                    $rider->balance += $productFee;
                                    $rider->save();
                                }
                            }
                        }
                    }
                    foreach ($data->vendororders as $vorder) {
                        $uprice = User::lockForUpdate()->find($vorder->user_id);
                        if ($uprice) {
                            $uprice->current_balance = $uprice->current_balance + $vorder->price - $data->commission;
                            $vorder->status = 'completed';
                            $vorder->update();
                            $uprice->update();
                        }
                    }
                    if (User::where('id', $data->affilate_user)->exists()) {
                        $auser = User::lockForUpdate()->where('id', $data->affilate_user)->first();
                        $auser->affilate_income += $data->affilate_charge;
                        $auser->update();
                        $affiliate_bonus = new AffliateBonus();
                        $affiliate_bonus->refer_id = $auser->id;
                        $affiliate_bonus->bonus = $data->affilate_charge;
                        $affiliate_bonus->type = 'Order';
                        $affiliate_bonus->user_id = $data->user_id;
                        $affiliate_bonus->save();
                    }
                    if ($data->affilate_users != null) {
                        $ausers = json_decode($data->affilate_users, true);
                        foreach ($ausers as $auser) {
                            $user = User::lockForUpdate()->find($auser['user_id']);
                            if ($user) {
                                $user->affilate_income += $auser['charge'];
                                $user->update();
                            }
                        }
                    }
                    // Release Escrow
                    $data->escrow_status = 'released';
                    $data->update();

                    WalletLedger::create([
                        'user_id' => $data->user_id ?? 0,
                        'amount' => $data->pay_amount,
                        'type' => 'escrow_release',
                        'order_id' => $data->id,
                        'reference' => $data->txnid,
                        'status' => 'completed',
                        'details' => 'Escrow released upon order completion',
                    ]);

                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => 'Your order '.$data->order_number.' is Confirmed!',
                        'body' => 'Hello '.$data->customer_name.','."\n Thank you for shopping with us. We are looking forward to your next visit.",
                    ];
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($maildata);
                }
                if ($input['status'] == 'declined') {
                    if ($data->user_id != 0) {
                        if ($data->wallet_price != 0) {
                            $user = User::lockForUpdate()->find($data->user_id);
                            if ($user) {
                                $user->balance = $user->balance + $data->wallet_price;
                                $user->save();
                            }
                        }
                    }
                    $cart = json_decode($data->cart, true);
                    foreach ($cart->items as $prod) {
                        $x = (string) $prod['stock'];
                        if ($x != null) {
                            $product = Product::findOrFail($prod['item']['id']);
                            $product->stock = $product->stock + $prod['qty'];
                            $product->update();
                        }
                    }
                    foreach ($cart->items as $prod) {
                        $x = (string) $prod['size_qty'];
                        if (! empty($x)) {
                            $product = Product::findOrFail($prod['item']['id']);
                            $x = (int) $x;
                            $temp = $product->size_qty;
                            $temp[$prod['size_key']] = $x;
                            $temp1 = implode(',', $temp);
                            $product->size_qty = $temp1;
                            $product->update();
                        }
                    }
                    $maildata = [
                        'to' => $data->customer_email,
                        'subject' => 'Your order '.$data->order_number.' is Declined!',
                        'body' => 'Hello '.$data->customer_name.','."\n We are sorry for the inconvenience caused. We are looking forward to your next visit.",
                    ];
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($maildata);
                }
                $data->update($input);
                if ($request->track_text) {
                    $title = ucwords($request->status);
                    $ck = OrderTrack::where('order_id', '=', $id)->where('title', '=', $title)->first();
                    if ($ck) {
                        $ck->order_id = $id;
                        $ck->title = $title;
                        $ck->text = $request->track_text;
                        $ck->update();
                    } else {
                        $track = new OrderTrack;
                        $track->order_id = $id;
                        $track->title = $title;
                        $track->text = $request->track_text;
                        $track->save();
                    }
                }
                DB::commit();
                $msg = __('Status Updated Successfully.');

                return response()->json($msg);
            }
            $data->update($input);
            DB::commit();
            $msg = __('Data Updated Successfully.');

            return redirect()->back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->has('status')) {
                return response()->json(['errors' => [$e->getMessage()]], 500);
            }

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    //     public function update(Request $request, $id)
    // {
    //     $order = Order::findOrFail($id);
    //     $input = $request->all();

    //     if ($request->has('status')) {

    //         /*-------------------------------------------------------
    //         | ✅ COMPLETED + PAID  → Rider ko delivery fee deni hai
    //         -------------------------------------------------------*/
    //         if (
    //             isset($input['status']) && $input['status'] === 'completed' &&
    //             isset($input['payment_status']) && $input['payment_status'] === 'Paid'
    //         ) {
    //             // 👉 Rider ko sirf aik martaba pay karna
    //             if (!$order->rider_payment_done) {

    //                 // vendor_ids JSON se nikalna
    //                 $vendorIds = $order->vendor_ids ? json_decode($order->vendor_ids, true) : [];
    //                 $cartItems = json_decode($order->cart, true)['items'] ?? [];

    //                 foreach ($vendorIds as $vendorId) {

    //                     // delivery_riders se rider nikalna
    //                     $deliveryRider = DeliveryRider::where('order_id', $order->id)
    //                         ->where('vendor_id', $vendorId)
    //                         ->first();

    //                     if ($deliveryRider) {
    //                         $vendorDeliveryFee = 0;

    //                         // is vendor ke items ka total delivery_fee
    //                         foreach ($cartItems as $item) {
    //                             if ((int)$item['user_id'] === (int)$vendorId) {
    //                                 $fee = $item['delivery_fee'] ?? 0;
    //                                 $vendorDeliveryFee += ($fee * $item['qty']);
    //                             }
    //                         }

    //                         // rider balance add
    //                         if ($vendorDeliveryFee > 0) {
    //                             $rider = Rider::find($deliveryRider->rider_id);
    //                             if ($rider) {
    //                                 $rider->balance += $vendorDeliveryFee;
    //                                 $rider->save();
    //                             }
    //                         }
    //                     }
    //                 }

    //                 // flag set -> taake dobara payment na ho
    //                 $order->rider_payment_done = 1;
    //                 $order->save();
    //             }

    //             /*-------------------------------------------------------
    //             | ✅ Vendors ko unka paisa (no change)
    //             -------------------------------------------------------*/
    //             foreach ($order->vendororders as $vorder) {
    //                 $uprice = User::find($vorder->user_id);
    //                 if ($uprice) {
    //                     $uprice->current_balance =
    //                         $uprice->current_balance + $vorder->price - $order->commission;
    //                     $vorder->status = 'completed';
    //                     $vorder->update();
    //                     $uprice->update();
    //                 }
    //             }

    //             /*-------------------------------------------------------
    //             | ✅ Affiliate logic (no change)
    //             -------------------------------------------------------*/
    //             if (User::where('id', $order->affilate_user)->exists()) {
    //                 $auser = User::where('id', $order->affilate_user)->first();
    //                 $auser->affilate_income += $order->affilate_charge;
    //                 $auser->update();

    //                 $affiliate_bonus = new AffliateBonus();
    //                 $affiliate_bonus->refer_id = $auser->id;
    //                 $affiliate_bonus->bonus =  $order->affilate_charge;
    //                 $affiliate_bonus->type  = 'Order';
    //                 $affiliate_bonus->user_id = $order->user_id;
    //                 $affiliate_bonus->save();
    //             }

    //             if ($order->affilate_users) {
    //                 $ausers = json_decode($order->affilate_users, true);
    //                 foreach ($ausers as $auser) {
    //                     $user = User::find($auser['user_id']);
    //                     if ($user) {
    //                         $user->affilate_income += $auser['charge'];
    //                         $user->update();
    //                     }
    //                 }
    //             }

    //             /*-------------------------------------------------------
    //             | ✅ Order confirmation mail
    //             -------------------------------------------------------*/
    //             $maildata = [
    //                 'to'      => $order->customer_email,
    //                 'subject' => 'Your order ' . $order->order_number . ' is Confirmed!',
    //                 'body'    => "Hello " . $order->customer_name . ",\nThank you for shopping with us. We are looking forward to your next visit.",
    //             ];
    //             (new GeniusMailer())->sendCustomMail($maildata);
    //         }

    //         /*-------------------------------------------------------
    //         | ❌ DECLINED logic (unchanged)
    //         -------------------------------------------------------*/
    //         if (isset($input['status']) && $input['status'] === 'declined') {

    //             if ($order->user_id != 0 && $order->wallet_price != 0) {
    //                 $user = User::find($order->user_id);
    //                 if ($user) {
    //                     $user->balance += $order->wallet_price;
    //                     $user->save();
    //                 }
    //             }

    //             $cart = json_decode($order->cart, true);
    //             foreach ($cart['items'] as $prod) {
    //                 if (!empty($prod['stock'])) {
    //                     $product = Product::findOrFail($prod['item']['id']);
    //                     $product->stock += $prod['qty'];
    //                     $product->update();
    //                 }
    //             }
    //             foreach ($cart['items'] as $prod) {
    //                 if (!empty($prod['size_qty'])) {
    //                     $product = Product::findOrFail($prod['item']['id']);
    //                     $temp = $product->size_qty;
    //                     $temp[$prod['size_key']] = (int)$prod['size_qty'];
    //                     $product->size_qty = implode(',', $temp);
    //                     $product->update();
    //                 }
    //             }

    //             $maildata = [
    //                 'to'      => $order->customer_email,
    //                 'subject' => 'Your order ' . $order->order_number . ' is Declined!',
    //                 'body'    => "Hello " . $order->customer_name . ",\nWe are sorry for the inconvenience caused. We are looking forward to your next visit.",
    //             ];
    //             (new GeniusMailer())->sendCustomMail($maildata);
    //         }

    //         /*-------------------------------------------------------
    //         | ✅ Update order record + tracking
    //         -------------------------------------------------------*/
    //         $order->update($input);

    //         if ($request->track_text) {
    //             $title = ucwords($request->status);
    //             $ck = OrderTrack::where('order_id', $id)->where('title', $title)->first();
    //             if ($ck) {
    //                 $ck->update([
    //                     'text' => $request->track_text
    //                 ]);
    //             } else {
    //                 OrderTrack::create([
    //                     'order_id' => $id,
    //                     'title'    => $title,
    //                     'text'     => $request->track_text,
    //                 ]);
    //             }
    //         }

    //         return response()->json(__('Status Updated Successfully.'));
    //     }

    //     // 👉 Default update if no status
    //     $order->update($input);
    //     return redirect()->back()->with('success', __('Data Updated Successfully.'));
    // }

    public function product_submit(Request $request)
    {

        $sku = $request->sku;
        $product = Product::whereUserId($request->vendor_id)->whereStatus(1)->where('sku', $sku)->first();
        $data = [];
        if (! $product) {
            $data[0] = false;
            $data[1] = __('No Product Found');
        } else {
            $data[0] = true;
            $data[1] = $product->id;
        }

        return response()->json($data);
    }

    public function product_show($id)
    {
        $data['productt'] = Product::find($id);
        $data['curr'] = $this->curr;

        return view('admin.order.add-product', $data);
    }

    public function addcart($id)
    {
        $order = Order::find($id);
        $id = $_GET['id'];
        $qty = $_GET['qty'];
        $size = str_replace(' ', '-', $_GET['size']);
        $color = $_GET['color'];
        $size_qty = $_GET['size_qty'];
        $size_price = (float) $_GET['size_price'];
        $size_key = $_GET['size_key'];
        $affilate_user = isset($_GET['affilate_user']) ? $_GET['affilate_user'] : '0';
        $keys = $_GET['keys'];
        $keys = explode(',', $keys);
        $values = $_GET['values'];
        $values = explode(',', $values);
        $prices = $_GET['prices'];
        $prices = explode(',', $prices);
        $keys = $keys == '' ? '' : implode(',', $keys);
        $values = $values == '' ? '' : implode(',', $values);
        $size_price = ($size_price / $order->currency_value);
        $prod = Product::where('id', '=', $id)->first(['id', 'user_id', 'slug', 'name', 'photo', 'size', 'size_qty', 'size_price', 'color', 'price', 'stock', 'type', 'file', 'link', 'license', 'license_qty', 'measure', 'whole_sell_qty', 'whole_sell_discount', 'attributes', 'minimum_qty']);

        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->gs->fixed_commission + ($prod->price / 100) * $this->gs->percentage_commission;
            $prod->price = round($prc, 2);
        }
        if (! empty($prices)) {
            if (! empty($prices[0])) {
                foreach ($prices as $data) {
                    $prod->price += ($data / $order->currency_value);
                }
            }
        }

        if (! empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return 0;
            }
        }

        if (empty($size)) {
            if (! empty($prod->size)) {
                $size = trim($prod->size[0]);
            }
            $size = str_replace(' ', '-', $size);
        }

        if (empty($color)) {
            if (! empty($prod->color)) {
                $color = $prod->color[0];
            }
        }

        $color = str_replace('#', '', $color);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        if (! empty($cart->items)) {
            if (! empty($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)])) {
                $minimum_qty = (int) $prod->minimum_qty;
                if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'] < $minimum_qty) {
                    return redirect()->back()->with('unsuccess', __('Minimum Quantity is:').' '.$prod->minimum_qty);
                }
            } else {
                if ($prod->minimum_qty != null) {
                    $minimum_qty = (int) $prod->minimum_qty;
                    if ($qty < $minimum_qty) {
                        return redirect()->back()->with('unsuccess', __('Minimum Quantity is:').' '.$prod->minimum_qty);
                    }
                }
            }
        } else {
            $minimum_qty = (int) $prod->minimum_qty;
            if ($prod->minimum_qty != null) {
                if ($qty < $minimum_qty) {
                    return redirect()->back()->with('unsuccess', __('Minimum Quantity is:').' '.$prod->minimum_qty);
                }
            }
        }

        $cart->addnum($prod, $prod->id, $qty, $size, $color, $size_qty, $size_price, $size_key, $keys, $values, $affilate_user);
        if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['dp'] == 1) {
            return redirect()->back()->with('unsuccess', __('This item is already in the cart.'));
        }
        if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['stock'] < 0) {
            return redirect()->back()->with('unsuccess', __('Out Of Stock.'));
        }
        if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['size_qty']) {
            if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'] > $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['size_qty']) {
                return redirect()->back()->with('unsuccess', __('Out Of Stock.'));
            }
        }

        $cart->totalPrice = 0;
        foreach ($cart->items as $data) {
            $cart->totalPrice += $data['price'];
        }
        $o_cart = json_decode($order->cart, true);

        $order->totalQty = $order->totalQty + $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'];
        $order->pay_amount = $order->pay_amount + $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'];

        $prev_qty = 0;
        $prev_price = 0;

        if (! empty($o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)])) {
            $prev_qty = $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'];
            $prev_price = $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'];
        }

        $prev_qty += $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'];
        $prev_price += $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'];

        $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)] = $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)];
        $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'] = $prev_qty;
        $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'] = $prev_price;

        $order->cart = json_encode($o_cart);

        $order->update();

        return redirect()->back()->with('success', __('Successfully Added To Cart.'));
    }

    public function product_edit($id, $itemid, $orderid)
    {

        $product = Product::find($itemid);
        $order = Order::find($orderid);
        $cart = json_decode($order->cart, true);
        $data['productt'] = $product;
        $data['item_id'] = $id;
        $data['prod'] = $id;
        $data['order'] = $order;
        $data['item'] = $cart['items'][$id];
        $data['curr'] = $this->curr;

        return view('admin.order.edit-product', $data);
    }

    public function updatecart($id)
    {
        $order = Order::find($id);
        $id = $_GET['id'];
        $qty = $_GET['qty'];
        $size = str_replace(' ', '-', $_GET['size']);
        $color = $_GET['color'];
        $size_qty = $_GET['size_qty'];
        $size_price = (float) $_GET['size_price'];
        $size_key = $_GET['size_key'];
        $affilate_user = isset($_GET['affilate_user']) ? $_GET['affilate_user'] : '0';
        $keys = $_GET['keys'];
        $keys = explode(',', $keys);
        $values = $_GET['values'];
        $values = explode(',', $values);
        $prices = $_GET['prices'];
        $prices = explode(',', $prices);
        $keys = $keys == '' ? '' : implode(',', $keys);
        $values = $values == '' ? '' : implode(',', $values);

        $item_id = $_GET['item_id'];

        $size_price = ($size_price / $order->currency_value);
        $prod = Product::where('id', '=', $id)->first(['id', 'user_id', 'slug', 'name', 'photo', 'size', 'size_qty', 'size_price', 'color', 'price', 'stock', 'type', 'file', 'link', 'license', 'license_qty', 'measure', 'whole_sell_qty', 'whole_sell_discount', 'attributes', 'minimum_qty']);

        if ($prod->user_id != 0) {
            $prc = $prod->price + $this->gs->fixed_commission + ($prod->price / 100) * $this->gs->percentage_commission;
            $prod->price = round($prc, 2);
        }
        if (! empty($prices)) {
            if (! empty($prices[0])) {
                foreach ($prices as $data) {
                    $prod->price += ($data / $order->currency_value);
                }
            }
        }

        if (! empty($prod->license_qty)) {
            $lcheck = 1;
            foreach ($prod->license_qty as $ttl => $dtl) {
                if ($dtl < 1) {
                    $lcheck = 0;
                } else {
                    $lcheck = 1;
                    break;
                }
            }
            if ($lcheck == 0) {
                return 0;
            }
        }
        if (empty($size)) {
            if (! empty($prod->size)) {
                $size = trim($prod->size[0]);
            }
            $size = str_replace(' ', '-', $size);
        }

        if (empty($color)) {
            if (! empty($prod->color)) {
                $color = $prod->color[0];
            }
        }
        $color = str_replace('#', '', $color);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

        if (! empty($cart->items)) {
            if (! empty($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)])) {
                $minimum_qty = (int) $prod->minimum_qty;
                if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'] < $minimum_qty) {
                    return redirect()->back()->with('unsuccess', __('Minimum Quantity is:').' '.$prod->minimum_qty);
                }
            } else {
                if ($prod->minimum_qty != null) {
                    $minimum_qty = (int) $prod->minimum_qty;
                    if ($qty < $minimum_qty) {
                        return redirect()->back()->with('unsuccess', __('Minimum Quantity is:').' '.$prod->minimum_qty);
                    }
                }
            }
        } else {
            $minimum_qty = (int) $prod->minimum_qty;
            if ($prod->minimum_qty != null) {
                if ($qty < $minimum_qty) {
                    return redirect()->back()->with('unsuccess', __('Minimum Quantity is:').' '.$prod->minimum_qty);
                }
            }
        }

        $cart->addnum($prod, $prod->id, $qty, $size, $color, $size_qty, $size_price, $size_key, $keys, $values, $affilate_user);
        if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['dp'] == 1) {
            return redirect()->back()->with('unsuccess', __('This item is already in the cart.'));
        }
        if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['stock'] < 0) {
            return redirect()->back()->with('unsuccess', __('Out Of Stock.'));
        }
        if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['size_qty']) {
            if ($cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'] > $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['size_qty']) {
                return redirect()->back()->with('unsuccess', __('Out Of Stock.'));
            }
        }

        $cart->totalPrice = 0;
        foreach ($cart->items as $data) {
            $cart->totalPrice += $data['price'];
        }
        $o_cart = json_decode($order->cart, true);

        if (! empty($o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)])) {

            $cart_qty = $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'];
            $cart_price = $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'];

            $prev_qty = $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'];
            $prev_price = $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'];

            $temp_qty = 0;
            $temp_price = 0;

            if ($o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'] < $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty']) {

                $temp_qty = $cart_qty - $prev_qty;
                $temp_price = $cart_price - $prev_price;

                $order->totalQty += $temp_qty;
                $order->pay_amount += $temp_price;
                $prev_qty += $temp_qty;
                $prev_price += $temp_price;
            } elseif ($o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'] > $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty']) {

                $temp_qty = $prev_qty - $cart_qty;
                $temp_price = $prev_price - $cart_price;

                $order->totalQty -= $temp_qty;
                $order->pay_amount -= $temp_price;
                $prev_qty -= $temp_qty;
                $prev_price -= $temp_price;
            }
        } else {

            $order->totalQty -= $o_cart['items'][$item_id]['qty'];

            $order->pay_amount -= $o_cart['items'][$item_id]['price'];

            unset($o_cart['items'][$item_id]);

            $order->totalQty = $order->totalQty + $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'];
            $order->pay_amount = $order->pay_amount + $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'];

            $prev_qty = 0;
            $prev_price = 0;

            if (! empty($o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)])) {
                $prev_qty = $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'];
                $prev_price = $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'];
            }

            $prev_qty += $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'];
            $prev_price += $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'];
        }

        $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)] = $cart->items[$id.$size.$color.str_replace(str_split(' ,'), '', $values)];
        $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['qty'] = $prev_qty;
        $o_cart['items'][$id.$size.$color.str_replace(str_split(' ,'), '', $values)]['price'] = $prev_price;

        $order->cart = json_encode($o_cart);

        $order->update();

        return redirect()->back()->with('success', __('Successfully Updated The Cart.'));
    }

    public function product_delete($id, $orderid)
    {

        $order = Order::find($orderid);
        $cart = json_decode($order->cart, true);

        $order->totalQty = $order->totalQty - $cart['items'][$id]['qty'];
        $order->pay_amount = $order->pay_amount - $cart['items'][$id]['price'];
        unset($cart['items'][$id]);
        $order->cart = json_encode($cart);

        $order->update();

        return redirect()->back()->with('success', __('Successfully Deleted From The Cart.'));
    }
}
