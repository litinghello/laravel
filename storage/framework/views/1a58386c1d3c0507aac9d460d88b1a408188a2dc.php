<?php $__env->startSection('content_header'); ?>
    <h1>代缴订单</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startComponent('layouts.resources'); ?>
<?php echo $__env->renderComponent(); ?>


<?php $__env->startSection('content'); ?>
    <p>:</p>
    
        
            
            
                
                
                
                
                
                
                
                
                
            
            
        
    

    <?php if(!count($list)): ?>
        <p class="help-block text-center well">没 有 记 录 哦！</p>
    <?php else: ?>
    <div class="table-responsive table-bordered">

        <table class="table table-striped table-hover">
        <thead>
        <tr>
        <tr >
            <th>编号</th>
            <th>订单号</th>
            <th>订单金额</th>
            <th>决定书编号</th>
            <th>用户ID</th>
            <th>订单状态</th>
            <th>更新时间</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>

            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as &$data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr onclick="dataClick(<?php echo e(json_encode($data)); ?>)">
                <td class="text-center"><?php echo e($data->id); ?></td>
                <td><?php echo e($data->order_number); ?></td>
                <td><?php echo e($data->order_money); ?></td>
                <td><?php echo e($data->order_src_id); ?></td>
                <td><?php echo e($data->order_user_id); ?></td>
                <td><?php echo e($data->order_status); ?></td>
                <td><?php echo e($data->created_at); ?></td>
                <td><?php echo e($data->updated_at); ?></td>
                <td><a href="<?php echo e(route('adminltes.table.complete', ['id'=>$data->id,'order_number'=>$data->order_number])); ?>" class="btn btn-xs btn-primary">完成</a></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>

        </table>

        <?php if(isset($page)): ?><?php echo $page; ?><?php endif; ?>
    </div>
    <?php endif; ?>

    <?php $__env->startComponent('layouts.modal'); ?>
    <?php echo $__env->renderComponent(); ?>
    <script>
        function dataClick(data){
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                url: "<?php echo e(route('adminltes.table.data.detail')); ?>",
                data: data,
                success: function (data) {
                    if (data['status'] === 0) {
                        var html = "<div>决定书编号:" + data['data']['penalty_number'] + "</div>"
                        html += "<div>车牌号:" + data['data']['penalty_car_number'] + "</div>"
                        html += "<div>金额:" + data['data']['penalty_money'] + "</div>"
                        html += "<div>姓名:" + data['data']['penalty_user_name'] + "</div>";
                        user_modal_show('详情', html)
                    } else {
                        user_modal_warning(data['data']);
                    }
                },
                error: function (error) {
                    user_modal_warning("请再次提交");
                }
            })
        }
    </script>


    

        
            
                
                
                
                    
                    
                    
                
                
                    
                    
                    
                    
                    
                    
                    
                    
                    
                
                
                    
                    
                    
                        
                        
                        
                        
                    
                    
                    
                    
                    
                    
                
                
                
            
                
                
                
                    
                    
                    
                    
                    
                        
                            
                            
                            
                            
                            
                        
                            
                        
                    
                    
                        
                    
                

            
        
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>