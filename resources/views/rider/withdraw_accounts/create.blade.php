@extends('layouts.front')
@section('content')
@include('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Add Withdrawal Account') }}</h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('rider-dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li class="breadcrumb-item"><a href="{{ route('rider-withdraw-accounts-index') }}">{{ __('Withdrawal Accounts') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Add Account') }}</li>
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
                     <h4 class="widget-title down-line mb-30">{{ __('Add New Withdrawal Account') }}
                        <a class="mybtn1" href="{{route('rider-withdraw-accounts-index')}}"> <i class="fas fa-arrow-left"></i> {{ __('Back') }}</a>
                     </h4>
                     <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                     @include('alerts.admin.form-both')
                     <form id="geniusform" action="{{route('rider-withdraw-accounts-store')}}" method="POST">
                        @csrf
                        <div class="row mb-3">
                           <div class="col-lg-3">
                              <label>{{ __('Withdraw Method') }} *</label>
                           </div>
                           <div class="col-lg-9">
                              <select class="form-control" name="method" id="withmethod" required>
                                 <option value="">{{ __('Select Withdraw Method') }}</option>
                                 <option value="Bank">{{ __('Bank') }}</option>
                                 <option value="MTN Mobile Money">{{ __('MTN Mobile Money') }}</option>
                                 <option value="Orange Money">{{ __('Orange Money') }}</option>
                                 <option value="Campay">{{ __('Campay') }}</option>
                              </select>
                           </div>
                        </div>

                        <div class="row mb-3">
                           <div class="col-lg-3">
                              <label>{{ __('Account Name') }} *</label>
                           </div>
                           <div class="col-lg-9">
                              <input name="acc_name" placeholder="{{ __('Enter Account Name') }}" class="form-control" type="text" required>
                           </div>
                        </div>

                        <div id="bank_fields" style="display: none;">
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label>{{ __('Bank Name') }} *</label>
                                </div>
                                <div class="col-lg-9">
                                    <input name="bank_name" placeholder="{{ __('Enter Bank Name') }}" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label>{{ __('IBAN') }} *</label>
                                </div>
                                <div class="col-lg-9">
                                    <input name="iban" placeholder="{{ __('Enter IBAN') }}" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-3">
                                    <label>{{ __('Swift Code') }}</label>
                                </div>
                                <div class="col-lg-9">
                                    <input name="swift" placeholder="{{ __('Enter Swift Code') }}" class="form-control" type="text">
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                           <div class="col-lg-3">
                              <label>{{ __('Account Number / Phone') }} *</label>
                           </div>
                           <div class="col-lg-9">
                              <input name="acc_number" placeholder="{{ __('Enter Account Number or Phone Number') }}" class="form-control" type="text" required>
                           </div>
                        </div>

                        <div class="row mb-3">
                           <div class="col-lg-3">
                              <label>{{ __('Network (Optional for MM)') }}</label>
                           </div>
                           <div class="col-lg-9">
                              <select name="network" class="form-control">
                                 <option value="">{{ __('Select Network') }}</option>
                                 <option value="MTN">MTN</option>
                                 <option value="Orange">Orange</option>
                              </select>
                           </div>
                        </div>

                        <div class="row">
                           <div class="col-lg-3"></div>
                           <div class="col-lg-9">
                              <button class="mybtn1" type="submit">{{ __('Add Account') }}</button>
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
})(jQuery);
</script>
@endsection
