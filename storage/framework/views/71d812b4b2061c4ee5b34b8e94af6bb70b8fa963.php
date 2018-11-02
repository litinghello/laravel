<?php $__env->startSection('content_header'); ?>
    <h1>罚款查询</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startComponent('layouts.resources'); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                
                <div id="card_body_input" class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('决定书编号')); ?></div>
                            <input id="penalty_number" type="text" class="form-control" name="penalty_number" placeholder="请输入15-16位处罚决定书编号" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="text-center">
                            <button id="penalty_submit" type="button" class="btn btn-primary">确认</button>
                            
                        </div>
                    </div>
                </div>
                <?php $__env->startComponent('layouts.datatables'); ?>
                <?php echo $__env->renderComponent(); ?>
                <?php $__env->startComponent('layouts.modal'); ?>
                <?php echo $__env->renderComponent(); ?>
                <?php $__env->startComponent('layouts.order'); ?>
                <?php echo $__env->renderComponent(); ?>
                <?php $__env->startComponent('layouts.wechat'); ?>
                <?php echo $__env->renderComponent(); ?>
                <?php $__env->startComponent('layouts.floatmenu'); ?>
                <?php echo $__env->renderComponent(); ?>
            </div>
            <script type="text/javascript">
                let info_object = {
                    'penalty_number':'决定书号',
                    'penalty_user_name':'车主姓名',
                    'penalty_car_number':'车牌号牌',
                    //'penalty_car_type'=>'车辆类型',
                    'penalty_money':'罚款额(元)',
                    'penalty_money_late':'滞纳金(元)',
                    'penalty_illegal_place':'违法地点',
                    // 'penalty_illegal_time':'违法时间',
                    'penalty_process_time':'处理时间',
                    // 'penalty_behavior':'违法行为',
                    //'penalty_money_extra':'手续费',
                    // 'penalty_phone_number':'手机号码',
                };
                $(document).ready(function() {
                    $("#penalty_submit").click(function () {
                        user_modal_loading(0);
                        $.ajax({
                            type:"POST",
                            headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                            url:"<?php echo e(route('penalties.info')); ?>",
                            // data:{penalty_number:'5101071200480104'},
                            data:{penalty_number:$("#penalty_number").val()},
                            success:function(data){
                                user_modal_loading_close();
                                if(data['status'] === 0){
                                    let display_info = "";
                                    for(key in info_object){
                                        display_info += "<div>"+info_object[key]+":"+data['data'][0][key]+"</div>";
                                    }
                                    // display_info+="<div>确认订单进行缴费！</div>";
                                    user_modal_comfirm(display_info,function () {
                                        let order_value={
                                            order_money:0,
                                            order_src_type:"penalty",
                                            order_src_id:data['data'][0]['id'],
                                            order_phone_number:"13000000000"
                                        };
                                        user_order_create_pay(order_value);
                                    });
                                }else{
                                    user_modal_warning(data['data']);
                                }
                            },
                            error:function(error){
                                user_modal_loading_close();
                                user_modal_warning("请再次提交");
                            }
                        });
                    });
                });
            </script>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>