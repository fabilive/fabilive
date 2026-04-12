@extends('layouts.front')
@section('content')
@include('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Checkout') }}</h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Checkout') }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<section class="checkout">
   <div class="container">
      <div class="row">
         <div class="col-lg-12">
            <div class="checkout-area mb-0 pb-0">
               <div class="checkout-process">
                  <ul class="nav" role="tablist">
                     <li class="nav-item">
                        <a class="nav-link active" id="pills-step1-tab" data-toggle="pill" href="#pills-step1"
                           role="tab" aria-controls="pills-step1" aria-selected="true">
                           <span>1</span> {{ __('Address') }}
                           <i class="far fa-address-card"></i>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link disabled" id="pills-step2-tab" data-toggle="pill" href="#pills-step2"
                           role="tab" aria-controls="pills-step2" aria-selected="false">
                           <span>2</span> {{ __('Orders') }}
                           <i class="fas fa-dolly"></i>
                        </a>
                     </li>
                     <li class="nav-item">
                        <a class="nav-link disabled" id="pills-step3-tab" data-toggle="pill" href="#pills-step3"
                           role="tab" aria-controls="pills-step3" aria-selected="false">
                           <span>3</span> {{ __('Payment') }}
                           <i class="far fa-credit-card"></i>
                        </a>
                     </li>
                  </ul>
               </div>
            </div>
         </div>
         <div class="col-lg-8">
            <form id="" action="{{ Auth::check() ? route('front.wallet.submit') : ($gateways->first() ? $gateways->first()->showCheckoutLink() : route('front.payment.campay')) }}" method="POST" class="checkoutform">
               @include('includes.form-success')
               @include('includes.form-error')
               {{ csrf_field() }}
               <div class="checkout-area">
                  <div class="tab-content" id="pills-tabContent">
                     <div class="tab-pane fade show active" id="pills-step1" role="tabpanel"
                        aria-labelledby="pills-step1-tab">
                        <div class="content-box displayBoxNone">
                           <div class="content">
                              <div class="submit-loader" style="display: none;">
                                 <img src="//geniusocean.com/demo/geniuscart/default/assets/images/loading_large.gif"
                                    alt="">
                              </div>
                              <div class="personal-info">
                                 <h5 class="title">
                                    {{ __('Personal Information') }} :
                                 </h5>
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <input type="text" id="personal-name" class="form-control" name="personal_name"
                                          placeholder="{{ __('Enter Your Name')}}"
                                          value="{{ Auth::check() ? Auth::user()->name : '' }}" {{ Auth::check()
                                          ? 'readonly' : '' }}>
                                    </div>
                                    <div class="col-lg-6">
                                       <input type="email" id="personal-email" class="form-control"
                                          name="personal_email" placeholder="{{ __('Enter Your Email') }}"
                                          value="{{ Auth::check() ? Auth::user()->email : '' }}" {{ Auth::check()
                                          ? 'readonly' : '' }}>
                                    </div>
                                 </div>
                                 @if(!Auth::check())
                                 <div class="row">
                                    <div class="col-lg-12 mt-3">
                                       <input class="styled-checkbox" id="open-pass" type="checkbox" value="1"
                                          name="pass_check">
                                       <label for="open-pass">{{ __('Create an account ?') }}</label>
                                    </div>
                                 </div>
                                 <div class="row set-account-pass d-none">
                                    <div class="col-lg-6 my-2">
                                       <input type="password" name="personal_pass" id="personal-pass"
                                          class="form-control" placeholder="{{ __('Enter Your Password') }}">
                                    </div>
                                    <div class="col-lg-6 my-2">
                                       <input type="password" name="personal_confirm" id="personal-pass-confirm"
                                          class="form-control" placeholder="{{ __('Confirm Your Password') }}">
                                    </div>
                                 </div>
                                 @endif
                              </div>
                              <div class="billing-address">
                                 <h5 class="title">
                                    {{ __('Billing Details') }}
                                 </h5>
                                 <div class="row">
                                    <div class="col-lg-6 my-2 {{ $digital == 1 ? 'd-none' : '' }}">
                                       <select class="form-control" id="shipop" name="shipping" required="">
                                          <option value="shipto">{{ __('Ship To Address') }}</option>
                                          {{-- <option value="pickup">{{ __('Pick Up') }}</option> --}}
                                       </select>
                                    </div>
                                    <div class="col-lg-6 mb-2 d-none" id="shipshow">
                                       <select class="form-control" name="pickup_location">
                                          @foreach($pickups as $pickup)
                                          <option value="{{$pickup->location}}">{{$pickup->location}}
                                          </option>
                                          @endforeach
                                       </select>
                                    </div>
                                    <div class="col-lg-6 my-2">
                                       <input class="form-control" type="text" name="customer_name"
                                          placeholder="{{ __('Full Name') }}" required=""
                                          value="{{ Auth::check() ? Auth::user()->name : '' }}">
                                    </div>
                                    <div class="col-lg-6 my-2">
                                       <input class="form-control" type="text" name="customer_email"
                                          placeholder="{{ __('Email') }}" required=""
                                          value="{{ Auth::check() ? Auth::user()->email : '' }}">
                                    </div>
                                    <div class="col-lg-6 my-2">
                                       <input class="form-control" type="text" name="customer_phone"
                                          placeholder="{{ __('Phone Number') }}" required=""
                                          value="{{ Auth::check() ? Auth::user()->phone : '' }}">
                                    </div>
                                    <div class="col-lg-6 my-2">
                                       <input class="form-control" type="text" id="customer_whatsapp"
                                          placeholder="{{ __('WhatsApp Number (Optional)') }}"
                                          value="">
                                    </div>
                                    <div class="col-lg-6 my-2">
                                       <input class="form-control" type="text" name="customer_address"
                                          placeholder="{{ __('Address') }}" required=""
                                          value="{{ Auth::check() ? Auth::user()->address : '' }}">
                                    </div>
                                    <div class="col-lg-6 my-2">
                                       <select class="form-control" id="select_country" name="customer_country"
                                          required="">
                                          @include('includes.countries')
                                       </select>
                                    </div>
                                    <div class="col-lg-6 my-2 select_state">
                                       <select class="form-control " id="show_state" name="customer_state">
                                       </select>
                                    </div>
                                    <div class="col-lg-6 my-2 select_city">
                                       <select class="form-control " id="show_city" name="customer_city">
                                       </select>
                                    </div>
                                    <div class="col-lg-6 my-2">
                                       <select class="form-control" name="service_area_id" id="service_area_id">
                                            <option  value="">Select Location</option>
                                            @foreach($service_areas as $service_area)
                                                <option value="{{ $service_area->id }}">{{ $service_area->location }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="row {{ $digital == 1 ? 'd-none' : '' }}">
                                 <div class="col-lg-12 mt-3 d-flex">
                                    <input class="styled-checkbox" id="ship-diff-address" type="checkbox"
                                       value="value1">
                                    <label for="ship-diff-address">{{ __('Ship to a Different Address?') }}</label>
                                 </div>
                              </div>
                              <div class="ship-diff-addres-area d-none">
                                 <h5 class="title">
                                    {{ __('Shipping Details') }}
                                 </h5>
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <input class="form-control ship_input" type="text" name="shipping_name"
                                          id="shippingFull_name" placeholder="{{ __('Full Name') }}">
                                       <input type="hidden" name="shipping_email" value="">
                                    </div>
                                    <div class="col-lg-6">
                                       <input class="form-control ship_input" type="text" name="shipping_phone"
                                          id="shipingPhone_number" placeholder="{{ __('Phone Number') }}">
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <input class="form-control ship_input" type="text" name="shipping_address"
                                          id="shipping_address" placeholder="{{ __('Address') }}">
                                    </div>
                                    <div class="col-lg-6">
                                       <input class="form-control ship_input" type="text" name="shipping_zip"
                                          id="shippingPostal_code" placeholder="{{ __('Postal Code') }}">
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <input class="form-control ship_input" type="text" name="shipping_city"
                                          id="shipping_city" placeholder="{{ __('City') }}">
                                    </div>
                                    <div class="col-lg-6">
                                       <input class="form-control ship_input" type="text" name="shipping_state"
                                          id="shipping_state" placeholder="{{ __('State') }}">
                                    </div>
                                 </div>
                                 <div class="row">
                                    <div class="col-lg-6">
                                       <select class="form-control ship_input" name="shipping_country">
                                          @include('partials.user.countries')
                                       </select>
                                    </div>
                                 </div>
                              </div>
                              <div class="order-note mt-3">
                                 <div class="row">
                                    <div class="col-lg-12">
                                       <input type="text" id="Order_Note" class="form-control" name="order_notes"
                                          placeholder="{{ __('Order Note') }} ({{ __('Optional') }})">
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-12  mt-3">
                                    <div class="bottom-area paystack-area-btn">
                                       <button type="button" id="continue-step1" class="mybtn1 ">{{ __('Continue') }}</button>
                                       <button type="submit" id="submit-form-hidden" class="d-none"></button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade show" id="pills-step2" role="tabpanel" aria-labelledby="pills-step2-tab">
                        <div class="content-box">
                           <div class="content">
                              <div class="order-area">

                                 @php
                                 foreach ($products as $key => $item) {
                                 $userId = $item["user_id"];
                                 if (!isset($resultArray[$userId])) {
                                 $resultArray[$userId] = [];
                                 }
                                 $resultArray[$userId][$key] = $item;
                                 }
                                 @endphp


                                 @php
                                 $is_Digital = 1;
                                 @endphp

                                 @foreach($resultArray as $vendor_id => $array_product)
                                 @php

                                 if($vendor_id != 0){
                                 $shipping = App\Models\Shipping::where('user_id',0)->get();
                                 $packaging = App\Models\Package::where('user_id',0)->get();
                                 $vendor = App\Models\Admin::findOrFail(1);
                                 }else{
                                 $shipping = App\Models\Shipping::where('user_id',0)->get();
                                 $packaging = App\Models\Package::where('user_id',0)->get();
                                 $vendor = App\Models\Admin::findOrFail(1);
                                 }

                                 @endphp
                                 <div class="py-4" style="border-bottom:1px solid #ddd">

                                    @foreach ($array_product as $product)
                                    @php
                                    if($product['dp'] == 0){
                                    $is_Digital = 0;
                                    }
                                    @endphp
                                    <div class="order-item border-bottom-0">
                                       <div class="product-img">
                                          <div class="d-flex">
                                             <img
                                                src=" {{ $product['item']['photo'] }}"
                                                height="80" width="80" class="p-1">
                                          </div>
                                       </div>
                                       <div class="product-content">
                                          <p class="name"><a
                                                href="{{ route('front.product', $product['item']['slug']) }}"
                                                target="_blank">{{ $product['item']['name'] }}</a></p>
                                          <div class="unit-price d-flex">
                                             <h5 class="label mr-2">{{ __('Price') }} : </h5>
                                             <p>{{ App\Models\Product::convertPrice($product['item_price']) }}
                                             </p>
                                          </div>
                                          @if(!empty($product['size']))
                                          <div class="unit-price d-flex">
                                             <h5 class="label mr-2">{{ __('Size') }} : </h5>
                                             <p>{{str_replace('-',' ',$product['size'])}}</p>
                                          </div>
                                          @endif
                                          @if(!empty($product['color']))
                                          <div class="unit-price d-flex">
                                             <h5 class="label mr-2">{{ __('Color') }} : </h5>
                                             <span id="color-bar"
                                                style="border: 10px solid {{$product['color'] == "" ? " white" : '#'
                                                .$product['color']}};"></span>
                                          </div>
                                          @endif
                                          @if(!empty($product['keys']))
                                          @foreach( array_combine(explode(',', $product['keys']), explode(',',
                                          $product['values'])) as $key => $value)
                                          <div class="quantity d-flex">
                                             <h5 class="label mr-2">{{ ucwords(str_replace('_', ' ', $key)) }} :
                                             </h5>
                                             <span class="qttotal">{{ $value }} </span>
                                          </div>
                                          @endforeach
                                          @endif
                                          <div class="quantity d-flex">
                                             <h5 class="label mr-2">{{ __('Quantity') }} : </h5>
                                             <span class="qttotal">{{ $product['qty'] }} </span>
                                          </div>
                                          <div class="total-price d-flex">
                                             <h5 class="label mr-2">{{ __('Total Price') }} : </h5>
                                             <p>
                                                {{ App\Models\Product::convertPrice($product['price']) }}
                                                <small>{{ $product['discount'] == 0 ? '' : '('.$product['discount'].'%
                                                   '.__('Off').')' }}</small>
                                             </p>
                                          </div>
                                       </div>
                                    </div>
                                    @endforeach
                                    @if ($gs->multiple_shipping == 1)
                                    <div class="d-flex p-4  border justify-content-between">

                                       <div class="">

                                       </div>

                                       @if ($is_Digital == 0)
                                       <div>
                                          <h5 class="label mr-2">{{ __('Packageing :') }} </h5>
                                          <button type="button" class="mybtn1" data-bs-toggle="modal"
                                             data-bs-target="#vendor_package{{$vendor_id}}">
                                             {{ __('Select Package') }}
                                          </button>
                                          <p id="packing_text{{$vendor_id}}">
                                             {{isset($packaging[0]) ? $packaging[0]['title'] .'+'. $curr->sign
                                             .round($packaging[0]['price'] * $curr->value,2) : 'Package not found'}}
                                          </p>
                                       </div>
                                       <div>
                                          <h5 class="label mr-2">{{ __('Shipping Method :') }} </h5>
                                          <button type="button" class="mybtn1" data-bs-toggle="modal"
                                             data-bs-target="#vendor_shipping{{$vendor_id}}">
                                             {{ __('Select Shipping') }}
                                          </button>
                                          <p id="shipping_text{{$vendor_id}}">
                                             {{isset($shipping[0]) ? $shipping[0]['title'] .'+'. $curr->sign
                                             .round($shipping[0]['price'] * $curr->value,2) : 'Shipping not found'}}
                                          </p>
                                       </div>
                                       @include('includes.vendor_shipping', ['shipping' => $shipping, 'vendor_id' =>
                                       $vendor_id])
                                       @include('includes.vendor_packaging', ['packaging' => $packaging, 'vendor_id' =>
                                       $vendor_id])
                                       @endif

                                    </div>
                                    @else
                                     {{-- Seller Details Hidden --}}
                                    @endif

                                 </div>
                                 @php
                                 $is_Digital = 1;
                                 @endphp
                                 @endforeach
                              </div>
                              <div class="row">
                                 <div class="col-lg-12 mt-3">
                                    <div class="bottom-area">
                                       <a href="javascript:;" id="step1-btn" class="mybtn1 mr-3">{{ __('Back') }}</a>
                                       <a href="javascript:;" id="step3-btn" class="mybtn1">{{ __('Continue') }}</a>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="tab-pane fade show" id="pills-step3" role="tabpanel" aria-labelledby="pills-step3-tab">
                        <div class="content-box">
                           <div class="content">
                              <div class="billing-info-area {{ $digital == 1 ? 'd-none' : '' }}">
                                 <h4 class="title">
                                    {{ __('Shipping Info') }}
                                 </h4>
                                 <ul class="info-list">
                                    <li>
                                       <p id="shipping_user"></p>
                                    </li>
                                    <li>
                                       <p id="shipping_location"></p>
                                    </li>
                                    <li>
                                       <p id="shipping_phone"></p>
                                    </li>
                                    <li>
                                       <p id="shipping_email"></p>
                                    </li>
                                 </ul>
                              </div>
                              <div class="payment-information">
                                 <h4 class="title">
                                    {{ __('Payment Info') }}
                                 </h4>
                                 <div class="row">
                                    <div class="col-lg-12">
                                       <div class="nav flex-column" role="tablist" aria-orientation="vertical">

                                          @if (Auth::check())
                                          {{-- wallet checkout start --}}
                                          <a class="nav-link payment" href="javascript:;" data-show="no"
                                             data-val="wallet" data-toggle="pill" role="tab"
                                             data-form="{{ route('front.wallet.submit') }}"
                                             aria-controls="v-pills-tab-wallet" aria-selected="false">
                                             <div class="icon">
                                                <span class="radio"></span>
                                             </div>
                                             <p>
                                                {{ __('Wallet') }}
                                                <small>
                                                   {{ __('Pay from your wallet') }}
                                                </small>
                                             </p>
                                          </a>
                                          {{-- wallet checkout end --}}
                                          @endif

                                          @foreach($gateways as $gt)
                                          @if ($gt->checkout == 1)
                                          @if($gt->type == 'manual')
                                          @if($digital == 0)
                                          <a class="nav-link payment" data-val="" data-show="{{$gt->showForm()}}"
                                             data-form="{{ $gt->showCheckoutLink() }}"
                                             data-href="{{ route('front.load.payment',['slug1' => $gt->showKeyword(),'slug2' => $gt->id]) }}"
                                             id="v-pills-tab{{ $gt->id }}-tab" data-toggle="pill"
                                             href="#v-pills-tab{{ $gt->id }}" role="tab"
                                             aria-controls="v-pills-tab{{ $gt->id }}" aria-selected="false">
                                             <div class="icon">
                                                <span class="radio"></span>
                                             </div>
                                             <p>
                                                {{ $gt->title }}
                                                @if($gt->subtitle != null)
                                                <small>
                                                   {{ $gt->subtitle }}
                                                </small>
                                                @endif
                                             </p>
                                          </a>
                                          @endif
                                          @else
                                          <a class="nav-link payment" data-val="{{ $gt->keyword }}"
                                             data-show="{{$gt->showForm()}}" data-form="{{ $gt->showCheckoutLink() }}"
                                             data-href="{{ route('front.load.payment',['slug1' => $gt->showKeyword(),'slug2' => $gt->id]) }}"
                                             id="v-pills-tab{{ $gt->id }}-tab" data-toggle="pill"
                                             href="#v-pills-tab{{ $gt->id }}" role="tab"
                                             aria-controls="v-pills-tab{{ $gt->id }}" aria-selected="false">
                                             <div class="icon">
                                                <span class="radio"></span>
                                             </div>
                                             <p>
                                                {{ $gt->name }}
                                                @if($gt->information != null)
                                                <small>
                                                   {{ $gt->getAutoDataText() }}
                                                </small>
                                                @endif
                                             </p>
                                          </a>
                                          @endif
                                          @endif
                                          @endforeach

                                       </div>
                                    </div>
                                    <div class="col-lg-12">
                                       <div class="pay-area d-none">
                                          <div class="tab-content" id="v-pills-tabContent">
                                             @foreach($gateways as $gt)
                                             @if($gt->type == 'manual')
                                             @if($digital == 0)
                                             <div class="tab-pane fade" id="v-pills-tab{{ $gt->id }}" role="tabpanel"
                                                aria-labelledby="v-pills-tab{{ $gt->id }}-tab">
                                             </div>
                                             @endif
                                             @else
                                             <div class="tab-pane fade" id="v-pills-tab{{ $gt->id }}" role="tabpanel"
                                                aria-labelledby="v-pills-tab{{ $gt->id }}-tab">
                                             </div>
                                             @endif
                                              @endforeach
                                              <div class="tab-pane fade" id="v-pills-tab-wallet" role="tabpanel"></div>
                                              <div class="tab-pane fade" id="v-pills-tab-campay" role="tabpanel"></div>
                                           </div>
                                       </div>
                                    </div>
                                 </div>
                              </div>
                              <div class="row">
                                 <div class="col-lg-12 mt-3">
                                    <div class="bottom-area">
                                       <a href="javascript:;" id="step2-btn" class="mybtn1 mr-3">{{ __('Back') }}</a>
                                       <button type="submit" id="final-btn" class="mybtn1">{{ __('Continue') }}</button>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>

               @if ($gs->multiple_shipping == 0)
               <input type="hidden" name="shipping_id" id="multi_shipping_id" value="{{@$shipping_data[0]->id}}">
               <input type="hidden" name="packaging_id" id="multi_packaging_id" value="{{@$package_data[0]->id}}">
               @endif
               <input type="hidden" name="dp" value="{{$digital}}">
               <input type="hidden" id="input_tax" name="tax" value="">
               <input type="hidden" id="input_tax_type" name="tax_type" value="">
               <input type="hidden" name="totalQty" value="{{$totalQty}}">
               <input type="hidden" name="vendor_shipping_id" value="{{ $vendor_shipping_id }}">
               <input type="hidden" name="vendor_packing_id" value="{{ $vendor_packing_id }}">
               <input type="hidden" name="currency_sign" value="{{ $curr->sign }}">
               <input type="hidden" name="currency_name" value="{{ $curr->name }}">
               <input type="hidden" name="currency_value" value="{{ $curr->value }}">
               @php
               @endphp
               @if(Session::has('coupon_total'))
               <input type="hidden" name="total" id="grandtotal" value="{{round($totalPrice * $curr->value,2)}}">
               <input type="hidden" id="tgrandtotal" value="{{ $totalPrice }}">
                <input type="hidden" id="base-cart-total" value="{{ round($totalPrice * $curr->value, 2) }}">
                <input type="hidden" name="total_delivery_fee" id="total_delivery_fee" value="0">

               @elseif(Session::has('coupon_total1'))
               <input type="hidden" name="total" id="grandtotal" value="{{ preg_replace(" /[^0-9,.]/", "" ,
                  Session::get('coupon_total1') ) }}">
               <input type="hidden" id="tgrandtotal" value="{{ preg_replace(" /[^0-9,.]/", "" ,
                  Session::get('coupon_total1') ) }}">
                  <input type="hidden" id="base-cart-total" value="{{ round($totalPrice * $curr->value, 2) }}">
                  <input type="hidden" name="total_delivery_fee" id="total_delivery_fee" value="0">
               @else
               <input type="hidden" name="total" id="grandtotal" value="{{round($totalPrice * $curr->value,2)}}">
               <input type="hidden" id="tgrandtotal" value="{{round($totalPrice * $curr->value,2)}}">
               <input type="hidden" id="base-cart-total" value="{{ round($totalPrice * $curr->value, 2) }}">
               <input type="hidden" name="total_delivery_fee" id="total_delivery_fee" value="0">
               @endif
               <input type="hidden" id="original_tax" value="0">
               <input type="hidden" id="wallet-price" name="wallet_price" value="0">
               <input type="hidden" id="ttotal"
                  value="{{ Session::has('cart') ? App\Models\Product::convertPrice(Session::get('cart')->totalPrice) : '0' }}">
               <input type="hidden" name="coupon_code" id="coupon_code"
                  value="{{ Session::has('coupon_code') ? Session::get('coupon_code') : '' }}">
               <input type="hidden" name="coupon_discount" id="coupon_discount"
                  value="{{ Session::has('coupon') ? Session::get('coupon') : '' }}">
               <input type="hidden" name="coupon_id" id="coupon_id"
                  value="{{ Session::has('coupon') ? Session::get('coupon_id') : '' }}">
               <input type="hidden" name="user_id" id="user_id"
                  value="{{ Auth::guard('web')->check() ? Auth::guard('web')->user()->id : '' }}">
            </form>
         </div>
         @if(Session::has('cart'))
         <div class="col-lg-4">
            <div class="right-area">
               <div class="order-box">
                  <h4 class="title">{{ __('PRICE DETAILS') }}</h4>
                  <ul class="order-list">
                     <li>
                        <p>
                           {{ __('Total MRP') }}
                        </p>
                        <P>
                           <b class="cart-total">{{ Session::has('cart') ?
                              App\Models\Product::convertPrice(Session::get('cart')->totalPrice) : '0.00' }}</b>
                        </P>
                     </li>
                        <li id="total-fee-row" style="display:none;">
                            <p>{{ __('Total Delivery Fee') }}</p>
                            <p><b id="total-fee">0.00</b></p>
                        </li>
                     <li class="tax_show  d-none">
                        <p>
                           {{ __('Tax')}}
                        </p>
                        <P>
                           <b> <span class="original_tax">0</span> % </b>
                        </P>
                     </li>
                     <li class="">
                        <p>
                           {{ __('Packaging Cost')}}
                        </p>
                        <P>
                           <b> <span class="packing_cost_view">{{App\Models\Product::convertPrice(0)}}</span> </b>
                        </P>
                     </li>
                     @if(Session::has('coupon'))
                     <li class="discount-bar">
                        <p>
                           {{ __('Discount') }} <span class="dpercent">{{ Session::get('coupon_percentage') == 0 ? '' :
                              '('.Session::get('coupon_percentage').')' }}</span>
                        </p>
                        <P>
                           @if($gs->currency_format == 0)
                           <b id="discount">{{ $curr->sign }}{{ Session::get('coupon') }}</b>
                           @else
                           <b id="discount">{{ Session::get('coupon') }}{{ $curr->sign }}</b>
                           @endif
                        </P>
                     </li>
                     @else
                     <li class="discount-bar d-none">
                        <p>
                           {{ __('Discount') }} <span class="dpercent"></span>
                        </p>
                        <P>
                           <b id="discount">{{ $curr->sign }}{{ Session::get('coupon') }}</b>
                        </P>
                     </li>
                     @endif
                  </ul>

                  <div class="cupon-box">
                     <div id="coupon-link">
                        <img src="{{ asset('assets/front/images/tag.png') }}">
                        {{ __('Have a promotion code?') }}
                     </div>
                     <form id="check-coupon-form" class="coupon">
                        <input type="text" placeholder="{{ __('Coupon Code') }}" id="code" required=""
                           autocomplete="off">
                        <button type="submit">{{ __('Apply') }}</button>
                     </form>
                  </div>
                  @if($digital == 0)
                  @if ($gs->multiple_shipping == 0)
                  <div class="packeging-area">
                     <h4 class="title">{{ __('Shipping Method') }}</h4>

                     @foreach($shipping_data as $data)
                     <div class="radio-design">
                        <input type="radio" class="shipping" data-price="{{ round($data->price * $curr->value,2) }}"
                           data-form="{{$data->title}}" id="free-shepping{{ $data->id }}" name="shipping_id"
                           value="{{ $data->id }}">
                        <span class="checkmark"></span>
                        <label for="free-shepping{{ $data->id }}">
                           {{ $data->title }}
                           @if($data->price != 0)
                           + {{ $curr->sign }}{{ round($data->price * $curr->value,2) }}
                           @endif
                           <small>{{ $data->subtitle }}</small>
                        </label>
                     </div>
                     @endforeach
                  </div>

                  <div class="packeging-area">
                     <h4 class="title">{{ __('Packaging') }}</h4>
                     <div class="radio-design">
                        <input type="radio" class="packing" data-price="0"
                           data-form="{{ __('None') }}" id="free-package-none" name="packeging_id"
                           value="0">
                        <span class="checkmark"></span>
                        <label for="free-package-none">
                           {{ __('None') }}
                        </label>
                     </div>
                     @foreach($package_data as $data)
                     <div class="radio-design">
                        <input type="radio" class="packing" data-price="{{ round($data->price * $curr->value,2) }}"
                           data-form="{{$data->title}}" id="free-package{{ $data->id }}" name="packeging_id"
                           value="{{ $data->id }}">
                        <span class="checkmark"></span>
                        <label for="free-package{{ $data->id }}">
                           {{ $data->title }}
                           @if($data->price != 0)
                           + {{ $curr->sign }}{{ round($data->price * $curr->value,2) }}
                           @endif
                           <small>{{ $data->subtitle }}</small>
                        </label>
                     </div>
                     @endforeach
                  </div>
                  @endif
                  @endif

                  <div class="final-price">
                     <span>{{ __('Final Price') }} :</span>
                     @if(Session::has('coupon_total'))
                     @if($gs->currency_format == 0)
                     <span id="final-cost">{{ $curr->sign }}{{ $totalPrice }}</span>
                     @else
                     <span id="final-cost">{{ $totalPrice }}{{ $curr->sign }}</span>
                     @endif
                     @elseif(Session::has('coupon_total1'))
                     <span id="final-cost"> {{ Session::get('coupon_total1') }}</span>
                     @else
                     <span id="final-cost">{{ App\Models\Product::convertPrice($totalPrice) }}</span>
                     @endif
                  </div>
                  {{-- Final Price Area End --}}
                  @endif
               </div>
            </div>
         </div>

      </div>
   </div>
