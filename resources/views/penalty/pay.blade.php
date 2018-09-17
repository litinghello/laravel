@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('支付页面') }}</div>

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

                    <form method="POST" action="{{ route('penalties.pay') }}">
                        @csrf
                        {{--{{ $penalty = session('penalty_info')  }}--}}
                        {{--{{session()->has('penalty_info')}}}--}}
                        @if(session()->has('penalty_info'))
                            @php
                                $penalty_info = session('penalty_info');
                                $info_object = [
                                'penalty_number'=>'决定数编号',
                                'penalty_user_name'=>'姓名',
                                'penalty_car_number'=>'车牌号',
                                //'penalty_car_type'=>'车辆类型',
                                'penalty_money'=>'罚款金额(元)',
                                'penalty_money_late'=>'滞纳金(元)',
                                'penalty_illegal_place'=>'违法地点',
                                'penalty_illegal_time'=>'违法时间',
                                'penalty_process_time'=>'处理时间',
                                //'penalty_behavior'=>'违法行为',
                                //'penalty_money_extra'=>'手续费',
                                'penalty_phone_number'=>'手机号码(必填)',
                                ];
                            @endphp
                            @foreach ($info_object as $key => $value)
                                <div class="form-group row">
                                    <label for="{{$key}}" class="col-md-4 col-form-label text-md-right">{{ $value }}</label>
                                    <div class="col-md-6">
                                        <input name="{{$key}}" class="form-control" type="text" value="{{$penalty_info[$key]?$penalty_info[$key]:''}}" {{$penalty_info[$key]?'readonly':''}} required>
                                    </div>
                                </div>
                            @endforeach
                            @if ($errors->has('penalty_phone_number'))
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('penalty_phone_number') }}</strong>
                                    </span>
                            @endif
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('微信支付') }}
                                    </button>
                                    <a class="btn btn-link" data-toggle="modal" data-target="#penalty_info">
                                        {{ __('收费规则?') }}
                                    </a>
                                </div>
                            </div>

                            <div class="modal fade" id="penalty_info" tabindex="-1" role="dialog" aria-labelledby="penalty_info_label" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" id="penalty_info_label">手续费</h4>
                                        </div>
                                        <div class="modal-body">每笔手续费10元人民币</div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal -->
                            </div>
                        @else
                            {{--{{ __('数据错误') }}--}}
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
