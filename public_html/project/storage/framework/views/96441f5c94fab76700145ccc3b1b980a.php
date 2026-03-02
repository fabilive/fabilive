<div class="product-info text-center">
  <h4 class="item-name">
    <?php echo e($productt->name); ?>

  </h4>     
                  
        <div class="price-and-discount">
          <div class="price">
            <div class="current-price" id="sizeprice">
              <?php echo e(PriceHelper::showAdminCurrencyPrice($item['item_price'])); ?>

            </div> 
          </div>
        </div>
        
                  

              <?php if(!empty($productt->size)): ?>

              <div class="product-size">
                <p class="title"><?php echo e(__('Size :')); ?></p>
                <ul class="siz-list">
                  <?php $__currentLoopData = array_unique($productt->size); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li class="<?php echo e($item['size'] == $data1 ? 'active' : ''); ?>" data-key="<?php echo e(str_replace(' ','',$data1)); ?>">
                      <span class="box">
                        <?php echo e($data1); ?>     
                      </span>
                    </li>
                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
              </div>

              <?php endif; ?>

                 

                   

              <?php if(!empty($productt->color)): ?>

              <div class="product-color">
                <div class="title"><?php echo e(__('Color :')); ?></div>
                <ul class="color-list">

                  <?php $__currentLoopData = $productt->color; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data1): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <li class="<?php echo e(('#'.$item['color'] == $data1 && $productt->size[$key] == $productt->size[$item['size_key']]) ? 'active' : ''); ?> <?php echo e($productt->IsSizeColor($productt->size[$key]) ? str_replace(' ','',$productt->size[$key]) : ''); ?> <?php echo e($productt->size[$key] == $productt->size[$item['size_key']] ? 'show-colors' : ''); ?>">
                      <span class="box" data-color="<?php echo e($productt->color[$key]); ?>" style="background-color: <?php echo e($productt->color[$key]); ?>">

                        <input type="hidden" class="size" value="<?php echo e($productt->size[$key]); ?>">
                        <input type="hidden" class="size_qty" value="<?php echo e($productt->size_qty[$key]); ?>">
                        <input type="hidden" class="size_key" value="<?php echo e($key); ?>">
                        <input type="hidden" class="size_price" value="<?php echo e(round($productt->size_price[$key] * $curr->value,2)); ?>">                        
                      
                      </span>
                    </li>

                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </ul>
              </div>

              <?php endif; ?>

                 

                  

              <?php if(!empty($productt->size)): ?>

                <input type="hidden" class="product-stock" value="<?php echo e($productt->size_qty[$item['size_key']]); ?>">

                <?php else: ?>


                <?php if(!$productt->emptyStock()): ?>
                  <input type="hidden" class="product-stock" value="<?php echo e($productt->stock); ?>">
                <?php elseif($productt->type != 'Physical'): ?>
                  <input type="hidden" class="product-stock" value="0">
                <?php else: ?>
                  <input type="hidden" class="product-stock" value="">

                <?php endif; ?>

              <?php endif; ?>

               

                  

              <?php if(!empty($productt->attributes)): ?>
                <?php
                  $attrArr = json_decode($productt->attributes, true);
                ?>
              <?php endif; ?>
              <?php if(!empty($attrArr)): ?>

                  <div class="product-attributes mt-3 mb-3 text-left">
                    <div class="row">
                    <?php $__currentLoopData = $attrArr; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attrKey => $attrVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <?php if(array_key_exists("details_status",$attrVal) && $attrVal['details_status'] == 1): ?>

                    <div class="col-lg-6 offset-lg-4">
                      <div class="form-group mb-2">
                        <strong for="" class="text-capitalize"><?php echo e(str_replace("_", " ", $attrKey)); ?> :</strong>
                          <div class="">
                          <?php $__currentLoopData = $attrVal['values']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optionKey => $optionVal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="custom-control custom-radio">
                              <input type="hidden" class="keys" value="">
                              <input type="hidden" class="values" value="">
                              <input type="radio" id="<?php echo e($attrKey); ?><?php echo e($optionKey); ?>" name="<?php echo e($attrKey); ?>" class="custom-control-input product-attr"  data-key="<?php echo e($attrKey); ?>" data-price = "<?php echo e($attrVal['prices'][$optionKey] * $curr->value); ?>" value="<?php echo e($optionVal); ?>" <?php echo e(in_array($optionVal,(explode(",",$item['values'])))   ? 'checked' : ''); ?>>
                              <label class="custom-control-label" for="<?php echo e($attrKey); ?><?php echo e($optionKey); ?>"><?php echo e($optionVal); ?>


                              <?php if(!empty($attrVal['prices'][$optionKey])): ?>
                                +
                                <?php echo e($curr->sign); ?> <?php echo e($attrVal['prices'][$optionKey] * $curr->value); ?>

                              <?php endif; ?>
                              </label>
                            </div>
                          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                          </div>
                      </div>
                    </div>
                      <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                  </div>

              <?php endif; ?>

        

    <input type="hidden" id="product_price" value="<?php echo e(round($productt->vendorPrice() * $curr->value,2)); ?>">
    <input type="hidden" id="product_id" value="<?php echo e($productt->id); ?>">
    <input type="hidden" id="curr_pos" value="<?php echo e($gs->currency_format); ?>">
    <input type="hidden" id="curr_sign" value="<?php echo e($curr->sign); ?>">
    <input type="hidden" id="order_id" value="<?php echo e($order->id); ?>">
    <input type="hidden" id="item_id" value="<?php echo e($item_id); ?>">

    <div class="inner-box">
      <div class="cart-btn">

                <div class="multiple-item-price">
                  <div class="qty">
                    <span class="qtplus">
                      <i class="fas fa-plus"></i>
                    </span>
                    <input class="qttotal" type="text" id="order-qty" value="<?php echo e($item['qty']); ?>">
                    <span class="qtminus">
                      <i class="fas fa-minus"></i>
                    </span>
                  </div>
                </div>

            <button type="button" id="qaddcrt" class="addProductSubmit-btn1" href="javascript:;">
              <?php echo e(__('Update')); ?>

            </button>

      </div>
    </div>

