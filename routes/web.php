<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//首頁
Route::get('/', 'Controller@indexPage');

//使用者
Route::group(['prefix' => 'user'], function(){
    //使用者驗證
    Route::group(['prefix' => 'auth'], function(){
        //使用者註冊頁面
        Route::get('/sing-up', 'UserAuthController@singUpPage');
        //使用者資料新增
        Route::post('/sing-up', 'UserAuthController@singUpProcess');
        //使用者登入頁面
        Route::get('/sing-in', 'UserAuthController@singInPage');
        //使用者登入處理
        Route::post('/sing-in', 'UserAuthController@singInProcess');
        //使用者登出
        Route::get('/sing-out', 'UserAuthController@singOut');
        //Facebook 登入
        Route::get('/facebook-sing-in', 'UserAuthController@facebookSingInProcess');
        //Facebook 登入重新導向授權資料處理
        Route::get('/facebook-sing-in-callback', 'UserAuthController@facebookSingInCallbackProcess');
    });
});


//商品
Route::group(['prefix' => 'merchandise'], function(){
    //商品檢視清單
    Route::get('/', 'MerchandiseController@merchandiseListPage');
    //商品資料新增
    Route::get('/create', 'MerchandiseController@merchandiseCreatProcess');//->middleware(['user.auth.admin']);
    //商品管理清單顯示
    Route::get('/manage', 'MerchandiseController@merchandiseManageListPage');//->middleware(['user.auth.admin']);

    //指定商品
    Route::group(['prefix' => '{merchandise_id}'], function(){
        //Route::group(['middleware' => ['user.auth.admin']], function(){
            //商品單品編輯頁面顯示
            Route::get('/edit', 'MerchandiseController@merchandiseItemEditPage');
            //商品單品資料修改
            Route::put('/', 'MerchandiseController@merchandiseUpdateProcess');
        //});
        //商品單品檢視
        Route::get('/', 'MerchandiseController@merchandiseItemPage');
        //購買商品
        Route::get('/buy', 'MerchandiseController@merchandiseItemBuyProcess');//->middleware(['user.auth']);
    });
});


//交易
Route::get('/transaction', 'TransactionController@transactionListPage');//->middleware(['user.auth']);
