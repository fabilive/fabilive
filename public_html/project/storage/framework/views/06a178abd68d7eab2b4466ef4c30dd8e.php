<div class="dashboard-overlay">&nbsp;</div>
<div id="sidebar" class="sidebar-blog bg-light p-30">
  <div class="dashbaord-sidebar-close d-xl-none">
    <i class="fas fa-times"></i>
  </div>
    <div class="widget border-0 py-0 widget_categories">
        <h4 class="widget-title down-line"><?php echo e(__('Dashboard')); ?></h4>
        <ul>
            <li class=""><a class="<?php echo e(Request::url() == route('user-dashboard') ? 'active':''); ?>" href="<?php echo e(route('user-dashboard')); ?>">Dashboard</a></li>
            <?php if(Auth::user()->IsVendor()): ?>
            <li class=""><a class="" href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Vendor Dashboard')); ?></a></li>
            <?php endif; ?>
            <li class=""><a class="<?php echo e(Request::url() == route('user-orders') ? 'active':''); ?>" href="<?php echo e(route('user-orders')); ?>"><?php echo e(__('Purchased Items')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('user-deposit-index') ? 'active':''); ?>" href="<?php echo e(route('user-deposit-index')); ?>"><?php echo e(__('Deposit')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('user-transactions-index') ? 'active':''); ?>" href="<?php echo e(route('user-transactions-index')); ?>"><?php echo e(__('Transactions')); ?></a></li>

            <!--<li class=""><a class="<?php echo e(Request::url() == route('user-reward-index') ? 'active':''); ?>" href="<?php echo e(route('user-reward-index')); ?>"><?php echo e(__('Rewards')); ?></a></li>-->

            <li class=""><a class="<?php echo e(Request::url() == route('user-affilate-program') ? 'active':''); ?>" href="<?php echo e(route('user-affilate-program')); ?>"><?php echo e(__('Referral Program')); ?></a></li>

    <li class=""><a class="<?php echo e(Request::url() == route('user-wwt-index') ? 'active':''); ?>" href="<?php echo e(route('user-wwt-index')); ?>"><?php echo e(__('Withdraw')); ?></a></li>

            <li class=""><a class="<?php echo e(Request::url() == route('user-order-track') ? 'active':''); ?>" href="<?php echo e(route('user-order-track')); ?>"><?php echo e(__('Order Tracking')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('user-favorites') ? 'active':''); ?>" href="<?php echo e(route('user-favorites')); ?>"><?php echo e(__('Favourite Sellers')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('user-messages') ? 'active':''); ?>" href="<?php echo e(route('user-messages')); ?>"><?php echo e(__('Messages')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('user-messages-delivery') ? 'active':''); ?>" href="<?php echo e(route('user-messages-delivery')); ?>"><?php echo e(__('Deleviry Conservation' )); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('user-message-index') ? 'active':''); ?>" href="<?php echo e(route('user-message-index')); ?>"><?php echo e(__('Tickets')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('user-dmessage-index') ? 'active':''); ?>" href="<?php echo e(route('user-dmessage-index')); ?>"><?php echo e(__('Disputes')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('user-profile') ? 'active':''); ?>" href="<?php echo e(route('user-profile')); ?>"><?php echo e(__('Edit Profile')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('user-reset') ? 'active':''); ?>" href="<?php echo e(route('user-reset')); ?>"><?php echo e(__('Reset Password')); ?></a></li>
            <li class=""><a class="" href="<?php echo e(route('user-logout')); ?>"><?php echo e(__('Logout')); ?></a></li>
        </ul>
    </div>

    <?php if($gs->reg_vendor == 1): ?>
        <?php
            $user = Auth::user();
            $activeSubscription = $user->subscribes()->where('status', 1)->latest('id')->first();
            $subscriptionId = $activeSubscription ? $activeSubscription->subscription_id : 8;
        ?>

        <div class="row mt-4">
            <div class="col-lg-12 text-center">
                <a href="<?php echo e(route('user-vendor-request', $subscriptionId)); ?>" class="mybtn1 lg">
                    <i class="fas fa-dollar-sign"></i>
                    <?php echo e($user->is_vendor == 1 ? __('Start Selling') : __('Start Selling')); ?>

                </a>
            </div>
        </div>
    <?php endif; ?>

</div>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/partials/user/dashboard-sidebar.blade.php ENDPATH**/ ?>