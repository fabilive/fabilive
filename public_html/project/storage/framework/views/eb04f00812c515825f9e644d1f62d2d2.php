 

<?php $__env->startSection('content'); ?>  
                    <input type="hidden" id="headerdata" value="<?php echo e(__("WITHDRAW")); ?>">
                    <div class="content-area">
                        <div class="mr-breadcrumb">
                            <div class="row">
                                <div class="col-lg-12">
                                        <h4 class="heading"><?php echo e(__("Withdraws")); ?></h4>
                                        <ul class="links">
                                            <li>
                                                <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__("Dashboard")); ?> </a>
                                            </li>
                                            <li>
                                                <a href="javascript:;"><?php echo e(__("Vendors")); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e(route('admin-withdraw-index')); ?>"><?php echo e(__("Withdraws")); ?></a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>
                        <div class="product-area">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="mr-table allproduct">
                                        <?php echo $__env->make('alerts.admin.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
                                        <div class="table-responsive">
                                                <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th><?php echo e(__("Email")); ?></th>
                                                            <th><?php echo e(__("Phone")); ?></th>
                                                            <th><?php echo e(__("Amount")); ?></th>
                                                            <th><?php echo e(__("Method")); ?></th>
                                                            <th><?php echo e(__("Withdraw Date")); ?></th>
                                                            <th><?php echo e(__("Status")); ?></th>
                                                            <th><?php echo e(__("Options")); ?></th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



            <div class="modal fade" id="modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
                                        
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="submit-loader">
                                <img  src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>" alt="">
                            </div>
                            <div class="modal-header">
                                <h5 class="modal-title"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo e(__("Close")); ?></button>
                            </div>
                        </div>
                    </div>

                </div>





<div class="modal fade" id="status-modal1" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

    <div class="modal-header d-block text-center">
        <h4 class="modal-title d-inline-block"><?php echo e(__("Accept Withdraw")); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
    </div>

      <!-- Modal body -->
      <div class="modal-body">
            <p class="text-center"><?php echo e(__("You are about to accept this Withdraw.")); ?></p>
            <p class="text-center"><?php echo e(__("Do you want to proceed?")); ?></p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__("Cancel")); ?></button>
            <a class="btn btn-success btn-ok"><?php echo e(__("Accept")); ?></a>
      </div>

    </div>
  </div>
</div>






<div class="modal fade" id="status-modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

    <div class="modal-header d-block text-center">
        <h4 class="modal-title d-inline-block"><?php echo e(__("Reject Withdraw")); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
    </div>

      <!-- Modal body -->
      <div class="modal-body">
            <p class="text-center"><?php echo e(__("You are about to reject this Withdraw.")); ?></p>
            <p class="text-center"><?php echo e(__("Do you want to proceed?")); ?></p>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__("Cancel")); ?></button>
            <a class="btn btn-danger btn-ok"><?php echo e(__("Reject")); ?></a>
      </div>

    </div>
  </div>
</div>



<?php $__env->stopSection(); ?>    

<?php $__env->startSection('scripts'); ?>



    <script type="text/javascript">

(function($) {
		"use strict";

        var table = $('#geniustable').DataTable({
               ordering: false,
               processing: true,
               serverSide: true,
               ajax: '<?php echo e(route('admin-vendor-withdraw-datatables')); ?>',
               columns: [
                        { data: 'email', name: 'email' },
                        { data: 'phone', name: 'phone' },
                        { data: 'amount', name: 'amount' },
                        { data: 'method', name: 'method' },
                        { data: 'created_at', name: 'created_at' },
                        { data: 'status', name: 'status' },
                        { data: 'action', searchable: false, orderable: false }
                     ],
               language : {
                    processing: '<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>">'
                }
            });
                           
        $('#status-modal').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

        $('#status-modal1').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });

})(jQuery);

    </script>


    
<?php $__env->stopSection(); ?>   
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/vendor/withdraws.blade.php ENDPATH**/ ?>