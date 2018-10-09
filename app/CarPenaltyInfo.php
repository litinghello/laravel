<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarViolateInfo extends Model
{
    //protected $connection = 'connection-name';//数据库连接
    protected $table = 'car_violate_info';//数据表名

    protected $fillable = [
        'car_type',
        'car_province',
        'car_number',
        'violate_info',
        'violate_code',
        'violate_time',
        'violate_address',
        'violate_money',
        'violate_marks',
    ];
}
