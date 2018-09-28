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

//用户路由
Auth::routes();
//界面 主页面
Route::get('/', 'HomeController@views_home')->name('views.home');
//接口 微信认证接口 回调token 官方需要
Route::any('/wechats/token','WeChatsController@back_token')->name("wechats.serve");
//接口 创建微信公众号的菜单
Route::get('/wechats/menu/create','WeChatsController@create_menu')->name("wechats.menu");
//接口 微信支付回调
Route::any('/penalties/pay_call.php','WeChatsController@penalty_paycall')->name('penalties.pay.call');
//界面 罚单查询
Route::get('/penalties/inquire','HomeController@views_penalty_inquire')->name('views.penalty.inquire');
//接口 决定书编号
Route::post('/penalties/info','PenaltiesController@penalty_info')->name('penalties.info');
//界面 支付页面界面
Route::get('/penalties/pay', 'HomeController@views_penalty_pay')->name('views.penalty.pay');
//接口 支付接口
Route::post('/penalties/pay','WeChatsController@penalty_pay')->name('wechats.penalty.pay');
//接口 用户查看订单号
Route::post('/penalties/order_data','PenaltiesController@penalty_order_data')->name('penalties.order.data');
//微信认证的中间路由
//(注：使用web中间件是为了防止出现session不共享的情况)
Route::group(['middleware' => ['web', 'wechat.oauth']], function () use ($router) {
//    Route::get('/wechats/auth','WeChatsController@login_auth')->name("wechats.auth");
    Route::get('/wechats/login','Auth\LoginController@wechat_login')->name('wechats.login');//微信一键登录
});

//添加第三方账户
Route::any('/penalties/account/add','PenaltiesController@add_third_account')->name('penalties.account.add');
//登录第三方账户 51jfk
Route::any('/penalties/login/51jfk','PenaltiesController@login_51jfk_account')->name('penalties.login.51jfk');

//后台查看数据
Route::get('/adminltes/table/home', 'AdminLtesController@penalty_order_table_home')->name('adminltes.table.home');
Route::post('/adminltes/table/data', 'AdminLtesController@get_penalty_order_data')->name('adminltes.table.data');
Route::get('/adminltes/table/complete', 'AdminLtesController@set_penalty_order_data')->name('adminltes.table.complete');


