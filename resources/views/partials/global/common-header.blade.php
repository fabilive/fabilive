@if (request('success') && request('message'))
    <div class="alert alert-success text-center">
        {{ request('message') }}
    </div>
@endif

<!--==================== Header Section Start ====================-->
<header class="ecommerce-header px-lg-5">
    <div style="display: block !important;" class="top-header d-none d-lg-block py-2 border-0 font-400">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div style="display: block !important;" class="col-lg-4 sm-mx-none">
                    <a href="javascript:;" class="text-general"><span>{{ __('Contact & Support') }} :
                            {{ $ps->phone }}</span></a>
                </div>
                <div class="col-lg-8 d-flex d-none d-lg-block">
                    <ul class="top-links d-flex ms-auto align-items-center justify-content-end">
                        @if (!Auth::guard('web')->check() && !Auth::guard('rider')->check())
                            <li><a class="border px-3 py-1"
                                    href="{{ route('user.register', ['source' => 'sell']) }}">{{ __('Sell') }}</a></li>
                            <li><a class="border px-3 py-1"
                                    href="{{ route('rider.register') }}">{{ __('Become a Delivery Agent') }}</a></li>
                        @endif
                        @if (Auth::guard('web')->check() && Auth::guard('web')->user()->is_vendor == 0)
                            <li><a class="border px-3 py-1"
                                    href="{{ route('user-vendor-request', 8) }}">{{ __('Sell') }}</a></li>
                        @endif
                        @if (Auth::guard('web')->check() && Auth::guard('web')->user()->is_vendor == 2)
                            <li><a class="border px-3 py-1"
                                    href="{{ route('vendor.dashboard') }}">{{ __('Vendor Panel') }}</a>
                            </li>
                        @endif
                        <li>
                            <div id="desktop_google_translate_element"></div>
                        </li>
                        @php
                            $currencies = App\Models\Currency::all();
                        @endphp
                        {{-- Currency Switcher Hidden --}}
                        {{-- <li class="my-account-dropdown">
                            <div class="currency-selector nice-select">
                                <span class="text-dark">
                                    {{ Session::has('currency')
                                        ? $currencies->where('id', '=', Session::get('currency'))->first()->sign
                                        : DB::table('currencies')->where('is_default', '=', 1)->first()->sign }}
                                </span>
                                <select name="currency" class="currency selectors nice select2-js-init">
                                    @foreach ($currencies as $currency)
                                        <option value="{{ route('front.currency', $currency->id) }}"
                                            {{ Session::has('currency')
                                                ? (Session::get('currency') == $currency->id
                                                    ? 'selected'
                                                    : '')
                                                : ($currencies->where('is_default', '=', 1)->first()->id == $currency->id
                                                    ? 'selected'
                                                    : '') }}>
                                            {{ $currency->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </li> --}}
                        <li class="my-account-dropdown">
                            <a href="my-account.html" class="has-dropdown"><i
                                    class="flaticon-user-3 flat-mini me-1"></i>{{ __('My Account') }}</a>
                            <ul class="my-account-popup">
                                @if (Auth::guard('web')->check())
                                    <li><a href="{{ route('user-dashboard') }}"><span
                                                class="menu-item-text">{{ 'User
                                                                                            Panel' }}</span></a>
                                    </li>
                                    @if (Auth::guard('web')->user()->IsVendor())
                                        <li><a href="{{ route('vendor.dashboard') }}"><span
                                                    class="menu-item-text">{{ __('Vendor
                                                                                                Panel') }}</span></a>
                                        </li>
                                    @endif
                                    <li><a href="{{ route('user-profile') }}"><span
                                                class="menu-item-text">{{ __('Edit
                                                                                            Profile') }}</span></a>
                                    </li>
                                    <li><a href="{{ route('user-logout') }}"><span
                                                class="menu-item-text">{{ __('Logout') }}</span></a></li>
                                @elseif(Auth::guard('rider')->check())
                                    <li><a href="{{ route('rider-dashboard') }}"><span
                                                class="menu-item-text">{{ 'Delivery
                                                                                            Panel' }}</span></a>
                                    </li>
                                    <li><a href="{{ route('rider-profile') }}"><span
                                                class="menu-item-text">{{ __('Edit
                                                                                            Profile') }}</span></a>
                                    </li>
                                    <li><a href="{{ route('rider-logout') }}"><span
                                                class="menu-item-text">{{ __('Logout') }}</span></a></li>
                                @else
                                    <li><a href="{{ route('user.login') }}"><span
                                                class="menu-item-text sign-in">{{ __('User
                                                                                            Login') }}</span></a>
                                    </li>
                                    <li><a href="{{ route('rider.login') }}"><span
                                                class="menu-item-text sign-in">{{ __('Delivery Login') }}</span></a>
                                    </li>
                                    <li><a href="{{ route('user.register') }}"><span
                                                class="menu-item-text join">{{ __('Join') }}</span></a></li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-8 d-lg-none mt-1">
                    <ul class="top-linksMob d-flex">
                        <div class="d-flex">
                            @if (!Auth::guard('web')->check() && !Auth::guard('rider')->check())
                                <li><a class="border px-3 py-1"
                                        href="{{ route('user.register', ['source' => 'sell']) }}">{{ __('Sell') }}</a></li>
                                <li><a class="border px-3 py-1"
                                        href="{{ route('rider.register') }}">{{ __('Become a Delivery Agent') }}</a>
                                </li>
                            @endif

                            @if (Auth::guard('web')->check() && Auth::guard('web')->user()->is_vendor == 0)
                                <li><a class="border px-3 py-1"
                                        href="{{ route('user-vendor-request', 8) }}">{{ __('Sell') }}</a></li>
                            @endif

                            @if (Auth::guard('web')->check() && Auth::guard('web')->user()->is_vendor == 2)
                                <li><a class="border px-3 py-1"
                                        href="{{ route('vendor.dashboard') }}">{{ __('Vendor Panel') }}</a>
                                </li>
                            @endif
                        </div>
                        <li>
                            <div id="desktop_google_translate_element"></div>
                        </li>
                        <div class="d-flex">
                            @php
                                $currencies = App\Models\Currency::all();
                            @endphp
                            {{-- Currency Switcher Hidden --}}
                            {{-- <li class="my-account-dropdown">
                                <div class="currency-selector nice-select">
                                    <span class="text-dark">
                                        {{ Session::has('currency')
                                            ? $currencies->where('id', '=', Session::get('currency'))->first()->sign
                                            : DB::table('currencies')->where('is_default', '=', 1)->first()->sign }}
                                    </span>
                                    <select name="currency" class="currency selectors nice select2-js-init">
                                        @foreach ($currencies as $currency)
                                            <option value="{{ route('front.currency', $currency->id) }}"
                                                {{ Session::has('currency')
                                                    ? (Session::get('currency') == $currency->id
                                                        ? 'selected'
                                                        : '')
                                                    : ($currencies->where('is_default', '=', 1)->first()->id == $currency->id
                                                        ? 'selected'
                                                        : '') }}>
                                                {{ $currency->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </li> --}}
                            <li class="my-account-dropdown">
                                <a href="my-account.html" class="has-dropdown"><i
                                        class="flaticon-user-3 flat-mini me-1"></i>{{ __('My Account') }}</a>
                                <ul class="my-account-popup">
                                    @if (Auth::guard('web')->check())
                                        <li><a href="{{ route('user-dashboard') }}"><span
                                                    class="menu-item-text">{{ 'User
                                                                                                Panel' }}</span></a>
                                        </li>
                                        @if (Auth::guard('web')->user()->IsVendor())
                                            <li><a href="{{ route('vendor.dashboard') }}"><span
                                                        class="menu-item-text">{{ __('Vendor
                                                                                                    Panel') }}</span></a>
                                            </li>
                                        @endif
                                        <li><a href="{{ route('user-profile') }}"><span
                                                    class="menu-item-text">{{ __('Edit
                                                                                                Profile') }}</span></a>
                                        </li>
                                        <li><a href="{{ route('user-logout') }}"><span
                                                    class="menu-item-text">{{ __('Logout') }}</span></a></li>
                                    @elseif(Auth::guard('rider')->check())
                                        <li><a href="{{ route('rider-dashboard') }}"><span
                                                    class="menu-item-text">{{ 'Delivery
                                                                                                Panel' }}</span></a>
                                        </li>
                                        <li><a href="{{ route('rider-profile') }}"><span
                                                    class="menu-item-text">{{ __('Edit
                                                                                                Profile') }}</span></a>
                                        </li>
                                        <li><a href="{{ route('rider-logout') }}"><span
                                                    class="menu-item-text">{{ __('Logout') }}</span></a></li>
                                    @else
                                        <li><a href="{{ route('user.login') }}"><span
                                                    class="menu-item-text sign-in">{{ __('User
                                                                                                Login') }}</span></a>
                                        </li>
                                        <li><a href="{{ route('rider.login') }}"><span
                                                    class="menu-item-text sign-in">{{ __('Delivery Login') }}</span></a>
                                        </li>
                                        <li><a href="{{ route('user.register') }}"><span
                                                    class="menu-item-text join">{{ __('Join') }}</span></a></li>
                                    @endif
                                </ul>
                            </li>
                        </div>

                    </ul>
                </div>
            </div>
        </div>
    </div>
    @php
        $categories = isset($global_categories) ? $global_categories : collect();
        $pages = isset($global_pages) ? $global_pages : collect();
    @endphp
    <div class="main-nav py-4 d-none d-lg-block">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-7 col-lg-9">
                    <nav class="navbar navbar-expand-lg nav-dark nav-primary-hover nav-line-active">
                        <a class="navbar-brand" href="{{ route('front.index') }}">
                            @php
                                $active_logo = asset('assets/images/logo.png');
                                if (isset($gs->logo)) {
                                    $active_logo = asset('assets/images/' . $gs->logo);
                                } elseif (file_exists(public_path('assets/images/fabilive_logo_white_bg.png'))) {
                                    $active_logo = asset('assets/images/fabilive_logo_white_bg.png');
                                }
                            @endphp
                            <img class="nav-logo" src="{{ $active_logo }}" alt="Fabilive Logo">
                        </a>
                        <button class="navbar-toggler" type="button" data-toggle="collapse"
                            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                            aria-expanded="false" aria-label="Toggle navigation">
                            <i class="flaticon-menu-2 flat-small text-primary"></i>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarSupportedContent">
                            <ul class="navbar-nav ms-md-5">
                                <li class="nav-item dropdown {{ request()->path() == '/' ? 'active' : '' }}">
                                    <a class="nav-link dropdown-toggle"
                                        href="{{ route('front.index') }}">{{ __('Home') }}</a>
                                </li>
                                @if ($ps->home == 1)
                                    <li class="nav-item dropdown">
                                        <a class="nav-link dropdown-toggle" href="{{ route('front.category') }}">{{ __('Product') }}</a>
                                        <ul class="dropdown-menu">
                                            <style>
                                                .dropdown-menu .dropend:hover > .sub-menu-dropdown {
                                                    display: block !important;
                                                }
                                                .dropdown-menu .dropend .sub-menu-dropdown {
                                                    display: none;
                                                    top: -10px;
                                                    left: 100%;
                                                    min-width: 220px;
                                                    position: absolute;
                                                    background: #fff;
                                                    border: 1px solid rgba(0,0,0,.15);
                                                    box-shadow: 0 0.5rem 1rem rgba(0,0,0,.175);
                                                    z-index: 1000;
                                                    padding: 0.5rem 0;
                                                }
                                                .dropdown-menu .dropend {
                                                    position: relative;
                                                }
                                            </style>
                                            @foreach ($categories as $category)
                                                @if ($category->subs->count() > 0)
                                                    <li class="dropend">
                                                        <a class="dropdown-item d-flex justify-content-between align-items-center" href="{{ route('front.category', $category->slug) }}">
                                                            {{ $category->name }}
                                                            <i class="fas fa-chevron-right ms-2" style="font-size: 10px; color: #999;"></i>
                                                        </a>
                                                        <ul class="sub-menu-dropdown list-unstyled">
                                                            @foreach ($category->subs as $subcategory)
                                                                @if ($subcategory->childs && $subcategory->childs->count() > 0)
                                                                    <li class="dropend">
                                                                        <a class="dropdown-item d-flex justify-content-between align-items-center"
                                                                            href="{{ route('front.category', [$category->slug, $subcategory->slug]) }}{{ !empty(request()->input('search')) ? '?search=' . request()->input('search') : '' }}">
                                                                            {{ $subcategory->name }}
                                                                            <i class="fas fa-chevron-right ms-2" style="font-size: 10px; color: #999;"></i>
                                                                        </a>
                                                                        <ul class="sub-menu-dropdown list-unstyled">
                                                                            @foreach ($subcategory->childs as $child)
                                                                                <li>
                                                                                    <a class="dropdown-item" href="{{ route('front.category', [$category->slug, $subcategory->slug, $child->slug]) }}{{ !empty(request()->input('search')) ? '?search=' . request()->input('search') : '' }}">
                                                                                        {{ $child->name }}
                                                                                    </a>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    </li>
                                                                @else
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('front.category', [$category->slug, $subcategory->slug]) }}{{ !empty(request()->input('search')) ? '?search=' . request()->input('search') : '' }}">{{ $subcategory->name }}</a>
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                @else
                                                    <li><a class="dropdown-item" href="{{ route('front.category', $category->slug) }}">{{ $category->name }}</a></li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </li>
                                @endif
                                <li class="nav-item dropdown ">
                                    <a class="nav-link dropdown-toggle" href="#">{{ __('Pages') }}</a>
                                    <ul class="dropdown-menu">
                                        @foreach ($pages->where('header', '=', 1) as $data)
                                            <li><a class="dropdown-item"
                                                    href="{{ route('front.vendor', $data->slug) }}">{{ $data->title }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @if ($ps->blog == 1)
                                    <li class="nav-item dropdown {{ request()->path() == 'blog' ? 'active' : '' }}">
                                        <a class="nav-link dropdown-toggle"
                                            href="{{ route('front.blog') }}">{{ __('Blog') }}</a>
                                    </li>
                                @endif
                                @if ($ps->faq == 1)
                                    <li class="nav-item dropdown {{ request()->path() == 'faq' ? 'active' : '' }}">
                                        <a class="nav-link dropdown-toggle"
                                            href="{{ route('front.faq') }}">{{ __('FAQ') }}</a>
                                    </li>
                                @endif
                                @if ($ps->contact == 1)
                                    <li class="nav-item {{ request()->path() == 'contact' ? 'active' : '' }}"><a
                                            class="nav-link"
                                            href="{{ route('front.contact') }}">{{ __('Contact') }}</a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </nav>
                </div>
                <div class="col-xl-5 col-lg-3">
                    <div class="margin-right-1 d-flex align-items-center justify-content-end h-100">
                        <div class="product-search-one flex-grow-1 global-search touch-screen-view">
                            <form id="searchForm" class="search-form form-inline search-pill-shape"
                                action="{{ route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}"
                                method="GET">
                                @if (!empty(request()->input('sort')))
                                    <input type="hidden" name="sort" value="{{ request()->input('sort') }}">
                                @endif
                                @if (!empty(request()->input('minprice')))
                                    <input type="hidden" name="minprice"
                                        value="{{ request()->input('minprice') }}">
                                @endif
                                @if (!empty(request()->input('maxprice')))
                                    <input type="hidden" name="maxprice"
                                        value="{{ request()->input('maxprice') }}">
                                @endif
                                <input type="text" id="prod_name" class="col form-control search-field "
                                    name="search" placeholder="Search Product For"
                                    value="{{ request()->input('search') }}">
                                <div class=" categori-container select-appearance-none " id="catSelectForm">
                                    <select name="category" class="form-control categoris select2-js-search-init">
                                        <option selected="">{{ __('All Categories') }}</option>
                                        @foreach ($categories->where('status', 1) as $data)
                                            <option value="{{ $data->slug }}"
                                                {{ Request::route('category') == $data->slug ? 'selected' : '' }}>
                                                {{ $data->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <button type="submit" name="submit" class="search-submit"><i
                                        class="flaticon-search flat-mini text-white"></i></button>

                            </form>
                        </div>
                        <div class="autocomplete">
                            <div id="myInputautocomplete-list" class="autocomplete-items"></div>
                        </div>

                        <div class="search-view d-xxl-none">
                            <a href="#"
                                class="search-pop top-quantity d-flex align-items-center text-decoration-none">
                                <i class="flaticon-search flat-mini text-dark mx-auto"></i>
                            </a>
                        </div>
                        <div class="header-cart-1">
                            @if (Auth::guard('web')->check())
                                <a href="{{ route('user-wishlists') }}" class="cart " title="View Wishlist">
                                    <div class="cart-icon"><i class="flaticon-like flat-mini mx-auto text-dark"></i>
                                        <span class="header-cart-count "
                                            id="wishlist-count">{{ Auth::guard('web')->user()->wishlistCount() }}</span>
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('user.login') }}" class="cart " title="View Wishlist">
                                    <div class="cart-icon"><i class="flaticon-like flat-mini mx-auto text-dark"></i>
                                        <span class="header-cart-count"
                                            id="wishlist-count">{{ 0 }}</span></div>
                                </a>
                            @endif
                        </div>

                        <div class="header-cart-1">
                            <a href="{{ route('product.compare') }}" class="cart " title="Compare">
                                <div class="cart-icon"><i class="flaticon-shuffle flat-mini mx-auto text-dark"></i>
                                    <span class="header-cart-count "
                                        id="compare-count">{{ Session::has('compare') ? count(Session::get('compare')->items) : '0' }}</span>
                                </div>
                            </a>
                        </div>

                        <div class="header-cart-1">
                            <a href="{{ route('front.cart') }}" class="cart has-cart-data" title="View Cart">
                                <div class="cart-icon"><i class="flaticon-shopping-cart flat-mini"></i> <span
                                        class="header-cart-count"
                                        id="cart-count1">{{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}</span>
                                </div>
                                <div class="cart-wrap">
                                    <div class="cart-text">@lang('Cart')</div>
                                    <span
                                        class="header-cart-count">{{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}</span>
                                </div>
                            </a>
                            @include('load.cart')
                        </div>
                        <div class="header-user">
                            <a href="#" class="user-icon" id="userDropdownToggle" title="User Menu">
                                <div class="icon">
                                    <i class="fas fa-bell"></i>
                                    <span class="notification-count"
                                        id="notificationCount">{{ session()->get('notifications', []) ? count(session()->get('notifications', [])) : 0 }}</span>
                                    <!-- Notification count added here -->
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-custom" id="userDropdown">
                                <div class="dropdown-header">New Notification(s).</div>
                                @php
                                    $sessionNotifications = session()->get('notifications', []);
                                @endphp
                                <ul>
                                    @foreach ($sessionNotifications as $noti)
                                        <li><a href="{{ url('carts') }}"> {{ $noti['message'] }}</a></li>
                                    @endforeach
                                </ul>
                                <div class="dropdown-footer"><a href="#">Clear All</a></div>
                            </div>
                        </div>
                        <!-- JavaScript -->
                        <script>
                            document.addEventListener("DOMContentLoaded", function() {
                                var userIcon = document.getElementById("userDropdownToggle");
                                var dropdown = document.getElementById("userDropdown");

                                userIcon.addEventListener("click", function(event) {
                                    event.preventDefault();

                                    // Toggle dropdown visibility
                                    if (dropdown.style.display === "block") {
                                        dropdown.style.display = "none";
                                    } else {
                                        dropdown.style.display = "block";

                                        // Ensure dropdown stays inside the screen
                                        var rect = dropdown.getBoundingClientRect();
                                        var screenWidth = window.innerWidth;

                                        if (rect.right > screenWidth) {
                                            dropdown.style.right = "auto";
                                            dropdown.style.left = "-200px";
                                        } else {
                                            dropdown.style.right = "0";
                                            dropdown.style.left = "-200px";
                                        }
                                    }
                                });

                                // Hide dropdown when clicking outside
                                document.addEventListener("click", function(event) {
                                    if (!userIcon.contains(event.target) && !dropdown.contains(event.target)) {
                                        dropdown.style.display = "none";
                                    }
                                });

                                // Ensure dropdown hides on window resize
                                window.addEventListener("resize", function() {
                                    dropdown.style.display = "none";
                                });
                            });
                        </script>

                        <!-- Responsive CSS -->
                        <style>
                            .notification-count {
                                position: absolute;
                                width: 24px;
                                height: 24px;
                                background-color: #424a4d !important;
                                color: var(--theme-white-color);
                                border-radius: 50%;
                                text-align: center;
                                font-size: 11px;
                                line-height: 25px;
                                top: -4px;
                                left: -4px;
                                justify-content: center;
                            }

                            .dropdown-custom {
                                padding: 20px;
                                width: max-content !important;
                            }

                            .dropdown-custom ul {
                                padding: 10px 0;
                            }

                            .dropdown-custom .dropdown-header {
                                display: block;
                                padding: .3rem 1rem;
                                margin-bottom: 0;
                                font-size: .875rem;
                                color: #6c757d;
                                font-weight: 700;
                                white-space: nowrap;
                                border-bottom: 2px solid #6c757d;
                            }

                            .dropdown-custom .dropdown-footer {
                                font-weight: 700;
                            }

                            .header-user {
                                position: relative;
                                display: inline-block;
                            }

                            .dropdown-menu ul li a:hover {
                                color: red !important;
                                /* Text color will turn red on hover */
                            }

                            .user-icon {
                                font-size: 20px;
                                cursor: pointer;
                                color: #333;
                                position: relative;
                            }

                            /* 🔔 Bell Icon Styling */
                            .user-icon .icon {
                                font-size: 24px;
                                color: #333;
                                background: white;
                                padding: 8px;
                                border-radius: 50%;
                                width: 50px;
                                background: var(--theme-light-color);
                                text-align: center;
                            }

                            /* Notification Count Badge */

                            @media screen and (max-width: 1199px) {
                                .user-icon .icon {
                                    line-height: 40px;
                                    width: 40px;
                                    height: 40px;
                                }

                                @media screen and (max-width: 1199px) {
                                    .fa-bell:before {
                                        font-size: 17px;
                                        margin-top: -8px;
                                    }

                                    /* Dropdown Styling */
                                    .dropdown-menu {
                                        display: none;
                                        position: absolute;
                                        top: 40px;
                                        right: 0;
                                        background: #fff;
                                        border: 1px solid #ddd;
                                        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
                                        width: 250px;
                                        border-radius: 5px;
                                        z-index: 1000;
                                        max-width: 90vw;
                                        padding-left: 12px;
                                    }

                                    /* ✅ Mobile Responsive Fix */
                                    @media (max-width: 768px) {
                                        .dropdown-menu {
                                            position: fixed;
                                            top: 50px;
                                            left: 50%;
                                            transform: translateX(-50%);
                                            max-width: 280px;
                                            width: 90%;
                                        }
                                    }
                        </style>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="header-sticky bg-white py-10">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-2 col-xl-2 col-lg-3 col-6 order-lg-1">
                    <div class="d-flex align-items-center h-100 md-py-10">
                        <div class="nav-leftpush-overlay">
                            <nav class="navbar navbar-expand-lg nav-general nav-primary-hover">
                                <button type="button" class="push-nav-toggle d-lg-none border-0">
                                    <i class="flaticon-menu-2 flat-small text-primary"></i>
                                </button>
                                <div class="navbar-slide-push transation-this">
                                    <div
                                        class="login-signup bg-secondary d-flex justify-content-between py-10 px-20 align-items-center">
                                        <a href="{{ route('user.login') }}"
                                            class="d-flex align-items-center text-white">
                                            <i class="flaticon-user flat-small me-1"></i>
                                            <span>Login/Signup</span>
                                        </a>
                                        <span class="slide-nav-close"><i
                                                class="flaticon-cancel flat-mini text-white"></i></span>
                                    </div>
                                    <div class="menu-and-category">
                                        <ul class="nav nav-pills wc-tabs" id="menu-and-category" role="tablist">
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link active" id="pills-push-menu-tab"
                                                    data-bs-toggle="pill" href="#pills-push-menu" role="tab"
                                                    aria-controls="pills-push-menu" aria-selected="true">Menu</a>
                                            </li>
                                            <li class="nav-item" role="presentation">
                                                <a class="nav-link" id="pills-push-categories-tab"
                                                    data-bs-toggle="pill" href="#pills-push-categories"
                                                    role="tab" aria-controls="pills-push-categories"
                                                    aria-selected="true">Categories</a>
                                            </li>
                                        </ul>
                                        <div class="tab-content" id="menu-and-categoryContent">
                                            <div class="tab-pane fade show active woocommerce-Tabs-panel woocommerce-Tabs-panel--description"
                                                id="pills-push-menu" role="tabpanel"
                                                aria-labelledby="pills-push-menu-tab">
                                                <div class="push-navbar">
                                                    <ul class="navbar-nav">
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                                href="{{ route('front.index') }}">{{ __('Home') }}</a>
                                                        </li>
                                                        <li class="nav-item ">
                                                            <a class="nav-link"
                                                                href="{{ route('front.category') }}">{{ __('PRODUCT') }}</a>
                                                        </li>
                                                        <li class="nav-item dropdown">
                                                            <a class="nav-link dropdown-toggle"
                                                                href="#">{{ __('Pages') }}</a>
                                                            <ul class="dropdown-menu">
                                                                @foreach ($pages->where('header', '=', 1) as $data)
                                                                    <li><a class="dropdown-item"
                                                                            href="{{ route('front.vendor', $data->slug) }}">{{ $data->title }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        </li>
                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                                href="{{ route('front.blog') }}">{{ __('Blog') }}</a>
                                                        </li>

                                                        <li class="nav-item">
                                                            <a class="nav-link"
                                                                href="{{ route('front.faq') }}">{{ __('FAQ') }}</a>
                                                        </li>
                                                        <li class="nav-item"><a class="nav-link"
                                                                href="{{ route('front.contact') }}">{{ __('Contact') }}</a>
                                                        </li>
                                                    </ul>

                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="pills-push-categories" role="tabpanel"
                                                aria-labelledby="pills-push-categories-tab">
                                                <div class="categories-menu">
                                                    <ul class="menu">
                                                        @foreach ($categories as $category)
                                                            <li class="menu-item-has-children"><a
                                                                    href="{{ route('front.category', $category->slug) }}">{{ $category->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <a class="navbar-brand" href="{{ route('front.index') }}"><img class="nav-logo"
                                src="{{ asset('assets/images/logo.png') }}" alt="Fabilive Logo"></a>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4 col-lg-3 col-6 order-lg-3">
                    <div class="margin-right-1 d-flex align-items-center justify-content-end h-100 md-py-10">
                        <a class="mobile-screen-api me-2">
                            <div id="mobile_google_translate_element"></div>
                        </a>
                        <div class="sign-in position-relative font-general my-account-dropdown">
                            <a href="my-account.html"
                                class="has-dropdown d-flex align-items-center text-dark text-decoration-none"
                                title="My Account">
                                <i class="flaticon-user-3 flat-mini me-1 mx-auto"></i>
                            </a>
                            <ul class="my-account-popup">
                                @if (Auth::guard('web')->check())
                                    <li><a href="{{ route('user-dashboard') }}"><span
                                                class="menu-item-text">{{ 'User
                                                                                            Panel' }}</span></a>
                                    </li>
                                    @if (Auth::guard('web')->user()->IsVendor())
                                        <li><a href="{{ route('vendor.dashboard') }}"><span
                                                    class="menu-item-text">{{ __('Vendor
                                                                                                Panel') }}</span></a>
                                        </li>
                                    @endif
                                    <li><a href="{{ route('user-profile') }}"><span
                                                class="menu-item-text">{{ __('Edit
                                                                                            Profile') }}</span></a>
                                    </li>
                                    <li><a href="{{ route('user-logout') }}"><span
                                                class="menu-item-text">{{ __('Logout') }}</span></a></li>
                                @elseif(Auth::guard('rider')->check())
                                    <li><a href="{{ route('rider-dashboard') }}"><span
                                                class="menu-item-text">{{ 'User
                                                                                            Panel' }}</span></a>
                                    </li>
                                    <li><a href="{{ route('rider-profile') }}"><span
                                                class="menu-item-text">{{ __('Edit
                                                                                            Profile') }}</span></a>
                                    </li>
                                    <li><a href="{{ route('rider-logout') }}"><span
                                                class="menu-item-text">{{ __('Logout') }}</span></a></li>
                                @else
                                    <li><a href="{{ route('user.login') }}"><span
                                                class="menu-item-text sign-in">{{ __('User
                                                                                            Login') }}</span></a>
                                    </li>
                                    <li><a href="{{ route('rider.login') }}"><span
                                                class="menu-item-text sign-in">{{ __('Delivery Login') }}</span></a>
                                    </li>
                                    <li><a href="{{ route('user.register') }}"><span
                                                class="menu-item-text join">{{ __('Join') }}</span></a></li>
                                @endif
                            </ul>
                        </div>


                        @if (Auth::check())
                            <div class="wishlist-view">
                                <a href="{{ route('user-wishlists') }}"
                                    class="position-relative top-quantity d-flex align-items-center text-white text-decoration-none">
                                    <i class="flaticon-like flat-mini text-dark mx-auto"></i>
                                </a>
                            </div>
                        @else
                            <div class="wishlist-view">
                                <a href="{{ route('user.login') }}"
                                    class="position-relative top-quantity d-flex align-items-center text-white text-decoration-none">
                                    <i class="flaticon-like flat-mini text-dark mx-auto"></i>
                                </a>
                            </div>
                        @endif

                        <div class="refresh-view">
                            <a href="{{ route('product.compare') }}"
                                class="position-relative top-quantity d-flex align-items-center text-dark text-decoration-none">
                                <i class="flaticon-shuffle flat-mini text-dark mx-auto"></i>
                            </a>
                        </div>
                        <div class="header-cart-1">
                            <a href="{{ route('front.cart') }}" class="cart has-cart-data" title="View Cart">
                                <div class="cart-icon"><i class="flaticon-shopping-cart flat-mini"></i> <span
                                        class="header-cart-count"
                                        id="cart-count">{{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}</span>
                                </div>
                                <div class="cart-wrap">
                                    <div class="cart-text">Cart</div>
                                    <span
                                        class="header-cart-count">{{ Session::has('cart') ? count(Session::get('cart')->items) : '0' }}</span>
                                </div>
                            </a>
                            @include('load.cart')
                        </div>
                        <div class="header-user">
                            <a href="#" class="user-icon" id="userDropdownToggle" title="User Menu">
                                <div class="icon">
                                    <i class="fas fa-bell"></i>
                                    <span class="notification-count"
                                        id="notificationCount">{{ session()->get('notifications', []) ? count(session()->get('notifications', [])) : 0 }}</span>
                                    <!-- Notification count added here -->
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-custom" id="userDropdown">
                                <div class="dropdown-header">New Notification(s).</div>
                                @php
                                    $sessionNotifications = session()->get('notifications', []);
                                @endphp
                                <ul>
                                    @foreach ($sessionNotifications as $noti)
                                        <li><a href="{{ url('carts') }}"> {{ $noti['message'] }}</a></li>
                                    @endforeach
                                </ul>
                                <div class="dropdown-footer"><a href="#">Clear All</a></div>
                            </div>
                        </div>


                        <!-- JavaScript -->

                    </div>
                </div>
                <div class="col-xxl-7 col-xl-6 col-lg-6 col-12 order-lg-2">
                    <div class="product-search-one">

                        <form id="searchForm" class="search-form form-inline search-pill-shape"
                            action="{{ route('front.category', [Request::route('category'), Request::route('subcategory'), Request::route('childcategory')]) }}"
                            method="GET">

                            @if (!empty(request()->input('sort')))
                                <input type="hidden" name="sort" value="{{ request()->input('sort') }}">
                            @endif
                            @if (!empty(request()->input('minprice')))
                                <input type="hidden" name="minprice" value="{{ request()->input('minprice') }}">
                            @endif
                            @if (!empty(request()->input('maxprice')))
                                <input type="hidden" name="maxprice" value="{{ request()->input('maxprice') }}">
                            @endif
                            <input type="text" id="prod_name" class="col form-control search-field "
                                name="search" placeholder="Search Product For"
                                value="{{ request()->input('search') }}">
                            <div class=" categori-container select-appearance-none " id="catSelectForm">
                                <select name="category" class="form-control categoris select2-js-search-init">
                                    <option selected="">{{ __('All Categories') }}</option>
                                    @foreach ($categories->where('status', 1) as $data)
                                        <option value="{{ $data->slug }}"
                                            {{ Request::route('category') == $data->slug ? 'selected' : '' }}>
                                            {{ $data->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" name="submit" class="search-submit"><i
                                    class="flaticon-search flat-mini text-white"></i></button>

                        </form>



                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


<style>
    #google_translate_element {
        padding: 0;
        margin-right: 5px;
    }

    .goog-te-banner-frame.skiptranslate,
    .goog-te-gadget-simple img,
    img.goog-te-gadget-icon,
    .goog-te-menu-value span {
        display: none !important;
    }

    .goog-te-menu-frame {
        box-shadow: none !important;
    }

    .goog-te-gadget-simple {
        background-color: transparent !important;
        background: url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' xmlns='http://www.w3.org/2000/svg'%3E%3Cg style='fill:none;stroke:%2308102b;stroke-linecap:round;stroke-linejoin:round'%3E%3Cpath d='M.5,2V18A1.5,1.5,0,0,0,2,19.5H17L10.5.5H2A1.5,1.5,0,0,0,.5,2Z' /%3E%3Cpath d='M12,4.5H22A1.5,1.5,0,0,1,23.5,6V22A1.5,1.5,0,0,1,22,23.5H13.5l-1.5-4' /%3E%3Cline x1='17' x2='13.5' y1='19.5' y2='23.5' /%3E%3Cline x1='14.5' x2='21.5' y1='10.5' y2='10.5' /%3E%3Cline x1='17.5' x2='17.5' y1='9.5' y2='10.5' /%3E%3Cpath d='M20,10.5c0,1.1-1.77,4.42-4,6' /%3E%3Cpath d='M16,13c.54,1.33,4,4.5,4,4.5' /%3E%3Cpath d='M10.1,7.46a4,4,0,1,0,1.4,3h-4' /%3E%3C/g%3E%3C/svg%3E") center / 12px no-repeat;
        background-size: 20px 20px;
        display: inline-block;
        font-weight: 400;
        line-height: 1.8;
        padding: 0 6px;
        text-align: center;
        white-space: nowrap;
        vertical-align: middle;
        -ms-touch-action: manipulation;
        touch-action: manipulation;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border-left: none !important;
        border-top: none !important;
        border-bottom: none !important;
        border-right: none !important;
        border-radius: 4px;
    }

    body {
        top: 0px !important;
    }

    .goog-te-gadget-simple .VIpgJd-ZVi9od-xl07Ob-lTBxed span {
        text-decoration: none;
        display: none;
    }

    .VIpgJd-ZVi9od-ORHb-OEVmcd {
        display: none !important;
    }


    /* Show both Google Translate widgets */
    .mobile-screen-api {
        display: block;
    }

    .computer-screen-gapi {
        display: block;
    }

    /* Hide the mobile widget on larger screens */
    @media (min-width: 768px) {
        .mobile-screen-api {
            display: none;
        }

        .computer-screen-gapi {
            display: block;
        }
    }


    @media (max-width: 767px) {
    .navbar-nav .dropdown-menu {
        position: absolute !important;
        top: 100% !important;   /* directly under Pages */
        left: 0 !important;
        right: auto !important;
        width: 100% !important;
        max-width: 100%;
        transform: none !important;
        z-index: 9999;
        border-radius: 6px;
    }

    /* Prevent clipping */
    .navbar,
    .navbar-collapse {
        overflow: visible !important;
    }
}



    .ecommerce-header .top-linksMob {
        flex-direction: column;
        gap: 3px;
    }
</style>

<script>
    function googleTranslateElementInit() {
        const isMobileScreen = window.innerWidth <= 767;

        if (isMobileScreen) {
            // Initialize the mobile widget
            new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    includedLanguages: 'en,fr',
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                },
                'mobile_google_translate_element'
            );
        } else {
            // Initialize the desktop widget
            new google.translate.TranslateElement({
                    pageLanguage: 'en',
                    includedLanguages: 'en,fr',
                    layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                },
                'desktop_google_translate_element'
            );
        }
    }

    // Load the Google Translate script dynamically
    var translateScript = document.createElement('script');
    translateScript.type = 'text/javascript';
    translateScript.async = true;
    translateScript.src = "//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit";
    document.getElementsByTagName('head')[0].appendChild(translateScript);
</script>
<!--==================== Header Section End ====================-->
