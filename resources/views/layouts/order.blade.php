<script type="text/javascript">
    function user_order_create(order_data){
        $.ajax({
            headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
            url:"{{route('order.create')}}",
            type:"POST",
            data:order_data,
            success:function(data){
                if(data['status'] === 0){
                    // user_modal_prompt("创建成功，是否返回支付页面");
                    user_modal_comfirm("订单创建成功，是否返回支付页面？",function () {
                        {{--window.location.replace("{{route('views.home')}}");--}}
                        window.location.replace("{{route('order.get')}}");
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
</script>