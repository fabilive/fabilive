<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5 mb-4" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Compare')); ?></h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="#"><?php echo e(__('Home')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Compare')); ?></li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<!-- Compare Area Start -->
<section class="compare-page">
   <div class="container">
      <?php if(Session::has('compare')): ?>
      <div class="row">
         <div class="col-lg-12">
            <div class="content">
               <div class="com-heading">
                  <h2 class="title">
                     <?php echo e(__('Product Compare')); ?>

                  </h2>
               </div>
               <div class="compare-page-content-wrap">
                  <div class="compare-table table-responsive">
                     <table class="table table-bordered mb-0">
                        <tbody>
                           <tr>
                              <td class="first-column top"><?php echo e(__('Product Name')); ?></td>
                              <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <td class="product-image-title c<?php echo e($product['item']['id']); ?>">
                                 <img class="img-fluid" src="<?php echo e($product['item']['thumbnail'] ? asset('assets/images/thumbnails/'.$product['item']['thumbnail']):asset('assets/images/noimage.png')); ?>" alt="Compare product['item']">
                                 <a href="<?php echo e(route('front.product', $product['item']['slug'])); ?>">
                                    <h4 class="title">
                                       <?php echo e($product['item']['name']); ?>

                                    </h4>
                                 </a>
                              </td>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </tr>
                           <tr>
                              <td class="first-column"><?php echo e(__('Price')); ?></td>
                              <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <td class="pro-price c<?php echo e($product['item']['id']); ?>"><?php echo e(App\Models\Product::find($product['item']['id'])->showPrice()); ?></td>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </tr>
                           <tr>
                              <td class="first-column"><?php echo e(__('Rating')); ?></td>
                              <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <td class="pro-ratting c<?php echo e($product['item']['id']); ?>">
                                 <div class="ratings">
                                    <div class="empty-stars"></div>
                                    <div class="full-stars" style="width:<?php echo e(App\Models\Rating::ratings($product['item']['id'])); ?>%"></div>
                                 </div>
                              </td>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </tr>
                           <tr>
                              <td class="first-column"><?php echo e(__('Description')); ?></td>
                              <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <td class="pro-desc c<?php echo e($product['item']['id']); ?>">
                                 <p><?php echo e(strip_tags($product['item']['details'])); ?></p>
                              </td>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </tr>
                           <tr>
                              <td class="first-column"><?php echo e(__('Add To Cart')); ?></td>
                              <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <td class="c<?php echo e($product['item']['id']); ?>">
                                 <?php if($product['item']['product_type'] == "affiliate"): ?>
                                 <a href="<?php echo e($product['item']['affiliate_link']); ?>" class="btn__bg"><?php echo e(__('Buy Now')); ?></a>
                                 <?php else: ?>
                                 <li class="addtocart">
                                    <a href="javascript:;" class="add-cart"  data-href="<?php echo e(route('product.cart.add',$product['item']['id'])); ?>"><?php echo e(__('Add to Cart')); ?></a>
                                 </li>
                                 <li class="addtocart">
                                    <a id="qaddcrt" class="add-to-cart-quick" href="javascript:;" data-href="<?php echo e(route('product.cart.quickadd',$product['item']['id'])); ?>">
                                    <?php echo e(__('Buy Now')); ?>

                                    </a>
                                 </li>
                                 <?php endif; ?>
                              </td>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </tr>
                           <tr>
                              <td class="first-column"><?php echo e(__('Remove')); ?></td>
                              <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                              <td class="pro-remove c<?php echo e($product['item']['id']); ?>">
                                 <i class="far fa-trash-alt compare-remove" data-href="<?php echo e(route('product.compare.remove',$product['item']['id'])); ?>" data-class="c<?php echo e($product['item']['id']); ?>"></i>
                              </td>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                           </tr>
                        </tbody>
                     </table>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <?php else: ?>
      <div class="row mb-2">
         <div class="col-lg-12">
            <div class="content">
               <div class="com-heading ">
                  <h2 class="title p-5 text-center text-center border ">
                     <?php echo e(__('No Product To Compare.')); ?>

                  </h2>
               </div>
            </div>
         </div>
      </div>
      <?php endif; ?>
   </div>
</section>
<!-- Compare Area End -->
<?php echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/frontend/compare.blade.php ENDPATH**/ ?>