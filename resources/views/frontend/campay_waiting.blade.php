@extends('layouts.front')

@section('content')
<section class="checkout-area py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); min-height: 80vh; display: flex; align-items: center;">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-6">
            <div class="order-box text-center p-5 shadow-lg border-0 rounded-lg" style="background: white; border-radius: 20px;">
               
               <div id="status-icon-container" class="mb-4">
                  <div class="spinner-border text-primary" role="status" id="payment-spinner" style="width: 3rem; height: 3rem;">
                     <span class="sr-only">{{ __('Loading...') }}</span>
                  </div>
                  <div id="success-icon" class="text-success d-none" style="font-size: 4rem;">
                     <i class="fas fa-check-circle"></i>
                  </div>
               </div>

               <h3 class="title font-weight-bold mb-3" id="main-title">{{ __('Processing Payment') }}</h3>
               <p class="text-muted mb-4" id="instruction-text">{{ __('Please check your phone for a payment prompt from Campay.') }}</p>
               
               <hr class="my-4">
               
               <div class="payment-details bg-light p-4 rounded-lg mb-4 text-left" style="border-radius: 12px;">
                  <div class="d-flex justify-content-between mb-2">
                     <span class="text-secondary">{{ __('Order Number:') }}</span>
                     <span class="font-weight-bold text-dark">{{ $order->order_number }}</span>
                  </div>
                  <div class="d-flex justify-content-between mb-2">
                     <span class="text-secondary">{{ __('Total Amount:') }}</span>
                     <span class="font-weight-bold text-primary" style="font-size: 1.2rem;">
                        {{ App\Helpers\PriceHelper::showOrderCurrencyPrice($order->pay_amount * $order->currency_value, $order->currency_sign) }}
                     </span>
                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                     <span class="text-secondary">{{ __('Current Status:') }}</span>
                     <span id="payment-status" class="badge badge-pill badge-warning px-3 py-2" style="font-size: 0.9rem;">
                        {{ __('Waiting for confirmation...') }}
                     </span>
                  </div>
               </div>

               <div class="mt-4">
                  <p class="text-muted small" id="footer-text">
                     <i class="fas fa-info-circle mr-1"></i>
                     {{ __('This page will automatically redirect once your payment is confirmed.') }}
                  </p>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<style>
    .order-box {
        transition: transform 0.3s ease;
    }
    .order-box:hover {
        transform: translateY(-5px);
    }
    #success-icon i {
        animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes scaleIn {
        from { transform: scale(0); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
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
