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
                                        <div class="social-area text-center mt-4">
                                            <div class="border-top pt-3">
                                                <p class="text-muted mb-3">{{ __("OR") }}</p>
                                                <a href="{{ route('social-provider', ['provider' => 'google', 'role' => 'seller']) }}" class="btn btn-outline-dark w-100 d-flex align-items-center justify-content-center py-2 shadow-sm border-2 rounded-2">
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
    <!--==================== Registration Form Start ====================-->


    @include('partials.global.common-footer')
@endsection

@section('script')
    @if($gs->is_capcha == 1)
        {!! NoCaptcha::renderJs() !!}
    @endif
@endsection
