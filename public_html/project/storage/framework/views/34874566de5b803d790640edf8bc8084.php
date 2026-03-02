<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Wishlist')); ?></h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(('Wishlist')); ?></li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<!--==================== Wishlist Section Start ====================-->
<div class="full-row">
   <div class="container" id="ajaxContent">
        <div class="mb-4 d-xl-none">
            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
      <div class="row wish_load">
         <div class="col-12">
            <table class="shop_table cart wishlist_table wishlist_view traditional table" data-pagination="no" data-per-page="5" data-page="1" data-id="3989" data-token="G5CZRAZPRKEY">
               <thead>
                  <tr>
                     <th class="product-thumbnail"><?php echo e(__('Product Image')); ?></th>
                     <th class="product-name"> <span class="nobr"> <?php echo e(__('Product name')); ?> </span></th>
                     <th class="product-price"> <span class="nobr"> <?php echo e(__('Unit price')); ?> </span></th>
                     <th class="product-stock-status"> <span class="nobr"> <?php echo e(__('Stock status')); ?> </span></th>
                     <th class="product-add-to-cart"> <span class="nobr"> </span><?php echo e(__('Actions')); ?></th>
                     <th class="product-remove"> <span class="nobr"> </span></th>
                  </tr>
               </thead>
               <tbody class="wishlist-items-wrapper">
                  <?php $__currentLoopData = $wishlists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wishlist): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <tr id="yith-wcwl-row-103" data-row-id="103">
                     <td class="product-thumbnail">
                        <a href="<?php echo e(route('front.product', $wishlist->slug)); ?>"> <img src="<?php echo e($wishlist->photo ? asset('assets/images/products/'.$wishlist->photo):asset('assets/images/noimage.png')); ?>" alt=""> </a>
                     </td>
                     <td class="product-name"> <a href="<?php echo e(route('front.product', $wishlist->slug)); ?>"><?php echo e(mb_strlen($wishlist->name,'UTF-8') > 35 ? mb_substr($wishlist->name,0,35,'UTF-8').'...' : $wishlist->name); ?></a></td>
                     <td class="product-price"> <span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"><?php echo e($wishlist->showPrice()); ?>  <small>
                        <del>
                        <?php echo e($wishlist->showPreviousPrice()); ?>

                        </del>
                        </small></bdi>
                        </span>
                     </td>
                     <td class="product-stock-status">
                        <?php if($wishlist->type == 'Physical'): ?>
                        <?php if($wishlist->emptyStock()): ?>
                        <div class="stock-availability out-stock"><?php echo e(('Out Of Stock')); ?></div>
                        <?php else: ?>
                        <div class="stock-availability in-stock text-bold"><?php echo e(('In Stock')); ?></div>
                        <?php endif; ?>
                        <?php else: ?>
                        <div class="stock-availability in-stock text-bold"><?php echo e($wishlist->type); ?></div>
                        <?php endif; ?>
                     </td>
                     <td class="product-add-to-cart">
                        <!-- Date added -->
                        
                        <?php if($wishlist->type == "affiliate"): ?>
                        <li class="addtocart">
                           <a class="affilate-btn"  data-href="<?php echo e($productt->affiliate_link); ?>" target="_blank" > <?php echo e(__('Buy Now')); ?></a>
                        </li>
                        <?php else: ?>
                        <?php if($wishlist->emptyStock()): ?>
                        <li class="addtocart">
                           <a href="javascript:;" class="cart-out-of-stock">
                           <?php echo e(__('Out Of Stock')); ?></a>
                        </li>
                        <?php else: ?>
                        <li class="addtocart">
                           <a href="javascript:;" class="add-cart"  data-href="<?php echo e(route('product.cart.add',$wishlist->id)); ?>"><?php echo e(__('Add to Cart')); ?></a>
                        </li>
                        <li class="addtocart">
                           <a id="qaddcrt" class="add-to-cart-quick" href="javascript:;" data-href="<?php echo e(route('product.cart.quickadd',$wishlist->id)); ?>">
                           <?php echo e(__('Buy Now')); ?>

                           </a>
                        </li>
                        <?php endif; ?>
                        <?php endif; ?>
                        <!-- Remove from wishlist -->
                     </td>
                     <td class="product-remove">
                        <div>
                           <a href="javascript:;" data-href="<?php echo e(route('user-wishlist-remove', App\Models\Wishlist::where('user_id','=',$user->id)->where('product_id','=',$wishlist->id)->first()->id )); ?>" class="remove wishlist-remove remove_from_wishlist" title="Remove this product">×</a>
                        </div>
                     </td>
                     <input type="hidden" id="product_price" value="<?php echo e(round($wishlist->vendorPrice() * $curr->value,2)); ?>">
                     <input type="hidden" id="product_id" value="<?php echo e($wishlist->id); ?>">
                     <input type="hidden" id="curr_pos" value="<?php echo e($gs->currency_format); ?>">
                     <input type="hidden" id="curr_sign" value="<?php echo e($curr->sign); ?>">
                  </tr>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<!--==================== Wishlist Section End ====================-->
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/wishlist.blade.php ENDPATH**/ ?>