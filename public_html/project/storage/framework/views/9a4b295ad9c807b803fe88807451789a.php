<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Deposite')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Deposite')); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
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

                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Deposit')); ?>

                                <a class="mybtn1" href="<?php echo e(route('user-deposit-index')); ?>">
                                    <i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?>

                                </a>
                            </h4>

                            <div class="pack-details">
                                <div class="row">

                                    <div class="col-lg-4">
                                        <h5 class="title">
                                          <?php echo e(__('Current Balance')); ?>

                                        </h5>
                                    </div>
                                    <div class="col-lg-8">
                                        <p class="value">
                                          <!--<?php echo e(App\Models\Product::vendorConvertPrice(Auth::user()->balance)); ?>-->
                                          <?php echo e($sign->sign); ?><?php echo e(number_format($user->balance, 2)); ?>

                                        </p>
                                    </div>
                                </div>

                                <form id="deposit-form" class="pay-form" action="" method="POST">

                                    <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php echo $__env->make('alerts.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <?php echo csrf_field(); ?>
                                    <div class="row mb-3">
                                        <div class="col-lg-4">
                                            <h5 class="title pt-1">
                                              <?php echo e(__('Deposit Amount')); ?> *
                                            </h5>
                                        </div>
                                        <div class="col-lg-8">
                                        <input type="number" class="option form-control border" min="1" id="amount"  name="amount" placeholder="<?php echo e($curr->name); ?>" step="0.01" required="" value="<?php echo e(old('amount')); ?>">
                                        </div>
                                      </div>
                                    <div class="row">
                                        <div class="col-lg-4">
                                            <h5 class="title pt-1">
                                                <?php echo e(__('Select Payment Method')); ?> *
                                            </h5>
                                        </div>
                                        <div class="col-lg-8">
                                            <select name="method" id="method" class="option form-control border" required="">
                                                <option value="" data-form="" data-show="no" data-val="" data-href="">
                                                  <?php echo e(__('Select an option')); ?>

                                                </option>
                                                <?php $__currentLoopData = $gateway; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paydata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                  <?php if($paydata->type == 'manual'): ?>

                                                  <option value="<?php echo e($paydata->title); ?>"
                                                    data-form="<?php echo e($paydata->showDepositLink()); ?>"
                                                    data-show="<?php echo e($paydata->showForm()); ?>"
                                                    data-href="<?php echo e(route('user.load.payment',['slug1' =>
                                                    $paydata->showKeyword(),'slug2' => $paydata->id])); ?>"
                                                    data-val="<?php echo e($paydata->keyword); ?>">
                                                    <?php echo e($paydata->title); ?>

                                                  </option>
                                                  <?php else: ?>
                                                  <option value="<?php echo e($paydata->name); ?>" data-form="<?php echo e($paydata->showDepositLink()); ?>" data-show="<?php echo e($paydata->showForm()); ?>" data-href="<?php echo e(route('user.load.payment',['slug1' => $paydata->showKeyword(),'slug2' => $paydata->id])); ?>" data-val="<?php echo e($paydata->keyword); ?>">
                                                    <?php echo e($paydata->name); ?>

                                                  </option>
                                                  <?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="payments" class="d-none">
                                    </div>
                                    <input type="hidden" name="sub" id="sub" value="0">
                                    <div class="row">
                                        <div class="col-lg-4">
                                        </div>
                                        <div class="col-lg-8 mt-4">
                                            <button type="submit" id="final-btn" class="mybtn1"><?php echo e(__('Submit')); ?></button>
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
<!--==================== Blog Section End ====================-->


<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block"><?php echo e(__('License Key')); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <p class="text-center"><?php echo e(__('The Licenes Key is :')); ?> <span id="key"></span></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
            </div>
        </div>
    </div>
</div>

<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript" src="<?php echo e(asset('assets/front/js/payvalid.js')); ?>"></script>

<script type="text/javascript" src="<?php echo e(asset('assets/front/js/paymin.js')); ?>"></script>

<script type="text/javascript" src="<?php echo e(asset('assets/front/js/payform.js')); ?>"></script>

<script src="https://js.paystack.co/v1/inline.js"></script>

<script src="//voguepay.com/js/voguepay.js"></script>


<script type="text/javascript" src="https://js.stripe.com/v2/"></script>

<script src="https://sdk.mercadopago.com/js/v2"></script>

<script type="text/javascript">

(function($) {
		"use strict";

$('#method').on('change',function(){
    var val  = $(this).find(':selected').attr('data-val');
    var form = $(this).find(':selected').attr('data-form');
    var show = $(this).find(':selected').attr('data-show');
    var href = $(this).find(':selected').attr('data-href');

    if(show == "yes"){
        $('#payments').removeClass('d-none');
    }else{
        $('#payments').addClass('d-none');
    }

    if(val == 'paystack'){
			$('.pay-form').prop('id','paystack');
      $('#amount').prop('name','amount');
		}
		else if(val == 'voguepay'){
			$('.pay-form').prop('id','voguepay');
      $('#amount').prop('name','amount');
		}
		else if(val == 'mercadopago'){
			$('.pay-form').prop('id','mercadopago');
      $('#amount').prop('name','deposit_amount');
		}
		else if(val == '2checkout'){
			$('.pay-form').prop('id','twocheckout');
      $('#amount').prop('name','amount');
		}
		else {
			$('.pay-form').prop('id','deposit-form');
      $('#amount').prop('name','amount');
		}


    $('#payments').load(href);
    $('.pay-form').attr('action',form);
});


    $(document).on('submit','#paystack',function(){
            var val = $('#sub').val();
            if(val == 0){
                var total = $('#amount').val();
                total = Math.round(total);
                var handler = PaystackPop.setup({
                key: '<?php echo e($paystack["key"]); ?>',
                email: '<?php echo e(Auth::user()->email); ?>',
                amount: total * 100,
                currency: "<?php echo e($curr->name); ?>",
                ref: ''+Math.floor((Math.random() * 1000000000) + 1),
                    callback: function(response){
                        $('#ref_id').val(response.reference);
                        $('#sub').val('1');
                        $('#final-btn').click();
                    },
                    onClose: function(){
                        window.location.reload();
                    }
                });
                handler.openIframe();
                 return false;

            }
            else {
                return true;
            }
		});

})(jQuery);

</script>


<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/deposit/create.blade.php ENDPATH**/ ?>