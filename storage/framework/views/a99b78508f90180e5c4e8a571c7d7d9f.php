<?php if(isset($visited)): ?>

<?php if($gs->is_popup == 1): ?>

<!-- Advertising Banner Section -->

<!--  Starting of subscribe-pre-loader Area   -->

<div style="display:none">
    <img src="<?php echo e(asset('assets/images/'.$gs->popup_background)); ?>">
</div>

<div class="subscribe-preloader-wrap" id="subscriptionForm">
    <div class="subscribePreloader__thumb" style="background-image: url(<?php echo e(asset('assets/images/'.$gs->popup_background)); ?>);">
        <span class="preload-close"><i class="fas fa-times"></i></span>
        <div class="subscribePreloader__text text-center">
            <h1><?php echo e(__('NEWSLETTER')); ?></h1>
            <p><?php echo e(__('Hey there! Do you want to get updates on exclusive offers and good quality, affordable products at the best rates? To do so, just write your email address.')); ?></p>
            <form action="<?php echo e(route('front.subscribe')); ?>" class="subscribeform" method="POST">
                <?php echo e(csrf_field()); ?>

                <div class="form-group">
                    <input type="email" name="email"  placeholder="<?php echo e(__('Enter Your Email Address')); ?>" required="">
                    <button   type="submit" class="subscribe-btn"><?php echo e(__('SUBSCRIBE')); ?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Advertising Banner Section Ends -->

<?php endif; ?>

<?php endif; ?>
<?php /**PATH C:\Users\Shilley Pc\fabilive\resources\views/partials/global/subscription-popup.blade.php ENDPATH**/ ?>