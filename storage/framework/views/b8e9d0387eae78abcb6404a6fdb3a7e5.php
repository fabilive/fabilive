
<?php if(Session::has('admin_cart')): ?>
    <?php
        $cart = Session::get('admin_cart');
    ?>

<div class="mr-table allproduct">

  <?php echo $__env->make('alerts.admin.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
  <div class="table-responsive">
     <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
        <thead>
           <tr>
           <tr>
              <th><?php echo e(__('Product ID#')); ?></th>
              <th><?php echo e(__('Product Title')); ?></th>
              <th><?php echo e(__('Price')); ?></th>
              <th><?php echo e(__('Details')); ?></th>
              <th><?php echo e(__('Subtotal')); ?></th>
              <th><?php echo e(__('Action')); ?></th>
           </tr>
           </tr>
        </thead>
        <tbody>
           <?php $__currentLoopData = $cart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1 => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          
           <tr>
              <td><input type="hidden" value="<?php echo e($key1); ?>"><?php echo e($product['item']['id']); ?></td>
              <td>
                <img src="<?php echo e(asset('assets/images/products/'.$product['item']['photo'])); ?>" alt="">
                <br>
                 <input type="hidden" value="<?php echo e($product['license']); ?>">
                <a target="_blank" href="<?php echo e(route('front.product', $product['item']['slug'])); ?>"><?php echo e(mb_strlen($product['item']['name'],'utf-8') > 30 ? mb_substr($product['item']['name'],0,30,'utf-8').'...' : $product['item']['name']); ?></a>
              </td>
              <td class="product-price">
                 <span><?php echo e(App\Models\Product::convertPrice($product['item_price'])); ?>

                 </span>
              </td>
              <td>
                 <?php if($product['size']): ?>
                 <p>
                    <strong><?php echo e(__('Size')); ?> :</strong> <?php echo e(str_replace('-',' ',$product['size'])); ?>

                 </p>
                 <?php endif; ?>
                 <?php if($product['color']): ?>
                 <p>
                    <strong><?php echo e(__('color')); ?> :</strong> <span
                       style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; border-radius: 50%; background: #<?php echo e($product['color']); ?>;"></span>
                 </p>
                 <?php endif; ?>
                 <p>
                    <strong><?php echo e(__('Qty')); ?> :</strong> <?php echo e($product['qty']); ?> <?php echo e($product['item']['measure']); ?>

                 </p>
                 <?php if(!empty($product['keys'])): ?>
                 <?php $__currentLoopData = array_combine(explode(',', $product['keys']), explode(',', $product['values'])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                 <p>
                    <b><?php echo e(ucwords(str_replace('_', ' ', $key))); ?> : </b> <?php echo e($value); ?> 
                 </p>
                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                 <?php endif; ?>
              </td>
              <td class="product-subtotal">
                 <p class="d-inline-block"
                    id="prc<?php echo e($product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values'])); ?>">
                    <?php echo e(App\Models\Product::convertPrice($product['price'])); ?>

                 </p>
                 <?php if($product['discount'] != 0): ?>
                 <strong><?php echo e($product['discount']); ?> %<?php echo e(__('off')); ?></strong>
                 <?php endif; ?>
              </td>
              <td>
                 <a href="javascript:;"  data-href="<?php echo e(route('admin.order.remove.cart',$product['item']['id'].$product['size'].$product['color'].str_replace(str_split(' ,'),'',$product['values']))); ?>" class="mybtn1 removeOrder"><i class="fa fa-trash"></i> <?php echo e(__('Remove')); ?></a>
              </td>
           </tr>
           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
     </table>
  </div>
  <div class="row">
   
    
    <div class="col-lg-12 text-right">
      <button type="submit" class="mybtn1">View & Continue</button>
    </div>

  </div>
</div>


<?php endif; ?>



<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/create/product_add_table.blade.php ENDPATH**/ ?>