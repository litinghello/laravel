<script type="text/javascript">
    function user_order_create(order_data){
        $.ajax({
            headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
            url:"<?php echo e(route('order.create')); ?>",
            type:"POST",
            data:order_data,
            success:function(data){
                if(data['status'] === 0){
                    user_modal_prompt("创建成功，跳转订单页面！");
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