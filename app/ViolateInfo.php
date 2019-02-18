<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViolateInfo extends Model
{
    //protected $connection = 'connection-name';//数据库连接
    protected $table = 'violate_info';//数据表名

    protected $fillable = [
        'car_type',
        'car_province',
        'car_number',
        'car_frame_number',
        'violate_info',
        'violate_code',
        'violate_time',
        'violate_address',
        'violate_money',
        'violate_marks',
        'violate_status',
        'violate_pay',
        'violate_msg',
    ];
}
