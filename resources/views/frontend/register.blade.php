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

                        <li class="breadcrumb-item active" aria-current="page">{{ __('Register') }}</li>
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
                                        <h3>{{ __('Signup Now') }}</h3>
                                        <form id="registerform" action="{{route('user-register-submit')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="source" value="{{ request()->source }}">
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
                                                <input type="password" name="password" class="form-control" required=""  placeholder="{{ __('Password') }}" >
                                            </p>
                                            <p>
                                                <input type="password" name="password_confirmation" class="form-control" required=""  placeholder="{{ __('Confirm Password') }}" >
                                            </p>
                                            <p>
                                                <input type="text" name="referral_code" class="form-control" placeholder="{{ __('Referral Code (Optional)') }}" value="{{ Session::has('affilate_code') ? Session::get('affilate_code') : (Session::has('custom_referral_code') ? Session::get('custom_referral_code') : '') }}">
                                            </p>
                                            @if(request()->source == 'sell')
                                            <div class="form-group mb-3">
                                                <label><strong>{{ __('Live Selfie Verification') }} *</strong></label>
                                                <p class="small text-muted">{{ __('Please take a live selfie for identity verification.') }}</p>
                                                
                                                <div id="selfie-container" class="text-center">
                                                    <video id="selfie-video" width="100%" autoplay playsinline class="rounded border mb-2" style="display:none; max-width: 400px;"></video>
                                                    <canvas id="selfie-canvas" style="display:none;"></canvas>
                                                    <img id="selfie-preview" src="" class="img-fluid rounded border mb-2" style="display:none; max-width: 400px;">
                                                    
                                                    <div class="btn-group w-100">
                                                        <button type="button" id="start-camera" class="btn btn-sm btn-info">{{ __('Open Camera') }}</button>
                                                        <button type="button" id="capture-photo" class="btn btn-sm btn-success" style="display:none;">{{ __('Capture Selfie') }}</button>
                                                        <button type="button" id="retake-photo" class="btn btn-sm btn-warning" style="display:none;">{{ __('Retake') }}</button>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="selfie" id="selfie-input" required>
                                            </div>
                                            @endif

                                            <input id="processdata" type="hidden" value="{{ __('Processing...') }}">
                                                <button class="btn btn-primary float-none w-100 rounded-0 submit-btn" name="register" value="Register">{{ __('Register') }}</button>
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
@include('partials.global.common-footer')
@endsection
@section('script')

    {{-- V90.7: Captcha JS DISABLED for recovery --}}
    {{-- @if($gs->is_capcha == 1)
        {!! NoCaptcha::renderJs() !!}
    @endif --}}

    <script src="{{ asset('assets/js/selfie-capture.js') }}"></script>

<script type="text/javascript">
    let isSubmitting = false;
    $(document).ready(function() {
        @if(request()->source == 'sell')
        if (window.SelfieCapture) {
            SelfieCapture.init('#selfie-video', '#selfie-canvas', '#selfie-input', '#selfie-preview');
            
            $('#start-camera').on('click', async function() {
                const started = await SelfieCapture.startCamera();
                if (started) {
                    $('#selfie-video').show();
                    $('#start-camera').hide();
                    $('#capture-photo').show();
                }
            });
            
            $('#capture-photo').on('click', function() {
                SelfieCapture.capture();
                $('#capture-photo').hide();
                $('#retake-photo').show();
            });
            
            $('#retake-photo').on('click', function() {
                SelfieCapture.retake();
                $('#retake-photo').hide();
                $('#capture-photo').show();
            });
        }
        @endif

        $("#registerform").off('submit').on('submit', function(e){
            e.preventDefault();
            e.stopImmediatePropagation();
            if (isSubmitting) return;
            isSubmitting = true;
            console.log("Registration submitting...");
            $('.submit-btn').html('Registering... <i class="fas fa-spinner fa-spin"></i>');
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                timeout: 15000, // 15 second safety timeout
                success: function(response){
                    console.log("Registration response received:", response);
                    if(response == 1) {
                        alert('Registration Successful!');
                        @if(request()->source == 'sell')
                            window.location.href = '{{ route("user-vendor-request", 8) }}';
                        @else
                            window.location.href = '{{ route("user-dashboard") }}';
                        @endif
                    } else if(typeof response === 'string') {
                        alert(response);
                        window.location.reload();
                    } else if(response.errors) {
                        $('.submit-btn').html('Register');
                        let err = '';
                        for(let e in response.errors) {
                            err += response.errors[e] + '\n';
                        }
                        alert(err);
                        isSubmitting = false;
                    }
                },
                error: function(xhr, status, error){
                    console.error("Registration AJAX error:", status, error);
                    $('.submit-btn').html('Register');
                    if (status === 'timeout') {
                        alert('The server is taking too long to respond. Please check your internet or try again later.');
                    } else {
                        alert('Registration failed. Please try again.');
                    }
                    isSubmitting = false;
                }
            });
        });
    });
</script>
@endsection