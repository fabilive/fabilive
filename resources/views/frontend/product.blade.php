@extends('layouts.front')

@section('content')
@include('partials.global.common-header')

<style>
        /*#chat-icon {*/
        /*    position: fixed;*/
        /*    bottom: 95px;*/
        /*    right: 25px;*/
        /*    width: 50px;*/
        /*    height: 50px;*/
        /*    background: #007bff;*/
        /*    border-radius: 50%;*/
        /*    cursor: pointer;*/
        /*    display: flex;*/
        /*    align-items: center;*/
        /*    justify-content: center;*/
        /*    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);*/
        /*    z-index : 3;*/
        /*}*/
        
        /*#chat-icon img {*/
        /*    width: 30px;*/
        /*    height: 30px;*/
        /*}*/
        
        /*#closeChat {*/
        /*    background: none;*/
        /*    border: none;*/
        /*    color: #fff;*/
        /*    font-size: 26px;*/
        /*    cursor: pointer;*/
        /*    position: absolute;*/
        /*    right: 13px;*/
        /*    top: 6px;*/
        /*    outline: none;*/
        /*}*/
        
        /*#chat-popup {*/
        /*    width: 100%;*/
        /*    max-width: 400px;*/
        /*    height: 500px;*/
        /*    position: fixed;*/
        /*    bottom: 20px;*/
        /*    right: 20px;*/
        /*    background: #fff;*/
        /*    border-radius: 10px;*/
        /*    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);*/
        /*    overflow: hidden;*/
        /*    display: flex;*/
        /*    flex-direction: column;*/
        /*    border: 1px solid #ddd;*/
        /*    font-family: Arial, sans-serif;*/
        /*    z-index : 200;*/
        /*}*/

        /* Chat Header */
        /*#chat-header {*/
        /*    background: #007bff;*/
        /*    color: white;*/
        /*    padding: 15px;*/
        /*    text-align: center;*/
        /*    font-size: 16px;*/
        /*    font-weight: bold;*/
        /*}*/

        /* Chat Body */
        /*#chat-body {*/
        /*    flex: 1;*/
        /*    overflow-y: auto;*/
        /*    padding: 10px;*/
        /*    background: #f9f9f9;*/
        /*    display: flex;*/
        /*    flex-direction: column;*/
        /*}*/
        /*.chat-icons{*/
        /*    color: #fff;*/
        /*    font-size:35px;*/
        /*}*/

        /* Chat Messages */
        /*.message {*/
        /*    padding: 10px 14px;*/
        /*    margin: 5px 0;*/
        /*    border-radius: 20px;*/
        /*    max-width: 75%;*/
        /*    word-wrap: break-word;*/
        /*    font-size: 14px;*/
        /*    display: inline-block;*/
        /*}*/

        /*.sent {*/
        /*    background: #007bff;*/
        /*    color: white;*/
        /*    align-self: flex-end;*/
        /*    text-align: right;*/
        /*}*/

        /*.received {*/
        /*    background: #e9ecef;*/
        /*    color: black;*/
        /*    align-self: flex-start;*/
        /*    text-align: left;*/
        /*}*/

        /* Chat Footer */
        /*#chat-footer {*/
        /*    display: flex;*/
        /*    padding: 10px;*/
        /*    background: #fff;*/
        /*    border-top: 1px solid #ddd;*/
        /*}*/

        /*#message {*/
        /*    flex: 1;*/
        /*    padding: 10px;*/
        /*    border: 1px solid #ccc;*/
        /*    border-radius: 20px;*/
        /*    outline: none;*/
        /*    font-size: 14px;*/
        /*}*/

        /*#sendMessage {*/
        /*    padding: 10px 15px;*/
        /*    background: #007bff;*/
        /*    color: white;*/
        /*    border: none;*/
        /*    border-radius: 20px;*/
        /*    cursor: pointer;*/
        /*    margin-left: 10px;*/
        /*    font-size: 14px;*/
        /*    outline: none;*/
        /*}*/

        /*#sendMessage:hover {*/
        /*    background: #0056b3;*/
        /*}*/

        /* Responsive Design */
        /*@media (max-width: 500px) {*/
        /*    #chat-popup {*/
        /*        width: 90%;*/
        /*        right: 5%;*/
        /*        bottom: 10px;*/
        /*    }*/

        /*    #message {*/
        /*        font-size: 12px;*/
        /*        padding: 8px;*/
        /*    }*/

        /*    #sendMessage {*/
        /*        font-size: 12px;*/
        /*        padding: 8px;*/
        /*    }*/
        /*}*/
    </style>
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5"
   style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Product Details') }}</h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Product Details') }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
