@extends('layouts.front')
@section('content')
@include('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Withdraw') }}
            </h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('rider-dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Withdraw ') }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<!--==================== Blog Section Start ====================-->
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
                     <h4 class="widget-title down-line mb-30">{{ __('My Withdraws') }}
                        <a class="mybtn1" href="{{route('rider-wwt-index')}}">  {{
                           __('Back') }}</a>
                     </h4>
                     <hr>
                     <div class="gocover"
                        style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                     </div>
                     @include('alerts.admin.form-both')
                     <form id="" class="form-horizontal" action="{{ route('rider-wwt-store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label class="control-label col-sm-4"><b>{{ __('Current Balance') }}:</b>
                                {{ App\Models\Product::vendorConvertPrice(Auth::guard('rider')->user()->balance) }}
                            </label>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-4">{{ __('Withdraw Method') }} *</label>
                            <div class="col-sm-12">
                                <select class="form-control" name="methods" id="withmethod" required>
                                    <option value="">{{ __('Select Withdraw Method') }}</option>
                                    <option value="Bank">{{ __('Bank') }}</option>
                                    <option value="Pesapal">{{ __('Pesapal') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12">{{ __('Withdraw Amount') }} *</label>
                            <div class="col-sm-12">
                                <input name="amount" placeholder="{{ __('Withdraw Amount') }}" class="form-control" type="text" required>
                            </div>
                        </div>
                        <div id="pesapal" style="display: none;">
                            <div class="form-group">
                                <label class="control-label col-sm-12">{{ __('Select Network') }} *</label>
                                <div class="col-sm-12">
                                    <select name="network" class="form-control">
                                        <option value="Mpesa">Mpesa</option>
                                        <option value="Airtel">Airtel</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12">{{ __('Account Number') }} *</label>
                                <div class="col-sm-12">
                                    <input name="pesapal_acc_no" placeholder="{{ __('Enter Account Number') }}" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12">{{ __('Account Name') }} *</label>
                                <div class="col-sm-12">
                                    <input name="pesapal_acc_name" placeholder="{{ __('Enter Account Name') }}" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                        <div id="bank" style="display: none;">
                            <div class="form-group">
                                <label class="control-label col-sm-12">{{ __('Enter IBAN/Account No') }} *</label>
                                <div class="col-sm-12">
                                    <input name="iban" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12">{{ __('Enter Account Name') }} *</label>
                                <div class="col-sm-12">
                                    <input name="acc_name" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12">{{ __('Enter Address') }} *</label>
                                <div class="col-sm-12">
                                    <input name="address" class="form-control" type="text">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label col-sm-12">{{ __('Enter Swift Code') }} *</label>
                                <div class="col-sm-12">
                                    <input name="swift" class="form-control" type="text">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12">{{ __('Additional Reference (Optional)') }}</label>
                            <div class="col-sm-12">
                                <textarea class="form-control" name="reference" rows="6" placeholder="{{ __('Additional Reference') }}"></textarea>
                            </div>
                        </div>
                        <div id="resp" class="col-md-12">
                            <span class="help-block">
                                <strong>{{ __('Withdraw Fee') }} {{ $sign->sign }}{{ $gs->withdraw_fee }} {{ __('and') }} {{ $gs->withdraw_charge }}% {{ __('will be deducted.') }}</strong>
                            </span>
                        </div>
                        <div class="add-product-footer mt-3">
                            <button type="submit" class="mybtn1">{{ __('Withdraw') }}</button>
                        </div>
                    </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Blog Section End ====================-->
@includeIf('partials.global.common-footer')
@endsection
@section('script')
<script>
(function($) {
    "use strict";
    $('#withmethod').change(function() {
        let method = $(this).val();
        $('#pesapal, #bank').hide().find('input, select').attr('required', false);

        if (method === 'Pesapal') {
            $('#pesapal').show().find('input, select').attr('required', true);
        } else if (method === 'Bank') {
            $('#bank').show().find('input, select').attr('required', true);
        }
    });
})(jQuery);
</script>

<script type="text/javascript">
//   (function($) {
//           "use strict";

//       $("#withmethod").change(function () {
//           var method = $(this).val();
//           if (method == "Bank") {

//               $("#bank").show();
//               $("#bank").find('input, select').attr('required', true);

//               $("#paypal").hide();
//               $("#paypal").find('input').attr('required', false);

//           }
//           if (method != "Bank") {
//               $("#bank").hide();
//               $("#bank").find('input, select').attr('required', false);

//               $("#paypal").show();
//               $("#paypal").find('input').attr('required', true);
//           }
//           if (method == "") {
//               $("#bank").hide();
//               $("#paypal").hide();
//           }

//       })

//   })(jQuery);

</script>
@endsection