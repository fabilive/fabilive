<?php $__env->startSection('content'); ?>
<?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url(<?php echo e($gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png')); ?>); background-position: center center; background-size: cover;">
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
<!-- breadcrumb -->

<!--==================== Blog Section Start ====================-->
<div class="full-row">
   <div class="container">
      <div class="row">
         <div class="col-lg-4 md-mb-50 order-lg-2">
            <div id="sidebar" class="sidebar-blog bg-light p-30">
               <div class="widget border-0 py-0 search-widget">
                  <form action="#" method="post">
                     <input type="text" class="form-control bg-light" name="search" placeholder="Search">
                     <button type="submit" name="submit" class="bg-light"><i
                           class="flaticon-search flat-mini text-secondary"></i></button>
                  </form>
               </div>
               <div class="widget border-0 py-0 widget_categories">
                  <h4 class="widget-title down-line"><?php echo e(__('Categories')); ?></h4>
                  <ul>
                     <?php $__currentLoopData = $bcats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <li><a class="<?php echo e(isset($bcat) ? ($bcat->id == $cat->id ? 'active' : '') : ''); ?>"
                           href="<?php echo e(route('front.blogcategory',$cat->slug)); ?>"><?php echo e($cat->name); ?> (<?php echo e($cat->blogs_count); ?>) </a></li>
                     <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </ul>
               </div>
               <div class="widget border-0 py-0 widget_recent_entries">
                  <h4 class="widget-title down-line"><?php echo e(__('Recent Post')); ?></h4>
                  <ul>
                     <?php $__currentLoopData = App\Models\Blog::latest()->limit(4)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reblog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                     <li>
                        <a href="<?php echo e(route('front.blogshow',$reblog->slug)); ?>"><?php echo e(mb_strlen($reblog->title,'UTF-8') > 45
                           ? mb_substr($reblog->title,0,45,'UTF-8')."..":$reblog->title); ?></a>
                        <span class="post-date"><?php echo e(date('M d - Y',(strtotime($reblog->created_at)))); ?></span>
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
                           <a class="<?php echo e(isset($slug) ? ($slug == $tag ? 'active' : '') : ''); ?>"
                              href="<?php echo e(route('front.blogtags',$tag)); ?>">
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
         <div class="col-lg-8 order-lg-1">
            <div class="single-post">
               <div class="single-post-title">
                  <h3 class="mb-2 text-secondary"><?php echo e($blog->title); ?></h3>
                  <div class="post-meta mb-4">
                     <a href="#"><i class="flaticon-user-silhouette flat-mini"></i> <span><?php echo e(__('By Admin')); ?></span></a>
                     <a href="#"><i class="flaticon-calendar flat-mini"></i> <span><?php echo e(date('M d -
                           Y',(strtotime($blog->created_at)))); ?></span></a>
                     <a href="#"><i class="flaticon-like flat-mini"></i> <span><?php echo e($blog->views); ?> <?php echo e(__('View(s)')); ?></span></a>
                     <span><i class="flaticon-document flat-mini text-primary"></i> <a href="#"><span><?php echo e(__('Source')); ?>

                              : </span></a><a href="#"><span><?php echo e($blog->source); ?></span></a></span>
                  </div>
               </div>
               <div class="img">
                  <img src="<?php echo e(asset('assets/images/blogs/'.$blog->photo)); ?>" class="img-fluid" alt="">
               </div>
               <div class="post-content pt-4 mb-5">
                  <p><?php echo clean($blog->details , array('Attr.EnableID' => true)); ?></p>
               </div>
               <div class="share-post mt-5">
                  <span><b><?php echo e(__('Share This Post:')); ?></b></span>

                  <a class="a2a_dd plus" href="https://www.addtoany.com/share">
                     <i class="fas fa-plus"></i>
                  </a>
               </div>
               <script async src="https://static.addtoany.com/menu/page.js"></script>
               
               <?php if($gs->is_disqus == 1): ?>
               <div class="comments">
                  <div id="disqus_thread">
                     <script>
                        (function() {
                        var d = document, s = d.createElement('script');
                        s.src = 'https://<?php echo e($gs->disqus); ?>.disqus.com/embed.js';
                        s.setAttribute('data-timestamp', +new Date());
                        (d.head || d.body).appendChild(s);
                        })();
                     </script>
                     <noscript><?php echo e(__('Please enable JavaScript to view the')); ?> <a
                           href="https://disqus.com/?ref_noscript"><?php echo e(__('comments powered by Disqus.')); ?></a></noscript>
                  </div>
               </div>
               <?php endif; ?>
               
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Blog Section End ====================-->
<?php if ($__env->exists('partials.global.common-footer')) echo $__env->make('partials.global.common-footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Shilley Pc\fabilive\resources\views/frontend/blogshow.blade.php ENDPATH**/ ?>