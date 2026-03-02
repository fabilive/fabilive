<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="keywords" content="<?php echo e($seo->meta_keys); ?>">
        <meta name="author" content="GeniusOcean">

        <title><?php echo e($gs->title); ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/print/bootstrap/dist/css/bootstrap.min.css')); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/print/font-awesome/css/font-awesome.min.css')); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/print/Ionicons/css/ionicons.min.css')); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo e(asset('assets/print/css/style.css')); ?>">
  <link href="<?php echo e(asset('assets/print/css/print.css')); ?>" rel="stylesheet">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="icon" type="image/png" href="<?php echo e(asset('assets/images/'.$gs->favicon)); ?>">
  <style type="text/css">

#color-bar {
  display: inline-block;
  width: 20px;
  height: 20px;
  margin-left: 5px;
  margin-top: 5px;
}

@page { size: auto;  margin: 0mm; }
@page {
  size: A4;
  margin: 0;
}
@media print {
  html, body {
    width: 210mm;
    height: 287mm;
  }

html {

}
::-webkit-scrollbar {
    width: 0px;  /* remove scrollbar space */
    background: transparent;  /* optional: just make scrollbar invisible */
}
  </style>
</head>
<body onload="window.print();">
   <div class="container-fluid">
   <div class="row">
   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
      <!-- Starting of Dashboard data-table area -->
      <div class="section-padding add-product-1">
         <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
               <div class="product__header">
                  <div class="row reorder-xs">
                     <div class="col-lg-8 col-md-5 col-sm-5 col-xs-12">
                        <div class="product-header-title">
                           <h2><?php echo e(__('Order#')); ?> <?php echo e($order->order_number); ?> [<?php echo e($order->status); ?>]</h2>
                        </div>
                     </div>
                     <div class="row">
                        <div class="col-md-10">
                           <div class="dashboard-content">
                              <div class="view-order-page" id="print">
                                 <p class="order-date" style="margin-left: 2%"><?php echo e(__('Order Date')); ?> <?php echo e(date('d-M-Y',strtotime($order->created_at))); ?></p>
                                 <?php if($order->dp == 1): ?>
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
                                          <p><?php echo e(__('Tax:')); ?>  <?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->tax) / $order->currency_value),$order->currency_sign)); ?></p>
                                          <p><?php echo e(__('Paid Amount:')); ?> <?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->pay_amount  * $order->currency_value),$order->currency_sign)); ?></p>
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
                                 <div class="invoice__metaInfo">
                                    <div class="col-md-6">
                                       <h5><?php echo e(__('Billing Address')); ?></h5>
                                       <address>
                                          <?php echo e(__('Name:')); ?> <?php echo e($order->customer_name); ?><br>
                                          <?php echo e(__('Email:')); ?> <?php echo e($order->customer_email); ?><br>
                                          <?php echo e(__('Phone:')); ?> <?php echo e($order->customer_phone); ?><br>
                                          <?php echo e(__('Address:')); ?> <?php echo e($order->customer_address); ?><br>
                                          <?php echo e($order->customer_city); ?>-<?php echo e($order->customer_zip); ?>

                                       </address>
                                       <h5><?php echo e(__('Payment Information')); ?></h5>
                                       <p><?php echo e(__('Tax:')); ?>  <?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->tax) / $order->currency_value),$order->currency_sign)); ?></p>
                                       <p><?php echo e(__('Paid Amount:')); ?> <?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->pay_amount  * $order->currency_value),$order->currency_sign)); ?></p>
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
                                    <div class="col-md-6" style="width: 50%;">
                                       <?php if($order->shipping == "shipto"): ?>
                                       <h5><?php echo e(__('Shipping Address')); ?></h5>
                                       <address>
                                          <?php echo e(__('Name:')); ?> <?php echo e($order->shipping_name == null ? $order->customer_name : $order->shipping_name); ?><br>
                                          <?php echo e(__('Email:')); ?> <?php echo e($order->shipping_email == null ? $order->customer_email : $order->shipping_email); ?><br>
                                          <?php echo e(__('Phone:')); ?> <?php echo e($order->shipping_phone == null ? $order->customer_phone : $order->shipping_phone); ?><br>
                                          <?php echo e(__('Address:')); ?> <?php echo e($order->shipping_address == null ? $order->customer_address : $order->shipping_address); ?><br>
                                          <?php echo e($order->shipping_city == null ? $order->customer_city : $order->shipping_city); ?>-<?php echo e($order->shipping_zip == null ? $order->customer_zip : $order->shipping_zip); ?>

                                       </address>
                                       <?php else: ?>
                                       <h5><?php echo e(__('PickUp Location')); ?></h5>
                                       <address>
                                          <?php echo e(__('Address:')); ?> <?php echo e($order->pickup_location); ?><br>
                                       </address>
                                       <?php endif; ?>
                                       <h5><?php echo e(__('Shipping Method')); ?></h5>
                                       <?php if($order->shipping == "shipto"): ?>
                                       <p><?php echo e(__('Ship To Address')); ?></p>
                                       <?php else: ?>
                                       <p><?php echo e(__('Pick Up')); ?></p>
                                       <?php endif; ?>
                                    </div>
                                 </div>
                                 <?php endif; ?>
                                 <br>
                                 <br>
                                 <div class="table-responsive">
                                    <table id="example" class="table">
                                       <h4 class="text-center"><?php echo e(__('Ordered Products:')); ?></h4>
                                       <hr>
                                       <thead>
                                          <tr>
                                             <th width="10%"><?php echo e(__('ID#')); ?></th>
                                             <th><?php echo e(__('Name')); ?></th>
                                             <th width="20%"><?php echo e(__('Details')); ?></th>
                                             <th width="20%"><?php echo e(__('Price')); ?></th>
                                             <th width="10%"><?php echo e(__('Total')); ?></th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          <?php $__currentLoopData = $cart['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <tr>
                                             <td><?php echo e($product['item']['id']); ?></td>
                                             <td><?php echo e(mb_strlen($product['item']['name'],'UTF-8') > 50 ? mb_substr($product['item']['name'],0,50,'UTF-8').'...' : $product['item']['name']); ?></td>
                                             <td>
                                                <b><?php echo e(__('Quantity')); ?></b>: <?php echo e($product['qty']); ?> <br>
                                                <?php if(!empty($product['size'])): ?>
                                                <b><?php echo e(__('Size')); ?></b>: <?php echo e($product['item']['measure']); ?><?php echo e(str_replace('-',' ',$product['size'])); ?> <br>
                                                <?php endif; ?>
                                                <?php if(!empty($product['color'])): ?>
                                                <b><?php echo e(__('Color')); ?></b>:  <span id="color-bar" style="border-radius: 50%; vertical-align: bottom; border: 10px solid <?php echo e($product['color'] == "" ? "white" : '#'.$product['color']); ?>;"></span>
                                                <?php endif; ?>
                                                <?php if(!empty($product['keys'])): ?>
                                                <?php $__currentLoopData = array_combine(explode(',', $product['keys']), explode(',', $product['values'])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <b><?php echo e(ucwords(str_replace('_', ' ', $key))); ?> : </b> <?php echo e($value); ?> <br>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php endif; ?>
                                             </td>
                                             <td>
                                                <?php echo e(\PriceHelper::showCurrencyPrice(($product['item_price'] ) * $order->currency_value)); ?>

                                             </td>
                                             <td>
                                                <?php echo e(\PriceHelper::showCurrencyPrice(($product['item_price'] * $product['qty'] ) * $order->currency_value)); ?> <small><?php echo e($product['discount'] == 0 ? '' : '('.$product['discount'].'% '.__('Off').')'); ?></small>
                                             </td>
                                          </tr>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                       </tbody>
                                    </table>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <!-- Ending of Dashboard data-table area -->
   </div>
   <!-- ./wrapper -->
   <!-- ./wrapper -->
   <script type="text/javascript">
      (function($) {
      "use strict";
      
      setTimeout(function () {
          window.close();
        }, 500);
      
      })(jQuery);
      
   </script>
</body>
</html>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/order/print.blade.php ENDPATH**/ ?>