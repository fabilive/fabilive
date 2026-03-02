<?php $__env->startSection('content'); ?>
					<input type="hidden" id="headerdata" value="<?php echo e(__('PRODUCT')); ?>">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading"><?php echo e(__('Product Catalogs')); ?></h4>
										<ul class="links">
											<li>
												<a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
											</li>
											<li>
												<a href="javascript:;"><?php echo e(__('Products')); ?> </a>
											</li>
											<li>
												<a href="<?php echo e(route('admin-vendor-catalog-index')); ?>"><?php echo e(__('Product Catalogs')); ?></a>
											</li>
										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">

                        <?php echo $__env->make('alerts.vendor.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

										<div class="table-responsive">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
									                        <th><?php echo e(__('Name')); ?></th>
									                        <th><?php echo e(__('Type')); ?></th>
									                        <th><?php echo e(__('Price')); ?></th>
									                        <th><?php echo e(__('Actions')); ?></th>
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
               ajax: '<?php echo e(route('admin-vendor-catalog-datatables')); ?>',
               columns: [
                        { data: 'name', name: 'name' },
                        { data: 'type', name: 'type' },
                        { data: 'price', name: 'price' },
            			{ data: 'action', searchable: false, orderable: false }

                     ],
                language : {
                	processing: '<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>">'
                },
				drawCallback : function( settings ) {
	    				$('.select').niceSelect();
				}
            });

})(jQuery);


</script>



<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/product/catalogs.blade.php ENDPATH**/ ?>