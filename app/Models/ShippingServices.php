<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingServices extends Model
{
    use HasFactory;
   
    protected $fillable = ['title', 'cost', 'distance'];
    public $timestamps = false;
}
