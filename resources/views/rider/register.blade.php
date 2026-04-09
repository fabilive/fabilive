@extends('layouts.front')
@section('content')
@include('partials.global.common-header')
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
        <div class="full-row">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <div class="woocommerce">
                            <div class="row">
                                <div class="col-lg-6 col-md-8 col-12 mx-auto">
                                    <div class="registration-form border">
                                        @include('includes.admin.form-login')
                                        <h3>{{ __('Delivery Register') }}</h3>
                                        <form id="registerform" action="{{route('rider-register-submit')}}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            <p>
                                                <label for="rider_type">{{ __('Rider Type') }}</label>
                                                <select name="rider_type" id="rider_type" class="form-control" required>
                                                    <option value="">{{ __('Select Rider Type') }}</option>
                                                    <option value="company">{{ __('Company') }}</option>
                                                    <option value="individual">{{ __('Individual') }}</option>
                                                </select>
                                            </p>

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
                                                <label for="national_id_front_image">{{ __('National ID Front Image') }}</label>
                                                <input type="file" name="national_id_front_image" id="national_id_front_image" class="form-control" required="">
                                            </p>

                                            <div id="company_fields" style="display:none;">
                                                <div class="alert alert-info mb-4">
                                                    <label><strong>{{ __('Step 1: Download & Sign') }}</strong></label>
                                                    <p>{{ __('Please download the Delivery Company Agreement, sign it, and you will upload it at the bottom of this section.') }}</p>
                                                    <a href="{{ asset('assets/images/submerchantagreementrider/1773713000CompanyAgreement.pdf') }}" target="_blank" class="btn btn-sm btn-info text-white">
                                                        <i class="fa fa-download"></i> {{ __('Download Delivery Company Agreement') }}
                                                    </a>
                                                </div>

                                                <p>
                                                    <label for="">{{ __('Company Registration Documents') }}</label>
                                                    <input type="file" name="company_registration_document" class="form-control">
                                                </p>
                                                <p>
                                                    <label for="">{{ __('Valid ID/Passport Of Company Owner or Representative.') }}</label>
                                                    <input type="file" name="id_company_owner" class="form-control">
                                                </p>

                                                <p>
                                                    <label for="selfieFile1">{{ __('Live Selfie (Required - Use Camera)') }}</label>
                                                    <input type="hidden" id="selfieFile1" name="live_selfie_company">
                                                </p>
                                                <video id="cam_company" class="w-100 rounded mb-2" style="display:none;"></video>
                                                <img id="preview_company" class="w-100 rounded mb-2" style="display:none;">
                                                <button type="button" class="btn btn-sm btn-dark w-100 rounded-2 mb-2" id="openCamera_company">{{ __('Use Camera') }}</button>
                                                <button type="button" id="capture_company" class="btn btn-sm btn-dark w-100 rounded-2 mb-2" style="display:none;">{{ __('Capture') }}</button>

                                                <p>
                                                    <label for="">{{ __('Transport License / Permis de Transport.') }}</label>
                                                    <input type="file" name="transport_license" class="form-control">
                                                </p>
                                                <p>
                                                    <label for="">{{ __('Insurance Certificate') }}</label>
                                                    <input type="file" name="insurance_certificate_company" class="form-control">
                                                </p>
                                                <p>
                                                    <label for="">{{ __('Taxpayer Registration number (TIN)') }}</label>
                                                    <input type="text" name="tin_company" class="form-control">
                                                </p>
                                                <p>
                                                    <label for="submerchant_agreement_company">{{ __('Step 2: Upload Signed Delivery Company Agreement') }} *</label>
                                                    <div class="mb-2">
                                                        <a href="{{ asset('assets/images/submerchantagreementrider/1773713000CompanyAgreement.pdf') }}" target="_blank" class="text-info small">
                                                            <i class="fa fa-download"></i> {{ __('Re-download Agreement') }}
                                                        </a>
                                                    </div>
                                                    <input type="file" name="submerchant_agreement_company" id="submerchant_agreement_company" class="form-control agreement-upload">
                                                </p>
                                            </div>

                                            <div id="individual_fields" style="display:none;">
                                                <div class="alert alert-info mb-4">
                                                    <label><strong>{{ __('Step 1: Download & Sign') }}</strong></label>
                                                    <p>{{ __('Please download the Delivery Agent Agreement, sign it, and you will upload it at the bottom of this section.') }}</p>
                                                    <a href="{{ asset('assets/images/submerchantagreementrider/1773713000IndividualAgreement.pdf') }}" target="_blank" class="btn btn-sm btn-info text-white">
                                                        <i class="fa fa-download"></i> {{ __('Download Delivery Agent Agreement') }}
                                                    </a>
                                                </div>

                                                <p>
                                                    <select name="vehicle_type_individual" class="form-control">
                                                        <option value="">{{ __('Select Vehicle Type') }}</option>
                                                        <option value="bike">{{ __('MotorBike') }}</option>
                                                        <option value="truck">{{ __('Light Duty Trucks') }}</option>
                                                        <option value="car">{{ __('Small Cars') }}</option>
                                                        <option value="van">{{ __('Vans') }}</option>
                                                        <option value="pickup">{{ __('Pickup Trucks') }}</option>
                                                    </select>
                                                </p>
                                                <p>
                                                    <label for="">{{ __('Taxpayer Registration number (TIN)') }}</label>
                                                    <input type="text" name="tin_individual" class="form-control">
                                                </p>
                                                <p>
                                                    <label for="">{{ __('Drivers License image') }}</label>
                                                    <input type="file" name="driver_license_individual" class="form-control">
                                                </p>
                                                <p>
                                                    <label for="selfieFile">{{ __('Live Selfie (Required - Use Camera)') }}</label>
                                                    <input type="hidden" id="selfieFile" name="live_selfie_individual">
                                                </p>
                                                <video id="cam_individual" class="w-100 rounded mb-2" style="display:none;"></video>
                                                <img id="preview_individual" class="w-100 rounded mb-2" style="display:none;">
                                                <button type="button" class="btn btn-sm btn-dark w-100 rounded-2 mb-2" id="openCamera_individual">{{ __('Use Camera') }}</button>
                                                <button type="button" id="capture_individual" class="btn btn-sm btn-dark w-100 rounded-2 mb-2" style="display:none;">{{ __('Capture') }}</button>
                                                <p>
                                                    <label for="">{{ __('Vehicle Registration Certificate (Carte Grise)') }}</label>
                                                    <input type="file" name="vehicle_registration_certificate" class="form-control">
                                                </p>
                                                <p>
                                                    <label for="">{{ __('Insurance Certificate') }}</label>
                                                    <input type="file" name="insurance_certificate_individual" class="form-control">
                                                </p>
                                                <p>
                                                    <label for="">{{ __('Criminal records / Police Report') }}</label>
                                                    <input type="file" name="criminal_records" class="form-control">
                                                </p>
                                                <p>
                                                    <label for="submerchant_agreement_individual">{{ __('Step 2: Upload Signed Delivery Agent Agreement') }} *</label>
                                                    <div class="mb-2">
                                                        <a href="{{ asset('assets/images/submerchantagreementrider/1773713000IndividualAgreement.pdf') }}" target="_blank" class="text-info small">
                                                            <i class="fa fa-download"></i> {{ __('Re-download Agreement') }}
                                                        </a>
                                                    </div>
                                                    <input type="file" name="submerchant_agreement_individual" id="submerchant_agreement_individual" class="form-control agreement-upload">
                                                </p>
                                            </div>

                                            <input type="hidden" name="submerchant_agreement" id="final_submerchant_agreement">

                                            <p>
                                                <label for="national_id_back_image">{{ __('National ID Back Image') }}</label>
                                                <input type="file" name="national_id_back_image" id="national_id_back_image" class="form-control" required=""  placeholder="{{ __('National ID Back Image') }}" >
                                            </p>
                                            <p>
                                                <label for="license_image">{{ __('Drivers License image') }}</label>
                                                <input type="file" name="license_image" id="license_image" class="form-control" required=""  placeholder="{{ __('Drivers License image') }}" >
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
                                                <button type="submit" class="btn btn-primary float-none w-100 rounded-0 submit-btn" name="register" value="Register">{{ __('Register') }}</button>
                                            </p>
                                        </form>
                                        <p>
                                                {{ __("Do have any account?") }}<a href="{{ route('rider.login') }}"  class="text-secondary">{{__(' Login')}}</a>
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
<script>
document.addEventListener('DOMContentLoaded', function () {
    const riderType = document.getElementById('rider_type');
    const companyFields = document.getElementById('company_fields');
    const individualFields = document.getElementById('individual_fields');

    const companyUpload = document.getElementById('submerchant_agreement_company');
    const individualUpload = document.getElementById('submerchant_agreement_individual');
    const finalUpload = document.getElementById('final_submerchant_agreement');

    riderType.addEventListener('change', function () {
        if (this.value === 'company') {
            companyFields.style.display = 'block';
            individualFields.style.display = 'none';
            companyUpload.setAttribute('required', 'required');
            individualUpload.removeAttribute('required');
        } else if (this.value === 'individual') {
            companyFields.style.display = 'none';
            individualFields.style.display = 'block';
            individualUpload.setAttribute('required', 'required');
            companyUpload.removeAttribute('required');
        } else {
            companyFields.style.display = 'none';
            individualFields.style.display = 'none';
            companyUpload.removeAttribute('required');
            individualUpload.removeAttribute('required');
        }
    });

    companyUpload.name = "submerchant_agreement";
    individualUpload.name = "submerchant_agreement";
});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  function setupCamera(openBtnId, captureBtnId, videoId, previewId, fileInputId) {
      const video     = document.getElementById(videoId);
      const preview   = document.getElementById(previewId);
      const openBtn   = document.getElementById(openBtnId);
      const captureBtn= document.getElementById(captureBtnId);
      const fileInput = document.getElementById(fileInputId);
      let stream = null;
      openBtn.addEventListener('click', async () => {
          stream = await navigator.mediaDevices.getUserMedia({ video: true });
          video.srcObject = stream;
          await video.play();
          video.style.display = 'block';
          captureBtn.style.display = 'inline-block';
          openBtn.style.display = 'none';
      });
      captureBtn.addEventListener('click', () => {
          const canvas = document.createElement('canvas');
          canvas.width  = video.videoWidth;
          canvas.height = video.videoHeight;
          canvas.getContext('2d').drawImage(video, 0, 0);
          canvas.toBlob(blob => {
              const file = new File([blob], 'selfie.jpg', { type: 'image/jpeg' });
              const dt = new DataTransfer();
              dt.items.add(file);
              fileInput.files = dt.files;
              stream.getTracks().forEach(t => t.stop());
              video.style.display = 'none';
              preview.src = URL.createObjectURL(file);
              preview.style.display = 'block';
              captureBtn.style.display = 'none';
              openBtn.textContent = 'Retake Selfie';
              openBtn.style.display = 'inline-block';
          }, 'image/jpeg', 0.9);
      });
  }
  setupCamera('openCamera_company','capture_company','cam_company','preview_company','selfieFile1');
  setupCamera('openCamera_individual','capture_individual','cam_individual','preview_individual','selfieFile');
});
</script>
@endsection