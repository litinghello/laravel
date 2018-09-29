{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1>扣分支付</h1>
@stop
@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
@show
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('支付页面') }}</div>
                    <div class="card-body">
                        <div class="form-group">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul style="color:red;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        <div class="row center-block">
                            <table id="table_info" class="table table-striped table-hover table-condensed" style="width:100%">
                                <thead>
                                <tr>
                                    <th>单号</th>
                                    <th>金额</th>
                                    <th>状态</th>
                                    <th>时间</th>
                                    {{--<th>操作</th>--}}
                                </tr>
                                </thead>
                            </table>
                        </div>
                        {{--<div id="text"></div>--}}
                        <script type="text/javascript">
                            var wechat_pay_data = null;
                            var wechat_pay_type = "JSSDK";//WeixinJSBridge or JSSDK
                            function wechat_pay(data){//微信两种支付方式，
                                if(wechat_pay_type === "WeixinJSBridge"){
                                    WeixinJSBridge.invoke(
                                        'getBrandWCPayRequest',data,
                                        function(res){
                                            if(res.err_msg === "get_brand_wcpay_request:ok" ) {
                                                window.location.replace("{{ route('views.home') }}");
                                            }else{
                                            }
                                        }
                                    );
                                }else if(wechat_pay_type === "JSSDK"){
                                    // document.getElementById("text").innerText= JSON.stringify(data);
                                    wx.config({
                                        debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                                        appId:data['appId'] , // 必填，公众号的唯一标识
                                        timestamp: data['timestamp'] , // 必填，生成签名的时间戳
                                        nonceStr: data['nonceStr'], // 必填，生成签名的随机串
                                        //signature: data['paySign'],// 必填，签名，见附录1
                                        jsApiList: ['chooseWXPay'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
                                    });
                                    wx.ready(function(){
                                        wx.chooseWXPay({
                                            debug: false,timestamp:data['timestamp'] ,nonceStr: data['nonceStr'] ,
                                            package: data['package'] ,signType: data['signType'] ,paySign: data['paySign'] , // 支付签名
                                            success: function (res) {
                                                window.location.replace("{{ route('views.home') }}");// 支付成功后的回调函数
                                            },
                                            cancel: function(res) {
                                                alert('支付取消');//支付取消
                                            },
                                            fail: function(res) {
                                                //接口调用失败时执行的回调函数。
                                                // alert("fail"+JSON.stringify(res));//支付取消
                                            }
                                        });
                                    });
                                }
                            }
                            $("#wechat_pay").click(function(){
                                //这里防止重复加载支付订单
                                if(wechat_pay_data !== null){
                                    wechat_pay(wechat_pay_data);
                                }else{
                                    var order_info = $("#order_info").serialize();
                                    $.ajax({
                                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
{{--                                        url:"{{route('wechats.violate.pay')}}",type:"POST",data:order_info,--}}
                                        success:function(data){
                                            if(data['status'] === 0){
                                                wechat_pay_data = data['data'];//保存值
                                                // document.getElementById("text").innerText = JSON.stringify(wechat_pay_data);
                                                wechat_pay(wechat_pay_data);//采用微信网页支付
                                            }else{
                                                alert(data['data']);
                                            }
                                        },
                                        error:function(error){
                                            alert("请再次提交");
                                        }
                                    });
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection




