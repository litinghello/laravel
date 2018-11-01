

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('css/jquery.dataTables.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('css/dataTables.bootstrap.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(URL::asset('layui/css/layui.css')); ?>">
<?php echo $__env->yieldSection(); ?>
<?php $__env->startSection('js'); ?>
    <script type="text/javascript" src="<?php echo e(URL::asset('js/jquery.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(URL::asset('js/jquery.dataTables.min.js')); ?>"></script>

    <script type="text/javascript" src="<?php echo e(URL::asset('js/bootstrap.min.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(URL::asset('js/jweixin-1.4.0.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(URL::asset('layui/lay/modules/layer.js')); ?>"></script>
    <script type="text/javascript" src="<?php echo e(URL::asset('layui/layui.all.js')); ?>"></script>
<?php echo $__env->yieldSection(); ?>