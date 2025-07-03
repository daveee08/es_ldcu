<?php
    $priveledge = DB::table('faspriv')
                    ->join('usertype','faspriv.usertype','=','usertype.id')
                    ->select('faspriv.*','usertype.utype')
                    ->where('userid', auth()->user()->id)
                    ->where('faspriv.deleted','0')
                    ->where('type_active',1)
                    ->where('faspriv.privelege','!=','0')
                    ->get();

    $usertype = DB::table('usertype')->where('deleted',0)->where('id',auth()->user()->type)->first();

?>
<li class="nav-header text-warning" <?php echo e(count($priveledge) > 0 ? '':'hidden'); ?>>Other Portal</li>
<?php $__currentLoopData = $priveledge; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($item->usertype != Session::get('currentPortal')): ?>
        <li class="nav-item">
            <a class="nav-link portal" href="/gotoPortal/<?php echo e($item->usertype); ?>" id="<?php echo e($item->usertype); ?>">
                <i class=" nav-icon fas fa-cloud"></i>
                <p>
                    <?php echo e($item->utype); ?>

                </p>
            </a>
        </li>
    <?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

<?php if($usertype->id != Session::get('currentPortal')): ?>
    <li class="nav-item">
        <a class="nav-link portal" href="/gotoPortal/<?php echo e($usertype->id); ?>">
            <i class=" nav-icon fas fa-cloud"></i>
            <p>
                <?php echo e($usertype->utype); ?>

            </p>
        </a>
    </li>
<?php endif; ?><?php /**PATH C:\laragon\www\es_ldcu2\resources\views/components/privsidenav.blade.php ENDPATH**/ ?>