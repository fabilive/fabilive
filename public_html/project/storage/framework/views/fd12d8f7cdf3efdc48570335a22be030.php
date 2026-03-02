
<?php $__env->startSection('content'); ?>

						<div class="content-area no-padding">
							<div class="add-product-content1">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">

                                    <div class="table-responsive show-table">
                                        <table class="table">
                                            <tr>
                                                <th><?php echo e(__("Staff ID#")); ?></th>
                                                <td><?php echo e($data->id); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Staff Photo")); ?></th>
                                                <td>
                                              <img src="<?php echo e($data->photo ? asset('assets/images/admins/'.$data->photo):asset('assets/images/noimage.png')); ?>" alt="<?php echo e(__("No Image")); ?>">

                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Staff Name")); ?></th>
                                                <td><?php echo e($data->name); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Staff Role")); ?></th>
                                                <td><?php echo e($data->role_id == 0 ? 'No Role' : $data->role->name); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Staff Email")); ?></th>
                                                <td><?php echo e($data->email); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Staff Phone")); ?></th>
                                                <td><?php echo e($data->phone); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Joined")); ?></th>
                                                <td><?php echo e($data->created_at->diffForHumans()); ?></td>
                                            </tr>
                                        </table>
                                    </div>


											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/staff/show.blade.php ENDPATH**/ ?>