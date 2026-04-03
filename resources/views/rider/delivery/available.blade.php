@extends('layouts.front')
@php use App\Helpers\PriceHelper; @endphp
@section('css')
<link rel="stylesheet" href="{{asset('assets/front/css/datatables.css')}}">
@endsection
@section('content')
@include('partials.global.common-header')

<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('Available Delivery Jobs') }}</h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('rider-dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Available Jobs') }}</li>
                    </ol>
                </nav>
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
                <div class="row table-responsive-lg mt-3">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-30 widget_categories bg-light account-info table-responsive">
                            <h4 class="widget-title down-line mb-30">{{ __('Available Delivery Jobs') }}</h4>

                            @include('alerts.form-success')
                            @include('alerts.form-error')

                            <table class="table order-table" cellspacing="0" id="example" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order #') }}</th>
                                        <th>{{ __('Earnings') }}</th>
                                        <th>{{ __('Stops') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobs as $job)
                                        <tr>
                                            <td>{{ $job->order->order_number }}</td>
                                            <td>{{ PriceHelper::showOrderCurrencyPrice($job->rider_earnings, $job->order->currency_sign) }}</td>
                                            <td>{{ $job->stops->count() }} {{ __('Stops') }}</td>
                                            <td>
                                                <a href="{{ route('rider-delivery-accept', $job->id) }}" class="mybtn1 sm1">
                                                    {{ __('Accept Job') }}
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

@includeIf('partials.global.common-footer')
@endsection

@section('script')
<script src="{{ asset('assets/front/js/dataTables.min.js') }}" defer></script>
<script src="{{ asset('assets/front/js/user.js') }}" defer></script>
@endsection
