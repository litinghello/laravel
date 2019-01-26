{{--@extends('layouts.app')--}}
@extends('adminlte::page')

@section('content_header')
    <h1>违章查询</h1>
@stop
@component('layouts.resources')
@endcomponent
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    {{--<div class="card-header">{{ __('查询记录') }}</div>--}}
                    <div id="card_body_input" class="card-body">
                        <div class="form-group input-group ">
                            <div class="input-group-addon">车牌号码</div>
                            <div class="input-group-addon">
                                <select id="car_province" class="selectpicker" data-style="btn-info">
                                </select>
                            </div><input type="text" class="form-control" id="car_number" placeholder="" value="AF474B">
                        </div>
                        <div class="form-group input-group">
                            <div class="input-group-addon" >车辆类型</div>
                            <div class="input-group">
                                <div class="form-control">
                                    <select id="car_type" class="selectpicker" data-style="btn-info">
                                        <option value="02" >小型汽车</option><option value="01" >大型汽车</option><option value="15" >挂车</option><option value="04" >领馆汽车</option><option value="05" >境外汽车</option><option value="06" >外籍汽车</option><option value="07" >两、三轮摩托车</option><option value="08" >轻便摩托车</option><option value="09" >使馆摩托车</option><option value="10" >领馆摩托车</option><option value="11" >境外摩托车</option><option value="12" >外籍摩托车</option><option value="13" >农用运输车</option><option value="14" >拖拉机</option><option value="03" >使馆汽车</option><option value="16" >教练汽车</option><option value="17" >教练摩托车</option><option value="18" >试验汽车</option><option value="19" >试验摩托车</option><option value="20" >临时入境汽车</option><option value="21" >临时入境摩托车</option><option value="22" >临时行驶车</option><option value="23" >警用汽车</option><option value="24" >警用摩托</option><option value="25" >原农机</option><option value="26" >香港入出境车</option><option value="27" >澳门入出境车</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group input-group">
                            <div class="input-group-addon">车架号码</div>
                            <input type="text" class="form-control" id="car_frame_number" placeholder="后八位" value="A4300825">
                        </div>

                        <div class="form-group input-group hidden">
                            <div class="input-group-addon">发动机号</div>
                            <input type="text" class="form-control" id="car_engine_number" placeholder="后六位" value="">
                        </div>


                        <div class="form-group input-group">
                            <div class="input-group-addon">车主姓名</div>
                            <input type="text" class="form-control" id="car_name" value="曾行">
                        </div>


                        <div class="form-group input-group">
                            <div class="input-group-addon">验证码</div>
                            <input type="text" class="form-control" id="code"  value="">
                        </div>

                        <div class="form-group input-group">
                            <span><img src="" alt="" id="img_code"/><a href="javascript:vertify()" style="color: red;margin-left: 10px;">看不清，换一张</a></span>
                        </div>


                        <div class="form-group">
                            <div class="text-center">
                                <button id="violate_submit" type="button" class="btn btn-primary">
                                    {{ __('查询') }}
                                </button>
                            </div>
                        </div>

                        <input id="cookies" placeholder="cookies" hidden>
                    </div>

                    <input id="handle_status" value="" hidden="hidden">

                    @component('layouts.datatables')
                    @endcomponent
                    @component('layouts.modal')
                    @endcomponent
                    @component('layouts.order')
                    @endcomponent
                    @component('layouts.wechat')
                    @endcomponent
                    @component('layouts.floatmenu')
                    @endcomponent
                </div>

                <script type="text/javascript">

                    let code_url = 'http://192.168.0.18:8889/laravel/public/violates/chengdu/img';
                    let search_url= 'http://192.168.0.18:8889/laravel/public/violates/chengdu/info';

                    function vertify() {
                        let dd = $.ajax({url:code_url,async:false});
                        let ss = JSON.parse(dd.responseText);
                        $("#img_code").attr("src", ss.data);
                        $("#cookies").val(ss.cookies);
                    }



                    let info_object = {
                        'car_type':'车辆种类',
                        'car_province':'车辆省份',
                        'car_number':'车牌号',
                        'violate_code':'违章代码',
                        'violate_time':'违章时间',
                        'violate_address':'违章地点',
                        'violate_money':'罚款金额（元）',
                        // 'penalty_phone_number':'手续费',
                        'violate_marks':'扣分（仅供参考）',
                    };
                    var province_array = ["川"];
                    // var province_array = ["川","渝","鄂","豫","皖","云","吉","鲁","沪","陕","京","湘","宁","津","粤","新","冀","晋","辽","黑","赣","桂","琼","藏","甘","青","闽","蒙","贵","苏","浙"];
                    $(document).ready(function() {

                        vertify()

                        user_float_menu_select(2);

                        province_array.forEach(function(value){
                            $("#car_province").append("<option value='"+value+"'>"+value+"</option>");
                        });


                        $("#violate_submit").click(function (){
                            let post_data = {
                                car_province:$("#car_province").val(),
                                car_number:$("#car_number").val().toLocaleUpperCase(),
                                car_type:$("#car_type").val(),
                                car_frame_number:$("#car_frame_number").val(),
                                car_engine_number:$("#car_engine_number").val(),
                            };


                            let json_object = {
                                "__EVENTTARGET":"ctl00%24ContentPlaceHolder1%24btnVeh",
                                "__EVENTARGUMENT":"",
                                "__VIEWSTATE":"%2FwEPDwULLTE2MTY5NTQyNDQPZBYCZg9kFgQCBQ8PFgIeBFRleHQFBDEwMzdkZAIHDw8WAh8ABQkyNDQzNzA0MzFkZGR5Q9t%2B087xC4AqKlSvvs8YY9eSZ%2BkqeI94Pi%2BsBw3Pvg%3D%3D",
                                "__VIEWSTATEGENERATOR":"B386BE9B",
                                "__PREVIOUSPAGE":"Atov0RgqxUCPpmnPaWLhwrQ68YFgo55B0Eqh1d3fRBAN3GOaej3EhvhtD2IfgaCzm4mCTLeTlV_zlWphsGEVGWbQ4SHgjRn1mw8bxqHZV24u-lNFKxppD8CGBF4neha9aLRWtjZDr6MAyAu8cLZo6hqKy6FUJ4yvVvexj2mitKLs10Phe2gDJUgFi-q1poYO0",
                                "__EVENTVALIDATION":"%2FwEdAAm6HXBzvi7lHpKd1LEIWuoTU1nw%2BekAp261U8JQiZc9CSUh7%2BmcdQVCh7zs1yhjMaHoKrN8Em90H9%2FCULMBREiAtd4irRCGYF9cWkg9N731x4sFrRe%2FK9yDRLISNEAr2KLaPn1lP427U6BbhUdiccb9%2BAID6RSWbuIxYniix%2B9qZCFDiZv7uMZvG0Evv%2B8hFIjNoXIc4ylIblglHfiHeaU12LnJtb3xHleb0Yw8xEZwBg%3D%3D",
                                "ctl00%24ContentPlaceHolder1%24txtYzm":"LPDD",
                                "ctl00%24ContentPlaceHolder1%24txtSyr":"%E6%9B%BE%E8%A1%8C",
                                "ctl00%24ContentPlaceHolder1%24hidCode":"",
                                "ctl00%24ContentPlaceHolder1%24hidHpzl":"02",
                                "ctl00%24ContentPlaceHolder1%24hidHphm":"%E5%B7%9DAF474B",
                                "ctl00%24ContentPlaceHolder1%24hidClsbdh":"A4300825"
                            };

                            json_object['cookies'] = $("#cookies").val();
                            json_object['ctl00%24ContentPlaceHolder1%24txtYzm'] = $("#code").val();
                            json_object['ctl00%24ContentPlaceHolder1%24txtSyr'] = $("#car_name").val();
                            json_object['ctl00%24ContentPlaceHolder1%24hidHphm'] = $("#car_province").val()+$("#car_number").val().toLocaleUpperCase();
                            json_object['ctl00%24ContentPlaceHolder1%24hidHpzl'] = $("#car_type").val()
                            json_object['ctl00%24ContentPlaceHolder1%24hidClsbdh'] = $("#car_frame_number").val();
                            let post_object = "";
                            for(let index in json_object) {
                                post_object += index+"="+json_object[index]+"&";
                            }
                            post_object = post_object.substr(0, post_object.length - 1);//删掉最后一个&字符串


                            let durl = search_url + `?${post_object}`;

                            user_modal_loading(0);
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"},
                                url:durl,type:"POST",
                                success:function(data){

                                    user_modal_loading_close();
                                    if(data['status'] === 0){
                                        console.log(data)
                                            user_datatables_init(info_object,data['data'],function (data) {
                                                let display_info = "";
                                                for(key in info_object){
                                                    display_info += "<div>"+info_object[key]+":"+data[key]+"</div>";
                                                }
                                                display_info += "<div>合计："+parseFloat(parseFloat(data.violate_marks)*150 + parseFloat(data.violate_money) + 30)+"元</div>";
                                                display_info += "<div>收费规则：150元*扣分+罚款+30元服务费</div>";
                                                open_upphoto_layer(null,'{{url('driving/upfile')}}','上传行驶证正面照片',function (d) {
                                                    if (d !== undefined && d !== '') {
                                                        $.ajax({
                                                            type: 'POST',
                                                            data: {'order_src_id': data['id'], 'img': d},
                                                            url: "{{route('driving.upfile_suc')}}",
                                                            headers: {'X-CSRF-TOKEN': "{{csrf_token()}}"}
                                                        })
                                                    }
                                                    user_modal_comfirm(display_info, function () {
                                                        let order_value = {
                                                            order_money: 0,
                                                            order_src_type: "violate",
                                                            order_src_id: data['id'],
                                                            order_phone_number: "13000000000"
                                                        };
                                                        user_order_create_pay(order_value);
                                                    });
                                                });
                                            });
                                            user_datatables_show();
                                            $("#card_body_input").hide();
                                    }else{
                                        user_modal_warning(data['data']);
                                    }
                                },
                                error:function(error){
                                    user_modal_loading_close();
                                    user_modal_warning("请再次提交");
                                }
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </div>
@endsection
