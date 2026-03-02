<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="full-row bg-light overlay-dark py-5"
    style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Delivery Items')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(('rider-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Delivery Items')); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<?php
$order = $data->order;
?>
<div class="full-row">
    <div class="container">
        <div class="mb-4 d-xl-none">
            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-xl-3">
                <?php echo $__env->make('partials.rider.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="col-xl-9">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-40 widget_categories bg-light account-info">
                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Delivery Details')); ?>

                                <?php if($data->status == 'pending'): ?>
                                <a class="mybtn1 alert_link"
                                    href="<?php echo e(route('rider-order-delivery-accept',$data->id)); ?>"><?php echo app('translator')->get('Accept'); ?></a>
                                <a class="mybtn1 alert_link"
                                    href="<?php echo e(route('rider-order-delivery-reject',$data->id)); ?>"><?php echo app('translator')->get('Reject'); ?></a>
                                <?php elseif($data->status == 'accepted'): ?>
                                <a class="mybtn1 alert_link"
                                    href="<?php echo e(route('rider-order-delivery-complete',$data->id)); ?>"><?php echo app('translator')->get('Make
                                    Delivered'); ?></a>
                                <?php elseif($data->status == 'rejected'): ?>
                                <strong class="bg-danger p-2 text-white"><?php echo e(__('Rejected')); ?></strong>
                                <?php else: ?>
                                <strong class="bg-success p-2 text-white"><?php echo e(__('Delivered')); ?></strong>
                                <?php endif; ?>
                            </h4>
                            <div class="view-order-page">
                                <div class="billing-add-area">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h5><?php echo e(__('Delivery Address')); ?></h5>
                                            <address>
                                                <?php echo e(__('Name:')); ?> <?php echo e($order->customer_name); ?><br>
                                                <?php echo e(__('Email:')); ?> <?php echo e($order->customer_email); ?><br>
                                                <?php echo e(__('Phone:')); ?> <?php echo e($order->customer_phone); ?><br>
                                                <?php echo e(__('City:')); ?> <?php echo e($data->pick->location ?? "N/A"); ?><br>

                                                <?php echo e(__('Address:')); ?> <?php echo e($order->customer_address); ?><br>


                                            </address>
                                        </div>
                                        <div class="col-md-6">
                                            <h5><?php echo e(__('Vendor Information')); ?></h5>
                                            <p>
                                                <?php echo e(__('Shop Name:')); ?> <?php echo e($data->vendor->shop_name); ?><br>
                                                <?php echo e(__('Email:')); ?> <?php echo e($data->vendor->email); ?><br>
                                                <?php echo e(__('Phone:')); ?> <?php echo e($data->vendor->phone); ?><br>

                                                <?php echo e(__('Address:')); ?> <?php echo e($data->vendor->address); ?> <br>

                                                <?php echo e(__('Picup Location:')); ?> <?php echo e($data->serviceAreas->location ?? "N/A"); ?><br>

                                                <?php echo e(__('Picup Location More Info:')); ?> <?php echo e($data->more_info ?? "N/A"); ?><br>

                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="table-responsive">
                                    <h5><?php echo e(__('Ordered Products:')); ?></h5>
                                    <table class="table veiw-details-table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('ID#')); ?></th>
                                                <th><?php echo e(__('Name')); ?></th>
                                                <th><?php echo e(__('Details')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $extra_price = 0;
                                            ?>
                                            <?php $__currentLoopData = json_decode($order->cart,true)['items']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if($product['user_id'] == $data->vendor_id): ?>
                                            <tr>
                                                <td data-label="<?php echo e(__('ID#')); ?>">
                                                    <div>
                                                        <?php echo e($product['item']['id']); ?>

                                                    </div>
                                                </td>
                                                <td data-label="<?php echo e(__('Name')); ?>">
                                                    <?php echo e(mb_strlen($product['item']['name'],'UTF-8') > 50 ?
                                                    mb_substr($product['item']['name'],0,50,'UTF-8').'...' :
                                                    $product['item']['name']); ?>


                                                </td>
                                                <td data-label="<?php echo e(__('Details')); ?>">
                                                    <div>
                                                        <b><?php echo e(__('Quantity')); ?></b>: <?php echo e($product['qty']); ?> <br>
                                                        <?php if(!empty($product['size'])): ?>
                                                        <b><?php echo e(__('Size')); ?></b>: <?php echo e($product['item']['measure']); ?><?php echo e(str_replace('-',' ',$product['size'])); ?> <br>
                                                        <?php endif; ?>
                                                        <?php if(!empty($product['color'])): ?>
                                                        <div class="d-flex mt-2">
                                                            <b><?php echo e(__('Color')); ?></b>: <span id="color-bar"
                                                                style="width: 20px; height: 20px; display: inline-block; vertical-align: middle; border-radius: 50%; background: #<?php echo e($product['color']); ?>;"></span>
                                                        </div>
                                                        <?php endif; ?>
                                                        <?php if(!empty($product['keys'])): ?>
                                                        <?php $__currentLoopData = array_combine(explode(',', $product['keys']),
                                                        explode(',', $product['values'])); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <b><?php echo e(ucwords(str_replace('_', ' ', $key))); ?> : </b> <?php echo e($value); ?> <br>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <div class="text-center">
                                        <?php
                                        $order_shipping = @json_decode($data->order->vendor_shipping_id, true);
                                        $order_package = @json_decode($data->order->vendor_packing_id, true);
                                        $vendor_shipping_id = @$order_shipping[$data->vendor_id];
                                        $vendor_package_id = @$order_package[$data->vendor_id];
                                        if($vendor_shipping_id){
                                        $shipping = App\Models\Shipping::findOrFail($vendor_shipping_id);
                                        }else{
                                        $shipping = [];
                                        }
                                        if($vendor_package_id){
                                        $package = App\Models\Package::findOrFail($vendor_package_id);
                                        }else{
                                        $package = [];
                                        }
                                        $shipping_cost = 0;
                                        $packing_cost = 0;
                                        if($shipping){
                                        $shipping_cost = $shipping->price;
                                        }
                                        if($package){
                                        $packing_cost = $package->price;
                                        }
                                        $extra_price = $shipping_cost + $packing_cost;
                                        ?>

                                    </div>
                                </div>
                            </div>
                            <a class="back-btn theme-bg" href="<?php echo e(route('rider-orders')); ?>"> <?php echo e(__('Back')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
    $(document).on('click','.alert_link',function(){
        var status = confirm('Are you sure? You want to perform this action');
        if(status == true){
            return true;
        }else{
            return false;
        }
    })
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/rider/order_details.blade.php ENDPATH**/ ?>