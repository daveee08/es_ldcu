<?php
    $getSchoolInfo = DB::table('schoolinfo')->select('region', 'division', 'district', 'schoolname', 'schoolid')->get();
    $syid = DB::table('sy')->where('isactive', '1')->first();
    $getProgname = DB::table('teacher')
        ->select(
            'teacher.id',
            'sections.levelid',
            'gradelevel.levelname',
            'sections.id as sectionid',
            'sections.sectionname',
            'academicprogram.progname'
        )
        ->join('sectiondetail', 'teacher.id', '=', 'sectiondetail.teacherid')
        ->join('sections', 'sectiondetail.sectionid', '=', 'sections.id')
        ->join('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
        ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
        ->where('teacher.userid', auth()->user()->id)
        ->where('sectiondetail.syid', $syid->id)
        ->where('sections.deleted', '0')
        ->get();

?>
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <!-- Brand Logo
    <a href="/home" class="brand-link">
      <img src="<?php echo e(asset('dist/img/AdminLTELogo.png')); ?>"
           alt="AdminLTE Logo"
           class="brand-image img-circle elevation-3"
           style="opacity: .8">
  <span class="brand-text font-weight-light" style="position: absolute;top: 6%;"><?php echo e(DB::table('schoolinfo')->first()->abbreviation); ?></span>
      
    </a> -->
    <div class="ckheader">
        <a href="#" class="brand-link sidehead">
            <img src="<?php echo e(asset(DB::table('schoolinfo')->first()->picurl)); ?>" class="brand-image img-circle elevation-3"
                style="opacity: .8">
            <span class="brand-text font-weight-light"
                style="position: absolute;top: 6%;"><?php echo e(DB::table('schoolinfo')->first()->abbreviation); ?></span>
            <?php
                $utype = db::table('usertype')->where('id', Session::get('currentPortal'))->first()->utype;

            ?>

            <span class="brand-text font-weight-light"
                style="position: absolute;top: 50%;font-size: 16px!important;"><b><?php echo e($utype); ?>'S
                    PORTAL</b></span>
        </a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <!-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo e(asset('dist/img/user2-160x160.jpg')); ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
            <a href="#" class="d-block"><?php echo e(auth()->user()->name); ?></a>
        </div>
      </div> -->
        <?php
            $randomnum = rand(1, 4);
            $avatar =
                'assets/images/avatars/unknown.png' .
                '?random="' .
                \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') .
                '"';
            $picurl = DB::table('teacher')
                ->where('userid', auth()->user()->id)
                ->first()->picurl;
            $picurl =
                str_replace('jpg', 'png', $picurl) .
                '?random="' .
                \Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss') .
                '"';
        ?>
        <?php
            $teacher_profile = Db::table('teacher')
                ->select(
                    'teacher.id',
                    'teacher.lastname',
                    'teacher.middlename',
                    'teacher.firstname',
                    'teacher.suffix',
                    'teacher.licno',
                    'teacher.tid',
                    'teacher.deleted',
                    'teacher.isactive',
                    'teacher.picurl',
                    'usertype.utype',
                    'usertype.refid'
                )
                ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
                ->where('teacher.userid', auth()->user()->id)
                ->first();

            $teacher_info = Db::table('employee_personalinfo')
                ->select(
                    'employee_personalinfo.id as employee_personalinfoid',
                    'employee_personalinfo.nationalityid',
                    'employee_personalinfo.religionid',
                    'employee_personalinfo.dob',
                    'employee_personalinfo.gender',
                    'employee_personalinfo.address',
                    'employee_personalinfo.contactnum',
                    'employee_personalinfo.email',
                    'employee_personalinfo.maritalstatusid',
                    'employee_personalinfo.spouseemployment',
                    'employee_personalinfo.numberofchildren',
                    'employee_personalinfo.emercontactname',
                    'employee_personalinfo.emercontactrelation',
                    'employee_personalinfo.emercontactnum',
                    'employee_personalinfo.departmentid',
                    'employee_personalinfo.designationid',
                    'employee_personalinfo.date_joined'
                )
                ->where('employee_personalinfo.employeeid', $teacher_profile->id)
                ->get();
            $number = rand(1, 3);
            if (count($teacher_info) == 0) {
                $avatar = 'assets/images/avatars/unknown.png';
            } else {
                if (strtoupper($teacher_info[0]->gender) == 'FEMALE') {
                    $avatar = 'avatar/T(F) ' . $number . '.png';
                } else {
                    $avatar = 'avatar/T(M) ' . $number . '.png';
                }
            }
        ?>
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle" src="<?php echo e(asset($picurl)); ?>"
                        onerror="this.onerror=null; this.src='<?php echo e(asset($avatar)); ?>'" alt="User Image" width="100%"
                        style="width:130px; border-radius: 12% !important;">
                </div>
            </div>
        </div>
        <div class="row  user-panel">
            <div class="col-md-12 info text-center">
                <a class=" text-white mb-0 "><?php echo e(auth()->user()->name); ?></a>
                <h6 class="text-warning text-center"><?php echo e(auth()->user()->email); ?></h6>
            </div>
        </div>
        <?php
            $utype = db::table('usertype')->where('id', Session::get('currentPortal'))->first()->utype;

        ?>
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- <li class="nav-header text-warning"><h4><?php echo e($utype); ?>'S PORTAL</h4></li> -->
                <li class="nav-item">
                    <a href="/home" id="dashboard"
                        class="nav-link <?php echo e(Request::url() == url('/home') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/user/profile"
                        class="nav-link <?php echo e(Request::url() == url('/user/profile') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>

                <?php if(isset(DB::table('schoolinfo')->first()->withschoolfolder)): ?>
                    <?php if(DB::table('schoolinfo')->first()->withschoolfolder == 1): ?>
                        <li class="nav-item">
                            <a class="<?php echo e(Request::url() == url('/schoolfolderv2/index') ? 'active' : ''); ?> nav-link"
                                href="/schoolfolderv2/index">
                                <i class="nav-icon fa fa-calendar"></i>
                                <p>
                                    <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct'): ?>
                                        BCT Commons
                                    <?php else: ?>
                                        Doc Con
                                    <?php endif; ?>
                                </p>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait' ||
                        strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lchsi'): ?>
                    <li
                        class="nav-item has-treeview <?php echo e(Request::fullUrl() == url('/administrator/schoolfolders') || Request::fullUrl() == url('/administrator/schoolfolders') || Request::fullUrl() == url('/mydocs/index') || Request::fullUrl() == url('/mydocs/filesindex') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                INTRANET
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a class="<?php echo e(Request::url() == url('/mydocs/index') || Request::url() == url('/mydocs/filesindex') ? 'active' : ''); ?> nav-link"
                                    href="/mydocs/index">
                                    <i class="nav-icon fa fa-calendar"></i>
                                    <p>
                                        My Documents
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="<?php echo e(Request::url() == url('/administrator/schoolfolders') ? 'active' : ''); ?> nav-link"
                                    href="/administrator/schoolfolders">
                                    <i class="nav-icon fa fa-calendar"></i>
                                    <p>
                                        Doc Con
                                    </p>
                                </a>
                            </li>
                        </ul>

                    </li>
                <?php endif; ?>
                
                <?php if(isset(DB::table('schoolinfo')->first()->withleaveapp)): ?>
                    <?php if(DB::table('schoolinfo')->first()->withleaveapp == 1): ?>
                        <li class="nav-item">
                            <a href="/leaves/apply/index" id="dashboard"
                                class="nav-link <?php echo e(Request::url() == url('/leaves/apply/index') ? 'active' : ''); ?>">
                                <i class="nav-icon fa fa-file"></i>
                                <p>
                                    Apply Leave
                                </p>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li class="nav-item">
                        <a href="/leaves/apply/index" id="dashboard"
                            class="nav-link <?php echo e(Request::url() == url('/leaves/apply/index') ? 'active' : ''); ?>">
                            <i class="nav-icon fa fa-file"></i>
                            <p>
                                Apply Leave
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <?php if(isset(DB::table('schoolinfo')->first()->withovertimeapp)): ?>
                    <?php if(DB::table('schoolinfo')->first()->withovertimeapp == 1): ?>
                        <li class="nav-item">
                            <a href="/overtime/apply/index" id="dashboard"
                                class="nav-link <?php echo e(Request::url() == url('/overtime/apply/index') ? 'active' : ''); ?>">
                                <i class="nav-icon fa fa-file"></i>
                                <p>
                                    Apply Overtime
                                </p>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if(isset(DB::table('schoolinfo')->first()->withundertimeapp)): ?>
                    <?php if(DB::table('schoolinfo')->first()->withundertimeapp == 1): ?>
                        <li class="nav-item">
                            <a href="/undertime/apply" id="dashboard"
                                class="nav-link <?php echo e(Request::url() == url('/undertime/apply') ? 'active' : ''); ?>">
                                <i class="nav-icon fa fa-file"></i>
                                <p>
                                    Apply Undertime
                                </p>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="/dtr/attendance/index"
                        class="nav-link <?php echo e(Request::url() == url('/dtr/attendance/index') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Daily Time Record
                        </p>
                    </a>
                </li>
                <?php
                    $priveledge = DB::table('faspriv')
                        ->join('usertype', 'faspriv.usertype', '=', 'usertype.id')
                        ->select('faspriv.*', 'usertype.utype')
                        ->where('userid', auth()->user()->id)
                        ->where('faspriv.deleted', '0')
                        ->where('faspriv.privelege', '!=', '0')
                        ->get();

                    $usertype = DB::table('usertype')
                        ->where('deleted', 0)
                        ->where('id', auth()->user()->type)
                        ->first();

                ?>

                <?php $__currentLoopData = $priveledge; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($item->usertype != Session::get('currentPortal')): ?>
                        <li class="nav-item">
                            <a class="nav-link portal" href="/gotoPortal/<?php echo e($item->usertype); ?>"
                                id="<?php echo e($item->usertype); ?>">
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
                <?php endif; ?>
                <?php
                    $leavepersonnel = DB::table('hr_leavesappr')
                        ->join('teacher', 'hr_leavesappr.employeeid', '=', 'teacher.id')
                        ->where('teacher.userid', auth()->user()->id)
                        ->where('hr_leavesappr.deleted', '0')
                        ->get();
                ?>

                <li class="nav-header">DOCUMENT TRACKING</li>
                <li class="nav-item">
                    <a href="/documenttracking"
                        class="nav-link <?php echo e(Request::url() == url('/documenttracking') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Document Tracking
                        </p>
                    </a>
                </li>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <br />
    <br />
    <br />
    <!-- /.sidebar -->
</aside>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/general/defaultportal/inc/sidenav.blade.php ENDPATH**/ ?>