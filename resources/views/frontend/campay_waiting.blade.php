@extends('layouts.front')

@section('content')
<section class="checkout-area">
   <div class="container">
      <div class="row justify-content-center">
         <div class="col-lg-8">
            <div class="order-box text-center py-5">
               <h4 class="title">{{ __('Processing Payment') }}</h4>
               <p class="mb-4">{{ __('Please check your phone for a payment prompt from Campay.') }}</p>
               
               <div class="spinner-border text-primary mb-4" role="status">
                  <span class="sr-only">{{ __('Loading...') }}</span>
               </div>
               
               <p>{{ __('Order Number:') }} <strong>{{ $order->order_number }}</strong></p>
               <p>{{ __('Status:') }} <span id="payment-status" class="badge badge-warning">{{ __('Waiting for confirmation...') }}</span></p>

               <div class="mt-4">
                  <p class="text-muted small">{{ __('This page will automatically redirect once your payment is confirmed.') }}</p>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>

<script>
    function checkStatus() {
        fetch("{{ route('front.campay.check', $order->order_number) }}")
            .then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                }
            })
            .catch(error => console.error('Error checking status:', error));
    }

    // Poll every 5 seconds
    setInterval(checkStatus, 5000);
</script>
@endsection
