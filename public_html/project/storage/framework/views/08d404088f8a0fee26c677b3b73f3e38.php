 

<?php $__env->startSection('content'); ?>  

					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading"><?php echo e(__('Popular Products')); ?></h4>
										<ul class="links">
											<li>
												<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
											</li>
											<li>
												<a href="javascript:;"><?php echo e(__('SEO Tools')); ?> </a>
											</li>
											<li>
												<a href="javascript:;"><?php echo e(__('Popular Products')); ?></a>
											</li>
										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">
							          <?php echo $__env->make('alerts.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
							          <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
										<div class="table-responsive">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
									                        <th><?php echo e(__('Name')); ?></th>
									                        <th><?php echo e(__('Category')); ?></th>
									                        <th><?php echo e(__('Type')); ?></th>
									                        <th><?php echo e(__('Clicks')); ?></th>
														</tr>
													</thead>

                                              <tbody>
                                                <?php $__currentLoopData = $productss; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> 
                    								<?php $__currentLoopData = $productt; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $prod): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                        <tr>

														<td>
															<?php echo e(mb_strlen($prod->product->name,'UTF-8') > 60 ? mb_substr($prod->product->name,0,60,'UTF-8').'...' : $prod->product->name); ?>

														</td>
                                                      <td>
                                                        <?php echo e($prod->product->category->name); ?>

                                                      </td>
												  <td>
												<?php echo e($prod->product->type); ?>

												  </td>
                                      <td><?php echo e($productt->count('product_id')); ?></td>
                                                  </tr>

                                                  <?php break; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



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

 			$('#geniustable').DataTable({
			   ordering: false
            });

$( document ).ready(function() {
        $(".btn-area").append('<div class="col-sm-4 table-contents">'+
        '<select class="form-control" id="prevdate">'+
          '<option value="30" <?php echo e($val==30 ? 'selected':''); ?>><?php echo e(__('Last 30 Days')); ?></option>'+
          '<option value="15" <?php echo e($val==15 ? 'selected':''); ?>><?php echo e(__('Last 15 Days')); ?></option>'+
          '<option value="7" <?php echo e($val==7 ? 'selected':''); ?>><?php echo e(__('Last 7 Days')); ?></option>'+
        '</select>'+
          '</div>'); 

        $("#prevdate").change(function () {
        var sort = $("#prevdate").val();
        window.location = "<?php echo e(url('/admin/products/popular/')); ?>/"+sort;
    });                                                                      
});

})(jQuery);

</script>

<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/seotool/popular.blade.php ENDPATH**/ ?>