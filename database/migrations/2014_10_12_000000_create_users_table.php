<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    //執行資料庫異動
    public function up()
    {
        //建立資料表
        Schema::create('users', function (Blueprint $table) {
            //會員編號
            $table->increments('id');   //主鍵
            //Email
            $table->string('email', 150);
            //密碼
            $table->string('password', 60);
            //帳號類型(type):用於識別登入會員身分
            // - A (Admin):管理者
            // - G (General):一般會員
            $table->string('type', 1)->default('G'); //帳號狀態
            //暱稱
            $table->string('nickname', 50);
            //時間戳記
            $table->timestamps();

            /*$table->dataTime('created_at');
            $table->dataTime('updated_at');
            //唯一值鍵值索引
            $table->unique(['email'], 'user_email_uk');
            $table->unique(['user_id', 'transaction_id'], 'user_transactions');
            //主鍵索引值
            $table->primary(['id'], 'user_pk');
            //索引值
            $table->index(['nickname'], 'user_nickname_idx');*/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    //恢復資料庫異動
    public function down()
    {
        //移除資料表
        Schema::dropIfExists('users');
    }
}
