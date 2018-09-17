<?php

namespace App\Http\Controllers;

use App\PenaltyInfo;
use App\PenaltyOrder;
use App\ThirdAccount;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\json_decode;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use EasyWeChat\Payment\Order;

class PenaltiesController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //用于只允许通过认证的用户访问指定的路由
    public function __construct()
    {
        $this->middleware('auth');
    }
    //添加第三方账户
    //http://localhost/laravel/penalties/account/add?account_type=51jfk&account_name=123456&account_password=123456
    public function add_third_account(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_type' => 'required|alpha_num',
            'account_name' => 'required|alpha_num',
            'account_password' => 'required|alpha_num'
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        switch ($request['account_type']) {
            case "51jfk":
                return redirect()->route('penalties.login.51jfk', ['account_name' => $request['account_name'], 'account_password' => $request['account_password'], 'account_type' => $request['account_type']]);
                break;
            default:
                return "账号类型设置错误";
                break;
        }
    }

    //登录第三方账户
    public function login_51jfk_account(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'account_type' => 'required|alpha_num|in:51jfk',
            'account_name' => 'required|alpha_num',
            'account_password' => 'required|alpha_num',
        ]);
        if ($validator->fails()) {
            return $validator->errors()->first();
        }
        //1.获取图片
        $client = new Client();//
        //$client = new Client(['cookies' => true]);// 可开启共享
        $jar = new CookieJar();
        $server_addr = "http://www.51jfk.com/index.php/Index/verify/";//获取验证图片
        $response = $client->get($server_addr, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Cookie' => ''
            ],
            'cookies' => $jar //读取cookie
        ]);
        $cookies_str = "";
        foreach ($jar->getIterator() as $item) {
            $cookies_str = $cookies_str . $item->getName() . "=" . $item->getValue() . "; ";
        }
        //echo $cookies_str;//打印cookie
        $image_data = (string)Image::make($response->getBody())->encode('data-url');
        $image_data_url = explode(',', $image_data)[1];
        //echo $data;
        //echo "<img src=\"{$image_data}\" />";
        //2.解码识别
        $server_addr = "http://47.105.52.97/";//识别
        $response = $client->post($server_addr, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Cookie' => ''
            ],
            'body' => $image_data_url
        ]);
        $verify_code = $response->getBody();
        //echo $verify_code;
        //3.验证图片 用于测试验证是否成功
        $server_addr = 'http://www.51jfk.com/index.php/Index/check_verify.html';
        $response = $client->post($server_addr, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Cookie' => $cookies_str
            ],
            'body' => 'verify=' . $response->getBody()
        ]);
        //echo $response->getBody();
        $response_body = json_decode($response->getBody(), true);
        if ($response_body['code'] == 0) {
            return redirect()->route('penalties.login.51jfk', ['account_name' => $request['account_name'], 'account_password' => $request['account_password'], 'account_type' => $request['account_type']]);
        }
        //4.验证登录
        $server_addr = "http://www.51jfk.com/index.php/Index/tclogin.html";
        $response = $client->post($server_addr, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Cookie' => $cookies_str
            ],
            'body' => "username={$request['name']}&userpwd={$request['password']}&verify=" . $verify_code,
            'cookies' => $jar //读取cookie
        ]);
        $cookies_str = "";
        foreach ($jar->getIterator() as $item) {
            $cookies_str = $cookies_str . $item->getName() . "=" . $item->getValue() . "; ";
        }

        $response_body = json_decode($response->getBody(), true);
        if ($response_body != null) {
            if ($response_body['status'] == 0) {
                //return redirect()->route('penalties.login.51jfk',['name'=>$request['account_name'],'password'=>$request['account_password']]);//echo "验证失败";
                return ['status' => 1, 'cookie' => ''];
            }
        } else {
            $account = ThirdAccount::where("account_name", $request['account_name'])->first();
            if (!$account) {
                $account = new ThirdAccount;//未找到用户进行创建
            }
            $account->account_type = $request['account_type'];
            $account->account_name = $request['account_name'];
            $account->account_password = $request['account_password'];
            $account->account_status = "valid";
            $account->account_cookie = $cookies_str;
            $account->account_reserve = "";
            $account->save();
        }
        //echo $cookies_str;//打印cookie
        return ['status' => 0, 'cookie' => $cookies_str];
    }


    /**决定书编号查询违法信息
     * @param Request $request
     * @return string
     */
    //5101041204594064
    public function penalty_info(Request $request)
    {

//        return back()->withErrors(['penalty_number'=>'此激活用定过！']);
        $validator = Validator::make($request->all(), [
            'penalty_number' => 'required|alpha_num|between:15,16',
        ]);
        if ($validator->fails()) {
//            return $this->fail(2002, [], $validator->errors()->first());
//            return redirect('/penalties/inquire')->withErrors($validator);
            return redirect()->back()->withErrors($validator);
        }
        $penalty_number = $request['penalty_number'];

        // 这里需要实现 已经存在直接返回（10分钟内）
        $penaltyinfo = PenaltyInfo::where('penalty_number', $penalty_number)->first();
        if ($penaltyinfo != null) {
//            if ($penaltyinfo->updated_at > date("Y-m-d H:i:s", strtotime("-10 minute"))) {
            if ($penaltyinfo->updated_at > date("Y-m-d H:i:s", strtotime("-100000 minute"))) {
//                return $this->success($penaltyinfo);
                return redirect('/penalties/pay')->with('penalty_info', $penaltyinfo);
            }
        } else {
            $penaltyinfo = new PenaltyInfo;
        }

        $account = ThirdAccount::where("account_status", 'valid')->where("account_type", '51jfk')->first();
        if (!$account) {
            $account = ThirdAccount::where("account_type", '51jfk')->first();
            if ($account) {
                return redirect()->route('penalties.login.51jfk', ['name' => $account['account_name'], 'password' => $account['account_password']]);//echo "验证失败";
            } else {
//                return "no account";
//                return $this->fail(9999, "请添加账户");
                return back()->withErrors(['penalty_number' => '请添加账户！']);
            }
        }

        $url = 'http://www.51jfk.com/index.php/Fakuan/fkdjg';
//        $cookies = 'PHPSESSID=rf6jd40r10mq2djftcfgsrtpl7; temp_user=think%3A%7B%22username%22%3A%22temp_user222.211.251.209%22%2C%22day%22%3A%222018-07-11%22%2C%22day_query_count%22%3A%2220%22%7D; UM_distinctid=1648810ae8d31a-0c671bc72b0985-f373567-13c680-1648810ae8e5ac; CNZZDATA1000345804=1917868247-1531286351-null%7C1531286351; user=think%3A%7B%22memberid%22%3A%2250959%22%2C%22nickname%22%3A%22%22%2C%22membername%22%3A%2215228949671%22%2C%22weixin%22%3A%22%22%7D; Hm_lvt_f06eee151ce72cc27662fae694f526b8=1531291152,1531291535; Hm_lpvt_f06eee151ce72cc27662fae694f526b8=1531291535';
        //       $cookies = 'yunsuo_session_verify=4164ba9853b8fa004df7f1487ee107fc; temp_user=think%3A%7B%22username%22%3A%22temp_user222.211.235.249%22%2C%22day%22%3A%222018-09-05%22%2C%22day_query_count%22%3A%2220%22%7D; CNZZDATA1000345804=1169833295-1536113091-%7C1536113091; user=think%3A%7B%22memberid%22%3A%2258276%22%2C%22nickname%22%3A%22%22%2C%22membername%22%3A%2215228867020%22%2C%22weixin%22%3A%22%22%7D; PHPSESSID=ehtung44o0jb7c6ch7mnll77t5; UM_distinctid=165a7bf1b2633d-00856ede20d123-784a5037-1fa400-165a7bf1b2710c5; Hm_lvt_f06eee151ce72cc27662fae694f526b8=1536117644; Hm_lpvt_f06eee151ce72cc27662fae694f526b8=1536117644';
        $cookies = $account['account_cookie'];
        $body = "fkdbh=" . $penalty_number . "&type=outoinput";
        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Cookie' => $cookies
            ],
            'body' => $body
        ]);
