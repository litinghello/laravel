<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenaltyOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalty_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_number')->unique()->comment('订单编号');//订单编号
            $table->double('order_money')->default(0)->comment('订单金额');//订单金额
            $table->string('order_penalty_number')->comment('订单关联的决定书编号');//订单关联的决定书编号
            $table->string('order_user_id')->comment('订单用户id');//订单用户id
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
        Schema::dropIfExists('penalty_order');
    }
}
