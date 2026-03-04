<?php $__env->startSection('styles'); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('Order Details')); ?> <a class="add-btn"
                        href="<?php echo e(route('vendor-order-index')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a>
                </h4>
                <ul class="links">
                    <li>
                        <a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
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
        <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="special-box">
                    <div class="heading-area">
                        
                        <h4 class="title">
                            <?php echo e(__('Order Details')); ?>

                            <?php if(@App\Models\DeliveryRider::where('vendor_id',auth()->id())->where('order_id',$order->id)->first()->status
                            == 'delivered'): ?>
                            
                            <a href="<?php echo e(route('vendor-order-status', ['id1' => $order->order_number, 'status' => 'completed'])); ?>" class="mybtn1">
                                    <?php echo app('translator')->get('Make Complete'); ?>
                                </a>
                            <?php endif; ?>
                        </h4>
                        
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th class="45%" width="45%"><?php echo e(__('Order ID')); ?></th>
                                    <td width="10%">:</td>
                                    <td class="45%" width="45%"><?php echo e($order->order_number); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><?php echo e(__('Total Product')); ?></th>
                                    <td width="10%">:</td>
                                    <td width="45%">
                                        <?php echo e($order->vendororders()->where('user_id','=',$user->id)->sum('qty')); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><?php echo e(__('Total Cost')); ?></th>
                                    <td width="10%">:</td>
                                    

                                    <?php
    $price = $order->vendororders()->where('user_id', $user->id)->sum('price');
    if ($order->is_shipping == 1) {
        $user_id = auth()->id();

        // shipping cost
        $vendor_shipping = json_decode($order->vendor_shipping_id, true); // decode as array
        $shipping_id = $vendor_shipping[$user_id] ?? null;

        if ($shipping_id) {
            $shipping = App\Models\Shipping::find($shipping_id); // use find instead of findOrFail to avoid crash
            if ($shipping) {
                $price += round($shipping->price * $order->currency_value, 2);
            }
        }

        // packaging cost
        $vendor_packing_id = json_decode($order->vendor_packing_id, true); // decode as array
        $packing_id = $vendor_packing_id[$user_id] ?? null;

        if ($packing_id) {
            $packaging = App\Models\Package::find($packing_id);
            if ($packaging) {
                $price += round($packaging->price * $order->currency_value, 2);
            }
        }
    }