@include('partials.product-details.top')
<!--==================== Product Description Section Start ====================-->
<div class="full-row">
   <div class="container">
      <div class="row justify-content-between">
         <div class="col-lg-8">
            <div class="section-head border-bottom">
               <div class="woocommerce-tabs wc-tabs-wrapper ps-0">
                  <ul class="nav nav-pills wc-tabs" id="pills-tab-one" role="tablist">
                     <li class="nav-item" role="presentation">
                        <a class="nav-link active" id="pills-description-one-tab" data-bs-toggle="pill"
                           href="#pills-description-one" role="tab" aria-controls="pills-description-one"
                           aria-selected="true">{{ __('Description') }}</a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-information-one-tab" data-bs-toggle="pill"
                           href="#pills-information-one" role="tab" aria-controls="pills-information-one"
                           aria-selected="true">{{ __('Buy / Return Policy') }}</a>
                     </li>
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-reviews-one-tab" data-bs-toggle="pill" href="#pills-reviews-one"
                           role="tab" aria-controls="pills-reviews-one" aria-selected="true">{{ __('Reviews') }}</a>
                     </li>
                     @if($gs->is_comment == 1)
                     <li class="nav-item" role="presentation">
                        <a class="nav-link" id="pills-comment-one-tab" data-bs-toggle="pill" href="#pills-comment-one"
                           role="tab" aria-controls="pills-comment-one" aria-selected="true">{{ __('Comments') }}</a>
                     </li>
                     @endif
                  </ul>
               </div>
            </div>
            <div class="woocommerce-tabs wc-tabs-wrapper ps-0 mt-0">
               <div class="tab-content" id="pills-tabContent-one">
                  <div
                     class="tab-pane fade show active woocommerce-Tabs-panel woocommerce-Tabs-panel--description mb-5 mt-4"
                     id="pills-description-one" role="tabpanel" aria-labelledby="pills-description-one-tab">
                     {!! clean($productt->details , array('Attr.EnableID' => true)) !!}
                  </div>
                  <div class="tab-pane fade mb-5" id="pills-information-one" role="tabpanel"
                     aria-labelledby="pills-information-one-tab">
                     <div class="row">
                        <div class="col-8">
                           {!! clean($productt->policy , array('Attr.EnableID' => true)) !!}
                        </div>
                     </div>
                  </div>
                  {{-- Comment Section --}}
                  @if($gs->is_comment == 1)
                  <div class="tab-pane fade" id="pills-comment-one" role="tabpanel"
                     aria-labelledby="pills-comment-one-tab">
                     @include('partials.product-details.comment-replies')
                  </div>
                  @endif
                  <div class="tab-pane fade" id="pills-reviews-one" role="tabpanel"
                     aria-labelledby="pills-reviews-one-tab">
                     @include('partials.product-details.reviews')
                  </div>
               </div>
            </div>
         </div>
      </div>

      @if($productt->user_id != 0 && $vendor_products->count() > 0)
      <div class="col-lg-3">

         <div class="section-head border-bottom d-flex justify-content-between align-items-center">
            <div class="d-flex section-head-side-title">
               <h5 class="font-700 text-dark mb-0">{{ __("Seller's Product") }}</h5>
            </div>
         </div>

         <div
            class="product-style-2 owl-carousel owl-nav-hover-primary nav-top-right single-carousel dot-disable product-list e-bg-white">

            @foreach($vendor_products as $chunk)

            <div class="item">
               <div class="row row-cols-1">
                  @foreach($chunk as $prod)

                  <div class="col mb-1">
                     <div class="product type-product">
                        <div class="product-wrapper">
                           <div class="product-image">
                              <a href="{{ route('front.product', $prod['slug'] ?: 'missing-slug') }}"
                                 class="woocommerce-LoopProduct-link"><img
                                    src="{{ $prod->thumbnail }}"
                                    alt="Product Image"></a>
                              <div class="wishlist-view">
                                 <div class="quickview-button">
                                    <a class="quickview-btn" href="{{ route('front.product', $prod['slug'] ?: 'missing-slug') }}"
                                       data-bs-toggle="tooltip" data-bs-placement="top" title=""
                                       data-bs-original-title="Quick View" aria-label="Quick View">{{ __('Quick View')
                                       }}</a>
                                 </div>
                                 <div class="whishlist-button">
                                    <a class="add_to_wishlist" href="#" data-bs-toggle="tooltip" data-bs-placement="top"
                                       title="" data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist">{{
                                       __('Wishlist') }}</a>
                                 </div>
                              </div>
                           </div>
                           <div class="product-info">
                              <h3 class="product-title"><a
                                    href="{{ route('front.product', $prod['slug'] ?: 'missing-slug') }}">{{$prod->name }}</a></h3>
                              <div class="product-price">
                                 <div class="price">
                                    <ins>{{ $prod->showPrice() }}</ins>
                                    @if(!empty($prod->showPreviousPrice()))
                                        <del>{{ $prod->showPreviousPrice() }}</del>
                                    @endif
                                 </div>
                                 @if($prod->offPercentage() > 0)
                                    <div class="on-sale"><span>{{ round($prod->offPercentage())}}</span><span>% off</span>
                                    </div>
                                 @endif
                              </div>
                              <div class="shipping-feed-back">
                                 <div class="star-rating">
                                    <div class="rating-wrap">
                                       <p><i class="fas fa-star"></i><span> {{
                                             number_format($prod->ratings_avg_rating,1) }}</span></p>
                                    </div>
                                    <div class="rating-counts-wrap">
                                       <p>({{ $prod->ratings_count }})</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
            @endforeach
         </div>
      </div>
      @endif
   </div>
