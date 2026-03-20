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
                                                <p class="mb-0 text-muted small"><i class="fas fa-map-marker-alt"></i> {{ $stop->location_text }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            @if($stop->type == 'pickup')
                                                <div class="d-flex flex-column align-items-end">
                                                    <div class="mb-2">
                                                        <a href="tel:{{ $stop->seller->phone }}" class="btn btn-sm btn-outline-success shadow-sm" title="{{ __('Call Seller') }}">
                                                            <i class="fas fa-phone"></i> {{ $stop->seller->phone }}
                                                        </a>
                                                        @php
                                                            $sellerThread = $job->chatThreads->where('thread_type', 'rider_seller')->where('seller_id', $stop->seller_id)->first();
                                                        @endphp
                                                        @if($sellerThread)
                                                            <a href="{{ route('rider-delivery-chat', $sellerThread->id) }}" class="btn btn-sm btn-outline-primary shadow-sm" title="{{ __('Chat with Seller') }}">
                                                                <i class="fas fa-comments"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                            <p class="mb-0"><strong>{{ ucwords(str_replace('_', ' ', $stop->status)) }}</strong></p>
                                        </div>
                                    </div>

                                    @if($isNext)
                                        <div class="mt-3 action-buttons">
                                            @if($stop->status == 'pending')
                                                <button onclick="updateStop('{{ $stop->id }}', 'arrived')" class="btn btn-info btn-sm">
                                                    <i class="fas fa-map-marker-alt"></i> {{ __('I have Arrived') }}
                                                </button>
                                            @elseif($stop->status == 'arrived')
                                                @if($stop->type == 'pickup')
                                                    <button onclick="updateStop('{{ $stop->id }}', 'picked_up')" class="btn btn-success btn-sm">
                                                        <i class="fas fa-check-circle"></i> {{ __('Confirm Pickup') }}
                                                    </button>
                                                @else
                                                    <div class="btn-group">
                                                        <button onclick="updateStop('{{ $stop->id }}', 'delivered')" class="btn btn-success btn-sm mr-2">
                                                            <i class="fas fa-check-double"></i> {{ __('Confirm Delivery') }}
                                                        </button>
                                                        <button onclick="updateStop('{{ $stop->id }}', 'failed')" class="btn btn-danger btn-sm">
                                                            <i class="fas fa-times-circle"></i> {{ __('Delivery Failed') }}
                                                        </button>
                                                    </div>
                                                @endif
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Handle Return to Seller --}}
                                    @if($stop->type == 'dropoff' && $stop->status == 'failed' && $job->status != 'returned')
                                        <div class="mt-3 p-3 bg-light-danger border rounded">
                                            <p class="mb-2 text-danger"><strong>{{ __('Delivery failed.') }}</strong> {{ __('Please initiate return to seller if you cannot reach the buyer.') }}</p>
                                            <button onclick="updateJobStatus('{{ $job->id }}', 'returning')" class="btn btn-warning btn-sm">
                                                <i class="fas fa-undo"></i> {{ __('Initiate Return to Seller') }}
                                            </button>
                                        </div>
                                    @endif

                                    @if($stop->type == 'pickup' && $job->status == 'returning' && $stop->status != 'returned')
                                        <div class="mt-3">
                                            <button onclick="updateStop('{{ $stop->id }}', 'returned')" class="btn btn-primary btn-sm">
                                                <i class="fas fa-store"></i> {{ __('Confirm Return to Seller') }}
                                            </button>
                                        </div>
                                    @endif
                                    
                                    @if($stop->type == 'dropoff')
                                        <div class="mt-3 p-3 bg-white border rounded shadow-sm">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h6 class="mb-0"><i class="fas fa-user-tie text-primary"></i> {{ __('Buyer Details') }}</h6>
                                                @php
                                                    $buyerThread = $job->chatThreads->where('thread_type', 'rider_buyer')->first();
                                                @endphp
                                                @if($buyerThread)
                                                    <a href="{{ route('rider-delivery-chat', $buyerThread->id) }}" class="btn btn-sm btn-outline-primary shadow-sm">
                                                        <i class="fas fa-comments"></i> {{ __('Chat with Buyer') }}
                                                    </a>
                                                @endif
                                            </div>
                                            <div class="pl-4">
                                                <p class="mb-1"><strong>{{ $job->order->customer_name }}</strong></p>
                                                <p class="mb-1"><i class="fas fa-phone-alt text-success"></i> <a href="tel:{{ $job->order->customer_phone }}">{{ $job->order->customer_phone }}</a></p>
                                                <p class="mb-0 small"><i class="fas fa-map-pin text-danger"></i> {{ $job->order->customer_address }}</p>
                                            </div>
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
        if(confirm('Are you sure you want to update status to ' + status.replace('_', ' ') + '?')) {
            let baseUrl = "{{ url('/rider/delivery/stop') }}";
            let form = document.getElementById('status-update-form');
            form.action = baseUrl + '/' + stopId;
            document.getElementById('target-status').value = status;
            form.submit();
        }
    }

    function updateJobStatus(jobId, status) {
        if(confirm('Are you sure you want to ' + status.replace('_', ' ') + '?')) {
            let baseUrl = "{{ url('/rider/delivery/job/status') }}";
            let form = document.getElementById('job-status-update-form');
            form.action = baseUrl + '/' + jobId;
            document.getElementById('job-target-status').value = status;
            form.submit();
        }
    }
</script>

<form id="job-status-update-form" action="" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="job-target-status">
</form>
@endsection
