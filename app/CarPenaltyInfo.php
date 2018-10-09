<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CarPenaltyInfo extends Model
{
    //protected $connection = 'connection-name';//数据库连接
    protected $table = 'car_penalty_info';//数据表名

    protected $fillable = [
        'car_type',
        'car_province',
        'car_number',
        'penalty_info',
        'penalty_code',
        'penalty_time',
        'penalty_address',
        'penalty_money',
        'penalty_marks',
    ];
}
