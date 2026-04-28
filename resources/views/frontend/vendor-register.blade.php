@extends('layouts.front')

@section('content')
    @include('partials.global.common-header')

    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/' . $gs->breadcrumb_banner) : asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
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
                                    <form id="registerform" action="{{ route('user-register-submit') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <p>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="{{ __('Full Name') }}">
                                        </p>
                                        <p>
                                            <input type="email" name="email" class="form-control" required=""
                                                placeholder="{{ __('Email Address') }}">
                                        </p>
                                        <p>
                                            <input type="text" name="phone" class="form-control" required=""
                                                placeholder="{{ __('Phone Number') }}">
                                        </p>
                                        <p>
                                            <input type="text" name="address" class="form-control" required=""
                                                placeholder="{{ __('Address') }}">
                                        </p>
                                        <p>
                                            <input type="text" name="shop_name" class="form-control" required=""
                                                placeholder="{{ __('Shop Name') }}">
                                        </p>
                                        <p>
                                            <input type="text" name="owner_name" class="form-control" required=""
                                                placeholder="{{ __('Shop Owner Name') }}">
                                        </p>

                                        <p>
                                            <input type="text" name="reg_number" class="form-control" required=""
                                                placeholder="{{ __('Taxpayer Registration Number') }}">
                                        </p>
                                        <p>
                                            <input type="text" name="shop_message" class="form-control" required=""
                                                placeholder="{{ __('Shop Message') }}">
                                        </p>


                                            <p>
                                                <label for="national_id_front_image">National ID Front Image</label>
                                                <input type="file" name="national_id_front_image" id="national_id_front_image" class="form-control" required>
                                            </p>
                                            <p>
                                                <label for="national_id_back_image">National ID Back Image</label>
                                                <input type="file" name="national_id_back_image" id="national_id_back_image" class="form-control" required>
                                            </p>
                                            {{-- <p>
                                                <label for="license_image">License Image</label>
                                                <input type="file" name="license_image" id="license_image" class="form-control">
                                            </p> --}}

                                            @foreach($agreements->where('type', 'Fabilive_Sub_merchant_Agreement') as $agreement)
                                            <p>
                                                <label>{{ __('Please Download, Sign, and upload the Fabilive Sub-Merchant Agreement') }}</label>
                                                <a href="{{ asset($agreement->image) }}" target="_blank">
                                                    {{ __('Fabilive Sub-Merchant Agreement') }} <i class="fa fa-download"></i>
                                                </a>
                                            </p>
                                        @endforeach


                                        <p>
                                            <label
                                                for="submerchant_agreement">{{ __('Fabilive Sub-Merchant Agreement') }}</label>
                                            <input type="file" name="submerchant_agreement" id="submerchant_agreement"
                                                class="form-control" required="">
                                        </p>


                                        <!-- -->


                                        <div class="row mb-2">
                                            <div class="col-12">

                                                <input type="file" id="selfieFile" class="w-100" name="selfie_image" style="display:none;">

                                                <!-- Video element full width -->
                                                <video id="cam" class="w-100 rounded mb-2" style="display:none;"></video>

                                                <!-- Preview image full width -->
                                                <img id="preview" class="w-100 rounded mb-2" style="display:none;">

                                                <button type="button" class="btn btn-dark btn-sm rounded-2 w-100 mb-2" id="openCamera">
                                                    Open Camera <i class="fa fa-camera"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-dark rounded-2 w-100 mb-2" id="capture" style="display:none;">
                                                    Capture
                                                </button>

                                                <script>
                                                document.addEventListener('DOMContentLoaded', () => {
                                                    const video = document.getElementById('cam');
                                                    const preview = document.getElementById('preview');
                                                    const openBtn = document.getElementById('openCamera');
                                                    const captureBtn = document.getElementById('capture');
                                                    const fileInput = document.getElementById('selfieFile');
                                                    let stream = null;

                                                    openBtn.addEventListener('click', async () => {
                                                        try {
                                                            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                                                                throw new Error('Camera access is not supported in this browser or requires a secure (HTTPS) connection.');
                                                            }
                                                            stream = await navigator.mediaDevices.getUserMedia({ video: true });
                                                            video.srcObject = stream;
                                                            await video.play();
                                                            video.style.display = 'block';
                                                            captureBtn.style.display = 'inline-block';
                                                            openBtn.style.display = 'none';
                                                        } catch (err) {
                                                            alert('Error: ' + err.message);
                                                            console.error('Camera access error:', err);
                                                        }
                                                    });

                                                    captureBtn.addEventListener('click', () => {
                                                        const canvas = document.createElement('canvas');
                                                        canvas.width = video.videoWidth;
                                                        canvas.height = video.videoHeight;
                                                        canvas.getContext('2d').drawImage(video, 0, 0);

                                                        canvas.toBlob(blob => {
                                                            const file = new File([blob], 'selfie.jpg', { type: 'image/jpeg' });
                                                            const dt = new DataTransfer();
                                                            dt.items.add(file);
                                                            fileInput.files = dt.files;

                                                            if(stream) {
                                                                stream.getTracks().forEach(t => t.stop());
                                                            }
                                                            video.style.display = 'none';
                                                            preview.src = URL.createObjectURL(file);
                                                            preview.style.display = 'block';
                                                            captureBtn.style.display = 'none';
                                                            openBtn.textContent = 'Retake Selfie';
                                                            openBtn.style.display = 'inline-block';
                                                        }, 'image/jpeg', 0.9);
                                                    });
                                                });
                                                </script>

                                            </div>
                                        </div>


                                        <p>
                                            <input type="password" name="password" class="form-control" required=""
                                                placeholder="{{ __('Password') }}">
                                        </p>
                                        <p>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                required="" placeholder="{{ __('Confirm Password') }}">
                                        </p>
                                        @if ($gs->is_capcha == 1)
                                            <div class="form-input mb-3">
                                                 {!! NoCaptcha::display() !!}
                                                @error('g-recaptcha-response')
                                                    <p class="my-2">{{ $message }}</p>
                                                @enderror
                                            </div>
                                        @endif
                                        <input type="hidden" name="vendor" value="1">
                                        <input id="processdata" type="hidden" value="{{ __('Processing...') }}">
                                        <button type="submit"
                                            class="btn btn-primary float-none w-100 rounded-0 submit-btn" name="register"
                                            value="Register">{{ __('Register') }}</button>
                                        </p>
                                    </form>
                                    <p>
                                        {{ __('Do have any account?') }}<a href="{{ route('user.login') }}"
                                            class="text-secondary">{{ __(' Login') }}</a>
                                    </p>
                                    @php
                                        $socialsetting = App\Models\Socialsetting::find(1);
                                    @endphp
                                    @if($socialsetting->g_check == 1)
                                        <div class="social-area text-center">
                                            <h3 class="title mt-3">{{ 'OR' }}</h3>
                                            <a href="{{ route('social-provider', ['provider' => 'google', 'role' => 'seller']) }}" class="btn btn-outline-danger w-100 mt-2 d-flex align-items-center justify-content-center">
                                                <i class="fab fa-google mr-2"></i> &nbsp; {{ __('Continue with Google') }}
                                            </a>
                                        </div>
                                    @endif

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
    @if($gs->is_capcha == 1)
        {!! NoCaptcha::renderJs() !!}
    @endif
@endsection
