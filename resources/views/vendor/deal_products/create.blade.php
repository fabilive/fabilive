@extends('layouts.load')
@section('content')

<div class="content-area">
    <div class="add-product-content1">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('alerts.admin.form-error')
                        <form id="geniusformdata" action="{{route('vendor-deal-product-store')}}" method="POST"
                            enctype="multipart/form-data">
                            {{csrf_field()}}

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Select Product') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select class="input-field" name="product_id" required>
                                        <option value="">{{ __('Select a Product') }}</option>
                                        @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Deal Category') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <select class="input-field" name="deal_page_id" required>
                                        <option value="">{{ __('Select Deal Category') }}</option>
                                        @foreach($dealPages as $page)
                                        <option value="{{ $page->id }}">{{ $page->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Deal Price') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="number" step="0.01" class="input-field" name="deal_price" placeholder="{{ __('Enter Deal Price') }}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('End Date') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="date" class="input-field" name="deal_end_date" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Add to Deal') }}</button>
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
