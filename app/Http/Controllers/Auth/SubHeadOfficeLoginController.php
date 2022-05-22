<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\Head;
use Illuminate\Support\Facades\Session;


class SubHeadOfficeLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:frenchise', ['except' => ['logout']]);
    }

    public function showLoginForm()
    {
        $country = new Country;
        $countries = $country->get_countries();
        return view('sub-head-office.login', compact('countries'));
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        if (Auth::guard('head')->attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::guard('head')->user();
            if($user->status == 1){
                return redirect()->intended(route('sub_head_office_dashboard'));
            }else{
                Session::flash('message', "f");
                return redirect()->back()->withInput($request->only('email', 'remember'));
            }
        }
        Session::flash('message', "f");
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    public function logout()
    {
        Auth::guard('head')->logout();
        return redirect()->route('sub-head-office-login');
    }

    protected function guard()
    {
        return Auth::guard('head');
    }
}