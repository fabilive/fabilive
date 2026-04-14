@extends('layouts.front')
@php use App\Helpers\PriceHelper; @endphp

@section('content')
@include('partials.global.common-header')
@php
    // --- Bulletproof Cart Data Retrieval ---
    $order_items = [];
    $raw_cart = $order->cart;

    // 1. Recursive Decoding (Handle double-encoding)
    $max_depth = 5;
    while (is_string($raw_cart) && $max_depth > 0) {
        $decoded = json_decode($raw_cart, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            $raw_cart = $decoded;
        } else {
            break;
        }
        $max_depth--;
    }

    // 2. Deep Item Extraction
    if (isset($raw_cart['items']) && !empty($raw_cart['items'])) {
        $order_items = $raw_cart['items'];
    } elseif (is_array($raw_cart) && !empty($raw_cart)) {
        // Check if the array itself contains product-like items
        $first = reset($raw_cart);
        if (is_array($first) && (isset($first['qty']) || isset($first['item']))) {
            $order_items = $raw_cart;
        }
    }

    // 3. Last Resort Fallback to Session
    if (empty($order_items) && !empty($tempcart)) {
        if (is_object($tempcart)) {
            $order_items = $tempcart->items ?? [];
        } elseif (is_array($tempcart)) {
            $order_items = $tempcart['items'] ?? [];
        }
    }

    // 4. Ensure iterable
    if (!is_array($order_items)) {
        $order_items = [];
    }
@endphp

