<div class="modal fade" id="add-product" tabindex="-1" role="dialog" aria-labelledby="billing-details-edit"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>" alt="">
            </div>
            <div class="modal-header">
                <h5 class="modal-title"><?php echo e(__('Add Product')); ?> |  <code class="text-center show_vendor_message">
                    
                </code></h5>
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
                                        <form id="show-product" action="<?php echo e(route('admin-order-product-submit')); ?>"
                                            method="POST" enctype="multipart/form-data">
                                            <?php echo e(csrf_field()); ?>

                                            <input type="hidden" name="vendor_id" id="add_vendor_id" value="">
                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">
                                                        <h4 class="heading"><?php echo e(__('SKU')); ?> *</h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <input type="text" class="input-field" name="sku"
                                                        placeholder="<?php echo e(__('Enter Product Sku')); ?>" required=""
                                                        value="">
                                                </div>
                                            </div>

                                            <input type="hidden" name="order_id" id="order_id" value="<?php echo e($order->id); ?>">

                                            <div class="row">
                                                <div class="col-lg-4">
                                                    <div class="left-area">

                                                    </div>
                                                </div>
                                                <div class="col-lg-7">
                                                    <button class="addProductSubmit-btn mt-0" type="submit"><?php echo e(__('Submit')); ?></button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row d-block text-center" id="product-show">

                        </div>


                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
            </div>
        </div>
    </div>

</div><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/partials/add-product.blade.php ENDPATH**/ ?>