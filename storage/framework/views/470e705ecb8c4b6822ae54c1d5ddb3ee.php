
<?php $__env->startSection('styles'); ?>

<link href="<?php echo e(asset('assets/admin/css/product.css')); ?>" rel="stylesheet"/>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

						<div class="content-area">
							<div class="mr-breadcrumb">
								<div class="row">
									<div class="col-lg-12">
											<h4 class="heading"><?php echo e(__("Product Bulk Upload")); ?></h4>
											<ul class="links">
												<li>
													<a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__("Dashboard")); ?></a>
												</li>
												<li>
													<a href="<?php echo e(route('vendor-prod-import')); ?>"><?php echo e(__("Bulk Upload")); ?></a>
												</li>
											</ul>
									</div>
								</div>
							</div>
							<div class="add-product-content">
								<div class="row">
									<div class="col-lg-12 p-5">

					                      <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
					                      <form id="geniusform" action="<?php echo e(route('vendor-prod-importsubmit')); ?>" method="POST" enctype="multipart/form-data">
					                        <?php echo e(csrf_field()); ?>


                        					<?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

											  <div class="row">
												  <div class="col-lg-12 text-right">
													  <span style="margin-top:10px;"><a class="btn btn-primary" href="<?php echo e(asset('assets/product-csv-format.csv')); ?>"><?php echo e(__("Download Sample CSV")); ?></a></span>
												  </div>

											  </div>
											  <hr>

											  <div class="row justify-content-center">
												<div class="col-lg-12 d-flex justify-content-center text-center">
													  <div class="csv-icon">
														<h4 class="heading"><?php echo e(__('Select Language')); ?>*</h4>
													  </div>
												</div>
												<div class="col-lg-6 d-flex justify-content-center text-center">
													<select name="language_id" required="">
														<?php $__currentLoopData = DB::table('languages')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ldata): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
														  <option value="<?php echo e($ldata->id); ?>"><?php echo e($ldata->language); ?></option>
														<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													</select>
												</div>

											</div>
										  <div class="row text-center">
											  <div class="col-lg-12">
													<div class="csv-icon">
														<i class="fas fa-file-csv"></i>
													</div>
											  </div>
											  <div class="col-lg-12">
												  <div class="left-area mr-4">
													  <h4 class="heading"><?php echo e(__("Upload a File")); ?> *</h4>
												  </div>
												  <span class="file-btn">
													  <input type="file" id="csvfile" name="csvfile" accept=".csv">
												  </span>

											  </div>
										  </div>

						                        <input type="hidden" name="type" value="Physical">
												<div class="row">
													<div class="col-lg-12 mt-4 text-center">
														<button class="mybtn1 mr-5" type="submit"><?php echo e(__("Start Import")); ?></button>
													</div>
												</div>
											</form>
									</div>
								</div>
							</div>
						</div>



<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

<?php echo $__env->make('partials.admin.product.product-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/product/productcsv.blade.php ENDPATH**/ ?>