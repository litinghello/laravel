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
//微信认证的中间路由
//(注：使用web中间件是为了防止出现session不共享的情况)
Route::group(['middleware' => ['web', 'wechat.oauth']], function () use ($router) {
    Route::get('/wechats/login','Auth\LoginController@wechat_login')->name('wechats.login');//微信一键登录
});
//接口 微信认证接口 回调token 官方需要
Route::any('/wechats/token','WeChatsController@back_token')->name("wechats.serve");

//接口 创建订单
Route::any('/order/create','UserOrderController@create_user_order')->name('order.create');
//接口 获取订单
Route::any('/order/get','UserOrderController@get_user_order')->name('order.get');
//接口 微信支付 获取订单数据
Route::any('/order/pay/wechat','WeChatsController@order_pay_wechat')->name('order.pay.wechat');
//接口 微信支付回调
Route::any('/order/pay/wechat/paycall','WeChatsController@wechat_paycall')->name('penalties.paycall');

//接口 创建微信公众号的菜单
Route::get('/wechats/menu/create','WeChatsController@create_menu')->name("wechats.menu");

//界面 决定书编号查询
Route::get('/penalties/inquire','HomeController@views_penalty_inquire')->name('views.penalty.inquire');
//接口 决定书编号
Route::any('/penalties/info','ThirdInterfaceController@penalty_info')->name('penalties.info');
//界面 违章查询
Route::get('/violates/inquire','HomeController@views_violate_inquire')->name('views.violate.inquire');
//接口 违章查询
Route::any('/violates/info','ThirdInterfaceController@penalty_car_info')->name('violates.info');

//添加第三方账户
//Route::any('/penalties/account/add','ThirdAccountController@add_third_account')->name('penalties.account.add');
//登录第三方账户 51jfk
//Route::any('/penalties/login/51jfk','ThirdAccountController@login_51jfk_account')->name('penalties.login.51jfk');

//后台查看数据
Route::get('/adminltes/table/home', 'AdminLtesController@order_table_home')->name('adminltes.table.home');
Route::post('/adminltes/table/data', 'AdminLtesController@get_order_data')->name('adminltes.table.data');
Route::post('/adminltes/table/data/detail', 'AdminLtesController@get_order_detail')->name('adminltes.table.data.detail');
Route::get('/adminltes/table/complete', 'AdminLtesController@set_order_data')->name('adminltes.table.complete');







