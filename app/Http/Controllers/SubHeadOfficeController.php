<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateValidationRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\Head;
use App\Models\Order;
use App\Models\Vendororder;
use App\Models\Frenchise;
use App\Models\Product;
use App\Models\Subscriber;
use App\Models\UserSubscription;
use App\Models\Counter;
use App\Models\User;
use App\Models\Withdraw;
use App\Classes\GeniusMailer;
use App\Models\Generalsetting;
use App\Models\Notification;
use App\Models\FrenchiseNotification;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\VendorSubscription;
use Carbon\Carbon;
use Auth;

class SubHeadOfficeController extends Controller
{
    public function create_sub_head_office(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:heads',
        ]);
        $data = array(
            'owner_name' => $request->owner_name,
            'father_name' => $request->father_name,
            'cnic' => $request->cnic,
            'frenchise_address' => $request->frenchise_address,
            'religion' => $request->religion,
            'province' => $request->province,
            'city' => $request->city,
            'address' => $request->address,
            'home_address' => $request->home_address,
            'bank_account_1' => $request->bank_account_1,
            'bank_account_2' => $request->bank_account_2,
            'mobile_number' => $request->mobile_number,
            'mobile_number_1' => $request->mobile_number_1,
            'frenchise_name' => $request->frenchise_name,
            'frenchise_mobile_number' => $request->frenchise_mobile_number,
            'email' => $request->email,
            'password' => $request->password,
            'frenchise_message' => $request->frenchise_message,
            'frenchise_detail' => $request->frenchise_detail,
        );
        $data['password'] = Hash::make($data['password']);
        Head::create($data);
        return redirect()->Route('message.return')->with('success', 'Sub Head Office Created Successfully. Please wait until Admin approval!');
    }

    public function sho_profile()
    {
       $sho = Auth::guard('head')->user();
        return view('sub-head-office.profile',compact('sho'));
    }

    public function profile_update(UpdateValidationRequest $request)
    {
        $input = $request->all();  
        $sho = Auth::guard('head')->user();        
            if ($file = $request->file('photo')) 
            {              
                $name = time().$file->getClientOriginalName();
                $file->move('assets/images',$name);
                if($sho->photo != null)
                {
                    if (file_exists(public_path().'/assets/images/'.$sho->photo)) {
                        unlink(public_path().'/assets/images/'.$sho->photo);
                    }
                }            
            $input['photo'] = $name;
            } 

        $sho->update($input);
        Session::flash('success', 'Successfully updated your profile');
        return redirect()->back();
    }

    public function view_password_reset()
    {
        return view('sub-head-office.reset-password');
    }

    public function update_password(Request $request)
    {
        $sho = Auth::guard('head')->user();
        if ($request->cpass) {
            if (Hash::check($request->cpass, $sho->password)) {
                if ($request->newpass == $request->renewpass) {
                    $input['password'] = Hash::make($request->newpass);
                } else {
                    Session::flash('unsuccess', 'Confirm password does not match.');
                    return redirect()->back();
                }
            } else {
                Session::flash('unsuccess', 'Current password Does not match.');
                return redirect()->back();
            }
        }
        $sho->update($input);
        Session::flash('success', 'Successfully updated your password');
        return redirect()->back();
    }

    // Orders Section
    public function index()
    {
        $user = Auth::guard('head')->user();
        $franchises_list = Frenchise::all()->where('sub_head_office_id','=',$user->id);
        $users = [];
        $products = Product::all();
        $subs = Subscriber::all();
        $pending = Order::where('status','=','pending')->get();
        $processing = Order::where('status','=','processing')->get();
        $completed = Order::where('status','=','completed')->get();
        $referrals = Counter::where('type','referral')->orderBy('total_count','desc')->take(5)->get();
        $browsers = Counter::where('type','browser')->orderBy('total_count','desc')->take(5)->get();
        $days = "";
        $sales = "";

        $franchises_orders = [];
        $franchises_orders_pending = [];
        $franchises_orders_processing = [];
        $franchises_orders_completed = [];
        foreach ($franchises_list??[] as $franchise) {
            $fren_users = User::where('frenchise_id','=',$franchise->id)->where('is_vendor','=',2)->orWhere('is_vendor','=',1)->orderBy('id','desc')->get();
            foreach ($fren_users??[] as $key => $u) {
                $users[] = $u;
            }
        }

        foreach ($users??[] as $key => $u) {
            $temp_franchises_orders =  Vendororder::all()->where('user_id','=',$u->id);
            foreach ($temp_franchises_orders->where('status','=','pending')??[] as $order) {
                $franchises_orders[] = $order;
                $franchises_orders_pending[] = $order;
            }
            foreach ($temp_franchises_orders->where('status','=','processing')??[] as $order) {
                $franchises_orders[] = $order;
                $franchises_orders_processing[] = $order;
            }
            foreach ($temp_franchises_orders->where('status','=','completed')??[] as $order) {
                $franchises_orders[] = $order;
                $franchises_orders_completed[] = $order;
            }
        }
        
        for($i = 0; $i < 30; $i++) {
            $days .= "'".date("d M", strtotime('-'. $i .' days'))."',";
            $sales .=  "'".Order::where('status','=','completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->count()."',";
        }
        $activation_notify = "";
        
        return view('sub-head-office.index',
            compact(
                'franchises_list',
                'franchises_orders',
                'franchises_orders_pending',
                'franchises_orders_processing',
                'franchises_orders_completed',
                'users',
                'products',
                'subs',
                'pending',
                'processing',
                'completed',
                'referrals',
                'browsers',
                'days',
                'sales',
                'activation_notify',
            )
        );
    }

    public function orders_by_status($status)
    {
        $user = Auth::guard('head')->user();
        $franchises_list = Frenchise::all()->where('sub_head_office_id','=',$user->id);
        $users = [];

        $orders = [];
        foreach ($franchises_list??[] as $franchise) {
            $fren_users = User::where('frenchise_id','=',$franchise->id)->where('is_vendor','=',2)->orWhere('is_vendor','=',1)->orderBy('id','desc')->get();
            foreach ($fren_users??[] as $key => $u) {
                $users[] = $u;
            }
        }

        foreach ($users??[] as $key => $u) {
            $temp_franchises_orders =  Vendororder::all()->where('user_id','=',$u->id);
            foreach ($temp_franchises_orders as $order) {
                $oList = [];
                if($status == 'all') $oList = Order::all()->where('id','=',$order->order_id);
                else $oList = Order::all()->where('id','=',$order->order_id)->where('status','=',$status);
                foreach ($oList as $key => $o) {
                    $orders[] = $o;
                }
            }
        }
        
        return view('sub-head-office.order.index',compact('orders'));
    }

    public function view_order($id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
        return view('sub-head-office.order.details',compact('order','cart'));
    }

    public function view_order_invoice($id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
        return view('sub-head-office.order.invoice',compact('order','cart'));
    }

    public function print_page($id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
        return view('admin.order.print',compact('order','cart'));
    }

    public function emailsub(Request $request)
    {
        $gs = Generalsetting::findOrFail(1);
        if($gs->is_smtp == 1)
        {
            $data = [
                'to' => $request->to,
                'subject' => $request->subject,
                'body' => $request->message,
            ];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);                
        }
        else
        {
            $data = 0;
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
            $mail = mail($request->to,$request->subject,$request->message,$headers);
            if($mail) {   
                $data = 1;
            }
        }
        return response()->json($data);
    }

    public function license(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $cart = unserialize(gzuncompress(utf8_decode($order->cart)));
        $cart->items[$request->license_key]['license'] = $request->license;
        $order->cart = utf8_encode(gzcompress(serialize($cart), 9));
        $order->update();
        return redirect()->route('sub_head_office_order_details',$order->id)->with('success','Successfully Changed The License Key.');
    }

    public function update_order_status_by_id($id,$status)
    {
        $mainorder = Order::findOrFail($id);
        if ($mainorder->status == "completed"){
            return redirect()->back()->with('success','This Order is Already Completed');
        }else{
        if ($status == "completed"){
            foreach($mainorder->vendororders as $vorder)
            {
                $uprice = User::findOrFail($vorder->user_id);
                $uprice->current_balance = $uprice->current_balance + $vorder->price;
                $uprice->update();
            }
            $gs = Generalsetting::findOrFail(1);
            if($gs->is_smtp == 1)
            {
                $data = [
                    'to' => $mainorder->customer_email,
                    'subject' => 'Your order '.$mainorder->order_number.' is Confirmed!',
                    'body' => "Hello ".$mainorder->customer_name.","."\n Thank you for shopping with us. We are looking forward to your next visit.",
                ];

                $mailer = new GeniusMailer();
                $mailer->sendCustomMail($data);                
            }
            else
            {
               $to = $mainorder->customer_email;
               $subject = 'Your order '.$mainorder->order_number.' is Confirmed!';
               $msg = "Hello ".$mainorder->customer_name.","."\n Thank you for shopping with us. We are looking forward to your next visit.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
               mail($to,$subject,$msg,$headers);                
            }
        }
        if ($status == "declined"){
            $gs = Generalsetting::findOrFail(1);
            if($gs->is_smtp == 1)
            {
                $data = [
                    'to' => $mainorder->customer_email,
                    'subject' => 'Your order '.$mainorder->order_number.' is Declined!',
                    'body' => "Hello ".$mainorder->customer_name.","."\n We are sorry for the inconvenience caused. We are looking forward to your next visit.",
                ];
            $mailer = new GeniusMailer();
            $mailer->sendCustomMail($data);
            }
            else
            {
               $to = $mainorder->customer_email;
               $subject = 'Your order '.$mainorder->order_number.' is Declined!';
               $msg = "Hello ".$mainorder->customer_name.","."\n We are sorry for the inconvenience caused. We are looking forward to your next visit.";
            $headers = "From: ".$gs->from_name."<".$gs->from_email.">";
               mail($to,$subject,$msg,$headers);
            }

        }
        $stat['status'] = $status;
        $stat['payment_status'] = ucfirst($status);
        $order = Vendororder::where('order_id','=',$id)->update(['status' => $status]);
        $mainorder->update($stat);
        return redirect()->back()->with('success','Order Status Updated Successfully');
        }
    }

    // Franchises
    public function add_franchise()
    {
        return view('sub-head-office.franchise.add');
    }

    public function create_franchise(Request $request)
    {
        $user = Auth::guard('head')->user();
        $request->validate([
            'email' => 'required|unique:frenchises',
        ]);
        $data=array(
			'reg_number'=>$request->reg_number,
            'owner_name'=>$request->owner_name,
            'father_name'=>$request->father_name,
            'cnic'=>$request->cnic,
			'frenchise_address'=>$request->frenchise_address,
			'religion'=>$request->religion,
			'province'=>$request->province,
			'city'=>$request->city,
			'address'=>$request->address,
			'home_address'=>$request->home_address,
			'bank_account_1'=>$request->bank_account_1,
			'bank_account_2'=>$request->bank_account_2,
            'mobile_number'=>$request->mobile_number,
            'mobile_number_1'=>$request->mobile_number_1,
            'submit_amount'=>$request->submit_amount,
            'remaining_amount'=>$request->remaining_amount,
            'duration'=>$request->duration,
            'partner'=>$request->partner,
            'percentage'=>$request->percentage,
            'monthly_percentage'=>$request->monthly_percentage,
            'yearly_percentage'=>$request->yearly_percentage,
            'completion_percentage'=>$request->completion_percentage,
            'vitnes'=>$request->vitnes,
            'father_vitnes'=>$request->father_vitnes,
            'cnic_vitnes'=>$request->cnic_vitnes,
            'vitnes_address'=>$request->vitnes_address,
            'vitnes_mobile'=>$request->vitnes_mobile,
            'vitnes_mobile_1'=>$request->vitnes_mobile_1,
            'frenchise_name'=>$request->frenchise_name,
			'frenchise_mobile_number'=>$request->frenchise_mobile_number,
			'vendor_limit'=>$request->vendor_limit,
            'email'=>$request->email,
            'password'=>$request->password,
			'area'=>$request->area,
			'frenchise_message'=>$request->frenchise_message,
			'frenchise_detail'=>$request->frenchise_detail,
            'sub_head_office_id' => $user->id,
        );
        $data['password'] = Hash::make($data['password']);
        if ($file = $request->file('photo')) 
        {              
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images',$name);           
            $input['photo'] = $name;
            $data['photo'] = $name; 
        }
        Frenchise::create($data);
        return redirect()->Route('sub_head_office_frenchises')->with('success','Franchise Created Successfully.');
    }

    public function list_franchise()
    {
        $user = Auth::guard('head')->user();
        $frenchises = Frenchise::where('sub_head_office_id','=',$user->id)->latest()->get();
        return view('sub-head-office.franchise.index',compact('frenchises'));
    }

    public function list_franchise_by_status($status)
    {
        $user = Auth::guard('head')->user();
        $frenchises = Frenchise::where('sub_head_office_id','=',$user->id)->where('status','=',$status == 'pending' ? '0' : '1')->latest()->get();
        return view('sub-head-office.franchise.index',compact('frenchises'));
    }

    public function details_franchise($id)
    {
        $frenchise = Frenchise::findOrFail($id);
        return view('sub-head-office.franchise.details',compact('frenchise'));
    }

    public function dashboard_franchise($fid)
    {
        $frenchise = Frenchise::where('id',$fid)->first();
        $vendors = User::where('frenchise_id',$frenchise->id)->get()->pluck('id');
        $orders   = Order::whereIn('user_id',$vendors)->get();
        $count_vendor =  count($vendors);
        $products = Product::whereIn('user_id',$vendors)->get();
        $pending = Order::whereIn('user_id',$vendors)->where('status','=','pending')->get();
        $processing = Order::whereIn('user_id',$vendors)->where('status','=','processing')->get();
        $completed = Order::whereIn('user_id',$vendors)->where('status','=','completed')->get();
        $referrals = Counter::where('type','referral')->orderBy('total_count','desc')->take(5)->get();
        $browsers = Counter::where('type','browser')->orderBy('total_count','desc')->take(5)->get();
        $currency_sign = Currency::where('is_default','=',1)->first();
        $c_order = Vendororder::whereIn('user_id',$vendors)->groupBy('order_id')->get()->pluck('order_id');
        $cust = Order::whereIn('id',$c_order)->groupBy('user_id')->pluck('user_id');
        $customer = Customer::whereIn('id',$cust)->orderBy('id','desc')->get();

        $days = "";
        $sales = "";
        for($i = 0; $i < 30; $i++) {
            $days .= "'".date("d M", strtotime('-'. $i .' days'))."',";
            $sales .=  "'".Order::where('status','=','completed')->whereDate('created_at', '=', date("Y-m-d", strtotime('-'. $i .' days')))->count()."',";
        }
        return view('sub-head-office.franchise.frenchise_dashboard',compact('customer','orders','products','currency_sign','frenchise','count_vendor','pending','processing','completed','referrals','browsers','fid','days','sales'));
    }

    public function edit($id)
    {
        $frenchise = Frenchise::findOrFail($id);
        return view('sub-head-office.franchise.edit',compact('frenchise'));
    }

    public function update(Request $request, $id)
    {
        $frenchise = Frenchise::findOrFail($id);
        $data = $request->all();
        if ($file = $request->file('photo'))
        {
            $name = time().$file->getClientOriginalName();
            $file->move('assets/images',$name);
            if($frenchise->photo != null)
            {
                if (file_exists(public_path().'/assets/images/'.$frenchise->photo)) {
                    unlink(public_path().'/assets/images/'.$frenchise->photo);
                }
            }
            $data['photo'] = $name;
        }
        $frenchise->update($data);
        return redirect()->route('sub_head_office_frenchises')->with('success','Franchise Information Updated Successfully.');
    }

    public function franchise_orders_by_status(Frenchise $franchise, $status)
    {
        $user = Auth::guard('head')->user();
        $users = [];
        $vendors = User::where('frenchise_id',$franchise->id)->get()->pluck('id');
        $orders = [];
        if($status == 'all') $orders = Order::whereIn('user_id',$vendors)->get();
        else $orders = Order::whereIn('user_id',$vendors)->where('status','=',$status)->get();
        return view('sub-head-office.order.index',compact('orders'));
    }

    // Customers
    public function franchise_vendors_customers(Frenchise $franchise)
    {
        $vendors = User::where('frenchise_id',$franchise->id)->get()->pluck('id');
        $c_order = Vendororder::whereIn('user_id',$vendors)->groupBy('order_id')->get()->pluck('order_id');
        $cust = Order::whereIn('id',$c_order)->groupBy('user_id')->pluck('user_id');
        $customer = Customer::whereIn('id',$cust)->orderBy('id','desc')->get();
        return view('sub-head-office.franchise.customerlist',compact('customer'));
    }

    public function customer_show(Customer $user)
    {
        return view('sub-head-office.franchise.customerdetail',compact('user'));
    }

    // Vendors
    public function franchise_vendors_list(Frenchise $franchise)
    {
        $users = User::where('frenchise_id',$franchise->id)->get();
        return view('sub-head-office.vendor.index',compact('users'));
    }

    public function vendors_list()
    {
        $id = Auth::guard('head')->user()->id;
        $franchise = Frenchise::where('sub_head_office_id','=',$id)->get()->pluck('id');
        $users = User::whereIn('frenchise_id',$franchise)->orderBy('id','desc')->get();
        return view('sub-head-office.vendor.index',compact('users')); 
    }

    public function orders_by_vendor_and_status($status, User $vendor)
    {
        $orders = [];
        $vendor_order_ob =  Vendororder::all()->where('user_id','=',$vendor->id);
        foreach ($vendor_order_ob as $order) {
            $oList = [];
            if($status == 'all') $oList = Order::all()->where('id','=',$order->order_id);
            else $oList = Order::all()->where('id','=',$order->order_id)->where('status','=',$status);
            foreach ($oList as $key => $o) {
                $orders[] = $o;
            }
        }

        return view('sub-head-office.order.index',compact('orders'));
    }

    public function vendor_details($id)
    {
        $user = User::findOrFail($id);
        $frenchise = Frenchise::findOrFail($user->frenchise_id);
        return view('sub-head-office.vendor.details',compact('user','frenchise')); 
    }

    public function vendor_update($id)
    {
        $user = User::findOrFail($id);
        return view('sub-head-office.vendor.edit',compact('user'));
    }

    // Vendor Withdraw
    public function withdraws_list()
    {
        $sho = Auth::guard('head')->user();
        $franchise = Frenchise::where('sub_head_office_id','=',$sho->id)->get()->pluck('id');
        $vendors = User::whereIn('frenchise_id',$franchise)->get()->pluck('id');
        $withdraws = Withdraw::whereIn('user_id',$vendors)->orderBy('id','desc')->get();
        $pending = Withdraw::where('status','=','pending')->get()->count();
        return view('sub-head-office.vendor.withdraws',compact('withdraws','pending'));
    }

    public function withdraw_details($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        return view('sub-head-office.vendor.withdraw-details',compact('withdraw'));
    }

    public function withdraw_accept($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $data['status'] = "completed";
        $withdraw->update($data);
        return redirect()->route('sub_head_office_vendors_width')->with('success','Withdraw Accepted Successfully');
    }

    public function withdraw_reject($id)
    {
        $withdraw = Withdraw::findOrFail($id);
        $account = User::findOrFail($withdraw->user->id);
        $account->current_balance = $account->current_balance + $withdraw->amount + $withdraw->fee;
        $account->update();
        $data['status'] = "rejected";
        $withdraw->update($data);
        return redirect()->route('sub_head_office_vendors_width')->with('success','Withdraw Rejected Successfully');
    }

    public function withdraw_pendings()
    {
        $sho = Auth::guard('head')->user();
        $franchise = Frenchise::where('sub_head_office_id','=',$sho->id)->get()->pluck('id');
        $vendors = User::whereIn('frenchise_id',$franchise)->get()->pluck('id');
        $withdraws = Withdraw::whereIn('user_id',$vendors)->where('status','=','pending')->orderBy('id','desc')->get();
        return view('sub-head-office.vendor.pending-withdraws',compact('withdraws'));
    }
    
    public function subs_list()
    {
        $sho = Auth::guard('head')->user();
        $franchise = Frenchise::where('sub_head_office_id','=',$sho->id)->get()->pluck('id');
        $vendors = User::whereIn('frenchise_id',$franchise)->get()->pluck('id');
        $subs = UserSubscription::whereIn('user_id',$vendors)->where('status','=',1)->orderBy('id','desc')->get();
        return view('sub-head-office.vendor.subscriptions',compact('subs'));
    }

    public function sub_details($id)
    {
        $subs = UserSubscription::findOrFail($id);
        return view('sub-head-office.vendor.subdetails',compact('subs'));
    }
}