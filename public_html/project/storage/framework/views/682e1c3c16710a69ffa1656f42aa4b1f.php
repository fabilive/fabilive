
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
                                                <th><?php echo e(__("User ID#")); ?></th>
                                                <td><?php echo e($withdraw->user->id); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("User Name")); ?></th>
                                                <td>
                                                    <a href="<?php echo e(route('admin-user-show',$withdraw->user->id)); ?>" target="_blank"><?php echo e($withdraw->user->name); ?></a>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Withdraw Amount")); ?></th>
                                                <td><?php echo e(\PriceHelper::showAdminCurrencyPrice($withdraw->amount * $sign->value)); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Withdraw Charge")); ?></th>
                                                <td><?php echo e(\PriceHelper::showAdminCurrencyPrice($withdraw->fee * $sign->value)); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Withdraw Process Date")); ?></th>
                                                <td><?php echo e(date('d-M-Y',strtotime($withdraw->created_at))); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Withdraw Status")); ?></th>
                                                <td><?php echo e(ucfirst($withdraw->status)); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("User Email")); ?></th>
                                                <td><?php echo e($withdraw->user->email); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("User Phone")); ?></th>
                                                <td><?php echo e($withdraw->user->phone); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Withdraw Method")); ?></th>
                                                <td><?php echo e($withdraw->method); ?></td>
                                            </tr>
                                            <?php if($withdraw->method != "Bank"): ?>
                                            <tr>
                                                <th><?php echo e($withdraw->method); ?> <?php echo e(__("Email")); ?>:</th>
                                                <td><?php echo e($withdraw->acc_email); ?></td>
                                            </tr>
                                            <?php else: ?> 
                                            <tr>
                                                <th><?php echo e($withdraw->method); ?> <?php echo e(__("Account")); ?>:</th>
                                                <td><?php echo e($withdraw->iban); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Account Name")); ?>:</th>
                                                <td><?php echo e($withdraw->acc_name); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Country")); ?></th>
                                                <td><?php echo e(ucfirst(strtolower($withdraw->country))); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e(__("Address")); ?></th>
                                                <td><?php echo e($withdraw->address); ?></td>
                                            </tr>
                                            <tr>
                                                <th><?php echo e($withdraw->method); ?> <?php echo e(__("Swift Code")); ?>:</th>
                                                <td><?php echo e($withdraw->swift); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>


                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.load', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/vendor/withdraw-details.blade.php ENDPATH**/ ?>