<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DrivingLicenseImgs extends Model
{
    //
    protected $table = 'driving_license_imgs';//数据表名

    protected $fillable = [
        'order_src_id',
        'img',
    ];
}
