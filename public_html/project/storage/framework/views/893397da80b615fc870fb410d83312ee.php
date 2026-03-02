<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/front/css/datatables.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

 <!-- breadcrumb -->
 <div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white"><?php echo e(__('Dispute')); ?></h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Dispute')); ?></li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- breadcrumb -->



<!--==================== Blog Section Start ====================-->
<div class="full-row">
    <div class="container">
        <div class="mb-4 d-xl-none">
            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
        <div class="row">
            <div class="col-xl-4">
                <?php echo $__env->make('partials.user.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="col-xl-8">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-40 widget_categories bg-light account-info">

                            <h4 class="widget-title down-line mb-30"><?php echo e(__('Dispute')); ?>

                                <a data-bs-toggle="modal" data-bs-target="#vendorform" class="mybtn1" href="javascript:;"> <i class="fas fa-envelope"></i> <?php echo e(__('Add Dispute')); ?></a>
                            </h4>
                            <div class="mr-table allproduct message-area  mt-4">
								<?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
									<div class="table-responsive">
											<table id="example" class="table" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th><?php echo e(__('Subject')); ?></th>
														<th><?php echo e(__('Message')); ?></th>
														<th><?php echo e(__('Time')); ?></th>
														<th><?php echo e(__('Actions')); ?></th>
													</tr>
												</thead>
												<tbody>
                        <?php $__currentLoopData = $convs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                          <tr class="conv">
                            <input type="hidden" value="<?php echo e($conv->id); ?>">
                            <td class="<?php echo e(__('Subject')); ?>">
                              <div>
                                <?php echo e($conv->subject); ?>

                              </div>
                            </td>
                            <td class="<?php echo e(__('Message')); ?>">
                              <div>
                                <?php echo e($conv->message); ?>

                              </div>
                            </td>

                            <td class="<?php echo e(__('Time')); ?>">
                              <div>
                                <?php echo e($conv->created_at->diffForHumans()); ?>

                              </div>
                            </td>
                            <td class="<?php echo e(__('Actions')); ?>">
                              <div>
                                <a href="<?php echo e(route('user-message-show',$conv->id)); ?>" class="link view mybtn1"><i class="fa fa-eye"></i></a>
                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#confirm-delete" data-href="<?php echo e(route('user-message-delete1',$conv->id)); ?>"class="link remove mybtn1"><i class="fa fa-trash"></i></a>
                              </div>
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
</div>
<!--==================== Blog Section End ====================-->


<div class="message-modal">
    <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="vendorformLabel"><?php echo e(__('Add Dispute')); ?></h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
                        <input type="text" class="input-field form-control border mb-4" id="order" name="order_number" placeholder="<?php echo e(__('Order Number *')); ?>" required="">
                      </li>

                      <li>
                        <input type="text" class="input-field form-control border mb-4" id="subj1" name="subject" placeholder="<?php echo e(__('Subject *')); ?>" required="">
                      </li>
                      <li>
                        <textarea class="input-field textarea form-control border mb-4" name="message" id="msg1" placeholder="<?php echo e(__('Your Message *')); ?>" required=""></textarea>
                      </li>
                    </ul>
                      <input type="hidden"  name="type" value="Dispute">

                    <button class="submit-btn" id="emlsub1" type="submit"><?php echo e(__('Send')); ?></button>
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

  

  <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">

      <div class="modal-header d-block text-center">
          <h4 class="modal-title d-inline-block"><?php echo e(__('Confirm Delete ?')); ?></h4>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
              </button>
      </div>

                  <div class="modal-body">
              <p class="text-center"><?php echo e(__('You are about to delete this Dispute.')); ?></p>
              <p class="text-center"><?php echo e(__('Do you want to proceed?')); ?></p>
                  </div>
                  <div class="modal-footer justify-content-center">
                      <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__('Cancel')); ?></button>
                      <a class="btn btn-danger btn-ok"><?php echo e(__('Delete')); ?></a>
                  </div>
              </div>
          </div>
      </div>

<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>

<script src = "<?php echo e(asset('assets/front/js/dataTables.min.js')); ?>" ></script>
<script src = "<?php echo e(asset('assets/front/js/user.js')); ?>"  ></script>
<script type="text/javascript">
    (function($) {
		"use strict";

          $(document).on("submit", "#emailreply1" , function(){
          var token = $(this).find('input[name=_token]').val();
          var subject = $(this).find('input[name=subject]').val();
          var message =  $(this).find('textarea[name=message]').val();
          var $type  = $(this).find('input[name=type]').val();
          var order = $('#order').val();
          $('#subj1').prop('disabled', true);
          $('#msg1').prop('disabled', true);
          $('#emlsub1').prop('disabled', true);
          $.ajax({
            type: 'post',
            url: "<?php echo e(URL::to('/user/admin/user/send/message')); ?>",
            data: {
                '_token': token,
                'subject'   : subject,
                'message'  : message,
                'type'   : $type,
                'order'  : order
                  },
            success: function( data) {

            $('#subj1').prop('disabled', false);
            $('#msg1').prop('disabled', false);
            $('#subj1').val('');
            $('#msg1').val('');
            $('#emlsub1').prop('disabled', false);
            if(data.success == false)
              toastr.error(data.message);
            else
            toastr.success(data.message);
              $('#vendorform').modal('hide');
            }
        });
          return false;
        });

        })(jQuery);

</script>
<script type="text/javascript">

(function($) {
		"use strict";

      $('#confirm-delete').on('show.bs.modal', function(e) {
          $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));

      });

})(jQuery);

</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/dispute/index.blade.php ENDPATH**/ ?>