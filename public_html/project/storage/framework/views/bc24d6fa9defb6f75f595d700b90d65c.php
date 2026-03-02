

<?php $__env->startSection('styles'); ?>

<style type="text/css">
.table-responsive {
    overflow-x: hidden;
    overflow-y: hidden;
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
                                        <h4 class="heading"><?php echo e(__("Vendor Details")); ?> <a class="add-btn" href="<?php echo e(url()->previous()); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__("Back")); ?></a></h4>
                                        <ul class="links">
                                            <li>
                                                <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__("Dashboard")); ?> </a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e(route('admin-vendor-index')); ?>"><?php echo e(__("Vendors")); ?></a>
                                            </li>
                                            <li>
                                                <a href="<?php echo e(route('admin-vendor-show',$data->id)); ?>"><?php echo e(__("Details")); ?></a>
                                            </li>
                                        </ul>
                                </div>
                            </div>
                        </div>
                            <div class="add-product-content1 customar-details-area">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="product-description">
                                            <div class="body-area">
                                            <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="user-image">
                                                            <?php if($data->is_provider == 1): ?>
                                                            <img src="<?php echo e($data->photo ? asset($data->photo):asset('assets/images/noimage.png')); ?>" alt="No Image">
                                                            <?php else: ?>
                                                            <img src="<?php echo e($data->photo ? asset('assets/images/users/'.$data->photo):asset('assets/images/noimage.png')); ?>" alt="<?php echo e(__("No Image")); ?>">                                            
                                                            <?php endif; ?>
                                                        <a href="javascript:;" class="mybtn1 send" data-email="<?php echo e($data->email); ?>" data-toggle="modal" data-target="#vendorform"><?php echo e(__("Send Message")); ?></a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="table-responsive show-table">
                                                        <table class="table">
                                                        <tr>
                                                            <th><?php echo e(__("Vendor ID#")); ?></th>
                                                            <td><?php echo e($data->id); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Store Name")); ?></th>
                                                            <td><?php echo e($data->shop_name); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Owner Name")); ?></th>
                                                            <td><?php echo e($data->owner_name); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Email")); ?></th>
                                                            <td><?php echo e($data->email); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Shop Number")); ?></th>
                                                            <td><?php echo e($data->shop_number); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Registration Number")); ?></th>
                                                            <td><?php echo e($data->reg_number); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <th><?php echo e(__("Shop Address")); ?></th>
                                                            <td><?php echo e($data->shop_address); ?></td>
                                                        </tr>
                                                        
                                                        </table>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                    <div class="table-responsive show-table">
                                                        
                                                        
                                                    <table class="table">
                                                        <tr>
                                                            <th><?php echo e(__("Message")); ?></th>
                                                            <td><?php echo e($data->shop_message); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Total Product(s)")); ?></th>
                                                            <td><?php echo e($data->products()->count()); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Joined")); ?></th>
                                                            <td><?php echo e($data->created_at->diffForHumans()); ?></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th><?php echo e(__("National ID Front Image")); ?></th>
                                                            <td><a href="<?php echo e(asset('assets/images/vendorfront/'. $data->national_id_front_image)); ?>" target="_blank">View</a></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("National ID Back Image")); ?></th>
                                                            <td><a href="<?php echo e(asset('assets/images/vendorback/'. $data->national_id_back_image)); ?>" target="_blank">View</a></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("License Image")); ?></th>
                                                            <td><a href="<?php echo e(asset('assets/images/vendorlicense/'. $data->license_image)); ?>" target="_blank">View</a></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("SubMerchant Agreement Image")); ?></th>
                                                            <td><a href="<?php echo e(asset('assets/images/submerchantagreement/'. $data->submerchant_agreement)); ?>" target="_blank">View</a></td>
                                                        </tr>
                                                        
                                                        <tr>
                                                            <th><?php echo e(__("Business Registration Certificate")); ?></th>
                                                            <td><a href="<?php echo e(asset($data->business_registration_certificate)); ?>" target="_blank">View</a></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Tax Payer Card Copy")); ?></th>
                                                            <td><a href="<?php echo e(asset($data->taxpayer_card_copy)); ?>" target="_blank">View</a></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("ID Card Copy")); ?></th>
                                                            <td><a href="<?php echo e(asset($data->id_card_copy)); ?>" target="_blank">View</a></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Passport Copy")); ?></th>
                                                            <td><a href="<?php echo e(asset($data->passport_copy)); ?>" target="_blank">View</a></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Driver License Copy")); ?></th>
                                                            <td><a href="<?php echo e(asset($data->driver_license_copy)); ?>" target="_blank">View</a></td>
                                                        </tr>
                                                        <tr>
                                                            <th><?php echo e(__("Residence Permit For Foreigners")); ?></th>
                                                            <td><a href="<?php echo e(asset($data->residence_permit)); ?>" target="_blank">View</a></td>
                                                        </tr>

                                                        
                                                        <!--<tr>-->
                                                        <!--    <th><?php echo e(__("Business Registration Certificate")); ?></th>-->
                                                        <!--    <td><a href="<?php echo e(asset('assets/uploads/documents/'. $data->business_registration_certificate)); ?>" target="_blank">View</a></td>-->
                                                        <!--</tr>-->
                                                        <!--<tr>-->
                                                        <!--    <th><?php echo e(__("Tax Payer Card Copy")); ?></th>-->
                                                        <!--    <td><a href="<?php echo e(asset('assets/uploads/documents/'. $data->taxpayer_card_copy)); ?>" target="_blank">View</a></td>-->
                                                        <!--</tr>-->
                                                        <!--<tr>-->
                                                        <!--    <th><?php echo e(__("ID Card Copy")); ?></th>-->
                                                        <!--    <td><a href="<?php echo e(asset('assets/uploads/documents/'. $data->id_card_copy)); ?>" target="_blank">View</a></td>-->
                                                        <!--</tr>-->
                                                        <!--<tr>-->
                                                        <!--    <th><?php echo e(__("Passport Copy")); ?></th>-->
                                                        <!--    <td><a href="<?php echo e(asset('assets/uploads/documents/'. $data->passport_copy)); ?>" target="_blank">View</a></td>-->
                                                        <!--</tr>-->
                                                        <!--<tr>-->
                                                        <!--    <th><?php echo e(__("Driver License Copy")); ?></th>-->
                                                        <!--    <td><a href="<?php echo e(asset('assets/uploads/documents/'. $data->driver_license_copy)); ?>" target="_blank">View</a></td>-->
                                                        <!--</tr>-->
                                                        <!--<tr>-->
                                                        <!--    <th><?php echo e(__("Residence Permit For Foreigners")); ?></th>-->
                                                        <!--    <td><a href="<?php echo e(asset('assets/uploads/documents/'. $data->residence_permit)); ?>" target="_blank">View</a></td>-->
                                                        <!--</tr>-->
                                                        
                                                        
                                                        <tr>
                                                            <th width="35%"><?php echo e(__("Shop Details")); ?></th>
                                                            <td><?php echo clean($data->shop_details , array('Attr.EnableID' => true)); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>
                                                                    <?php if($data->checkStatus()): ?>
                                                                    <a class="badge badge-success verify-link" href="javascript:;">Verified</a>
                                                                    <a class="set-gallery1" href="javascript:;" data-toggle="modal" data-target="#setgallery"><input type="hidden" value="<?php echo e($data->verifies()->where('status','=','Verified')->first()->id); ?>">(View)</a>
                                                                    <?php else: ?> 
                                                                    <a class="badge badge-danger verify-link" href="javascript:;">Unverified</a>
                                                                    <?php endif; ?>
                                                            </td>
                                                            <td>
                                                            </td>
                                                        </tr>
                                                        </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="order-table-wrap">
                                                <div class="order-details-table">
                                                    <div class="mr-table">
                                                        <h4 class="title"><?php echo e(__("Products Added")); ?></h4>
                                                        <div class="table-responsive">
                                                                <table id="example2" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <tr>
                                                                            <th><?php echo e(__("Product ID")); ?></th>
                                                                            <th><?php echo e(__("Type")); ?></th>
                                                                            <th><?php echo e(__("Stock")); ?></th>
                                                                            <th><?php echo e(__("Price")); ?></th>
                                                                            <th><?php echo e(__("Status")); ?></th>
                                                                            <th></th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php $__currentLoopData = $data->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <tr>
                                                                        <td><a href="<?php echo e(route('front.product', $dt->slug)); ?>" target="_blank"><?php echo e(sprintf("%'.08d",$dt->id)); ?></a></td>
                                                                            <td><?php echo e($dt->type); ?></td>
                                                                            <?php 
                                                                            $stck = (string)$dt->stock;
                                                                            if($stck == "0")
                                                                            $stck = "Out Of Stock";
                                                                            elseif($stck == null)
                                                                            $stck = "Unlimited";
                                                                            ?>
                                                                            <td><?php echo e($stck); ?></td>
                                                                            <td><?php echo e(\App\Models\Product::convertPrice($dt->price)); ?></td>
                                                                            <td>
                                                                                <div class="action-list">
                                                                                <select class="process select droplinks <?php echo e($dt->status == 1 ? 'drop-success' : 'drop-danger'); ?>">
                                                                                    <option data-val="1" value="<?php echo e(route('admin-prod-status',['id1' => $data->id, 'id2' => 1])); ?>" <?php echo e($dt->status == 1 ? 'selected' : ''); ?>><?php echo e(__("Activated")); ?></option>
                                                                                    <<option data-val="0" value="<?php echo e(route('admin-prod-status',['id1' => $data->id, 'id2' => 0])); ?>" <?php echo e($dt->status == 0 ? 'selected' : ''); ?>><?php echo e(__("Deactivated")); ?></option>
                                                                                </select>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                <a href=" <?php echo e(route('admin-prod-edit',$dt->id)); ?>" class="view-details">
                                                                                    <i class="fas fa-eye"></i><?php echo e(__("Details")); ?>

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






