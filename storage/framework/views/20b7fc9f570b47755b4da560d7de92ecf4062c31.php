<?php $__env->startSection('headerscript'); ?>
    <script src="<?php echo e(asset('plugins/moment/moment.min.js')); ?>"></script>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <?php
        use Carbon\Carbon;

        $nowInManilaTz = now()->setTimezone('Asia/Manila');
        $schoolInfo = DB::table('schoolinfo')->first();
        $adImages = DB::table('adimages')->where('isactive', 1)->get();
    ?>

    <style>
        .school-details {
            font-size: 14px;
        }

        .visit-website {
            font-size: 12px;
        }

        .tagline {
            font-size: 20px;
        }

        #thiscontainer .fxt-bg-color {
            margin: 0 auto;
            width: 60%;
        }

        @media  only screen and (max-width: 600px) {

            .school-details,
            .visit-website {
                font-size: 14px;
            }

            .tagline {
                font-size: 18px;
            }

            #thiscontainer .fxt-bg-color {
                width: 100%;
            }
        }

        .modal-content {
            transition: all 0.3s ease-in-out;
        }
        .btn-lg {
            min-width: 140px;
        }
        .btn-outline-primary:hover,
        .btn-outline-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
    </style>

    <!-- Pre-registration Modal -->
    <div class="modal fade" id="preRegistrationModal" tabindex="-1" role="dialog" aria-labelledby="preRegistrationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content border-0 shadow-lg rounded-3">
                <div class="modal-header bg-light border-bottom-0">
                    <h5 class="modal-title fw-semibold text-primary" id="preRegistrationModalLabel">Pre-registration</h5>
                    
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>
                <div class="modal-body text-center px-4 pb-4">
                    <p class="mb-4 fs-5 text-muted">Select student type:</p>
                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                        <a href="/preregv2?type=old" class="btn btn-outline-primary btn-lg px-4">Old Student</a>
                        <a href="/preregv2?type=new" class="btn btn-outline-success btn-lg px-4">New Student</a>
                        <a href="/preregv2?type=transferee" class="btn btn-outline-warning btn-lg px-4">Transferee</a>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <section class="fxt-template-animation fxt-template-layout20 m-0">
        <div class="container" id="thiscontainer">
            <div class="fxt-bg-color pt-0" style="border-radius: 20px;">
                <div class="fxt-content text-center" style="padding: 20px 40px!important;">
                     <?php if($schoolInfo): ?>
                        <div class="fxt-header">
                            <div class="row mb-2">
                                <?php
                                    // Remove query parameters from the URL
                                    $picurl = $schoolInfo->picurl ? parse_url($schoolInfo->picurl, PHP_URL_PATH) : null;

                                    // Get the absolute path in the public directory
                                    $imagePath = $picurl ? public_path($picurl) : null;

                                    // Check if the file exists
                                    $imageSrc = (!empty($picurl) && file_exists($imagePath)) 
                                        ? $picurl
                                        : asset('assets/images/CK_Logo.png');
                                ?>

                                <div class="col-12">
                                    <?php if(!empty($schoolInfo->websitelink)): ?>
                                        <a href="<?php echo e(Str::startsWith($schoolInfo->websitelink, ['http://', 'https://']) ? $schoolInfo->websitelink : 'https://' . $schoolInfo->websitelink); ?>"
                                        target="_blank" rel="noopener noreferrer">
                                            <img src="<?php echo e($imageSrc); ?>" 
                                                alt="School Logo" width="150" 
                                                onerror="this.onerror=null; this.src='<?php echo e(asset('assets/images/avatars/hansolo.png')); ?>';">
                                        </a>
                                    <?php else: ?>
                                        <img src="<?php echo e($imageSrc); ?>" 
                                            alt="School Logo" width="150" 
                                            onerror="this.onerror=null; this.src='<?php echo e(asset('assets/images/avatars/hansolo.png')); ?>';">
                                    <?php endif; ?>
                                </div>
                                
                                <div class="col-12 school-details tagline">
                                    <strong><?php echo e($schoolInfo->schoolname ?? 'SCHOOL NAME'); ?></strong><br>
                                    <small><?php echo e($schoolInfo->address ?? 'SCHOOL ADDRESS'); ?></small>
                                </div>
                            </div>
                            <div class="row mb-0">
                                <div class="col-12">
                                    <em><?php echo e($schoolInfo->tagline ?? 'School Tagline'); ?></em>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?> 

                    <!-- Login Form -->
                    <div class="fxt-form">
                        <form method="POST" action="<?php echo e(route('login')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <input id="email" type="text"
                                    class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email"
                                    value="<?php echo e(old('email')); ?>" required autocomplete="email" autofocus
                                    placeholder="Username" style="border-radius: 25px;">
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback"><strong><?php echo e($message); ?></strong></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <input id="password" type="password"
                                        class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password"
                                        required autocomplete="current-password" placeholder="Password"
                                        style="border-top-left-radius: 25px; border-bottom-left-radius: 25px;">
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-default border" id="togglePassword"
                                            style="border-top-right-radius: 25px; border-bottom-right-radius: 25px;">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </span>
                                </div>
                            </div>
                            <div class="alert alert-warning text-justify"
                                style="font-size: 12.5px; border: 1px solid gold;">
                                <strong><?php echo e($schoolInfo->abbreviation ?? ''); ?>'s SMS Privacy Notice</strong><br>
                                By clicking the Login button, I recognize the authority of the school and the third-party
                                service provider
                                to process my personal information pursuant to the Data Privacy and Regulation of the school
                                and applicable laws.
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-block text-white"
                                    style="background-color: <?php echo e($schoolInfo->schoolcolor ?? '#007bff'); ?>; border-radius: 25px; font-size: 1.25rem;">
                                    <?php echo e(__('Login')); ?>

                                </button>
                            </div>

                            <a href="/coderecovery" class="btn btn-lg btn-primary btn-block"
                                style="border-radius: 25px; font-size: 1.25rem;">Get Username/Password</a>

                            <a class="btn btn-lg btn-danger btn-block text-white mt-2"
                                style="border-radius: 25px; font-size: 1.25rem;" data-toggle="modal"
                                data-target="#preRegistrationModal">Pre-registration</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById("togglePassword").addEventListener("click", function() {
                let passwordInput = document.getElementById("password");
                passwordInput.type = passwordInput.type === "password" ? "text" : "password";
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\es_ldcu2\resources\views/auth/login.blade.php ENDPATH**/ ?>