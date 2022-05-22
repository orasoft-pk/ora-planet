@extends('layouts.user')
@section('content')
<?php
    $txn_id = uniqid();
?>
<div class="right-side">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <!-- Starting of Dashboard add-product-1 area -->
                <div class="section-padding add-product-1">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="add-product-box">
                                <div class="product__header">
                                    <div class="row reorder-xs">
                                        <div class="col-lg-8 col-md-5 col-sm-5 col-xs-12">
                                            <div class="product-header-title">
                                                <h2>{{$subs->title}} Plan <a href="{{ route('user-package') }}" style="padding: 5px 12px;" class="btn add-back-btn"><i class="fa fa-arrow-left"></i> Back</a></h2>
                                                <p>Dashboard <i class="fa fa-angle-right" style="margin: 0 2px;"></i> Subscription Plans <i class="fa fa-angle-right" style="margin: 0 2px;"></i> {{$subs->title}} Plan</p>
                                            </div>
                                        </div>
                                        @include('includes.user-notification')
                                        @include('layouts.helper')
                                    </div>
                                </div>
                                <form class="form-horizontal" id="subscribe_form" action="{{route('user-vendor-request-submit')}}" method="POST" <?php if (isset($_GET['success'])) {echo 'style="display:none;"'; } ?>>
                                    {{ csrf_field() }}
                                    @include('includes.form-error')
                                    @include('includes.form-success')
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">Plan:</label>
                                        <p class="control-label col-sm-6" style="text-align: left;">{{$subs->title}}</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">Price:</label>
                                        @if($subs->price)
                                            <p class="control-label col-sm-6" style="text-align: left;">{{$subs->currency}}. {{toFixed($subs->price)}}</p>
                                        @else
                                            <p class="control-label col-sm-6 text-success" style="text-align: left;"><strong>Free</strong></p>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">Durations:</label>
                                        <p class="control-label col-sm-6" style="text-align: left;">{{toFixed($subs->days)}} Day(s)</p>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4">Product(s) Allowed:</label>
                                        <p class="control-label col-sm-6" style="text-align: left;">{{ $subs->allowed_products == 0 ? 'Unlimited':  $subs->allowed_products}}</p>
                                    </div>

                                    @if(!empty($package))
                                    @if($package->subscription_id != $subs->id)
                                    <div class="form-group">
                                        <label class="control-label col-sm-4"></label>
                                        <small class="control-label col-sm-6" style="text-align: left;"><b>Note:</b> Your Previous Plan will be deactivated!</small>
                                    </div>
                                    @endif
                                    @endif
                                    @if($user->is_vendor == 0)
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="v1">Shop Name *</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="shop_name" id="v1" placeholder="Shop Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="v2">Owner Name *</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="owner_name" id="v2" placeholder="Owner Name" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="v3">Mobile Number *</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="shop_number" id="v3" placeholder="Mobile Number" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="v4">Address *</label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="shop_address" id="v4" placeholder="Address" required>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="v5">Registration Number *<span>(Optional)</span></label>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control" name="reg_number" id="v5" placeholder="Registration Number">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="v6">Message *<span>(Optional)</span></label>
                                        <div class="col-sm-6">
                                            <textarea class="form-control" id="v6" name="shop_message" placeholder="Message" rows="5"></textarea>
                                        </div>
                                    </div>
                                    @endif
                                    <input type="hidden" name="subs_id" value="{{ $subs->id }}">
                                    @if($subs->price != 0)
                                    <div class="form-group">
                                        <label class="control-label col-sm-4" for="option">Select Payment Method *</label>
                                        <div class="col-sm-6">
                                            <select name="method" id="option" onchange="meThods(this)" class="form-control" required="">
                                                <option value="alfa" selected>Bank Alfalah</option>
                                                <!-- <option value="Paypal">Paypal</option> -->
                                            </select>
                                        </div>
                                    </div>
                                    <!-- <div id="paypals">
                                        <input type="hidden" name="cmd" value="_xclick">
                                        <input type="hidden" name="no_note" value="1">
                                        <input type="hidden" name="lc" value="UK">
                                        <input type="hidden" name="currency_code" value="{{strtoupper($subs->currency_code)}}">
                                        <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest">
                                    </div> -->
                                    @endif
                                    <hr />
                                    <div class="add-product-footer">
                                        @if($subs->price != 0)
                                        <button id="addProduct_btn" name="addProduct_btn" type="submit" class="btn add-product_btn" style="display: none;">Continue by Paypal</button>
                                        <button id="alfa_handshake" name="alfa_handshake" type="button" class="btn add-product_btn" onclick="alfa()">Continue by Bank</button>
                                        @else
                                        <button name="addProduct_btn" type="submit" class="btn add-product_btn">Activate</button>
                                        @endif
                                    </div>
                                </form>

                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4" style="margin-top: 3px !important;">
                                    <input id="Key1" name="Key1" type="hidden" value="9hJk4MxUkRe7cudH">
                                    <input id="Key2" name="Key2" type="hidden" value="2635443025834838">
                                    <form action="https://sandbox.bankalfalah.com/HS/HS/HS" id="HandshakeForm" method="post">
                                        <input id="HS_RequestHash" name="HS_RequestHash" type="hidden" value="">
                                        <input id="HS_IsRedirectionRequest" name="HS_IsRedirectionRequest" type="hidden" value="1">
                                        <input id="HS_ChannelId" name="HS_ChannelId" type="hidden" value="1001">
                                        <input id="HS_ReturnURL" name="HS_ReturnURL" type="hidden" value="{{route('user-vendor-request', $subs->id)}}">
                                        <input id="HS_MerchantId" name="HS_MerchantId" type="hidden" value="3162">
                                        <input id="HS_StoreId" name="HS_StoreId" type="hidden" value="011925">
                                        <input id="HS_MerchantHash" name="HS_MerchantHash" type="hidden" value="OUU362MB1uqOvabSg7KsREd15e+opQs5xXBRMsmPw/EuZIlEb1IyqYaLW6J1b44w">
                                        <input id="HS_MerchantUsername" name="HS_MerchantUsername" type="hidden" value="abumub">
                                        <input id="HS_MerchantPassword" name="HS_MerchantPassword" type="hidden" value="JVthGzAvw6ZvFzk4yqF7CA==">
                                        <input id="HS_TransactionReferenceNumber" name="HS_TransactionReferenceNumber" autocomplete="off" placeholder="Order ID" type="hidden" value="{{$txn_id}}">
                                    </form>
                                </div>




                                <div class="container-fluid" <?php if (!isset($_GET['success'])) {echo 'style="display:none;"'; } ?>>
                                    <div class="row" style="border: 1px solid gray;border-radius: 8px;padding: 10px;margin: 3vh 0px;">
                                        <div class="paynew2div">
                                            <h4>Package Summery</h4>
                                            <div class="row">
                                                <div class="col-sm-8 col-xs-8 text-left">
                                                    <h2 class="paynew2divh2">Totall Amount</h2>
                                                </div>
                                                <div class="col-sm-4 col-xs-4 text-right">
                                                    <h2 class="paynew2divh2">{{$subs->currency}} {{number_format((float)$subs->price??0, 2, '.', '')}}</h2>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style="border: 1px solid gray;border-radius: 8px;padding: 10px;margin: 3vh 0px;">
                                        <input id="Key1" name="Key1" type="hidden" value="9hJk4MxUkRe7cudH">
                                        <input id="Key2" name="Key2" type="hidden" value="2635443025834838">
                                        <form action="https://sandbox.bankalfalah.com/SSO/SSO/SSO" id="PageRedirectionForm" method="post" novalidate="novalidate">
                                            <div class="row">
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top: 3px !important;">
                                                    <a class="showSingle" target="3">
                                                        <div class="paymentnewadd text-center" style="height:17vh;padding:0px 4px;">
                                                            <input type="radio" id="TransactionTypeId" name="TransactionTypeId" value="{{$txn_id}}">
                                                            <img src="{{asset('assets/front/images/paypage/alfa_wallet_01.png')}}" alt="" style="width: 14vh;">
                                                            <h6>Alfa Wallet</h6>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top: 3px !important;">
                                                    <a class="showSingle" target="3">
                                                        <div class="paymentnewadd text-center" style="height:17vh;padding:0px 4px;">
                                                            <input type="radio" id="TransactionTypeId" name="TransactionTypeId" value="2">
                                                            <img for src="{{asset('assets/front/images/paypage/bank_acc_01.png')}}" alt="" style="width: 14vh;">
                                                            <h6>Alfalah Bank Account</h6>
                                                        </div>
                                                    </a>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="margin-top: 3px !important;">
                                                    <a class="showSingle" target="3">
                                                        <div class="paymentnewadd text-center" style="height:17vh;padding:0px 4px;">
                                                            <input type="radio" id="TransactionTypeId" checked="checked" name="TransactionTypeId" value="3">
                                                            <img for src="{{asset('assets/front/images/paypage/crddeb.png')}}" alt="" style="width: 14vh;">
                                                            <h6>Credit/Debit Card</h6>
                                                        </div>
                                                    </a>
                                                </div>
                                            </div>
                                            <div style="height: 4vh;"></div>
                                            <div class="row" style="align-self: end;display: contents;">
                                                <a class="showSingle" target="4">
                                                    <div class="text-right">
                                                        <input id="AuthToken" name="AuthToken" type="hidden" value="<?php if (isset($_GET['AuthToken'])) {echo $_GET['AuthToken'];} ?>">
                                                        <input id="RequestHash" name="RequestHash" type="hidden" value="">
                                                        <input id="ChannelId" name="ChannelId" type="hidden" value="1001">
                                                        <input id="Currency" name="Currency" type="hidden" value="PKR">
                                                        <input id="IsBIN" name="IsBIN" type="hidden" value="0">
                                                        <input id="ReturnURL" name="ReturnURL" type="hidden" value="{{route('package.payment.return')}}">
                                                        <input id="MerchantId" name="MerchantId" type="hidden" value="3162">
                                                        <input id="StoreId" name="StoreId" type="hidden" value="011925">
                                                        <input id="MerchantHash" name="MerchantHash" type="hidden" value="OUU362MB1uqOvabSg7KsREd15e+opQs5xXBRMsmPw/EuZIlEb1IyqYaLW6J1b44w">
                                                        <input id="MerchantUsername" name="MerchantUsername" type="hidden" value="abumub">
                                                        <input id="MerchantPassword" name="MerchantPassword" type="hidden" value="JVthGzAvw6ZvFzk4yqF7CA==">
                                                        <input autocomplete="off" id="TransactionReferenceNumber" name="TransactionReferenceNumber" placeholder="Order ID" type="hidden" value="{{$txn_id}}">
                                                        <input autocomplete="off" id="TransactionAmount" name="TransactionAmount" placeholder="Transaction Amount" type="hidden" value="{{$subs->price??1}}">
                                                        <button type="submit" class="btn btn-custon-four btn-danger" id="run">Proceed</button>
                                                    </div>
                                                </a>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                
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

