<?php $__env->startSection('content_header'); ?>
    <h1>代缴订单</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startComponent('layouts.resources'); ?>
<?php echo $__env->renderComponent(); ?>


<?php $__env->startSection('content'); ?>
    <p>:</p>
    <div class="row center-block">
        
        <table id="table_info" class="table table-striped table-bordered" style="width:100%">
            <thead>
            <tr>
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
        </table>
    </div>

    
        
    
    

        
        
        
        
            
            
            
            
            
            
            
            
            
        
        
        

            
                
                
                
                
                
                
                
                
                
                
                
            
        

        

        
    
    

    
    
    
        
            
                
                
                
                
                
                    
                        
                        
                        
                        
                        
                    
                        
                    
                
                
                    
                
            
        
    


    <script type="text/javascript" >

        $(document).ready(function() {
            var user_datatables_object = $('#table_info').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url":"<?php echo e(route('adminltes.table.data')); ?>",
                    "type": "POST",
                    "headers": {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'order_money', name: 'order_money' },
                    { data: 'order_src_id', name: 'order_src_id' },
                    { data: 'order_user_id', name: 'order_user_id' },
                    { data: 'order_status', name: 'order_status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ],
                language: {
                    lengthMenu: '<select class="form-control input-xsmall">' + '<option value="1">1</option>' + '<option value="10">10</option>' + '<option value="20">20</option>' + '<option value="30">30</option>' + '<option value="40">40</option>' + '<option value="50">50</option>' + '</select>条记录',//左上角的分页大小显示。
                    search: '<span class="btn btn-xs btn-primary">搜索：</span>',//右上角的搜索文本，可以写html标签
                    paginate: {//分页的样式内容。
                        previous: "上一页",
                        next: "下一页",
                        first: "第一页",
                        last: "最后"
                    },
                    zeroRecords: "没有内容",//table tbody内容为空时，tbody的内容。
                    //下面三者构成了总体的左下角的内容。
                    info: "总共_PAGES_ 页，显示第_START_ 到第 _END_ ，筛选之后得到 _TOTAL_ 条，初始_MAX_ 条 ",//左下角的信息显示，大写的词为关键字。
                    infoEmpty: "0条记录",//筛选为空时左下角的显示。
                    infoFiltered: ""//筛选之后的左下角筛选提示，
                },
                paging: true,
                pagingType: "full_numbers",//分页样式的类型
            }).on('click', 'tr', function () {
                var info = user_datatables_object.row( this ).data();
                console.log(info);
                $.ajax({
                    type:"POST",
                    headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                    url:"<?php echo e(route('adminltes.table.data.detail')); ?>",
                    data:info,
                    success:function(data){
                        if(data['status'] === 0){
                            var html="<div>决定书编号:"+data['data']['penalty_number']+"</div>"
                            html+= "<div>车牌号:"+data['data']['penalty_car_number']+"</div>"
                            html+= "<div>金额:"+data['data']['penalty_money']+"</div>"
                            html+= "<div>姓名:"+data['data']['penalty_user_name']+"</div>";
                            user_modal_show('详情',html)
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>