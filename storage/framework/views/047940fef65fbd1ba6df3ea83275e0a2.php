<div class="products-header d-flex justify-content-between align-items-center py-10 px-20 bg-light md-mt-30">
    <div class="products-header-left d-flex align-items-center">
       <h6 class="woocommerce-products-header__title page-title"> <strong> <?php echo e(__('Products')); ?></strong>  </h6>
       <div class="woocommerce-result-count"></div> 
    </div>
    <div class="products-header-right">
       <form class="woocommerce-ordering" method="get">
          <select name="sort" class="orderby short-item" aria-label="Shop order" id="sortby">
             <option value="date_desc"><?php echo e(__('Latest Product')); ?></option>
             <option value="date_asc"><?php echo e(__('Oldest Product')); ?></option>
             <option value="price_asc"><?php echo e(__('Lowest Price')); ?></option>
             <option value="price_desc"><?php echo e(__('Highest Price')); ?></option>
          </select>
          <?php if($gs->product_page != null): ?>
          <select id="pageby" name="pageby" class="short-itemby-no">
             <?php $__currentLoopData = explode(',',$gs->product_page); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
             <option value="<?php echo e($element); ?>"><?php echo e($element); ?></option>
             <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </select>
          <?php else: ?>
          <input type="hidden" id="pageby" name="paged" value="<?php echo e($gs->page_count); ?>">
          <input type="hidden" name="shop-page-layout" value="left-sidebar">
          <?php endif; ?>
       </form>
       <div class="products-view">
          <a  class="grid-view check_view" data-shopview="grid-view" href="javascript:;"><i class="flaticon-menu-1 flat-mini"></i></a>
          <a class="list-view check_view" data-shopview="list-view" href="javascript:;"><i class="flaticon-list flat-mini"></i></a>
       </div>
    </div>
 </div>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/frontend/category.blade.php ENDPATH**/ ?>