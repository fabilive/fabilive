
<?php if(Session::has('view')): ?>
<?php if(Session::get('view')=='list-view'): ?>
<div class="row row-cols-xxl-2 row-cols-md-2 row-cols-1 g-3 product-style-1 shop-list product-list e-bg-light e-title-hover-primary e-hover-image-zoom">
    <?php $__currentLoopData = $vprods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col" >
        <div class="product type-product">
            <div class="product-wrapper">
                <div class="product-image">
                    <a href="<?php echo e(route('front.product', $product->slug)); ?>" class="woocommerce-LoopProduct-link"><img src="<?php echo e($product->thumbnail ? asset('assets/images/thumbnails/'.$product->thumbnail):asset('assets/images/noimage.png')); ?>" alt="Product Image"></a>
                    <div class="hover-area">
                        <?php if($product->product_type == "affiliate"): ?>
                        <div class="cart-button">
                           <a href="javascript:;" data-href="<?php echo e($product->affiliate_link); ?>" class="button add_to_cart_button affilate-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
                        </div>
                        <?php else: ?>
                        <?php if($product->emptyStock()): ?>
                        <div class="cart-button">
                           <a class="cart-out-of-stock button add_to_cart_button" href="#" title="<?php echo e(__('Out Of Stock')); ?>" ><i class="flaticon-cancel flat-mini mx-auto"></i></a>
                        </div>
                        <?php else: ?>
                        <div class="cart-button">
                            <a href="javascript:;" data-bs-toggle="modal" data-cross-href="<?php echo e(route('front.show.cross.product',$product->id)); ?>"  <?php echo e($product->cross_products ? 'data-bs-target=#exampleModal' : ''); ?> data-href="<?php echo e(route('product.cart.add',$product->id)); ?>" class="add-cart button add_to_cart_button <?php echo e($product->cross_products ? 'view_cross_product' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
                         </div>
                       
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if(Auth::check()): ?>
                        <div class="wishlist-button">
                           <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish" href="javascript:;" data-href="<?php echo e(route('user-wishlist-add',$product->id)); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Wishlist')); ?>" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                        </div>
                        <?php else: ?>
                        <div class="wishlist-button">
                           <a class="add_to_wishlist button add_to_cart_button" href="<?php echo e(route('user.login')); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Wishlist')); ?>" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                        </div>
                        <?php endif; ?>
                        <div class="compare-button">
                           <a class="compare button button add_to_cart_button" data-href="<?php echo e(route('product.compare.add',$product->id)); ?>" href="javascrit:;" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Compare')); ?>" data-bs-original-title="Compare" aria-label="Compare"><?php echo e(__('Compare')); ?></a>
                        </div>
                     </div>
                </div>
                <div class="product-info">
                    <h3 class="product-title"><a href="<?php echo e(route('front.product', $product->slug)); ?>"><?php echo e($product->showName()); ?></a></h3>
                    <div class="product-price">
                        <div class="price">
                            <ins><?php echo e($product->showPrice()); ?></ins>
                            <del><?php echo e($product->showPreviousPrice()); ?></del>
                        </div>
                    </div>
                    <div class="shipping-feed-back">
                        <div class="star-rating">
                            <div class="rating-wrap">
                                <p><i class="fas fa-star"></i><span> <?php echo e(number_format($product->ratings_avg_rating,1)); ?> (<?php echo e($product->ratings_count); ?>)</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<?php else: ?>
<div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 product-style-1 e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
    <?php $__currentLoopData = $vprods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col" >
        <div class="product type-product">
            <div class="product-wrapper">
                <div class="product-image">
                    <a href="<?php echo e(route('front.product', $product->slug)); ?>" class="woocommerce-LoopProduct-link"><img src="<?php echo e($product->thumbnail ? asset('assets/images/thumbnails/'.$product->thumbnail):asset('assets/images/noimage.png')); ?>" alt="Product Image"></a>
                    <div class="hover-area">
                        <?php if($product->product_type == "affiliate"): ?>
                        <div class="cart-button">
                           <a href="javascript:;" data-href="<?php echo e($product->affiliate_link); ?>" class="button add_to_cart_button affilate-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
                        </div>
                        <?php else: ?>
                        <?php if($product->emptyStock()): ?>
                        <div class="cart-button">
                           <a class="cart-out-of-stock button add_to_cart_button" href="#" title="<?php echo e(__('Out Of Stock')); ?>" ><i class="flaticon-cancel flat-mini mx-auto"></i></a>
                        </div>
                        <?php else: ?>
                        <?php if($product->type != 'Listing'): ?>
                            
                        <div class="cart-button">
                            <a href="javascript:;" data-bs-toggle="modal" data-cross-href="<?php echo e(route('front.show.cross.product',$product->id)); ?>"  <?php echo e($product->cross_products ? 'data-bs-target=#exampleModal' : ''); ?> data-href="<?php echo e(route('product.cart.add',$product->id)); ?>" class="add-cart button add_to_cart_button <?php echo e($product->cross_products ? 'view_cross_product' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
                         </div>

                        <?php endif; ?>
                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if(Auth::check()): ?>
                        <div class="wishlist-button">
                           <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish" href="javascript:;" data-href="<?php echo e(route('user-wishlist-add',$product->id)); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Wishlist')); ?>" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                        </div>
                        <?php else: ?>
                        <div class="wishlist-button">
                           <a class="add_to_wishlist button add_to_cart_button" href="<?php echo e(route('user.login')); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Wishlist')); ?>" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                        </div>
                        <?php endif; ?>

                        <?php if($product->type != 'Listing'): ?>
                            <div class="compare-button">
                                <a class="compare button button add_to_cart_button" data-href="<?php echo e(route('product.compare.add',$product->id)); ?>" href="javascrit:;" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Compare')); ?>" data-bs-original-title="Compare" aria-label="Compare"><?php echo e(__('Compare')); ?></a>
                            </div>
                        <?php endif; ?>
                     </div>
                </div>
                <div class="product-info">
                    <h3 class="product-title"><a href="<?php echo e(route('front.product', $product->slug)); ?>"><?php echo e($product->showName()); ?></a></h3>
                    <div class="product-price">
                        <div class="price">
                            <ins><?php echo e($product->showPrice()); ?></ins>
                            <del><?php echo e($product->showPreviousPrice()); ?></del>
                        </div>
                    </div>
                    <div class="shipping-feed-back">
                        <div class="star-rating">
                            <div class="rating-wrap">
                                <p><i class="fas fa-star"></i><span> <?php echo e(number_format($product->ratings_avg_rating,1)); ?> (<?php echo e($product->ratings_count); ?>)</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php endif; ?>

