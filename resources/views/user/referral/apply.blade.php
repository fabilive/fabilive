@extends('layouts.front')

@section('content')
@include('partials.global.common-header')

<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
    <div class="container">
        <div class="row text-center text-white">
            <div class="col-12">
                <h3 class="mb-2 text-white">{{ __('Ambassador Program') }}</h3>
            </div>
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                        <li class="breadcrumb-item"><a href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Ambassador Program') }}</li>
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
            <div class="col-xl-4">
                @include('partials.user.dashboard-sidebar')
            </div>
            <div class="col-xl-8">
                <div class="widget border-0 p-40 widget_categories bg-light account-info">
                    <h4 class="widget-title down-line mb-30">{{ __('Become a Fabilive Ambassador') }}</h4>
                    
                    <div class="row mb-4">
                        <div class="col-lg-12">
                            <div class="card border-0 shadow-sm rounded-lg overflow-hidden">
                                <div class="card-header bg-primary text-white py-3">
                                    <h5 class="mb-0 font-weight-bold"><i class="fas fa-gem mr-2"></i> {{ __('Why join the Ambassador Program?') }}</h5>
                                </div>
                                <div class="card-body bg-white p-4">
                                    <div class="row text-center">
                                        <div class="col-md-6 mb-4 mb-md-0 border-right">
                                            <div class="display-4 text-success mb-2"><i class="fas fa-tags"></i></div>
                                            <h6 class="font-weight-bold">{{ __('For Your Friends') }}</h6>
                                            <p class="text-muted mb-0">{{ __('They get a instant 200 CFA discount on their first purchase using your code.') }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="display-4 text-primary mb-2"><i class="fas fa-wallet"></i></div>
                                            <h6 class="font-weight-bold">{{ __('For You') }}</h6>
                                            <p class="text-muted mb-0">{{ __('You earn 100 CFA for every new user who makes their first purchase.') }}</p>
                                        </div>
                                    </div>
                                    <hr class="my-4">
                                    <p class="small text-muted text-center mb-0">
                                        <i class="fas fa-info-circle mr-1"></i> {{ __('Rewards are locked until you reach a balance of 25,000 CFA, at which point you can withdraw or use them.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="edit-info-area">
                        <div class="body p-0">
                            <div class="edit-info-area-form">
                                <div class="gocover" style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);"></div>
                                @include('alerts.admin.form-both')
                                
                                <form id="referral-apply-form" action="{{ route('user-referral-apply-submit') }}" method="POST">
                                    @csrf
                                    <div class="row mb-4">
                                        <div class="col-lg-12">
                                            <label class="font-weight-bold mb-2">{{ __('Ambassador Name') }} *</label>
                                            <input type="text" class="form-control border-0 shadow-sm py-4" name="referral_name" placeholder="{{ __('e.g. John Doe') }}" value="{{ Auth::user()->name }}" required>
                                            <small class="text-muted">{{ __('This name will be associated with your referral profile.') }}</small>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-lg-12">
                                            <label class="font-weight-bold mb-2">{{ __('Your Unique Referral Code / Coupon Name') }} *</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control border-0 shadow-sm py-4" name="referral_code" id="slug-input" placeholder="{{ __('e.g. SAVE200') }}" required maxlength="20">
                                            </div>
                                            <small class="text-muted">{{ __('This code will be used as a coupon code by your friends. Use letters and numbers only.') }}</small>
                                        </div>
                                    </div>

                                    <div class="row mt-4">
                                        <div class="col-lg-12">
                                            <button type="submit" class="mybtn1 w-100 py-3 shadow-sm" id="apply-btn">
                                                {{ __('Activate My Ambassador Profile') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
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
<script type="text/javascript">
    $(document).ready(function() {
        // Auto-slugify the code input
        $('#slug-input').on('keyup', function() {
            let val = $(this).val();
            val = val.toUpperCase().replace(/[^A-Z0-9]/g, '');
            $(this).val(val);
        });

        $('#referral-apply-form').on('submit', function(e) {
            e.preventDefault();
            $('.gocover').show();
            $('#apply-btn').prop('disabled', true).html('{{ __("Processing...") }}');

            $.ajax({
                method: "POST",
                url: $(this).prop('action'),
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    if ((data.errors)) {
                        $('.gocover').hide();
                        $('#apply-btn').prop('disabled', false).html('{{ __("Activate My Ambassador Profile") }}');
                        $('.alert-danger').show();
                        $('.alert-danger ul').html('');
                        for (var error in data.errors) {
                            $('.alert-danger ul').append('<li>' + data.errors[error] + '</li>');
                        }
                    } else {
                        $('.gocover').hide();
                        $('.alert-success').show();
                        $('.alert-success p').html(data);
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                }
            });
        });
    });
</script>
@endsection
