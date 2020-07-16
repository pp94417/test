<?php

namespace App\Http\Middleware;

use App\Shop\Entity\User;
use Closure;

class AuthUserMiddleware
{
    public function handle($request, Closure $next)
    {
        //預設不允許存取
        $is_allow_access = false;
        //取得會員編號
        $user_id = session()->get('user_id');

        if(!is_null($user_id)){
            //session 有會員編號資料 允許存取
            $is_allow_access = ture;
        }
        if(!$is_allow_access){
            //若不允許存取 重新導向登入頁
            return redirect()->to('/user/auth/sing-in');
        }
        return $next($request);
    }
}
