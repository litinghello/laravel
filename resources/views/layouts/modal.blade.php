
<script type="text/javascript">
    function user_modal_hide(){
    }
    function user_modal_show(title,body){
        layer.confirm(body, {icon: 3, title:title}, function(index){
            //do something
            layer.close(index);
        });
    }
    function user_modal_prompt(body){
        layer.confirm(body, {icon: 3, title:title}, function(index){
            //do something
            layer.close(index);
        });
    }
    function user_modal_comfirm(body,event){
        layer.confirm(body, {icon: 3, title:"提示"}, function(index){
            event();
            layer.close(index);
        });
    }
    function user_modal_warning(html){
        layer.alert(html);
    }
    function user_modal_input(title,name,event) {
        layer.prompt({
            formType: 0,
            // value: '初始值',
            title: '请输入手机号码',
             // area: ['60%', '30%'] //自定义文本域宽高
        }, function(value, index, elem){
            event(value); //得到value
            layer.close(index);
        });
    }
</script>