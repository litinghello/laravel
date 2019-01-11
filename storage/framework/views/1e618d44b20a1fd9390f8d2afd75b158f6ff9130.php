<?php $__env->startSection('content_header'); ?>
    <h1>违章查询</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startComponent('layouts.resources'); ?>
<?php echo $__env->renderComponent(); ?>
<?php $__env->startSection('content'); ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    
                    <div id="card_body_input" class="card-body">
                        <div class="form-group input-group ">
                            <div class="input-group-addon">车牌号码</div>
                            <div class="input-group-addon">
                                <select id="car_province" class="selectpicker" data-style="btn-info">
                                </select>
                            </div><input type="text" class="form-control" id="car_number" placeholder="" value="">
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
                            <input type="text" class="form-control" id="car_frame_number" placeholder="后六位" value="">
                        </div>
                        <div class="form-group input-group hidden">
                            <div class="input-group-addon">发动机号</div>
                            <input type="text" class="form-control" id="car_engine_number" placeholder="后六位" value="">
                        </div>
                        <div class="form-group">
                            <div class="text-center">
                                <button id="violate_submit" type="button" class="btn btn-primary">
                                    <?php echo e(__('查询')); ?>

                                </button>
                            </div>
                        </div>
                    </div>
                    <?php $__env->startComponent('layouts.datatables'); ?>
                    <?php echo $__env->renderComponent(); ?>
                    <?php $__env->startComponent('layouts.modal'); ?>
                    <?php echo $__env->renderComponent(); ?>
                    <?php $__env->startComponent('layouts.order'); ?>
                    <?php echo $__env->renderComponent(); ?>
                    <?php $__env->startComponent('layouts.wechat'); ?>
                    <?php echo $__env->renderComponent(); ?>
                    <?php $__env->startComponent('layouts.floatmenu'); ?>
                    <?php echo $__env->renderComponent(); ?>
                </div>
                <script type="text/javascript">
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
                    var province_array = ["川","渝","鄂","豫","皖","云","吉","鲁","沪","陕","京","湘","宁","津","粤","新","冀","晋","辽","黑","赣","桂","琼","藏","甘","青","闽","蒙","贵","苏","浙"];
                    $(document).ready(function() {
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
                            // $("#violate_submit").attr('disabled',true);
                            user_modal_loading(0);
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"},
                                url:"<?php echo e(route('violates.info')); ?>",type:"POST",data:post_data,
                                success:function(data){
                                    user_modal_loading_close();
                                    if(data['status'] === 0){
                                        user_datatables_init(info_object,data['data'],function (data) {
                                            let display_info = "";
                                            for(key in info_object){
                                                display_info += "<div>"+info_object[key]+":"+data[key]+"</div>";
                                            }
                                            display_info += "<div>合计："+parseFloat(parseFloat(data.violate_marks)*150 + parseFloat(data.violate_money) + 30)+"元</div>";
                                            display_info += "<div>收费规则：150元*扣分+罚款+30元服务费</div>";
                                            user_modal_comfirm(display_info,function () {
                                                let order_value={
                                                    order_money:0,
                                                    order_src_type:"violate",
                                                    order_src_id:data['id'],
                                                    order_phone_number:"13000000000"
                                                };
                                                user_order_create_pay(order_value);
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>