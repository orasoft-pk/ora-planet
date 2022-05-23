<?php

namespace App\Http\Controllers\Auth;
use App\Classes\GeniusMailer;
use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use App\Models\Generalsetting;
use Illuminate\Support\Str;

class UserForgotController extends Controller
{
    public function __construct()
    {
      $this->middleware('guest:user', ['except' => ['logout']]);
    }

    public function showforgotform()
    {
    	return view('user.forgot');
    }

    public function forgot(Request $request)
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
