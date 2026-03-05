@if(isset($order))
<div class="tracking-steps-area">
    @php
        $deliveryJob = $order->deliveryJob;
    @endphp

    @if($deliveryJob)
        <div class="delivery-job-tracking mb-4">
            <h5 class="mb-3"><i class="fas fa-truck"></i> {{ __('Live Delivery Progress') }}</h5>
            <div class="job-status-badge mb-3">
                <span class="badge badge-{{ $deliveryJob->status == 'delivered' ? 'success' : 'primary' }}">
                    {{ strtoupper(str_replace('_', ' ', $deliveryJob->status)) }}
                </span>
            </div>

            <div class="stops-timeline">
                <ul class="tracking-steps">
                    @foreach($deliveryJob->stops->sortBy('pickup_sequence') as $stop)
                        <li class="{{ in_array($stop->status, ['arrived', 'picked_up', 'delivered']) ? 'active' : '' }}">
                            <div class="icon">
                                @if($stop->type == 'pickup')
                                    <i class="fas fa-store"></i>
                                @else
                                    <i class="fas fa-home"></i>
                                @endif
                            </div>
                            <div class="content">
                                <h4 class="title">
                                    @if($stop->type == 'pickup')
                                        {{ __('Pickup from') }} {{ $stop->seller->shop_name }}
                                    @else
                                        {{ __('Delivery to You') }}
                                    @endif
                                </h4>
                                <p class="details">
                                    Status: <strong>{{ ucwords(str_replace('_', ' ', $stop->status)) }}</strong>
                                    @if($stop->arrived_at) <br><small>Arrived: {{ $stop->arrived_at->diffForHumans() }}</small> @endif
                                    @if($stop->picked_up_at) <br><small>Picked Up: {{ $stop->picked_up_at->diffForHumans() }}</small> @endif
                                </p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if($deliveryJob->rider)
                <div class="rider-info mt-4 p-3 bg-white border rounded">
                    <h6><i class="fas fa-user-circle"></i> {{ __('Your Rider') }}</h6>
                    <p class="mb-0"><strong>{{ $deliveryJob->rider->name }}</strong></p>
                    <p class="small text-muted">{{ __('Vehicle') }}: {{ $deliveryJob->rider->vehicle_type ?? 'N/A' }}</p>
                </div>
            @endif
        </div>
    @else
        {{-- Fallback to legacy tracking if no delivery job --}}
        <ul class="tracking-steps">
            @foreach($order->tracks as $track)
                <li class="{{ in_array($track->title, $datas) ? 'active' : '' }}">
                    <div class="icon">{{ $loop->index + 1 }}</div>
                    <div class="content">
                            <h4 class="title">{{ ucwords($track->title)}}</h4>
                            <p class="date">{{ date('d m Y',strtotime($track->created_at)) }}</p>
                            <p class="details">{{ $track->text }}</p>
                    </div>
                </li>
            @endforeach
        </ul>
    @endif
</div>
@else
    <h3 class="text-center">{{ __('No Order Found.') }}</h3>
@endif
