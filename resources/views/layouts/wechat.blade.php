<script type="text/javascript">
    var wechat_pay_data = null;
    var wechat_pay_type = "JSSDK";//WeixinJSBridge or JSSDK
    function wechat_process(data){//微信两种支付方式，
        if(wechat_pay_type === "WeixinJSBridge"){
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',data,
                function(res){
                    if(res.err_msg === "get_brand_wcpay_request:ok" ) {
                        {{--window.location.replace("{{ route('views.home') }}");--}}
                        user_modal_prompt("支付成功，我们将在12小时以内处理，请等待！");
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
                        {{--window.location.replace("{{ route('views.home') }}");// 支付成功后的回调函数--}}
                        user_modal_prompt("支付成功，我们将在12小时以内处理，请等待！");
                    },
                    cancel: function(res) {
                        // alert('支付取消');//支付取消
                        user_modal_prompt("支付取消");
                    },
                    fail: function(res) {
                        //接口调用失败时执行的回调函数。
                        // alert("fail"+JSON.stringify(res));//支付取消
                        user_modal_prompt("支付失败");
                    }
                });
            });
        }
    }
    function user_wechat_pay(order_data){
        //这里防止重复加载支付订单
        if(wechat_pay_data !== null){
            wechat_process(wechat_pay_data);
        }else{
            console.log(order_data);
            $.ajax({
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                url:"{{route('wechats.pay')}}",
                type:"GET",
                data:order_data,
                success:function(data){
                    if(data['status'] === 0){
                        wechat_pay_data = data['data'];//保存值
                        wechat_process(wechat_pay_data);//采用微信网页支付
                    }else{
                        user_modal_prompt(data['data']);
                    }
                },
                error:function(error){
                    user_modal_prompt("支付提交失败:"+JSON.stringify(error));
                }
            });
        }
    }
</script>