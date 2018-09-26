<!doctype html>
<html lang="en" xmlns:v-bind="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>订单确认</title>
    <meta name="format-detection" content="telephone=no" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=0"/>
  {{--  <link rel="stylesheet" href="../public/css/common.css?13" />
    <link rel="stylesheet" href="../public/css/order_conform.css?333" />
    <link rel="stylesheet" href="../public/css/layer.css" />
    <script src="../public/js/jquery-1.11.2.min.js"></script>
    <script src="../public/js/layer.js?12"></script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="../public/js/jquery.cookie.js"></script>
    <script type="text/javascript">
        //移动端屏幕适配
        var html = document.querySelector('html');
        var rem = html.offsetWidth / 20;
        html.style.fontSize = rem + "px";
        $(window).on("change, resize",function(){
            var html = document.querySelector('html');
            var rem = html.offsetWidth / 20;
            html.style.fontSize = rem + "px";
        });
    </script>
    <style>
        .oc_body{overflow:hidden;}
    </style>
    <script>
        var _hmt = _hmt || [];
        (function() {
            var hm = document.createElement("script");
            hm.src = "https://hm.baidu.com/hm.js?6c2af968bd814b5239b3f5d19e8299c5";
            var s = document.getElementsByTagName("script")[0];
            s.parentNode.insertBefore(hm, s);
        })();
    </script>--}}
</head>
<body  >


@php
     $config =  session('config');
@endphp
{{$config}}

</body>


<script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $config; ?>,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                alert(res.err_code+res.err_desc+res.err_msg);
            }
        );
    }

    function callpay()
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall);
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }

    window.onload = function(){
        callpay()
    }

</script>



<script>



    // $(function(){
//
//         if ($.cookie("get_uid") == "true") {
//             var userid = $.cookie("userid"),ddbh=getQueryString('ddbh');
//             $.ajax({
//                 url: 'https://zfbservice.xmxing.net/xcx_api/xcx/wx_gzh_pay.php',
//                 data: {
//                     ddbh: ddbh,
//                     wxid: userid
//                 },
//                 type:'Post',
//                 dataType: 'json',
//                 success: function (data) {
//                     if (data.state == 'false') {
//                         tankuang(data.msg);
//                     } else {
//                         app3.ajax_hide=true;
//                         app3.create_ts=data.create_ts;
//                         app3.jdsbh=data.jdsbh;
//                         app3.ddje=data.ddje;
//                         app3.ddbh=ddbh;
//                     }
//                     var type;
//                     $(".ev_save").on("click",function(e){
//                         e.stopPropagation();
//                         var isCheck=$("#xy_con").is(':checked');
//                         //console.log(isCheck);
//                         if(isCheck===false){
//                             tankuang('请阅读并同意温馨提示');
//                             return false;
//                         }
//
//                         $(".check_img").each(function(){
//                             if($(this).attr('src')=='../public/image/pic_12.png'){
//                                 type=$(this).attr('data');
//                             }
//                         });
//                         callpay(data.jsApiParameters);
//                         function callpay(data)
//                         {
//                             if (typeof WeixinJSBridge == "undefined"){
//                                 if( document.addEventListener ){
//                                     document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
//                                 }else if (document.attachEvent){
//                                     document.attachEvent('WeixinJSBridgeReady', jsApiCall);
//                                     document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
//                                 }
//                             }else{
//                                 jsApiCall(data);
//                             }
//                         }
// //        调用微信JS api 支付
//                         function jsApiCall(data)
//                         {
//                             var nd=JSON.parse(data);
//                             WeixinJSBridge.invoke(
//                                 'getBrandWCPayRequest', {
//                                     "appId":nd.appId,     //公众号名称，由商户传入
//                                     "timeStamp":nd.timeStamp,         //时间戳，自1970年以来的秒数
//                                     "nonceStr":nd.nonceStr, //随机串
//                                     "package":nd.package,
//                                     "signType":"MD5",         //微信签名方式：
//                                     "paySign":nd.paySign //微信签名
//                                 },
//                                 function(res){
//                                     loadOff();
//                                     if(res.err_msg=='get_brand_wcpay_request:ok'){
//                                         window.location.href='order_detail.php?ddbh='+ddbh+'&type=go';
//                                     }
//                                 }
//                             );
//                         }
//                     });
//                 },
//                 error:function(XMLHttpRequest, textStatus, errorThrown){
//                     new Toast({context:$('body'),message:'系统繁忙'}).show();
//                 }
//             });
//         }else{
//             tankuang_back('系统错误，请稍后访问',-4);
//         }
//     });

</script>
</html>