</section>
<!-- Check Out Area End-->
@if(isset($checked))
<!-- LOGIN MODAL -->
<div class="modal fade" id="comment-log-reg1" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog"
   aria-labelledby="comment-log-reg-Title" aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" aria-label="Close">
               <a href="{{ url()->previous() }}"><span aria-hidden="true">&times;</span></a>
            </button>
         </div>
         <div class="modal-body">
            <nav class="comment-log-reg-tabmenu">
               <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link login active" id="nav-log-tab" data-toggle="tab" href="#nav-log"
                     role="tab" aria-controls="nav-log" aria-selected="true">
                     {{ __('Login') }}
                  </a>
                  <a class="nav-item nav-link" id="nav-reg-tab" data-toggle="tab" href="#nav-reg" role="tab"
                     aria-controls="nav-reg" aria-selected="false">
                     {{ __('Register') }}
                  </a>
               </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
               <div class="tab-pane fade show active" id="nav-log" role="tabpanel" aria-labelledby="nav-log-tab">
                  <div class="login-area">
                     <div class="header-area">
                        <h4 class="title">{{ __('LOGIN NOW') }}</h4>
                     </div>
                     <div class="login-form signin-form">
                        @include('includes.admin.form-login')
                        <form class="mloginform" action="{{ route('user.login.submit') }}" method="POST">
                           {{ csrf_field() }}
                           <div class="form-input">
                              <input type="email" name="email" placeholder="{{ __('Type Email Address') }}" required="">
                              <i class="icofont-user-alt-5"></i>
                           </div>
                           <div class="form-input">
                              <input type="password" class="Password" name="password"
                                 placeholder="{{ __('Type Password') }}" required="">
                              <i class="icofont-ui-password"></i>
                           </div>
                           <div class="form-forgot-pass">
                              <div class="left">
                                 <input type="hidden" name="modal" value="1">
                                 <input type="checkbox" name="remember" id="mrp" {{ old('remember') ? 'checked' : '' }}>
                                 <label for="mrp">{{ __('Remember Password') }}</label>
                              </div>
                              <div class="right">
                                 <a id="show-forgot">
                                    {{ __('Forgot Password?') }}
                                 </a>
                              </div>
                           </div>
                           <input id="authdata" type="hidden" value="{{ __('Authenticating...') }}">
                           <button type="submit" class="submit-btn">{{ __('Login') }}</button>
                           @if(App\Models\Socialsetting::find(1)->f_check == 1 ||
                           App\Models\Socialsetting::find(1)->g_check == 1)
                           <div class="social-area">
                              <h3 class="title">{{ __('Or')}}</h3>
                              <p class="text">{{__('Sign In with social media')}}</p>
                              <ul class="social-links">
                                 @if(App\Models\Socialsetting::find(1)->f_check == 1)
                                 <li>
                                    <a href="{{ route('social-provider','facebook') }}">
                                       <i class="fab fa-facebook-f"></i>
                                    </a>
                                 </li>
                                 @endif
                                 @if(App\Models\Socialsetting::find(1)->g_check == 1)
                                 <li>
                                    <a href="{{ route('social-provider','google') }}">
                                       <i class="fab fa-google-plus-g"></i>
                                    </a>
                                 </li>
                                 @endif
                              </ul>
                           </div>
                           @endif
                        </form>
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade" id="nav-reg" role="tabpanel" aria-labelledby="nav-reg-tab">
                  <div class="login-area signup-area">
                     <div class="header-area">
                        <h4 class="title">{{ __('Signup Now') }}</h4>
                     </div>
                     <div class="login-form signup-form">
                        @include('includes.admin.form-login')
                        <form class="mregisterform" action="{{route('user-register-submit')}}" method="POST">
                           @csrf
                           <div class="form-input">
                              <input type="text" class="User Name" name="name" placeholder="{{ __('Full Name') }}"
                                 required="">
                              <i class="icofont-user-alt-5"></i>
                           </div>
                           <div class="form-input">
                              <input type="email" class="User Name" name="email" placeholder="{{ __('Email Address') }}"
                                 required="">
                              <i class="icofont-email"></i>
                           </div>
                           <div class="form-input">
                              <input type="text" class="User Name" name="phone" placeholder="{{ __('Phone Number') }}"
                                 required="">
                              <i class="icofont-phone"></i>
                           </div>
                           <div class="form-input">
                              <input type="text" class="User Name" name="address" placeholder="{{ __('Address') }}"
                                 required="">
                              <i class="icofont-location-pin"></i>
                           </div>
                           <div class="form-input">
                              <input type="password" class="Password" name="password" placeholder="{{ __('Password') }}"
                                 required="">
                              <i class="icofont-ui-password"></i>
                           </div>
                           <div class="form-input">
                              <input type="password" class="Password" name="password_confirmation"
                                 placeholder="{{ __('Confirm Password') }}" required="">
                              <i class="icofont-ui-password"></i>
                           </div>
                           <ul class="captcha-area">
                              <li>
                                 <p>
                                    <img class="codeimg1" src="{{asset(" assets/images/capcha_code.png")}}" alt="">
                                    <i class="fas fa-sync-alt pointer refresh_code"></i>
                                 </p>
                              </li>
                           </ul>
                           <div class="form-input">
                              <input type="text" class="Password" name="codes" placeholder="{{ __('Enter Code') }}"
                                 required="">
                              <i class="icofont-refresh"></i>
                           </div>
                           <input class="mprocessdata" type="hidden" value="{{ __('Processing...') }}">
                           <button type="submit" class="submit-btn">{{ __('Register') }}</button>
                        </form>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!-- LOGIN MODAL ENDS -->
