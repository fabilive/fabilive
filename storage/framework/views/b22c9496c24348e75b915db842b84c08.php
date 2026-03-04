<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/' . $gs->breadcrumb_banner) : asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
        <div class="container">
            <div class="row text-center text-white">
                <div class="col-12">
                    <h3 class="mb-2 text-white"><?php echo e(__('Register')); ?></h3>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>

                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Vendor Register')); ?></li>
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
                                    <?php echo $__env->make('includes.admin.form-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <h3><?php echo e(__('Vendor Registration')); ?></h3>
                                    <form id="registerform" action="<?php echo e(route('user-register-submit')); ?>" method="POST"
                                        enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <p>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="<?php echo e(__('Full Name')); ?>">
                                        </p>
                                        <p>
                                            <input type="email" name="email" class="form-control" required=""
                                                placeholder="<?php echo e(__('Email Address')); ?>">
                                        </p>
                                        <p>
                                            <input type="text" name="phone" class="form-control" required=""
                                                placeholder="<?php echo e(__('Phone Number')); ?>">
                                        </p>
                                        <p>
                                            <input type="text" name="address" class="form-control" required=""
                                                placeholder="<?php echo e(__('Address')); ?>">
                                        </p>
                                        <p>
                                            <input type="text" name="shop_name" class="form-control" required=""
                                                placeholder="<?php echo e(__('Shop Name')); ?>">
                                        </p>
                                        <p>
                                            <input type="text" name="owner_name" class="form-control" required=""
                                                placeholder="<?php echo e(__('Shop Owner Name')); ?>">
                                        </p>

                                        <p>
                                            <input type="text" name="reg_number" class="form-control" required=""
                                                placeholder="<?php echo e(__('Taxpayer Registration Number')); ?>">
                                        </p>
                                        <p>
                                            <input type="text" name="shop_message" class="form-control" required=""
                                                placeholder="<?php echo e(__('Shop Message')); ?>">
                                        </p>


                                            <p>
                                                <label for="national_id_front_image">National ID Front Image</label>
                                                <input type="file" name="national_id_front_image" id="national_id_front_image" class="form-control" required>
                                            </p>
                                            <p>
                                                <label for="national_id_back_image">National ID Back Image</label>
                                                <input type="file" name="national_id_back_image" id="national_id_back_image" class="form-control" required>
                                            </p>
                                            

                                            <?php $__currentLoopData = $agreements->where('type', 'Fabilive_Sub_merchant_Agreement'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agreement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <p>
                                                <label><?php echo e(__('Please Download, Sign, and upload the merchant Agreement')); ?></label>
                                                <a href="<?php echo e(asset($agreement->image)); ?>" target="_blank">
                                                    <?php echo e(__('Merchant Agreement')); ?> <i class="fa fa-download"></i>
                                                </a>
                                            </p>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                                        <p>
                                            <label
                                                for="submerchant_agreement"><?php echo e(__('Fabilive Sub-Merchant Agreement')); ?></label>
                                            <input type="file" name="submerchant_agreement" id="submerchant_agreement"
                                                class="form-control" required="">
                                        </p>


                                        <!-- -->

                                        <?php $__currentLoopData = $agreements->where('type', 'Selfi_Instructions'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $agreement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p>
                                            <label><?php echo e(__('Please Download Selfi Intructions')); ?></label>
                                            <a href="<?php echo e(asset($agreement->image)); ?>" target="_blank">
                                                <?php echo e(__('Selfi Intructions')); ?> <i class="fa fa-download"></i>
                                            </a>
                                        </p>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

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
                                                        stream = await navigator.mediaDevices.getUserMedia({ video: true });
                                                        video.srcObject = stream;
                                                        await video.play();
                                                        video.style.display = 'block';
                                                        captureBtn.style.display = 'inline-block';
                                                        openBtn.style.display = 'none';
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

                                                            stream.getTracks().forEach(t => t.stop());
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
                                                placeholder="<?php echo e(__('Password')); ?>">
                                        </p>
                                        <p>
                                            <input type="password" name="password_confirmation" class="form-control"
                                                required="" placeholder="<?php echo e(__('Confirm Password')); ?>">
                                        </p>
                                        <?php if($gs->is_capcha == 1): ?>
                                            <div class="form-input mb-3">
                                                <div data-sitekey="6LcQrmIrAAAAABNWTdrJbBzy9TfhBzBH4P4IQIIT"
                                                    class="g-recaptcha"></div>
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
                                        <input type="hidden" name="vendor" value="1">
                                        <input id="processdata" type="hidden" value="<?php echo e(__('Processing...')); ?>">
                                        <button type="submit"
                                            class="btn btn-primary float-none w-100 rounded-0 submit-btn" name="register"
                                            value="Register"><?php echo e(__('Register')); ?></button>
                                        </p>
                                    </form>
                                    <p>
                                        <?php echo e(__('Do have any account?')); ?><a href="<?php echo e(route('user.login')); ?>"
                                            class="text-secondary"><?php echo e(__(' Login')); ?></a>
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


    <?php echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/frontend/vendor-register.blade.php ENDPATH**/ ?>