<?php

namespace App\Http\Controllers\Front;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use Auth;
use Session;
use App\Helpers\PriceHelper;
use App\Services\ReferralService;


class CouponController extends FrontBaseController
{
    public function coupon()
    {
        try {
            $gs = $this->gs;
            $code = request()->get('code');
            $total = (float) preg_replace('/[^0-9\.]/ui', '', request()->get('total', 0));
            $coupon = Coupon::where('code', '=', $code)->where('status', 1)->first();

            if (!$coupon) {
                // Check if it's a Referral Code
                $referralService = app(\App\Services\ReferralService::class);
                $user = Auth::user();
                $referralCode = $referralService->validateReferralForCoupon($code, $user);

                $curr = $this->curr;
                $discount = 200 * $curr->value;
                if ($discount >= $total) {
                    return response()->json(3); // Discount more than total
                }

                $total = $total - $discount;
                $data[0] = \PriceHelper::showCurrencyPrice($total);
                $data[1] = $code;
                $data[2] = $discount;
                Session::put('coupon', $data[2]);
                Session::put('coupon_code', $code);
                Session::put('coupon_id', 'referral');
                Session::put('coupon_is_referral', true);
                Session::put('coupon_total', $data[0]);
                $data[3] = 'referral';
                $data[4] = \PriceHelper::showCurrencyPrice($data[2]);
                $data[5] = 1;

                return response()->json($data);
            }
        } catch (\Throwable $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }


        $cart = Session::get('cart');
        foreach ($cart->items as $item) {
            $product = Product::findOrFail($item['item']['id']);

            if ($coupon->coupon_type == 'category') {
                $coupon_cats = explode(',', $coupon->category);
                if (in_array('all', $coupon_cats) || in_array($product->category_id, $coupon_cats)) {
                    $coupon_check_type[] = 1;
                } else {
                    $coupon_check_type[] = 0;
                }
            } elseif ($coupon->coupon_type == 'sub_category') {
                $coupon_subs = explode(',', $coupon->sub_category);
                if (in_array('all', $coupon_subs) || in_array($product->subcategory_id, $coupon_subs)) {
                    $coupon_check_type[] = 1;
                } else {
                    $coupon_check_type[] = 0;
                }
            } elseif ($coupon->coupon_type == 'child_category') {
                $coupon_childs = explode(',', $coupon->child_category);
                if (in_array('all', $coupon_childs) || in_array($product->childcategory_id, $coupon_childs)) {
                    $coupon_check_type[] = 1;
                } else {
                    $coupon_check_type[] = 0;
                }
            } else {
                $coupon_check_type[] = 0;
            }
        }

        if (in_array(0, $coupon_check_type)) {
            return response()->json(0);
        }

        $fnd = Coupon::where('code', '=', $code)->get()->count();
        if ($fnd < 1) {
            return response()->json(0);
        } else {
            $coupon = Coupon::where('code', '=', $code)->first();
            $curr = $this->curr;
            if ($coupon->times != null) {
                if ($coupon->times == '0') {
                    return response()->json(0);
                }
            }
            $today = date('Y-m-d');
            $from = date('Y-m-d', strtotime($coupon->start_date));
            $to = date('Y-m-d', strtotime($coupon->end_date));
            if ($from <= $today && $to >= $today) {
                if ($coupon->status == 1) {
                    $oldCart = Session::has('cart') ? Session::get('cart') : null;
                    $val = Session::has('already') ? Session::get('already') : null;
                    if ($val == $code) {
                        return response()->json(2);
                    }
                    $cart = new Cart($oldCart);
                    if ($coupon->type == 0) {
                        if ($coupon->price >= $total) {
                            return response()->json(3);
                        }
                        Session::put('already', $code);
                        $coupon->price = (int) $coupon->price;
                        $val = $total / 100;
                        $sub = $val * $coupon->price;
                        $total = $total - $sub;
                        $data[0] = \PriceHelper::showCurrencyPrice($total);
                        $data[1] = $code;
                        $data[2] = round($sub, 2);
                        Session::put('coupon', $data[2]);
                        Session::put('coupon_code', $code);
                        Session::put('coupon_id', $coupon->id);
                        Session::put('coupon_total', $data[0]);
                        $data[3] = $coupon->id;
                        $data[4] = $coupon->price.'%';
                        $data[5] = 1;

                        Session::put('coupon_percentage', $data[4]);

                        return response()->json($data);
                    } else {
                        if ($coupon->price >= $total) {
                            return response()->json(3);
                        }
                        Session::put('already', $code);
                        $total = $total - round($coupon->price * $curr->value, 2);
                        $data[0] = $total;
                        $data[1] = $code;
                        $data[2] = $coupon->price * $curr->value;
                        Session::put('coupon', $data[2]);
                        Session::put('coupon_code', $code);
                        Session::put('coupon_id', $coupon->id);
                        Session::put('coupon_total', $data[0]);
                        $data[3] = $coupon->id;
                        $data[4] = \PriceHelper::showCurrencyPrice($data[2]);
                        $data[0] = \PriceHelper::showCurrencyPrice($data[0]);
                        Session::put('coupon_percentage', 0);
                        $data[5] = 1;

                        return response()->json($data);
                    }
                } else {
                    return response()->json(0);
                }
            } else {
                return response()->json(0);
            }
        }
    }

