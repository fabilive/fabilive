<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Withdraw')); ?>

            </h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('rider-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Withdraw ')); ?></li>
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
         <div class="col-xl-3">
            <?php echo $__env->make('partials.rider.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         </div>
         <div class="col-xl-9">
            <div class="row">
               <div class="col-lg-12">
                  <div class="widget border-0 p-40 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('My Withdraws')); ?>

                        <a class="mybtn1" href="<?php echo e(route('rider-wwt-index')); ?>">  <?php echo e(__('Back')); ?></a>
                     </h4>
                     <hr>
                     <div class="gocover"
                        style="background: url(<?php echo e(asset('assets/images/'.$gs->loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                     </div>
                     <!--<form id="userform" class="form-horizontal" action="<?php echo e(route('rider-wwt-store')); ?>" method="POST"-->
                     <!--   enctype="multipart/form-data">-->
                     <!--   <?php echo csrf_field(); ?>-->
                     <!--   <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>-->
                     <!--   <div class="form-group mb-4">-->
                     <!--      <label class="control-label col-sm-4" for="name"><?php echo e(__('Current Balance')); ?>-->
                     <!--         <?php echo e(App\Models\Product::vendorConvertPrice(Auth::guard('rider')->user()->balance)); ?></label>-->
                     <!--   </div>-->
                     <!--   <div class="form-group">-->
                     <!--      <label class="control-label col-sm-4" for="name"><?php echo e(__('Withdraw Method')); ?> *-->
                     <!--      </label>-->
                     <!--      <div class="col-sm-12 mt-2">-->
                     <!--         <select class="form-control border " name="methods" id="withmethod" required>-->
                     <!--            <option value=""><?php echo e(__('Select Withdraw Method')); ?></option>-->
                     <!--            <option value="Paypal"><?php echo e(__('Paypal')); ?></option>-->
                     <!--            <option value="Skrill"><?php echo e(__('Skrill')); ?></option>-->
                     <!--            <option value="Payoneer"><?php echo e(__('Payoneer')); ?></option>-->
                     <!--            <option value="Bank"><?php echo e(__('Bank')); ?></option>-->
                     <!--         </select>-->
                     <!--      </div>-->
                     <!--   </div>-->
                     <!--   <div class="form-group mt-4 mb-4">-->
                     <!--      <label class="control-label col-sm-12 mb-2" for="name"><?php echo e(__('Withdraw Amount')); ?> *-->
                     <!--      </label>-->
                     <!--      <div class="col-sm-12">-->
                     <!--         <input name="amount" placeholder="<?php echo e(__('Withdraw Amount')); ?>" class="form-control border"-->
                     <!--            type="text" value="" required>-->
                     <!--      </div>-->
                     <!--   </div>-->
                     <!--   <div class="" id="paypal" style="display: none;">-->
                     <!--      <div class="form-group">-->
                     <!--         <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter Account Email')); ?> *-->
                     <!--         </label>-->
                     <!--         <div class="col-sm-12">-->
                     <!--            <input name="acc_email" placeholder="<?php echo e(__('Enter Account Email')); ?>"-->
                     <!--               class="form-control border" value="" type="email">-->
                     <!--         </div>-->
                     <!--      </div>-->
                     <!--   </div>-->
                     <!--   <div id="bank" style="display: none;">-->
                     <!--      <div class="form-group">-->
                     <!--         <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter IBAN/Account No')); ?>-->
                     <!--            *-->
                     <!--         </label>-->
                     <!--         <div class="col-sm-12">-->
                     <!--            <input name="iban" value="" placeholder="<?php echo e(__('Enter IBAN/Account No')); ?>"-->
                     <!--               class="form-control" type="text">-->
                     <!--         </div>-->
                     <!--      </div>-->
                     <!--      <div class="form-group">-->
                     <!--         <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter Account Name')); ?> *-->
                     <!--         </label>-->
                     <!--         <div class="col-sm-12">-->
                     <!--            <input name="acc_name" value="" placeholder="<?php echo e(__('Enter Account Name')); ?>"-->
                     <!--               class="form-control" type="text">-->
                     <!--         </div>-->
                     <!--      </div>-->
                     <!--      <div class="form-group">-->
                     <!--         <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter Address')); ?> *-->
                     <!--         </label>-->
                     <!--         <div class="col-sm-12">-->
                     <!--            <input name="address" value="" placeholder="<?php echo e(__('Enter Address')); ?>"-->
                     <!--               class="form-control" type="text">-->
                     <!--         </div>-->
                     <!--      </div>-->
                     <!--      <div class="form-group">-->
                     <!--         <label class="control-label col-sm-12" for="name"><?php echo e(__('Enter Swift Code')); ?> *-->
                     <!--         </label>-->
                     <!--         <div class="col-sm-12">-->
                     <!--            <input name="swift" value="" placeholder="<?php echo e(__('Enter Swift Code')); ?>"-->
                     <!--               class="form-control" type="text">-->
                     <!--         </div>-->
                     <!--      </div>-->
                     <!--   </div>-->
                     <!--   <div class="form-group">-->
                     <!--      <label class="control-label col-sm-12 mb-2" for="name"><?php echo e(__('Additional-->
                     <!--         Reference(Optional)')); ?> *-->
                     <!--      </label>-->
                     <!--      <div class="col-sm-12">-->
                     <!--         <textarea class="form-control border" name="reference" rows="6"-->
                     <!--            placeholder="<?php echo e(__('Additional Reference(Optional)')); ?>"></textarea>-->
                     <!--      </div>-->
                     <!--   </div>-->
                     <!--   <div id="resp" class="col-md-12 mt-4">-->
                     <!--      <span class="help-block">-->
                     <!--         <strong><?php echo e(__('Withdraw Fee')); ?> <?php echo e($sign->sign); ?><?php echo e($gs->withdraw_fee); ?>-->
                     <!--            <?php echo e(__('and')); ?> <?php echo e($gs->withdraw_charge); ?>%-->
                     <!--            <?php echo e(__('will deduct from your account.')); ?>-->
                     <!--         </strong>-->
                     <!--      </span>-->
                     <!--   </div>-->
                     <!--   <hr>-->
                     <!--   <div class="add-product-footer">-->
                     <!--      <button name="addProduct_btn" type="submit" class="mybtn1"><?php echo e(__('Withdraw')); ?></button>-->
                     <!--   </div>-->
                     <!--</form>-->
                     
                     <form id="riderWithdrawForm" class="form-horizontal" action="<?php echo e(route('rider-wwt-store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <div class="form-group">
        <label class="control-label col-sm-4"><b><?php echo e(__('Current Balance')); ?>:</b>
            <?php echo e(App\Models\Product::vendorConvertPrice(Auth::guard('rider')->user()->balance)); ?>

        </label>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-4"><?php echo e(__('Withdraw Method')); ?> *</label>
        <div class="col-sm-12">
            <select class="form-control" name="methods" id="withmethod" required>
                <option value=""><?php echo e(__('Select Withdraw Method')); ?></option>
                <option value="Bank"><?php echo e(__('Bank')); ?></option>
                <option value="Campay"><?php echo e(__('Campay')); ?></option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-12"><?php echo e(__('Withdraw Amount')); ?> *</label>
        <div class="col-sm-12">
            <input name="amount" placeholder="<?php echo e(__('Withdraw Amount')); ?>" class="form-control" type="text" required>
        </div>
    </div>

    <!-- Campay Section -->
    <div id="campay" style="display: none;">
        <div class="form-group">
            <label class="control-label col-sm-12"><?php echo e(__('Select Network')); ?> *</label>
            <div class="col-sm-12">
                <select name="network" class="form-control">
                    <option value="MTN">MTN</option>
                    <option value="Orange">Orange</option>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-12"><?php echo e(__('Account Number')); ?> *</label>
            <div class="col-sm-12">
                <input name="campay_acc_no" placeholder="<?php echo e(__('Enter Account Number')); ?>" class="form-control" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-12"><?php echo e(__('Account Name')); ?> *</label>
            <div class="col-sm-12">
                <input name="campay_acc_name" placeholder="<?php echo e(__('Enter Account Name')); ?>" class="form-control" type="text">
            </div>
        </div>
    </div>

    <!-- Bank Section -->
    <div id="bank" style="display: none;">
        <div class="form-group">
            <label class="control-label col-sm-12"><?php echo e(__('Enter IBAN/Account No')); ?> *</label>
            <div class="col-sm-12">
                <input name="iban" class="form-control" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-12"><?php echo e(__('Enter Account Name')); ?> *</label>
            <div class="col-sm-12">
                <input name="acc_name" class="form-control" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-12"><?php echo e(__('Enter Address')); ?> *</label>
            <div class="col-sm-12">
                <input name="address" class="form-control" type="text">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-12"><?php echo e(__('Enter Swift Code')); ?> *</label>
            <div class="col-sm-12">
                <input name="swift" class="form-control" type="text">
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-12"><?php echo e(__('Additional Reference (Optional)')); ?></label>
        <div class="col-sm-12">
            <textarea class="form-control" name="reference" rows="6" placeholder="<?php echo e(__('Additional Reference')); ?>"></textarea>
        </div>
    </div>

    <div id="resp" class="col-md-12">
        <span class="help-block">
            <strong><?php echo e(__('Withdraw Fee')); ?> <?php echo e($sign->sign); ?><?php echo e($gs->withdraw_fee); ?> <?php echo e(__('and')); ?> <?php echo e($gs->withdraw_charge); ?>% <?php echo e(__('will be deducted.')); ?></strong>
        </span>
    </div>

    <div class="add-product-footer mt-3">
        <button type="submit" class="mybtn1"><?php echo e(__('Withdraw')); ?></button>
    </div>
</form>
                     
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Blog Section End ====================-->
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<script>
(function($) {
    "use strict";
    $('#withmethod').change(function() {
        let method = $(this).val();
        $('#campay, #bank').hide().find('input, select').attr('required', false);

        if (method === 'Campay') {
            $('#campay').show().find('input, select').attr('required', true);
        } else if (method === 'Bank') {
            $('#bank').show().find('input, select').attr('required', true);
        }
    });
})(jQuery);
</script>

<script type="text/javascript">
//   (function($) {
//           "use strict";
//       $("#withmethod").change(function () {
//           var method = $(this).val();
//           if (method == "Bank") {
//               $("#bank").show();
//               $("#bank").find('input, select').attr('required', true);
//               $("#paypal").hide();
//               $("#paypal").find('input').attr('required', false);
//           }
//           if (method != "Bank") {
//               $("#bank").hide();
//               $("#bank").find('input, select').attr('required', false);

//               $("#paypal").show();
//               $("#paypal").find('input').attr('required', true);
//           }
//           if (method == "") {
//               $("#bank").hide();
//               $("#paypal").hide();
//           }
//       })
//   })(jQuery);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/rider/withdraw/withdraw.blade.php ENDPATH**/ ?>