?>


                                    <td width="45%">
                                        <?php echo e(\PriceHelper::showOrderCurrencyPrice(($price-$order->commission), $order->currency_sign)); ?>

                                    </td>
                                </tr>

                                <?php if(isset($shipping)): ?>
                                <tr>
                                    <th width="45%"><?php echo e(__('Shipping Method')); ?></th>
                                    <td width="10%">:</td>
                                    <td width="45%"><?php echo e($shipping->title); ?> | <?php echo e(\PriceHelper::showOrderCurrencyPrice(($shipping->price *
                                        $order->currency_value),$order->currency_sign)); ?></td>
                                </tr>
                                <?php endif; ?>

                                <?php if(isset($packaging)): ?>
                                <tr>
                                    <th width="45%"><?php echo e(__('Packaging Method')); ?></th>
                                    <td width="10%">:</td>
                                    <td width="45%"><?php echo e($packaging->title); ?> | <?php echo e(\PriceHelper::showOrderCurrencyPrice(($packaging->price *
                                        $order->currency_value),$order->currency_sign)); ?></td>
                                </tr>
                                <?php endif; ?>
                                
                                <tr>
                                    <th class="45%" width="45%"><?php echo e(__('Total Delivery Fee')); ?></th>
                                    <td width="10%">:</td>
                                    <td class="45%" width="45%"><?php echo e($order->total_delivery_fee); ?></td>
                                </tr>
                                
                                <tr>
                                    <th width="45%"><?php echo e(__('Ordered Date')); ?></th>
                                    <td width="10%">:</td>
                                    <td width="45%"><?php echo e(date('d-M-Y H:i:s a',strtotime($order->created_at))); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><?php echo e(__('Payment Method')); ?></th>
                                    <td width="10%">:</td>
                                    <td width="45%"><?php echo e($order->method); ?></td>
                                </tr>

                                <?php if($order->method != "Cash On Delivery"): ?>
                                <?php if($order->method=="Stripe"): ?>
                                <tr>
                                    <th width="45%"><?php echo e($order->method); ?> <?php echo e(__('Charge ID')); ?></th>
                                    <td width="10%">:</td>
                                    <td width="45%"><?php echo e($order->charge_id); ?></td>
                                </tr>
                                <?php endif; ?>
                                <tr>
                                    <th width="45%"><?php echo e($order->method); ?> <?php echo e(__('Transaction ID')); ?></th>
                                    <td width="10%">:</td>
                                    <td width="45%"><?php echo e($order->txnid); ?></td>
                                </tr>
                                <?php endif; ?>


                                <th width="45%"><?php echo e(__('Payment Status')); ?></th>
                                <td width="10%">:</td>
                                <td><?php if($order->payment_status == 'Pending'): ?>
                                    <span class='badge badge-danger'><?php echo e(__('Unpaid')); ?></span>
                                    <?php else: ?>
                                    <span class='badge badge-success'><?php echo e(__('Paid')); ?></span>
                                    <?php endif; ?>
                                </td>


                                <?php if(!empty($order->order_note)): ?>
                                <th width="45%"><?php echo e(__('Order Note')); ?></th>
                                <th width="10%">:</th>
                                <td width="45%"><?php echo e($order->order_note); ?></td>
                                <?php endif; ?>

                            </tbody>
                        </table>
                    </div>
                    <div class="footer-area">
                        <a href="<?php echo e(route('vendor-order-invoice',$order->order_number)); ?>" class="mybtn1"><i
                                class="fas fa-eye"></i> <?php echo e(__('View Invoice')); ?></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="special-box">
                    <div class="heading-area">
                        <h4 class="title">
                            <?php echo e(__('Billing Details')); ?>

                        </h4>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="45%"><?php echo e(__('Name')); ?></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->customer_name); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><?php echo e(__('Email')); ?></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->customer_email); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><?php echo e(__('Phone')); ?></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->customer_phone); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><?php echo e(__('Address')); ?></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->customer_address); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><?php echo e(__('Country')); ?></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->customer_country); ?></td>
                                </tr>
                                
                                <tr>
                                    <th width="45%"><?php echo e(__('City')); ?></th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        <?php echo e($order->customerCity ? $order->customerCity->city_name : 'N/A'); ?>

                                    </td>
                                </tr>

                                
                                <tr>
                                    <th width="45%"><?php echo e(__('Postal Code')); ?></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->customer_zip); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><?php echo e(__('Service Area')); ?></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->servicearea->location); ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <?php if($order->dp == 0): ?>
            <div class="col-lg-6">
                <div class="special-box">
                    <div class="heading-area">
                        <h4 class="title">
                            <?php echo e(__('Coupon Code')); ?>

                        </h4>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                <?php if($order->shipping == "pickup"): ?>
                                <tr>
                                    <th width="45%"><strong><?php echo e(__('Pickup Location')); ?>:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->pickup_location); ?></td>
                                </tr>
                                <?php else: ?>
                                <tr>
                                    <th width="45%"><strong><?php echo e(__('Name')); ?>:</strong></th>
                                    <th width="10%">:</th>
                                    <td><?php echo e($order->shipping_name == null ? $order->customer_name :
                                        $order->shipping_name); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong><?php echo e(__('Email')); ?>:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->shipping_email == null ? $order->customer_email :
                                        $order->shipping_email); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong><?php echo e(__('Phone')); ?>:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->shipping_phone == null ? $order->customer_phone :
                                        $order->shipping_phone); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong><?php echo e(__('Address')); ?>:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->shipping_address == null ? $order->customer_address :
                                        $order->shipping_address); ?></td>
                                </tr>
                                <tr>
                                    <th width="45%"><strong><?php echo e(__('Country')); ?>:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->shipping_country == null ? $order->customer_country :
                                        $order->shipping_country); ?></td>
                                </tr>
                                
                                <tr>
                                    <th width="45%"><strong><?php echo e(__('City')); ?>:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        <?php echo e($order->shippingCity
                                            ? $order->shippingCity->city_name
                                            : ($order->customerCity ? $order->customerCity->city_name : 'N/A')); ?>

                                    </td>
                                </tr>

                                
                                <tr>
                                    <th width="45%"><strong><?php echo e(__('Postal Code')); ?>:</strong></th>
                                    <th width="10%">:</th>
                                    <td width="45%"><?php echo e($order->shipping_zip == null ? $order->customer_zip :
                                        $order->shipping_zip); ?></td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>



        <div class="row">
            <div class="col-lg-12 order-details-table">
                <div class="mr-table">
                    <h4 class="title"><?php echo e(__('Products Ordered')); ?></h4>
                    <div class="table-responsive">
                        <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>

                                <tr>
                                    <th><?php echo e(__('Product ID#')); ?></th>
                                    <th><?php echo e(__('Shop Name')); ?></th>
                                    <th><?php echo e(__('Status')); ?></th>
                                    <th><?php echo e(__('Product Title')); ?></th>
                                    <th width="20%"><?php echo e(__('Details')); ?></th>
                                    <th width="10%"><?php echo e(__('Total Price')); ?></th>
                                </tr>

                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $cart['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($product['item']['user_id'] != 0): ?>
                                <?php if($product['item']['user_id'] == $user->id): ?>
                                <tr>
                                    <td><input type="hidden" value="<?php echo e($key); ?>"><?php echo e($product['item']['id']); ?></td>
                                    <td>
                                        <?php if($product['item']['user_id'] != 0): ?>
                                        <?php
                                        $user = App\Models\User::find($product['item']['user_id']);
                                        ?>
                                        <?php if(isset($user)): ?>
                                        <a target="_blank"
                                            href="<?php echo e(route('admin-vendor-show',$user->id)); ?>"><?php echo e($user->shop_name); ?></a>
                                        <?php else: ?>
                                        <?php echo e(__('Vendor Removed')); ?>

                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($product['item']['user_id'] != 0): ?>
                                        <?php
                                        $user =
                                        App\Models\VendorOrder::where('order_id','=',$order->id)->where('user_id','=',$product['item']['user_id'])->first();
                                        ?>
                                        <?php if($order->dp == 1 && $order->payment_status == 'Completed'): ?>
                                        <span class="badge badge-success"><?php echo e(__('Completed')); ?></span>
                                        <?php else: ?>
                                        <?php if($user->status == 'pending'): ?>
                                        <span class="badge badge-warning"><?php echo e(ucwords($user->status)); ?></span>
                                        <?php elseif($user->status == 'processing'): ?>
                                        <span class="badge badge-info"><?php echo e(ucwords($user->status)); ?></span>
                                        <?php elseif($user->status == 'on delivery'): ?>
                                        <span class="badge badge-primary"><?php echo e(ucwords($user->status)); ?></span>
                                        <?php elseif($user->status == 'completed'): ?>
                                        <span class="badge badge-success"><?php echo e(ucwords($user->status)); ?></span>
                                        <?php elseif($user->status == 'declined'): ?>
                                        <span class="badge badge-danger"><?php echo e(ucwords($user->status)); ?></span>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <input type="hidden" value="<?php echo e($product['license']); ?>">
                                        <?php if($product['item']['user_id'] != 0): ?>
                                        <?php
                                        $user = App\Models\User::find($product['item']['user_id']);
                                        ?>
                                        <?php if(isset($user)): ?>
                                        <a target="_blank"
                                            href="<?php echo e(route('front.product', $product['item']['slug'])); ?>">
                                            <?php echo e(mb_strlen($product['item']['name'],'UTF-8') > 30 ?
                                            mb_substr($product['item']['name'],0,30,'UTF-8').'...' :
                                            $product['item']['name']); ?>

                                        </a>
                                        <?php else: ?>
                                        <a href="javascript:;">
                                            <?php echo e(mb_strlen($product['item']['name'],'UTF-8') > 30 ?
                                            mb_substr($product['item']['name'],0,30,'UTF-8').'...' :
                                            $product['item']['name']); ?>

                                        </a>
                                        <?php endif; ?>
                                        <?php endif; ?>
                                        <?php if($product['license'] != ''): ?>
                                        <a href="javascript:;" data-toggle="modal" data-target="#confirm-delete"
                                            class="btn btn-info product-btn" id="license" style="padding: 5px 12px;"><i
                                                class="fa fa-eye"></i> <?php echo e(__('View License')); ?></a>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if($product['size']): ?>
                                        <p>
                                            <strong><?php echo e(__('Size')); ?> :</strong> <?php echo e(str_replace('-','
                                            ',$product['size'])); ?>

                                        </p>
                                        <?php endif; ?>
                                        <?php if($product['color']): ?>
                                        <p>
                                            <strong><?php echo e(__('Color')); ?> :</strong> <span
                                                style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; border-radius: 50%; background: #<?php echo e($product['color']); ?>;"></span>
                                        </p>
                                        <?php endif; ?>
                                        <p>
                                            <strong><?php echo e(__('Price')); ?> :</strong>
                                            <?php echo e(\PriceHelper::showOrderCurrencyPrice(($price-$order->commission), $order->currency_sign)); ?>

                                        </p>
                                        <p>
                                            <strong><?php echo e(__('Qty')); ?> :</strong> <?php echo e($product['qty']); ?> <?php echo e($product['item']['measure']); ?>

                                        </p>
                                        <?php if(!empty($product['keys'])): ?>

                                        <?php $__currentLoopData = array_combine(explode(',', $product['keys']), explode(',',
                                        $product['values'])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <p>
                                            <b><?php echo e(ucwords(str_replace('_', ' ', $key))); ?> : </b> <?php echo e($value); ?>

                                        </p>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php echo e(\PriceHelper::showOrderCurrencyPrice(($price-$order->commission), $order->currency_sign)); ?> <small><?php echo e($product['discount']
                                            == 0 ? '' : '('.$product['discount'].'% '.__('Off').')'); ?></small>
                                    </td>
                                </tr>
                                <?php endif; ?>
                                <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-12 text-center mt-2">
                <a class="btn sendEmail send" href="javascript:;" class="send" data-email="<?php echo e($order->customer_email); ?>"
                    data-toggle="modal" data-target="#vendorform">
                    <i class="fa fa-send"></i> <?php echo e(__('Send Email')); ?>

                </a>
            </div>
        </div>
    </div>
</div>
<!-- Main Content Area End -->
</div>
</div>
</div>



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
                <p class="text-center"><?php echo e(__('The Licenes Key is')); ?> : <span id="key"></span> <a href="javascript:;"
                        id="license-edit"><?php echo e(__('Edit License')); ?></a><a href="javascript:;" id="license-cancel"
                        class="showbox"><?php echo e(__('Cancel')); ?></a></p>
                <form method="POST" action="<?php echo e(route('vendor-order-license',$order->order_number)); ?>" id="edit-license"
                    style="display: none;">
                    <?php echo e(csrf_field()); ?>

                    <input type="hidden" name="license_key" id="license-key" value="">
                    <div class="form-group text-center">
                        <input type="text" name="license" placeholder="<?php echo e(__('Enter New License Key')); ?>"
                            style="width: 40%; border: none;" required="">
                        <input type="submit" name="submit" value="<?php echo e(__('Save License')); ?>" class="btn btn-primary"
                            style="border-radius: 0; padding: 2px; margin-bottom: 2px;">
                    </div>
                </form>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
            </div>
        </div>
    </div>
</div>





<div class="sub-categori">
    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel"><?php echo e(__('Send Email')); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid p-0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="contact-form">
                                    <form id="emailreply">
                                        <?php echo e(csrf_field()); ?>

                                        <ul>
                                            <li>
                                                <input type="email" class="input-field eml-val" id="eml" name="to"
                                                    placeholder="<?php echo e(__('Email')); ?> *" value="" required="">
                                            </li>
                                            <li>
                                                <input type="text" class="input-field" id="subj" name="subject"
                                                    placeholder="<?php echo e(__('Subject')); ?> *" required="">
                                            </li>
                                            <li>
                                                <textarea class="input-field textarea" name="message" id="msg"
                                                    placeholder="<?php echo e(__('Your Message')); ?> *" required=""></textarea>
                                            </li>
                                        </ul>
                                        <button class="submit-btn" id="emlsub" type="submit"><?php echo e(__('Send Email')); ?></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






<?php $__env->stopSection(); ?>


<?php $__env->startSection('scripts'); ?>

<script type="text/javascript">
    (function($) {
		"use strict";

$('#example2').dataTable( {
  "ordering": false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
      'responsive'  : true
} );

})(jQuery);

</script>

<script type="text/javascript">
    (function($) {
		"use strict";
        $(document).on('click','#license' , function(e){
            var id = $(this).parent().find('input[type=hidden]').val();
            var key = $(this).parent().parent().find('input[type=hidden]').val();
            $('#key').html(id);
            $('#license-key').val(key);
    });
        $(document).on('click','#license-edit' , function(e){
            $(this).hide();
            $('#edit-license').show();
            $('#license-cancel').show();
        });
        $(document).on('click','#license-cancel' , function(e){
            $(this).hide();
            $('#edit-license').hide();
            $('#license-edit').show();
        });
        <?php if(Session::has('license')): ?>
        $.notify('<?php echo e(Session::get('license')); ?>','success');
        <?php endif; ?>
})(jQuery);

</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/order/details.blade.php ENDPATH**/ ?>