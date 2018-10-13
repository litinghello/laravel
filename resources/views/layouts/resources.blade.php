

@section('css')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    {{--<link rel="stylesheet" href="{{ URL::asset('layui/css/layui.css') }}" media="all">--}}
    {{--<link rel="stylesheet" href="{{ URL::asset('layui/css/modules/layer/default/layer.css') }}">--}}
@show
@section('js')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" ></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
    {{--<script type="text/javascript" src="{{ URL::asset('layui/layui.js') }}"></script>--}}
    <script type="text/javascript" src="{{ URL::asset('layui/lay/modules/layer.js') }}"></script>
@show