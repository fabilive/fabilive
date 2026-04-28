<!doctype html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{$gs->title}} - OTP Verification</title>
    <!-- favicon -->
    <link rel="icon"  type="image/x-icon" href="{{asset('assets/images/'.$gs->favicon)}}"/>
    <!-- Bootstrap -->
    <link href="{{asset('assets/admin/css/bootstrap.min.css')}}" rel="stylesheet" />
    <!-- Fontawesome -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/fontawesome.css')}}">
    <!-- icofont -->
    <link rel="stylesheet" href="{{asset('assets/admin/css/icofont.min.css')}}">
    <!-- Main Css -->
    <link href="{{asset('assets/admin/css/style.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/custom.css')}}" rel="stylesheet"/>
    <link href="{{asset('assets/admin/css/responsive.css')}}" rel="stylesheet" />
</head>
<body>
    <section class="login-signup">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5">
            <div class="login-area">
              <div class="header-area">
                <h4 class="title">{{ __('Two-Factor Authentication') }}</h4>
                <p class="text">{{ __('Please enter the 6-digit OTP code sent to your email.') }}</p>
              </div>
              <div class="login-form">
                @include('alerts.admin.form-login')
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="otpform" action="{{ route('admin.otp.verify') }}" method="POST">
                  @csrf
                  <div class="form-input">
                    <input type="text" name="otp_code" class="User Name" placeholder="{{ __('6-digit code') }}" required="" autofocus>
                    <i class="icofont-lock"></i>
                  </div>
                  
                  <button class="submit-btn">{{ __('Verify Code') }}</button>
                  <div class="mt-4 text-center">
                      <a href="{{ route('admin.logout') }}" class="text-secondary">{{ __('Cancel and Logout') }}</a>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Dashboard Core -->
    <script src="{{asset('assets/admin/js/vendors/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('assets/admin/js/vendors/bootstrap.min.js')}}"></script>
</body>
</html>
