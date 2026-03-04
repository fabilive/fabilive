<?php

$pay_data = $gateway->convertAutoData();

?>

<?php if($payment == 'cod'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->title); ?>">

<?php endif; ?>

<?php if($payment == 'paypal'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'campay'): ?>
    <div class="row">
        <div class="col-lg-12">
            <label><?php echo e(__('Mobile Money Number')); ?> *</label>
            <input class="form-control" name="phone" type="text" required="" placeholder="<?php echo e(__('237xxxxxxxx')); ?>" value="<?php echo e(Auth::check() ? Auth::user()->phone : ''); ?>" />
            <small><?php echo e(__('Please enter your phone number with country code (e.g., 237xxxxxxxxx)')); ?></small>
        </div>
    </div>
    <input type="hidden" name="method" value="Campay">
<?php endif; ?>

<?php if($payment == 'hitpay'): ?>
    <input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">
<?php endif; ?>

<?php if($payment == 'instamojo'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'razorpay'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'paytm'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>


<?php if($payment == 'sslcommerz'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'flutterwave'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'paystack'): ?>

<input type="hidden" name="txnid" id="ref_id" value="">
<input type="hidden" name="sub" id="sub" value="0">
<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'voguepay'): ?>

<input type="hidden" name="txnid" id="ref_id" value="">
<input type="hidden" name="sub" id="sub" value="0">
<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'mollie'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<?php endif; ?>

<?php if($payment == 'authorize.net'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->name); ?>">

<div class="row">

  <div class="col-lg-6">
    <input class="form-control card-elements" name="cardNumber" type="text" id="cardNumber"
      placeholder="<?php echo e(__('Card Number')); ?>" autocomplete="off" autofocus oninput="validateCard(this.value);" />
    <span id="errCard"></span>
  </div>

  <div class="col-lg-6">
    <input class="form-control card-elements" name="cardCode" id="cardCVC" type="text"
      placeholder="<?php echo e(__('Card Code')); ?>" autocomplete="off" oninput="validateCVC(this.value);" />
    <span id="errCVC"></span>
  </div>

  <div class="col-lg-6">
    <input class="form-control card-elements" name="month" type="text" placeholder="<?php echo e(__('Month')); ?>" />
  </div>

  <div class="col-lg-6">
    <input class="form-control card-elements" name="year" type="text" placeholder="<?php echo e(__('Year')); ?>" />
  </div>

</div>

<script type="text/javascript" src="<?php echo e(asset('assets/front/js/payvalid.js')); ?>"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/front/js/paymin.js')); ?>"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript" src="<?php echo e(asset('assets/front/js/payform.js')); ?>"></script>

<script type="text/javascript">
  var cnstatus = false;
    var dateStatus = false;
    var cvcStatus = false;

    function validateCard(cn) {
      "use strict";
      cnstatus = Stripe.card.validateCardNumber(cn);
      if (!cnstatus) {
        $("#errCard").html('<?php echo e(__("Card number not valid")); ?>');
      } else {
        $("#errCard").html('');
      }
    }

    function validateCVC(cvc) {
      "use strict";
      cvcStatus = Stripe.card.validateCVC(cvc);
      if (!cvcStatus) {
        $("#errCVC").html('<?php echo e(__("CVC number not valid")); ?>');
      } else {
        $("#errCVC").html('');
      }

    }



</script>


<?php endif; ?>



<?php if($gateway->keyword == 'mercadopago'): ?>

<?php
$paydata = $gateway->convertAutoData();
?>

<div id="cardNumber"></div>
<div id="expirationDate"></div>
<div id="securityCode"> </div>


<div class="form-group pb-2">
  <input class="form-control" type="text" id="cardholderName" data-checkout="cardholderName"
    placeholder="<?php echo e(__('Card Holder Name')); ?>" required />
</div>
<div class="form-group py-2">
  <input class="form-control" type="text" id="docNumber" data-checkout="docNumber"
    placeholder="<?php echo e(__('Document Number')); ?>" required />
