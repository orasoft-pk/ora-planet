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
use App\Models\UserSubscription;
use App\Models\UserNotification;
use App\Models\VendorOrder;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use InvalidArgumentException;
use Markury\MarkuryPost;
use Illuminate\Support\Str;

use DB;

class ShopsController extends Controller {
  public function fetch_all_shops(Request $request)
  {
    $shops_list = User::all()->where(['is_vendor'=>1, 'is_vendor'=>2]);
    $shops = [];
    foreach ($shops_list as $shop){
      array_push($shops, $shop);
    }

    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'data' => $shops,
    ]);
  }

  public function fetch_featured_shops(Request $request)
  {
    $shops_list = User::all()->where('top_by_category','=',1);
    $shops = [];
    foreach ($shops_list as $shop){
      array_push($shops, $shop);
    }

    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'data' => $shops,
    ]);
  }

  public function fetch_grocery_shops(Request $request)
  {
    $shops_list = User::all()->where('grocery','=',1);
    $shops = [];
    foreach ($shops_list as $shop){
      array_push($shops, $shop);
    }

    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'data' => $shops,
    ]);
  }

  public function fetch_top_rated_shops(Request $request)
  {
    $shops_list = User::all()->where('top_rated','=',1);
    $shops = [];
    foreach ($shops_list as $shop){
      array_push($shops, $shop);
    }

    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'data' => $shops,
    ]);
  }

  public function fetch_brand_shops(Request $request)
  {
    $shops_list = User::all()->where('brand','=',1);
    $shops = [];
    foreach ($shops_list as $shop){
      array_push($shops, $shop);
    }

    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'data' => $shops,
    ]);
  }

  public function search_shops(Request $request)
  {
    $payload = $request->validate([
      'string' => 'required',
    ]);
    
    $reservedSymbols = ['-', '_', '+', '<', '>', '@', '(', ')', '~'];
    $searchTerm = str_replace($reservedSymbols, ' ', $payload['string']);
    $searchValues = preg_split('/\s+/', $searchTerm, -1, PREG_SPLIT_NO_EMPTY);
    $vendors = User::where(['is_vendor' => 1, 'is_vendor' => 2])->where(function ($q) use ($searchValues) {
      foreach ($searchValues as $value) {
        $q->orWhere('city', 'like', "%{$value}%")->orWhere('shop_address', 'like', "%{$value}%")->orWhere('shop_name', 'like', "%{$value}%");
      }
    })->orderBy('id', 'desc')->skip(0)->take(60)->get();

    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'data' => $vendors,
    ]);
  }
}
