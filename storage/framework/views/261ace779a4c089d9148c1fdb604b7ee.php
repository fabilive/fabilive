
<?php $__env->startSection('content'); ?>




                            <div class="add-product-content1">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product-description">
                                            <div class="body-area" id="modalEdit">
                                                <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                                            <input type="hidden" id="track-store" value="<?php echo e(route('admin-order-track-store')); ?>">
                                            <form id="trackform" action="<?php echo e(route('admin-order-track-store')); ?>" method="POST" enctype="multipart/form-data">
                                                <?php echo e(csrf_field()); ?>

                                                <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>  

                                                <input type="hidden" name="order_id" value="<?php echo e($order->id); ?>">

                                                <div class="row">
                                                    <div class="col-lg-5">
                                                        <div class="left-area">
                                                                <h4 class="heading"><?php echo e(__('Title')); ?> *</h4>
                                                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <textarea class="input-field" id="track-title" name="title" placeholder="<?php echo e(__('Title')); ?>" required=""></textarea>
                                                    </div>
                                                </div>


                                                <div class="row">
                                                    <div class="col-lg-5">
                                                        <div class="left-area">
                                                                <h4 class="heading"><?php echo e(__('Details')); ?> *</h4>
                                                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <textarea class="input-field" id="track-details" name="text" placeholder="<?php echo e(__('Details')); ?>" required=""></textarea>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-lg-5">
                                                        <div class="left-area">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <button class="addProductSubmit-btn" id="track-btn" type="submit"><?php echo e(__('ADD')); ?></button>
                                                        <button class="addProductSubmit-btn ml=3 d-none" id="cancel-btn" type="button"><?php echo e(__('Cancel')); ?></button>
                                                        <input type="hidden" id="add-text" value="<?php echo e(__('ADD')); ?>">
                                                        <input type="hidden" id="edit-text" value="<?php echo e(__('UPDATE')); ?>">
                                                    </div>
                                                </div>
                                            </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

<hr>

                                                <h5 class="text-center"><?php echo e(__('TRACKING DETAILS')); ?></h5>

<hr>



						<div class="content-area no-padding">
							<div class="add-product-content">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area" id="modalEdit">


                                    <div class="table-responsive show-table ml-3 mr-3">
                                        <table class="table" id="track-load" data-href=<?php echo e(route('admin-order-track-load',$order->id)); ?>>
                                            <tr>
                                                <th><?php echo e(__("Title")); ?></th>
                                                <th><?php echo e(__("Details")); ?></th>
                                                <th><?php echo e(__("Date")); ?></th>
                                                <th><?php echo e(__("Time")); ?></th>
                                                <th><?php echo e(__("Options")); ?></th>
                                            </tr>
                                            <?php $__currentLoopData = $order->tracks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $track): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <tr data-id="<?php echo e($track->id); ?>">
                                                <td width="30%" class="t-title"><?php echo e($track->title); ?></td>
                                                <td width="30%" class="t-text"><?php echo e($track->text); ?></td>
                                                <td><?php echo e(date('Y-m-d',strtotime($track->created_at))); ?></td>
                                                <td><?php echo e(date('h:i:s:a',strtotime($track->created_at))); ?></td>
                                                <td>
                                                    <div class="action-list">
                                                        <a data-href="<?php echo e(route('admin-order-track-update',$track->id)); ?>" class="track-edit"> <i class="fas fa-edit"></i><?php echo e(__('Edit')); ?></a>
                                                        <a href="javascript:;" data-href="<?php echo e(route('admin-order-track-delete',$track->id)); ?>" class="track-delete"><i class="fas fa-trash-alt"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </table>
                                    </div>


											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/track.blade.php ENDPATH**/ ?>