@endif








@includeIf('partials.global.common-footer')
@endsection
@section('script')
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<script src="https://www.2checkout.com/checkout/api/2co.min.js"></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>



<script type="text/javascript">
   $('a.payment:first').addClass('active');
   $('.checkoutform').attr('action',$('a.payment:first').attr('data-form'));
   $($('a.payment:first').attr('href')).load($('a.payment:first').data('href'));
   	var show = $('a.payment:first').data('show');
   	if(show != 'no') {
   		$('.pay-area').removeClass('d-none');
   	}
   	else {
   		$('.pay-area').addClass('d-none');
   	}
   $($('a.payment:first').attr('href')).addClass('active').addClass('show');
</script>
<script type="text/javascript">
   var coup = 0;
   var pos = {{ $gs->currency_format }};
   @if(isset($checked))
   	$('#comment-log-reg1').modal('show');
   @endif
  let mship = 0;
  let mpack = 0;
   var ftotal = parseFloat($('#grandtotal').val());
      ftotal = parseFloat(ftotal).toFixed(2);
      if(pos == 0){
         $('#final-cost').html('{{ $curr->sign }}'+ftotal)
      }
      else{
         $('#final-cost').html(ftotal+'{{ $curr->sign }}')
      }
      $('#grandtotal').val(ftotal);
      let original_tax = 0;
   	$(document).on('change','#select_country',function(){
   		$(this).attr('data-href');
   		let state_id = 0;
   		let country_id = $('#select_country option:selected').attr('data');
   		let is_state = $('option:selected', this).attr('rel');
   		let is_auth = $('option:selected', this).attr('rel1');
   		let is_user = $('option:selected', this).attr('rel5');
   		let state_url = $('option:selected', this).attr('data-href');
   		if(is_auth == 1 || is_state == 1) {
   			if(is_state == 1){
   				$('.select_state').removeClass('d-none');
   				$.get(state_url,function(response){
   					$('#show_state').html(response.data);
   					if(is_user==1){
   						tax_submit(country_id,response.state);
   					}else{
   						tax_submit(country_id,state_id);
   					}
   				});
   			}else{
   				tax_submit(country_id,state_id);
   				hide_state();
   			}
   		}else{
   			tax_submit(country_id,state_id);
   			hide_state();
   		}
   	});
   	$(document).on('change','#show_state',function(){
   		let state_id = $(this).val();
   		let country_id = $('#select_country option:selected').attr('data');
   		tax_submit(country_id,state_id);
         $.get("{{route('state.wise.city')}}",{state_id:state_id},function(data){
            $('#show_city').parent().removeClass('d-none');
            $('#show_city').html(data.data);
         });
   	});
