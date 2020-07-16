<?php

namespace App\Http\Middleware;

use App\Shop\Entity\User;
use Closure;

class AuthUserAdminMiddleware
{
    //處理請求
    public function handle($request, Closure $next)
    {
        //預設不允許存取
        $is_allow_access = false;
        //取得會員編號
        $user_id = session()->get('user_id');

        if(!is_null($user_id)){
            //session 有會員編號 取得會員資料
            $User = User::findOrFail($user_id);

            if($User->type == 'A'){
                //是管理者 允許存取
                $is_allow_access = ture;
            }
        }
        if(!$is_allow_access){
            //若不允許存取 重新導向首頁
            return redirect()->to('/');
        }

        //允許存取繼續做下個請求的處理
        return $next($request);
    }
}
?>