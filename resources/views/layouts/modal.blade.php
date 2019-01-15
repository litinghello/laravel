
<script type="text/javascript">

    function user_modal_show(title,body){
        layer.confirm(body, {offset: '100px',icon: 3, title:title}, function(index){
            //do something
            layer.close(index);
        });
    }
    function user_modal_image(title,body){
        layer.confirm(body, {offset: '100px', title:title}, function(index){
            //do something
            layer.close(index);
        });
    }
    function user_modal_prompt(body){
        layer.confirm(body, {offset: '100px',icon: 3, title:"确认"}, function(index){
            //do something
            layer.close(index);
        });
    }
    function user_modal_loading(style){
        layer.load(style);
    }
    function user_modal_loading_close() {
        layer.closeAll('loading'); //关闭加载层
    }
    function user_modal_comfirm(body,event){
        layer.confirm(body, {offset: '100px',icon: 3, title:"提示"}, function(index){
            event();
            layer.close(index);
        });
    }
    function user_modal_warning(html){
        layer.alert(html);
    }
    function user_modal_input(title,name,event) {
        layer.prompt({
            offset: '100px',
            formType: 0,
            // value: '初始值',
            title: '请输入手机号码',
             // area: ['60%', '30%'] //自定义文本域宽高
        }, function(value, index, elem){
            event(value); //得到value
            layer.close(index);
        });
    }
    //支付模块
    function user_modal_order_pay(body,pay_value) {
        if (/MicroMessenger/.test(window.navigator.userAgent)) {
            pay_value['wechat_pay_type'] = 'JSAPI';
            pay_value['wechat_pay_limit'] = true;
            layer.confirm(body, {offset: '100px',icon: 3, title:"微信支付"}, function(index){
                user_wechat_pay(pay_value);
                layer.close(index);
            });
        } else if (/AlipayClient/.test(window.navigator.userAgent)) {
            layer.confirm(body, {offset: '100px',icon: 3, title:"支付宝支付"}, function(index){
                layer.close(index);
            });
        } else {
            pay_value['wechat_pay_type'] = 'NATIVE';//支付方式
            pay_value['wechat_pay_limit'] = false;//支付限制
            // console.log(pay_value);
            layer.confirm(body, {offset: '100px',icon: 1, title:"二维码支付"}, function(index){
                layer.close(index);
                user_wechat_pay(pay_value);
            });
        }
    }
    //支付模块
    function user_modal_order_pay_now(pay_value) {
        if (/MicroMessenger/.test(window.navigator.userAgent)) {
            pay_value['wechat_pay_type'] = 'JSAPI';
            pay_value['wechat_pay_limit'] = true;
            user_wechat_pay(pay_value);
        } else if (/AlipayClient/.test(window.navigator.userAgent)) {
            layer.confirm("暂时不支持", {offset: '100px',icon: 3, title:"支付宝支付"}, function(index){
                layer.close(index);
            });
        } else {
            pay_value['wechat_pay_type'] = 'NATIVE';//支付方式
            pay_value['wechat_pay_limit'] = false;//支付限制
            user_wechat_pay(pay_value);
        }
    }
    //上传照片
    function open_upphoto_layer(url, title,event) {

        layer.open({title: title || '窗口', type: 2, area: ['320px', '430px'], fix: true, maxmin: false, content: url,end:function () {
            var $ = layui.$
            var handle_status = $("#handle_status").val();
            if(handle_status!=undefined && handle_status!='')
            {
                event(handle_status)
            }
        }});
    }
</script>