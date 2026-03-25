@extends('layouts.front')
@section('css')
    {{-- <link rel="stylesheet" href="{{ asset('assets/front/css/category/classic.css') }}"> --}}

    <style>
        :root {
            --premium-gradient: linear-gradient(135deg, #7c3aed 0%, #3b82f6 100%);
            --glass-bg: rgba(15, 15, 15, 0.4);
            --glass-border: rgba(255, 255, 255, 0.1);
            --accent-purple: #a855f7;
        }

        .banner-slide-item {
            overflow: hidden;
            background-color: #050505;
        }

        .banner-slide-item video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: 0;
            transform: translate(-50%, -50%);
            object-fit: cover;
            opacity: 0.45; /* Slightly brighter for more life */
        }

        model-viewer {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            z-index: 1;
            --poster-color: transparent;
        }

        /* Premium Content Box (No Cover) */
        .banner-content-box {
            max-width: 800px;
            animation: luxuryEntry 1.4s cubic-bezier(0.19, 1, 0.22, 1) forwards;
            position: relative;
            z-index: 10;
        }

        @keyframes luxuryEntry {
            from { opacity: 0; transform: scale(0.98) translateY(20px); filter: blur(5px); }
            to { opacity: 1; transform: scale(1) translateY(0); filter: blur(0); }
        }

        /* Staggered Animations */
        @keyframes slideUpFade {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-stagger-1 { animation-delay: 0.1s; }
        .animate-stagger-2 { animation-delay: 0.2s; }
        .animate-stagger-3 { animation-delay: 0.3s; }

        .banner-content .subtitle {
            font-family: 'Outfit', sans-serif;
            text-transform: uppercase;
            letter-spacing: 7px;
            font-weight: 800;
            color: #60a5fa; /* Electric blue accent */
            margin-bottom: 25px;
            display: inline-block;
            text-shadow: 0 0 15px rgba(96, 165, 250, 0.4);
        }

        .banner-content .title {
            font-family: 'Jost', sans-serif;
            font-weight: 900;
            font-size: clamp(40px, 10vw, 80px); /* Larger, more impactful */
            line-height: 0.95;
            margin-bottom: 30px;
            color: #fff;
            text-shadow: 0 20px 40px rgba(0,0,0,0.6);
            letter-spacing: -2px;
        }

        .banner-content .details-text {
            font-size: 20px;
            color: rgba(255, 255, 255, 0.95);
            line-height: 1.5;
            margin-bottom: 35px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.8);
            font-weight: 500;
        }

        .premium-btn {
            background: var(--premium-gradient);
            color: white !important;
            padding: 16px 40px;
            border-radius: 50px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
            border: none;
            text-decoration: none;
        }

        .premium-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.5);
            color: white;
        }

        .robot-float {
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(2deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        /* Category Carousel */
        .category-carousel-wrap {
            position: relative;
            overflow: hidden;
        }
        .category-carousel-wrap .owl-carousel .item {
            padding: 8px;
        }
        .category-carousel-wrap .product-wrapper {
            border-radius: 12px;
            overflow: hidden;
            position: relative;
        }
        .category-carousel-wrap .product-image {
            height: 200px;
            overflow: hidden;
        }
        .category-carousel-wrap .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.4s ease;
        }
        .category-carousel-wrap .product-wrapper:hover .product-image img {
            transform: scale(1.08);
        }
        .category-carousel-wrap .product-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(255,255,255,0.55);
            backdrop-filter: blur(4px);
            -webkit-backdrop-filter: blur(4px);
            padding: 12px 14px;
            text-align: center;
            transition: background 0.3s ease;
        }
        .category-carousel-wrap .product-wrapper:hover .product-info {
            background: rgba(255,255,255,0.70);
        }
        .category-carousel-wrap .product-info .product-title span {
            display: block;
            white-space: normal !important;
            overflow: visible !important;
            text-overflow: clip;
            line-height: 1.2;
            font-size: 0.9rem;
        }
        .category-carousel-wrap .item {
            min-width: 140px;
        }
        .category-carousel-wrap .product-info .strok {
            color: #6c63ff;
            font-size: 0.82rem;
            font-weight: 500;
        }
        .category-carousel-wrap .owl-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            width: 100%;
            display: flex;
            justify-content: space-between;
            pointer-events: none;
        }
        .category-carousel-wrap .owl-prev,
        .category-carousel-wrap .owl-next {
            pointer-events: all;
            background: rgba(255,255,255,0.85) !important;
            border-radius: 50% !important;
            width: 38px;
            height: 38px;
            line-height: 36px !important;
            text-align: center;
            font-size: 18px !important;
            color: #333 !important;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            transition: background 0.2s;
        }
        .category-carousel-wrap .owl-prev:hover,
        .category-carousel-wrap .owl-next:hover {
            background: rgba(108,99,255,0.9) !important;
            color: #fff !important;
        }
    </style>
