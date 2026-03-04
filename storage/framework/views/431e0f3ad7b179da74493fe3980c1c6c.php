<?php $__env->startSection('content'); ?>

						<div class="content-area">
							<div class="add-product-content1">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area" id="modalEdit">
                        					<?php echo $__env->make('alerts.admin.form-error', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
											<form id="geniusformdata" action="<?php echo e(route('admin-user-deposit-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
												<?php echo e(csrf_field()); ?>

												<div class="row">
                                                        <div class="col-lg-4">
                                                            <div class="left-area">
                                                                    <h4 class="heading"><?php echo e(__("Current Balance")); ?> *</h4>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-7">
                                                            
                                                                <h6 class="heading"><?php echo e($sign->sign); ?><?php echo e(number_format($data->balance, 2)); ?></h6>
                                                        </div>
                                                    </div>
												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
                                                                <h4 class="heading"><?php echo e(__("Amount")); ?> *</h4>
                                                                <p class="sub-heading">(<?php echo e(__("In")); ?> <?php echo e($sign->name); ?>)</p>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="number" class="input-field" name="amount" placeholder="<?php echo e(__("Amount")); ?>" required="" value="1" min="1" step="0.1">
													</div>
												</div>
												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Details")); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="details" placeholder="<?php echo e(__("Details")); ?>" required="" value="">
													</div>
												</div>
												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading"><?php echo e(__("Action")); ?> *</h4>
														</div>
													</div>
													<div class="col-lg-7">
                                                        <select class="input-field" name="type">
                                                            <option value="plus"><?php echo e(__('Add')); ?></option>
                                                            <option value="minus"><?php echo e(__('Subtract')); ?></option>
                                                        </select>
													</div>
												</div>

                                                <input type="hidden" name="currency_sign" value="<?php echo e($sign->sign); ?>">
                                                <input type="hidden" name="currency_code" value="<?php echo e($sign->name); ?>">
						                        <div class="row">
						                          <div class="col-lg-4">
						                            <div class="left-area">
						                            </div>
						                          </div>
						                          <div class="col-lg-7">
						                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__("Submit")); ?></button>
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

<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/user/deposit.blade.php ENDPATH**/ ?>