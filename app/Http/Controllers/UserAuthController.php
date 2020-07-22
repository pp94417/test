<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Validator;    //使用驗證器
use App\Shop\Entity\User;   //使用者 Eloquen ORM Model
use Hash;   //雜湊(密碼加密)
use DB; //使用資料庫物件
use Mail;
use Socialite;
use App\Jobs\SendSignUpMailJob;


class UserAuthController extends Controller{


    //註冊頁
    public function singUpPage(){

         $binding = [
            'title' => '註冊',
        ];
        return view('auth.singUp', $binding);

    }

     //處理註冊資料
     public function singUpProcess(){
        
        //接收註冊資料
        $input = request()->all();

        /*var_dump($input);
        exit;*/

        //驗證規則
        $rules = [
            //暱稱
            'nickname'=> [
                'required',
                'max:150',
            ],
            //Email
            'email'=> [
                'required',  //必填欄位
                'max:150',  //資料最長長度
                'email',    //驗證資料是否為 Email 格式
            ],
            //密碼
            'password'=> [
                'required',
                'same:password_confirmation',   //需與密碼驗證相同
                'min:6',
            ],
            //密碼驗證
            'password_confirmation'=> [
                'required',
                'min:6',
            ],
            //帳號類型
            'type'=> [
                'required',
                'in:G,A',   //限定資料只能為G及A
            ],
        ];

        //驗證資料
        $validator = Validator::make($input, $rules);

        if ($validator->fails()){
            //資料驗證錯誤
            return redirect('/user/auth/sing-up')
            ->withErrors($validator)
            ->withInput();
        }
        //密碼加密 
        $input['password'] = Hash::make($input['password']); //會使用.evn中的APP_KEY當作密鑰進行加密
                                                            //php artisan key:generate隨時產生新密鑰
        
        //新增會員資料
        $Users = User::create($input);

        //寄送註冊通知信
        $mail_binding=[
            'nickname'=>$input['nickname']
        ];

        /*Mail::send('email.singUpEmailNotification', $mail_binding,
        function($mail) use ($input){
            //寄件人
            $mail->to($input['email']);
            //收件人
            $mail->from($input['']);
            //郵件主旨
            $mail->subject('恭喜註冊成功');
        });*/
        //SendSignUpMailJob::dispatch($mail_binding);
        return redirect('/user/auth/sing-in');

    }

        //登入
        public function singInPage(){
            $binding = [
                'title' => '登入',
            ];
            return view('auth.singIn', $binding);
        }
        
        //處理登入資料
        public function singInProcess(){
                    
            //接收註冊資料
            $input = request()->all();

            //驗證規則
            $rules = [
                //Email
                'email'=> [
                    'required',
                    'max:150',
                    'email',
                ],
                 //密碼
                'password'=> [
                    'required',
                    'min:6',
                ],
            ];

            //驗證資料
            $validator = Validator::make($input, $rules);

            if ($validator->fails()){
                //資料驗證錯誤
                return redirect('/user/auth/sing-in')
                ->withErrors($validator)
                ->withInput();
            }

            //啟用紀錄 SQL 語法
            DB::enableQueryLog();

            //撈取使用者資料
            $User = User::where('email', $input['email'])
                //->where('type', 'A')    //指定其他撈取條件
                ->firstOrfail();

            //列印出資料庫目前所有執行的 SQL 語法
            /*var_dump(DB::getQueryLog());

            exit;*/

            //檢查密碼是否正確
            $is_password_correct = Hash::check($input['password'], $User->password);

            if(!$is_password_correct){
                //密碼錯誤回傳錯誤訊息
                $error_massage = [
                    'msg' => [
                        '密碼驗證錯誤',
                    ],
                ];
                return redirect('/user/auth/sing-in')
                    ->withErrors($error_massage)
                    ->withInput();
            }

            //session 紀錄會員編號
            session()->put('user_id', $User->id);

            //重新導向回到原先使用者造訪網頁 沒有嘗試造訪頁則重新導向回首頁
            return redirect()->intended('/merchandise');
        }

        //處理登出資料
        public function singOut(){
            //清除 session
            session()->forget('user_id');

            //重新導向
            return redirect('/user/auth/sing-in');
        }

        //Facebook 登入
        public function facebookSingInProcess(){
            //$redirect_url = env('FB_REDIRECT');
            $redirect_url = 'http://localhost:8000/user/auth/facebook-sing-in-callback';

            return Socialite::driver('facebook')
                //->scoprs(['user_firends'])
                ->redirectUrl($redirect_url)
                ->redirect();
        }

        //Facebook 登入重新導向授權資料
        public function facebookSingInCallbackProcess(){
            if(request()->error == 'access_denied'){
                throw new Exception('授權失敗，存取錯誤');
            }
            //依照網域產出重新導向連結(來驗證是否為出發時通一callback)
            //$redirect_url = env('FB_REDIRECT');
            $redirect_url = 'http://localhost:8000/user/auth/facebook-sing-in-callback';
            //依照第三方使用者資料
            $FacebookUser=Socialite::driver('facebook')
            ->fields([
                'name',
                'email',
                'gender',
                'verified',
                'link',
                'first_name',
                'last_name',
                'locale',
            ])
            ->redirectUrl($redirect_url)->user();

            $facebook_email=$FacebookUser->email;

            if(is_null($facebook_email)){
                throw new Exception('未授權取得使用者 Email');
            }
            //取得FB資料
            $facebook_email=$FacebookUser->email;
            $facebook_id=$FacebookUser->id;
            $facebook_name=$FacebookUser->name;

            //取得使用者資料是否有此FB ID資料
            $User=User::where('facebook_id', $facebook_id)->first();

            if(is_null($User)){
                //沒有綁定FB ID 的帳號 透過email尋找是否有此帳號
                $User=User::where('email', $facebook_email)->first();
                if(!is_null($User)){
                    //沒有此帳號 綁定FB ID
                    $User->facebook_id=$facebook_id;
                    $User->save();
                }
            }

            if(!is_null($User)){
                //尚未註冊
                $input=[
                    'email' => $facebook_email, //Email
                    'nickname' => $facebook_name,    //暱稱
                    'password' => uniqid(), //隨機產生密碼
                    'facebook_id' => $facebook_id,  //FB ID
                    'type' => 'G',  //一般使用者
                ];
                //密碼加密
                $input['password']=Hash::make($input['password']);
                //新增會員資料
                $User=User::create($input);

                //寄送會員通知信

                //會員登入
                session()->put('user_id', $User->id);

                //重新導向原先使用者造訪網頁 沒有嘗試造訪業則重新導向回首頁
                return redirect()->intended('/');
            }
        }

        public function test(){
            return User::all();
        }
}