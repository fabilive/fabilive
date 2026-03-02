<?php $__env->startSection('content'); ?>

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="heading"><?php echo e(__('My Withdraws')); ?></h4>
				<ul class="links">
					<li>
						<a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
					</li>
					<li>
						<a href="<?php echo e(route('vendor-wt-index')); ?>"><?php echo e(__('My Withdraws')); ?></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="product-area">
		<div class="row">
			<div class="col-lg-12">
				<div class="mr-table allproduct">
					<?php echo $__env->make('alerts.admin.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<div class="table-responsive">
						<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php echo e(__('Withdraw Date')); ?></th>
									<th><?php echo e(__('Method')); ?></th>
									<th><?php echo e(__('Account')); ?></th>
									<th><?php echo e(__('Amount')); ?></th>
									<th><?php echo e(__('Status')); ?></th>
								</tr>
							</thead>
							<tbody>
								<?php $__currentLoopData = $withdraws; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdraw): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
								<tr>
									<td><?php echo e(date('d-M-Y',strtotime($withdraw->created_at))); ?></td>
									<td><?php echo e($withdraw->method); ?></td>
									
									<td>
                                        <?php if($withdraw->method == "Bank"): ?>
                                            <?php echo e($withdraw->iban); ?>

                                        <?php elseif($withdraw->method == "Campay"): ?>
                                            <?php echo e($withdraw->campay_acc_no); ?>

                                        <?php else: ?>
                                            <?php echo e($withdraw->acc_email); ?>

                                        <?php endif; ?>
                                    </td>
									<td><?php echo e($sign->sign); ?><?php echo e(round($withdraw->amount * $sign->value , 2)); ?></td>
									<td><?php echo e(ucfirst($withdraw->status)); ?></td>
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
	(function($) {
		"use strict";
		var table = $('#geniustable').DataTable({
			ordering:false
		});
      	$(function() {
        $(".btn-area").append('<div class="col-sm-4 mt-2 text-right">'+
        	'<a class="add-btn" href="<?php echo e(route('vendor-wt-create')); ?>">'+
          '<i class="fas fa-plus"></i> <?php echo e(__('Withdraw Now')); ?>'+
          '</a>'+
          '</div>');
      });
	})(jQuery);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/withdraw/index.blade.php ENDPATH**/ ?>