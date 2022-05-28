<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\Country;
use App\Models\Product;
use App\Models\Page;


class FrontendController extends Controller
{
     public function categories(Request $request)
  {

   	 $category= Category::with('subs')->get();

      return response()->json([
        'status_code' => 200,
        'status' => 1,
        'categories' => $category,
      ]);
  }

   public function shops(Request $request)
  {

   	$shops= User::all();
    if(!$shops)
        {
            return response()->json([
                'status_code' => 500,
                'status' => 0,
                'message' => 'No category Found',
            ]);
        }

    return response()->json([
        'status_code' => 200,
        'status' => 1,
        'shops' => $shops,
      ]);
  }

   public function countries()
  {

  	$country = new Country;
   	$countries = $country->get_countries();

    return response()->json([
        'status_code' => 200,
        'status' => 1,
        'countries' => $countries,
      ]);
  }

   public function search(Request $request)
  {
    
    $search = Product::where('status', '=', 1)->where('name', 'like', '%' . $request->product . '%')->get();

    return response()->json([
        'status_code' => 200,
        'status' => 1,
        'products' => $search,
      ]);
  
  }

  public function road_search(Request $request)
  {

    $vendors = User::where('shop_address', 'like', "%{$request->shop_address}%")->orWhere('shop_name', 'like', "%{$request->shop_name}%")->orderBy('id', 'desc')->get();

    return response()->json([
        'status_code' => 200,
        'status' => 1,
        'countries' => $vendors,
      ]);
  }

  public function brands()
    {
        $brands = User::where('brand','1')->get();
        if(!$brands)
        {
            return response()->json([
                'status_code' => 500,
                'status' => 0,
                'message' => 'No category Found',
            ]);
        }

        return response()->json([
        'status_code' => 200,
        'status' => 1,
        'brands' => $brands,
      ]);
    }

    public function festivels()
    {
        $product = Product::where('status',1)->where('festival',1)->get();
        if(!$product)
        {
            return response()->json([
                'status_code' => 500,
                'status' => 0,
                'message' => 'No category Found',
            ]);
        }

        return response()->json([
            'status_code' => 200,
            'status' => 1,
            'festivels'=>$product,

        ]);
    }

    public function about()
    {
        $page = Page::where('slug','about')->first();
        
        return response()->json([
            'status_code' => 200,
            'status' => 1,
            'about'=>$page,

        ]);
    }



}
