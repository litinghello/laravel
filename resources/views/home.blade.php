
{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1>支付订单</h1>
@stop
@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
@show
@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs/jszip-2.5.0/dt-1.10.18/af-2.3.0/b-1.5.2/b-colvis-1.5.2/b-flash-1.5.2/b-html5-1.5.2/b-print-1.5.2/fh-3.1.4/kt-2.4.0/r-2.2.2/rg-1.0.3/rr-1.2.4/sc-1.5.0/sl-1.2.6/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
@show

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{--<div class="card-header">订单状态</div>--}}
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    @component('layouts.datatables')
                    @endcomponent
                    @component('layouts.modal')
                    @endcomponent
                    @component('layouts.wechat')
                    @endcomponent
                        <script type="text/javascript">
                            let info_object = {
                                'order_number':'订单号',
                                'order_money':'订单金额',
                                'order_phone_number':'联系电话',
                                'order_status':'状态',
                                'updated_at':'时间',
                            };
                            $(document).ready(function() {
                                $.ajax({
                                    type:"POST",
                                    headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                                    url:"{{route('wechats.order.data')}}",
                                    data:"",
                                    success:function(data){
                                        console.log(data);
                                        if(data['status'] === 0){
                                            user_datatables_init(info_object,data['data'],function (data) {
                                                /*var info_object = {
                                                    'order_src_id':'决定数编号',
                                                    'order_src_type':'订单号',
                                                    'order_status':"订单状态"
                                                };
                                                var order_status={
                                                    'invalid':"无效",
                                                    'unpaid':'未支付',
                                                    'paid':'已支付',
                                                    'processing':'正在处理',
                                                    'completed':'处理完成',
                                                };
                                                var body_text = "";
                                                for (value in info_object){
                                                    console.log(value);
                                                    if(value === 'order_status'){
                                                        body_text+= info_object[value]+":"+order_status[data[value]];
                                                    }else{
                                                        body_text+= info_object[value]+":"+data[value];
                                                    }
                                                    body_text+="<br>";
                                                }
                                                user_modal_show("订单信息",body_text);*/
                                            });
                                            user_datatables_show();
                                        }else{
                                            user_modal_warning(data['data']);
                                        }
                                    },
                                    error:function(error){
                                        user_modal_warning("请再次提交");
                                    }
                                });
                            });
                            // $("#table_info_filter input[type=search]").css({ width: "auto" });
                        </script>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


