<?php $__env->startSection('content_header'); ?>
    <h1>联系我们</h1>
<?php $__env->stopSection(); ?>

<?php $__env->startComponent('layouts.resources'); ?>
<?php echo $__env->renderComponent(); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                
                <div class="card-body">
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('座 机')); ?></div>
                            <a type="text" class="form-control" href="tel:028-62561692">028-64363802</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('手 机')); ?></div>
                            <a type="text" class="form-control" href="tel:18090005244">18090005244</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('邮 件')); ?></div>
                            <a type="text" class="form-control" href="mailto:570446275@qq.com">570446275@qq.com</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('微 信')); ?></div>
                            <a type="text" class="form-control" href="tel:weizhangxxx">weizhangxxx</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <img src="<?php echo e(URL::asset('images/contact_us_wechat.jpg')); ?>" class="img-responsive" alt="Responsive image">
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('公众号')); ?></div>
                            <a type="text" class="form-control" onclick="ss();">违章消消</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <img src="<?php echo e(URL::asset('images/wechat_public.jpg')); ?>" class="img-responsive" alt="Responsive image">
                    </div>
                </div>
                <?php $__env->startComponent('layouts.modal'); ?>
                <?php echo $__env->renderComponent(); ?>
                <?php $__env->startComponent('layouts.floatmenu'); ?>
                <?php echo $__env->renderComponent(); ?>
                <?php $__env->startComponent('layouts.wechat'); ?>
                <?php echo $__env->renderComponent(); ?>
            </div>
            <script type="text/javascript">
                function ss(){
                    layer.confirm("<img src=\"<?php echo e(URL::asset('images/car_license_ex.jpg')); ?>\" height=\"300px\" width=\"400px\"/>", {offset: '100px', title:"行驶证示例"}, function(index){

                    });
                }
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

<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>