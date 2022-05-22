<?php

namespace App\Http\Controllers\API\Auth;

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

class AuthController extends Controller
{
  public function userlogin(Request $request)
  {
    try {
      $request->validate([
        'email' => 'email|required',
        'password' => 'required'
      ]);
      $credentials = request(['email', 'password']);
      if (!Auth::guard('user')->attempt(['email' => $request->email, 'password' => $request->password])) {
        return response()->json([
          'status_code' => 500,
          'status' => 0,
          'message' => 'Unauthorized'
        ]);
      }
      $user = User::where('email', $request->email)->first();

      if ( ! Hash::check($request->password, $user->password, [])) {
        throw new \Exception('Error in Login');
      }
      $tokenResult = $user->createToken('authToken')->plainTextToken;

      $user['addresses'] = UsersAddresses::all()->where('user_id','=', $user->id);
      return response()->json([
        'status_code' => 200,
        'status' => 1,
        'access_token' => $tokenResult,
        'token_type' => 'Bearer',
        'user' => $user,
      ]);
    } catch (Exception $error) {
      return response()->json([
          'status_code' => 500,
          'status' => 022,
          'message'=>'Invalid Username or Password',
        ]);
    }
  }

  public function up(){
      $products = User::all();
      return response()->json($products);
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
    $user = User::create($input);
    if (!$user) {
      return response()->json([
        'status_code' => 500,
        'status' => 0,
        'user' => $user,
      ]);
    }
    $tokenResult = $user->createToken('authToken')->plainTextToken;
    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'access_token' => $tokenResult,
      'token_type' => 'Bearer',
      'user' => $user,
    ]);
  }

  /**
   * details api
   *
   * @return \Illuminate\Http\Response
   */
  public function details()
  {
    $user = Auth::user();
    $user['addresses'] = UsersAddresses::all()->where('user_id','=', $user->id);
    if (!$user) {
      return response()->json([
        'status_code' => 500,
        'status' => 0,
        'user' => $user,
      ]);
    }
    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'user' => $user,
    ]);
  }

  public function userforgetpassword(Request $request)
  {
    try {
      $validator=  $request->validate([
        'email' => 'required|email',
      ]);
      $user=User::where('email',$request->input('email'))->first();

      if (!$user) {
        return response()->json([
          'status_code' => 500,
          'status' => 0,
          'message' => 'WrongEmail'
        ]);
      }

          $expire = Carbon::now()->addMinute(5);
        $check=$user;
        while($check)
        {
          $code=mt_rand(1000, 9999);
          $check=User::where('forgetpasswordcode',$code)->first();
        }
        $user->forgetpasswordcode = $code;
        $user->codeexpireat = $expire;
        $user->save();
      Mail::to($user->email)->send(new UserSendCode($code));
      return response()->json([
        'status_code' => 200,
        'status' => 1,
        'message' => 'code has been sent to your email',

      ]);
    } catch (Exception $error) {
      return response()->json([
          'status_code' => 500,
          'status' => 0,
          'user' => $user,
        ]);
    }
  }

  public function userentercode(Request $request)
  {
    try {
      $validator=  $request->validate([
        'email' => 'required|email',
        'code' => 'required',
      ]);
      $user=User::where('email',$request->input('email'))->where('forgetpasswordcode',$request->input('code'))->first();
      if (!$user) {
        return response()->json([
          'status_code' => 500,
          'status' => 0,
          'message' => 'WrongCode'
        ]);
      }

      $now = Carbon::now();
      if($now>$user->codeexpireat)
      {
        return response()->json([
          'status_code' => 500,
          'status' => 0,
          'message' => 'Your Code has been expire please click on resend button to receive a fresh code',
        ]);
      }

      $user['addresses'] = UsersAddresses::all()->where('user_id','=', $user->id);
      return response()->json([
        'status_code' => 200,
        'status' => 1,
        'message' => 'verified your code please inter new password',
        'user'=>$user,

      ]);
    } catch (Exception $error) {
      return response()->json([
          'status_code' => 500,
          'status' => 0,
          'user' => $user,
        ]);
    }

  }

  public function forgetpasswordchange(Request $request)
  {
    try {
      $data=$request->validate([
        'password' => 'required',
        'c_password' => 'required|same:password',
        'email'=> 'required|email',
      ]);

      $user=User::where('email',$request->input('email'))->first();
      if(!$user)
      {
          return response()->json([
        'status_code' => 500,
        'status' => 0,
        'message' => 'Email is wrong'

      ]);
      }

      $input['password'] = bcrypt($data['password']);
      $user = User::where('id',$user->id)->update($input);
      return response()->json([
        'status_code' => 200,
        'status' => 1,
        'message' => 'password has been changed You can login with new password'

      ]);
    } catch (Exception $error) {
      return response()->json([
          'status_code' => 500,
          'status' => 0,
          'user' => 'exception',
        ]);
    }
  }

  // Shawal Ahmad Mohmand
  public function manage_address(Request $request)
  {
    $user = Auth::user();
    $payload =  $request->validate([
      'type' => '',
      'country' => 'required',
      'province' => 'required',
      'city' => 'required',
      'address' => 'required',
      'zip' => '',
      'lat' => '',
      'lng' => '',
      'default' => '',
    ]);

    if(!$user)
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => "Can't " . ($request->id ? "update" : "add") . " address please logout and login again!"
      ]);
    
    $payload['user_id'] = $user->id;
    
    if($payload['default'] == 1 || $payload['default'] == TRUE){
      UsersAddresses::where('user_id','=',$user->id)->update([ 'default'=>0 ]);
    }

    $address = NULL;
    if($request->id) $address = UsersAddresses::whereId($request->id)->update($payload);
    else $address = UsersAddresses::create($payload);

    $user['addresses'] = UsersAddresses::all()->where('user_id','=', $user->id);

    if($address){
      return response()->json([
        'status_code' => 200,
        'status' => 1,
        'message' => "User address " . ($request->id ? "updated" : "added") . " successfully!",
        'data' => $user,
      ]);
    }else{
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => "Can't " . ($request->id ? "update" : "add") . " address please logout and login again!",
        'data' => $user,
      ]);
    }
  }

  public function remove_address(Request $request)
  {
    $user = Auth::user();
    $payload = $request->validate([ 'id' => 'required' ]);
    $address = UsersAddresses::findOrFail($payload['id']);
    $address->delete();

    if(!$address){
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => "Sorry we can't remove this address at this time!",
      ]);
    }

    $user['addresses'] = UsersAddresses::all()->where('user_id','=', $user->id);

    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'message' => 'Address deleted successfully!',
      'data' => $user,
    ]);
  }

  public function update_profile(Request $request){
    $user = Auth::user();

    $payload = $request->validate([
      'name' => 'required',
      'photo' => '',
      'phone' => '',
      'province' => 'required',
      'address' => 'required',
      'country' => 'required',
      'city' => 'required',
      'zip' => 'required',
    ]);

    if($image = $request->file('photo')){
      $png_url = "profile_" . $user->id . ".png";
      $path = public_path('photo/');
      Storage::delete($path.$png_url);
      $request->photo->move(public_path('photo/'), $png_url);
      $user['photo'] = "$png_url";
    }

    $user->name = $payload['name'];
    if($payload['phone'] !== NULL) $user->phone = $payload['phone'];
    if($payload['province'] !== NULL) $user->province = $payload['province'];
    if($payload['address'] !== NULL) $user->address = $payload['address'];
    if($payload['city'] !== NULL) $user->city = $payload['city'];
    if($payload['country'] !== NULL) $user->country = $payload['country'];
    if($payload['zip'] !== NULL) $user->zip = $payload['zip'];
    $user->save();

    $user['addresses'] = UsersAddresses::all()->where('user_id','=', $user->id);
    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'message' => 'User Profile updated successfully!',
      'data' => $user
    ],200);
  }

  public function update_photo(Request $request)
  {
    $user = Auth::user();

    $payload = $request->validate([
      'photo' => 'mimes:jpeg,jpg,png|required',
    ]);

    if($image = $request->file('photo')){
      $png_url = "profile_" . $user->id . ".png";
      $path = public_path('photo/');
      Storage::delete($path.$png_url);
      $request->photo->move(public_path('photo/'), $png_url);
      $user['photo'] = "$png_url";
    }

    $user->save();

    $user['addresses'] = UsersAddresses::all()->where('user_id','=', $user->id);
    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'message' => 'User Profile updated successfully!',
      'data' => $user
    ],200);
  }

  public function userchangepassword(Request $request)
  {
    $user = Auth::user();
    try {
      $data=$request->validate([
        'password' => 'required',
        'new_password' => 'required',
        'c_new_password' => 'required|same:new_password',
      ]);
      if (!Hash::check($data['password'], $user->password)) {
        return response()->json([
          'status_code' => 200,
          'status' => 0,
          'message' => 'Invalid old password!'
        ]);
      }
      $input['password'] = bcrypt($data['new_password']);
      $user = User::where('id',$user->id)->update($input);
      return response()->json([
        'status_code' => 200,
        'status' => 1,
        'message' => 'Password has been changed!'
      ]);
    } catch (Exception $error) {
      return response()->json([
        'status_code' => 500,
        'status' => 0,
        'message' => 'Unkown error from server side!'
      ]);
    }
  }
}