</div>
<div class="form-group py-2">
  <select id="docType" class="form-control" name="docType" data-checkout="docType" type="text"></select>
</div>




<script>
  var mp = new MercadoPago("<?php echo e($paydata['public_key']); ?>");
        var cardNumberElement = mp.fields.create('cardNumber', {
            placeholder: "Card Number"
        }).mount('cardNumber');

        var expirationDateElement = mp.fields.create('expirationDate', {
            placeholder: "MM/YY",
        }).mount('expirationDate');

        var securityCodeElement = mp.fields.create('securityCode', {
            placeholder: "Security Code"
        }).mount('securityCode');


        (async function getIdentificationTypes() {
            try {
                var identificationTypes = await mp.getIdentificationTypes();

                var identificationTypeElement = document.getElementById('docType');

                createSelectOptions(identificationTypeElement, identificationTypes);

            } catch (e) {
                return console.error('Error getting identificationTypes: ', e);
            }
        })();

        function createSelectOptions(elem, options, labelsAndKeys = {
            label: "name",
            value: "id"
        }) {

            var {
                label,
                value
            } = labelsAndKeys;

         
            var tempOptions = document.createDocumentFragment();

            options.forEach(option => {
                var optValue = option[value];
                var optLabel = option[label];

                var opt = document.createElement('option');
                opt.value = optValue;
                opt.textContent = optLabel;


                tempOptions.appendChild(opt);
            });

            elem.appendChild(tempOptions);
        }
        cardNumberElement.on('binChange', getPaymentMethods);
        async function getPaymentMethods(data) {
            var {
                bin
            } = data
            var {
                results
            } = await mp.getPaymentMethods({
                bin
            });
            console.log(results);
            return results[0];
        }

        async function getIssuers(paymentMethodId, bin) {
            var issuears = await mp.getIssuers({
                paymentMethodId,
                bin
            });
           
            return issuers;
        };

        async function getInstallments(paymentMethodId, bin) {
            var installments = await mp.getInstallments({
                amount: document.getElementById('transactionAmount').value,
                bin,
                paymentTypeId: 'credit_card'
            });

        };

        async function createCardToken() {
            var token = await mp.fields.createCardToken({
                cardholderName,
                identificationType,
                identificationNumber,
            });

        }
        var doSubmit = false;
        $(document).on('submit', '#mercadopago', function(e) {
            getCardToken();
            e.preventDefault();
        });
        async function getCardToken() {
            if (!doSubmit) {
                let $form = document.getElementById('mercadopago');
                var token = await mp.fields.createCardToken({
                    cardholderName: document.getElementById('cardholderName').value,
                    identificationType: document.getElementById('docType').value,
                    identificationNumber: document.getElementById('docNumber').value,
                })
                setCardTokenAndPay(token.id)
            }
        };

        function setCardTokenAndPay(token) {
            let form = document.getElementById('mercadopago');
            let card = document.createElement('input');
            card.setAttribute('name', 'token');
            card.setAttribute('type', 'hidden');
            card.setAttribute('value', token);
            form.appendChild(card);
            doSubmit = true;
            form.submit();
        };
</script>
<?php endif; ?>


<?php if($payment == 'other'): ?>

<input type="hidden" name="method" value="<?php echo e($gateway->title); ?>">

<div class="row">

  <div class="col-lg-12 pb-2">

    <?php echo clean($gateway->details , array('Attr.EnableID' => true)); ?>


  </div>


  <div class="col-lg-6">
    <label><?php echo e(__('Transaction ID#')); ?> *</label>
    <input class="form-control" name="txnid" type="text" required="" placeholder="<?php echo e(__('Transaction ID#')); ?>" />
  </div>

</div>

<?php endif; ?><?php /**PATH C:\Users\Shilley Pc\fabilive\resources\views/load/payment.blade.php ENDPATH**/ ?>