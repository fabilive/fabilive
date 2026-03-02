<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Purchased Items')); ?></h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Purchased Items')); ?></li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->

<!--==================== Blog Section Start ====================-->
<div class="full-row">
   <div class="container">
      <div class="mb-4 d-xl-none">
         <button class="dashboard-sidebar-btn btn bg-primary rounded">
            <i class="fas fa-bars"></i>
         </button>
      </div>
      <div class="row">
         <div class="col-xl-4">
            <?php echo $__env->make('partials.user.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         </div>
         <div class="col-xl-8">
            <div class="row">
               <div class="col-lg-12">
                  <div class="widget border-0 p-40 widget_categories bg-light account-info">
                     <div class="process-steps-area">
                        <?php echo $__env->make('partials.user.order-process', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                     </div>
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('Purchased Items')); ?></h4>
                     <div class="view-order-page">
                        <h3 class="order-code"><?php echo e(__('Order#')); ?> <?php echo e($order->order_number); ?> [<?php echo e($order->status); ?>]
                        </h3>
                        <div class="print-order text-right">
                           <a href="<?php echo e(route('user-order-print',$order->id)); ?>" target="_blank" class="print-order-btn">
                              <i class="fa fa-print"></i> <?php echo e(__('Print Order')); ?>

                           </a>
                        </div>
                        <p class="order-date"><?php echo e(__('Order Date')); ?> <?php echo e(date('d-M-Y',strtotime($order->created_at))); ?>

                        </p>
                        <?php if($order->dp == 1): ?>
                        <div class="billing-add-area">
                           <div class="row">
                              <div class="col-md-6">
                                 <h5><?php echo e(__('Shipping Address')); ?></h5>
                                 <address>
                                    <?php echo e(__('Name:')); ?> <?php echo e($order->customer_name); ?><br>
                                    <?php echo e(__('Email:')); ?> <?php echo e($order->customer_email); ?><br>
                                    <?php echo e(__('Phone:')); ?> <?php echo e($order->customer_phone); ?><br>
                                    <?php echo e(__('Address:')); ?> <?php echo e($order->customer_address); ?><br>
                                    <?php echo e($order->customer_city); ?>-<?php echo e($order->customer_zip); ?>

                                 </address>
                              </div>
                              <div class="col-md-6">
                                 <h5><?php echo e(__('Shipping Method')); ?></h5>
                                 <p><?php echo e(__('Payment Status:')); ?>

                                    <?php if($order->payment_status == 'Pending'): ?>
                                    <strong><?php echo e(__('Unpaid')); ?></strong>
                                    <?php else: ?>
                                    <strong><?php echo e(__('Paid')); ?></strong>
                                    <?php endif; ?>
                                 </p>
                                 <p><?php echo e(__('Tax :')); ?>

                                    <?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->tax) /
                                    $order->currency_value),$order->currency_sign)); ?>

                                 </p>
                                 <p><?php echo e(__('Paid Amount:')); ?>

                                    <?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->pay_amount *
                                    $order->currency_value),$order->currency_sign)); ?>

                                 </p>
                                 <p><?php echo e(__('Payment Method:')); ?> <?php echo e($order->method); ?></p>
                                 <?php if($order->method != "Cash On Delivery"): ?>
                                 <?php if($order->method=="Stripe"): ?>
                                 <?php echo e($order->method); ?> <?php echo e(__('Charge ID:')); ?>

                                 <p><?php echo e($order->charge_id); ?></p>
                                 <?php endif; ?>
                                 <?php echo e($order->method); ?> <?php echo e(__('Transaction ID:')); ?>

                                 <p id="ttn"><?php echo e($order->txnid); ?></p>
                                 <?php endif; ?>
                              </div>
                           </div>
                        </div>
                        <?php else: ?>
                        <div class="shipping-add-area">
                           <div class="row">
                              <div class="col-md-6">
                                 <?php if($order->shipping == "shipto"): ?>
                                 <h5><?php echo e(__('Shipping Address')); ?></h5>
                                 <address>
                                    <?php echo e(__('Name:')); ?>

                                    <?php echo e($order->shipping_name == null ? $order->customer_name :
                                    $order->shipping_name); ?><br>
                                    <?php echo e(__('Email:')); ?>

                                    <?php echo e($order->shipping_email == null ? $order->customer_email :
                                    $order->shipping_email); ?><br>
                                    <?php echo e(__('Phone:')); ?>

                                    <?php echo e($order->shipping_phone == null ? $order->customer_phone :
                                    $order->shipping_phone); ?><br>
                                    <?php echo e(__('Address:')); ?>

                                    <?php echo e($order->shipping_address == null ? $order->customer_address :
                                    $order->shipping_address); ?><br>
                                    <?php echo e($order->shipping_city == null ? $order->customer_city :
                                    $order->shipping_city); ?>-<?php echo e($order->shipping_zip == null ? $order->customer_zip :
                                    $order->shipping_zip); ?>

                                 </address>
                                 <?php else: ?>
                                 <h5><?php echo e(__('PickUp Location')); ?></h5>
                                 <address>
                                    <?php echo e(__('Address:')); ?> <?php echo e($order->pickup_location); ?><br>
                                 </address>
                                 <?php endif; ?>
                              </div>
                              <div class="col-md-6">
                                 <h5><?php echo e(__('Shipping Method')); ?></h5>
                                 <?php if($order->shipping == "shipto"): ?>
                                 <p><?php echo e(__('Ship To Address')); ?></p>
                                 <?php else: ?>
                                 <p><?php echo e(__('Pick Up')); ?></p>
                                 <?php endif; ?>
                              </div>
                           </div>
                        </div>
                        <div class="billing-add-area">
                           <div class="row">
                              <div class="col-md-6">
                                 <h5><?php echo e(__('Billing Address')); ?></h5>
                                 <address>
                                    <?php echo e(__('Name:')); ?> <?php echo e($order->customer_name); ?><br>
                                    <?php echo e(__('Email:')); ?> <?php echo e($order->customer_email); ?><br>
                                    <?php echo e(__('Phone:')); ?> <?php echo e($order->customer_phone); ?><br>
                                    <?php echo e(__('Address:')); ?> <?php echo e($order->customer_address); ?><br>
                                    <?php echo e($order->customer_city); ?>-<?php echo e($order->customer_zip); ?>

                                 </address>
                              </div>
                              <div class="col-md-6">
                                 <h5><?php echo e(__('Payment Information')); ?></h5>
                                 <p><?php echo e(__('Payment Status :')); ?>

                                    <?php if($order->payment_status == 'Pending'): ?>
                                    <strong class="text-danger"><?php echo e(__('Unpaid')); ?></strong>
                                    <?php else: ?>
                                    <strong class="text-success"><?php echo e(__('Paid')); ?></strong>
                                    <?php endif; ?>
                                 </p>
                                 <p><?php echo e(__('Tax :')); ?>

                                    <?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->tax) /
                                    $order->currency_value),$order->currency_sign)); ?>

                                 </p>
                                 <p><?php echo e(__('Paid Amount:')); ?>

                                    <?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->pay_amount *
                                    $order->currency_value),$order->currency_sign)); ?>

                                 </p>
                                 <p><?php echo e(__('Payment Method:')); ?> <?php echo e($order->method); ?></p>
                                 <?php if($order->method != "Cash On Delivery"): ?>
                                 <?php if($order->method=="Stripe"): ?>
                                 <?php echo e($order->method); ?> <?php echo e(__('Charge ID:')); ?>

                                 <p><?php echo e($order->charge_id); ?></p>
                                 <?php endif; ?>
                                 <?php echo e($order->method); ?> <?php echo e(__('Transaction ID:')); ?>

                                 <p id="ttn"> <?php echo e($order->txnid); ?></p>

                                 <?php endif; ?>
                              </div>
                           </div>
                        </div>
                        <?php endif; ?>
                        <br>
                        <div class="table-responsive">
                           <h5><?php echo e(__('Ordered Products:')); ?></h5>
                           <table class="table veiw-details-table">
                              <thead>
                                 <tr>
                                    <th><?php echo e(__('ID#')); ?></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                    <th><?php echo e(__('Details')); ?></th>
                                    <th><?php echo e(__('Price')); ?></th>
                                    <th><?php echo e(__('Total')); ?></th>
                                 </tr>
                              </thead>
                              <tbody>
                                 <?php $__currentLoopData = $cart['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                 <tr>
                                    <td data-label="<?php echo e(__('ID#')); ?>">
                                       <div>
                                          <?php echo e($product['item']['id']); ?>

                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Name')); ?>">
                                       <div>
                                          <input type="hidden" value="<?php echo e($product['license']); ?>">
                                          <?php if($product['item']['user_id'] != 0): ?>
                                          <?php
                                          $user = App\Models\User::find($product['item']['user_id']);
                                          ?>
                                          <?php if(isset($user)): ?>
                                          <a target="_blank"
                                             href="<?php echo e(route('front.product', $product['item']['slug'])); ?>"><?php echo e(mb_strlen($product['item']['name'],'UTF-8')
                                             > 50 ? mb_substr($product['item']['name'],0,50,'UTF-8').'...' :
                                             $product['item']['name']); ?></a>
                                          <?php else: ?>
                                          <a target="_blank"
                                             href="<?php echo e(route('front.product', $product['item']['slug'])); ?>">
                                             <?php echo e(mb_strlen($product['item']['name'],'UTF-8') > 50 ?
                                             mb_substr($product['item']['name'],0,50,'UTF-8').'...' :
                                             $product['item']['name']); ?>

                                          </a>
                                          <?php endif; ?>
                                          <?php else: ?>
                                          <a target="_blank"
                                             href="<?php echo e(route('front.product', $product['item']['slug'])); ?>">
                                             <?php echo e(mb_strlen($product['item']['name'],'UTF-8') > 50 ?
                                             mb_substr($product['item']['name'],0,50,'UTF-8').'...' :
                                             $product['item']['name']); ?>

                                          </a>
                                          <?php endif; ?>
                                          <?php if($product['item']['type'] != 'Physical'): ?>
                                          <?php if($order->payment_status == 'Completed'): ?>
                                          <?php if($product['item']['file'] != null): ?>
                                          <a href="<?php echo e(route('user-order-download',['slug' => $order->order_number , 'id' => $product['item']['id']])); ?>"
                                             class="btn btn-sm btn-primary">
                                             <i class="fa fa-download"></i> <?php echo e(__('Download')); ?>

                                          </a>
                                          <?php else: ?>
                                          <a target="_blank" href="<?php echo e($product['item']['link']); ?>"
                                             class="btn btn-sm btn-primary">
                                             <i class="fa fa-download"></i> <?php echo e(__('Download')); ?>

                                          </a>
                                          <?php endif; ?>
                                          <?php if($product['license'] != ''): ?>
                                          <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete"
                                             class="btn btn-sm btn-info product-btn" id="license"><i
                                                class="fa fa-eye"></i> <?php echo e(__('View License')); ?></a>
                                          <?php endif; ?>
                                          <?php endif; ?>
                                          <?php endif; ?>
                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Details')); ?>">
                                       <div>
                                          <b><?php echo e(__('Quantity')); ?></b>: <?php echo e($product['qty']); ?> <br>
                                          <?php if(!empty($product['size'])): ?>
                                          <b><?php echo e(__('Size')); ?></b>: <?php echo e($product['item']['measure']); ?><?php echo e(str_replace('-','
                                          ',$product['size'])); ?> <br>
                                          <?php endif; ?>
                                          <?php if(!empty($product['color'])): ?>
                                          <div class="d-flex mt-2">
                                             <b><?php echo e(__('Color')); ?></b>: <span id="color-bar"
                                                style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; border-radius: 50%; background: #<?php echo e($product['color']); ?>;"></span>
                                          </div>
                                          <?php endif; ?>
                                          <?php if(!empty($product['keys'])): ?>
                                          <?php $__currentLoopData = array_combine(explode(',', $product['keys']), explode(',',
                                          $product['values'])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <b><?php echo e(ucwords(str_replace('_', ' ', $key))); ?> : </b> <?php echo e($value); ?> <br>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                          <?php endif; ?>
                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Price')); ?>">
                                       <div>
                                          <?php echo e(\PriceHelper::showCurrencyPrice(($product['item_price'] ) *
                                          $order->currency_value)); ?>

                                       </div>
                                    </td>
                                    <td data-label="<?php echo e(__('Total')); ?>">
                                       <div>
                                          <?php echo e(\PriceHelper::showCurrencyPrice(($product['item_price'] * $product['qty'] )
                                          * $order->currency_value)); ?> <small><?php echo e($product['discount'] == 0 ? '' :
                                             '('.$product['discount'].'% '.__('Off').')'); ?></small></small>
                                       </div>
                                    </td>
                                 </tr>
                                 <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </tbody>
                           </table>
                        </div>
                     </div>
                     <a class="back-btn theme-bg" href="<?php echo e(route('user-orders')); ?>"> <?php echo e(__('Back')); ?></a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Blog Section End ====================-->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header d-block text-center">
            <h4 class="modal-title d-inline-block"><?php echo e(__('License Key')); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p class="text-center"><?php echo e(__('The Licenes Key is :')); ?> <span id="key"></span></p>
         </div>
         <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
         </div>
      </div>
   </div>
</div>

<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script type="text/javascript">
   (function($) {
            "use strict";

        $('#example').dataTable({
            "ordering": false,
            'paging': false,
            'lengthChange': false,
            'searching': false,
            'ordering': false,
            'info': false,
            'autoWidth': false,
            'responsive': true
        });

    })(jQuery);

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/order/details.blade.php ENDPATH**/ ?>