@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('微信页面') }}</div>
                    <div class="card-body">
                        <div class="form-group">
                            @if (count($errors) > 0)
                                <div class="alert alert-danger">
                                    <ul style="color:red;">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session()->has('config'))
            @php
                $config = session('config');
            @endphp
            <script>
                WeixinJSBridge.invoke(
                    'getBrandWCPayRequest', '{{ $config }}',
                    function (res) {
                        if (res.err_msg == "get_brand_wcpay_request:ok") {
                            // 使用以上方式判断前端返回,微信团队郑重提示：
                            // res.err_msg将在用户支付成功后返回
                            // ok，但并不保证它绝对可靠。
                        }
                    }
                );
            </script>
        @endif

    </div>
@endsection
