@extends('layouts.front')
@section('css')
<style>
    .stop-card { border-left: 5px solid #ccc; margin-bottom: 15px; transition: all 0.3s; }
    .stop-card.active { border-left-color: #2d3274; background-color: #f8f9ff; }
    .stop-card.completed { border-left-color: #28a745; opacity: 0.8; }
    .stop-number { width: 30px; height: 30px; background: #2d3274; color: #fff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-right: 10px; }
</style>
@endsection
@section('content')
@include('partials.global.common-header')

<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('Manage Delivery Job') }} #{{ $job->order->order_number }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="full-row">
    <div class="container">
        <div class="row">
            <div class="col-xl-3">
                @include('partials.rider.dashboard-sidebar')
            </div>
            <div class="col-xl-9">
                <div class="widget border-0 p-30 bg-light account-info">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="widget-title down-line">{{ __('Delivery Route Stops') }}</h4>
                        <span class="badge badge-primary p-2">{{ strtoupper(str_replace('_', ' ', $job->status)) }}</span>
                    </div>

                    @include('alerts.form-success')
                    @include('alerts.form-error')

                    <div class="stops-container">
                        @foreach($job->stops->sortBy('pickup_sequence') as $stop)
                            @php
                                $isNext = ($job->status == 'assigned' && $loop->first) || ($job->status == 'picking_up' && $stop->status != 'picked_up' && !isset($picking_up_started)) || ($job->status == 'delivering' && $stop->type == 'delivery');
                                if($isNext) $picking_up_started = true;
                            @endphp
                            <div class="card stop-card {{ $isNext ? 'active' : '' }} {{ in_array($stop->status, ['picked_up', 'delivered']) ? 'completed' : '' }}">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <span class="stop-number">{{ $loop->iteration }}</span>
                                            <div>
                                                <h5 class="mb-1">
                                                    @if($stop->type == 'pickup')
                                                        {{ __('Pickup') }}: {{ $stop->seller->shop_name }}
                                                    @else
                                                        {{ __('Delivery to Customer') }}
                                                    @endif
                                                </h5>
                                                <p class="mb-0 text-muted small"><i class="fas fa-map-marker-alt"></i> {{ $stop->address }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @if($stop->type == 'pickup' && $stop->status != 'picked_up')
                                                <a href="tel:{{ $stop->seller->phone }}" class="btn btn-sm btn-outline-primary mb-1"><i class="fas fa-phone"></i></a>
                                                {{-- Add Chat Link Here later --}}
                                            @endif
                                            <p class="mb-0"><strong>{{ ucwords(str_replace('_', ' ', $stop->status)) }}</strong></p>
                                        </div>
                                    </div>

                                    @if($isNext)
                                        <div class="mt-3 action-buttons">
                                            @if($stop->status == 'pending')
                                                <button onclick="updateStop('{{ $stop->id }}', 'arrived')" class="btn btn-info btn-sm">
                                                    {{ __('I have Arrived') }}
                                                </button>
                                            @elseif($stop->status == 'arrived')
                                                @if($stop->type == 'pickup')
                                                    <button onclick="updateStop('{{ $stop->id }}', 'picked_up')" class="btn btn-success btn-sm">
                                                        {{ __('Confirm Pickup') }}
                                                    </button>
                                                @else
                                                    <button onclick="updateStop('{{ $stop->id }}', 'delivered')" class="btn btn-success btn-sm">
                                                        {{ __('Confirm Delivery') }}
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                    
                                    @if($stop->type == 'dropoff' && $job->status == 'assigned')
                                        <div class="mt-3 p-2 bg-white border rounded">
                                            <h6><i class="fas fa-user"></i> {{ __('Buyer Details') }}</h6>
                                            <p class="mb-1"><strong>{{ $job->order->customer_name }}</strong></p>
                                            <p class="mb-1"><i class="fas fa-phone"></i> {{ $job->order->customer_phone }}</p>
                                            <p class="mb-0 small"><i class="fas fa-map-marker-alt"></i> {{ $job->order->customer_address }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="order-summary mt-5 p-3 bg-white border rounded">
                        <h5>{{ __('Order Summary') }}</h5>
                        <p>{{ __('Total Items') }}: {{ $job->order->total_qty }}</p>
                        <p>{{ __('Your Earning') }}: <strong>{{ \PriceHelper::showOrderCurrencyPrice($job->rider_earnings, $job->order->currency_sign) }}</strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@includeIf('partials.global.common-footer')

{{-- Hidden Form for status updates --}}
<form id="status-update-form" action="" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="target-status">
</form>

@endsection

@section('script')
<script>
    function updateStop(stopId, status) {
        if(confirm('Are you sure you want to update status to ' + status + '?')) {
            let baseUrl = "{{ url('/rider/delivery/stop') }}";
            let form = document.getElementById('status-update-form');
            form.action = baseUrl + '/' + stopId;
            document.getElementById('target-status').value = status;
            form.submit();
        }
    }
</script>
@endsection
