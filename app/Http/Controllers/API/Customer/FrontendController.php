<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\User;
use App\Models\Country;
use App\Models\Product;
use App\Models\Page;
use App\Models\State;


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

   public function search($id)
  {
    
    $search = Product::where('status', '=', 1)->where('name', 'like', '%' . $id . '%')->get();

    return response()->json([
        'status_code' => 200,
        'status' => 1,
        'products' => $search,
      ]);
  
  }

  public function road_search($id)
  {

    $vendors = User::where('shop_address', 'like', "%{$id}%")->orWhere('shop_name', 'like', "%{$id}%")->orderBy('id', 'desc')->get();

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

     public function groceries()
  {
    
    $groceries = Product::where('status', '=', 1)->where('category_id',13)->get();

    return response()->json([
        'status_code' => 200,
        'status' => 1,
        'groceries' => $groceries,
      ]);
  
  }

  public function advance_search()
    {
        $states = State::all();
        $country = new Country;
        $countries = $country->get_countries();
        $category= Category::all();


        return response()->json([
        'status_code' => 200,
        'status' => 1,
        'data' => ["categories"=>$category,"countries"=>$countries],
      ]);

    }

    public function productsearch(Request $request)
    {
        $min      = $request->min;
        $max      = $request->max;
        $name     = $request->name;
        $category = $request->category;

         $products = Product::query();
        
        if($name)
        {
          $products->orWhere('name', 'like' ,'%{$name}%');
        }
        if ($category) 
        {
          $products->orWhere('category_id',$category);
        }
        if($min && $max)
        {
          $products->whereBetween('cprice', [$min, $max]);
        }
        
        $products->get();


        return response()->json([
        'status_code' => 200,
        'status' => 1,
        'data' => ["products"=>$products],
      ]);   

    }



}
