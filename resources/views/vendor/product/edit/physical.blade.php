@extends('layouts.vendor')
@section('styles')

<link href="{{asset('assets/admin/css/product.css')}}" rel="stylesheet" />
<link href="{{asset('assets/admin/css/jquery.Jcrop.css')}}" rel="stylesheet" />
<link href="{{asset('assets/admin/css/Jcrop-style.css')}}" rel="stylesheet" />

@endsection
@section('content')

<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="heading"> {{ __('Edit Product') }}<a class="add-btn" href="{{ url()->previous() }}"><i
							class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
				<ul class="links">
					<li>
						<a href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }} </a>
					</li>
					<li>
						<a href="{{ route('vendor-prod-index') }}">{{ __('Products') }} </a>
					</li>
					<li>
						<a href="javascript:;">{{ __('Physical Product') }}</a>
					</li>
					<li>
						<a href="javascript:;">{{ __('Edit') }}</a>
					</li>
				</ul>
			</div>
		</div>
	</div>

	<form id="geniusform" action="{{route('vendor-prod-update',$data->id)}}" method="POST"
		enctype="multipart/form-data">
		{{csrf_field()}}
		@include('alerts.admin.form-both')
		<div class="row">
			<div class="col-lg-8">
				<div class="add-product-content">
					<div class="row">
						<div class="col-lg-12">
							<div class="product-description">
								<div class="body-area">
									<div class="gocover"
										style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
									</div>


									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __("Product Name") }}* </h4>
												<p class="sub-heading">{{ __("(In Any Language)") }}</p>
											</div>
										</div>
										<div class="col-lg-12">
											<input type="text" class="input-field" placeholder="{{ __(" Enter Product
												Name") }}" name="name" required="" value="{{ $data->name }}">
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __('Product Sku') }}* </h4>
											</div>
										</div>
										<div class="col-lg-12">
											<input type="text" class="input-field"
												placeholder="{{ __('Enter Product Sku') }}" name="sku" required=""
												value="{{ $data->sku }}">
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __('Category') }}*</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<select id="cat" name="category_id" required="">
												<option>{{ __('Select Category') }}</option>
												@foreach($cats as $cat)
												<option data-href="{{ route('vendor-subcat-load',$cat->id) }}"
													value="{{$cat->id}}" {{$cat->id == $data->category_id ?
													"selected":""}} >{{$cat->name}}</option>
												@endforeach
											</select>
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __('Sub Category') }}*</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<select id="subcat" name="subcategory_id">
												<option value="">{{ __('Select Sub Category') }}</option>
												@if($data->subcategory_id == null)
												@foreach($data->category->subs as $sub)
												<option data-href="{{ route('vendor-childcat-load',$sub->id) }}"
													value="{{$sub->id}}">{{$sub->name}}</option>
												@endforeach
												@else
												@foreach($data->category->subs as $sub)
												<option data-href="{{ route('vendor-childcat-load',$sub->id) }}"
													value="{{$sub->id}}" {{$sub->id == $data->subcategory_id ?
													"selected":""}} >{{$sub->name}}</option>
												@endforeach
												@endif
											</select>
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __('Child Category') }}*</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<select id="childcat" name="childcategory_id" {{$data->subcategory_id ==
												null ? "disabled":""}}>
												<option value="">{{ __('Select Child Category') }}</option>
												@if($data->subcategory_id != null)
												@if($data->childcategory_id == null)
												@foreach($data->subcategory->childs as $child)
												<option value="{{$child->id}}">{{$child->name}}</option>
												@endforeach
												@else
												@foreach($data->subcategory->childs as $child)
												<option value="{{$child->id}} " {{$child->id == $data->childcategory_id
													? "selected":""}}>{{$child->name}}</option>
												@endforeach
												@endif
												@endif
											</select>
										</div>
									</div>

                                    <!--<div class="row">-->
                                    <!--    <div class="col-lg-12">-->
                                    <!--        <div class="left-area">-->
                                    <!--            <h4 class="heading">-->
                                    <!--                {{ __('Product Location') }}* <small> (Enter city and area name here)</small>-->
                                    <!--            </h4>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--    <div class="col-lg-12">-->
                                    <!--        <div class="text-editor">-->
                                    <!--            <textarea class="nic-edit-p" name="product_location">{{ old('product_location', $data->product_location) }}</textarea>-->
                                    <!--        </div>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    
                                        @php
                                            $serviceAreas = \App\Models\ServiceArea::all();
                                        @endphp
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Product Location') }}* <small>(Select city and area)</small>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="text-editor">
                                                    <select name="product_location" id="service_area_id" class="form-control" required>
                                                        <option value="">-- Select Location --</option>
                                                        @foreach($serviceAreas as $area)
                                                            <option value="{{ $area->id }}" {{ $data->product_location == $area->id ? 'selected' : '' }}>
                                                                {{ $area->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- City -->
                                        
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Product City') }}* <small>(Select City)</small>
                                                    </h4>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="text-editor">
                                                    <select name="product_city" class="form-control" required>
                                                        <option value="">-- Select City --</option>
                                                        @foreach($cities as $id => $name)
                                                            <option value="{{ $id }}" {{ $data->product_city == $id ? 'selected' : '' }}>
                                                                {{ $name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <!-- 04/09/2025 ko below Field add ki by suleman -->
                                        <div class="row mt-3">
                                            <div class="col-lg-12">
                                                <div class="left-area">
                                                    <h4 class="heading">
                                                        {{ __('Delivery Fee Calculation') }}*
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
                                                               value="{{ old('delivery_fee', $data->delivery_fee) }}">
                                                    </div>
                                        
                                                    <!-- Delivery Unit Dropdown -->
                                                    <div class="col-md-6">
                                                        <select name="delivery_unit" class="form-control">
                                                            <option value="gram" {{ old('delivery_unit', $data->delivery_unit) == 'gram' ? 'selected' : '' }}>Gram</option>
                                                            <option value="kilogram" {{ old('delivery_unit', $data->delivery_unit) == 'kilogram' ? 'selected' : '' }}>Kilogram</option>
                                                            <option value="ton" {{ old('delivery_unit', $data->delivery_unit) == 'ton' ? 'selected' : '' }}>Ton</option>
                                                        </select>
                                                    </div>
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
                                                        <input class="checkclick1" name="color_check" type="checkbox" id="check3" value="1" {{ !empty($data->color_all) ? "checked":"" }}>
                                                        <label for="check3">{{ __('Allow Product Colors') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="{{ !empty($data->color_all) ? "" : "showbox" }}">
                                            <div class="row">
                                                <div  class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">
                                                            {{ __('Product Colors') }}*
                                                        </h4>
                                                        <p class="sub-heading">
                                                            {{ __('(Hold Ctrl/Cmd to select multiple)') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div  class="col-lg-12">
                                                    @php
                                                        $selected_colors = !empty($data->color_all) ? explode(',', $data->color_all) : [];
                                                    @endphp
                                                    <select name="color_all[]" class="form-control select2-tags" multiple>
                                                        @foreach(['Black', 'White', 'Red', 'Blue', 'Green', 'Yellow', 'Orange', 'Purple', 'Pink', 'Brown', 'Grey', 'Silver', 'Gold', 'Multicolor'] as $color)
                                                            <option value="{{ $color }}" {{ in_array($color, $selected_colors) ? 'selected' : '' }}>{{ $color }}</option>
                                                        @endforeach
                                                    </select>
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
                                                        <input class="checkclick1" name="size_check" type="checkbox" id="tcheck" value="1" {{ !empty($data->size_all) ? "checked":"" }}>
                                                        <label for="tcheck">{{ __('Allow Product Sizes') }}</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="{{ !empty($data->size_all) ? "" : "showbox" }}">
                                            <div class="row">
                                                <div  class="col-lg-12">
                                                    <div class="left-area">
                                                        <h4 class="heading">
                                                            {{ __('Product Size') }}*
                                                        </h4>
                                                        <p class="sub-heading">
                                                            {{ __('(Hold Ctrl/Cmd to select multiple)') }}
                                                        </p>
                                                    </div>
                                                </div>
                                                <div  class="col-lg-12">
                                                    @php
                                                        $selected_sizes = !empty($data->size_all) ? explode(',', $data->size_all) : [];
                                                    @endphp
                                                    <select name="size_all[]" class="form-control select2-tags" multiple>
                                                        <optgroup label="Clothes Sizes">
                                                            @foreach(['S', 'SM', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $sz)
                                                                <option value="{{ $sz }}" {{ in_array($sz, $selected_sizes) ? 'selected' : '' }}>{{ $sz }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                        <optgroup label="Adult Shoe Sizes (EU)">
                                                            @for($i = 20; $i <= 45; $i++)
                                                                <option value="{{ $i }}" {{ in_array((string)$i, $selected_sizes) ? 'selected' : '' }}>{{ $i }}</option>
                                                            @endfor
                                                        </optgroup>
                                                        <optgroup label="Baby Shoe Sizes">
                                                            @foreach(['0-3 Months', '3-6 Months', '6-9 Months', '9-12 Months', '12-18 Months', '18-24 Months'] as $bsz)
                                                                <option value="{{ $bsz }}" {{ in_array($bsz, $selected_sizes) ? 'selected' : '' }}>{{ $bsz }}</option>
                                                            @endforeach
                                                        </optgroup>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>


									@php
									$selectedAttrs = json_decode($data->attributes, true);
									// dd($selectedAttrs);
									@endphp

									{{-- Attributes of category starts --}}
									<div id="catAttributes">
										@php
										$catAttributes = !empty($data->category->attributes) ?
										$data->category->attributes : '';
										@endphp
										@if (!empty($catAttributes))
										@foreach ($catAttributes as $catAttribute)
										<div class="row">
											<div class="col-lg-12">
												<div class="left-area">
													<h4 class="heading">{{ $catAttribute->name }} *</h4>
												</div>
											</div>
											<div class="col-lg-12">
												@php
												$i = 0;
												@endphp
												@foreach ($catAttribute->attribute_options as $optionKey => $option)
												@php
												$inName = $catAttribute->input_name;
												$checked = 0;
												@endphp
												<div class="row">
													<div class="col-lg-5">
														<div class="custom-control custom-checkbox">
															<input type="checkbox" id="{{ $catAttribute->input_name }}{{$option->id}}" name="{{ $catAttribute->input_name }}[]" value="{{$option->name}}" class="custom-control-input attr-checkbox"
															@if (is_array($selectedAttrs) && array_key_exists($catAttribute->input_name,$selectedAttrs))
															@if (is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"]))
																checked
															   @php
																   $checked = 1;
															   @endphp
															@endif
														@endif
															>
															<label class="custom-control-label" for="{{ $catAttribute->input_name }}{{$option->id}}">{{ $option->name }}</label>
													   </div>
													</div>

													<div
														class="col-lg-7 {{ $catAttribute->price_status == 0 ? 'd-none' : '' }}">
														<div class="row">
															<div class="col-2">
																+
															</div>
															<div class="col-10">
																<div class="price-container">
																	<span class="price-curr">{{ $sign->sign }}</span>
																	<input type="text" class="input-field price-input"
																		id="{{ $catAttribute->input_name }}{{$option->id}}_price"
																		data-name="{{ $catAttribute->input_name }}_price[]"
																		placeholder="0.00 (Additional Price)"
																		value="{{ !empty($selectedAttrs["
																		$inName"]['prices'][$i]) && $checked==1 ?
																		round($selectedAttrs["$inName"]['prices'][$i]*
																		$sign->value , 2) : '' }}">
																</div>
															</div>
														</div>
													</div>
												</div>
												@php
												if ($checked == 1) {
												$i++;
												}
												@endphp
												@endforeach
											</div>
										</div>
										@endforeach
										@endif
									</div>
									{{-- Attributes of category ends --}}

									{{-- Attributes of subcategory starts --}}
									<div id="subcatAttributes">
										@php
										$subAttributes = !empty($data->subcategory->attributes) ?
										$data->subcategory->attributes : '';
										@endphp
										@if (!empty($subAttributes))
										@foreach ($subAttributes as $subAttribute)
										<div class="row">
											<div class="col-lg-12 mb-2">
												<div class="left-area">
													<h4 class="heading">{{ $subAttribute->name }} *</h4>
												</div>
											</div>
											<div class="col-lg-12">
												@php
												$i = 0;
												@endphp
												@foreach ($subAttribute->attribute_options as $option)
												@php
												$inName = $subAttribute->input_name;
												$checked = 0;
												@endphp

												<div class="row">
													<div class="col-lg-5">
														<div class="custom-control custom-checkbox">
															<input type="checkbox" id="{{ $subAttribute->input_name }}{{$option->id}}" name="{{ $subAttribute->input_name }}[]" value="{{$option->name}}" class="custom-control-input attr-checkbox"
															@if (is_array($selectedAttrs) && array_key_exists($subAttribute->input_name,$selectedAttrs))
															@php
															$inName = $subAttribute->input_name;
															@endphp
															@if (is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"]))
															checked
															@php
																$checked = 1;
															@endphp
															@endif
															@endif
															>

															<label class="custom-control-label" for="{{ $subAttribute->input_name }}{{$option->id}}">{{ $option->name }}</label>
														</div>
													</div>
													<div
														class="col-lg-7 {{ $subAttribute->price_status == 0 ? 'd-none' : '' }}">
														<div class="row">
															<div class="col-2">
																+
															</div>
															<div class="col-10">
																<div class="price-container">
																	<span class="price-curr">{{ $sign->sign }}</span>
																	<input type="text" class="input-field price-input"
																		id="{{ $subAttribute->input_name }}{{$option->id}}_price"
																		data-name="{{ $subAttribute->input_name }}_price[]"
																		placeholder="0.00 (Additional Price)"
																		value="value=" {{
																		!empty($selectedAttrs["$inName"]['prices'][$i])
																		&& $checked==1 ?
																		round($selectedAttrs["$inName"]['prices'][$i]*
																		$sign->value , 2) : '' }}"">
																</div>
															</div>
														</div>
													</div>
												</div>
												@php
												if ($checked == 1) {
												$i++;
												}
												@endphp
												@endforeach
											</div>
										</div>
										@endforeach
										@endif
									</div>
									{{-- Attributes of subcategory ends --}}

									{{-- Attributes of child category starts --}}
									<div id="childcatAttributes">
										@php
										$childAttributes = !empty($data->childcategory->attributes) ?
										$data->childcategory->attributes : '';
										@endphp
										@if (!empty($childAttributes))
										@foreach ($childAttributes as $childAttribute)
										<div class="row">
											<div class="col-lg-12 mb-2">
												<div class="left-area">
													<h4 class="heading">{{ $childAttribute->name }} *</h4>
												</div>
											</div>
											<div class="col-lg-12">
												@php
												$i = 0;
												@endphp
												@foreach ($childAttribute->attribute_options as $optionKey => $option)
												@php
												$inName = $childAttribute->input_name;
												$checked = 0;
												@endphp
												<div class="row">
													<div class="col-lg-5">
														<div class="custom-control custom-checkbox">
															<input type="checkbox" id="{{ $childAttribute->input_name }}{{$option->id}}" name="{{ $childAttribute->input_name }}[]" value="{{$option->name}}" class="custom-control-input attr-checkbox"
															@if (is_array($selectedAttrs) && array_key_exists($childAttribute->input_name,$selectedAttrs))
																@php
																	$inName = $childAttribute->input_name;
																@endphp
																@if (is_array($selectedAttrs["$inName"]["values"]) && in_array($option->name, $selectedAttrs["$inName"]["values"]))
																	checked
																	@php
																		$checked = 1;
																	@endphp
																@endif
															@endif																										
															
															>

															<label class="custom-control-label" for="{{ $childAttribute->input_name }}{{$option->id}}">{{ $option->name }}</label>
														</div>
													</div>


													<div
														class="col-lg-7 {{ $childAttribute->price_status == 0 ? 'd-none' : '' }}">
														<div class="row">
															<div class="col-2">
																+
															</div>
															<div class="col-10">
																<div class="price-container">
																	<span class="price-curr">{{ $sign->sign }}</span>
																	<input type="text" class="input-field price-input"
																		id="{{ $childAttribute->input_name }}{{$option->id}}_price"
																		data-name="{{ $childAttribute->input_name }}_price[]"
																		placeholder="0.00 (Additional Price)"
																		value="value=" {{
																		!empty($selectedAttrs["$inName"]['prices'][$i])
																		&& $checked==1 ?
																		round($selectedAttrs["$inName"]['prices'][$i]*
																		$sign->value , 2) : '' }}"">
																</div>
															</div>
														</div>
													</div>
												</div>
												@php
												if ($checked == 1) {
												$i++;
												}
												@endphp
												@endforeach
											</div>

										</div>
										@endforeach
										@endif
									</div>
									{{-- Attributes of child category ends --}}
									<div class="{{ $data->measure == null ? 'showbox' : '' }}">
										<div class="row">
											<div class="col-lg-6">
												<div class="left-area">
													<h4 class="heading">{{ __('Product Measurement') }}*</h4>
												</div>
											</div>
											<div class="col-lg-6">
												<select id="product_measure">
													<option value="" {{$data->measure == null ? 'selected':''}}>{{
														__('None') }}</option>
													<option value="Gram" {{$data->measure == 'Gram' ? 'selected':''}}>{{
														__('Gram') }}</option>
													<option value="Kilogram" {{$data->measure == 'Kilogram' ?
														'selected':''}}>{{ __('Kilogram') }}</option>
													<option value="Litre" {{$data->measure == 'Litre' ?
														'selected':''}}>{{ __('Litre') }}</option>
													<option value="Pound" {{$data->measure == 'Pound' ?
														'selected':''}}>{{ __('Pound') }}</option>
													<option value="Custom" {{ in_array($data->measure,explode(',',
														'Gram,Kilogram,Litre,Pound')) ? '' : 'selected' }}>{{
														__('Custom') }}</option>
												</select>
											</div>
											{{-- <div class="col-lg-1"></div> --}}
											<div class="col-lg-6 {{ in_array($data->measure,explode(',', 'Gram,Kilogram,Litre,Pound')) ? 'hidden' : '' }}"
												id="measure">
												<input name="measure" type="text" id="measurement" class="input-field"
													placeholder="Enter Unit" value="{{$data->measure}}">
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
														id="size-check" value="1" {{ !empty($data->size) ? "checked":""
													}}>
													<label for="size-check" class="stock-text">{{ __('Manage Stock')
														}}</label>
												</li>
											</ul>
										</div>
									</div>



									<div class="{{ !empty($data->size) ? "":" showbox" }}" id="size-display">
										<div class="row">
											<div class="col-lg-12">
											</div>
											<div class="col-lg-12">
												<div class="product-size-details" id="size-section">
													@if(!empty($data->size))
													@foreach($data->size as $key => $data1)

													<div class="size-area">
														<span class="remove size-remove"><i
																class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-md-3 col-sm-6">
																<label>
																	{{ __('Size Name') }} :
																	<span>
																		{{ __('(eg. S,M,L,XL,3XL,4XL)') }}
																	</span>
																</label>
																<select name="size[]" class="input-field size-name">
																	@foreach(array_unique($data->size) as $dt)
																	<option value="{{ $dt }}" {{ $dt==$data1
																		? 'selected' : '' }}>{{ $dt }}</option>
																	@endforeach
																</select>
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	{{ __('Size Qty') }} :
																	<span>
																		{{ __('(Quantity of this size)') }}
																	</span>
																</label>
																<input type="number" name="size_qty[]" required
																	class="input-field"
																	placeholder="{{ __('Size Qty') }}"
																	value="{{ $data->size_qty[$key] }}" min="1">
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	{{ __('Size Price') }} :
																	<span>
																		{{ __('(Added with base price)') }}
																	</span>
																</label>
																<input type="number" name="size_price[]" required
																	class="input-field"
																	placeholder="{{ __('Size Price') }}"
																	value="{{ round($data->size_price[$key] * $sign->value , 2) }}"
																	min="0">
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	{{ __('Size Color') }} :
																	<span>
																		{{ __('(Select color of this size)') }}
																	</span>
																</label>
																<select name="color[]" class="input-field color-name">
																	@foreach(array_unique($data->color) as $ct)
																	<option value="{{ $ct }}"
																		style="background-color:{{ $ct }}" {{
																		$ct==$data->color[$key] ? 'selected' : '' }}>
																	</option>
																	@endforeach
																</select>
															</div>

														</div>
													</div>

													@endforeach

													@else

													<div class="size-area">
														<span class="remove size-remove"><i
																class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-md-3 col-sm-6">
																<label>
																	{{ __('Size Name') }} :
																	<span>
																		{{ __('(eg. S,M,L,XL,3XL,4XL)') }}
																	</span>
																</label>
																<select name="size[]"
																	class="input-field size-name"></select>
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	{{ __('Size Qty') }} :
																	<span>
																		{{ __('(Quantity of this size)') }}
																	</span>
																</label>
																<input type="number" name="size_qty[]"
																	class="input-field"
																	placeholder="{{ __('Size Qty') }}" value="1"
																	min="1">
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	{{ __('Size Price') }} :
																	<span>
																		{{ __('(Added with base price)') }}
																	</span>
																</label>
																<input type="number" name="size_price[]"
																	class="input-field"
																	placeholder="{{ __('Size Price') }}" value="0"
																	min="0">
															</div>
															<div class="col-md-3 col-sm-6">
																<label>
																	{{ __('Size Color') }} :
																	<span>
																		{{ __('(Select color of this size)') }}
																	</span>
																</label>
																<select name="color[]"
																	class="input-field color-name"></select>
															</div>

														</div>
													</div>

													@endif

												</div>

												<a href="javascript:;" id="size-btn" class="add-more"><i
														class="fas fa-plus"></i>{{ __('Add More') }} </a>
											</div>
										</div>
									</div>


									<div class="row {{ !empty($data->size) ? " d-none":"" }}" id="default_stock">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __('Product Stock') }}*</h4>
												<p class="sub-heading">{{ __('(Leave Empty will Show Always Available)')
													}}</p>
											</div>
										</div>
										<div class="col-lg-12">
											<input name="stock" type="number" class="input-field" placeholder="e.g 20"
												value="{{$data->stock}}" min="0">
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">
													{{ __('Product Description') }}*
												</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="text-editor">
												<textarea name="details"
													class="nic-edit">{{$data->details}}</textarea>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">
													{{ __('Product Buy/Return Policy') }}*
												</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="text-editor">
												<textarea name="policy" class="nic-edit">{{$data->policy}}</textarea>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="checkbox-wrapper">
												<input type="checkbox" name="seo_check" value="1" class="checkclick"
													id="allowProductSEO" {{ ($data->meta_tag != null ||
												strip_tags($data->meta_description) != null) ? 'checked':'' }}>
												<label for="allowProductSEO">{{ __('Allow Product SEO') }}</label>
											</div>
										</div>
									</div>

									<div class="{{ ($data->meta_tag == null && strip_tags($data->meta_description) == null) ? "
										showbox":"" }}">
										<div class="row">
											<div class="col-lg-12">
												<div class="left-area">
													<h4 class="heading">{{ __('Meta Tags') }} *</h4>
												</div>
											</div>
											<div class="col-lg-12">
												<ul id="metatags" class="myTags">
													@if(!empty($data->meta_tag))
													@foreach ($data->meta_tag as $element)
													<li>{{ $element }}</li>
													@endforeach
													@endif
												</ul>
											</div>
										</div>

										<div class="row">
											<div class="col-lg-12">
												<div class="left-area">
													<h4 class="heading">
														{{ __('Meta Description') }} *
													</h4>
												</div>
											</div>
											<div class="col-lg-12">
												<div class="text-editor">
													<textarea name="meta_description" class="input-field"
														placeholder="{{ __('Details') }}">{{ $data->meta_description }}</textarea>
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
												<h4 class="heading">{{ __('Feature Image') }} *</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<div class="panel panel-body">
												<div class="span4 cropme text-center" id="landscape"
													style="width: 100%; height: 285px; border: 1px dashed #ddd; background: #f1f1f1;">
													<a href="javascript:;" id="crop-image"
														class="d-inline-block mybtn1">
														<i class="icofont-upload-alt"></i> {{ __('Upload Image Here') }}
													</a>
												</div>
											</div>
										</div>
									</div>

									<input type="hidden" id="feature_photo" name="photo" value="{{ $data->photo }}"
										accept="image/*">
									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">
													{{ __('Product Gallery Images') }} *
												</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<a href="javascript" class="set-gallery" data-toggle="modal"
												data-target="#setgallery">
												<input type="hidden" value="{{$data->id}}">
												<i class="icofont-plus"></i> {{ __('Set Gallery') }}
											</a>
										</div>
									</div>

									<div class="row">
														<div class="col-lg-12">
															<div class="left-area">
																	<h4 class="heading">{{ __('Product Regular Price') }}*</h4>
																	<p class="sub-heading">{{ __('(Price to be crossed out - Optional)') }}</p>
															</div>
														</div>
														<div class="col-lg-12">
															<input name="previous_price" step="0.1" type="number" class="input-field" placeholder="{{ __('e.g 1000') }}" value="{{round($data->vendorPreviousPrice() * $sign->value , 2)}}" min="1000">
														</div>
													</div>

													<div class="row">
														<div class="col-lg-12">
															<div class="left-area">
																<h4 class="heading">
																	{{ __('Product Sale Price') }}*
																</h4>
																<p class="sub-heading">
																	({{ __('Active Price') }} in {{$sign->name}})
																</p>
															</div>
														</div>
														<div class="col-lg-12">
															<input name="price" type="number" class="input-field" placeholder="{{ __('e.g 1000') }}" step="0.1" min="1000" value="{{round($data->vendorPrice() * $sign->value , 2)}}" required="">
														</div>
													</div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Discount Start Date') }}*</h4>
                                                <p class="sub-heading">{{ __('(When the sale starts - Compulsory)') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <input name="discount_date_start" type="date" class="input-field" value="{{ $data->discount_date_start }}" required="" min="{{ date('Y-m-d') }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="left-area">
                                                <h4 class="heading">{{ __('Discount End Date') }}</h4>
                                                <p class="sub-heading">{{ __('(When the sale ends - Optional)') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-lg-12">
                                            <input name="discount_date_end" type="date" class="input-field" value="{{ $data->discount_date_end }}">
                                        </div>
                                    </div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __('Youtube Video URL') }}*</h4>
												<p class="sub-heading">{{ __('(Optional)') }}</p>
											</div>
										</div>
										<div class="col-lg-12">
											<input name="youtube" type="text" class="input-field"
												placeholder="Enter Youtube Video URL" value="{{$data->youtube}}">
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
													<h4 class="title">{{ __('Feature Tags') }}</h4>
												</div>

												<div class="feature-tag-top-filds" id="feature-section">
													@if(!empty($data->features))

													@foreach($data->features as $key => $data1)

													<div class="feature-area">
														<span class="remove feature-remove"><i
																class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-lg-6">
																<input type="text" name="features[]" class="input-field"
																	placeholder="{{ __('Enter Your Keyword') }}"
																	value="{{ $data->features[$key] }}">
															</div>

															<div class="col-lg-6">
																<div class="input-group colorpicker-component cp">
																	<input type="text" name="colors[]"
																		value="{{ $data->colors[$key] }}"
																		class="input-field cp" />
																	<span class="input-group-addon"><i></i></span>
																</div>
															</div>
														</div>
													</div>

													@endforeach
													@else

													<div class="feature-area">
														<span class="remove feature-remove"><i
																class="fas fa-times"></i></span>
														<div class="row">
															<div class="col-lg-6">
																<input type="text" name="features[]" class="input-field"
																	placeholder="{{ __('Enter Your Keyword') }}">
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

													@endif
												</div>

												<a href="javascript:;" id="feature-btn" class="add-fild-btn"><i
														class="icofont-plus"></i> {{ __('Add More Field') }}</a>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-lg-12">
											<div class="left-area">
												<h4 class="heading">{{ __('Tags') }} *</h4>
											</div>
										</div>
										<div class="col-lg-12">
											<ul id="tags" class="myTags">
												@if(!empty($data->tags))
												@foreach ($data->tags as $element)
												<li>{{ $element }}</li>
												@endforeach
												@endif
											</ul>
										</div>
									</div>

									<div class="row text-center">
										<div class="col-6 offset-3">
											<button class="addProductSubmit-btn" type="submit">{{ __('Save Product')
												}}</button>
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
				<h5 class="modal-title" id="exampleModalCenterTitle">{{ __('Image Gallery') }}</h5>
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
									@csrf
									<input type="hidden" id="pid" name="product_id" value="">
									<input type="file" name="gallery[]" class="hidden" id="uploadgallery"
										accept="image/*" multiple>
									<label for="image-upload" id="prod_gallery"><i class="icofont-upload-alt"></i>{{
										__('Upload File') }}</label>
								</form>
							</div>
						</div>
						<div class="col-sm-6">
							<a href="javascript:;" class="upload-done" data-dismiss="modal"> <i
									class="fas fa-check"></i> {{ __('Done') }}</a>
						</div>
						<div class="col-sm-12 text-center">( <small>{{ __('You can upload multiple Images.') }}</small>
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

@endsection

@section('scripts')

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
                    url:"{{ route('vendor-gallery-show') }}",
                    data:{id:pid},
                    success:function(data){
                      if(data[0] == 0)
                      {
	                    $('.selected-image .row').addClass('justify-content-center');
	      				$('.selected-image .row').html('<h3>{{ __('No Images Found.') }}</h3>');
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
                                            '<a href="'+'{{asset('assets/images/galleries').'/'}}'+arr[k]['photo']+'" target="_blank">'+
                                            '<img src="'+'{{asset('assets/images/galleries').'/'}}'+arr[k]['photo']+'" alt="gallery image">'+
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
	        url:"{{ route('vendor-gallery-delete') }}",
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
		   url:"{{ route('vendor-gallery-store') }}",
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
                                            '<a href="'+'{{asset('assets/images/galleries').'/'}}'+arr[k]['photo']+'" target="_blank">'+
                                            '<img src="'+'{{asset('assets/images/galleries').'/'}}'+arr[k]['photo']+'" alt="gallery image">'+
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

<script src="{{asset('assets/admin/js/jquery.Jcrop.js')}}"></script>

<script src="{{asset('assets/admin/js/jquery.SimpleCropper.js')}}"></script>

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

    let html = `<img src="{{ empty($data->photo) ? asset('assets/images/noimage.png') : (filter_var($data->photo, FILTER_VALIDATE_URL) ? $data->photo : asset('assets/images/products/'.$data->photo)) }}" alt="">`;
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
        url: "{{route('vendor-prod-upload-update',$data->id)}}",
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
        $('.select2-tags').select2({
            tags: true,
            tokenSeparators: [','],
            width: '100%'
        });
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
@include('partials.admin.product.product-scripts')
@endsection