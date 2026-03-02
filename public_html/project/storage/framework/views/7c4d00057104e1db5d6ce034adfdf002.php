<?php
$categories = App\Models\Category::with('subs')->where('status',1)->get();
$pages = App\Models\Page::get();
?>
<?php if($ps->newsletter==1): ?>
    <!--==================== Newsleter Section Start ====================-->
    <div class="full-row bg-dark py-30">
        <div class="container">
            <div class="row mx-auto">
                <div class="col-lg-5 col-md-6 mx-auto">
                    <div class="d-flex align-items-center h-100">
                        <h4 class="text-white mb-0 text-uppercase"><?php echo e(__('Sign up to newslatter')); ?>  </h4>
                    </div>
                </div>

                <div class="col-lg-5 col-md-12">
                    <form action="<?php echo e(route('front.subscribe')); ?>" class="subscribe-form subscribeform  position-relative md-mt-20" method="POST">
                        <?php echo csrf_field(); ?>
                        <input class="form-control rounded-pill mb-0" type="text" placeholder="Enter your email" aria-label="Address" name="email">
                        <button type="submit" class="btn btn-secondary rounded-right-pill text-white"><?php echo e(__('Send')); ?></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!--==================== Newsleter Section End ====================-->
<?php endif; ?>
<!--==================== Newslatter Section End ====================-->

<!--==================== Footer Section Start ====================-->
<footer class="full-row bg-white border-footer p-0">
    <div class="container">
        <div class="row row-cols-xl-4 row-cols-md-2 row-cols-1">
            <div class="col">
                <div class="footer-widget my-5">
                    <div class="footer-logo mb-4">
                        <a href="<?php echo e(route('front.index')); ?>"><img src="<?php echo e(asset('assets/images/'.$gs->footer_logo)); ?>" alt="Image not found!" /></a>
                    </div>
                    <div class="widget-ecommerce-contact">
                        <?php if($ps->phone != null): ?>
                        <span class="font-medium font-500 text-dark"><?php echo e(__('Got Questions ? Call us 24/7!')); ?></span>
                        <div class="text-dark h4 font-400 "><?php echo e($ps->phone); ?></div>
                        <?php endif; ?>
                        <?php if($ps->street != null): ?>
                        <span class="h6 text-secondary mt-2"><?php echo e(__('Address :')); ?></span>
                        <div class="text-general"><?php echo e($ps->street); ?></div>
                        <?php endif; ?>
                        <?php if($ps->email != null): ?>
                        <span class="h6 text-secondary mt-2"><?php echo e(__('Email :')); ?></span>
                        <div class="text-general"><?php echo e($ps->email); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="footer-widget media-widget mb-4">
                    <?php $__currentLoopData = DB::table('social_links')->where('user_id',0)->where('status',1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e($link->link); ?>" target="_blank">
                            <i class="<?php echo e($link->icon); ?>"></i>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
            <div class="col">
                <div class="footer-widget category-widget my-5">
                    <h3 class="widget-title mb-4"><?php echo e(__('Product Category')); ?></h3>
                        <ul>
                        <?php $__currentLoopData = $categories->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cate): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li><a href="<?php echo e(route('front.category', $cate->slug)); ?><?php echo e(!empty(request()->input('search')) ? '?search='.request()->input('search') : ''); ?>"><?php echo e($cate->name); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                </div>
            </div>


            <div class="col">
                <div class="footer-widget category-widget my-5">
                    <h6 class="widget-title mb-sm-4"><?php echo e(__('Customer Care')); ?></h6>
                    <ul>
                        <?php if($ps->home == 1): ?>
                        <li>
                            <a href="<?php echo e(route('front.index')); ?>"><?php echo e(__('Home')); ?></a>
                        </li>
                        <?php endif; ?>

                        <?php if($ps->blog == 1): ?>
                            <li>
                                <a href="<?php echo e(route('front.blog')); ?>"><?php echo e(__('Blog')); ?></a>
                            </li>

                            

                            <!--    <a href="https://fabilive.com/assets/pdf/fabilive.pdf" target="_blank">-->
                            <!--    <?php echo e(__('Fabilive Sub-Merchant Agreement')); ?>-->
                            <!--</a>-->

                        <?php endif; ?>



                        <?php if($ps->faq == 1): ?>
                            <li>
                                <a href="<?php echo e(route('front.faq')); ?>"><?php echo e(__('Faq')); ?></a>
                            </li>
                            <?php endif; ?>
                            <?php $__currentLoopData = DB::table('pages')->where('footer','=',1)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><a href="<?php echo e(route('front.vendor',$data->slug)); ?>"><?php echo e($data->title); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php if($ps->contact == 1): ?>
                        <li>
                            <a href="<?php echo e(route('front.contact')); ?>"><?php echo e(__('Contact Us')); ?></a>
                        </li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>



            <div class="col">
                <div class="footer-widget widget-nav my-5">
                    <h6 class="widget-title mb-sm-4"><?php echo e(__('Recent Post')); ?></h6>
                    <ul>
                        <?php $__currentLoopData = $footer_blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $footer_blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <div class="post">
                                <div class="post-img">
                                    <img src="<?php echo e(asset('assets/images/blogs/'.$footer_blog->photo)); ?>" alt="">
                                  </div>
                                  <div class="post-details">
                                    <a href="<?php echo e(route('front.blogshow',$footer_blog->slug)); ?>">
                                        <h4 class="post-title">
                                            <?php echo e(mb_strlen($footer_blog->title,'UTF-8') > 45 ? mb_substr($footer_blog->title,0,45,'UTF-8')." .." : $footer_blog->title); ?>

                                        </h4>
                                    </a>
                                    <p class="date">
                                        <?php echo e(date('M d - Y',(strtotime($footer_blog->created_at)))); ?>

                                    </p>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<!--==================== Footer Section End ====================-->

<!--==================== Copyright Section Start ====================-->
<div class="container">
    <div class="mx-auto text-center py-3">
        <span class="sm-mb-10 d-block"><?php echo e($gs->copyright); ?></span>
    </div>
</div>

<?php if(isset($visited)): ?>

<?php if($gs->is_cookie == 1): ?>
    <div class="cookie-bar-wrap show">
        <div class="container d-flex justify-content-center">
            <div class="col-xl-10 col-lg-12">
                <div class="row justify-content-center">
                    <div class="cookie-bar">
                        <div class="cookie-bar-text">
                            <?php echo e(__('The website uses cookies to ensure you get the best experience on our website.')); ?>

                        </div>
                        <div class="cookie-bar-action">
                            <button class="btn btn-primary btn-accept">
                             <?php echo e(__('GOT IT!')); ?>

                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?php endif; ?>


<!--==================== Copyright Section End ====================-->

<!-- Scroll to top -->
<a href="#" class="scroller text-white" id="scroll"><i class="fa fa-angle-up"></i></a>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/partials/global/common-footer.blade.php ENDPATH**/ ?>