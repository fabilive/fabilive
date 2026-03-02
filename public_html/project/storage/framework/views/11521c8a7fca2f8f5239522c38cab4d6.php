

<?php $__env->startSection('content'); ?>
            <div class="content-area">
                <div class="mr-breadcrumb">
                    <div class="row">
                      <div class="col-lg-12">
                          <h4 class="heading"><?php echo e(__('Edit Role')); ?> <a class="add-btn" href="<?php echo e(route('admin-role-index')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
                          <ul class="links">
                            <li>
                              <a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
                            </li>
                            <li>
                              <a href="<?php echo e(route('admin-role-index')); ?>"><?php echo e(__('Manage Roles')); ?></a>
                            </li>
                            <li>
                              <a href="<?php echo e(route('admin-role-edit',$data->id)); ?>"><?php echo e(__('Edit Role')); ?></a>
                            </li>
                          </ul>
                      </div>
                    </div>
                  </div>
              <div class="add-product-content">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                          <div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                      <form id="geniusform" action="<?php echo e(route('admin-role-update',$data->id)); ?>" method="POST" enctype="multipart/form-data">
                          <?php echo e(csrf_field()); ?>

                          <?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading"><?php echo e(__("Name")); ?> *</h4>
                                <p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="name" placeholder="<?php echo e(__('Name')); ?>" value="<?php echo e($data->name); ?>" required="">
                          </div>
                        </div>


                        <hr>
                        <h5 class="text-center"><?php echo e(__('Permissions')); ?></h5>
                        <hr>

                        <div class="row justify-content-center">

                          <div class="col-lg-4 d-flex justify-content-between">
                            <label class="control-label"><?php echo e(__('Orders')); ?> *</label>
                            <label class="switch">
                              <input type="checkbox" name="section[]" value="orders" <?php echo e($data->sectionCheck('orders') ? 'checked' : ''); ?>>
                              <span class="slider round"></span>
                            </label>
                          </div>

                          <div class="col-lg-2"></div>

                          <div class="col-lg-4 d-flex justify-content-between">
                            <label class="control-label"><?php echo e(__('Manage Categories')); ?> *</label>
                            <label class="switch">
                              <input type="checkbox" name="section[]" value="categories" <?php echo e($data->sectionCheck('categories') ? 'checked' : ''); ?>>
                              <span class="slider round"></span>
                            </label>
                          </div>

                      </div>


                      <div class="row justify-content-center">

                        <div class="col-lg-4 d-flex justify-content-between">
                          <label class="control-label"><?php echo e(__('Manage country')); ?> *</label>
                          <label class="switch">
                            <input type="checkbox" name="section[]" value="manage-country"  <?php echo e($data->sectionCheck('manage-country') ? 'checked' : ''); ?>>
                            <span class="slider round"></span>
                          </label>
                        </div>

                        <div class="col-lg-2"></div>

                        <div class="col-lg-4 d-flex justify-content-between">
                          <label class="control-label"><?php echo e(__('Tax Calculate')); ?> *</label>
                          <label class="switch">
                            <input type="checkbox" name="section[]" value="earning" <?php echo e($data->sectionCheck('earning') ? 'checked' : ''); ?>>
                            <span class="slider round"></span>
                          </label>
                        </div>
                    </div>

                      <div class="row justify-content-center">

                        <div class="col-lg-4 d-flex justify-content-between">
                          <label class="control-label"><?php echo e(__('Products')); ?> *</label>
                          <label class="switch">
                            <input type="checkbox" name="section[]" value="products" <?php echo e($data->sectionCheck('products') ? 'checked' : ''); ?>>
                            <span class="slider round"></span>
                          </label>
                        </div>

                        <div class="col-lg-2"></div>

                        <div class="col-lg-4 d-flex justify-content-between">
                          <label class="control-label"><?php echo e(__('Affiliate Products')); ?> *</label>
                          <label class="switch">
                            <input type="checkbox" name="section[]" value="affilate_products" <?php echo e($data->sectionCheck('affilate_products') ? 'checked' : ''); ?>>
                            <span class="slider round"></span>
                          </label>
                        </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Bulk Product Upload')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="bulk_product_upload" <?php echo e($data->sectionCheck('bulk_product_upload') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Product Discussion')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="product_discussion" <?php echo e($data->sectionCheck('product_discussion') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Set Coupons')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="set_coupons" <?php echo e($data->sectionCheck('set_coupons') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Customers')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="customers" <?php echo e($data->sectionCheck('customers') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Customer Deposits')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="customer_deposits" <?php echo e($data->sectionCheck('customer_deposits') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Vendors')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="vendors" <?php echo e($data->sectionCheck('vendors') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Vendor Subscriptions')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="vendor_subscriptions" <?php echo e($data->sectionCheck('vendor_subscriptions') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Vendor Verifications')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="vendor_verifications" <?php echo e($data->sectionCheck('vendor_verifications') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Vendor Subscription Plans')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="vendor_subscription_plans" <?php echo e($data->sectionCheck('vendor_subscription_plans') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Messages')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="messages" <?php echo e($data->sectionCheck('messages') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('General Settings')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="general_settings" <?php echo e($data->sectionCheck('general_settings') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Home Page Settings')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="home_page_settings" <?php echo e($data->sectionCheck('home_page_settings') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Menu Page Settings')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="menu_page_settings" <?php echo e($data->sectionCheck('menu_page_settings') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Email Settings')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="emails_settings" <?php echo e($data->sectionCheck('email_settings') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Payment Settings')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="payment_settings" <?php echo e($data->sectionCheck('payment_settings') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Social Settings')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="social_settings" <?php echo e($data->sectionCheck('social_settings') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Language Settings')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="language_settings" <?php echo e($data->sectionCheck('language_settings') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('SEO Tools')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="seo_tools" <?php echo e($data->sectionCheck('seo_tools') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                    <div class="row justify-content-center">

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Manage Staffs')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="manage_staffs" <?php echo e($data->sectionCheck('manage_staffs') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                      <div class="col-lg-2"></div>

                      <div class="col-lg-4 d-flex justify-content-between">
                        <label class="control-label"><?php echo e(__('Subscribers')); ?> *</label>
                        <label class="switch">
                          <input type="checkbox" name="section[]" value="subscribers" <?php echo e($data->sectionCheck('subscribers') ? 'checked' : ''); ?>>
                          <span class="slider round"></span>
                        </label>
                      </div>

                    </div>

                        <div class="row">
                          <div class="col-lg-5">
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
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/role/edit.blade.php ENDPATH**/ ?>