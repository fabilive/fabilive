
<?php $__env->startSection('styles'); ?>

<link href="<?php echo e(asset('assets/admin/css/product.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('assets/admin/css/jquery.Jcrop.css')); ?>" rel="stylesheet" />
<link href="<?php echo e(asset('assets/admin/css/Jcrop-style.css')); ?>" rel="stylesheet" />

<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="heading"> <?php echo e(__('Edit Product')); ?><a class="add-btn" href="<?php echo e(url()->previous()); ?>"><i
							class="fas fa-arrow-left"></i> <?php echo e(__('Back')); ?></a></h4>
				<ul class="links">
					<li>
						<a href="<?php echo e(route('vendor.dashboard')); ?>"><?php echo e(__('Dashboard')); ?> </a>
					</li>
					<li>
						<a href="<?php echo e(route('vendor-prod-index')); ?>"><?php echo e(__('Products')); ?> </a>
					</li>
					<li>
						<a href="javascript:;"><?php echo e(__('Physical Product')); ?></a>
					</li>
					<li>
						<a href="javascript:;"><?php echo e(__('Edit')); ?></a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<form id="geniusform" action="<?php echo e(route('vendor-prod-update',$data->id)); ?>" method="POST"
		enctype="multipart/form-data">
		<?php echo e(csrf_field()); ?>

		<?php echo $__env->make('alerts.admin.form-both', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
		<div class="row">
			<div class="col-lg-8">
				<div class="add-product-content">
					<div class="row">
						<div class="col-lg-12">
							<div class="product-description">
								<div class="body-area">
									<div class="gocover"
										style="background: url(<?php echo e(asset('assets/images/'.$gs->admin_loader)); ?>) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
									</div>


									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading"><?php echo e(__("Product Name")); ?>* </h4>
												<p class="sub-heading"><?php echo e(__("(In Any Language)")); ?></p>
											</div>
										</div>
										<div class="col-lg-12">
											<input type="text" class="input-field" placeholder="<?php echo e(__(" Enter Product
												Name")); ?>" name="name" required="" value="<?php echo e($data->name); ?>">
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading"><?php echo e(__('Product Sku')); ?>* </h4>
											</div>
										</div>
										<div class="col-lg-12">
											<input type="text" class="input-field"
												placeholder="<?php echo e(__('Enter Product Sku')); ?>" name="sku" required=""
												value="<?php echo e($data->sku); ?>">
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
												<option><?php echo e(__('Select Category')); ?></option>
												<?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option data-href="<?php echo e(route('vendor-subcat-load',$cat->id)); ?>"
													value="<?php echo e($cat->id); ?>" <?php echo e($cat->id == $data->category_id ?
													"selected":""); ?> ><?php echo e($cat->name); ?></option>
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
											<select id="subcat" name="subcategory_id">
												<option value=""><?php echo e(__('Select Sub Category')); ?></option>
												<?php if($data->subcategory_id == null): ?>
												<?php $__currentLoopData = $data->category->subs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option data-href="<?php echo e(route('vendor-childcat-load',$sub->id)); ?>"
													value="<?php echo e($sub->id); ?>"><?php echo e($sub->name); ?></option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php else: ?>
												<?php $__currentLoopData = $data->category->subs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option data-href="<?php echo e(route('vendor-childcat-load',$sub->id)); ?>"
													value="<?php echo e($sub->id); ?>" <?php echo e($sub->id == $data->subcategory_id ?
													"selected":""); ?> ><?php echo e($sub->name); ?></option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php endif; ?>
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
											<select id="childcat" name="childcategory_id" <?php echo e($data->subcategory_id ==
												null ? "disabled":""); ?>>
												<option value=""><?php echo e(__('Select Child Category')); ?></option>
												<?php if($data->subcategory_id != null): ?>
												<?php if($data->childcategory_id == null): ?>
												<?php $__currentLoopData = $data->subcategory->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option value="<?php echo e($child->id); ?>"><?php echo e($child->name); ?></option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php else: ?>
												<?php $__currentLoopData = $data->subcategory->childs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<option value="<?php echo e($child->id); ?> " <?php echo e($child->id == $data->childcategory_id
													? "selected":""); ?>><?php echo e($child->name); ?></option>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php endif; ?>
												<?php endif; ?>
											</select>
										</div>
									</div>

                                    <!--<div class="row">-->
                                    <!--    <div class="col-lg-12">-->
                                    <!--        <div class="left-area">-->
                                    <!--            <h4 class="heading">-->
                                    <!--                <?php echo e(__('Product Location')); ?>* <small> (Enter city and area name here)</small>-->
                                    <!--            </h4>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--    <div class="col-lg-12">-->
                                    <!--        <div class="text-editor">-->
                                    <!--            <textarea class="nic-edit-p" name="product_location"><?php echo e(old('product_location', $data->product_location)); ?></textarea>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                        <?php
                                            $serviceAreas = \App\Models\ServiceArea::all();
                                        ?>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        <?php echo e(__('Product Location')); ?>* <small>(Select city and area)</small>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="text-editor">
                                                    <select name="product_location" id="service_area_id" class="form-control" required>
                                                        <option value="">-- Select Location --</option>
                                                        <?php $__currentLoopData = $serviceAreas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $area): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($area->id); ?>" <?php echo e($data->product_location == $area->id ? 'selected' : ''); ?>>
                                                                <?php echo e($area->location); ?>

                                                            </option>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- City -->
                                        
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        <?php echo e(__('Product City')); ?>* <small>(Select City)</small>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="text-editor">
                                                    <select name="product_city" class="form-control" required>
                                                        <option value="">-- Select City --</option>
                                                        <?php $__currentLoopData = $cities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <option value="<?php echo e($id); ?>" <?php echo e($data->product_city == $id ? 'selected' : ''); ?>>
                                                                <?php echo e($name); ?>

                                                            </option>
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
                                                        <small>(Enter weight and select unit)</small>
                                                    </h4>
                                                </div>
                                            </div>
                                        
                                            <div class="col-lg-12">
                                                <div class="form-group row align-items-center">
                                                    <!-- Delivery Fee Input -->
                                                    <div class="col-md-6">
                                                        <input type="number" 
                                                               name="delivery_fee" 
                                                               class="form-control" 
                                                               placeholder="Enter weight" 
                                                               step="0.01"
                                                               min="0"
                                                               value="<?php echo e(old('delivery_fee', $data->delivery_fee)); ?>">
                                                    </div>
                                        
                                                    <!-- Delivery Unit Dropdown -->
                                                    <div class="col-md-6">
                                                        <select name="delivery_unit" class="form-control">
                                                            <option value="gram" <?php echo e(old('delivery_unit', $data->delivery_unit) == 'gram' ? 'selected' : ''); ?>>Gram</option>
                                                            <option value="kilogram" <?php echo e(old('delivery_unit', $data->delivery_unit) == 'kilogram' ? 'selected' : ''); ?>>Kilogram</option>
                                                            <option value="ton" <?php echo e(old('delivery_unit', $data->delivery_unit) == 'ton' ? 'selected' : ''); ?>>Ton</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


									<?php
									$selectedAttrs = json_decode($data->attributes, true);
									// dd($selectedAttrs);
									?>

									
									<div id="catAttributes">
										<?php
										$catAttributes = !empty($data->category->attributes) ?
										$data->category->attributes : '';
										?>
										<?php if(!empty($catAttributes)): ?>
										<?php $__currentLoopData = $catAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $catAttribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<div class="row">
											<div class="col-lg-12">
												<div class="left-area">
													<h4 class="heading"><?php echo e($catAttribute->name); ?> *</h4>
												</div>
											</div>
											<div class="col-lg-12">
												<?php
												$i = 0;
												?>
												<?php $__currentLoopData = $catAttribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<?php
												$inName = $catAttribute->input_name;
												$checked = 0;
												?>
												<div class="row">
													<div class="col-lg-5">
														<div class="custom-control custom-checkbox">
															<input type="checkbox" id="<?php echo e($catAttribute->input_name); ?><?php echo e($option->id); ?>" name="<?php echo e($catAttribute->input_name); ?>[]" value="<?php echo e($option->name); ?>" class="custom-control-input attr-checkbox"
															<?php if(is_array($selectedAttrs) && array_key_exists($catAttribute->input_name,$selectedAttrs)): ?>
															<?php if(is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"])): ?>
																checked
															   <?php
																   $checked = 1;
															   ?>
															<?php endif; ?>
														<?php endif; ?>
															>
															<label class="custom-control-label" for="<?php echo e($catAttribute->input_name); ?><?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
													   </div>
													</div>

													<div
														class="col-lg-7 <?php echo e($catAttribute->price_status == 0 ? 'd-none' : ''); ?>">
														<div class="row">
															<div class="col-2">
																+
															</div>
															<div class="col-10">
																<div class="price-container">
																	<span class="price-curr"><?php echo e($sign->sign); ?></span>
																	<input type="text" class="input-field price-input"
																		id="<?php echo e($catAttribute->input_name); ?><?php echo e($option->id); ?>_price"
																		data-name="<?php echo e($catAttribute->input_name); ?>_price[]"
																		placeholder="0.00 (Additional Price)"
																		value="<?php echo e(!empty($selectedAttrs["
																		$inName"]['prices'][$i]) && $checked==1 ?
																		round($selectedAttrs["$inName"]['prices'][$i]*
																		$sign->value , 2) : ''); ?>">
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php
												if ($checked == 1) {
												$i++;
												}
												?>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</div>
										</div>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										<?php endif; ?>
									</div>
									

									
									<div id="subcatAttributes">
										<?php
										$subAttributes = !empty($data->subcategory->attributes) ?
										$data->subcategory->attributes : '';
										?>
										<?php if(!empty($subAttributes)): ?>
										<?php $__currentLoopData = $subAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subAttribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<div class="row">
											<div class="col-lg-12 mb-2">
												<div class="left-area">
													<h4 class="heading"><?php echo e($subAttribute->name); ?> *</h4>
												</div>
											</div>
											<div class="col-lg-12">
												<?php
												$i = 0;
												?>
												<?php $__currentLoopData = $subAttribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<?php
												$inName = $subAttribute->input_name;
												$checked = 0;
												?>

												<div class="row">
													<div class="col-lg-5">
														<div class="custom-control custom-checkbox">
															<input type="checkbox" id="<?php echo e($subAttribute->input_name); ?><?php echo e($option->id); ?>" name="<?php echo e($subAttribute->input_name); ?>[]" value="<?php echo e($option->name); ?>" class="custom-control-input attr-checkbox"
															<?php if(is_array($selectedAttrs) && array_key_exists($subAttribute->input_name,$selectedAttrs)): ?>
															<?php
															$inName = $subAttribute->input_name;
															?>
															<?php if(is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"])): ?>
															checked
															<?php
																$checked = 1;
															?>
															<?php endif; ?>
															<?php endif; ?>
															>

															<label class="custom-control-label" for="<?php echo e($subAttribute->input_name); ?><?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
														</div>
													</div>
													<div
														class="col-lg-7 <?php echo e($subAttribute->price_status == 0 ? 'd-none' : ''); ?>">
														<div class="row">
															<div class="col-2">
																+
															</div>
															<div class="col-10">
																<div class="price-container">
																	<span class="price-curr"><?php echo e($sign->sign); ?></span>
																	<input type="text" class="input-field price-input"
																		id="<?php echo e($subAttribute->input_name); ?><?php echo e($option->id); ?>_price"
																		data-name="<?php echo e($subAttribute->input_name); ?>_price[]"
																		placeholder="0.00 (Additional Price)"
																		value="value=" <?php echo e(!empty($selectedAttrs["$inName"]['prices'][$i])
																		&& $checked==1 ?
																		round($selectedAttrs["$inName"]['prices'][$i]*
																		$sign->value , 2) : ''); ?>"">
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php
												if ($checked == 1) {
												$i++;
												}
												?>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</div>
										</div>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										<?php endif; ?>
									</div>
									

									
									<div id="childcatAttributes">
										<?php
										$childAttributes = !empty($data->childcategory->attributes) ?
										$data->childcategory->attributes : '';
										?>
										<?php if(!empty($childAttributes)): ?>
										<?php $__currentLoopData = $childAttributes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $childAttribute): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
										<div class="row">
											<div class="col-lg-12 mb-2">
												<div class="left-area">
													<h4 class="heading"><?php echo e($childAttribute->name); ?> *</h4>
												</div>
											</div>
											<div class="col-lg-12">
												<?php
												$i = 0;
												?>
												<?php $__currentLoopData = $childAttribute->attribute_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<?php
												$inName = $childAttribute->input_name;
												$checked = 0;
												?>
												<div class="row">
													<div class="col-lg-5">
														<div class="custom-control custom-checkbox">
															<input type="checkbox" id="<?php echo e($childAttribute->input_name); ?><?php echo e($option->id); ?>" name="<?php echo e($childAttribute->input_name); ?>[]" value="<?php echo e($option->name); ?>" class="custom-control-input attr-checkbox"
															<?php if(is_array($selectedAttrs) && array_key_exists($childAttribute->input_name,$selectedAttrs)): ?>
																<?php
																	$inName = $childAttribute->input_name;
																?>
																<?php if(is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"])): ?>
																	checked
																	<?php
																		$checked = 1;
																	?>
																<?php endif; ?>
															<?php endif; ?>																										
															
															>

															<label class="custom-control-label" for="<?php echo e($childAttribute->input_name); ?><?php echo e($option->id); ?>"><?php echo e($option->name); ?></label>
														</div>
													</div>


													<div
														class="col-lg-7 <?php echo e($childAttribute->price_status == 0 ? 'd-none' : ''); ?>">
														<div class="row">
															<div class="col-2">
																+
															</div>
															<div class="col-10">
																<div class="price-container">
																	<span class="price-curr"><?php echo e($sign->sign); ?></span>
																	<input type="text" class="input-field price-input"
																		id="<?php echo e($childAttribute->input_name); ?><?php echo e($option->id); ?>_price"
																		data-name="<?php echo e($childAttribute->input_name); ?>_price[]"
																		placeholder="0.00 (Additional Price)"
																		value="value=" <?php echo e(!empty($selectedAttrs["$inName"]['prices'][$i])
																		&& $checked==1 ?
																		round($selectedAttrs["$inName"]['prices'][$i]*
																		$sign->value , 2) : ''); ?>"">
																</div>
															</div>
														</div>
													</div>
												</div>
												<?php
												if ($checked == 1) {
												$i++;
												}
												?>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
											</div>

										</div>
										<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
										<?php endif; ?>
									</div>
									
									<div class="<?php echo e($data->measure == null ? 'showbox' : ''); ?>">
										<div class="row">
											<div class="col-lg-6">
												<div class="left-area">
													<h4 class="heading"><?php echo e(__('Product Measurement')); ?>*</h4>
												</div>
											</div>
											<div class="col-lg-6">
												<select id="product_measure">
													<option value="" <?php echo e($data->measure == null ? 'selected':''); ?>><?php echo e(__('None')); ?></option>
													<option value="Gram" <?php echo e($data->measure == 'Gram' ? 'selected':''); ?>><?php echo e(__('Gram')); ?></option>
													<option value="Kilogram" <?php echo e($data->measure == 'Kilogram' ?
														'selected':''); ?>><?php echo e(__('Kilogram')); ?></option>
													<option value="Litre" <?php echo e($data->measure == 'Litre' ?
														'selected':''); ?>><?php echo e(__('Litre')); ?></option>
													<option value="Pound" <?php echo e($data->measure == 'Pound' ?
														'selected':''); ?>><?php echo e(__('Pound')); ?></option>
													<option value="Custom" <?php echo e(in_array($data->measure,explode(',',
														'Gram,Kilogram,Litre,Pound')) ? '' : 'selected'); ?>><?php echo e(__('Custom')); ?></option>
												</select>
											</div>
											
											<div class="col-lg-6 <?php echo e(in_array($data->measure,explode(',', 'Gram,Kilogram,Litre,Pound')) ? 'hidden' : ''); ?>"
												id="measure">
												<input name="measure" type="text" id="measurement" class="input-field"
													placeholder="Enter Unit" value="<?php echo e($data->measure); ?>">
											</div>
										</div>
									</div>


									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">

											</div>
										</div>
										<div class="col-lg-12">
											<ul class="list">
												<li>
													<input name="size_check" class="stock-check"" type=" checkbox"
														id="size-check" value="1" <?php echo e(!empty($data->size) ? "checked":""); ?>>
													<label for="size-check" class="stock-text"><?php echo e(__('Manage Stock')); ?></label>
												</li>
											</ul>
										</div>
									</div>



									<div class="<?php echo e(!empty($data->size) ? "":" showbox"); ?>" id="size-display">
										<div class="row">
											<div class="col-lg-12">
											</div>
											<div class="col-lg-12">
												<div class="product-size-details" id="size-section">
													<?php if(!empty($data->size)): ?>
													<?php $__currentLoopData = $data->size; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

													<div class="size-area">
														<span class="remove size-remove"><i
																class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-md-3 col-sm-6">
																<label>
																	<?php echo e(__('Size Name')); ?> :
																	<span>
																		<?php echo e(__('(eg. S,M,L,XL,3XL,4XL)')); ?>

																	</span>
																</label>
																<select name="size[]" class="input-field size-name">
																	<?php $__currentLoopData = array_unique($data->size); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<option value="<?php echo e($dt); ?>" <?php echo e($dt==$data1
																		? 'selected' : ''); ?>><?php echo e($dt); ?></option>
																	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																</select>
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	<?php echo e(__('Size Qty')); ?> :
																	<span>
																		<?php echo e(__('(Quantity of this size)')); ?>

																	</span>
																</label>
																<input type="number" name="size_qty[]" required
																	class="input-field"
																	placeholder="<?php echo e(__('Size Qty')); ?>"
																	value="<?php echo e($data->size_qty[$key]); ?>" min="1">
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	<?php echo e(__('Size Price')); ?> :
																	<span>
																		<?php echo e(__('(Added with base price)')); ?>

																	</span>
																</label>
																<input type="number" name="size_price[]" required
																	class="input-field"
																	placeholder="<?php echo e(__('Size Price')); ?>"
																	value="<?php echo e(round($data->size_price[$key] * $sign->value , 2)); ?>"
																	min="0">
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	<?php echo e(__('Size Color')); ?> :
																	<span>
																		<?php echo e(__('(Select color of this size)')); ?>

																	</span>
																</label>
																<select name="color[]" class="input-field color-name">
																	<?php $__currentLoopData = array_unique($data->color); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
																	<option value="<?php echo e($ct); ?>"
																		style="background-color:<?php echo e($ct); ?>" <?php echo e($ct==$data->color[$key] ? 'selected' : ''); ?>>
																	</option>
																	<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
																</select>
															</div>

														</div>
													</div>

													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

													<?php else: ?>

													<div class="size-area">
														<span class="remove size-remove"><i
																class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-md-3 col-sm-6">
																<label>
																	<?php echo e(__('Size Name')); ?> :
																	<span>
																		<?php echo e(__('(eg. S,M,L,XL,3XL,4XL)')); ?>

																	</span>
																</label>
																<select name="size[]"
																	class="input-field size-name"></select>
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	<?php echo e(__('Size Qty')); ?> :
																	<span>
																		<?php echo e(__('(Quantity of this size)')); ?>

																	</span>
																</label>
																<input type="number" name="size_qty[]"
																	class="input-field"
																	placeholder="<?php echo e(__('Size Qty')); ?>" value="1"
																	min="1">
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	<?php echo e(__('Size Price')); ?> :
																	<span>
																		<?php echo e(__('(Added with base price)')); ?>

																	</span>
																</label>
																<input type="number" name="size_price[]"
																	class="input-field"
																	placeholder="<?php echo e(__('Size Price')); ?>" value="0"
																	min="0">
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	<?php echo e(__('Size Color')); ?> :
																	<span>
																		<?php echo e(__('(Select color of this size)')); ?>

																	</span>
																</label>
																<select name="color[]"
																	class="input-field color-name"></select>
															</div>

														</div>
													</div>

													<?php endif; ?>

												</div>

												<a href="javascript:;" id="size-btn" class="add-more"><i
														class="fas fa-plus"></i><?php echo e(__('Add More')); ?> </a>
											</div>
										</div>
									</div>


									<div class="row <?php echo e(!empty($data->size) ? " d-none":""); ?>" id="default_stock">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading"><?php echo e(__('Product Stock')); ?>*</h4>
												<p class="sub-heading"><?php echo e(__('(Leave Empty will Show Always Available)')); ?></p>
											</div>
										</div>
										<div class="col-lg-12">
											<input name="stock" type="number" class="input-field" placeholder="e.g 20"
												value="<?php echo e($data->stock); ?>" min="0">
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
												<textarea name="details"
													class="nic-edit"><?php echo e($data->details); ?></textarea>
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
												<textarea name="policy" class="nic-edit"><?php echo e($data->policy); ?></textarea>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="checkbox-wrapper">
												<input type="checkbox" name="seo_check" value="1" class="checkclick"
													id="allowProductSEO" <?php echo e(($data->meta_tag != null ||
												strip_tags($data->meta_description) != null) ? 'checked':''); ?>>
												<label for="allowProductSEO"><?php echo e(__('Allow Product SEO')); ?></label>
											</div>
										</div>
									</div>

									<div class="<?php echo e(($data->meta_tag == null && strip_tags($data->meta_description) == null) ? "
										showbox":""); ?>">
										<div class="row">
											<div class="col-lg-12">
												<div class="left-area">
													<h4 class="heading"><?php echo e(__('Meta Tags')); ?> *</h4>
												</div>
											</div>
											<div class="col-lg-12">
												<ul id="metatags" class="myTags">
													<?php if(!empty($data->meta_tag)): ?>
													<?php $__currentLoopData = $data->meta_tag; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
													<li><?php echo e($element); ?></li>
													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													<?php endif; ?>
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
													<textarea name="meta_description" class="input-field"
														placeholder="<?php echo e(__('Details')); ?>"><?php echo e($data->meta_description); ?></textarea>
												</div>
											</div>
										</div>
									</div>


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
													<a href="javascript:;" id="crop-image"
														class="d-inline-block mybtn1">
														<i class="icofont-upload-alt"></i> <?php echo e(__('Upload Image Here')); ?>

													</a>
												</div>
											</div>
										</div>
									</div>

									<input type="hidden" id="feature_photo" name="photo" value="<?php echo e($data->photo); ?>"
										accept="image/*">
									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">
													<?php echo e(__('Product Gallery Images')); ?> *
												</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<a href="javascript" class="set-gallery" data-toggle="modal"
												data-target="#setgallery">
												<input type="hidden" value="<?php echo e($data->id); ?>">
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
											<input name="price" type="number" class="input-field" placeholder="e.g 20"
												step="0.1" min="0" value="<?php echo e(round($data->price * $sign->value , 2)); ?>"
												required="">
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
											<input name="previous_price" step="0.1" type="number" class="input-field"
												placeholder="e.g 20"
												value="<?php echo e(round($data->previous_price * $sign->value , 2)); ?>" min="0">
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
											<input name="youtube" type="text" class="input-field"
												placeholder="Enter Youtube Video URL" value="<?php echo e($data->youtube); ?>">
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
													<?php if(!empty($data->features)): ?>

													<?php $__currentLoopData = $data->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

													<div class="feature-area">
														<span class="remove feature-remove"><i
																class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-lg-6">
																<input type="text" name="features[]" class="input-field"
																	placeholder="<?php echo e(__('Enter Your Keyword')); ?>"
																	value="<?php echo e($data->features[$key]); ?>">
															</div>

															<div class="col-lg-6">
																<div class="input-group colorpicker-component cp">
																	<input type="text" name="colors[]"
																		value="<?php echo e($data->colors[$key]); ?>"
																		class="input-field cp" />
																	<span class="input-group-addon"><i></i></span>
																</div>
															</div>
														</div>
													</div>

													<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
													<?php else: ?>

													<div class="feature-area">
														<span class="remove feature-remove"><i
																class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-lg-6">
																<input type="text" name="features[]" class="input-field"
																	placeholder="<?php echo e(__('Enter Your Keyword')); ?>">
															</div>

															<div class="col-lg-6">
																<div class="input-group colorpicker-component cp">
																	<input type="text" name="colors[]" value="#000000"
																		class="input-field cp" />
																	<span class="input-group-addon"><i></i></span>
																</div>
															</div>
														</div>
													</div>

													<?php endif; ?>
												</div>

												<a href="javascript:;" id="feature-btn" class="add-fild-btn"><i
														class="icofont-plus"></i> <?php echo e(__('Add More Field')); ?></a>
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
												<?php if(!empty($data->tags)): ?>
												<?php $__currentLoopData = $data->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $element): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
												<li><?php echo e($element); ?></li>
												<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
												<?php endif; ?>
											</ul>
										</div>
									</div>

									<div class="row text-center">
										<div class="col-6 offset-3">
											<button class="addProductSubmit-btn" type="submit"><?php echo e(__('Save Product')); ?></button>
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
				<h5 class="modal-title" id="exampleModalCenterTitle"><?php echo e(__('Image Gallery')); ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">×</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="top-area">
					<div class="row">
						<div class="col-sm-6 text-right">
							<div class="upload-img-btn">
								<form method="POST" enctype="multipart/form-data" id="form-gallery">
									<?php echo csrf_field(); ?>
									<input type="hidden" id="pid" name="product_id" value="">
									<input type="file" name="gallery[]" class="hidden" id="uploadgallery"
										accept="image/*" multiple>
									<label for="image-upload" id="prod_gallery"><i class="icofont-upload-alt"></i><?php echo e(__('Upload File')); ?></label>
								</form>
							</div>
						</div>
						<div class="col-sm-6">
							<a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
									class="fas fa-check"></i> <?php echo e(__('Done')); ?></a>
						</div>
						<div class="col-sm-12 text-center">( <small><?php echo e(__('You can upload multiple Images.')); ?></small>
							)</div>
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

