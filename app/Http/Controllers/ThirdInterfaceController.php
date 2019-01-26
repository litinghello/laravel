<?php

namespace App\Http\Controllers;

use App\User;
use App\ViolateInfo;
use App\PenaltyInfo;
use App\ThirdAccount;
use App\ViolateCode;
use App\UserOrderInfo;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Yajra\Datatables\Datatables;
use Intervention\Image\Facades\Image;
use Ammadeuss\LaravelHtmlDomParser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use EasyWeChat\Payment\Order;

class ThirdInterfaceController extends BaseController
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
    public function login_51jfk_account(Request $request){
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
        $server_addr = "http://www.51jfk.com/index.php/Index/login.html";
        $response = $client->post($server_addr, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Cookie' => $cookies_str
            ],
            'body' => "username={$request['name']}&userpwd={$request['password']}&verify=" . $verify_code . "&autologin=1&exptime=365",
            'cookies' => $jar //读取cookie
        ]);
        $cookies_str = "";
        foreach ($jar->getIterator() as $item) {
            $cookies_str = $cookies_str . $item->getName() . "=" . $item->getValue() . "; ";
        }
        //记住访问页面
        $server_addr = "http://www.51jfk.com/index.php/Member/index.html";
        $response = $client->get($server_addr, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Cookie' => $cookies_str
            ],
            'body' => "",
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
    public function penalty_info(Request $request){
        $validator = Validator::make($request->all(), [
            'penalty_number' => 'required|alpha_num|between:15,16',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 1, 'data' => $validator->errors()->first()]);
        }
        $penalty_number = $request['penalty_number'];
        if(strlen($penalty_number) == 15){
            // 这里需要实现 已经存在直接返回（10分钟内）
            $penaltyinfo = PenaltyInfo::where('penalty_number','like', $penalty_number.'%')->first();
        }else{
            // 这里需要实现 已经存在直接返回（10分钟内）
            $penaltyinfo = PenaltyInfo::where('penalty_number', $penalty_number)->first();
        }
        if ($penaltyinfo != null) {
            if($penaltyinfo->penalty_car_number == ""){
                return response()->json(['status' => 1, 'data' => "查询不到记录！"]);
            }
            if ($penaltyinfo->updated_at > date("Y-m-d H:i:s", strtotime("-10 minute"))) {
                return response()->json(['status' => 0, 'data' => [$penaltyinfo]]);
            }
            $penaltyinfo->delete();
        }
        $account = ThirdAccount::where("account_status", 'valid')->where("account_type", '51jfk')->first();
        if (!$account) {
            $account = ThirdAccount::where("account_type", '51jfk')->first();
            if ($account) {
                return redirect()->route('penalties.login.51jfk', ['name' => $account['account_name'], 'password' => $account['account_password']]);//echo "验证失败";
            } else {
                return response()->json(['status' => 1, 'data' => '请添加账户！']);
            }
        }
        $url = 'http://www.51jfk.com/index.php/Fakuan/fkdjg';
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
            return response()->json(['status' => 1, 'data' => '系统异常！']);
        }
        $response_body = json_decode($response->getBody(), true);
        if ($response_body['code'] != 200) {
            PenaltyInfo::create([
                'penalty_number' => $penalty_number,
                'penalty_car_number' => "",
                'penalty_car_type' => "",
                'penalty_money' => 0,
                'penalty_money_late' => 0,
                'penalty_user_name' => "",
                'penalty_process_time' => "",
                'penalty_illegal_time' => "",//date('Y-m-d H:i:s', strtotime($response_body['wfsj'])),
                'penalty_illegal_place' => "",
                'penalty_behavior' => "",
            ]);
            return response()->json(['status' => 1, 'data' => "查询不到记录！"]);
        }else{
            $penaltyinfo = PenaltyInfo::create([
                'penalty_number' => $response_body['jdsbh'],
                'penalty_car_number' => $response_body['hphm'],
                'penalty_car_type' => $response_body['hpzl'],
                'penalty_money' => $response_body['fkje'],
                'penalty_money_late' => $response_body['znj'],
                'penalty_user_name' => $response_body['dsr'],
                'penalty_process_time' => $response_body['clsj'],
                'penalty_illegal_time' => $response_body['wfsj'],//date('Y-m-d H:i:s', strtotime($response_body['wfsj'])),
                'penalty_illegal_place' => $response_body['wfdz'],
                'penalty_behavior' => $response_body['wfxw'] . "",
            ]);
            return response()->json(['status' => 0, 'data' => [$penaltyinfo]]);
        }
    }

    /**车辆违法
     * @param Request $request
     * @return string
     */
    public function violate_info(Request $request){
        $validator = Validator::make($request->all(), [
            'car_province' => 'required',//省份  川
            'car_number' => 'required|alpha_num',//号牌  A5F795
            'car_type' => 'required|alpha_num',//车辆种类  02 暂时支持小车
            'car_frame_number' => 'required|alpha_num',//车架号后6位  010304
//            'car_engine_number' => 'required|alpha_num',//车架号后6位  010304
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 1, 'data' => $validator->errors()->first()]);
        }
        $lsprefix = $request['car_province'];
        $lsnum = $request['car_number'];
        $lstype = $request['car_type'];
        $frameno = $request['car_frame_number'];

        //判断是否在最近8小时内查询了，有则直接返回结果，不在查询接口
        $carviolates = ViolateInfo::where( 'car_type' , $lstype)->where('car_province' , $lsprefix)->where('car_number' , $lsnum)->where('car_frame_number' , $frameno)->get();
        if($carviolates!= null && count($carviolates)>0){
            if ($carviolates[0] != null && $carviolates[0]->updated_at > date("Y-m-d H:i:s", strtotime("-480 minute"))) {
                //没有违法返回提示
                if(($carviolates[0]->violate_time == null || $carviolates[0]->violate_time == '') && $carviolates[0]->violate_msg != null && $carviolates[0]->violate_msg != ''){
                    return response()->json(['status' => 1, 'data' => $carviolates[0]->violate_msg]);
                }else{
                    return response()->json(['status' => 0, 'data' => $carviolates]);
                }
            }else{
                ViolateInfo::where( 'car_type' , $lstype)->where('car_province' , $lsprefix)->where('car_number' , $lsnum)->where('car_frame_number' , $frameno)->delete();
            }
        }


        $account = ThirdAccount::where("account_status", 'valid')->where("account_type", '51jfk')->first();
        if (!$account) {
            $account = ThirdAccount::where("account_type", '51jfk')->first();
            if ($account) {
                return redirect()->route('penalties.login.51jfk', ['name' => $account['account_name'], 'password' => $account['account_password']]);//echo "验证失败";
            } else {
                return response()->json(['status' => 1, 'data' => '请添加账户！']);
            }
        }
        $url = 'http://www.51jfk.com/index.php/Weizhang/index.html';
        $body = "lsprefix=" . $lsprefix . "&lsnum=" . $lsnum . "&lstype=".$lstype."&frameno=" . $frameno . "&engineno=&mobileno=&category=geren&cartype=feiyingyun&verify=3240&memberid=116&carorg=&api=CHETAIJI&addr=&isdirect=&is_dangerousgoods=1&checkcode=&postcphm=&tempuser=";
        $cookies = $account['account_cookie'];
        $client = new Client();
        $response = $client->post($url, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Cookie' =>$cookies
            ],
            'body' => $body
        ]);
        $response_code = $response->getStatusCode();
        if ($response_code != 200) {
            return response()->json(['status' => 1, 'data' => "系统异常"]);
        }
