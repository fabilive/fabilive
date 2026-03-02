@extends('layouts.admin') 
@section('content')
<div class="content-area">
    <div class="mr-breadcrumb">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="heading">{{ __('Edit Delivery Fee') }}</h4>
                <ul class="links">
                    <li><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li><a href="{{ route('admin-deliveryfee-index') }}">{{ __('Delivery Fee') }}</a></li>
                    <li><a href="javascript:;">{{ __('Edit') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <form action="{{ route('admin-deliveryfee-update', $id) }}" method="POST">
        @csrf
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <div id="weight-container">
            @foreach($deliveryFees as $key => $deliveryFee)
            <div class="weight-row d-flex" style="gap:15px; margin-bottom:10px;">
                <div class="form-group">
                    <label>Weight Unit</label>
                    <select name="weight[]" class="form-control" required>
                        <option value="">-- Select Weight Unit --</option>
                        <option value="Kg" {{ $deliveryFee->weight == 'Kg' ? 'selected' : '' }}>Kg</option>
                        <option value="Gram" {{ $deliveryFee->weight == 'Gram' ? 'selected' : '' }}>Gram</option>
                        <option value="Ton" {{ $deliveryFee->weight == 'Ton' ? 'selected' : '' }}>Ton</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Start Range</label>
                    <input type="number" name="start_range[]" class="form-control" value="{{ $deliveryFee->start_range }}" required>
                </div>
                <div class="form-group">
                    <label>End Range</label>
                    <input type="number" name="end_range[]" class="form-control" value="{{ $deliveryFee->end_range }}" required>
                </div>
                <div class="form-group">
                    <label>Fee</label>
                    <input type="number" name="fee[]" class="form-control" value="{{ $deliveryFee->fee }}" required>
                </div>
                <div class="form-group" style="display:flex;align-items:center;gap:5px;margin-top:25px;">
                    <button type="button" class="btn btn-success btn-add">+</button>
                    <button type="button" class="btn btn-danger btn-remove" style="{{ $key == 0 ? 'display:none;' : '' }}">-</button>
                </div>
            </div>
            @endforeach
        </div>
        <script>
        $(document).ready(function () {
            $(document).on("click", ".btn-add", function () {
                let clone = $(this).closest(".weight-row").clone();
                clone.find("input").val("");
                clone.find("select").val("");
                clone.find(".btn-remove").show();
                $("#weight-container").append(clone);
            });
            $(document).on("click", ".btn-remove", function () {
                $(this).closest(".weight-row").remove();
            });
        });
        </script>
        <button class="btn btn-primary">Update Delivery Fee</button>
        <a href="{{ route('admin-deliveryfee-index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
