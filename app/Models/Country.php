<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = ['city','lat','lng','country','iso2','admin_name','capital','population','population_proper'];
    public $timestamps = false;

    public function get_countries()
    {
        $countries = [];
        $statesList = [];
        $cities = [];
        
        $states = Country::all();
        foreach ($states as $k => $v) {
            $countries[$v->country] = $v;
            $statesList[$v->admin_name] = $v;
            $cities[$v->city] = $v;
        }

        $formated = [];

        foreach ($countries as $k => $v) {
            $provinces = [];

            foreach ($statesList as $sk => $sv) {
                if ($k == $sv->country) {
                    $pro = (object)array(
                        'province' => $sk,
                        'cities' => []
                    );
                    $cit = [];
                    foreach ($cities as $cv) {
                        if ($sk == $cv->admin_name) {
                            $cit[] = (object)array("city" => $cv->city);
                        }
                    }
                    $pro->cities = $cit;
                    $provinces[] = $pro;
                }
            }

            $formated[] = (object)array(
                // "id" => $v->id,
                "country" => $k,
                "image" => null, //$v->image,
                "provinces" => $provinces
            );
        }

        return $formated;
    }
}