//        return LaravelHtmlDomParser\Facade::str_get_html($response->getBody());
        $form_strs = LaravelHtmlDomParser\Facade::str_get_html($response->getBody())->find('div.chaxun_jg > form');
        if($form_strs!= null && count($form_strs)>0){
            $form_str = $form_strs[0];
        }else{
            $form_strs = LaravelHtmlDomParser\Facade::str_get_html($response->getBody())->find('div.chaxun_jg');
            unset($response);
            if($form_strs!= null && count($form_strs)>0){
               $error = LaravelHtmlDomParser\Facade::str_get_html($form_strs[0])->find('div.tishi');
               if($error!= null && count($error)>1){
                   $carviolate = ViolateInfo::create([
                           'car_type' => $lstype,
                           'car_province' => $lsprefix,
                           'car_number' => $lsnum,
                           'car_frame_number' => $frameno,
                           'violate_info' => '',
                           'violate_code' => '',
                           'violate_time' => '',
                           'violate_address' => '',
                           'violate_money' => 0,
                           'violate_marks' => 0,
                           'violate_msg' => $error[1]->innertext,
                       ]
                   );
                    return response()->json(['status' => 1, 'data' => $error[1]->innertext]);
               }elseif ($error!= null && count($error)==1){
                   $carviolate = ViolateInfo::create([
                           'car_type' => $lstype,
                           'car_province' => $lsprefix,
                           'car_number' => $lsnum,
                           'car_frame_number' => $frameno,
                           'violate_info' => '',
                           'violate_code' => '',
                           'violate_time' => '',
                           'violate_address' => '',
                           'violate_money' => 0,
                           'violate_marks' => 0,
                           'violate_msg' => $error[0]->innertext,
                       ]
                   );
                   return response()->json(['status' => 1, 'data' => $error[0]->innertext]);
               }
            }
            return response()->json(['status' => 1, 'data' => "查询异常"]);
        }
        unset($response);
        $carviolates = array();
        if (isset($form_str)) {
            $infos = array();
            foreach (LaravelHtmlDomParser\Facade::str_get_html($form_str)->find('ul') as $ul) {
                $info = array();
                foreach (LaravelHtmlDomParser\Facade::str_get_html($ul)->find('li') as $li) {
                    $info[] = $li->innertext;
                }
                if (count($info) > 1) {
                    $infos[] = $info;
                }
            }
            if (count($infos) > 1) {
                unset($infos[0]);
                foreach ($infos as $info) {
                    if ($info != null) {
                        $carviolate = ViolateInfo::create([
                                'car_type' => $lstype,
                                'car_province' => $lsprefix,
                                'car_number' => $lsnum,
                                'car_frame_number' => $frameno,
                                'violate_info' => $info[1],
                                'violate_code' => $info[2],
                                'violate_time' => $info[3],
                                'violate_address' => $info[4],
                                'violate_money' => $info[5],
                                'violate_marks' => $info[7],
                                'violate_msg' => "",
                            ]
                        );
                        $carviolates[] = $carviolate;
                    }
                }
            }
        } else {
            //这里找不到form表单 需要显示错误信息
            return response()->json(['status' => 1, 'data' => "获取异常"]);
        }
        return response()->json(['status' => 0, 'data' => $carviolates]);
    }
    //获取图片
    function chengdu_img(){
        $client = new Client();//
        //$client = new Client(['cookies' => true]);// 可开启共享
        $jar = new CookieJar();
        $server_addr = 'http://www.cdjg.gov.cn/WebService/Yzm.aspx?val=0.13383302428998878';
        $cookies_str = "";
        $response = $client->get($server_addr, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Referer'=>"http://www.cdjg.gov.cn/WebService/OnlineWork/QueryDrvOrVeh/QueryVehCenter/QueryVehStyleFour.aspx",
                'Cookie' => $cookies_str
            ],
            'cookies' => $jar //读取cookie
        ]);
        $cookies_str = "";
        foreach ($jar->getIterator() as $item) {
            $cookies_str = $cookies_str . $item->getName() . "=" . $item->getValue() . "; ";
        }
        return response()->json(['status' => 0,'cookies'=>$cookies_str, 'data' => "data:image/jpeg;base64,".base64_encode($response->getBody())]);
    }
    //返回请求结果
    function chengdu_violate_info(Request $request){
        $validator = Validator::make($request->all(), [
            'cookies' => 'required',//
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 1, 'data' => $validator->errors()->first()]);
        }

        $lsprefix = $request['ctl00$ContentPlaceHolder1$txtSyr'];
        $lsnum = $request['ctl00$ContentPlaceHolder1$hidHphm'];
        $lstype = $request['ctl00$ContentPlaceHolder1$hidHpzl'];
        $frameno = $request['ctl00$ContentPlaceHolder1$hidClsbdh'];


