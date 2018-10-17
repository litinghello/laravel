@extends('layouts.app')

@section('js')
    <script type="text/javascript" src="{{ URL::asset('js/jquery.min.js') }}"></script>
@show

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('用户登录') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="form-group row">
                            <label for="email" class="col-sm-4 col-form-label text-md-right">{{ __('邮件') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('密码') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('记住我') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button id="email_login" type="submit" class="btn btn-primary">
                                    {{ __('邮件登录') }}
                                </button>
                                <a id="wechat_login" class="btn btn-success" href="{{ route('wechats.login') }}">
                                    {{ __('微信登录') }}
                                </a>
                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                    {{ __('忘记密码？') }}
                                </a>
                            </div>
                            <script type="text/javascript">
                                if(navigator.userAgent.toLowerCase().indexOf('micromessenger') === -1){
                                    //未找到微信登录
                                    $("#wechat_login").hide();
                                    $("#email_login").show();
                                }else{
                                    //找到微信登录
                                    $("#wechat_login").show();
                                    $("#email_login").hide();
                                    $(":input").attr("disabled","disabled");//关闭所有input元素
                                }
                            </script>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
