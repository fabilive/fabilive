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
                                                 {!! NoCaptcha::renderJs() !!}
                                                 @error('g-recaptcha-response')
                                                 <p class="my-2">{{$message}}</p>
                                                 @enderror
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
<script type="text/javascript">
    let isSubmitting = false;
    $(document).ready(function() {
        $("#registerform").off('submit').on('submit', function(e){
            e.preventDefault();
            if (isSubmitting) return;
            isSubmitting = true;
            $('.submit-btn').html('Processing...');
            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(response){
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
                error: function(xhr){
                    $('.submit-btn').html('Register');
                    alert('Registration failed. Please try again.');
                    isSubmitting = false;
                }
            });
        });
    });
</script>
@endsection