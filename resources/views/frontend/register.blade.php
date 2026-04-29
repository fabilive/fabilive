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
                                            @if($gs->is_capcha == 1)
                                                <div class="form-input mb-3">
                                                     {!! NoCaptcha::display() !!}
                                                     @error('g-recaptcha-response')
                                                     <p class="my-2 text-danger">{{$message}}</p>
                                                     @enderror
                                                 </div>
                                            @endif
                                            <button class="btn btn-primary float-none w-100 rounded-0 submit-btn" name="register" value="Register">{{ __('Register') }}</button>
                                        </form>
                                        <p>
                                                {{ __("Do have any account?") }}<a href="{{ route('user.login') }}"  class="text-secondary">{{__(' Login')}}</a>
                                        </p>
                                        @php
                                            $socialsetting = App\Models\Socialsetting::find(1);
                                        @endphp
                                            <div class="social-area text-center mt-4">
                                                <div class="border-top pt-3">
                                                    <p class="text-muted mb-3">{{ __("OR") }}</p>
                                                    <a href="{{ route('social-provider', ['provider' => 'google', 'role' => 'buyer']) }}" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center py-2 shadow-sm border-2 rounded-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" style="margin-right: 10px;"><path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.954 4 4 12.954 4 24s8.954 20 20 20s20-8.954 20-20c0-1.341-.138-2.65-.389-3.917z"/><path fill="#FF3D00" d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z"/><path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/><path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/></svg>
                                                        <span style="font-weight: 600; color: #333;">{{ __('Continue with Google') }}</span>
                                                    </a>
                                                </div>
                                            </div>
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

    @if($gs->is_capcha == 1)
        {!! NoCaptcha::renderJs() !!}
    @endif

    <script src="{{ asset('assets/js/selfie-capture.js') }}"></script>

<script type="text/javascript">
    let isSubmitting = false;
    $(document).ready(function() {


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
                        @if(request()->source == 'seller_application')
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
