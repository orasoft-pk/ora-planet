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
use App\Models\Slider;
use App\Models\Subcategory;
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

class SliderController extends Controller {
  public function get_sliders_all()
  {
    $sliders_obj = Slider::all();
    $sliders = [];
    $base_url = url('/') . '/assets/images/';
    foreach ($sliders_obj as $slider)
      array_push($sliders, $base_url . $slider->photo);

    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'data' => $sliders
    ]);
  }
}
