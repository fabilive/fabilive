<!--==================== Cart Section Start ====================-->
<div class="full-row cartpage">
    <div class="container">
       <div class="row">
          <?php if(Session::has('cart')): ?>
          <div class="col-xl-8 col-lg-12 col-md-12 col-12">
             <div class="cart-table">
                <div class="gocover" style="position: absolute; background: url(<?php echo e(asset('assets/images/xloading.gif')); ?>) center center no-repeat scroll rgba(255, 255, 255, 0.5); display: none;"></div>
                <table class="shop_table cart">
                   <tr>
                      <th class="product-thumbnail">&nbsp;</th>
                      <th class="product-name"><?php echo e(__('Product')); ?></th>
                      <th class="product-price"><?php echo e(__('Price')); ?></th>
                      <th class="product-quantity"><?php echo e(__('Quantity')); ?></th>
                      <th class="product-subtotal"><?php echo e(__('Subtotal')); ?></th>
                      <th class="product-remove">&nbsp;</th>
                   </tr>
                   <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                   <tr class="woocommerce-cart-form__cart-item cart_item">
                      <td class="product-thumbnail">
                         <a href="<?php echo e(route('front.product', $product['item']['slug'])); ?>"><img src="<?php echo e($product['item']['photo'] ? asset('assets/images/products/'.$product['item']['photo']) : asset('assets/images/noimage.png')); ?>" alt="Product image"></a>
                      </td>
                      <td class="product-name">
                         <a href="<?php echo e(route('front.product', $product['item']['slug'])); ?>"><?php echo e(mb_strlen($product['item']['name'],'UTF-8') > 35 ? mb_substr($product['item']['name'],0,35,'UTF-8').'...' : $product['item']['name']); ?></a>
                         <?php if(!empty($product['color'])): ?>
                         <div class="d-flex mt-2 ml-1">
                            <b><?php echo e(__('Colour')); ?></b>:  <span id="color-bar" style="border: 10px solid #<?php echo e($product['color'] == "" ? "white" : $product['color']); ?>;"></span>
                         </div>
                         <?php endif; ?>
                      </td>
                      <td class="product-price">
                         <span><?php echo e(App\Models\Product::convertPrice($product['item_price'])); ?>

                         </span>
                      </td>
                      <?php if($product['item']['type'] == 'Physical' && $product['item']['type'] != 'affiliate'): ?>
                      <td class="product-quantity">
                         <input type="hidden" class="prodid" value="<?php echo e($product['item']['id']); ?>">
                         <input type="hidden" class="itemid"
                            value="<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>">
                         <input type="hidden" class="size_qty" value="<?php echo e($product['size_qty']); ?>">
                         <input type="hidden" class="size_price" value="<?php echo e($product['size_price']); ?>">
                         <input type="hidden" class="minimum_qty" value="<?php echo e($product['item']['minimum_qty'] == null ? '0' : $product['item']['minimum_qty']); ?>">
                         <div class="quantity">
                            <input id="qty<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>" class="qttotal1" type="number" name="quantity"  value="<?php echo e($product['qty']); ?>">
                         </div>
                      </td>
                      <?php else: ?>
                      <td class="product-quantity">
                         1
                      </td>
                      <?php endif; ?>
                      <?php if($product['size_qty']): ?>
                      <input type="hidden"
                         id="stock<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>"
                         value="<?php echo e($product['size_qty']); ?>">
                      <?php elseif($product['item']['type'] != 'Physical'): ?>
                      <input type="hidden"
                         id="stock<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>"
                         value="1">
                      <?php else: ?>
                      <input type="hidden"
                         id="stock<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>"
                         value="<?php echo e($product['stock']); ?>">
                      <?php endif; ?>
                      <td class="product-subtotal">
                         <p class="d-inline-block"
                            id="prc<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>">
                            <?php echo e(App\Models\Product::convertPrice($product['price'])); ?>

                         </p>
                         <?php if($product['discount'] != 0): ?>
                         <strong><?php echo e($product['discount']); ?> %<?php echo e(__('off')); ?></strong>
                         <?php endif; ?>
                      </td>
                      <td class="product-remove">
                         <a href="#" class="remove cart-remove" data-class="cremove<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>" data-href="<?php echo e(route('product.cart.remove',$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values']))); ?>">×</a>
                      </td>
                   </tr>
                   <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </table>
             </div>
          </div>
          <div class="col-xl-4 col-lg-12 col-md-12 col-12">
             <div class="cart-collaterals">
                <div class="cart_totals ">
                   <h2><?php echo e(__('Cart totals')); ?></h2>
                   <table>
                      <tr>
                         <th>Subtotal</th>
                         <td>
                            <span><b
                               class="cart-total"><?php echo e(Session::has('cart') ? App\Models\Product::convertPrice($totalPrice) : '0.00'); ?></b>
                            </span>
                         </td>
                      </tr>
                      <tr>
                         <th><?php echo e(__('Discount')); ?></th>
                         <td>
                            <span>
                            <b class="discount"><?php echo e(App\Models\Product::convertPrice(0)); ?></b>
                            <input type="hidden" id="d-val" value="<?php echo e(App\Models\Product::convertPrice(0)); ?>">
                            </span>
                         </td>
                      </tr>
                      <tr class="order-total">
                         <th>Total</th>
                         <td><strong><span class="woocommerce-Price-amount amount main-total"><?php echo e(Session::has('cart') ? App\Models\Product::convertPrice($mainTotal) : '0.00'); ?></span></strong> </td>
                      </tr>
                   </table>
                   <div class="wc-proceed-to-checkout">
                      <a href="<?php echo e(route('front.checkout')); ?>" class="checkout-button"><?php echo e(__('Proceed to checkout')); ?></a>
                   </div>
                </div>
             </div>
          </div>
          <?php else: ?>
          <div class="col-xl-12 col-lg-12 col-md-12 col-12">
             <div class="card border py-4">
                <div class="card-body">
                   <h4 class="text-center"><?php echo e(__('Cart is Empty!! Add some products in your Cart')); ?></h4>
                </div>
             </div>
          </div>
          <?php endif; ?>
       </div>
    </div>
 </div>
 <script src="<?php echo e(asset('assets/front/js/custom.js')); ?>"></script>

<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/frontend/ajax/cart-page.blade.php ENDPATH**/ ?>