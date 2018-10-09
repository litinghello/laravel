{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1>罚款查询</h1>
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    {{--<link rel="stylesheet" type="text/css" href="http://cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.css"/>--}}
@show
@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    {{--<script type="text/javascript" src="http://cdn.datatables.net/plug-ins/28e7751dbec/integration/bootstrap/3/dataTables.bootstrap.js"></script>--}}
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
@show

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{--<div class="card-header">{{ __('查询记录') }}</div>--}}
                <div id="card_body_input" class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{ __('决定书编号') }}</div>
                            <input id="penalty_number" type="text" class="form-control" name="penalty_number" placeholder="请输入15-16位处罚决定书编号" value="" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="text-center">
                            <a id="penalty_submit" type="button" class="btn btn-primary">确认</a>
                            {{--<a id="is_order">什么是决定书编号?</a>--}}
                        </div>
                    </div>
                </div>
                @component('layouts.datatables')
                @endcomponent
                @component('layouts.modal')
                @endcomponent
                @component('layouts.wechat')
                @endcomponent
            </div>
            <script type="text/javascript">
                let info_object = {
                    'penalty_number':'决定书号',
                    'penalty_user_name':'车主姓名',
                    'penalty_car_number':'车牌号牌',
                    //'penalty_car_type'=>'车辆类型',
                    'penalty_money':'罚款额(元)',
                    'penalty_money_late':'滞纳金(元)',
                    'penalty_illegal_place':'违法地点',
                    // 'penalty_illegal_time':'违法时间',
                    'penalty_process_time':'处理时间',
                    // 'penalty_behavior':'违法行为',
                    //'penalty_money_extra':'手续费',
                    // 'penalty_phone_number':'手机号码',
                };
                $(document).ready(function() {
                    // user_datatables_show();
                    $("#penalty_submit").click(function () {
                        let post_data = {penalty_number:$("#penalty_number").val()};//获取数据
                        $.ajax({
                            type:"POST",
                            headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                            url:"{{route('penalties.info')}}",
                            data:post_data,
                            success:function(data){
                                if(data['status'] === 0){
                                    user_datatables_init(info_object,data['data'],function (data) {
                                        user_modal_input("手机号码",function (value) {
                                            let pay_value={
                                                order_money:parseInt(data.penalty_money)+parseInt(data.penalty_money_late)+30,
                                                order_src_type:"penalty",
                                                order_src_id:data.penalty_number,
                                                order_phone_number:value,
                                            };
                                            user_modal_hide();//关闭弹出框
                                            user_wechat_pay(pay_value);
                                        });
                                    });
                                    user_datatables_show();
                                    $("#card_body_input").hide();
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
        </div>
    </div>
</div>
@endsection
