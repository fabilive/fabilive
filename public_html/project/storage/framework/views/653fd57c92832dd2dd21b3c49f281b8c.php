<?php $__env->startSection('styles'); ?>
<link href="<?php echo e(asset('assets/admin/css/jquery-ui.css')); ?>" rel="stylesheet" type="text/css">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

          <div class="content-area">
            <div class="mr-breadcrumb">
              <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading"><?php echo e(__('Vendor Earning')); ?></h4>
                    <ul class="links">
                      <li>
                        <a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Dashbord')); ?> </a>
                      </li>
                      <li>
                        <a href="javascript:;"><?php echo e(__('Settings')); ?></a>
                      </li>
                      <li>
                        <a href="<?php echo e(route('vendor-shipping-index')); ?>"><?php echo e(__('Vendor Earning')); ?></a>
                      </li>
                    </ul>
                </div>
              </div>
            </div>
            <form action="<?php echo e(route('vendor.income')); ?>" method="GET">
            <div class="product-area">
              <div class="row">

                <?php echo $__env->make('includes.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="col-sm-3  offset-2 mt-3">
								  <input type="text"  autocomplete="off" class="input-field discount_date" value="<?php echo e($start_date != '' ? $start_date->format('d-m-Y') : ''); ?>"  name="start_date"  placeholder="<?php echo e(__("Enter Date")); ?>"  value="">
								</div>
								<div class="col-sm-3 mt-3">
								  <input type="text"  autocomplete="off" class="input-field discount_date" value="<?php echo e($end_date != '' ? $end_date->format('d-m-Y') : ''); ?>" name="end_date"  placeholder="<?php echo e(__("Enter Date")); ?>"  value="">
								</div>
								<div class="col-sm-4 mt-3">
								 <button type="submit" class="mybtn1">Check</button>
								 <button type="button" id="reset" class="mybtn1">Reset</button>
								</div>

                <div class="col-lg-12">
                  <p class="text-center"> <b> <?php echo e($start_date != '' ? $start_date->format('d-m-Y') : ''); ?> <?php echo e($start_date != '' && $end_date != '' ? 'To' : ''); ?>  <?php echo e($end_date != '' ? $end_date->format('d-m-Y') : ''); ?> <?php echo e(__('Total Earning')); ?> : <?php echo e($total); ?></b></p>
                  <div class="mr-table allproduct">

                </form>


                        <?php echo $__env->make('includes.admin.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="table-responsive">
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                          <thead>
                            <tr>
                                <th><?php echo e(__('Order Number')); ?></th>
                                <th><?php echo e(__('Total Earning')); ?></th>
                                <th><?php echo e(__('Payment Method')); ?></th>
                                <th><?php echo e(__('Txn Id')); ?></th>
                                <th><?php echo e(__('Order Date')); ?></th>
                            </tr>
                          </thead>
                          <tbody>

                            <?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                  <td>

                                    <a href="<?php echo e(route('vendor-order-invoice',$data->order->order_number)); ?>"><?php echo e($data->order->order_number); ?></a>
                                  </td>
                                  <td>
                                    <?php echo e($data->order->currency_sign . round($data->price * $data->order->currency_value, 2)); ?>

                                  </td>
                                  <td>
                                    <?php echo e($data->order->method); ?>

                                  </td>
                                  <td>
                                    <?php echo e($data->order->txnid); ?>

                                  </td>
                                  <td>
                                    <?php echo e($data->order->created_at->format('d-m-Y')); ?>

                                  </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                          </tbody>
                        </table>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>




<script type="text/javascript">
		$('#geniustable').DataTable();

  $(document).on('click','#reset',function(){
    $('.discount_date').val('');
    location.href = '<?php echo e(route('vendor.income')); ?>';
  })
</script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/earning.blade.php ENDPATH**/ ?>