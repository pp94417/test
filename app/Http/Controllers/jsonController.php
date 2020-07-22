<?php

namespace App\Http\Controllers;

class jsonController extends Controller{

    public function test(){
        return response()->json(['name' => 'Abigail', 'state' => 'CA']);
    }

    public function test1(){
        $jsonurl = "https://data.epa.gov.tw/api/v1/aqf_p_01?limit=1000&api_key=9be7b239-557b-4c10-9775-78cadfc555e9&format=json";
        $json = json_decode(file_get_contents($jsonurl));
        $a = $json->{'records'};
        $b = array_pluck($a, 'Content');
        //dd($a);
        $binding=[
            'a'=>$a,
        ];  //定義模板中數值
        return view('json', $binding);
        //return response()->json($json)->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

}