</div>
</div>
</div>
<!--==================== Product Description Section End ====================-->
<!--==================== Related Products Section Start ====================-->
<div class="full-row pt-0">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <div class="section-head border-bottom d-flex justify-content-between align-items-end mb-2">
               <div class="d-flex section-head-side-title">
                  <h4 class="font-600 text-dark mb-0">{{ __('Related Products') }}</h4>
               </div>
            </div>
         </div>
         <div class="col-12">
            <div class="products product-style-1 owl-mx-5">
               <div
                  class="five-carousel owl-carousel nav-top-right e-title-hover-primary e-image-bg-light e-hover-image-zoom e-info-center">
                  @foreach (App\Models\Product::where('type',$productt->type)->where('product_type',$productt->product_type)->withCount('ratings')
                  ->withAvg('ratings','rating')->take(12)->get() as $item)
                  <div class="item">
                     <div class="product type-product">
                        <div class="product-wrapper">
                           <div class="product-image">
                              <a href="{{ route('front.product', $item->slug ?: 'missing-slug') }}"
                                 class="woocommerce-LoopProduct-link"><img
                                    src="{{ $item->thumbnail ? (file_exists(public_path('assets/images/thumbnails/'.$item->thumbnail)) ? asset('assets/images/thumbnails/'.$item->thumbnail) : asset('assets/images/products/'.$item->thumbnail)) : asset('assets/images/noimage.png') }}"
                                    alt="Product Image"></a>
                              <div class="on-sale">-{{ round($item->offPercentage(), 2)}}%</div>
                              <div class="hover-area">
                                 @if($item->product_type == "affiliate")
                                 <div class="cart-button">
                                    <a href="javascript:;" data-href="{{ $item->affiliate_link }}"
                                       class="button add_to_cart_button affilate-btn" data-bs-toggle="tooltip"
                                       data-bs-placement="right" title=""
                                       data-bs-original-title="{{ __('Add To Cart') }}"
                                       aria-label="{{ __('Add To Cart') }}"></a>
                                 </div>
                                 @else
                                 @if($item->emptyStock())
                                 <div class="closed">
                                    <a class="cart-out-of-stock button add_to_cart_button" href="#"
                                       title="{{ __('Out Of Stock') }}"><i
                                          class="flaticon-cancel flat-mini mx-auto"></i></a>
                                 </div>
                                 @else
                                 <div class="cart-button">
                                    <a href="javascript:;" data-href="{{ route('product.cart.add',$item->id) }}"
                                       class="add-cart button add_to_cart_button" data-bs-toggle="tooltip"
                                       data-bs-placement="right" title=""
                                       data-bs-original-title="{{ __('Add To Cart') }}"
                                       aria-label="{{ __('Add To Cart') }}"></a>
                                 </div>
                                
                                 @endif
                                 @endif
                                 @if(\Auth::check())
                                 <div class="wishlist-button">
                                    <a class="add_to_wishlist  new button add_to_cart_button" id="add-to-wish"
                                       href="javascript:;" data-href="{{ route('user-wishlist-add',$item->id) }}"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title=""
                                       data-bs-original-title="Add to Wishlist" aria-label="Add to Wishlist">{{
                                       __('Wishlist') }}</a>
                                 </div>
                                 @else
                                 <div class="wishlist-button">
                                    <a class="add_to_wishlist button add_to_cart_button"
                                       href="{{ route('user.login') }}" data-bs-toggle="tooltip"
                                       data-bs-placement="right" title="" data-bs-original-title="Add to Wishlist"
                                       aria-label="Add to Wishlist">{{ __('Wishlist') }}</a>
                                 </div>
                                 @endif
                                 <div class="compare-button">
                                    <a class="compare button add_to_cart_button"
                                       data-href="{{ route('product.compare.add',$item->id) }}" href="javascrit:;"
                                       data-bs-toggle="tooltip" data-bs-placement="right" title=""
                                       data-bs-original-title="Compare" aria-label="Compare">{{ __('Compare') }}</a>
                                 </div>
                              </div>
                           </div>
                           <div class="product-info">
                              <h3 class="product-title"><a href="{{ route('front.product', $item->slug ?: 'missing-slug') }}">{{
                                    $item->showName()}}</a></h3>
                              <div class="product-price">
                                 <div class="price">
                                    <ins>{{ $item->showPrice()}}</ins>
                                    @if(!empty($item->showPreviousPrice()))
                                        <del>{{ $item->showPreviousPrice() }}</del>
                                    @endif
                                 </div>
                                 @if($item->offPercentage() > 0)
                                    <div class="on-sale"><span>{{ round($item->offPercentage())}}</span><span>% off</span>
                                    </div>
                                 @endif
                              </div>
                              <div class="shipping-feed-back">
                                 <div class="star-rating">
                                    <div class="rating-wrap">
                                       <p><i class="fas fa-star"></i><span> {{
                                             number_format($item->ratings_avg_rating,1) }}</span></p>
                                    </div>
                                    <div class="rating-counts-wrap">
                                       <p>({{ $item->ratings_count }})</p>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  @endforeach
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Related Products Section End ====================-->
@includeIf('partials.global.common-footer')


