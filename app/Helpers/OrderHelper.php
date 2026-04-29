<?php

namespace App\Helpers;

use App\Models\AffliateBonus;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use Auth;
use Illuminate\Support\Str;
use Session;

class OrderHelper
{
    public static function auth_check($data)
    {
        try {
            $resdata = [];
            $users = User::where('email', '=', $data['personal_email'])->get();
            if (count($users) == 0) {
                if ($data['personal_pass'] == $data['personal_confirm']) {
                    $user = new User;
                    $user->name = $data['personal_name'];
                    $user->email = $data['personal_email'];
                    $user->password = bcrypt($data['personal_pass']);
                    $token = md5(time().$data['personal_name'].$data['personal_email']);
                    $user->verification_link = $token;
                    $user->affilate_code = md5($data['personal_name'].$data['personal_email']);
                    $user->email_verified = 'Yes';
                    $user->save();
                    Auth::login($user);
                    $resdata['auth_success'] = true;
                } else {
                    $resdata['auth_success'] = false;
                    $resdata['error_message'] = __("Confirm Password Doesn't Match.");
                }
            } else {
                $resdata['auth_success'] = false;
                $resdata['error_message'] = __('This Email Already Exist.');
            }

            return $resdata;
        } catch (\Exception $e) {
        }
    }

    public static function license_check($cart)
    {

        foreach ($cart->items as $key => $prod) {
            if (! empty($prod['item']['license']) && ! empty($prod['item']['license_qty'])) {
                foreach ($prod['item']['license_qty'] as $ttl => $dtl) {
                    if ($dtl != 0) {
                        $dtl--;
                        $produc = Product::find($prod['item']['id']);
                        $temp = $produc->license_qty;
                        $temp[$ttl] = $dtl;
                        $final = implode(',', $temp);
                        $produc->license_qty = $final;
                        $produc->update();
                        $temp = $produc->license;
                        $license = $temp[$ttl];
                        $oldCart = Session::has('cart') ? Session::get('cart') : null;
                        $cart = new Cart($oldCart);
                        $cart->updateLicense($prod['item']['id'], $license);

                        Session::put('cart', $cart);
                        break;
                    }
                }
            }
        }
    }

    public static function product_affilate_check($cart)
    {
        $affilate_users = null;
        $i = 0;
        $gs = Generalsetting::safeFirst();
        $percentage = $gs->affilate_charge / 100;
        foreach ($cart->items as $prod) {

            if ($prod['affilate_user'] != 0) {
                if (Auth::user()->id != $prod['affilate_user']) {
                    $affilate_users[$i]['user_id'] = $prod['affilate_user'];
                    $affilate_users[$i]['product_id'] = $prod['item']['id'];
                    $price = $prod['price'] * $percentage;
                    $affilate_users[$i]['charge'] = $price;
                    $i++;
                }
            }
        }

        return $affilate_users;
    }

    public static function set_currency($new_value)
    {

        try {
            $oldCart = Session::get('cart');
            $cart = new Cart($oldCart);

            foreach ($cart->items as $key => $prod) {

                $cart->items[$key]['price'] = $cart->items[$key]['price'] * $new_value;
                $cart->items[$key]['item']['price'] = $cart->items[$key]['item']['price'] * $new_value;
            }
            Session::put('cart', $cart);
        } catch (\Exception $e) {
        }
    }

    public static function affilate_check($id, $sub, $dp)
    {
        try {
            $user = User::find($id);
            if ($dp == 1) {
                $affiliate_bonus = new AffliateBonus();
                $affiliate_bonus->refer_id = $user->id;
                $affiliate_bonus->bonus = $sub;
                $affiliate_bonus->type = 'Order';
                if (Auth::user()) {
                    $affiliate_bonus->refer_id = Auth::user()->id;
                }
                $affiliate_bonus->save();
                $user->affilate_income += $sub;
                $user->update();
            }

            return $user;
        } catch (\Exception $e) {
            \Log::error('Affiliate Check Error: '.$e->getMessage());
        }
    }

