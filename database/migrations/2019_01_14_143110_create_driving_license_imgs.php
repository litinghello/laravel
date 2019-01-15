<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDrivingLicenseImgs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('driving_license_imgs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_src_id')->comment('订单关联类型表的id');//订单关联的订单总单号
            $table->string('img')->comment('行驶证图片');
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
        Schema::dropIfExists('driving_license_imgs');
    }
}
