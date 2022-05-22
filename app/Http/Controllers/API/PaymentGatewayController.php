<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentGateway;

class PaymentGatewayController extends Controller
{
    public function fetch(Request $request)
    {
        $data = PaymentGateway::all();
        $methods = [];

        foreach ($data as $method) {
            // if($method['status'] == 1){
            // unset($method['method']);
            $methods[] = $method;
            // }
        }

        return response()->json([
            'status_code' => 200,
            'status' => 1,
            'data' => $methods,
        ]);
    }
}
