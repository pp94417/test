<?php

namespace App\Http\Controllers;

use App\Shop\Entity\Merchandise;
use App\Shop\Entity\Transaction;
use App\Shop\Entity\User;
use DB;
use Exception;
use Validator;
//use Image;

class MerchandiseController extends Controller {

    //新增商品
    public function merchandiseCreatProcess(){

        //建立商品基本資訊
        $merchandise_data = [
            'status'            =>  'C',    //建立中
            'name'              =>  'aaa',     //商品名稱
            'name_en'           =>  'aaa_en',     //商品英文名稱
            'introduction'      =>  'bbb',     //商品介紹
            'introduction_en'   =>  'bbb_ne',     //商品英文介紹
            'photo'             =>  null,   //商品照片
            'price'             =>  100,      //價格
            'remain_count'      =>  20,      //商品剩餘數量
        ];
        $Merchandise = Merchandise::create($merchandise_data);

        return redirect('/merchandise/'.$Merchandise->id.'/edit');
    }

    public function merchandiseItemEditPage($merchandise_id){
        //撈取商品資料
        $Merchandise = Merchandise::findOrfail($merchandise_id);    //findOrFail 以及 firstOrFail 方法會取回查詢的第一個結果。而如果沒有找到結果，將會拋出一個

        //變更圖片網址
        if(!is_null($Merchandise->photo)){
            //設定商品照片網址
            $Merchandise->photo = "http://images.shop-laravel.local/" . $Merchandise->photo;
        }

        $binding = [
            'title' => '編輯商品',
            'Merchandise' => $Merchandise,
        ];
        return view('merchandise.editMerchandise', $binding);
    }

    //商品資料更新處理
    public function merchandiseUpdateProcess($merchandise_id){
        //撈取商品資料
        $Merchandise = Merchandise::findOrfail($merchandise_id);
        //接收輸入資料
        $input = request()->all();

        //驗證規則
        $rules = [
            //商品狀態
            'status'=>[
                'required',
                'in:C,S'
            ],
            //商品名稱
            'name'=> [
                'required',
                'max:80',
            ],
            //商品英文名稱
             'name_en'=> [
                'required',
                'max:80',
            ],
            //商品介紹
            'introduction'=> [
                'required',
                'max:2000',
            ],
            //商品英文介紹
            'introduction_en'=> [
                'required',
                'max:2000',
            ],
            //商品照片
            'photo'=> [
                'file', //必須為檔案
                'image',    //必須為圖片
                'max:10240', //10 MB
            ],
            //商品價格
            'price'=> [
                'required',
                'integer',
                'min:0',
            ],
            //商品剩餘數量
            'remain_count'=> [
                'required',
                'integer',
                'min:0',
            ],
        ];
        
        //驗證資料
        $validator = Validator::make($input, $rules);

        
        if ($validator->fails()){
            //資料驗證錯誤
            return redirect('/merchandise/'.$Merchandise->id.'/edit')
            ->withErrors($validator)
            ->withInput();
        }

        if(isset($input['photo'])){
            //有上傳圖片
            $photo = $input['photo'];
            //檔案副檔名
            $file_extension = $photo->getClientE();
            //產生自訂隨機檔案名稱
            $file_name = uniqid().'.'.#file_extension;
            //檔案相對路徑
            $file_relative_path = 'image/merchandise/' . $file_name;
            //檔案存放目錄為對外公開 pulic 目錄下的相對位置
            $file_path = public_path($file_relative_path);
            //裁切圖片
            $image = Image::make($photo)->fit(450, 300)->save($file_path);
            //設定圖片檔案相對位置
            $input['photo'] = $file_relative_path;
        }

        $Merchandise->update($input);

        return redirect('/merchandise/'.$Merchandise->id.'/edit');
    }

