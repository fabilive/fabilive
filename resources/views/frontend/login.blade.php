@extends('layouts.front')
@php use Anhskohbo\NoCaptcha\Facades\NoCaptcha; @endphp

@section('content')
    @include('partials.global.common-header')
    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/' . $gs->breadcrumb_banner) : asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
        <div class="container">
            <div class="row text-center text-white">
                <div class="col-12">
                    <h3 class="mb-2 text-white">{{ __('Login Page') }}</h3>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>

                            <li class="breadcrumb-item active" aria-current="page">{{ __('Login') }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->
    <!--==================== Login Form Start ====================-->
    <div class="full-row">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="woocommerce">
                        <div class="row">
                            <div class="col-lg-6 col-md-8 col-12 mx-auto">
                                <div class="sign-in-form border">
                                    <h3>{{ __('User Login') }}</h3>

                                    @include('alerts.admin.form-login')

                                    @if (Session::has('auth-modal'))
                                        <div class="alert alert-danger alert-dismissible">

                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            {{ Session::get('auth-modal') }}
                                        </div>
                                    @endif

                                    @if (Session::has('forgot-modal'))
                                        <div class="alert alert-success alert-dismissible">

                                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                                            {{ Session::get('forgot-modal') }}
                                        </div>
                                    @endif
                                    <form class="woocommerce-form-login" id="loginform"
                                        action="{{ route('user.login.submit') }}" method="POST">
                                        @csrf
                                        <p>
                                            <label for="username">{{ __('Email address') }}<span
                                                    class="required">*</span></label>
                                            <input type="email" class="form-control" name="email" id="username"
                                                placeholder="{{ __('Type Email Address') }}" required="">
                                        </p>
                                        <p>
                                            <label for="password">{{ __('Password') }}<span
                                                    class="required">*</span></label>
                                            <input class="form-control" type="password" name="password" id="password"
                                                placeholder="{{ __('Type Password') }}" required="">
                                        </p>
                                        <div class="d-flex justify-content-between">
                                            <p>
                                                <a href="{{ route('user.register') }}"
                                                    class="text-secondary">{{ __("Don't have any account?") }}</a>
                                            </p>
                                            <p>
                                                <a href="{{ route('user.forgot') }}"
                                                    class="text-secondary">{{ __('Lost your password?') }}</a>
                                            </p>

                                        </div>
                                        
                                        <input type="hidden" name="modal" value="1">
                                        @if (Session::has('auth-modal'))
                                            <input type="hidden" name="auth_modal" value="1">
                                        @endif
                                        <input id="authdata" type="hidden" value="{{ __('Authenticating...') }}">
                                        @if($gs->is_capcha == 1)
                                            <div class="form-input mb-3">
                                                 {!! NoCaptcha::display() !!}
                                                 
                                                 @error('g-recaptcha-response')
                                                 <p class="my-2">{{$message}}</p>
                                                 @enderror
                                             </div>
                                        @endif
                                        
                                        
                                        <button type="submit"
                                            class="woocommerce-form-login__submit btn btn-primary border-0 rounded-0 submit-btn float-none w-100"
                                            name="login" value="Log in">{{ __('Log in') }}</button>

                                            <div class="social-area text-center mt-4">
                                                <div class="border-top pt-3">
                                                    <p class="text-muted mb-3">{{ __("OR") }}</p>
                                                    <a href="{{ route('social-provider', ['provider' => 'google', 'role' => 'buyer']) }}" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center py-2 shadow-sm border-2 rounded-2">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 48 48" style="margin-right: 10px;"><path fill="#FFC107" d="M43.611 20.083H42V20H24v8h11.303c-1.649 4.657-6.08 8-11.303 8c-6.627 0-12-5.373-12-12s5.373-12 12-12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C12.954 4 4 12.954 4 24s8.954 20 20 20s20-8.954 20-20c0-1.341-.138-2.65-.389-3.917z"/><path fill="#FF3D00" d="m6.306 14.691l6.571 4.819C14.655 15.108 18.961 12 24 12c3.059 0 5.842 1.154 7.961 3.039l5.657-5.657C34.046 6.053 29.268 4 24 4C16.318 4 9.656 8.337 6.306 14.691z"/><path fill="#4CAF50" d="M24 44c5.166 0 9.86-1.977 13.409-5.192l-6.19-5.238A11.91 11.91 0 0 1 24 36c-5.202 0-9.619-3.317-11.283-7.946l-6.522 5.025C9.505 39.556 16.227 44 24 44z"/><path fill="#1976D2" d="M43.611 20.083H42V20H24v8h11.303a12.04 12.04 0 0 1-4.087 5.571l.003-.002l6.19 5.238C36.971 39.205 44 34 44 24c0-1.341-.138-2.65-.389-3.917z"/></svg>
                                                        <span style="font-weight: 600; color: #333;">{{ __('Continue with Google') }}</span>
                                                    </a>
                                                </div>
                                            </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--==================== Login Form Start ====================-->
    @includeIf('partials.global.common-footer')
@endsection
@section('script')
    @if($gs->is_capcha == 1)
        {!! NoCaptcha::renderJs() !!}
    @endif
@endsection