<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
    style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('Success') }}</h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>

                        <li class="breadcrumb-item active" aria-current="page">{{ __('Success') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->
<section class="tempcart">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Starting of Dashboard data-table area -->
                <div class="content-box section-padding add-product-1">
                    <div class="top-area">
                        <div class="content order-de">
                            {{-- <h4 class="heading">
                                {{ __('THANK YOU FOR YOUR PURCHASE.') }}
                            </h4> --}}
                            <p class="text">
                                {{ __("We'll email you an order confirmation with details and tracking info.") }}
                            </p>
                            <a href="{{ route('front.index') }}" style="color:green;font-weight: bold" class="link">{{
                                __('Get Back To Our Homepage') }}</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="product__header">
                                <div class="row reorder-xs">
                                    <div class="col-lg-12">
                                        <div class="product-header-title">
                                            <h4>{{ __('Order#') }} {{$order->order_number}}</h4>
                                        </div>
                                    </div>
                                    @include('alerts.form-success')
                                    <div class="col-md-12" id="tempview">
                                        <div class="dashboard-content">
                                            <div class="view-order-page" id="print">
                                                <p class="order-date">{{ __('Order Date') }}
                                                    {{date('d-M-Y',strtotime($order->created_at))}}</p>


                                                @if($order->dp == 1)

                                                <div class="billing-add-area">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>{{ __('Shipping Address') }}</h5>
                                                            <address>
                                                                {{ __('Name:') }} {{$order->customer_name}}<br>
                                                                {{ __('Email:') }} {{$order->customer_email}}<br>
                                                                {{ __('Phone:') }} {{$order->customer_phone}}<br>
                                                                @if($order->customer_whatsapp)
                                                                {{ __('WhatsApp:') }} {{$order->customer_whatsapp}}<br>
                                                                @endif
                                                                {{ __('Address:') }} {{$order->customer_address}}<br>
                                                                @if($order->service_area_id)
                                                                {{ optional($order->servicearea)->location }}
                                                                @else
                                                                {{$order->customer_city}}@if($order->customer_zip)-{{$order->customer_zip}}@endif
                                                                @endif
                                                            </address>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>{{ __('Shipping Method') }}</h5>

                                                            <p>{{ __('Payment Status') }}
                                                                @if($order->payment_status == 'Pending')
                                                                <span class='badge badge-danger'>{{ __('Unpaid')
                                                                    }}</span>
                                                                @else
                                                                <span class='badge badge-success'>{{ __('Paid')
                                                                    }}</span>
                                                                @endif
                                                            </p>

                                                            <p>{{ __('Tax :') }}
                                                                {{ PriceHelper::showOrderCurrencyPrice((($order->tax) /
                                                                $order->currency_value),$order->currency_sign) }}
                                                            </p>

                                                            <p>{{ __('Paid Amount:') }}
                                                                {{
                                                                PriceHelper::showOrderCurrencyPrice((($order->pay_amount
                                                                + $order->wallet_price) *
                                                                $order->currency_value),$order->currency_sign) }}
                                                            </p>
                                                            <p>{{ __('Payment Method:') }} {{$order->method}}</p>

                                                            @if($order->method != "Cash On Delivery")
                                                            @if($order->method=="Stripe")
                                                            {{ $order->method }} {{ __('Charge ID:') }} <p>
                                                                {{$order->charge_id}}</p>
                                                            @endif
                                                            {{ $order->method }} {{ __('Transaction ID:') }} <p
                                                                id="ttn">{{ $order->txnid }}</p>

                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>

                                                @else
                                                <div class="shipping-add-area">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            @if($order->shipping == "shipto")
                                                            <h5>{{ __('Shipping Address') }}</h5>
                                                            <address>
                                                                {{ __('Name:') }}
                                                                {{$order->shipping_name == null ? $order->customer_name
                                                                : $order->shipping_name}}<br>
                                                                {{ __('Email:') }}
                                                                {{$order->shipping_email == null ?
                                                                $order->customer_email : $order->shipping_email}}<br>
                                                                {{ __('Phone:') }}
                                                                {{$order->shipping_phone == null ?
                                                                $order->customer_phone : $order->shipping_phone}}<br>
                                                                @if($order->customer_whatsapp)
                                                                {{ __('WhatsApp:') }} {{$order->customer_whatsapp}}<br>
                                                                @endif
                                                                {{ __('Address:') }}
                                                                {{$order->shipping_address == null ?
                                                                $order->customer_address :
                                                                $order->shipping_address}}<br>
                                                                @if($order->service_area_id)
                                                                {{ optional($order->servicearea)->location }}
                                                                @else
                                                                {{$order->shipping_city == null ? $order->customer_city : $order->shipping_city}}@if($order->shipping_zip || $order->customer_zip)-{{$order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip}}@endif
                                                                @endif
                                                            </address>
                                                            @if($order->service_area_id)
                                                            <div class="mt-3">
                                                                <h5 class="text-primary"><i class="fas fa-map-marker-alt mr-2"></i>{{ __('Service Area / Delivery Location') }}</h5>
                                                                <p class="font-weight-bold" style="font-size: 1.1rem; color: #333;">
                                                                    {{ optional($order->servicearea)->location }}
                                                                </p>
                                                            </div>
                                                            @endif
                                                            @else
                                                            <h5>{{ __('PickUp Location') }}</h5>
                                                            <address>
                                                                {{ __('PickUp Location') }}: {{ optional($order->servicearea)->location }}
                                                            </address>

                                                            @endif

                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>{{ __('Shipping Method') }}</h5>
                                                            @if($order->shipping == "shipto")
                                                            <p>{{ __('Ship To Address') }}</p>
                                                            @else
                                                            <p>{{ __('Pick Up') }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="billing-add-area">
                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <h5>{{ __('Billing Address') }}</h5>
                                                            <address>
                                                                {{ __('Name:') }} {{$order->customer_name}}<br>
                                                                {{ __('Email:') }} {{$order->customer_email}}<br>
                                                                {{ __('Phone:') }} {{$order->customer_phone}}<br>
                                                                @if($order->customer_whatsapp)
                                                                {{ __('WhatsApp:') }} {{$order->customer_whatsapp}}<br>
                                                                @endif
                                                                {{ __('Address:') }} {{$order->customer_address}}<br>
                                                                @if($order->service_area_id)
                                                                {{ optional($order->servicearea)->location }}
                                                                @else
                                                                {{$order->customer_city}}@if($order->customer_zip)-{{$order->customer_zip}}@endif
                                                                @endif
                                                            </address>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <h5>{{ __('Payment Information') }}</h5>

                                                            @if ($gs->multiple_shipping == 0)
                                                            @if($order->shipping_cost != 0)
                                                            <p>{{ $order->shipping_title }}:
                                                                {{
                                                                PriceHelper::showOrderCurrencyPrice($order->shipping_cost,$order->currency_sign)
                                                                }}
                                                            </p>
                                                            @endif


                                                            @if($order->packing_cost != 0)
                                                            <p>{{ $order->packing_title }}:
                                                                {{
                                                                PriceHelper::showOrderCurrencyPrice($order->packing_cost,$order->currency_sign)
                                                                }}
                                                            </p>
                                                            @endif

                                                            @else

                                                            @if($order->shipping_cost != 0)
                                                            <p>{{__('Shipping Cost')}}:
                                                                {{
                                                                PriceHelper::showOrderCurrencyPrice($order->shipping_cost* $order->currency_value,$order->currency_sign)
                                                                }}
                                                            </p>
                                                            @endif


                                                            @if($order->packing_cost != 0)
                                                            <p>{{ __('Packing Cost')}}:
                                                                {{
                                                                PriceHelper::showOrderCurrencyPrice($order->packing_cost* $order->currency_value,$order->currency_sign)
                                                                }}
                                                            </p>
                                                            @endif

                                                            @endif

                                                            @if($order->wallet_price != 0)
                                                            <p>{{ __('Paid From Wallet') }}:
                                                                {{
                                                                PriceHelper::showOrderCurrencyPrice(($order->wallet_price
                                                                * $order->currency_value),$order->currency_sign) }}
                                                            </p>

                                                            @if($order->method != "Wallet")

                                                            <p>{{$order->method}}:
                                                                {{
                                                                PriceHelper::showOrderCurrencyPrice(($order->pay_amount
                                                                * $order->currency_value),$order->currency_sign) }}
                                                            </p>

                                                            @endif

                                                            @endif

                                                            <p>{{ __('Tax :') }}
                                                                {{ PriceHelper::showOrderCurrencyPrice((($order->tax) /
                                                                $order->currency_value),$order->currency_sign) }}
                                                            </p>

                                                            <p>{{ __('Paid Amount:') }}
                                                                @if($order->method != "Wallet")

                                                                {{
                                                                PriceHelper::showOrderCurrencyPrice((($order->pay_amount+$order->wallet_price)
                                                                * $order->currency_value),$order->currency_sign) }}

                                                                @else
                                                                {{
                                                                PriceHelper::showOrderCurrencyPrice(($order->wallet_price
                                                                * $order->currency_value),$order->currency_sign) }}
                                                                @endif



                                                            </p>
                                                            <p>{{ __('Payment Method:') }} {{$order->method}}</p>

                                                            @if($order->method != "Cash On Delivery" && $order->method
                                                            != "Wallet")
                                                            @if($order->method=="Stripe")
                                                            {{$order->method}} {{ __('Charge ID:') }} <p>
                                                                {{$order->charge_id}}</p>
                                                            @else
                                                            {{$order->method}} {{ __('Transaction ID:') }} <p id="ttn">
                                                                {{$order->txnid}}</p>
                                                            @endif

                                                            @endif

                                                        </div>
                                                    </div>
                                                </div>
                                                @endif
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <h4 class="text-center">{{ __('Ordered Products:') }}</h4>
                                                        <thead>
                                                            <tr>
                                                                <th width="35%">{{ __('Name') }}</th>
                                                                <th width="20%">{{ __('Details') }}</th>
                                                                <th>{{ __('Price') }}</th>
                                                                <th>{{ __('Total') }}</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            @foreach($order_items as $product)
                                                            <tr>

                                                                <td>{{ $product['item']['name'] ?? 'Product' }}</td>
                                                                <td>
                                                                    <b>{{ __('Quantity') }}</b>: {{ $product['qty'] ?? 1 }}
                                                                    <br>
                                                                    @if(!empty($product['size']))
                                                                    <b>{{ __('Size') }}</b>:
                                                                    {{ $product['item']['measure'] ?? '' }}{{str_replace('-','
                                                                    ',$product['size'] ?? '')}}
                                                                    <br>
                                                                    @endif
                                                                    @if(!empty($product['color']))
                                                                    <div class="d-flex mt-2">
                                                                        <b>{{ __('Color') }}</b>: <span id="color-bar"
                                                                            style="border: 10px solid #{{ ($product['color'] ?? '') == "" ? "
                                                                            white" : ($product['color'] ?? '') }};"></span>
                                                                    </div>
                                                                    @endif

                                                                    @if(!empty($product['keys']))

                                                                    @foreach( array_combine(explode(',',
                                                                    $product['keys'] ?? ''), explode(',', $product['values'] ?? ''))
                                                                    as $key => $value)

                                                                    <b>{{ ucwords(str_replace('_', ' ', $key ?? '')) }} :
                                                                    </b> {{ $value ?? '' }} <br>
                                                                    @endforeach

                                                                    @endif

                                                                </td>

                                                                <td>{{
                                                                    PriceHelper::showCurrencyPrice(($product['item_price'] ?? 0
                                                                    ) * ($order->currency_value ?? 1)) }}
                                                                </td>

                                                                <td>{{ PriceHelper::showCurrencyPrice(($product['price'] ?? 0)
                                                                    * ($order->currency_value ?? 1)) }} <small>{{
                                                                        ($product['discount'] ?? 0) == 0 ? '' :
                                                                        '('.$product['discount'].' % '.__('Off').')'
                                                                        }}</small>
                                                                </td>

                                                            </tr>
                                                            @endforeach

                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- Ending of Dashboard data-table area -->
            </div>



</section>





@include('partials.global.common-footer')
@endsection

@section('script')


@endsection