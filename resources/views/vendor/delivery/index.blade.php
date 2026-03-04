@extends('layouts.vendor')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/front/css/select2.min.css') }}">
    <style>
        .select2-container {
            display: unset !important;
        }
    </style>
@endsection
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Order Delivery') }}</h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('vendor.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="javascript:;">{{ __('Orders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('vendor.delivery.index') }}">{{ __('Order Delivery') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="product-area">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mr-table allproduct">
                        @include('alerts.form-success')
                        <div class="table-responsive">
                            <div class="gocover"
                                style="background: url({{ asset('assets/images/' . $gs->admin_loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
                            </div>
                            <table id="geniustable" class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order Number') }}</th>
                                        <th>{{ __('Customer') }}</th>
                                        <th>{{ __('Total Cost') }}</th>
                                        <th>{{ __('Payment Method') }}</th>
                                        <th>{{ __('Rider') }}</th>
                                        <th>{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="riderList" tabindex="-1" role="dialog" aria-labelledby="modal1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="submit-loader">
                    <img src="{{ asset('assets/images/' . $gs->admin_loader) }}" alt="">
                </div>
                <div class="modal-header d-block text-center">
                    <h4 class="modal-title d-inline-block">{{ __('Search Rider') }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="riderSearchForm" action="{{ route('vendor-rider-search-submit') }}" method="POST"
                        enctype="multipart/form-data">
                        {{ csrf_field() }}
                        @include('alerts.vendor.form-both')
                        <input type="hidden" name="order_id" value="" id="vendor_rider_find_order_id">
                        <input type="hidden" name="product_id" value="" id="vendor_rider_find_product_id">
                        <div class="row mb-4">
                            <div class="col-lg-12">
                                <select class="border rider_select2" style="margin-bottom: 40px" name="rider_id"
                                    id="redierList" required>
                                </select>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <select class="border pickup_select2" name="pickup_point_id" id="pickup_point_id" required>
                                    <option value="" selected>@lang('Select Pickup Point')</option>
                                    @foreach (App\Models\Pickup::all() as $pickup)
                                        <option value="{{ $pickup->id }}">{{ $pickup->location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-lg-12 mt-3">
                                <textarea class="form-control" name="more_info" placeholder="Enter More Details" style="color:#000;" rows="2"></textarea>


                            </div>
                            <div class="col-lg-12 mt-3">
                                <input type="text" class="form-control" name="phone_number" placeholder="Phone Number"
                                    required>
                            </div>
                            <div class="col-lg-12 py-3 d-none viewRider">
                                <div><strong>
                                        Rider Name : <span id="ridername"></span>
                                    </strong></div>
                                <div>
                                    <strong>
                                        Service Area : <span id="serviceArea"></span>
                                    </strong>
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <button class="mybtn1" type="submit">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('assets/front/js/select2.min.js') }}"></script>
    <script type="text/javascript">
        (function($) {
            "use strict";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $(document).on('change', '.vendor-btn', function() {
                $('#vendor-status').modal('show');
                $('#vendor-status').find('.btn-ok').attr('href', $(this).val());
            });
            var table = $('#geniustable').DataTable({
                ordering: false,
                processing: true,
                serverSide: true,
                ajax: '{{ route('vendor-delivery-order-datatables') }}',
                columns: [{
                        data: 'order_number',
                        name: 'order_number'
                    },
                    {
                        data: 'customer_info',
                        name: 'customer_info'
                    },
                    {
                        data: 'pay_amount'
                    },
                    {
                        data: 'method',
                        name: 'method'
                    },
                    {
                        data: 'riders',
                        name: 'riders'
                    },
                    {
                        data: 'action',
                        name: 'action'
                    }
                ],
                language: {
                    processing: '<img src="{{ asset('assets/images/' . $gs->admin_loader) }}">'
                },
                drawCallback: function(settings) {
                    $('.select').niceSelect();
                }
            });
            $(document).on('click', '.searchDeliveryRider', function() {
                let city = $(this).attr('customer-city');
                let order_id = $(this).attr('order_id');
                $('#vendor_rider_find_order_id').val(order_id);
                let product_id = $(this).attr('product_id'); // attribute se value lo
                $('#vendor_rider_find_product_id').val(product_id);
                $.get("{{ route('vendor.find.rider') }}", {
                    // city: city
                    order_id: order_id
                }, function(data) {
                    $('#redierList').html(data.riders);
                })
            })
            $(document).on('change', '#redierList', function() {
                let rider_id = $(this).val();
                let area = $(this).find('option:selected').attr('area');
                let riderName = $(this).find('option:selected').attr('riderName');
                $('#ridername').text(riderName);
                $('#serviceArea').text(area);
                $('.viewRider').removeClass('d-none');
            })

            function loadRiders() {
                $.get("{{ route('vendor.find.rider') }}", function(data) {
                    $('#redierList').html(data.riders);
                });
            }
            $(document).ready(function() {
                loadRiders();
            });
            $('.rider_select2').select2({
                placeholder: "Select Rider",
                allowClear: true
            });
            $('.pickup_select2').select2({
                placeholder: "Select Pickup Point",
                allowClear: true
            });
            $(document).on('submit', '#riderSearchForm', function(e) {
                e.preventDefault();
                var form = $(this);
                var actionUrl = form.attr('action');
                $.ajax({
                    type: "POST",
                    url: actionUrl,
                    data: form.serialize(), // serializes the form's elements.
                    success: function(data) {
                        if (data.success == true) {
                            $('#riderList').modal('hide');
                            $('#redierList').val(null).trigger('change');
                            $('.viewRider').addClass('d-none');
                            $('#vendor_rider_find_order_id').val('');
                            $.notify(data.message, "success");
                            table.ajax.reload();
                        }
                    }
                });
            })
        })(jQuery);
    </script>
@endsection
