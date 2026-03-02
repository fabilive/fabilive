<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/front/css/datatables.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Dashboard')); ?></h3>
         </div>
      </div>
   </div>
</div>
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
            <?php if(Session::has('success')): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
               <strong><?php echo e(__('Success')); ?></strong> <?php echo e(Session::get('success')); ?>

            </div>
            <?php endif; ?>
            <div class="row">
               <div class="col-lg-6">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('Account Information')); ?></h4>
                     <div class="user-info">
                        <h5 class="title"><?php echo e($user->name); ?></h5>
                        <p><span class="user-title"><?php echo e(__('Email')); ?>:</span> <?php echo e($user->email); ?></p>
                        <?php if($user->phone != null): ?>
                        <p><span class="user-title"><?php echo e(__('Phone')); ?>:</span> <?php echo e($user->phone); ?></p>
                        <?php endif; ?>
                        <?php if($user->fax != null): ?>
                        <p><span class="user-title"><?php echo e(__('Fax')); ?>:</span> <?php echo e($user->fax); ?></p>
                        <?php endif; ?>
                        <?php if($user->city != null): ?>
                        <p><span class="user-title"><?php echo e(__('City')); ?>:</span> <?php echo e($user->city->city_name); ?></p>
                        <?php endif; ?>
                        <?php if($user->zip != null): ?>
                        <p><span class="user-title"><?php echo e(__('Zip')); ?>:</span> <?php echo e($user->zip); ?></p>
                        <?php endif; ?>
                        <?php if($user->address != null): ?>
                        <p><span class="user-title"><?php echo e(__('Address')); ?>:</span> <?php echo e($user->address); ?></p>
                        <?php endif; ?>
                     </div>
                  </div>
               </div>
               <div class="col-lg-6">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('My Wallet')); ?></h4>
                     <div class="user-info">
                        <h5 class="title"><?php echo e(__('Current Balance')); ?>:</h5>
                        <h5 class="title w-price"><?php echo e(App\Models\Product::vendorConvertPrice($user->balance)); ?></h5>
                        <hr>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row table-responsive-lg mt-3">
               <div class="col-lg-12">
                  <div class="widget border-0 p-30 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('Recent Orders')); ?></h4>
                     <div class="table-responsive">
                        <table class="table order-table" cellspacing="0" id="example" width="100%">
    <thead>
        <tr>
            <th><?php echo e(__('#Order')); ?></th>
            <th><?php echo e(__('Shipping Price')); ?></th>
            <th><?php echo e(__('Pickup Point')); ?></th>
            <th><?php echo e(__('Phone Number')); ?></th>
            <th><?php echo e(__('Order Total')); ?></th>
            <th><?php echo e(__('Order Status')); ?></th>
            <th><?php echo e(__('View')); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $vendorPackingJson = optional($order->order)->vendor_packing_id;
                $order_package     = $vendorPackingJson ? json_decode($vendorPackingJson, true) : [];
                $vendor_package_id = $order_package[$order->vendor_id] ?? null;
                $package           = $vendor_package_id ? \App\Models\Package::find($vendor_package_id) : null;
                $shipping_cost  = optional($order->order)->shipping_cost ?? 0;
                $packing_cost   = $package?->price ?? 0;
                $extra_price    = $shipping_cost + $packing_cost;
                $order_subtotal = optional($order->order)->vendororders
                                    ? optional($order->order)->vendororders->where('user_id', $order->vendor_id)->sum('price')
                                    : 0;
                $pay_amount = optional($order->order)->pay_amount ?? 0;
                $commission = optional($order->order)->commission ?? 0;
                $currency   = optional($order->order)->currency_value ?? 1;
                $total = ($pay_amount - $commission) * $currency;
            ?>
            <tr>
                <td data-label="<?php echo e(__('#Order')); ?>">
                    <?php echo e(optional($order->order)->order_number ?? 'N/A'); ?>

                </td>
                <td data-label="<?php echo e(__('Shipping Price')); ?>">
                    <?php echo e(number_format($order->order->total_delivery_fee, 2)); ?>

                </td>
                <td data-label="<?php echo e(__('Pickup Point')); ?>">
                    <p><?php echo e(optional($order->pickup)->location ?? 'N/A'); ?></p>
                </td>
                <td data-label="<?php echo e(__('Phone Number')); ?>">
                    <?php if($order->phone_number): ?>
                        <?php
                            $local = preg_replace('/[^0-9]/', '', $order->phone_number);
                            // Cameroon numbers usually start with 6 and are 9 digits long
                            $wa_number = (str_starts_with($local, '6') && strlen($local) === 9)
                                ? '237' . $local
                                : $local;
                        ?>

                        <a href="tel:<?php echo e($order->phone_number); ?>" style="display:block; color:blue;">
                            📞 <?php echo e($order->phone_number); ?>

                        </a>
                    <?php else: ?>
                        <p>N/A</p>
                    <?php endif; ?>
                </td>
                <td data-label="<?php echo e(__('Order Total')); ?>">
                    <?php echo e(\PriceHelper::showAdminCurrencyPrice($total, $order->currency_sign)); ?>

                </td>
                <td data-label="<?php echo e(__('Order Status')); ?>">
                    <span class="badge badge-dark p-2"><?php echo e(ucwords($order->status)); ?></span>
                </td>
                <td data-label="<?php echo e(__('View')); ?>">
                    <a class="mybtn1 sm1" href="<?php echo e(route('rider-order-details', $order->id)); ?>">
                        <?php echo e(__('View Order')); ?>

                    </a>
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
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/rider/dashbaord.blade.php ENDPATH**/ ?>