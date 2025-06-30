<style>
    @keyframes blink {
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
</style>

<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
        <li class="nav-item">
            <a class="{{ Request::url() == url('/home') ? 'active' : '' }} nav-link" href="/home">
                <i class="nav-icon fa fa-home"></i>
                <p>
                    Home
                </p>
            </a>
        </li>

        <li class="nav-item">
            <a href="/user/profile" class="nav-link {{ Request::url() == url('/user/profile') ? 'active' : '' }}">
                <i class="nav-icon fa fa-user "></i>
                {{--  --}}
                <p>
                    Profile
                </p>
            </a>
        </li>


        @if (isset(DB::table('schoolinfo')->first()->withschoolfolder))
            @if (DB::table('schoolinfo')->first()->withschoolfolder == 1)
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/schoolfolderv2/index') ? 'active' : '' }} nav-link"
                        href="/schoolfolderv2/index">
                        <i class="nav-icon fa fa-folder"></i>
                        <p>
                            @if (strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                BCT Commons
                            @else
                                File Directory
                            @endif
                        </p>
                    </a>
                </li>
            @endif
        @endif

        {{-- <li class="nav-item">
            <a href="/user/notification/userview_notifications" class="nav-link">
                <i class="nav-icon fas fa-exclamation"></i>
                <p>Notifications</p> &nbsp;
                @php
                    $deptid = null;
                    $authid = auth()->user()->id;
                    $userid = DB::table('teacher')
                        ->where('userid', $authid)
                        ->first()->id;

                    // Fetch department ID, handle null if not found
                    $dept_userid = DB::table('employee_personalinfo')
                        ->where('employeeid', $userid)
                        ->first();
                    if ($dept_userid) {
                        if ($dept_userid->departmentid == null || $dept_userid->departmentid == '') {
                            $deptid = null;
                        } else {
                            $deptid = $dept_userid->departmentid;
                        }
                    }

                    $notifications = DB::table('hr_notifications')
                        ->where('sentrusystem', 1)
                        ->where('deleted', 0)
                        ->get()
                        ->map(function ($notification) use ($userid, $deptid) {
                            $recipientIds = explode(',', $notification->recipientid);
                            $acknowledgeIds = explode(',', $notification->acknowledgeby);

                            // Check if the user ID or department ID is in the recipient IDs
                            $isRecipient = in_array($userid, $recipientIds);
                            if ($deptid !== null) {
                                $isRecipient = $isRecipient || in_array($deptid, $recipientIds);
                            }

                            // Set acknowledge status
                            $notification->acknowledge_status = in_array($userid, $acknowledgeIds) ? 1 : 0;

                            // Return the notification only if the user or department is a recipient
                            return $isRecipient ? $notification : null;
                        })
                        ->filter();

                    $notacknowledge = count($notifications->where('acknowledge_status', 0));
                @endphp
                <span class="badge badge-light {{ $notacknowledge > 0 ? 'blink' : '' }}">{{ $notacknowledge }}</span>
            </a>
        </li> --}}

        <li class="nav-item">
            <a href="/hr/settings/notification/index"
                class="nav-link {{ Request::url() == url('/hr/settings/notification/index') ? 'active' : '' }}">
                <i class="nav-icon  fas fa-bell"></i>
                <p>
                    Notification & Request
                </p>
            </a>
        </li>

        <li class="nav-header ">SECTION</li>
        <li class="nav-item">
            <a class="{{ Request::fullUrl() == url('/college/teacher/sched?blade=blade') ? 'active' : '' }} nav-link"
                href="/college/teacher/sched?blade=blade">
                <i class="nav-icon fa fa-clipboard-list text-secondary"></i>
                <p>
                    Class Schedule Details
                </p>
            </a>
        </li>


        <li class="nav-header ">GRADING AND ATTENDANCE</li>
        <li class="nav-item">
            <a class="{{ Request::url() == url('/college/attendance-showpage') ? 'active' : '' }} nav-link"
                href="/college/attendance-showpage">
                <i class="nav-icon fas fa-user-check text-white"></i>
                <p>
                    Attendance <span class="right badge badge-warning" id="pending_grade_count"></span>
                </p>
            </a>
        </li>

        {{-- <li class="nav-item">
            <a class="{{ Request::url() == url('/college/teacher/student/grades') ? 'active' : '' }} nav-link"
                href="/college/teacher/student/grades">
                <i class="nav-icon fas fa-chart-bar text-success"></i>
                <p>
                    Student Grades <span class="right badge badge-warning" id="pending_grade_count"></span>
                </p>
            </a>
        </li> --}}


        {{-- //////////////////////////////////// --}}

        @php
            $temp_url_grade = [
                (object) ['url' => url('/college/teacher/student/excelupload')],
                (object) ['url' => url('/college/teacher/student/grades')],
                (object) ['url' => url('/college/teacher/student/collegesystemgrading')],
                // (object) ['url' => url('/student/loading')],
            ];
        @endphp
        <li
            class="nav-item has-treeview {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'menu-open' : '' }}">
            <a href="#"
                class="nav-link {{ collect($temp_url_grade)->where('url', Request::url())->count() > 0 ? 'active' : '' }}">
                <i class="nav-icon fas fa-chart-bar text-white"></i>
                <p>
                    Grading
                    <i class="fas fa-angle-left right" style="right: 5%;
						top: 28%;"></i>
                </p>
            </a>
            <ul class="nav nav-treeview udernavs">


                @if ($schoolinfo->projectsetup == 'offline' || $schoolinfo->processsetup == 'all')
                    <li class="nav-item">
                        <a class="{{ Request::url() == url('/college/teacher/student/collegesystemgrading') ? 'active' : '' }} nav-link"
                            href="/college/teacher/student/collegesystemgrading">
                            <i
                                class="nav-icon far fa-circle {{ Request::fullUrl() == url('/college/teacher/student/collegesystemgrading') ? 'text-dark' : '' }}"></i>
                            <p>
                                System Grading
                            </p>
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="{{ Request::url() == url('/college/teacher/student/excelupload') ? 'active' : '' }} nav-link"
                            href="/college/teacher/student/excelupload">
                            <i
                                class="nav-icon far fa-circle {{ Request::fullUrl() == url('/college/teacher/student/excelupload') ? 'text-dark' : '' }}"></i>
                            <p>
                                Excel Upload
                            </p>
                        </a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="{{ Request::url() == url('/college/teacher/student/grades') ? 'active' : '' }} nav-link"
                            href="/college/teacher/student/grades">
                            <i
                                class="nav-icon far fa-circle {{ Request::fullUrl() == url('/college/teacher/student/grades') ? 'text-dark' : '' }}"></i>
                            <p>
                                Final Grading
                            </p>
                        </a>
                    </li>
                @endif
                {{-- @if (($schoolinfo->projectsetup == 'online' && $schoolinfo->processsetup == 'hybrid1') || $schoolinfo->processsetup == 'all')
                    <li class="nav-item">
                        <a href="/college/grade/monitoring/teacher"
                            class="nav-link {{ Request::fullUrl() == url('/college/grade/monitoring/teacher') ? 'active' : '' }}">
                            <i class="nav-icon far fa-circle"></i>
                            <p>Student Grades</p>
                        </a>
                    </li>
                @endif --}}

            </ul>
        </li>




        {{-- ///////////////////////////////// --}}
        {{-- <li class="nav-item">
            <a  class="{{Request::fullUrl() == url('/college/grade/upload') ? 'active':''}} nav-link" href="/college/grade/upload">
                <i class="nav-icon fa fa-clipboard-list"></i>
                <p>
                       Grade Upload
                </p>
            </a>
        </li> --}}


        <li class="nav-header ">STUDENTS</li>
        <li class="nav-item">
            <a class="{{ Request::fullUrl() == url('/college/teacher/student/information') ? 'active' : '' }} nav-link"
                href="/college/teacher/student/information">
                <i class="nav-icon fa fa-users text-white"></i>
                <p>
                    Student Information
                </p>
            </a>
        </li>
        {{-- <li class="nav-item">
            <a  class="{{Request::fullUrl() == url('/college/setup/schedule/get/tchrcollegeviewschedules') ? 'active':''}} nav-link" href="/college/setup/schedule/get/tchrcollegeviewschedules">
                <i class="nav-icon fas fa-list"></i>
                <p>
                    Scheduling
                </p>
            </a>
        </li> --}}

        <li class="nav-header ">My Applications</li>
        {{-- <li class="nav-item">
            <a href="/hr/leaves/index?action=myleave" class="nav-link {{ Request::fullUrl() === url('/hr/leaves/index?action=myleave') ? 'active' : '' }}">
                <i class="nav-icon fa fa-calendar-alt"></i>
                <p>
                    Leave Applications
                </p>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="/leaves/apply/index" id="dashboard"
                class="nav-link {{ Request::url() == url('/leaves/apply/index') ? 'active' : '' }}">
                <i class="nav-icon fa fa-file"></i>
                <p>
                    Leave Applications
                </p>
            </a>
        </li>
        <li class="nav-header ">Employee Requirements</li>
        <li class="nav-item">
            <a href="/hr/requirements/employee"
                class="nav-link {{ Request::fullUrl() === url('/hr/requirements/employee') ? 'active' : '' }}">
                <i class="nav-icon fa fa-folder-open"></i>
                <p>
                    My Requirements
                </p>
            </a>
        </li>

        <li class="nav-header ">Other Portal</li>
        @php
            $priveledge = DB::table('faspriv')
                ->join('usertype', 'faspriv.usertype', '=', 'usertype.id')
                ->select('faspriv.usertype', 'usertype.utype')
                ->where('userid', auth()->user()->id)
                ->where('faspriv.deleted', '0')
                ->where('type_active', 1)
                ->where('faspriv.privelege', '!=', '0')
                ->get();

            $usertype = DB::table('usertype')
                ->where('deleted', 0)
                ->where('id', auth()->user()->type)
                ->first();
        @endphp
        @if ($usertype->id != Session::get('currentPortal'))
            <li class="nav-item">
                <a class="nav-link portal" href="/gotoPortal/{{ $usertype->id }}">
                    <i class=" nav-icon fas fa-cloud"></i>
                    <p>
                        {{ $usertype->utype }}
                    </p>
                </a>
            </li>
        @endif
        @foreach ($priveledge as $item)
            @if ($item->usertype != Session::get('currentPortal'))
                <li class="nav-item">
                    <a class="nav-link portal" href="/gotoPortal/{{ $item->usertype }}" id="{{ $item->usertype }}">
                        <i class=" nav-icon fas fa-cloud"></i>
                        <p>
                            {{ $item->utype }}
                        </p>
                    </a>
                </li>
            @endif
        @endforeach

    </ul>
</nav>
