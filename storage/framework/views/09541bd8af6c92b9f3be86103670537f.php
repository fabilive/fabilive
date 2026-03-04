
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
                                                <th width="50%"><?php echo e(__("Customer Name")); ?></th>
                                                <td><?php echo e($data->user['name']); ?></td>
                                            </tr>
                                            <tr>
                                                <th width="50%"><?php echo e(__("Amount")); ?></th>
                                                <td><?php echo e($data->type == 'plus' ? '+':'-'); ?><?php echo e(\PriceHelper::showOrderCurrencyPrice(($data->amount * $data->currency_value),$data->currency_sign)); ?></td>
                                            </tr>
                                            <tr>
                                                <th width="50%"><?php echo e(__("Transaction ID")); ?></th>
                                                <td><?php echo e($data->txn_number); ?></td>
                                            </tr>
                                            <?php if($data->method != null): ?>
                                            <tr>
                                                <th width="50%"><?php echo e(__("Method")); ?></th>
                                                <td><?php echo e($data->method); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php if($data->txnid != null): ?>
                                            <tr>
                                                <th width="50%"><?php echo e($data->method); ?> <?php echo e(__("Transaction ID")); ?></th>
                                                <td><?php echo e($data->txnid); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                            <tr>
                                                <th width="50%"><?php echo e(__("Details")); ?></th>
                                                <td><?php echo e($data->details); ?></td>
                                            </tr>
                                            <tr>
                                                <th width="50%"><?php echo e(__("Transaction Date")); ?></th>
                                                <td><?php echo e($data->created_at); ?></td>
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
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/trans/show.blade.php ENDPATH**/ ?>