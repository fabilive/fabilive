<div class="cart-popup">
    <ul class="cart_list product_list_widget ">
        <?php if(Session::has('cart')): ?>
        <?php $__currentLoopData = Session::get('cart')->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <li class="mini-cart-item">
            <div class="cart-remove remove" data-class="cremove<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>"
            data-href="<?php echo e(route('product.cart.remove',$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values']))); ?>" title="Remove this item"><i class="fas fa-times"></i></div>

            <a href="<?php echo e(route('front.product', $product['item']['slug'])); ?>" class="product-image"><img  src="<?php echo e($product['item']['photo'] ? filter_var($product['item']['photo'], FILTER_VALIDATE_URL) ?$product['item']['photo']:asset('assets/images/products/'.$product['item']['photo']):asset('assets/images/noimage.png')); ?>" class="attachment-woocommerce_thumbnail size-woocommerce_thumbnail" alt="Cart product"></a>

            <a href="<?php echo e(route('front.product',$product['item']['slug'])); ?>" class="product-name"><?php echo e(mb_strlen($product['item']['name'],'UTF-8') > 45 ? mb_substr($product['item']['name'],0,45,'UTF-8').'...' : $product['item']['name']); ?></a>


            <div class="cart-item-quantity">
                <span class="cart-product-qty"
                id="cqt<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>"><?php echo e($product['qty']); ?></span><span><?php echo e($product['item']['measure']); ?></span>
            x <span
                id="prct<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>"><?php echo e(App\Models\Product::convertPrice($product['item_price'])); ?> <?php echo e($product['discount'] == 0 ? '' : '('.$product['discount'].'% '.__('Off').')'); ?>

            </span>
            </div>
        </li>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <div class="card">
            <div class="card-body">
                <h4 class="text-center"><?php echo e(__('Cart is Empty!! Add some products in your Cart')); ?></h4>
            </div>
        </div>
        <?php endif; ?>

    </ul>
    <div class="total-cart">
        <div class="title">Total: </div>
        <div class="price"><span
            class="cart-total"><?php echo e(Session::has('cart') ? App\Models\Product::convertPrice(Session::get('cart')->totalPrice) : '0.00'); ?>

        </span>
        </div>
    </div>

        <a href="<?php echo e(route('front.cart')); ?>" class="btn btn-primary rounded-0 view-cart"><?php echo e(__('View cart')); ?></a>

        <a href="<?php echo e(route('front.checkout')); ?>" class="btn btn-secondary rounded-0 checkout"><?php echo e(__('Check out')); ?></a>

</div>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/load/cart.blade.php ENDPATH**/ ?>