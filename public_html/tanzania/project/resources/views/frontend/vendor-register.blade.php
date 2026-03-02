@extends('layouts.front')

@section('content')
@include('partials.global.common-header')

 <!-- breadcrumb -->
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('Register') }}</h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>

                        <li class="breadcrumb-item active" aria-current="page">{{ __('Vendor Register') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->

        <!--==================== Registration Form Start ====================-->
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="woocommerce">
                            <div class="row">
                                <div class="col-lg-6 col-md-8 col-12 mx-auto">
                                    <div class="registration-form border">
                                        @include('includes.admin.form-login')
                                        <h3>{{ __('Vendor Registration') }}</h3>
                                        <form id="registerform" action="{{route('user-register-submit')}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <p>

                                                <input type="text" name="name" class="form-control" placeholder="{{ __('Full Name') }}"  >
                                            </p>
                                            <p>

                                                <input type="email" name="email" class="form-control" required=""  placeholder="{{ __('Email Address') }}" >
                                            </p>
                                            <p>

                                                <input type="text" name="phone" class="form-control" required=""  placeholder="{{ __('Phone Number') }}" >
                                            </p>
                                            <p>
                                                <input type="text" name="address" class="form-control" required=""  placeholder="{{ __('Address') }}" >
                                            </p>
                                            <p>
                                                <input type="text" name="shop_name" class="form-control" required=""  placeholder="{{ __('Shop Name') }}" >
                                            </p>
                                            <p>
                                                <input type="text" name="owner_name" class="form-control" required=""  placeholder="{{ __('Shop Owner Name') }}" >
                                            </p>
                                            <p>
                                                <input type="text" name="shop_number" class="form-control" required=""  placeholder="{{ __('Shop Number') }}" >
                                            </p>
                                            <p>
                                                <input type="text" name="shop_address" class="form-control" required=""  placeholder="{{ __('Shop Address') }}" >
                                            </p>

                                            <p>
                                                <input type="text" name="reg_number" class="form-control" required=""  placeholder="{{ __('Taxpayer Registration Number') }}" >
                                            </p>
                                            <p>
                                                <input type="text" name="shop_message" class="form-control" required=""  placeholder="{{ __('Shop Message') }}" >
                                            </p>
                                            
                                            <!-- 6 New Points 18/08/2025 -->
                                            <p>
                                                <label
                                                    for="business_registration_certificate">{{ __('Business Registration Certificate [if available]') }}</label>
                                                <input type="file" name="business_registration_certificate"
                                                    id="business_registration_certificate" class="form-control" required="">
                                            </p>
                                            <p>
                                                <label
                                                    for="tin">{{ __('Taxpayer Card copy or Taxpayer Identification Number (TIN) document copy [must]') }}</label>
                                                <input type="file" name="tin"
                                                    id="tin" class="form-control" required="">
                                            </p>
                                            <p>
                                                <label
                                                    for="id_card_copy">{{ __('National ID Card Copy') }}</label>
                                                <input type="file" name="id_card_copy"
                                                    id="id_card_copy" class="form-control" required="">
                                            </p>
                                            <p>
                                                <label
                                                    for="passport_copy">{{ __('Passport Copy') }}</label>
                                                <input type="file" name="passport_copy"
                                                    id="passport_copy" class="form-control" required="">
                                            </p>
                                            <p>
                                                <label
                                                    for="driver_license_copy">{{ __('Drivers License Copy') }}</label>
                                                <input type="file" name="driver_license_copy"
                                                    id="driver_license_copy" class="form-control" required="">
                                            </p>
                                            <p>
                                                <label
                                                    for="residence_permit">{{ __('Residence Permit for foreigners [must]') }}</label>
                                                <input type="file" name="residence_permit"
                                                    id="residence_permit" class="form-control" required="">
                                            </p>
                                            <!-- 6 New Points End -->
                                            
                                            <p>
                                                <label
                                                    for="national_id_front_image">{{ __('National ID Front Image') }}</label>
                                                <input type="file" name="national_id_front_image"
                                                    id="national_id_front_image" class="form-control" required="">
                                            </p>

                                        <p>
                                            <label for="national_id_back_image">{{ __('National ID Back Image') }}</label>
                                            <input type="file" name="national_id_back_image" id="national_id_back_image"
                                                class="form-control" required=""
                                                placeholder="{{ __('National ID Back Image') }}">
                                        </p>

                                        <p>
                                            <label for="license_image">{{ __('License Image') }}</label>
                                            <input type="file" name="license_image" id="license_image"
                                                class="form-control" required=""
                                                placeholder="{{ __('License Image') }}">
                                        </p>
                                        
                                            <p>
                                                <label
                                                    for="submerchant_agreement">{{ __('Fabilive Sub-Merchant Agreement') }}</label>
                                                <input type="file" name="submerchant_agreement"
                                                    id="submerchant_agreement" class="form-control" required="">
                                            </p>
                                            
                                            <p>
                                                    <label>{{ __('Please Download Fabilive Sub-Merchant Agreement') }}</label>
                                                <a href="{{ asset('assets/pdf/fabilive.pdf') }}" target="_blank">
                                        {{ __('Fabilive Sub-Merchant Agreement') }}
                                                </a>
                                            </p>

                                            <p>
                                                <input type="password" name="password" class="form-control" required=""  placeholder="{{ __('Password') }}" >
                                            </p>
                                            <p>
                                                <input type="password" name="password_confirmation" class="form-control" required=""  placeholder="{{ __('Confirm Password') }}" >
                                            </p>

                                            @if($gs->is_capcha == 1)
                                            <!--<div class="form-input mb-3">-->
                                            <!--     {!! NoCaptcha::display() !!}-->
                                            <!--     {!! NoCaptcha::renderJs() !!}-->
                                            <!--     @error('g-recaptcha-response')-->
                                            <!--     <p class="my-2">{{$message}}</p>-->
                                            <!--     @enderror-->
                                            <!-- </div>-->
                                             
                                             <div class="form-input mb-3">
                                                 <div data-sitekey="6Le36XQrAAAAAI6rT9SvZvhurUzrU9WhwpXyZ_1a" class="g-recaptcha"></div>
                                                 {!! NoCaptcha::renderJs() !!}
                                                 @error('g-recaptcha-response')
                                                 <p class="my-2">{{$message}}</p>
                                                 @enderror
                                             </div>
                                             @endif
                                             <input type="hidden" name="vendor"  value="1">
                                            <input id="processdata" type="hidden" value="{{ __('Processing...') }}">
                                                <button type="submit" class="btn btn-primary float-none w-100 rounded-0 submit-btn" name="register" value="Register">{{ __('Register') }}</button>
                                            </p>
                                        </form>
                                        <p>
                                                {{ __("Do have any account?") }}<a href="{{ route('user.login') }}"  class="text-secondary">{{__(' Login')}}</a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--==================== Registration Form Start ====================-->


@include('partials.global.common-footer')
@endsection

@section('script')


@endsection
