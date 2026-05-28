@extends('layouts.front')
@section('content')
@includeIf('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ $deal_title }}</h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('front.index') }}">{{ __('Home') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ $deal_title }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->

<!-- Deal Banner -->
@if(!empty($deal_banner))
<div class="full-row bg-white py-4 pb-0">
   <div class="container">
      <div class="row">
         <div class="col-12">
            <img src="{{ $deal_banner }}" alt="{{ $deal_title }}" class="img-fluid w-100 rounded" style="max-height: 350px; object-fit: cover;">
         </div>
      </div>
   </div>
</div>
@endif
<!-- Deal Banner -->
<div class="full-row">
   <div class="container">
      <div class="row">
         @includeIf('partials.catalog.catalog')
         @if (count($prods) > 0)
         <div class="col-xl-9">
             <div class="mb-4 d-xl-none">
            <button class="dashboard-sidebar-btn btn bg-primary rounded">
                <i class="fas fa-bars"></i>
            </button>
        </div>
            @includeIf('frontend.category')
            <div class="showing-products pt-30 pb-50 border-2 border-bottom border-light" id="ajaxContent">
               @includeIf('partials.product.product-different-view')
            </div>
            @include('frontend.pagination.product')
         </div>
         @else
         <div class="col-lg-9">
            <div class="card" style="background-color: #000000; border: 1px solid #222222;">
               <div class="card-body">
                  <div class="page-center">
                     <h4 class="text-center text-white">{{ __('No Product Found.') }}</h4>
                  </div>
               </div>
            </div>
         </div>
         @endif
      </div>
   </div>
</div>
@includeIf('partials.global.common-footer')
@endsection
@section('script')
<script>
   let check_view = '';
   $(document).on('click','.check_view',function(){
       check_view = $(this).attr('data-shopview');
       filter();
       $('.check_view').removeClass('active');
       $(this).addClass('active');
   });

   $(".attribute-input, #sortby, #pageby").on('change', function() {
     $(".ajax-loader").show();
     filter();
   });

   function filter() {
     let filterlink = '';

     if ($("#prod_name").val() != '') {
       filterlink += '{{ url($deal_slug) }}' + '?search='+$("#prod_name").val();
     }

     $(".attribute-input").each(function() {
       if ($(this).is(':checked')) {
         if (filterlink == '') {
           filterlink += '{{ url($deal_slug) }}' + '?'+$(this).attr('name')+'='+$(this).val();
         } else {
           filterlink += '&'+encodeURI($(this).attr('name'))+'='+$(this).val();
         }
       }
     });

     if ($("#sortby").val() != '') {
       if (filterlink == '') {
         filterlink += '{{ url($deal_slug) }}' + '?'+$("#sortby").attr('name')+'='+$("#sortby").val();
       } else {
         filterlink += '&'+$("#sortby").attr('name')+'='+$("#sortby").val();
       }
     }

     if ($("#min_price").val() != '') {
       if (filterlink == '') {
         filterlink += '{{ url($deal_slug) }}' + '?'+$("#min_price").attr('name')+'='+$("#min_price").val();
       } else {
         filterlink += '&'+$("#min_price").attr('name')+'='+$("#min_price").val();
       }
     }

     if ($("#max_price").val() != '') {
       if (filterlink == '') {
         filterlink += '{{ url($deal_slug) }}' + '?'+$("#max_price").attr('name')+'='+$("#max_price").val();
       } else {
         filterlink += '&'+$("#max_price").attr('name')+'='+$("#max_price").val();
       }
     }

     if ($("#pageby").val() != '') {
       if (filterlink == '') {
         filterlink += '{{ url($deal_slug) }}' + '?'+$("#pageby").attr('name')+'='+$("#pageby").val();
       } else {
         filterlink += '&'+$("#pageby").attr('name')+'='+$("#pageby").val();
       }
     }

     if(check_view){
         filterlink+= '&view_check='+check_view;
     }

     $("#ajaxContent").load(encodeURI(filterlink), function(data) {
        addToPagination();
        $(".ajax-loader").fadeOut(1000);
     });
   }

   function addToPagination() {
     $('ul.pagination li a').each(function() {
       let url = $(this).attr('href');
       let queryString = '?' + url.split('?')[1];
       let urlParams = new URLSearchParams(queryString);
       let page = urlParams.get('page');

       let fullUrl = '{{ url($deal_slug) }}?page='+page+'&search='+'{{urlencode(request()->input('search'))}}';

       $(".attribute-input").each(function() {
         if ($(this).is(':checked')) {
           fullUrl += '&'+encodeURI($(this).attr('name'))+'='+encodeURI($(this).val());
         }
       });

       if ($("#sortby").val() != '') {
         fullUrl += '&sort='+encodeURI($("#sortby").val());
       }

       if ($("#min_price").val() != '') {
         fullUrl += '&min='+encodeURI($("#min_price").val());
       }

       if ($("#max_price").val() != '') {
         fullUrl += '&max='+encodeURI($("#max_price").val());
       }

       if ($("#pageby").val() != '') {
         fullUrl += '&pageby='+encodeURI($("#pageby").val());
       }

       $(this).attr('href', fullUrl);
     });
   }
</script>
<script type="text/javascript">
   (function($) {
   		"use strict";
      $(function () {
        $("#slider-range").slider({
        range: true,
        orientation: "horizontal",
        min: {{ $gs->min_price }},
        max: {{ $gs->max_price }},
        values: [{{ isset($_GET['min']) ? $_GET['min'] : $gs->min_price }}, {{ isset($_GET['max']) ? $_GET['max'] : $gs->max_price }}],
        step: 1,
        slide: function (event, ui) {
          if (ui.values[0] == ui.values[1]) {
            return false;
          }
          $("#min_price").val(ui.values[0]);
          $("#max_price").val(ui.values[1]);
        }
        });
        $("#min_price").val($("#slider-range").slider("values", 0));
        $("#max_price").val($("#slider-range").slider("values", 1));
      });
   })(jQuery);
</script>
@endsection