// var pos = {{ $gs->currency_format }};   // currency position
// var cartDeliveryFee = 0;                // 🔑 hold latest fee globally
// $(document).on('change', '#show_city', function () {
//     let cityId = $(this).val();
//     if (!cityId) return;
//     $.get("{{ route('front.calculateDistance') }}",
//         { city_id: cityId, _token: "{{ csrf_token() }}" },
//         function (res) {
//             if (!res.error && res.total_fee !== undefined) {
//                 $('#total-fee').text(res.total_fee + ' XAF');
//                 $('#total-fee-row').show();
//                 let cartBase = parseFloat($('#base-cart-total').val());
//                 let finalWithDelivery = cartBase + parseFloat(res.total_fee);
//                 if (pos == 0) {
//                     $('#final-cost').html('{{ $curr->sign }}' + finalWithDelivery.toFixed(2));
//                 } else {
//                     $('#final-cost').html(finalWithDelivery.toFixed(2) + '{{ $curr->sign }}');
//                 }
//                 $('#grandtotal').val(finalWithDelivery.toFixed(2));
//                 cartDeliveryFee = parseFloat(res.total_fee) || 0;
//                 if ($('#total_delivery_fee').length === 0) {
//                     $('.checkoutform').append(
//                         '<input type="hidden" name="total_delivery_fee" id="total_delivery_fee">'
//                     );
//                 }
//                 $('#total_delivery_fee').val(cartDeliveryFee.toFixed(2));
//             }
//         }
//     );
// });
// $(document).on('submit', '.checkoutform', function () {
//     if ($('#total_delivery_fee').length === 0) {
//         $(this).append(
//             '<input type="hidden" name="total_delivery_fee" id="total_delivery_fee">'
//         );
//     }
//     $('#total_delivery_fee').val(cartDeliveryFee.toFixed(2));
// });

