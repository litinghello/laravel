<?php

namespace App\Http\Controllers;

use Log;
use App\PenaltyInfo;
use App\WechatOrder;
use App\WechatAccount;
use App\User;
use GuzzleHttp\Cookie\json_decode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use EasyWeChat\Factory;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class WeChatsController extends Controller
{
    //用于只允许通过认证的用户访问指定的路由
    public function __construct()
    {
//        $this->middleware('auth');
    }
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
    public function back_token(){
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
                        "url"=>"http://www.cttx-zbx.com/",
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
                        "url"=>"http://www.cttx-zbx.com/",
                    ],
                    [
                        "type" => "view",
                        "name"=>"过户上户",
                        "url"=>"http://www.cttx-zbx.com/",
                    ],
                    [
                        "type" => "view",
                        "name"=>"事故查询",
                        "url"=>"http://www.cttx-zbx.com/",
                    ]
                ]
            ],
            [
                "name" => "个人信息",
                "sub_button"=>[
                    [
                        "type" => "view",
                        "name"=>"订单信息",
                        "url"=>"http://www.cttx-zbx.com/home",
                    ],
                    [
                        "type" => "view",
                        "name"=>"联系我们",
                        "url"=>"http://www.cttx-zbx.com/",
                    ]
                ]
            ]
        ];
        return $app->menu->create($buttons);
    }
    //微信支付
    public function wechat_pay(Request $request){
        $user = session('wechat.oauth_user'); //拿到授权用户资料
        if(session()->has('wechat.oauth_user') == false){
            return response()->json(['status' => 1,'data' => "请尝试通过微信访问支付"]);
        }
        $validator = Validator::make($request->all(), [
            'order_money' => 'required|numeric',
            'order_src_type' => 'required|alpha_num',
            'order_src_id' => 'required|alpha_num',
            'order_phone_number' => 'required|regex:/^1[34578]\d{9}$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 1,'data' => $validator->errors()->first()]);
        }
        $penalty_order = WechatOrder::where('order_src_id', $request['order_src_id'])->first();
        if ($penalty_order != null) {
            if ($penalty_order->order_status == "paid" || $penalty_order->order_status == "processing") {
                return response()->json(['status' => 1,'data' => "该订单已在处理中"]);
            } else if ($penalty_order->order_status == "completed") {
                return response()->json(['status' => 1,'data' => "该订单已经处理完成"]);
            }else if($penalty_order->order_user_id != Auth::id() ){
                return response()->json(['status' => 1,'data' => "该订单已被其他用户关联"]);
            }
        }else{
            $penalty_order = WechatOrder::create([
                'order_number'=> date("YmdHis") .'0'. rand(10000, 99999),
                'order_money'=> $request['order_money'],
                'order_src_type'=> $request['order_src_type'],
                'order_src_id'=> $request['order_src_id'],
                'order_phone_number'=> $request['order_phone_number'],
                'order_user_id'=> Auth::id(),
                'order_status'=> 'unpaid',
            ]);
        }

        $pay = Factory::payment(config('wechat.payment')['default']);
        $result = $pay->order->unify([
            'body' => '缴费',
            'out_trade_no' => $penalty_order->order_number,//传入订单ID
            'total_fee' => $penalty_order->order_money * 100, //因为是以分为单位，所以订单里面的金额乘以100
//            'total_fee' => 1, //因为是以分为单位，所以订单里面的金额乘以100
//            'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
//            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI',
            'openid' =>  $user['default']['id'],//TODO: 用户openid
//            'openid' =>  "oiGyj0im2uCtxHX3_oFct-BDyOuA",//TODO: 用户openid
        ]);
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
//            $config = $pay->jssdk->bridgeConfig($result['prepay_id'],false); //WeixinJSBridge支付 返回 json 字符串，如果想返回数组，传第二个参数 false
            $config = $pay->jssdk->sdkConfig($result['prepay_id']); //JSSDK支付 返回数组
            //$configForPickAddress = $pay->jssdk->shareAddressConfig($token);//生成共享收货地址 JS 配置
            //$config = $pay->jssdk->appConfig($result['prepay_id']);
            return response()->json(['status' => 0,'data' => $config]);
        } else {
            return response()->json(['status' => 1,'data' => "微信支付异常"]);
        }
    }
    //微信回调
    public function wechat_paycall(Request $request){
//        $xml = file_get_contents("php://input");
//        Log::info($request.$xml);
//        $options = config('wechat.payment');
        $app = Factory::payment(config('wechat.payment')['default']);
        $response = $app->handlePaidNotify(function($message, $fail){
            Log::info( 'message:'.json_encode($message));
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = PenaltyOrder::where('order_number', $message['out_trade_no'])->first();
            if (!$order) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            $app = Factory::payment(config('wechat.payment')['default']);

            if ($message['return_code'] === 'SUCCESS' ) { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $out = $app->order->queryByOutTradeNumber($message['out_trade_no']);
                    if($out['return_code'] === 'SUCCESS' && $out['result_code'] === 'SUCCESS' && $out['trade_state'] === 'SUCCESS' ){
                        if( $out['total_fee'] == $order->order_money*100) {
                            $order->order_status = 'paid';
                        }else{
                            Log::info("  out:".json_encode($out));
                            return true; // 订单不对，别再通知我了
                        }
                    }else{
                        return $fail('通信失败，请稍后再通知我');
                    }
                    // 用户支付失败
                } elseif (array_get($message, 'result_code') === 'FAIL') {
                    $order->status = 'unpaid';
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }
            $order->save(); // 保存订单
            return true; // 返回处理完成
        });
        $response->send(); // return $response;
    }

}