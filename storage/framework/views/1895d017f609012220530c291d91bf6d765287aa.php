<?php $__env->startSection('headerscript'); ?>

    <link rel="stylesheet" href="<?php echo e(asset('dist/css/adminlte.min.css')); ?>">
    
<?php $__env->stopSection(); ?>
<?php
    $schoolinfo = DB::table('schoolinfo')->first();
?>
<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-6">
            <div class="card mb-2">
                <div class="card-header" style="min-height:150px;">
                    <div class="row">
                        <h2 class="w-100"><?php echo e($fullname); ?></h2>
                        <h1 class="w-100 text-white float-left" style="font-size:40px"><?php echo e($code[0]->queing_code); ?></h1>
                    </div>  
                        
                </div>
                <div class="card-body ">
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-danger h6">
                                <p>Please login to your portal to complete pre-enrollment process.</p>
                               
                                
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="callout callout-info h6">
                                <h5 class="mb-3">Login Credentials</h5>
                                <div class="row">
                                    <div class="col-md-2">
                                        <b>Username: </b>
                                    </div>
                                    <div class="col-md-8">
                                        <?php echo e($user->email); ?>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <b>Password: </b>
                                    </div>
                                    <div class="col-md-8">
                                        <?php echo e($user->passwordstr); ?>

                                    </div>
                                </div>
                                <p class="mt-3 mb-0 text-danger"><i>Note: Make sure to save this credentials!<i></p>
                                <p ><a href="/login" target="_blank">Click here</a> to login!</p>
                                
                            </div>
                        </div>
                    </div>
                   
                    <hr>
                    <div class="row">
                        <h4 class="underlined">Payment Options:</h4>
                    </div>
                  
                    <ul style="list-style-type: none;" class="mt-2 mb-4">
                        <?php $__currentLoopData = DB::table('onlinepaymentoptions')->where('deleted','0')->where('isActive','1')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="mt-3">
                                <img width="60" src="<?php echo e(asset($item->picurl)); ?>" width="60">
                                    <?php if($item->paymenttype == 3): ?>
                                        <ul class="mt-2">
                                            <li>Account Name: <?php echo e($item->accountName); ?></li>
                                            <li>Account Number:  <?php echo e($item->accountNum); ?></li>
                                        </ul>
                                    <?php elseif($item->paymenttype == 4): ?>
                                        <ul class="mt-2" >
                                            <li>Mobile Number: <?php echo e($item->mobileNum); ?></li>
                                        </ul>
                                    <?php else: ?>
                                        <ul class="mt-2">
                                            <li>Account Name: <?php echo e($item->accountName); ?></li>
                                            <li>Account Number:  <?php echo e($item->mobileNum); ?></li>
                                        </ul>
                                    <?php endif; ?>
                                </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <?php if(isset($schoolinfo->abbreviation)): ?>
                                <?php if($schoolinfo->abbreviation == 'sait'): ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <p>For students paying to any MLHUILLIER branches nationwide they have to fill the Bills Payment form , pls read below</p>
                                    </div>
                                    <div class="col-md-12">
                                        <ul>
                                            <li>company name: SAN AGUSTIN INSTITUTE OF TECHNOLOGY</li>
                                            <li>account name: NAME OF STUDENT ex. <?php echo e($fullname); ?></li>
                                            <li>account number: STUDENT ID NUMBER ex. <?php echo e($code[0]->queing_code); ?></li>
                                            <li>Amount: </li>
                                            <li>contact #: </li>
                                            <li>other details: TUITION FEE, ENROLLMENT FEE ETC.</li>
                                        </ul>
                                    </div>
                                    <div class="col-md-12">
                                        <img  src="<?php echo e(asset('sait_mlhuillier.png')); ?>" width="100%">
                                    </div>
                                    <div class="col-md-12">
                                        <img  src="<?php echo e(asset('sait_mlhuillier_2.png')); ?>" width="100%">
                                    </div>
                                </div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    </ul>
                    <a href="/preregv2" class="btn btn-block btn-success">REGISTER NEW STUDENT</a>
                    <a href="/login" class="btn btn-block btn-primary">LOGIN TO PORTAL</a>
                    <?php if(isset($schoolinfo->websitelink)): ?>
                        <a href="<?php echo e($schoolinfo->websitelink); ?>" class="btn btn-block btn-warning">VISIT SCHOOL WEBSITE</a>
                    <?php endif; ?>
                </div>
              
            </div>
        </div>
        <div class="col-md-3"></div>
    </div>
<?php $__env->stopSection(); ?>


                        
            


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\es_ldcu\resources\views/registrar/preregistrationgetcode.blade.php ENDPATH**/ ?>