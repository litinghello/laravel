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
                            <div class="input-group-addon"><?php echo e(__('座机')); ?></div>
                            <a type="text" class="form-control" href="tel:028-62561692">028-62561692</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('手机')); ?></div>
                            <a type="text" class="form-control" href="tel:13194893073">13194893037</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('邮件')); ?></div>
                            <a type="text" class="form-control" href="mailto:3325815724@qq.com">3325815724@qq.com</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="input-group-addon"><?php echo e(__('微信')); ?></div>
                            <a type="text" class="form-control" href="tel:13194893073">13194893037</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <img src="<?php echo e(URL::asset('images/contact_us_wechat.jpg')); ?>" class="img-responsive" alt="Responsive image">
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