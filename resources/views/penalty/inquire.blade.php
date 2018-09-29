{{--@extends('layouts.app')--}}
@extends('adminlte::page')


@section('content_header')
    <h1>罚款查询</h1>
@stop
@section('css')
    {{--<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">--}}
@show
@section('js')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@show
@extends('layouts.modal')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{--<div class="card-header">{{ __('查询记录') }}</div>--}}

                <div class="card-body">
                    <form method="POST" action="{{ route('penalties.info') }}">
                        @csrf
                        <div class="form-group">
                            <label class="sr-only" for="penalty_number"></label>
                            <div class="input-group">
                                <div class="input-group-addon">{{ __('决定书编号') }}</div>
                                <input for="penalty_number" id="penalty_number" type="text" class="form-control{{ $errors->has('penalty_number') ? ' is-invalid' : '' }}" name="penalty_number" placeholder="请输入15-16位处罚决定书编号" value="" required>
                                @if ($errors->has('penalty_number'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('penalty_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('查询') }}
                                </button>
                                <a id="is_order">
                                    {{ __('什么是决定书编号?') }}
                                </a>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                $("#is_order").click(function(){
                                    modal_show({
                                        label:"说明",
                                        body:"请注意一般是在处理完违章后有一份《处罚决定书》，标题下方有此编号。"
                                    });
                                });
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
