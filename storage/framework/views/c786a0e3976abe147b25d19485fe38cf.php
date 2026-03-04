<?php $__env->startSection('css'); ?>
    

    <style>
        /* @media only screen and (max-width: 767px) {
            .banner-slide-item  {
            background-size: contain !important;
        }
        .banner-wrapper-item {
        min-height: 250px !important;
        padding: 0 15px !important;

    }
        } */
    </style>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('partials.global.common-header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <?php echo $__env->make('partials.global.subscription-popup', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <?php if($ps->slider == 1): ?>
        <div class="position-relative">
            <span class="nextBtn"></span>
            <span class="prevBtn"></span>
            <section class="home-slider owl-theme owl-carousel">
                <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="banner-slide-item"
                        style="position: relative; background: url('<?php echo e(asset('assets/images/sliders/' . $data->photo)); ?>') no-repeat center center / cover;">

                        <!-- Overlay -->
                        <div
                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.35);">
                        </div>

                        <!-- Text -->
                        <div class="container"
                            style="position: relative; z-index: 2; display: flex; align-items: center; height: 100%;">
                            <div class="banner-wrapper-item text-<?php echo e($data->position); ?>" style="width: 100%;">
                                <div class="banner-content" style="text-align: <?php echo e($data->position); ?>;">

                                    <!-- Subtitle -->
                                    <h5 class="subtitle slide-h5"
                                        style="font-size: 20px; font-weight: 600; color: #ffffff;
                              letter-spacing: 1px; text-shadow: 2px 2px 8px rgba(0,0,0,0.7);
                              margin-bottom: 8px;">
                                        <?php echo e($data->subtitle_text); ?>

                                    </h5>

                                    <h2 class="title slide-h5"
                                    style="
                                        font-size: 44px;
                                        font-weight: 800;
                                        color: #ffd900e4; /* bright yellow */
                                        text-shadow: 2px 2px 6px rgba(0,0,0,0.6); /* makes it readable on any background */
                                        margin-bottom: 12px;
                                        line-height: 1.2;
                                    ">
                                    <?php echo e($data->title_text); ?>

                                </h2>

                                    <!-- Paragraph -->
                                    <p class="slide-h5"
                                        style="font-size: 20px; color: #ffffff;
                             text-shadow: 1px 1px 6px rgba(0,0,0,0.7); margin-bottom: 18px;">
                                        <?php echo e($data->details_text); ?>

                                    </p>

                                    <!-- Button -->
                                    <a href="<?php echo e($data->link); ?>" class="cmn--btn"
                                     >
                                        <?php echo e(__('SHOP NOW')); ?>

                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </section>
        </div>
    <?php endif; ?>




    <!--==================== Category Section Start ====================-->
    <div class="full-row pt-0 mt-5 px-sm-5 pb-0">
        <div class="container-fluid">
            <div
                class="row row-cols-xxl-6 row-cols-md-3 row-cols-sm-2 row-cols-2 g-3 coustom-categories-banner-1 e-wrapper-absolute e-hover-image-zoom">
                <?php $__currentLoopData = $featured_categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col">
                        <div class="product type-product">
                            <div class="product-wrapper">
                                <div class="product-image">
                                    <a href="<?php echo e(route('front.category', $fcategory->slug)); ?>"><img
                                            src="<?php echo e(asset('assets/images/categories/' . $fcategory->image)); ?>"
                                            alt="Product image"></a>
                                </div>
                                <div class="product-info">
                                    <h6 class="product-title"><a
                                            href="<?php echo e(route('front.category', $fcategory->slug)); ?>"><?php echo e($fcategory->name); ?></a>
                                    </h6>
                                    <span class="strok">(<?php echo e($fcategory->products_count); ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <!--==================== Category Section End ====================-->





    <?php if($ps->arrival_section == 1): ?>
        <!--==================== Best Month Offer Section Start ====================-->
        <div class="full-row px-sm-5">
            <div class="container-fluid">
                <div class="row justify-content-center wow fadeInUp animated" data-wow-delay="200ms"
                    data-wow-duration="1000ms">
                    <div class="col-xxl-5 col-xl-7 col-lg-9">
                        <div class="text-center mb-40">
                            <h2 class="text-center font-500 mb-4"><?php echo e(__('Best Month Offer')); ?></h2>
                            <span class="sub-title">
                                <?php echo e(__('Enjoy unbeatable deals and exclusive discounts available only this month.
                                                        Don’t miss out on your chance to save big!')); ?>

                            </span>
                        </div>
                    </div>
                </div>
                <div class="row g-4">
                    <div class="col-xxl-6 col-md-12">
                        <div class="banner-wrapper hover-img-zoom banner-one custom-class-122 bg-light">

                            <div class="banner-image overflow-hidden transation">
                                <img src="<?php echo e(asset('assets/images/arrival/' . $arrivals[0]['photo'])); ?>" alt="Banner Image">
                            </div>

                            <div class="banner-content y-center position-absolute">
                                <div class="middle-content">

                                    <span class="up-to-sale"
                                    style="color:#ffffff;
                                           background:rgba(0,0,0,0.35);
                                           padding:4px 10px;
                                           border-radius:3px;">

                                        <?php echo e($arrivals[0]['up_sale']); ?>

                                    </span>

                                    <h3>
                                        <a href="<?php echo e($arrivals[0]['url']); ?>"
                                           style="color:#ffffff; background:rgba(0,0,0,0.35); padding:6px 12px; border-radius:3px; text-decoration:none; transition: color 0.3s;"
                                           class="arrival-link">
                                            <?php echo e($arrivals[0]['title']); ?>

                                        </a>

                                        <style>
                                            .arrival-link:hover {
                                                color: #ff6a00 !important;
                                            }
                                        </style>
                                    </h3>


                                    <a href="<?php echo e($arrivals[0]['url']); ?>"
                                    class="category arrival-link"
                                    style="color:#ffffff;
                                           background:rgba(0,0,0,0.35);
                                           padding:4px 10px;
                                           border-radius:3px;">
                                     <?php echo e($arrivals[0]['header']); ?>

                                 </a>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div class="banner-wrapper hover-img-zoom banner-one custom-class-123">
                            <div class="banner-image overflow-hidden transation"><img
                                    src="<?php echo e(asset('assets/images/arrival/' . $arrivals[1]['photo'])); ?>" alt="Banner Image">
                            </div>
                            <div class="banner-content position-absolute">
                                <div class="middle-content">
                                    <span class="up-to-sale"><?php echo e($arrivals[1]['up_sale']); ?></span>
                                    <h3><a href="<?php echo e($arrivals[1]['url']); ?>"
                                            class="text-dark text-decoration-none"><?php echo e($arrivals[1]['title']); ?></a></h3>
                                    <a href="<?php echo e($arrivals[1]['url']); ?>" class="category"><?php echo e($arrivals[1]['header']); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-3 col-md-6">
                        <div class="banner-wrapper hover-img-zoom banner-one custom-class-124"
                             style="position:relative; overflow:hidden; border-radius:8px;">

                            <div class="banner-image overflow-hidden transation">
                                <img src="<?php echo e(asset('assets/images/arrival/' . $arrivals[2]['photo'])); ?>"
                                     alt="Banner Image"
                                     style="width:100%; display:block;">

                                <!-- Overlay -->
                                <div style="position:absolute; top:0; left:0; width:100%; height:100%;
                                            background:rgba(0,0,0,0.35); pointer-events:none;" class="p-3">
                                </div>
                            </div>

                            <div class="banner-content position-absolute" style="top:10px; left:10px;">
                                <span style="color:#ffffff; padding:4px 10px; border-radius:3px;"><?php echo e($arrivals[2]['up_sale']); ?></span>

                                <h5>
                                    <a href="<?php echo e($arrivals[2]['url']); ?>" class="arrival-link"
                                       style="color:#ffffff; text-decoration:none; padding:6px 12px; border-radius:3px"
                                      >
                                       <?php echo e($arrivals[2]['title']); ?>

                                    </a>
                                </h5>

                                <a href="<?php echo e($arrivals[2]['url']); ?>"
                                   style="color:#ffffff; padding:4px 10px; border-radius:3px; text-decoration:none;"
                                    class="arrival-link">
                                   <?php echo e($arrivals[2]['header']); ?>

                                </a>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
        <!--==================== Best Month Offer Section End ====================-->
    <?php endif; ?>


    <?php echo $__env->make('partials.theme.extraindex', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



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
    <!-- Scroll to top -->
    <a href="#" class="scroller text-white" id="scroll"><i class="fa fa-angle-up"></i></a>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('script'); ?>
    <script>
        var owl = $('.home-slider').owlCarousel({
            loop: true,
            nav: false,
            dots: true,
            items: 1,
            autoplay: true,
            margin: 0,
            animateIn: 'fadeInDown',
            animateOut: 'fadeOutUp',
            mouseDrag: false,
        })
        $('.nextBtn').click(function() {
            owl.trigger('next.owl.carousel', [300]);
        })
        $('.prevBtn').click(function() {
            owl.trigger('prev.owl.carousel', [300]);
        })
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.front', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Shilley Pc\fabilive\resources\views/partials/theme/theme1.blade.php ENDPATH**/ ?>