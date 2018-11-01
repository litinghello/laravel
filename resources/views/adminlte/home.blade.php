 {{--resources/views/admin/dashboard.blade.php --}}
@extends('adminlte::page')
@section('content_header')
    <h1>代缴订单</h1>
@stop
@component('layouts.resources')
@endcomponent
@section('content')
    <script type="text/javascript" src="{{ URL::asset('layui/lay/modules/layer.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('layui/layui.js') }}"></script>
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
        {{--<div class="layui-card-header layuiadmin-card-header-auto">--}}
            {{--<div class="layui-btn-group">--}}
                    {{--<button class="layui-btn layui-btn-sm layui-btn-danger" id="listDelete">删 除</button>--}}
                    {{--<a class="layui-btn layui-btn-sm" href="">添 加</a>--}}

            {{--</div>--}}
        {{--</div>--}}
        {{--<div class="layui-card-body">--}}
            <table id="tab" lay-filter='dataTable' class="layui-table"></table>
            <script type="text/html" id="options">
                <a class="layui-btn layui-btn-primary layui-btn-xs " style="margin-top:2px;" lay-event="finish">完成</a>
            </script>
        {{--</div>--}}
    </div>
    @component('layouts.modal')
    @endcomponent
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
                ,url: "{{ route('adminltes.table.home') }}" //数据接口
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
                         headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                         url:"{{route('adminltes.table.complete')}}",
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
                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                    url:"{{route('adminltes.table.data.detail')}}",
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

    {{--<p>:</p>--}}
    {{--<div class="row center-block">--}}
        {{--<table id="table_info" class="table table-striped table-hover table-condensed">--}}
        {{--<table id="table_info" class="table table-striped table-bordered" style="width:100%">--}}
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




    {{--@if(!count($list))--}}
        {{--<p class="help-block text-center well">没 有 记 录 哦！</p>--}}
    {{--@else--}}
    {{--<div class="table-responsive table-bordered">--}}

        {{--<table class="table table-striped table-hover">--}}
        {{--<thead>--}}
        {{--<tr>--}}
        {{--<tr >--}}
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
        {{--<tbody>--}}

            {{--@foreach ($list as &$data)--}}
                {{--<tr onclick="dataClick({{json_encode($data)}})">--}}
                {{--<td class="text-center">{{ $data->id }}</td>--}}
                {{--<td>{{ $data->order_number }}</td>--}}
                {{--<td>{{ $data->order_money }}</td>--}}
                {{--<td>{{ $data->order_src_id }}</td>--}}
                {{--<td>{{ $data->order_user_id }}</td>--}}
                {{--<td>@if($data->order_status=='paid')已支付@elseif($data->order_status=='unpaid')未支付@elseif($data->order_status=='invalid')无效@elseif($data->order_status=='processing')正在处理@elseif($data->order_status=='completed')处理完成@endif</td>--}}
                {{--<td>{{ $data->created_at }}</td>--}}
                {{--<td>{{ $data->updated_at }}</td>--}}
                {{--<td><a href="{{route('adminltes.table.complete', ['id'=>$data->id,'order_number'=>$data->order_number])}}" class="btn btn-xs btn-primary">完成</a></td>--}}
                {{--</tr>--}}
            {{--@endforeach--}}
        {{--</tbody>--}}

        {{--</table>--}}

        {{--@if(isset($page)){!!$page!!}@endif--}}
    {{--</div>--}}
    {{--@endif--}}

    {{--@component('layouts.modal')--}}
    {{--@endcomponent--}}
    {{--<script>--}}
        {{--function dataClick(data){--}}
            {{--$.ajax({--}}
                {{--type: "POST",--}}
                {{--headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},--}}
                {{--url: "{{route('adminltes.table.data.detail')}}",--}}
                {{--data: data,--}}
                {{--success: function (data) {--}}
                    {{--if (data['status'] === 0) {--}}
                        {{--var html = "<div>决定书编号:" + data['data']['penalty_number'] + "</div>"--}}
                        {{--html += "<div>车牌号:" + data['data']['penalty_car_number'] + "</div>"--}}
                        {{--html += "<div>金额:" + data['data']['penalty_money'] + "</div>"--}}
                        {{--html += "<div>姓名:" + data['data']['penalty_user_name'] + "</div>";--}}
                        {{--user_modal_show('详情', html)--}}
                    {{--} else {--}}
                        {{--user_modal_warning(data['data']);--}}
                    {{--}--}}
                {{--},--}}
                {{--error: function (error) {--}}
                    {{--user_modal_warning("请再次提交");--}}
                {{--}--}}
            {{--})--}}
        {{--}--}}
    {{--</script>--}}






    {{----}}
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
                    {{--{ data: 'order_number', name: 'order_number' },--}}
                    {{--{ data: 'order_money', name: 'order_money' },--}}
                    {{--{ data: 'order_src_id', name: 'order_src_id' },--}}
                    {{--{ data: 'order_user_id', name: 'order_user_id' },--}}
                    {{--{ data: 'order_status', name: 'order_status' },--}}
                    {{--{ data: 'created_at', name: 'created_at' },--}}
                    {{--{ data: 'updated_at', name: 'updated_at' },--}}
                    {{--{data: 'action', name: 'action', orderable: false, searchable: false}--}}
                {{--],--}}
                {{--language: {--}}
                    {{--lengthMenu: '<select class="form-control input-xsmall">' + '<option value="1">1</option>' + '<option value="10">10</option>' + '<option value="20">20</option>' + '<option value="30">30</option>' + '<option value="40">40</option>' + '<option value="50">50</option>' + '</select>条记录',//左上角的分页大小显示。--}}
                    {{--search: '<span class="btn btn-xs btn-primary">搜索：</span>',//右上角的搜索文本，可以写html标签--}}
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