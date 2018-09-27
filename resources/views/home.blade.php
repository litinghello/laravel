
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">订单状态</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
@show

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
                "ajax": "/penalties/order_data",
                columns: [
                    // { data: 'id', name: 'id' },
                    { data: 'order_number', name: 'order_number' },
                    { data: 'order_money', name: 'order_money' },
                    // { data: 'order_penalty_number', name: 'order_penalty_number' },
                    { data: 'order_status', name: 'order_status' },
                    { data: 'updated_at', name: 'updated_at' },
                    {data: 'action', name: 'action', orderable: false, searchable: false}
                ]
            });
        });
    </script>
@show