//        //判断是否在最近8小时内查询了，有则直接返回结果，不在查询接口
//        $carviolates = ViolateInfo::where( 'car_type' , $lstype)->where('car_province' , $lsprefix)->where('car_number' , $lsnum)->where('car_frame_number' , $frameno)->get();
//        if($carviolates!= null && count($carviolates)>0){
//            if ($carviolates[0] != null && $carviolates[0]->updated_at > date("Y-m-d H:i:s", strtotime("-480 minute"))) {
//                //没有违法返回提示
//                if(($carviolates[0]->violate_time == null || $carviolates[0]->violate_time == '') && $carviolates[0]->violate_msg != null && $carviolates[0]->violate_msg != ''){
//                    return response()->json(['status' => 1, 'data' => $carviolates[0]->violate_msg]);
//                }else{
//                    return response()->json(['status' => 0, 'data' => $carviolates]);
//                }
//            }else{
//                ViolateInfo::where( 'car_type' , $lstype)->where('car_province' , $lsprefix)->where('car_number' , $lsnum)->where('car_frame_number' , $frameno)->delete();
//            }
//        }


        $data_object = $request->all();
        $data_str = "";
        foreach ($data_object as $key => $value){
            if($key != "cookies"){
                $data_str = $data_str.urlencode($key)."=".urlencode($value)."&";
            }
        }
        $data_str = substr($data_str, 0, -1) ;//删掉最后一个&字符

        $client = new Client();//
        $server_addr = 'http://www.cdjg.gov.cn/WebService/OnlineWork/QueryDrvOrVeh/QueryVehCenter/QueryVehDetailFour.aspx';
        $response = $client->post($server_addr, [
            'headers' => [
                'X-Requested-With' => 'XMLHttpRequest',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'Referer'=>"http://www.cdjg.gov.cn/WebService/OnlineWork/QueryDrvOrVeh/QueryVehCenter/QueryVehStyleFour.aspx",
                'Host'=>'www.cdjg.gov.cn',
                'Cookie' => $request['cookies'],
            ],
            'body' => $data_str
        ]);

        $response_code = $response->getStatusCode();
        if ($response_code != 200) {
            return response()->json(['status' => 1, 'data' => "系统异常"]);
        }
        $count_str = LaravelHtmlDomParser\Facade::str_get_html($response->getBody())->find('#ContentPlaceHolder1_Panel1 > #lblCount');
        $list = LaravelHtmlDomParser\Facade::str_get_html($response->getBody())->find('#ContentPlaceHolder1_Panel1 > tr');
        if($count_str == null || $list == null){
            return response()->json(['status' => 1, 'data' => "请求错误"]);
        }
        if($count_str[0]->innertext === 0 || $count_str[0]->innertext === ''){
            return response()->json(['status' => 1, 'data' => LaravelHtmlDomParser\Facade::str_get_html($response->getBody())->find('#ContentPlaceHolder1_Panel2 > span')[0]->innertext]);
        }else{
            $object = array();
            foreach ($list as  $key => $value){
                if($key > 1){//设置过滤标题的 tr 设置为1
                    $array = array();
                    $list = LaravelHtmlDomParser\Facade::str_get_html($value->innertext)->find('td');
                    $array['violate_time'] = trim($list[0]->innertext);//时间
                    $array['violate_address'] = trim($list[1]->innertext);//地点
                    $array['violate_info'] = trim($list[2]->innertext);//违法信息
                    $array['violate_status'] = trim($list[3]->innertext);//处理状态
                    $array['violate_pay'] = trim($list[4]->innertext);//交款状态
                    $array['car_type'] = $lstype;
                    $array['car_province'] = $lsprefix;
                    $array['car_number'] = $lsnum;
                    $array['car_frame_number'] = $frameno;
                    preg_match('#\((.*?)\)#', trim($array['violate_info']), $match);
                    $ViolateCode = ViolateCode::where("code",$match[1])->first();
                    if($ViolateCode != null) {
                        $array['violate_code'] = $match[1];//违法代码
                        $array['violate_money'] = $ViolateCode['money'];
                        $array['violate_marks'] = $ViolateCode['score'];
                        $array['violate_msg'] = $ViolateCode['notification'];
                    }else{
                        $array['violate_code'] = '';//违法代码
                        $array['violate_money'] = '';
                        $array['violate_marks'] = '';
                        $array['violate_msg'] = '';
                    }
//                    $carviolate = ViolateInfo::create($array );
                    $carviolate = ViolateInfo::create([
                            'car_type' => $lstype,
                            'car_province' => $lsprefix,
                            'car_number' => $lsnum,
                            'car_frame_number' => $frameno,
                            'violate_info' => $array['violate_info'],
                            'violate_code' => $array['violate_code'],
                            'violate_time' => $array['violate_time'],
                            'violate_address' => $array['violate_address'],
                            'violate_money' => $array['violate_money'],
                            'violate_marks' => $array['violate_marks'],
                            'violate_msg' => $array['violate_msg'],
                        ]
                    );
                    $object[] =$carviolate;
                }
            }
            return response()->json(['status' => 0, 'data' => $object]);
        }
    }
}
