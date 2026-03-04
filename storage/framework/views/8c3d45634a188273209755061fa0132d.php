 

<?php $__env->startSection('content'); ?>  
					<div class="content-area">

						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading"><?php echo e(__('Completed Deposits')); ?></h4>
										<ul class="links">
											<li>
												<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
											</li>
											<li>
												<a href="javascript:;"><?php echo e(__('Customer Deposits')); ?> </a>
											</li>
											<li>
												<a href="<?php echo e(route('admin-sociallink-index')); ?>"><?php echo e(__('Completed Deposits')); ?></a>
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
									                        <th><?php echo e(__('Customer Name')); ?></th>
															<th><?php echo e(__('Amount')); ?></th>
									                        <th><?php echo e(__('Payment Method')); ?></th>
									                        <th><?php echo e(__('Transaction ID')); ?></th>
									                        <th><?php echo e(__('Status')); ?></th>
														</tr>
													</thead>
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
			   ordering: false,
               processing: true,
               serverSide: true,
               ajax: '<?php echo e(route('admin-user-deposit-datatables','1')); ?>',
               columns: [
                        { data: 'name', name: 'name' },
                        { data: 'amount', name: 'amount' },
                        { data: 'method', name: 'method' },
                        { data: 'txnid', name: 'txnid' },
            			{ data: 'action', searchable: false, orderable: false }

                     ],
               language: {
                	processing: '<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>">'
                }
            });


	})(jQuery);		

	</script>
	
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/deposit/index.blade.php ENDPATH**/ ?>