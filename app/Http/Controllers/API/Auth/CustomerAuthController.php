<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Hash;
use Auth;
use App\Models\Notification;
use App\Models\Customer;
use App\Models\UsersAddresses;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserSendCode;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Session;
use App\Models\Generalsetting;
use Illuminate\Support\Str;


class CustomerAuthController extends Controller
{
    public function customerlogin(Request $request)
  {
    try {
      $request->validate([
        'email' => 'email|required',
        'password' => 'required'
      ]);
      $credentials = request(['email', 'password']);
      if (!Auth::guard('customer')->attempt(['email' => $request->email, 'password' => $request->password])) {
        return response()->json([
          'status_code' => 500,
          'status' => 0,
          'message' => 'Unauthorized'
        ]);
      }
      $customer = Customer::where('email', $request->email)->first();

      if ( ! Hash::check($request->password, $customer->password, [])) {
        throw new \Exception('Error in Login');
      }
      $tokenResult = $customer->createToken('authToken')->plainTextToken;
      return response()->json([
        'status_code' => 200,
        'status' => 1,
        'access_token' => $tokenResult,
        'token_type' => 'Bearer',
        'customer' => $customer,
      ]);
    } catch (Exception $error) {
      return response()->json([
          'status_code' => 500,
          'status' => 022,
          'message'=>'Invalid Username or Password',
        ]);
    }
  }


  public function register(Request $request)
  {
    $validator=  $request->validate([
      'name' => 'required',
      'email' => 'required|email|unique:customers',
      'password' => 'required',
      'c_password' => 'required|same:password',
    ]);

    $input = $request->all();
    $input['password'] = bcrypt($input['password']);
    $customer = Customer::create($input);
    if (!$customer) {
      return response()->json([
        'status_code' => 500,
        'status' => 0,
        'customer' => $customer,
      ]);
    }
    $tokenResult = $customer->createToken('authToken')->plainTextToken;
    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'access_token' => $tokenResult,
      'token_type' => 'Bearer',
      'customer' => $customer,
    ]);
  }

 public function customer_forgot(Request $request)
    {

      $gs = Generalsetting::findOrFail(1);
      $input =  $request->all();
        if (Customer::where('email', '=', $request->email)->count() > 0) {
            // user found
            $user = Customer::where('email', '=', $request->email)->firstOrFail();
            $autopass = str::random(8);
            $input['password'] = bcrypt($autopass);

            $user->update($input);
            $subject = "Reset Password Request";
            $msg = "Your New Password is : ".$autopass;
        if($gs->is_smtp == 1)
        {
            $data = [
                    'to' => $request->email,
                    'subject' => $subject,
                    'body' => $msg,
            ];

            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);                
        }
        else
        {
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            mail($request->email,$subject,$msg,$headers);            
        }

            Session::flash('success', 'Your Password Reseted Successfully. Please Check your email for new Password.');
        return redirect()->route('user-forgot');

        }
        else{
            // user not found
            Session::flash('unsuccess', 'No Account Found With This Email.');
        return redirect()->route('user-forgot');
        }

    }



}
