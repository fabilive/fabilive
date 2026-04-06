@extends('layouts.vendor')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Edit Withdrawal Account') }} <a class="add-btn" href="{{ route('vendor-withdraw-accounts-index') }}"><i class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
            </div>
        </div>
    </div>
    <div class="add-product-content1">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        <div class="gocover" style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                        @include('alerts.admin.form-both')
                        <form id="geniusform" action="{{route('vendor-withdraw-accounts-update', $account->id)}}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Withdraw Method') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <select class="form-control" name="method" id="withmethod" required>
                                        <option value="">{{ __('Select Withdraw Method') }}</option>
                                        <option value="Bank" {{ $account->method == 'Bank' ? 'selected' : '' }}>{{ __('Bank') }}</option>
                                        <option value="MTN Mobile Money" {{ $account->method == 'MTN Mobile Money' ? 'selected' : '' }}>{{ __('MTN Mobile Money') }}</option>
                                        <option value="Orange Money" {{ $account->method == 'Orange Money' ? 'selected' : '' }}>{{ __('Orange Money') }}</option>
                                        <option value="Campay" {{ $account->method == 'Campay' ? 'selected' : '' }}>{{ __('Campay') }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Account Name') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <input name="acc_name" value="{{ $account->acc_name }}" placeholder="{{ __('Enter Account Name') }}" class="form-control" type="text" required>
                                </div>
                            </div>

                            <div id="bank_fields" style="display: {{ $account->method == 'Bank' ? 'block' : 'none' }};">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Bank Name') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input name="bank_name" value="{{ $account->bank_name }}" placeholder="{{ __('Enter Bank Name') }}" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('IBAN') }} *</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input name="iban" value="{{ $account->iban }}" placeholder="{{ __('Enter IBAN') }}" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="left-area">
                                            <h4 class="heading">{{ __('Swift Code') }}</h4>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <input name="swift" value="{{ $account->swift }}" placeholder="{{ __('Enter Swift Code') }}" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Account Number / Phone') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <input name="acc_number" value="{{ $account->acc_number }}" placeholder="{{ __('Enter Account Number or Phone Number') }}" class="form-control" type="text" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Network (Optional for MM)') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <select name="network" class="form-control">
                                        <option value="">{{ __('Select Network') }}</option>
                                        <option value="MTN" {{ $account->network == 'MTN' ? 'selected' : '' }}>MTN</option>
                                        <option value="Orange" {{ $account->network == 'Orange' ? 'selected' : '' }}>Orange</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-12 text-center mt-3">
                                    <button class="addProduct_btn" type="submit">{{ __('Update Account') }}</button>
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

@section('scripts')
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

    // Check on page load if bank is selected
    if ($("#withmethod").val() == "Bank") {
        $("#bank_fields input").prop('required', true);
    }
})(jQuery);
</script>
@endsection
