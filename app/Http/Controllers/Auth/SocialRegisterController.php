<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Sociallink; 
use App\Models\User;
use App\Models\Customer;
use Auth;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Socialite;

class SocialRegisterController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callbackFromGoogle()
    {
         try {
      
            $customer = Socialite::driver('google')->user();
       
            $findcustomer = Customer::where('google_id', $customer->id)->first();
       
            if($findcustomer){
       
                Auth::guard('customer')->login($findcustomer);
      
                // return redirect()->intended('dashboard');
                 return redirect()->intended(route('customer-dashboard'));
       
            }else{
                $newUser = Customer::create([
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'google_id'=> $customer->id
                ]);
      
                Auth::guard('customer')->login($newUser);
      
                // return redirect()->intended('dashboard');
                return redirect()->intended(route('customer-dashboard'));
            }
      
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }



}
