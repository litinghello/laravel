<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/5 0005
 * Time: 15:04
 */


return [

    /*
    |--------------------------------------------------------------------------
    | customized http code
    |--------------------------------------------------------------------------
    |
    | The first number is error type, the second and third number is
    | product type, and it is a specific error code from fourth to
    | sixth.But the success is different.
    |
    */

    'code' => [
        200 => '成功',
        2001 => '缺少必要的参数',
        2002 => '参数不正确',

        3001 => '该订单已在处理中',
        3002 => '该订单已缴费',


        9999 => '系统繁忙',
    ],

];