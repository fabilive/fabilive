<div class="product type-product">
    <div class="product-wrapper">
       <div class="product-image">

          <a href="<?php echo e(route('front.product', $prod->slug)); ?>" class="woocommerce-LoopProduct-link"><img src="<?php echo e($prod->thumbnail ? asset('assets/images/thumbnails/'.$prod->thumbnail):asset('assets/images/noimage.png')); ?>" alt="Product Image"></a>
          <?php if(!empty($prod->features)): ?>
          <div class="product-variations">
             <?php $__currentLoopData = $prod->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
             <span class="active sale"><a href="#" style="background-color: <?php echo e($prod->colors[$key]); ?>"><?php echo e($prod->features[$key]); ?></a></span>
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
          <?php endif; ?>
          
          <?php if($prod->offPercentage() && round($prod->offPercentage())>0): ?>
          <div class="on-sale">- <?php echo e(round($prod->offPercentage() )); ?>%</div>
          <?php endif; ?>

          <div class="hover-area">
            <?php if($prod->product_type == "affiliate"): ?>
            <div class="cart-button">
               <a href="javascript:;" data-href="<?php echo e($prod->affiliate_link); ?>" class="button add_to_cart_button affilate-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
            </div>
            <?php else: ?>
            <?php if($prod->emptyStock()): ?>
            <div class="closed">
               <a class="cart-out-of-stock button add_to_cart_button"  href="#" title="<?php echo e(__('Out Of Stock')); ?>" ><i class="flaticon-cancel flat-mini mx-auto"></i></a>
            </div>
            <?php else: ?>
            <?php if($prod->type != "Listing"): ?>
          
               <div class="cart-button">
                 
                  <a href="javascript:;"
                  data-bs-toggle="modal"  <?php echo e($prod->cross_products ? 'data-bs-target=#exampleModal' : ''); ?>  data-href="<?php echo e(route('product.cart.add',$prod->id)); ?>" data-cross-href="<?php echo e(route('front.show.cross.product',$prod->id)); ?>" class="add-cart button add_to_cart_button <?php echo e($prod->cross_products ? 'view_cross_product' : ''); ?>"  data-bs-placement="right"  title="Add To Cart" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
               </div>
               
       
            <?php endif; ?>
            <?php endif; ?>
            <?php endif; ?>
            <?php if(Auth::check()): ?>
            <div class="wishlist-button">
               <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish" href="javascript:;" data-href="<?php echo e(route('user-wishlist-add',$prod->id)); ?>" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title="Add to Wishlist" title="<?php echo e(__('Wishlist')); ?>" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
            </div>
            <?php else: ?>
            <div class="wishlist-button">
               <a class="add_to_wishlist button add_to_cart_button" href="<?php echo e(route('user.login')); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Wishlist')); ?>" data-bs-original-title="<?php echo e(__('Wishlist')); ?>" aria-label="<?php echo e(__('Wishlist')); ?>"><?php echo e(__('Wishlist')); ?></a>
            </div>
            <?php endif; ?>

            <?php if($prod->type != "Listing"): ?>
               <div class="compare-button">
                  <a class="compare button add_to_cart_button" data-href="<?php echo e(route('product.compare.add',$prod->id)); ?>" href="javascrit:;" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Compare')); ?>" data-bs-original-title="<?php echo e(__('Compare')); ?>" aria-label="<?php echo e(__('Compare')); ?>"><?php echo e(__('Compare')); ?></a>
               </div>
            <?php endif; ?>
         </div>
       </div>
       <div class="product-info">
          <h3 class="product-title"><a href="<?php echo e(route('front.product', $prod->slug)); ?>"><?php echo e($prod->showName()); ?></a></h3>
          <div class="product-price">
             <div class="price">
                <ins><?php echo e($prod->showPrice()); ?> </ins>
                <del><?php echo e($prod->showPreviousPrice()); ?></del>
             </div>
          </div>
          <div class="shipping-feed-back">
             <div class="star-rating">
                <div class="rating-wrap">
                   <p><i class="fas fa-star"></i><span> <?php echo e(number_format($prod->ratings_avg_rating,1)); ?> (<?php echo e($prod->ratings_count); ?>)</span></p>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>
<?php /**PATH C:\Users\Shilley Pc\fabilive\resources\views/partials/product/home-product.blade.php ENDPATH**/ ?>