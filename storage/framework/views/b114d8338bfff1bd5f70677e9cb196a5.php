<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(asset('assets/front/css/datatables.css')); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
  style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
  <div class="container">
    <div class="row text-center text-white">
      <div class="col-12">
        <h3 class="mb-2 text-white"><?php echo e(__('Messages')); ?>


        </h3>
      </div>
      <div class="col-12">
        <nav aria-label="breadcrumb">
          <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
            <li class="breadcrumb-item"><a href="<?php echo e(route('user-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Messages')); ?></li>
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
        <?php if(Session::has('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <strong><?php echo e(__('Success')); ?></strong> <?php echo e(Session::get('success')); ?>

          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <?php endif; ?>
        <div class="row">
          <div class="col-lg-12">
            <div class="widget border-0 p-40 widget_categories bg-light account-info">

              <h4 class="widget-title down-line mb-30"><?php echo e(__('Messages')); ?>

                <a data-bs-toggle="modal" data-bs-target="#vendorform" class="mybtn1 ml-3" href="javascript:;"> <i
                    class="fas fa-envelope"></i>
                  <?php echo e(__('Compose Message')); ?>

                </a>
              </h4>
              <div class="mr-table allproduct message-area  mt-4">
                <?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <div class="table-responsive">
                  <table id="example" class="table" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th><?php echo e(__('Name')); ?></th>
                        <th><?php echo e(__('Message')); ?></th>
                        <th><?php echo e(__('Sent')); ?></th>
                        <th><?php echo e(__('Actions')); ?></th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php $__currentLoopData = $convs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conv): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                      <tr class="conv">

                        <input type="hidden" value="<?php echo e($conv->id); ?>">
                        <?php if($user->id == $conv->sent->id): ?>
                        <td data-label="<?php echo e(__('Name')); ?>">
                          <div>
                            <?php echo e($conv->recieved->name); ?>

                          </div>
                        </td>
                        <?php else: ?>
                        <td data-label="<?php echo e(__('Name')); ?>">
                          <div>
                            <?php echo e($conv->sent->name); ?>

                          </div>
                        </td>
                        <?php endif; ?>
                        <td data-label="<?php echo e(__('Message')); ?>">
                          <div>
                            <?php echo e($conv->subject); ?>

                          </div>
                        </td>
                        <td data-label="<?php echo e(__('Sent')); ?>">
                          <div>
                            <?php echo e($conv->created_at->diffForHumans()); ?>

                          </div>
                        </td>
                        <td data-label="<?php echo e(__('Actions')); ?>">
                          <div>
                            <a href="<?php echo e(route('user-message',$conv->id)); ?>" class="link view mybtn1"><i
                                class="fa fa-eye"></i></a>
                            <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#confirm-delete"
                              data-href="<?php echo e(route('user-message-delete',$conv->id)); ?>" class="link remove mybtn1"><i
                                class="fa fa-trash"></i></a>
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


<!--==================== Blog Section End ====================-->


<div class="message-modal">
  <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h5 class="modal-title " id="vendorformLabel"><?php echo e(__('Send Message')); ?></h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
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
                      <input type="hidden" name="vendor_id" value="<?php echo e(auth()->id()); ?>">

                      <div class="msg mb-3">
                        <li>
                          <input type="email" class="input-field form-control border-bottom" id="eml" name="email"
                            placeholder="<?php echo e(__('Email *')); ?>" required="">
                        </li>
                      </div>


                      <div class="msg mb-3">
                        <li>
                          <input type="text" class="input-field form-control border-bottom" id="subj" name="subject"
                            placeholder="<?php echo e(__('Subject *')); ?>" required="">
                        </li>
                      </div>

                      <div class="msg mb-5">
                        <li>
                          <textarea class="input-field textarea form-control border-bottom" name="message" id="msg"
                            placeholder="<?php echo e(__('Your Message *')); ?>" required=""></textarea>
                        </li>
                      </div>


                      <input type="hidden" name="name" value="<?php echo e(Auth::user()->name); ?>">
                      <input type="hidden" name="user_id" value="<?php echo e(Auth::user()->id); ?>">

                    </ul>
                    <button class="submit-btn bg-primary mx-auto
                    " id="emlsub" type="submit"><?php echo e(__('Send Message')); ?></button>
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
        <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p class="text-center"><?php echo e(__('You are about to delete this Conversation.')); ?></p>
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
<script src="<?php echo e(asset('assets/front/js/dataTables.min.js')); ?>" defer></script>
<script src="<?php echo e(asset('assets/front/js/user.js')); ?>" defer></script>
<script type="text/javascript">
  (function($) {
		"use strict";

          $(document).on("submit", "#emailreply" , function(){
          var token = $(this).find('input[name=_token]').val();
          var subject = $(this).find('input[name=subject]').val();
          var message =  $(this).find('textarea[name=message]').val();
          var email = $(this).find('input[name=email]').val();
          var name = $(this).find('input[name=name]').val();
          var user_id = $(this).find('input[name=user_id]').val();
          var vendor_id = $(this).find('input[name=vendor_id]').val();
          $('#eml').prop('disabled', true);
          $('#subj').prop('disabled', true);
          $('#msg').prop('disabled', true);
          $('#emlsub').prop('disabled', true);
     $.ajax({
            type: 'post',
            url: "<?php echo e(URL::to('/user/user/contact')); ?>",
            data: {
                '_token': token,
                'subject'   : subject,
                'message'  : message,
                'email'   : email,
                'name'  : name,
                'user_id'   : user_id,
                'vendor_id'  : vendor_id

                  },
            success: function(data) {
          $('#eml').prop('disabled', false);
          $('#subj').prop('disabled', false);
          $('#msg').prop('disabled', false);
          $('#subj').val('');
          $('#msg').val('');
          $('#emlsub').prop('disabled', false);
        if(data.error)
          toastr.error(data.message);
        else
          toastr.success("Message Sent");
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
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/user/message/index.blade.php ENDPATH**/ ?>