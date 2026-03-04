

<?php $__env->startSection('styles'); ?>

<link href="<?php echo e(asset('assets/admin/css/jquery-ui.css')); ?>" rel="stylesheet" type="text/css">

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

						<div class="content-area">

							<div class="mr-breadcrumb">
								<div class="row">
								  <div class="col-lg-12">
									  <h4 class="heading"><?php echo e(__('Add New Link')); ?> <a class="add-btn" href="<?php echo e(route('admin-sociallink-index')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
									  <ul class="links">
										<li>
										  <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
										</li>
										<li>
										  <a href="javascript:;"><?php echo e(__('Settings')); ?></a>
										</li>
										<li>
										  <a href="<?php echo e(route('admin-sociallink-index')); ?>"><?php echo e(__('Social Links')); ?></a>
										</li>
										<li>
										  <a href="<?php echo e(route('admin-sociallink-create')); ?>"><?php echo e(__('Add')); ?></a>
										</li>
									  </ul>
								  </div>
								</div>
							  </div>


							<div class="add-product-content1 add-product-content2">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">

											<div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>

											<?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
											
											<form id="geniusform" action="<?php echo e(route('admin-sociallink-create')); ?>" method="POST" enctype="multipart/form-data">
												<?php echo e(csrf_field()); ?>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
															<h4 class="heading"><?php echo e(__('Social Link')); ?> *</h4>
															<p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="link" placeholder="<?php echo e(__('Social Link')); ?>" required="" value="">
													</div>
												</div>

												<div class="row">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading"><?php echo e(__('Icon')); ?> *</h4>
                                                        </div>
                                                    </div>
													 
													 <div class="col-lg-7 d-flex">
														 <i class="" id="icn"></i>
														 <input type="text" id="icons" class="input-field" name="icon" placeholder="<?php echo e(__('Social Icon')); ?>" required="" value="">
														
													</div>

                                                </div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
															
														</div>
													</div>
													<div class="col-lg-7">
														<button class="addProductSubmit-btn" type="submit"><?php echo e(__('Create')); ?></button>
													</div>
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

<script src="<?php echo e(asset('assets/admin/js/iconpicker.js')); ?>"></script>

<script>

$( "#icons" ).autocomplete({
	  source: icons,
	  select: function (event, ui) {
    var label = ui.item.label;
    var value = ui.item.value;
   	$('#icn').prop('class',value);
}
    })

$('#icons').on('change',function(){
	$('#icn').prop('class',$(this).val());
})

</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/sociallink/create.blade.php ENDPATH**/ ?>