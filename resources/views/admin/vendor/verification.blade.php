@extends('layouts.load')
@section('content')

						<div class="content-area">
							<div class="add-product-content1">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">
											@include('alerts.admin.form-error') 
											<form id="geniusformdata" action="{{route('admin-vendor-verify-submit',$data->id)}}" method="POST" enctype="multipart/form-data">
												{{csrf_field()}}
                                                <div class="row mb-3">
                                                    <div class="col-lg-4">
                                                        <div class="left-area">
                                                            <h4 class="heading">{{ __('Required Documents') }}</h4>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-7">
                                                        <div class="row">
                                                            <div class="col-6 mb-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" name="reverify_options[]" value="Identity Document (ID/Passport)" class="custom-control-input" id="opt_id">
                                                                    <label class="custom-control-label" for="opt_id">{{ __('Identity Document (ID/Passport)') }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" name="reverify_options[]" value="Selfie with ID" class="custom-control-input" id="opt_selfie">
                                                                    <label class="custom-control-label" for="opt_selfie">{{ __('Selfie with ID') }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" name="reverify_options[]" value="Business Registration Certificate" class="custom-control-input" id="opt_biz">
                                                                    <label class="custom-control-label" for="opt_biz">{{ __('Business Registration Certificate') }}</label>
                                                                </div>
                                                            </div>
                                                            <div class="col-6 mb-2">
                                                                <div class="custom-control custom-checkbox">
                                                                    <input type="checkbox" name="reverify_options[]" value="Taxpayer Card Copy" class="custom-control-input" id="opt_tax">
                                                                    <label class="custom-control-label" for="opt_tax">{{ __('Taxpayer Card Copy') }}</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __('Additional Details') }}</h4>
																<p class="sub-heading">{{ __('(In Any Language)') }}</p>
														</div>
													</div>
                                                    <div class="col-lg-7">
                                                        <textarea class="input-field" name="details" placeholder="{{ __('Enter additional details or instructions') }}" ></textarea> 
                                                    </div>
												</div>
                                                <input type="hidden" name="user_id" value="{{ $data->id }}">

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
															
														</div>
													</div>
													<div class="col-lg-7">
														<button class="addProductSubmit-btn" type="submit">{{ __('Send') }}</button>
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