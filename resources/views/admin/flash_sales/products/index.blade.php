@extends('layouts.admin')
@section('content')
<input type="hidden" id="headerdata" value="{{ __('PRODUCT') }}">
<div class="content-area">
	<div class="mr-breadcrumb">
		<div class="row">
			<div class="col-lg-12">
				<h4 class="heading">{{ __('Flash Sale Products') }}</h4>
				<ul class="links">
					<li>
						<a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
					</li>
					<li><a href="javascript:;">{{ __('Flash Sales') }}</a></li>
					<li>
						<a href="{{ route('admin-flash-products-index') }}">{{ __('Flash Products') }}</a>
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
									<th>{{ __('Product') }}</th>
									<th>{{ __('Vendor') }}</th>
									<th>{{ __('Time Slot') }}</th>
									<th>{{ __('Flash Date') }}</th>
									<th>{{ __('Flash Price') }}</th>
									<th>{{ __('Qty') }}</th>
									<th>{{ __('Sold') }}</th>
									<th>{{ __('Status') }}</th>
									<th>{{ __('Options') }}</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-block text-center">
				<h4 class="modal-title d-inline-block">{{ __('Confirm Delete') }}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<p class="text-center">{{ __('You are about to delete this Product from Flash Sales.') }}</p>
				<p class="text-center">{{ __('Do you want to proceed?') }}</p>
			</div>
			<div class="modal-footer justify-content-center">
				<button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
				<form action="" class="d-inline delete-form" method="POST">
					<input type="hidden" name="_method" value="delete" />
					<input type="hidden" name="_token" value="{{ csrf_token() }}">
					<button type="submit" class="btn btn-danger">{{ __('Delete') }}</button>
				</form>
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
               ajax: '{{ route('admin-flash-products-datatables') }}',
               columns: [
                        { data: 'product', name: 'product' },
						{ data: 'vendor', name: 'vendor' },
						{ data: 'time_slot', name: 'time_slot' },
						{ data: 'flash_date', name: 'flash_date' },
						{ data: 'flash_price', name: 'flash_price' },
						{ data: 'flash_quantity', name: 'flash_quantity' },
						{ data: 'sold_quantity', name: 'sold_quantity' },
                        { data: 'status', searchable: false, orderable: false},
            			{ data: 'action', searchable: false, orderable: false }
                     ],
                language : {
                	processing: '<img src="{{asset('assets/images/'.$gs->admin_loader)}}">'
                },
				drawCallback : function( settings ) {
	    			$('.select').niceSelect();
				}
            });
})(jQuery);
</script>
@endsection
