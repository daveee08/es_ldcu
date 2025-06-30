@extends('finance_v2.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
    <style>
        body {
            /* background-color: #f5f5f5; */
            /* font-family: Arial, sans-serif; */
        }
    
        .bg-light-gray {
            background-color: #e6e6e6;
        }
    
        .welcome-section {
            padding: 20px;
        }
    
        .welcome-name {
            color: #0e6c88;
            font-weight: bold;
        }
    
        .card {
            border-radius: 0;
            margin-bottom: 10px;
            border: none;
        }
    
        .card-header {
            background-color: #e6e6e6;
            border-radius: 0;
            padding: 15px;
            font-weight: bold;
        }
    
        .stats-card {
            border-radius: 4px;
            background-color: white;
            padding: 15px;
            height: 100%;
        }
    
        .stats-icon {
            font-size: 24px;
            margin-right: 10px;
        }
    
        .stats-number {
            font-size: 24px;
            font-weight: bold;
        }
    
        .time-display {
            font-size: 28px;
            color: #008000;
            font-weight: bold;
        }
    
        .school-level {
            font-size: 14px;
            font-weight: bold;
        }
    
        .enrollment-count {
            font-size: 20px;
            font-weight: bold;
        }
    
        .transaction-bar {
            height: 25px;
            margin: 5px 0;
            border-radius: 15px;
            overflow: hidden;
            display: flex;
        }
    
        .daily-trans {
            background-color: #a8d5f7;
        }
    
        .total-collection {
            background-color: #4495e5;
        }
    
        .receivable {
            background-color: #003b73;
            color: white;
        }
    
        .pagination-custom {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
        }
    
        .chart-container {
            height: 250px;
            width: 100%;
        }
    
        .menu-card {
            border-radius: 10px;
            padding: 20px;
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
            color: #333;
            font-weight: 500;
            position: relative;
        }
    
        .menu-link {
            color: #007bff;
            font-size: 14px;
            position: absolute;
            bottom: 5px;
            right: 15px;
        }
    
        .modal-header {
            border-bottom: none;
            position: relative;
        }
    
        .close-icon {
            position: absolute;
            top: 15px;
            right: 15px;
        }
    
        .hand-icon {
            margin-right: 10px;
        }
    
        .cashiering-bg {
            background-color: #a8e1ec;
        }
    
        .pin-bg {
            background-color: #8c8c8c;
            color: white;
        }
    
        .downpayment-bg {
            background-color: #eedeb0;
        }
    
        .signatories-bg {
            background-color: #d99c9c;
        }
    
        .payment-bg {
            background-color: #d8a8e1;
        }
    
        .chart-bg {
            background-color: #e0e0e0;
        }
    </style>
@endsection

@section('content')
    <div class="content">
       
        <div class="container-fluid px-0">
            <!-- Top Header Section -->
            <div class="row no-gutters align-items-stretch">
                <!-- Left Section (Welcome) -->
                <div class="col-md-6 d-flex">
                    <div class="d-flex bg-light-gray p-4 align-items-center w-100">
                        <div class="w-50">
                            <h5 class="mb-1">Welcome, <span class="font-weight-bold text-primary">KATHLEEN GRACE!</span>
                            </h5>
                            <p class="small mb-1 text-muted">Finance Admin</p>
                            <a href="#" id="finance_authorization" class="text-primary small" data-toggle="modal"
                                data-target="#financeAuthModal">
                                Go to Finance Authorization Menu
                            </a>
                        </div>
                        <div class="w-50 text-center">
                            <h6 class="mb-1">August 10, 2024</h6>
                            <p class="small text-muted mb-1">Tap IN today</p>
                            <p class="font-weight-bold text-success display-4 mb-0">08:17 am</p>
                        </div>
                    </div>
                </div>

                <!-- Right Section (School Stats) -->
                <div class="col-md-6 bg-light-gray p-4 align-items-center d-flex">
                    <div class="w-100">
                        <div class="row">
                            <!-- Preschool -->
                            <div class="col-md-4 mb-3">
                                <div class="stats-card p-3 bg-white shadow-sm rounded text-center">
                                    <i class="fas fa-child fa-2x mb-2"></i>
                                    <div class="small text-muted">Preschool</div>
                                    <div class="font-weight-bold text-dark">40</div>
                                </div>
                            </div>
                            <!-- Junior High School -->
                            <div class="col-md-4 mb-3">
                                <div class="stats-card p-3 bg-white shadow-sm rounded text-center">
                                    <i class="fas fa-user-graduate fa-2x mb-2"></i>
                                    <div class="small text-muted">Junior High School</div>
                                    <div class="font-weight-bold text-dark">420</div>
                                </div>
                            </div>
                            <!-- Grade School -->
                            <div class="col-md-4 mb-3">
                                <div class="stats-card p-3 bg-white shadow-sm rounded text-center">
                                    <i class="fas fa-school fa-2x mb-2"></i>
                                    <div class="small text-muted">Grade School</div>
                                    <div class="font-weight-bold text-dark">196</div>
                                </div>
                            </div>
                            <!-- Senior High School -->
                            <div class="col-md-4 mb-3">
                                <div class="stats-card p-3 bg-white shadow-sm rounded text-center">
                                    <i class="fas fa-graduation-cap fa-2x mb-2"></i>
                                    <div class="small text-muted">Senior High School</div>
                                    <div class="font-weight-bold text-dark">268</div>
                                </div>
                            </div>
                            <!-- College -->
                            <div class="col-md-4 mb-3">
                                <div class="stats-card p-3 bg-white shadow-sm rounded text-center">
                                    <i class="fas fa-university fa-2x mb-2"></i>
                                    <div class="small text-muted">College</div>
                                    <div class="font-weight-bold text-dark">0</div>
                                </div>
                            </div>
                            <!-- TECHVOC -->
                            <div class="col-md-4 mb-3">
                                <div class="stats-card p-3 bg-white shadow-sm rounded text-center">
                                    <i class="fas fa-tools fa-2x mb-2"></i>
                                    <div class="small text-muted">TECHVOC</div>
                                    <div class="font-weight-bold text-dark">0</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <!-- Left Column -->
                <div class="container-fluid">
                    <div class="row no-gutters align-items-stretch">
                        <!-- Cashier Transactions Card -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Cashier Transactions</span>
                                    <div>
                                        <button class="btn btn-sm btn-light mr-2"><i
                                                class="fas fa-chevron-left"></i></button>
                                        <span>All</span>
                                        <button class="btn btn-sm btn-light ml-2"><i
                                                class="fas fa-chevron-right"></i></button>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div class="container-fluid">
                                        <div class="row mb-2 mt-3">
                                            <div class="col-3"></div>
                                            <div class="col-3"><span class="badge daily-trans">&nbsp;</span> Daily
                                                Transactions</div>
                                            <div class="col-3"><span class="badge total-collection">&nbsp;</span> Total
                                                Collection</div>
                                            <div class="col-3"><span class="badge receivable">&nbsp;</span> Receivable</div>
                                        </div>

                                        <!-- Transaction Bars -->
                                        <div class="row align-items-center mb-2">
                                            <div class="col-3">Kinder</div>
                                            <div class="col-9">
                                                <div class="transaction-bar">
                                                    <div class="daily-trans" style="width: 7%;">12,000</div>
                                                    <div class="total-collection" style="width: 15%;">36,000</div>
                                                    <div class="receivable" style="width: 78%; text-align: center;">453,223
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-2">
                                            <div class="col-3">Grade 1</div>
                                            <div class="col-9">
                                                <div class="transaction-bar">
                                                    <div class="daily-trans" style="width: 8%;">19,000</div>
                                                    <div class="total-collection" style="width: 17%;">61,000</div>
                                                    <div class="receivable" style="width: 75%; text-align: center;">412,000
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-2">
                                            <div class="col-3">Grade 2</div>
                                            <div class="col-9">
                                                <div class="transaction-bar">
                                                    <div class="daily-trans" style="width: 6%;">13,000</div>
                                                    <div class="total-collection" style="width: 16%;">59,000</div>
                                                    <div class="receivable" style="width: 78%; text-align: center;">414,656
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-2">
                                            <div class="col-3">Grade 3</div>
                                            <div class="col-9">
                                                <div class="transaction-bar">
                                                    <div class="daily-trans" style="width: 9%;">23,000</div>
                                                    <div class="total-collection" style="width: 18%;">65,000</div>
                                                    <div class="receivable" style="width: 73%; text-align: center;">401,332
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row align-items-center mb-4">
                                            <div class="col-3">Grade 4</div>
                                            <div class="col-9">
                                                <div class="transaction-bar">
                                                    <div class="daily-trans" style="width: 8%;">25,000</div>
                                                    <div class="total-collection" style="width: 14%;">48,000</div>
                                                    <div class="receivable" style="width: 78%; text-align: center;">423,665
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-4">
                                            <div class="col-3">Grade 4</div>
                                            <div class="col-9">
                                                <div class="transaction-bar">
                                                    <div class="daily-trans" style="width: 8%;">25,000</div>
                                                    <div class="total-collection" style="width: 14%;">48,000</div>
                                                    <div class="receivable" style="width: 78%; text-align: center;">423,665
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row align-items-center mb-2">
                                            <div class="col-3">Grade 6</div>
                                            <div class="col-9">
                                                <div class="transaction-bar">
                                                    <div class="daily-trans" style="width: 4%;">9,123</div>
                                                    <div class="total-collection" style="width: 16%;">72,000</div>
                                                    <div class="receivable" style="width: 80%; text-align: center;">436,654
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Account Receivable Card -->
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <span>Account Receivable</span>
                                    <div class="d-flex align-items-center">
                                        <small class="mr-2">School Year</small>
                                        <select class="form-control form-control-sm" style="width: 150px;">
                                            <option>2024-2025</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h6 class="mb-0">Total Receivable</h6>
                                        <h4 class="mb-0 font-weight-bold">7,780,650.00</h4>
                                    </div>

                                    <!-- Bar Chart -->
                                    <div class="chart-container">
                                        <div class="d-flex align-items-end" style="height: 100%;">
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 40px; width: 20px; background-color: #00a000;"></div>
                                                <small class="mt-1">120K</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 60px; width: 20px; background-color: #00a000;"></div>
                                                <small class="mt-1">132K</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 90px; width: 20px; background-color: #00a000;"></div>
                                                <small class="mt-1">421K</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 130px; width: 20px; background-color: #00a000;"></div>
                                                <small class="mt-1">611K</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 170px; width: 20px; background-color: #005000;"></div>
                                                <small class="mt-1">930K</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 200px; width: 20px; background-color: #005000;"></div>
                                                <small class="mt-1">990K</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 150px; width: 20px; background-color: #005000;"></div>
                                                <small class="mt-1">840K</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 170px; width: 20px; background-color: #005000;"></div>
                                                <small class="mt-1">930K</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 200px; width: 20px; background-color: #005000;"></div>
                                                <small class="mt-1">990K</small>
                                            </div>
                                            <div class="d-flex flex-column align-items-center" style="width: 8.33%;">
                                                <div style="height: 150px; width: 20px; background-color: #005000;"></div>
                                                <small class="mt-1">840K</small>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>

        {{-- this is the finance Authorization modal --}}
        <!-- Finance Authorization Modal -->
        <div class="modal fade" id="financeAuthModal" tabindex="-1" role="dialog" aria-labelledby="financeAuthModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header pb-0">
                        <div class="d-flex align-items-center">
                            <span class="hand-icon">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7 11.5V14M10.5 5.5V14M14 6.5V14M17.5 9.5V14M5 19.5H19C19.5523 19.5 20 19.0523 20 18.5V5.5C20 4.94772 19.5523 4.5 19 4.5H5C4.44772 4.5 4 4.94772 4 5.5V18.5C4 19.0523 4.44772 19.5 5 19.5Z"
                                        stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            <h5 class="modal-title" id="financeAuthModalLabel">Finance Authorization Menu</h5>
                        </div>
                        <button type="button" class="close close-icon" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <!-- First Row -->
                                <div class="col-md-4">
                                    <div class="menu-card cashiering-bg d-flex flex-column" style="min-height: 150px;">
                                        <div>Cashiering Setup</div>
                                        <div class="flex-grow-1"></div>
                                        <div class="w-100 bg-white d-flex justify-content-center align-items-center"
                                            style="height: 40px;">
                                            <a href="#" class="menu-link">View more Info</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="menu-card pin-bg d-flex flex-column" style="min-height: 150px;">
                                        <div>PIN &amp; Authorization</div>
                                        <div class="flex-grow-1"></div>
                                        <div class="w-100 bg-white d-flex justify-content-center align-items-center"
                                            style="height: 40px;">
                                            <a href="#" class="menu-link">View more Info</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="menu-card downpayment-bg d-flex flex-column" style="min-height: 150px;">
                                        <div>NO Downpayment Setup</div>
                                        <div class="flex-grow-1"></div>
                                        <div class="w-100 bg-white d-flex justify-content-center align-items-center"
                                            style="height: 40px;">
                                            <a href="#" class="menu-link">View more Info</a>
                                        </div>
                                    </div>
                                </div>
                                <!-- Second Row -->
                                <div class="col-md-4">
                                    <div class="menu-card signatories-bg d-flex flex-column" style="min-height: 150px;">
                                        <div>Signatories</div>
                                        <div class="flex-grow-1"></div>
                                        <div class="w-100 bg-white d-flex justify-content-center align-items-center"
                                            style="height: 40px;">
                                            <a href="#" class="menu-link">View more Info</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="menu-card payment-bg d-flex flex-column" style="min-height: 150px;">
                                        <div>Online Payment Setup</div>
                                        <div class="flex-grow-1"></div>
                                        <div class="w-100 bg-white d-flex justify-content-center align-items-center"
                                            style="height: 40px;">
                                            <a href="#" class="menu-link">View more Info</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="menu-card chart-bg d-flex flex-column" style="min-height: 150px;">
                                        <div>Chart of Account</div>
                                        <div class="flex-grow-1"></div>
                                        <div class="w-100 bg-white d-flex justify-content-center align-items-center"
                                            style="height: 40px;">
                                            <a href="#" class="menu-link">View more Info</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@endsection
