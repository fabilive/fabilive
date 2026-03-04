@extends('layouts.admin')
@section('content')
<input type="hidden" id="headerdata" value="{{ __('Delivery Fee') }}">
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Delivery Fee Management') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="javascript:;">{{ __('Delivery Fee') }}</a></li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add Button -->
    <div class="text-right mb-3">
        <a href="{{ route('admin-deliveryfee-create') }}" class="add-btn">
            <i class="fas fa-plus"></i> Add Delivery Fee
        </a>
    </div>

    <!-- Table -->
    <div class="product-area">
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>Weight</th>
                        <th>Start Range</th>
                        <th>End Range</th>
                        <th>Fee</th>
                        <th width="180px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveryFees as $fee)
                        <tr>
                            <td>{{ $fee->weight }}</td>
                            <td>{{ $fee->start_range }}</td>
                            <td>{{ $fee->end_range }}</td>
                            <td>{{ $fee->fee }}</td>
                            <td>
                                <a href="{{ route('admin-deliveryfee-edit', $fee->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('admin-deliveryfee-delete', $fee->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No Delivery Fees Found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
