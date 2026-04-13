@extends('layouts.front')
@php use App\Helpers\PriceHelper; @endphp

@section('content')
@include('partials.global.common-header')

<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Purchased Items') }}</h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ ('user-dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Purchased Items') }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>

<div class="full-row bg-light">
   <div class="container">
      <div class="mb-4 d-xl-none">
         <button class="dashboard-sidebar-btn btn bg-primary rounded">
            <i class="fas fa-bars"></i>
         </button>
      </div>
      
      <div class="row">
         <div class="col-xl-3">
            @include('partials.user.dashboard-sidebar')
         </div>
         
         <div class="col-xl-9">
            <div class="row">
               
               <!-- LEFT COLUMN: Addresses & Progress -->
               <div class="col-lg-8">
                  <div class="widget border-0 p-4 widget_categories bg-white rounded shadow-sm mb-4">
                     <div class="process-steps-area mb-4">
                        @include('partials.user.order-process')
                     </div>

                     <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="widget-title down-line mb-0">{{ __('Order#') }} {{$order->order_number}}</h4>
                        <span class="badge badge-primary px-3 py-2">{{$order->status}}</span>
                     </div>

                     <div class="row">
                        <div class="col-md-6 mb-4">
                           <div class="card border-0 bg-light rounded-lg h-100">
                              <div class="card-body">
                                 <h6 class="font-weight-bold mb-3 text-uppercase small text-secondary"><i class="fas fa-map-marker-alt mr-2"></i>{{ __('Shipping Address') }}</h6>
                                 <address class="mb-0 text-dark">
                                    @if($order->shipping == "shipto")
                                       <strong>{{$order->shipping_name == null ? $order->customer_name : $order->shipping_name}}</strong><br>
                                       {{$order->shipping_email == null ? $order->customer_email : $order->shipping_email}}<br>
                                       {{$order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone}}<br>
                                       {{$order->shipping_address == null ? $order->customer_address : $order->shipping_address}}<br>
                                       {{$order->shipping_city == null ? $order->customer_city : $order->shipping_city}}-{{$order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip}}
                                    @else
                                       <strong>{{ __('PickUp Location') }}</strong><br>
                                       {{$order->pickup_location}}
                                    @endif
                                 </address>
                              </div>
                           </div>
                        </div>

                        <div class="col-md-6 mb-4">
                           <div class="card border-0 bg-light rounded-lg h-100">
                              <div class="card-body">
                                 <h6 class="font-weight-bold mb-3 text-uppercase small text-secondary"><i class="fas fa-file-invoice mr-2"></i>{{ __('Billing Address') }}</h6>
                                 <address class="mb-0 text-dark">
                                    <strong>{{$order->customer_name}}</strong><br>
                                    {{$order->customer_email}}<br>
                                    {{$order->customer_phone}}<br>
                                    {{$order->customer_address}}<br>
                                    {{$order->customer_city}}-{{$order->customer_zip}}
                                 </address>
                              </div>
                           </div>
                        </div>
                     </div>

                     @if($order->deliveryRider)
                     <div class="alert alert-info border-0 rounded-lg d-flex align-items-center mb-4">
                        <div class="mr-3 h3 mb-0 text-info"><i class="fas fa-motorcycle"></i></div>
                        <div>
                           <h6 class="font-weight-bold mb-1">{{ __('Delivery Agent Assigned') }}</h6>
                           <p class="mb-0 small text-dark">
                              <strong>{{$order->deliveryRider->rider->name}}</strong> 
                              (<a href="tel:{{$order->deliveryRider->phone_number}}" class="text-primary">{{$order->deliveryRider->phone_number}}</a>)
                              @if($order->deliveryRider->rider->is_verified == 1)
                                 <span class="ml-2 text-success"><i class="fas fa-check-circle"></i> {{ __('Verified') }}</span>
                              @endif
                           </p>
                        </div>
                     </div>
                     @endif

                     <div class="mt-4">
                        <h5 class="font-weight-bold mb-3">{{ __('Ordered Products') }}</h5>
                        <div class="table-responsive">
                           <table class="table table-hover border-0">
                              <thead class="bg-light">
                                 <tr>
                                    <th class="border-0">{{ __('Product') }}</th>
                                    <th class="border-0 text-center">{{ __('Qty') }}</th>
                                    <th class="border-0 text-right">{{ __('Total') }}</th>
                                 </tr>
                              </thead>
                              <tbody>
                                 @foreach($cart['items'] as $product)
                                 <tr>
                                    <td class="border-bottom py-3">
                                       <div class="d-flex align-items-center">
                                          <div class="name">
                                             <a target="_blank" href="{{ route('front.product', $product['item']['slug']) }}" class="text-dark font-weight-bold">
                                                {{mb_strlen($product['item']['name'],'UTF-8') > 40 ? mb_substr($product['item']['name'],0,40,'UTF-8').'...' : $product['item']['name']}}
                                             </a>
                                             @if(!empty($product['size']) || !empty($product['color']))
                                                <div class="small text-muted mt-1">
                                                   @if(!empty($product['size'])) <span>{{ __('Size') }}: {{ $product['size'] }}</span> @endif
                                                   @if(!empty($product['color'])) <span class="ml-2">{{ __('Color') }}: <i class="fas fa-circle" style="color:#{{$product['color']}}"></i></span> @endif
                                                </div>
                                             @endif
                                          </div>
                                       </div>
                                    </td>
                                    <td class="border-bottom text-center py-3">{{$product['qty']}}</td>
                                    <td class="border-bottom text-right py-3 font-weight-bold">
                                       {{ PriceHelper::showCurrencyPrice(($product['item_price'] ?? 0) * $product['qty'] * $order->currency_value) }}
                                    </td>
                                 </tr>
                                 @endforeach
                              </tbody>
                           </table>
                        </div>
                     </div>
                     
                     <div class="mt-4 text-center">
                        <a class="btn btn-outline-primary px-4 rounded-pill" href="{{ route('user-orders') }}"> <i class="fas fa-arrow-left mr-2"></i>{{ __('Back to Orders') }}</a>
                     </div>
                  </div>
               </div>

               <!-- RIGHT COLUMN: Price Details card -->
               <div class="col-lg-4">
                  <div class="price-details-card bg-white rounded shadow-sm mb-4 overflow-hidden border">
                     <div class="card-header bg-dark text-white p-3 border-0">
                        <h5 class="mb-0 font-weight-bold text-uppercase small" style="letter-spacing: 1px;"><i class="fas fa-receipt mr-2"></i>{{ __('Price Details') }}</h5>
                     </div>
                     <div class="card-body p-4">
                        <ul class="list-unstyled mb-0">
                           <li class="d-flex justify-content-between mb-3">
                              <span class="text-secondary">{{ __('Subtotal') }}</span>
                              <span class="text-dark">
                                 {{ PriceHelper::showOrderCurrencyPrice(($order->pay_amount - $order->shipping_cost - $order->packing_cost - ($order->tax / $order->currency_value)) * $order->currency_value, $order->currency_sign) }}
                              </span>
                           </li>
                           <li class="d-flex justify-content-between mb-3">
                              <span class="text-secondary">{{ __('Delivery Fee') }}</span>
                              <span class="text-dark">+ {{ PriceHelper::showOrderCurrencyPrice($order->shipping_cost * $order->currency_value, $order->currency_sign) }}</span>
                           </li>
                           <li class="d-flex justify-content-between mb-3">
                              <span class="text-secondary">{{ __('Packaging') }}</span>
                              <span class="text-dark">+ {{ PriceHelper::showOrderCurrencyPrice($order->packing_cost * $order->currency_value, $order->currency_sign) }}</span>
                           </li>
                           <li class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                              <span class="text-secondary">{{ __('Tax') }}</span>
                              <span class="text-dark">+ {{ PriceHelper::showOrderCurrencyPrice($order->tax, $order->currency_sign) }}</span>
                           </li>
                           <li class="d-flex justify-content-between mt-3 pt-2">
                              <h5 class="font-weight-bold text-dark">{{ __('Total Paid') }}</h5>
                              <h5 class="font-weight-bold text-primary">
                                 {{ PriceHelper::showOrderCurrencyPrice(($order->pay_amount * $order->currency_value),$order->currency_sign) }}
                              </h5>
                           </li>
                        </ul>
                     </div>
                     <div class="card-footer bg-light p-4 border-0">
                        <h6 class="font-weight-bold text-uppercase small text-secondary mb-3">{{ __('Payment Information') }}</h6>
                        <div class="d-flex justify-content-between mb-2">
                           <span class="text-secondary small">{{ __('Method') }}</span>
                           <span class="text-dark font-weight-bold small">{{$order->method}}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                           <span class="text-secondary small">{{ __('Status') }}</span>
                           @if($order->payment_status == 'Pending')
                              <span class="badge badge-warning text-dark px-2 py-1 small">{{ __('Unpaid') }}</span>
                           @else
                              <span class="badge badge-success px-2 py-1 small">{{ __('Paid') }}</span>
                           @endif
                        </div>
                        
                        <div class="mt-4">
                           <a href="{{route('user-order-print',$order->id)}}" target="_blank" class="btn btn-outline-dark btn-block btn-sm rounded-pill font-weight-bold">
                              <i class="fa fa-print mr-1"></i> {{ __('Print Invoice') }}
                           </a>
                        </div>
                     </div>
                  </div>

                  <div class="card border-0 rounded shadow-sm bg-white p-3">
                     <p class="text-muted small text-center mb-0">
                        {{ __('Order Date') }}: <strong>{{date('d M, Y',strtotime($order->created_at))}}</strong>
                     </p>
                  </div>
               </div>

            </div>
         </div>
      </div>
   </div>
</div>

<style>
   .widget {
      box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
   }
   .card-header {
      background: #1a1a1a;
   }
   .font-weight-bold {
      font-weight: 700 !important;
   }
   .rounded-lg {
      border-radius: 1rem !important;
   }
</style>

@includeIf('partials.global.common-footer')
@endsection