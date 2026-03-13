@extends('layouts.admin') 

@section('styles')
<style type="text/css">
    .order-table-wrap thead tr th {
        background: #f0f0f0;
    }
</style>
@endsection

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Delivery Job Details') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a></li>
                    <li><a href="javascript:;">{{ __('Logistics') }} </a></li>
                    <li><a href="{{ route('admin-delivery-job-index') }}">{{ __('Delivery Jobs') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="order-table-wrap">
        @include('alerts.admin.form-success')
        <div class="row">
            <div class="col-lg-6">
                <div class="special-box">
                    <div class="heading-area">
                        <h4 class="title">{{ __('Delivery Information') }}</h4>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="45%">{{ __('Order Number') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{ $data->order->order_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Status') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">
                                        <span class="badge badge-info">{{ ucwords(str_replace('_', ' ', $data->status)) }}</span>
                                    </td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Delivered At') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{ $data->delivered_at ? $data->delivered_at->format('d-M-Y H:i:s') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Verified At') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">{{ $data->verified_at ? $data->verified_at->format('d-M-Y H:i:s') : 'N/A' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="special-box">
                    <div class="heading-area">
                        <h4 class="title">{{ __('Financial Details') }}</h4>
                    </div>
                    <div class="table-responsive-sm">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th width="45%">{{ __('Total Delivery Fee') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">XAF {{ number_format($data->delivery_fee_total, 2) }}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Rider Earnings') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">XAF {{ number_format($data->rider_earnings, 2) }}</td>
                                </tr>
                                <tr>
                                    <th width="45%">{{ __('Platform Commission') }}</th>
                                    <th width="10%">:</th>
                                    <td width="45%">XAF {{ number_format($data->platform_delivery_commission, 2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        @if($data->proof_photo)
            <div class="row">
                <div class="col-lg-12">
                    <div class="special-box">
                        <div class="heading-area">
                            <h4 class="title">{{ __('Proof of Delivery') }}</h4>
                        </div>
                        <div class="text-center">
                            <img src="{{ asset('assets/images/delivery_proofs/' . $data->proof_photo) }}" alt="Proof of Delivery" style="max-width: 100%; height: auto; border: 1px solid #ddd; padding: 5px;">
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12 text-center">
                @if($data->status === 'delivered_pending_verification')
                    <button id="verify-btn" class="btn btn-success" data-toggle="modal" data-target="#confirm-verification">{{ __('Verify Delivery & Settle Payouts') }}</button>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- VERIFICATION MODAL --}}
<div class="modal fade" id="confirm-verification" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h4 class="modal-title d-inline-block">{{ __('Confirm Verification') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p class="text-center">{{ __('You are about to verify this delivery and settle payouts.') }}</p>
                <p class="text-center">{{ __('This will release funds to the rider and seller.') }}</p>
                <p class="text-center"><strong>{{ __('Do you want to proceed?') }}</strong></p>
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-default" data-dismiss="modal">{{ __('Cancel') }}</button>
                <a href="javascript:;" id="verify-confirm" class="btn btn-success">{{ __('Verify Now') }}</a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript">
(function($) {
    "use strict";

    $('#verify-confirm').on('click', function() {
        $.ajax({
            type: "POST",
            url: "{{ route('admin-delivery-verify', $data->id) }}",
            data: {
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                $.notify(data.message, "success");
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr) {
                $.notify(xhr.responseJSON.message, "error");
            }
        });
    });

})(jQuery);
</script>
@endsection
