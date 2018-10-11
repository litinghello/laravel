<?php $__env->startSection('content_header'); ?>
    <h1>支付订单</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.css"/>
<?php echo $__env->yieldSection(); ?>
<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
<?php echo $__env->yieldSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                
                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>
                    <?php $__env->startComponent('layouts.datatables'); ?>
                    <?php echo $__env->renderComponent(); ?>
                    <?php $__env->startComponent('layouts.modal'); ?>
                    <?php echo $__env->renderComponent(); ?>
                    <?php $__env->startComponent('layouts.wechat'); ?>
                    <?php echo $__env->renderComponent(); ?>
                        <script type="text/javascript">
                            let info_object = {
                                'order_number':'订单号',
                                'order_money':'订单金额',
                                'order_phone_number':'联系电话',
                                'order_status':'状态',
                                'updated_at':'时间',
                            };
                            var order_status={
                                'invalid':"无效",
                                'unpaid':'未支付',
                                'paid':'已支付',
                                'processing':'正在处理',
                                'completed':'处理完成',
                            };
                            $(document).ready(function() {
                                $.ajax({
                                    type:"POST",
                                    headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                                    url:"<?php echo e(route('wechats.order.data')); ?>",
                                    data:"",
                                    success:function(data){
                                        if(data['status'] === 0){
                                            data['data'].forEach(function (value) {
                                                value.order_status = order_status[value.order_status];
                                            });
                                            user_datatables_init(info_object,data['data'],function (data) {
                                                user_modal_warning("订单处理");
                                            });
                                            user_datatables_show();
                                        }else{
                                            user_modal_warning(data['data']);
                                        }
                                    },
                                    error:function(error){
                                        user_modal_warning("请再次提交");
                                    }
                                });
                            });
                        </script>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>