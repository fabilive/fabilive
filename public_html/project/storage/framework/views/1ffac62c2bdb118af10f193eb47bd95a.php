 

<?php $__env->startSection('content'); ?>  
					<input type="hidden" id="headerdata" value="<?php echo e(__('TRANSACTION')); ?>">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading"><?php echo e(__('Transactions')); ?></h4>
										<ul class="links">
											<li>
												<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
											</li>
											<li>
												<a href="<?php echo e(route('admin-user-index')); ?>"><?php echo e(__('Customers')); ?></a>
											</li>
											<li>
												<a href="<?php echo e(route('admin-trans-index')); ?>"><?php echo e(__('Transactions')); ?></a>
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
									                        <th><?php echo e(__('Transaction ID')); ?></th>
									                        <th><?php echo e(__('Date')); ?></th>
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



				<div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
										
					<div class="modal-dialog modal-dialog-centered" role="document">
						<div class="modal-content">
							<div class="submit-loader">
								<img  src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>" alt="">
							</div>
							<div class="modal-header">
								<h5 class="modal-title"></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">

							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
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
               ajax: '<?php echo e(route('admin-trans-datatables')); ?>',
               columns: [
                        { data: 'name', name: 'name' },
                        { data: 'amount', name: 'amount' },
                        { data: 'txn_number', name: 'txn_number' },
                        { data: 'date', },
            			{ data: 'action', searchable: false, orderable: false }
                     ],
               language : {
                	processing: '<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>">'
                }
            });
											

		})(jQuery);

    </script>


    
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/trans/index.blade.php ENDPATH**/ ?>