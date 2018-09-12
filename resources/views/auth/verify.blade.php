@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">邮件验证</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            新的验证链接已发送到您的电子邮件地址。
                        </div>
                    @endif

                    在继续之前，请检查您的电子邮件以获取验证链接。
                    如果您没有收到电子邮件， <a href="{{ route('verification.resend') }}">点击再次发送。</a>.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
