<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipItems extends Model
{
    use HasFactory;

    protected $fillable = [
        'track_id',
        'slip_link',
        'order_id',
        'order_number',
        'customer_id',
        'vendor_id',
        'frenchise_id',
        'shipping_service_id',
        'shipping_charges',
        'booked_packet_json',
        'status',
    ];
}
