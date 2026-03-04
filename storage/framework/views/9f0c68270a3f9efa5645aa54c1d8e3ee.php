

<?php $__env->startSection('styles'); ?>

<style type="text/css">
    .table-responsive {
    overflow-x: hidden;
}
table#example2 {
    margin-left: 10px;
}

</style>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

                    <div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading"><?php echo e(__("Customer Details")); ?> <a class="add-btn" href="<?php echo e(url()->previous()); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__("Back")); ?></a></h4>
                                        <ul class="links">
                                            <li>
                                                <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__("Dashboard")); ?> </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e(route('admin-user-index')); ?>"><?php echo e(__("Customers")); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e(route('admin-user-show',$data->id)); ?>"><?php echo e(__("Details")); ?></a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>
                            <div class="add-product-content1 customar-details-area add-product-content2">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product-description">
                                            <div class="body-area">
                                            <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="user-image">
                                                            <?php if($data->is_provider == 1): ?>
                                                            <img src="<?php echo e($data->photo ? asset($data->photo):asset('assets/images/'.$gs->user_image)); ?>" alt="No Image">
                                                            <?php else: ?>
                                                            <img src="<?php echo e($data->photo ? asset('assets/images/users/'.$data->photo):asset('assets/images/'.$gs->user_image)); ?>" alt="No Image">                                            
                                                            <?php endif; ?>
                                                        <a href="javascript:;" class="mybtn1 send" data-email="<?php echo e($data->email); ?>" data-toggle="modal" data-target="#vendorform"><?php echo e(__("Send Message")); ?></a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="table-responsive show-table">
                                                        <table class="table">
                                                        <tr>
                                                            <th><?php echo e(__("ID#")); ?></th>
                                                            <td><?php echo e($data->id); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Name")); ?></th>
                                                            <td><?php echo e($data->name); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Email")); ?></th>
                                                            <td><?php echo e($data->email); ?></td>
                                                        </tr>
                                                        <tr>
                                                                <th><?php echo e(__("Phone")); ?></th>
                                                                <td><?php echo e($data->phone); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <th><?php echo e(__("Address")); ?></th>
                                                                <td><?php echo e($data->address); ?></td>
                                                            </tr>

                                                        </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="table-responsive show-table">
                                                    <table class="table">
                                                            
                                                            <?php if($data->city != null): ?>
                                                            <tr>
                                                                <th><?php echo e(__("City")); ?></th>
                                                                <td><?php echo e($data->city); ?></td>
                                                            </tr>
                                                            <?php endif; ?>
                                                            <?php if($data->fax != null): ?>
                                                            <tr>
                                                                <th><?php echo e(__("Fax")); ?></th>
                                                                <td><?php echo e($data->fax); ?></td>
                                                            </tr>
                                                            <?php endif; ?>
                                                            <?php if($data->zip != null): ?>
                                                            <tr>
                                                                <th><?php echo e(__("Zip Code")); ?></th>
                                                                <td><?php echo e($data->zip); ?></td>
                                                            </tr>
                                                            <?php endif; ?>
                                                            <tr>
                                                                <th><?php echo e(__("Joined")); ?></th>
                                                                <td><?php echo e($data->created_at->diffForHumans()); ?></td>
                                                            </tr>
                                                        </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-table-wrap">
                                                <div class="order-details-table">
                                                    <div class="mr-table">
                                                        <h4 class="title"><?php echo e(__("Products Ordered")); ?></h4>
                                                        <div class="table-responsive">
                                                                <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><?php echo e(__("Order ID")); ?></th>
                                                                            <th><?php echo e(__("Purchase Date")); ?></th>
                                                                            <th><?php echo e(__("Amount")); ?></th>
                                                                            <th><?php echo e(__("Status")); ?></th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $__currentLoopData = $data->orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <tr>
            <td><a href="<?php echo e(route('admin-order-invoice',$order->id)); ?>"><?php echo e(sprintf("%'.08d", $order->id)); ?></a></td>
                                                                            <td><?php echo e(date('Y-m-d h:i:s a',strtotime($order->created_at))); ?></td>
                                                                            <td><?php echo e(\PriceHelper::showOrderCurrencyPrice(($order->pay_amount * $order->currency_value),$order->currency_sign)); ?></td>
                                                                            <td><?php echo e($order->status); ?></td>
                                                                            <td>
                                                                                <a href=" <?php echo e(route('admin-order-show',$order->id)); ?>" class="view-details">
                                                                                    <i class="fas fa-check"></i><?php echo e(__("Details")); ?>

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
                        </div>


<div class="sub-categori">
    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="vendorformLabel"><?php echo e(__("Send Message")); ?></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                </div>
            <div class="modal-body">
                <div class="container-fluid p-0">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="contact-form">
                                <form id="emailreply1">
                                    <?php echo e(csrf_field()); ?>

                                    <ul>
                                        <li>
                                            <input type="email" class="input-field eml-val" id="eml1" name="to" placeholder="<?php echo e(__("Email")); ?> *" value="" required="">
                                        </li>
                                        <li>
                                            <input type="text" class="input-field" id="subj1" name="subject" placeholder="<?php echo e(__("Subject")); ?> *" required="">
                                        </li>
                                        <li>
                                            <textarea class="input-field textarea" name="message" id="msg1" placeholder="<?php echo e(__("Your Message")); ?> *" required=""></textarea>
                                        </li>
                                    </ul>
                                    <button class="submit-btn" id="emlsub1" type="submit"><?php echo e(__("Send Message")); ?></button>
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
      'paging'      : false,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
      'responsive'  : true
} );

})(jQuery);

</script>


<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/user/show.blade.php ENDPATH**/ ?>