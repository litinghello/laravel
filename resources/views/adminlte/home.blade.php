{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')


@section('content_header')
    <h1>代缴订单</h1>
@stop

@section('content')
    <p>:</p>
    <div class="row center-block">
        <table id="table_info" class="table table-striped table-hover table-condensed">
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
@stop

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
@stop

@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" >
        $(document).ready(function() {
            $('#table_info').DataTable( {
                "processing": true,
                "serverSide": true,
                "ajax": "/laravel/adminltes/table/data",
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'order_number', order_number: 'name' },
                    { data: 'order_money', order_money: 'email' },
                    { data: 'order_penalty_number', name: 'order_penalty_number' },
                    { data: 'order_user_id', name: 'order_user_id' },
                    { data: 'order_status', name: 'order_status' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'updated_at', name: 'updated_at' },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
                });
        });
    </script>
@stop