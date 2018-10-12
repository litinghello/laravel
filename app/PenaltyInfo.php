<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PenaltyInfo extends Model
{
    //protected $connection = 'connection-name';//数据库连接
    protected $table = 'penalty_info';//数据表名

    protected $fillable = [
        'penalty_number',
        'penalty_car_number',
        'penalty_car_type',
        'penalty_money',
        'penalty_money_late',
        'penalty_user_name',
        'penalty_process_time',
        'penalty_illegal_time',
        'penalty_illegal_place',
        'penalty_behavior',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
//        'id',
    ];
    //获取决定书编号相关的 决定书相关信息
    public function order_info(){
        return $this->belongsTo('App\PenaltyOrder', 'order_penalty_number', 'penalty_number');
    }

}
