 

<?php $__env->startSection('content'); ?>  

					<div class="content-area">

						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
									<h4 class="heading"><?php echo e(__("Addons")); ?></h4>
									<ul class="links">
										<li>
											<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__("Dashboard")); ?> </a>
										</li>
										<li>
											<a href="<?php echo e(route('admin-addon-index')); ?>"><?php echo e(__("Addons")); ?></a>
										</li>
									</ul>
								</div>
							</div>
						</div>

						<div class="product-area">
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">
                        				<?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  
										<div class="table-responsive">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
                                                            <th class="pl-2"><?php echo e(__("Name")); ?></th>
                                                            <th class="pl-2"><?php echo e(__("Keyword")); ?></th>
                                                            <th class="pl-2"><?php echo e(__("Installation Date")); ?></th>
														</tr>
													</thead>
												</table>
										</div>
									</div>
								</div>
							</div>
						</div>
						
					</div>


			

			<div class="modal fade" id="confirm-status">
				<div class="modal-dialog">
				<div class="modal-content">
			
					<!-- Modal Header -->
					<div class="modal-header text-center">
					<h4 class="modal-title w-100"><?php echo e(__('Confirm Uninstall')); ?></h4>
					</div>
			
					<!-- Modal body -->
					<div class="modal-body">
						<p class="text-center"><?php echo e(__('You are about to uninstall this Addon.')); ?></p>
						<p class="text-center"><?php echo e(__('Do you want to proceed?')); ?></p>
					</div>
			
					<!-- Modal footer -->
					<div class="modal-footer justify-content-center">
						<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
						<a  class="btn btn-success btn-ok"><?php echo e(__('Uninstall')); ?></a>
					</div>
			
				</div>
				</div>
			</div>
			
			

<?php $__env->stopSection(); ?>    

<?php $__env->startSection('scripts'); ?>

    <script type="text/javascript">

(function($) {
		"use strict";

		$('#geniustable').DataTable({
			ordering: false,
			processing: true,
			serverSide: true,
			ajax: '<?php echo e(route('admin-addon-datatables')); ?>',
			columns: [
					{ data: 'name', name: 'name' },
					{ data: 'uninstall_files', name: 'uninstall_files' },
					{ data: 'created_at', name: 'created_at' }
					],
			language : {
				processing: '<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>">'
			}
        });								

		$(function() {
			$(".btn-area").append('<div class="col-sm-4 table-contents">'+
				'<a class="add-btn" href="<?php echo e(route('admin-addon-create')); ?>">'+
			'<i class="fas fa-upload"></i> <?php echo e(__('Install New Addon')); ?>'+
			'</a>'+
			'</div>');
      	});	

})(jQuery);

	</script>
	
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/addon/index.blade.php ENDPATH**/ ?>