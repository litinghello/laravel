<script type="text/javascript">
    let wechat_pay_type = "JSSDK";//WeixinJSBridge or JSSDK
    function wechat_process(data,success){//微信两种支付方式，
        if(wechat_pay_type === "WeixinJSBridge"){
            WeixinJSBridge.invoke(
                'getBrandWCPayRequest',data,
                function(res){
                    if(res.err_msg === "get_brand_wcpay_request:ok" ) {
                        
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
                        
                        // user_modal_prompt("支付成功，我们将在12小时以内处理，请等待！");
                        success();
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
    function user_wechat_pay_check(order_data) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
            url:"<?php echo e(route('order.pay.check')); ?>",
            type:"POST",
            data:order_data,
            success:function(data){
                if(data['status'] === 0){
                }else{
                    user_modal_prompt(data['data']);
                }
            },
            error:function(error){
                user_modal_prompt("支付提交失败:"+JSON.stringify(error));
            }
        });
    }
    function user_wechat_pay(order_data){
        $.ajax({
            headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
            url:"<?php echo e(route('order.pay.wechat')); ?>",
            type:"POST",
            data:order_data,
            success:function(data){
                // user_modal_warning(data);
                // console.log(data);
                if(data['status'] === 0){
                    if(order_data['wechat_pay_type'] === 'JSAPI'){
                        wechat_process(data['data'],function () {
                            //user_wechat_pay_check(order_data);
                            user_modal_prompt("支付成功，我们将在12小时以内处理，请等待！");
                        });//采用微信网页支付
                    }else if(order_data['wechat_pay_type'] === 'NATIVE'){
                        user_modal_image("微信支付码",data['data']);
                    }else{
                    }
                }else{
                    user_modal_prompt(data['data']);
                }
            },
            error:function(error){
                user_modal_prompt("支付提交失败:"+JSON.stringify(error));
            }
        });
    }
    function user_wechat_share(){
        $.ajax({
            headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
            url:"<?php echo e(route('wechats.get.config')); ?>",
            type:"POST",
            data:"",
            success:function(data){
                // console.log(JSON.parse(data["data"]));
                wx.config(JSON.parse(data["data"]));
                // wx.checkJsApi({
                //     jsApiList: ['updateTimelineShareData','updateAppMessageShareData'], // 需要检测的JS接口列表，所有JS接口列表见附录2,
                //     success: function(res) {
                //         if(res['checkResult']['updateTimelineShareData'] === false){
                //             alert("请升级微信。");
                //         }
                //     }
                // });
                wx.error(function(res){
                    // alert(JSON.stringify(res));
                });
                wx.ready(function () {//需在用户可能点击分享按钮前就先调用
                    // wx.updateTimelineShareData({ //jssdk 1.40+
                    wx.onMenuShareTimeline({//jssdk 1.0+
                        title: '违章罚款代缴 | 首单免费，最低手续费1元，名额有限。', // 分享标题
                        desc : "快速违章代缴，违法代办，免费名额有限。",//摘要,如果分享到朋友圈的话，不显示摘要。
                        // link: 'https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzUzMzkyNTUxMg==#wechat_redirect', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        link: 'http://www.cttx-zbx.com/penalties/inquire', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: 'http://www.cttx-zbx.com/images/wechat_payment.jpg', // 分享图标
                        success: function (res) {
                            // alert(JSON.stringify(res));
                        },
                        fail:function (err) {
                            // alert(JSON.stringify(err));
                        },
                        cancel: function (res) {
                            // alert(JSON.stringify(res));
                        }
                    });
                })
            },
            error:function(error){
            }
        });
    }
</script>