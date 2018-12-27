<?php

namespace App\Http\Controllers;

use Log;
use App\PenaltyInfo;
use App\UserOrderInfo;
use App\WechatAccount;
use App\User;
use GuzzleHttp\Cookie\json_decode;
use Yajra\Datatables\Datatables;
use SimpleSoftwareIO\QrCode\BaconQrCodeGenerator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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
//                    return '收到事件消息';
                    break;
                case 'text':
                    //new Text('您好！');
//                    return '收到文字消息';
                    break;
                case 'image':
//                    return '收到图片消息';
                    break;
                case 'voice':
//                    return '收到语音消息';
                    break;
                case 'video':
//                    return '收到视频消息';
                    break;
                case 'location':
                    /*
                    $message->MsgType     location
                    $message->Location_X  地理位置纬度
                    $message->Location_Y  地理位置经度
                    $message->Scale       地图缩放大小
                    $message->Label       地理位置信息
                    */
//                    return '收到坐标消息';
                    break;
                case 'link':
//                    return '收到链接消息';
                    break;
                case 'file':
//                    return '收到文件消息';
                // ... 其它消息
                default:
//                    return '收到其它消息';
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
                        "url"=>"http://www.weizhangxiaoxiao.com/violates/inquire",
                    ],
                    [
                        "type" => "view",
                        "name"=>"罚款缴纳",
                        "url"=>"http://www.weizhangxiaoxiao.com/penalties/inquire",
                    ]
                ]
            ],
            [
                "name" => "汽车业务",
                "sub_button"=>[
                    [
                        "type" => "view",
                        "name"=>"汽车审验",
                        "url"=>"http://www.weizhangxiaoxiao.com/",
                    ],
                    [
                        "type" => "view",
                        "name"=>"过户上户",
                        "url"=>"http://www.weizhangxiaoxiao.com/",
                    ],
                    [
                        "type" => "view",
                        "name"=>"事故查询",
                        "url"=>"http://www.weizhangxiaoxiao.com/",
                    ]
                ]
            ],
            [
                "name" => "个人信息",
                "sub_button"=>[
                    [
                        "type" => "view",
                        "name"=>"订单信息",
                        "url"=>"http://www.weizhangxiaoxiao.com/",
                    ],
                    [
                        "type" => "view",
                        "name"=>"联系我们",
                        "url"=>"http://www.weizhangxiaoxiao.com/contact/us",
                    ]
                ]
            ]
        ];
        return $app->menu->create($buttons);
    }

    //发消息给指定的人员
    public function send_message_to_server_wechat(Request $request)
    {
        $order = UserOrderInfo::where('order_number', '20181101180000074571')->first();
//        $rest = $this->send_message_to_server_paid($order->user_info->name, '缴费成功', $order->order_money, '已完成');
        if($order->user_info->user_wechat != null){
             $this->send_message_to_user_paid($order->user_info->user_wechat->wechat_id,$order->order_number,$order->order_money,'0');
//            $rest = $this->send_message_to_user_paid("oiGyj0im2uCtxHX3_oFct-BDyOuA",$order->order_number,$order->order_money,'0');
        }
        return response()->json(['status' => 1, 'data' => "ok"]);
    }

    //发消息给指定客服的人员
    public function send_message_to_server_paid($name, $bz, $order_money, $remark)
    {
        $app = app('wechat.official_account');
        $serverUser = ["oIIzd55p7UnPrvJRZ8SQ53nbBLlk", "oiGyj0vmN3G2pLa2PRNkZUa2aXbA"];
        foreach ($serverUser as $user) {
            $app->template_message->send([
                'touser' => $user,
                'template_id' => '9qcIi1wu2OZGH8FLmgmN1_p8tnwteD1JCBDBf4zjxF4',
                'url' => 'https://easywechat.org',
                'data' => [
                    'first' => '通知',
                    'keyword1' => $name,
                    'keyword2' => date('Y-m-d H:i:s'),
                    'keyword3' => '罚款缴费',
                    'keyword4' => $order_money,
                    'keyword5' => $bz,
                    'remark' => $remark,
                ],
            ]);
        }
    }

    /**发送付款成功消息给用户
     * @param $useropenid
     * @param $orderid
     * @param $order_money
     * @param $order_money_discounts 优惠金额
     */
    public function send_message_to_user_paid($useropenid,$orderid,  $order_money,$order_money_discounts)
    {
        $app = app('wechat.official_account');
//        $serverUser = ["oiGyj0gWZdBklqN79Rmq8MS9cRq4", "oiGyj0vmN3G2pLa2PRNkZUa2aXbA", "oiGyj0im2uCtxHX3_oFct-BDyOuA"];
//        foreach ($serverUser as $user) {
            $app->template_message->send([
                'touser' => $useropenid,
                'template_id' => 'TXyFUPqrBzQDf33dCh49nezHr3RdLQrP7VrrodKejF0',
                'url' => 'http://www.cttx-zbx.com/order/get',
                'data' => [
                    'first' => '付款成功提醒',
                    'keyword1' => $orderid,
                    'keyword2' => '￥'.$order_money.'元',
                    'keyword3' => '￥'.$order_money_discounts.'元',
                    'keyword4' => '移动支付',
                    'keyword5' => date('Y-m-d H:i'),
                    'remark' => '详情请登录后台查看',
                ],
            ]);
//        }
    }

    //通过微信支付
    public function order_pay_wechat(Request $request){

        $validator = Validator::make($request->all(), [
            'order_money' => 'required|numeric',
            'order_number' => 'required|alpha_num',
            'order_src_type' => 'required|alpha_num',
            'order_src_id' => 'required|alpha_num',
            'order_phone_number' => 'required|regex:/^1[34578]\d{9}$/',
            'wechat_pay_type' => 'required|in:JSAPI,NATIVE,APP',
            'wechat_pay_limit' => 'required|in:true,false'
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 1, 'data' => $validator->errors()->first()]);
        }
