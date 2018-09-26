<?php

namespace App\Http\Controllers;

//use Log;

use App\PenaltyInfo;
use App\PenaltyOrder;
use GuzzleHttp\Cookie\json_decode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use EasyWeChat\Factory;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

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
                        "url"=>"http://www.cttx-zbx.com/penalties/inquire",
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




    public function penalty_pay(Request $request)
    {

        $user = session('wechat.oauth_user'); //拿到授权用户资料
//        return $user['default']['id'];

        $validator = Validator::make($request->all(), [
            'penalty_number' => 'required|alpha_num|between:15,16',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $penalty_number = $request['penalty_number'];
        // 查询数据库
        $pnaltyinfo = PenaltyInfo::where('penalty_number', $penalty_number)->first();
        if ($pnaltyinfo == null) {
            return back()->withErrors(['penalty_number' => '系统异常']);
        }

        //计算订单金额
        $valid_ddje = $pnaltyinfo->penalty_money;
        $valid_ddje += 10;//TODO:服务费
        $valid_ddje += ($pnaltyinfo->penalty_money_late);

        //自动生成，订单编号
        $sj = rand(10000, 99999);
        $order_number = date("YmdHis") . '0' . $sj;

        $config = config('wechat.official_account')['default'];
        $appAccount = Factory::officialAccount($config);
        $penaltyorder = PenaltyOrder::where('order_penalty_number', $penalty_number)->first();
        if ($penaltyorder != null) {
            $order_status = $penaltyorder->order_status;
            if ($order_status == "paid" || $order_status == "processing") {
                return back()->withErrors(['penalty_number' => '该违法已在处理中...']);
            } else if ($order_status == "completed") {
                return back()->withErrors(['penalty_number' => '该违法已处理']);
            }
            //修改订单金额
            $penaltyorder->order_money = $valid_ddje;
            //修改订单用户
//            $penaltyorder->order_user_id = Auth::id();//TODO: 用户id
            $penaltyorder->order_user_id = 123;//TODO: 用户id
            $penaltyorder->save();
        } else {
            $penaltyorder = new PenaltyOrder;
            $penaltyorder->order_number = $order_number;
            $penaltyorder->order_money = $valid_ddje;
            $penaltyorder->order_penalty_number = $penalty_number;
//            $penaltyorder->order_user_id = Auth::id();//TODO: 用户id
            $penaltyorder->order_user_id = 123;//TODO: 用户id
            $penaltyorder->order_status = "unpaid";
            $penaltyorder->save();
        }

        $options = config('wechat.payment')['default'];
        $app = Factory::payment($options);
        $result = $app->order->unify([
            'body' => 'ceshi',
            'out_trade_no' => $penaltyorder->order_number,//传入订单ID
            'total_fee' =>  '100', //TODO:测试先用1分
//            'total_fee' => $penaltyorder->order_money * 100, //因为是以分为单位，所以订单里面的金额乘以100

//            'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
//            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI',
            'openid' =>  $user['default']['id'],//TODO: 用户openid
        ]);
//        return $result;
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
            $prepayId = $result['prepay_id'];
            $jssdk = $app->jssdk;
            $config = $jssdk->bridgeConfig($prepayId); // // 返回 json 字符串，如果想返回数组，传第二个参数 false
//            return  json_encode($config);
            return redirect('/penalties/pay_order')->with('config',$config);
        } else {
            return back()->withErrors(['penalty_number' => '微信支付异常']);
        }


    }

    //下面是回调函数
    public function paycall()
    {
        $options = config('wechat.payment');
        $app = new Application($options);
        $response = $app->payment->handleNotify(function ($notify, $successful) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
//            $order = ExampleOrder::where('out_trade_no', $notify->out_trade_no)->first();
            $order = PenaltyOrder::where('order_number', $notify->out_trade_no)->first();
            if (count($order) == 0) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
//            if ($order->pay_time) { // 假设订单字段“支付时间”不为空代表已经支付
//                return true; // 已经支付成功了就不再更新了
//            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
//                $order->pay_time = time(); // 更新支付时间为当前时间
//                $order->status = 6; //支付成功,
                $order->order_status = 'paid'; //支付成功,
            } else { // 用户支付失败
                $order->order_status = 'unpaid'; //待付款
            }
            $order->save(); // 保存订单
            return true; // 返回处理完成
        });
    }

}