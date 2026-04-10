@extends('layouts.vendor')

@section('content')

<div class="content-area">
  <div class="mr-breadcrumb">
    <div class="row">
      <div class="col-lg-12">
        <h4 class="heading">{{ __('Vendor Verification') }}</h4>
        <ul class="links">
          <li>
            <a href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }}</a>
          </li>
          <li>
            <a href="{{ route('vendor-verify') }}">{{ __('Vendor Verification') }}</a>
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="add-product-content1">
    <div class="row">
      <div class="col-lg-12">
        <div class="product-description">
          <div class="body-area">

            @if($data->checkVerification())


            <div class="alert alert-success validation" style="">
              <p class="text-left">
                <div class="text-center"><i class="fas fa-check-circle fa-4x"></i><br>
                  <h3>{{ __('Your Documents Submitted Successfully.') }}</h3>
                </div>
              </p>
            </div>
            @else
            @include('alerts.admin.form-both')
            <div class="gocover"
              style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
            </div>
            <form id="verifyform" action="{{route('vendor-verify-submit')}}" method="POST"
              enctype="multipart/form-data">
              {{csrf_field()}}
              @include('alerts.form-success')
              <div class="row  py-3">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">
                      {{ __('Description') }} *
                    </h4>
                  </div>
                </div>
                <div class="col-lg-7">
                  <textarea class="input-field" name="text" required=""
                    placeholder="{{ __('Enter Details') }}"></textarea>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">
                    <h4 class="heading">
                      {{ __('Attachment') }}*
                    </h4>
                    <p class="sub-heading">{{__('(Maximum Size is 10 MB)')}}</p>
                  </div>
                </div>
                <div class="col-lg-7">
                  <div class="attachments" id="attachment-section">
                    <div class="attachment-area">
                      <input type="file" name="attachments[]" required>
                    </div>
                  </div>
                  <a href="javascript:;" id="attachment-btn" class="add-more mt-4"><i
                      class="fas fa-plus"></i>{{ __('Add More Attachment') }} </a>

                  @if(isset($verify) && str_contains($verify->warning_reason, 'Selfie'))
                  <div class="mt-4 p-3 border rounded bg-light" id="camera-container">
                    <h5 class="mb-3"><i class="fas fa-camera"></i> {{ __('Take Live Selfie') }}</h5>
                    <div id="camera-preview-area" class="text-center mb-3" style="display:none;">
                      <video id="webcam" autoplay playsinline width="100%" style="max-width: 400px; border-radius: 8px; border: 2px solid #ddd;"></video>
                      <canvas id="canvas" style="display:none;"></canvas>
                      <div class="mt-2">
                        <button type="button" id="snap" class="btn btn-primary d-none">{{ __('Capture Photo') }}</button>
                        <button type="button" id="retake" class="btn btn-warning d-none">{{ __('Retake') }}</button>
                      </div>
                    </div>
                    <div id="photo-preview" class="text-center mb-3" style="display:none;">
                      <h6>{{ __('Captured Photo') }}</h6>
                      <img id="screenshot" src="" style="max-width: 100%; height: auto; border-radius: 8px; border: 2px solid #28a745;">
                    </div>
                    <button type="button" id="start-camera" class="btn btn-info btn-sm">{{ __('Open Camera') }}</button>
                    <p class="small text-muted mt-2">{{ __('Note: Your browser will ask for camera permission.') }}</p>
                  </div>
                  @endif
                </div>
              </div>

              <input type="hidden" name="warning" value="{{ isset($verify) ? $verify->admin_warning : '0' }}" />
              <input type="hidden" name="verify_id" value="{{ isset($verify) ? $verify->id : '0' }}" />

              <div class="row">
                <div class="col-lg-4">
                  <div class="left-area">

                  </div>
                </div>
                <div class="col-lg-7">
                  <button class="addProductSubmit-btn" type="submit">{{ __('Submit') }}</button>
                </div>
              </div>
            </form>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>



@endsection

@section('scripts')


<script type="text/javascript">

(function($) {
		"use strict";

  function isEmpty(el) {
    return !$.trim(el.html())
  }

  // Color Section

  $("#attachment-btn").on('click', function () {

    $("#attachment-section").append('' +
      '<div class="attachment-area  mt-2">' +
      '<span class="remove attachment-remove"><i class="fas fa-times"></i></span>' +
      '<input type="file" name="attachments[]" required>' +
      '</div>' +
      '');
  });


  $(document).on('click', '.attachment-remove', function () {

    $(this.parentNode).remove();
    if (isEmpty($('#attachment-section'))) {

      $("#attachment-section").append('' +
        '<div class="attachment-area  mt-2">' +
        '<input type="file" name="attachments[]" required>' +
        '</div>' +
        '');

    }

  });

  // Camera Integration
  const video = document.getElementById('webcam');
  const canvas = document.getElementById('canvas');
  const snap = document.getElementById('snap');
  const retake = document.getElementById('retake');
  const startBtn = document.getElementById('start-camera');
  const previewArea = document.getElementById('camera-preview-area');
  const photoPreview = document.getElementById('photo-preview');
  const screenshotImg = document.getElementById('screenshot');
  let stream = null;

  if (startBtn) {
    startBtn.addEventListener('click', async () => {
      try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: "user" }, audio: false });
        video.srcObject = stream;
        $(previewArea).fadeIn();
        $(startBtn).hide();
        $(snap).removeClass('d-none');
      } catch (err) {
        alert("Error accessing camera: " + err.message + ". Please ensure you are on HTTPS.");
      }
    });

    snap.addEventListener('click', () => {
      const context = canvas.getContext('2d');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      context.drawImage(video, 0, 0, canvas.width, canvas.height);
      
      const dataUrl = canvas.toDataURL('image/png');
      screenshotImg.src = dataUrl;
      
      $(previewArea).hide();
      $(photoPreview).fadeIn();
      $(retake).removeClass('d-none');
      
      // Stop stream
      if (stream) {
        stream.getTracks().forEach(track => track.stop());
      }

      // Convert dataUrl to a File object and add it to the attachment list
      const blob = dataURLtoBlob(dataUrl);
      const file = new File([blob], "selfie.png", { type: "image/png" });
      
      // Create a new file input area if needed, or use a hidden one
      addSelfieToForm(file);
    });

    retake.addEventListener('click', () => {
      $(photoPreview).hide();
      $(startBtn).trigger('click');
    });

    function dataURLtoBlob(dataurl) {
      let arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
          bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
      while(n--){
          u8arr[n] = bstr.charCodeAt(n);
      }
      return new Blob([u8arr], {type:mime});
    }

    function addSelfieToForm(file) {
      // Find the first empty file input or add a new one
      let container = document.getElementById('attachment-section');
      let dataTransfer = new DataTransfer();
      dataTransfer.items.add(file);
      
      // Create a unique area for the selfie so it doesn't get removed easily
      let selfieArea = document.createElement('div');
      selfieArea.className = 'attachment-area mt-2 selfie-attachment';
      selfieArea.innerHTML = '<span class="remove attachment-remove"><i class="fas fa-times"></i></span><label class="badge badge-success">Selfie Captured</label>';
      
      let fileInput = document.createElement('input');
      fileInput.type = 'file';
      fileInput.name = 'attachments[]';
      fileInput.style.display = 'none';
      fileInput.files = dataTransfer.files;
      
      selfieArea.appendChild(fileInput);
      container.appendChild(selfieArea);
      
      toastr.success("Selfie captured and attached successfully!");
    }
  }

})(jQuery);

</script>

@endsection