//        return response()->json(['status' => 1,'data' => "111"]);

        $user_id = null;
        if ($request['wechat_pay_limit'] == 'true') {//如果指定支付唯一账户那么需要进行账户获取
            if (session('wechat.oauth_user') == null) {
                if (Auth::check()) {
                    if (Auth::user()->user_wechat != null) {
                        $user_id = Auth::user()->user_wechat->wechat_id;
                    } else {
                        return response()->json(['status' => 1, 'data' => "未绑定微信账户，请先绑定微信。"]);
                    }
                } else {
                    return response()->json(['status' => 1, 'data' => "请登录再尝试。"]);
                }
            } else {
                $user_id = session('wechat.oauth_user')['default']['id'];
            }
        }
        $user_order = UserOrderInfo::where('order_number', $request['order_number'])->first();
        if ($user_order == null) {//检查订单是否存在
            return response()->json(['status' => 1, 'data' => "此订单不存在，请联系客服。"]);
        }
        if ($user_order->order_money != $request['order_money'] || $user_order->order_src_type != $request['order_src_type']
            || $user_order->order_src_id != $request['order_src_id'] || $user_order->order_phone_number != $request['order_phone_number']
        ) {//检查订单信息是否正确
            return response()->json(['status' => 1, 'data' => "订单信息有误"]);
        }
        if ($user_order->order_status != 'unpaid') {//检查是否支付完成
            return response()->json(['status' => 1, 'data' => "此订单已正在处理中。"]);
        }
        $pay = Factory::payment(config('wechat.payment')['default']);
        $result = $pay->order->unify([
            'body' => '代办付款',
            'out_trade_no' => $user_order->order_number,//传入订单ID
            'total_fee' => $user_order->order_money * 100, //因为是以分为单位，所以订单里面的金额乘以100
            'trade_type' => $request['wechat_pay_type'], // JSAPI，NATIVE，APP...
            'openid' => $user_id,//TODO: 用户openid ex "oiGyj0im2uCtxHX3_oFct-BDyOuA"
        ]);
        if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
