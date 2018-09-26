<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WechatAccount extends Model
{
    //protected $connection = 'connection-name';//数据库连接
    protected $table = 'wechat_account';//数据表名

    protected $fillable = [
        'wechat_id',
        'wechat_name',
        'wechat_nick_name',
        'wechat_email_account',
        'wechat_main_account',
        'wechat_reserve',
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
        return $this->belongsTo('App\User', 'wechat_account', 'wechat_main_account');
    }

}
