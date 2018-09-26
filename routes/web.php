<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

//用户路由
Auth::routes();
//用户登录访问
Route::get('/home', 'HomeController@index')->name('home');


//微信认证接口 回调token 官方需要
Route::any('/wechats/token','WeChatsController@back_token')->name("wechats.serve");
//创建微信公众号的菜单
Route::get('/wechats/menu/create','WeChatsController@create_menu')->name("wechats.menu");
//微信认证的中间路由
//(注：使用web中间件是为了防止出现session不共享的情况)
Route::group(['middleware' => ['web', 'wechat.oauth']], function () use ($router) {
    Route::get('/wechats/auth','WeChatsController@login_auth')->name("wechats.auth");
    //查询页面
    Route::get('/penalties/inquire', function () {
        return view('penalty.inquire');
    });
    //查询决定书编号信息
    Route::any('/penalties/info','PenaltiesController@penalty_info')->name('penalties.info');
    //支付页面
    Route::get('/penalties/pay', function () {
        return view('penalty.pay');
    });
    //支付处理
    Route::post('/penalties/pay','WeChatsController@penalty_pay')->name('penalties.pay');
    //支付页面
    Route::get('/penalties/pay_order', function () {
        return view('penalty.pay_order');
    });
    //微信一键登录
    Route::get('/wechats/login','Auth\LoginController@wechat_login')->name('wechats.login');
});

//支付回调
Route::post('/penalties/pay_call','WeChatsController@penalty_paycall')->name('penalties.paycall');

//添加第三方账户
Route::any('/penalties/account/add','PenaltiesController@add_third_account')->name('penalties.account.add');
//登录第三方账户 51jfk
Route::any('/penalties/login/51jfk','PenaltiesController@login_51jfk_account')->name('penalties.login.51jfk');


//后台查看数据
Route::get('/adminltes/table/home', 'AdminLtesController@penalty_order_table_home')->name('adminltes.table.home');
Route::get('/adminltes/table/data', 'AdminLtesController@get_penalty_order_data')->name('adminltes.table.data');
Route::get('/adminltes/table/complete', 'AdminLtesController@set_penalty_order_data')->name('adminltes.table.complete');


