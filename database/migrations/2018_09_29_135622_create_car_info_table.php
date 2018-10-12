<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('userid')->comment('用户id');//
            $table->string('car_type')->comment('号牌种类');//
            $table->string('car_province')->comment('车辆省份');//
            $table->string('car_number')->comment('号牌号码');//
            $table->string('car_vin')->comment('车架号后六位');//
            $table->string('car_engine')->comment('发动机号后六位');//
            $table->string('car_reserve')->comment('其它信息');//
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
        Schema::dropIfExists('car_info');
    }
}
