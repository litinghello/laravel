<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\WechatAccount;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    //微信登录
    public function wechat_login(){
        $wechat_info = session('wechat.oauth_user');//得到用户数据
        $wechat_account = WechatAccount::where("wechat_id",$wechat_info['default']['id'])->first();
        if($wechat_account == null){
            $user_account = User::create([
                'name' => $wechat_info['default']['name'],
                'email' => $wechat_info['default']['id']."@cttx.com",
                'password' => Hash::make(rand(1000,1000000)),
            ]);
            $wechat_account = WechatAccount::create([
                'wechat_id' => $wechat_info['default']['id'],
                'wechat_name' => $wechat_info['default']['name'],
                'wechat_nick_name' => $wechat_info['default']['nickname'],
                'wechat_email_account' => $user_account->email,
                'wechat_main_account' => $user_account->id,
                'wechat_reserve' => "",
            ]);
        }
        $user_account = User::where('email',$wechat_info['default']['id']."@cttx.com")->first();//查找主账户邮箱是否存在
        if($user_account == null){
            $user_account = User::create([
                'name' => $wechat_info['default']['name'],
                'email' => $wechat_info['default']['id']."@cttx.com",
                'password' => Hash::make($wechat_info['default']['id']),
            ]);
            $wechat_account->update(['wechat_email_account' => $user_account->email,'wechat_main_account'=>$user_account->id]);
        }else{
            if($wechat_account->wechat_main_account != $user_account->id || $wechat_account->wechat_email_account != $user_account->email){
                $wechat_account->update(['wechat_email_account' => $user_account->email,'wechat_main_account'=>$user_account->id]);
            }
        }
        Auth::login($user_account);//登录账户

        $user = Auth::user();


        if($user['authorize']=='1')
        {
//            return view('adminlte.home');
            return redirect()->route("adminltes.table.home");
        }else{
//            return view('home');
            return redirect()->route("order.get");
        }
    }
}
