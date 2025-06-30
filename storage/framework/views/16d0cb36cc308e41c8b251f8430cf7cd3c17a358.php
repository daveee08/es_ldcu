<?php

    $syid = DB::table('sy')->where('isactive', 1)->first()->id;

    $teacherid = DB::table('teacher')
        ->where('tid', auth()->user()->email)
        ->select('id')
        ->first()->id;

    $teacheradprogid = DB::table('teacheracadprog')
        ->where('teacherid', $teacherid)
        ->where('syid', $syid)
        ->whereIn('acadprogutype', [3, 8])
        ->where('deleted', 0)
        ->get();

    $isjs = collect($teacheradprogid)->where('acadprogid', 4)->count() > 0 ? true : false;
    $issh = collect($teacheradprogid)->where('acadprogid', 5)->count() > 0 ? true : false;
    $iscollege = collect($teacheradprogid)->where('acadprogid', 6)->count() > 0 ? true : false;
    $isgs = collect($teacheradprogid)->where('acadprogid', 3)->count() > 0 ? true : false;
    $isps = collect($teacheradprogid)->where('acadprogid', 2)->count() > 0 ? true : false;

    $activeacadprogs = DB::table('gradelevel')->select('acadprogid')->where('deleted', '0')->get();

?>
<style>
    @keyframes  blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }

        /* Reduce the opacity to make it more subtle */
        100% {
            opacity: 1;
        }
    }

    .blink {
        animation: blink 1s ease-in-out infinite;
        /* Increase the duration for a smoother effect */
    }

    .nav-link,
    .nav-header {
        color: #343A30 !important;
    }

    .nav-link.active {
        color: #F0F0F0 !important;
        background: #787c7f !important;
    }

    .user-panel {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .brand-link {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .side li i {
        font-size: 14px !important;
        color: #343A30 !important;
    }

    .side li:hover .udernavs p {
        transition: none;
        font-size: 15px;
        padding-left: none;
    }

    .side li:hover i {
        color: #343A30 !important;
    }

    .nav-link.active .nav-icon {
        color: white !important;
    }

    .sidebar {
        overflow-y: auto;
        /* Enable scrolling */
        max-height: 100vh;
        /* Ensure sidebar has limited height */
        position: relative;
    }
</style>
<aside id="sidenav" class="main-sidebar sidebar-dark-primary elevation-4 asidebar"
    style="background-color: white !important">

    <?php
        $getSchoolInfo = DB::table('schoolinfo')->select('schoolname', 'projectsetup')->get();
    ?>
    <a href="/home" class="brand-link text-white nav-bg">
        <img src="<?php echo e(asset(DB::table('schoolinfo')->first()->picurl)); ?>" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3 " style="opacity: .8 !important; width: 33px; height: 33px;"
            onerror="this.src='<?php echo e(asset('assets/images/department_of_Education.png')); ?>'">
        <span class="brand-text font-weight-light"
            style="position: absolute;top: 6%;"><?php echo e(DB::table('schoolinfo')->first()->abbreviation); ?></span>
        <?php
            $utype = db::table('usertype')->where('id', 3)->first()->utype;
        ?>
        <span class="brand-text font-weight-light"
            style="position: absolute;top: 50%;font-size: 16px!important;"><b><?php echo e($utype); ?>'S PORTAL</b></span>
    </a>

    <?php
        $registrar_profile = Db::table('teacher')
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
            )
            ->join('usertype', 'teacher.usertypeid', '=', 'usertype.id')
            ->where('teacher.userid', auth()->user()->id)
            ->first();

        $registrar_info = Db::table('employee_personalinfo')
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
                'employee_personalinfo.date_joined',
            )
            ->where('employee_personalinfo.employeeid', $registrar_profile->id)
            ->get();
        $number = rand(1, 3);
        if (count($registrar_info) == 0) {
            $avatar = 'assets/images/avatars/unknown.png';
        } else {
            if (strtoupper($registrar_info[0]->gender) == 'FEMALE') {
                $avatar = 'avatar/T(F) ' . $number . '.png';
            } else {
                $avatar = 'avatar/T(M) ' . $number . '.png';
            }
        }
        $basicedacadprogs = DB::table('academicprogram')->where('id', '<', 6)->get();
        // echo
    ?>
    <div class="sidebar" id="sidebar">
        <div class="row">
            <div class="col-md-12">
                <div class="text-center">
                    <img class="profile-user-img img-fluid"
                        src="<?php echo e(asset($registrar_profile->picurl)); ?>?random=<?php echo e(\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')); ?>"
                        onerror="this.onerror=null; this.src='<?php echo e(asset($avatar)); ?>';" alt="User Image"
                        style="max-width:130px; width:100%; height:auto; aspect-ratio:1/1; border-radius:20px !important; object-fit:cover; background:#f0f0f0;">
                </div>
            </div>
        </div>

        <div class="row  user-panel">
            <div class="col-md-12 info text-center">
                <h6 class="mb-0"><?php echo e(auth()->user()->name); ?></h6>
                <h6 class="text-center"><?php echo e(auth()->user()->email); ?></h6>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu"
                data-accordion="false">
                
                <li class="nav-item">
                    <a href="/home" id="dashboard"
                        class="nav-link <?php echo e(Request::url() == url('/home') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <?php if(isset(DB::table('schoolinfo')->first()->withschoolfolder)): ?>
                    <?php if(DB::table('schoolinfo')->first()->withschoolfolder == 1): ?>
                        <li class="nav-item">
                            <a class="<?php echo e(Request::url() == url('/schoolfolderv2/index') ? 'active' : ''); ?> nav-link"
                                href="/schoolfolderv2/index">
                                <i class="nav-icon fa fa-folder"></i>
                                <p>
                                    <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct'): ?>
                                        BCT Commons
                                    <?php else: ?>
                                        File Directory
                                    <?php endif; ?>
                                </p>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="/documenttracking"
                        class="nav-link <?php echo e(Request::url() == url('/documenttracking') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Document Tracking
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
                <li class="nav-item">
                    <a class="<?php echo e(Request::url() == url('/school-calendar') ? 'active' : ''); ?> nav-link"
                        href="/school-calendar">
                        <i class="nav-icon fas fa-calendar"></i>
                        <p>
                            School Calendar
                        </p>
                    </a>
                </li>
                
                

                <li class="nav-item">
                    <a href="/hr/settings/notification/index"
                        class="nav-link <?php echo e(Request::url() == url('/hr/settings/notification/index') ? 'active' : ''); ?>">
                        <i class="nav-icon  fas fa-bell"></i>
                        <p>
                            Notification & Request
                            
                        </p>
                    </a>
                </li>
                <?php
                    $countapproval = DB::table('hr_leaveemployeesappr')
                        ->where('appuserid', auth()->user()->id)
                        ->where('deleted', '0')
                        ->count();
                    // $countapproval = DB::table('hr_leavesappr')
                    // ->where('employeeid', $hr_profile->id)
                    // ->where('deleted','0')
                    // ->count();
                ?>

                <?php if($countapproval > 0): ?>
                    <li class="nav-item">
                        <a href="/hr/leaves/index"
                            class="nav-link <?php echo e(Request::url() == url('/hr/leaves/index') ? 'active' : ''); ?>">
                            <i class="fa fa-file-contract nav-icon"></i>
                            <p>
                                Filed Leaves
                            </p>
                        </a>
                    </li>
                <?php endif; ?>

                <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait' ||
                        strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lchsi'): ?>
                    <li
                        class="nav-item has-treeview <?php echo e(Request::fullUrl() == url('/administrator/schoolfolders') || Request::fullUrl() == url('/administrator/schoolfolders') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-layer-group"></i>
                            <p>
                                INTRANET
                                <i class="fas fa-angle-left right"
                                    style="right: 5%;
                                    top: 28%;"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
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

                <li class="nav-header">STUDENTS</li>
                <li
                    class="nav-item has-treeview <?php echo e(Request::fullUrl() == url('/registrar/studentmanagement') || Request::fullUrl() == url('/student/preregistration') || Request::url() == url('/registrar/leaf') || Request::url() == url('/student/requirements') || Request::url() == url('/student/loading') || Request::url() == url('/printable/studentacademicrecord') || Request::url() == url('/college/completiongrades') || Request::fullUrl() == url('/student/contactnumber') || Request::url() == url('/student/promotion') ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link ? 'menu-open' : '' }}">
                        <i class="nav-icon fas fa-user-graduate"></i>
                        <p>
                            Student Management
                            <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item" hidden>
                            <a href="/registrar/studentmanagement?sstatus=1"
                                class="nav-link <?php echo e(Request::url() == url('/registrar/studentmanagement') ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-user-graduate"></i>
                                <p>
                                    Student Information
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="<?php echo e(Request::url() == url('/student/preregistration') ? 'active' : ''); ?> nav-link"
                                href="/student/preregistration">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Enrollment
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/registrar/leaf"
                                class="nav-link <?php echo e(Request::url() == url('/registrar/leaf') ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    LEASF
                                </p>
                            </a>
                        </li>
                        <li class="nav-item" hidden>
                            <a class="<?php echo e(Request::url() == url('/student/medinfo') ? 'active' : ''); ?> nav-link"
                                href="/student/medinfo">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Medical Information
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/student/requirements"
                                class="nav-link <?php echo e(Request::url() == url('/student/requirements') ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Requirements
                                </p>
                            </a>
                        </li>
                        <?php if($iscollege): ?>
                            <li class="nav-item">
                                <a class="<?php echo e(Request::url() == url('/student/loading') ? 'active' : ''); ?> nav-link"
                                    href="/student/loading">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Student Loading
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="/printable/studentacademicrecord"
                                class="nav-link <?php echo e(Request::url() == url('/printable/studentacademicrecord') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Student Academic Record</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="<?php echo e(Request::url() == url('/college/completiongrades') ? 'active' : ''); ?> nav-link"
                                href="/college/completiongrades">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Grade Completion
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="<?php echo e(Request::fullUrl() == url('/student/contactnumber') ? 'active' : ''); ?> nav-link"
                                href="/student/contactnumber">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Contact Information
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="<?php echo e(Request::url() == url('/student/promotion') ? 'active' : ''); ?> nav-link"
                                href="/student/promotion">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Promotion
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="<?php echo e(Request::url() == url('/sadtr') ? 'active' : ''); ?> nav-link"
                                href="/sadtr">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Tap Monitoring
                                </p>
                            </a>
                        </li>
                        
                    </ul>
                </li>

                
                <?php
                    $temp_url_grade = [
                        (object) ['url' => url('/setup/subject')],
                        (object) ['url' => url('/setup/subject/plot')],
                        (object) ['url' => url('/grade/preschool/setup')],
                        (object) ['url' => url('/grade/prekinder/setup')],
                        (object) ['url' => url('/setup/attendance')],
                        (object) ['url' => url('/student/specialization')],
                        (object) ['url' => url('/basiced/student/specialclas')],
                        (object) ['url' => url('/basiced/student/excludedsubj')],
                        (object) ['url' => url('/transferedin/grades')],
                        (object) ['url' => url('/student/quarter')],
                    ];
                ?>
                

                
                <li class="nav-header">SETUP</li>


                <?php if($iscollege): ?>
                    <li
                        class="nav-item has-treeview <?php echo e(Request::fullUrl() == url('/college/section') || Request::fullUrl() == url('/setup/prospectus') || Request::fullUrl() == url('/setup/college') || Request::fullUrl() == url('/setup/course') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link ">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                College
                                <i class="fas fa-angle-left right" style="right: 5%;
									top: 28%;"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a href="/setup/college"
                                    class="nav-link <?php echo e(Request::url() == url('/setup/college') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Colleges
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/setup/course"
                                    class="nav-link <?php echo e(Request::url() == url('/setup/course') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Courses
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="<?php echo e(Request::url() == url('/setup/prospectus') ? 'active' : ''); ?> nav-link"
                                    href="/setup/prospectus">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Prospectus
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/college/section"
                                    class="nav-link <?php echo e(Request::url() == url('/college/section') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>College Sections</p>
                                </a>
                            </li>
                            
                            
                        </ul>
                    </li>
                <?php endif; ?>
                <?php if($issh || $isjs || $isgs || $isps): ?>
                    <?php
                        $temp_url_grade = [
                            (object) ['url' => url('/setup/sections')],
                            (object) ['url' => url('/setup/attendance')],
                            (object) ['url' => url('/setup/subject')],
                            (object) ['url' => url('/setup/subject/plot')],
                            (object) ['url' => url('/setup/track')],
                            (object) ['url' => url('/setup/strand')],
                            (object) ['url' => url('/setup/observed/values')],
                            // (object) ['url' => url('/setup/student/clearance/monitoring')],
                            // (object) ['url' => url('/setup/student/clearance/approval')],
                            // (object) ['url' => url('/setup/student/clearance/signatories')],
                            // (object) ['url' => url('/setup/acadterm')],
                        ];
                    ?>
                    <li
                        class="nav-item has-treeview <?php echo e(collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Basic Education
                                <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li
                                class="1 nav-item has-treeview <?php echo e(Request::fullUrl() == url('/setup/subject') || Request::fullUrl() == url('/setup/subject/plot') ? 'menu-open' : ''); ?>">
                                <a href="#"
                                    class="nav-link <?php echo e(Request::fullUrl() == url('/setup/subject') || Request::fullUrl() == url('/setup/subject/plot') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Subject Setup
                                        <i class="fas fa-angle-left right"
                                            style="right: 5%;
												top: 28%;"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo e(Request::url() == url('/setup/subject') ? 'active' : ''); ?>"
                                            href="/setup/subject">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Add Subject
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link <?php echo e(Request::url() == url('/setup/subject/plot') ? 'active' : ''); ?>"
                                            href="/setup/subject/plot">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Subject Plotting
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <?php if($issh): ?>
                                <li
                                    class="nav-item has-treeview <?php echo e(Request::fullUrl() == url('/setup/track') || Request::fullUrl() == url('/setup/strand') ? 'menu-open' : ''); ?>">
                                    <a href="#"
                                        class="nav-link <?php echo e(Request::fullUrl() == url('/setup/track') || Request::fullUrl() == url('/setup/strand') ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>
                                            Senior High
                                            <i class="fas fa-angle-left right"
                                                style="right: 5%;
												top: 28%;"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview udernavs">
                                        <li class="nav-item">
                                            <a href="/setup/track"
                                                class="nav-link <?php echo e(Request::url() == url('/setup/track') ? 'active' : ''); ?>">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>
                                                    Tracks
                                                </p>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="/setup/strand"
                                                class="nav-link <?php echo e(Request::url() == url('/setup/strand') ? 'active' : ''); ?>">
                                                <i class="far fa-dot-circle nav-icon"></i>
                                                <p>
                                                    Strands
                                                </p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item 1">
                                <a class="<?php echo e(Request::url() == url('/setup/sections') ? 'active' : ''); ?> nav-link"
                                    href="/setup/sections">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Sections
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item 1">
                                <a class="nav-link <?php echo e(Request::url() == url('/setup/attendance') ? 'active' : ''); ?>"
                                    href="/setup/attendance">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        School Days
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item 1">
                                <a class="nav-link <?php echo e(Request::url() == url('/setup/observed/values') ? 'active' : ''); ?>"
                                    href="/setup/observed/values">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Observed Values
                                    </p>
                                </a>
                            </li>
                            

                        </ul>
                    </li>

                    <?php
                        $temp_url_reportcard = [
                            (object) ['url' => url('/student/specialization')],
                            (object) ['url' => url('/basiced/student/specialclass')],
                            (object) ['url' => url('/basiced/student/excludedsubj')],
                            (object) ['url' => url('/student/quarter')],
                            (object) ['url' => url('/basiced/student/quarter')],
                            (object) ['url' => url('/transferedin/grades')],
                            // (object) ['url' => url('/setup/student/clearance/monitoring')],
                            // (object) ['url' => url('/setup/student/clearance/approval')],
                            // (object) ['url' => url('/setup/student/clearance/signatories')],
                            // (object) ['url' => url('/setup/acadterm')],
                        ];
                    ?>
                    <li
                        class="nav-item has-treeview <?php echo e(collect($temp_url_reportcard)->where('url', Request::url())->count() > 0 ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-cog"></i>
                            <p>
                                Report Card
                                <i class="fas fa-angle-left right"></i>
                                <span class="badge badge-info right"><?php echo e(count($temp_url_reportcard)); ?></span>
                            </p>
                        </a>
                        <ul class="nav nav-treeview ">
                            
                            
                            
                            <li class="nav-item">
                                <a class="<?php echo e(Request::fullUrl() == url('/student/specialization') ? 'active' : ''); ?> nav-link"
                                    href="/student/specialization">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Subject Specialization
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="<?php echo e(Request::fullUrl() == url('/basiced/student/specialclass') ? 'active' : ''); ?> nav-link"
                                    href="/basiced/student/specialclass">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Remedial Subject
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="<?php echo e(Request::fullUrl() == url('/basiced/student/excludedsubj') ? 'active' : ''); ?> nav-link"
                                    href="/basiced/student/excludedsubj">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Excluded Subject
                                    </p>
                                </a>
                            </li>
                            <li
                                class="nav-item has-treeview <?php echo e(Request::fullUrl() == url('/student/quarter') || Request::fullUrl() == url('/transferedin/grades') ? 'menu-open' : ''); ?>">
                                <a href="#"
                                    class="nav-link <?php echo e(Request::fullUrl() == url('/student/quarter') || Request::fullUrl() == url('/transferedin/grades') ? 'active' : ''); ?>">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        Transfer Setup
                                        <i class="fas fa-angle-left right"
                                            style="right: 5%;
												top: 28%;"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <li class="nav-item">
                                        <a class="<?php echo e(Request::fullUrl() == url('/student/quarter') ? 'active' : ''); ?> nav-link"
                                            href="/student/quarter">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Student Quarter (Trans. In)
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="<?php echo e(Request::fullUrl() == url('/transferedin/grades') ? 'active' : ''); ?> nav-link"
                                            href="/transferedin/grades">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                Transferred In Grades
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li
                    class="nav-item has-treeview <?php echo e(Request::fullUrl() == url('/admission/setup') || Request::fullUrl() == url('/setup/modeoflearning') || Request::fullUrl() == url('/superadmin/enrollmentsetup') || Request::fullUrl() == url('/setup/signatories') || Request::fullUrl() == url('/setup/signatories') || Request::fullUrl() == url('/setup/schoolyear') || Request::fullUrl() == url('/setup/document') || Request::url() == url('/setup/admissiondate') ? 'menu-open' : ''); ?>">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            School Configuration
                            <i class="fas fa-angle-left right" style="right: 5%;
								top: 28%;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview udernavs">
                        <li class="nav-item 1">
                            <a class="<?php echo e(Request::url() == url('/setup/schoolyear') ? 'active' : ''); ?> nav-link"
                                href="/setup/schoolyear">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    School Year
                                </p>
                            </a>
                        </li>
                        <li class="nav-item 1">
                            <a class="<?php echo e(Request::url() == url('/admission/setup') ? 'active' : ''); ?> nav-link"
                                href="/admission/setup">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Admission Date Setup
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="<?php echo e(Request::url() == url('/setup/modeoflearning') ? 'active' : ''); ?> nav-link"
                                href="/setup/modeoflearning">
                                <i class="nav-icon  far fa-circle"></i>
                                <p>
                                    Mode of Learning
                                </p>
                            </a>
                        </li>
                        <li class="nav-item 1">
                            <a class="nav-link <?php echo e(Request::url() == url('/setup/document') ? 'active' : ''); ?>"
                                href="/setup/document">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Docum. Requirements
                                </p>
                            </a>
                        </li>
                        <?php if($issh || $isjs || $isgs || $isps): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e(Request::url() == url('/setup/signatories') ? 'active' : ''); ?>"
                                    href="/setup/signatories">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>
                                        School Form Signatories
                                    </p>
                                </a>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>


                <li class="nav-header "> GENERAL REPORTS</li>
                <li class="nav-item">
                    <a href="/reportssummariesallstudentsnew/dashboard"
                        class="nav-link <?php echo e(Request::url() == url('/reportssummariesallstudentsnew/dashboard') ? 'active' : ''); ?>">
                        <i class="fas fa-file nav-icon"></i>
                        <p>Student Summary</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/registrar/reports/notenrolled"
                        class="nav-link <?php echo e(Request::url() == url('/registrar/reports/notenrolled') ? 'active' : ''); ?>">
                        <i class="fas fa-file  nav-icon"></i>
                        <p>Not Enrolled Students</p>
                    </a>
                <li class="nav-item">
                    <a href="/registrar/studentlist"
                        class="nav-link <?php echo e(Request::url() == url('/registrar/studentlist') ? 'active' : ''); ?>">
                        <i class="fas fa-file  nav-icon"></i>
                        <p>
                            Student List & <br /> Enrollment Summary
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/printable/numofstudents/index"
                        class="nav-link <?php echo e(Request::url() == url('/printable/numofstudents/index') ? 'active' : ''); ?>">
                        <i class="fas fa-file  nav-icon"></i>
                        
                        <p>Population Summary</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/printable/studentvacc/index"
                        class="nav-link <?php echo e(Request::url() == url('/printable/studentvacc/index') ? 'active' : ''); ?>">
                        <i class="fas fa-file  nav-icon"></i>
                        <p>Students Vaccination<br />Statistics</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/registrar/reports/monthly"
                        class="nav-link <?php echo e(Request::url() == url('/registrar/reports/monthly') ? 'active' : ''); ?>">
                        <i class="fas fa-file  nav-icon"></i>
                        <p>Monthly Enrollment Statistics</p>
                    </a>
                </li>

                <?php if($issh || $isjs || $isgs || $isps): ?>
                    <li class="nav-header "> BASIC EDUCATION REPORTS</li>
                    <li
                        class="nav-item has-treeview <?php echo e(Request::url() == url('/registar/schoolforms/index') || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=1' || Request::getRequestUri() == '/reports_schoolform4/dashboard' || Request::getRequestUri() == '/registar/schoolforms/index?sf=4' || Request::getRequestUri() == '/reports_schoolform6/dashboard' || Request::getRequestUri() == '/schoolform10/index' ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-file"></i>
                            <p>
                                School Forms
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li
                                class="nav-item has-treeview <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=5' ? 'menu-open' : ''); ?>">
                                <a href="#"
                                    class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=5' ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        SF 1
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <?php $__currentLoopData = collect($basicedacadprogs)->where('id', '!=', 6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eachacadprog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if(collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0): ?>
<li class="nav-item">
                                        <a href="/registar/schoolforms/index?sf=1&acadprogid=<?php echo e($eachacadprog->id); ?>"
                                            class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=1&acadprogid=' . $eachacadprog->id ? 'active' : ''); ?>">
                                            <i class="nav-icon  far fa-circle"></i>
                                            <p>
                                                <?php echo e($eachacadprog->progname); ?>

                                            </p>
                                        </a>
                                    </li>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </li>
                            <li
                                class="nav-item has-treeview <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=5' || Request::getRequestUri() == '/setup/forms/sf2' ? 'menu-open' : ''); ?>">
                                <a href="#"
                                    class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=5' || Request::getRequestUri() == '/setup/forms/sf2' ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        SF 2
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <?php $__currentLoopData = collect($basicedacadprogs)->where('id', '!=', 6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eachacadprog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if(collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0): ?>
<li class="nav-item">
                                        <a href="/registar/schoolforms/index?sf=2&acadprogid=<?php echo e($eachacadprog->id); ?>"
                                            class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=2&acadprogid=' . $eachacadprog->id ? 'active' : ''); ?>">
                                            <i class="nav-icon far fa-circle"></i>
                                            <p>
                                                <?php echo e($eachacadprog->progname); ?>

                                            </p>
                                        </a>
                                    </li>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <li class="nav-item">
                                        <a href="/setup/forms/sf2"
                                            class="nav-link <?php echo e(Request::url() == url('/setup/forms/sf2') ? 'active' : ''); ?>">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p>
                                                Lock SF2
                                            </p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li
                                class="nav-item <?php echo e(Request::url() == '/reports_schoolform4/dashboard' ? 'menu-open' : ''); ?>">
                                <a href="/reports_schoolform4/dashboard" id="dashboard"
                                    class="nav-link <?php echo e(Request::url() == url('/reports_schoolform4/dashboard') ? 'active' : ''); ?>">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 4
                                    </p>
                                </a>
                            </li>
                            <li
                                class="nav-item <?php echo e(Request::url() == '/registar/schoolforms/index?sf=4' ? 'menu-open' : ''); ?>">
                                <a href="/registar/schoolforms/index?sf=4" id="dashboard"
                                    class="nav-link <?php echo e(Request::url() == url('/registar/schoolforms/index?sf=4') ? 'active' : ''); ?>">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 4 (By AcadProg)
                                    </p>
                                </a>
                            </li>
                            <li
                                class="nav-item has-treeview  <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=5' ? 'menu-open' : ''); ?>">
                                <a href="#"
                                    class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=5' ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        SF 5
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <?php $__currentLoopData = collect($basicedacadprogs)->where('id', '!=', 6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eachacadprog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<?php if(collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0): ?>
<li class="nav-item">
                                        <a href="/registar/schoolforms/index?sf=5&acadprogid=<?php echo e($eachacadprog->id); ?>"
                                            class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=5&acadprogid=' . $eachacadprog->id ? 'active' : ''); ?>">
                                            <i class="nav-icon  far fa-circle"></i>
                                            <p>
                                                <?php echo e($eachacadprog->progname); ?>

                                            </p>
                                        </a>
                                    </li>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    <?php if(collect($activeacadprogs)->where('acadprogid', 5)->count() > 0): ?>
<li class="nav-item">
                                        <a href="/registar/schoolforms/index?sf=5a&acadprogid=5" id="dashboard"
                                            class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=5a&acadprogid=5' ? 'active' : ''); ?>">
                                            <i class="nav-icon fa fa-file"></i>
                                            <p>
                                                SF 5A
                                            </p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="/registar/schoolforms/index?sf=5b&acadprogid=5" id="dashboard"
                                            class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=5b&acadprogid=5' ? 'active' : ''); ?>">
                                            <i class="nav-icon fa fa-file"></i>
                                            <p>
                                                SF 5B
                                            </p>
                                        </a>
                                    </li>
<?php endif; ?>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="/registar/schoolforms/index?sf=6" id="dashboard"
                                    class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=6' ? 'active' : ''); ?>">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 6 (By AcadProg)
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item <?php echo e(Request::url() == '/schoolform/sf7' ? 'menu-open' : ''); ?>">
                                <a href="/schoolform/sf7" id="dashboard"
                                    class="nav-link <?php echo e(Request::url() == url('/schoolform/sf7') ? 'active' : ''); ?>">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 7
                                    </p>
                                </a>
                            </li>
                            
                            <li
                                class="nav-item has-treeview <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=5' ? 'menu-open' : ''); ?>">
                                <a href="#"
                                    class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=1' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=2' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=3' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=4' || Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=5' ? 'active' : ''); ?>">
                                    <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        SF 9
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview udernavs">
                                    <?php $__currentLoopData = collect($basicedacadprogs)->where('id', '!=', 6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eachacadprog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0): ?>
                                            <li class="nav-item">
                                                <a href="/registar/schoolforms/index?sf=9&acadprogid=<?php echo e($eachacadprog->id); ?>"
                                                    class="nav-link <?php echo e(Request::getRequestUri() == '/registar/schoolforms/index?sf=9&acadprogid=' . $eachacadprog->id ? 'active' : ''); ?>">
                                                    <i class="nav-icon  far fa-circle"></i>
                                                    <p>
                                                        <?php echo e($eachacadprog->progname); ?>

                                                    </p>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </li>
                            <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'apmc'): ?>
                                <li class="nav-item">
                                    <a href="/reports_schoolform10v2/index" id="dashboard"
                                        class="nav-link <?php echo e(Request::url() == url('/reports_schoolform10v2/index') ? 'active' : ''); ?>">
                                        <i class="nav-icon fa fa-file"></i>
                                        <p>
                                            SF 10
                                        </p>
                                    </a>
                                </li>
                            <?php else: ?>
                                <li class="nav-item">
                                    <a href="/schoolform10/index" id="dashboard"
                                        class="nav-link <?php echo e(Request::url() == url('/schoolform10/index') ? 'active' : ''); ?>">
                                        <i class="nav-icon fa fa-file"></i>
                                        <p>
                                            SF 10
                                        </p>
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a href="/schoolform10/index_v4" id="dashboard"
                                    class="nav-link <?php echo e(Request::url() == url('/schoolform10/index') ? 'active' : ''); ?>">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        SF 10 V4
                                    </p>
                                </a>
                            </li>

                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="/printable/certifications"
                            class="nav-link <?php echo e(Request::getRequestUri() == '/printable/certifications' ? 'active' : ''); ?>">
                            <i class="fas fa-file nav-icon"></i>
                            <p>Certifications</p>
                        </a>
                    </li>
                    <li
                        class="nav-item has-treeview <?php echo e(Request::url() == url('/registrar/student/awards') || Request::url() == url('/posting/grade') || Request::url() == url('/printable/certification/index') || Request::url() == url('/printable/cor') || Request::url() == url('/printable/masterlist') || Request::url() == url('/printable/othercertification/index') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link"><i class="nav-icon fas fa-file"></i>
                            <p>
                                Reports
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li
                                class="nav-item has-treeview <?php echo e(Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=1' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=2' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=3' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=4' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=5' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=6' ? 'menu-open' : ''); ?>">
                                <a href="#" class="nav-link"> <i class="nav-icon fas fa-file"></i>
                                    <p>
                                        Masterlist
                                        <i class="fas fa-angle-left right"></i>
                                    </p>
                                </a>
                                <ul
                                    class="nav nav-treeview udernavs <?php echo e(Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=1' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=2' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=3' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=4' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=5' || Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=6' ? 'menu-open' : ''); ?>">
                                    <?php $__currentLoopData = $basicedacadprogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eachacadprog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(collect($activeacadprogs)->where('acadprogid', $eachacadprog->id)->count() > 0): ?>
                                            <li class="nav-item">
                                                <a href="/printable/masterlist?sf=0&acadprogid=<?php echo e($eachacadprog->id); ?>"
                                                    class="nav-link <?php echo e(Request::getRequestUri() == '/printable/masterlist?sf=0&acadprogid=' . $eachacadprog->id ? 'active' : ''); ?>">
                                                    <i class="nav-icon  far fa-circle"></i>
                                                    <p>
                                                        <?php echo e($eachacadprog->progname); ?>

                                                    </p>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a href="/posting/grade" id="dashboard"
                                    class="nav-link <?php echo e(Request::url() == url('/posting/grade') ? 'active' : ''); ?>">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Grade Summary
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/registrar/student/awards" id="dashboard"
                                    class="nav-link <?php echo e(Request::url() == url('/registrar/student/awards') ? 'active' : ''); ?>">
                                    <i class="nav-icon far fa-circle"></i>
                                    <p>
                                        Student Awards
                                    </p>
                                </a>
                            </li>
                            
                            
                            <?php if($iscollege): ?>
                                <li class="nav-item" hidden>
                                    <a href="/printable/certification/index?type=graduation"
                                        class="nav-link <?php echo e(Request::getRequestUri() == '/printable/certification/index?type=graduation' ? 'active' : ''); ?>">
                                        <i class="far fa-circle nav-icon"></i>
                                        <p>Certification of Graduation</p> 
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <?php if($iscollege): ?>

                    <li class="nav-header ">COLLEGE REPORTS</li>
                    <li
                        class="nav-item has-treeview <?php echo e(Request::url() == url('/student/cor/printing') || Request::url() == url('/printable/coranking') || Request::url() == url('/schoolform/tor/index') || Request::url() == url('/schoolform/rcfg/index') || Request::url() == url('/registrar/reports/enrollment') || Request::url() == url('/student/permrecord/index') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link"> <i class="nav-icon fas fa-file"></i>
                            <p>
                                Certificates
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a class="<?php echo e(Request::fullUrl() == url('/student/cor/printing') ? 'active' : ''); ?> nav-link"
                                    href="/student/cor/printing">
                                    <i class="fas fa-file nav-icon"></i>
                                    <p>
                                        COR Printing
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/schoolform/tor/index" id="dashboard"
                                    class="nav-link <?php echo e(Request::url() == url('/schoolform/tor/index') ? 'active' : ''); ?>">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        Transcript of Records
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/schoolform/rcfg/index"
                                    class="nav-link <?php echo e(Request::url() == url('/schoolform/rcfg/index') ? 'active' : ''); ?>">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'mci'): ?>
                                            Application for Graduation<br />from Collegiate Course
                                        <?php else: ?>
                                            Record of Candidate<br />For Graduation
                                            <!--sbc-->
                                        <?php endif; ?>
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/printable/coranking"
                                    class="nav-link <?php echo e(Request::url() == url('/printable/coranking') ? 'active' : ''); ?>">
                                    <i class="fas fa-file nav-icon"></i>
                                    <p>Certification of Ranking</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li
                        class="nav-item has-treeview <?php echo e(Request::url() == url('/printable/gwaranking') || Request::url() == url('/registrar/summaries/alphaloading/index') || Request::url() == url('/registrar/reports/promotional') || Request::url() == url('/registrar/reports/enrollment') || Request::url() == url('/student/permrecord/index') || Request::url() == url('/sc/report/promotional') ? 'menu-open' : ''); ?>">
                        <a href="#" class="nav-link"> <i class="nav-icon fas fa-file"></i>
                            <p>
                                Reports
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview udernavs">
                            <li class="nav-item">
                                <a class="<?php echo e(Request::fullUrl() == url('/sc/report/promotional') ? 'active' : ''); ?> nav-link"
                                    href="/sc/report/promotional">
                                    <i class="nav-icon fa fa-file"></i>
                                    <p>
                                        Beginning & Promotional
                                    </p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/registrar/summaries/alphaloading/index"
                                    class="nav-link <?php echo e(Request::url() == url('/registrar/summaries/alphaloading/index') ? 'active' : ''); ?>">
                                    <i class="fas fa-file  nav-icon"></i>
                                    <p>Alpha Loading</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="/printable/gwaranking"
                                    class="nav-link <?php echo e(Request::url() == url('/printable/gwaranking') ? 'active' : ''); ?>">
                                    <i class="fas fa-file nav-icon"></i>
                                    <p>GWA Ranking</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php endif; ?>
                <li class="nav-header">Utility</li>
                <?php if($getSchoolInfo[0]->projectsetup == 'online'): ?>
                    <li class="nav-item 1">
                        <a class="<?php echo e(Request::url() == url('/textblast') ? 'active' : ''); ?> nav-link"
                            href="/textblast">
                            <i class="nav-icon far fa-paper-plane"></i>
                            <p>
                                Text Blast
                            </p>
                        </a>
                    </li>
                <?php endif; ?>
                <li class="nav-item 1">
                    <a class="<?php echo e(Request::url() == url('/teacher/student/credential') ? 'active' : ''); ?> nav-link"
                        href="/teacher/student/credential">
                        <i class="nav-icon far fa-paper-plane"></i>
                        <p>
                            Student Credentials
                        </p>
                    </a>
                </li>
                <li class="nav-item 1" hidden>
                    <a class="<?php echo e(Request::url() == url('/data/localtocloud') ? 'active' : ''); ?> nav-link"
                        href="/data/localtocloud">
                        <i class="nav-icon far fa-paper-plane"></i>
                        <p>
                            Data From Online
                        </p>
                    </a>
                </li>

                

                 
                <li class="nav-header text-warning">My Applications</li>
                
                <li class="nav-item">
                    <a href="/dtr/attendance/index"
                        class="nav-link <?php echo e(Request::url() == url('/dtr/attendance/index') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Daily Time Record
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/hr/requirements/employee"
                        class="nav-link <?php echo e(Request::fullUrl() === url('/hr/requirements/employee') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-folder-open"></i>
                        <p>
                            My Requirements
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/leaves/apply/index" id="dashboard"
                        class="nav-link <?php echo e(Request::url() == url('/leaves/apply/index') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Leave Applications
                        </p>
                    </a>
                </li>
                <?php echo $__env->make('components.privsidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </nav>
    </div>
    <script>
        window.onload = function() {
            const activeItem = document.querySelector(".sidebar .active");

            if (activeItem) {
                setTimeout(function() {
                    activeItem.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });
                }, 100);
            }

            // Monitor window resize events to ensure the scroll stays in place
            window.addEventListener("resize", () => {
                const activeItem = document.querySelector(".sidebar .active");
                if (activeItem) {
                    activeItem.scrollIntoView({
                        behavior: "smooth",
                        block: "center"
                    });
                }
            });
        };
    </script>
    <!-- /.sidebar -->

</aside>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/registrar/inc/sidenav.blade.php ENDPATH**/ ?>