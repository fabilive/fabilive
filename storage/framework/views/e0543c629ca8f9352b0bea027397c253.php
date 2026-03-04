
<?php $__env->startSection('styles'); ?>

<link href="<?php echo e(asset('assets/admin/css/product.css')); ?>" rel="stylesheet"/>
<link href="<?php echo e(asset('assets/admin/css/jquery.Jcrop.css')); ?>" rel="stylesheet"/>
<link href="<?php echo e(asset('assets/admin/css/Jcrop-style.css')); ?>" rel="stylesheet"/>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

						<div class="content-area">
							<div class="mr-breadcrumb">
								<div class="row">
									<div class="col-lg-12">
											<h4 class="heading"><?php echo e(__("License Product")); ?> <a class="add-btn" href="<?php echo e(route('admin-prod-types')); ?>"><i class="fas fa-arrow-left"></i> <?php echo e(__("Back")); ?></a></h4>
											<ul class="links">
												<li>
													<a href="<?php echo e(route('admin.dashboard')); ?>"><?php echo e(__("Dashboard")); ?> </a>
												</li>
											<li>
												<a href="javascript:;"><?php echo e(__("Products")); ?> </a>
											</li>
											<li>
												<a href="<?php echo e(route('admin-prod-index')); ?>"><?php echo e(__("All Products")); ?></a>
											</li>
												<li>
													<a href="<?php echo e(route('admin-prod-types')); ?>"><?php echo e(__("Add Product")); ?></a>
												</li>
												<li>
													<a href="<?php echo e(route('admin-prod-create','license')); ?>"><?php echo e(__("License Product")); ?></a>
												</li>
											</ul>
									</div>
								</div>
							</div>

							<form id="geniusform" action="<?php echo e(route('admin-prod-store')); ?>" method="POST" enctype="multipart/form-data">
								<?php echo e(csrf_field()); ?>

								<?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
								<div class="row">
									<div class="col-lg-8">
										<div class="add-product-content">
											<div class="row">
												<div class="col-lg-12">
													<div class="product-description">
														<div class="body-area">
															<div class="gocover" style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
															

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																			<h4 class="heading"><?php echo e(__('Product Name')); ?>* </h4>
																			<p class="sub-heading"><?php echo e(__('(In Any Language)')); ?></p>
																	</div>
																</div>
																<div class="col-lg-12">
																	<input type="text" class="input-field" placeholder="<?php echo e(__('Enter Product Name')); ?>" name="name" required="">
																</div>
															</div>


															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading"><?php echo e(__('Category')); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																	<select id="cat" name="category_id" required="">
																		<option value=""><?php echo e(__('Select Category')); ?></option>
																		<?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																			<option data-href="<?php echo e(route('admin-subcat-load',$cat->id)); ?>" value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
																		<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																	</select>
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading"><?php echo e(__('Sub Category')); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																	<select id="subcat" name="subcategory_id" disabled="">
																			<option value=""><?php echo e(__('Select Sub Category')); ?></option>
																	</select>
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading"><?php echo e(__('Child Category')); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																	<select id="childcat" name="childcategory_id" disabled="">
																			<option value=""><?php echo e(__('Select Child Category')); ?></option>
																	</select>
																</div>
															</div>


															<div id="catAttributes"></div>
															<div id="subcatAttributes"></div>
															<div id="childcatAttributes"></div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																			<h4 class="heading"><?php echo e(__("Select Upload Type")); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																		<select id="type_check" name="type_check">
																		  <option value="1"><?php echo e(__("Upload By File")); ?></option>
																		  <option value="2"><?php echo e(__("Upload By Link")); ?></option>
																		</select>
																</div>
															</div>
			
			        <!--                    <div class="row">-->
											<!--	<div class="col-lg-12">-->
											<!--		<div class="left-area">-->
											<!--			<h4 class="heading">-->
											<!--				<?php echo e(__('Product Location')); ?>* <small> (Enter city and area name here)</small>-->
											<!--			</h4>-->
											<!--		</div>-->
											<!--	</div>-->
											<!--	<div class="col-lg-12">-->
											<!--		<div class="text-editor">-->
											<!--			<textarea class="nic-edit-p" name="product_location"></textarea>-->
											<!--		</div>-->
											<!--	</div>-->
											<!--</div>-->
											
											<?php
                                                $serviceAreas = \App\Models\ServiceArea::all(); 
                                            ?>
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">
                                                            <?php echo e(__('Product Location')); ?>* <small> (Select city and area)</small>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="text-editor">
                                                        <select name="product_location" class="form-control" required>
                                                            <option value="">-- Select Location --</option>
                                                            <?php $__currentLoopData = $serviceAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($area->id); ?>"><?php echo e($area->location); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">
                                                            <?php echo e(__('Product City')); ?>* <small>(Select city)</small>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="text-editor">
                                                        <select name="product_city" class="form-control" required>
                                                            <option value="">-- Select City --</option>
                                                            <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- 04/09/2025 ko below Field add ki by suleman -->
                                            <div class="row mt-3">
                                                <div class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">
                                                            <?php echo e(__('Delivery Fee Calculation')); ?>*
                                                            <small> (Enter weight and select unit)</small>
                                                        </h4>
                                                    </div>
                                                </div>
                                                <div class="col-lg-12">
                                                    <div class="form-group row align-items-center">
                                                        <div class="col-md-6">
                                                            <input type="number" 
                                                                   name="delivery_fee" 
                                                                   class="form-control" 
                                                                   placeholder="Enter weight" 
                                                                   step="0.01"
                                                                   min="0">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <select name="delivery_unit" class="form-control">
                                                                <option value="gram">Gram</option>
                                                                <option value="kilogram">Kilogram</option>
                                                                <option value="ton">Ton</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
			
										<div class="row file">
																<div class="col-lg-12">
																	<div class="left-area">
																			<h4 class="heading"><?php echo e(__("Select File")); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																		<input type="file" name="file" required="">
																</div>
															</div>
			
															<div class="row link hidden">
																<div class="col-lg-4">
																	<div class="left-area">
																			<h4 class="heading"><?php echo e(__("Link")); ?>*</h4>
																	</div>
																</div>
																<div class="col-lg-7">
																		<textarea class="input-field" rows="4" name="link" placeholder="<?php echo e(__("Link")); ?>"></textarea> 
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
			
																	</div>
																</div>
																<div class="col-lg-12">
																	<div class="featured-keyword-area">
																		<div class="heading-area">
																			<h4 class="title"><?php echo e(__("Product License")); ?></h4>
																		</div>
			
																		<div class="feature-tag-top-filds" id="license-section">
																			<div class="license-area">
																				<span class="remove license-remove"><i class="fas fa-times"></i></span>
																					<div  class="row">
																					   <div class="col-lg-6">
																						  <input type="text" name="license[]" class="input-field" placeholder="<?php echo e(__("License Key")); ?>" required="">
																						</div>
																						<div class="col-lg-6">
																						   <input type="number" min="1" name="license_qty[]" class="input-field" placeholder="<?php echo e(__("License Quantity")); ?>" value="1">
																						</div>
																				   </div>
																			</div>
																		</div>
			
																		<a href="javascript:;" id="license-btn" class="add-fild-btn"><i class="icofont-plus"></i> <?php echo e(__("Add More Field")); ?></a>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading">
																			<?php echo e(__('Product Description')); ?>*
																		</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																	<div class="text-editor">
																		<textarea class="nic-edit" name="details"></textarea>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading">
																			<?php echo e(__('Product Buy/Return Policy')); ?>*
																		</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																	<div class="text-editor">
																		<textarea class="nic-edit" name="policy"></textarea>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="checkbox-wrapper">
																		<input type="checkbox" name="seo_check" value="1" class="checkclick" id="allowProductSEO" value="1">
																		<label for="allowProductSEO"><?php echo e(__('Allow Product SEO')); ?></label>
																	</div>
																</div>
															</div>
		
		
		
														<div class="showbox">
															<div class="row">
															  <div class="col-lg-12">
																<div class="left-area">
																	<h4 class="heading"><?php echo e(__('Meta Tags')); ?> *</h4>
																</div>
															  </div>
															  <div class="col-lg-12">
																<ul id="metatags" class="myTags">
																</ul>
															  </div>
															</div>
		  
															<div class="row">
															  <div class="col-lg-12">
																<div class="left-area">
																  <h4 class="heading">
																	  <?php echo e(__('Meta Description')); ?> *
																  </h4>
																</div>
															  </div>
															  <div class="col-lg-12">
																<div class="text-editor">
																  <textarea name="meta_description" class="input-field" placeholder="<?php echo e(__('Meta Description')); ?>"></textarea>
																</div>
															  </div>
															</div>
														  </div>

														  
														<div class="row">
															<div class="col-lg-12">
																<div class="left-area">
																		<h4 class="heading"><?php echo e(__("Platform")); ?> * </h4>
																		<p class="sub-heading"><?php echo e(__('(Optional)')); ?></p>
																</div>
															</div>
															<div class="col-lg-12">
																<input type="text" class="input-field" placeholder="<?php echo e(__("Enter Platform")); ?>" name="platform">
															</div>
														</div>

														<div class="row">
															<div class="col-lg-12">
																<div class="left-area">
																		<h4 class="heading"><?php echo e(__("Region")); ?> * </h4>
																		<p class="sub-heading"><?php echo e(__('(Optional)')); ?></p>
																</div>
															</div>
															<div class="col-lg-12">
																<input type="text" class="input-field" placeholder="<?php echo e(__("Enter Region")); ?>" name="region">
															</div>
														</div>

														<div class="row">
															<div class="col-lg-12">
																<div class="left-area">
																		<h4 class="heading"><?php echo e(__("License Type")); ?> * </h4>
																		<p class="sub-heading"><?php echo e(__("(Optional)")); ?></p>
																</div>
															</div>
															<div class="col-lg-12">
																<input type="text" class="input-field" placeholder="<?php echo e(__("Enter Type")); ?>" name="licence_type">
															</div>
														</div>

														  <input type="hidden" name="type" value="License">
												
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
									<div class="col-lg-4">
										<div class="add-product-content">
											<div class="row">
												<div class="col-lg-12">
													<div class="product-description">
														<div class="body-area">
															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading"><?php echo e(__('Feature Image')); ?> *</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																		<div class="panel panel-body">
																			<div class="span4 cropme text-center" id="landscape"
																				style="width: 100%; height: 285px; border: 1px dashed #ddd; background: #f1f1f1;">
																				<a href="javascript:;" id="crop-image" class=" mybtn1" style="">
																					<i class="icofont-upload-alt"></i> <?php echo e(__('Upload Image Here')); ?>

																				</a>
																			</div>
																		</div>
																</div>
															</div>
															<input type="hidden" id="feature_photo" name="photo" value="">
															<input type="file" name="gallery[]" class="hidden" id="uploadgallery" accept="image/*"
																multiple>
															<div class="row mb-4">
																<div class="col-lg-12 mb-2">
																	<div class="left-area">
																		<h4 class="heading">
																			<?php echo e(__('Product Gallery Images')); ?> *
																		</h4>
																	</div>
																</div>
																<div class="col-lg-12">
																	<a href="#" class="set-gallery" data-toggle="modal" data-target="#setgallery">
																		<i class="icofont-plus"></i> <?php echo e(__('Set Gallery')); ?>

																	</a>
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading">
																			<?php echo e(__('Product Current Price')); ?>*
																		</h4>
																		<p class="sub-heading">
																			(<?php echo e(__('In')); ?> <?php echo e($sign->name); ?>)
																		</p>
																	</div>
																</div>
																<div class="col-lg-12">
																	<input name="price" type="number" class="input-field" placeholder="<?php echo e(__('e.g 20')); ?>" step="0.1" required="" min="0">
																</div>
															</div>
			
															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																			<h4 class="heading"><?php echo e(__('Product Discount Price')); ?>*</h4>
																			<p class="sub-heading"><?php echo e(__('(Optional)')); ?></p>
																	</div>
																</div>
																<div class="col-lg-12">
																	<input name="previous_price" step="0.1" type="number" class="input-field" placeholder="<?php echo e(__('e.g 20')); ?>" min="0">
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
																		<h4 class="heading"><?php echo e(__('Youtube Video URL')); ?>*</h4>
																		<p class="sub-heading"><?php echo e(__('(Optional)')); ?></p>
																	</div>
																</div>
																<div class="col-lg-12">
																	<input  name="youtube" type="text" class="input-field" placeholder="<?php echo e(__('Enter Youtube Video URL')); ?>">
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																	<div class="left-area">
			
																	</div>
																</div>
																<div class="col-lg-12">
																	<div class="featured-keyword-area">
																		<div class="left-area">
																			<h4 class="title"><?php echo e(__('Feature Tags')); ?></h4>
																		</div>
			
																		<div class="feature-tag-top-filds" id="feature-section">
																			<div class="feature-area">
																				<span class="remove feature-remove"><i class="fas fa-times"></i></span>
																				<div class="row">
																					<div class="col-lg-6">
																					<input type="text" name="features[]" class="input-field" placeholder="<?php echo e(__('Enter Your Keyword')); ?>">
																					</div>
			
																					<div class="col-lg-6">
																						<div class="input-group colorpicker-component cp">
																						  <input type="text" name="colors[]" value="#000000" class="input-field cp"/>
																						  <span class="input-group-addon"><i></i></span>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>
			
																		<a href="javascript:;" id="feature-btn" class="add-fild-btn"><i class="icofont-plus"></i> <?php echo e(__('Add More Field')); ?></a>
																	</div>
																</div>
															</div>

															<div class="row">
																<div class="col-lg-12">
																  <div class="left-area">
																	  <h4 class="heading"><?php echo e(__('Tags')); ?> *</h4>
																  </div>
																</div>
																<div class="col-lg-12">
																  <ul id="tags" class="myTags">
																  </ul>
																</div>
															  </div>

															  <div class="row text-center">
																<div class="col-6 offset-3">
																	<button class="addProductSubmit-btn" type="submit"><?php echo e(__('Create Product')); ?></button>
																</div>
															</div>

														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</form>
						</div>

		<div class="modal fade" id="setgallery" tabindex="-1" role="dialog" aria-labelledby="setgallery" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered  modal-lg" role="document">
				<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalCenterTitle"><?php echo e(__("Image Gallery")); ?></h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="top-area">
						<div class="row">
							<div class="col-sm-6 text-right">
								<div class="upload-img-btn">
											<label for="image-upload" id="prod_gallery"><i class="icofont-upload-alt"></i><?php echo e(__("Upload File")); ?></label>
								</div>
							</div>
							<div class="col-sm-6">
								<a href="javascript:;" class="upload-done" data-dismiss="modal"> <i class="fas fa-check"></i> <?php echo e(__("Done")); ?></a>
							</div>
							<div class="col-sm-12 text-center">( <small><?php echo e(__("You can upload multiple Images.")); ?></small> )</div>
						</div>
					</div>
					<div class="gallery-images">
						<div class="selected-image">
							<div class="row">


							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>

		<script src="<?php echo e(asset('assets/admin/js/jquery.Jcrop.js')); ?>"></script>
		<script src="<?php echo e(asset('assets/admin/js/jquery.SimpleCropper.js')); ?>"></script>

