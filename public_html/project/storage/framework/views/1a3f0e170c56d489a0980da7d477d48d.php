<?php $__env->startSection('content'); ?>

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('Withdraw Now')); ?> <a class="add-btn" href="<?php echo e(url()->previous()); ?>"><i
                            class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                <ul class="links">
                    <li>
                        <a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                    </li>
                    <li>
                        <a href="javascript:;"><?php echo e(__('My Withdraws')); ?> </a>
                    </li>
                    <li>
                        <a href="javascript:;"><?php echo e(__('Withdraw Now')); ?></a>
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

                        <div class="gocover"
                            style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>

                        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <form id="geniusform" class="form-horizontal" action="<?php echo e(route('vendor-wt-store')); ?>"
                            method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="item form-group">
                                <label class="control-label col-sm-4" for="name"><b><?php echo e(__('Current Balance')); ?> :
                                        <?php echo e(App\Models\Product::vendorConvertPrice($actualBalance)); ?></b></label>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-sm-4" for="name"><?php echo e(__('Withdraw Method')); ?> *
                                </label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="methods" id="withmethod" required>
                                        <option value=""><?php echo e(__('Select Withdraw Method')); ?></option>
                                        <option value="Bank"><?php echo e(__('Bank')); ?></option>
                                        <option value="Campay"><?php echo e(__('Campay')); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div class="item form-group">
                                <label class="control-label col-sm-12" for="name"><?php echo e(__('Withdraw Amount')); ?> *

                                </label>
                                <div class="col-sm-12">
                                    <input name="amount" placeholder="<?php echo e(__('Withdraw Amount')); ?>" class="form-control"
                                        type="text" value="<?php echo e(old('amount')); ?>" required>
                                </div>
                            </div>

                            <div id="paypal" style="display: none;">

                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter Account Email')); ?> *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="acc_email" placeholder="<?php echo e(__('Enter Account Email')); ?>" class="form-control"
                                            value="<?php echo e(old('email')); ?>" type="email">
                                    </div>
                                </div>

                            </div>
                            <!-- campay -->
                            <div id="campay" style="display: none;">
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="network"><?php echo e(__('Select Network')); ?> *</label>
                                    <div class="col-sm-12">
                                        <select name="network" class="form-control">
                                            <option value="MTN">MTN</option>
                                            <option value="Orange">Orange</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="campay_acc_no"><?php echo e(__('Account Number')); ?> *</label>
                                    <div class="col-sm-12">
                                        <input name="campay_acc_no" placeholder="<?php echo e(__('Enter Account Number')); ?>" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="campay_acc_name"><?php echo e(__('Account Name')); ?> *</label>
                                    <div class="col-sm-12">
                                        <input name="campay_acc_name" placeholder="<?php echo e(__('Enter Account Name')); ?>" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            
                            <div id="bank" style="display: none;">
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter IBAN/Account No')); ?> *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="iban" value="" placeholder="<?php echo e(__('Enter IBAN/Account No')); ?>"
                                            class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter Account Name')); ?> *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="acc_name" value=""
                                            placeholder="<?php echo e(__('Enter Account Name')); ?>" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter Address')); ?> *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="address" value=""
                                            placeholder="<?php echo e(__('Enter Address')); ?>" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter Swift Code')); ?>} *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="swift" value=""
                                            placeholder="<?php echo e(__('Enter Swift Code')); ?>" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-sm-12" for="name"><?php echo e(__('Additional Reference(Optional)')); ?> *

                                </label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="reference" rows="6"
                                        placeholder="<?php echo e(__('Additional Reference(Optional)')); ?>"></textarea>
                                </div>
                            </div>
                            <div id="resp" class="col-md-12">
                                <span class="help-block">
                                    <strong><?php echo e(__('Withdraw Fee')); ?> <?php echo e($sign->sign); ?><?php echo e($gs->withdraw_fee); ?> <?php echo e(__('and ')); ?>

                                        <?php echo e($gs->withdraw_charge); ?>% <?php echo e(__('will deduct from your account.')); ?></strong>
                                </span>
                            </div>
                            <hr>
                            <div class="add-product-footer">
                                <button name="addProduct_btn" type="submit"
                                    class="mybtn1"><?php echo e(__('Withdraw')); ?></button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript">
(function($) {
    "use strict";
    $("#withmethod").change(function () {
        var method = $(this).val();
        if (method == "Bank") {
            $("#bank").show();
            $("#paypal, #campay").hide();
            $("#bank").find('input, select').attr('required', true);
            $("#paypal, #campay").find('input, select').attr('required', false);
        } else if (method == "Campay") {
            $("#campay").show();
            $("#bank, #paypal").hide();
            $("#campay").find('input, select').attr('required', true);
            $("#bank, #paypal").find('input, select').attr('required', false);
        } else if (method != "") {
            $("#paypal").show();
            $("#bank, #campay").hide();
            $("#paypal").find('input').attr('required', true);
            $("#bank, #campay").find('input, select').attr('required', false);
        } else {
            $("#bank, #paypal, #campay").hide();
            $("#bank, #paypal, #campay").find('input, select').attr('required', false);
        }
    });
})(jQuery);
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/withdraw/create.blade.php ENDPATH**/ ?>