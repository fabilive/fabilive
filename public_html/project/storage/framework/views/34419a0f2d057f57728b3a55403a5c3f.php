<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- breadcrumb -->
    <div class="full-row bg-light overlay-dark py-5"
        style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/' . $gs->breadcrumb_banner) : asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
        <div class="container">
            <div class="row text-center text-white">
                <div class="col-12">
                    <h3 class="mb-2 text-white"><?php echo e(__('Shop Details')); ?>


                    </h3>
                </div>
                <div class="col-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Shop Details')); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- breadcrumb -->

    <!--==================== Blog Section Start ====================-->
    <div class="full-row">
        <div class="container">
            <div class="mb-4 d-xl-none">
                <button class="dashboard-sidebar-btn btn bg-primary rounded">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="row">
                <div class="col-xl-4">
                    <?php echo $__env->make('partials.user.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                </div>
                <div class="col-xl-8">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="widget border-0 p-40 widget_categories bg-light account-info">

                                <h4 class="widget-title down-line mb-30"><?php echo e(__('Shop Details')); ?>


                                </h4>
                                <div class="pack-details">
                                    <?php if(!empty($package)): ?>
                                        <?php if($package->subscription_id != $subs->id): ?>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                </div>
                                                <div class="col-lg-8">
                                                    <span class="notic"><b><?php echo e(__('Note:')); ?></b>
                                                        <?php echo e(__('Your Previous Plan will be deactivated!')); ?></span>
                                                </div>
                                            </div>

                                            <br>
                                        <?php else: ?>
                                            <br>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <br>
                                    <?php endif; ?>

                                    <form id="subscribe-form" class="pay-form"
                                        action="<?php echo e($subs->price == 0 ? route('user-vendor-request-submit') : ''); ?>"
                                        method="POST" enctype="multipart/form-data">
                                        <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php echo $__env->make('alerts.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                        <?php echo csrf_field(); ?>
                                        <?php if($user->is_vendor == 0): ?>
                                            <div class="row mb-3 align-items-center">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Full Name')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="text" name="name"
                                                        class="form-control form-control-sm"
                                                        placeholder="<?php echo e(__('Full Name')); ?>" required>
                                                </div>
                                            </div>

                                            <br>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1 fw-semibold mb-0">
                                                        <?php echo e(__('Shop Name')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="text" id="shop-name"
                                                        class="form-control form-control-sm option" name="shop_name"
                                                        placeholder="<?php echo e(__('Shop Name')); ?>" required>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Email')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="email" class="option form-control form-control-sm"
                                                        name="email" placeholder="<?php echo e(__('Email')); ?>" required>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Phone Number')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="text" class="option form-control form-control-sm"
                                                        name="phone" placeholder="<?php echo e(__('Phone Number')); ?>" required>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Address')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="text" class="option form-control form-control-sm"
                                                        name="address" placeholder="<?php echo e(__('Address')); ?>" required>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Owner Name')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="text" class="option form-control form-control-sm"
                                                        name="owner_name" placeholder="<?php echo e(__('Owner Name')); ?>" required>
                                                </div>
                                            </div>
                                            <br>


                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Business Registration Certificate(if available)')); ?>

                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="file" class="option form-control form-control-sm"
                                                        name="business_registration_certificate"
                                                        placeholder="<?php echo e(__('Business Registration Certificate')); ?>">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Taxpayer Card Copy or Taxpayer Identification Number (TIN) document Copy')); ?>

                                                        *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="file" class="option form-control form-control-sm"
                                                        name="taxpayer_card_copy" placeholder="<?php echo e(__('Taxpayer Card')); ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <br>
                                            <div id="identityError" class="alert alert-danger d-none my-2">
                                                Please upload at least ONE document: Passport, National ID Card, or Driver
                                                License.
                                            </div>


                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('National ID Card copy')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="file" class="option form-control form-control-sm"
                                                        id="id_card_copy" name="id_card_copy"
                                                        placeholder="<?php echo e(__('National ID Card')); ?>">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Passport Copy')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="file" class="option form-control form-control-sm"
                                                        id="passport_copy" name="passport_copy"
                                                        placeholder="<?php echo e(__('Passport Copy')); ?>">
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Driver License Copy')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="file" class="option form-control form-control-sm"
                                                        id="driver_license_copy" name="driver_license_copy"
                                                        placeholder="<?php echo e(__('Driver License Copy')); ?>">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Fabilive Sub-Merchant Agreement')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="file" class="option form-control form-control-sm"
                                                        name="submerchant_agreement"
                                                        placeholder="<?php echo e(__('Fabilive Sub-Merchant Agreement')); ?>"
                                                        required>
                                                </div>
                                            </div>
                                            <br>
                                            <!-- Selfie Image -->
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Please Download Fabilive Sub-Merchant Agreement')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <a href="<?php echo e(asset('assets/pdf/fabilive.pdf')); ?>" target="_blank">
                                                        <?php echo e(__('Fabilive Sub-Merchant Agreement')); ?> <i
                                                            class="fa fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col-12">

                                                    <input type="file" id="selfieFile" class="w-100" name="selfie_image" style="display:none;">

                                                    <!-- Video element full width -->
                                                    <video id="cam" class="w-100 rounded mb-2" style="display:none;"></video>

                                                    <!-- Preview image full width -->
                                                    <img id="preview" class="w-100 rounded mb-2" style="display:none;">

                                                    <button type="button" class="btn btn-dark btn-sm rounded-2 w-100 mb-2" id="openCamera">
                                                        Open Camera
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

                                            <!-- Selfie Image -->




                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Residence Permit for Foreigners')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="file" class="option form-control form-control-sm"
                                                        name="residence_permit"
                                                        placeholder="<?php echo e(__('Residence Permit')); ?>" required>
                                                </div>
                                            </div>
                                            <br>

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Taxpayer Registration Number')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="text" class="option form-control form-control-sm"
                                                        name="reg_number"
                                                        placeholder="<?php echo e(__('Taxpayer Registration Number')); ?>" required>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Registration Number')); ?>

                                                        <small><?php echo e(__('(Optional)')); ?></small>
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <input type="text" class="option form-control form-control-sm"
                                                        name="reg_number" placeholder="<?php echo e(__('Registration Number')); ?>">
                                                </div>
                                            </div>
                                            <br>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Message')); ?> <small><?php echo e(__('(Optional)')); ?></small>
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <textarea class="option form-control form-control-sm" name="shop_message" placeholder="<?php echo e(__('Message')); ?>"
                                                        rows="5"></textarea>
                                                </div>
                                            </div>
                                            <br>
                                        <?php endif; ?>
                                        <input type="hidden" name="subs_id" value="<?php echo e($subs->id); ?>">
                                        <?php if($subs->price != 0): ?>
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <h5 class="title pt-1">
                                                        <?php echo e(__('Select Payment Method')); ?> *
                                                    </h5>
                                                </div>
                                                <div class="col-lg-8">
                                                    <select name="method" id="method"
                                                        class="option form-control form-control-sm form-control border mb-3"
                                                        required="">
                                                        <option value="" data-form="" data-show="no"
                                                            data-val="" data-href=""><?php echo e(__('Select an option')); ?>

                                                        </option>
                                                        <?php $__currentLoopData = $gateway; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paydata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <?php if($paydata->type == 'manual'): ?>
                                                                <option value="<?php echo e($paydata->title); ?>"
                                                                    data-form="<?php echo e($paydata->showSubscriptionLink()); ?>"
                                                                    data-show="<?php echo e($paydata->showForm()); ?>"
                                                                    data-href="<?php echo e(route('user.load.payment', ['slug1' => $paydata->showKeyword(), 'slug2' => $paydata->id])); ?>"
                                                                    data-val="<?php echo e($paydata->title); ?>">
                                                                    <?php echo e($paydata->title); ?>

                                                                </option>
                                                            <?php else: ?>
                                                                <option value="<?php echo e($paydata->name); ?>"
                                                                    data-form="<?php echo e($paydata->showSubscriptionLink()); ?>"
                                                                    data-show="<?php echo e($paydata->showForm()); ?>"
                                                                    data-href="<?php echo e(route('user.load.payment', ['slug1' => $paydata->showKeyword(), 'slug2' => $paydata->id])); ?>"
                                                                    data-val="<?php echo e($paydata->keyword); ?>">
                                                                    <?php echo e($paydata->name); ?>

                                                                </option>
                                                            <?php endif; ?>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div id="payments" class="d-none">
                                            </div>
                                        <?php endif; ?>
                                        <input type="hidden" id="ck" value="0">
                                        <input type="hidden" name="sub" id="sub" value="0">
                                        <div class="row">
                                            <div class="col-lg-4">
                                            </div>
                                            <div class="col-lg-8">
                                                <button type="submit" id="final-btn"
                                                    class="mybtn1"><?php echo e(__('Submit')); ?></button>
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
    <?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script type="text/javascript" src="<?php echo e(asset('assets/front/js/payvalid.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets/front/js/paymin.js')); ?>"></script>
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <script type="text/javascript" src="<?php echo e(asset('assets/front/js/payform.js')); ?>"></script>
    <script src="https://sdk.mercadopago.com/js/v2"></script>
    <script src="https://js.paystack.co/v1/inline.js"></script>
    <script>
        document.getElementById('subscribe-form').addEventListener('submit', function(e) {

            const passport = document.getElementById('passport_copy').files.length;
            const idCard = document.getElementById('id_card_copy').files.length;
            const license = document.getElementById('driver_license_copy').files.length;

            if (!passport && !idCard && !license) {
                e.preventDefault(); // stop form submit

                const errorBox = document.getElementById('identityError');
                errorBox.classList.remove('d-none');

                errorBox.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
                return false;
            }

        });
    </script>

    <script type="text/javascript">
        (function($) {
            "use strict";

            $('#method').on('change', function() {
                var val = $(this).find(':selected').attr('data-val');
                var form = $(this).find(':selected').attr('data-form');
                var show = $(this).find(':selected').attr('data-show');
                var href = $(this).find(':selected').attr('data-href');

                if (show == "yes") {
                    $('#payments').removeClass('d-none');
                } else {
                    $('#payments').addClass('d-none');
                }

                if (val == 'paystack') {
                    $('.pay-form').prop('id', 'paystack');
                } else if (val == 'voguepay') {
                    $('.pay-form').prop('id', 'voguepay');
                } else if (val == 'mercadopago') {
                    $('.pay-form').prop('id', 'mercadopago');
                } else if (val == '2checkout') {
                    $('.pay-form').prop('id', 'twocheckout');
                } else {
                    $('.pay-form').prop('id', 'subscribe-form');
                }


                $('#payments').load(href);
                $('.pay-form').attr('action', form);
            });


            $(document).on('submit', '#paystack', function() {
                var val = $('#sub').val();
                if (val == 0) {
                    if ($('#shop-name').length > 0) {

                        $.get('<?php echo e(route('user.shop.check') . '?shop_name='); ?>' + $('#shop-name').val(),
                            function(
                                data, status) {
                                if ((data.errors)) {

                                    $('.alert-danger').show();
                                    $('.alert-danger ul').html('');
                                    for (var error in data.errors) {
                                        $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>');
                                        $('#sub').val('0');
                                        $('#ck').val('1');
                                    }
                                } else {
                                    $('#ck').val('0');
                                }
                            });

                    }

                    setTimeout(function() {
                        if ($('#ck').val() == '0') {

                            var total = <?php echo e($subs->price * $curr->value); ?>;
                            total = Math.round(total);

                            var handler = PaystackPop.setup({
                                key: '<?php echo e($paystack['key']); ?>',
                                email: '<?php echo e(Auth::user()->email); ?>',
                                amount: total * 100,
                                currency: "<?php echo e($curr->name); ?>",
                                ref: '' + Math.floor((Math.random() * 1000000000) + 1),
                                callback: function(response) {
                                    $('#ref_id').val(response.reference);
                                    $('#sub').val('1');
                                    $('#final-btn').click();
                                },
                                onClose: function() {
                                    window.location.reload();
                                }
                            });
                            handler.openIframe();
                            return false;
                        }

                    }, 1000);
                    return false;
                } else {
                    return true;
                }
            });

        })(jQuery);
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/package/details.blade.php ENDPATH**/ ?>