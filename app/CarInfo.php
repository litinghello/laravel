<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarInfo extends Model
{
    //protected $connection = 'connection-name';//数据库连接
    protected $table = 'car_info';//数据表名

    protected $fillable = [
        'userid',
        'car_type',
        'car_province',
        'car_number',
        'car_vin',
        'car_engine',
        'car_reserve',
    ];

}
