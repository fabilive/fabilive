@extends('layouts.front')
@section('css')
    <link rel="stylesheet" href="{{asset('assets/front/css/datatables.css')}}">
@endsection
@section('content')
@include('partials.global.common-header')
<!-- breadcrumb -->
<div class="full-row bg-light overlay-dark py-5" style="background-image: url({{ $gs->breadcrumb_banner ? asset('assets/images/'.$gs->breadcrumb_banner):asset('assets/images/noimage.png') }}); background-position: center center; background-size: cover;">
   <div class="container">
      <div class="row text-center text-white">
         <div class="col-12">
            <h3 class="mb-2 text-white">{{ __('Withdraw') }}
            </h3>
         </div>
         <div class="col-12">
            <nav aria-label="breadcrumb">
               <ol class="breadcrumb mb-0 d-inline-flex bg-transparent p-0">
                  <li class="breadcrumb-item"><a href="{{ route('user-dashboard') }}">{{ __('Dashboard') }}</a></li>
                  <li class="breadcrumb-item active" aria-current="page">{{ __('Withdraw ') }}</li>
               </ol>
            </nav>
         </div>
      </div>
   </div>
</div>
<!-- breadcrumb -->
<!--==================== Blog Section Start ====================-->
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
            <div class="row">
               <div class="col-lg-12">
                  <div class="widget border-0 p-40 widget_categories bg-light account-info">
                   <div class="d-flex align-items-center justify-content-between mb-3">

                    <h4 class="widget-title down-line ">{{ __('My Withdraws') }}

                    </h4>
                    <a class="mybtn1" href="{{route('user-wwt-create')}}"> <i class="fas fa-plus"></i> {{ __('Withdraw Now') }}</a>
                   </div>
                     <div class="mr-table allproduct mt-4">
                        <div class="table-responsive">
                           <table id="example" class="table" cellspacing="0" width="100%">
                              <thead>
                                 <tr>
                                    <th>{{ __('Withdraw Date') }}</th>
                                    <th>{{ __('Method') }}</th>
                                    <th>{{ __('Account') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                 </tr>
                              </thead>
                              <tbody>

                                  @foreach($withdraws as $withdraw)
								<tr>
									<td>{{date('d-M-Y',strtotime($withdraw->created_at))}}</td>
									<td>{{$withdraw->method}}</td>

									<td>
                                        @if($withdraw->method == "Bank")
                                            {{$withdraw->iban}}
                                        @elseif($withdraw->method == "Campay")
                                            {{$withdraw->campay_acc_no}}
                                        @else
                                            {{$withdraw->acc_email}}
                                        @endif
                                    </td>
									<td>{{$sign->sign}}{{ round($withdraw->amount * $sign->value , 2) }}</td>
									<td>{{ucfirst($withdraw->status)}}</td>
								</tr>
								@endforeach
                              </tbody>
                           </table>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
<!--==================== Blog Section End ====================-->
@includeIf('partials.global.common-footer')
@endsection
@section('script')
<script src = "{{ asset('assets/front/js/dataTables.min.js') }}" defer ></script>
<script src = "{{ asset('assets/front/js/user.js') }}" defer ></script>
@endsection
