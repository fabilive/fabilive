<?php if(Session::has('order_address')): ?>
<?php
    $user = Session::get('order_address');
?>
<div class="row mt-2">
  <div class="col col-md-4 col-sm-6">
    <label for="name">Name *</label>
    <input type="text" class="form-control" required name="customer_name" id="name" value="<?php echo e($user['customer_name']); ?>" placeholder="Name">
  </div>
  <div class="col col-md-4 col-sm-6">
    <label for="email">Email *</label>
    <input type="text" class="form-control" required name="customer_email" id="email" placeholder="Email" value="<?php echo e($user['customer_email']); ?>">
  </div>
  <div class="col col-md-4 col-sm-6">
    <label for="phone">Phone *</label>
    <input type="text" class="form-control" required name="customer_phone" id="phone" placeholder="Phone" value="<?php echo e($user['customer_phone']); ?>">
  </div>
  <div class="col col-md-12 col-sm-12">
    <label for="customer_address">Address *</label>
    <input type="text" class="form-control" required name="customer_address" id="customer_address" placeholder="Address" value="<?php echo e($user['customer_address']); ?>">
  </div>
</div>
<div class="row">
  <div class="col col-md-6 col-sm-6">
    <label for="customer_country">Country * </label>
    <select type="text" class="form-control" name="customer_country" id="customer_country" required>
      <option value=""><?php echo e(__('Select Country')); ?></option>
      <?php $__currentLoopData = DB::table('countries')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <option value="<?php echo e($data->country_name); ?>"  <?php echo e($user['customer_country'] == $data->country_name ? 'selected' : ''); ?>>
              <?php echo e($data->country_name); ?>

          </option>		
       <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </select>
  </div>
  <div class="col col-md-6 col-sm-6">
    <label for="customer_city">City</label>
    <input type="text" class="form-control" name="customer_city" id="customer_city" placeholder="City" value="<?php echo e($user['customer_city']); ?>">
  </div>
  <div class="col col-md-6 col-sm-6">
    <label for="customer_state">State</label>
    <input type="text" class="form-control" name="customer_state" id="customer_state" placeholder="State" value="<?php echo e($user['customer_state']); ?>">
  </div>
 
  <div class="col col-md-6 col-sm-6">
    <label for="post_code">Postal Code</label>
    <input type="text" class="form-control" name="customer_zip" id="post_code" placeholder="Postal Code" value="<?php echo e($user['customer_zip']); ?>">
  </div>

</div>

<?php else: ?>  




<?php
    $isUser = isset($isUser) ? $isUser : false;
   
?>
<?php if($isUser == 1): ?>
  <div class="row mt-2">
    <div class="col col-md-4 col-sm-6">
      <label for="name">Name *</label>
      <input type="text" class="form-control" required name="customer_name" id="name" value="<?php echo e($user['name']); ?>" placeholder="Name">
    </div>
    <div class="col col-md-4 col-sm-6">
      <label for="email">Email *</label>
      <input type="text" class="form-control" required name="customer_email" id="email" placeholder="Email" value="<?php echo e($user['email']); ?>">
    </div>
    <div class="col col-md-4 col-sm-6">
      <label for="phone">Phone *</label>
      <input type="text" class="form-control" required name="customer_phone" id="phone" placeholder="Phone" value="<?php echo e($user['phone']); ?>">
    </div>
    <div class="col col-md-12 col-sm-12">
      <label for="customer_address">Address *</label>
      <input type="text" class="form-control" required name="customer_address" id="customer_address" placeholder="Address" value="<?php echo e($user['address']); ?>">
    </div>
  </div>
  <div class="row">
    <div class="col col-md-6 col-sm-6">
      <label for="customer_country">Country * </label>
      <select type="text" class="form-control" name="customer_country" id="customer_country" required>
        <option value=""><?php echo e(__('Select Country')); ?></option>
        <?php $__currentLoopData = DB::table('countries')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($data->country_name); ?>"  <?php echo e($user['country'] == $data->country_name ? 'selected' : ''); ?>>
                <?php echo e($data->country_name); ?>

            </option>		
         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    </div>
    <div class="col col-md-6 col-sm-6">
      <label for="customer_city">City</label>
      <input type="text" class="form-control" name="customer_city" id="customer_city" placeholder="City" value="<?php echo e($user['city']); ?>">
    </div>
    <div class="col col-md-6 col-sm-6">
      <label for="customer_state">State</label>
      <input type="text" class="form-control" name="customer_state" id="customer_state" placeholder="State" value="<?php echo e($user['state']); ?>">
    </div>
   
    <div class="col col-md-6 col-sm-6">
      <label for="post_code">Postal Code</label>
      <input type="text" class="form-control" name="customer_zip" id="post_code" placeholder="Postal Code" value="<?php echo e($user['zip']); ?>">
    </div>
 
  </div>
<?php else: ?>

  <div class="row mt-2">
    <div class="col col-md-4 col-sm-6">
      <label for="name">Name *</label>
      <input type="text" class="form-control" required name="customer_name" id="name" placeholder="Name">
    </div>
    <div class="col col-md-4 col-sm-6">
      <label for="email">Email *</label>
      <input type="text" class="form-control" required name="customer_email" id="email" placeholder="Email">
    </div>
    <div class="col col-md-4 col-sm-6">
      <label for="phone">Phone *</label>
      <input type="text" class="form-control" required name="customer_phone" id="phone" placeholder="Email">
    </div>
    <div class="col col-md-12 col-sm-12">
      <label for="customer_address">Address *</label>
      <input type="text" class="form-control" required name="customer_address" id="customer_address" placeholder="Address">
    </div>
  </div>
  <div class="row">
    <div class="col col-md-6 col-sm-6">
      <label for="customer_country">Country * </label>
      <select  class="form-control" name="customer_country" id="customer_country" required>
        <option value=""><?php echo e(__('Select Country')); ?></option>
        <?php $__currentLoopData = DB::table('countries')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <option value="<?php echo e($data->country_name); ?>" >
                <?php echo e($data->country_name); ?>

            </option>		
         <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </select>
    </div>
    <div class="col col-md-6 col-sm-6">
      <label for="customer_city">City</label>
      <input type="text" class="form-control" name="customer_city" id="customer_city" placeholder="City">
    </div>
    <div class="col col-md-6 col-sm-6">
      <label for="customer_state">State</label>
      <input type="text" class="form-control" name="customer_state" id="customer_state" placeholder="State">
    </div>
   
    <div class="col col-md-6 col-sm-6">
      <label for="post_code">Postal Code</label>
      <input type="text" class="form-control" name="customer_zip" id="post_code" placeholder="Postal Code">
    </div>
  </div>
<?php endif; ?>
    
<?php endif; ?>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/create/address_form.blade.php ENDPATH**/ ?>