@extends('layouts.vendor')
@section('content')

<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Withdraw Now') }} <a class="add-btn" href="{{ url()->previous() }}"><i
                            class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                <ul class="links">
                    <li>
                        <a href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('My Withdraws') }} </a>
                    </li>
                    <li>
                        <a href="javascript:;">{{ __('Withdraw Now') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>


    <div class="add-product-content1">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">

                        <div class="gocover"
                            style="background: url({{asset('assets/images/'.$gs->admin_loader)}}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                        </div>

                        @include('alerts.admin.form-both')
                        <form id="geniusform" class="form-horizontal" action="{{route('vendor-wt-store')}}"
                            method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="item form-group">
                                <label class="control-label col-sm-4" for="name"><b>{{ __('Current Balance') }} :
                                        {{ App\Models\Product::vendorConvertPrice($actualBalance) }}</b></label>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-sm-4" for="name">{{ __('Withdraw Method') }} *
                                </label>
                                <div class="col-sm-12">
                                    <select class="form-control" name="methods" id="withmethod" required>
                                        <option value="">{{ __('Select Withdraw Method') }}</option>
                                        <option value="Bank">{{ __('Bank') }}</option>
                                        <option value="MTN Mobile Money">{{ __('MTN Mobile Money') }}</option>
                                        <option value="Orange Money">{{ __('Orange Money') }}</option>
                                        <option value="Campay">{{ __('Campay') }}</option>
                                    </select>
                                </div>
                            </div>

                            @if(count($savedAccounts) > 0)
                            <div class="item form-group">
                                <label class="control-label col-sm-12" for="saved_account">{{ __('Select Saved Account') }}</label>
                                <div class="col-sm-12">
                                    <select class="form-control" id="saved_account">
                                        <option value="">{{ __('Select a saved account') }}</option>
                                        @foreach($savedAccounts as $acc)
                                            <option value="{{ $acc->id }}" 
                                                data-method="{{ $acc->method }}"
                                                data-acc_name="{{ $acc->acc_name }}"
                                                data-acc_number="{{ $acc->acc_number }}"
                                                data-bank_name="{{ $acc->bank_name }}"
                                                data-iban="{{ $acc->iban }}"
                                                data-swift="{{ $acc->swift }}"
                                                data-network="{{ $acc->network }}"
                                                data-address="{{ $acc->address }}"
                                                {{ $acc->is_default ? 'selected' : '' }}
                                            >
                                                {{ $acc->method }} - {{ $acc->acc_name }} ({{ $acc->acc_number ?: $acc->iban }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @endif

                            <div class="item form-group">
                                <label class="control-label col-sm-12" for="name">{{ __('Withdraw Amount') }} *

                                </label>
                                <div class="col-sm-12">
                                    <input name="amount" placeholder="{{ __('Withdraw Amount') }}" class="form-control"
                                        type="text" value="{{ old('amount') }}" required>
                                </div>
                            </div>

                            <div id="paypal" style="display: none;">

                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name">{{ __('Enter Account Email') }} *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="acc_email" placeholder="{{ __('Enter Account Email') }}" class="form-control"
                                            value="{{ old('email') }}" type="email">
                                    </div>
                                </div>

                            </div>
                            <!-- campay -->
                            <div id="campay" style="display: none;">
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="network">{{ __('Select Network') }} *</label>
                                    <div class="col-sm-12">
                                        <select name="network" class="form-control">
                                            <option value="MTN">MTN</option>
                                            <option value="Orange">Orange</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="campay_acc_no">{{ __('Account Number') }} *</label>
                                    <div class="col-sm-12">
                                        <input name="campay_acc_no" placeholder="{{ __('Enter Account Number') }}" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="campay_acc_name">{{ __('Account Name') }} *</label>
                                    <div class="col-sm-12">
                                        <input name="campay_acc_name" placeholder="{{ __('Enter Account Name') }}" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            
                            <div id="bank" style="display: none;">
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name">{{ __('Enter IBAN/Account No') }} *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="iban" value="" placeholder="{{ __('Enter IBAN/Account No') }}"
                                            class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name">{{ __('Enter Account Name') }} *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="acc_name" value=""
                                            placeholder="{{ __('Enter Account Name') }}" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name">{{ __('Enter Address') }} *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="address" value=""
                                            placeholder="{{ __('Enter Address') }}" class="form-control" type="text">
                                    </div>
                                </div>
                                <div class="item form-group">
                                    <label class="control-label col-sm-12" for="name">{{ __('Enter Swift Code') }}} *

                                    </label>
                                    <div class="col-sm-12">
                                        <input name="swift" value=""
                                            placeholder="{{ __('Enter Swift Code') }}" class="form-control" type="text">
                                    </div>
                                </div>
                            </div>
                            <div class="item form-group">
                                <label class="control-label col-sm-12" for="name">{{ __('Additional Reference(Optional)') }} *

                                </label>
                                <div class="col-sm-12">
                                    <textarea class="form-control" name="reference" rows="6"
                                        placeholder="{{ __('Additional Reference(Optional)') }}"></textarea>
                                </div>
                            </div>
                            <div id="resp" class="col-md-12">
                                <span class="help-block">
                                    <strong>{{ __('Withdraw Fee') }} {{ $sign->sign }}{{ $gs->withdraw_fee }} {{ __('and ') }}
                                        {{ $gs->withdraw_charge }}% {{ __('will deduct from your account.') }}</strong>
                                </span>
                            </div>
                            <hr>
                            <div class="add-product-footer">
                                <button name="addProduct_btn" type="submit"
                                    class="mybtn1">{{ __('Withdraw') }}</button>
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

    function updateFieldsBySavedAccount() {
        var $el = $("#saved_account option:selected");
        if ($el.val() == "") return;

        var method = $el.data('method');
        var acc_name = $el.data('acc_name');
        var acc_number = $el.data('acc_number');
        var bank_name = $el.data('bank_name');
        var iban = $el.data('iban');
        var swift = $el.data('swift');
        var network = $el.data('network');
        var address = $el.data('address');

        $("#withmethod").val(method).trigger('change');

        // Populate fields based on method
        if (method == "Bank") {
            $('input[name="acc_name"]').val(acc_name);
            $('input[name="iban"]').val(iban || acc_number);
            $('input[name="swift"]').val(swift);
            $('input[name="address"]').val(address);
        } else {
            $('input[name="campay_acc_name"]').val(acc_name);
            $('input[name="campay_acc_no"]').val(acc_number);
            if (network) {
                $('select[name="network"]').val(network);
            }
        }
    }

    $("#saved_account").change(function() {
        updateFieldsBySavedAccount();
    });

    // Auto-fill if default is selected on load
    $(document).ready(function() {
        if ($("#saved_account").val() != "") {
            updateFieldsBySavedAccount();
        }
    });

    $("#withmethod").change(function () {
        var method = $(this).val();
        if (method == "Bank") {
            $("#bank").show();
            $("#paypal, #campay").hide();
            $("#bank").find('input, select').attr('required', true);
            $("#paypal, #campay").find('input, select').attr('required', false);
        } else if (method == "Campay" || method == "MTN Mobile Money" || method == "Orange Money") {
            $("#campay").show();
            $("#bank, #paypal").hide();
            $("#campay").find('input, select').attr('required', true);
            
            // If it's a specific MM method, we can hide the network selector as it's implied
            if(method == "MTN Mobile Money" || method == "Orange Money"){
                $("#campay").find('label[for="network"]').parent().parent().hide();
                $("#campay").find('select[name="network"]').attr('required', false);
            } else {
                $("#campay").find('label[for="network"]').parent().parent().show();
                $("#campay").find('select[name="network"]').attr('required', true);
            }

            $("#bank, #paypal").find('input, select').attr('required', false);
        } else if (method != "") {
            $("#paypal").show();
            $("#bank, #campay").hide();
            $("#paypal").find('input').attr('required', true);
            $("#bank, #campay").find('input, select').attr('required', false);
        } else {
            $("#bank, #paypal, #campay").hide();
            $("#bank, #paypal, #campay").find('input, select').attr('required', false);
        }
    });
})(jQuery);
</script>
@endsection
