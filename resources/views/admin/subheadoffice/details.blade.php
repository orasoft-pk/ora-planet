@extends('layouts.admin')

@section('styles')
<style type="text/css">
    .table>tbody>tr>td,
    .table>tbody>tr>th,
    .table>tfoot>tr>td,
    .table>tfoot>tr>th,
    .table>thead>tr>td,
    .table>thead>tr>th {
        border-top: none;
    }

    .add-product-box {
        box-shadow: none;
    }

    .add-product-1 {
        padding-bottom: 30px;
    }
</style>
@endsection

@section('content')
<div class="right-side">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Starting of Dashboard area -->
                <div class="section-padding add-product-1">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="add-product-box">
                                <div class="product__header" style="border-bottom: none;">
                                    <div class="row reorder-xs">
                                        <div class="col-lg-6 col-md-5 col-sm-5 col-xs-12">
                                            <div class="product-header-title">
                                                <h2>Sub Head Office Details <a href="{{ route('admin.sub_head_office_list') }}" style="padding: 5px 12px;" class="btn add-back-btn"><i class="fa fa-arrow-left"></i> Back</a></h2>
                                                <p>Dashboard <i class="fa fa-angle-right" style="margin: 0 2px;"></i> Sub Head Office <i class="fa fa-angle-right" style="margin: 0 2px;"></i> Sub Head Office Details
                                            </div>
                                        </div>
                                        @include('includes.notification')
                                    </div>
                                </div>
                                <hr>
                                @include('includes.form-error')
                                @include('includes.form-success')
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Regestration #</strong></td>
                                            <td>{{$heads->reg_number}}</td>
                                        </tr>
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Owner Name:</strong></td>
                                            <td>{{$heads->owner_name}}</td>
                                        </tr>
                                        @if($heads->father_name != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Father Name/Husband Name:</strong></td>
                                            <td>{{$heads->father_name}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->cnic != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>CINC:</strong></td>
                                            <td>{{$heads->cnic}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->frenchise_address != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Address:</strong></td>
                                            <td>{{$heads->frenchise_address}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->religion != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Religion:</strong></td>
                                            <td>{{$heads->religion}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->city != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>City:</strong></td>
                                            <td>{{$heads->city}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->address != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Owner Address:</strong></td>
                                            <td>{{$heads->address}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->home_address != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Home Address:</strong></td>
                                            <td>{{$heads->home_address}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->bank_account_1 != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Bank Account:</strong></td>
                                            <td>{{$heads->bank_account_1}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->bank_account_2 != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Bank Account:</strong></td>
                                            <td>{{$heads->bank_account_2}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->mobile_number != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Mobile Number:</strong></td>
                                            <td>{{$heads->mobile_number}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->mobile_number_1 != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Mobile Number:</strong></td>
                                            <td>{{$heads->mobile_number_1}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->submit_amount != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Submit Amount:</strong></td>
                                            <td>{{$heads->submit_amount}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->remaining_amount != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Remaining Amount:</strong></td>
                                            <td>{{$heads->remaining_amount}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->duration != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Duration:</strong></td>
                                            <td>{{$heads->duration}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->partner != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>PartnerShip:</strong></td>
                                            <td>{{$heads->partner}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->percentage != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Percentage:</strong></td>
                                            <td>{{$heads->percentage}}%</td>
                                        </tr>
                                        @endif
                                        @if($heads->monthly_percentage != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Monthly Percentage:</strong></td>
                                            <td>{{$heads->monthly_percentage}}%</td>
                                        </tr>
                                        @endif
                                        @if($heads->yearly_percentage != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Yearly Percentage:</strong></td>
                                            <td>{{$heads->yearly_percentage}}%</td>
                                        </tr>
                                        @endif
                                        @if($heads->completion_percentage != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Completion Percentage:</strong></td>
                                            <td>{{$heads->completion_percentage}}%</td>
                                        </tr>
                                        @endif
                                        @if($heads->vitnes != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Name Of Vitnes:</strong></td>
                                            <td>{{$heads->vitnes}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->father_vitnes != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Father Name/ Huband Name Of Vitnes:</strong></td>
                                            <td>{{$heads->father_vitnes}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->cnic_vitnes != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Vitnes CNIC:</strong></td>
                                            <td>{{$heads->cnic_vitnes}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->vitnes_address != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Vitnes Address:</strong></td>
                                            <td>{{$heads->vitnes_address}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->vitnes_mobile != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Vitnes Mobile#:</strong></td>
                                            <td>{{$heads->vitnes_mobile}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->vitnes_mobile_1 != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Vitnes Mobile#:</strong></td>
                                            <td>{{$heads->vitnes_mobile_1}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->frenchise_name != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Franchise Name:</strong></td>
                                            <td>{{$heads->frenchise_name}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->assign_frenchise != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>assign_frenchise:</strong></td>
                                            <td>{{$heads->assign_frenchise}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->shop_count != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>shop_count:</strong></td>
                                            <td>{{$heads->shop_count}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->frenchise_mobile_number != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Franchise Mobile Number:</strong></td>
                                            <td>{{$heads->frenchise_mobile_number}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->vendor_limit != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Expected Vendor Limit:</strong></td>
                                            <td>{{$heads->vendor_limit}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->email != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Email:</strong></td>
                                            <td>{{$heads->email}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->area != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Area Details :</strong></td>
                                            <td>{{$heads->area}}</td>
                                        </tr>
                                        @endif

                                        @if($heads->frenchise_detail != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Franchise Details :</strong></td>
                                            <td>{{$heads->frenchise_detail}}</td>
                                        </tr>
                                        @endif
                                        @if($heads->frenchise_message != "")
                                        <tr>
                                            <td width="49%" style="text-align: right;"><strong>Franchise Message :</strong></td>
                                            <td>{{$heads->frenchise_message}}</td>
                                        </tr>
                                        @endif

                                    </tbody>
                                </table>
                            </div>
                            <div class="text-center">
                                <input type="hidden" value="{{$heads->email}}"><a style="cursor: pointer;" data-toggle="modal" data-target="#emailModal1" class="btn btn-primary email1"><i class="fa fa-send"></i> Contact Sub Head Office</a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Ending of Dashboard area -->
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title text-center" id="myModalLabel">Accept Vendor</h4>
            </div>
            <div class="modal-body">
                <p class="text-center">You are about to accept this Vendor.</p>
                <p class="text-center">Do you want to proceed?</p>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-success btn-ok">Accept</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirm-delete1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Reject Vendor</h4>
            </div>
            <div class="modal-body">
                <p class="text-center">You are about to reject this Vendor.</p>
                <p class="text-center">Do you want to proceed?</p>
            </div>
            <div class="modal-footer" style="text-align: center;">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <a class="btn btn-danger btn-ok">Reject</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script type="text/javascript">
    $('#confirm-delete').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
    $('#confirm-delete1').on('show.bs.modal', function(e) {
        $(this).find('.btn-ok').attr('href', $(e.relatedTarget).data('href'));
    });
</script>

@endsection