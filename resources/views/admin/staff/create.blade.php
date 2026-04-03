@extends('layouts.load')
@section('content')

						<div class="content-area">
							<div class="add-product-content1">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">
                        					@include('alerts.admin.form-error') 
											<form id="geniusformdata" action="{{ route('admin-staff-store') }}" method="POST" enctype="multipart/form-data">
												{{csrf_field()}}

						                        <div class="row">
						                          <div class="col-lg-4">
						                            <div class="left-area">
						                                <h4 class="heading">{{ __('Staff Profile Image') }} *</h4>
						                            </div>
						                          </div>
						                          <div class="col-lg-7">
						                            <div class="img-upload">
						                                <div id="image-preview" class="img-preview" style="background: url({{ asset('assets/images/noimage.png') }});">
						                                    <label for="image-upload" class="img-label" id="image-label"><i class="icofont-upload-alt"></i>{{ __('Upload Image') }}</label>
						                                    <input type="file" name="photo" class="img-upload" id="image-upload">
						                                  </div>
						                            </div>
						                          </div>
						                        </div>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __('Name') }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="name" placeholder="{{ __("User Name") }}" required="" value="">
													</div>
												</div>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __("Email") }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="email" class="input-field" name="email" placeholder="{{ __("Email Address") }}" required="" value="">
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __("Phone") }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="phone" placeholder="{{ __("Phone Number") }}" required="" value="">
													</div>
												</div>


												<hr>
												<h5 class="text-center">{{ __('Permissions') }}</h5>
												<hr>

												<div class="row justify-content-center">

														<div class="col-lg-4 d-flex justify-content-between">
															<label class="control-label">{{ __('Orders') }} *</label>
															<label class="switch">
																<input type="checkbox" name="section[]" value="orders">
																<span class="slider round"></span>
															</label>
														</div>

														<div class="col-lg-2"></div>

														<div class="col-lg-4 d-flex justify-content-between">
															<label class="control-label">{{ __('Manage Categories') }} *</label>
															<label class="switch">
																<input type="checkbox" name="section[]" value="categories">
																<span class="slider round"></span>
															</label>
														</div>

												</div>

												<div class="row justify-content-center">

													<div class="col-lg-4 d-flex justify-content-between">
														<label class="control-label">{{ __('Manage country') }} *</label>
														<label class="switch">
															<input type="checkbox" name="section[]" value="manage-country">
															<span class="slider round"></span>
														</label>
													</div>

													<div class="col-lg-2"></div>

													<div class="col-lg-4 d-flex justify-content-between">
														<label class="control-label">{{ __('Tax Calculate') }} *</label>
														<label class="switch">
															<input type="checkbox" name="section[]" value="earning">
															<span class="slider round"></span>
														</label>
													</div>
											</div>

												<div class="row justify-content-center">

													<div class="col-lg-4 d-flex justify-content-between">
														<label class="control-label">{{ __('Products') }} *</label>
														<label class="switch">
															<input type="checkbox" name="section[]" value="products">
															<span class="slider round"></span>
														</label>
													</div>

													<div class="col-lg-2"></div>

													<div class="col-lg-4 d-flex justify-content-between">
														<label class="control-label">{{ __('Affiliate Products') }} *</label>
														<label class="switch">
															<input type="checkbox" name="section[]" value="affilate_products">
															<span class="slider round"></span>
														</label>
													</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Bulk Product Upload') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="bulk_product_upload">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Product Discussion') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="product_discussion">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Set Coupons') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="set_coupons">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Customers') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="customers">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Customer Deposits') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="customer_deposits">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Vendors') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="vendors">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Vendor Subscriptions') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="vendor_subscriptions">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Vendor Verifications') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="vendor_verifications">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Vendor Subscription Plans') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="vendor_subscription_plans">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Messages') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="messages">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('General Settings') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="general_settings">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Home Page Settings') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="home_page_settings">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Menu Page Settings') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="menu_page_settings">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Email Settings') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="emails_settings">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Payment Settings') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="payment_settings">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Social Settings') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="social_settings">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Language Settings') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="language_settings">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('SEO Tools') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="seo_tools">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

											<div class="row justify-content-center">

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Manage Staffs') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="manage_staffs">
														<span class="slider round"></span>
													</label>
												</div>

												<div class="col-lg-2"></div>

												<div class="col-lg-4 d-flex justify-content-between">
													<label class="control-label">{{ __('Subscribers') }} *</label>
													<label class="switch">
														<input type="checkbox" name="section[]" value="subscribers">
														<span class="slider round"></span>
													</label>
												</div>

											</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __("Password") }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="password" class="input-field" name="password" placeholder="{{ __("Password") }}" required="" value="">
													</div>
												</div>

						                        <div class="row">
						                          <div class="col-lg-4">
						                            <div class="left-area">
						                              
						                            </div>
						                          </div>
						                          <div class="col-lg-7">
						                            <button class="addProductSubmit-btn" type="submit">{{ __("Create Staff") }}</button>
						                          </div>
						                        </div>

											</form>


											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

@endsection