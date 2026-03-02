<?php if(count($prods) > 0): ?>
<div class="col-lg-12">
   <?php echo $__env->make('partials.product.product-different-view', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php else: ?>
<div class="col-lg-12">
   <div class="page-center">
      <h4 class="text-center"><?php echo e(__('No Product Found.')); ?></h4>
   </div>
</div>
<?php endif; ?>
<script>
  
    $('[data-toggle="tooltip"]').tooltip({});
   
    $('[rel-toggle="tooltip"]').tooltip();
   
    $('[data-toggle="tooltip"]').on('click', function () {
      $(this).tooltip('hide');
    })
   
    $('[rel-toggle="tooltip"]').on('click', function () {
      $(this).tooltip('hide');
    })
   
   // Tooltip Section Ends
</script><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/frontend/ajax/category.blade.php ENDPATH**/ ?>