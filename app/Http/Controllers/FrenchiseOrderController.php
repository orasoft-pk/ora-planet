<?php

namespace App\Http\Controllers;

use App\Classes\GeniusMailer;
use App\Http\Controllers\Shipping\LeopardsController;
use App\Models\Order;
use App\Models\Vendororder;
use App\Models\User;
use App\Models\Generalsetting;
use App\Models\ShipItems;
use Illuminate\Http\Request;
use Auth;

class FrenchiseOrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:frenchise');
    }

    public function index()
    {
        $auth = Auth::guard('frenchise')->user()->vendors()->get()->pluck('id');
        $v_orders = Vendororder::whereIn('user_id',$auth)->get()->pluck('order_id');
        $orders = Order::whereIn('id',$v_orders)->orderBy('id','desc')->get();
        return view('frenchise.order.index',compact('orders'));
    }

    public function show($id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
        $shippment = ShipItems::all()->where('order_id','=',$order->id)->where('order_number','=',$order->order_number)->where('customer_id','=',$order->user_id)->first();
        return view('frenchise.order.details',compact('order','cart','shippment'));
    }

    public function shows($id)
    {
        $user = User::findOrFail($id);
        return view('frenchise.order.detail',compact('user'));
    }

    public function invoice($id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
        return view('frenchise.order.invoice',compact('order','cart'));
    }

    public function emailsub(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
        if($gs->is_smtp == 1)
        {
            $data = [
                'to' => $request->to,
                'subject' => $request->subject,
                'body' => $request->message,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
        }
        else
        {
            $data = 0;
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            $mail = mail($request->to,$request->subject,$request->message,$headers);
            if($mail) {
                $data = 1;
            }
        }

        return response()->json($data);
    }

    public function printpage($id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
        return view('frenchise.order.print',compact('order','cart'));
    }

    public function license(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
        $cart->items[$request->license_key]['license'] = $request->license;
        $order->cart = utf8_encode(gzcompress(serialize($cart), 9));
        $order->update();
        return redirect()->route('frenchise-order-show',$order->id)->with('success','Successfully Changed The License Key.');
    }

    // public function status($id,$status)
    // {
    //     $mainorder = Order::findOrFail($id);
    //     if ($mainorder->status == "completed"){
    //         return redirect()->back()->with('success','This Order is Already Completed');
    //     }else{
    //     if ($status == "completed"){
    //         foreach($mainorder->vendororders as $vorder)
    //         {
    //             $uprice = User::findOrFail($vorder->user_id);
    //             $uprice->current_balance = $uprice->current_balance + $vorder->price;
    //             $uprice->update();
    //         }
    //         $gs = Generalsetting::findOrFail(1);
    //         if($gs->is_smtp == 1)
    //         {
    //             $data = [
    //                 'to' => $mainorder->customer_email,
    //                 'subject' => 'Your order '.$mainorder->order_number.' is Confirmed!',
    //                 'body' => "Hello ".$mainorder->customer_name.","."\n Thank you for shopping with us. We are looking forward to your next visit.",
    //             ];

    //             $mailer = new GeniusMailer();
    //             $mailer->sendCustomMail($data);
    //         }
    //         else
    //         {
    //            $to = $mainorder->customer_email;
    //            $subject = 'Your order '.$mainorder->order_number.' is Confirmed!';
    //            $msg = "Hello ".$mainorder->customer_name.","."\n Thank you for shopping with us. We are looking forward to your next visit.";
    //         $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
    //            mail($to,$subject,$msg,$headers);
    //         }
    //     }
    //     if ($status == "declined"){
    //         $gs = Generalsetting::findOrFail(1);
    //         if($gs->is_smtp == 1)
    //         {
    //             $data = [
    //                 'to' => $mainorder->customer_email,
    //                 'subject' => 'Your order '.$mainorder->order_number.' is Declined!',
    //                 'body' => "Hello ".$mainorder->customer_name.","."\n We are sorry for the inconvenience caused. We are looking forward to your next visit.",
    //             ];
    //         $mailer = new GeniusMailer();
    //         $mailer->sendCustomMail($data);
    //         }
    //         else
    //         {
    //            $to = $mainorder->customer_email;
    //            $subject = 'Your order '.$mainorder->order_number.' is Declined!';
    //            $msg = "Hello ".$mainorder->customer_name.","."\n We are sorry for the inconvenience caused. We are looking forward to your next visit.";
    //         $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
    //            mail($to,$subject,$msg,$headers);
    //         }

    //     }
    //     $stat['status'] = $status;
    //     $stat['payment_status'] = ucfirst($status);
    //     $order = Vendororder::where('order_id','=',$id)->update(['status' => $status]);
    //     $mainorder->update($stat);
    //     return redirect()->back()->with('success','Order Status Updated Successfully!');
    //     }
    // }

    public function status($id, $status)
    {
        $mainorder = Order::findOrFail($id);
        $shipitems = ShipItems::all()->where('order_number','=',$mainorder->order_number)->where('customer_id','=',$mainorder->user_id)->where('status','=','pending');

        if ($mainorder->status == 'pending'){
            return redirect()->back()->with('unsuccess', 'Order can not be shipped until vendor pass it to franchise!');
        }

        if(count($shipitems) > 0){
            return redirect()->back()->with('unsuccess', 'Sorry we can\'t change order state! Before Changing Order status cancel shipping from Order details screen!');
        } elseif ($mainorder->status == "completed") {
            return redirect()->back()->with('success', 'This Order is Already Completed');
        } elseif ($mainorder->status == "declined") {
            return redirect()->back()->with('success', 'This Order is Already Declined');
        }else {
            if ($status == "completed") {
                foreach ($mainorder->vendororders as $vorder) {
                    $uprice = User::findOrFail($vorder->user_id);
                    $uprice->current_balance = $uprice->current_balance + $vorder->price;
                    $uprice->update();
                }
                $gs = Generalsetting::findOrFail(1);
                if ($gs->is_smtp == 1) {
                    $data = [
                        'to' => $mainorder->customer_email,
                        'subject' => 'Your order ' . $mainorder->order_number . ' is Confirmed!',
                        'body' => "Hello " . $mainorder->customer_name . "," . "\n Thank you for shopping with us. We are looking forward to your next visit.",
                    ];

                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);
                } else {
                    $to = $mainorder->customer_email;
                    $subject = 'Your order ' . $mainorder->order_number . ' is Confirmed!';
                    $msg = "Hello " . $mainorder->customer_name . "," . "\n Thank you for shopping with us. We are looking forward to your next visit.";
                    $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }
            }
            if ($status == "declined") {
                $gs = Generalsetting::findOrFail(1);
                if ($gs->is_smtp == 1) {
                    $data = [
                        'to' => $mainorder->customer_email,
                        'subject' => 'Your order ' . $mainorder->order_number . ' is Declined!',
                        'body' => "Hello " . $mainorder->customer_name . "," . "\n We are sorry for the inconvenience caused. We are looking forward to your next visit.",
                    ];
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);
                } else {
                    $to = $mainorder->customer_email;
                    $subject = 'Your order ' . $mainorder->order_number . ' is Declined!';
                    $msg = "Hello " . $mainorder->customer_name . "," . "\n We are sorry for the inconvenience caused. We are looking forward to your next visit.";
                    $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }
            }
            if ($status == "processing") {
                $gs = Generalsetting::findOrFail(1);
                if ($gs->is_smtp == 1) {
                    $data = [
                        'to' => $mainorder->customer_email,
                        'subject' => 'Your order ' . $mainorder->order_number . ' is in Processing State!',
                        'body' => "Hello " . $mainorder->customer_name . "," . "\n You order state is changed to processing.",
                    ];
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);
                } else {
                    $to = $mainorder->customer_email;
                    $subject = 'Your order ' . $mainorder->order_number . ' is in Processing State!';
                    $msg = "Hello " . $mainorder->customer_name . "," . "\n You order state is changed to processing.";
                    $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }
            }
            if ($status == "shipping") {

                if(count($shipitems) < 1){
                    $order = Vendororder::all()->where('order_id', '=', $id);
                    $pending_items = $order->where('status','=','pending');
                    $pending_items_qty_checks = $order->where('qty_check','=',0);
                    $pending_items_qlty_checks = $order->where('qlty_check','=',0);

                    if(count($pending_items) > 0){
                        return redirect()->back()->with('unsuccess', 'Sorry! you can\'t change order state to shipping! Some Items in pending state from vendor side!');
                    }
                    if(count($pending_items_qty_checks) > 0){
                        return redirect()->back()->with('unsuccess', 'Sorry! you can\'t change order state to shipping! Some Items Qty is not checked in order details screen, please checked qty and try again!');
                    }

                    if(count($pending_items_qlty_checks) > 0){
                        return redirect()->back()->with('unsuccess', 'Sorry! you can\'t change order state to shipping! Some Items Quality is not checked in order details screen, please checked quality and try again!');
                    }
                    $r = $this->book_shipping($mainorder);
                    if($r->status == 0){
                        return redirect()->back()->with('unsuccess', "Failed: $r->error");
                    }
                }

                $stat['shipping'] = '';
                $gs = Generalsetting::findOrFail(1);
                if ($gs->is_smtp == 1) {
                    $data = [
                        'to' => $mainorder->customer_email,
                        'subject' => 'Your order ' . $mainorder->order_number . ' is in Shipping State!',
                        'body' => "Hello " . $mainorder->customer_name . "," . "\n You order state is changed to shipping.",
                    ];
                    $mailer = new GeniusMailer();
                    $mailer->sendCustomMail($data);
                } else {
                    $to = $mainorder->customer_email;
                    $subject = 'Your order ' . $mainorder->order_number . ' is in Shipping State!';
                    $msg = "Hello " . $mainorder->customer_name . "," . "\n You order state is changed to shipping.";
                    $headers = "From: " . $gs->from_name . "<" . $gs->from_email . ">";
                    mail($to, $subject, $msg, $headers);
                }
            }
            $stat['status'] = $status;
            // $stat['payment_status'] = ucfirst($status);
            // $order = Vendororder::where('order_id', '=', $id)->update(['status' => $status]);
            // return $status;
            $mainorder->update($stat);
            Vendororder::where('order_number', '=', $mainorder->order_number)->update(['status' => $stat['status']]);

            return redirect()->back()->with('success', 'Order Status Updated Successfully');
        }
    }

    public function book_shipping($order)
    {
        $frenchise = Auth::guard('frenchise')->user();
        $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
        $weight = 0;
        $collect_amount = (strtolower($order->method) == 'cash on delivery' || strtolower($order->method) == 'cod')?$order->pay_amount:0;
        $vendors = [];
        foreach ($cart->items as $k => $v) {
            $weight += $v['item']['measure'];
            $vendors[] = $v['item']['user_id'];
        }

        $shipping = new LeopardsController();

        $cities_list = $shipping->get_leopards_cities();
        $origin_city = $shipping->get_city_details_from_cities($cities_list, $frenchise->city);
        $shipping_city = $shipping->get_city_details_from_cities($cities_list, $order->shipping_city);

        $shipto_phone = preg_replace("/[^0-9\s]/", "", isset($order->shipping_phone)?$order->shipping_phone:$order->customer_phone);
        $frenchise_mobile_number = preg_replace("/[^0-9\s]/", "", isset($frenchise->frenchise_mobile_number)?$frenchise->frenchise_mobile_number:0);

        $payload = (object)array(
            "weight"                    => $weight,
            'qty'                       => (int)$order->totalQty,
            'collect_amount'            => $collect_amount,
            'order_id'                  => strtolower($order->order_number),
            'origin_city'               => $origin_city->id,
            'destination_city'          => $shipping_city->id,
            'frenchise_name'            => $frenchise->frenchise_name,
            'frenchise_email'           => $frenchise->email,
            'frenchise_phone'           => $frenchise_mobile_number,
            'frenchise_address'         => $frenchise->frenchise_address,
            'shipto_name'               => isset($order->shipping_name)?$order->shipping_name:$order->customer_name,
            'shipto_email'              => isset($order->shipping_email)?$order->shipping_email:$order->customer_email,
            'shipto_phone'              => $shipto_phone,
            'shipto_address'            => isset($order->shipping_address)?$order->shipping_address:$order->customer_address,
            'note'                      => isset($order->order_note)?$order->order_note:'N/A',
            'return_address'            => $frenchise->frenchise_address,
        );
        $res = json_decode($shipping->book_packet($payload));
        if($res->status == 1){
            $shipto = new ShipItems;
            $shipto['track_id'] = $res->track_number;
            $shipto['slip_link'] = $res->slip_link;
            $shipto['order_id'] = $order->id;
            $shipto['order_number'] = $order->order_number;
            $shipto['customer_id'] = $order->user_id;
            $shipto['vendor_id'] = json_encode($vendors);
            $shipto['frenchise_id'] = $frenchise->id;
            $shipto['shipping_service_id'] = $order->shipping_service;
            $shipto['shipping_charges'] = '';
            $shipto['booked_packet_json'] = json_encode($payload);
            // $shipto['status'] = 'pending';
            $shipto->save();
        }
        return $res;
    }

    public function cancel_shipping($order_id, $track_id)
    {
        $frenchise = Auth::guard('frenchise')->user();
        $order = Order::all()->where('id','=',$order_id)->first();
        if(strtolower($order->status) == 'completed'){
            return redirect()->back()->with('unsuccess', 'This order status is completed! so, you can\'t cancel shipping!');
        }
        if(strtolower($order->status) == 'declined'){
            return redirect()->back()->with('unsuccess', 'This order status is declined! so, you can\'t cancel shipping!');
        }

        $shipping = new LeopardsController();
        $payload = (object)array("cn"=>$track_id);
        $res = ($shipping->cancel_packet($payload));
        // if($res->status == 1){
        $shipto = ShipItems::where('order_id','=',$order_id)->where('track_id','=',$track_id);
        $shipto->delete();
        // }
        $this->status($order_id, 'processing');
        return redirect()->back()->with('success', "Order Track ID '$track_id' Delivery is Cancelled!");
    }

    public function ordersstatus($status)
    {
        $user = Auth::guard('frenchise')->user();
        $vendors = $user->vendors()->get()->pluck('id');
        $v_orders = Vendororder::whereIn('user_id',$vendors)->get()->pluck('order_id');
        $orders = Order::whereIn('id',$v_orders)->where('status','=',$status)->orderBy('id','desc')->get();
        return view('frenchise.order.index',compact('user','orders'));
    }

}
