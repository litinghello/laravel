{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1>联系我们</h1>
@stop

@component('layouts.resources')
@endcomponent

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{--<div class="card-header">{{ __('查询记录') }}</div>--}}
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{ __('座 机') }}</div>
                            <a type="text" class="form-control" href="tel:028-62561692">028-64363802</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{ __('手 机') }}</div>
                            <a type="text" class="form-control" href="tel:13194893073">13308000058</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{ __('邮 件') }}</div>
                            <a type="text" class="form-control" href="mailto:3325815724@qq.com">68518537@qq.com</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{ __('微 信') }}</div>
                            <a type="text" class="form-control" href="tel:13194893073">13308000058</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <img src="{{ URL::asset('images/contact_us_wechat.jpg') }}" class="img-responsive" alt="Responsive image">
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{ __('公众号') }}</div>
                            <a type="text" class="form-control" href="https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzUzMzkyNTUxMg==#wechat_redirect" >违章消消</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <img src="{{ URL::asset('images/wechat_public.jpg') }}" class="img-responsive" alt="Responsive image">
                    </div>
                </div>
                @component('layouts.modal')
                @endcomponent
                @component('layouts.floatmenu')
                @endcomponent
                @component('layouts.wechat')
                @endcomponent
            </div>
            <script type="text/javascript">
                $(document).ready(function() {
                    let share_info = {
                        title: '违章罚款代缴 | 首单免费，最低手续费1元，名额有限。', // 分享标题
                        desc : "快速违章代缴，违法代办，免费名额有限。",//摘要,如果分享到朋友圈的话，不显示摘要。
                        link: 'http://www.weizhangxiaoxiao.com/penalties/inquire', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: 'http://www.weizhangxiaoxiao.com/images/wechat_payment.jpg' // 分享图标
                    };
                    user_wechat_share(share_info);
                });
            </script>
        </div>
    </div>
</div>

@endsection
