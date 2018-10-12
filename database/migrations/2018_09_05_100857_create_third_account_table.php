<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateThirdAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('third_account', function (Blueprint $table) {
            $table->increments('id');
            $table->string('account_type')->comment('账户类型');//
            $table->string('account_name')->comment('账户名称');//
            $table->string('account_password')->comment('账户密码');//
            $table->string('account_status')->comment('账户状态');//
            $table->string('account_cookie',4096)->comment('账户cookie');//
            $table->string('account_reserve')->comment('其它信息');//
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
        Schema::dropIfExists('third_account');
    }
}
