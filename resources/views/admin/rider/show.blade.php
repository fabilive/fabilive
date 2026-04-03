@extends('layouts.admin')
@php use App\Helpers\PriceHelper; @endphp
@section('styles')
    <style type="text/css">
        .table-responsive {
            overflow-x: hidden;
        }

        table#example2 {
            margin-left: 10px;
        }
    </style>
@endsection
@section('content')
    <div class="content-area">
        <div class="mr-breadcrumb">
            <div class="row">
                <div class="col-lg-12">
                    <h4 class="heading">{{ __('Rider Details') }} <a class="add-btn" href="{{ url()->previous() }}"><i
                                class="fas fa-arrow-left"></i> {{ __('Back') }}</a></h4>
                    <ul class="links">
                        <li>
                            <a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }} </a>
                        </li>
                        <li>
                            <a href="{{ route('admin-rider-index') }}">{{ __('Riders') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('admin-rider-show', $data->id) }}">{{ __('Details') }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="add-product-content1 customar-details-area add-product-content2">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-description">
                        <div class="body-area">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="user-image">
                                        @if ($data->is_provider == 1)
                                            <img src="{{ $data->photo ? asset($data->photo) : asset('assets/images/' . $gs->user_image) }}"
                                                alt="No Image">
                                        @else
                                            <img src="{{ $data->photo ? asset('assets/images/users/' . $data->photo) : asset('assets/images/' . $gs->user_image) }}"
                                                alt="No Image">
                                        @endif
                                        <a href="javascript:;" class="mybtn1 send" data-email="{{ $data->email }}"
                                            data-toggle="modal" data-target="#vendorform">{{ __('Send Message') }}</a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="table-responsive show-table">
                                        <table class="table">
                                            <tr>
                                                <th>{{ __('ID#') }}</th>
                                                <td>{{ $data->id }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Name') }}</th>
                                                <td>{{ $data->name }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Email') }}</th>
                                                <td>{{ $data->email }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Phone') }}</th>
                                                <td>{{ $data->phone }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('Address') }}</th>
                                                <td>{{ $data->address }}</td>
                                            </tr>

                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="table-responsive show-table">
                                        <table class="table">

                                            @if ($data->country != null)
                                                <tr>
                                                    <th>{{ __('Country') }}</th>
                                                    <td>{{ $data->country }}</td>
                                                </tr>
                                            @endif
                                            @if ($data->city_id != null)
                                                <tr>
                                                    <th>{{ __('City') }}</th>
                                                    <td>{{ $data->city->city_name }}</td>
                                                </tr>
                                            @endif
                                            @if ($data->state_id != null)
                                                <tr>
                                                    <th>{{ __('State') }}</th>
                                                    <td>{{ $data->state->state }}</td>
                                                </tr>
                                            @endif
                                            @if ($data->fax != null)
                                                <tr>
                                                    <th>{{ __('Fax') }}</th>
                                                    <td>{{ $data->fax }}</td>
                                                </tr>
                                            @endif
                                            @if ($data->zip != null)
                                                <tr>
                                                    <th>{{ __('Zip Code') }}</th>
                                                    <td>{{ $data->zip }}</td>
                                                </tr>
                                            @endif
                                            <tr>
                                                <th>{{ __('Joined') }}</th>
                                                <td>{{ $data->created_at->diffForHumans() }}</td>
                                            </tr>
                                            <tr>
                                                <th>{{ __('SubMerchant Delivery Image') }}</th>
                                                <td><a href="{{ asset('assets/images/submerchantagreementrider/' . $data->submerchant_agreement) }}"
                                                        target="_blank">View</a></td>
                                            </tr>

                                            {{-- Ensure $data is the rider object passed from controller --}}
                                            @if (isset($data) && $data->rider_type === 'company')
                                                <tr>
                                                    <th>{{ __('Live Company Selfie') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->live_selfie_company) &&
                                                                file_exists(public_path('assets/images/liveselfiecompany/' . $data->live_selfie_company)))
                                                            <a href="{{ asset('assets/images/liveselfiecompany/' . $data->live_selfie_company) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Company Registration Documents') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->company_registration_document) &&
                                                                file_exists(public_path('assets/images/companyregistrationdocument/' . $data->company_registration_document)))
                                                            <a href="{{ asset('assets/images/companyregistrationdocument/' . $data->company_registration_document) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Valid ID/Passport of Company Owner') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->id_company_owner) &&
                                                                file_exists(public_path('assets/images/companyownerid/' . $data->id_company_owner)))
                                                            <a href="{{ asset('assets/images/companyownerid/' . $data->id_company_owner) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Transport License / Permis de Transport.') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->transport_license) &&
                                                                file_exists(public_path('assets/images/transportlicense/' . $data->transport_license)))
                                                            <a href="{{ asset('assets/images/transportlicense/' . $data->transport_license) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Company Insurance Cerfificate') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->insurance_certificate_company) &&
                                                                file_exists(public_path('assets/images/insurancecertificatecompany/' . $data->insurance_certificate_company)))
                                                            <a href="{{ asset('assets/images/insurancecertificatecompany/' . $data->insurance_certificate_company) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Fabilive Company Rider Agreement') }}</th>
                                                    <td><a href="{{ asset('assets/pdf/fabiliveridercompany.pdf') }}"
                                                            target="_blank">View</a></td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Company Taxpayer Registration number (TIN)') }}</th>
                                                    <td>{{ $data->tin_company ?? __('Not provided') }}</td>
                                                </tr>
                                            @elseif(isset($data) && $data->rider_type === 'individual')
                                                <tr>
                                                    <th>{{ __('Live Individual Selfie') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->live_selfie_individual) &&
                                                                file_exists(public_path('assets/images/liveselfieindividual/' . $data->live_selfie_individual)))
                                                            <a href="{{ asset('assets/images/liveselfieindividual/' . $data->live_selfie_individual) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Individual Drivers License') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->driver_license_individual) &&
                                                                file_exists(public_path('assets/images/driverlicenseindividual/' . $data->driver_license_individual)))
                                                            <a href="{{ asset('assets/images/driverlicenseindividual/' . $data->driver_license_individual) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Vehicle Registration Certificate (Carte Grise)') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->vehicle_registration_certificate) &&
                                                                file_exists(public_path('assets/images/vehicleregistrationcertificate/' . $data->vehicle_registration_certificate)))
                                                            <a href="{{ asset('assets/images/vehicleregistrationcertificate/' . $data->vehicle_registration_certificate) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Insurance Certificate') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->insurance_certificate_individual) &&
                                                                file_exists(public_path('assets/images/insurancecertificateindividual/' . $data->insurance_certificate_individual)))
                                                            <a href="{{ asset('assets/images/insurancecertificateindividual/' . $data->insurance_certificate_individual) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Criminal records / Police Report') }}</th>
                                                    <td>
                                                        @if (
                                                            !empty($data->criminal_records) &&
                                                                file_exists(public_path('assets/images/criminalrecords/' . $data->criminal_records)))
                                                            <a href="{{ asset('assets/images/criminalrecords/' . $data->criminal_records) }}"
                                                                target="_blank">View</a>
                                                        @else
                                                            <span>{{ __('Not available') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Fabilive Rider Agreement') }}</th>
                                                    <td>
                                                        @if(!empty($data->submerchant_agreement))
                                                            <a href="{{ asset('assets/images/submerchantagreementrider/'.$data->submerchant_agreement) }}" target="_blank">
                                                                View
                                                            </a>
                                                        @else
                                                            <span class="text-danger">Not Uploaded</span>
                                                        @endif
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Vehicle Type Individual') }}</th>
                                                    <td>{{ $data->vehicle_type_individual ?? __('Not provided') }}</td>
                                                </tr>

                                                <tr>
                                                    <th>{{ __('Individual Taxpayer Registration number (TIN)') }}</th>
                                                    <td>{{ $data->tin_individual ?? __('Not provided') }}</td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="2">
                                                        {{ __('No rider type selected or data not found.') }}</td>
                                                </tr>
                                            @endif

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order-table-wrap">
                            <div class="order-details-table">
                                <div class="mr-table">
                                    <h4 class="title">{{ __('Products Ordered') }}</h4>
                                    <div class="table-responsive">
                                        <table id="example2" class="table table-hover dt-responsive" cellspacing="0"
                                            width="100%">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Order ID') }}</th>
                                                    <th>{{ __('Purchase Date') }}</th>
                                                    <th>{{ __('Order Amount') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($data->orders as $order)
                                                    <tr>
                                                        <td><a
                                                                href="{{ route('admin-order-invoice', $order->order->id) }}">{{ sprintf("%'.08d", $order->order->id) }}</a>
                                                        </td>
                                                        <td>{{ Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}
                                                        </td>
                                                        <td>{{ PriceHelper::showOrderCurrencyPrice(
                                                            $order->order->pay_amount * $order->order->currency_value,
                                                            $order->order->currency_sign,
                                                        ) }}
                                                        </td>
                                                        <td>{{ ucwords($order->status) }}</td>
                                                        <td>
                                                            <a href=" {{ route('admin-order-show', $order->order->id) }}"
                                                                class="view-details">
                                                                <i class="fas fa-check"></i>{{ __('Details') }}
                                                            </a>
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
        </div>
    </div>

    {{-- MESSAGE MODAL --}}
    <div class="sub-categori">
        <div class="modal" id="vendorform" tabindex="-1" role="dialog" aria-labelledby="vendorformLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="vendorformLabel">{{ __('Send Message') }}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid p-0">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="contact-form">
                                        <form id="emailreply1">
                                            {{ csrf_field() }}
                                            <ul>
                                                <li>
                                                    <input type="email" class="input-field eml-val" id="eml1"
                                                        name="to" placeholder="{{ __(' Email') }} *"
                                                        value="" required="">
                                                </li>
                                                <li>
                                                    <input type="text" class="input-field" id="subj1"
                                                        name="subject" placeholder="{{ __(' Subject') }} *"
                                                        required="">
                                                </li>
                                                <li>
                                                    <textarea class="input-field textarea" name="message" id="msg1" placeholder="{{ __(' Your Message') }} *"
                                                        required=""></textarea>
                                                </li>
                                            </ul>
                                            <button class="submit-btn" id="emlsub1"
                                                type="submit">{{ __('Send Message') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MESSAGE MODAL ENDS --}}

@endsection

@section('scripts')
    <script type="text/javascript">
        (function($) {
            "use strict";

            $('#example2').dataTable({
                "ordering": false,
                'paging': false,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': false,
                'autoWidth': false,
                'responsive': true
            });

        })(jQuery);
    </script>
@endsection
