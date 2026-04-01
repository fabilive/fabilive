@extends('layouts.load')

@section('content')

            <div class="content-area">

              <div class="add-product-content1">
                <div class="row">
                  <div class="col-lg-12">
                    <div class="product-description">
                      <div class="body-area">
                        @include('alerts.admin.form-error')
                      <form id="geniusformdata" action="{{route('admin-payment-update',$data->id)}}" method="POST" enctype="multipart/form-data">
                        {{csrf_field()}}

                      @php
                        // Fix: handle both string 'automatic' and integer 1 (old DB rows)
                        $isAutomatic = ($data->type == 'automatic' || $data->type == 1);
                      @endphp

                      @if($isAutomatic)

                      {{-- Gateway Name --}}
                      <div class="row">
                        <div class="col-lg-4">
                          <div class="left-area">
                              <h4 class="heading">{{ __('Name') }} *</h4>
                              <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                          </div>
                        </div>
                        <div class="col-lg-7">
                          <input type="text" class="input-field" name="name" placeholder="{{ __('Name') }}" value="{{$data->name}}" required="">
                        </div>
                      </div>

                      {{-- CAMPAY: Dedicated API credential fields --}}
                      @if($data->keyword == 'campay')
                        @php
                          $campayData = $data->information ? json_decode($data->information, true) : [];
                        @endphp

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('App Username') }} *</h4>
                                <p class="sub-heading">{{ __('Campay App Username') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="pkey[username]" placeholder="App Username" value="{{ $campayData['username'] ?? '' }}" required="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('App Password') }} *</h4>
                                <p class="sub-heading">{{ __('Campay App Password') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="pkey[password]" placeholder="App Password" value="{{ $campayData['password'] ?? '' }}" required="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Permanent Access Token') }} *</h4>
                                <p class="sub-heading">{{ __('Campay Permanent Token') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="pkey[permanent_token]" placeholder="Permanent Access Token" value="{{ $campayData['permanent_token'] ?? '' }}" required="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('App Webhook Key') }}</h4>
                                <p class="sub-heading">{{ __('Campay Webhook Key') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="pkey[webhook_key]" placeholder="App Webhook Key" value="{{ $campayData['webhook_key'] ?? '' }}">
                          </div>
                        </div>

                        {{-- Preserve the text field as hidden --}}
                        <input type="hidden" name="pkey[text]" value="{{ $campayData['text'] ?? 'Pay via Campay' }}">

                      @elseif($data->information != null)
                        {{-- Generic automatic gateway: iterate over information JSON keys --}}
                        @foreach($data->convertAutoData() as $pkey => $pdata)

                        @if($pkey == 'sandbox_check')
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __( $data->name.' '.ucwords(str_replace('_',' ',$pkey)) ) }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <label class="switch">
                              <input type="checkbox" name="pkey[{{ __($pkey) }}]" value="1" {{ $pdata == 1 ? "checked":"" }}>
                              <span class="slider round"></span>
                            </label>
                          </div>
                        </div>
                        @else
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __( $data->name.' '.ucwords(str_replace('_',' ',$pkey)) ) }} *</h4>
                                <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="pkey[{{ __($pkey) }}]" placeholder="{{ __( $data->name.' '.ucwords(str_replace('_',' ',$pkey)) ) }}" value="{{ $pdata }}" required="">
                          </div>
                        </div>
                        @endif

                        @endforeach
                      @endif

                        <hr>
                       @php
                           $setCurrency = json_decode($data->currency_id);
                           if ($setCurrency == 0 || $setCurrency == null || $setCurrency == '*') {
                             $setCurrency = [];
                           }
                       @endphp
                        @foreach(DB::table('currencies')->get() as $dcurr)
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area"></div>
                          </div>
                          <div class="col-lg-7">
                            <ul class="list">
                              <li>
                                <input class="" name="currency_id[]" {{in_array($dcurr->id, (array)$setCurrency) ? 'checked' : ''}} type="checkbox" id="currency_id{{$dcurr->id}}" value="{{$dcurr->id}}">
                                <label for="currency_id{{$dcurr->id}}">{{$dcurr->name}}</label>
                              </li>
                            </ul>
                          </div>
                        </div>
                        @endforeach

                      @else
                        {{-- Manual payment gateway --}}
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Name') }} *</h4>
                                <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                            </div>
                          </div>
                          <div class="col-lg-7">
                            <input type="text" class="input-field" name="title" placeholder="{{ __('Name') }}" value="{{$data->title}}" required="">
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                                <h4 class="heading">{{ __('Subtitle') }} *</h4>
                                @if($data->keyword == null)
                                <p class="sub-heading">{{ __('(Optional)') }}</p>
                                @else
                                <p class="sub-heading">{{ __('(In Any Language)') }}</p>
                                @endif
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <input type="text" class="input-field" name="subtitle" placeholder="{{ __('Subtitle') }}" value="{{$data->subtitle}}">
                          </div>
                        </div>

                        @if($data->keyword == null)
                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area">
                              <h4 class="heading">{{ __('Description') }} *</h4>
                            </div>
                          </div>
                          <div class="col-lg-7">
                              <textarea class="nic-edit" name="details" placeholder="{{ __('Details') }}">{{ $data->details }}</textarea>
                          </div>
                        </div>
                        @endif
                      @endif

                        <div class="row">
                          <div class="col-lg-4">
                            <div class="left-area"></div>
                          </div>
                          <div class="col-lg-7">
                            <button class="addProductSubmit-btn" type="submit">{{ __('Save') }}</button>
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