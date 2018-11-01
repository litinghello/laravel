 

<?php $__env->startSection('content_header'); ?>
    <h1>代缴订单</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startComponent('layouts.resources'); ?>
<?php echo $__env->renderComponent(); ?>
<?php $__env->startSection('content'); ?>
    <script type="text/javascript" src="<?php echo e(URL::asset('layui/lay/modules/layer.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(URL::asset('layui/layui.js')); ?>"></script>
    <form autocomplete="off" class="layui-form layui-form-pane form-search" action="" onsubmit="return false" method="get">
        <div class="layui-form-item layui-inline">
            <label class="layui-form-label">订单号</label>
            <div class="layui-input-inline">
                <input name="ddh" id="ddh" value="" placeholder="请输入订单号" class="layui-input" autocomplete="off">
            </div>
        </div>
        <div class="layui-form-item layui-inline">
            <label class="layui-form-label">创建时间</label>
            <div class="layui-input-inline">
                <input name="date" id='range-date'  placeholder="请选择创建时间" class="layui-input">
            </div>
        </div>
        <div class="layui-form-item layui-inline" style="margin-top: -6px;">
            <button id="search" class="layui-btn layui-btn-primary" data-type="reload"><i class="layui-icon">&#xe615;</i> 搜 索</button>
        </div>
    </form>
    <div class="layui-card">
        
            
                    
                    

            
        
        
            <table id="tab" lay-filter='dataTable' class="layui-table"></table>
            <script type="text/html" id="options">
                <a class="layui-btn layui-btn-primary layui-btn-xs " style="margin-top:2px;" lay-event="finish">完成</a>
            </script>
        
    </div>
    <?php $__env->startComponent('layouts.modal'); ?>
    <?php echo $__env->renderComponent(); ?>
    <script>

        layui.use(['layer','table','form','laydate'],function () {
            var layer = layui.layer;
            var form = layui.form;
            var table = layui.table;
            var $ = layui.$
            var laydate = layui.laydate;
            //时间选择器
            laydate.render({range: true, elem: '#range-date'});
            //用户表格初始化
            table.render({
                elem: '#tab',
                id:'tab'
//                ,toolbar: '#options'
                ,height: 500
                ,url: "<?php echo e(route('adminltes.table.home')); ?>" //数据接口
//                ,where:{model:"role"}true
                ,page: true //开启分页
                ,cols: [[ //表头
//                    {checkbox:true ,fixed: true},
//加逗号显示异常
                    {field: 'id', title: '编号', sort: true,width:80}
                    ,{field: 'order_number', sort: true,title: '订单号'}
                    ,{field: 'order_money', sort: true,title: '订单金额'}
                    ,{field: 'order_src_id',sort: true, title: '决定书编号'}
                    ,{field: 'order_status', sort: true,title: '订单状态' ,templet:function(d){
                        if(d.order_status=='paid')
                        {
                            return '已支付'
                        }else if(d.order_status == 'unpaid'){
                            return '未支付'
                        }else if(d.order_status == 'invalid')
                        {
                            return '无效'
                        }else if(d.order_status == 'processing')
                        {
                            return '正在处理'
                        }else if(d.order_status == 'completed')
                        {
                            return '处理完成'
                        }
                    }}
                    ,{field: 'created_at',sort: true, title: '创建时间'}
                    ,{field: 'updated_at', sort: true,title: '更新时间'}

                    ,{title:'操作', width: 100, align:'center', templet: '#options'}
                ]]
            });
            //监听工具条
            table.on("tool(dataTable)",function(obj){
                var data = obj.data //获得当前行数据
                layEvent = obj.event; //获得 lay-event 对应的值
//                console.log(obj.tr);
//                layui.stope(obj.tr)
                 if(layEvent === 'finish') {
                     $.ajax({
                         type:'GET',
                         data:{id:data.id,order_number:data.order_number},
                         headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                         url:"<?php echo e(route('adminltes.table.complete')); ?>",
                         success:function (data) {
                             if(data['state']=='0')
                             {
                                 $(".layui-laypage-btn").click()
                             }
                         },
                         error:function (error) {
                             user_modal_warning("请再次提交1");
                         }
                     })

                }
            });
            //监听行事件
            table.on('rowDouble(dataTable)', function(obj){
                var data = obj.data;
                console.log(obj.tr[0]);
                $.ajax({
                    type:"POST",
                    headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                    url:"<?php echo e(route('adminltes.table.data.detail')); ?>",
                    data:data,
                    success:function(data){
//                        console.log(data);
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
                //标注选中样式
                obj.tr.addClass('layui-table-click').siblings().removeClass('layui-table-click');
            });
            var $ = layui.$,active = {
                reload:function () {
                    var input = $('#ddh')
                    var date = $('#range-date')

                    table.reload('tab',{
                        page: {
                            curr: 1 //重新从第 1 页开始
                        },
                        where:{
                            order_number:input.val(),
                            date:date.val()
                        }
                    })
                }
            };
            $('#search').on('click', function(){
                var type = $(this).data('type');
                active[type] ? active[type].call(this) : '';
            });
        })
    </script>

    
    
        
        
            
            
                
                
                
                
                
                
                
                
                
            
            
        
    




    
        
    
    

        
        
        
        
            
            
            
            
            
            
            
            
            
        
        
        

            
                
                
                
                
                
                
                
                
                
                
                
            
        

        

        
    
    

    
    
    
        
            
                
                
                
                
                
                    
                        
                        
                        
                        
                        
                    
                        
                    
                
                
                    
                
            
        
    






    
    

        
            
                
                
                
                    
                    
                    
                
                
                    
                    
                    
                    
                    
                    
                    
                    
                    
                
                
                    
                    
                    
                        
                        
                        
                        
                    
                    
                    
                    
                    
                    
                
                
                
            
                
                
                
                    
                    
                    
                    
                    
                        
                            
                            
                            
                            
                            
                        
                            
                        
                    
                    
                        
                    
                

            
        
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>