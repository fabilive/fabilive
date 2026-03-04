@extends('layouts.front')
@section('css')
<link rel="stylesheet" href="{{asset('assets/front/css/datatables.css')}}">
@endsection
@section('content')
@include('partials.global.common-header')

<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
    style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('My Orders') }}</h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('My Orders') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->

<!--==================== Blog Section Start ====================-->
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
                <div class="row table-responsive-lg mt-3">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-30 widget_categories bg-light account-info table-responsive">
                            <h4 class="widget-title down-line mb-30">{{ __('My Orders') }}</h4>

                            <table class="table order-table" cellspacing="0" id="example" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('#Order') }}</th>
                                        <th>{{ __('Shipping Price') }}</th>
                                        <!--<th>{{ __('Service Area') }}</th>-->
                                        <th>{{ __('Pickup Point') }}</th>
                                        <th>{{ __('Phone Number') }}</th>

                                        <th>{{ __('Order Status') }}</th>
                                        <th>{{ __('View') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                @foreach($orders as $order)

    <tr>
        <td data-label="{{ __('#Order') }}">
            {{ $order->order->order_number }}
        </td>

        <td data-label="{{ __('Shipping Price') }}">
            {{ number_format($order->order->total_delivery_fee, 2) }}
        </td>



        <td data-label="{{ __('Pickup Point') }}">
            <p>{{ $order->order->pickup_location  }}</p>
        </td>

        <td data-label="{{ __('Phone Number') }}">
            <p>{{ $order->phone_number ?? 'N/A' }}</p>
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
<!--==================== Blog Section End ====================-->

@includeIf('partials.global.common-footer')

@endsection
@section('script')
<script src="{{ asset('assets/front/js/dataTables.min.js') }}" defer></script>
<script src="{{ asset('assets/front/js/user.js') }}" defer></script>
@endsection