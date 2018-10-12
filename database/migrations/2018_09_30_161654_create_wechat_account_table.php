<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWechatAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wechat_account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('wechat_id')->comment('账户openid');//
            $table->string('wechat_name')->comment('用户名称');//
            $table->string('wechat_nick_name')->comment('用户别名');//
            $table->string('wechat_email_account')->comment('绑定的邮箱');//
            $table->string('wechat_main_account')->comment('绑定的主账户');//
            $table->string('wechat_reserve')->comment('其它信息');//
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wechat_account');
    }
}
