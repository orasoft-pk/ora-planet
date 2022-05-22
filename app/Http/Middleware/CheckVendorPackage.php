<?php

namespace App\Http\Middleware;

use App\Models\UserSubscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Closure;

class CheckVendorPackage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $vendor = Auth::guard('user')->user();
        if($vendor){
            $vendor_sub = UserSubscription::all()->where('user_id','=',$vendor->id)->where('status','=',1)->first();
            if($vendor_sub){
                $now = time();
                $your_date = strtotime($vendor_sub->created_at);
                $datediff = $now - $your_date;
                $remaining_days = $vendor_sub->days-round($datediff / (60 * 60 * 24));
                if ($remaining_days >= 0){
                    return $next($request);
                }
                $vendor_sub['status'] = 0;
                $vendor_sub->save();
            }
        }
        return redirect()->route('user-package')->with('unsuccess',"Please Buy Package to continue your shop selling!");
    }
}
