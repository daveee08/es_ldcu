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

    .nav-icon {
        color: #343A30 !important;
    }

    .fa-angle-left {
        color: #343A30 !important;
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
                style="position: absolute;top: 50%;font-size: 16px!important;"><b>Bookkeeper</b></span>
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
                {{-- <li class="nav-item">
                    <a class="{{ Request::url() == url('/schoolfolderv2/index') ? 'active' : '' }} nav-link"
                        href="/schoolfolderv2/index">
                        <i class="nav-icon fa fa-folder"></i>
                        <p>
                            File Directory
                        </p>
                    </a>
                </li> --}}
                <li class="nav-item">
                    <a class="{{ Request::url() == url('/') ? 'active' : '' }} nav-link" href="/">
                        <i class="nav-icon fa fa-sitemap"></i>
                        <p>
                            Flowchart
                        </p>
                    </a>
                </li>

                <li class="nav-header">
                    SETUP
                </li>

                <li
                    class="nav-item has-treeview {{ 
                    Request::url() == url('/bookkeeper/chart_of_accounts') || 
                    Request::url() == url('/bookkeeper/setup')  || 
                    Request::url() == url('/bookkeeper/fiscal_year')  || 
                    Request::url() == url('/bookkeeper/enrollment_setup') ||
                    Request::url() == url('/bookkeeper/other_setup') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ 
                        Request::url() == url('/bookkeeper/chart_of_accounts') || 
                        Request::url() == url('/bookkeeper/fiscal_year')  || 
                        Request::url() == url('/bookkeeper/setup')  || 
                        Request::url() == url('/bookkeeper/enrollment_setup')  ||
                        Request::url() == url('/bookkeeper/other_setup') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            General Configuration
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/bookkeeper/chart_of_accounts"
                                class="nav-link {{ Request::url() == url('/bookkeeper/chart_of_accounts') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Charts of Accounts
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/fiscal_year"
                                class="nav-link {{ Request::url() == url('bookkeeper/fiscal_year') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Fiscal Year
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/enrollment_setup"
                                class="nav-link {{ Request::url() == url('/bookkeeper/enrollment_setup') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Enrollment Setup
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/other_setup"
                                class="nav-link {{ Request::url() == url('/bookkeeper/other_setup') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Other Setup
                                </p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="/bookkeeper/fixedasset"
                        class="nav-link {{ Request::url() == url('/bookkeeper/fixedasset') ? 'active' : '' }}">
                        <i class="fas fa-building nav-icon"></i>
                        <p>
                            Fixed Assets
                        </p>
                    </a>
                </li>


                <li class="nav-header">Purchasing & Receiving</li>
                <li
                    class="nav-item has-treeview {{ Request::url() == url('/bookkeeper/supplier') || Request::url() == url('/bookkeeper/purchase_order') || Request::url() == url('/bookkeeper/receiving') || Request::url() == url('/bookkeeper/stock_card') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ Request::url() == url('/bookkeeper/supplier') || Request::url() == url('/bookkeeper/purchase_order') || Request::url() == url('/bookkeeper/receiving') || Request::url() == url('/bookkeeper/stock_card') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-shopping-cart"></i>
                        <p>
                            Purchasing
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/bookkeeper/supplier"
                                class="nav-link {{ Request::url() == url('/bookkeeper/supplier') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/purchase_order"
                                class="nav-link {{ Request::url() == url('/bookkeeper/purchase_order') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Purchase Order</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/receiving"
                                class="nav-link {{ Request::url() == url('/bookkeeper/receiving') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Receiving</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/stock_card"
                                class="nav-link {{ Request::url() == url('/bookkeeper/stock_card') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Stock Card</p>
                            </a>
                        </li>
                    </ul>
                </li>


                <li class="nav-header">Others</li>
                <li class="nav-item">
                    <a href="/bookkeeper/expense_monitoring"
                        class="nav-link {{ Request::url() == url('/bookkeeper/expense_monitoring') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar nav-icon"></i>
                        <p>Expenses Monitoring</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/bookkeeper/disbursement"
                        class="nav-link {{ Request::url() == url('/bookkeeper/disbursement') ? 'active' : '' }}">
                        <i class="fas fa-file-invoice-dollar nav-icon"></i>
                        <p>
                            Disbursement
                        </p>
                    </a>
                </li>


                <li class="nav-header nav-item"> Accounting Reports</li>
                <li class="nav-item">
                    <a href="/bookkeeper/general_ledger"
                        class="nav-link {{ Request::url() == url('/bookkeeper/general_ledger') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list nav-icon"></i>
                        <p>
                            General Ledger
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/bookkeeper/subsidiary_ledger"
                        class="nav-link {{ Request::url() == url('/bookkeeper/subsidiary_ledger') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list nav-icon"></i>
                        <p>
                            Subsidiary Ledger
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/bookkeeper/trial_balance"
                        class="nav-link {{ Request::url() == url('bookkeeper/trial_balance') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list nav-icon"></i>
                        <p>
                            Trial Balance
                        </p>
                    </a>
                </li>
                <li class="nav-item has-treeview {{ (Request::url() == url('/bookkeeper/income_statement') || Request::url() == url('/bookkeeper/balance_sheet')) ? 'menu-open' : '' }}">
                    <a href="/" class="nav-link {{ (Request::url() == url('/bookkeeper/income_statement') || Request::url() == url('/bookkeeper/balance_sheet')) ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list nav-icon"></i>
                        <p>
                            Financial Statement <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="/bookkeeper/income_statement" class="nav-link {{ Request::url() == url('/bookkeeper/income_statement') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Income Statement
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/balance_sheet" class="nav-link {{ Request::url() == url('/bookkeeper/balance_sheet') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Balance Sheets
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/cashflow_statement" class="nav-link {{ Request::url() == url('bookkeeper/cashflow_statement') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Cashflow Statement
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/bookkeeper/equity_statement" class="nav-link {{ Request::url() == url('bookkeeper/equity_statement') ? 'active' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>
                                    Equity Statement
                                </p>
                            </a>
                        </li>
                    </ul>
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
                {{-- <li class="nav-item">
                    <a href="/documenttracking"
                        class="nav-link {{ Request::url() == url('/documenttracking') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-image"></i>
                        <p>
                            Document Tracking
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/hr/requirements/employee"
                        class="nav-link {{ Request::fullUrl() === url('/hr/requirements/employee') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-file-alt"></i>
                        <p>
                            My Requirements
                        </p>
                    </a>
                </li> --}}

                <li class="nav-header">My Account</li>
                <li class="nav-item">
                    <a href="/dtr/attendance/index" id="dashboard"
                        class="nav-link {{ Request::url() == url('/dtr/attendance/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            DTR
                        </p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/leaves/apply/index" id="dashboard"
                        class="nav-link {{ Request::url() == url('/leaves/apply/index') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Leave Applications
                        </p>
                    </a>
                </li>
                {{-- <li class="nav-item">
                    <a href="/overtime" class="nav-link {{ Request::fullUrl() === url('/overtime') ? 'active' : '' }}">
                        <i class="nav-icon fa fa-file"></i>
                        <p>
                            Overtime
                        </p>
                    </a>
                </li> --}}


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
