<?php
/**
 * Created by PhpStorm.
 * User: pompy
 * Date: 2019/1/11
 * Time: 5:06 PM
 */


namespace App\Http\Controllers;

use Illuminate\Http\Request;


class Plugs extends Controller
{
    public function upload(Request $request)
    {

// 设置超时时间为没有限制
        ini_set("max_execution_time", "0");

        $file = $request->file('file');

        $allowed_extensions = ["png", "jpg", "gif", "jpeg", "bmp"];

        if ($file->getClientOriginalExtension() && !in_array(strtolower($file->getClientOriginalExtension()), $allowed_extensions)) {
            return response()->json(['code' => 'ERROR', 'msg' => '文件上传类型受限']);
        }

        $destinationPath = 'storage/uploads/'.date('Ymd').'/'; //public 文件夹下面建 storage/uploads 文件夹
        $extension = $file->getClientOriginalExtension();
        $fileName = md5(microtime(true)).'.'.$extension;
        $file->move($destinationPath, $fileName);

        $appRoot = request()->root(true);  // 去掉参数 true 将获得相对地址
        $uriRoot = preg_match('/\.php$/', $appRoot) ? dirname($appRoot) : $appRoot;
        $uriRoot = in_array($uriRoot, ['/', '\\']) ? '' : $uriRoot;

        return response()->json(['type' => $extension , 'url' => $uriRoot .'/'.$destinationPath.'/'.$fileName , 'name' => $fileName,'code' => 'SUCCESS', 'msg' => '文件上传成功']);

    }

    public function upstate(Request $request)
    {

        $appRoot = request()->root(true);  // 去掉参数 true 将获得相对地址
        $uriRoot = preg_match('/\.php$/', $appRoot) ? dirname($appRoot) : $appRoot;
        $uriRoot = in_array($uriRoot, ['/', '\\']) ? '' : $uriRoot;

        // 需要上传文件，生成上传配置参数
        $config['server'] = $uriRoot;
        $config['code']='NOT_FOUND';
        return response()->json(array('data'=>$config,'code'=>'NOT_FOUND'));
    }


}