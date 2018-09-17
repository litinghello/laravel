<?php

namespace App\Http\Controllers;

use App\PenaltyInfo;
use App\PenaltyOrder;
use App\ThirdAccount;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\json_decode;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use EasyWeChat\Factory;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class WXpayController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    protected function options()
    { //选项设置
        return [
            // 必要配置
            'app_id'             => 'xxxx',
            'mch_id'             => 'your-mch-id',
            'key'                => 'key-for-signature',   // API 密钥
            'sandbox' => true, // 设置为 false 或注释则关闭沙箱模式
            // 如需使用敏感接口（如退款、发送红包等）需要配置 API 证书路径(登录商户平台下载 API 证书)
            'cert_path'          => 'path/to/your/cert.pem', // XXX: 绝对路径！！！！
            'key_path'           => 'path/to/your/key',      // XXX: 绝对路径！！！！

            'notify_url'         => '默认的订单回调地址',     // 你也可以在下单时单独设置来想覆盖它
        ];
    }



    public function penalty_pay(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'penalty_number' => 'required|alpha_num|between:15,16',
        ]);
        $log = new Logger('register');
        $log->pushHandler(new StreamHandler(storage_path('logs/reg.log'),Logger::INFO) );
        $log->addInfo('penalty_pay:'.'1111');
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $log->addInfo('penalty_pay:'.'2222');
        $penalty_number = $request['penalty_number'];
        // 查询数据库
        $pnaltyinfo = PenaltyInfo::where('penalty_number', $penalty_number)->first();
        if ($pnaltyinfo == null) {
            return back()->withErrors(['penalty_number' => '系统异常']);
        }

        //计算订单金额
        $valid_ddje = $pnaltyinfo->penalty_money;
        $valid_ddje += 14;//TODO:服务费
        $valid_ddje += ($pnaltyinfo->penalty_money_late);

        //自动生成，订单编号
        $sj = rand(10000, 99999);
        $order_number = date("YmdHis") . '0' . $sj;

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
            $penaltyorder->order_user_id = Auth::id();//TODO: 用户id
            $penaltyorder->save();
        } else {
            $penaltyorder = new PenaltyOrder;
            $penaltyorder->order_number = $order_number;
            $penaltyorder->order_money = $valid_ddje;
            $penaltyorder->order_penalty_number = $penalty_number;
            $penaltyorder->order_user_id = Auth::id();//TODO: 用户id
            $penaltyorder->order_status = "unpaid";
            $penaltyorder->save();
        }
        $log->addInfo('penalty_pay:'.'333333');

        $options = $this->options();
        $app = Factory::payment($options);
        $log->addInfo('penalty_pay:'.'444444');
        $result = $app->order->unify([
            'body' => '腾讯充值中心-QQ会员充值',
            'out_trade_no' => $penaltyorder->order_number,//传入订单ID
            'total_fee' =>  $penaltyorder->order_money * 100, //因为是以分为单位，所以订单里面的金额乘以100
//            'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
//            'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => 'JSAPI',
            'openid' => 'oUpF8uMuAJO_M2pxb1Q9zNjWeS6o',
        ]);
        $log->addInfo('penalty_pay:'.'5555555');
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS') {
            $prepayId = $result->prepay_id;
            $jssdk = $app->jssdk;
            $config = $jssdk->sdkConfig($prepayId); // 返回数组
            return redirect('/penalties/pay_order')->with('config', $config);
        } else {
            return back()->withErrors(['penalty_number' => '微信支付异常']);
        }


    }

    //下面是回调函数
    public function paySuccess()
    {
        $options = $this->options();
        $app = new Application($options);
        $response = $app->payment->handleNotify(function ($notify, $successful) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = ExampleOrder::where('out_trade_no', $notify->out_trade_no)->first();
            $penaltyorder = PenaltyOrder::where('order_penalty_number', $notify->out_trade_no)->first();
            if (count($order) == 0) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->pay_time) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }
            
            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
                $order->pay_time = time(); // 更新支付时间为当前时间
                $order->status = 6; //支付成功,
            } else { // 用户支付失败
                $order->status = 2; //待付款
            }
            $order->save(); // 保存订单
            return true; // 返回处理完成
        });
    }


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