// Robust service-area delivery fee updater
var pos = {{ $gs->currency_format }};   // currency position
var cartDeliveryFee = 0;
$(document).on('change', '#service_area_select, #service_area_id', function () {
    var $sel = $(this);
    var serviceAreaId = $sel.val();
    if (!serviceAreaId) {
        $('#total-fee-row').hide();
        return;
    }
    $.get("{{ route('front.calculateDistance') }}", {
        service_area_id: serviceAreaId,
        _token: "{{ csrf_token() }}"
    })
    .done(function(res) {
        console.log('calculateDistance response:', res);
        if (!res || typeof res.total_fee === 'undefined') {
            console.warn('calculateDistance: unexpected response', res);
            return;
        }
        var fee = parseFloat(String(res.total_fee).toString().replace(/[^0-9.\-]+/g, '')) || 0;
        $('#total-fee').text(fee.toFixed(2) + ' {{ $curr->sign }}');
        $('#total-fee-row').show();
        var baseVal = $('#base-cart-total').val() || $('#tgrandtotal').val() || '0';
        var cartBase = parseFloat(String(baseVal).replace(/[^0-9.\-]+/g, '')) || 0;
        var finalWithDelivery = cartBase + fee;
        if (parseInt(pos) === 0) {
            $('#final-cost').html('{{ $curr->sign }}' + finalWithDelivery.toFixed(2));
        } else {
            $('#final-cost').html(finalWithDelivery.toFixed(2) + '{{ $curr->sign }}');
        }
        if ($('#grandtotal').length) {
            $('#grandtotal').val(finalWithDelivery.toFixed(2));
        }
        cartDeliveryFee = fee;
        if ($('#total_delivery_fee').length === 0) {
            var $form = $('form.checkoutform').length ? $('form.checkoutform') : $('form').first();
            $form.append('<input type="hidden" name="total_delivery_fee" id="total_delivery_fee" />');
        }
        $('#total_delivery_fee').val(cartDeliveryFee.toFixed(2));
    })
    .fail(function(xhr, status, err) {
        console.error('calculateDistance request failed:', status, err, xhr.responseText);
    });
});
$(document).on('submit', 'form.checkoutform, form#checkoutForm, form[name="checkout"]', function () {
    if ($('#total_delivery_fee').length === 0) {
        $(this).append('<input type="hidden" name="total_delivery_fee" id="total_delivery_fee" />');
    }
    $('#total_delivery_fee').val((cartDeliveryFee || 0).toFixed(2));
});
   	function hide_state(){
   		$('.select_state').addClass('d-none');
   	}
   $(document).ready(function(){
      //getShipping();
      getPacking();
      let country_id = $('#select_country option:selected').attr('data');
      let state_id = $('#select_country option:selected').attr('rel2');
      let is_state = $('#select_country option:selected', this).attr('rel');
      let is_auth = $('#select_country option:selected', this).attr('rel1');
      let state_url = $('#select_country option:selected', this).attr('data-href');
      if(is_auth == 1 && is_state ==1) {
         if(is_state == 1){
            $('.select_state').removeClass('d-none');
            $.get(state_url,function(response){
               $('#show_state').html(response.data);
               tax_submit(country_id,response.state);
            });
         }else{
            tax_submit(country_id,state_id);
            hide_state();
         }
      }else{
         tax_submit(country_id,state_id);
         hide_state();
      }
   });
   function tax_submit(country_id,state_id){
      $('.gocover').show();
      var total = $("#ttotal").val();
      var ship = 0;
      $.ajax({
            type: "GET",
            url:mainurl+"/country/tax/check",
            data:{state_id:state_id,country_id:country_id,total:total,shipping_cost:ship},
            success:function(data){
               $('#grandtotal').val(data[0]);
               $('#tgrandtotal').val(data[0]);
               $('#original_tax').val(data[1] ? data[1] : 0);
               $('.tax_show').removeClass('removeClass').removeClass('d-none');
               $('#input_tax').val(data[11] ? data[11] : 0);
               $('#input_tax_type').val(data[12] ? data[12] : 'country_tax');
               let tax_val = parseFloat(data[1]);
               if (isNaN(tax_val)) {
                   tax_val = 0;
               }
               $('.original_tax').html(tax_val.toFixed(2));
                  var ttotal = parseFloat($('#grandtotal').val());
                  var tttotal = parseFloat($('#grandtotal').val()) + (parseFloat(mship) + parseFloat(mpack));
                  ttotal = parseFloat(ttotal).toFixed(2);
                  tttotal = parseFloat(tttotal).toFixed(2);
                  $('#grandtotal').val(data[0]+parseFloat(mship) + parseFloat(mpack));
                  if(pos == 0){
                     $('#final-cost').html('{{ $curr->sign }}'+tttotal);
                     $('.total-cost-dum #total-cost').html('{{ $curr->sign }}'+ttotal);
                  }
                  else{
                     $('#total-cost').html('');
                     $('#final-cost').html(tttotal+'{{ $curr->sign }}');
                     $('.total-cost-dum #total-cost').html(ttotal+'{{ $curr->sign }}');
                  }
                  $('.gocover').hide();
            }
      });
   }
   $('#shipop').on('change',function(){
      var val = $(this).val();
      if(val == 'pickup'){
         $('#shipshow').removeClass('d-none');
         $("#ship-diff-address").parent().addClass('d-none');
         $('.ship-diff-addres-area').addClass('d-none');
         $('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required',false);
      }
      else{
         $('#shipshow').addClass('d-none');
         $("#ship-diff-address").parent().removeClass('d-none');
         $('.ship-diff-addres-area').removeClass('d-none');
         $('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required',true);
      }
   });
   function getShipping(){
      mship = 0;
      $('.shipping').each(function(){
            if($(this).is(':checked')){
               mship += parseFloat($(this).attr('data-price'));
            }
      });
   }
   $(document).on('mousedown', '.shipping', function() {
      $(this).data('wasChecked', $(this).is(':checked'));
   });

   $('.shipping').on('click',function(){
      if ($(this).data('wasChecked')) {
         $(this).prop('checked', false);
         $(this).data('wasChecked', false);
         mship = 0;
         $('#multi_shipping_id').val('');
      } else {
         getShipping();
         let ref = $(this).attr('ref');
         let view = $(this).attr('view');
         let title = $(this).attr('data-form');
         $('#shipping_text'+ref).html(title +'+'+ view);
         $('#multi_shipping_id').val($(this).val());
      }
      var cartDeliveryFee = parseFloat($('#total_delivery_fee').val()) || 0;
      var ttotal = parseFloat($('#tgrandtotal').val()) + parseFloat(mship) + parseFloat(mpack) + cartDeliveryFee;
      ttotal = parseFloat(ttotal).toFixed(2);
      if(pos == 0){
            $('#final-cost').html('{{ $curr->sign }}'+ttotal);
         }
         else{
            $('#final-cost').html(ttotal+'{{ $curr->sign }}');
         }
      $('#grandtotal').val(ttotal);
   })
   $(document).on('mousedown', '.packing', function() {
      $(this).data('wasChecked', $(this).is(':checked'));
   });

   $('.packing').on('click',function(){
      if ($(this).data('wasChecked')) {
         $(this).prop('checked', false);
         $(this).data('wasChecked', false);
         mpack = 0;
         $('#multi_packaging_id').val('');
      } else {
         getPacking();
         let ref = $(this).attr('ref');
         let view = $(this).attr('view');
         let title = $(this).attr('data-form');
         $('#packing_text'+ref).html(title +'+'+ view);
         $('#multi_packaging_id').val($(this).val());
      }
      var cartDeliveryFee = parseFloat($('#total_delivery_fee').val()) || 0;
      var ttotal = parseFloat($('#tgrandtotal').val()) + parseFloat(mship) + parseFloat(mpack) + cartDeliveryFee;
      ttotal = parseFloat(ttotal).toFixed(2);
      if(pos == 0){
            $('#final-cost').html('{{ $curr->sign }}'+ttotal);
         }
         else{
            $('#final-cost').html(ttotal+'{{ $curr->sign }}');
         }
      $('#grandtotal').val(ttotal);
   })
   $("#check-coupon-form").on('submit', function () {
      var val = $("#code").val();
      var total = $("#ttotal").val();
      var ship = 0;
         $.ajax({
                  type: "GET",
                  url:mainurl+"/carts/coupon/check",
                  data:{code:val, total:total, shipping_cost:ship},
                  success:function(data){
                     if(data == 0)
                     {
                        toastr.error('{{__('Coupon not found')}}');
                           $("#code").val("");
                     }
                     else if(data == 2)
                     {
                        toastr.error('{{__('Coupon already have been taken')}}');
                           $("#code").val("");
                     }
                     else
                     {
                           $("#check-coupon-form").toggle();
                           $(".discount-bar").removeClass('d-none');
                  if(pos == 0){
                     $('.total-cost-dum #total-cost').html('{{ $curr->sign }}'+data[0]);
                     $('#discount').html('{{ $curr->sign }}'+data[2]);
                  }
                  else{
                     $('.total-cost-dum #total-cost').html(data[0]);
                     $('#discount').html(data[2]+'{{ $curr->sign }}');
                  }
                     $('#grandtotal').val(data[0]);
                     $('#tgrandtotal').val(data[0]);
                     $('#coupon_code').val(data[1]);
                     $('#coupon_discount').val(data[2]);
                     if(data[4] != 0){
                     $('.dpercent').html('('+data[4]+')');
                     }
                     else{
                     $('.dpercent').html('');
                     }
                  var ttotal = data[6] + parseFloat(mship) + parseFloat(mpack);
                  ttotal = parseFloat(ttotal);
                     if(ttotal % 1 != 0)
                     {
                        ttotal = ttotal.toFixed(2);
                     }
                        if(pos == 0){
                           $('#final-cost').html('{{ $curr->sign }}'+ttotal)
                        }
                        else{
                           $('#final-cost').html(ttotal+'{{ $curr->sign }}')
                        }
                              toastr.success(lang.coupon_found);
                              $("#code").val("");
                              }
                           }
                        });
            return false;
   });
   $("#open-pass").on( "change", function() {
      if(this.checked){
         $('.set-account-pass').removeClass('d-none');
         $('.set-account-pass input').prop('required',true);
         $('#personal-email').prop('required',true);
         $('#personal-name').prop('required',true);
      }
      else{
         $('.set-account-pass').addClass('d-none');
         $('.set-account-pass input').prop('required',false);
         $('#personal-email').prop('required',false);
         $('#personal-name').prop('required',false);

      }
   });
   $("#ship-diff-address").on( "change", function() {
         if(this.checked){
            $('.ship-diff-addres-area').removeClass('d-none');
            $('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required',true);
         }
         else{
            $('.ship-diff-addres-area').addClass('d-none');
            $('.ship-diff-addres-area input, .ship-diff-addres-area select').prop('required',false);
         }
   });
   function getPacking(){
      mpack = 0;
      $('.packing').each(function(){
            if($(this).is(':checked')){
               mpack += parseFloat($(this).attr('data-price'));
            }
            $('.packing_cost_view').html('{{ $curr->sign }}'+mpack);
      });
   }
