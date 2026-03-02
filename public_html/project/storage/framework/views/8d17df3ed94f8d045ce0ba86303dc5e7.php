
     
<?php $__env->startSection('styles'); ?>

<style type="text/css">
    .order-table-wrap table#example2 {
    margin: 10px 20px;
}

</style>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
 

<div class="content-area">
    <div class="mr-breadcrumb">
       <div class="row">
          <div class="col-lg-12">
             <h4 class="heading"><?php echo e(__('Order Details')); ?> <a class="add-btn" href="javascript:history.back();"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
             <ul class="links">
                <li>
                   <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                </li>
                <li>
                   <a href="javascript:;"><?php echo e(__('Orders')); ?></a>
                </li>
                <li>
                   <a href="javascript:;"><?php echo e(__('Order Details')); ?></a>
                </li>
             </ul>
          </div>
       </div>
    </div>
    <div class="order-table-wrap">
       <div class="row">
        
          <div class="col-lg-12 order-details-table">
            <div class="mr-table">
                <h4 class="title">
                    <?php echo e(__('Products')); ?>

                </h4>
                <div class="table-responsive">
                    <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                        <thead>
                           <tr>
                           <tr>
                              <th><?php echo e(__('Product ID#')); ?></th>
                              <th><?php echo e(__('Product Title')); ?></th>
                              <th><?php echo e(__('Price')); ?></th>
                              <th><?php echo e(__('Details')); ?></th>
                              <th><?php echo e(__('Subtotal')); ?></th>
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
                           </tr>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                     </table>
                </div>
            </div>
        </div>
          <div class="col-lg-8 my-5">
             <div class="special-box">
                <div class="heading-area">
                   <h4 class="title">
                      <?php echo e(__('Customer Details')); ?> 
                   </h4>
                </div>
                <div class="table-responsive-sm">
                   <table class="table">
                      <tbody>
                         <tr>
                            <th width="45%"><?php echo e(__('Name')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e($address['customer_name']); ?></td>
                         </tr>
                         <tr>
                            <th width="45%"><?php echo e(__('Email')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e($address['customer_email']); ?></td>
                         </tr>
                         <tr>
                            <th width="45%"><?php echo e(__('Phone')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e($address['customer_phone']); ?></td>
                         </tr>
                         <tr>
                            <th width="45%"><?php echo e(__('Address')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e($address['customer_address']); ?></td>
                         </tr>
                         <tr>
                            <th width="45%"><?php echo e(__('Country')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e($address['customer_country'] ? $address['customer_country'] : '--'); ?></td>
                         </tr>
                         <?php if(@$address['customer_city'] != null): ?>
                         <tr>
                            <th width="45%"><?php echo e(__('State')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e($address['customer_state'] ? $address['customer_state'] : '--'); ?></td>
                         </tr>
                         <?php endif; ?>
                         <tr>
                            <th width="45%"><?php echo e(__('City')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e($address['customer_city'] ? $address['customer_city'] : '--'); ?></td>
                         </tr>
                         <tr>
                            <th width="45%"><?php echo e(__('Postal Code')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e($address['customer_zip'] ? $address['customer_zip'] : '--'); ?></td>
                         </tr>
                      </tbody>
                   </table>
                </div>
             </div>
          </div>
          <div class="col-lg-4 my-5 ">
             <div class="special-box">
                <div class="heading-area">
                   <h4 class="title">
                      <?php echo e(__('Order Details')); ?> 
                   </h4>
                </div>
            
                <div class="table-responsive-sm">
                   <table class="table">
                      <tbody>
                         <tr>
                            <th width="45%"><?php echo e(__('Total Products')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e(count($cart->items)); ?></td>
                         </tr>
                         <tr>
                            <th width="45%"><?php echo e(__('Total Quintity')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e($cart->totalQty); ?></td>
                         </tr>
                         <tr>
                            <th width="45%"><?php echo e(__('Total Amount')); ?></th>
                            <th width="10%">:</th>
                            <td width="45%"><?php echo e(App\Models\Product::convertPrice($cart->totalPrice)); ?></td>
                         </tr>
                         <tr>
                            <td>
                                <a href="<?php echo e(route('admin-order-create-submit')); ?>" class="mybtn1">Order Submit</a>
                            </td>
                         </tr>
                      </tbody>
                   </table>
                </div>
             </div>
          </div>
       </div>
    </div>
 </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/create/view.blade.php ENDPATH**/ ?>