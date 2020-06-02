<?php $__env->startSection('content'); ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <?php if( Auth::user()->roles->first()->name =='block' ): ?>
                <div class="card">
                    <div class="card-header">Status</div>
                    <div class="card-body">
                            Hello <?php echo e(Auth::user()->name); ?></br>
                            Your account has been temporarily locked. Please confirm with admin
                    </div>
                </div>
            <?php else: ?>
                <?php if( Auth::user()->roles->first()->name =='admin' ): ?>
                <div class="card">
                    <div class="card-header">Manager list</div>
                    <div class="card-body">
                        <ul>
                            <li class="card-body-item">
                                <a href="<?php echo e(url('manager_users')); ?>"><img src="https://cdn2.iconfinder.com/data/icons/user-management/512/profile_settings-512.png"></a>
                                <a href="<?php echo e(url('manager_users')); ?>"><p>Manage user</p></a>
                            </li>
                            <li class="card-body-item">
                                <a href="<?php echo e(url('manager_tools')); ?>"><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSQJHa59ZKMm2VfIDNWYeMA3H6ew4h-CmM6CxqEQt4VcmzTZa1n&s"></a>
                                <a href="<?php echo e(url('manager_tools')); ?>"><p>Manage tool</p></a>
                            </li>
                            <li class="card-body-item">
                                <a href="<?php echo e(route('manager_slack')); ?>"><img src="https://user-images.githubusercontent.com/819186/51553744-4130b580-1e7c-11e9-889e-486937b69475.png"></a>
                                <a href="<?php echo e(route('manager_slack')); ?>"><p>Manage slack translation</p></a>
                            </li>

                            <li class="card-body-item">
                                <a href="<?php echo e(route('chatwork_admin_index')); ?>"><img src="https://apprecs.org/gp/images/app-icons/300/da/jp.ecstudio.chatworkandroid.jpg"></a>
                                <a href="<?php echo e(route('chatwork_admin_index')); ?>"><p>Manage chatwork translation</p></a>
                            </li>
                        </ul>
                    </div>
                </div>
                </br>
                <div class="card">
                    <div class="card-header">Tool list</div>
                    <div class="card-body">
                        <ul>
                            <?php $__currentLoopData = $tools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool_key => $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="card-body-item">
                                <a href="<?php echo e(route($tool->route)); ?>" ><img src="<?php echo e(asset('img/tools/'.$tool->image)); ?>"></a>
                                <a href="<?php echo e(route($tool->route)); ?>" ><p><?php echo e($tool->name); ?></p></a>
                            </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <?php else: ?>
                </br>
                <div class="card">
                    <div class="card-header">Tool list</div>
                    <div class="card-body">
                        <ul>
                            <?php $__currentLoopData = $tools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tool_key => $tool): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($tool->status == 1): ?>
                                <li class="card-body-item">
                                    <a href="<?php echo e(route($tool->route)); ?>" ><img src="<?php echo e(asset('img/tools/'.$tool->image)); ?>"></a>
                                    <a href="<?php echo e(route($tool->route)); ?>" ><p><?php echo e($tool->name); ?></p></a>
                                </li>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>    
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\BACKEND\agltool_local\resources\views/home.blade.php ENDPATH**/ ?>