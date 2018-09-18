<?php

namespace App\Http\Controllers;

//use Log;

class WeChatsController extends Controller
{
    public function wechat_oauth(){
        $app = app('wechat.official_account');
        $response = $app->oauth->scopes(['snsapi_userinfo'])->redirect();
        //回调后获取user时也要设置$request对象
        //$user = $app->oauth->setRequest($request)->user();
        return $response;
        /*
        获取已授权用户
        $user = $app->oauth->user();
        // $user 可以用的方法:
        // $user->getId();  // 对应微信的 OPENID
        // $user->getNickname(); // 对应微信的 nickname
        // $user->getName(); // 对应微信的 nickname
        // $user->getAvatar(); // 头像网址
        // $user->getOriginal(); // 原始API返回的结果
        // $user->getToken(); // access_token， 比如用于地址共享时使用
        */
    }
    //登录授权
    public function login_auth(){
        $user = session('wechat.oauth_user'); //拿到授权用户资料
//        dd($user);
        return $user;
    }
    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function back_token()
    {
        //Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志

        $app = app('wechat.official_account');
        //$message = $server->getMessage();//另外一种获取消息
        $app->server->push(function($message){
            //return "欢迎使用，内测阶段！";
            /*
            $message['ToUserName']    接收方帐号（该公众号 ID）
            $message['FromUserName']  发送方帐号（OpenID, 代表用户的唯一标识）
            $message['CreateTime']    消息创建时间（时间戳）
            $message['MsgId']         消息 ID（64位整型）
            */
            switch ($message['MsgType']) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    //new Text('您好！');
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    /*
                    $message->MsgType     location
                    $message->Location_X  地理位置纬度
                    $message->Location_Y  地理位置经度
                    $message->Scale       地图缩放大小
                    $message->Label       地理位置信息
                    */
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                case 'file':
                    return '收到文件消息';
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        return $app->server->serve();
    }
    //创建菜单
    public function create_menu(){
        $app = app('wechat.official_account');
        $buttons = [
            [
                "name" => "违章业务",
                "sub_button"=>[
                    [
                        "type" => "view",
                        "name"=>"违章处理",
                        "url"=>"http://www.soso.com/",
                    ],
                    [
                        "type" => "view",
                        "name"=>"罚款缴纳",
                        "url"=>"http://www.soso.com/",
                    ]
                ]
            ],
            [
                "name" => "汽车业务",
                "sub_button"=>[
                    [
                        "type" => "view",
                        "name"=>"汽车审验",
                        "url"=>"http://www.soso.com/",
                    ],
                    [
                        "type" => "view",
                        "name"=>"过户上户",
                        "url"=>"http://www.soso.com/",
                    ],
                    [
                        "type" => "view",
                        "name"=>"事故查询",
                        "url"=>"http://www.soso.com/",
                    ]
                ]
            ],
            [
                "name" => "个人信息",
                "sub_button"=>[
                    [
                        "type" => "view",
                        "name"=>"订单进度",
                        "url"=>"http://www.soso.com/",
                    ],
                    [
                        "type" => "view",
                        "name"=>"联系我们",
                        "url"=>"http://www.soso.com/",
                    ]
                ]
            ]
        ];
        return $app->menu->create($buttons);
    }

}