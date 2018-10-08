<?php $__env->startSection('content_header'); ?>
    <h1>罚款查询</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<?php echo $__env->yieldSection(); ?>
<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<?php echo $__env->yieldSection(); ?>

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
                            <a id="penalty_submit" type="button" class="btn btn-primary">确认</a>
                            
                        </div>
                    </div>
                </div>
                <?php $__env->startComponent('layouts.datatables'); ?>
                <?php echo $__env->renderComponent(); ?>
                <?php $__env->startComponent('layouts.modal'); ?>
                <?php echo $__env->renderComponent(); ?>
                <?php $__env->startComponent('layouts.wechat'); ?>
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
                    // user_datatables_show();
                    $("#penalty_submit").click(function () {
                        let post_data = {penalty_number:$("#penalty_number").val()};//获取数据
                        $.ajax({
                            type:"POST",
                            headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                            url:"<?php echo e(route('penalties.info')); ?>",
                            data:post_data,
                            success:function(data){
                                if(data['status'] === 0){
                                    user_datatables_init(info_object,data['data'],function (data) {
                                        user_modal_input("手机号码",function (value) {
                                            let pay_value={
                                                order_money:data.penalty_money+data.penalty_money_late+10,
                                                order_src_type:"penalty",
                                                order_src_id:data.penalty_number,
                                                order_phone_number:value,
                                            };
                                            user_modal_hide();//关闭弹出框
                                            user_wechat_pay(pay_value);
                                        });
                                    });
                                    user_datatables_show();
                                    $("#card_body_input").hide();
                                }else{
                                    user_modal_warning(data['data']);
                                }
                            },
                            error:function(error){
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