</script>
<script type="text/javascript">
    var ck = 0;

    // Step 1 form submission
    // Step 1 button click
    $('#continue-step1').on('click', function(e){
        // Manual form validation check
        var form = $('.checkoutform')[0];
        
        // Manual check for service area since we removed 'required' to avoid Select2 blocking
        var serviceArea = $('#service_area_id').val();
        if(!serviceArea || serviceArea == "") {
            toastr.error('Please select a Service Area / Location');
            return false;
        }

        if (form.checkValidity()) {
            $('.nav-item a[href="#pills-step2"]').removeClass('disabled').click();
            
            $('#pills-step1').removeClass('active show');
            $('#pills-step2').addClass('active show');
            
            // Sync tabs explicitly
            $('#pills-step1-tab').removeClass('active');
            $('#pills-step2-tab').addClass('active').removeClass('disabled');
        } else {
            // Trigger browser validation UI for other fields
            form.reportValidity();
        }
    });

    $('.checkoutform').on('submit', function(e){
        if(ck == 0) {
            e.preventDefault();
        } else {
            $('#preloader').show();
        }
    });

    // Step navigation buttons
    $('#step1-btn').on('click', function(){
        $('#pills-step2').removeClass('active');
        $('#pills-step2').removeClass('show');
        $('#pills-step1').addClass('active');
        $('#pills-step1').addClass('show');
        $('#pills-step1-tab').click();
    });

    $('#step2-btn').on('click', function(){
        $('#pills-step3').removeClass('active');
        $('#pills-step3').removeClass('show');
        $('#pills-step2').addClass('active');
        $('#pills-step2').addClass('show');
        $('#pills-step2-tab').removeClass('active');
        $('#pills-step3-tab').addClass('disabled');
        $('#pills-step2-tab').click();
    });

    // Step 3 button
    $('#step3-btn').on('click', function(){
        if ($('.shipping:checked').length == 0) {
            toastr.error('Please select a delivery method before proceeding.');
            return false;
        }
        ck = 1; // allow submit for all users

        // Set form ID based on payment
        var paymentVal = $('a.payment:first').data('val');
        if(paymentVal == 'paystack'){
            $('.checkoutform').attr('id','step1-form');
        } else if(paymentVal == 'voguepay'){
            $('.checkoutform').attr('id','voguepay');
        } else if(paymentVal == 'twocheckout'){
            $('.checkoutform').attr('id','twocheckout');
        } else if(paymentVal == 'mercadopago'){
            $('.checkoutform').attr('id','mercadopago');
        } else {
            $('.checkoutform').attr('id','');
        }

        $('#pills-step2').removeClass('active');
        $('#pills-step2').removeClass('show');

        $('#pills-step3-tab').removeClass('disabled').click();

        // Standardize shipping info for guest or logged-in users
        var shipping_user  = $('input[name="shipping_name"]').val() || $('input[name="customer_name"]').val() || '';
        var shipping_location  = $('input[name="shipping_address"]').val() || $('input[name="customer_address"]').val() || '';
        var shipping_phone = $('input[name="shipping_phone"]').val() || $('input[name="customer_phone"]').val() || '';
        var shipping_email = $('input[name="shipping_email"]').val() || $('input[name="customer_email"]').val() || '';

        $('#shipping_user').html('<i class="fas fa-user"></i>' + shipping_user);
        $('#shipping_location').html('<i class="fas fas fa-map-marker-alt"></i>' + shipping_location);
        $('#shipping_phone').html('<i class="fas fa-phone"></i>' + shipping_phone);
        $('#shipping_email').html('<i class="fas fa-envelope"></i>' + shipping_email);

        $('#pills-step1-tab').removeClass('active');
        $('#pills-step2-tab').removeClass('active');
        $('#pills-step3-tab').addClass('active');

        $('#pills-step3').addClass('active');
        $('#pills-step3').addClass('show');
    });

    // Final button triggers checkout
    $('#final-btn').on('click', function(){
        ck = 1;
    });

    // Automatically append WhatsApp number to order notes on submit
    $(document).on('submit', '.checkoutform', function() {
        var whatsapp = $('#customer_whatsapp').val();
        if(whatsapp && whatsapp.trim() !== '') {
            var note = $('#Order_Note').val();
            note = note.replace(/\s*\[WhatsApp: .*?\]\s*/g, '');
            $('#Order_Note').val(note + ' [WhatsApp: ' + whatsapp + ']');
        }
    });

   // When user clicks a payment method
