<li class="nav-item dropdown user user-menu">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-bell text"></i>
                <span class="badge badge-primary navbar-badge" id="notifbell_count">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notificationBellHolder">
                <a href="/notificationv2/index" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
        </li>
    </ul>
</li>
<li class="nav-item dropdown user user-menu">
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-comments text"></i>
                <span class="badge badge-danger navbar-badge" id="notification_count">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification_holder">
                <div class="text-center">
                    <p class="text-muted">No message found</p>
                </div>
                <a href="/hr/settings/notification/index" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
        </li>
    </ul>
</li>
<li class="nav-item dropdown user user-menu">
    <?php
        $refid = DB::table('usertype')
            ->where('id', Session::get('currentPortal'))
            ->first()->refid;
            // dd($refid);
    ?>

    <style>
        .nav-link {
    padding-bottom: 5px; /* Space between text and underline */
    text-decoration: none; /* Remove default underline */
    }

.nav-link:hover {
    text-decoration: underline;
    text-decoration-color: yellow; /* Set underline color */
    text-underline-offset: 5px; /* Adjusts space between text and underline */

}
    </style>

    <a class="nav-link btn  btn-block" href="#" id="logout"
    style="color: white !important"></i> Logout
    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
    </form>
    </a>

    

    


    <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        <?php if($refid == 35): ?>
            <li class="user-header nav-bg" style="height:auto !important; display: flex; justify-content: center;">
                <img src="<?php echo e(asset($picurl)); ?>" onerror="this.onerror=null; this.src='<?php echo e(asset($avatar)); ?>'"
                    class="img-circle elevation-2" alt="User Image" style="height: 70px; width: 70px;">
                <div class="d-flex flex-column justify-content-center ml-1">
                    <p style="font-weight: 500;">
                        <?php echo e(strtoupper(auth()->user()->name)); ?>

                    </p>
                    <span style="font-size: 15px">TESDA Administrator</span>
                </div>
            </li>
            <li class="user-footer">
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-id-card text-gray"></i> Profile
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-key text-gray"></i> Change Password
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-calendar-check text-gray"></i> My Leave
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-clock text-gray"></i> My Overtime
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-exclamation-circle text-gray"></i> Report Incident
                </a>
                <a class="nav-link btn btn-dark btn-block" href="#" id="logout"
                    style="color: white !important">
                    <i class="fas fa-sign-out-alt text-white"></i> Logout
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </a>
            </li>
        <?php elseif($refid == 36): ?>
            <li class="user-header nav-bg" style="height:auto !important; display: flex; justify-content: center;">
                <img src="<?php echo e(asset($picurl)); ?>" onerror="this.onerror=null; this.src='<?php echo e(asset($avatar)); ?>'"
                    class="img-circle elevation-2" alt="User Image" style="height: 70px; width: 70px;">
                <div class="d-flex flex-column justify-content-center ml-1">
                    <p style="font-weight: 500;">
                        <?php echo e(strtoupper(auth()->user()->name)); ?>

                    </p>
                    <span style="font-size: 15px">TESDA Trainer</span>
                </div>
            </li>
            <li class="user-footer">
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-id-card text-gray"></i> Profile
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-key text-gray"></i> Change Password
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-calendar-check text-gray"></i> My Leave
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-clock text-gray"></i> My Overtime
                </a>
                <a href="/user/profile" class="btn btn-default btn-block mb-1">
                    <i class="fas fa-exclamation-circle text-gray"></i> Report Incident
                </a>
                <a class="nav-link btn btn-dark btn-block" href="#" id="logout"
                    style="color: white !important">
                    <i class="fas fa-sign-out-alt text-white"></i> Logout
                    <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                        <?php echo csrf_field(); ?>
                    </form>
                </a>
            </li>
        <?php else: ?>
            
            
        <?php endif; ?>


    </ul>
</li>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/components/headerprofile.blade.php ENDPATH**/ ?>