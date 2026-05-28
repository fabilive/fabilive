@extends('layouts.front')

@section('content')
@includeIf('partials.global.common-header')
<!-- Breadcrumb Area Start -->
<div class="breadcrumb-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <ul class="pages">
                    <li>
                        <a href="{{ route('front.index') }}">{{ __('Home') }}</a>
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
        <!-- HUGE BLACK BANNER -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="flash-banner" style="background-color: #000; border-radius: 8px; padding: 40px; text-align: center; color: white;">
                    <div class="d-flex flex-wrap justify-content-center align-items-center">
                        <i class="fas fa-bolt" style="color: #ffcc00; font-size: 80px; margin-right: 20px;"></i>
                        <div class="text-left" style="text-align: left;">
                            <h1 style="font-size: 50px; font-weight: 900; margin: 0; line-height: 1; letter-spacing: -2px; color: white;">FLASH<br>SALES</h1>
                        </div>
                        <div style="margin-left: 30px; text-align: left; border-left: 2px solid rgba(255,255,255,0.2); padding-left: 30px;" class="d-none d-md-block">
                            <h2 style="font-size: 26px; font-weight: 300; margin: 0; color: white;">Everyday deals you<br>don't want to miss</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- SIDEBAR -->
            @includeIf('partials.catalog.catalog')

            <!-- MAIN CONTENT -->
            <div class="col-xl-9">
                <div class="mb-4 d-xl-none">
                    <button class="dashboard-sidebar-btn btn bg-primary rounded">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
                
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="flash-header d-flex justify-content-between align-items-center" style="background-color: #cb202d; padding: 15px 20px; border-radius: 5px 5px 0 0; color: white;">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-bolt" style="color: #ffcc00; font-size: 24px; margin-right: 10px;"></i>
                                <h3 class="mb-0" style="font-weight: 700; color: white; font-size: 20px;">{{ __('Flash Sales') }} <span style="font-size: 14px; font-weight: 400; opacity: 0.9;">({{ $flashProducts->count() }} {{ __('products found') }})</span></h3>
                            </div>
                            <div>
                                <span style="font-weight: 600; font-size: 14px; cursor: pointer;">{{ __('Sort by: Popularity') }} <i class="fas fa-chevron-down" style="font-size: 10px; margin-left: 5px;"></i></span>
                            </div>
                        </div>
                        
                        <div class="flash-timeline" style="background: white; padding: 15px 20px; border-radius: 0 0 5px 5px; border: 1px solid #eee; border-top: none; display: flex; overflow-x: auto; align-items: center; white-space: nowrap;">
                            @if($selectedSlot)
                                <div style="color: #cb202d; font-weight: 600; font-size: 15px; margin-right: 30px; padding-right: 30px; border-right: 1px solid #eee; display: inline-block;">
                                    {{ __('Time Left:') }} 
                                    <span class="flash-timer" data-end="{{ \Carbon\Carbon::parse(\Carbon\Carbon::today()->format('Y-m-d') . ' ' . $selectedSlot->end_time)->format('Y-m-d H:i:s') }}">
                                        00h : 00m : 00s
                                    </span>
                                </div>
                            @else
                                <div style="color: #cb202d; font-weight: 600; font-size: 15px; margin-right: 30px; padding-right: 30px; border-right: 1px solid #eee; display: inline-block;">
                                    {{ __('Time Left:') }} 
                                    <span class="flash-timer" data-end="{{ now()->endOfDay()->format('Y-m-d H:i:s') }}">
                                        00h : 00m : 00s
                                    </span>
                                </div>
                            @endif
                            
                            @foreach($timeSlots as $slot)
                            @php
                                $isActive = ($selectedSlot && $selectedSlot->id == $slot->id);
                                $isPast = now()->format('H:i:s') > $slot->end_time;
                                $color = $isActive ? '#cb202d' : ($isPast ? '#ccc' : '#666');
                            @endphp
                                <a href="{{ route('front.flash-sales', ['slot' => $slot->id]) }}" style="color: {{ $color }}; font-weight: 600; margin-right: 30px; text-decoration: none; font-size: 14px;">
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('g:iA') }}
                                </a>
                            @endforeach
                            
                            <!-- Dummy Upcoming Days to match design -->
                            @foreach($timeSlots as $slot)
                                <a href="#" style="color: #999; font-weight: 600; margin-right: 30px; text-decoration: none; font-size: 14px;">
                                    {{ strtoupper(\Carbon\Carbon::tomorrow()->format('d M')) }}, {{ \Carbon\Carbon::parse($slot->start_time)->format('g:iA') }}
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
                        <div class="col-lg-4 col-md-6 col-sm-6 mb-4">
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
                                        @if($isUpcoming ?? false)
                                            <button class="btn btn-secondary w-100" disabled style="background: #ccc; border: none;">{{ __('Upcoming') }}</button>
                                        @elseif(($isEnded ?? false) || $itemsLeft <= 0)
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
                            <i class="fas fa-box-open" style="font-size: 60px; color: #ddd; margin-bottom: 20px;"></i>
                            <h4 style="color: #666;">{{ __('No products available in this time slot.') }}</h4>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@includeIf('partials.global.common-footer')
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
                flashTimer.html(text + hours + "h : " + minutes + "m : " + seconds + "s");
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
