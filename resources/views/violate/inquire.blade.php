{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1>违章查询</h1>
@stop

@section('js')
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
@show
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{--<div class="card-header">{{ __('查询记录') }}</div>--}}

                <div class="card-body">
                    <form method="POST" action="{{ route('violates.info') }}">
                        @csrf
                        <div class="form-group">
                            <label class="sr-only" for="violate_car_number"></label>
                            <div class="input-group">
                                <div class="input-group-addon">车牌号</div>
                                <div class="input-group-addon">
                                    <label for="violate_car_number_province">
                                    </label><select id="violate_car_number_province" class="selectpicker" data-style="btn-info">
                                    </select>
                                </div>
                                <input type="text" class="form-control" id="violate_car_number" placeholder="">
                                @if ($errors->has('violate_car_number'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('violate_car_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group col-sm-2 control-label">
                                <div class="input-group-addon" >车辆类型</div>
                                <div class="input-group-addon">
                                    <label for="violate_car_number_type">
                                    </label><select id="violate_car_number_type" class="selectpicker" data-style="btn-info">
                                        <option>小型汽车</option>
                                        <option>大型汽车</option>
                                    </select>
                                </div>
                                @if ($errors->has('violate_car_number_type'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('violate_car_number_type') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="sr-only" for="violate_car_frame_number"></label>
                            <div class="input-group">
                                <div class="input-group-addon">车架号</div>
                                <input type="text" class="form-control" id="violate_car_frame_number" placeholder="后六位">
                                @if ($errors->has('violate_car_frame_number'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('violate_car_frame_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="violate_car_engine_number"></label>
                            <div class="input-group">
                                <div class="input-group-addon">车架号</div>
                                <input type="text" class="form-control" id="violate_car_engine_number" placeholder="后六位">
                                @if ($errors->has('violate_car_engine_number'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('violate_car_engine_number') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('查询') }}
                                </button>
                            </div>
                        </div>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                var province_array=["川","渝","鄂","豫","皖","云","吉","鲁","沪","陕","京","湘","宁","津","粤","新","冀","晋","辽","黑","赣","桂","琼","藏","甘","青","闽","蒙","贵","苏","浙"];
                                province_array.forEach(function(value){
                                    $("#violate_car_number_province").append("<option>"+value+"</option>");
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
