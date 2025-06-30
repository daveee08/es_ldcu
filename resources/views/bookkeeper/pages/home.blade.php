@extends('bookkeeper.layouts.app')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endsection

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Home Accounting</title>
    <style>
        /* .progress-bar-receivables {
            background: linear-gradient(to right, #004d00, #008000);
        } */
        .progress-bar-receivables {
            background: linear-gradient(to right, #004d00, #008000);
        }

        .progress-bar-payables {
            background: linear-gradient(to right, #000066, #0000ff);
        }

        .gradient-green {
            background: linear-gradient(to top, #004d00, #00cc00);
        }

        .card-header {
            background: #D8D8D9 !important;
            ;
            font-weight: bold;
        }

        .income-bar {
            background: linear-gradient(to top, #004d00, #00cc00);
        }

        .expenses-bar {
            background: linear-gradient(to top, #8B4513, #A0522D);
        }
    </style>

</head>

<body>
    @section('content')
        @php
            $activeFiscalYear = DB::table('bk_fiscal_year')->where('isactive', 1)->where('deleted', 0)->first();
            $activeFiscalYearId = isset($activeFiscalYear) ? $activeFiscalYear->id : null;

            $setactiveFiscalYear = isset($activeFiscalYear) ? $activeFiscalYear->description : null;

            $setactiveStartdateFiscalYear = isset($activeFiscalYear)
                ? \Carbon\Carbon::parse($activeFiscalYear->stime)->format('Y')
                : null;

            $coa = DB::table('chart_of_accounts')->get();

            // $FinanceSchoolYear = DB::table('sy')->select('sydesc')->get();

            $FinanceSchoolYear = DB::table('sy')->select('id', 'sydesc')->get();
            $activeFiscalYearId = isset($activeFiscalYear) ? $activeFiscalYear->id : null;

            $fiscal = DB::table('bk_fiscal_year')->where('deleted', 0)->get();
        @endphp
        <div class="container-fluid mt-3">
            <div class=" d-flex justify-content-between align-items-center">
                <div class="d-flex">
                    <p class="mr-1">FISCAL YEAR</p>
                    {{-- (<p class="ml-0" id="selectedfiscalyear"> {{ $setactiveFiscalYear }}</p>) --}}
                    (<p class="ml-0" id="selectedfiscalyear"> {{ $setactiveStartdateFiscalYear }}</p>)
                </div>
            </div>
            <div>
                <p id="school_year" hidden>{{ $FinanceSchoolYear->pluck('sydesc')->implode(', ') }}</p>
            </div>

            <div>
                <p id="school_year_data" hidden data-school-years='{{ json_encode($FinanceSchoolYear) }}'></p>
            </div>

            <div>
                <p id="income_expenses_school_year_data" hidden data-school-years='{{ json_encode($FinanceSchoolYear) }}'>
                </p>
            </div>


            <div class="row">
                <!-- Receivables -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light-gray">Receivables</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span>Total Receivables</span>
                                <span class="font-weight-bold" id="total_receivables"></span>

                            </div>
                            <div class="progress mt-2" style="height: 10px;" id="receivablesProgress"></div>
                            <div class="row mt-2" style="border-top: 1px solid black;">
                                <div class="col-8 text-left ">
                                    <small class="text-primary">Total Paid</small>
                                    <h6 id="total_paid"></h6>
                                </div>
                                <div class="col-4 text-left " style="border-left: 1px solid black;">
                                    <small class="text-danger">Total Unpaid</small>
                                    <h6 id="total_unpaid"></h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payables -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-light-gray">Payables</div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <span>Total Payables</span>
                                <span class="font-weight-bold" id="total_payables"></span>
                            </div>
                            <div class="progress mt-2" style="height: 10px;" id="payablesProgress"></div>
                            <div class="row mt-2" style="border-top: 1px solid black;">
                                <div class="col-8 text-left">
                                    <small class="text-primary">Total Paid</small>
                                    <h6 id="total_paid_payables"></h6>
                                </div>
                                <div class="col-4 text-left " style="border-left: 1px solid black;">
                                    <small class="text-danger">Total Unpaid</small>
                                    <h6 id="total_unpaid_payables"></h6>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Cashflow Section -->
            <div class="row mt-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">Cashflow</h3>
                            <div class="ml-auto">
                                <select id="cashflow_fiscal_year" class="btn btn-sm btn-outline-secondary">
                                    <option value="{{ $activeFiscalYearId }}" selected>This Fiscal Year</option>
                                    @foreach ($fiscal as $item)
                                        <option value="{{ $item->id }}" @if ($item->id == $activeFiscalYearId)  @endif>
                                            {{ $item->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <canvas id="cashflowChart"></canvas>
                                </div>
                                <div class="col-md-4">

                                    <div class="d-flex justify-content-between">
                                        <div><small class="mr-1">Cash as on</small><small id="fiscal_start_date"></small>
                                        </div>
                                        <div>
                                            <h6 id="cash_as_on_starting">PHP 20,000.00</h6>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between text-primary">
                                        <small>Receivables</small>
                                        <h6 id="cashflow_receivables"></h6>
                                    </div>
                                    <div class="d-flex justify-content-between text-danger">
                                        <small>Payables</small>
                                        <h6 id="cashflow_payables"></h6>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <div><small class="mr-1">Cash as of</small><small id="fiscal_end_date"></small>
                                        </div>
                                        <div>
                                            <h6 id="cash_as_of_ending">PHP 4,526,110.23</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container-fluid mt-3">
                <div class="row d-flex align-items-stretch">
                    <!-- Income & Expenses -->
                    <div class="col-md-6 d-flex">
                        <div class="card h-100 w-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Income & Expenses</span>
                                <div class="ml-auto">
                                    <select id="income_expenses_fiscal_year" class="btn btn-sm btn-outline-secondary">
                                        <option value="{{ $activeFiscalYearId }}" selected
                                            data-description="{{ isset($activeFiscalYear) ? $activeFiscalYear->description : '' }}">
                                            This Fiscal Year</option>
                                        @foreach ($fiscal as $item)
                                            <option value="{{ $item->id }}" @if ($item->id == $activeFiscalYearId)  @endif>
                                                {{ $item->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="card-body">
                                <!-- Aligns buttons to the right -->
                                <div class="d-flex justify-content-end mb-2">
                                    {{-- <button class="btn btn-sm btn-outline-secondary mr-2">Accrual</button>
                                    <button class="btn btn-sm btn-outline-secondary">Cash</button> --}}
                                </div>

                                <canvas id="incomeExpensesChart"></canvas>





                                <div class="row align-items-center">
                                    <div class="col-md-3">
                                        <div class="d-flex flex-column" style="border-right: #E4E5E4 solid 1px">
                                            <div class="d-flex align-items-center">
                                                <span class="badge mr-2" style="background-color: #005A0B;">■</span>
                                                <span>Income</span>
                                            </div>
                                            <div class="d-flex align-items-center mt-2">
                                                <span class="badge mr-2" style="background-color: #944311;">■</span>
                                                <span>Expenses</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-9">
                                        <hr class="my-2">
                                        <div class="row">
                                            <div class="col-md-6 text-left">
                                                <h6 class="text-success">Total Income</h6>
                                                <h5 class="font-weight-bold" id="total_income">PHP 2,213,066.50</h5>
                                            </div>
                                            <div class="col-md-6 text-left">
                                                <h6 class="text-brown" style="color: #944311;">Total Expenses</h6>
                                                <h5 class="font-weight-bold" id="total_expenses">PHP 1,562,002.75</h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                                <small class="text-muted">*Income and Expense values displayed are exclusive of
                                    Taxes</small>
                            </div>
                        </div>
                    </div>

                    <!-- Top Expenses -->
                    <div class="col-md-6 d-flex">
                        <div class="card h-100 w-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Top Expenses</span>
                                <div class="ml-auto">
                                    <select id="top_expenses_fiscal_year" class="btn btn-sm btn-outline-secondary">
                                        <option value="{{ $activeFiscalYearId }}" selected>This Fiscal Year</option>
                                        @foreach ($fiscal as $item)
                                            <option value="{{ $item->id }}" @if ($item->id == $activeFiscalYearId)  @endif>
                                                {{ $item->description }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="card-body p-3 mt-2">
                                <canvas class="p-3" id="topExpensesChart"></canvas>
                                <div class="mt-3 p-3">
                                    <ul class="list-unstyled m-0">
                                        <li class="d-flex align-items-center mb-2">
                                            <span class="d-inline-block rounded-circle" id="top1"
                                                style="width: 10px; height: 10px; background-color: #145A32; margin-right: 8px;"></span>
                                            <span id="top1-text"></span> <!-- Added span for text -->
                                        </li>
                                        <li class="d-flex align-items-center mb-2">
                                            <span class="d-inline-block rounded-circle" id="top2"
                                                style="width: 10px; height: 10px; background-color: #8B4513; margin-right: 8px;"></span>
                                            <span id="top2-text"></span> <!-- Added span for text -->
                                        </li>
                                        <li class="d-flex align-items-center mb-2">
                                            <span class="d-inline-block rounded-circle" id="top3"
                                                style="width: 10px; height: 10px; background-color: #B8860B; margin-right: 8px;"></span>
                                            <span id="top3-text"></span> <!-- Added span for text -->
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            {{-- <div class="card-body p-3 mt-2">
                                <canvas class="p-3" id="topExpensesChart"></canvas>
                                <div class="mt-3 p-3">
                                    <ul class="list-unstyled m-0" id="topExpensesLegend">
                                        <!-- This will be populated dynamically -->
                                    </ul>
                                </div>
                            </div> --}}
                        </div>
                    </div>

                </div>
            </div>



    </body>
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
    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     function generateProgressBar(data, total, progressBarId) {
        //         const progressBar = document.getElementById(progressBarId);
        //         data.forEach(segment => {
        //             let widthPercentage = (segment.amount / total) * 100;
        //             let barSegment = document.createElement("div");
        //             barSegment.classList.add("progress-bar");
        //             barSegment.style.width = widthPercentage + "%";
        //             barSegment.style.background = segment.color;
        //             progressBar.appendChild(barSegment);
        //         });
        //     }

        //     // Data
        //     const receivablesTotal = 12_562_523.00;
        //     const receivablesData = [{
        //             amount: 4_526_110.23,
        //             color: "linear-gradient(to right, #004d00, #008000)"
        //         }, // Paid (36%)
        //         {
        //             amount: 8_036_412.77,
        //             color: "linear-gradient(to right, #ff0000, #ff6600)"
        //         } // Unpaid (64%)
        //     ];

        //     const payablesTotal = 5_203_220.00;
        //     const payablesData = [{
        //             amount: 4_526_110.23,
        //             color: "linear-gradient(to right, #000066, #0000ff)"
        //         }, // Paid (87%)
        //         {
        //             amount: 677_109.77,
        //             color: "linear-gradient(to right, #ff0000, #ff6600)"
        //         } // Unpaid (13%)
        //     ];

        //     // Generate bars
        //     generateProgressBar(receivablesData, receivablesTotal, "receivablesProgress");
        //     generateProgressBar(payablesData, payablesTotal, "payablesProgress");

        //     // Cashflow Chart
        //     var ctx = document.getElementById("cashflowChart").getContext("2d");

        //     var gradient = ctx.createLinearGradient(0, 200, 0, 0);
        //     gradient.addColorStop(0, "#004d00");
        //     gradient.addColorStop(1, "#00cc00");

        //     var cashflowChart = new Chart(ctx, {
        //         type: "bar",
        //         data: {
        //             labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov",
        //                 "Dec"
        //             ],
        //             datasets: [{
        //                 label: "Cashflow",
        //                 data: [15000, 25000, 35000, 40000, 50000, 60000, 58000, 62000, 70000, 75000,
        //                     80000, 500
        //                 ],
        //                 backgroundColor: gradient,
        //                 borderWidth: 1
        //             }]
        //         },
        //         options: {
        //             responsive: true,
        //             scales: {
        //                 yAxes: [{
        //                     ticks: {
        //                         beginAtZero: true,
        //                         callback: function(value) {
        //                             return value.toLocaleString();
        //                         }
        //                     }
        //                 }]
        //             }
        //         }
        //     });
        // });



        // document.addEventListener("DOMContentLoaded", function() {
        //     var ctxIncomeExpenses = document.getElementById("incomeExpensesChart").getContext("2d");

        //     var incomeExpensesChart = new Chart(ctxIncomeExpenses, {
        //         type: "bar",
        //         data: {
        //             labels: ["May 2024", "Jun 2024", "Jul 2024", "Aug 2024", "Sep 2024", "Oct 2024",
        //                 "Nov 2024", "Dec 2024", "Jan 2025", "Feb 2025", "Mar 2025", "Apr 2025"
        //             ],
        //             datasets: [{
        //                     label: "Income",
        //                     data: [650000, 600000, 450000, 500000, 350000, 300000, 250000, 700000,
        //                         300000, 200000, 0, 0
        //                     ],
        //                     backgroundColor: "rgba(0, 128, 0, 0.7)"
        //                 },
        //                 {
        //                     label: "Expenses",
        //                     data: [100000, 200000, 150000, 250000, 400000, 350000, 300000, 600000,
        //                         100000, 50000, 0, 0
        //                     ],
        //                     backgroundColor: "rgba(139,69,19, 0.7)"
        //                 }
        //             ]
        //         },
        //         options: {
        //             responsive: true,
        //             scales: {
        //                 yAxes: [{
        //                     ticks: {
        //                         beginAtZero: true,
        //                         callback: function(value) {
        //                             return "PHP " + value.toLocaleString();
        //                         }
        //                     }
        //                 }]
        //             }
        //         }
        //     });

        //     var ctxTopExpenses = document.getElementById("topExpensesChart").getContext("2d");

        //     var topExpensesChart = new Chart(ctxTopExpenses, {
        //         type: "doughnut",
        //         data: {
        //             labels: ["Building Infrastructure", "Salaries & Wages", "Discounts"],
        //             datasets: [{
        //                 data: [800000, 500000, 262002.75],
        //                 backgroundColor: ["#008000", "#8B4513", "#DAA520"]
        //             }]
        //         },
        //         options: {
        //             responsive: true,
        //             legend: {
        //                 display: false
        //             }
        //         }
        //     });
        // });

        $(document).ready(function() {
            var fiscal_year = $('#selectedfiscalyear').text().trim();
            var schoolYearsData = JSON.parse($('#school_year_data').attr('data-school-years'));

            var matchedYear = schoolYearsData.find(item => item.sydesc.startsWith(fiscal_year));





            if (matchedYear) {
                console.log('Matched year:', matchedYear.sydesc, 'ID:', matchedYear.id);
                $('#school_year_data').text(matchedYear.id);
            } else {
                console.log('No matched year');
                $('#school_year_data').text('No matching school year found');
            }

            console.log('selected school_year id:', $('#school_year_data').text());

            var selectedschoolyear = $('#school_year_data').text();

            var loadingtext = 'Getting ready...';
            var selecteddaterange = $('#selecteddaterange').val() || null;
            var selecteddepartment = $('#selecteddepartment').val() || null;
            var selectedgradelevel = $('#selectedgradelevel').val() || null;
            var selectedsemester = $('#selectedsemester').val();
            var selectedsection = $('#selectedsection').val() || null;
            var selectedgrantee = $('#selectedgrantee').val() || null;
            var selectedmode = $('#selectedmode').val() || null;

            function filterstudents() {
                Swal.fire({
                    title: loadingtext,
                    onBeforeOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false
                });

                $.ajax({
                    url: '{{ route('acctreceivablefilter_bookkeeper') }}',
                    type: 'GET',
                    data: {
                        selectedschoolyear,
                        selecteddaterange,
                        selecteddepartment,
                        selectedgradelevel,
                        selectedsemester,
                        selectedsection,
                        selectedgrantee,
                        selectedmode
                    },
                    success: function(data) {
                        var total_receivables = 'PHP ' + data.overalltotalassessment
                            .toLocaleString();
                        var total_paid = 'PHP ' + data.overalltotalpayment.toLocaleString();
                        var total_unpaid = 'PHP ' + data.overalltotalbalance.toLocaleString();

                        $('#total_receivables').text(total_receivables);
                        $('#total_paid').text(total_paid);
                        $('#total_unpaid').text(total_unpaid);
                        $('#cashflow_receivables').text('PHP ' + data.overalltotalpayment
                            .toLocaleString());
                        $(".swal2-container").remove();
                        $('body').removeClass('swal2-shown swal2-height-auto');
                        // $('#total_income').text('PHP ' + data.overalltotalpayment.toLocaleString());

                        updateProgressBars(total_receivables, total_paid, total_unpaid)
                    }
                });
            }

            filterstudents();

            function total_received_history() {
                $.ajax({
                    url: '{{ route('filter_total_received_history') }}',
                    type: 'GET',
                    data: {
                        selectedschoolyear,
                        selecteddaterange,
                        selecteddepartment,
                        selectedgradelevel,
                        selectedsemester,
                        selectedsection,
                        selectedgrantee,
                        selectedmode
                    },
                    success: function(data) {
                        console.log('Received data:', data);


                        $('#cashflow_payables').text('PHP ' + data.overalltotaldisbursements
                            .toLocaleString());
                        var overallunpaid_payables = isNaN(data.overallpayables - data
                            .overalltotaldisbursements) ? 0 : Math.max(0, data.overallpayables - data
                            .overalltotaldisbursements);
                        console.log('overallunpaid_payables:', overallunpaid_payables.toLocaleString());

                        var formattedTotalPayables = 'PHP ' + (data.overallpayables).toLocaleString(
                            'en-US', {
                                minimumFractionDigits: 2
                            });
                        var formattedTotalPaidPayables = 'PHP ' + data.overalltotaldisbursements
                            .toLocaleString();

                            
                        var formattedTotalUnpaidPayables = 'PHP ' + overallunpaid_payables
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2
                            });

                        $('#total_payables').text(formattedTotalPayables);
                        $('#total_paid_payables').text(formattedTotalPaidPayables);
                        $('#total_unpaid_payables').text(formattedTotalUnpaidPayables);
                        // $('#total_expenses').text('PHP ' + data.overalltotaldisbursements
                        //     .toLocaleString());
                        updateProgressBars_payables(formattedTotalPayables, formattedTotalPaidPayables, formattedTotalUnpaidPayables)
                    }
                });
            }

            total_received_history();


            loadcashflow_fiscal();

            $('#cashflow_fiscal_year').change(function() {
                var fiscal_year = $(this).val();
                loadcashflow_fiscal(fiscal_year);
            });

            // function loadcashflow_fiscal(fiscal_year) {
            //     var fiscal_year = $('#cashflow_fiscal_year').val();
            //     $.ajax({
            //         url: '/bookkeeper/cashflow_fiscal',
            //         type: 'GET',
            //         data: {
            //             fiscal_year_id: fiscal_year
            //         },
            //         success: function(data) {
            //             if (data.fiscal_year.length > 0) {
            //                 let stime = new Date(data.fiscal_year[0].stime);
            //                 $('#fiscal_start_date').text(
            //                     ('0' + (stime.getDate())).slice(-2) + ' ' + ['Jan', 'Feb', 'Mar',
            //                         'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            //                     ][stime.getMonth()] + ' ' +
            //                     stime.getFullYear()
            //                 );
            //             } else {
            //                 $('#fiscal_start_date').text('N/A');
            //             }

            //             if (data.fiscal_year.length > 0) {
            //                 let etime = new Date(data.fiscal_year[0].etime);
            //                 $('#fiscal_end_date').text(
            //                     ('0' + (etime.getDate())).slice(-2) + ' ' + ['Jan', 'Feb', 'Mar',
            //                         'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            //                     ][etime.getMonth()] + ' ' +
            //                     etime.getFullYear()
            //                 );
            //             } else {
            //                 $('#fiscal_end_date').text('N/A');
            //             }

            //         },
            //         error: function() {
            //             $('#fiscal_end_date').text('N/A');
            //         }
            //     });
            // }

            function loadcashflow_fiscal(fiscal_year) {
                var fiscal_year = $('#cashflow_fiscal_year').val();
                $.ajax({
                    url: '/bookkeeper/cashflow_fiscal',
                    type: 'GET',
                    data: {
                        fiscal_year_id: fiscal_year
                    },
                    success: function(data) {
                        console.log('Fiscal Year Data:', data);

                        // Update fiscal year dates
                        console.log('Fiscal Year Dates:', data.fiscal_year);

                        if (data.fiscal_year) {
                            let stime = new Date(data.fiscal_year.stime);
                            $('#fiscal_start_date').text(
                                ('0' + (stime.getDate())).slice(-2) + ' ' + ['Jan', 'Feb', 'Mar',
                                    'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                ][stime.getMonth()] + ' ' +
                                stime.getFullYear()
                            );

                            let etime = new Date(data.fiscal_year.etime);
                            etime.setDate(new Date(etime.getFullYear(), etime.getMonth() + 1, 0)
                                .getDate());
                            $('#fiscal_end_date').text(
                                ('0' + (etime.getDate())).slice(-2) + ' ' + ['Jan', 'Feb', 'Mar',
                                    'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                                ][etime.getMonth()] + ' ' +
                                etime.getFullYear()
                            );

                            $('#cash_as_on_starting').text('PHP ' + data.cashflow_for_stime_month
                                .startdate_net_amount);

                            // var receivables = parseFloat($('#cashflow_receivables').text().replace(/[^\d.-]/g, '')) || 0;
                            // var payables = parseFloat($('#cashflow_payables').text().replace(/[^\d.-]/g, '')) || 0;
                            // var cashEnding = receivables - payables;
                            // $('#cash_as_of_ending').text('PHP ' + cashEnding.toLocaleString());

                            var receivablesText = $('#cashflow_receivables').text().trim();  // Fixed typo in selector (was 'cashflow_receivables')
                            var payablesText = $('#cashflow_payables').text().trim();

                            // Use consistent parsing with proper error handling
                            var receivables = parseFloat(receivablesText.replace(/[^\d.-]/g, '')) || 0;
                            var payables = parseFloat(payablesText.replace(/[^\d.-]/g, '')) || 0;

                            // Ensure we're working with numbers and handle potential NaN cases
                            receivables = isNaN(receivables) ? 0 : receivables;
                            payables = isNaN(payables) ? 0 : payables;

                            // Perform calculation with fixed precision to avoid floating point issues
                            var cashEnding = parseFloat((receivables - payables).toFixed(2));

                            // Format with currency and proper localization
                            $('#cash_as_of_ending').text('PHP ' + cashEnding.toLocaleString('en-PH', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }));
                          


                        } else {
                            $('#fiscal_start_date').text('N/A');
                            $('#fiscal_end_date').text('N/A');
                        }

                        // Update cashflow chart with dynamic data
                        updateCashflowChart(data.cashflow_by_month);

                        // Update progress bars (you would need to fetch this data separately)
                        updateProgressBars();
                    },
                    error: function() {
                        $('#fiscal_start_date').text('N/A');
                        $('#fiscal_end_date').text('N/A');
                        console.error('Error loading cashflow data');
                    }
                });
            }

            // Function to update the cashflow chart
            function updateCashflowChart(cashflowData) {
                const ctx = document.getElementById("cashflowChart").getContext("2d");

                // Check if the chart exists and is a valid Chart instance before destroying
                if (window.cashflowChart && typeof window.cashflowChart.destroy === 'function') {
                    window.cashflowChart.destroy();
                }

                // Extract labels and values from the cashflow data
                const labels = Object.keys(cashflowData);
                const values = Object.values(cashflowData).map(val => parseFloat(val.replace(/,/g, '')));

                var gradient = ctx.createLinearGradient(0, 200, 0, 0);
                gradient.addColorStop(0, "#004d00");
                gradient.addColorStop(1, "#00cc00");

                window.cashflowChart = new Chart(ctx, {
                    type: "bar",
                    data: {
                        labels: labels,
                        datasets: [{
                            label: "Cashflow",
                            data: values,
                            backgroundColor: gradient,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: false,
                                    callback: function(value) {
                                        return value.toLocaleString();
                                    }
                                }
                            }]
                        },
                        tooltips: {
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    return 'Amount: ' + tooltipItem.yLabel.toLocaleString();
                                }
                            }
                        }
                    }
                });
            }

            // Function to generate progress bars
            // function generateProgressBar(data, total, progressBarId) {
            //     const progressBar = document.getElementById(progressBarId);
            //     progressBar.innerHTML = ''; // Clear existing bars

            //     data.forEach(segment => {
            //         let widthPercentage = (segment.amount / total) * 100;
            //         let barSegment = document.createElement("div");
            //         barSegment.classList.add("progress-bar");
            //         barSegment.style.width = widthPercentage + "%";
            //         barSegment.style.background = segment.color;
            //         progressBar.appendChild(barSegment);
            //     });
            // }

            function generateProgressBar(data, total, receivablesProgress) {
                const progressBar = document.getElementById(receivablesProgress);
                progressBar.innerHTML = ''; // Clear existing bars

                data.forEach(segment => {
                    let widthPercentage = (segment.amount / total) * 100;
                    let barSegment = document.createElement("div");
                    barSegment.classList.add("progress-bar");
                    barSegment.style.width = widthPercentage + "%";
                    barSegment.style.background = segment.color;
                    progressBar.appendChild(barSegment);
                });
            }

            function generateProgressBar_payables(data, total, payablesProgress) {
                const progressBar = document.getElementById(payablesProgress);
                progressBar.innerHTML = ''; // Clear existing bars

                data.forEach(segment => {
                    let widthPercentage = (segment.amount / total) * 100;
                    let barSegment = document.createElement("div");
                    barSegment.classList.add("progress-bar");
                    barSegment.style.width = widthPercentage + "%";
                    barSegment.style.background = segment.color;
                    progressBar.appendChild(barSegment);
                });
            }

            // Function to update progress bars (with placeholder data - replace with real API call)
            // function updateProgressBars() {
            //     // These would ideally come from your backend API
            //     const receivablesTotal = 12562523.00;
            //     const receivablesData = [{
            //         amount: 4526110.23,
            //         color: "linear-gradient(to right, #004d00, #008000)"
            //     }, {
            //         amount: 8036412.77,
            //         color: "linear-gradient(to right, #ff0000, #ff6600)"
            //     }];

            //     const payablesTotal = 5203220.00;
            //     const payablesData = [{
            //         amount: 4526110.23,
            //         color: "linear-gradient(to right, #000066, #0000ff)"
            //     }, {
            //         amount: 677109.77,
            //         color: "linear-gradient(to right, #ff0000, #ff6600)"
            //     }];

            //     generateProgressBar(receivablesData, receivablesTotal, "receivablesProgress");
            //     generateProgressBar(payablesData, payablesTotal, "payablesProgress");
            // }

            function updateProgressBars(totalReceivables, totalPaid, totalUnpaid) {

                const totalReceivablesS = parseFloat(totalReceivables.replace('PHP ', '').replace(/,/g, ''));
                const totalPaidS = parseFloat(totalPaid.replace('PHP ', '').replace(/,/g, ''));
                const totalUnpaidS = parseFloat(totalUnpaid.replace('PHP ', '').replace(/,/g, ''));


                console.log(`Total Receivables: ${totalReceivablesS}`);
                console.log(`Total Paid: ${totalPaidS}`);
                console.log(`Total Unpaid: ${totalUnpaidS}`);

                const receivablesData = [{
                    amount: totalPaidS,
                    color: "linear-gradient(to right, #0000ff, #0000cc)"
                }, {
                    amount: totalUnpaidS,
                    color: "linear-gradient(to right, #ff0000, #ff6600)"
                }];

                generateProgressBar(receivablesData, totalReceivablesS, "receivablesProgress");

            }

            function updateProgressBars_payables(formattedTotalPayables, formattedTotalPaidPayables, formattedTotalUnpaidPayables) {

                const totalPayablesS = parseFloat(formattedTotalPayables.replace('PHP ', '').replace(/,/g, ''));
                const totalPaidPayablesS = parseFloat(formattedTotalPaidPayables.replace('PHP ', '').replace(/,/g, ''));
                const totalUnpaidPayablesS = parseFloat(formattedTotalUnpaidPayables.replace('PHP ', '').replace(/,/g, ''));


                console.log(`Total Receivables: ${totalPayablesS}`);
                console.log(`Total Paid: ${totalPaidPayablesS}`);
                console.log(`Total Unpaid: ${totalUnpaidPayablesS}`);

                const payaablesData = [{
                    amount: totalPaidPayablesS,
                    color: "linear-gradient(to right, #004d00, #008000)"
                }, {
                    amount: totalUnpaidPayablesS,
                    color: "linear-gradient(to right, #ff0000, #ff6600)"
                }];

                generateProgressBar_payables(payaablesData, totalPayablesS, "payablesProgress");

            }

            // // Initialize on page load
            // $(document).ready(function() {
            //     // Load initial data
            //     loadcashflow_fiscal();

            //     // Add change event listener for fiscal year dropdown
            //     $('#cashflow_fiscal_year').change(function() {
            //         loadcashflow_fiscal();
            //     });
            // });

            var income_expenses_fiscal_year_options = $('#income_expenses_fiscal_year option');
            var income_expenses_fiscal_year = [];
            income_expenses_fiscal_year_options.each(function() {
                let description = $(this).data('description') || $(this).text().trim();
                income_expenses_fiscal_year.push({
                    value: $(this).val(),
                    description: description
                });
            });

            console.log('income_expenses_fiscal_year:', income_expenses_fiscal_year);
            var schoolYearsDatas = JSON.parse($('#income_expenses_school_year_data').attr('data-school-years'));
            console.log('schoolYearsDatas:', schoolYearsDatas);

            // Find the first fiscal year that matches the START of a school year
            var matchedYears = null;
            for (const fiscalYear of income_expenses_fiscal_year) {
                const yearToMatch = fiscalYear.description.toString();

                matchedYears = schoolYearsDatas.find(item => {
                    // Extract the start year from "2024-2025" (first 4 chars)
                    const schoolYearStart = item.sydesc.split('-')[0];
                    return schoolYearStart === yearToMatch;
                });

                if (matchedYears) break; // Stop at first match
            }

            console.log('matchedYears:', matchedYears);

            if (matchedYears) {
                console.log('Matched year:', matchedYears.sydesc, 'ID:', matchedYears.id);
                $('#income_expenses_school_year_data').text(matchedYears.id);
            } else {
                console.log('No matched year');
                $('#income_expenses_school_year_data').text('No matching school year found');
            }
            var selectedschoolyear_income_expenses = $('#income_expenses_school_year_data').text();
            console.log('Selected School Year Income Expenses:', selectedschoolyear_income_expenses);

            var selectedfiscalyear_income_expenses = $('#income_expenses_fiscal_year').val();
            console.log('Selected Fiscal Year Income Expenses:', selectedschoolyear_income_expenses);

            var selectedfiscalyear_income_expenses_val = $('#income_expenses_fiscal_year').val();
            var selectedFiscalYearOption = $(
                `#income_expenses_fiscal_year option[value="${selectedfiscalyear_income_expenses_val}"]`);
            var selectedFiscalYearText = selectedFiscalYearOption.data('description') ||
                selectedFiscalYearOption.text().trim();

            console.log('Selected Fiscal Year Income Expenses Description:', selectedFiscalYearText);

            // filterincome_expenses()

            // function filterincome_expenses() {
            //     $.ajax({
            //         url: '{{ route('filter_acctreceivable_income_expenses') }}',
            //         type: 'GET',
            //         data: {
            //             selectedschoolyear_income_expenses,
            //             selectedfiscalyear_income_expenses,
            //             selecteddaterange,
            //             selecteddepartment,
            //             selectedgradelevel,
            //             selectedsemester,
            //             selectedsection,
            //             selectedgrantee,
            //             selectedmode
            //         },
            //         success: function(data) {

            //         }
            //     });
            // }
            /////////////////////////////////////////////
            // $('#income_expenses_fiscal_year').on('change', function() {
            //     // Get the selected fiscal year value and text
            //     var selectedFiscalYear = $(this).val();
            //     var selectedFiscalYearText = $(this).find('option:selected').text().trim();

            //     // Get school years data from the hidden element
            //     var schoolYearsDatas = JSON.parse($('#income_expenses_school_year_data').attr(
            //         'data-school-years'));
            //     console.log('Available School Years:', schoolYearsDatas);

            //     // Find the school year where the start year matches the selected fiscal year
            //     var matchedSchoolYear = schoolYearsDatas.find(item => {
            //         // Extract the start year from "2024-2025" format (first 4 characters)
            //         const schoolYearStart = item.sydesc.split('-')[0].trim();
            //         return schoolYearStart === selectedFiscalYearText;
            //     });

            //     console.log('Matched School Year:', matchedSchoolYear);

            //     if (matchedSchoolYear) {
            //         console.log('Successfully matched:', selectedFiscalYearText, 'with school year:',
            //             matchedSchoolYear.sydesc);
            //         $('#income_expenses_school_year_data').text(matchedSchoolYear.id);

            //         // Get the final values to pass to the filter function
            //         var schoolYearId = matchedSchoolYear.id;
            //         var fiscalYearValue = selectedFiscalYear;

            //         // Call the filter function
            //         filterincome_expenses(schoolYearId, fiscalYearValue);
            //     } else {
            //         console.log('No matching school year found for fiscal year:', selectedFiscalYearText);
            //         $('#income_expenses_school_year_data').text('No match found');
            //         // You might want to handle the no-match case differently
            //     }
            // });
            // $('#income_expenses_fiscal_year').on('change', function() {
            //     const selectedFiscalYear = $(this).val();
            //     const selectedFiscalYearOption = $(
            //         `#income_expenses_fiscal_year option[value="${selectedFiscalYear}"]`);
            //     const selectedFiscalYearText = selectedFiscalYearOption.data('description') ||
            //         selectedFiscalYearOption.text().trim();
            //     console.log('Selected Fiscal Year:', selectedFiscalYearText);

            //     // Get school years data from the hidden element
            //     const schoolYearsDatas = JSON.parse($('#income_expenses_school_year_data').attr(
            //         'data-school-years'));
            //     console.log('Available School Years:', schoolYearsDatas);

            //     // Find the school year where the start year matches the selected fiscal year
            //     const matchedSchoolYear = schoolYearsDatas.find(item => {
            //         // Ensure selectedFiscalYearText is a string
            //         const fiscalYearText = String(selectedFiscalYearText || '');
            //         // Extract the start year from "2024-2025" format (first 4 characters)
            //         const schoolYearStart = String(item.sydesc || '').split('-')[0].trim();
            //         // Extract just the year part from the fiscal year text
            //         const fiscalYearMatch = fiscalYearText.match(/\d{4}/);
            //         const fiscalYear = fiscalYearMatch ? fiscalYearMatch[0] : null;

            //         return fiscalYear && schoolYearStart === fiscalYear;
            //     });

            //     console.log('Matched School Year:', matchedSchoolYear);

            //     if (matchedSchoolYear) {
            //         console.log('Successfully matched:', selectedFiscalYearText, 'with school year:',
            //             matchedSchoolYear.sydesc);
            //         $('#income_expenses_school_year_data').text(matchedSchoolYear.id);

            //         // Get the final values to pass to the filter function
            //         const schoolYearId = matchedSchoolYear.id;
            //         const fiscalYearValue = selectedFiscalYear;

            //         // Call the filter function
            //         filterincome_expenses(schoolYearId, fiscalYearValue);
            //     } else {
            //         console.log('No matching school year found for fiscal year:', selectedFiscalYearText);
            //         $('#income_expenses_school_year_data').text('No match found');

            //         // Clear the chart by passing empty or null values
            //         filterincome_expenses(null, null);
            //         // Alternatively, if you have direct access to the chart:
            //         // incomeExpensesChart.data.datasets.forEach((dataset) => {
            //         //     dataset.data = [];
            //         // });
            //         // incomeExpensesChart.update();
            //     }
            // });
            $('#income_expenses_fiscal_year').on('change', function() {
                const selectedFiscalYear = $(this).val();
                const selectedFiscalYearOption = $(
                    `#income_expenses_fiscal_year option[value="${selectedFiscalYear}"]`);
                const selectedFiscalYearText = selectedFiscalYearOption.data('description') ||
                    selectedFiscalYearOption.text().trim();

                // Get school years data from the hidden element
                const schoolYearsDatas = JSON.parse($('#income_expenses_school_year_data').attr(
                    'data-school-years'));

                // Find the school year where the start year matches the selected fiscal year
                const matchedSchoolYear = schoolYearsDatas.find(item => {
                    const fiscalYearText = String(selectedFiscalYearText || '');
                    const schoolYearStart = String(item.sydesc || '').split('-')[0].trim();
                    const fiscalYearMatch = fiscalYearText.match(/\d{4}/);
                    const fiscalYear = fiscalYearMatch ? fiscalYearMatch[0] : null;
                    return fiscalYear && schoolYearStart === fiscalYear;
                });

                if (matchedSchoolYear) {
                    $('#income_expenses_school_year_data').text(matchedSchoolYear.id);
                    filterincome_expenses(matchedSchoolYear.id, selectedFiscalYear);
                } else {
                    // Clear the chart when no match found
                    incomeExpensesChart.data.labels = [];
                    incomeExpensesChart.data.datasets[0].data = [];
                    if (incomeExpensesChart.data.datasets[1]) {
                        incomeExpensesChart.data.datasets[1].data = [];
                    }
                    incomeExpensesChart.update();
                    $('#total_income').text('PHP 0');
                    $('#total_expenses').text('PHP 0');
                }
            });
            ////////////////////////////////////////////



            //working jud ni siya
            // function filterincome_expenses(selectedschoolyear_income_expenses,
            //     selectedfiscalyear_income_expenses) {
            //     $.ajax({
            //         url: '{{ route('filter_acctreceivable_income_expenses') }}',
            //         type: 'GET',
            //         data: {
            //             selectedschoolyear_income_expenses,
            //             selectedfiscalyear_income_expenses,
            //             selecteddaterange,
            //             selecteddepartment,
            //             selectedgradelevel,
            //             selectedsemester,
            //             selectedsection,
            //             selectedgrantee,
            //             selectedmode
            //         },
            //         success: function(data) {
            //             $('#total_income').text('PHP ' + data.overalltotalpayment
            //                 .toLocaleString());

            //             // Process the monthlyPayments object into chart-friendly format
            //             const monthsOrder = ['January', 'February', 'March', 'April', 'May', 'June',
            //                 'July', 'August', 'September', 'October', 'November', 'December'
            //             ];

            //             // Prepare labels and data
            //             const labels = [];
            //             const incomeData = [];

            //             monthsOrder.forEach(month => {
            //                 if (data.monthlyPayments && data.monthlyPayments[month] !==
            //                     undefined) {
            //                     labels.push(month);
            //                     // Remove commas and convert to number
            //                     const value = parseFloat(data.monthlyPayments[month].replace(
            //                         /,/g, ''));
            //                     incomeData.push(value);
            //                 }
            //             });

            //             // Update chart data
            //             incomeExpensesChart.data.labels = labels;
            //             incomeExpensesChart.data.datasets[0].data = incomeData;

            //             // If you want to keep expenses empty
            //             if (incomeExpensesChart.data.datasets[1]) {
            //                 incomeExpensesChart.data.datasets[1].data = new Array(incomeData.length)
            //                     .fill(0);
            //             }

            //             incomeExpensesChart.update();
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("Error fetching data:", error);
            //         }
            //     });
            // }

            /////////////working jud ni/////////////////
            // function filterincome_expenses(selectedschoolyear_income_expenses, selectedfiscalyear_income_expenses) {
            //     $.ajax({
            //         url: '{{ route('filter_acctreceivable_income_expenses') }}',
            //         type: 'GET',
            //         data: {
            //             selectedschoolyear_income_expenses,
            //             selectedfiscalyear_income_expenses,
            //             selectedFiscalYearText,
            //             selecteddaterange,
            //             selecteddepartment,
            //             selectedgradelevel,
            //             selectedsemester,
            //             selectedsection,
            //             selectedgrantee,
            //             selectedmode
            //         },
            //         success: function(data) {
            //             if (data.error) {
            //                 // Clear the chart when fiscal year not found
            //                 incomeExpensesChart.data.labels = [];
            //                 incomeExpensesChart.data.datasets[0].data = [];
            //                 if (incomeExpensesChart.data.datasets[1]) {
            //                     incomeExpensesChart.data.datasets[1].data = [];
            //                 }
            //                 incomeExpensesChart.update();
            //                 $('#total_income').text('PHP 0.00');
            //                 return;
            //             }

            //             $('#total_income').text('PHP ' + data.overalltotalpayment.toLocaleString());

            //             // Process the monthlyPayments object into chart-friendly format
            //             const monthsOrder = ['January', 'February', 'March', 'April', 'May', 'June',
            //                 'July', 'August', 'September', 'October', 'November', 'December'
            //             ];

            //             // Prepare labels and data
            //             const labels = [];
            //             const incomeData = [];

            //             monthsOrder.forEach(month => {
            //                 if (data.monthlyPayments && data.monthlyPayments[month] !==
            //                     undefined) {
            //                     labels.push(month);
            //                     // Remove commas and convert to number
            //                     const value = parseFloat(data.monthlyPayments[month].replace(
            //                         /,/g, ''));
            //                     incomeData.push(value);
            //                 }
            //             });

            //             // Update chart data
            //             incomeExpensesChart.data.labels = labels;
            //             incomeExpensesChart.data.datasets[0].data = incomeData;

            //             // If you want to keep expenses empty
            //             if (incomeExpensesChart.data.datasets[1]) {
            //                 incomeExpensesChart.data.datasets[1].data = new Array(incomeData.length)
            //                     .fill(0);
            //             }

            //             incomeExpensesChart.update();
            //         },
            //         error: function(xhr, status, error) {
            //             console.error("Error fetching data:", error);
            //             // Clear the chart on any error
            //             incomeExpensesChart.data.labels = [];
            //             incomeExpensesChart.data.datasets[0].data = [];
            //             if (incomeExpensesChart.data.datasets[1]) {
            //                 incomeExpensesChart.data.datasets[1].data = [];
            //             }
            //             incomeExpensesChart.update();
            //             $('#total_income').text('PHP 0.00');
            //         }
            //     });
            // }

            // // Initialize charts
            // var ctxIncomeExpenses = document.getElementById("incomeExpensesChart").getContext("2d");
            // var incomeExpensesChart = new Chart(ctxIncomeExpenses, {
            //     type: "bar",
            //     data: {
            //         labels: [], // Will be populated dynamically
            //         datasets: [{
            //                 label: "Income",
            //                 data: [], // Will be populated via AJAX
            //                 backgroundColor: "rgba(0, 128, 0, 0.7)"
            //             },
            //             {
            //                 label: "Expenses",
            //                 data: [], // Will remain empty or be zeroed out
            //                 backgroundColor: "rgba(139,69,19, 0.7)"
            //             }
            //         ]
            //     },
            //     options: {
            //         responsive: true,
            //         scales: {
            //             yAxes: [{
            //                 ticks: {
            //                     beginAtZero: true,
            //                     callback: function(value) {
            //                         return "PHP " + value.toLocaleString();
            //                     }
            //                 }
            //             }]
            //         }
            //     }
            // });
            //////////////working jud ni ////////////////////

            function filterincome_expenses(selectedschoolyear_income_expenses, selectedfiscalyear_income_expenses) {
                $.ajax({
                    url: '{{ route('filter_acctreceivable_income_expenses') }}',
                    type: 'GET',
                    data: {
                        selectedschoolyear_income_expenses,
                        selectedfiscalyear_income_expenses,
                        selectedFiscalYearText,
                        selecteddaterange,
                        selecteddepartment,
                        selectedgradelevel,
                        selectedsemester,
                        selectedsection,
                        selectedgrantee,
                        selectedmode
                    },
                    success: function(data) {
                        if (data.error) {
                            // Clear the chart when fiscal year not found
                            incomeExpensesChart.data.labels = [];
                            incomeExpensesChart.data.datasets[0].data = [];
                            incomeExpensesChart.data.datasets[1].data = [];
                            incomeExpensesChart.update();
                            $('#total_income').text('PHP 0.00');
                            $('#total_expenses').text('PHP 0.00');
                            return;
                        }

                        $('#total_income').text('PHP ' + parseFloat(data.overalltotalpayment)
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }));

                        $('#total_expenses').text('PHP ' + (data.overallmonthlydisbursement || 0)
                            .toLocaleString('en-US', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2,
                                useGrouping: true
                            }));

                        // Process the monthlyPayments and monthlyDisbursements objects into chart-friendly format
                        const monthsOrder = ['January', 'February', 'March', 'April', 'May', 'June',
                            'July', 'August', 'September', 'October', 'November', 'December'
                        ];

                        // Prepare labels and data
                        const labels = [];
                        const incomeData = [];
                        const expensesData = [];

                        monthsOrder.forEach(month => {
                            if ((data.monthlyPayments && data.monthlyPayments[month] !==
                                    undefined) ||
                                (data.monthlyDisbursements && data.monthlyDisbursements[
                                    month] !== undefined)) {

                                labels.push(month);

                                // Process Income
                                const incomeValue = data.monthlyPayments && data
                                    .monthlyPayments[month] ?
                                    parseFloat(data.monthlyPayments[month].replace(/,/g, '')) :
                                    0;
                                incomeData.push(incomeValue);

                                // Process Expenses
                                const expenseValue = data.monthlyDisbursements && data
                                    .monthlyDisbursements[month] ?
                                    parseFloat(data.monthlyDisbursements[month].replace(/,/g,
                                        '')) : 0;
                                expensesData.push(expenseValue);
                            }
                        });

                        // Update chart data
                        incomeExpensesChart.data.labels = labels;
                        incomeExpensesChart.data.datasets[0].data = incomeData;
                        incomeExpensesChart.data.datasets[1].data = expensesData;

                        incomeExpensesChart.update();
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data:", error);
                        // Clear the chart on any error
                        incomeExpensesChart.data.labels = [];
                        incomeExpensesChart.data.datasets[0].data = [];
                        incomeExpensesChart.data.datasets[1].data = [];
                        incomeExpensesChart.update();
                        $('#total_income').text('PHP 0.00');
                        $('#total_expenses').text('PHP 0.00');
                    }
                });
            }

            // Initialize charts
            var ctxIncomeExpenses = document.getElementById("incomeExpensesChart").getContext("2d");
            var incomeExpensesChart = new Chart(ctxIncomeExpenses, {
                type: "bar",
                data: {
                    labels: [], // Will be populated dynamically
                    datasets: [{
                            label: "Income",
                            data: [], // Will be populated via AJAX
                            backgroundColor: "rgba(0, 128, 0, 0.7)"
                        },
                        {
                            label: "Expenses",
                            data: [], // Will be populated with disbursements data
                            backgroundColor: "rgba(139, 69, 19, 0.7)"
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                callback: function(value) {
                                    return "PHP " + value.toLocaleString();
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                var label = data.datasets[tooltipItem.datasetIndex].label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'PHP ' + tooltipItem.yLabel.toLocaleString('en-US', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                                return label;
                            }
                        }
                    }
                }
            });


            // function filter_top_expenses(selectedschoolyear_income_expenses, selectedfiscalyear_income_expenses) {
            //     $.ajax({
            //         url: '{{ route('filter_top_expenses') }}',
            //         type: 'GET',
            //         data: {
            //             selectedschoolyear_income_expenses,
            //             selectedfiscalyear_income_expenses,
            //             selectedFiscalYearText,
            //         },
            //         success: function(data) {
            //             // Extract labels and data from the response
            //             var labels = data.result.map(item => item.account_name);
            //             var amounts = data.result.map(item => parseFloat(item.total_amount));

            //             // Update the chart with new data
            //             topExpensesChart.data.labels = labels;
            //             topExpensesChart.data.datasets[0].data = amounts;
            //             topExpensesChart.update();

            //             // Update the top 3 expenses
            //             var top3 = data.result.slice(0, 3);
            //             $('#top1').css('background-color', top3[0] ? '#145A32' : '');
            //             $('#top2').css('background-color', top3[1] ? '#8B4513' : '');
            //             $('#top3').css('background-color', top3[2] ? '#B8860B' : '');

            //             $('#top1').next('span').text(top3[0] ?
            //                 `${top3[0].account_name} - PHP ${top3[0].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` :
            //                 '');
            //             $('#top2').next('span').text(top3[1] ?
            //                 `${top3[1].account_name} - PHP ${top3[1].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` :
            //                 '');
            //             $('#top3').next('span').text(top3[2] ?
            //                 `${top3[2].account_name} - PHP ${top3[2].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}` :
            //                 '');
            //         }
            //     });
            // }
            /////////////////////////////////////////////////
            // $('#top_expenses_fiscal_year').change(function() {
            //     var fiscal_year = $(this).val();
            //     filter_top_expenses(fiscal_year);
            // });


            // function filter_top_expenses(selectedfiscalyear_income_expenses) {
            //     $.ajax({
            //         url: '{{ route('filter_top_expenses') }}',
            //         type: 'GET',
            //         data: {
            //             selectedfiscalyear_income_expenses,
            //             selectedFiscalYearText,
            //         },
            //         success: function(data) {
            //             // Extract labels and data from the response
            //             var labels = data.result.map(item => item.account_name);
            //             var amounts = data.result.map(item => parseFloat(item.total_amount));

            //             // Update the chart with new data
            //             topExpensesChart.data.labels = labels;
            //             topExpensesChart.data.datasets[0].data = amounts;
            //             topExpensesChart.update();

            //             // Update the top 3 expenses
            //             var top3 = data.result.slice(0, 3);

            //             // Update colors and text for each top expense
            //             $('#top1').css('background-color', top3[0] ? '#145A32' : '');
            //             $('#top2').css('background-color', top3[1] ? '#8B4513' : '');
            //             $('#top3').css('background-color', top3[2] ? '#B8860B' : '');

            //             // Update the text using the specific span IDs we added
            //             // $('#top1-text').text(top3[0] ?
            //             //     `${top3[0].account_name} - PHP ${top3[0].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}` :
            //             //     '');
            //             // $('#top2-text').text(top3[1] ?
            //             //     `${top3[1].account_name} - PHP ${top3[1].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}` :
            //             //     '');
            //             // $('#top3-text').text(top3[2] ?
            //             //     `${top3[2].account_name} - PHP ${top3[2].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}` :
            //             //     '');

            //             $('#top1-text').html(top3[0] ?
            //                 `<b>${top3[0].account_name}</b> - PHP <b>${top3[0].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</b>` :
            //                 '');
            //             $('#top2-text').html(top3[1] ?
            //                 `<b>${top3[1].account_name}</b> - PHP <b>${top3[1].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</b>` :
            //                 '');
            //             $('#top3-text').html(top3[2] ?
            //                 `<b>${top3[2].account_name}</b> - PHP <b>${top3[2].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</b>` :
            //                 '');
            //         }
            //     });
            // }

            // // Initialize the chart with empty data first
            // var ctxTopExpenses = document.getElementById("topExpensesChart").getContext("2d");
            // var topExpensesChart = new Chart(ctxTopExpenses, {
            //     type: "doughnut",
            //     data: {
            //         labels: [], // Will be populated by AJAX response
            //         datasets: [{
            //             data: [], // Will be populated by AJAX response
            //             backgroundColor: ["#008000", "#8B4513", "#DAA520"]
            //         }]
            //     },
            //     options: {
            //         responsive: true,
            //         legend: {
            //             display: false
            //         }
            //     }
            // });

            // // Call the function to load data
            // filter_top_expenses(selectedfiscalyear_income_expenses);
            ///////////////////////////////////////////////////

            $('#top_expenses_fiscal_year').change(function() {
                var fiscal_year = $(this).val();
                filter_top_expenses(fiscal_year);
            });


            function filter_top_expenses(selectedfiscalyear_income_expenses) {
                $.ajax({
                    url: '{{ route('filter_top_expenses') }}',
                    type: 'GET',
                    data: {
                        selectedfiscalyear_income_expenses,
                        selectedFiscalYearText,
                    },
                    success: function(data) {
                        //
                        if (!data.result || data.result.length === 0) {
                            // No data case
                            topExpensesChart.data.labels = ['No Data'];
                            topExpensesChart.data.datasets[0].data = [1];
                            topExpensesChart.data.datasets[0].backgroundColor = [
                                '#d3d3d3'
                            ]; // Grey color for no data
                            topExpensesChart.update();
                            $('#top1-text, #top2-text, #top3-text').html('');
                            return;
                        }

                        // Extract labels and data from the response
                        var labels = data.result.map(item => item.account_name);
                        var amounts = data.result.map(item => parseFloat(item.total_amount));

                        // Update the chart with new data
                        topExpensesChart.data.labels = labels;
                        topExpensesChart.data.datasets[0].data = amounts;
                        //
                        topExpensesChart.data.datasets[0].backgroundColor = ["#008000", "#8B4513",
                            "#DAA520"
                        ];
                        topExpensesChart.update();

                        // Update the top 3 expenses
                        var top3 = data.result.slice(0, 3);

                        // Update colors and text for each top expense
                        $('#top1').css('background-color', top3[0] ? '#145A32' : '');
                        $('#top2').css('background-color', top3[1] ? '#8B4513' : '');
                        $('#top3').css('background-color', top3[2] ? '#B8860B' : '');

                        $('#top1-text').html(top3[0] ?
                            `<b>${top3[0].account_name}</b> - PHP <b>${top3[0].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</b>` :
                            '');
                        $('#top2-text').html(top3[1] ?
                            `<b>${top3[1].account_name}</b> - PHP <b>${top3[1].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</b>` :
                            '');
                        $('#top3-text').html(top3[2] ?
                            `<b>${top3[2].account_name}</b> - PHP <b>${top3[2].total_amount.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}</b>` :
                            '');
                    }
                });
            }

            // Initialize the chart with empty data first
            var ctxTopExpenses = document.getElementById("topExpensesChart").getContext("2d");
            var topExpensesChart = new Chart(ctxTopExpenses, {
                type: "doughnut",
                data: {
                    labels: [], // Will be populated by AJAX response
                    datasets: [{
                        data: [], // Will be populated by AJAX response
                        backgroundColor: ["#008000", "#8B4513", "#DAA520"]
                    }]
                },
                options: {
                    responsive: true,
                    legend: {
                        display: false
                    }
                }
            });

            // Call the function to load data
            filter_top_expenses(selectedfiscalyear_income_expenses);


            // Initial load
            filterincome_expenses(selectedschoolyear_income_expenses,
                selectedfiscalyear_income_expenses);




        });

        // $(document).ready(function() {
        //     console.log('selected fiscal year:', $('#selectedfiscalyear').text());

        //     console.log('school_year:', $('#school_year').text());



        //     // if (school_year.some(year => year.startsWith(fiscal_year))) {
        //     //     console.log('Matched year');
        //     // } else {
        //     //     console.log('No matched year');
        //     // }
        //     var fiscal_year = $('#selectedfiscalyear').text().trim();
        //     var school_year = $('#school_year').text().trim().split(', ');

        //     var matching_year = school_year.find(year => year.startsWith(fiscal_year));
        //     if (matching_year) {
        //         console.log('Matched year:', matching_year);
        //     } else {
        //         console.log('No matched year');
        //     }
        //     // var loadingtext = 'Getting ready...';
        //     // var selectedschoolyear = $('#selectedschoolyear').val();
        //     // var selecteddaterange = $('#selecteddaterange').val() || null;
        //     // var selecteddepartment = $('#selecteddepartment').val() || null;
        //     // var selectedgradelevel = $('#selectedgradelevel').val() || null;
        //     // var selectedsemester = $('#selectedsemester').val();
        //     // var selectedsection = $('#selectedsection').val() || null;
        //     // var selectedgrantee = $('#selectedgrantee').val() || null;
        //     // var selectedmode = $('#selectedmode').val() || null;

        //     // function filterstudents() {
        //     //     Swal.fire({
        //     //         title: loadingtext,
        //     //         onBeforeOpen: () => {
        //     //             Swal.showLoading();
        //     //         },
        //     //         allowOutsideClick: false
        //     //     });

        //     //     $.ajax({
        //     //         url: '{{ route('acctreceivablefilter') }}',
        //     //         type: 'GET',
        //     //         data: {
        //     //             selectedschoolyear,
        //     //             selecteddaterange,
        //     //             selecteddepartment,
        //     //             selectedgradelevel,
        //     //             selectedsemester,
        //     //             selectedsection,
        //     //             selectedgrantee,
        //     //             selectedmode
        //     //         },
        //     //         success: function(data) {
        //     //             $('#resultscontainer').html(data);
        //     //             $(".swal2-container").remove();
        //     //             $('body').removeClass('swal2-shown swal2-height-auto');
        //     //         }
        //     //     });
        //     // }

        //     // filterstudents();

        // });
    </script>
@endsection
