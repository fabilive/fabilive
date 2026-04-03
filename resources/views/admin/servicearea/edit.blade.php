@extends('layouts.load')
@section('content')

						<div class="content-area">
							<div class="add-product-content1">
								<div class="row">
									<div class="col-lg-12">
										<div class="product-description">
											<div class="body-area">
											@include('alerts.admin.form-error') 
											<form id="geniusformdata" action="{{route('admin-servicearea-update',$data->id)}}" method="POST" enctype="multipart/form-data">
												{{csrf_field()}}


												@php
													$current_city = $data->city;
													$current_state = $current_city ? $current_city->state : null;
													$current_country_id = $current_state ? $current_state->country_id : null;
												@endphp

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __('Country') }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<select id="countrycode" class="input-field" name="country_id" required>
															<option value="">{{ __('Select Country') }}</option>
															@foreach($countries as $country)
																<option value="{{$country->id}}" data-href="{{route('admin-state-load',$country->id)}}" {{ $current_country_id == $country->id ? 'selected' : '' }}>{{$country->country_name}}</option>
															@endforeach
														</select>
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __('State') }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<select id="statecode" class="input-field" name="state_id" required>
															<option value="">{{ __('Select State') }}</option>
															@if($current_country_id)
																@foreach(App\Models\State::where('country_id', $current_country_id)->get() as $state)
																	<option value="{{$state->id}}" {{ $current_state && $current_state->id == $state->id ? 'selected' : '' }}>{{$state->state}}</option>
																@endforeach
															@endif
														</select>
													</div>
												</div>

												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __('City') }} *</h4>
														</div>
													</div>
													<div class="col-lg-7">
														<select id="citycode" class="input-field" name="city_id" required>
															<option value="">{{ __('Select City') }}</option>
															@if($current_state)
																@foreach(App\Models\City::where('state_id', $current_state->id)->get() as $city)
																	<option value="{{$city->id}}" {{ $current_city && $current_city->id == $city->id ? 'selected' : '' }}>{{$city->city_name}}</option>
																@endforeach
															@endif
														</select>
													</div>
												</div>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
																<h4 class="heading">{{ __('Location') }} *</h4>
																<p class="sub-heading">{{ __('(In Any Language)') }}</p>
														</div>
													</div>
													<div class="col-lg-7">
														<input type="text" class="input-field" name="location" placeholder="{{ __('Location') }}" required="" value="{{$data->location}}">
													</div>
												</div>

<script type="text/javascript">
	$(document).on('change','#countrycode',function(){
		let state_url = $('option:selected', this).attr('data-href');
		$('#statecode').prop('disabled', false).html('<option value="">{{ __("Select State") }}</option>');
		$('#citycode').prop('disabled', true).html('<option value="">{{ __("Select City") }}</option>');
		$.get(state_url,function(response){
			$('#statecode').html(response.data);
		});
	});

	$(document).on('change','#statecode',function(){
		let state_id = $(this).val();
		$('#citycode').prop('disabled', false).html('<option value="">{{ __("Select City") }}</option>');
		$.get("{{route('state.wise.city')}}",{state_id:state_id},function(data){
			$('#citycode').html(data.data);
		});
	});
</script>


												<div class="row">
													<div class="col-lg-4">
														<div class="left-area">
															
														</div>
													</div>
													<div class="col-lg-7">
														<button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
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