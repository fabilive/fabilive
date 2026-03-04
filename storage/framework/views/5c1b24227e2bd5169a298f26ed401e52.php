

<?php $__env->startSection('content'); ?>
<input type="hidden" id="headerdata" value="<?php echo e(__(" VENDOR")); ?>">
<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="heading"><?php echo e(__("Vendors")); ?></h4>
				<ul class="links">
					<li>
						<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__("Dashboard")); ?> </a>
					</li>
					<li>
						<a href="javascript:;"><?php echo e(__("Vendors")); ?></a>
					</li>
					<li>
						<a href="<?php echo e(route('admin-vendor-index')); ?>"><?php echo e(__("Vendors List")); ?></a>
					</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="product-area">
		<div class="row">
			<div class="col-lg-12">

				<div class="heading-area">
					<h4 class="title">
						<?php echo e(__("Vendor Registration")); ?> :
					</h4>
					<div class="action-list">
						<select
							class="process select1 vdroplinks <?php echo e($gs->reg_vendor == 1 ? 'drop-success' : 'drop-danger'); ?>">
							<option data-val="1" value="<?php echo e(route('admin-gs-status',['reg_vendor',1])); ?>" <?php echo e($gs->
								reg_vendor == 1 ? 'selected' : ''); ?>><?php echo e(__("Activated")); ?></option>
							<option data-val="0" value="<?php echo e(route('admin-gs-status',['reg_vendor',0])); ?>" <?php echo e($gs->
								reg_vendor == 0 ? 'selected' : ''); ?>><?php echo e(__("Deactivated")); ?></option>
						</select>
					</div>
				</div>


				<div class="mr-table allproduct">
					<?php echo $__env->make('alerts.admin.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<?php echo $__env->make('alerts.form-success', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
					<div class="table-responsive">
						<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th><?php echo e(__("Store Name")); ?></th>
									<th><?php echo e(__("Vendor Email")); ?></th>
									<th><?php echo e(__("Shop Number")); ?></th>
									<th><?php echo e(__("Pending Commission")); ?></th>
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
				<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>" alt="">
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






<div class="modal fade" id="verify-modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">

	<div class="modal-dialog modal-dialog-centered" role="document">
		<div class="modal-content">
			<div class="submit-loader">
				<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>" alt="">
			</div>
			<div class="modal-header">
				<h5 class="modal-title"><?php echo e(__('ASK FOR VERIFICATION')); ?></h5>
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





<div class="modal fade" id="ad-subscription-modal" tabindex="-1" role="dialog" aria-labelledby="modal1"
	aria-hidden="true">

	<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
		<div class="modal-content">
			<div class="submit-loader">
				<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>" alt="">
			</div>
			<div class="modal-header">
				<h5 class="modal-title"><?php echo e(__('ADD SUBSCRIPTION PLAN')); ?></h5>
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







<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header d-block text-center">
				<h4 class="modal-title d-inline-block"><?php echo e(__("Confirm Delete")); ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<p class="text-center"><?php echo e(__("You are about to delete this Vendor. Every informtation under this vendor
					will be deleted.")); ?></p>
				<p class="text-center"><?php echo e(__("Do you want to proceed?")); ?></p>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__("Cancel")); ?></button>
				<form action="" class="d-inline delete-form" method="POST">
					<input type="hidden" name="_method" value="delete" />
					<input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
					<button type="submit" class="btn btn-danger"><?php echo e(__('Delete')); ?></button>
				</form>
			</div>

		</div>
	</div>
</div>






<div class="modal fade" id="status-modal" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header d-block text-center">
				<h4 class="modal-title d-inline-block"><?php echo e(__("Update Status")); ?></h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				<p class="text-center"><?php echo e(__("You are about to change the status.")); ?></p>
				<p class="text-center"><?php echo e(__("Do you want to proceed?")); ?></p>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-default" data-dismiss="modal"><?php echo e(__("Cancel")); ?></button>
				<a class="btn btn-success btn-ok"><?php echo e(__("Update")); ?></a>
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
												<input type="email" class="input-field eml-val" id="eml1" name="to"
													placeholder="<?php echo e(__(" Email")); ?> *" value="" required="">
											</li>
											<li>
												<input type="text" class="input-field" id="subj1" name="subject"
													placeholder="<?php echo e(__(" Subject")); ?> *" required="">
											</li>
											<li>
												<textarea class="input-field textarea" name="message" id="msg1"
													placeholder="<?php echo e(__(" Your Message")); ?> *" required=""></textarea>
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

		var table = $('#geniustable').DataTable({
			   ordering: false,
               processing: true,
               serverSide: true,
               ajax: '<?php echo e(route('admin-vendor-datatables')); ?>',
               columns: [
                        { data: 'shop_name', name: 'shop_name' },
                        { data: 'email', name: 'email' },
                        { data: 'shop_number', name: 'shop_number' },
                        { data: 'admin_commission', name: 'admin_commission' },
                        { data: 'status', searchable: false, orderable: false},
            			{ data: 'action', searchable: false, orderable: false }
                     ],
               language : {
                	processing: '<img src="<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>">'
                },
				drawCallback : function( settings ) {
	    				$('.select').niceSelect();	
				}
            });

	    				$('.select1').niceSelect();	


	})(jQuery);			

</script>


<script type="text/javascript">
	(function($) {
		"use strict";

$(document).on('click','.verify',function(){
if(admin_loader == 1)
  {
  $('.submit-loader').show();
}
  $('#verify-modal .modal-content .modal-body').html('').load($(this).attr('data-href'),function(response, status, xhr){
      if(status == "success")
      {
        if(admin_loader == 1)
          {
            $('.submit-loader').hide();
          }
      }
    });
});


})(jQuery);

</script>


<script type="text/javascript">
	(function($) {
		"use strict";

	$(document).on('click','.add-subs',function(){
	if(admin_loader == 1)
	  {
	  $('.submit-loader').show();
	}
	  $('#ad-subscription-modal .modal-content .modal-body').html('').load($(this).attr('data-href'),function(response, status, xhr){
		  if(status == "success")
		  {
			if(admin_loader == 1)
			  {
				$('.submit-loader').hide();
			  }
		  }
		});
	});


	$(document).on('click','.vendor_commission',function(){
		let status = confirm('Are you sure to release the commission?');
		if(status){
			return true;
		}else{
			return false;
		}
	})
	
	
})(jQuery);

</script>




<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/vendor/index.blade.php ENDPATH**/ ?>