<script type="text/javascript">
	$(function($) {
		"use strict";

// Gallery Section Update

    $(document).on("click", ".set-gallery" , function(){
        var pid = $(this).find('input[type=hidden]').val();
        $('#pid').val(pid);
        $('.selected-image .row').html('');
            $.ajax({
                    type: "GET",
                    url:"<?php echo e(route('vendor-gallery-show')); ?>",
                    data:{id:pid},
                    success:function(data){
                      if(data[0] == 0)
                      {
	                    $('.selected-image .row').addClass('justify-content-center');
	      				$('.selected-image .row').html('<h3><?php echo e(__('No Images Found.')); ?></h3>');
     				  }
                      else {
	                    $('.selected-image .row').removeClass('justify-content-center');
	      				$('.selected-image .row h3').remove();
                          var arr = $.map(data[1], function(el) {
                          return el });

                          for(var k in arr)
                          {
        				$('.selected-image .row').append('<div class="col-sm-6">'+
                                        '<div class="img gallery-img">'+
                                            '<span class="remove-img"><i class="fas fa-times"></i>'+
                                            '<input type="hidden" value="'+arr[k]['id']+'">'+
                                            '</span>'+
                                            '<a href="'+'<?php echo e(asset('assets/images/galleries').'/'); ?>'+arr[k]['photo']+'" target="_blank">'+
                                            '<img src="'+'<?php echo e(asset('assets/images/galleries').'/'); ?>'+arr[k]['photo']+'" alt="gallery image">'+
                                            '</a>'+
                                        '</div>'+
                                  	'</div>');
                          }
                       }

                    }
                  });
      });


  $(document).on('click', '.remove-img' ,function() {
    var id = $(this).find('input[type=hidden]').val();
    $(this).parent().parent().remove();
	    $.ajax({
	        type: "GET",
	        url:"<?php echo e(route('vendor-gallery-delete')); ?>",
	        data:{id:id}
	    });
  });

  $(document).on('click', '#prod_gallery' ,function() {
    $('#uploadgallery').click();
  });


  $("#uploadgallery").change(function(){
    $("#form-gallery").submit();
  });

  $(document).on('submit', '#form-gallery' ,function() {
		  $.ajax({
		   url:"<?php echo e(route('vendor-gallery-store')); ?>",
		   method:"POST",
		   data:new FormData(this),
		   dataType:'JSON',
		   contentType: false,
		   cache: false,
		   processData: false,
		   success:function(data)
		   {
		    if(data != 0)
		    {
	                    $('.selected-image .row').removeClass('justify-content-center');
	      				$('.selected-image .row h3').remove();
		        var arr = $.map(data, function(el) {
		        return el });
		        for(var k in arr)
		           {
        				$('.selected-image .row').append('<div class="col-sm-6">'+
                                        '<div class="img gallery-img">'+
                                            '<span class="remove-img"><i class="fas fa-times"></i>'+
                                            '<input type="hidden" value="'+arr[k]['id']+'">'+
                                            '</span>'+
                                            '<a href="'+'<?php echo e(asset('assets/images/galleries').'/'); ?>'+arr[k]['photo']+'" target="_blank">'+
                                            '<img src="'+'<?php echo e(asset('assets/images/galleries').'/'); ?>'+arr[k]['photo']+'" alt="gallery image">'+
                                            '</a>'+
                                        '</div>'+
                                  	'</div>');
		            }
		    }

		                       }

		  });
		  return false;
 });


// Gallery Section Update Ends

})(jQuery);

