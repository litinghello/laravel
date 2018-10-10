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

        return $this->hasOne('App\PenaltyInfo', 'penalty_number', 'order_src_id');
    }
    //获取决定书编号绑定的 用户相关信息
    public function user_info(){
        return $this->hasOne('App\User', 'id', 'order_user_id');
    }
    //获取一条订单的详情
    public static function get_one_order_by_order_id($order_src_id)
    {
        return WechatOrder::where('order_src_id', $order_src_id)->first()->penalty_info;
    }

}
