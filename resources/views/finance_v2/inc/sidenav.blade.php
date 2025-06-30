<style>
    .nav-link,
    .nav-header {
        color: #343A30 !important;
    }

    .nav-link.active {
        color: #F0F0F0 !important;
        background: #787c7f !important;
    }

    .text-clr {
        color: #343A30 !important;
    }

    .user-panel {
        border-bottom: 1px solid #dee2e6 !important;
    }

    .brand-link {
        border-bottom: 1px solid #dee2e6 !important;
    }
</style>

<aside class="main-sidebar sidebar-dark-primary elevation-4 asidebar" style="background-color: white !important">
    <div class="">
        <a href="#" class="brand-link" style="background-color: white !important;">
            @if (DB::table('schoolinfo')->first()->picurl != null)
                <img src="{{ asset(DB::table('schoolinfo')->first()->picurl) }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" height="100%" width="100%"
                    style="opacity: .8; height: 30px; width: 30px;"
                    onerror="this.src='{{ asset('assets/images/department_of_Education.png') }}'">
            @else
                <img src="{{ asset('assets/images/department_of_Education.png') }}" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
            @endif
            <span class="brand-text text-clr" style="position: absolute;top: 6%; font-weight: 500 !important">
                {{ DB::table('schoolinfo')->first()->abbreviation }}
            </span>
            <span class="brand-text font-weight-light text-clr"
                style="position: absolute;top: 50%;font-size: 16px!important;"><b>Finance Portal</b></span>
        </a>
    </div>
    <div id="sidenav-container" class="sidebar"
        style="overflow-x: hidden; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none;
        background-color: white;">
        @php
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
        @endphp
        <div class="row">
            <div class="col-md-12">
                <div class="text-center mt-2">
                    <img class="profile-user-img img-fluid img-circle" src="{{ asset($picurl) }}"
                        onerror="this.onerror=null; this.src='{{ asset($avatar) }}'" alt="User Image" width="100%"
                        height="100%" style="width:130px;  border-radius: 12% !important;">
                </div>
            </div>
        </div>
        <div class="row user-panel">
            <div class="col-md-12 info text-center">
                <a class="mb-0 text-clr" style="font-weight: 500">{{ auth()->user()->name }}</a>
                <h6 class="text-clr text-center" style="font-weight: 400">{{ auth()->user()->email }}</h6>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/home') ? 'active' : '' }} nav-link" href="/home">
                        <i class="nav-icon fa fa-home"></i>
                        <p>
                            Home
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/user/profile"
                        class="nav-link {{ Request::url() == url('/user/profile') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-user"></i>
                        <p>
                            Profile
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/school-calendar"
                        class="nav-link {{ Request::url() == url('/school-calendar') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-calendar"></i>
                        <p>
                            Calendar
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/hr/settings/notification/index"
                        class="nav-link {{ Request::url() == url('/hr/settings/notification/index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-envelope"></i>
                        <p>
                            Notification & Request
                            {{-- <span class="ml-2 badge badge-primary">2</span> --}}
                        </p>
                    </a>
                </li>




                <li class="nav-header">
                    SETUP
                </li>


                <li
                    class="nav-item has-treeview {{ Request::url() == url('/financev2/setup/') || Request::url() == url('/financev2/setup') || Request::url() == url('/financev2/') || Request::url() == url('/financev2/') || Request::url() == url('/financev2/') || Request::url() == url('/financev2/') ? 'menu-open' : '' }}">
                    <a href=""
                        class="nav-link {{ Request::url() == url('/financev2/setup/') || Request::url() == url('/financev2/') || Request::url() == url('/financev2/setup') || Request::url() == url('/financev2/') || Request::url() == url('/financev2/') || Request::url() == url('/financev2/') ? 'active' : '' }}">

                <li class="nav-item has-treeview {{ Request::url() == url('/financev2/setup/1') || Request::url() == url('/financev2/setup/2') || Request::url() == url('/financev2/setup/3') || Request::url() == url('/financev2/setup/4')  ? 'menu-open' : '' }}">
                    <a href=""
                       class="nav-link {{ Request::url() == url('/financev2/setup/1') || Request::url() == url('/financev2/setup/2') || Request::url() == url('/financev2/setup/3') || Request::url() == url('/financev2/setup/4')  ? 'active' : '' }}"
                    >   

                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            School Fees Setting
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/financev2/setup/1"
                                class="nav-link {{ Request::url() == url('/financev2/setup/1') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Fees Classification
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/financev2/setup/2"
                                class="nav-link {{ Request::url() == url('/financev2/setup/2') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Laboratory Fees
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/financev2/setup/3"
                                class="nav-link {{ Request::url() == url('/financev2/setup/3') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Payment Schedule
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/financev2/setup/4"
                                class="nav-link {{ Request::url() == url('/financev2/setup/4') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    School Fees
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="/" class="nav-link {{ Request::url() == url('/') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-tags"></i>
                        <p>
                            Discount Setup
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('book_list') }}"
                        class="nav-link {{ Request::routeIs('book_list') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-book"></i>
                        <p>
                            Book List
                        </p>
                    </a>
                </li>


                <li class="nav-header">STUDENT</li>
                <li class="nav-item">
                    <a href="/financev2/studentaccounts"
                        class="nav-link {{ Request::url() == url('/financev2/studentaccounts') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-credit-card"></i>
                        <p>
                            Student Accounts
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/" class="nav-link {{ Request::url() == url('/') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-arrow-circle-right"></i>
                        <p>
                            Overpayment & Refunds
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/" class="nav-link {{ Request::url() == url('/') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-id-card"></i>
                        <p>
                            Examination Permit
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/" class="nav-link {{ Request::url() == url('/') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-archive"></i>
                        <p>
                            Old Accounts
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/" class="nav-link {{ Request::url() == url('/') ? 'active' : '' }}">
                        <i class="nav-icon fab fa-internet-explorer"></i>
                        <p>
                            Online Payments
                        </p>
                    </a>
                </li>


                <li class="nav-header">EXPENSES & DISBURSEMENT</li>
                <li class="nav-item">
                    <a href="/" class="nav-link {{ Request::url() == url('/') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-chart-pie"></i>
                        <p>
                            Expenses Monitoring
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('disbursement') }}"
                        class="nav-link {{ Request::routeIs('disbursement') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-money-bill-wave"></i>
                        <p>
                            Disbursement
                        </p>
                    </a>
                </li>

                <li class="nav-header">FINANCE REPORTS</li>
                <li class="nav-item">
                    <a href="/" class="nav-link {{ Request::url() == url('/') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>
                            Finance Reports
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/" class="nav-link {{ Request::url() == url('/') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-spinner"></i>
                        <p>
                            Pending Transactions
                        </p>
                    </a>
                </li>



                <li class="nav-header">Utility</li>
                <li class="nav-item">
                    <a href="/textblast" class="nav-link {{ Request::url() == url('/textblast') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-paper-plane"></i>
                        <p>
                            Textblast
                        </p>
                    </a>
                </li>


                <li class="nav-header text-warning">My Other Portal</li>

                @php
                    $priveledge = DB::table('faspriv')
                        ->join('usertype', 'faspriv.usertype', '=', 'usertype.id')
                        ->select('faspriv.*', 'usertype.utype')
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

                @foreach ($priveledge as $item)
                    @if ($item->usertype != Session::get('currentPortal'))
                        <li class="nav-item">
                            <a class="nav-link portal" href="/gotoPortal/{{ $item->usertype }}"
                                id="{{ $item->usertype }}">
                                <i class=" nav-icon fas fa-cloud"></i>
                                <p>
                                    {{ $item->utype }}
                                </p>
                            </a>
                        </li>
                    @endif
                @endforeach

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
            </ul>
        </nav>
    </div>
</aside>