<script type="text/javascript">
	
    (function($) {
		"use strict";

// Gallery Section Insert

  $(document).on('click', '.remove-img' ,function() {
    var id = $(this).find('input[type=hidden]').val();
    $('#galval'+id).remove();
    $(this).parent().parent().remove();
  });

  $(document).on('click', '#prod_gallery' ,function() {
    $('#uploadgallery').click();
     $('.selected-image .row').html('');
    $('#geniusform').find('.removegal').val(0);
  });
                                        
                                
  $("#uploadgallery").change(function(){
     var total_file=document.getElementById("uploadgallery").files.length;
     for(var i=0;i<total_file;i++)
     {
      $('.selected-image .row').append('<div class="col-sm-6">'+
                                        '<div class="img gallery-img">'+
                                            '<span class="remove-img"><i class="fas fa-times"></i>'+
                                            '<input type="hidden" value="'+i+'">'+
                                            '</span>'+
                                            '<a href="'+URL.createObjectURL(event.target.files[i])+'" target="_blank">'+
                                            '<img src="'+URL.createObjectURL(event.target.files[i])+'" alt="gallery image">'+
                                            '</a>'+
                                        '</div>'+
                                  '</div> '
                                      );
      $('#geniusform').append('<input type="hidden" name="galval[]" id="galval'+i+'" class="removegal" value="'+i+'">')
     }

  });

// Gallery Section Insert Ends


})(jQuery);

</script>

<script type="text/javascript">
	
    (function($) {
		"use strict";

$('.cropme').simpleCropper();

	})(jQuery);

</script>


<?php echo $__env->make('partials.admin.product.product-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/product/create/license.blade.php ENDPATH**/ ?>