<?php else: ?>
<div class="row row-cols-xl-4 row-cols-md-3 row-cols-sm-2 row-cols-1 product-style-1 e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
    <?php $__currentLoopData = $vprods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col" >
        <div class="product type-product">
            <div class="product-wrapper">
                <div class="product-image">
                    <a href="<?php echo e(route('front.product', $product->slug)); ?>" class="woocommerce-LoopProduct-link"><img src="<?php echo e($product->thumbnail ? asset('assets/images/thumbnails/'.$product->thumbnail):asset('assets/images/noimage.png')); ?>" alt="Product Image"></a>
                    <div class="hover-area">
                        <?php if($product->product_type == "affiliate"): ?>
                        <div class="cart-button">
                           <a href="javascript:;" data-href="<?php echo e($product->affiliate_link); ?>" class="button add_to_cart_button affilate-btn" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
                        </div>
                        <?php else: ?>
                        <?php if($product->emptyStock()): ?>
                        <div class="cart-button">
                           <a class="cart-out-of-stock button add_to_cart_button" href="#" title="<?php echo e(__('Out Of Stock')); ?>" ><i class="flaticon-cancel flat-mini mx-auto"></i></a>
                        </div>
                        <?php else: ?>
                        <?php if($product->type != 'Listing'): ?>
                        <div class="cart-button">
                            <a href="javascript:;" data-bs-toggle="modal" data-cross-href="<?php echo e(route('front.show.cross.product',$product->id)); ?>"  <?php echo e($product->cross_products ? 'data-bs-target=#exampleModal' : ''); ?> data-href="<?php echo e(route('product.cart.add',$product->id)); ?>" class="add-cart button add_to_cart_button <?php echo e($product->cross_products ? 'view_cross_product' : ''); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="" data-bs-original-title="<?php echo e(__('Add To Cart')); ?>" aria-label="<?php echo e(__('Add To Cart')); ?>"></a>
                         </div>
                            
                        <?php endif; ?>

                        <?php endif; ?>
                        <?php endif; ?>
                        <?php if(Auth::check()): ?>
                        <div class="wishlist-button">
                           <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish" href="javascript:;" data-href="<?php echo e(route('user-wishlist-add',$product->id)); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Wishlist')); ?>" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                        </div>
                        <?php else: ?>
                        <div class="wishlist-button">
                           <a class="add_to_wishlist button add_to_cart_button" href="<?php echo e(route('user.login')); ?>" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Wishlist')); ?>" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist"><?php echo e(__('Wishlist')); ?></a>
                        </div>
                        <?php endif; ?>

                        <?php if($product->type != 'Listing'): ?>
                        <div class="compare-button">
                           <a class="compare button button add_to_cart_button" data-href="<?php echo e(route('product.compare.add',$product->id)); ?>" href="javascrit:;" data-bs-toggle="tooltip" data-bs-placement="right" title="<?php echo e(__('Compare')); ?>" data-bs-original-title="Compare" aria-label="Compare"><?php echo e(__('Compare')); ?></a>
                        </div>
                        <?php endif; ?>
                     </div>
                </div>
                <div class="product-info">
                    <h3 class="product-title"><a href="<?php echo e(route('front.product', $product->slug)); ?>"><?php echo e($product->showName()); ?></a></h3>
                    <div class="product-price">
                        <div class="price">
                            <ins><?php echo e($product->showPrice()); ?></ins>
                            <del><?php echo e($product->showPreviousPrice()); ?></del>
                        </div>
                    </div>
                    <div class="shipping-feed-back">
                        <div class="star-rating">
                            <div class="rating-wrap">
                                <p><i class="fas fa-star"></i><span> <?php echo e(number_format($product->ratings_avg_rating,1)); ?> (<?php echo e($product->ratings_count); ?>)</span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<?php endif; ?>


<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/partials/product/vendor-product-different-view.blade.php ENDPATH**/ ?>