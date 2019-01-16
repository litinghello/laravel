<?php $__env->startSection('content_header'); ?>
    <h1>支付订单</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startComponent('layouts.resources'); ?>
<?php echo $__env->renderComponent(); ?>
<?php $__env->startSection('content'); ?>
    <?php if(!count($list)): ?>
        <p class="help-block text-center well">没 有 记 录 哦！</p>
    <?php else: ?>
        <div class="table-responsive table-bordered">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                <tr >
                    <th>订单号</th>
                    <th>订单金额</th>
                    
                    <th>状态</th>
                    <th>照片</th>
                    <th>时间</th>
                </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as &$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr onclick="dataClick(<?php echo e(json_encode($data)); ?>)">
                        <td><?php echo e($data->order_number); ?></td>
                        <td><?php echo e($data->order_money); ?></td>
                        
                        <td><?php if($data->order_status=='paid'): ?>已支付<?php elseif($data->order_status=='unpaid'): ?>未支付<?php elseif($data->order_status=='invalid'): ?>无效<?php elseif($data->order_status=='processing'): ?>正在处理<?php elseif($data->order_status=='completed'): ?>处理完成<?php endif; ?></td>
                        <td><?php if($data->order_status=='paid'): ?>传照片<?php else: ?> 无照片 <?php endif; ?></td>
                        <td><?php echo e($data->updated_at); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
            <?php if(isset($page)): ?><?php echo $page; ?><?php endif; ?>
        </div>
    <?php endif; ?>
<?php $__env->stopSection(); ?>
<?php $__env->startComponent('layouts.modal'); ?>
<?php echo $__env->renderComponent(); ?>
<?php $__env->startComponent('layouts.wechat'); ?>
<?php echo $__env->renderComponent(); ?>
<?php $__env->startComponent('layouts.floatmenu'); ?>
<?php echo $__env->renderComponent(); ?>
<script>
    $(document).ready(function() {
        user_float_menu_select(0);
    });
    function dataClick(data) {
        console.log(data);
        switch(data.order_status){
            case 'unpaid':
                let body = "<div>金额:" + data['order_money'] + "元</div>" +
                    "<div>电话:" + data['order_phone_number'] + "</div>" + "<br>是否确认支付？";
                let pay_value = {
                    order_number: data.order_number,
                    order_money: parseFloat(data.order_money),
                    order_src_type: data.order_src_type,
                    order_src_id: data.order_src_id,
                    order_phone_number: data.order_phone_number
                };
                user_modal_order_pay(body, pay_value);
                break;
            case 'paid':
                open_upphoto_layer('<?php echo e(url('driving/upfile')); ?>','上传行驶证正面照片',function (d) {
                    if (d !== undefined && d !== '') {
                        $.ajax({
                            type: 'POST',
                            data: {'order_src_id': data.id, 'img': d},
                            url: "<?php echo e(route('driving.upfile_suc')); ?>",
                            headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"}
                        })
                    }
                });
                break;
            default:
                break;
        }

    }
</script>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>