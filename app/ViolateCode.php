<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViolateCode extends Model
{
    //protected $connection = 'connection-name';//数据库连接
    protected $table = 'violate_code';//数据表名

    protected $fillable = [
        'code',
        'content',
        'score',
        'money',
        'notification',
    ];
}
