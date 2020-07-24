<?php

//資料庫互動

namespace App\Shop\Entity;

use Illuminate\Database\Eloquent\Model;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements JWTSubject{
    //資料表名稱
    protected $table = 'users';

    //主鍵名稱
    protected $primaryKey = 'id';

    //可以大量指定異動的欄位(Mass Assignment)
    protected $fillable = [
        "email",
        "password",
        "type",
        "nickname",
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}