@extends('layouts.load')
@section('content')

<div class="content-area">
    <div class="add-product-content1">
        <div class="row">
            <div class="col-lg-12">
                <div class="product-description">
                    <div class="body-area">
                        @include('alerts.admin.form-error')
                        <form id="geniusformdata" action="{{route('vendor-deal-product-update', $data->id)}}" method="POST"
                            enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="_method" value="PUT">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('Product Name') }}</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="text" class="input-field" disabled value="{{ $data->name }}">
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
                                        <option value="{{ $page->id }}" {{ $data->deal_page_id == $page->id ? 'selected' : '' }}>{{ $page->name }}</option>
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
                                    <input type="number" step="0.01" class="input-field" name="deal_price" placeholder="{{ __('Enter Deal Price') }}" required value="{{ $data->getRawOriginal('price') }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        <h4 class="heading">{{ __('End Date') }} *</h4>
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <input type="date" class="input-field" name="deal_end_date" required value="{{ $data->discount_date_end }}">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="left-area">
                                        
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <button class="addProductSubmit-btn" type="submit">{{ __('Update Deal') }}</button>
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
