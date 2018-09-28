<?php $__env->startSection('content_header'); ?>
    <h1>罚款查询</h1>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header"><?php echo e(__('查询记录')); ?></div>

                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('penalties.info')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="form-group row">
                            <label for="penalty_number" class="col-md-4 col-form-label text-md-right"><?php echo e(__('决定书编号')); ?></label>
                            <div class="col-md-6">
                                <input for="penalty_number" id="penalty_number" type="text" class="form-control<?php echo e($errors->has('penalty_number') ? ' is-invalid' : ''); ?>" name="penalty_number" value="5101041204594064" required>
                                <?php if($errors->has('penalty_number')): ?>
                                    <span class="invalid-feedback" role="alert">
                                        <strong><?php echo e($errors->first('penalty_number')); ?></strong>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    <?php echo e(__('查询')); ?>

                                </button>

                                <a class="btn btn-link" data-toggle="modal" data-target="#penalty_info">
                                    <?php echo e(__('决定书编号?')); ?>

                                </a>
                            </div>
                        </div>

                        <div class="modal fade" id="penalty_info" tabindex="-1" role="dialog" aria-labelledby="penalty_info_label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="penalty_info_label">说明</h4>
                                    </div>
                                    <div class="modal-body">交通违章处罚决定书编号就是驾驶证号，可以到网上交罚款。</div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal -->
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('adminlte::page', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>