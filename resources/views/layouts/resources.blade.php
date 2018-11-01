

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('layui/css/layui.css') }}">
@show
@section('js')
    <script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery.dataTables.min.js') }}"></script>
{{--    <script type="text/javascript" src="{{ URL::asset('js/dataTables.bootstrap.min.js') }}"></script>--}}
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jweixin-1.4.0.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('layui/lay/modules/layer.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('layui/layui.all.js') }}"></script>
@show