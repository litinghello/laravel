<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenaltyInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penalty_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('penalty_number')->unique()->comment('决定书编号');//决定书编号
            $table->string('penalty_car_number')->comment('罚款车辆');//罚款车辆
            $table->string('penalty_car_type')->comment('罚款车辆类型');//罚款车辆类型
            $table->double('penalty_money')->comment('罚款金额');//罚款金额
            $table->double('penalty_money_late')->comment('罚款滞纳金');//罚款滞纳金
            $table->string('penalty_user_name')->comment('罚款人姓名');//罚款人姓名
            $table->string('penalty_process_time')->nullable()->comment('罚款处理时间');//罚款处理时间
            $table->string('penalty_illegal_time')->nullable()->comment('罚款违法时间');//罚款违法时间
            $table->string('penalty_illegal_place')->comment('罚款违法地点');//罚款违法地点
            $table->string('penalty_behavior')->comment('罚款违法行为');//罚款违法行为
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
        Schema::dropIfExists('penalty_info');
    }
}