<div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
			<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalCenterTitle"><?php echo e(__('Attachments')); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">



				<div class="gallery-images">
					<div class="selected-image">
						<div class="row">


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
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : false,
      'info'        : false,
      'autoWidth'   : false,
      'responsive'  : true
} );

})(jQuery);

</script>

<script type="text/javascript">
	
	// Gallery Section Update
	
	
    (function($) {
		"use strict";

		$(document).on("click", ".set-gallery1" , function(){
			var pid = $(this).find('input[type=hidden]').val();
			$('#pid').val(pid);
			$('.selected-image .row').html('');
				$.ajax({
						type: "GET",
						url:"<?php echo e(route('admin-vr-show')); ?>",
						data:{id:pid},
						success:function(data){

						  if(data[0] == 0)
						  {
							$('.selected-image .row').addClass('justify-content-center');
							  $('.selected-image .row').html('<h3><?php echo e(__("No Images Found.")); ?></h3>');
						   }
						  else {
							$('.selected-image .row').removeClass('justify-content-center');
							  $('.selected-image .row h3').remove();      
							  var arr = $.map(data[1], function(el) {
							  return el });
	
							  for(var k in arr)
							  {
							$('.selected-image .row').append('<div class="col-sm-6">'+
											'<div class="img gallery-img">'+
												'<a class="img-popup" href="'+'<?php echo e(asset('assets/images/attachments').'/'); ?>'+arr[k]+'">'+
												'<img  src="'+'<?php echo e(asset('assets/images/attachments').'/'); ?>'+arr[k]+'" alt="gallery image">'+
												'</a>'+
											'</div>'+
										  '</div>');
							  }                         
						   }
	 
							$('.img-popup').magnificPopup({
							type: 'image'
						  });
	
						 $(document).off('focusin');
	
						}
	
	
					  });
		  });
	
	// Gallery Section Update Ends	
	
          })(jQuery);

	</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/vendor/show.blade.php ENDPATH**/ ?>