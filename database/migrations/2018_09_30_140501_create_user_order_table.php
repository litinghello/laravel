<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number')->unique()->comment('订单编号');//订单编号
            $table->double('order_money')->default(0)->comment('订单金额');//订单金额
            $table->string('order_src_type')->comment('订单类型');//订单关联的类型
            $table->string('order_src_id')->comment('订单关联类型表的id');//订单关联的订单总单号
            $table->string('order_user_id')->comment('订单用户id');//订单用户id
            $table->string('order_phone_number')->comment('订单用户手机号码');//订单用户id
            $table->enum('order_status',['invalid','unpaid','paid','processing','completed'])->default('invalid')->comment('处理状态：无效、未支付、已支付、处理中、处理完成，默认：无效');//订单状态 未支付 支付 处理中 处理完成
            $table->nullableTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_order');
    }
}