@endsection
@section('content')
    @include('partials.global.common-header')


    @include('partials.global.subscription-popup')


    @if ($ps->slider == 1)
        <div class="position-relative">
            <span class="nextBtn"></span>
            <span class="prevBtn"></span>
            <section class="home-slider owl-theme owl-carousel">
                @foreach ($sliders as $data)
                    <div class="banner-slide-item"
                        style="position: relative; background: {{ $data->video || $data->{'3d_model'} ? 'black' : "url('" . asset('assets/images/sliders/' . $data->photo) . "')" }} no-repeat center center / cover;">

                        @if($data->video)
                            <video autoplay muted loop playsinline>
                                <source src="{{ asset('assets/videos/' . $data->video) }}" type="video/mp4">
                            </video>
                        @endif

                        @if($data->{'3d_model'})
                            <model-viewer 
                                src="{{ asset('assets/models/' . $data->{'3d_model'}) }}" 
                                alt="A 3D robot model" 
                                auto-rotate 
                                camera-controls 
                                autoplay 
                                animation-name="Idle"
                                shadow-intensity="1" 
                                class="robot-float"
                                exposure="1"
                                environment-image="neutral"
                                disable-zoom>
                            </model-viewer>
                        @endif

                        <!-- Overlay -->
                        <div
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.35); z-index: 1;">
                        </div>

                        <!-- Text Section -->
                        <div class="container" style="position: relative; z-index: 2; display: flex; align-items: center; height: 100%;">
                            <div class="banner-wrapper-item text-{{ $data->position }}" style="width: 100%;">
                                <div class="banner-content-box {{ $data->position == 'right' ? 'ms-auto' : ($data->position == 'center' ? 'mx-auto' : '') }}">
                                    <div class="banner-content" style="text-align: {{ $data->position }};">

                                        <!-- Subtitle -->
                                        <span class="subtitle animate-stagger-1">
                                            {{ $data->subtitle_text }}
                                        </span>

                                        <!-- Title -->
                                        <h1 class="title animate-stagger-2">
                                            {{ $data->title_text }}
                                        </h1>

                                        <!-- Paragraph -->
                                        <p class="details-text animate-stagger-2">
                                            {{ $data->details_text }}
                                        </p>

                                        <!-- Button -->
                                        <div class="animate-stagger-3 mt-4">
                                            <a href="{{ route('front.category') }}" class="premium-btn">
                                                {{ __('EXPLORE COLLECTION') }}
                                            </a>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </section>
        </div>
    @endif




    <!--==================== Category Section Start ====================-->
    <div class="full-row pt-0 mt-5 px-sm-5 pb-0 category-carousel-wrap">
        <div class="container-fluid">
            <div class="category-owl-carousel owl-carousel owl-theme">
                @foreach ($featured_categories as $fcategory)
                    <div class="item">
                        <a href="{{ route('front.category', $fcategory->slug) }}" style="text-decoration:none;">
                            <div class="product-wrapper" style="border-radius:12px;overflow:hidden;position:relative;">
                                <div class="product-image" style="height:200px;overflow:hidden;">
                                    <img src="{{ asset('assets/images/categories/' . $fcategory->image) }}"
                                         alt="{{ $fcategory->name }}"
                                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.4s ease;">
                                </div>
                                <div class="product-info">
                                    <h6 class="product-title mb-0">
                                        <span style="font-weight:700;font-size:0.95rem;color:#111;">{{ $fcategory->name }}</span>
                                    </h6>
                                    <span class="strok">({{ $fcategory->products_count }})</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    <!--==================== Category Section End ====================-->





    @if ($ps->arrival_section == 1)
        <!--==================== Best Month Offer Section Start ====================-->
        <div class="full-row px-sm-5">
            <div class="container-fluid">
                <div class="row justify-content-center wow fadeInUp animated" data-wow-delay="200ms"
                    data-wow-duration="1000ms">
                    <div class="col-xxl-5 col-xl-7 col-lg-9">
                        <div class="text-center mb-40">
                            <h2 class="text-center font-500 mb-4">{{ __('Best Month Offer') }}</h2>
                            <span class="sub-title">
                                {{ __('Enjoy unbeatable deals and exclusive discounts available only this month.
                                                        Don’t miss out on your chance to save big!') }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-xxl-6 col-md-12">
                        <div class="banner-wrapper hover-img-zoom banner-one custom-class-122 bg-light">

                            <div class="banner-image overflow-hidden transation">
                                <img src="{{ asset('assets/images/arrival/' . $arrivals[0]['photo']) }}" alt="Banner Image">
                            </div>

                            <div class="banner-content y-center position-absolute">
                                <div class="middle-content">

                                    <span class="up-to-sale"
                                    style="color:#ffffff;
                                           background:rgba(0,0,0,0.35);
                                           padding:4px 10px;
                                           border-radius:3px;">

                                        {{ $arrivals[0]['up_sale'] }}
                                    </span>

                                    <h3>
                                        <a href="{{ $arrivals[0]['url'] }}"
                                           style="color:#ffffff; background:rgba(0,0,0,0.35); padding:6px 12px; border-radius:3px; text-decoration:none; transition: color 0.3s;"
                                           class="arrival-link">
                                            {{ $arrivals[0]['title'] }}
                                        </a>

                                        <style>
                                            .arrival-link:hover {
                                                color: #ff6a00 !important;
                                            }
                                        </style>
                                    </h3>


                                    <a href="{{ $arrivals[0]['url'] }}"
                                    class="category arrival-link"
                                    style="color:#ffffff;
                                           background:rgba(0,0,0,0.35);
                                           padding:4px 10px;
                                           border-radius:3px;">
                                     {{ $arrivals[0]['header'] }}
                                 </a>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div class="banner-wrapper hover-img-zoom banner-one custom-class-123">
                            <div class="banner-image overflow-hidden transation"><img
                                    src="{{ asset('assets/images/arrival/' . $arrivals[1]['photo']) }}" alt="Banner Image">
                            </div>
                            <div class="banner-content position-absolute">
                                <div class="middle-content">
                                    <span class="up-to-sale">{{ $arrivals[1]['up_sale'] }}</span>
                                    <h3><a href="{{ $arrivals[1]['url'] }}"
                                            class="text-dark text-decoration-none">{{ $arrivals[1]['title'] }}</a></h3>
                                    <a href="{{ $arrivals[1]['url'] }}" class="category">{{ $arrivals[1]['header'] }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6">
                        <div class="banner-wrapper hover-img-zoom banner-one custom-class-124"
                             style="position:relative; overflow:hidden; border-radius:8px;">

                            <div class="banner-image overflow-hidden transation">
                                <img src="{{ asset('assets/images/arrival/' . $arrivals[2]['photo']) }}"
                                     alt="Banner Image"
                                     style="width:100%; display:block;">

                                <!-- Overlay -->
                                <div style="position:absolute; top:0; left:0; width:100%; height:100%;
                                            background:rgba(0,0,0,0.35); pointer-events:none;" class="p-3">
                                </div>
                            </div>

                            <div class="banner-content position-absolute" style="top:10px; left:10px;">
                                <span style="color:#ffffff; padding:4px 10px; border-radius:3px;">{{ $arrivals[2]['up_sale'] }}</span>

                                <h5>
                                    <a href="{{ $arrivals[2]['url'] }}" class="arrival-link"
                                       style="color:#ffffff; text-decoration:none; padding:6px 12px; border-radius:3px"
                                      >
                                       {{ $arrivals[2]['title'] }}
                                    </a>
                                </h5>

                                <a href="{{ $arrivals[2]['url'] }}"
                                   style="color:#ffffff; padding:4px 10px; border-radius:3px; text-decoration:none;"
                                    class="arrival-link">
                                   {{ $arrivals[2]['header'] }}
                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--==================== Best Month Offer Section End ====================-->
    @endif


    @include('partials.theme.extraindex')



    @if (isset($visited))
        @if ($gs->is_cookie == 1)
            <div class="cookie-bar-wrap show">
                <div class="container d-flex justify-content-center">
                    <div class="col-xl-10 col-lg-12">
                        <div class="row justify-content-center">
                            <div class="cookie-bar">
                                <div class="cookie-bar-text">
                                    {{ __('The website uses cookies to ensure you get the best experience on our website.') }}
                                </div>
                                <div class="cookie-bar-action">
                                    <button class="btn btn-primary btn-accept">
                                        {{ __('GOT IT!') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endif
    <!-- Scroll to top -->
    <a href="#" class="scroller text-white" id="scroll"><i class="fa fa-angle-up"></i></a>
@endsection
@section('script')
    <script type="module" src="https://ajax.googleapis.com/ajax/libs/model-viewer/4.0.0/model-viewer.min.js"></script>
    <script>
        // Hero slider
        var owl = $('.home-slider').owlCarousel({
            loop: true,
            nav: false,
            dots: true,
            items: 1,
            autoplay: true,
            margin: 0,
            animateIn: 'fadeInDown',
            animateOut: 'fadeOutUp',
            mouseDrag: false,
        });
        $('.nextBtn').click(function() {
            owl.trigger('next.owl.carousel', [300]);
        });
        $('.prevBtn').click(function() {
            owl.trigger('prev.owl.carousel', [300]);
        });

        // Category carousel — infinite scroll
        $('.category-owl-carousel').owlCarousel({
            loop: true,
            margin: 12,
            nav: true,
            dots: false,
            autoplay: true,
            autoplayTimeout: 3000,
            autoplayHoverPause: true,
            responsive: {
                0:    { items: 2 },
                576:  { items: 3 },
                768:  { items: 4 },
                992:  { items: 5 },
                1200: { items: 6 }
            },
            navText: [
                '<i class="fa fa-chevron-left"></i>',
                '<i class="fa fa-chevron-right"></i>'
            ]
        });
    </script>
@endsection
