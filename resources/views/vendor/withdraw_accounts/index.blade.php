@extends('layouts.vendor')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Withdrawal Accounts') }}</h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="{{ route('vendor-withdraw-accounts-index') }}">{{ __('Withdrawal Accounts') }}</a>
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
                    <div class="row">
                        <div class="col-md-12 text-right mb-3">
                            <a class="add-btn" href="{{ route('vendor-withdraw-accounts-create') }}">
                                <i class="fas fa-plus"></i> {{ __('Add New Account') }}
                            </a>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>{{ __('Method') }}</th>
                                    <th>{{ __('Account Name') }}</th>
                                    <th>{{ __('Account Number') }}</th>
                                    <th>{{ __('Is Default') }}</th>
                                    <th>{{ __('Options') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($accounts as $account)
                                <tr>
                                    <td>{{ $account->method }}</td>
                                    <td>{{ $account->acc_name }}</td>
                                    <td>{{ $account->acc_number ?: $account->iban }}</td>
                                    <td>
                                        @if($account->is_default)
                                            <span class="badge badge-success">{{ __('Default') }}</span>
                                        @else
                                            <a href="{{ route('vendor-withdraw-accounts-default', $account->id) }}" class="badge badge-secondary">{{ __('Set Default') }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-list">
                                            <a href="{{ route('vendor-withdraw-accounts-edit', $account->id) }}" class="edit"> <i class="fas fa-edit"></i>{{ __('Edit') }}</a>
                                            <a href="javascript:;" data-href="{{ route('vendor-withdraw-accounts-delete', $account->id) }}" data-toggle="modal" data-target="#confirm-delete" class="delete"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- DELETE MODAL --}}
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
            <p class="text-center">{{ __('You are about to delete this account.') }}</p>
            <p class="text-center">{{ __('Do you want to proceed?') }}</p>
        </div>
        <div class="modal-footer justify-content-center">
            <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
            <a class="btn btn-danger btn-ok">{{ __('Delete') }}</a>
        </div>
    </div>
  </div>
</div>
{{-- DELETE MODAL ENDS --}}

@endsection

@section('scripts')
<script type="text/javascript">
	(function($) {
		"use strict";
		$('#geniustable').DataTable({
			ordering: false
		});
	})(jQuery);
</script>
@endsection