//        $response->getStatusCode();
//        $response->getHeader('content-type');
//        $response->body();
        $response_code = $response->getStatusCode();
        if ($response_code != 200) {
//            return $this->fail(9999);
            return back()->withErrors(['penalty_number' => '系统异常！']);
        }
        $response_body = json_decode($response->getBody(), true);
        if ($response_body['code'] != 200) {
//            return $this->fail(9999, [], $response_body);
            return back()->withErrors(['penalty_number' => $response_body]);
        }
        $penaltyinfo->penalty_number = $response_body['jdsbh'];
        $penaltyinfo->penalty_car_number = $response_body['hphm'];
        $penaltyinfo->penalty_car_type = $response_body['hpzl'];
        $penaltyinfo->penalty_money = $response_body['fkje'];
        $penaltyinfo->penalty_money_late = $response_body['znj'];
        $penaltyinfo->penalty_user_name = $response_body['dsr'];
        $penaltyinfo->penalty_process_time = date('Y-m-d H:i:s', strtotime($response_body['clsj']));
        $penaltyinfo->penalty_illegal_time = date('Y-m-d H:i:s', strtotime($response_body['wfsj']));
        $penaltyinfo->penalty_illegal_place = $response_body['wfdz'];
        $penaltyinfo->penalty_behavior = $response_body['wfxw'] . "";
        $penaltyinfo->setUpdatedAt(date("Y-m-d H:i:s"));
        // 缓存
        $penaltyinfo->save();
