@extends('layouts.front')
@php use App\Helpers\PriceHelper; @endphp
@section('css')
<link rel="stylesheet" href="{{asset('assets/front/css/datatables.css')}}">
@endsection
@section('content')
@include('partials.global.common-header')
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Dashboard') }}</h3>
         </div>
      </div>
   </div>
</div>
<div class="full-row">
   <div class="container">
      <div class="mb-4 d-xl-none">
         <button class="dashboard-sidebar-btn btn bg-primary rounded">
            <i class="fas fa-bars"></i>
         </button>
      </div>
      <div class="row">
         <div class="col-xl-3">
            @include('partials.rider.dashboard-sidebar')
         </div>
         <div class="col-xl-9">
            @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <strong>{{__('Success')}}</strong> {{Session::get('success')}}
            </div>
            @endif
            <div class="row">
               <div class="col-lg-6">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30">{{ __('Account Information') }}</h4>
                     <div class="user-info">
                        <h5 class="title">{{ $user->name }}</h5>
                        <p><span class="user-title">{{ __('Email') }}:</span> {{ $user->email }}</p>
                        @if($user->phone != null)
                        <p><span class="user-title">{{ __('Phone') }}:</span> {{ $user->phone }}</p>
                        @endif
                        @if($user->fax != null)
                        <p><span class="user-title">{{ __('Fax') }}:</span> {{ $user->fax }}</p>
                        @endif
                        @if($user->city != null)
                        <p><span class="user-title">{{ __('City') }}:</span> {{ $user->city->city_name }}</p>
                        @endif
                        @if($user->zip != null)
                        <p><span class="user-title">{{ __('Zip') }}:</span> {{ $user->zip }}</p>
                        @endif
                        @if($user->address != null)
                        <p><span class="user-title">{{ __('Address') }}:</span> {{ $user->address }}</p>
                        @endif
                     </div>
                  </div>
               </div>
                <div class="col-lg-6">
                   <div class="widget border-0 p-30 widget_categories bg-light account-info">
                      <h4 class="widget-title down-line mb-30">{{ __('My Wallet & Stats') }}</h4>
                      <div class="user-info">
                         <h5 class="title">{{ __('Current Balance') }}: <span class="float-right">{{ App\Models\Product::vendorConvertPrice($user->balance) }}</span></h5>
                         <p><span class="user-title">{{ __('Completed Deliveries') }}:</span> <span class="float-right">{{ $total_deliveries }}</span></p>
                         <p><span class="user-title">{{ __('Active Jobs') }}:</span> <span class="float-right">{{ $active_jobs_count }}</span></p>
                         <hr>
                      </div>
                   </div>
                </div>

            </div>
            <div class="row mt-3">
               <div class="col-lg-12">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30">{{ __('Available Delivery Jobs') }}</h4>
                     <div class="table-responsive">
                        <table class="table order-table" cellspacing="0" width="100%">
                           <thead>
                              <tr>
                                 <th>{{ __('Order #') }}</th>
                                 <th>{{ __('Earnings') }}</th>
                                 <th>{{ __('Stops') }}</th>
                                 <th>{{ __('Action') }}</th>
                              </tr>
                           </thead>
                           <tbody>
                              @forelse($available_jobs as $job)
                              <tr>
                                 <td>{{ $job->order->order_number }}</td>
                                 <td>{{ PriceHelper::showOrderCurrencyPrice($job->rider_earnings, $job->order->currency_sign) }}</td>
                                 <td>{{ $job->stops->count() }} {{ __('Stops') }}</td>
                                 <td>
                                    <a href="{{ route('rider-delivery-accept', $job->id) }}" class="mybtn1 sm1">
                                       {{ __('Accept') }}
                                    </a>
                                    <a href="{{ route('rider-delivery-details', $job->id) }}" class="mybtn1 sm1 bg-info">
                                       {{ __('View') }}
                                    </a>
                                 </td>
                              </tr>
                              @empty
                              <tr>
                                 <td colspan="4" class="text-center">{{ __('No available jobs in your service area.') }}</td>
                              </tr>
                              @endforelse
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>

            <div class="row table-responsive-lg mt-3">
               <div class="col-lg-12">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30">{{ __('Recent Orders') }}</h4>
                     <div class="table-responsive">
                        <table class="table order-table" cellspacing="0" width="100%">
    <thead>
        <tr>
            <th>{{ __('#Order') }}</th>
            <th>{{ __('Shipping Price') }}</th>
            <th>{{ __('Pickup Point') }}</th>
            <th>{{ __('Phone Number') }}</th>
            <th>{{ __('Order Total') }}</th>
            <th>{{ __('Order Status') }}</th>
            <th>{{ __('View') }}</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
            @php
                $vendorPackingJson = optional($order->order)->vendor_packing_id;
                $order_package     = $vendorPackingJson ? json_decode($vendorPackingJson, true) : [];
                $vendor_package_id = $order_package[$order->vendor_id] ?? null;
                $package           = $vendor_package_id ? \App\Models\Package::find($vendor_package_id) : null;
                $shipping_cost  = optional($order->order)->shipping_cost ?? 0;
                $packing_cost   = $package?->price ?? 0;
                $extra_price    = $shipping_cost + $packing_cost;
                $order_subtotal = optional($order->order)->vendororders
                                    ? optional($order->order)->vendororders->where('user_id', $order->vendor_id)->sum('price')
                                    : 0;
                $pay_amount = optional($order->order)->pay_amount ?? 0;
                $commission = optional($order->order)->commission ?? 0;
                $currency   = optional($order->order)->currency_value ?? 1;
                $total = ($pay_amount - $commission) * $currency;
            @endphp
            <tr>
                <td data-label="{{ __('#Order') }}">
                    {{ optional($order->order)->order_number ?? 'N/A' }}
                </td>
                <td data-label="{{ __('Shipping Price') }}">
                    {{ number_format($order->order->total_delivery_fee, 2) }}
                </td>
                <td data-label="{{ __('Pickup Point') }}">
                    <p>{{ optional($order->pickup)->location ?? 'N/A' }}</p>
                </td>
                <td data-label="{{ __('Phone Number') }}">
                    @if($order->phone_number)
                        @php
                            $local = preg_replace('/[^0-9]/', '', $order->phone_number);
                            // Cameroon numbers usually start with 6 and are 9 digits long
                            $wa_number = (str_starts_with($local, '6') && strlen($local) === 9)
                                ? '237' . $local
                                : $local;
                        @endphp

                        <a href="tel:{{ $order->phone_number }}" style="display:block; color:blue;">
                            📞 {{ $order->phone_number }}
                        </a>
                    @else
                        <p>N/A</p>
                    @endif
                </td>
                <td data-label="{{ __('Order Total') }}">
                    {{ PriceHelper::showOrderCurrencyPrice($total, $order->currency_sign) }}
                </td>
                <td data-label="{{ __('Order Status') }}">
                    <span class="badge badge-dark p-2">{{ ucwords($order->status) }}</span>
                </td>
                <td data-label="{{ __('View') }}">
                    <a class="mybtn1 sm1" href="{{ route('rider-order-details', $order->id) }}">
                        {{ __('View Order') }}
                    </a>
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
@includeIf('partials.global.common-footer')
@endsection