<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Models\Advertise;
use App\Models\Banner;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\Jazzcash;
use App\Models\EaseyPasa;
use App\Models\Head;
use App\Models\NewUpdate;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Childcategory;
use App\Classes\GeniusMailer;
use App\Models\Compare;
use App\Models\Conversation;
use App\Models\Counter;
use App\Models\Coupon;
use App\Models\Currency;
use App\Models\Frenchise;
use App\Models\Faq;
use App\Models\Generalsetting;
use App\Models\Image;
use App\Models\Language;
use App\Models\Message;
use App\Models\VendorSubscription;
use App\Models\Notification;
use App\Models\FrenchiseNotification;
use App\Models\Order;
use App\Models\Country;
use App\Models\Page;
use App\Models\Pagesetting;
use App\Models\PaymentGateway;
use App\Models\Pickup;
use App\Models\Portfolio;
use App\Models\Product;
use App\Models\ProductClick;
use App\Models\Review;
use App\Models\Service;
use App\Models\Slider;
use App\Models\Subcategory;
use App\Models\Subscriber;
use App\Models\User;
use App\Models\UserSubscription;
use App\Models\UserNotification;
use App\Models\Vendororder;
use Auth;
use Hash;
use Captcha;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use Markury\MarkuryPost;
use Illuminate\Support\Str;

use DB;

use function GuzzleHttp\Promise\all;
use function Symfony\Component\String\b;

class CommonController extends Controller
{
    public function change_vendor_qty_qlty(Request $request)
    {
        $payload = $request->validate([
            "vendor_id"=>'required',
            "order_no"=>'required',
            "item_id"=>'required',
            "key"=>'required',
            "value"=>'required',
            "remarks"=>'',
        ]);
        $vendor_order = VendorOrder::where('order_number','=',$payload['order_no'])->where('user_id','=',$payload['vendor_id'])->where('item_id','=',$payload['item_id'])->first();
        if($vendor_order){
            $vendor_order[$payload['key'].'_check'] = $payload['value']?1:0;
            $vendor_order[$payload['key'].'_remarks'] = $payload['remarks'];
            $vendor_order->save();

            $order = Order::where('order_number','=',$payload['order_no'])->first();
            $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
            $cart->items[$payload['item_id']][$payload['key'].'_check'] = $payload['value']?"1":"0";

            // return $cart->items[$payload['item_id']];
            $order->cart = utf8_encode(gzcompress(serialize($cart), 9));
            $order->update();
        }
        if($payload['value']){
            return ([
                'status'=>1,
                'message'=>'Checked Successfully!'
            ]);
        }else{
            return ([
                'status'=>1,
                'message'=>'Unchecked Successfully!'
            ]);
        }
    }
}
