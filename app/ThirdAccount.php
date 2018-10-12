<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThirdAccount extends Model
{
    //protected $connection = 'connection-name';//数据库连接
    protected $table = 'third_account';//数据表名

    protected $fillable = [
        'account_type',
        'account_name',
        'account_password',
        'account_status',
        'account_cookie',
        'account_reserve',
    ];
}
