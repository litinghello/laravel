{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('content_header')
    <h1>代缴订单</h1>
@stop

@component('layouts.resources')
@endcomponent


@section('content')
    <p>:</p>
    {{--<div class="row center-block">--}}
        {{--<table id="table_info" class="table table-striped table-hover table-condensed">--}}
            {{--<thead>--}}
            {{--<tr>--}}
                {{--<th>编号</th>--}}
                {{--<th>订单号</th>--}}
                {{--<th>订单金额</th>--}}
                {{--<th>决定书编号</th>--}}
                {{--<th>用户ID</th>--}}
                {{--<th>订单状态</th>--}}
                {{--<th>更新时间</th>--}}
                {{--<th>创建时间</th>--}}
                {{--<th>操作</th>--}}
            {{--</tr>--}}
            {{--</thead>--}}
        {{--</table>--}}
    {{--</div>--}}

    @if(!count($list))
        <p class="help-block text-center well">没 有 记 录 哦！</p>
    @else
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

            @foreach ($list as &$data)
                <tr onclick="dataClick({{json_encode($data)}})">
                <td class="text-center">{{ $data->id }}</td>
                <td>{{ $data->order_number }}</td>
                <td>{{ $data->order_money }}</td>
                <td>{{ $data->order_src_id }}</td>
                <td>{{ $data->order_user_id }}</td>
                <td>@if($data->order_status=='paid')已支付@elseif($data->order_status=='unpaid')未支付@elseif($data->order_status=='invalid')无效@elseif($data->order_status=='processing')正在处理@elseif($data->order_status=='completed')处理完成@endif</td>
                <td>{{ $data->created_at }}</td>
                <td>{{ $data->updated_at }}</td>
                <td><a href="{{route('adminltes.table.complete', ['id'=>$data->id,'order_number'=>$data->order_number])}}" class="btn btn-xs btn-primary">完成</a></td>
                </tr>
            @endforeach
        </tbody>

        </table>

        @if(isset($page)){!!$page!!}@endif
    </div>
    @endif

    @component('layouts.modal')
    @endcomponent
    <script>
        function dataClick(data){
            $.ajax({
                type: "POST",
                headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                url: "{{route('adminltes.table.data.detail')}}",
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


    {{--<script type="text/javascript" >--}}

        {{--$(document).ready(function() {--}}
            {{--var user_datatables_object = $('#table_info').DataTable( {--}}
                {{--"processing": true,--}}
                {{--"serverSide": true,--}}
                {{--"ajax": {--}}
                    {{--"url":"{{ route('adminltes.table.data') }}",--}}
                    {{--"type": "POST",--}}
                    {{--"headers": {'X-CSRF-TOKEN': "{{csrf_token()}}"},--}}
                {{--},--}}
                {{--columns: [--}}
                    {{--{ data: 'id', name: 'id' },--}}
                    {{--{ data: 'order_number', name: 'name' },--}}
                    {{--{ data: 'order_money', name: 'email' },--}}
                    {{--{ data: 'order_src_id', name: 'order_src_id' },--}}
                    {{--{ data: 'order_user_id', name: 'order_user_id' },--}}
                    {{--{ data: 'order_status', name: 'order_status' },--}}
                    {{--{ data: 'created_at', name: 'created_at' },--}}
                    {{--{ data: 'updated_at', name: 'updated_at' },--}}
                    {{--{data: 'action', name: 'action', orderable: false, searchable: false}--}}
                {{--],--}}
                {{--language: {--}}
                    {{--lengthMenu: '<select class="form-control input-xsmall">' + '<option value="1">1</option>' + '<option value="10">10</option>' + '<option value="20">20</option>' + '<option value="30">30</option>' + '<option value="40">40</option>' + '<option value="50">50</option>' + '</select>条记录',//左上角的分页大小显示。--}}
                    {{--search: '<span class="label label-success">搜索：</span>',//右上角的搜索文本，可以写html标签--}}
                    {{--paginate: {//分页的样式内容。--}}
                        {{--previous: "上一页",--}}
                        {{--next: "下一页",--}}
                        {{--first: "第一页",--}}
                        {{--last: "最后"--}}
                    {{--},--}}
                    {{--zeroRecords: "没有内容",//table tbody内容为空时，tbody的内容。--}}
                    {{--//下面三者构成了总体的左下角的内容。--}}
                    {{--info: "总共_PAGES_ 页，显示第_START_ 到第 _END_ ，筛选之后得到 _TOTAL_ 条，初始_MAX_ 条 ",//左下角的信息显示，大写的词为关键字。--}}
                    {{--infoEmpty: "0条记录",//筛选为空时左下角的显示。--}}
                    {{--infoFiltered: ""//筛选之后的左下角筛选提示，--}}
                {{--},--}}
                {{--paging: true,--}}
                {{--pagingType: "full_numbers",//分页样式的类型--}}
            {{--}).on('click', 'tr', function () {--}}
                {{--var info = user_datatables_object.row( this ).data();--}}
                {{--console.log(info);--}}
                {{--$.ajax({--}}
                    {{--type:"POST",--}}
                    {{--headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},--}}
                    {{--url:"{{route('adminltes.table.data.detail')}}",--}}
                    {{--data:info,--}}
                    {{--success:function(data){--}}
                        {{--if(data['status'] === 0){--}}
                            {{--var html="<div>决定书编号:"+data['data']['penalty_number']+"</div>"--}}
                            {{--html+= "<div>车牌号:"+data['data']['penalty_car_number']+"</div>"--}}
                            {{--html+= "<div>金额:"+data['data']['penalty_money']+"</div>"--}}
                            {{--html+= "<div>姓名:"+data['data']['penalty_user_name']+"</div>";--}}
                            {{--user_modal_show('详情',html)--}}
                        {{--}else{--}}
                            {{--user_modal_warning(data['data']);--}}
                        {{--}--}}
                    {{--},--}}
                    {{--error:function(error){--}}
                        {{--user_modal_warning("请再次提交");--}}
                    {{--}--}}
                {{--});--}}

            {{--});--}}
        {{--});--}}
    {{--</script>--}}
@endsection