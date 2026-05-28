@extends('layouts.front')

@section('content')
@includeIf('partials.global.common-header')
<!-- Breadcrumb removed per user request -->

<!-- Flash Sales Section -->
<section class="flash-sales-page mt-4 mb-5">
    <div class="container">
        <!-- HUGE BLACK BANNER -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="flash-banner" style="background-color: #000; border-radius: 8px; padding: 60px 40px; text-align: center; color: white;">
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

        <!-- Flash Sale Categories -->
        <div class="row mb-4">
            <div class="col-12">
                <div style="background: white; border-radius: 8px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <h4 style="text-align: center; font-size: 18px; font-weight: 600; margin-bottom: 20px;">Flash Sale Categories</h4>
                    <div class="d-flex justify-content-between text-center" style="overflow-x: auto; gap: 15px;">
                        @foreach($flashCategories as $fcat)
                        <a href="{{ route('front.flash-sales', array_merge(request()->query(), ['category' => $fcat->id])) }}" class="category-tile" style="flex: 1; min-width: 120px; padding: 15px; border: 1px solid {{ $selectedCategory == $fcat->id ? '#cb202d' : '#eee' }}; border-radius: 5px; text-decoration: none; color: #333; transition: all 0.3s;">
                            <h5 style="font-size: 14px; margin: 0; font-weight: {{ $selectedCategory == $fcat->id ? '700' : '400' }}; color: {{ $selectedCategory == $fcat->id ? '#cb202d' : '#333' }};">{{ $fcat->name }}</h5>
                        </a>
                        @endforeach
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
                            <div class="sort-by-dropdown">
                                <form action="{{ route('front.flash-sales') }}" method="GET" id="sortForm">
                                    @if(request()->has('slot'))
                                        <input type="hidden" name="slot" value="{{ request('slot') }}">
                                    @endif
                                    @if(request()->has('category'))
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                    @endif
                                    <select name="sort" onchange="document.getElementById('sortForm').submit()" style="background: rgba(255,255,255,0.2); color: white; border: 1px solid rgba(255,255,255,0.3); padding: 5px 10px; border-radius: 4px; font-weight: 600; font-size: 14px; outline: none; cursor: pointer;">
                                        <option style="color: black;" value="popularity" {{ $sort == 'popularity' ? 'selected' : '' }}>Sort by: Popularity</option>
                                        <option style="color: black;" value="newest" {{ $sort == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                                        <option style="color: black;" value="price_asc" {{ $sort == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                                        <option style="color: black;" value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                                        <option style="color: black;" value="rating" {{ $sort == 'rating' ? 'selected' : '' }}>Product Rating</option>
                                    </select>
                                </form>
                            </div>
                        </div>
                        
                        <div class="flash-timeline" style="background: white; padding: 15px 20px; border-radius: 0 0 5px 5px; border: 1px solid #eee; border-top: none; display: flex; overflow-x: auto; align-items: center; white-space: nowrap;">
                            @if($selectedSlot)
                                @php
                                    $currentTime = now()->format('H:i:s');
                                    $isCurrentlyActive = ($selectedDate == \Carbon\Carbon::today()->format('Y-m-d')) && ($currentTime >= $selectedSlot->start_time && $currentTime <= $selectedSlot->end_time);
                                    
                                    $isPast = ($selectedDate < \Carbon\Carbon::today()->format('Y-m-d')) || ($selectedDate == \Carbon\Carbon::today()->format('Y-m-d') && $currentTime > $selectedSlot->end_time);
                                    
                                    if ($isPast) {
                                        $timerLabel = __('Sale Ended');
                                        // Set an arbitrary past time so JS knows it ended immediately
                                        $endTimeStr = \Carbon\Carbon::parse('-1 day')->format('Y-m-d H:i:s');
                                    } else {
                                        $timerLabel = $isCurrentlyActive ? __('Time Left:') : __('Starts In:');
                                        if ($isCurrentlyActive) {
                                            $endTimeStr = $selectedDate . ' ' . $selectedSlot->end_time;
                                        } else {
                                            $endTimeStr = $selectedDate . ' ' . $selectedSlot->start_time;
                                        }
                                    }
                                    $endTime = \Carbon\Carbon::parse($endTimeStr)->format('Y/m/d H:i:s');
                                    $endTimestamp = \Carbon\Carbon::parse($endTimeStr)->timestamp * 1000;
                                @endphp
                                <div style="color: #cb202d; font-weight: 600; font-size: 15px; margin-right: 30px; padding-right: 30px; border-right: 1px solid #eee; display: inline-block;">
                                    <span id="flash-timer-label">{{ $timerLabel }}</span> 
                                    @if(!$isPast)
                                    <span class="flash-timer" data-end="{{ $endTime }}" data-end-timestamp="{{ $endTimestamp }}">
                                        00h : 00m : 00s
                                    </span>
                                    @endif
                                </div>
                            @else
                                <div style="color: #cb202d; font-weight: 600; font-size: 15px; margin-right: 30px; padding-right: 30px; border-right: 1px solid #eee; display: inline-block;">
                                    <span id="flash-timer-label">{{ __('Time Left:') }}</span> 
                                    <span class="flash-timer" data-end="{{ now()->addDay()->format('Y/m/d H:i:s') }}" data-end-timestamp="{{ now()->addDay()->timestamp * 1000 }}">
                                        00h : 00m : 00s
                                    </span>
                                </div>
                            @endif
                            
                            @foreach($timeSlots as $slot)
                            @php
                                $todayStr = \Carbon\Carbon::today()->format('Y-m-d');
                                $isActive = ($selectedSlot && $selectedSlot->id == $slot->id && $selectedDate == $todayStr);
                                $isPastSlot = now()->format('H:i:s') > $slot->end_time;
                                $color = $isActive ? '#cb202d' : ($isPastSlot ? '#ccc' : '#666');
                            @endphp
                                <a href="{{ route('front.flash-sales', array_merge(request()->query(), ['slot' => $slot->id, 'date' => $todayStr])) }}" style="color: {{ $color }}; font-weight: 600; margin-right: 30px; text-decoration: none; font-size: 14px;">
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('g:iA') }}
                                </a>
                            @endforeach
                            
                            @foreach($timeSlots as $slot)
                            @php
                                $tomorrowStr = \Carbon\Carbon::tomorrow()->format('Y-m-d');
                                $isActiveTomorrow = ($selectedSlot && $selectedSlot->id == $slot->id && $selectedDate == $tomorrowStr);
                                $colorTomorrow = $isActiveTomorrow ? '#cb202d' : '#999';
                            @endphp
                                <a href="{{ route('front.flash-sales', array_merge(request()->query(), ['slot' => $slot->id, 'date' => $tomorrowStr])) }}" style="color: {{ $colorTomorrow }}; font-weight: 600; margin-right: 30px; text-decoration: none; font-size: 14px;">
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

@section('script')
<script>
    $(document).ready(function() {
        $('.flash-timer').each(function() {
            var flashTimer = $(this);
            var endDate = parseInt(flashTimer.attr('data-end-timestamp'));
            
            if (isNaN(endDate)) {
                var endDateStr = flashTimer.attr('data-end');
                // Cross-browser compatibility for Safari/iOS parsing
                if(endDateStr && endDateStr.indexOf('-') !== -1) {
                    endDateStr = endDateStr.replace(/-/g, '/');
                }
                if (endDateStr) {
                    endDate = new Date(endDateStr).getTime();
                }
            }

            var updateTimer = function() {
                var now = new Date().getTime();
                var distance = endDate - now;
                
                if (isNaN(distance) || distance < 0) {
                    var intervalId = flashTimer.data('timer-interval');
                    if (intervalId) clearInterval(intervalId);
                    
                    var label = $('#flash-timer-label');
                    if (label.length > 0 && label.text().indexOf('Starts In') !== -1) {
                        if (!isNaN(distance)) location.reload();
                    } else {
                        flashTimer.html("{{ __('Sale Ended') }}");
                    }
                    return;
                }
                
                var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                var dStr = days < 10 ? "0" + days : days;
                var hStr = hours < 10 ? "0" + hours : hours;
                var mStr = minutes < 10 ? "0" + minutes : minutes;
                var sStr = seconds < 10 ? "0" + seconds : seconds;

                var out = "";
                if (days > 0) {
                    out += dStr + "d : ";
                }
                out += hStr + "h : " + mStr + "m : " + sStr + "s";
                
                flashTimer.html(out);
            };
            
            updateTimer();
            var x = setInterval(updateTimer, 1000);
            flashTimer.data('timer-interval', x);
        });
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
