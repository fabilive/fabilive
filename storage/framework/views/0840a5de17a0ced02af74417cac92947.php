<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
    style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Success')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>

                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Success')); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->
<section class="tempcart">

    <?php if(!empty($tempcart)): ?>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <!-- Starting of Dashboard data-table area -->
                <div class="content-box section-padding add-product-1">
                    <div class="top-area">
                        <div class="content order-de">
                            
                            <p class="text">
                                <?php echo e(__("We'll email you an order confirmation with details and tracking info.")); ?>

                            </p>
                            <a href="<?php echo e(route('front.index')); ?>" style="color:green;font-weight: bold" class="link"><?php echo e(__('Get Back To Our Homepage')); ?></a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">

                            <div class="product__header">
                                <div class="row reorder-xs">
                                    <div class="col-lg-12">
                                        <div class="product-header-title">
                                            <h4><?php echo e(__('Order#')); ?> <?php echo e($order->order_number); ?></h4>
                                        </div>
                                    </div>
                                    <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <div class="col-md-12" id="tempview">
                                        <div class="dashboard-content">
                                            <div class="view-order-page" id="print">
                                                <p class="order-date"><?php echo e(__('Order Date')); ?>

                                                    <?php echo e(date('d-M-Y',strtotime($order->created_at))); ?></p>


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

                                                            <p><?php echo e(__('Payment Status')); ?>

                                                                <?php if($order->payment_status == 'Pending'): ?>
                                                                <span class='badge badge-danger'><?php echo e(__('Unpaid')); ?></span>
                                                                <?php else: ?>
                                                                <span class='badge badge-success'><?php echo e(__('Paid')); ?></span>
                                                                <?php endif; ?>
                                                            </p>

                                                            <p><?php echo e(__('Tax :')); ?>

                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->tax) /
                                                                $order->currency_value),$order->currency_sign)); ?>

                                                            </p>

                                                            <p><?php echo e(__('Paid Amount:')); ?>

                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->pay_amount
                                                                + $order->wallet_price) *
                                                                $order->currency_value),$order->currency_sign)); ?>

                                                            </p>
                                                            <p><?php echo e(__('Payment Method:')); ?> <?php echo e($order->method); ?></p>

                                                            <?php if($order->method != "Cash On Delivery"): ?>
                                                            <?php if($order->method=="Stripe"): ?>
                                                            <?php echo e($order->method); ?> <?php echo e(__('Charge ID:')); ?> <p>
                                                                <?php echo e($order->charge_id); ?></p>
                                                            <?php endif; ?>
                                                            <?php echo e($order->method); ?> <?php echo e(__('Transaction ID:')); ?> <p
                                                                id="ttn"><?php echo e($order->txnid); ?></p>

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

                                                                <?php echo e($order->shipping_name == null ? $order->customer_name
                                                                : $order->shipping_name); ?><br>
                                                                <?php echo e(__('Email:')); ?>

                                                                <?php echo e($order->shipping_email == null ?
                                                                $order->customer_email : $order->shipping_email); ?><br>
                                                                <?php echo e(__('Phone:')); ?>

                                                                <?php echo e($order->shipping_phone == null ?
                                                                $order->customer_phone : $order->shipping_phone); ?><br>
                                                                <?php echo e(__('Address:')); ?>

                                                                <?php echo e($order->shipping_address == null ?
                                                                $order->customer_address :
                                                                $order->shipping_address); ?><br>
                                                                <?php echo e($order->shipping_city == null ? $order->customer_city
                                                                : $order->shipping_city); ?>-<?php echo e($order->shipping_zip == null
                                                                ? $order->customer_zip : $order->shipping_zip); ?>

                                                            </address>
                                                            <?php else: ?>
                                                            <h5><?php echo e(__('PickUp Location')); ?></h5>
                                                            <address>
                                                                <?php echo e(__('PickUp Location')); ?>: <?php echo e(optional($order->servicearea)->location); ?>

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

                                                            <?php if($gs->multiple_shipping == 0): ?>
                                                            <?php if($order->shipping_cost != 0): ?>
                                                            <p><?php echo e($order->shipping_title); ?>:
                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice($order->shipping_cost,$order->currency_sign)); ?>

                                                            </p>
                                                            <?php endif; ?>


                                                            <?php if($order->packing_cost != 0): ?>
                                                            <p><?php echo e($order->packing_title); ?>:
                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice($order->packing_cost,$order->currency_sign)); ?>

                                                            </p>
                                                            <?php endif; ?>

                                                            <?php else: ?>

                                                            <?php if($order->shipping_cost != 0): ?>
                                                            <p><?php echo e(__('Shipping Cost')); ?>:
                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice($order->shipping_cost* $order->currency_value,$order->currency_sign)); ?>

                                                            </p>
                                                            <?php endif; ?>


                                                            <?php if($order->packing_cost != 0): ?>
                                                            <p><?php echo e(__('Packing Cost')); ?>:
                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice($order->packing_cost* $order->currency_value,$order->currency_sign)); ?>

                                                            </p>
                                                            <?php endif; ?>

                                                            <?php endif; ?>

                                                            <?php if($order->wallet_price != 0): ?>
                                                            <p><?php echo e(__('Paid From Wallet')); ?>:
                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->wallet_price
                                                                * $order->currency_value),$order->currency_sign)); ?>

                                                            </p>

                                                            <?php if($order->method != "Wallet"): ?>

                                                            <p><?php echo e($order->method); ?>:
                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->pay_amount
                                                                * $order->currency_value),$order->currency_sign)); ?>

                                                            </p>

                                                            <?php endif; ?>

                                                            <?php endif; ?>

                                                            <p><?php echo e(__('Tax :')); ?>

                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->tax) /
                                                                $order->currency_value),$order->currency_sign)); ?>

                                                            </p>

                                                            <p><?php echo e(__('Paid Amount:')); ?>

                                                                <?php if($order->method != "Wallet"): ?>

                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->pay_amount+$order->wallet_price)
                                                                * $order->currency_value),$order->currency_sign)); ?>


                                                                <?php else: ?>
                                                                <?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->wallet_price
                                                                * $order->currency_value),$order->currency_sign)); ?>

                                                                <?php endif; ?>



                                                            </p>
                                                            <p><?php echo e(__('Payment Method:')); ?> <?php echo e($order->method); ?></p>

                                                            <?php if($order->method != "Cash On Delivery" && $order->method
                                                            != "Wallet"): ?>
                                                            <?php if($order->method=="Stripe"): ?>
                                                            <?php echo e($order->method); ?> <?php echo e(__('Charge ID:')); ?> <p>
                                                                <?php echo e($order->charge_id); ?></p>
                                                            <?php else: ?>
                                                            <?php echo e($order->method); ?> <?php echo e(__('Transaction ID:')); ?> <p id="ttn">
                                                                <?php echo e($order->txnid); ?></p>
                                                            <?php endif; ?>

                                                            <?php endif; ?>

                                                        </div>
                                                    </div>
                                                </div>
                                                <?php endif; ?>
                                                <br>
                                                <div class="table-responsive">
                                                    <table class="table">
                                                        <h4 class="text-center"><?php echo e(__('Ordered Products:')); ?></h4>
                                                        <thead>
                                                            <tr>
                                                                <th width="35%"><?php echo e(__('Name')); ?></th>
                                                                <th width="20%"><?php echo e(__('Details')); ?></th>
                                                                <th><?php echo e(__('Price')); ?></th>
                                                                <th><?php echo e(__('Total')); ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>

                                                            <?php $__currentLoopData = $tempcart->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>

                                                                <td><?php echo e($product['item']['name']); ?></td>
                                                                <td>
                                                                    <b><?php echo e(__('Quantity')); ?></b>: <?php echo e($product['qty']); ?>

                                                                    <br>
                                                                    <?php if(!empty($product['size'])): ?>
                                                                    <b><?php echo e(__('Size')); ?></b>:
                                                                    <?php echo e($product['item']['measure']); ?><?php echo e(str_replace('-','
                                                                    ',$product['size'])); ?>

                                                                    <br>
                                                                    <?php endif; ?>
                                                                    <?php if(!empty($product['color'])): ?>
                                                                    <div class="d-flex mt-2">
                                                                        <b><?php echo e(__('Color')); ?></b>: <span id="color-bar"
                                                                            style="border: 10px solid #<?php echo e($product['color'] == "" ? "
                                                                            white" : $product['color']); ?>;"></span>
                                                                    </div>
                                                                    <?php endif; ?>

                                                                    <?php if(!empty($product['keys'])): ?>

                                                                    <?php $__currentLoopData = array_combine(explode(',',
                                                                    $product['keys']), explode(',', $product['values'])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                                                                    <b><?php echo e(ucwords(str_replace('_', ' ', $key))); ?> :
                                                                    </b> <?php echo e($value); ?> <br>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                    <?php endif; ?>

                                                                </td>

                                                                <td><?php echo e(\PriceHelper::showCurrencyPrice(($product['item_price']
                                                                    ) * $order->currency_value)); ?>

                                                                </td>

                                                                <td><?php echo e(\PriceHelper::showCurrencyPrice($product['price']
                                                                    * $order->currency_value)); ?> <small><?php echo e($product['discount'] == 0 ? '' :
                                                                        '('.$product['discount'].'% '.__('Off').')'); ?></small>
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
                <!-- Ending of Dashboard data-table area -->
            </div>

            <?php endif; ?>

</section>





<?php echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/frontend/success.blade.php ENDPATH**/ ?>