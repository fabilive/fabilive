@extends('layouts.front')

@section('content')
<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li>
                        <a href="{{ route('front.index') }}">{{ $langg->lang17 }}</a>
                    </li>
                    <li>
                        <a href="{{ route('front.flash-sales') }}">{{ __('Flash Sales') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb Area End -->

<!-- Flash Sales Section -->
<section class="flash-sales-page mt-4 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <div class="flash-header" style="background: linear-gradient(135deg, #ff4d4d, #ff6b6b); padding: 30px; border-radius: 10px; color: white;">
                    <h2 class="mb-2" style="font-weight: bold; color: white;">⚡ {{ __('FLASH SALES') }} ⚡</h2>
                    <h5 class="mb-3" style="color: #fff;">{{ __('Everyday top deals with massive discounts!') }}</h5>
                    <div class="countdown-timer d-inline-block" style="background: rgba(0,0,0,0.2); padding: 10px 20px; border-radius: 5px;">
                        <span style="font-size: 18px; margin-right: 15px;">{{ __('Time Left For This Sale:') }}</span>
                        <div id="flash-countdown" style="display: inline-block; font-size: 24px; font-weight: bold; letter-spacing: 2px;">
                            @if($selectedSlot && now()->format('H:i:s') <= $selectedSlot->end_time && now()->format('H:i:s') >= $selectedSlot->start_time)
                                <span class="flash-timer" data-end="{{ \Carbon\Carbon::parse(\Carbon\Carbon::today()->format('Y-m-d') . ' ' . $selectedSlot->end_time)->format('Y-m-d H:i:s') }}">00:00:00</span>
                            @elseif($selectedSlot && now()->format('H:i:s') < $selectedSlot->start_time)
                                <span class="flash-timer" data-end="{{ \Carbon\Carbon::parse(\Carbon\Carbon::today()->format('Y-m-d') . ' ' . $selectedSlot->start_time)->format('Y-m-d H:i:s') }}">{{ __('Starts in') }} 00:00:00</span>
                            @else
                                <span>{{ __('Sale Ended') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12">
                <div class="flash-tabs" style="display: flex; overflow-x: auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 10px;">
                    @foreach($timeSlots as $slot)
                    @php
                        $isLive = now()->format('H:i:s') >= $slot->start_time && now()->format('H:i:s') <= $slot->end_time;
                        $isUpcoming = now()->format('H:i:s') < $slot->start_time;
                        $isEnded = now()->format('H:i:s') > $slot->end_time;
                        $statusText = $isLive ? __('Sale is Live') : ($isUpcoming ? __('Upcoming') : __('Ended'));
                        
                        $activeClass = ($selectedSlot && $selectedSlot->id == $slot->id) ? 'active-slot' : '';
                    @endphp
                    <a href="{{ route('front.flash-sales', ['slot' => $slot->id]) }}" class="flash-tab-item {{ $activeClass }}" style="flex: 1; text-align: center; padding: 15px 20px; text-decoration: none; border-radius: 8px; margin: 0 5px; min-width: 150px; transition: 0.3s; {{ $activeClass ? 'background: #ff4d4d; color: white;' : 'background: #f8f9fa; color: #333;' }}">
                        <h4 style="margin: 0; font-weight: bold; {{ $activeClass ? 'color: white;' : '' }}">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }}</h4>
                        <span style="font-size: 14px; opacity: 0.9;">{{ $statusText }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="row">
            @if($flashProducts->count() > 0)
                @foreach($flashProducts as $flashItem)
                @php 
                    $prod = $flashItem->product; 
                    if(!$prod) continue;
                    $percent = 0;
                    if($prod->previous_price > 0){
                        $percent = round((($prod->previous_price - $flashItem->flash_price) / $prod->previous_price) * 100);
                    }
                    $itemsLeft = $flashItem->flash_quantity - $flashItem->sold_quantity;
                    $progressWidth = ($flashItem->sold_quantity / max($flashItem->flash_quantity, 1)) * 100;
                    if($progressWidth > 100) $progressWidth = 100;
                @endphp
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card flash-card h-100" style="border: 1px solid #eee; border-radius: 8px; overflow: hidden; transition: 0.3s;">
                        <a href="{{ route('front.product', $prod->slug) }}" style="display: block; position: relative;">
                            @if($percent > 0)
                                <span class="badge" style="position: absolute; top: 10px; right: 10px; background: #ff4d4d; color: white; padding: 5px 10px; border-radius: 3px; font-weight: bold; z-index: 2;">-{{ $percent }}%</span>
                            @endif
                            <img src="{{ filter_var($prod->photo, FILTER_VALIDATE_URL) ? $prod->photo : asset('assets/images/products/'.$prod->photo) }}" class="card-img-top" alt="{{ $prod->name }}" style="height: 200px; object-fit: contain; padding: 15px;">
                        </a>
                        <div class="card-body" style="padding: 15px;">
                            <h5 class="card-title" style="font-size: 14px; font-weight: 600; height: 40px; overflow: hidden; color: #333;">
                                <a href="{{ route('front.product', $prod->slug) }}" style="color: inherit; text-decoration: none;">
                                    {{ strlen($prod->name) > 40 ? substr($prod->name,0,40).'...' : $prod->name }}
                                </a>
                            </h5>
                            <div class="price-box mb-2">
                                <h4 style="color: #ff4d4d; font-weight: 700; margin-bottom: 5px; font-size: 18px;">{{ \PriceHelper::showCurrencyPrice($flashItem->flash_price) }}</h4>
                                @if($prod->previous_price > 0)
                                    <span style="text-decoration: line-through; color: #999; font-size: 14px;">{{ $prod->showPreviousPrice() }}</span>
                                @endif
                            </div>
                            
                            <div class="flash-stock-info mt-3">
                                <div class="d-flex justify-content-between mb-1" style="font-size: 12px; color: #666; font-weight: bold;">
                                    <span>{{ $itemsLeft }} {{ __('items left') }}</span>
                                </div>
                                <div class="progress" style="height: 8px; border-radius: 4px; background-color: #f1f1f1;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ $progressWidth }}%; background-color: #ff4d4d;" aria-valuenow="{{ $progressWidth }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>

                            <div class="mt-3 text-center">
                                @if($isUpcoming)
                                    <button class="btn btn-secondary w-100" disabled style="background: #ccc; border: none;">{{ __('Upcoming') }}</button>
                                @elseif($isEnded || $itemsLeft <= 0)
                                    <button class="btn btn-secondary w-100" disabled style="background: #ccc; border: none;">{{ __('Sold Out') }}</button>
                                @else
                                    <a href="{{ route('front.product', $prod->slug) }}" class="btn w-100" style="background: #ff4d4d; color: white; font-weight: bold; border-radius: 5px;">{{ __('BUY NOW') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div class="col-12 text-center" style="padding: 50px 0;">
                    <img src="{{ asset('assets/images/no-results.png') }}" style="max-width: 150px; margin-bottom: 20px; opacity: 0.5;">
                    <h4 style="color: #666;">{{ __('No products available in this time slot.') }}</h4>
                </div>
            @endif
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        var flashTimer = $('.flash-timer');
        if (flashTimer.length > 0) {
            var endTime = new Date(flashTimer.data('end')).getTime();
            
            var x = setInterval(function() {
                var now = new Date().getTime();
                var distance = endTime - now;
                
                if (distance < 0) {
                    clearInterval(x);
                    flashTimer.html("{{ __('00:00:00') }}");
                    // Optionally reload page to update slot status
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                    return;
                }
                
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                hours = (hours < 10) ? "0" + hours : hours;
                minutes = (minutes < 10) ? "0" + minutes : minutes;
                seconds = (seconds < 10) ? "0" + seconds : seconds;
                
                var text = flashTimer.text().includes('Starts in') ? '{{ __('Starts in') }} ' : '';
                flashTimer.html(text + hours + "h " + minutes + "m " + seconds + "s");
            }, 1000);
        }
    });
</script>
<style>
    .flash-tab-item:hover {
        background: #ffe6e6 !important;
        color: #ff4d4d !important;
    }
    .flash-tab-item.active-slot:hover {
        background: #ff4d4d !important;
        color: white !important;
    }
    .flash-card:hover {
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    .flash-tabs::-webkit-scrollbar {
        height: 6px;
    }
    .flash-tabs::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 3px;
    }
    .flash-tabs::-webkit-scrollbar-thumb {
        background: #ccc; 
        border-radius: 3px;
    }
    .flash-tabs::-webkit-scrollbar-thumb:hover {
        background: #999; 
    }
</style>
@endsection
