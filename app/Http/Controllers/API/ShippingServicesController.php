<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShippingServices;

class ShippingServicesController extends Controller
{
    public function fetch(Request $request)
    {
        $data = ShippingServices::all();
        $methods = [];

        foreach ($data as $method) {
            $methods[] = $method;
        }

        return response()->json([
            'status_code' => 200,
            'status' => 1,
            'data' => $methods,
        ]);
    }
}