$('.payment').on('click', function () {
    var paymentVal = $(this).data('val');          // e.g., paystack, mercadopago, wallet
    var paymentFormAction = $(this).data('form');  // actual POST route for payment

    // Set the form action to the correct POST route
    $('.checkoutform').attr('action', paymentFormAction);

    // Set form ID if needed for specific payment JS
    if(paymentVal === 'paystack') {
        $('.checkoutform').attr('id', 'step1-form');
    } else if(paymentVal === 'mercadopago') {
        $('.checkoutform').attr('id', 'mercadopago');
    } else if(paymentVal === 'voguepay') {
        $('.checkoutform').attr('id', 'voguepay');
    } else if(paymentVal === 'twocheckout') {
        $('.checkoutform').attr('id', 'twocheckout');
    } else {
        $('.checkoutform').attr('id', '');
    }

    // Toggle active class for tabs
    $('.payment').removeClass('active');
    $(this).addClass('active');

    // Show or hide pay-area if this payment method has a form
    if ($(this).data('show') !== 'no') {
        $('.pay-area').removeClass('d-none');
    } else {
        $('.pay-area').addClass('d-none');
    }

    // Load payment form HTML via AJAX (GET request)
    var ajaxLoadUrl = $(this).data('href');        // front.load.payment GET route
    var tabId = $(this).attr('aria-controls');
    var $tabPane = $('#v-pills-tabContent #' + tabId);
    $tabPane.addClass('active show').load(ajaxLoadUrl, function() {
        if (paymentVal === 'campay') {
            let country = $('#select_country').val();
            let phoneInput = $('#campay_phone');
            if (country === 'Cameroon') {
                $(document).on('focus', '#campay_phone', function() {
                    let phoneInput = $(this);
                    let currentVal = phoneInput.val() ? phoneInput.val().trim() : '';
                    if (currentVal === '') {
                        phoneInput.val('+237');
                    } else if (!currentVal.startsWith('+237')) {
                        if (currentVal.startsWith('237')) {
                            phoneInput.val('+' + currentVal);
                        } else {
                            phoneInput.val('+237' + currentVal);
                        }
                    }
                });
                // Also trigger immediately once loaded
                let currentVal = phoneInput.val() ? phoneInput.val().trim() : '';
                if (currentVal === '') {
                    phoneInput.val('+237');
                }
            }
        }
    });

    // Remove active/show from other tabs
    $('#v-pills-tabContent .tab-pane').not($tabPane).removeClass('active show').html('');
});


    // Paystack form submit
    $(document).on('submit', '#step1-form', function(){
        $('#preloader').hide();
        var val = $('#sub').val();
        var total = Math.round($('#grandtotal').val());
        if(val == 0) {
            var handler = PaystackPop.setup({
                key: '{{$paystack['key']}}',
                email: $('input[name=customer_email]').val(),
                amount: total * 100,
                currency: "{{$curr->name}}",
                ref: '' + Math.floor((Math.random() * 1000000000) + 1),
                callback: function(response){
                    $('#ref_id').val(response.reference);
                    $('#sub').val('1');
                    $('#final-btn').click();
                },
                onClose: function(){
                    window.location.reload();
                }
            });
            handler.openIframe();
            return false;
        } else {
            $('#preloader').show();
            return true;
        }
    });
    </script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Automatically select the first payment method to initialize the form action
        $('.payment').first().click();

        $('#service_area_id').select2({
            placeholder: 'Select Service Area',
            allowClear: true,
            width: 'resolve'
        });
    });
</script>
<style>
    .displayBoxNone .select2-container--default .select2-selection--single{
            border: none !important;
    }
    .displayBoxNone .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: 7px;
    }
    .displayBoxNone .select2-container {
        border: 1px solid #bdccdb;
        border-radius: 0.25rem;
        height: 45px;
    }
</style>

@endsection