<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Edit Profile')); ?></h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('rider-dashboard')); ?>"><?php echo e(__('Dashboard')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Edit Profile')); ?></li>
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
         <div class="col-xl-3">
            <?php echo $__env->make('partials.rider.dashboard-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         </div>
         <div class="col-xl-9">
            <div class="row">
               <div class="col-lg-12">
                  <div class="widget border-0 p-40 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30"><?php echo e(__('Edit Profile')); ?>

                     </h4>
                     <div class="edit-info-area">
                        <div class="body">
                           <div class="edit-info-area-form">
                              <div class="gocover"
                                 style="background: url(<?php echo e(asset('assets/images/'.$gs->loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                              </div>
                              <form id="userform" action="<?php echo e(route('rider-profile-update')); ?>" method="POST"
                                 enctype="multipart/form-data">
                                 <?php echo csrf_field(); ?>
                                 <div class="upload-img">
                                    <?php if($user->is_provider == 1): ?>
                                    <div class="img"><img
                                          src="<?php echo e($user->photo ? asset($user->photo):asset('assets/images/'.$gs->user_image)); ?>">
                                    </div>
                                    <?php else: ?>
                                    <div class="img"><img
                                          src="<?php echo e($user->photo ? asset('assets/images/users/'.$user->photo):asset('assets/images/'.$gs->user_image)); ?>">
                                    </div>
                                    <?php endif; ?>
                                    <?php if($user->is_provider != 1): ?>
                                    <div class="file-upload-area">
                                       <div class="upload-file">
                                          <label><?php echo e(__('Upload')); ?>

                                             <input type="file" size="60" name="photo" class="upload form-control">
                                          </label>
                                       </div>
                                    </div>
                                    <?php endif; ?>
                                 </div>
                                 <div class="row mb-4">
                                    <div class="col-lg-6">
                                       <input name="name" type="text" class="input-field form-control border"
                                          placeholder="<?php echo e(__('Rider Name')); ?>" required="" value="<?php echo e($user->name); ?>">
                                    </div>
                                    <div class="col-lg-6">
                                       <input name="email" type="email" class="input-field form-control border"
                                          placeholder="<?php echo e(__('Email Address')); ?>" required="" value="<?php echo e($user->email); ?>"
                                          disabled>
                                    </div>
                                 </div>
                                 <div class="row mb-4">
                                    <div class="col-lg-6">
                                       <input name="phone" type="text" class="input-field form-control border"
                                          placeholder="<?php echo e(__('Phone Number')); ?>" required="" value="<?php echo e($user->phone); ?>">
                                    </div>
                                    <div class="col-lg-6">
                                       <input name="fax" type="text" class="input-field form-control border"
                                          placeholder="<?php echo e(__('Fax')); ?>" value="<?php echo e($user->fax); ?>">
                                    </div>
                                 </div>
                                 <div class="row mb-4">
                                    <div class="col-lg-6">
                                       <select class="input-field form-control border" name="country"
                                          id="select_country">
                                          <?php echo $__env->make('includes.countries', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                       </select>
                                    </div>

                                    <div class="col-lg-6">
                                       <select class="input-field form-control border" name="state_id" id="show_state">

                                          <?php if($user->country): ?>
                                          <?php
                                          $country = App\Models\Country::where('country_name',$user->country)->first();
                                          $states =
                                          App\Models\State::whereCountryId($country->id)->whereStatus(1)->get();
                                          ?>
                                          <?php $__currentLoopData = $states; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <option value="<?php echo e($state->id); ?>" <?php echo e($user->state_id == $state->id ? 'selected' :
                                             ''); ?>><?php echo e($state->state); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                          <?php else: ?>
                                          <option value=""><?php echo app('translator')->get('Select State'); ?></option>
                                          <?php endif; ?>
                                       </select>
                                    </div>
                                 </div>

                                 <div class="row mb-4">
                                    <div class="col-lg-6">
                                       <select class="input-field form-control border" name="city_id" id="show_city">
                                          <?php if($user->state_id): ?>
                                          <?php
                                          $cities =
                                          App\Models\City::whereStateId($user->state_id)->whereStatus(1)->get();
                                          ?>
                                          <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                          <option value="<?php echo e($city->id); ?>" <?php echo e($user->city_id == $city->id ? 'selected' :
                                             ''); ?>><?php echo e($city->city_name); ?></option>
                                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                          <?php else: ?>
                                          <option value=""><?php echo app('translator')->get('Select City'); ?></option>
                                          <?php endif; ?>
                                       </select>
                                    </div>

                                    <div class="col-lg-6">
                                       <input name="zip" type="text" class="input-field form-control border"
                                          placeholder="<?php echo e(__('Zip')); ?>" value="<?php echo e($user->zip); ?>">
                                    </div>
                                 </div>


                                 <div class="row mb-4">
                                    <div class="col-lg-12">
                                       <textarea class="input-field form-control border" name="address"
                                          placeholder="<?php echo e(__('Address')); ?>" cols="30" rows="10"
                                          required><?php echo e($user->address); ?></textarea>
                                    </div>
                                 </div>
                                 <div class="form-links">
                                    <button class="submit-btn btn btn-primary" type="submit"><?php echo e(__('Save')); ?></button>
                                 </div>
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
</div>

<!--==================== Blog Section End ====================-->

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header d-block text-center">
            <h4 class="modal-title d-inline-block"><?php echo e(__('License Key')); ?></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p class="text-center"><?php echo e(__('The Licenes Key is :')); ?> <span id="key"></span></p>
         </div>
         <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo e(__('Close')); ?></button>
         </div>
      </div>
   </div>
</div>
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<script>
   $(document).on('change','#select_country',function(){
      let state_url = $('option:selected', this).attr('data-href');
      $.get(state_url,function(response){
         $('#show_state').html(response.data);
      });
   });

   $(document).on('change','#show_state',function(){
   		let state_id = $(this).val();
         $.get("<?php echo e(route('state.wise.city')); ?>",{state_id:state_id},function(data){
            $('#show_city').html(data.data);
         });
   	});




</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/rider/profile.blade.php ENDPATH**/ ?>