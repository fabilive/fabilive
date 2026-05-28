@extends('layouts.load')
@section('content')

<div class="content-area">
    <div class="add-product-content1">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('alerts.admin.form-error')
                        <form id="geniusformdata" action="{{route('vendor-flash-products-store')}}" method="POST"
                            enctype="multipart/form-data">
                            {{csrf_field()}}

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Select Product') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select name="product_id" required="" class="input-field">
                                        <option value="">{{ __('Select Product') }}</option>
                                        @foreach($products as $prod)
                                            <option value="{{$prod->id}}">{{$prod->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Select Time Slot') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select name="time_slot_id" required="" class="input-field">
                                        <option value="">{{ __('Select Time Slot') }}</option>
                                        @foreach($time_slots as $slot)
                                            <option value="{{$slot->id}}">{{$slot->name}} ({{ \Carbon\Carbon::parse($slot->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('h:i A') }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Flash Date') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="date" class="input-field" name="flash_date" required="" value="{{ date('Y-m-d') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Flash Price') }} *</h4>
                                        <p class="sub-heading">({{ __('In Base Currency') }})</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="number" step="0.01" class="input-field" name="flash_price" placeholder="{{ __('Enter Flash Price') }}" required="" value="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Stock Quantity') }} *</h4>
                                        <p class="sub-heading">({{ __('Units available for Flash Sale') }})</p>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="number" class="input-field" name="flash_quantity" placeholder="{{ __('Enter Quantity') }}" required="" min="1" value="">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Submit Product') }}</button>
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
