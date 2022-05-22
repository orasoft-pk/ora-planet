<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShippingServices;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'cart', 'method','shipping', 'pickup_location', 'totalQty', 'pay_amount', 'txnid', 'charge_id', 'order_number', 'payment_status', 'customer_email', 'customer_name', 'customer_phone', 'customer_country', 'customer_address', 'customer_city','province', 'customer_zip','shipping_name', 'shipping_email', 'shipping_phone', 'shipping_address', 'shipping_country', 'shipping_city', 'shipping_zip', 'order_note', 'status', 'tax', 'shipping_cost', 'currency_sign', 'currency_value'];

    public function vendororders()
    {
        return $this->hasMany('App\Models\Vendororder');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function shipping_service()
    {
        $shipping_service = ShippingServices::all()->where('id','=',$this->shipping_service)->first();
        if($shipping_service){
            return $shipping_service;
        }else{
            return (object)[];
        }
    }
}
