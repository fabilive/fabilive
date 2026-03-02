<div class="modal fade" id="shipping-details-edit" tabindex="-1" role="dialog" aria-labelledby="shipping-details-edit" aria-hidden="true">
										
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img  src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('Shipping Details')); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="content-area">

                    <div class="add-product-content1">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="product-description">
                                    <div class="body-area">
                                    <form  action="<?php echo e(route('admin-order-update',$order->id)); ?>" method="POST" enctype="multipart/form-data">
                                        <?php echo e(csrf_field()); ?>


                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading"><?php echo e(__('Name')); ?> *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <input type="text" class="input-field" name="shipping_name" placeholder="<?php echo e(__('Name')); ?>" required="" value="<?php echo e($order->shipping_name == null ? $order->customer_name : $order->shipping_name); ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading"><?php echo e(__('Email')); ?> *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <input type="email" class="input-field" name="shipping_email" placeholder="<?php echo e(__('Email')); ?>" required="" value="<?php echo e($order->shipping_email == null ? $order->customer_email : $order->shipping_email); ?>">
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading"><?php echo e(__('Phone')); ?> *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <input type="text" class="input-field" name="shipping_phone" placeholder="<?php echo e(__('Phone')); ?>" required="" value="<?php echo e($order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone); ?>">
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading"><?php echo e(__('Address')); ?> *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <input type="text" class="input-field" name="shipping_address" placeholder="<?php echo e(__('Address')); ?>" required="" value="<?php echo e($order->shipping_address == null ? $order->customer_address : $order->shipping_address); ?>">
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading"><?php echo e(__('City')); ?> *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <input type="text" class="input-field" name="shipping_city" placeholder="<?php echo e(__('City')); ?>" required="" value="<?php echo e($order->shipping_city == null ? $order->customer_city : $order->shipping_city); ?>">
                                            </div>
                                        </div>

                                        
                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading"><?php echo e(__('State')); ?> *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <input type="text" class="input-field" name="shipping_state" placeholder="<?php echo e(__('State')); ?>" required="" value="<?php echo e($order->shipping_state == null ?  $order->customer_state: $order->shipping_state); ?>">
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading"><?php echo e(__('Country')); ?> *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <select type="text" class="input-field" name="shipping_country" required="">
                                                    <option value=""><?php echo e(__('Select Country')); ?></option>
                                                    <?php $__currentLoopData = DB::table('countries')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option value="<?php echo e($data->country_name); ?>" <?php echo e($order->shipping_country == $data->country_name ? 'selected' : ''); ?>>
                                                            <?php echo e($data->country_name); ?>

                                                        </option>		
                                                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    <h4 class="heading"><?php echo e(__('Postal Code')); ?> *</h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <input type="text" class="input-field" name="shipping_zip" placeholder="<?php echo e(__('Postal Code')); ?>" required="" value="<?php echo e($order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip); ?>">
                                            </div>
                                        </div>



                                        <div class="row">
                                            <div class="col-lg-4">
                                                <div class="left-area">
                                                    
                                                </div>
                                            </div>
                                            <div class="col-lg-7">
                                                <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Submit')); ?></button>
                                            </div>
                                        </div>
                                    </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
            </div>
        </div>
    </div>

</div><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/partials/shipping-details.blade.php ENDPATH**/ ?>