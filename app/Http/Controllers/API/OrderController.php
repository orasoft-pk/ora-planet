<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Advertise;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Childcategory;
use App\Classes\GeniusMailer;
use App\Models\Compare;
use App\Models\Conversation;
use App\Models\Counter;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Faq;
use App\Models\Generalsetting;
use App\Models\Image;
use App\Models\Language;
use App\Models\Message;
use App\Models\VendorSubscription;
use App\Models\Notification;
use App\Models\FrenchiseNotification;
use App\Models\Order;
use App\Models\Page;
use App\Models\Pagesetting;
use App\Models\PaymentGateway;
use App\Models\Pickup;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\ProductClick;
use App\Models\Review;
use App\Models\Service;
use App\Models\Wishlist;
use App\Models\Customer;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\UsersAddresses;
use App\Models\UserSubscription;
use App\Models\UserNotification;
use App\Models\Vendororder;
use App\Models\ShippingServices;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use Markury\MarkuryPost;
use Illuminate\Support\Str;

use DB;

class OrderController extends Controller {
  public function fetch(Request $request)
  {
    $user = Auth::user();
    $orders_list = Order::all()->where('user_id','=',$user->id);
    $orders = [];
    foreach ($orders_list as $order){
      $tmp_obj = (object)[];
      $tmp_obj->method = $order['method'];
      $tmp_obj->order_note = $order['order_note'];
      $tmp_obj->shipping_cost = $order['shipping_cost'];
      $tmp_obj->shipping = $order['shipping'];
      $tmp_obj->coupon_discount = $order['coupon_discount'];
      $tmp_obj->coupon_code = $order['coupon_code'];
      $tmp_obj->total_items = $order['totalQty'];
      $tmp_obj->pay_amount = $order['pay_amount'];

      $cart_list = unserialize(gzuncompress(utf8_decode($order->cart)));
      $tempList1 = [];
      $tempList = [];
      foreach ($cart_list as $key => $obj){
        if($key == 'items'){
          foreach ($obj as $item) {
            $p = Product::all()->where('id','=',$item['item']['id'])->first();
            $p['selected_qty'] = $item['qty'];
            $tmp_obj->cart[] = $p;
          }
        }
      }
      
      array_push($orders, $tmp_obj);
    }

    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'data' => $orders,
    ]);
  }

  public function create(Request $request)
  {
    $user = Auth::user();

    $payload = $request->validate([
      'address_id' => 'required',
      'method_id' => 'required',
      'shipping_id' => 'required',
      'shipping_cost' => '',
      'order_note' => '',
      'shipping' => '',

      'coupon_discount' => '',
      'coupon_code' => '',
      'cart' => 'required'
    ]);
    
    $pay_amount = 0;
    $totalQty = 0;
    $items = array();

    $tmp_cart = json_decode($payload['cart']);

    foreach ($tmp_cart as $item) {
      $p = Product::all()->where('id','=',$item->id)->first();
      $price = $p['deal_of_the_day'] == 1 ? $p['cprice'] : $p['pprice'];
      $totalQty = $item->qty + $totalQty;
      $pay_amount = ($price * $item->qty) + $pay_amount;
      unset($p['description']);
      $p['selected_qty'] = $item->qty;
      $items[$p['id']] = [
        "color" => null,
        "license" => "",
        "price" => ($price * $item->qty),
        "qty" => $item->qty,
        "size" => null,
        "item" => $p
      ];
    }

    $cart_items = (object) array(
      "exists" => false,
      "incrementing" => true,
      "items" => $items,
      "preventsLazyLoading" => false,
      "timestamps" => true,
      "totalPrice" => $pay_amount,
      "totalQty" => $totalQty,
    );

    $payload["order_number"] = strtoupper(str::random(4).time());
    
    $address = UsersAddresses::all()->where('user_id','=', $user->id)->where('id','=', $payload['address_id'])->first();
    unset($payload['address_id']);
    if(!$address){
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => 'Address not found please select another address!',
      ]);
    }

    $pay_method = PaymentGateway::all()->where('id','=', $payload['method_id'])->where('status','=', 1)->first();
    unset($payload['method_id']);
    if(!$pay_method){
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => 'Payment Method not available please choose another one!',
      ]);
    }

    $shipping_method = ShippingServices::all()->where('id','=', $payload['shipping_id'])->where('status','=', 1)->first();
    unset($payload['shipping_id']);
    if(!$shipping_method){
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => 'This shipping service not available please choose another one!',
      ]);
    }

    // customer details
    $payload["user_id"] = $user->id;
    $payload["customer_name"] = $user->name;
    $payload["customer_email"] = $user->email;
    $payload["customer_phone"] = $user->phone;
    $payload["currency_sign"] = $user->currency_sign;
    $payload["currency_value"] = $user->currency_value;
    
    // customer adresses
    $payload["customer_country"] = $user->country;
    $payload["customer_zip"] = $user->zip;
    $payload["customer_city"] = $user->city;
    $payload["customer_address"] = $user->address;
    
    // customer shipping adresses
    $payload["method"] = $pay_method['title'];
    $payload["charge_id"] = $pay_method['id'];
    $payload["txnid"] = preg_replace('/[0-9\@\.\;\" "]+/', '', $pay_method['title']) == 'cashondelivery' ? "COD" : "N/A";
    
    $payload["shipping"] = $shipping_method['title'];
    $payload["shipping_country"] = "Pakistan";//$address['country'];
    $payload["province"] = $address['province'];
    $payload["shipping_city"] = $address['city'];
    $payload["shipping_zip"] = $address['zip'];
    $payload["shipping_address"] = $address['address'];

    $payload["shipping_name"] = $user->name;
    $payload["shipping_email"] = $user->email;
    $payload["shipping_phone"] = $user->phone;
    
    $payload["payment_status"] = "Pending";
    $payload["status"] = "pending";

    $payload["pickup_location"] = null;

    $payload["pay_amount"] = $pay_amount;
    $payload["totalQty"] = $totalQty;

    $payload['cart'] = utf8_encode(gzcompress(serialize($cart_items), 9));

    $order_req = Order::create($payload);

    if($order_req != NULL){
      foreach ($cart_items->items as $prod) {
        $vorder =  new Vendororder;
        $vorder->order_id = $order_req->id;
        $vorder->user_id = $user->id;
        $vorder->qty = $prod['qty'];
        $vorder->price = $prod['price'];
        $vorder->order_number = $order_req->order_number;             
        $vorder->save();
      }

      return response()->json([
        'status_code' => 200,
        'status' => 1,
        'message' => 'Order posted successfully!',
        'data' => $order_req,
      ]);
    }else{
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => 'Order not posted!',
      ]);
    }
  }
}