    public function merchandiseManageListPage(){
        //每頁資料量
        $row_per_page=10;
        //撈取商品分頁資料  OrderBy('created_at', 'desc') 依照加入時間遞減排序 paginate 分頁
        $MerchandisePaginate=Merchandise::OrderBy('created_at', 'desc')->paginate($row_per_page);

        //設定
        foreach ($MerchandisePaginate as &$Merchandise){
            if (!is_null($Merchandise->photo)){
                //設定商品網址
                $Merchandise->photo=url($Merchandise->photo);
            }
        }

        $binding=[
            'title'=>'管理商品',
            'MerchandisePaginate'=>$MerchandisePaginate,
        ];

        return view('merchandise.manageMerchandise', $binding);
    }

    //商品清單顯示
    public function merchandiseListPage(){
         //每頁資料量
         $row_per_page=10;
         //撈取商品分頁資料
         $MerchandisePaginate=Merchandise::OrderBy('updated_at','desc')
            ->where('status', 'S')  //可販售
            ->paginate($row_per_page);

        //設定商品頁面圖片網址
        foreach ($MerchandisePaginate as $Merchandise){
            if(!is_null($Merchandise->photo)){
                //設定商品照片網址
                $Merchandise->photo=url($Merchandise->photo);
            }
        }

        $binding=[
            'title'=>'商品列表',
            "MerchandisePaginate"=>$MerchandisePaginate,
        ];
        
        return view('merchandise.listMerchandise', $binding);
    }

    //商品頁
    public function merchandiseItemPage($merchandise_id){
        //撈取資料
        $Merchandise = Merchandise::findOrFail($merchandise_id);

        if(!is_null($Merchandise->photo)){
            //設定商品照片網址
            $Merchandise->photo = url($Merchandise->photo);
        }

        $binding=[
            'title'=>'商品頁',
            'Merchandise'=>$Merchandise,
        ];
        
        return view('merchandise.showMerchandise', $binding);
    }

    //商品購買處理
    public function merchandiseItemBuyProcess($merchandise_id){
        //接收資料
        $input=request()->all();
        //驗證規則
        $rules=[
            //購買數量
            'buy_count'=>[
                'required',
                'integer',
                'min:1',
            ],
        ];

        //驗證資料
        $validator = Validator::make($input, $rules);

        
        if ($validator->fails()){
            //資料驗證錯誤
            return redirect('/merchandise/'.$merchandise_id)
            ->withErrors($validator)
            ->withInput();
        }

        try{
            //取得登入會員資料
            $user_id=session()->get('user_id');
            $User=User::findOrFail($user_id);

            //交易開始
            DB::beginTransaction();
            //取得商品資料
            $Merchandise=Merchandise::findOrFail($merchandise_id);

            //購買數量
            $buy_count=$input['buy_count'];
            //購買後剩餘數量
            $remain_count_after_buy=$Merchandise->remain_count - $buy_count;
            if($remain_count_after_buy<0){
                //購買後剩餘數量小於0 不足以賣給使用者
                throw new Exception('商品數量不足，無法購買');
            }
            //紀錄購買後剩餘數量
            $Merchandise->remain_count=$remain_count_after_buy;
            $Merchandise->save();   //商品資料更新

            //總金額:購買數量*商品價格
            $total_price=$buy_count*$Merchandise->price;

            $transaction_data=[
                'user_id'=>$User->id,
                'merchandise_id'=>$Merchandise->id,
                'price'=>$Merchandise->price,
                'buy_count'=>$buy_count,
                'total_price'=>$total_price,
            ];

            //建立交易資料
            Transaction::create($transaction_data);
            //交易結束
            DB::commit();

            //回傳購物成功訊息
            $message=[
                'msg'=>[
                    '購買成功',
                ],
            ];
            return redirect()
                ->to('/merchandise/'.$Merchandise->id)
                ->withErrors($message);
        }catch (Exception $exception){
            //恢復原先交易狀態
            DB::rollback(); //交易發生例外錯誤時 將使用者導回頁面 並顯示錯誤訊息

            //回傳錯誤訊息
            $error_message=[
                'msg'=>[
                    $exception->getMessage(),
                ],
            ];
            return redirect()
                ->back()
                ->withErrors($error_message)
                ->withInput();
        }
    }

}