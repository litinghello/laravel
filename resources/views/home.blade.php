
{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1>支付订单</h1>
@stop
@component('layouts.resources')
@endcomponent
@section('content')

    @if(!count($list))
        <p class="help-block text-center well">没 有 记 录 哦！</p>
    @else
        <div class="table-responsive table-bordered">

            <table class="table table-striped table-hover">
                <thead>
                <tr>
                <tr >
                    <th>订单号</th>
                    <th>订单金额</th>
                    <th>联系电话</th>
                    <th>状态</th>
                    <th>时间</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($list as &$data)
                    <tr onclick="dataClick({{json_encode($data)}})">
                        <td>{{ $data->order_number }}</td>
                        <td>{{ $data->order_money }}</td>
                        <td>{{ $data->order_phone_number }}</td>
                        <td>@if($data->order_status=='paid')已支付@elseif($data->order_status=='unpaid')未支付@elseif($data->order_status=='invalid')无效@elseif($data->order_status=='processing')正在处理@elseif($data->order_status=='completed')处理完成@endif</td>
                        <td>{{ $data->updated_at }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @if(isset($page)){!!$page!!}@endif
        </div>
    @endif
@endsection
@component('layouts.modal')
@endcomponent
@component('layouts.wechat')
@endcomponent
<script>
    function dataClick(data){
        let body = "<div>金额:"+data['order_money']+"元</div>"+
            "<div>电话:"+data['order_phone_number']+"</div>"+"<br>是否确认支付？";
        let pay_value={
            order_money:parseInt(data.order_money),
            order_src_type:data.order_src_type,
            order_src_id:data.order_src_id,
            order_phone_number:data.order_phone_number,
            wechat_pay_type:'JSAPI'
        };
        user_modal_order_pay(body,pay_value);
    }
</script>


{{--<div class="container">--}}
    {{--<div class="row justify-content-center">--}}
        {{--<div class="col-md-8">--}}
            {{--<div class="card">--}}
                {{--<div class="card-header">订单状态</div>--}}
                {{--<div class="card-body">--}}
                    {{--@if (session('status'))--}}
                        {{--<div class="alert alert-success" role="alert">--}}
                            {{--{{ session('status') }}--}}
                        {{--</div>--}}
                    {{--@endif--}}
                    {{--@component('layouts.datatables')--}}
                    {{--@endcomponent--}}
                    {{--@component('layouts.modal')--}}
                    {{--@endcomponent--}}
                    {{--@component('layouts.wechat')--}}
                    {{--@endcomponent--}}
                        {{--<script type="text/javascript">--}}
                            {{--let info_object = {--}}
                                {{--'order_number':'订单号',--}}
                                {{--'order_money':'订单金额',--}}
                                {{--'order_phone_number':'联系电话',--}}
                                {{--'order_status':'状态',--}}
                                {{--'updated_at':'时间',--}}
                            {{--};--}}
                            {{--var order_status={--}}
                                {{--'invalid':"无效",--}}
                                {{--'unpaid':'未支付',--}}
                                {{--'paid':'已支付',--}}
                                {{--'processing':'正在处理',--}}
                                {{--'completed':'处理完成',--}}
                            {{--};--}}
                            {{--$(document).ready(function() {--}}
                                {{--$.ajax({--}}
                                    {{--type:"POST",--}}
                                    {{--headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},--}}
                                    {{--url:"{{route('order.get')}}",--}}
                                    {{--data:"",--}}
                                    {{--success:function(data){--}}
                                        {{--if(data['status'] === 0){--}}
                                            {{--data['data'].forEach(function (value) {--}}
                                                {{--value.order_status = order_status[value.order_status];--}}
                                            {{--});--}}
                                            {{--user_datatables_init(info_object,data['data'],function (data) {--}}
                                                {{--let html = "<div>金额:"+data['order_money']+"元</div>"+--}}
                                                            {{--"<div>电话:"+data['order_phone_number']+"</div>"+"<br>是否确认支付？";--}}
                                                {{--user_modal_comfirm(html,function () {--}}
                                                    {{--// user_modal_warning("订单处理");--}}
                                                    {{--// console.log(data);--}}
                                                    {{--let pay_value={--}}
                                                        {{--order_money:parseInt(data.order_money),--}}
                                                        {{--order_src_type:data.order_src_type,--}}
                                                        {{--order_src_id:data.order_src_id,--}}
                                                        {{--order_phone_number:data.order_phone_number,--}}
                                                    {{--};--}}
                                                    {{--// user_modal_hide();//关闭弹出框--}}
                                                    {{--user_wechat_pay(pay_value);--}}
                                                {{--});--}}
                                            {{--});--}}
                                            {{--user_datatables_show();--}}
                                        {{--}else{--}}
                                            {{--user_modal_warning(data['data']);--}}
                                        {{--}--}}
                                    {{--},--}}
                                    {{--error:function(error){--}}
                                        {{--user_modal_warning("请再次提交");--}}
                                    {{--}--}}
                                {{--});--}}
                            {{--});--}}
                        {{--</script>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
{{--@endsection--}}


