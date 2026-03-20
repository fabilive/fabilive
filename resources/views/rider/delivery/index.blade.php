@extends('layouts.front')
@section('css')
<link rel="stylesheet" href="{{asset('assets/front/css/datatables.css')}}">
@endsection
@section('content')
@include('partials.global.common-header')

<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('My Delivery Jobs') }}</h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('rider-dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Delivery Jobs') }}</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
</div>

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
                <div class="row table-responsive-lg mt-3">
                    <div class="col-lg-12">
                        <div class="widget border-0 p-30 widget_categories bg-light account-info table-responsive" style="overflow: visible;">
                            <h4 class="widget-title down-line mb-30">{{ __('My Delivery Jobs') }}</h4>

                            @include('alerts.form-success')
                            @include('alerts.form-error')

                            <table class="table order-table" cellspacing="0" id="example" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order #') }}</th>
                                        <th>{{ __('Earnings') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($jobs as $job)
                                        <tr>
                                            <td>{{ $job->order->order_number }}</td>
                                            <td>{{ \PriceHelper::showOrderCurrencyPrice($job->rider_earnings, $job->order->currency_sign) }}</td>
                                            <td>
                                                <span class="badge badge-dark p-2">{{ ucwords(str_replace('_', ' ', $job->status)) }}</span>
                                            </td>
                                            <td>{{ $job->created_at->format('d M Y') }}</td>
                                            <td>
                                                <div class="dropdown d-inline-block">
                                                    <button class="btn btn-dark btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport" aria-haspopup="true" aria-expanded="false">
                                                        {{ __('Action') }}
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="{{ route('rider-delivery-details', $job->id) }}">{{ __('View Details') }}</a>
                                                        <div class="dropdown-divider"></div>
                                                        <h6 class="dropdown-header text-uppercase" style="font-size: 0.7rem;">{{ __('Update Status') }}</h6>
                                                        <a class="dropdown-item" href="javascript:;" onclick="updateJobStatus('{{ $job->id }}', 'picked_up')">{{ __('Mark as Picked Up') }}</a>
                                                        <a class="dropdown-item" href="javascript:;" onclick="updateJobStatus('{{ $job->id }}', 'on_delivery')">{{ __('Out for Delivery') }}</a>
                                                        <a class="dropdown-item" href="javascript:;" onclick="updateJobStatus('{{ $job->id }}', 'delivered')">{{ __('Mark as Delivered') }}</a>
                                                        <a class="dropdown-item" href="javascript:;" onclick="updateJobStatus('{{ $job->id }}', 'returning')">{{ __('Initiate Return') }}</a>
                                                    </div>
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

@includeIf('partials.global.common-footer')
@endsection

@section('script')
<script src="{{ asset('assets/front/js/dataTables.min.js') }}" defer></script>
<script src="{{ asset('assets/front/js/user.js') }}" defer></script>
<script>
    function updateJobStatus(jobId, status) {
        if(confirm('Are you sure you want to update status to ' + status.replace('_', ' ') + '?')) {
            let baseUrl = "{{ url('/rider/delivery/job/status') }}";
            let form = document.getElementById('job-status-update-form');
            form.action = baseUrl + '/' + jobId;
            document.getElementById('job-target-status').value = status;
            form.submit();
        }
    }
</script>

<form id="job-status-update-form" action="" method="POST" style="display:none;">
    @csrf
    <input type="hidden" name="status" id="job-target-status">
</form>
@endsection