</div>

<script type="text/javascript">

(function($) {
		"use strict";

  var order_id = $('#order_id').val();
  var item_id = $('#item_id').val();
  let gs  = <?php echo json_encode(\App\Models\Generalsetting::first()->makeHidden(['stripe_key', 'stripe_secret', 'smtp_pass', 'instamojo_key', 'instamojo_token', 'paystack_key', 'paystack_email', 'paypal_business', 'paytm_merchant', 'paytm_secret', 'paytm_website', 'paytm_industry', 'paytm_mode', 'molly_key', 'razorpay_key', 'razorpay_secret'])); ?>;

  function number_format (number, decimals, dec_point, thousands_sep) {
      // Strip all characters but numerical ones.
      number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
      var n = !isFinite(+number) ? 0 : +number,
          prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
          sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
          dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
          s = '',
          toFixedFix = function (n, prec) {
              var k = Math.pow(10, prec);
              return '' + Math.round(n * k) / k;
          };
      // Fix for IE parseFloat(0.55).toFixed(0) = 0;
      s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
      if (s[0].length > 3) {
          s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
      }
      if ((s[1] || '').length < prec) {
          s[1] = s[1] || '';
          s[1] += new Array(prec - s[1].length + 1).join('0');
      }
      return s.join(dec);
  }


    var sizes = "<?php echo e($item['size']); ?>";
    var size_qty = "<?php echo e($item['size_qty']); ?>";
    var size_price = "<?php echo e($item['size_price']); ?>";
    var size_key = "<?php echo e($item['size_key']); ?>";
    var colors = "#<?php echo e($item['color']); ?>";
    var mtotal = "";
    var mstock = $('.product-stock').val();
    var keys = "";
    var values = "";
    var prices = "";

    $('.product-attr').on('change',function(){

      var total = 0;
       total = mgetAmount()+mgetSizePrice();
       total = total.toFixed(2);
       
       total = number_format(total, 2, gs.decimal_separator, gs.thousand_separator);

       var pos = $('#curr_pos').val();
       var sign = $('#curr_sign').val();
       if(pos == '0')
       {
       $('#sizeprice').html(sign+total);
       }
       else {
       $('#sizeprice').html(total+sign);
       }
    });

    function mgetSizePrice()
    {

      var total = 0;
        if($('.product-color .color-list li.active').length > 0)
        {
          total = parseFloat($('.product-color .color-list li.active').find('.size_price').val());
        }
        return total;
    }

    function mgetAmount()
    {
      var total = 0;
      var value = parseFloat($('#product_price').val());
      var datas = $(".product-attr:checked").map(function() {
        return $(this).data('price');
      }).get();

      var data;
      for (data in datas) {
        total += parseFloat(datas[data]);
      }
      total += value;
      return total;
    }

    // Product Details Product Size Active Js Code
    $('.product-size .siz-list .box').on('click', function () {

        var parent = $(this).parent();
        $('.product-size .siz-list li').removeClass('active');
        parent.addClass('active');

        $('.qttotal').val('1')

        $('.product-color .color-list li').removeClass('show-colors');
        var size_color = $('.product-color .color-list li.'+parent.data('key'));
        size_color.addClass('show-colors').first().addClass('active');
        colors = size_color.find('span.box').data('color');

        size_qty = size_color.find('.size_qty').val();
        size_price = size_color.find('.size_price').val();
        size_key = size_color.find('.size_key').val();
        sizes = size_color.find('.size').val();
        let total = mgetAmount()+parseFloat(size_price);
        stock = size_qty;
        total = total.toFixed(2);
        total = number_format(total, 2, gs.decimal_separator, gs.thousand_separator);
        var pos = $('#curr_pos').val();
        var sign = $('#curr_sign').val();
        if(pos == '0')
        {
         $('#sizeprice').html(sign+total);
        }
        else {
         $('#sizeprice').html(total+sign);
        }        

    });

    // Product Details Product Color Active Js Code
    $('.product-color .color-list .box').on('click', function () {
        colors = $(this).data('color');
        var parent = $(this).parent();
        $('.product-color .color-list li').removeClass('active');
        parent.addClass('active');

        $('.qttotal').html('1');
         size_qty = $(this).find('.size_qty').val();
         size_price = $(this).find('.size_price').val();
         size_key = $(this).find('.size_key').val();
         sizes = $(this).find('.size').val();
         let total = mgetAmount()+parseFloat(size_price);
         stock = size_qty;
         total = total.toFixed(2);
         total = number_format(total, 2, gs.decimal_separator, gs.thousand_separator);
         var pos = $('#curr_pos').val();
         var sign = $('#curr_sign').val();
         if(pos == '0')
         {
         $('#sizeprice').html(sign+total);
         }
         else {
         $('#sizeprice').html(total+sign);
         }

    });


    $('.qttotal').keypress(function(e){
      if (this.value.length == 0 && e.which == 48 ){
        return false;
     }
      if(e.which != 8 && e.which != 32){
        if(isNaN(String.fromCharCode(e.which))){
          e.preventDefault();
        }
      }
    });

    $('.qtminus').on('click', function () {
        var el = $(this);
        var $tselector = el.parent().parent().find('.qttotal');
        let total = $($tselector).val();
        if (total > 1) {
            total--;
        }
        $($tselector).val(total);
    });

    $('.qtplus').on('click', function () {
        var el = $(this);
        var $tselector = el.parent().parent().find('.qttotal');
       let  total = $($tselector).val();
        if(mstock != "")
        {
            var stk = parseInt(mstock);
            if(total < stk)
            {
                total++;
                $($tselector).val(total);
            }
        }
        else {
            total++;
        }
        $($tselector).val(total);
    });




    $(document).on("click", "#qaddcrt" , function(){
      var qty = $('.qttotal').val();
      var pid = $(this).parent().parent().parent().parent().parent().find("#product_id").val();
 
      if($('.product-attr').length > 0)
      {
      values = $(".product-attr:checked").map(function() {
      return $(this).val();
      }).get();

      keys = $(".product-attr:checked").map(function() {
      return $(this).data('key');
      }).get();


      prices = $(".product-attr:checked").map(function() {
      return $(this).data('price');
      }).get();

      }

     window.location = mainurl+"/admin/order/updatecart/"+order_id+"?id="+pid+"&qty="+qty+"&size="+sizes+"&color="+colors.substring(1, colors.length)+"&size_qty="+size_qty+"&size_price="+size_price+"&size_key="+size_key+"&keys="+keys+"&values="+values+"&prices="+prices+"&item_id="+item_id;
 
     });

})(jQuery);

</script><?php /**PATH /home/u112990875/domains/fabilive.com/public_html/project/resources/views/admin/order/edit-product.blade.php ENDPATH**/ ?>