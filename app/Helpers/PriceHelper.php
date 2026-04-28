<?php

namespace App\Helpers;

use App\Models\Country;
use App\Models\Currency;
use App\Models\Package;
use App\Models\Shipping;
use App\Models\State;
use DB;
use Session;

class PriceHelper
{
    public static function calculateDeliveryFee($cart)
    {
        if (!$cart || !isset($cart->items) || count($cart->items) === 0) {
            return 0;
        }

        // Check if there are any physical products
        $hasPhysical = false;
        $uniqueSellers = [];

        foreach ($cart->items as $item) {
            if (($item['item']['type'] ?? '') === 'Physical') {
                $hasPhysical = true;
                $sellerId = $item['item']['user_id'] ?? 0;
                if (!in_array($sellerId, $uniqueSellers)) {
                    $uniqueSellers[] = $sellerId;
                }
            }
        }

        if (!$hasPhysical) {
            return 0;
        }

        $sellerCount = count($uniqueSellers);
        $baseFee = 999;
        $additionalSellerFee = 300;

        if ($sellerCount <= 1) {
            return $baseFee;
        }

        return $baseFee + (($sellerCount - 1) * $additionalSellerFee);
    }

    public static function showPrice($price)
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        if (is_numeric($price) && floor($price) != $price) {
            return number_format($price, 2, $gs->decimal_separator, $gs->thousand_separator);
        } else {
            return number_format($price, 0, $gs->decimal_separator, $gs->thousand_separator);
        }
    }

    public static function apishowPrice($price)
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        if (is_numeric($price) && floor($price) != $price) {
            return round($price, 2);
        } else {
            return round($price, 0);
        }
    }

    public static function showCurrencyPrice($price)
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $new_price = 0;
        if (is_numeric($price) && floor($price) != $price) {
            $new_price = number_format($price, 2, $gs->decimal_separator, $gs->thousand_separator);
        } else {
            $new_price = number_format($price, 0, $gs->decimal_separator, $gs->thousand_separator);
        }

        $curr = null;
        try {
            if (Session::has('currency')) {
                $curr = cache()->remember('session_currency', now()->addDay(), function () {
                    return Currency::find(Session::get('currency'));
                });
            } else {
                $curr = cache()->remember('default_currency', now()->addDay(), function () {
                    return Currency::where('is_default', '=', 1)->first();
                });
            }
        } catch (\Exception $e) {}

        if (!$curr) {
            $curr = new \stdClass();
            $curr->sign = "CFA";
            $curr->value = 1;
        }

        // Force value to 1 for CFA/XFA to avoid unintended conversions
        if (in_array($curr->sign, ['CFA', 'XFA', 'XAF', 'XOF'])) {
            $curr->value = 1;
        }

        if ($gs->currency_format == 0) {
            return $curr->sign.$new_price;
        } else {
            return $new_price.$curr->sign;
        }
    }

    public static function showAdminCurrencyPrice($price)
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $new_price = 0;
        if (is_numeric($price) && floor($price) != $price) {
            $new_price = number_format($price, 2, $gs->decimal_separator, $gs->thousand_separator);
        } else {
            $new_price = number_format($price, 0, $gs->decimal_separator, $gs->thousand_separator);
        }

        $curr = null;
        try {
            $curr = Currency::where('is_default', '=', 1)->first();
        } catch (\Exception $e) {}

        if (!$curr) {
            $curr = new \stdClass();
            $curr->sign = "CFA";
            $curr->value = 1;
        }

        // Force value to 1 for CFA/XFA to avoid unintended conversions
        if (in_array($curr->sign, ['CFA', 'XFA', 'XAF', 'XOF'])) {
            $curr->value = 1;
        }

        if ($gs->currency_format == 0) {
            return $curr->sign.$new_price;
        } else {
            return $new_price.$curr->sign;
        }
    }

    public static function showOrderCurrencyPrice($price, $currency)
    {
        $gs = \App\Models\Generalsetting::safeFirst();
        $new_price = 0;
        if (is_numeric($price) && floor($price) != $price) {
            $new_price = number_format($price, 2, $gs->decimal_separator, $gs->thousand_separator);
        } else {
            $new_price = number_format($price, 0, $gs->decimal_separator, $gs->thousand_separator);
        }

        if ($gs->currency_format == 0) {
            return $currency.$new_price;
        } else {
            return $new_price.$currency;
        }
    }

    public static function ImageCreateName($image)
    {
        $filename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        $name = time() . preg_replace('/[^A-Za-z0-9\-]/', '', $filename) . '.' . $image->getClientOriginalExtension();

        return $name;
    }

    /**
     * Save base64 image data as a file.
     * 
     * @param string $base64Data The base64 string (e.g., "data:image/png;base64,iVBOR...")
     * @param string $path The directory path where the image should be saved
     * @return string|null The generated filename or null on failure
     */
    public static function saveBase64Image($base64Data, $path)
    {
        try {
            // Check if it is a base64 string
            if (preg_match('/^data:image\/(\w+);base64,/', $base64Data, $type)) {
                $base64Data = substr($base64Data, strpos($base64Data, ',') + 1);
                $type = strtolower($type[1]); // png, jpg, etc.

                if (!in_array($type, ['jpg', 'jpeg', 'png', 'jfif', 'webp'])) {
                    return null;
                }

                $base64Data = base64_decode($base64Data);

                if ($base64Data === false) {
                    return null;
                }
            } else {
                return null;
            }

            $filename = time() . uniqid() . '.' . $type;
            
            // Fix double slashes or missing slashes in path
            $path = rtrim($path, '/');
            if (!file_exists($path)) {
                mkdir($path, 0755, true);
            }

            file_put_contents($path . '/' . $filename, $base64Data);

            return $filename;
        } catch (\Exception $e) {
            \Log::error("Base64 Image Save Error: " . $e->getMessage());
            return null;
        }
    }

    public static function getOrderTotal($input, $cart)
    {

        try {
            $vendor_ids = [];
            foreach ($cart->items as $item) {
                if (! in_array($item['item']['user_id'], $vendor_ids)) {
                    $vendor_ids[] = $item['item']['user_id'];
                }
            }

            $gs = \App\Models\Generalsetting::safeFirst();

            $totalAmount = $cart->totalPrice - (\Session::has('coupon') ? (float)\Session::get('coupon') : 0);
            
            // Recalculate delivery fee on backend to prevent tampering
            $delivery_fee = self::calculateDeliveryFee($cart);
            $totalAmount += $delivery_fee;

            $tax_amount = 0;
            if (isset($input['tax']) && @$input['tax_type']) {
                if (@$input['tax_type'] == 'state_tax') {
                    $tax = State::findOrFail($input['tax'])->tax;
                } else {
                    $tax = Country::findOrFail($input['tax'])->tax;
                }
                $tax_amount = ($totalAmount / 100) * $tax;
                $totalAmount = $totalAmount + $tax_amount;
            }

            if ($gs->multiple_shipping == 0) {
                $vendor_shipping_ids = [];
                $vendor_packing_ids = [];
                foreach ($vendor_ids as $vendor_id) {
                    $vendor_shipping_ids[$vendor_id] = $input['shipping_id'] != 0 ? $input['shipping_id'] : null;
                    $vendor_packing_ids[$vendor_id] = $input['packaging_id'] != 0 ? $input['packaging_id'] : null;
                }

                $shipping = ($input['shipping_id'] != 0) ? Shipping::find($input['shipping_id']) : null;
                $packeing = ($input['packaging_id'] != 0) ? Package::find($input['packaging_id']) : null;

                $totalAmount = $totalAmount + ($shipping ? $shipping->price : 0) + ($packeing ? $packeing->price : 0);

                return [
                    'total_amount' => $totalAmount,
                    'shipping' => $shipping,
                    'packeing' => $packeing,
                    'is_shipping' => 0,
                    'vendor_shipping_ids' => @json_encode($vendor_shipping_ids),
                    'vendor_packing_ids' => @json_encode($vendor_packing_ids),
                    'vendor_ids' => @json_encode($vendor_ids),
                    'success' => true,
                ];
            } else {

                if (gettype($input['shipping']) == 'string') {
                    $shippingData = json_decode($input['shipping'], true);
                } else {
                    $shippingData = $input['shipping'];
                }

                $shipping_cost = 0;
                $packaging_cost = 0;
                $vendor_ids = [];
                if ($input['shipping'] && $input['shipping'] != 0) {
                    foreach ($shippingData as $key => $shipping_id) {
                        if ($shipping_id != 0) {
                            $shipping = Shipping::find($shipping_id);
                            if ($shipping) {
                                $shipping_cost += $shipping->price;
                                if (! in_array($shipping->user_id, $vendor_ids)) {
                                    $vendor_ids[] = $shipping->user_id;
                                }
                            }
                        }
                    }
                }

                if (gettype($input['packeging']) == 'string') {
                    $packegingData = json_decode($input['packeging'], true);
                } else {
                    $packegingData = $input['packeging'];
                }

                if ($input['packeging'] && $input['packeging'] != 0) {
                    foreach ($packegingData as $key => $packaging_id) {
                        if ($packaging_id != 0) {
                            $packeing = Package::find($packaging_id);
                            if ($packeing) {
                                $packaging_cost += $packeing->price;
                                if (! in_array($packeing->user_id, $vendor_ids)) {
                                    $vendor_ids[] = $packeing->user_id;
                                }
                            }
                        }
                    }
                }

                $totalAmount = $totalAmount + $shipping_cost + $packaging_cost;

                return [
                    'total_amount' => $totalAmount,
                    'shipping' => isset($shipping) ? $shipping : null,
                    'packeing' => isset($packeing) ? $packeing : null,
                    'is_shipping' => 1,
                    'tax' => $tax_amount,
                    'vendor_shipping_ids' => @json_encode($input['shipping']),
                    'vendor_packing_ids' => @json_encode($input['packeging']),
                    'vendor_ids' => @json_encode($vendor_ids),
                    'shipping_cost' => $shipping_cost,
                    'packing_cost' => $packaging_cost,
                    'success' => true,
                ];
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public static function getOrderTotalAmount($input, $cart)
    {

        if (Session::has('currency')) {
            $curr = cache()->remember('session_currency', now()->addDay(), function () {
                return Currency::find(Session::get('currency'));
            });
        } else {
            $curr = cache()->remember('default_currency', now()->addDay(), function () {
                return Currency::where('is_default', '=', 1)->first();
            });
        }

        try {
            $vendor_ids = [];
            foreach ($cart->items as $item) {
                if (! in_array($item['item']['user_id'], $vendor_ids)) {
                    $vendor_ids[] = $item['item']['user_id'];
                }
            }

            $gs = \App\Models\Generalsetting::safeFirst();
            $totalAmount = $cart->totalPrice - (\Session::has('coupon') ? (float)\Session::get('coupon') : 0);
            $delivery_fee = isset($input['total_delivery_fee']) ? (float) $input['total_delivery_fee'] : 0;
            $totalAmount += $delivery_fee;

            if (isset($input['tax']) && @$input['tax_type']) {
                if (@$input['tax_type'] == 'state_tax') {
                    $tax = State::findOrFail($input['tax'])->tax;
                } else {
                    $tax = Country::findOrFail($input['tax'])->tax;
                }
                $tax_amount = ($totalAmount / 100) * $tax;
                $totalAmount = $totalAmount + $tax_amount;
            }

            if ($gs->multiple_shipping == 0) {
                $vendor_shipping_ids = [];
                $vendor_packing_ids = [];
                foreach ($vendor_ids as $vendor_id) {
                    $vendor_shipping_ids[$vendor_id] = $input['shipping_id'];
                    $vendor_packing_ids[$vendor_id] = $input['packaging_id'];
                }

                $shipping = ($input['shipping_id'] != 0) ? Shipping::find($input['shipping_id']) : null;
                $packeing = ($input['packaging_id'] != 0) ? Package::find($input['packaging_id']) : null;
                $totalAmount = $totalAmount + ($shipping ? $shipping->price : 0) + ($packeing ? $packeing->price : 0);

                return round($totalAmount / $curr->value, 2);
            } else {

                $shipping_cost = 0;
                $packaging_cost = 0;
                $vendor_ids = [];
                if ($input['shipping']) {
                    foreach ($input['shipping'] as $key => $shipping_id) {
                        if ($shipping_id != 0) {
                            $shipping = Shipping::find($shipping_id);
                            if ($shipping) {
                                $shipping_cost += $shipping->price;
                                if (! in_array($shipping->user_id, $vendor_ids)) {
                                    $vendor_ids[] = $shipping->user_id;
                                }
                            }
                        }
                    }
                }
                if ($input['packeging']) {
                    foreach ($input['packeging'] as $key => $packaging_id) {
                        if ($packaging_id != 0) {
                            $packeing = Package::find($packaging_id);
                            if ($packeing) {
                                $packaging_cost += $packeing->price;
                                if (! in_array($packeing->user_id, $vendor_ids)) {
                                    $vendor_ids[] = $packeing->user_id;
                                }
                            }
                        }
                    }
                }

                $totalAmount = $totalAmount + $shipping_cost + $packaging_cost;

                return round($totalAmount * $curr->value, 2);
            }
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
