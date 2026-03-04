<?php $__env->startSection('content'); ?>

            <div class="content-area">
              <div class="mr-breadcrumb">
                <div class="row">
                  <div class="col-lg-12">
                      <h4 class="heading"><?php echo e(__('Edit Slider')); ?> <a class="add-btn" href="<?php echo e(route('admin-sl-index')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                      <ul class="links">
                        <li>
                          <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                        </li>
                        <li>
                          <a href="javascript:;"><?php echo e(__('Home Page Settings')); ?></a>
                        </li>
                        <li>
                          <a href="<?php echo e(route('admin-sl-index')); ?>"><?php echo e(__('Sliders')); ?></a>
                        </li>
                        <li>
                          <a href="<?php echo e(route('admin-sl-edit',$data->id)); ?>"><?php echo e(__('Edit')); ?></a>
                        </li>
                      </ul>
                  </div>
                </div>
              </div>
              <div class="add-product-content1 add-product-content2">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                      <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                      <form id="geniusform" action="<?php echo e(route('admin-sl-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo e(csrf_field()); ?>

                      <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


                                      

                                      <div class="panel panel-default slider-panel">
                                                <div class="panel-heading text-center"><h3><?php echo e(__('Sub Title')); ?></h3></div>
                                                <div class="panel-body">
                                              <div class="form-group">
                                                  <div class="col-sm-12">
                                                    <label class="control-label" for="subtitle_text"><?php echo e(__('Text')); ?>*</label>

                                                  <textarea class="form-control" name="subtitle_text" id="subtitle_text" rows="5"  placeholder="<?php echo e(__('Enter Title Text')); ?>"><?php echo e($data->subtitle_text); ?></textarea>
                                                </div>
                                              </div>


                                              <div class="form-group">
                                                  <div class="col-sm-12">
                                                   <div class="row">
                                                      <div class="col-sm-4">
                                                      <label class="control-label" for="subtitle_size"><?php echo e(__('Font Size')); ?> *<span> <?php echo e(__('(px)')); ?></span></label>
                                                      <input class="form-control" type="number" name="subtitle_size" value="<?php echo e($data->subtitle_size); ?>" min="1">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="subtitle_color"><?php echo e(__('Font Color')); ?> *</label>
                                                      <div class="input-group colorpicker-component cp">
                                                        <input type="text" name="subtitle_color" value="<?php echo e($data->subtitle_color); ?>"  class="form-control cp"  />
                                                        <span class="input-group-addon"><i></i></span>
                                                      </div>

                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="subtitle_anime"><?php echo e(__('Animation')); ?> *</label>
                                                          <select class="form-control" id="subtitle_anime" name="subtitle_anime">
                                                                <option value="fadeIn" <?php echo e($data->subtitle_anime == 'fadeIn' ? 'selected' :''); ?>>fadeIn</option>
                                                                <option value="fadeInDown" <?php echo e($data->subtitle_anime == 'fadeInDown' ? 'selected' :''); ?>>fadeInDown</option>
                                                                <option value="fadeInLeft" <?php echo e($data->subtitle_anime == 'fadeInLeft' ? 'selected' :''); ?>>fadeInLeft</option>
                                                                <option value="fadeInRight" <?php echo e($data->subtitle_anime == 'fadeInRight' ? 'selected' :''); ?>>fadeInRight</option>
                                                                <option value="fadeInUp" <?php echo e($data->subtitle_anime == 'fadeInUp' ? 'selected' :''); ?>>fadeInUp</option>
                                                                <option value="flip" <?php echo e($data->subtitle_anime == 'flip' ? 'selected' :''); ?>>flip</option>
                                                                <option value="flipInX" <?php echo e($data->subtitle_anime == 'flipInX' ? 'selected' :''); ?>>flipInX</option>
                                                                <option value="flipInY" <?php echo e($data->subtitle_anime == 'flipInY' ? 'selected' :''); ?>>flipInY</option>
                                                                <option value="slideInUp" <?php echo e($data->subtitle_anime == 'slideInUp' ? 'selected' :''); ?>>slideInUp</option>
                                                                <option value="slideInDown" <?php echo e($data->subtitle_anime == 'slideInDown' ? 'selected' :''); ?>>slideInDown</option>
                                                                <option value="slideInLeft" <?php echo e($data->subtitle_anime == 'slideInLeft' ? 'selected' :''); ?>>slideInLeft</option>
                                                                <option value="slideInRight" <?php echo e($data->subtitle_anime == 'slideInRight' ? 'selected' :''); ?>>slideInRight</option>
                                                                <option value="rollIn" <?php echo e($data->subtitle_anime == 'rollIn' ? 'selected' :''); ?>>rollIn</option>
                                                          </select>
                                                    </div>
                                                   </div>

                                                </div>
                                              </div>
                                        </div>
                                        </div>

                                      

                                      

                                      <div class="panel panel-default slider-panel">
                                                <div class="panel-heading text-center"><h3><?php echo e(__('Title')); ?></h3></div>
                                                <div class="panel-body">
                                              <div class="form-group">
                                                  <div class="col-sm-12">
                                                    <label class="control-label" for="title_text"><?php echo e(__('Text')); ?>*</label>

                                                  <textarea class="form-control" name="title_text" id="title_text" rows="5"  placeholder="<?php echo e(__('Enter Title Text')); ?>"><?php echo e($data->title_text); ?></textarea>
                                                </div>
                                              </div>


                                              <div class="form-group">
                                                  <div class="col-sm-12">
                                                   <div class="row">
                                                      <div class="col-sm-4">
                                                      <label class="control-label" for="title_size"><?php echo e(__('Font Size')); ?> *<span> <?php echo e(__('(px)')); ?></span></label>
                                                      <input class="form-control" type="number" name="title_size" value="<?php echo e($data->title_size); ?>" min="1">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="title_color"><?php echo e(__('Font Color')); ?> *</label>
                                                      <div class="input-group colorpicker-component cp">
                                                        <input type="text" name="title_color" value="<?php echo e($data->title_color); ?>"  class="form-control cp"  />
                                                        <span class="input-group-addon"><i></i></span>
                                                      </div>

                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="title_anime"><?php echo e(__('Animation')); ?> *</label>
                                                          <select class="form-control" id="title_anime" name="title_anime">
                                                                <option value="fadeIn" <?php echo e($data->title_anime == 'fadeIn' ? 'selected' :''); ?>>fadeIn</option>
                                                                <option value="fadeInDown" <?php echo e($data->title_anime == 'fadeInDown' ? 'selected' :''); ?>>fadeInDown</option>
                                                                <option value="fadeInLeft" <?php echo e($data->title_anime == 'fadeInLeft' ? 'selected' :''); ?>>fadeInLeft</option>
                                                                <option value="fadeInRight" <?php echo e($data->title_anime == 'fadeInRight' ? 'selected' :''); ?>>fadeInRight</option>
                                                                <option value="fadeInUp" <?php echo e($data->title_anime == 'fadeInUp' ? 'selected' :''); ?>>fadeInUp</option>
                                                                <option value="flip" <?php echo e($data->title_anime == 'flip' ? 'selected' :''); ?>>flip</option>
                                                                <option value="flipInX" <?php echo e($data->title_anime == 'flipInX' ? 'selected' :''); ?>>flipInX</option>
                                                                <option value="flipInY" <?php echo e($data->title_anime == 'flipInY' ? 'selected' :''); ?>>flipInY</option>
                                                                <option value="slideInUp" <?php echo e($data->title_anime == 'slideInUp' ? 'selected' :''); ?>>slideInUp</option>
                                                                <option value="slideInDown" <?php echo e($data->title_anime == 'slideInDown' ? 'selected' :''); ?>>slideInDown</option>
                                                                <option value="slideInLeft" <?php echo e($data->title_anime == 'slideInLeft' ? 'selected' :''); ?>>slideInLeft</option>
                                                                <option value="slideInRight" <?php echo e($data->title_anime == 'slideInRight' ? 'selected' :''); ?>>slideInRight</option>
                                                                <option value="rollIn" <?php echo e($data->title_anime == 'rollIn' ? 'selected' :''); ?>>rollIn</option>
                                                          </select>
                                                    </div>
                                                   </div>

                                                </div>
                                              </div>
                                        </div>
                                        </div>

                                      


                                      

                                      <div class="panel panel-default slider-panel">
                                                <div class="panel-heading text-center"><h3><?php echo e(__('Description')); ?></h3></div>
                                                <div class="panel-body">
                                              <div class="form-group">
                                                  <div class="col-sm-12">
                                                    <label class="control-label" for="details_text"><?php echo e(__('Text')); ?>*</label>

                                                  <textarea class="form-control" name="details_text" id="details_text" rows="5"  placeholder="Enter Title Text"><?php echo e($data->details_text); ?></textarea>
                                                </div>
                                              </div>


                                              <div class="form-group">
                                                  <div class="col-sm-12">
                                                   <div class="row">
                                                      <div class="col-sm-4">
                                                      <label class="control-label" for="details_size"><?php echo e(__('Font Size')); ?> *<span> <?php echo e(__('(px)')); ?></span></label>
                                                      <input class="form-control" type="number" name="details_size" value="<?php echo e($data->details_size); ?>" min="1">
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="details_color"><?php echo e(__('Font Color')); ?> *</label>
                                                      <div class="input-group colorpicker-component cp">
                                                        <input type="text" name="details_color" value="<?php echo e($data->details_color); ?>"  class="form-control cp" />
                                                        <span class="input-group-addon"><i></i></span>
                                                      </div>

                                                    </div>
                                                    <div class="col-sm-4">
                                                        <label class="control-label" for="details_anime"><?php echo e(__('Animation')); ?> *</label>
                                                          <select class="form-control" id="details_anime" name="details_anime">
                                                                <option value="fadeIn" <?php echo e($data->details_anime == 'fadeIn' ? 'selected' :''); ?>>fadeIn</option>
                                                                <option value="fadeInDown" <?php echo e($data->details_anime == 'fadeInDown' ? 'selected' :''); ?>>fadeInDown</option>
                                                                <option value="fadeInLeft" <?php echo e($data->details_anime == 'fadeInLeft' ? 'selected' :''); ?>>fadeInLeft</option>
                                                                <option value="fadeInRight" <?php echo e($data->details_anime == 'fadeInRight' ? 'selected' :''); ?>>fadeInRight</option>
                                                                <option value="fadeInUp" <?php echo e($data->details_anime == 'fadeInUp' ? 'selected' :''); ?>>fadeInUp</option>
                                                                <option value="flip" <?php echo e($data->details_anime == 'flip' ? 'selected' :''); ?>>flip</option>
                                                                <option value="flipInX" <?php echo e($data->details_anime == 'flipInX' ? 'selected' :''); ?>>flipInX</option>
                                                                <option value="flipInY" <?php echo e($data->details_anime == 'flipInY' ? 'selected' :''); ?>>flipInY</option>
                                                                <option value="slideInUp" <?php echo e($data->details_anime == 'slideInUp' ? 'selected' :''); ?>>slideInUp</option>
                                                                <option value="slideInDown" <?php echo e($data->details_anime == 'slideInDown' ? 'selected' :''); ?>>slideInDown</option>
                                                                <option value="slideInLeft" <?php echo e($data->details_anime == 'slideInLeft' ? 'selected' :''); ?>>slideInLeft</option>
                                                                <option value="slideInRight" <?php echo e($data->details_anime == 'slideInRight' ? 'selected' :''); ?>>slideInRight</option>
                                                                <option value="rollIn" <?php echo e($data->details_anime == 'rollIn' ? 'selected' :''); ?>>rollIn</option>
                                                          </select>
                                                    </div>
                                                   </div>

                                                </div>
                                              </div>
                                        </div>
                                        </div>

                                      


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Current Featured Image')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <div class="img-upload full-width-img">
                                <div id="image-preview" class="img-preview" style="background: url(<?php echo e($data->photo ? asset('assets/images/sliders/'.$data->photo):asset('assets/images/noimage.png')); ?>);">
                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i><?php echo e(__('Upload Image')); ?></label>
                                    <input type="file" name="photo" class="img-upload" id="image-upload">
                                  </div>
                                  <p class="text"><?php echo e(__('Prefered Size: (1920x800) or Square Sized Image')); ?></p>
                            </div>

                          </div>
                        </div>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Link')); ?> *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="link" placeholder="Link" required="" value="<?php echo e($data->link); ?>">

                          </div>
                        </div>


                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__('Text Position')); ?>*</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <select  name="position" required="">
                                  <option value=""><?php echo e(__('Select Position')); ?></option>
                                  <option value="left" <?php echo e($data->position == 'left' ? 'selected':''); ?>><?php echo e(__('Left')); ?></option>
                                  <option value="center" <?php echo e($data->position  == 'center' ? 'selected':''); ?>><?php echo e(__('Center')); ?></option>
                                  <option value="right" <?php echo e($data->position  == 'right' ? 'selected':''); ?>><?php echo e(__('Right')); ?></option>
                                </select>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">

                            </div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit"><?php echo e(__('Save')); ?></button>
                          </div>
                        </div>

                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/slider/edit.blade.php ENDPATH**/ ?>