    public function couponcheck()
    {
        try {
            $gs = $this->gs;
            $code = request()->get('code');
            $coupon = Coupon::where('code', '=', $code)->where('status', 1)->first();

            if (!$coupon) {
                // Check if it's a Referral Code
                $referralService = app(\App\Services\ReferralService::class);
                $user = Auth::user();
                $referralCode = $referralService->validateReferralForCoupon($code, $user);

                // Global discount of 200
                $total = (float) preg_replace('/[^0-9\.]/ui', '', request()->get('total', 0));
                $curr = $this->curr;
                
                // Get discount from settings
                $discount = ($gs->referral_bonus_referred ?? 200) * $curr->value;

                if ($discount >= $total) {
                    return response()->json(3);
                }

                $total = $total - $discount;
                $data[0] = \PriceHelper::showCurrencyPrice($total);
                $data[1] = $code;
                $data[2] = $discount;
                Session::put('coupon', $data[2]);
                Session::put('coupon_code', $code);
                Session::put('coupon_id', 'referral');
                Session::put('coupon_is_referral', true);
                Session::put('coupon_total', $data[0]);
                
                $data[3] = 'referral';
                $data[4] = \PriceHelper::showCurrencyPrice($data[2]);
                $data[5] = 1;
                $data[6] = round($total, 2);

                return response()->json($data);
            }
        } catch (\Throwable $e) {
            return response()->json(['status' => 0, 'message' => $e->getMessage()]);
        }


        if (! $coupon) {
            return response()->json(0);
        }

        $cart = Session::get('cart');
        $discount_items = [];
        foreach ($cart->items as $key => $item) {
            $product = Product::findOrFail($item['item']['id']);

            if ($coupon->coupon_type == 'category') {
                $coupon_cats = explode(',', $coupon->category);
                if (in_array('all', $coupon_cats) || in_array($product->category_id, $coupon_cats)) {
                    $discount_items[] = $key;
                }
            } elseif ($coupon->coupon_type == 'sub_category') {
                $coupon_subs = explode(',', $coupon->sub_category);
                if (in_array('all', $coupon_subs) || in_array($product->subcategory_id, $coupon_subs)) {
                    $discount_items[] = $key;
                }
            } elseif ($coupon->coupon_type == 'child_category') {
                $coupon_childs = explode(',', $coupon->child_category);
                if (in_array('all', $coupon_childs) || in_array($product->childcategory_id, $coupon_childs)) {
                    $discount_items[] = $key;
                }
            }
        }

        if (count($discount_items) == 0) {
            return 0;
        }

        //dd($discount_items);
        $main_discount_price = 0;
        foreach ($cart->items as $ckey => $cproduct) {
            if (in_array($ckey, $discount_items)) {
                $main_discount_price += $cproduct['price'];
            }
        }

        $total = (float) preg_replace('/[^0-9\.]/ui', '', $main_discount_price);

        $fnd = Coupon::where('code', '=', $code)->get()->count();
        if (Session::has('is_tax')) {
            $xtotal = ($total * Session::get('is_tax')) / 100;
            $total = $total + $xtotal;
        }
        if ($fnd < 1) {
            return response()->json(0);
        } else {
            $coupon = Coupon::where('code', '=', $code)->first();
            $curr = $this->curr;
            if ($coupon->times != null) {
                if ($coupon->times == '0') {
                    return response()->json(0);
                }
            }
            $today = date('Y-m-d');
            $from = date('Y-m-d', strtotime($coupon->start_date));
            $to = date('Y-m-d', strtotime($coupon->end_date));
            if ($from <= $today && $to >= $today) {
                if ($coupon->status == 1) {
                    $oldCart = Session::has('cart') ? Session::get('cart') : null;
                    $val = Session::has('already') ? Session::get('already') : null;
                    if ($val == $code) {
                        return response()->json(2);
                    }
                    $cart = new Cart($oldCart);
                    if ($coupon->type == 0) {

                        if ($coupon->price >= $total) {
                            return response()->json(3);
                        }
                        Session::put('already', $code);
                        $coupon->price = (int) $coupon->price;

                        $oldCart = Session::get('cart');
                        $cart = new Cart($oldCart);

                        $total = $total - $_GET['shipping_cost'];

                        $val = $total / 100;
                        $sub = $val * $coupon->price;
                        $total = $total - $sub;
                        $total = $total + $_GET['shipping_cost'];
                        $data[0] = \PriceHelper::showCurrencyPrice($total);
                        $data[1] = $code;
                        $data[2] = round($sub, 2);

                        Session::put('coupon', $data[2]);
                        Session::put('coupon_code', $code);
                        Session::put('coupon_id', $coupon->id);
                        Session::put('coupon_total', $data[0]);

                        $data[3] = $coupon->id;
                        $data[4] = $coupon->price.'%';
                        $data[5] = 1;
                        $data[6] = round($total, 2);
                        Session::put('coupon_percentage', $data[4]);

                        return response()->json($data);
                    } else {

                        if ($coupon->price >= $total) {
                            return response()->json(3);
                        }
                        Session::put('already', $code);
                        $total = $total - round($coupon->price * $curr->value, 2);
                        $data[0] = $total;
                        $data[1] = $code;
                        $data[2] = $coupon->price * $curr->value;
                        $data[3] = $coupon->id;
                        $data[4] = \PriceHelper::showCurrencyPrice($data[2]);
                        $data[0] = \PriceHelper::showCurrencyPrice($data[0]);
                        Session::put('coupon', $data[2]);
                        Session::put('coupon_code', $code);
                        Session::put('coupon_id', $coupon->id);
                        Session::put('coupon_total', $data[0]);
                        $data[1] = $code;
                        $data[2] = round($coupon->price * $curr->value, 2);
                        $data[3] = $coupon->id;
                        $data[5] = 1;
                        $data[6] = round($total, 2);
                        Session::put('coupon_percentage', $data[4]);

                        return response()->json($data);
                    }
                } else {
                    return response()->json(0);
                }
            } else {
                return response()->json(0);
            }
        }
    }
}
