<?php
$pay_data = $gateway->convertAutoData();
?>
<?php if($payment == 'paypal'): ?>
<?php endif; ?>
<?php if($payment == 'instamojo'): ?>
<?php endif; ?>
<?php if($payment == 'razorpay'): ?>
<?php endif; ?>
<?php if($payment == 'sslcommerz'): ?>
<?php endif; ?>
<?php if($payment == 'flutterwave'): ?>
<?php endif; ?>
<?php if($payment == 'paystack'): ?>
<input type="hidden" name="txnid" id="ref_id" value="">
<?php endif; ?>
<?php if($payment == 'voguepay'): ?>
<input type="hidden" name="txnid" id="ref_id" value="">
<?php endif; ?>
<?php if($payment == 'mollie'): ?>
<?php endif; ?>

<?php if($payment == 'authorize.net'): ?>
<div class="row mt-2">
  <div class="col-lg-4">
    <h5 class="title pt-1">
      <?php echo e(__('Card Number')); ?> *
    </h5>
  </div>
  <div class="col-lg-8">
    <input type="text" class="option form-control
            border w-50" name="cardNumber" placeholder="<?php echo e(__('Card Number')); ?>" required="">
  </div>
</div>
<div class="row mt-2">
  <div class="col-lg-4">
    <h5 class="title pt-1">
      <?php echo e(__('Card Code')); ?> *
    </h5>
  </div>
  <div class="col-lg-8">
    <input type="text" class="option form-control
            border w-50" name="cardCode" placeholder="<?php echo e(__('Card Code')); ?>" required="">
  </div>
</div>
<div class="row mt-2">
  <div class="col-lg-4">
    <h5 class="title pt-1">
      <?php echo e(__('Month')); ?> *
    </h5>
  </div>
  <div class="col-lg-8">
    <input type="text" class="option form-control
            border w-50" name="month" placeholder="<?php echo e(__('Month')); ?>" required="">
  </div>
</div>
<div class="row mt-2">
  <div class="col-lg-4">
    <h5 class="title pt-1">
      <?php echo e(__('Year')); ?> *
    </h5>
  </div>
  <div class="col-lg-8">
    <input type="text" class="option form-control
            border w-50" name="year" placeholder="<?php echo e(__('Year')); ?>" required="">
  </div>
</div>
@endifz



<div class="row mt-3">
    <div class="col-lg-4">
    </div>
    <div class="col-lg-8">
        <p><?php echo e(__('You will be redirected to complete payment via HitPay.')); ?></p>
    </div>
</div>
<?php endif; ?>
<?php if($gateway->keyword == 'mercadopago'): ?>
<?php
$paydata = $gateway->convertAutoData();
?>
<div class="my-5"></div>
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
  <select id="docType" class="option form-control border" name="docType" data-checkout="docType" type="text"></select>
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
            //heem.options.length = 0;
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
            console.log(issuers)
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
                var $form = document.getElementById('mercadopago');
                var token = await mp.fields.createCardToken({
                    cardholderName: document.getElementById('cardholderName').value,
                    identificationType: document.getElementById('docType').value,
                    identificationNumber: document.getElementById('docNumber').value,
                })
                setCardTokenAndPay(token.id)
            }
        };
        function setCardTokenAndPay(token) {
            var form = document.getElementById('mercadopago');
            var card = document.createElement('input');
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
<div class="row mt-3">
  <div class="col-lg-4">
  </div>
  <div class="col-lg-8">
    <?php echo clean($gateway->details , array('Attr.EnableID' => true)); ?>

  </div>
</div>
<div class="row mt-3">
  <div class="col-lg-4">
    <h5 class="title pt-1">
      <?php echo e(__('Transaction ID#')); ?> *
    </h5>
  </div>
  <div class="col-lg-8">
    <input type="text" class="option" name="txnid" required="" placeholder="<?php echo e(__('Transaction ID#')); ?>" required="">
  </div>
</div>
<?php endif; ?>
<?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/load/payment-user.blade.php ENDPATH**/ ?>