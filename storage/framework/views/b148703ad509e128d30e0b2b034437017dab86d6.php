
<div class="row">
    <div id="user_float_menu" class="row text-center navbar-fixed-bottom">
        
        
        
        <ul class="layui-nav layui-bg-cyan" lay-filter="" lay-separator="|">
            <li class="layui-nav-item"><a href="<?php echo e(route('order.get')); ?>">订单查询</a></li>
            <li class="layui-nav-item"><a href="<?php echo e(route('views.penalty.inquire')); ?>">代缴罚款</a></li>
            <li class="layui-nav-item "><a href="<?php echo e(route('views.violate.inquire')); ?>">违章查询</a></li>
        </ul>
    </div>

</div>
<script type="text/javascript">
    function user_device_is_phone(){
        let status = false;
        ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"].some(function (value) {
            if(navigator.userAgent.indexOf(value) > 0){
                return status = true;
            }
        });
        return status;
    }
    if(!user_device_is_phone()){
        document.getElementById("user_float_menu").style.display = "none";
    }else{
        document.getElementById("user_float_menu").style.display = "inline";
    }

</script>