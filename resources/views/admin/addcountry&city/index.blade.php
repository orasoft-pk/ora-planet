@extends('layouts.admin')
@section('content')
    <div class="right-side">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <!-- Starting of Dashboard add-product-1 area -->
                    <div class="section-padding add-product-1">
                        <div class="row">
                            @if (session()->has('success'))
                                <div class="alert alert-success">
                                    @if(is_array(session('success')))
                                        <ul>
                                            @foreach (session('success') as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        {{ session('success') }}
                                    @endif
                                </div>
                            @endif
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="add-product-box">
                                    <div class="product__header">
                                        <div class="row reorder-xs">
                                            <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12">
                                                <div class="product-header-title">
                                                    <h2>
                                                        <!-- Add Country/City -->
                                                        <a href="" style="padding: 5px 12px;" class="btn add-back-btn">
                                                            <i class="fa fa-arrow-left"></i> Back
                                                        </a>
                                                    </h2>
                                                    <p>Dashboard <i class="fa fa-angle-right" style="margin: 0 2px;"></i>
                                                        Admin <i class="fa fa-angle-right" style="margin: 0 2px;"></i> Add Country<small>(province, cities)</small>
                                                </div>
                                            </div>
                                            @include('includes.notification')
                                        </div>
                                    </div>
                                    <form class="form-horizontal" action="{{route('addcity')}}" method="post">
                                        @csrf
                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="v5">Country *</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="country" id="v5" placeholder="Add Country" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="v5">Country Code <small>(e.g PK, IN etc)</small>*</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="iso2" id="v5" placeholder="Add Country Code" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="v5">Province *</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="province" id="v5" placeholder="Add Province" required>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="v5">Image For Province</br><small>(you can also country flag photo)</small>*</label>
                                            <div class="col-sm-6">
                                                <input type="file" class="form-control" name="image" id="v5" placeholder="Add Country Code">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-sm-4" for="v2">Cities List<small>(e.g lahore,faisalabad etc)</small>*</label>
                                            <div class="col-sm-6">
                                                <input type="text" class="form-control" name="cities" id="v2" placeholder="e.g karachi,lahore,faisalabad,peshawar" required>
                                            </div>
                                        </div>
                                        
                                        <div class="add-product-footer">
                                            <button name="addProduct_btn" type="submit" class="btn add-product_btn">Submit</button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Ending of Dashboard add-product-1 area -->
                </div>
            </div>
        </div>
    </div>
    <!-- Ending of Account Dashboard area -->
@endsection
