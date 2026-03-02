

<?php $__env->startSection('content'); ?>

<div class="content-area">

    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('Product Settings')); ?></h4>
                <ul class="links">
                    <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                    </li>
                    <li>
                        <a href="javascript:;"><?php echo e(__('Products')); ?></a>
                    </li>
                    <li>
                        <a href="javascript:;"><?php echo e(__('Product Settings')); ?></a>
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
                        <div class="gocover"
                            style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <form action="<?php echo e(route('admin-gs-prod-settings-update')); ?>" id="geniusform" method="POST"
                            enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>

                            <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            <?php echo e(__('Display Stock Number')); ?>

                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="action-list">
                                        <select
                                            class="process select droplinks <?php echo e($gs->show_stock == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                            <option data-val="1" value="<?php echo e(route('admin-gs-status',['show_stock',1])); ?>"
                                                <?php echo e($gs->show_stock == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?>

                                            </option>
                                            <option data-val="0" value="<?php echo e(route('admin-gs-status',['show_stock',0])); ?>"
                                                <?php echo e($gs->show_stock == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Product Whole Sale Max Quantity')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Product Whole Sale Max Quantity')); ?>" name="wholesell"
                                        value="<?php echo e($gs->wholesell); ?>" required="" min="0">
                                </div>
                            </div>

                            <hr>

                            <h4 class="text-center"><?php echo e(__('HOME PAGE SECTION')); ?></h4>
      
                            <hr>



                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Flash Deal Products')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Flash Deal Products')); ?>" name="flash_count"
                                        value="<?php echo e($gs->flash_count); ?>" required="" min="0">
                                </div>
                            </div>


                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Hot Products')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Hot Products')); ?>" name="hot_count"
                                        value="<?php echo e($gs->hot_count); ?>" required="" min="0">
                                </div>
                            </div>


                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display New Products')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display New Products')); ?>" name="new_count"
                                        value="<?php echo e($gs->new_count); ?>" required="" min="0">
                                </div>
                            </div>


                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Sale Products')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field" placeholder="<?php echo e(__('Display Sale Products')); ?>"
                                        name="sale_count" value="<?php echo e($gs->sale_count); ?>" required="" min="0">
                                </div>
                            </div>


                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Best Seller Products')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Best Seller Products')); ?>"
                                        name="best_seller_count" value="<?php echo e($gs->best_seller_count); ?>" required=""
                                        min="0">
                                </div>
                            </div>


                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Popular Products')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Popular Products')); ?>" name="popular_count"
                                        value="<?php echo e($gs->popular_count); ?>" required="" min="0">
                                </div>
                            </div>


                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Top Rated Products')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Top Rated Products')); ?>" name="top_rated_count"
                                        value="<?php echo e($gs->top_rated_count); ?>" required="" min="0">
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Big Save Products')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Big Save Products')); ?>" name="big_save_count"
                                        value="<?php echo e($gs->big_save_count); ?>" required="" min="0">
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Trending Products')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Trending Products')); ?>" name="trending_count"
                                        value="<?php echo e($gs->trending_count); ?>" required="" min="0">
                                </div>
                            </div>


                            <hr>

                            <h4 class="text-center"><?php echo e(__('CATEGORY PAGE SECTION')); ?></h4>
      
                            <hr>


                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Products Per Page')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Products Per Page')); ?>" name="page_count"
                                        value="<?php echo e($gs->page_count); ?>" required="" min="0">
                                </div>
                            </div>

                            <hr>

                            <h4 class="text-center"><?php echo e(__('VENDOR PAGE SECTION')); ?></h4>
      
                            <hr>


                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Products Per Page')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Products Per Page')); ?>" name="vendor_page_count"
                                        value="<?php echo e($gs->vendor_page_count); ?>" required="" min="0">
                                </div>
                            </div>


                            <hr>

                            <h4 class="text-center"><?php echo e(__('PRODUCT DETAILS PAGE SECTION')); ?></h4>
      
                            <hr>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            <?php echo e(__('Display Contact Seller')); ?>

                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="action-list">
                                        <select
                                            class="process select droplinks <?php echo e($gs->is_contact_seller == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                            <option data-val="1" value="<?php echo e(route('admin-gs-status',['is_contact_seller',1])); ?>"
                                                <?php echo e($gs->is_contact_seller == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?>

                                            </option>
                                            <option data-val="0" value="<?php echo e(route('admin-gs-status',['is_contact_seller',0])); ?>"
                                                <?php echo e($gs->is_contact_seller == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                           
                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading"><?php echo e(__('Display Product By Seller')); ?> *
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <input type="number" class="input-field"
                                        placeholder="<?php echo e(__('Display Products Per Page')); ?>" name="seller_product_count"
                                        value="<?php echo e($gs->seller_product_count); ?>" required="" min="0">
                                </div>
                            </div>


                            <hr>
                            <h4 class="text-center"><?php echo e(__('VENDOR PRODUCT CREATE ENABLE & DISABLE')); ?></h4>
      
                            <hr>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            <?php echo e(__('Vendor Physical Products')); ?>

                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="action-list">
                                        <select
                                            class="process select droplinks <?php echo e($gs->physical == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                            <option data-val="1" value="<?php echo e(route('admin-gs-status',['physical',1])); ?>"
                                                <?php echo e($gs->physical == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?>

                                            </option>
                                            <option data-val="0" value="<?php echo e(route('admin-gs-status',['physical',0])); ?>"
                                                <?php echo e($gs->physical == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            <?php echo e(__('Vendor Digital Products')); ?>

                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="action-list">
                                        <select
                                            class="process select droplinks <?php echo e($gs->digital == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                            <option data-val="1" value="<?php echo e(route('admin-gs-status',['digital',1])); ?>"
                                                <?php echo e($gs->digital == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?>

                                            </option>
                                            <option data-val="0" value="<?php echo e(route('admin-gs-status',['digital',0])); ?>"
                                                <?php echo e($gs->digital == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            <?php echo e(__('Vendor License Products')); ?>

                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="action-list">
                                        <select
                                            class="process select droplinks <?php echo e($gs->license == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                            <option data-val="1" value="<?php echo e(route('admin-gs-status',['license',1])); ?>"
                                                <?php echo e($gs->license == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?>

                                            </option>
                                            <option data-val="0" value="<?php echo e(route('admin-gs-status',['license',0])); ?>"
                                                <?php echo e($gs->license == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            <?php echo e(__('Vendor Listing Products')); ?>

                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="action-list">
                                        <select
                                            class="process select droplinks <?php echo e($gs->listing == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                            <option data-val="1" value="<?php echo e(route('admin-gs-status',['listing',1])); ?>"
                                                <?php echo e($gs->listing == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?>

                                            </option>
                                            <option data-val="0" value="<?php echo e(route('admin-gs-status',['listing',0])); ?>"
                                                <?php echo e($gs->listing == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">
                                        <h4 class="heading">
                                            <?php echo e(__('Vendor Affilite Products')); ?>

                                        </h4>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="action-list">
                                        <select
                                            class="process select droplinks <?php echo e($gs->affilite == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                            <option data-val="1" value="<?php echo e(route('admin-gs-status',['affilite',1])); ?>"
                                                <?php echo e($gs->affilite == 1 ? 'selected' : ''); ?>><?php echo e(__('Activated')); ?>

                                            </option>
                                            <option data-val="0" value="<?php echo e(route('admin-gs-status',['affilite',0])); ?>"
                                                <?php echo e($gs->affilite == 0 ? 'selected' : ''); ?>><?php echo e(__('Deactivated')); ?>

                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>




                            <hr>

                            <h4 class="text-center"><?php echo e(__('WISHLIST PAGE SECTION')); ?></h4>
      
                            <hr>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading"><?php echo e(__('Display Products Per Page')); ?> *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="number" class="input-field"
                                            placeholder="<?php echo e(__('Display Products Per Page')); ?>" name="wishlist_count"
                                            value="<?php echo e($gs->wishlist_count); ?>" required="" min="0">
                                    </div>
                                </div>
                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading"><?php echo e(__('View Wishlist Product Per Page')); ?> *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <ul id="wishlist_page" class="myTags">
                                            <?php $__currentLoopData = explode(',',$gs->wishlist_page); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                              <li><?php echo e($element); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>




                                <hr>

                                <h4 class="text-center"><?php echo e(__('CATALOG & FILTER SECTION')); ?></h4>
          
                                <hr>
    
                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading"><?php echo e(__('Minimum Price')); ?> *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="number" class="input-field"
                                            placeholder="<?php echo e(__('Minimum Price')); ?>" name="min_price"
                                            value="<?php echo e($gs->min_price); ?>" required="" min="0">
                                    </div>
                                </div>

                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading"><?php echo e(__('Maximum Price')); ?> *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <input type="number" class="input-field"
                                            placeholder="<?php echo e(__('Maximum Price')); ?>" name="max_price"
                                            value="<?php echo e($gs->max_price); ?>" required="" min="0">
                                    </div>
                                </div>


                                <div class="row justify-content-center">
                                    <div class="col-lg-3">
                                        <div class="left-area">
                                            <h4 class="heading"><?php echo e(__('View Product Per Page')); ?> *
                                            </h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <ul id="product_page" class="myTags">
                                            <?php $__currentLoopData = explode(',',$gs->product_page); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                              <li><?php echo e($element); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                </div>

                            <div class="row justify-content-center">
                                <div class="col-lg-3">
                                    <div class="left-area">

                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Save')); ?></button>
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



<script type="text/javascript">

(function($) {
		"use strict";

          $("#product_page").tagit({
            fieldName: "product_page[]",
            allowSpaces: true 
          });
          $("#wishlist_page").tagit({
            fieldName: "wishlist_page[]",
            allowSpaces: true 
          });

})(jQuery);

</script>



<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/product/settings.blade.php ENDPATH**/ ?>