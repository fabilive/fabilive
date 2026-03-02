

<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('Order Invoice')); ?> <a class="add-btn" href="javascript:history.back();"><i
                            class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                <ul class="links">
                    <li>
                        <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                    </li>
                    <li>
                        <a href="javascript:;"><?php echo e(__('Orders')); ?></a>
                    </li>
                    <li>
                        <a href="javascript:;"><?php echo e(__('Invoice')); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="order-table-wrap">
        <div class="invoice-wrap">
            <div class="invoice__title">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="invoice__logo text-left">
                           <img src="<?php echo e(asset('assets/images/'.$gs->invoice_logo)); ?>" alt="woo commerce logo">
                        </div>
                    </div>
                    <div class="col-lg-6 text-right">
                        <a class="btn  add-newProduct-btn print" href="<?php echo e(route('admin-order-print',$order->id)); ?>"
                        target="_blank"><i class="fa fa-print"></i> <?php echo e(__('Print Invoice')); ?></a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row invoice__metaInfo mb-4">
                <div class="col-lg-6">
                    <div class="invoice__orderDetails">
                        
                        <p><strong><?php echo e(__('Order Details')); ?> </strong></p>
                        <span><strong><?php echo e(__('Invoice Number')); ?> :</strong> <?php echo e(sprintf("%'.08d", $order->id)); ?></span><br>
                        <span><strong><?php echo e(__('Order Date')); ?> :</strong> <?php echo e(date('d-M-Y',strtotime($order->created_at))); ?></span><br>
                        <span><strong><?php echo e(__('Order ID')); ?> :</strong> <?php echo e($order->order_number); ?></span><br>
                        <?php if($order->dp == 0): ?>
                        <span> <strong><?php echo e(__('Shipping Method')); ?> :</strong>
                            <?php if($order->shipping == "pickup"): ?>
                            <?php echo e(__('Pick Up')); ?>

                            <?php else: ?>
                            <?php echo e(__('Ship To Address')); ?>

                            <?php endif; ?>
                        </span><br>
                        <?php endif; ?>
                        <span> <strong><?php echo e(__('Payment Method')); ?> :</strong> <?php echo e($order->method); ?></span>
                    </div>
                </div>
            </div>
            <div class="row invoice__metaInfo">
           <?php if($order->dp == 0): ?>
                <div class="col-lg-6">
                        <div class="invoice__shipping">
                            <p><strong><?php echo e(__('Shipping Address')); ?></strong></p>
                           <span><strong><?php echo e(__('Customer Name')); ?></strong>: <?php echo e($order->shipping_name == null ? $order->customer_name : $order->shipping_name); ?></span><br>
                           <span><strong><?php echo e(__('Address')); ?></strong>: <?php echo e($order->shipping_address == null ? $order->customer_address : $order->shipping_address); ?></span><br>
                           <span><strong><?php echo e(__('City')); ?></strong>: <?php echo e($order->shipping_city == null ? $order->customer_city : $order->shipping_city); ?></span><br>
                           <span><strong><?php echo e(__('Country')); ?></strong>: <?php echo e($order->shipping_country == null ? $order->customer_country : $order->shipping_country); ?></span>

                        </div>
                </div>

            <?php endif; ?>

                <div class="col-lg-6">
                        <div class="buyer">
                            <p><strong><?php echo e(__('Billing Details')); ?></strong></p>
                            <span><strong><?php echo e(__('Customer Name')); ?></strong>: <?php echo e($order->customer_name); ?></span><br>
                            <span><strong><?php echo e(__('Address')); ?></strong>: <?php echo e($order->customer_address); ?></span><br>
                            <span><strong><?php echo e(__('City')); ?></strong>: <?php echo e($order->customer_city); ?></span><br>
                            <span><strong><?php echo e(__('Country')); ?></strong>: <?php echo e($order->customer_country); ?></span>
                        </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="invoice_table">
                        <div class="mr-table">
                            <div class="table-responsive">
                                <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                    width="100%" >
                                    <thead>
                                        <tr>
                                            <th><?php echo e(__('Product')); ?></th>
                                            <th><?php echo e(__('Details')); ?></th>
                                            <th><?php echo e(__('Total')); ?></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $subtotal = 0;
                                        $tax = 0;
                                        ?>
                                        <?php $__currentLoopData = $cart['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td width="50%">
                                                <?php if($product['item']['user_id'] != 0): ?>
                                                <?php
                                                $user = App\Models\User::find($product['item']['user_id']);
                                                ?>
                                                <?php if(isset($user)): ?>
                                                <a target="_blank"
                                                    href="<?php echo e(route('front.product', $product['item']['slug'])); ?>"><?php echo e($product['item']['name']); ?></a>
                                                <?php else: ?>
                                                <a href="javascript:;"><?php echo e($product['item']['name']); ?></a>
                                                <?php endif; ?>

                                                <?php else: ?>
                                                <a href="javascript:;"><?php echo e($product['item']['name']); ?></a>

                                                <?php endif; ?>
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
                                                        <strong><?php echo e(__('Price')); ?> :</strong><?php echo e(\PriceHelper::showCurrencyPrice(($product['item_price'] ) * $order->currency_value)); ?>

                                                </p>
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


                                            <td><?php echo e(\PriceHelper::showCurrencyPrice($product['price'] * $order->currency_value)); ?> <small><?php echo e($product['discount'] == 0 ? '' : '('.$product['discount'].'% '.__('Off').')'); ?></small>
                                            </td>
                                            <?php
                                            $subtotal += round(($product['price'] / $order->currency_value) * $order->currency_value, 2);
                                            ?>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>

                                    <tfoot>
                                        <tr>
                                            <td colspan="2"><?php echo e(__('Subtotal')); ?></td>
                                            <td><?php echo e(\PriceHelper::showCurrencyPrice($subtotal  * $order->currency_value)); ?></td>
                                        </tr>
                                        <?php if($order->shipping_cost != 0): ?>
                                        <?php 
                                        $price = round(($order->shipping_cost / $order->currency_value),2);
                                        ?>
                                            <?php if(DB::table('shippings')->where('price','=',$price)->count() > 0): ?>
                                            <tr>
                                                <td colspan="2"><?php echo e(DB::table('shippings')->where('price','=',$price)->first()->title); ?>(<?php echo e($order->currency_sign); ?>)</td>
                                                <td><?php echo e(\PriceHelper::showOrderCurrencyPrice($order->shipping_cost,$order->currency_sign)); ?></td>
                                            </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if($order->packing_cost != 0): ?>
                                        <?php 
                                        $pprice = round(($order->packing_cost / $order->currency_value),2);
                                        ?>
                                        <?php if(DB::table('packages')->where('price','=',$pprice)->count() > 0): ?>
                                        <tr>
                                            <td colspan="2"><?php echo e(DB::table('packages')->where('price','=',$pprice)->first()->title); ?>(<?php echo e($order->currency_sign); ?>)</td>
                                            <td><?php echo e(\PriceHelper::showOrderCurrencyPrice($order->packing_cost,$order->currency_sign)); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php endif; ?>

                                        <?php if($order->tax != 0): ?>
                                        <tr>
                                            <td colspan="2"><?php echo e(__('Tax')); ?></td>
                                            <td> <?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->tax) / $order->currency_value),$order->currency_sign)); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order->coupon_discount != null): ?>
                                        <tr>
                                            <td colspan="2"><?php echo e(__('Coupon Discount')); ?>(<?php echo e($order->currency_sign); ?>)</td>
                                            <td><?php echo e(\PriceHelper::showOrderCurrencyPrice($order->coupon_discount,$order->currency_sign)); ?></td>
                                        </tr>
                                        <?php endif; ?>
                                        <?php if($order->wallet_price != 0): ?>
                                        <tr>
                                            <td colspan="1"></td>
                                            <td><?php echo e(__('Paid From Wallet')); ?></td>
                                            <td><?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->wallet_price * $order->currency_value),$order->currency_sign)); ?>

                                            </td>
                                        </tr>
                                            <?php if($order->method != "Wallet"): ?>
                                            <tr>
                                                <td colspan="1"></td>
                                                <td><?php echo e($order->method); ?></td>
                                                <td><?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->pay_amount * $order->currency_value),$order->currency_sign)); ?>

                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                        <tr>
                                            <td colspan="1"></td>
                                            <td><?php echo e(__('Total')); ?></td>
                                            <td><?php echo e(\PriceHelper::showOrderCurrencyPrice((($order->pay_amount + $order->wallet_price) * $order->currency_value),$order->currency_sign)); ?>

                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Main Content Area End -->
</div>
</div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/invoice.blade.php ENDPATH**/ ?>