@if($gs->is_report)

@if(\Auth::check())

{{-- REPORT MODAL SECTION --}}

<div class="modal fade report" id="report-modal" tabindex="-1" role="dialog" aria-labelledby="report-modal-Title"
   aria-hidden="true">
   <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">

            <div class="gocover"
               style="background: url({{ asset('assets/images/'.$gs->loader) }}) no-repeat scroll center center rgba(45, 45, 45, 0.5);">
            </div>

            <div class="login-area">
               <div class="header-area forgot-passwor-area">
                  <h4 class="title text-center">{{ __(('REPORT PRODUCT'))}}</h4>
                  <p class="text">{{ __('Please give the following details')}}</p>
               </div>
               <div class="login-form">

                  <form id="reportform" action="{{ route('product.report') }}" method="POST">

                     @include('includes.admin.form-login')

                     {{ csrf_field() }}
                     <input type="hidden" name="user_id" value="{{ \Auth::user()->id }}">
                     <input type="hidden" name="product_id" value="{{ $productt->id }}">
                     <div class="form-input">
                        <input type="text" name="title" class="User Name form-control border"
                           placeholder="{{ __('Enter Report Title') }}" required="">

                     </div>
                     <br>

                     <div class="form-input">
                        <textarea name="note" class="User Name form-control border"
                           placeholder="{{ __('Enter Report Note') }}" required=""></textarea>
                     </div>

                     <button type="submit" class="submit-btn">{{ __('SUBMIT') }}</button>
                  </form>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

