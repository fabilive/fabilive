                                            <tr>
                                                <th><?php echo e(__("Title")); ?></th>
                                                <th><?php echo e(__("Details")); ?></th>
                                                <th><?php echo e(__("Date")); ?></th>
                                                <th><?php echo e(__("Time")); ?></th>
                                                <th><?php echo e(__("Action")); ?></th>
                                            </tr>
                                            <?php $__currentLoopData = $order->tracks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $track): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                            <tr data-id="<?php echo e($track->id); ?>">
                                                <td width="30%"><?php echo e($track->title); ?></td>
                                                <td width="30%"><?php echo e($track->text); ?></td>
                                                <td><?php echo e(date('Y-m-d',strtotime($track->created_at))); ?></td>
                                                <td><?php echo e(date('h:i:s:a',strtotime($track->created_at))); ?></td>
                                                <td>
                                                    <div class="action-list">
                                                        <a data-href="<?php echo e(route('admin-order-track-update',$track->id)); ?>" class="track-edit"> <i class="fas fa-edit"></i><?php echo e(__('Edit')); ?></a>
                                                        <a href="javascript:;" data-href="<?php echo e(route('admin-order-track-delete',$track->id)); ?>" class="track-delete"><i class="fas fa-trash-alt"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/track-load.blade.php ENDPATH**/ ?>