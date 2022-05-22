<?php

namespace App\Http\Controllers\Shipping;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;

class LeopardsController extends Controller {
  private $api_key = "917F66C3DA47EB8D1C427ACDB5E3177C";
  private $passwd = "HOUSE123";
  private $apis = [
    "GET_CITIES"=>"http://new.leopardscod.com/webservice/getAllCitiesTest/format/json/", //post
    "GET_CHARGES"=>"http://new.leopardscod.com/webservice/getTariffDetails/format/json/?", //get
    "BOOK_PACKET"=>"http://new.leopardscod.com/webservice/bookPacketTest/format/json/", //post
    "CANCEL_PACKET"=>"http://new.leopardscod.com/webservice/cancelBookedPackets/format/json/", //post
    "GET_PACKET_DETAILS"=>"http://new.leopardscod.com/webservice/getShipmentDetailsByOrderID/format/json/", //post
  ];


  public function get_charges(Product $product, User $vendor, $city=False)
  {
    $cities_list = $this->get_leopards_cities($city);
    $c1 = $this->get_city_details_from_cities($cities_list, $city);
    $c2 = $this->get_city_details_from_cities($cities_list, $vendor->city);

    if(!isset($c1->name)){
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => "shipment not available for '$city' city!",
        'data' => (object)[]
      ]);
    }

    if(!isset($c2->name)){
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => 'vendor can\'t deliver to your city!',
        'data' => (object)[]
      ]);
    }

    if(!isset($product->measure)){
      return response()->json([
        'status_code' => 200,
        'status' => 0,
        'message' => 'due to null weight, can\'t calculate shipping charges!',
        'data' => (object)[]
      ]);
    }

    $pyld = (object)[
      'cod_amount' => '1',
      'packet_weight' => $product->measure,
      'shipment_type' => 'N/A',
      'origin_city' => $c1->id,
      'destination_city' => $c2->id,
    ];

    $sc = $this->get_shipping_charges($pyld);
    return response()->json([
      'status_code' => 200,
      'status' => 1,
      'data' => $sc
    ]);
  }

  public function get_city_details_from_cities($cities=[], $city=false)
  {
    $result = (object)[];
    if($city){
      for ($i=0; $i < count($cities); $i++) {
        $elem = $cities[$i];
        if(strtolower($elem->name) == strtolower($city)){
          $result = $elem;
          break;
        }
      }
    }
    return $result;
  }

  // leopards apis
  public function get_leopards_cities()
  {
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $this->apis['GET_CITIES']); // Write here Test or Production Link
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_POST, 1);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, array( 'api_key' => $this->api_key, 'api_password' => $this->passwd ));
    $result = json_decode(curl_exec($curl_handle));
    curl_close($curl_handle);
    $result = ($result->status == 1 && $result->error == 0) ? $result->city_list : [];
    return $result;
  }

  public function get_shipping_charges($obj)
  {
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $this->apis['GET_CHARGES']."api_key=$this->api_key&api_password=$this->passwd&cod_amount=$obj->cod_amount&packet_weight=$obj->packet_weight&shipment_type=$obj->shipment_type&origin_city=$obj->origin_city&destination_city=$obj->destination_city");
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl_handle);
    curl_close($curl_handle);
    $result = json_decode($result);
    return $result->status == 1 ? $result->packet_charges : (object)[ "shipment_charges"=>"shipping charges will calculate later!" ];
  }

  public function book_packet($obj)
  {
    if(!isset($obj->destination_city) || !isset($obj->origin_city)){
      return json_encode([
        "status"        =>   1,
        "error"         =>   "Origin or Destination City id missing!",
        "track_number"  =>   null,
        "slip_link"     =>   null
      ]);
    }

    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $this->apis['BOOK_PACKET']);  // Write here Test or Production Link
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_POST, 1);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, (array(
        'api_key'                       => $this->api_key,
        'api_password'                  => $this->passwd,
        'booked_packet_weight'          => isset($obj->weight)?$obj->weight:1,
        'booked_packet_vol_weight_w'    => 0,
        'booked_packet_vol_weight_h'    => 0,
        'booked_packet_vol_weight_l'    => 0,
        'booked_packet_no_piece'        => isset($obj->qty)?$obj->qty:1,
        'booked_packet_collect_amount'  => isset($obj->collect_amount)?$obj->collect_amount:0,
        'booked_packet_order_id'        => isset($obj->order_id)?$obj->order_id:'',
        'origin_city'                   => $obj->origin_city,
        'destination_city'              => $obj->destination_city,
        'shipment_name_eng'             => isset($obj->frenchise_name)?$obj->frenchise_name:'N/A',
        'shipment_email'                => isset($obj->frenchise_email)?$obj->frenchise_email:'N/A',
        'shipment_phone'                => isset($obj->frenchise_phone)?$obj->frenchise_phone:0,
        'shipment_address'              => isset($obj->frenchise_address)?$obj->frenchise_address:'N/A',
        'consignment_name_eng'          => isset($obj->shipto_name)?$obj->shipto_name:'N/A',
        'consignment_email'             => isset($obj->shipto_email)?$obj->shipto_email:'N/A',
        'consignment_phone'             => isset($obj->shipto_phone)?$obj->shipto_phone:0,
        'consignment_phone_two'         => isset($obj->shipto_phone2)?$obj->shipto_phone2:'N/A',
        'consignment_phone_three'       => isset($obj->shipto_phone3)?$obj->shipto_phone3:'N/A',
        'consignment_address'           => isset($obj->shipto_address)?$obj->shipto_address:'N/A',
        'special_instructions'          => isset($obj->note)?$obj->note:'N/A',
        'shipment_type'                 => '',
        'custom_data'                   => '',
        'return_address'                => isset($obj->return_address)?$obj->return_address:$obj->frenchise_address,
    )));

    $result = curl_exec($curl_handle);
    curl_close($curl_handle);
    // return json_decode($result);
    return $result;
  }

  public function get_packet_details($obj)
  {
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $this->apis['GET_PACKET_DETAILS']);  // Write here Test or Production Link
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, json_encode(array(
      'api_key'                       => $this->api_key,
      'api_password'                  => $this->passwd,
      'shipment_order_id' => $obj->ids_array, // E.g. array('Order Id') OR  array('Order Id-1', 'Order Id-2', 'Order Id-3')
    )));

    $result = curl_exec($curl_handle);
    curl_close($curl_handle);
    $result = json_decode($result);
    return $result;
  }

  public function cancel_packet($obj)
  {
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $this->apis['CANCEL_PACKET']);  // Write here Test or Production Link
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_POST, 1);
    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, (array(
      'api_key'       => $this->api_key,
      'api_password'  => $this->passwd,
      'cn_numbers'    => $obj->cn
    )));

    $result = curl_exec($curl_handle);
    curl_close($curl_handle);
    return $result;
  }
}
