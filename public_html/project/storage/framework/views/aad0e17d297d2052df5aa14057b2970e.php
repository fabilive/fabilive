<?php $__env->startSection('content'); ?>
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading"><?php echo e(__('All Orders')); ?></h4>
                <ul class="links">
                    <li>
                        <a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                    </li>
                    <li>
                        <a href="javascript:;"><?php echo e(__('Orders')); ?></a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('vendor-order-index')); ?>"><?php echo e(__('All Orders')); ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="mr-table allproduct">
                    <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <div class="table-responsive">
                        <div class="gocover"
                            style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th><?php echo e(__('Order Number')); ?></th>
                                    <th><?php echo e(__('Total Qty')); ?></th>
                                    <th><?php echo e(__('Total Cost')); ?></th>
                                    <th><?php echo e(__('Payment Method')); ?></th>
                                    <th><?php echo e(__('Actions')); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="vendor-status" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="submit-loader">
                <img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>" alt="">
            </div>
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block"><?php echo e(__('Update Status')); ?></h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center"><?php echo e(__("You are about to update the order's Status.")); ?></p>
                <p class="text-center"><?php echo e(__('Do you want to proceed?')); ?></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                <a class="btn btn-success btn-ok order-btn"><?php echo e(__('Proceed')); ?></a>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('scripts'); ?>
<script type="text/javascript">
    (function($) {
		"use strict";
            $(document).on('change','.vendor-btn',function(){
                $('#vendor-status').modal('show');
                $('#vendor-status').find('.btn-ok').attr('href', $(this).val());
            });
           var table = $('#geniustable').DataTable({
               ordering: false,
               processing: true,
               serverSide: true,
               ajax: '<?php echo e(route('vendor-order-datatables')); ?>',
               columns: [
                        { data: 'order_number', name: 'order_number' },
                        { data: 'totalQty'},
                        { data: 'pay_amount'},
                        { data: 'method', name: 'method' },
                        { data: 'action', name: 'action' }
                     ],
               language : {
                    processing: '<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>">'
                },
                drawCallback : function( settings ) {
                        $('.select').niceSelect();
                }
            });
})(jQuery);
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/order/index.blade.php ENDPATH**/ ?>