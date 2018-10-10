<?php $__env->startSection('content_header'); ?>
    <h1>代缴订单</h1>
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
    
    
        
            
            
                
                
                
                
                
                
                
                
                
            
            
        
    
    <div id="card_body_datatables" class="card-body hidden" >
        <div class="row center-block">
            <table id="datalables_body" class="dataTables_wrapper form-inline" style="width:100%">
                <thead id="datalables_body_thead">
                <tr id="datalables_body_thead_tr"></tr>
                </thead>
            </table>
        </div>
    </div>
<script type="text/javascript" >
    // var user_datatables_object;
    function user_datatables_hidden() {
        $("#card_body_datatables").hide();
    }function user_datatables_show() {
        $("#card_body_datatables").show();
    }
    function user_datatables_init(object,data,click_event) {
        //var info_object_key = new Array();
        var info_object_columns = new Array();
        $("#datalables_body_thead_tr").html("");
        for(key in object){
            //info_object_key.push(key);
            info_object_columns.push({data:key});
            $("#datalables_body_thead_tr").append("<th>"+info_object[key]+"</th>");
        }
        var user_datatables_object = $('#datalables_body').DataTable( {
            "scrollX": true,
            "scrollY": true,
            "processing": true,
            // "serverSide": true,
            // "ajax": {
            //     "url":object.url,
            //     "type": "POST",
            //     "headers": {'X-CSRF-TOKEN': object.csrf_token},
            // },
            data:data,
            columns: info_object_columns,
            language: {
                lengthMenu: '<select class="form-control input-xsmall">' + '<option value="1">1</option>' + '<option value="10">10</option>' + '<option value="20">20</option>' + '<option value="30">30</option>' + '<option value="40">40</option>' + '<option value="50">50</option>' + '</select>条记录',//左上角的分页大小显示。
                search: '<span class="label label-success">搜索：</span>',//右上角的搜索文本，可以写html标签
                paginate: {//分页的样式内容。
                    previous: "上一页",
                    next: "下一页",
                    first: "第一页",
                    last: "最后"
                },
                zeroRecords: "没有内容",//table tbody内容为空时，tbody的内容。
                //下面三者构成了总体的左下角的内容。
                info: "第 _START_ - _END_ 共 _PAGES_ 页，共 _TOTAL_ 条，初始_MAX_ 条 ",//左下角的信息显示，大写的词为关键字。
                infoEmpty: "0条记录",//筛选为空时左下角的显示。
                infoFiltered: ""//筛选之后的左下角筛选提示，
            },
            paging: true,
            pagingType: "full_numbers"//分页样式的类型
        }).on('click', 'tr', function () {
            click_event(user_datatables_object.row( this ).data());
        });
    }
    $("#datalables_body input[type=search]").css({ width: "auto" });
    let info_object = {
        'order_number':'订单号',
        'order_money':'订单金额',
        'order_phone_number':'联系电话',
        'order_status':'状态',
        'updated_at':'时间',
    };
    $(document).ready(function() {

        user_datatables_init(info_object,"",function (data) {
            user_modal_warning("订单处理");
        });
        user_datatables_show();
        
            
            
            
                
                
                
            
            
                
                
                
                
                
                
                
                
                
            
            
                
                
                
                    
                    
                    
                    
                
                
                
                
                
                
            
            
            
            
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>