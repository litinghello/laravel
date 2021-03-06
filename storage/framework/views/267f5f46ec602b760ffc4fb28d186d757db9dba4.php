<?php $__env->startSection('content_header'); ?>
    <h1>代缴订单</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<?php echo $__env->yieldSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
<?php echo $__env->yieldSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">订单状态</div>
                <div class="card-body">
                    <?php if(session('status')): ?>
                        <div class="alert alert-success" role="alert">
                            <?php echo e(session('status')); ?>

                        </div>
                    <?php endif; ?>
                        <div class="row center-block">
                            <table id="table_info" class="table table-striped table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>订单号</th>
                                    <th>金额</th>
                                    <th>订单状态</th>
                                    <th>时间</th>
                                    <th>操作</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $('#table_info').DataTable( {
                                    "processing": true,
                                    "serverSide": true,
                                    "ajax": {
                                        "url":"<?php echo e(route('penalties.order.data')); ?>",
                                        "type": "POST",
                                        "headers": {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                                    },
                                    columns: [
                                        // { data: 'id', name: 'id' },
                                        { data: 'order_number', name: 'order_number' },
                                        { data: 'order_money', name: 'order_money' },
                                        // { data: 'order_penalty_number', name: 'order_penalty_number' },
                                        { data: 'order_status', name: 'order_status' },
                                        { data: 'updated_at', name: 'updated_at' },
                                        {data: 'action', name: 'action', orderable: false, searchable: false}
                                    ],
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
                                        info: "总共_PAGES_ 页，显示第_START_ 到第 _END_ ，筛选之后得到 _TOTAL_ 条，初始_MAX_ 条 ",//左下角的信息显示，大写的词为关键字。
                                        infoEmpty: "0条记录",//筛选为空时左下角的显示。
                                        infoFiltered: ""//筛选之后的左下角筛选提示，
                                    },
                                    paging: true,
                                    pagingType: "full_numbers"//分页样式的类型
                                });
                            });
                            $("#table_info_filter input[type=search]").css({ width: "auto" });
                        </script>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>



<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>