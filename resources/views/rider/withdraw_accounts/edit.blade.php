@extends('layouts.front')
@section('content')
@include('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Edit Withdrawal Account') }}</h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('rider-dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('rider-withdraw-accounts-index') }}">{{ __('Withdrawal Accounts') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Edit Account') }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->

<div class="full-row">
   <div class="container">
      <div class="row">
         <div class="col-xl-3">
            @include('partials.rider.dashboard-sidebar')
         </div>
         <div class="col-xl-9">
            <div class="row">
               <div class="col-lg-12">
                  <div class="widget border-0 p-40 widget_categories bg-light account-info">
                     <h4 class="widget-title down-line mb-30">{{ __('Edit Withdrawal Account') }}
                        <a class="mybtn1" href="{{route('rider-withdraw-accounts-index')}}"> <i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                     </h4>
                     <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                     @include('alerts.admin.form-both')
                     <form id="geniusform" action="{{route('rider-withdraw-accounts-update', $account->id)}}" method="POST">
                        @csrf
                        <div class="row mb-3">
                           <div class="col-lg-3">
                              <label>{{ __('Withdraw Method') }} *</label>
                           </div>
                           <div class="col-lg-9">
                              <select class="form-control" name="method" id="withmethod" required>
                                 <option value="">{{ __('Select Withdraw Method') }}</option>
                                 <option value="Bank" {{ $account->method == 'Bank' ? 'selected' : '' }}>{{ __('Bank') }}</option>
                                 <option value="MTN Mobile Money" {{ $account->method == 'MTN Mobile Money' ? 'selected' : '' }}>{{ __('MTN Mobile Money') }}</option>
                                 <option value="Orange Money" {{ $account->method == 'Orange Money' ? 'selected' : '' }}>{{ __('Orange Money') }}</option>
                                 <option value="Campay" {{ $account->method == 'Campay' ? 'selected' : '' }}>{{ __('Campay') }}</option>
                              </select>
                           </div>
                        </div>

                        <div class="row mb-3">
                           <div class="col-lg-3">
                              <label>{{ __('Account Name') }} *</label>
                           </div>
                           <div class="col-lg-9">
                              <input name="acc_name" value="{{ $account->acc_name }}" placeholder="{{ __('Enter Account Name') }}" class="form-control" type="text" required>
                           </div>
                        </div>

                        <div id="bank_fields" style="display: {{ $account->method == 'Bank' ? 'block' : 'none' }};">
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label>{{ __('Bank Name') }} *</label>
                                </div>
                                <div class="col-lg-9">
                                    <input name="bank_name" value="{{ $account->bank_name }}" placeholder="{{ __('Enter Bank Name') }}" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label>{{ __('IBAN') }} *</label>
                                </div>
                                <div class="col-lg-9">
                                    <input name="iban" value="{{ $account->iban }}" placeholder="{{ __('Enter IBAN') }}" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label>{{ __('Swift Code') }}</label>
                                </div>
                                <div class="col-lg-9">
                                    <input name="swift" value="{{ $account->swift }}" placeholder="{{ __('Enter Swift Code') }}" class="form-control" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                           <div class="col-lg-3">
                              <label>{{ __('Account Number / Phone') }} *</label>
                           </div>
                           <div class="col-lg-9">
                              <input name="acc_number" value="{{ $account->acc_number }}" placeholder="{{ __('Enter Account Number or Phone Number') }}" class="form-control" type="text" required>
                           </div>
                        </div>

                        <div class="row mb-3">
                           <div class="col-lg-3">
                              <label>{{ __('Network (Optional for MM)') }}</label>
                           </div>
                           <div class="col-lg-9">
                              <select name="network" class="form-control">
                                 <option value="">{{ __('Select Network') }}</option>
                                 <option value="MTN" {{ $account->network == 'MTN' ? 'selected' : '' }}>MTN</option>
                                 <option value="Orange" {{ $account->network == 'Orange' ? 'selected' : '' }}>Orange</option>
                              </select>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-lg-3"></div>
                           <div class="col-lg-9">
                              <button class="mybtn1" type="submit">{{ __('Update Account') }}</button>
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

@includeIf('partials.global.common-footer')
@endsection
@section('script')
<script src = "{{ asset('assets/front/js/user.js') }}" defer ></script>
<script type="text/javascript">
(function($) {
    "use strict";
    $("#withmethod").change(function () {
        var method = $(this).val();
        if (method == "Bank") {
            $("#bank_fields").show();
            $("#bank_fields input").prop('required', true);
        } else {
            $("#bank_fields").hide();
            $("#bank_fields input").prop('required', false);
        }
    });

    // Check on page load
    if ($("#withmethod").val() == "Bank") {
        $("#bank_fields input").prop('required', true);
    }
})(jQuery);
</script>
@endsection
