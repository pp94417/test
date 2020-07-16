<?php

//資料庫互動

namespace App\Shop\Entity;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model{
    //資料表名稱
    protected $table = 'transaction';

    //主鍵名稱
    protected $primaryKey = 'id';

     //可以大量指定異動的欄位(Mass Assignment)
    protected $fillable = [
        "id",
        "user_id",
        "merchandise_id ",
        "price",
        "buy_count",
        "total_price",
    ];

    public function Merchandise(){
        return $this->hasOne('App\Shop\Entity\Merchandise','id','merchandise_id');  
        //$this->hasOne() 擁有一筆資料表關聯 (每筆交易只會有一個商品)
        //$this->belongsTo() 資料屬於哪個資料表的資料
        //$this->hasMany() 有多個資料筆數的關聯
        //$this->belongsToMany() 屬於多個資料列的關聯
    }


}