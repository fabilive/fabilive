@extends('layouts.admin')

@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Financial & Reconciliation Report') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a></li>
                    <li><a href="javascript:;">{{ __('Reports') }}</a></li>
                    <li><a href="{{ route('admin-financial-report') }}">{{ __('Financial Report') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="product-area">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-area-inner">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card p-3 mb-4 shadow-sm border-0">
                                <h6 class="text-muted">{{ __('Total Admin Commission') }}</h6>
                                <h3 class="mb-0 text-primary">{{ $gs->currency_sign }}{{ number_format($summary['total_admin_commission'], 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 mb-4 shadow-sm border-0">
                                <h6 class="text-muted">{{ __('Total Delivery Fees') }}</h6>
                                <h3 class="mb-0 text-success">{{ $gs->currency_sign }}{{ number_format($summary['total_delivery_fees'], 2) }}</h3>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card p-3 mb-4 shadow-sm border-0">
                                <h6 class="text-muted">{{ __('Transactions Processed') }}</h6>
                                <h3 class="mb-0 text-info">{{ $summary['transaction_count'] }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="mr-table allproduct mt-4">
                        <div class="col-lg-12 pt-0 badge-area d-flex justify-content-between">
                            <h4 class="heading">{{ __('Reconciliation Breakdown') }}</h4>
                            <a href="{{ route('admin-financial-report-export', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="add-btn">
                                <i class="fas fa-download"></i> {{ __('Export CSV') }}
                            </a>
                        </div>

                        <div class="p-3">
                            <form action="{{ route('admin-financial-report') }}" method="GET" class="row">
                                <div class="col-md-4">
                                    <input type="date" name="start_date" value="{{ $startDate }}" class="input-field" placeholder="Start Date">
                                </div>
                                <div class="col-md-4">
                                    <input type="date" name="end_date" value="{{ $endDate }}" class="input-field" placeholder="End Date">
                                </div>
                                <div class="col-md-4">
                                    <button type="submit" class="submit-btn">{{ __('Filter') }}</button>
                                </div>
                            </form>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-hover dt-responsive" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('Order #') }}</th>
                                        <th>{{ __('Gross Amount') }}</th>
                                        <th>{{ __('Admin Fee') }}</th>
                                        <th>{{ __('Rider Fee') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Date') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reconciliationData as $data)
                                    <tr>
                                        <td>{{ $data['order_number'] }}</td>
                                        <td>{{ $gs->currency_sign }}{{ number_format($data['gross_amount'], 2) }}</td>
                                        <td>{{ $gs->currency_sign }}{{ number_format($data['admin_commission'], 2) }}</td>
                                        <td>{{ $gs->currency_sign }}{{ number_format($data['rider_fee'], 2) }}</td>
                                        <td><span class="badge badge-info">{{ $data['delivery_job_status'] }}</span></td>
                                        <td>{{ $data['date'] }}</td>
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
@endsection
