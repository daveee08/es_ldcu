<?php
    $getSchoolInfo = DB::table('schoolinfo')->select('region', 'division', 'district', 'schoolname', 'schoolid')->get();
    $syid = DB::table('sy')->where('isactive', '1')->first();
    $getProgname = DB::table('teacher')
        ->select('sectiondetail.syid', 'academicprogram.id as acadprogid', 'academicprogram.progname')
        ->join('sectiondetail', 'teacher.id', '=', 'sectiondetail.teacherid')
        ->join('sections', 'sectiondetail.sectionid', '=', 'sections.id')
        ->join('gradelevel', 'sections.levelid', '=', 'gradelevel.id')
        ->join('academicprogram', 'gradelevel.acadprogid', '=', 'academicprogram.id')
        ->where('teacher.userid', auth()->user()->id)
        ->where('sections.deleted', '0')
        ->where('sectiondetail.deleted', '0')
        ->where('gradelevel.deleted', '0')
        ->distinct('progname')
        ->get();

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
<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4" style="background-color: white !important">

    <div>
        <a href="/home" class="brand-link nav-bg">
            <img src="<?php echo e(asset(DB::table('schoolinfo')->first()->picurl)); ?>" 
                class="brand-image img-circle elevation-3 " style="opacity: .8 !important; width: 33px; height: 33px;"
                onerror="this.src='<?php echo e(asset('assets/images/department_of_Education.png')); ?>'">
            <span class="brand-text font-weight-light"
                style="position: absolute;top: 6%;"><?php echo e(DB::table('schoolinfo')->first()->abbreviation); ?></span>
            <span class="brand-text font-weight-light"
                style="position: absolute;top: 50%;font-size: 16px!important;"><b>TEACHER'S
                    PORTAL</b></span>
        </a>
    </div>
    <!-- Sidebar -->
    <div class="sidebar">

        <?php
            if (Auth::check()) {
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
                        'employee_personalinfo.date_joined',
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
            } else {
                $avatar = 'assets/images/avatars/unknown.png';
                $teacher_profile = (object) [
                    'picurl' => $avatar,
                ];
            }
        ?>
        <div class="row pt-2">
            <div class="col-md-12">
                <div class="text-center">
                    <img class="profile-user-img img-fluid img-circle"
                        src="<?php echo e(asset($teacher_profile->picurl)); ?>?random=<?php echo e(\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss')); ?>"
                        onerror="this.onerror=null; this.src='<?php echo e(asset($avatar)); ?>';" alt="User Image"
                        style="max-width:130px; width:100%; height:auto; aspect-ratio:1/1; border-radius:20px !important; object-fit:cover; background:#f0f0f0;">
                </div>
            </div>
        </div>
        <div class="row  user-panel">
            <div class="col-md-12 info text-center">
                <h6 class=" mb-0 "><?php echo e(auth()->user()->name); ?></h6>
                <h6 class="text-center"><?php echo e(auth()->user()->email); ?></h6>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu"
                data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                            with font-awesome or any other icon font library -->
                <!-- <li class="nav-header text-warning"><h4>TEACHER'S PORTAL</h4></li> -->
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
                    <a href="/hr/settings/notification/index"
                        class="nav-link <?php echo e(Request::url() == url('/hr/settings/notification/index') ? 'active' : ''); ?>">
                        <i class="nav-icon  fas fa-bell"></i>
                        <p>
                            Notification & Request
                            
                        </p>
                    </a>
                </li>

                <li
                    class="nav-item has-treeview <?php echo e(Request::url() == url('/classattendance') || Request::url() == url('/beadleAttendance') ? 'menu-open' : ''); ?>">
                    <a href="/principal/setup/schedule/get/teachersview" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Scheduling
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

                
                
                <li class="nav-header">
                    STUDENTS
                </li>
                <li
                    class="nav-item has-treeview <?php echo e(Request::url() == url('/classattendance') || Request::url() == url('/beadleAttendance') ? 'menu-open' : ''); ?>">
                    <a href="#"
                        class="nav-link <?php echo e(Request::url() == url('/classattendance') || Request::url() == url('/beadleAttendance') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            Attendance
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/classattendance"
                                class="nav-link <?php echo e(Request::url() == url('/classattendance') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Advisory
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/beadleAttendance?version=3"
                                class="nav-link <?php echo e(Request::url() == url('/beadleAttendance') ? 'active' : ''); ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    By Subject
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php
                    $temp_urls = [
                        (object) ['url' => url('/students/advisory')],
                        (object) ['url' => url('/students/bysubject')],
                        (object) ['url' => url('/teacher/student/credential')],
                        (object) ['url' => url('/sadtr')],
                        (object) ['url' => url('/students/clearance')],
                        (object) ['url' => url('/students/clearance/bysubject')],
                    ];
                ?>
                <li
                    class="nav-item has-treeview <?php echo e(collect($temp_urls)->where('url', Request::url())->count() > 0 ? 'menu-open' : ''); ?>">
                    <a href="#"
                        class="nav-link <?php echo e(collect($temp_urls)->where('url', Request::url())->count() > 0 ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Students Information
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/students/advisory" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Advisory
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/students/bysubject" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    By Subject
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/teacher/student/credential" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Student Credentials
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/sadtr" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Tap Monitoring
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/students/clearance" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Clearance Advisory
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/students/clearance/bysubject" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Clearance By Subject
                                </p>
                            </a>
                        </li>

                    </ul>
                </li>

                <?php
                    $temp_urls = [
                        (object) ['url' => url('/grades/index')],
                        (object) ['url' => url('/classschedule')],
                        (object) ['url' => url('/teacher/finalgrades')],
                        (object) ['url' => url('/teacher/pending/grades/view')],
                        (object) ['url' => url('/grade/observedvalues')],
                        (object) ['url' => url('/reportcard/quarterremarks')],
                        (object) ['url' => url('/grade/deportment-record')],
                    ];
                ?>

                
                <li
                    class="nav-item has-treeview <?php echo e(collect($temp_urls)->where('url', Request::url())->count() > 0 ? 'menu-open' : ''); ?>">
                    <a href="#"
                        class="nav-link <?php echo e(collect($temp_urls)->where('url', Request::url())->count() > 0 ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-star"></i>
                        <p>
                            Grades
                            <i class="fas fa-angle-left right"></i>
                            <span class="badge badge-warning pending_status_holder right" hidden>WP</span>
                        </p>

                    </a>
                    <ul class="nav nav-treeview ">
                        <li class="nav-item">
                            <a href="/grades/index"
                                class="nav-link <?php echo e(request()->is('subjects/*') || Request::url() == url('/grades/index') || Request::url() == url('/grades/getsubjects') ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    System Grading <span class="badge badge-warning right section_pending"
                                        hidden></span>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/classschedule" id="dashboard" class="nav-link">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Excel Upload <span class="badge badge-warning right section_pending" hidden></span>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/teacher/finalgrades" id="dashboard" class="nav-link">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Final Grading <span class="badge badge-warning right pending_status_holder"
                                        hidden>WP</span>
                                </p>
                            </a>
                        </li>

                        <li class="nav-item" id="pre_grade_sidenav">
                            <a href="/grade/prekinder" id="dashboard" class="nav-link">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Pre-School
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/teacher/pending/grades/view" id="dashboard"
                                class="nav-link <?php echo e(Request::url() == url('/teacher/pending/grades/view') ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Pending Grades <span class="badge badge-warning right student_pending"
                                        hidden></span>
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/grade/observedvalues" id="dashboard"
                                class="nav-link <?php echo e(Request::url() == url('/grade/observedvalues') ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Observed Values
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/reportcard/quarterremarks" id="dashboard"
                                class="nav-link <?php echo e(Request::url() == url('/reportcard/quarterremarks') ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Quarter Remarks
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/grade/deportment-record" id="dashboard"
                                class="nav-link <?php echo e(Request::url() == url('/grade/deportment-record') ? 'active' : ''); ?>">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Deportment Record
                                </p>
                            </a>
                        </li>
                    </ul>
                    <script>
                        $(document).ready(function() {
                            var uri = <?php echo json_encode(\Request::path(), 15, 512) ?>;
                            $('a[href="/' + uri + '"]').addClass('active')
                            $.ajax({
                                url: '/teacher/get/pending',
                                type: "GET",
                                success: function(data) {
                                    if (data[0].with_pending) {
                                        $('.pending_status_holder').removeAttr('hidden')
                                        if (data[0].student_pending_count > 0) {
                                            $('.student_pending').removeAttr('hidden')
                                            $('.student_pending').text(data[0].student_pending_count)
                                        } else {
                                            $('#student_pending').attr('hidden', 'hidden')
                                        }
                                        if (data[0].section_pending_count > 0) {
                                            $('.section_pending').removeAttr('hidden')
                                            $('.section_pending').text(data[0].section_pending_count)
                                        } else {
                                            $('#section_pending').attr('hidden', 'hidden')
                                        }
                                    } else {

                                    }
                                }
                            });
                        })
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
                </li>
                <li class="nav-item">
                    <a href="/setup/sections" id="dashboard"
                        class="nav-link  <?php echo e(request()->is('principalPortalSectionProfile/*') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Advisory Information
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo e(Request::url() == url('/teacher/evaluation/teacher') ? 'active' : ''); ?>"
                        href="/teacher/evaluation/teacher">
                        <i class="nav-icon fas fa-layer-group"></i>
                        <p>
                            Peer Evaluation
                        </p>
                    </a>
                </li>
                <?php
                    $checkifExists = Db::table('teacher')
                        ->join('sectiondetail', 'teacher.id', '=', 'sectiondetail.teacherid')
                        ->where('teacher.userid', auth()->user()->id)
                        ->where('sectiondetail.deleted', '0')
                        ->get();
                    $countExists = count($checkifExists);
                    $currentMonth = \Carbon\Carbon::now()->month;
                ?>

                <?php
                    $temp_urls = [
                        (object) ['url' => url('/forms/index/form1')],
                        (object) ['url' => url('/forms/index/form2')],
                        (object) ['url' => url('/forms/index/form5a')],
                        (object) ['url' => url('/forms/index/form5b')],
                        (object) ['url' => url('/forms/index/form9')],
                        (object) ['url' => url('/teacher/grade/summary')],
                        (object) ['url' => url('/teacher/student/ranking')],
                    ];
                ?>


                <li class="nav-header">PRINTABLES</li>

                <li class="nav-item menu-open">
                    <a href="/registar/schoolforms/index?sf=ssf" class="nav-link active <?php echo e(Request::url() == url('/registar/schoolforms/index?sf=ssf') ? 'active' : ''); ?>">
                        <i class="nav-icon fas fa-file"></i>
                        <p>
                            Students' Status Form
                        </p>
                    </a>
                </li>
                <?php if(count($getProgname) > 0): ?>
                    <?php if($countExists > 0): ?>

                        <li
                            class="nav-item has-treeview <?php echo e(Request::url() == url('/forms/form2') || Request::url() == url('/forms/form9') || Request::url() == url('/forms/index/form1') || Request::url() == url('/forms/index/form2') || Request::url() == url('/forms/index/form5') || Request::url() == url('/forms/index/form5a') || Request::url() == url('/forms/index/form5b') || Request::url() == url('/forms/index/form9') ? 'menu-open' : ''); ?>">
                            <a href="#"
                                class="nav-link <?php echo e(Request::url() == url('/forms/form2') || Request::url() == url('/forms/form9') || Request::url() == url('/forms/index/form1') || Request::url() == url('/forms/index/form2') || Request::url() == url('/forms/index/form5') || Request::url() == url('/forms/index/form5a') || Request::url() == url('/forms/index/form5b') || Request::url() == url('/forms/index/form9') ? 'active' : ''); ?>">
                                <i class="nav-icon fas fa-chart-pie"></i>
                                <p>
                                    School Forms
                                    <i class="right fas fa-angle-left"></i>
                                </p>
                            </a>
                            <ul class="nav nav-treeview">
                                <?php
                                    $schoolinfo = Db::table('schoolinfo')->first();
                                ?>
                                <li class="nav-item">
                                    <a href="/forms/index/form1"
                                        class="nav-link <?php echo e(Request::url() == url('/forms/index/form1') ? 'active' : ''); ?> ">
                                        <i class="nav-icon fa fa-file"></i>
                                        <p>
                                            SCHOOL FORM 1
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/forms/index/form2"
                                        class="nav-link <?php echo e(Request::url() == url('/forms/index/form2') || Request::url() == url('/forms/form2') ? 'active' : ''); ?> ">
                                        <i class="nav-icon fa fa-file"></i>
                                        <p>
                                            SCHOOL FORM 2
                                        </p>
                                    </a>
                                </li>
                                <li
                                    class="nav-item has-treeview  <?php echo e(Request::url() == url('/forms/index/form5') || Request::url() == url('/forms/index/form5a') || Request::url() == url('/forms/index/form5b') ? 'menu-open' : ''); ?>">

                                    

                                    <a href="#"
                                        class="nav-link <?php echo e(Request::url() == url('/forms/index/form5') || Request::url() == url('/forms/index/form5a') || Request::url() == url('/forms/index/form5b') ? 'active' : ''); ?> ">
                                        <i class="nav-icon fa fa-file"></i>
                                        <p>
                                            SCHOOL FORM 5
                                            <i class="right fas fa-angle-left"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview ml-3">
                                        <?php if(collect($getProgname)->whereIn('acadprogid', [2, 3, 4])->count() > 0): ?>
                                            <li class="nav-item">
                                                <a href="/forms/index/form5?acadprogid=0"
                                                    class="nav-link <?php echo e(Request::url() == url('/forms/index/form5') ? 'active' : ''); ?> ">
                                                    <i class="nav-icon fa fa-file"></i>
                                                    <p>
                                                        SCHOOL FORM 5
                                                    </p>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if(collect($getProgname)->where('acadprogid', '5')->count() > 0): ?>
                                            <li class="nav-item">
                                                <a href="/forms/index/form5a?acadprogid=5"
                                                    class="nav-link <?php echo e(Request::url() == url('/forms/index/form5a') ? 'active' : ''); ?> ">
                                                    <i class="nav-icon fa fa-file"></i>
                                                    <p>
                                                        SCHOOL FORM 5A
                                                    </p>
                                                </a>
                                            </li>
                                            <li class="nav-item">
                                                <a href="/forms/index/form5b?acadprogid=5"
                                                    class="nav-link <?php echo e(Request::url() == url('/forms/index/form5b') ? 'active' : ''); ?> ">
                                                    <i class="nav-icon fa fa-file"></i>
                                                    <p>
                                                        SCHOOL FORM 5B
                                                    </p>
                                                </a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                                <li class="nav-item">
                                    <a href="/forms/index/form9"
                                        class="nav-link <?php echo e(Request::url() == url('/forms/index/form9') || Request::url() == url('/forms/form9') ? 'active' : ''); ?> ">
                                        <i class="nav-icon fa fa-file"></i>
                                        <p>
                                            SCHOOL FORM 9
                                        </p>
                                    </a>
                                </li>
                                
                                <li class="nav-item">
                                    <a href="/teacher/grade/summary" id="dashboard" class="nav-link">
                                        <i class="nav-icon far fa-chart-bar"></i>
                                        <p>
                                            Grade Summary
                                        </p>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="/teacher/student/ranking" id="dashboard" class="nav-link">
                                        <i class="nav-icon far fa-chart-bar"></i>
                                        <p>
                                            Student Awards
                                        </p>
                                    </a>
                                </li>
                            </ul>
                        </li>
                    <?php else: ?>
                    <?php endif; ?>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="/teacher/grade/summary/quarter" id="dashboard" class="nav-link">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Quarter Grades
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/teacher/teachingload" class="nav-link">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Teaching Loads
                        </p>
                    </a>
                </li>

                <?php
                $countapproval = DB::table('hr_leaveemployeesappr')
                    ->where('appuserid', auth()->user()->id)
                    ->where('deleted', '0')
                    ->count();
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

            <li class="nav-header text-warning">My Applications</li>
            
            <li class="nav-item">
                <a href="/leaves/apply/index" id="dashboard"
                    class="nav-link <?php echo e(Request::url() == url('/leaves/apply/index') ? 'active' : ''); ?>">
                    <i class="nav-icon fa fa-file"></i>
                    <p>
                        Leave Applications
                    </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="/dtr/attendance/index"
                    class="nav-link <?php echo e(Request::url() == url('/dtr/attendance/index') ? 'active' : ''); ?>">
                    <i class="nav-icon fa fa-file"></i>
                    <p>
                        Daily Time Record
                    </p>
                </a>
            </li>

            <li class="nav-header text-warning">Employee Requirements</li>
            <li class="nav-item">
                <a href="/hr/requirements/employee"
                    class="nav-link <?php echo e(Request::fullUrl() === url('/hr/requirements/employee') ? 'active' : ''); ?>">
                    <i class="nav-icon fa fa-folder-open"></i>
                    <p>
                        My Requirements
                    </p>
                </a>
            </li>
                <li class="nav-header text-warning">Clinic Appointment</li>
                <li class="nav-item">
                    <a href="/clinic/patientdashboard/index" id="dental"
                        class="nav-link <?php echo e(Request::url() == url('clinic/patientdashboard/index') ? 'active' : ''); ?>">
                        <i class="nav-icon fa fa-user-md"></i>
                        <p>
                            Create Appointment
                        </p>
                    </a>
                </li>

                <li class="nav-header">UTILITY</li>
                <li class="nav-item">
                    <a class="<?php echo e(Request::url() == url('guidance/referralTeacher') ? 'active' : ''); ?> nav-link"
                        href="/guidance/referralTeacher">
                        <i class="nav-icon far fa-handshake"></i>
                        <p>
                            Referral
                        </p>
                    </a>
                </li>


                <?php echo $__env->make('components.privsidenav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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
        </nav>
    </div>

</aside>
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/teacher/inc/sidenav.blade.php ENDPATH**/ ?>