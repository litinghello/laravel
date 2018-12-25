<script type="text/javascript">
    function user_order_create(order_data){
        $.ajax({
            headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
            url:"<?php echo e(route('order.create')); ?>",
            type:"POST",
            data:order_data,
            success:function(data){
                if(data['status'] === 0){
                    user_modal_comfirm("订单创建成功，是否返回支付页面？",function () {
                        
                        window.location.replace("<?php echo e(route('order.get')); ?>");
                    });
                }else{
                    user_modal_prompt(data['data']);
                }
            },
            error:function(error){
                user_modal_prompt("创建失败");
            }
        });
    }
    function user_order_create_pay(order_data){
        $.ajax({
            headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
            url:"<?php echo e(route('order.create')); ?>",
            type:"POST",
            data:order_data,
            success:function(data){
                if(data['status'] === 0){
                    user_modal_order_pay_now(data['data']);
                }else{
                    user_modal_prompt(data['data']);
                }
            },
            error:function(error){
                user_modal_prompt("创建失败");
            }
        });
    }
</script>