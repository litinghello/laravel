<?php

namespace App\Http\Controllers\Auth;

use App\WechatAccount;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function wechat_login(){
        $wechat_info = session('wechat.oauth_user');//得到用户数据
        $wechat_account = WechatAccount::where("wechat_id",$wechat_info['default']['id'])->get();//检查用户是否已经存在
        if($wechat_account->isEmpty()) {//用户不存在
            //创建主用户
            $email_account = User::create([
                'name' => $wechat_info['default']['name'],
                'email' => $wechat_info['default']['id']."@cttx.com",
                'password' => Hash::make(rand(1000,1000000)),
            ]);
            //创建微信用户
            $new_account = WechatAccount::create([
                'wechat_id' => $wechat_info['default']['id'],
                'wechat_name' => $wechat_info['default']['name'],
                'wechat_nick_name' => $wechat_info['default']['nickname'],
                'wechat_email_account' => $email_account->email,
                'wechat_main_account' => $email_account->id,
                'wechat_reserve' => "",
            ]);
        }else{//用户存在 直接获取用户
            $email_account = User::where("id",$wechat_account->first()["wechat_main_account"])->first();
        }
        Auth::login($email_account);//登录账户

        return view('home');
    }
}