</script>

<script src="<?php echo e(asset('assets/admin/js/jquery.Jcrop.js')); ?>"></script>

<script src="<?php echo e(asset('assets/admin/js/jquery.SimpleCropper.js')); ?>"></script>

<script type="text/javascript">
	(function($) {
		"use strict";

$('.cropme').simpleCropper();

})(jQuery);

</script>


<script type="text/javascript">
	(function($) {
		"use strict";

  $(document).ready(function() {

    let html = `<img src="<?php echo e(empty($data->photo) ? asset('assets/images/noimage.png') : (filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('assets/images/products/'.$data->photo))); ?>" alt="">`;
    $(".span4.cropme").html(html);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

  })(jQuery);


  (function($) {
	"use strict";
  $('.ok').on('click', function () {



 setTimeout(
    function() {


  	var img = $('#feature_photo').val();

      $.ajax({
        url: "<?php echo e(route('vendor-prod-upload-update',$data->id)); ?>",
        type: "POST",
        data: {"image":img},
        success: function (data) {
          if (data.status) {
            $('#feature_photo').val(data.file_name);
          }
          if ((data.errors)) {
            for(var error in data.errors)
            {
              $.notify(data.errors[error], "danger");
            }
          }
        }
      });

    }, 1000);



    });

})(jQuery);

</script>

<script type="text/javascript">
	(function($) {
		"use strict";

  $('#imageSource').on('change', function () {
    var file = this.value;
      if (file == "file"){
          $('#f-file').show();
          $('#f-link').hide();
      }
      if (file == "link"){
          $('#f-file').hide();
          $('#f-link').show();
      }
  });


  $(document).on('click','#size-check',function(){
	if($(this).is(':checked')){
		$('#default_stock').addClass('d-none')
	}else{
		$('#default_stock').removeClass('d-none');
	}
})
})(jQuery);
</script>


<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('#service_area_id').select2({
            placeholder: 'Select Service Area',
            allowClear: true,
            width: 'resolve'
        });
    });
</script>
<style>
    .displayBoxNone .select2-container--default .select2-selection--single{
            border: none !important;
    }
    .displayBoxNone .select2-container--default .select2-selection--single .select2-selection__rendered {
        margin-top: 7px;
    }
    .displayBoxNone .select2-container {
        border: 1px solid #bdccdb;
        border-radius: 0.25rem;
        height: 45px;
    }
    .select2-container--default .select2-selection--single {
        height: 38px;
        padding: 5px;
    }
</style>
<?php echo $__env->make('partials.admin.product.product-scripts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.vendor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/vendor/product/edit/physical.blade.php ENDPATH**/ ?>