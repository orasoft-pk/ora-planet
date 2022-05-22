@extends('layouts.front')
@section('content')
@php
   $i=1;
   $j=1;
@endphp
<style type="text/css">
   .allshopspageimg {
      width: 100%;
      height: 300px;
      border-radius: 10px;
      margin-top: 10px;
   }
   .allshopspagediv1 {
      background-color: white;
      opacity: 0.9;
      margin-top: -120px;
      width: 100%;
      height: 120px;
      border-top-right-radius: 15px;
      border-top-left-radius: 15px;
      padding-top: 5px;
      color: rgba(0, 0, 0, 0.8);
   }
   .allshopspageh3 {
      font-size: 16px;
      font-weight: 600;
   }
   .allshopspagesp {
      font-size: 17px;
      font-weight: 600;
   }
   .allshopspagep {
      font-size: 14px;
   }
</style>
<!-- Starting of Section title overlay area -->
<div class="title-overlay-wrap overlay" id="title-overlay-wrap2" style="background-image: url({{asset('assets/images/'.$gs->bgimg)}});">
   <div class="container">
      <div class="row">
         <div class="col-sm-12 text-center">
            <h1 class="text-capitalize">{{$slug}}</h1>
         </div>
      </div>
   </div>
</div>
<!-- Ending of Section title overlay area -->
<!-- Starting of product category area -->
<div class="section-padding product-category-wrap" style="margin-top: 10px;">
   <div class="container">
      <div class="row">
         @include('includes.catalog')
         <div class="col-lg-9 col-md-8 col-sm-12 col-xs-12">
            <div class="category-wrap">
               <div class="row">
                  @foreach ($vendors as $vendor)
                     <div class="col-lg-4 col-md-6 col-sm-6 col-12 itemss">
                        <a href="{{route('front.vendor',str_replace(' ', '-',($vendor->shop_name)))}}">
                           @if($vendor->logo == null)
                              <img src="{{asset('assets/no_image.jpeg')}}" class="img-fluid allshopspageimg" style="border: 1px solid gray;" alt="">
                           @else
                              <img src="{{asset('assets/images/'.$vendor->logo)}}" class="img-fluid allshopspageimg" style="border: 1px solid gray;" alt="">
                           @endif
                        </a>
                        <div class="text-center allshopspagediv1 ">
                           <h4 class="allshopspageh3 ">{{$vendor->shop_name}}</h4>
                           <p class="productDetails-reviews">
                              <div class="ratings" dir="ltr">
                              <div class="empty-stars"></div>
                              <div class="full-stars" style="width:{{$vendor->shopratings($vendor->id)}}%"></div>
                              </div>
                           </p>
                           <p class="allshopspagep"><span class="allshopspagesp">{{count($vendor->products()->get())}} </span>Total Products</p>
                        </div>
                     </div>
                  @endforeach
            </div>
            @if(isset($min) || isset($max))
            <div class="row" style="margin: 8vh 0px;">
               <div class="col-md-12 text-center">
                  {!! $vendors->appends(['min' => $min, 'max' => $max])->links() !!}
               </div>
            </div>
            @else
            <div class="row" style="margin-top: 8vh;">
               <div class="col-md-12 text-center" style="font-size: 30px;"> 
                  {!! $vendors->appends(array('shop_address' => $slug))->links() !!}
               </div>
            </div>
            @endif
         </div>
      </div>
   </div>
</div>
</div>
<!-- Ending of product category area -->
@endsection
@section('scripts')
<script type="text/javascript">
   $("#ex2").slider({});
   $("#ex2").on("slide", function(slideRange) {
      var totals = slideRange.value;
      var value = totals.toString().split(',');
      $("#price-min").val(value[0]);
      $("#price-max").val(value[1]);
   });
</script>
@endsection