    public static function coupon_check($id)
    {
        try {
            if (empty($id)) {
                return;
            }
            $coupon = Coupon::find($id);
            if (!$coupon) {
                return;
            }
            $coupon->used++;
            if ($coupon->times !== null) {
                $i = (int) $coupon->times;
                if ($i > 0) {
                    $i--;
                }
                $coupon->times = (string) $i;
            }
            $coupon->update();
        } catch (\Exception $e) {
            \Log::error('Coupon Check Error: '.$e->getMessage());
        }
    }

    /**
     * Unified order finalization logic to handle coupons, rewards, stock, and session clearing.
     */
    public static function finalizeOrder($order, $cart)
    {
        try {
            // Fallback for total_delivery_fee if not set by controller
            if (empty($order->total_delivery_fee)) {
                $order->total_delivery_fee = PriceHelper::calculateDeliveryFee($cart);
                $order->update();
            }

            // 1. Marketplace Commission Calculation
            $orderCommission = 0;
            if ($cart->items) {
                foreach ($cart->items as $item) {
                    $product = Product::find($item['item']['id']);
                    if ($product) {
                        $basePrice = (float)$product->getAttributes()['price'];
                        if (!$product->isDiscountActive() && !empty($product->previous_price) && $product->previous_price > 0) {
                            $basePrice = (float)$product->previous_price;
                        }
                        $commission = Product::getTieredCommission($basePrice);
                        $orderCommission += ($commission * $item['qty']);
                    }
                }
            }
            $order->commission = $orderCommission;
            $order->update();

            // 2. Order Tracks & Notifications
            $order->tracks()->create(['title' => 'Pending', 'text' => 'You have successfully placed your order.']);
            $order->notifications()->create();

            // 3. Coupon & Referral Tracking
            if (! empty($order->coupon_id)) {
                if ($order->coupon_id === 'referral' || Session::get('coupon_is_referral') === true) {
                    // Trigger new referral reward logic
                    app(\App\Services\ReferralService::class)->applyReferralReward($order);
                } else {
                    // Standard coupon tracking
                    self::coupon_check($order->coupon_id);
                }
            }

            // 4. Reward Points
            if (Auth::check()) {
                $gs = Generalsetting::safeFirst();
                if ($gs->is_reward == 1) {
                    $num = $order->pay_amount;
                    $rewards = \App\Models\Reward::get();
                    $smallest = [];
                    foreach ($rewards as $i) {
                        $smallest[$i->order_amount] = abs($i->order_amount - $num);
                    }

                    if (! empty($smallest)) {
                        asort($smallest);
                        $final_reword = \App\Models\Reward::where('order_amount', key($smallest))->first();
                        Auth::user()->update(['reward' => (Auth::user()->reward + $final_reword->reward)]);
                    }
                }
            }

            // 5. Stock & Qty Checks
            self::size_qty_check($cart);
            self::stock_check($cart);
            self::vendor_order_check($cart, $order);

            // 6. Send Email & In-App Notifications (Buyer + Seller + Admin)
            try {
                \App\Services\FabiliveNotifier::orderPlaced($order);
            } catch (\Exception $ne) {
                \Log::error('Order Notification Error: ' . $ne->getMessage());
            }

            // 7. Clear All Related Sessions (Prevents "Ghost Coupons")
            Session::put('temporder', $order);
            Session::put('tempcart', $cart);
            Session::forget('cart');
            Session::forget('already');
            Session::forget('coupon');
            Session::forget('coupon_code');
            Session::forget('coupon_id');
            Session::forget('coupon_total');
            Session::forget('coupon_total1');
            Session::forget('coupon_percentage');
            Session::forget('coupon_is_referral');
            Session::forget('current_tax');
        } catch (\Exception $e) {
            \Log::error('Order Finalization Error: '.$e->getMessage());
        }
    }

