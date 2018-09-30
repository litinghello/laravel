<?php $__env->startSection('content_header'); ?>
    <h1>违章查询</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<?php echo $__env->yieldSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                
                <div class="card-body">
                    <form method="POST" class="form-group pull-left" action="<?php echo e(route('violates.info')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon">车牌号</div>
                                <div class="input-group-addon">
                                    <label for="violate_car_number_province">
                                    </label><select id="violate_car_number_province" class="selectpicker" data-style="btn-info">
                                    </select>
                                </div>
                                <label for="violate_car_number">
                                </label><input type="text" class="form-control" id="violate_car_number" placeholder="">
                                <?php if($errors->has('violate_car_number')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('violate_car_number')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <div class="input-group-addon" >车类型</div>
                                <div class="input-group">
                                    <div class="form-control">
                                    <label for="violate_car_number_type">
                                    </label><select id="violate_car_number_type" class="selectpicker" data-style="btn-info">
                                        <option>小型汽车</option>
                                        <option>大型汽车</option>
                                    </select>
                                    </div>
                                </div>
                                <?php if($errors->has('violate_car_number_type')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('violate_car_number_type')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="violate_car_frame_number"></label>
                            <div class="input-group">
                                <div class="input-group-addon">车架号</div>
                                <input type="text" class="form-control" id="violate_car_frame_number" placeholder="后六位">
                                <?php if($errors->has('violate_car_frame_number')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('violate_car_frame_number')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="sr-only" for="violate_car_engine_number"></label>
                            <div class="input-group">
                                <div class="input-group-addon">车架号</div>
                                <input type="text" class="form-control" id="violate_car_engine_number" placeholder="后六位">
                                <?php if($errors->has('violate_car_engine_number')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('violate_car_engine_number')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-center">
                                <a id="viole_inquire_button" type="button" class="btn btn-primary">
                                    <?php echo e(__('查询')); ?>

                                </a>
                            </div>
                        </div>
                    </form>
                </div>
                <script type="text/javascript">
                    $(document).ready(function() {
                        var province_array=["川","渝","鄂","豫","皖","云","吉","鲁","沪","陕","京","湘","宁","津","粤","新","冀","晋","辽","黑","赣","桂","琼","藏","甘","青","闽","蒙","贵","苏","浙"];
                        province_array.forEach(function(value){
                            $("#violate_car_number_province").append("<option>"+value+"</option>");
                        });
                        /*$("#viole_inquire_button").click(function (){
                            $.ajax({
                                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                                
                                success:function(data){
                                    if(data['status'] === 0){
                                        //wechat_pay_data = data['data'];//保存值
                                        // document.getElementById("text").innerText = JSON.stringify(wechat_pay_data);
                                        //wechat_pay(wechat_pay_data);//采用微信网页支付
                                    }else{
                                        alert(data['data']);
                                    }
                                },
                                error:function(error){
                                    alert("请再次提交");
                                }
                            });
                        });*/
                    });
                </script>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>