@extends('layouts.front')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/front/css/datatables.css')}}">
@endsection
@section('content')
@include('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Withdrawal Accounts') }}</h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('rider-dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Withdrawal Accounts') }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<div class="full-row">
   <div class="container">
        <div class="mb-4 d-xl-none">
            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
      <div class="row">
         <div class="col-xl-3">
            @include('partials.rider.dashboard-sidebar')
         </div>
         <div class="col-xl-9">
            <div class="row">
               <div class="col-lg-12">
                  <div class="widget border-0 p-40 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30">{{ __('Withdrawal Accounts') }}
                        <a class="mybtn1" href="{{route('rider-withdraw-accounts-create')}}"> <i class="fas fa-plus"></i> {{ __('Add New Account') }}</a>
                     </h4>
                     @include('alerts.admin.form-success')
                     <div class="mr-table allproduct mt-4">
                        <div class="table-responsive">
                           <table id="example" class="table" cellspacing="0" width="100%">
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
                                            <a href="{{ route('rider-withdraw-accounts-default', $account->id) }}" class="badge badge-secondary">{{ __('Set Default') }}</a>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-list">
                                            <a href="{{ route('rider-withdraw-accounts-edit', $account->id) }}" class="btn btn-sm btn-info"> <i class="fas fa-edit"></i></a>
                                            <a href="javascript:;" data-href="{{ route('rider-withdraw-accounts-delete', $account->id) }}" data-toggle="modal" data-target="#confirm-delete" class="btn btn-sm btn-danger"><i class="fas fa-trash-alt"></i></a>
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

@includeIf('partials.global.common-footer')
@endsection
@section('script')
<script src = "{{ asset('assets/front/js/dataTables.min.js') }}" defer ></script>
<script src = "{{ asset('assets/front/js/user.js') }}" defer ></script>
<script type="text/javascript">
	(function($) {
		"use strict";
        // Handle delete modal OK button
        $('#confirm-delete').on('show.bs.modal', function(e) {
            $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
        });
	})(jQuery);
</script>
@endsection
