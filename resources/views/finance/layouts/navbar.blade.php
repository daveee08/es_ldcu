<!-- header-->
<style>
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
    .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
        background-color: {!! $schoolinfo->schoolcolor !!} ;
    }

    /* a.brand-link.sidehead  {
        background-color:#ffffff !important ;
    } */


   .main-header {
        background-color: {!! $schoolinfo->schoolcolor !!} !important ;
    }

    .sidehead{
        background-color: {!! $schoolinfo->schoolcolor !!} !important ;
    }

    #sidenav{
        background-color:#ffffff !important
    }

    .status-dot {
        height: 7px;
        width: 7px;
        background-color: #ffffff;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
        animation: pulse 3s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.5);
        }
        100% {
            transform: scale(1);
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
            color: #FFFF !important;
        }

        .sidebar {
            overflow-y: auto;
            /* Enable scrolling */
            max-height: 100vh;
            /* Ensure sidebar has limited height */
            position: relative;
        }
    </style>

<nav class="main-header navbar navbar-expand navbar-dark navbar-info navss">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
        <span class="ml-2 text-white" style="font-size: 12px">
            <div class="d-flex align-items-center">
                <span class="status-dot mb-0"></span>
                <strong class="text-white">Active</strong>
            </div>
            <b>SY: @php
                $sydesc = DB::table('sy')->where('isactive', 1)->first();
            @endphp {{ $sydesc->sydesc }} |
            @php
                $semester = DB::table('semester')->where('isactive', 1)->first();
            @endphp {{ $semester->semester }}</b>
        </span>
    </ul>

      <!-- <li class="nav-item d-none d-sm-inline-block">
        <a href="/finance/index" class="nav-link">Home</a>
      </li> -->
    </ul>
    <ul class="navbar-nav ml-auto">
      <!-- Messages Dropdown Menu -->

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown sideright">

        <a class="nav-link " href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" id="dashboard">
          <!-- <i class="fas fa-power-off logouthover" style="margin-right: 7px; color: #fff"></i> -->
          <span class="logoutshow" id="logoutshow"> Logout</span>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </a>


      </li>

      <!-- <li class="nav-item">
        <a class="nav-link " href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" id="dashboard">

          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
              </form>

          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li> -->
    </ul>


    @php
      // use db;
      $schoolinfo = DB::table('schoolinfo')->first();
    @endphp

  </nav>

  <aside id="sidenav" class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <!-- Brand Logo -->
    <div class="ckheader">
        <a href="#"  class="brand-link sidehead " >
          @if( DB::table('schoolinfo')->first()->picurl !=null)
              <img src="{{asset(DB::table('schoolinfo')->first()->picurl)}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8;"  onerror="this.src='{{asset('assets/images/department_of_Education.png')}}'">
          @else
              <img src="{{asset('assets/images/department_of_Education.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"  >
          @endif
          <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">
              {{DB::table('schoolinfo')->first()->abbreviation}}
            </span>
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:white"><b>FINANCE PORTAL</b></span>
        </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host" ><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 848px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll; right: 0px; bottom: 0px;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
      <!-- Sidebar user (optional) -->
		@php
			$randomnum = rand(1, 4);
			$avatar = 'assets/images/avatars/unknown.png'.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
			$picurl = DB::table('teacher')->where('userid',auth()->user()->id)->first()->picurl;
			$picurl = str_replace('jpg','png',$picurl).'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
		@endphp
       <div class="row">
            <div class="col-md-12">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="{{asset($picurl)}}"" onerror="this.onerror=null; this.src='{{asset($avatar)}}'" alt="User Image" width="100%" style="width:130px; border-radius: 12% !important;">
            </div>
            </div>
        </div>
        <div class="row  user-panel">
            <div class="col-md-12 info text-center">
            <h6 class=" mb-0 ">{{auth()->user()->name}}</h6>
            <h6 class="text-black text-center">{{auth()->user()->email}}</h6>
            </div>
        </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2" style="back">
        <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
              <a  class="{{Request::url() == url('/home') ? 'active':''}} nav-link" href="/home">
                  <i class="nav-icon fa fa-home"></i>
                  <p>
                  Home
                  </p>
              </a>
          </li>
		<li class="nav-item">
		  <a href="/user/profile" class="nav-link {{Request::url() == url('/user/profile') ? 'active' : ''}}">
			  <i class="nav-icon fa fa-user"></i>
			  <p>
				  Profile
			  </p>
		  </a>
		</li>
                            @if(isset(DB::table('schoolinfo')->first()->withschoolfolder))
                                @if(DB::table('schoolinfo')->first()->withschoolfolder == 1)
                                <li class="nav-item">
                                    <a class="{{Request::url() == url('/schoolfolderv2/index') ? 'active':''}} nav-link" href="/schoolfolderv2/index">
                                        <i class="nav-icon fa fa-folder"></i>
                                        <p>
                                            @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'bct')
                                            BCT Commons
                                            @else
                                            File Directory
                                            @endif
                                        </p>
                                    </a>
                                </li>
                                @endif
                            @endif

                            <li class="nav-item">
                              <a href="/hr/settings/notification/index" class="nav-link {{Request::url() == url('/hr/settings/notification/index') ? 'active' : ''}}">
                                  <i class="nav-icon  fas fa-exclamation"></i>
                                  <p>
                                      Notification & Request
                                      {{-- <span class="ml-2 badge badge-primary">2</span> --}}
                                  </p>
                              </a>
                          </li>

          @php
              $countapproval = DB::table('hr_leaveemployeesappr')
                  ->where('appuserid', auth()->user()->id)
                  ->where('deleted','0')
                  ->count();
              // $countapproval = DB::table('hr_leavesappr')
              //     ->where('employeeid', $hr_profile->id)
              //     ->where('deleted','0')
              //     ->count();
          @endphp
          @if($countapproval > 0 )
              <li class="nav-item">
                  <a href="/hr/leaves/index" class="nav-link {{Request::url() == url('/hr/leaves/index') ? 'active' : ''}}">
                      <i class="fa fa-file-contract nav-icon"></i>
                      <p>
                          Filed Leaves
                      </p>
                  </a>
              </li>
          @endif

          @if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lchsi')
            <li class="nav-item has-treeview {{ Request::fullUrl() == url('/administrator/schoolfolders') || Request::fullUrl() == url('/administrator/schoolfolders')? 'menu-open':''}}">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>
                        INTRANET
                    <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview udernavs">
                    <li class="nav-item">
                        <a class="{{Request::url() == url('/administrator/schoolfolders') ? 'active':''}} nav-link" href="/administrator/schoolfolders">
                            <i class="nav-icon fa fa-calendar"></i>
                            <p>
                                Doc Con
                            </p>
                        </a>
                    </li>
                </ul>

            </li>
        @endif

        <li class="nav-header text-warning">DOCUMENT TRACKING</li>
        <li class="nav-item">
            <a href="/documenttracking"
                class="nav-link {{ Request::url() == url('/documenttracking') ? 'active' : '' }}">
                <i class="nav-icon fa fa-file"></i>
                <p>
                    Document Tracking
                </p>
            </a>
        </li>
          <li class="nav-header">
            FINANCE
          </li>
          <li class="nav-item">
            <a href="{!! route('studledger')!!}" class="nav-link {{(Request::Is('finance/studledger')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Student Ledger
              </p>
            </a>
          </li>
          @if($schoolinfo->snr == 'ldcu' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ldcu')
            @php
              $scholarship = DB::table('scholarship_applicants')->where('deleted', 0)->where('scholar_status', 'SUBMITTED')->count();
            @endphp
            <li class="nav-item">
              <a href="/finance/scholarship" class="nav-link {{(Request::Is('finance/scholarship')) ? 'active' : ''}}">
                <i class="nav-icon fas fa-file-invoice"></i>
                <p>
                  Scholarship Request <span class="badge badge-warning scholarshipcount">{{ $scholarship }}</span>
                </p>
              </a>
            </li>
          @endif



          <li class="nav-item has-treeview
			{{(Request::Is('finance/studupdateledger')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/discounts')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/tuitionentry')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/oldaccounts')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/adjustment')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/allowdp')) ? 'menu-open' : ''}}
            ">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-adjust"></i>

              <p>
                Accounts
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview udernavs">
			  <li class="nav-item">
				<a href="{!! route('studupdateledger')!!}" class="nav-link {{(Request::Is('finance/studupdateledger')) ? 'active' : ''}}">
					<i class="far fa-circle nav-icon"></i>
					<p>Students</p>
				</a>
			  </li>
              <li class="nav-item">
                <a href="{!! route('discounts')!!}" class="nav-link {{(Request::Is('finance/discounts')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Discounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{!! route('adjustment')!!}" class="nav-link {{(Request::Is('finance/adjustment')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Adjustments</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="{!! route('oldaccounts')!!}" class="nav-link {{(Request::Is('finance/oldaccounts')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Old Accounts</p>
                </a>
              </li>
              {{--<li class="nav-item">
                <a href="{!! route('balforward')!!}" class="nav-link {{(Request::Is('finance/balforward')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Balance Forwarding</p>
                </a>
              </li>--}}
              {{-- <li class="nav-item">
                <a href="{!! route('tuitionentry')!!}" class="nav-link {{(Request::Is('finance/tuitionentry')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tuition Entry</p>
                </a>
              </li> --}}
              <li class="nav-item" hidden>
                <a href="{!! route('allowdp')!!}" class="nav-link {{(Request::Is('finance/allowdp')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Allow No Downpayment</p>
                </a>
              </li>
			   <li class="nav-item">
                            <a class="{{Request::fullUrl() == url('/finance/allowdp') || Request::fullUrl() == url('/student/preregistration') ? 'active':''}} nav-link" href="/student/preregistration">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Preregistration
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                          <a class="{{Request::fullUrl() == url('/tesda/enrollment') ? 'active':''}} nav-link" href="/tesda/enrollment">
                              <i class="nav-icon far fa-circle"></i>
                              <p>
                                  TESDA Prereg
                              </p>
                          </a>
                      </li>
            </ul>
          </li>

          <li class="nav-item has-treeview
            {{(Request::Is('finance/itemclassification/index')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/payitems')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/modeofpayment')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/mopnew')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/mopedit/*')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/fees')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/feesnew')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/feesedit/*')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/labfees')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/espsetup')) ? 'menu-open' : ''}}
            {{(Request::Is('finance/books')) ? 'menu-open' : ''}}
            ">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-wallet"></i>
              <p>
                Payment Setup
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview udernavs">
              <li class="nav-item">
                <a href="{!! route('itemclassification_v2')!!}" class="nav-link {{(Request::Is('finance/itemclassification/index')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Item Classification</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{!! route('payitems')!!}" class="nav-link {{(Request::Is('finance/payitems')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment Items</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="{!! route('books')!!}" class="nav-link {{(Request::Is('finance/books')) ? 'active' : ''}}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Book Items</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{!! route('modeofpayment')!!}" class="nav-link
                  {{(Request::Is('finance/modeofpayment')) ? 'active' : ''}}
                  {{(Request::Is('finance/mopnew')) ? 'active' : ''}}
                  {{(Request::Is('finance/mopedit/*')) ? 'active' : ''}}
                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mode of Payment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{!! route('fees')!!}" class="nav-link
                  {{(Request::Is('finance/fees')) ? 'active' : ''}}
                  {{(Request::Is('finance/feesnew')) ? 'active' : ''}}
                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>School Fees</p>
                </a>
              </li>

			  <li class="nav-item">
                <a href="{!! route('labfees')!!}" class="nav-link
                  {{(Request::Is('finance/labfees')) ? 'active' : ''}}
                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laboratory Fees</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="{!! route('assessmentunit')!!}" class="nav-link
                  {{(Request::Is('finance/assessmentunit')) ? 'active' : ''}}
                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assessment Unit</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="{!! route('espsetup')!!}" class="nav-link
                  {{(Request::Is('finance/espsetup')) ? 'active' : ''}}
                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Special/Summer Class Setup</p>
                </a>
              </li>
              {{--<li class="nav-item">
                @if(db::table('schoolinfo')->first()->cashierversion == 1)
                  <a href="{!! route('dpsetup')!!}" class="nav-link
                    {{(Request::Is('finance/dpsetup')) ? 'active' : ''}}
                  ">

                    <i class="far fa-circle nav-icon"></i>
                    <p>Downpayment Setup</p>
                  </a>
                @else
                  <a href="{!! route('dpv2')!!}" class="nav-link
                    {{(Request::Is('finance/dpv2')) ? 'active' : ''}}
                  ">

                    <i class="far fa-circle nav-icon"></i>
                    <p>Downpayment Setup</p>
                  </a>
                @endif
              </li>--}}
            </ul>
          </li>
          <li class="nav-item">
            <a href="{!! route('onlinepay')!!}" class="nav-link {{(Request::Is('finance/onlinepay')) ? 'active' : ''}}">
              <i class="fas fa-gem nav-icon"></i>
              <p>
                Online Payment <span id="olpayCount" class="badge badge-warning right viewolpaycount">{{App\FinanceModel::countOnlinePayment()}}</span>
              </p>
            </a>
          </li>
          {{-- <li class="nav-item has-treeview
                  {{(Request::Is('finance/onlinepay')) ? 'menu-open' : ''}}
                  {{(Request::Is('finance/olreceipt')) ? 'menu-open' : ''}}
                  ">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-globe"></i>
              <p>
                Online Transactions
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview udernavs">
              <li class="nav-item">
                <a href="{!! route('onlinepay')!!}" class="nav-link {{(Request::Is('finance/onlinepay')) ? 'active' : ''}}">
                  <i class="fas fa-gem nav-icon"></i>
                  <p>
                    Online Payment <span id="olpayCount" class="badge badge-warning right viewolpaycount">{{App\FinanceModel::countOnlinePayment()}}</span>
                  </p>
                </a>
              </li>
              @if(auth()->user()->type == 15)
                <li class="nav-item">
                  <a href="{!! route('olreceipt')!!}" class="nav-link {{(Request::Is('finance/olreceipt')) ? 'active' : ''}}">
                    <i class="fas fa-download nav-icon"></i>
                    <p>
                      Download Online Receipts
                    </p>
                  </a>
                </li>
              @endif
            </ul>
          </li> --}}

		<li class="nav-item">
            <a href="{!! route('exampermit')!!}" class="nav-link {{(Request::Is('finance/exampermit')) ? 'active' : ''}}">
              <i class="fas fa-paperclip nav-icon"></i>
              <p>
                Examination Permit
              </p>
            </a>
		</li>

          <li class="nav-item">
            <a href="{!! route('expenses')!!}" class="nav-link {{(Request::Is('finance/expenses')) ? 'active' : ''}}">
              <i class="fas fa-share-square nav-icon"></i>
              <p>
                Expenses
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="{!! route('disbursement')!!}" class="nav-link {{(Request::Is('finance/disbursement')) ? 'active' : ''}}">
              <i class="fas fa-share-square nav-icon"></i>
              <p>
                Disbursement
              </p>
            </a>
          </li>

          @php
            $schoolinfo = DB::table('schoolinfo')
              ->first();

            $utype = DB::table('users')
              ->select('refid')
              ->join('usertype', 'users.type', '=', 'usertype.id')
              ->where('users.id', auth()->user()->id)
              ->first();
          @endphp

          {{-- @if($schoolinfo->accountingmodule == 1 && db::table('usertype')->where('id', Session::get('currentPortal'))->first()->refid == 19) --}}
          @if($schoolinfo->accountingmodule == 1)
              <li class="nav-item has-treeview {{ (Request::is('finance/accsetup', 'finance/accounting/journalentries', 'finance/accounting/reports')) ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ Request::is('finance/accsetup', 'finance/accounting/journalentries', 'finance/accounting/reports') ? 'active' : '' }}">
                      <i class="fas fa-calculator nav-icon"></i>
                      <p>
                          Accounting
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview udernavs">
                      <li class="nav-item">
                          <a href="{!! route('accsetup') !!}" class="nav-link {{ Request::is('finance/accsetup') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Setup</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{!! route('journalentries') !!}" class="nav-link {{ Request::is('finance/accounting/journalentries') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>General Journal</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{!! route('reports') !!}" class="nav-link {{ Request::is('finance/accounting/reports') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Reports</p>
                          </a>
                      </li>
                  </ul>
              </li>
              
              <li class="nav-item has-treeview {{ Request::is('finance/purchasing/*') ? 'menu-open' : '' }}">
                  <a href="#" class="nav-link {{ Request::is('finance/purchasing/*') ? 'active' : '' }}">
                      <i class="fas fa-calculator nav-icon"></i>
                      <p>
                          Purchasing
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview udernavs">
                      <li class="nav-item">
                          <a href="{!! route('purchaseorder') !!}" class="nav-link {{ Request::is('finance/purchasing/purchaseorder') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Purchase Order</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="{!! route('receiving') !!}" class="nav-link {{ Request::is('finance/purchasing/receiving') ? 'active' : '' }}">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Receiving</p>
                          </a>
                      </li>
                  </ul>
              </li>
          @endif

          @if(auth()->user()->type == '15')
            {{-- @php
              $permissions = DB::table('chrngpermission')
                ->get();

            @endphp --}}

            {{-- <li class="nav-item">
              <a href="{!! route('salaryrateelevation','view')!!}" class="nav-link {{(Request::Is('finance/salaryrateelevation/view')) ? 'active' : ''}}">
                <i class="fas fa-edit nav-icon"></i>
                <p>
                  Salary Rate Elevation <span class="badge badge-warning right countsalaryrateelevation">{{App\FinanceModel::countPendingRateElevationRequests()}}</span>
                </p>
              </a>
            </li> --}}
          @endif
        </ul>

        <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">
            Reports
          </li>
          {{--<li class="nav-item">
            <a href="{!! route('studentassessment')!!}" class="nav-link {{(Request::Is('/studentassessment')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-list"></i>
              <p>
                Student Assessment
              </p>
            </a>
          </li>--}}
          <li class="nav-item">
            <a href="{!! route('cashtrans')!!}" class="nav-link {{(Request::Is('finance/transactions/cashtrans')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Cashier Transactions
              </p>
            </a>
          </li>

          @php
            $snr = DB::table('schoolinfo')
              ->where('snr', 'hcb')
              ->count();
          @endphp

          @if($snr > 0)
            <li class="nav-item">
              <a href="{!! route('dailycashcollection')!!}" class="nav-link {{(Request::Is('finance/reports/dailycashcollection')) ? 'active' : ''}}">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                  Daily Cash Collection
                </p>
              </a>
            </li>
          @endif

		  <li class="nav-item">
            <a href="/finance/reports/consolidated/index" class="nav-link {{(Request::Is('finance/reports/consolidated/index')) ? 'active' : ''}}">
              {{-- <i class="nav-icon fas fa-user-friends"></i> --}}
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Consolidated Report
              </p>
            </a>
          </li>

         <li class="nav-item">
            <a href="/finance/reports/consolidated/schoolfees" class="nav-link {{(Request::Is('finance/reports/consolidated/schoolfees')) ? 'active' : ''}}">
              {{-- <i class="nav-icon fas fa-user-friends"></i> --}}
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Consolidated School Fees
              </p>
            </a>
          </li>

		  <li class="nav-item">
			<a href="/finance/feescollected" class="nav-link {{(Request::Is('finance/feescollected')) ? 'active' : ''}}">
			  {{-- <i class="nav-icon fas fa-user-friends"></i> --}}
			  <i class="nav-icon fas fa-angle-double-left"></i>
			  <p>
          Item Collection Summary
			  </p>
			</a>
		  </li>

		  <li class="nav-item">
			<a href="/finance/assessedfees" class="nav-link {{(Request::Is('finance/assessedfees')) ? 'active' : ''}}">
			  {{-- <i class="nav-icon fas fa-user-friends"></i> --}}
			  <i class="nav-icon fas fa-angle-double-right"></i>
			  <p>
          Item Receivables
			  </p>
			</a>
		  </li>

		  <li class="nav-item">
            <a href="/finance/reports/dcpr" class="nav-link {{(Request::Is('finance/reports/dcpr')) ? 'active' : ''}}">
              {{-- <i class="nav-icon fas fa-user-friends"></i> --}}
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                DCPR
              </p>
            </a>
          </li>

		  <li class="nav-item">
            <a href="/finance/reports/monthlycollection" class="nav-link {{(Request::Is('finance/reports/monthlycollection')) ? 'active' : ''}}">
              {{-- <i class="nav-icon fas fa-user-friends"></i> --}}
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                Monthly Collection
              </p>
            </a>
          </li>

		  <li class="nav-item">
            <a href="/finance/reports/yearend" class="nav-link {{(Request::Is('finance/reports/yearend')) ? 'active' : ''}}">
              {{-- <i class="nav-icon fas fa-user-friends"></i> --}}
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                Year End Summary
              </p>
            </a>
          </li>

          {{-- <li class="nav-item">
            <a href="{!! route('cashreceiptsummary')!!}" class="nav-link {{(Request::Is('cashreceiptsummary/index')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Cash Receipt Summary
              </p>
            </a>
          </li> --}}
          <li class="nav-item">
            <a href="{!! route('acctreceivable')!!}" class="nav-link {{(Request::Is('acctreceivable')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Account Receivable
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{!! route('statementofacct')!!}" class="nav-link {{(Request::Is('statementofacct')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Statement of Account
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="{!! route('oareceivables')!!}" class="nav-link {{(Request::Is('finance/reports/oareceivables')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Old Accounts Receivables
              </p>
            </a>
          </li>
          {{-- <li class="nav-item">
            <a href="/finance/reportonlinepayments/view" class="nav-link {{(Request::Is('finance/reportonlinepayments/view')) ? 'active' : ''}}">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Online Payments
              </p>
            </a>
          </li> --}}
		<li class="nav-item">
			<a href="/finance/stockcard/view" class="nav-link {{(Request::Is('finance/stockcard/view')) ? 'active' : ''}}">
				<i class="nav-icon fas fa-boxes"></i>
				<p>
				Stock Card
				</p>
			</a>
		</li>
        </ul>
		 <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
							<li class="nav-header text-warning">Utility</li>

							<li class="nav-item">
								<a class="{{Request::url() == url('/data/localtocloud') ? 'active':''}} nav-link" href="/data/localtocloud">
									<i class="nav-icon far fa-paper-plane"></i>
									<p>
										Data From Online
									</p>
								</a>
							</li>

		</ul>
        <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">
            Portals
          </li>
          @php
            $priveledge = DB::table('faspriv')
                ->join('usertype','faspriv.usertype','=','usertype.id')
                ->select('faspriv.*','usertype.utype')
                ->where('userid', auth()->user()->id)
                ->where('faspriv.deleted','0')
				->where('type_active',1)
                ->where('faspriv.privelege','!=','0')
                ->get();

            $usertype = DB::table('usertype')->where('deleted',0)->where('id',auth()->user()->type)->first();
          @endphp

           @foreach ($priveledge as $item)
            @if($item->usertype != Session::get('currentPortal'))
                <li class="nav-item">
                    <a class="nav-link portal" href="/gotoPortal/{{$item->usertype}}" id="{{$item->usertype}}">
                        <i class=" nav-icon fas fa-cloud"></i>
                        <p>
                            {{$item->utype}}
                        </p>
                    </a>
                </li>
            @endif
        @endforeach

        @if($usertype->id != Session::get('currentPortal'))
            <li class="nav-item">
                <a class="nav-link portal" href="/gotoPortal/{{$usertype->id}}">
                    <i class=" nav-icon fas fa-cloud"></i>
                    <p>
                        {{$usertype->utype}}
                    </p>
                </a>
            </li>
        @endif
              {{-- @if(isset(DB::table('schoolinfo')->first()->withleaveapp))
                  @if(DB::table('schoolinfo')->first()->withleaveapp == 1)
                  <li class="nav-item">
                      <a href="/leaves/apply/index"  id="dashboard" class="nav-link {{Request::url() == url('/leaves/apply/index') ? 'active' : ''}}">
                          <i class="nav-icon fa fa-file"></i>
                          <p>
                              Apply Leave
                          </p>
                      </a>
                  </li>
                  @endif
              @else
                  <li class="nav-item">
                      <a href="/leaves/apply/index"  id="dashboard" class="nav-link {{Request::url() == url('/leaves/apply/index') ? 'active' : ''}}">
                          <i class="nav-icon fa fa-file"></i>
                          <p>
                              Apply Leave
                          </p>
                      </a>
                  </li>
              @endif --}}
              {{-- @if(isset(DB::table('schoolinfo')->first()->withovertimeapp))
                  @if(DB::table('schoolinfo')->first()->withovertimeapp == 1)
                  <li class="nav-item">
                      <a href="/overtime/apply/index"  id="dashboard" class="nav-link {{Request::url() == url('/overtime/apply/index') ? 'active' : ''}}">
                          <i class="nav-icon fa fa-file"></i>
                          <p>
                              Apply Overtime
                          </p>
                      </a>
                  </li>
                  @endif
              @endif --}}
              {{-- @if(isset(DB::table('schoolinfo')->first()->withundertimeapp))
                  @if(DB::table('schoolinfo')->first()->withundertimeapp == 1)
                  <li class="nav-item">
                      <a href="/overtime/apply/index"  id="dashboard" class="nav-link {{Request::url() == url('/overtime/apply/index') ? 'active' : ''}}">
                          <i class="nav-icon fa fa-file"></i>
                          <p>
                              Apply Overtime
                          </p>
                      </a>
                  </li>
                  @endif
              @endif --}}
              <li class="nav-header text-warning">My Applications</li>
              {{-- <li class="nav-item">
                  <a href="/hr/leaves/index?action=myleave" class="nav-link {{ Request::fullUrl() === url('/hr/leaves/index?action=myleave') ? 'active' : '' }}">
                      <i class="nav-icon fa fa-calendar-alt"></i>
                      <p>
                          Leave Applications
                      </p>
                  </a>
              </li> --}}
              <li class="nav-item">
                  <a href="/leaves/apply/index"  id="dashboard" class="nav-link {{Request::url() == url('/leaves/apply/index') ? 'active' : ''}}">
                      <i class="nav-icon fa fa-file"></i>
                      <p>
                          Leave Applications
                      </p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="/dtr/attendance/index" class="nav-link {{Request::url() == url('/dtr/attendance/index') ? 'active' : ''}}">
                      <i class="nav-icon fa fa-file"></i>
                      <p>
                          Daily Time Record
                      </p>
                  </a>
              </li>
        </ul>
      </nav>
      <br>
      <br>
      <br>
      <!-- /.sidebar-menu -->
    </div></div></div><div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar os-scrollbar-vertical"><div class="os-scrollbar-track"><div class="os-scrollbar-handle" style="height: 61.2112%; transform: translate(0px, 0px);"></div></div></div><div class="os-scrollbar-corner"></div></div>
    <!-- /.sidebar -->
  </aside>
