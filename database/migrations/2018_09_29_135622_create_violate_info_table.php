<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateViolateInfoTable extends Migration
{
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('violate_info', function (Blueprint $table) {
            $table->increments('id');
            $table->string('car_type')->comment('号牌种类');//
            $table->string('car_province')->comment('车辆省份');//
            $table->string('car_number')->comment('号牌号码');//
            $table->string('car_frame_number')->comment('车架号后6位');//
            $table->string('violate_info')->comment('违章信息');//
            $table->string('violate_code')->comment('违章代码');//
            $table->string('violate_time')->comment('违章时间');//
            $table->string('violate_address')->comment('违章地点');//
            $table->double('violate_money')->comment('罚款金额(元)');//
            $table->double('violate_marks')->comment('扣分(仅供参考)');//
            $table->string('violate_msg')->comment('提示信息');//
            $table->string('violate_status')->comment('是否扣分');
            $table->string('violate_pay')->comment('是否缴纳罚款');//->nullable();
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
        Schema::dropIfExists('violate_info');
    }
}
