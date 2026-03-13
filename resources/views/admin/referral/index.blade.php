@extends('layouts.admin') 

@section('content')  
					<input type="hidden" id="headerdata" value="{{ __("REFERRAL TRACKING") }}">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">{{ __("Referral Tracking") }}</h4>
										<ul class="links">
											<li>
												<a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
											</li>
											<li>
												<a href="javascript:;">{{ __("Growth") }} </a>
											</li>
											<li>
												<a href="{{ route('admin-referral-index') }}">{{ __("Referral Tracking") }}</a>
											</li>
										</ul>
								</div>
							</div>
						</div>
						<div class="product-area">
							<div class="row">
								<div class="col-lg-12">
									<div class="mr-table allproduct">
										@include('alerts.admin.form-success') 
										<div class="table-responsive">
												<table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
													<thead>
														<tr>
		                                                  <th>{{ __("Date") }}</th>
		                                                  <th>{{ __("Referrer") }}</th>
		                                                  <th>{{ __("Referred") }}</th>
		                                                  <th>{{ __("Role") }}</th>
		                                                  <th>{{ __("Referrer Bonus") }}</th>
		                                                  <th>{{ __("Referred Bonus") }}</th>
		                                                  <th>{{ __("Status") }}</th>
														</tr>
													</thead>
												</table>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>

@endsection    

@section('scripts')

    <script type="text/javascript">

(function($) {
		"use strict";

		var table = $('#geniustable').DataTable({
			   ordering: false,
               processing: true,
               serverSide: true,
               ajax: '{{ route('admin-referral-datatables') }}',
               columns: [
                        { data: 'created_at', name: 'created_at' },
                        { data: 'referrer', name: 'referrer' },
                        { data: 'referred', name: 'referred' },
                        { data: 'referred_role', name: 'referred_role' },
                        { data: 'referrer_bonus', name: 'referrer_bonus' },
                        { data: 'referred_bonus', name: 'referred_bonus' },
                        { data: 'status', name: 'status' }
                     ],
               language : {
                	processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                }
            });

})(jQuery);

    </script>
@endsection
