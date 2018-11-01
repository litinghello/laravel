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
                            <a type="text" class="form-control" href="tel:028-62561692">028-62561692</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('手 机')); ?></div>
                            <a type="text" class="form-control" href="tel:13194893073">13194893073</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('邮 件')); ?></div>
                            <a type="text" class="form-control" href="mailto:3325815724@qq.com">3325815724@qq.com</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('微 信')); ?></div>
                            <a type="text" class="form-control" href="tel:13194893073">13194893073</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <img src="<?php echo e(URL::asset('images/contact_us_wechat.jpg')); ?>" class="img-responsive" alt="Responsive image">
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('公众号')); ?></div>
                            <a type="text" class="form-control" href="https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzUzMzkyNTUxMg==#wechat_redirect" >车途天下</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <img src="<?php echo e(URL::asset('images/wechat_public.jpg')); ?>" class="img-responsive" alt="Responsive image">
                    </div>
                </div>
                <?php $__env->startComponent('layouts.modal'); ?>
                <?php echo $__env->renderComponent(); ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>