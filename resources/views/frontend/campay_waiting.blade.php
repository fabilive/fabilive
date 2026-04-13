@extends('layouts.front')

@section('content')
<section class="checkout-area py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 80vh; display: flex; align-items: center;">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-10 col-xl-8">
            <div class="order-box shadow-lg border-0 rounded-lg overflow-hidden" style="background: white; border-radius: 20px;">
               <div class="row no-gutters">
                  
                  <!-- Left side: Status Visuals -->
                  <div class="col-md-7 p-5 text-center d-flex flex-column justify-content-center border-right">
                     <div id="status-icon-container" class="mb-4">
                        <div class="spinner-border text-primary" role="status" id="payment-spinner" style="width: 4rem; height: 4rem; border-width: .3em;">
                           <span class="sr-only">{{ __('Loading...') }}</span>
                        </div>
                        <div id="success-icon" class="text-success d-none" style="font-size: 5rem;">
                           <i class="fas fa-check-circle"></i>
                        </div>
                     </div>

                     <h3 class="title font-weight-bold mb-3" id="main-title">{{ __('Processing Payment') }}</h3>
                     <p class="text-muted mb-0" id="instruction-text">{{ __('Please check your phone for a payment prompt from Campay.') }}</p>
                  </div>

                  <!-- Right side: Payment Details -->
                  <div class="col-md-5 p-5 bg-light d-flex flex-column justify-content-center">
                     <h5 class="font-weight-bold mb-4 text-dark">{{ __('Order Details') }}</h5>
                     
                     <div class="payment-details">
                        <div class="mb-4">
                           <label class="text-secondary small text-uppercase mb-1 d-block">{{ __('Order Number') }}</label>
                           <span class="font-weight-bold text-dark h5 d-block">{{ $order->order_number }}</span>
                        </div>

                        <div class="mb-4">
                           <label class="text-secondary small text-uppercase mb-1 d-block">{{ __('Total Amount') }}</label>
                           <span class="font-weight-bold text-primary h3 d-block">
                              {{ App\Helpers\PriceHelper::showOrderCurrencyPrice($order->pay_amount * $order->currency_value, $order->currency_sign) }}
                           </span>
                        </div>

                        <div class="mb-4">
                           <label class="text-secondary small text-uppercase mb-1 d-block">{{ __('Current Status') }}</label>
                           <span id="payment-status" class="badge badge-pill badge-warning px-3 py-2" style="font-size: 0.9rem; letter-spacing: 0.5px;">
                              {{ __('Waiting for confirmation...') }}
                           </span>
                        </div>
                     </div>

                     <div class="mt-4 pt-4 border-top">
                        <p class="text-muted small mb-0" id="footer-text">
                           <i class="fas fa-info-circle mr-1"></i>
                           {{ __('This page will automatically redirect once your payment is confirmed.') }}
                        </p>
                     </div>
                  </div>

               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<style>
    .order-box {
        transition: all 0.3s ease;
    }
    .order-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 1rem 3rem rgba(0,0,0,.175)!important;
    }
    #success-icon i {
        animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes scaleIn {
        from { transform: scale(0); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }
    .border-right {
        border-right: 1px solid #dee2e6!important;
    }
    @media (max-width: 767.98px) {
        .border-right {
            border-right: none!important;
            border-bottom: 1px solid #dee2e6!important;
        }
    }
</style>

<script>
    let pollInterval;
    
    function checkStatus() {
        console.log("Checking payment status...");
        
        fetch("{{ route('front.campay.check', $order->order_number) }}", {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.status === 'successful') {
                handleSuccess(data.redirect_url);
            } else if (data.status === 'error') {
                handleError(data.message || "An error occurred");
            }
        })
        .catch(error => {
            console.error('Error checking status:', error);
        });
    }

    function handleSuccess(redirectUrl) {
        // Stop polling
        clearInterval(pollInterval);
        
        // Update UI
        const statusBadge = document.getElementById('payment-status');
        const spinner = document.getElementById('payment-spinner');
        const successIcon = document.getElementById('success-icon');
        const mainTitle = document.getElementById('main-title');
        const instructionText = document.getElementById('instruction-text');
        
        statusBadge.innerText = "{{ __('Successful') }}";
        statusBadge.classList.replace('badge-warning', 'badge-success');
        
        spinner.classList.add('d-none');
        successIcon.classList.remove('d-none');
        
        mainTitle.innerText = "{{ __('Payment Confirmed!') }}";
        instructionText.innerText = "{{ __('Your order has been placed successfully. Redirecting...') }}";
        
        // Final aesthetic delay before redirect
        setTimeout(() => {
            window.location.href = redirectUrl;
        }, 2000);
    }

    function handleError(message) {
        const statusBadge = document.getElementById('payment-status');
        statusBadge.innerText = message;
        statusBadge.classList.replace('badge-warning', 'badge-danger');
    }

    // Start polling every 5 seconds
    pollInterval = setInterval(checkStatus, 5000);
    
    // Initial check
    checkStatus();
</script>
@endsection
