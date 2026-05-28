<style>
    .jumia-style-carousel .item {
        padding: 5px;
    }
    .jumia-style-carousel .product.type-product {
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
        background: transparent !important;
    }
    .jumia-style-carousel .product-wrapper {
        background: #fff !important;
        border-radius: 8px !important;
        padding: 10px !important;
        border: 1px solid #eaeaea !important;
        transition: all 0.2s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .jumia-style-carousel .product-wrapper:hover {
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    .jumia-style-carousel .product-image {
        width: 100% !important;
        aspect-ratio: 1/1 !important;
        overflow: hidden !important;
        border-radius: 6px !important;
        background: #fff !important;
        position: relative !important;
    }
    .jumia-style-carousel .product-image img {
        width: 100% !important;
        height: 100% !important;
        object-fit: contain !important;
    }
    .jumia-style-carousel .product-info {
        padding-top: 8px !important;
        text-align: left !important;
    }
    .jumia-style-carousel .product-title {
        font-size: 13px !important;
        font-weight: 500 !important;
        line-height: 1.4 !important;
        height: 36px !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
        display: -webkit-box !important;
        -webkit-line-clamp: 2 !important;
        -webkit-box-orient: vertical !important;
        margin-bottom: 5px !important;
    }
    .jumia-style-carousel .product-title a {
        color: #333 !important;
    }
    .jumia-style-carousel .product-price {
        margin-top: 5px !important;
    }
    .jumia-style-carousel .product-price ins {
        font-size: 15px !important;
        font-weight: 700 !important;
        color: #111 !important;
        text-decoration: none !important;
    }
    .jumia-style-carousel .product-price del {
        font-size: 12px !important;
        color: #999 !important;
        margin-left: 5px !important;
    }
    .jumia-style-carousel .on-sale {
        position: absolute !important;
        top: 8px !important;
        right: 8px !important;
        background: #fef08a !important;
        color: #a16207 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        padding: 2px 6px !important;
        border-radius: 4px !important;
        z-index: 2 !important;
    }
    /* Owl Nav Buttons Styling */
    .jumia-style-carousel-wrapper {
        position: relative;
    }
    .jumia-style-carousel-wrapper .owl-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 100%;
        display: flex;
        justify-content: space-between;
        pointer-events: none;
        z-index: 5;
    }
    .jumia-style-carousel-wrapper .owl-prev,
    .jumia-style-carousel-wrapper .owl-next {
        pointer-events: all;
        background: rgba(255,255,255,0.9) !important;
        color: #333 !important;
        border-radius: 50% !important;
        width: 36px;
        height: 36px;
        line-height: 34px !important;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
        border: 1px solid #ddd !important;
        transition: all 0.2s;
    }
    .jumia-style-carousel-wrapper .owl-prev:hover,
    .jumia-style-carousel-wrapper .owl-next:hover {
        background: #000000 !important;
        color: #fff !important;
        border-color: #000000 !important;
    }
    .jumia-style-carousel-wrapper .owl-prev {
        margin-left: -18px;
    }
    .jumia-style-carousel-wrapper .owl-next {
        margin-right: -18px;
    }
</style>

<!--==================== Top Collection Section Start ====================-->
<div class="full-row bg-white py-4">
    <div class="container">
        
        <!-- SECTION 1: TOP SELLERS -->
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between p-3 rounded-top text-white" style="background: #000000; font-weight: 700;">
                <h4 class="mb-0 text-white" style="font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Top Sellers') }}</h4>
                <a href="{{ route('front.category') }}?sort=sales_desc" class="text-white text-decoration-none d-flex align-items-center" style="font-size: 14px; font-weight: 600;">
                    {{ __('See All') }} <i class="fas fa-chevron-right ms-2" style="font-size: 11px;"></i>
                </a>
            </div>
            <div class="bg-light p-3 rounded-bottom border border-top-0 jumia-style-carousel-wrapper">
                <div class="owl-carousel owl-theme jumia-style-carousel">
                    @foreach($sale_products as $prod)
                        <div class="item">
                            @include('partials.product.home-product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SECTION 2: NEW ARRIVALS -->
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between p-3 rounded-top text-white" style="background: #111; font-weight: 700;">
                <h4 class="mb-0 text-white" style="font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('New Arrivals') }}</h4>
                <a href="{{ route('front.category') }}?sort=date_desc" class="text-white text-decoration-none d-flex align-items-center" style="font-size: 14px; font-weight: 600;">
                    {{ __('See All') }} <i class="fas fa-chevron-right ms-2" style="font-size: 11px;"></i>
                </a>
            </div>
            <div class="bg-light p-3 rounded-bottom border border-top-0 jumia-style-carousel-wrapper">
                <div class="owl-carousel owl-theme jumia-style-carousel">
                    @foreach($latest_products as $prod)
                        <div class="item">
                            @include('partials.product.home-product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SECTION 3: BUYERS CHOICE -->
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between p-3 rounded-top text-white" style="background: #000000; font-weight: 700;">
                <h4 class="mb-0 text-white" style="font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Buyers Choice') }}</h4>
                <a href="{{ route('front.category') }}?sort=views_desc" class="text-white text-decoration-none d-flex align-items-center" style="font-size: 14px; font-weight: 600;">
                    {{ __('See All') }} <i class="fas fa-chevron-right ms-2" style="font-size: 11px;"></i>
                </a>
            </div>
            <div class="bg-light p-3 rounded-bottom border border-top-0 jumia-style-carousel-wrapper">
                <div class="owl-carousel owl-theme jumia-style-carousel">
                    @foreach($popular_products as $prod)
                        <div class="item">
                            @include('partials.product.home-product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- SECTION 4: TRENDING -->
        <div class="mb-4">
            <div class="d-flex align-items-center justify-content-between p-3 rounded-top text-white" style="background: #111; font-weight: 700;">
                <h4 class="mb-0 text-white" style="font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Trending') }}</h4>
                <a href="{{ route('front.category') }}?sort=date_desc" class="text-white text-decoration-none d-flex align-items-center" style="font-size: 14px; font-weight: 600;">
                    {{ __('See All') }} <i class="fas fa-chevron-right ms-2" style="font-size: 11px;"></i>
                </a>
            </div>
            <div class="bg-light p-3 rounded-bottom border border-top-0 jumia-style-carousel-wrapper">
                <div class="owl-carousel owl-theme jumia-style-carousel">
                    @foreach($trending_products as $prod)
                        <div class="item">
                            @include('partials.product.home-product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
<!--==================== Top Collection Section End ====================-->




<!--==================== Top Products Section Start (Featured Products Carousel) ====================-->
<div class="full-row bg-white py-4">
    <div class="container">
        <div class="mb-5">
            <div class="d-flex align-items-center justify-content-between p-3 rounded-top text-white" style="background: #000000; font-weight: 700;">
                <h4 class="mb-0 text-white" style="font-size: 18px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">{{ __('Featured Products') }}</h4>
                <a href="{{ route('front.category') }}?sort=views_desc" class="text-white text-decoration-none d-flex align-items-center" style="font-size: 14px; font-weight: 600;">
                    {{ __('See All') }} <i class="fas fa-chevron-right ms-2" style="font-size: 11px;"></i>
                </a>
            </div>
            <div class="bg-light p-3 rounded-bottom border border-top-0 jumia-style-carousel-wrapper">
                <div class="owl-carousel owl-theme jumia-style-carousel">
                    @foreach($popular_products as $prod)
                        <div class="item">
                            @include('partials.product.home-product')
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
<!--==================== Top Products Section End ====================-->


@if($ps->deal_of_the_day==1)

<!--==================== Deal of the day Section Start ====================-->
<div class="full-row home-deal box-shadow-small">
    <div class="container">
        <div class="row offer-product align-items-center">
            <div class="col-xl-5 col-lg-7">
                <h1 class="down-line-secondary text-dark text-uppercase mb-30">{{ __('Deal') }} <br> {{ __('of the Day')
                    }}</h1>
                <div class="product type-product">
                    <div class="product-wrapper">
                        <div class="product-info">

                            <h3 class="product-title">{{ __('Unlimited Supply Unisex Back Support Posture Belt') }}</h3>

                            <div class="font-fifteen">
                                <p>{{ __('Effective Instantly - Good Results. Improve your posture and relieve back pain with our premium ergonomic support belt.') }}</p>
                            </div>
                            <div class="time-count time-box text-center my-30 flex-between w-75"
                                data-countdown="{{ $gs->deal_time }}"></div>
                            <a href="https://fabilive.com/item/unlimited-supply-unisex-back-support-posture-belt-effective-instantly-good-results-chi1673qaq"
                                    class="btn btn-dark text-uppercase rounded-0">{{ __('Shop Now') }}</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-5 offset-xl-1">

                <div class="xs-mt-30"><img
                        src="{{ $gs->deal_background ? asset('assets/images/'.$gs->deal_background):asset('assets/images/noimage.png') }}"
                        alt=""></div>

            </div>
        </div>
    </div>
</div>
<!--==================== Deal of the day Section End ====================-->

@endif
<!--==================== Deal of the day Section End ====================-->



<!--==================== Service Section Start ====================-->
@if ($ps->partner==1)
<div class="full-row bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">

                <h2 class="main-title mb-4 text-center text-secondary">{{ $gs->partner_title }}</h2>
                <span class="mb-30 sub-title text-general font-medium ordenery-font font-400 text-center">{{
                    $gs->partner_text }}</span>
            </div>
        </div>
        <div class="row g-3">
            @foreach ((isset($global_partners) ? $global_partners : collect()) as $data)
            <div class="col-6 col-sm-4 col-lg-3 col-xl-2">
                <div class="simple-service">
                    <img src="{{ asset('assets/images/partner/'.$data->photo) }}" alt="">

                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endif

<!--==================== Service Section End ====================-->

<!--==================== Top Products Section Start ====================-->
<!--==================== Top Products Section Start ====================-->
<div class="full-row">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                <span class="text-secondary pb-2 d-table tagline mx-auto text-uppercase text-center">{{ __('Top
                    Products') }}</span>
                <h2 class="main-title mb-4 text-center text-secondary">{{ __('Top Sales Products') }}</h2>

            </div>
        </div>

        <div class="row">
            <div class="col-12">

                <div class="products product-style-1 owl-mx-15">
                    <div
                        class="four-carousel owl-carousel dot-disable nav-arrow-middle-show e-title-general e-title-hover-primary e-image-bg-light  e-info-center e-title-general e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
                        @foreach($best_products as $prod)
                        <div class="item">
                            @include('partials.product.home-product')
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--==================== Top Products Section End ====================-->






<!--==================== Service Section Start ====================-->
<div class="full-row bg-light pb-4">
    <div class="container">
        <div class="row row-cols-xl-4 row-cols-sm-2 row-cols-1 gy-4 gy-xl-0">
            {{-- <div class="col">
                <div class="simple-service px-3 md-my-10 d-flex align-items-center">
                    <div class="box-80px rounded-pill position-relative bg-white"><i
                            class="flaticon-money flat-medium text-secondary xy-center position-absolute"></i></div>
                    <div class="ms-3">
                        <h5 class="mb-1 font-500"><a href="service.html"
                                class="text-dark hover-text-primary transation-this">Money Gurantee</a></h5>
                        <div class="font-small text-secondary">
                            <span>With A 30 Days</span>
                        </div>
                    </div>
                </div>
            </div> --}}
            @foreach ((isset($global_services) ? $global_services : collect()) as $service)
            <div class="col">
                <div class="simple-service px-3 md-my-10 d-flex align-items-center">
                    <div class="box-80px rounded-pill position-relative bg-white">
                        <img class="flat-medium text-secondary xy-center position-absolute"
                            src="{{asset('assets/images/services/'.$service->photo)}}" alt="">
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-1 font-500"><a href="service.html"
                                class="text-dark hover-text-primary transation-this">{{$service->title}}</a></h5>
                        <div class="font-small text-secondary">
                            <span>{{$service->details}}</span>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach


        </div>
    </div>
</div>
<!--==================== Service Section End ====================-->





<!--==================== Our Blog Section Start ====================-->
@if($ps->blog==1)
<div class="full-row pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5">
                    <h2 class="main-title mb-4 text-center text-secondary">{{ __('Latest Post') }}</h2>
                    <span class="mb-30 sub-title text-general font-medium ordenery-font font-400 text-center">
                        {{ __('Stay updated with our most recent insights, news, and stories tailored just for you.') }}
                    </span>
                </div>
        </div>
        <div class="row row-cols-lg-2 row-cols-1">
            @foreach ($blogs as $blog)
            <div class="col">
                <div class="thumb-latest-blog text-center transation hover-img-zoom mb-3">
                    <div class="post-image overflow-hidden">
                        <a href="{{ route('front.blogshow',$blog->slug) }}">
                            <img src="{{ asset('assets/images/blogs/'.$blog->photo) }}" alt="Image not found!">
                        </a>

                    </div>
                    <div class="post-content">
                        <h3><a href="{{ route('front.blogshow',$blog->slug) }}"
                                class="transation text-dark hover-text-primary d-table my-10 mx-sm-auto">{{
                                mb_strlen($blog->title,'UTF-8') > 200 ?
                                mb_substr($blog->title,0,200,'UTF-8')."...":$blog->title }}</a></h3>
                        <div class="post-meta font-small text-uppercase list-color-general my-3">
                            <p class="post-date">{{ date('d M, Y',strtotime($blog->created_at)) }}</p>
                        </div>
                        <a href="{{ route('front.blogshow',$blog->slug) }}" class="btn-link-left-line">{{ __('Read
                            More') }}</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!--==================== Our Blog Section End ====================-->
@endif

@includeIf('partials.global.common-footer')

<script src="{{ asset('assets/front/js/extraindex.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        $('.jumia-style-carousel').owlCarousel({
            loop: false,
            margin: 10,
            nav: true,
            dots: false,
            autoplay: false,
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
    });
</script>