    public static function size_qty_check($cart)
    {
        try {
            foreach ($cart->items as $prod) {
                $x = (string) $prod['size_qty'];
                if (! empty($x)) {
                    $product = Product::find($prod['item']['id']);
                    $x = (int) $x;
                    $x = $x - $prod['qty'];
                    $temp = $product->size_qty;
                    $temp[$prod['size_key']] = $x;
                    $temp1 = implode(',', $temp);
                    $product->size_qty = $temp1;
                    $product->update();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Size Qty Check Error: '.$e->getMessage());
            throw $e;
        }
    }

    public static function stock_check($cart)
    {
        try {
            foreach ($cart->items as $prod) {
                $x = (string) $prod['stock'];
                if ($x != null) {

                    $product = Product::find($prod['item']['id']);
                    $product->stock = $prod['stock'];
                    $product->update();
                    if ($product->stock <= 5) {
                        $notification = new Notification;
                        $notification->product_id = $product->id;
                        $notification->save();
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('Stock Check Error: '.$e->getMessage());
            throw $e;
        }
    }

    public static function vendor_order_check($cart, $order)
    {

        try {
            $notf = [];

            foreach ($cart->items as $prod) {
                if ($prod['item']['user_id'] != 0) {
                    $vorder = new VendorOrder();
                    $vorder->order_id = $order->id;
                    $vorder->user_id = $prod['item']['user_id'];
                    $vorder->qty = $prod['qty'];
                    $product = Product::find($prod['item']['id']);
                    if ($product) {
                        $basePrice = (float)$product->getAttributes()['price'];
                        if (!$product->isDiscountActive() && !empty($product->previous_price) && $product->previous_price > 0) {
                            $basePrice = (float)$product->previous_price;
                        }
                        $commission = Product::getTieredCommission($basePrice);
                        $vorder->price = $prod['price'] - ($commission * $prod['qty']);
                    } else {
                        $vorder->price = $prod['price'];
                    }
                    $vorder->order_number = $order->order_number;
                    $vorder->save();
                    $notf[] = $prod['item']['user_id'];
                }
            }

            if (! empty($notf)) {
                $users = array_unique($notf);
                foreach ($users as $user) {
                    $notification = new UserNotification;
                    $notification->user_id = $user;
                    $notification->order_number = $order->order_number;
                    $notification->save();
                }
            }
        } catch (\Exception $e) {
            \Log::error('Vendor Order Check Error: '.$e->getMessage());
            throw $e;
        }
    }

    public static function add_to_transaction($data, $price)
    {
        try {
            $transaction = new Transaction;
            $transaction->txn_number = Str::random(3).substr(time(), 6, 8).Str::random(3);
            $transaction->user_id = $data->user_id;
            $transaction->amount = $price;
            $transaction->currency_sign = $data->currency_sign;
            $transaction->currency_code = $data->currency_name;
            $transaction->currency_value = $data->currency_value;
            $transaction->details = 'Payment Via Wallet';
            $transaction->type = 'minus';
            $transaction->save();
            $balance = $price;
            $user = $transaction->user;
            $user->balance = $user->balance - $balance;
            $user->update();
        } catch (\Exception $e) {
            \Log::error('Add to Transaction Error: '.$e->getMessage());
            throw $e;
        }
    }

    public static function mollie_currencies()
    {
        return [
            'AED',
            'AUD',
            'BGN',
            'BRL',
            'CAD',
            'CHF',
            'CZK',
            'DKK',
            'EUR',
            'GBP',
            'HKD',
            'HRK',
            'HUF',
            'ILS',
            'ISK',
            'JPY',
            'MXN',
            'MYR',
            'NOK',
            'NZD',
            'PHP',
            'PLN',
            'RON',
            'RUB',
            'SEK',
            'SGD',
            'THB',
            'TWD',
            'USD',
            'ZAR',
        ];
    }

    public static function flutter_currencies()
    {
        return [
            'BIF',
            'CAD',
            'CDF',
            'CVE',
            'EUR',
            'GBP',
            'GHS',
            'GMD',
            'GNF',
            'KES',
            'LRD',
            'MWK',
            'NGN',
            'RWF',
            'SLL',
            'STD',
            'TZS',
            'UGX',
            'USD',
            'XAF',
            'XOF',
            'ZMK',
            'ZMW',
            'ZWD',
        ];
    }

    public static function mercadopago_currencies()
    {
        return [
            'ARS',
            'BRL',
            'CLP',
            'MXN',
            'PEN',
            'UYU',
            'VEF',
        ];
    }
}
