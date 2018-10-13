<?php

namespace App\Http\Controllers;

use Log;
use App\PenaltyInfo;
use App\UserOrderInfo;
use App\WechatAccount;
use App\User;
use GuzzleHttp\Cookie\json_decode;
use Yajra\Datatables\Datatables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use EasyWeChat\Factory;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class UserOrderController extends Controller
{
    //用于只允许通过认证的用户访问指定的路由
    public function __construct()
    {
        $this->middleware('auth');
    }

    //创建用户订单
    public function create_user_order(Request $request){
        $validator = Validator::make($request->all(), [
            'order_money' => 'required|numeric',
            'order_src_type' => 'required|alpha_num',
            'order_src_id' => 'required|alpha_num',
            'order_phone_number' => 'required|regex:/^1[34578]\d{9}$/',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 1,'data' => $validator->errors()->first()]);
        }

        $user_order = UserOrderInfo::where('order_src_id', $request['order_src_id'])->first();
        if ($user_order != null) {
            if($user_order->order_money != $request['order_money'] ||
                $user_order->order_src_type != $request['order_src_type'] ||
                $user_order->order_phone_number != $request['order_phone_number']){
                return response()->json(['status' => 1,'data' => "订单错误。"]);
            }
            if ($user_order->updated_at > date("Y-m-d H:i:s", strtotime("-100000 minute"))) {
                //return response()->json(['status' => 0, 'data' => [$penaltyinfo]]);
                $user_order->order_user_id = Auth::id();
                $user_order->save();
            }else{
                return response()->json(['status' => 1,'data' => "订单已经存在，10分钟不付款将自动删除。"]);
            }
            if ($user_order->order_status == "paid" || $user_order->order_status == "processing") {
                return response()->json(['status' => 1,'data' => "该订单已在处理中"]);
            } else if ($user_order->order_status == "completed") {
                return response()->json(['status' => 1,'data' => "该订单已经处理完成"]);
            }else if($user_order->order_user_id != Auth::id() ){
                return response()->json(['status' => 1,'data' => "该订单已被其他用户关联"]);
            }
        }else{
            UserOrderInfo::create([
                'order_number'=> date("YmdHis") .'0'. rand(10000, 99999),
                'order_money'=> $request['order_money'],
                'order_src_type'=> $request['order_src_type'],
                'order_src_id'=> $request['order_src_id'],
                'order_phone_number'=> $request['order_phone_number'],
                'order_user_id'=> Auth::id(),
                'order_status'=> 'unpaid',
            ]);
        }
        return response()->json(['status' => 0, 'data' => '创建成功']);
    }
    //获取用户订单
    public function get_user_order(){

//        $table = User::where('id',Auth::id())->first()->user_order;
//
//        return response()->json(['status' => 0,'data' =>  $table]);

        $result = parent::_list(UserOrderInfo::where('order_user_id',Auth::id()));

        return view('home',$result);
    }

}