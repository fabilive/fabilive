<div class="dashboard-overlay">&nbsp;</div>
<div id="sidebar" class="sidebar-blog bg-light p-30">
  <div class="dashbaord-sidebar-close d-xl-none">
    <i class="fas fa-times"></i>
  </div>
    <div class="widget border-0 py-0 widget_categories">
        <h4 class="widget-title down-line"><?php echo e(__('Dashboard')); ?></h4>
        <ul>
            <li class=""><a class="<?php echo e(Request::url() == route('rider-dashboard') ? 'active':''); ?>" href="<?php echo e(route('rider-dashboard')); ?>"><?php echo app('translator')->get('Dashboard'); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('rider-orders') ? 'active':''); ?>" href="<?php echo e(route('rider-orders')); ?>"><?php echo e(__('Pending Order')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('rider-conversation') ? 'active':''); ?>" href="<?php echo e(route('rider-conversation')); ?>"><?php echo e(__('Buyer Conversation')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('rider-orders').'?type=complete' ? 'active':''); ?>" href="<?php echo e(route('rider-orders').'?type=complete'); ?>"><?php echo e(__('Complete Order')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('rider-service-area') ? 'active':''); ?>" href="<?php echo e(route('rider-service-area')); ?>"><?php echo e(__('Service Area')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('rider-profile') ? 'active':''); ?>" href="<?php echo e(route('rider-profile')); ?>"><?php echo e(__('Edit Profile')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('rider-wwt-index') ? 'active':''); ?>" href="<?php echo e(route('rider-wwt-index')); ?>"><?php echo e(__('Withdraw')); ?></a></li>
            <li class=""><a class="<?php echo e(Request::url() == route('rider-reset') ? 'active':''); ?>" href="<?php echo e(route('rider-reset')); ?>"><?php echo e(__('Reset Password')); ?></a></li>
            <li class=""><a class="" href="<?php echo e(route('rider-logout')); ?>"><?php echo e(__('Logout')); ?></a></li>
          </ul>
    </div>
</div>

<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/partials/rider/dashboard-sidebar.blade.php ENDPATH**/ ?>