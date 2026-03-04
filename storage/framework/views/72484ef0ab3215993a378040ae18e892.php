<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Register')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>

                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Register')); ?></li>
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
                                        <?php echo $__env->make('includes.admin.form-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <h3><?php echo e(__('Delivery Register')); ?></h3>
                                        <form id="registerform" action="<?php echo e(route('rider-register-submit')); ?>" method="POST" enctype="multipart/form-data">
                                            <?php echo csrf_field(); ?>
                                            <p>
                                                <label for="rider_type"><?php echo e(__('Rider Type')); ?></label>
                                                <select name="rider_type" id="rider_type" class="form-control" required>
                                                    <option value=""><?php echo e(__('Select Rider Type')); ?></option>
                                                    <option value="company"><?php echo e(__('Company')); ?></option>
                                                    <option value="individual"><?php echo e(__('Individual')); ?></option>
                                                </select>
                                            </p>

                                            <p>
                                                <input type="text" name="name" class="form-control" placeholder="<?php echo e(__('Full Name')); ?>"  >
                                            </p>
                                            <p>
                                                <input type="email" name="email" class="form-control" required=""  placeholder="<?php echo e(__('Email Address')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="phone" class="form-control" required=""  placeholder="<?php echo e(__('Phone Number')); ?>" >
                                            </p>
                                            <p>
                                                <input type="text" name="address" class="form-control" required=""  placeholder="<?php echo e(__('Address')); ?>" >
                                            </p>
                                            <p>
                                                <label for="national_id_front_image"><?php echo e(__('National ID Front Image')); ?></label>
                                                <input type="file" name="national_id_front_image" id="national_id_front_image" class="form-control" required="">
                                            </p>
                                            <div id="company_fields" style="display:none;">
                                                <p>
                                                    <label for=""><?php echo e(__('Company Registration Documents')); ?></label>
                                                    <input type="file" name="company_registration_document" class="form-control">
                                                </p>
                                                <p>
                                                    <label for=""><?php echo e(__('Valid ID/Passport Of Company Owner or Representative.')); ?></label>
                                                    <input type="file" name="id_company_owner" class="form-control">
                                                </p>

                                                <?php $__currentLoopData = $agreements->where('type', 'Selfi_Instructions'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agreement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p>
                                                    <label><?php echo e(__('Please Download and Read Selfi Instructions')); ?></label>
                                                    <a href="<?php echo e(asset($agreement->image)); ?>" target="_blank">
                                                        <?php echo e(__('Selfi Instructions')); ?> <i class="fa fa-download"></i>
                                                    </a>
                                                </p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <input type="file" id="selfieFile1" name="live_selfie_company" style="display:none;">
                                                <video id="cam_company"></video>
                                                <img id="preview_company">
                                                <button type="button" class="btn btn-sm btn-dark w-100 rounded-2" id="openCamera_company">Open Camera</button>
                                                <button type="button" id="capture_company">Capture</button>
                                                <p>
                                                    <label for=""><?php echo e(__('Transport License / Permis de Transport.')); ?></label>
                                                    <input type="file" name="transport_license" class="form-control">
                                                </p>
                                                <p>
                                                    <label for=""><?php echo e(__('Insurance Certificate')); ?></label>
                                                    <input type="file" name="insurance_certificate_company" class="form-control">
                                                </p>
                                                <p>
                                                    <label for=""><?php echo e(__('Taxpayer Registration number (TIN)')); ?></label>
                                                    <input type="text" name="tin_company" class="form-control">
                                                </p>
                                                <?php $__currentLoopData = $agreements->where('type', 'Fabilive_Delivery_Company_Agreement'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agreement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p>
                                                    <label><?php echo e(__('Please Download, Sign, and upload the Fabilive Rider Agreement')); ?></label>
                                                    <a href="<?php echo e(asset($agreement->image)); ?>" target="_blank">
                                                        <?php echo e(__('Fabilive Rider Agreement')); ?> <i class="fa fa-download"></i>
                                                    </a>
                                                </p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                            </div>
                                            <div id="individual_fields" style="display:none;">
                                                <p>
                                                    <select name="vehicle_type_individual" class="form-control">
                                                        <option value=""><?php echo e(__('Select Vehicle Type')); ?></option>
                                                        <option value="bike"><?php echo e(__('MotorBike')); ?></option>
                                                        <option value="truck"><?php echo e(__('Light Duty Trucks')); ?></option>
                                                        <option value="car"><?php echo e(__('Small Cars')); ?></option>
                                                        <option value="van"><?php echo e(__('Vans')); ?></option>
                                                        <option value="pickup"><?php echo e(__('Pickup Trucks')); ?></option>
                                                    </select>
                                                </p>
                                                <p>
                                                    <label for=""><?php echo e(__('Taxpayer Registration number (TIN)')); ?></label>
                                                    <input type="text" name="tin_individual" class="form-control">
                                                </p>
                                                <p>
                                                    <label for=""><?php echo e(__('Drivers License image')); ?></label>
                                                    <input type="file" name="driver_license_individual" class="form-control">
                                                </p>
                                                <?php $__currentLoopData = $agreements->where('type', 'Selfi_Instructions'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agreement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <p>
                                                    <label><?php echo e(__('Please Download and Read Selfi Instructions')); ?></label>
                                                    <a href="<?php echo e(asset($agreement->image)); ?>" target="_blank">
                                                        <?php echo e(__('Selfi Instructions')); ?> <i class="fa fa-download"></i>
                                                    </a>
                                                </p>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <input type="file" id="selfieFile" name="live_selfie_individual" style="display:none;">
                                                <video id="cam_individual"></video>
                                                <img id="preview_individual">
                                                <button type="button " class="btn btn-sm btn-dark w-100 rounded-2" id="openCamera_individual">Open Camera</button>
                                                <button type="button" id="capture_individual">Capture</button>
                                                <p>
                                                    <label for=""><?php echo e(__('Vehicle Registration Certificate (Carte Grise)')); ?></label>
                                                    <input type="file" name="vehicle_registration_certificate" class="form-control">
                                                </p>
                                                <p>
                                                    <label for=""><?php echo e(__('Insurance Certificate')); ?></label>
                                                    <input type="file" name="insurance_certificate_individual" class="form-control">
                                                </p>
                                                <p>
                                                    <label for=""><?php echo e(__('Criminal records / Police Report')); ?></label>
                                                    <input type="file" name="criminal_records" class="form-control">
                                                </p>
                                                <?php $__currentLoopData = $agreements->where('type', 'Fabilive_Delivery_Individual_Agreement'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agreement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <p>
                                                <label><?php echo e(__('Please Download, Sign, and upload the Fabilive Rider Agreement')); ?></label>
                                                <a href="<?php echo e(asset($agreement->image)); ?>" target="_blank">
                                                    <?php echo e(__('Fabilive Rider Agreement')); ?> <i class="fa fa-download"></i>
                                                </a>
                                            </p>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                            </div>
                                            <p>
                                                <label for="national_id_back_image"><?php echo e(__('National ID Back Image')); ?></label>
                                                <input type="file" name="national_id_back_image" id="national_id_back_image" class="form-control" required=""  placeholder="<?php echo e(__('National ID Back Image')); ?>" >
                                            </p>
                                            <p>
                                                <label for="license_image"><?php echo e(__('Drivers License image')); ?></label>
                                                <input type="file" name="license_image" id="license_image" class="form-control" required=""  placeholder="<?php echo e(__('Drivers License image')); ?>" >
                                            </p>

                                            <p>
                                                <label
                                                    for="submerchant_agreement"><?php echo e(__('Fabilive Rider Agreement')); ?></label>
                                                <input type="file" name="submerchant_agreement"
                                                    id="submerchant_agreement" class="form-control" required="">
                                            </p>


                                            <p>
                                                <input type="password" name="password" class="form-control" required=""  placeholder="<?php echo e(__('Password')); ?>" >
                                            </p>
                                            <p>
                                                <input type="password" name="password_confirmation" class="form-control" required=""  placeholder="<?php echo e(__('Confirm Password')); ?>" >
                                            </p>
                                            <?php if($gs->is_capcha == 1): ?>
                                            <div class="form-input mb-3">
                                                 <div data-sitekey="6LcQrmIrAAAAABNWTdrJbBzy9TfhBzBH4P4IQIIT" class="g-recaptcha"></div>
                                                 <?php echo NoCaptcha::renderJs(); ?>

                                                 <?php $__errorArgs = ['g-recaptcha-response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                 <p class="my-2"><?php echo e($message); ?></p>
                                                 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                             </div>
                                             <?php endif; ?>
                                            <input id="processdata" type="hidden" value="<?php echo e(__('Processing...')); ?>">
                                                <button type="submit" class="btn btn-primary float-none w-100 rounded-0 submit-btn" name="register" value="Register"><?php echo e(__('Register')); ?></button>
                                            </p>
                                        </form>
                                        <p>
                                                <?php echo e(__("Do have any account?")); ?><a href="<?php echo e(route('rider.login')); ?>"  class="text-secondary"><?php echo e(__(' Login')); ?></a>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const riderType = document.getElementById('rider_type');
    const companyFields = document.getElementById('company_fields');
    const individualFields = document.getElementById('individual_fields');
    riderType.addEventListener('change', function () {
        if (this.value === 'company') {
            companyFields.style.display = 'block';
            individualFields.style.display = 'none';
        } else if (this.value === 'individual') {
            companyFields.style.display = 'none';
            individualFields.style.display = 'block';
        } else {
            companyFields.style.display = 'none';
            individualFields.style.display = 'none';
        }
    });
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/rider/register.blade.php ENDPATH**/ ?>