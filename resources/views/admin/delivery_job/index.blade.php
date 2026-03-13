@extends('layouts.admin') 

@section('content')  
					<input type="hidden" id="headerdata" value="{{ __("DELIVERY JOBS") }}">
					<div class="content-area">
						<div class="mr-breadcrumb">
							<div class="row">
								<div class="col-lg-12">
										<h4 class="heading">{{ __("Delivery Jobs") }}</h4>
										<ul class="links">
											<li>
												<a href="{{ route('admin.dashboard') }}">{{ __("Dashboard") }} </a>
											</li>
											<li>
												<a href="javascript:;">{{ __("Logistics") }} </a>
											</li>
											<li>
												<a href="{{ route('admin-delivery-job-index') }}">{{ __("Delivery Jobs") }}</a>
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
		                                                  <th>{{ __("Order #") }}</th>
		                                                  <th>{{ __("Buyer") }}</th>
		                                                  <th>{{ __("Rider") }}</th>
		                                                  <th>{{ __("Total Fee") }}</th>
		                                                  <th>{{ __("Rider Earnings") }}</th>
		                                                  <th>{{ __("Commissions") }}</th>
		                                                  <th>{{ __("Status") }}</th>
		                                                  <th>{{ __("Options") }}</th>
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
               ajax: '{{ route('admin-delivery-job-datatables') }}',
               columns: [
                        { data: 'order_number', name: 'order_number' },
                        { data: 'buyer', name: 'buyer' },
                        { data: 'rider', name: 'rider' },
                        { data: 'delivery_fee_total', name: 'delivery_fee_total' },
                        { data: 'rider_earnings', name: 'rider_earnings' },
                        { data: 'platform_delivery_commission', name: 'platform_delivery_commission' },
                        { data: 'status', name: 'status' },
            			{ data: 'action', searchable: false, orderable: false }
                     ],
               language : {
                	processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                }
            });

})(jQuery);

    </script>
@endsection