{{-- REPORT MODAL SECTION ENDS --}}

@endif

@endif
@endsection

@section('script')

<script src="{{ asset('assets/front/js/jquery.elevatezoom.js') }}"></script>

<!-- Initializing the slider -->


<script type="text/javascript">
   (function($) {
		"use strict";

         //initiate the plugin and pass the id of the div containing gallery images
      $("#single-image-zoom").elevateZoom({
         gallery: 'gallery_09',
         zoomType: "inner",
         cursor: "crosshair",
         galleryActiveClass: 'active',
         imageCrossfade: true,
         loadingIcon: 'http://www.elevateweb.co.uk/spinner.gif'
      });
      //pass the images to Fancybox
      $("#single-image-zoom").bind("click", function(e) {
         var ez = $('#single-image-zoom').data('elevateZoom');
         $.fancybox(ez.getGalleryList());
         return false;
      });

      $(document).on("submit", "#emailreply" , function(){
      var token = $(this).find('input[name=_token]').val();
      var subject = $(this).find('input[name=subject]').val();
      var message =  $(this).find('textarea[name=message]').val();
      var email = $(this).find('input[name=email]').val();
      var name = $(this).find('input[name=name]').val();
      var user_id = $(this).find('input[name=user_id]').val();
      $('#eml').prop('disabled', true);
      $('#subj').prop('disabled', true);
      $('#msg').prop('disabled', true);
      $('#emlsub').prop('disabled', true);
      $.ajax({
            type: 'post',
            url: "{{ \URL::to('/user/user/contact') }}",
            data: {
                '_token': token,
                'subject'   : subject,
                'message'  : message,
                'email'   : email,
                'name'  : name,
                'user_id'   : user_id
                  },
            success: function( data) {
          $('#eml').prop('disabled', false);
          $('#subj').prop('disabled', false);
          $('#msg').prop('disabled', false);
          $('#subj').val('');
          $('#msg').val('');
          $('#emlsub').prop('disabled', false);
        if(data == 0)
          toastr.error("Email Not Found");
        else
          toastr.success("Message Sent");
          $('#vendorform').modal('hide');
            }
        });
          return false;
        });

})(jQuery);

$('.add-to-affilate').on('click',function(){
  
  var value = $(this).data('href');
  var tempInput = document.createElement("input");
  tempInput.style = "position: absolute; left: -1000px; top: -1000px";
  tempInput.value = value;
  document.body.appendChild(tempInput);
  tempInput.select();
  document.execCommand("copy");
  document.body.removeChild(tempInput);
  toastr.success('Affiliate Link Copied');

  });

</script>

@endsection
