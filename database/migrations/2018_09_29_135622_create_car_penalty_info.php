<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarPenaltyInfo extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('car_penalty_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('car_type')->comment('号牌种类');//
            $table->string('car_province')->comment('车辆省份');//
            $table->string('car_number')->comment('号牌号码');//
            $table->string('penalty_info')->comment('违章信息');//
            $table->string('penalty_code')->comment('违章代码');//
            $table->string('penalty_time')->comment('违章时间');//
            $table->string('penalty_address')->comment('违章地点');//
            $table->string('penalty_money')->comment('罚款金额(元)');//
            $table->string('penalty_marks')->comment('扣分(仅供参考)');//
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
        Schema::dropIfExists('car_penalty_info');
    }
}
