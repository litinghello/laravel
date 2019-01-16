
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
                    {{--<th>联系电话</th>--}}
                    <th>状态</th>
                    <th>照片</th>
                    <th>时间</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($list as &$data)
                    <tr onclick="dataClick({{json_encode($data)}})">
                        <td>{{ $data->order_number }}</td>
                        <td>{{ $data->order_money }}</td>
                        {{--<td>{{ $data->order_phone_number }}</td>--}}
                        <td>@if($data->order_status=='paid')已支付@elseif($data->order_status=='unpaid')未支付@elseif($data->order_status=='invalid')无效@elseif($data->order_status=='processing')正在处理@elseif($data->order_status=='completed')处理完成@endif</td>
                        <td>@if($data->order_status=='paid')传照片@else 无照片 @endif</td>
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
@component('layouts.floatmenu')
@endcomponent
<script>
    $(document).ready(function() {
        user_float_menu_select(0);
    });
    function dataClick(data) {
        console.log(data);
        switch(data.order_status){
            case 'unpaid':
                let body = "<div>金额:" + data['order_money'] + "元</div>" +
                    "<div>电话:" + data['order_phone_number'] + "</div>" + "<br>是否确认支付？";
                let pay_value = {
                    order_number: data.order_number,
                    order_money: parseFloat(data.order_money),
                    order_src_type: data.order_src_type,
                    order_src_id: data.order_src_id,
                    order_phone_number: data.order_phone_number
                };
                user_modal_order_pay(body, pay_value);
                break;
            case 'paid':
                open_upphoto_layer('{{url('driving/upfile')}}','上传行驶证正面照片',function (d) {
                    if (d !== undefined && d !== '') {
                        $.ajax({
                            type: 'POST',
                            data: {'order_src_id': data.id, 'img': d},
                            url: "{{route('driving.upfile_suc')}}",
                            headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"}
                        })
                    }
                });
                break;
            default:
                break;
        }

    }
</script>