//        return $this->success($pnaltyinfo);
        return redirect('/penalties/pay')->with('penalty_info', $penaltyinfo);
    }


    protected function options()
    { //选项设置
        return [
            // 前面的appid什么的也得保留哦
            'app_id' => 'xxxxxxxxx', //你的APPID
            'secret' => 'xxxxxxxxx',     // AppSecret
            // 'token'   => 'your-token',          // Token
            // 'aes_key' => '',                    // EncodingAESKey，安全模式下请一定要填写！！！
            // ...
            // payment
            'payment' => [
                'merchant_id' => '你的商户ID，MCH_ID',
                'key' => '你的KEY',
                // 'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
                // 'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！
                'notify_url' => '你的回调地址',       // TODO: 你也可以在下单时单独设置来想覆盖它
                // 'device_info'     => '013467007045764',
                // 'sub_app_id'      => '',
                // 'sub_merchant_id' => '',
                // ...
            ],
        ];
    }

//    public function penalty_pay(Request $request)
//    {
//
//        $validator = Validator::make($request->all(), [
//            'penalty_number' => 'required|alpha_num|between:15,16',
//        ]);
////        $log = new Logger('register');
////        $log->pushHandler(new StreamHandler(storage_path('logs/reg.log'),Logger::INFO) );
////        $log->addInfo('用户注册信息:'.'2222222222');
//        if ($validator->fails()) {
//            return back()->withErrors($validator);
//        }
//
////        $log->addInfo('用户注册信息:'.'33333');
////        Log::useFiles(storage_path().'/logs/laravel.log')->info('test:','111111');
//        $penalty_number = $request['penalty_number'];
//        // 查询数据库
//        $pnaltyinfo = PenaltyInfo::where('penalty_number', $penalty_number)->first();
//        if ($pnaltyinfo == null) {
////            return $this->fail(9999);
//            return back()->withErrors(['penalty_number' => '系统异常']);
//        }
//
//        //计算订单金额
//        $valid_ddje = $pnaltyinfo->penalty_money;
//        $valid_ddje += 14;//TODO:服务费
//        $valid_ddje += ($pnaltyinfo->penalty_money_late);
//
//        //自动生成，订单编号
//        $sj = rand(10000, 99999);
//        $order_number = date("YmdHis") . '0' . $sj;
//
//        $penaltyorder = PenaltyOrder::where('order_penalty_number', $penalty_number)->first();
//        if ($penaltyorder != null) {
//            $order_status = $penaltyorder->order_status;
//            if ($order_status == "paid" || $order_status == "processing") {
////                return $this->fail(9999, "该违法已在处理中...");
//                return back()->withErrors(['penalty_number' => '该违法已在处理中...']);
//            } else if ($order_status == "completed") {
////                return $this->fail(9999, "该违法已处理");
//                return back()->withErrors(['penalty_number' => '该违法已处理']);
//            }
//            //修改订单金额
//            $penaltyorder->order_money = $valid_ddje;
//            //修改订单用户
//            $penaltyorder->order_user_id = Auth::id();//TODO: 用户id
//            $penaltyorder->save();
//        } else {
//            $penaltyorder = new PenaltyOrder;
//            $penaltyorder->order_number = $order_number;
//            $penaltyorder->order_money = $valid_ddje;
//            $penaltyorder->order_penalty_number = $penalty_number;
//            $penaltyorder->order_user_id = Auth::id();//TODO: 用户id
//            $penaltyorder->order_status = "unpaid";
//            $penaltyorder->save();
//        }
//
//
//        $options = $this->options();
//        $app = new Application($options);
//        $payment = $app->payment;
//        $attributes = [
//            'trade_type' => 'JSAPI', // JSAPI，NATIVE，APP...
//            'body' => '代缴',
//            'detail' => $penalty_number . '代缴', //我这里是通过订单找到商品详情，你也可以自定义
//            'out_trade_no' => $penaltyorder->order_number,//传入订单ID
//            'total_fee' => $penaltyorder->order_money * 100, //因为是以分为单位，所以订单里面的金额乘以100
//            // 'notify_url'       => 'http://xxx.com/order-notify', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
//            'openid' => '当前用户的 openid', // TODO: trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
//            // ...
//        ];
//        $order = new Order($attributes);
//        $result = $payment->prepare($order);
//        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
//            $prepayId = $result->prepay_id;
//            $config = $payment->configForAppPayment($prepayId);
//            $config = $payment->sdkConfig($prepayId); // 返回数组
//
////            return response()->json($config);
//            return redirect('/penalties/pay_order')->with('config', $config);
//        } else {
//            return back()->withErrors(['penalty_number' => '微信支付异常']);
//        }
//
//    }
//
//    //下面是回调函数
//    public function paySuccess()
//    {
//        $options = $this->options();
//        $app = new Application($options);
//        $response = $app->payment->handleNotify(function ($notify, $successful) {
//            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
//            $order = ExampleOrder::where('out_trade_no', $notify->out_trade_no)->first();
//            $penaltyorder = PenaltyOrder::where('order_penalty_number', $notify->out_trade_no)->first();
//            if (count($order) == 0) { // 如果订单不存在
//                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
//            }
//            // 如果订单存在
//            // 检查订单是否已经更新过支付状态
//            if ($order->pay_time) { // 假设订单字段“支付时间”不为空代表已经支付
//                return true; // 已经支付成功了就不再更新了
//            }
//            // 用户是否支付成功
//            if ($successful) {
//                // 不是已经支付状态则修改为已经支付状态
//                $order->pay_time = time(); // 更新支付时间为当前时间
//                $order->status = 6; //支付成功,
//            } else { // 用户支付失败
//                $order->status = 2; //待付款
//            }
//            $order->save(); // 保存订单
//            return true; // 返回处理完成
//        });
//    }


    public function success($data = [], $msg = null)
    {
        return response()->json([
            'status' => true,
            'code' => 200,
            'message' => empty($msg) ? config('errorcode.code')[200] : $msg,
            'data' => $data,
        ]);
    }

    public function fail($code, $msg = null, $data = [])
    {
        return response()->json([
            'status' => false,
            'code' => $code,
            'message' => empty($msg) ? config('errorcode.code')[(int)$code] : $msg,
            'data' => $data,
        ]);
    }


}
