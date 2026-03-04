<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white"><?php echo e(__('Blog')); ?></h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a></li>
                  <li class="breadcrumb-item active" aria-current="page"><?php echo e(__('Blog')); ?></li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!--==================== Blog Section Start ====================-->
<div class="full-row">
   <div class="container">
      <div id="ajaxContent">
         <div class="row">
            <div class="col-lg-4 md-mb-50">
               <div id="sidebar" class="sidebar-blog bg-light p-30">
                  <div class="widget border-0 py-0 search-widget">
                     <form action="<?php echo e(route('front.blogsearch')); ?>" method="GET">
                        <input type="text" class="form-control bg-light" name="search" placeholder="<?php echo e(__('Search Here')); ?>" value="<?php echo e(isset($_GET['search']) ? $_GET['search'] : ''); ?>" required>
                        <button type="submit" name="submit" ><i class="flaticon-search flat-mini text-red"></i></button>
                     </form>
                  </div>
                  <div class="widget border-0 py-0 widget_categories">
                     <h4 class="widget-title down-line"><?php echo e(__('Categories')); ?></h4>
                     <ul>
                        <?php $__currentLoopData = $bcats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a class="<?php echo e(isset($bcat) ? ($bcat->id == $cat->id ? 'active' : '') : ''); ?>" href="<?php echo e(route('front.blogcategory',$cat->slug)); ?>"><?php echo e($cat->name); ?>  (<?php echo e($cat->blogs_count); ?>) </a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     </ul>
                  </div>
                  <div class="widget border-0 py-0 widget_recent_entries">
                     <h4 class="widget-title down-line"><?php echo e(__('Recent Post')); ?></h4>
                     <ul>
                        <?php $__currentLoopData = App\Models\Blog::latest()->limit(4)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                           <a href="<?php echo e(route('front.blogshow',$blog->slug)); ?>"><?php echo e(mb_strlen($blog->title,'UTF-8') > 45 ? mb_substr($blog->title,0,45,'UTF-8')."..":$blog->title); ?></a>
                           <span class="post-date"><?php echo e(date('M d - Y',(strtotime($blog->created_at)))); ?></span>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                     </ul>
                  </div>
                  <div class="widget border-0 py-0 widget_tag_cloud">
                     <h4 class="widget-title down-line"><?php echo e(__('Tags')); ?></h4>
                     <div class="tagcloud">
                        <ul>
                           <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                           <?php if(!empty($tag)): ?>
                           <li>
                              <a class="<?php echo e(isset($slug) ? ($slug == $tag ? 'active' : '') : ''); ?>" href="<?php echo e(route('front.blogtags',$tag)); ?>">
                              <?php echo e($tag); ?>

                              </a>
                           </li>
                           <?php endif; ?>
                           <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
            <div class="col-lg-8">
               <div class="row">
                  <?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <div class="col-md-12">
                     <div class="thumb-blog-horizontal clearfix hover-img-zoom transation mb-30">
                        <div class="post-image overflow-hidden">
                           <img src="<?php echo e($blog->photo ? asset('assets/images/blogs/'.$blog->photo):asset('assets/images/noimage.png')); ?>" alt="Image not found!">
                        </div>
                        <div class="post-content ps-3">
                           <div class="post-meta font-mini text-uppercase list-color-light mb-1">
                           </div>
                           <h4 class="mb-2"><a href="<?php echo e(route('front.blogshow',$blog->slug)); ?>" class="transation text-dark hover-text-primary d-block"><?php echo e(mb_strlen($blog->title,'UTF-8') > 45 ? mb_substr($blog->title,0,45,'UTF-8')."..":$blog->title); ?></a></h4>
                           <p><?php echo mb_strlen($blog->details,'UTF-8') > 200 ? mb_substr($blog->details,0,200,'UTF-8')."..":$blog->details; ?></p>
                           <div class="date font-small text-uppercase"><span><?php echo e(date('M d - Y',(strtotime($blog->created_at)))); ?></span></div>
                        </div>
                     </div>
                  </div>
               </div>
               <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
               <div class="col-lg-12 mt-3">
                  <div class="d-flex justify-content-center align-items-center pt-3" id="custom-pagination">
                     <div class="pagination-style-one">
                        <nav aria-label="Page navigation example">
                           <ul class="pagination">
                              <?php echo e($blogs->links()); ?>

                           </ul>
                        </nav>
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
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Shilley Pc\fabilive\resources\views/frontend/blog.blade.php ENDPATH**/ ?>