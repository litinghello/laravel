<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','authorize',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    //获取用户相关的的决定书订单
    public function user_order(){
        //第一参数关联的模型名称 第二参数关联模型的键值  第三参数本模型的关联值
        return $this->hasMany('App\UserOrderInfo', 'order_user_id','id');
//        return $this->hasOne('App\PenaltyOrder', 'order_user_id');
    }

}
