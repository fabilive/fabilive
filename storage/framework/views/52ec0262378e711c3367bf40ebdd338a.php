<div class="row">
    <div class="col-8">
        <div id="comments">

            <?php if(Auth::check()): ?>
            <div class="review-area mt-5">
                <h4 class="title"><?php echo e(__('Write Comment')); ?></h4>
              </div>
              <div class="write-comment-area">
                <form id="comment-form" action="<?php echo e(route('product.comment')); ?>" method="POST">
                  <?php echo e(csrf_field()); ?>

                  <input type="hidden" name="product_id" id="product_id" value="<?php echo e($productt->id); ?>">
                  <input type="hidden" name="user_id" id="user_id" value="<?php echo e(Auth::user()->id); ?>">
                  <div class="row">
                    <div class="col-lg-12">
                    <textarea class="form-control border" placeholder="<?php echo e(__('Write Your Comments Here...')); ?>" name="text" required></textarea>
                    </div>
                  </div>
                  <div class="row mt-3">
                    <div class="col-lg-12">
                      <button class="submit-btn mybtn1" type="submit"><?php echo e(__('Post Comment')); ?></button>
                    </div>
                  </div>
                </form>
              </div>
              <br>

              <ul class="all-comment">
                <?php
                    $comments = $productt->comments();
                ?>
                <?php if($comments->count() > 0): ?>
                <?php $__currentLoopData = $comments->latest(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <li>
                    <div class="single-comment comment-section">
                      <div class="left-area">
                        <img src="<?php echo e($comment->user->photo != null ? asset('assets/images/users/'.$comment->user->photo) : asset('assets/images/'.$gs->user_image)); ?>" alt="">
                        <h5 class="name"><?php echo e($comment->user->name); ?></h5>
                        <p class="date"><?php echo e($comment->created_at->diffForHumans()); ?></p>
                      </div>
                      <div class="right-area">
                        <div class="comment-body">
                          <p>
                            <?php echo e($comment->text); ?>

                          </p>
                        </div>
                        <div class="comment-footer">
                          <div class="links">
                            <a href="javascript:;" class="comment-link reply mr-2"><i class="fas fa-reply "></i><?php echo e(__('Reply')); ?></a>
                            <?php if(count($comment->replies) > 0): ?>
                            <a href="javascript:;" class="comment-link view-reply mr-2"><i class="fas fa-eye "></i><?php echo e(__('View ')); ?> <?php echo e(count($comment->replies) == 1 ? __('Reply') : __('Replies')); ?></a>
                            <?php endif; ?>
                          <?php if(Auth::user()->id == $comment->user->id): ?>
                            <a href="javascript:;" class="comment-link edit mr-2"><i class="fas fa-edit "></i><?php echo e(__('Edit')); ?></a>
                            <a href="javascript:;" data-href="<?php echo e(route('product.comment.delete',$comment->id)); ?>" class="comment-link comment-delete mr-2"><i class="fas fa-trash"></i><?php echo e(__('Delete')); ?></a>
                          <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="replay-area edit-area d-none">
                      <form class="update" action="<?php echo e(route('product.comment.edit',$comment->id)); ?>" method="POST">
                        <?php echo e(csrf_field()); ?>

                        <textarea placeholder="<?php echo e(__('Edit Your Comment')); ?>" name="text" required=""></textarea>
                        <button type="submit"><?php echo e(__('Submit')); ?></button>
                        <a href="javascript:;" class="remove cmn-rmv"><?php echo e(__('Cancel')); ?></a>
                      </form>
                    </div>
                <?php if($comment->replies): ?>
                  <?php $__currentLoopData = $comment->replies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="single-comment replay-review d-none">
                      <div class="left-area">
                        <img src="<?php echo e($reply->user->photo != null ? asset('assets/images/users/'.$reply->user->photo) : asset('assets/images/'.$gs->user_image)); ?>" alt="">
                        <h5 class="name"><?php echo e($reply->user->name); ?></h5>
                        <p class="date"><?php echo e($reply->created_at->diffForHumans()); ?></p>
                      </div>
                      <div class="right-area">
                        <div class="comment-body">
                          <p>
                            <?php echo e($reply->text); ?>

                          </p>
                        </div>
                        <div class="comment-footer">
                          <div class="links">

                            <a href="javascript:;" class="comment-link reply mr-2"><i class="fas fa-reply "></i><?php echo e(__('Reply')); ?></a>
                          <?php if(Auth::user()->id == $reply->user->id): ?>
                            <a href="javascript:;" class="comment-link edit mr-2"><i class="fas fa-edit "></i><?php echo e(__('Edit')); ?></a>
                            <a href="javascript:;" data-href="<?php echo e(route('product.reply.delete',$reply->id)); ?>" class="comment-link reply-delete mr-2"><i class="fas fa-trash"></i><?php echo e(__('Delete')); ?></a>
                          <?php endif; ?>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="replay-area edit-area d-none">
                      <form class="update" action="<?php echo e(route('product.reply.edit',$reply->id)); ?>" method="POST">
                        <?php echo e(csrf_field()); ?>

                        <textarea placeholder="<?php echo e(__('Edit Your Reply')); ?>" name="text" required=""></textarea>
                        <button type="submit"><?php echo e(__('Submit')); ?></button>
                        <a href="javascript:;"  class="remove cmn-rmv"><?php echo e(__('Cancel')); ?></a>
                      </form>
                    </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

                    <div class="replay-area reply-reply-area d-none">
                      <form class="reply-form" action="<?php echo e(route('product.reply',$comment->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="user_id" value="<?php echo e(Auth::user()->id); ?>">
                        <textarea placeholder="<?php echo e(__('Write Your Your Reply')); ?>" name="text" required=""></textarea>
                        <button type="submit"><?php echo e(__('Submit')); ?></button>
                        <a href="javascript:;" class="remove cmn-rmv"><?php echo e(__('Cancel')); ?></a>
                      </form>
                    </div>

                  </li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                </ul>


                <?php else: ?>
                <div class="row">
                <div class="col-lg-12">
                <br>
                  <h5 class="text-center"><a href="<?php echo e(route('user.login')); ?>"  class="btn login-btn"><?php echo e(__('Login')); ?></a> <?php echo e(__('To Comment')); ?> </h5>
                <br>
                </div>
                </div>

                <?php if($productt->comments): ?>
                <ul class="all-comment">

                  <?php $__currentLoopData = $productt->comments()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                  <li>
                    <div class="single-comment">
                      <div class="left-area">
                        <img src="<?php echo e($comment->user->photo != null ? asset('assets/images/users/'.$comment->user->photo) : asset('assets/images/'.$gs->user_image)); ?>" alt="">
                        <h5 class="name"><?php echo e($comment->user->name); ?></h5>
                        <p class="date"><?php echo e($comment->created_at->diffForHumans()); ?></p>
                      </div>
                      <div class="right-area">
                        <div class="comment-body">
                          <p>
                            <?php echo e($comment->text); ?>

                          </p>
                        </div>

                        <?php if(count($comment->replies) > 0): ?>
                        <div class="comment-footer">
                          <div class="links">
                            <a href="javascript:;" class="comment-link view-reply mr-2"><i class="fas fa-eye "></i><?php echo e(__('View ')); ?> <?php echo e(count($comment->replies) == 1 ? __('Reply') : __('Replies')); ?></a>
                          </div>
                        </div>
                        <?php endif; ?>

                      </div>
                    </div>

                <?php if($comment->replies): ?>
                  <?php $__currentLoopData = $comment->replies()->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reply): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="single-comment replay-review d-none">
                      <div class="left-area">
                        <img src="<?php echo e($reply->user->photo != null ? asset('assets/images/users/'.$reply->user->photo) : asset('assets/images/'.$gs->user_image)); ?>" alt="">
                        <h5 class="name"><?php echo e($reply->user->name); ?></h5>
                        <p class="date"><?php echo e($reply->created_at->diffForHumans()); ?></p>
                      </div>
                      <div class="right-area">
                        <div class="comment-body">
                          <p>
                            <?php echo e($reply->text); ?>

                          </p>
                        </div>

                      </div>
                    </div>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
                  </li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
                <?php endif; ?>
                <?php endif; ?>
        </div>
    </div>
</div>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/partials/product-details/comment-replies.blade.php ENDPATH**/ ?>