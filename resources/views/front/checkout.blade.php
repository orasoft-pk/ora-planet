@extends('layouts.front')
@section('content')
<!-- Starting of checkOut area -->
<div class="section-padding product-checkOut-wrap">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2 class="signIn-title">{{$lang->odetails}}</h2>
                <hr />
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{$lang->cproduct}}</th>
                                <th>{{$lang->cupice}}</th>
                                <th>{{$lang->cquantity}}</th>
                                <!-- <th>Measure</th> -->
                                <th>Shipping Charges</th>
                                <th>{{$lang->cst}}</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td><a href="{{ route('front.product',[$product['item']['id'],$product['item']['name']]) }}" target="_blank">{{ $product['item']['name'] }}</a></td>
                                <td>{{ round($product['item']['cprice'] * $curr->value , 2) }}</td>
                                <td>{{ $product['qty'] }}</td>
                                <!-- <td>{{ $product['item']['measure'] }}</td> -->
                                <td class="shipping_charges" id="shipping_charges_{{$product['item']['id']}}" data="{{ json_encode($product) }}">
                                    @if($product['shipping_charges']??0)
                                        {{$product['shipping_charges']}}
                                    @else
                                        <small><i>(auto calculate on address)</i></small>
                                    @endif
                                </td>
                                <td>
                                    @if($gs->sign == 0)
                                        {{$curr->sign}}. {{ round((($product['item']['cprice']*$product['qty']) * $curr->value), 2) }}
                                    @else
                                        {{ round((($product['item']['cprice']*$product['qty']) * $curr->value), 2) }} {{$curr->sign}}
                                    @endif
                                </td>
                                <td id="row_total_{{$product['item']['id']}}">
                                    @if($gs->sign == 0)
                                        {{$curr->sign}}. {{ round(($product['price']+($product['shipping_charges']??0)) * $curr->value , 2) }}
                                    @else
                                        {{ round(($product['price']+($product['shipping_charges']??0)) * $curr->value , 2) }} {{$curr->sign}}
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr id="shipshow" {!!$shipping_cost==0 ? 'style="display:none;"' : '' !!}>
                                <th colspan="5"><h4>{{$lang->ship}}:</h4></th>
                                <th>
                                    @if($gs->sign == 0)
                                    {{$curr->sign}} <span id="ship-cost">{{round($shipping_cost * $curr->value,2)}}</span>
                                    @else
                                    <span id="ship-cost">{{round($shipping_cost * $curr->value,2)}}</span> {{$curr->sign}}
                                    @endif
                                </th>
                            </tr>
                            @if($gs->tax != 0)
                            <tr id="taxshow">
                                <th colspan="5">{{$lang->tax}}:</th>
                                <th>
                                    {{$gs->tax}}%
                                </th>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="5"><h3>{{$lang->vt}}:</h3></td>
                                <td class="coupon-td">
                                    <h3>
                                        @if($gs->sign == 0)
                                            {{$curr->sign}} <span id="total-cost">{{round($totalPrice * $curr->value ,2)}}</span>
                                        @else
                                            <span id="total-cost">{{round($totalPrice * $curr->value,2)}}</span> {{$curr->sign}}
                                        @endif
                                    </h3>
                                    <div class="coupon-click" id="coupon-click1">
                                        <p>{{$lang->cpn}} <span>*</span></p>
                                    </div>
                                </td>
                            </tr>


                            <tr id="discount" style="display: none;">
                                <th colspan="3">{{$lang->ds}}(<span id="sign"></span>):</th>
                                <th>
                                    @if($gs->sign == 0)
                                        {{$curr->sign}} <span id="ds"></span>
                                    @else
                                    <span id="ds"></span> {{$curr->sign}}
                                    @endif
                                </th>
                            </tr>

                            <tr id="ftotal" style="display: none;">
                                <td colspan="5"><h3>{{$lang->ft}}:</h3></td>
                                <td class="coupon-td">
                                    <h3>
                                        @if($gs->sign == 0)
                                        {{$curr->sign}}<span id="ft"></span>
                                        @else
                                        <span id="ft"></span>{{$curr->sign}}
                                        @endif
                                    </h3>
                                    <div class="coupon-click" id="coupon-click2" style="display: none;">
                                        <p>{{$lang->cpn}} <span>*</span></p>
                                    </div>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <div class="col-lg-4 col-lg-offset-8 col-md-4 col-md-offset-8 col-sm-5 col-sm-offset-7">
                <div class="coupon-code text-right">

                    <form id="coupon">
                        <div class="form-group coupon-group">
                            <input class="form-control" type="text" id="code" placeholder="{{$lang->ecpn}}" required="" autocomplete="off">
                            <input type="hidden" id="">
                            <button class="btn btn-md order-btn" type="submit">{{$lang->acpn}}</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                <div class="billing-details-area">
                    <h2 class="signIn-title">{{$lang->bdetails}}</h2>
                    <hr />

                    <form action="{{route('cash.submit')}}" method="post" id="payment_form">
                        {{csrf_field()}}
                        <!-- <div class="form-group" {!!$digital==1 ? 'style="display:none;"' : '' !!} style="display:none">
                            <select class="form-control" onchange="sHipping(this)" id="shipop" name="shipping" required="">
                                <option value="shipto" selected="">{{$lang->ships}}</option>
                                {{-- <option value="pickup">{{$lang->pickup}}</option> --}}
                            </select>
                        </div> -->

                        <!-- <div id="pick" style="display:none;">
                            <div class="form-group">
                                <select class="form-control" name="pickup_location">
                                    <option value="">{{$lang->pickups}}</option>
                                    @foreach($pickups as $pickup)
                                    <option value="{{$pickup->location}}">{{$pickup->location}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div> -->
                        
                        <div>
                            <div class="form-group">
                                <label for="shipping_service">Shipping Service<span>*</span></label>
                                <select class="form-control" name="shipping_service" id="shipping_service">
                                    @php
                                    $shipping_services = App\Models\ShippingServices::all()->where('status','=','1');
                                    @endphp
                                    @foreach($shipping_services as $ss)
                                    <option value="{{json_encode(['title'=>$ss->title, 'id'=>$ss->id])}}" {{$cart->shippingService?($cart->shippingService->id==$ss->id?'selected':''):''}}>{{$ss->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        @if(Auth::guard('customer')->check())
                        <div class="form-group">
                            <label for="shippingFull_name">{{$lang->fname}} <span>*</span></label>
                            <input type="text" class="form-control" name="name" value="{{Auth::guard('customer')->user()->name}}" placeholder="{{$lang->fname}}" required="">
                        </div>
                        <div class="form-group">
                            <label for="shippingFull_name">{{$lang->doph}} <span>*</span></label>
                            <input type="text" class="form-control yourphone" name="phone" value="{{Auth::guard('customer')->user()->phone}}" placeholder="{{$lang->doph}} " required="">
                        </div>
                        <div class="form-group">
                            <label for="shippingFull_name">{{$lang->doeml}} <span>*</span></label>
                            <input type="email" class="form-control" name="email" value="{{Auth::guard('customer')->user()->email}}" placeholder="{{$lang->doeml}} " required="">
                        </div>

                        <div class="form-group">
                            <label for="shipping_addresss">{{$lang->doad}} <span>*</span></label>
                            <textarea placeholder="{{$lang->doad}} " class="form-control" name="address" id="shipping_addresss" cols="30" rows="2" style="resize: vertical;">{{Auth::guard('customer')->user()->address}}</textarea>
                        </div>
                        <div class="form-group">
                            <label for="shippingFull_name">Province <span>*</span></label>
                            <select class="form-control" id="prov" required="" name="province" required>
                                <option select>Select Province</option>
                                @php
                                $countries = App\Models\Country::groupBy('admin_name')->get();
                                @endphp

                                @foreach($countries as $country)
                                <option>{{$country->admin_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="shippingFull_name">{{$lang->doct}} <span>*</span></label>
                            <select class="form-control" id="city" name="city" disabled="" required>
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="shippingFull_name">{{$lang->suph}} <span>*</span></label>
                            <input type="text" class="form-control" id="numberField" name="zip" value="{{Auth::guard('customer')->user()->zip}}" placeholder="Postal Code" required>
                        </div>

                        <input type="hidden" name="user_id" value="{{Auth::guard('customer')->user()->id}}">
                        @endif

                        <input type="hidden" id="shipping-cost" name="shipping_cost" value="{{round($shipping_cost * $curr->value,2)}}">
                        <input type="hidden" name="dp" value="{{$digital}}">
                        <input type="hidden" name="tax" value="{{$gs->tax}}">
                        <input type="hidden" name="totalQty" value="{{$totalQty}}">
                        <input type="hidden" name="total" id="grandtotal" value="{{round($totalPrice * $curr->value,2)}}">
                        <input type="hidden" name="coupon_code" id="coupon_code" value="">
                        <input type="hidden" name="coupon_discount" id="coupon_discount" value="">
                        <input type="hidden" name="coupon_id" id="coupon_id" value="">

                        </div>
                    </div>

                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="margin-top:39px;">
                        <div class="form-group">
                                <label for="order_notes">{{$lang->onotes}} <span>*</span></label>
                                <textarea class="form-control order-notes" name="order_notes" id="order_notes" cols="30" rows="5" style="resize: vertical;"></textarea>
                            </div>
                            <div class="row text-center">
                                @if(Auth::guard('customer')->check())
                                <div class="form-group">
                                    <input class="btn btn-md order-btn" id="order_now" type="submit" value="{{$lang->onow}}" disabled>
                                </div>
                                @else
                                <div class="form-group">
                                    <input data-toggle="modal" data-target="#loginModal" class="btn btn-md order-btn" value="{{$lang->onow}}">
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!-- Ending of product shipping form -->

@endsection

@section('scripts')
<script src="{{ asset('assets/src/jquery.mask.js')}}"></script>
<script src="{{ asset('assets/dist/jquery.mask.min.js')}}"></script>

<script src="https://unpkg.com/jquery-input-mask-phone-number@1.0.14/dist/jquery-input-mask-phone-number.js"></script>

//phone format
<script type="text/javascript">
    $(document).ready(function() {
        $('.yourphone').mask('0000-0000000');
    });
</script>
//----------
//digit allow only
<script type="text/javascript">
    var userName = document.querySelector('#numberField');
    userName.addEventListener('input', restrictNumber);

    function restrictNumber(e) {
        var newValue = this.value.replace(new RegExp(/[^\d]/, 'ig'), "");
        this.value = newValue;
    }
</script>
//----------
<script type="text/javascript">
    $("#coupon").submit(function() {
        val = $("#code").val();
        total = $("#grandtotal").val();
        $.ajax({
            type: "GET",
            url: "{{URL::to('/json/coupon')}}",
            data: {
                code: val,
                total: total
            },
            success: function(data) {
                if (data == 0) {
                    $.notify("{{$gs->no_coupon}}", "error");
                    $("#code").val("");
                } else if (data == 2) {
                    $.notify("{{$gs->coupon_already}}", "error");
                    $("#code").val("");
                } else {
                    $("#coupon_code").val(data[1]);
                    $("#coupon_id").val(data[3]);
                    $("#coupon_discount").val(data[2]);
                    $("#discount").show("slow");
                    $("#ds").html(data[2]);
                    $("#ftotal").show("slow");
                    $("#sign").html(data[4]);
                    var x = $("#shipop").val();
                    var y = data[0];
                    $("#ft").html(y.toFixed(2));
                    $("#grandtotal").val(y);
                    $.notify("{{$gs->coupon_found}}", "success");
                    $("#code").val("");
                    $("#coupon-click1").hide();
                    $("#coupon-click2").show();

                }
            }
        });
        return false;
    });
</script>

<script type="text/javascript">
    $(document).on('click', '#coupon-click1', function() {
        $('.coupon-code form').slideToggle();
    });
    $(document).on('click', '#coupon-click2', function() {
        $('.coupon-code form').slideToggle();
    });
</script>

<script type="text/javascript">
    $('#prov').on('change', function() {
        var prov = $(this).val();
        if (prov == "") {
            $('#city').html('<option value="">Select City</option>');
            $('#city').prop('disabled', true);
        } else {
            $.ajax({
                type: "GET",
                url: "{{URL::to('json/city')}}",
                data: {
                    admin_name: prov
                },
                success: function(data) {
                    $('#city').html('<option value="">Select City</option>');

                    for (var k in data) {
                        $('#city').append('<option value="' + data[k]['city'] + '">' + data[k]['city'] + '</option>');
                    }
                    $('#city').prop('disabled', false);
                }

            });
        }
    });
</script>

<script>
    $('#city').on('change', function() {
        calculate_shipping_charges()
    })

    $('#shipping_service').on('change', function() {
        calculate_shipping_charges()
    })

    function calculate_shipping_charges() {
        let records_arr = document.getElementsByClassName("shipping_charges")
        let c = $('#city').val();
        let s = $('#shipping_service').val();
        let ss = false
        try {
            ss = JSON.parse(s).title;
        } catch (error) {}
        if (!c) return
        if (!ss) return
        var total = 0;
        var total_shipping = 0;
        for (let i = 0; i < records_arr.length; i++) {
            const el = records_arr[i];
            let d1 = JSON.parse(el.getAttribute("data"))
            let d = d1['item'];
            let uri = `/${ss.toLowerCase()}/${d.id}/${d.user_id}/${c}`
            $.ajax({
                type: "GET",
                async: false,
                url: `{{URL::to('/charges/')}}${uri}`,
                success: function(data) {
                    let cs = data.status == 1 ? (data.data.shipment_charges*d1['qty']) : data.message;
                    let line_total = (d1['qty'] * d['cprice']) + (Number(cs) ? cs : 0);
                    total = Number(total) + line_total;
                    total_shipping += typeof cs != 'string' ? cs : 0;
                    document.getElementById(`shipping_charges_${d.id}`).innerHTML = typeof cs != 'string' ? cs : `<small style='color:red;'>${cs}</small>`;

                    update_cart_shipping_charges({"id":d.id, "object":{ "shipping_charges":(typeof cs != 'string' ? cs : 0) }})
                },
                error: function(err){
                    console.log(err)
                    let line_total = (d1['qty'] * d['cprice']);
                    total = Number(total) + line_total;
                    total_shipping += 0;
                    document.getElementById(`shipping_charges_${d.id}`).innerHTML = "<small style='color:red;'>will calculate later!</small>";
                    update_cart_shipping_charges({"id":d.id, "object":{ "shipping_charges":"0.00" }})
                }
            });
        }
        document.getElementById(`total-cost`).innerHTML = total;
        $("#grandtotal").val(total)

        document.getElementById(`shipshow`).style = "";
        document.getElementById(`ship-cost`).innerHTML = total_shipping.toFixed(2);
        document.getElementById(`shipping-cost`).value = total_shipping;
        document.getElementById(`order_now`).disabled = false;
    }

    function update_cart_shipping_charges(obj){
        $.ajax({
            type: "GET",
            url:"{{URL::to('/json/cart/update/item/key')}}",
            data:obj,
            success:function(data){}
        });
    }
</script>
@endsection