//            $config = $pay->jssdk->bridgeConfig($result['prepay_id'],false); //WeixinJSBridge支付 返回 json 字符串，如果想返回数组，传第二个参数 false
//            $config = $pay->jssdk->sdkConfig($result['prepay_id']); //JSSDK支付 返回数组
//            $config = $pay->jssdk->appConfig($result['prepay_id']); //APP支付
//            return response()->json(['status' => 0,'data' => $config]);
//            return response()->json(['status' => 0,'data' => $result->code_url]);//二维码支付链接
            switch ($request['wechat_pay_type']) {
                case 'JSAPI':
                    return response()->json(['status' => 0, 'data' => $pay->jssdk->sdkConfig($result['prepay_id'])]);
                case 'APP':
                    return response()->json(['status' => 0, 'data' => $pay->jssdk->appConfig($result['prepay_id'])]);
                case 'NATIVE':
//                    return response()->json(['status' => 0,'data' => $result->code_url]);
                    return response()->json(['status' => 0, 'data' => (new BaconQrCodeGenerator)->size(200)->generate($result['code_url'])]);
                default:
                    return response()->json(['status' => 1, 'data' => "暂时不支持支付"]);
            }
        } else {
            return response()->json(['status' => 1, 'data' => $result['err_code_des']]);
        }
    }

    //微信回调
    public function wechat_paycall(Request $request)
    {
        $app = Factory::payment(config('wechat.payment')['default']);
        $response = $app->handlePaidNotify(function ($message, $fail) {
            Log::info('message:' . json_encode($message));
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = UserOrderInfo::where('order_number', $message['out_trade_no'])->first();
            if (!$order) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            ///////////// <- 建议在这里调用微信的【订单查询】接口查一下该笔订单的情况，确认是已经支付 /////////////
            $app = Factory::payment(config('wechat.payment')['default']);

            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (array_get($message, 'result_code') === 'SUCCESS') {
                    $out = $app->order->queryByOutTradeNumber($message['out_trade_no']);
                    if ($out['return_code'] === 'SUCCESS' && $out['result_code'] === 'SUCCESS' && $out['trade_state'] === 'SUCCESS') {
                        if ($out['total_fee'] == $order->order_money * 100) {
                            $order->order_status = 'paid';
                            $this->send_message_to_server_paid($order->user_info->name, '缴费成功', $order->order_money, '已完成');
                            if($order->user_info->user_wechat != null){
                                $this->send_message_to_user_paid($order->user_info->user_wechat->wechat_id,$order->order_number,$order->order_money,'0');
                            }
                        } else {
                            Log::info("  out:" . json_encode($out));
                            return true; // 订单不对，别再通知我了
                        }
                    } else {
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

    //设置订单为处理中
    public function wechat_set_user_order(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'order_number' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 1, 'data' => $validator->errors()->first()]);
        }
        $order = UserOrderInfo::where('order_number', $request['order_number'])->first();
        if (!$order) { // 如果订单不存在 或者 订单已经支付过了
            return response()->json(['status' => 1, 'data' => "订单不存在"]);
        }
        $app = Factory::payment(config('wechat.payment')['default']);
        $result = $app->order->queryByOutTradeNumber($request['order_number']);
        if (($result['return_code'] == 'success') && ($result['return_msg'] == 'ok') && ($result['result_code'] == 'success')) {
            switch ($result['trade_state']) {
                case 'SUCCESS':
                    if ($order->order_status == 'paying') {
                        $order->order_status = 'paid';
                        $order->save();
                    }
                    return response()->json(['status' => 1, 'data' => "支付成功"]);
                    break;
                case 'NOTPAY':
                    if ($order->order_status == 'paying') {
                        $order->order_status = 'unpaid';
                        $order->save();
                    }
                    return response()->json(['status' => 1, 'data' => "未支付"]);
                    break;
                case 'CLOSED':
                    if ($order->order_status == 'paying') {
                        $order->order_status = 'invalid';
                        $order->save();
                    }
                    return response()->json(['status' => 1, 'data' => "支付关闭"]);
                    break;
                case 'USERPAYING':
                    return response()->json(['status' => 1, 'data' => "正在支付"]);
                    break;
                case 'PAYERROR':
                    if ($order->order_status == 'paying') {
                        $order->order_status = 'invalid';
                        $order->save();
                    }
                    return response()->json(['status' => 1, 'data' => "支付故障"]);
                    break;
                default:
                    break;
            }
        }
        return response()->json(['status' => 1, 'data' => "微信支付异常"]);
    }

    function wechat_get_share_config()
    {
        $app = app('wechat.official_account');
        $app->jssdk->setUrl("http://www.cttx-zbx.com/contact/us");
        return response()->json(['status' => 0, 'data' => $app->jssdk->buildConfig(array('updateAppMessageShareData', 'updateTimelineShareData', 'onMenuShareTimeline'))]);
    }

    function test(){
        return file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxf76823239c5e6688&secret=9e98d6513da0cb6fb29d1098d0cd1fba");
    }
}