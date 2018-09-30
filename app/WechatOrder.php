<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WechatOrder extends Model
{
    //protected $connection = 'connection-name';//数据库连接
    protected $table = 'wechat_order';//数据表名

    protected $fillable = [
        'order_number',
        'order_money',
        'order_src_type',
        'order_src_id',
        'order_user_id',
        'order_phone_number',
        'order_status',
    ];
    //获取决定书编号相关的 决定书相关信息
    public function penalty_info(){
        return $this->belongsTo('App\PenaltyInfo', 'penalty_number', 'order_src_id');
    }
    //获取决定书编号绑定的 用户相关信息
    public function user_info(){
        return $this->belongsTo('App\User', 'id', 'order_user_id');
    }
}
