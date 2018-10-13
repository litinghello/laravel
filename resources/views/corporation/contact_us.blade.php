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
                            <div class="input-group-addon">{{ __('座机') }}</div>
                            <a type="text" class="form-control" href="tel:028-62561692">028-62561692</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{ __('手机') }}</div>
                            <a type="text" class="form-control" href="tel:13194893037">13194893037</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{ __('邮件') }}</div>
                            <a type="text" class="form-control" href="mailto:3325815724@qq.com">3325815724@qq.com</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon">{{ __('微信') }}</div>
                            <a type="text" class="form-control" href="tel:13194893037">13194893037</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <img src="{{ URL::asset('images/contact_us_wechat.jpg') }}" class="img-responsive" alt="Responsive image">
                    </div>
                </div>
                @component('layouts.modal')
                @endcomponent
            </div>
        </div>
    </div>
</div>
@endsection
