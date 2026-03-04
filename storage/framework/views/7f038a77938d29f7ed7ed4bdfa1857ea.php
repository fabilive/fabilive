<div class="row">
    <div class="col-8">
        <div id="comments">
            <?php if($productt->ratings()->count() > 0): ?>
            <h2 class="woocommerce-Reviews-title my-3"> <?php echo e(__('Ratings & Reviews')); ?></h2>

            <div class="reating-area">
                <div class="stars"><span id="star-rating"><?php echo e(App\Models\Rating::normalRating($productt->id)); ?></span> <i class="fas fa-star"></i></div>
              </div>

            <ul class="all-comments">
                <?php $__currentLoopData = $productt->ratings(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                  <div class="single-comment">
                    <div class="left-area">
                      <img src="<?php echo e($review->user->photo ? asset('assets/images/users/'.$review->user->photo):asset('assets/images/'.$gs->user_image)); ?>" alt="">
                      <h5 class="name">
                        <?php echo e($review->user->name); ?>

                      </h5>
                      <p class="date">
                        <?php echo e(Carbon\Carbon::createFromFormat('Y-m-d H:i:s',$review->review_date)->diffForHumans()); ?>

                      </p>
                    </div>
                    <div class="right-area">
                      <div class="header-area">
                        <div class="stars-area">
                          <ul class="stars">
                            <div class="ratings">
                              <div class="empty-stars"></div>
                              <div class="full-stars" style="width:<?php echo e($review->rating*20); ?>%"></div>
                            </div>
                          </ul>
                        </div>
                      </div>
                      <div class="comment-body">
                        <p>
                          <?php echo e($review->review); ?>

                        </p>
                      </div>
                    </div>
                  </div>
                </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
              </ul>
        </div>
        <?php else: ?>
        <p><?php echo e(__('No Review Found.')); ?></p>
        <?php endif; ?>
        <div id="review_form_wrapper">
            <?php if(Auth::check()): ?>
            <div class="review-area">
                <h4 class="title"><?php echo e(__('Reviews')); ?></h4>
                <div class="star-area">
                  <ul class="star-list">
                    <li class="stars" data-val="1">
                      <i class="fas fa-star"></i>
                    </li>
                    <li class="stars" data-val="2">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </li>
                    <li class="stars" data-val="3">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </li>
                    <li class="stars" data-val="4">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </li>
                    <li class="stars active" data-val="5">
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                      <i class="fas fa-star"></i>
                    </li>
                  </ul>
                </div>
              </div>
              <div class="write-comment-area">
                  <div class="gocover"
                  style="background: url(<?php echo e(asset('assets/images/'.$gs->loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                </div>
                <form id="reviewform" action="<?php echo e(route('front.review.submit')); ?>" data-href="<?php echo e(route('front.reviews',$productt->id)); ?>" data-side-href="<?php echo e(route('front.side.reviews',$productt->id)); ?>" method="POST">
                  <?php echo csrf_field(); ?>
                  <input type="hidden" id="rating" name="rating" value="5">
                  <input type="hidden" name="user_id" value="<?php echo e(Auth::user()->id); ?>">
                  <input type="hidden" name="product_id" value="<?php echo e($productt->id); ?>">
                  <div class="row">
                    <div class="col-lg-12">
                      <textarea name="review" placeholder="<?php echo e(__('Write Your Review *')); ?>" required></textarea>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-12">
                      <button class="mybtn1" type="submit"><?php echo e(__('Submit')); ?></button>
                    </div>
                  </div>
                </form>
              </div>
              <?php else: ?>
              <div class="row">
                <div class="col-lg-12">
                  <br>
                  <h5 class="text-center">
                    <a href="<?php echo e(route('user.login')); ?>" class="btn login-btn mr-1">
                      <?php echo e(__('Login')); ?>

                    </a>
                      <?php echo e(__('To Review')); ?>

                  </h5>
                  <br>
                </div>
            </div>
            <?php endif; ?>
    </div>

</div>
</div>

<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/partials/product-details/reviews.blade.php ENDPATH**/ ?>