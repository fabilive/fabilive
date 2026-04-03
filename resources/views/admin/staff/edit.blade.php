@extends('layouts.load')
@section('content')

						<div class="content-area">
							<div class="add-product-content1">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">
                        					@include('alerts.admin.form-error') 
											<form id="geniusformdata" action="{{ route('admin-staff-update',$data->id) }}" method="POST" enctype="multipart/form-data">
												{{csrf_field()}}

						                        <div class="row">
						                          <div class="col-lg-4">
						                            <div class="left-area">
						                                <h4 class="heading">{{ __('Staff Profile Image') }} *</h4>
						                            </div>
						                          </div>
						                          <div class="col-lg-7">
						                            <div class="img-upload">
						                                <div id="image-preview" class="img-preview" style="background: url({{ $data->photo ? asset('assets/images/admins/'.$data->photo) : asset('assets/images/noimage.png') }});">
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
														<input type="text" class="input-field" name="name" placeholder="{{ __("User Name") }}" required="" value="{{ $data->name }}">
													</div>
												</div>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __("Email") }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="email" class="input-field" name="email" placeholder="{{ __("Email Address") }}" required="" value="{{ $data->email }}">
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __("Phone") }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="phone" placeholder="{{ __("Phone Number") }}" required="" value="{{ $data->phone }}">
													</div>
												</div>

												@php
													$perms = $data->section ? explode(" , ", $data->section) : [];
												@endphp

                                                <div class="row justify-content-center">
                                                    <div class="col-lg-4"></div>
                                                    <div class="col-lg-7 text-center">
                                                        <button type="button" class="btn btn-primary" data-toggle="collapse" data-target="#permission-box">
                                                            <i class="fas fa-cog"></i> {{ __('Manage Permissions') }}
                                                        </button>
                                                    </div>
                                                </div>

                                                <div id="permission-box" class="collapse mt-3">
                                                    <hr>
                                                    <h5 class="text-center">{{ __('Dashboard Permissions') }}</h5>
                                                    <hr>

                                                    <div class="row justify-content-center">

                                                            <div class="col-lg-4 d-flex justify-content-between">
                                                                <label class="control-label">{{ __('Orders') }} *</label>
                                                                <label class="switch">
                                                                    <input type="checkbox" name="section[]" value="orders" {{ in_array('orders', $perms) ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>

                                                            <div class="col-lg-2"></div>

                                                            <div class="col-lg-4 d-flex justify-content-between">
                                                                <label class="control-label">{{ __('Manage Categories') }} *</label>
                                                                <label class="switch">
                                                                    <input type="checkbox" name="section[]" value="categories" {{ in_array('categories', $perms) ? 'checked' : '' }}>
                                                                    <span class="slider round"></span>
                                                                </label>
                                                            </div>

                                                    </div>

                                                    <div class="row justify-content-center">

                                                        <div class="col-lg-4 d-flex justify-content-between">
                                                            <label class="control-label">{{ __('Manage country') }} *</label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="section[]" value="manage-country" {{ in_array('manage-country', $perms) ? 'checked' : '' }}>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-2"></div>

                                                        <div class="col-lg-4 d-flex justify-content-between">
                                                            <label class="control-label">{{ __('Tax Calculate') }} *</label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="section[]" value="earning" {{ in_array('earning', $perms) ? 'checked' : '' }}>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>
                                                </div>

                                                    <div class="row justify-content-center">

                                                        <div class="col-lg-4 d-flex justify-content-between">
                                                            <label class="control-label">{{ __('Products') }} *</label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="section[]" value="products" {{ in_array('products', $perms) ? 'checked' : '' }}>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>

                                                        <div class="col-lg-2"></div>

                                                        <div class="col-lg-4 d-flex justify-content-between">
                                                            <label class="control-label">{{ __('Affiliate Products') }} *</label>
                                                            <label class="switch">
                                                                <input type="checkbox" name="section[]" value="affilate_products" {{ in_array('affilate_products', $perms) ? 'checked' : '' }}>
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Bulk Product Upload') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="bulk_product_upload" {{ in_array('bulk_product_upload', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Product Discussion') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="product_discussion" {{ in_array('product_discussion', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Set Coupons') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="set_coupons" {{ in_array('set_coupons', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Customers') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="customers" {{ in_array('customers', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Customer Deposits') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="customer_deposits" {{ in_array('customer_deposits', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Vendors') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="vendors" {{ in_array('vendors', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Vendor Subscriptions') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="vendor_subscriptions" {{ in_array('vendor_subscriptions', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Vendor Verifications') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="vendor_verifications" {{ in_array('vendor_verifications', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Vendor Subscription Plans') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="vendor_subscription_plans" {{ in_array('vendor_subscription_plans', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Messages') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="messages" {{ in_array('messages', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('General Settings') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="general_settings" {{ in_array('general_settings', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Home Page Settings') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="home_page_settings" {{ in_array('home_page_settings', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Menu Page Settings') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="menu_page_settings" {{ in_array('menu_page_settings', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Email Settings') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="emails_settings" {{ in_array('emails_settings', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Payment Settings') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="payment_settings" {{ in_array('payment_settings', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Social Settings') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="social_settings" {{ in_array('social_settings', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Language Settings') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="language_settings" {{ in_array('language_settings', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('SEO Tools') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="seo_tools" {{ in_array('seo_tools', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Manage Staffs') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="manage_staffs" {{ in_array('manage_staffs', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Subscribers') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="subscribers" {{ in_array('subscribers', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <hr>
                                                <h5 class="text-center">{{ __('Support System Permissions') }}</h5>
                                                <hr>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Live Chats') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="support_live_chats" {{ in_array('support_live_chats', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Knowledge Base') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="support_kb" {{ in_array('support_kb', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>

                                                <div class="row justify-content-center">

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('Bot Rules') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="support_bot_rules" {{ in_array('support_bot_rules', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                    <div class="col-lg-2"></div>

                                                    <div class="col-lg-4 d-flex justify-content-between">
                                                        <label class="control-label">{{ __('AI Dashboard') }} *</label>
                                                        <label class="switch">
                                                            <input type="checkbox" name="section[]" value="support_ai_dashboard" {{ in_array('support_ai_dashboard', $perms) ? 'checked' : '' }}>
                                                            <span class="slider round"></span>
                                                        </label>
                                                    </div>

                                                </div>
                                            </div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __("Password") }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="password" class="input-field" name="password" placeholder="{{ __("Password") }}" value="">
													</div>
												</div>

						                        <div class="row">
						                          <div class="col-lg-4">
						                            <div class="left-area">
						                              
						                            </div>
						                          </div>
						                          <div class="col-lg-7">
						                            <button class="addProductSubmit-btn" type="submit">{{ __("Save") }}</button>
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