@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.2/rollups/aes.js"></script>
@if($subs->price != 0)
<script type="text/javascript">
    function meThods(val) {
        var action1 = "{{route('user.paypal.submit')}}";
        var action2 = "{{route('user.stripe.submit')}}";

        if (val.value == "Paypal") {
            $("#subscribe_form").attr("action", action1);
            $("#scard").prop("required", false);
            $("#scvv").prop("required", false);
            $("#smonth").prop("required", false);
            $("#syear").prop("required", false);
            $("#stripes").hide();

            $("#addProduct_btn").attr("style", "")
            $("#alfa_handshake").attr("style", "display:none;")
        } else if (val.value == "alfa") {
            $("#addProduct_btn").attr("style", "display:none;")
            $("#alfa_handshake").attr("style", "")
            // $("#subscribe_form").attr("action", action2);
            // $("#scard").prop("required", true);
            // $("#scvv").prop("required", true);
            // $("#smonth").prop("required", true);
            // $("#syear").prop("required", true);
            // $("#stripes").show();
        }
    }
</script>
@endif

<script type="text/javascript">
    function alfa() {
        $("#alfa_handshake").attr('disabled', 'disabled');
        submitRequest("HandshakeForm");
        if ($("#HS_IsRedirectionRequest").val() == "1") {
            document.getElementById("HandshakeForm").submit();
        } else {
            var myData = {
                HS_MerchantId: $("#HS_MerchantId").val(),
                HS_StoreId: $("#HS_StoreId").val(),
                HS_MerchantHash: $("#HS_MerchantHash").val(),
                HS_MerchantUsername: $("#HS_MerchantUsername").val(),
                HS_MerchantPassword: $("#HS_MerchantPassword").val(),
                HS_IsRedirectionRequest: $("#HS_IsRedirectionRequest").val(),
                HS_ReturnURL: $("#HS_ReturnURL").val(),
                HS_RequestHash: $("#HS_RequestHash").val(),
                HS_ChannelId: $("#HS_ChannelId").val(),
                HS_TransactionReferenceNumber: $("#HS_TransactionReferenceNumber").val(),
            }
            $.ajax({
                type: 'POST',
                url: 'https://sandbox.bankalfalah.com/HS/HS/HS',
                contentType: "application/x-www-form-urlencoded",
                data: myData,
                dataType: "json",
                beforeSend: function() {},
                success: function(r) {
                    if (r != '') {
                        if (r.success == "true") {
                            $("#AuthToken").val(r.AuthToken);
                            $("#ReturnURL").val(r.ReturnURL);
                            alert('Success: Handshake Successful');
                        } else {
                            alert('Error: Handshake Unsuccessful');
                        }
                    } else {
                        alert('Error: Handshake Unsuccessful');
                    }
                },
                error: function(error) {
                    alert('Error: An error occurred');
                },
                complete: function(data) {
                    $("#alfa_handshake").removeAttr('disabled', 'disabled');
                }
            });
        }

    };

    function submitRequest(formName) {
        var mapString = '',
            hashName = 'RequestHash';
        if (formName == "HandshakeForm") {
            hashName = 'HS_' + hashName;
        }
        $("#" + formName + " :input").each(function() {
            if ($(this).attr('id') != '') {
                mapString += $(this).attr('id') + '=' + $(this).val() + '&';
            }
        });
        $("#" + hashName).val(CryptoJS.AES.encrypt(CryptoJS.enc.Utf8.parse(mapString.substr(0, mapString.length - 1)), CryptoJS.enc.Utf8.parse($("#Key1").val()), {
            keySize: 128 / 8,
            iv: CryptoJS.enc.Utf8.parse($("#Key2").val()),
            mode: CryptoJS.mode.CBC,
            padding: CryptoJS.pad.Pkcs7
        }));
    }
</script>

@endsection