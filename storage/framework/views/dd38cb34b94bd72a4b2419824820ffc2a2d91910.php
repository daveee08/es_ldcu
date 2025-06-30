<!-- header-->
<style>
    .sidebar-dark-primary .nav-sidebar>.nav-item>.nav-link.active,
    .sidebar-light-primary .nav-sidebar>.nav-item>.nav-link.active {
        background-color: <?php echo $schoolinfo->schoolcolor; ?> ;
    }

    /* a.brand-link.sidehead  {
        background-color:#ffffff !important ;
    } */


   .main-header {
        background-color: <?php echo $schoolinfo->schoolcolor; ?> !important ;
    }

    .sidehead{
        background-color: <?php echo $schoolinfo->schoolcolor; ?> !important ;
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

    @keyframes  pulse {
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
            <b>SY: <?php
                $sydesc = DB::table('sy')->where('isactive', 1)->first();
            ?> <?php echo e($sydesc->sydesc); ?> |
            <?php
                $semester = DB::table('semester')->where('isactive', 1)->first();
            ?> <?php echo e($semester->semester); ?></b>
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

        <a class="nav-link " href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" id="dashboard">
          <!-- <i class="fas fa-power-off logouthover" style="margin-right: 7px; color: #fff"></i> -->
          <span class="logoutshow" id="logoutshow"> Logout</span>
          <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
            <?php echo csrf_field(); ?>
          </form>
        </a>


      </li>

      <!-- <li class="nav-item">
        <a class="nav-link " href="<?php echo e(route('logout')); ?>" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" id="dashboard">

          <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" style="display: none;">
                <?php echo csrf_field(); ?>
              </form>

          <i class="fas fa-sign-out-alt"></i>
        </a>
      </li> -->
    </ul>


    <?php
      // use db;
      $schoolinfo = DB::table('schoolinfo')->first();
    ?>

  </nav>

  <aside id="sidenav" class="main-sidebar sidebar-dark-primary elevation-4 asidebar">
    <!-- Brand Logo -->
    <div class="ckheader">
        <a href="#"  class="brand-link sidehead " >
          <?php if( DB::table('schoolinfo')->first()->picurl !=null): ?>
              <img src="<?php echo e(asset(DB::table('schoolinfo')->first()->picurl)); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8;"  onerror="this.src='<?php echo e(asset('assets/images/department_of_Education.png')); ?>'">
          <?php else: ?>
              <img src="<?php echo e(asset('assets/images/department_of_Education.png')); ?>" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"  >
          <?php endif; ?>
          <span class="brand-text font-weight-light" style="position: absolute;top: 6%;">
              <?php echo e(DB::table('schoolinfo')->first()->abbreviation); ?>

            </span>
          <span class="brand-text font-weight-light" style="position: absolute;top: 50%;font-size: 16px!important;color:white"><b>FINANCE PORTAL</b></span>
        </a>
    </div>

    <!-- Sidebar -->
    <div class="sidebar os-host os-theme-light os-host-overflow os-host-overflow-y os-host-resize-disabled os-host-scrollbar-horizontal-hidden os-host-transition"><div class="os-resize-observer-host" ><div class="os-resize-observer observed" style="left: 0px; right: auto;"></div></div><div class="os-size-auto-observer" style="height: calc(100% + 1px); float: left;"><div class="os-resize-observer observed"></div></div><div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 848px;"></div><div class="os-padding"><div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll; right: 0px; bottom: 0px;"><div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">
      <!-- Sidebar user (optional) -->
		<?php
			$randomnum = rand(1, 4);
			$avatar = 'assets/images/avatars/unknown.png'.'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
			$picurl = DB::table('teacher')->where('userid',auth()->user()->id)->first()->picurl;
			$picurl = str_replace('jpg','png',$picurl).'?random="'.\Carbon\Carbon::now('Asia/Manila')->isoFormat('MMDDYYHHmmss').'"';
		?>
       <div class="row">
            <div class="col-md-12">
            <div class="text-center">
                <img class="profile-user-img img-fluid img-circle" src="<?php echo e(asset($picurl)); ?>"" onerror="this.onerror=null; this.src='<?php echo e(asset($avatar)); ?>'" alt="User Image" width="100%" style="width:130px; border-radius: 12% !important;">
            </div>
            </div>
        </div>
        <div class="row  user-panel">
            <div class="col-md-12 info text-center">
            <h6 class=" mb-0 "><?php echo e(auth()->user()->name); ?></h6>
            <h6 class="text-black text-center"><?php echo e(auth()->user()->email); ?></h6>
            </div>
        </div>


      <!-- Sidebar Menu -->
      <nav class="mt-2" style="back">
        <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
              <a  class="<?php echo e(Request::url() == url('/home') ? 'active':''); ?> nav-link" href="/home">
                  <i class="nav-icon fa fa-home"></i>
                  <p>
                  Home
                  </p>
              </a>
          </li>
		<li class="nav-item">
		  <a href="/user/profile" class="nav-link <?php echo e(Request::url() == url('/user/profile') ? 'active' : ''); ?>">
			  <i class="nav-icon fa fa-user"></i>
			  <p>
				  Profile
			  </p>
		  </a>
		</li>
                            <?php if(isset(DB::table('schoolinfo')->first()->withschoolfolder)): ?>
                                <?php if(DB::table('schoolinfo')->first()->withschoolfolder == 1): ?>
                                <li class="nav-item">
                                    <a class="<?php echo e(Request::url() == url('/schoolfolderv2/index') ? 'active':''); ?> nav-link" href="/schoolfolderv2/index">
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
                              <a href="/hr/settings/notification/index" class="nav-link <?php echo e(Request::url() == url('/hr/settings/notification/index') ? 'active' : ''); ?>">
                                  <i class="nav-icon  fas fa-exclamation"></i>
                                  <p>
                                      Notification & Request
                                      
                                  </p>
                              </a>
                          </li>

          <?php
              $countapproval = DB::table('hr_leaveemployeesappr')
                  ->where('appuserid', auth()->user()->id)
                  ->where('deleted','0')
                  ->count();
              // $countapproval = DB::table('hr_leavesappr')
              //     ->where('employeeid', $hr_profile->id)
              //     ->where('deleted','0')
              //     ->count();
          ?>
          <?php if($countapproval > 0 ): ?>
              <li class="nav-item">
                  <a href="/hr/leaves/index" class="nav-link <?php echo e(Request::url() == url('/hr/leaves/index') ? 'active' : ''); ?>">
                      <i class="fa fa-file-contract nav-icon"></i>
                      <p>
                          Filed Leaves
                      </p>
                  </a>
              </li>
          <?php endif; ?>

          <?php if(strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'sait' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'lchsi'): ?>
            <li class="nav-item has-treeview <?php echo e(Request::fullUrl() == url('/administrator/schoolfolders') || Request::fullUrl() == url('/administrator/schoolfolders')? 'menu-open':''); ?>">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-layer-group"></i>
                    <p>
                        INTRANET
                    <i class="fas fa-angle-left right" style="right: 5%; top: 28%;"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview udernavs">
                    <li class="nav-item">
                        <a class="<?php echo e(Request::url() == url('/administrator/schoolfolders') ? 'active':''); ?> nav-link" href="/administrator/schoolfolders">
                            <i class="nav-icon fa fa-calendar"></i>
                            <p>
                                Doc Con
                            </p>
                        </a>
                    </li>
                </ul>

            </li>
        <?php endif; ?>

        <li class="nav-header text-warning">DOCUMENT TRACKING</li>
        <li class="nav-item">
            <a href="/documenttracking"
                class="nav-link <?php echo e(Request::url() == url('/documenttracking') ? 'active' : ''); ?>">
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
            <a href="<?php echo route('studledger'); ?>" class="nav-link <?php echo e((Request::Is('finance/studledger')) ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-file-invoice"></i>
              <p>
                Student Ledger
              </p>
            </a>
          </li>
          <?php if($schoolinfo->snr == 'ldcu' || strtolower(DB::table('schoolinfo')->first()->abbreviation) == 'ldcu'): ?>
            <?php
              $scholarship = DB::table('scholarship_applicants')->where('deleted', 0)->where('scholar_status', 'SUBMITTED')->count();
            ?>
            <li class="nav-item">
              <a href="/finance/scholarship" class="nav-link <?php echo e((Request::Is('finance/scholarship')) ? 'active' : ''); ?>">
                <i class="nav-icon fas fa-file-invoice"></i>
                <p>
                  Scholarship Request <span class="badge badge-warning scholarshipcount"><?php echo e($scholarship); ?></span>
                </p>
              </a>
            </li>
          <?php endif; ?>



          <li class="nav-item has-treeview
			<?php echo e((Request::Is('finance/studupdateledger')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/discounts')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/tuitionentry')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/oldaccounts')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/adjustment')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/allowdp')) ? 'menu-open' : ''); ?>

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
				<a href="<?php echo route('studupdateledger'); ?>" class="nav-link <?php echo e((Request::Is('finance/studupdateledger')) ? 'active' : ''); ?>">
					<i class="far fa-circle nav-icon"></i>
					<p>Students</p>
				</a>
			  </li>
              <li class="nav-item">
                <a href="<?php echo route('discounts'); ?>" class="nav-link <?php echo e((Request::Is('finance/discounts')) ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Discounts</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo route('adjustment'); ?>" class="nav-link <?php echo e((Request::Is('finance/adjustment')) ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Adjustments</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="<?php echo route('oldaccounts'); ?>" class="nav-link <?php echo e((Request::Is('finance/oldaccounts')) ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Old Accounts</p>
                </a>
              </li>
              
              
              <li class="nav-item" hidden>
                <a href="<?php echo route('allowdp'); ?>" class="nav-link <?php echo e((Request::Is('finance/allowdp')) ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Allow No Downpayment</p>
                </a>
              </li>
			   <li class="nav-item">
                            <a class="<?php echo e(Request::fullUrl() == url('/finance/allowdp') || Request::fullUrl() == url('/student/preregistration') ? 'active':''); ?> nav-link" href="/student/preregistration">
                                <i class="nav-icon far fa-circle"></i>
                                <p>
                                    Student Preregistration
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                          <a class="<?php echo e(Request::fullUrl() == url('/tesda/enrollment') ? 'active':''); ?> nav-link" href="/tesda/enrollment">
                              <i class="nav-icon far fa-circle"></i>
                              <p>
                                  TESDA Prereg
                              </p>
                          </a>
                      </li>
            </ul>
          </li>

          <li class="nav-item has-treeview
            <?php echo e((Request::Is('finance/itemclassification/index')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/payitems')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/modeofpayment')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/mopnew')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/mopedit/*')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/fees')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/feesnew')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/feesedit/*')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/labfees')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/espsetup')) ? 'menu-open' : ''); ?>

            <?php echo e((Request::Is('finance/books')) ? 'menu-open' : ''); ?>

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
                <a href="<?php echo route('itemclassification_v2'); ?>" class="nav-link <?php echo e((Request::Is('finance/itemclassification/index')) ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Item Classification</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo route('payitems'); ?>" class="nav-link <?php echo e((Request::Is('finance/payitems')) ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Payment Items</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="<?php echo route('books'); ?>" class="nav-link <?php echo e((Request::Is('finance/books')) ? 'active' : ''); ?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Book Items</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo route('modeofpayment'); ?>" class="nav-link
                  <?php echo e((Request::Is('finance/modeofpayment')) ? 'active' : ''); ?>

                  <?php echo e((Request::Is('finance/mopnew')) ? 'active' : ''); ?>

                  <?php echo e((Request::Is('finance/mopedit/*')) ? 'active' : ''); ?>

                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Mode of Payment</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo route('fees'); ?>" class="nav-link
                  <?php echo e((Request::Is('finance/fees')) ? 'active' : ''); ?>

                  <?php echo e((Request::Is('finance/feesnew')) ? 'active' : ''); ?>

                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>School Fees</p>
                </a>
              </li>

			  <li class="nav-item">
                <a href="<?php echo route('labfees'); ?>" class="nav-link
                  <?php echo e((Request::Is('finance/labfees')) ? 'active' : ''); ?>

                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Laboratory Fees</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="<?php echo route('assessmentunit'); ?>" class="nav-link
                  <?php echo e((Request::Is('finance/assessmentunit')) ? 'active' : ''); ?>

                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Assessment Unit</p>
                </a>
              </li>
			  <li class="nav-item">
                <a href="<?php echo route('espsetup'); ?>" class="nav-link
                  <?php echo e((Request::Is('finance/espsetup')) ? 'active' : ''); ?>

                ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Special/Summer Class Setup</p>
                </a>
              </li>
              
            </ul>
          </li>
          <li class="nav-item">
            <a href="<?php echo route('onlinepay'); ?>" class="nav-link <?php echo e((Request::Is('finance/onlinepay')) ? 'active' : ''); ?>">
              <i class="fas fa-gem nav-icon"></i>
              <p>
                Online Payment <span id="olpayCount" class="badge badge-warning right viewolpaycount"><?php echo e(App\FinanceModel::countOnlinePayment()); ?></span>
              </p>
            </a>
          </li>
          

		<li class="nav-item">
            <a href="<?php echo route('exampermit'); ?>" class="nav-link <?php echo e((Request::Is('finance/exampermit')) ? 'active' : ''); ?>">
              <i class="fas fa-paperclip nav-icon"></i>
              <p>
                Examination Permit
              </p>
            </a>
		</li>

          <li class="nav-item">
            <a href="<?php echo route('expenses'); ?>" class="nav-link <?php echo e((Request::Is('finance/expenses')) ? 'active' : ''); ?>">
              <i class="fas fa-share-square nav-icon"></i>
              <p>
                Expenses
              </p>
            </a>
          </li>

          <li class="nav-item">
            <a href="<?php echo route('disbursement'); ?>" class="nav-link <?php echo e((Request::Is('finance/disbursement')) ? 'active' : ''); ?>">
              <i class="fas fa-share-square nav-icon"></i>
              <p>
                Disbursement
              </p>
            </a>
          </li>

          <?php
            $schoolinfo = DB::table('schoolinfo')
              ->first();

            $utype = DB::table('users')
              ->select('refid')
              ->join('usertype', 'users.type', '=', 'usertype.id')
              ->where('users.id', auth()->user()->id)
              ->first();
          ?>

          
          <?php if($schoolinfo->accountingmodule == 1): ?>
              <li class="nav-item has-treeview <?php echo e((Request::is('finance/accsetup', 'finance/accounting/journalentries', 'finance/accounting/reports')) ? 'menu-open' : ''); ?>">
                  <a href="#" class="nav-link <?php echo e(Request::is('finance/accsetup', 'finance/accounting/journalentries', 'finance/accounting/reports') ? 'active' : ''); ?>">
                      <i class="fas fa-calculator nav-icon"></i>
                      <p>
                          Accounting
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview udernavs">
                      <li class="nav-item">
                          <a href="<?php echo route('accsetup'); ?>" class="nav-link <?php echo e(Request::is('finance/accsetup') ? 'active' : ''); ?>">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Setup</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="<?php echo route('journalentries'); ?>" class="nav-link <?php echo e(Request::is('finance/accounting/journalentries') ? 'active' : ''); ?>">
                              <i class="far fa-circle nav-icon"></i>
                              <p>General Journal</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="<?php echo route('reports'); ?>" class="nav-link <?php echo e(Request::is('finance/accounting/reports') ? 'active' : ''); ?>">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Reports</p>
                          </a>
                      </li>
                  </ul>
              </li>
              
              <li class="nav-item has-treeview <?php echo e(Request::is('finance/purchasing/*') ? 'menu-open' : ''); ?>">
                  <a href="#" class="nav-link <?php echo e(Request::is('finance/purchasing/*') ? 'active' : ''); ?>">
                      <i class="fas fa-calculator nav-icon"></i>
                      <p>
                          Purchasing
                          <i class="fas fa-angle-left right"></i>
                      </p>
                  </a>
                  <ul class="nav nav-treeview udernavs">
                      <li class="nav-item">
                          <a href="<?php echo route('purchaseorder'); ?>" class="nav-link <?php echo e(Request::is('finance/purchasing/purchaseorder') ? 'active' : ''); ?>">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Purchase Order</p>
                          </a>
                      </li>
                      <li class="nav-item">
                          <a href="<?php echo route('receiving'); ?>" class="nav-link <?php echo e(Request::is('finance/purchasing/receiving') ? 'active' : ''); ?>">
                              <i class="far fa-circle nav-icon"></i>
                              <p>Receiving</p>
                          </a>
                      </li>
                  </ul>
              </li>
          <?php endif; ?>

          <?php if(auth()->user()->type == '15'): ?>
            

            
          <?php endif; ?>
        </ul>

        <ul class="nav nav-pills nav-sidebar flex-column side" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-header">
            Reports
          </li>
          
          <li class="nav-item">
            <a href="<?php echo route('cashtrans'); ?>" class="nav-link <?php echo e((Request::Is('finance/transactions/cashtrans')) ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-cash-register"></i>
              <p>
                Cashier Transactions
              </p>
            </a>
          </li>

          <?php
            $snr = DB::table('schoolinfo')
              ->where('snr', 'hcb')
              ->count();
          ?>

          <?php if($snr > 0): ?>
            <li class="nav-item">
              <a href="<?php echo route('dailycashcollection'); ?>" class="nav-link <?php echo e((Request::Is('finance/reports/dailycashcollection')) ? 'active' : ''); ?>">
                <i class="nav-icon fas fa-coins"></i>
                <p>
                  Daily Cash Collection
                </p>
              </a>
            </li>
          <?php endif; ?>

		  <li class="nav-item">
            <a href="/finance/reports/consolidated/index" class="nav-link <?php echo e((Request::Is('finance/reports/consolidated/index')) ? 'active' : ''); ?>">
              
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Consolidated Report
              </p>
            </a>
          </li>

         <li class="nav-item">
            <a href="/finance/reports/consolidated/schoolfees" class="nav-link <?php echo e((Request::Is('finance/reports/consolidated/schoolfees')) ? 'active' : ''); ?>">
              
              <i class="nav-icon fas fa-chart-line"></i>
              <p>
                Consolidated School Fees
              </p>
            </a>
          </li>

		  <li class="nav-item">
			<a href="/finance/feescollected" class="nav-link <?php echo e((Request::Is('finance/feescollected')) ? 'active' : ''); ?>">
			  
			  <i class="nav-icon fas fa-angle-double-left"></i>
			  <p>
          Item Collection Summary
			  </p>
			</a>
		  </li>

		  <li class="nav-item">
			<a href="/finance/assessedfees" class="nav-link <?php echo e((Request::Is('finance/assessedfees')) ? 'active' : ''); ?>">
			  
			  <i class="nav-icon fas fa-angle-double-right"></i>
			  <p>
          Item Receivables
			  </p>
			</a>
		  </li>

		  <li class="nav-item">
            <a href="/finance/reports/dcpr" class="nav-link <?php echo e((Request::Is('finance/reports/dcpr')) ? 'active' : ''); ?>">
              
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                DCPR
              </p>
            </a>
          </li>

		  <li class="nav-item">
            <a href="/finance/reports/monthlycollection" class="nav-link <?php echo e((Request::Is('finance/reports/monthlycollection')) ? 'active' : ''); ?>">
              
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                Monthly Collection
              </p>
            </a>
          </li>

		  <li class="nav-item">
            <a href="/finance/reports/yearend" class="nav-link <?php echo e((Request::Is('finance/reports/yearend')) ? 'active' : ''); ?>">
              
              <i class="nav-icon fas fa-chart-bar"></i>
              <p>
                Year End Summary
              </p>
            </a>
          </li>

          
          <li class="nav-item">
            <a href="<?php echo route('acctreceivable'); ?>" class="nav-link <?php echo e((Request::Is('acctreceivable')) ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Account Receivable
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo route('statementofacct'); ?>" class="nav-link <?php echo e((Request::Is('statementofacct')) ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-coins"></i>
              <p>
                Statement of Account
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="<?php echo route('oareceivables'); ?>" class="nav-link <?php echo e((Request::Is('finance/reports/oareceivables')) ? 'active' : ''); ?>">
              <i class="nav-icon fas fa-user-friends"></i>
              <p>
                Old Accounts Receivables
              </p>
            </a>
          </li>
          
		<li class="nav-item">
			<a href="/finance/stockcard/view" class="nav-link <?php echo e((Request::Is('finance/stockcard/view')) ? 'active' : ''); ?>">
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
								<a class="<?php echo e(Request::url() == url('/data/localtocloud') ? 'active':''); ?> nav-link" href="/data/localtocloud">
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
          <?php
            $priveledge = DB::table('faspriv')
                ->join('usertype','faspriv.usertype','=','usertype.id')
                ->select('faspriv.*','usertype.utype')
                ->where('userid', auth()->user()->id)
                ->where('faspriv.deleted','0')
				->where('type_active',1)
                ->where('faspriv.privelege','!=','0')
                ->get();

            $usertype = DB::table('usertype')->where('deleted',0)->where('id',auth()->user()->type)->first();
          ?>

           <?php $__currentLoopData = $priveledge; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($item->usertype != Session::get('currentPortal')): ?>
                <li class="nav-item">
                    <a class="nav-link portal" href="/gotoPortal/<?php echo e($item->usertype); ?>" id="<?php echo e($item->usertype); ?>">
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
              
              
              
              <li class="nav-header text-warning">My Applications</li>
              
              <li class="nav-item">
                  <a href="/leaves/apply/index"  id="dashboard" class="nav-link <?php echo e(Request::url() == url('/leaves/apply/index') ? 'active' : ''); ?>">
                      <i class="nav-icon fa fa-file"></i>
                      <p>
                          Leave Applications
                      </p>
                  </a>
              </li>
              <li class="nav-item">
                  <a href="/dtr/attendance/index" class="nav-link <?php echo e(Request::url() == url('/dtr/attendance/index') ? 'active' : ''); ?>">
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
<?php /**PATH C:\laragon\www\es_ldcu\resources\views/finance/layouts/navbar.blade.php ENDPATH**/ ?>