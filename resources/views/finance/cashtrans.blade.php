@extends('finance.layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <!-- <h1>Finance</h1> -->

                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">PAYMENT TRANSACTIONS</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <section class="content pt-0">
        <div class="main-card card">
            <div class="card-header bg-info">
                <div class="row">
                    <div class="text-lg col-md-4">
                        <!-- Fees and Collection     -->
                        <h4 class="text-warning" style="text-shadow: 1px 1px 1px #000000">
                            <!-- <i class="fa fa-chart-line nav-icon"></i>  -->
                            <b>PAYMENT TRANSACTIONS</b>
                        </h4>
                    </div>
                    <div class="col-md-4"></div>
                    <div class="col-md-4">

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <label for="">Terminal</label>
                            <select id="cboterminal" class="form-control filter-control" data-toggle="tooltip"
                                title="Terminal No.">
                                <option value="0">All</option>
                                {{ $terminals = DB::table('chrngterminals')->get() }}
                                @foreach ($terminals as $terminal)
                                    <option value="{{ $terminal->id }}">{{ $terminal->description }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="">Select Date Range</label>
                        <input type="text" class="form-control filter-control" id="selecteddaterange"
                            value="{{ date('m/d/Y') . ' - ' . date('m/d/Y') }}" placeholder="Select date range">
                    </div>
                    {{-- <div class="col-md-2">
                        <div class="form-group mb-3">
                            <input id="datefrom" class="form-control filter-control" type="date" data-toggle="tooltip"
                                title="Date from" value="{{ date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group mb-3">
                            <input id="dateto" class="form-control filter-control" type="date" data-toggle="tooltip"
                                title="Date to" value="{{ date_format(App\FinanceModel::getServerDateTime(), 'Y-m-d') }}">
                        </div>
                    </div> --}}

                    <div class="col-3">
                        <label for="">Academic Program</label>
                        <select id="acadprog" class="select2 form-control">
                            <option value="0" selected>All</option> <!-- Set "All" as default -->

                            @foreach (DB::table('academicprogram')->get() as $acadprog)
                                <option value="{{ $acadprog->id }}">{{ $acadprog->progname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3">
                        <label for="">Gradelevel</label>
                        <select id="gradelevel" class="select2 form-control filters" style="width: 100%;">
                            <option value="0" selected>All</option> <!-- Set "All" as default -->

                            {{-- @foreach (DB::table('gradelevel')->where('deleted', 0)->orderBy('sortid')->get() as $level)
                            @if ($level->id > 21)
                                <option value="{{ $level->id }}">HE - {{ $level->levelname }}</option>
                            @else
                                <option value="{{ $level->id }}">{{ $level->levelname }}</option>
                            @endif
                        @endforeach --}}
                        </select>
                    </div>


                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="input-group mb-3">
                            <input id="filter" type="text" class="form-control filter-control" placeholder="Search"
                                data-toggle="tooltip" title="Search">
                            <div class="input-group-append">
                                <span id="btnsearch" class="input-group-text" data-toggle="tooltip" title="Search">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group">
                            <select id="ptype" class="select2bs4 form-control filter-control" multiple=""
                                data-placeholder="Select Payment Type">
                                @php
                                    $paymenttype = db::table('paymenttype')->where('deleted', 0)->get();
                                @endphp

                                @foreach ($paymenttype as $paytype)
                                    <option value="{{ $paytype->id }}">{{ $paytype->description }}</option>
                                @endforeach

                            </select>
                            {{-- <div class="input-group-append">
                                <span id="btnsearch" class="input-group-text" data-toggle="tooltip" title="Search">
                                    <i class="fas fa-search"></i>
                                </span>
                            </div> --}}
                            <div class="input-group-append">
                                <span id="btnprint" class="input-group-text" data-toggle="tooltip" title="Print">
                                    <i class="fas fa-print"></i>
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="col-md-3">
                        <select id="trans_mode" class="select2 form-control">
                            <option value="0" selected>Cashier Transactions</option>
                            <option value="1">Online Transactions</option>

                        </select>
                    </div>
                </div>

            </div>

            <div class="card-body table-responsive p-0" style="height:380px">
                <table width="100%" class="table table-striped" style="table-layout: fixed">
                    <thead class="bg-warning">
                        <tr>
                            <th width="5%"></th>
                            <th width="10%">DATE</th>
                            <th width="10%">OR NUMBER</th>
                            <th width="15%">NAME</th>
                            <th width="10%">LEVEL</th>
                            <th width="8%">AMOUNT</th>
                            <th width="10%">POSTED</th>
                            <th width="10%">CASHIER</th>
                            <th width="12%">PAYMENT TYPE</th>
                            <th width="10%"></th>
                        </tr>
                    </thead>
                    <tbody id="list" class="text-sm">

                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <div class="modal fade show" id="modal-viewtrans" aria-modal="true" style="padding-right: 17px; display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="modalhead" class="modal-header">
                    <h4 class="modal-title">OR: <span id="head-ornum" class="text-bold"></span> <span id="lblvoid"
                            class="text-bold"> - VOID</span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="row">
                                <div class="col-md-12">
                                    ID No.: <span id="lblidno" class="text-bold"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    NAME: <span id="lblstudname" class="text-bold"></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    GRADE|SECTION: <span id="lblgrade" class="text-bold"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12">
                                    Date Trans: <span class="text-bold" id="lbltransdate"></span>
                                </div>
                                <div id="cheque-details" class="col-md-12" style="display: none;">
                                    <div class="col-md-12">
                                        Bank Name: <span class="text-bold" id="lblbankname"></span>
                                    </div>
                                    <div class="col-md-12">
                                        Cheque No: <span class="text-bold" id="lblchequeno"></span>
                                    </div>
                                    <div class="col-md-12">
                                        Cheque Date: <span class="text-bold" id="lblchequedate"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th class="">PARTICULARS</th>
                                            <th class="text-center">AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody id="list-detail">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer justify-content-between">
                    {{-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button id="saveItem" type="button" class="btn btn-primary" data-dismiss="modal">Save</button> --}}
                </div>
            </div>
        </div> {{-- dialog --}}
    </div>
@endsection

@section('js')
    <style type="text/css">
        .cursor-pointer {
            cursor: pointer;

        }

        .Div-hide {
            display: none !important;
        }

        .Div-show {
            display: block;
        }
    </style>


    <script type="text/javascript">
        $(document).ready(function() {


            $('#selecteddaterange').daterangepicker({
                autoUpdateInput: true,
                locale: {
                    format: 'MM/DD/YYYY'
                }
            });

            $('.select2bs4').select2({
                theme: 'bootstrap4'
            });

            function loadGradeLevel(acadprogid) {
                $.ajax({
                    url: '{{ route('fc_gradelevel') }}',
                    type: 'GET',
                    data: {
                        acadprogid: acadprogid
                    },
                    success: function(data) {
                        var options =
                            '<option value="0" selected>All</option>'; // Ensure "All" is included

                        data.forEach(function(item) {
                            options += '<option value="' + item.id + '">' + item.levelname +
                                '</option>';
                        });

                        $('#gradelevel').html(options);
                        searchTrans();
                    }
                });
            }


            $(document).on('change', '#acadprog', function() {
                var acadprogid = $('#acadprog').val();
                loadGradeLevel(acadprogid);
            });



            $(function() {
                $('[data-toggle="tooltip"]').tooltip()
            });

            searchTrans();

            //worling v2 code
            // function searchTrans() {
            //     var daterange = $('#selecteddaterange').val();
            //     var dates = daterange.split(" - ");
            //     var dtfrom = formatDate(dates[0]);
            //     var dtto = formatDate(dates[1]);
            //     var acadprog = $('#acadprog').val()
            //     var gradelevel = $('#gradelevel').val()

            //     // var dtfrom = $('#datefrom').val();
            //     // var dtto = $('#dateto').val();
            //     var filter = $('#filter').val();
            //     var terminalno = $('#cboterminal').val();
            //     var paytype = $('#ptype').val();

            //     $.ajax({
            //         url: "{{ route('cashtranssearch') }}",
            //         method: 'GET',
            //         data: {
            //             dtfrom: dtfrom,
            //             dtto: dtto,
            //             filter: filter,
            //             terminalno: terminalno,
            //             paytype: paytype,
            //             acadprog: acadprog,
            //             gradelevel: gradelevel
            //         },
            //         dataType: 'json',
            //         success: function(data) {
            //             $('#list').html(data.list);
            //         }
            //     });
            // }

            function searchTrans() {
                var daterange = $('#selecteddaterange').val();
                var dates = daterange.split(" - ");
                var dtfrom = formatDate(dates[0]);
                var dtto = formatDate(dates[1]);
                var acadprog = $('#acadprog').val()
                var gradelevel = $('#gradelevel').val()
                var trans_mode = $('#trans_mode').val()

                // var dtfrom = $('#datefrom').val();
                // var dtto = $('#dateto').val();
                var filter = $('#filter').val();
                var terminalno = $('#cboterminal').val();
                var paytype = $('#ptype').val();

                $.ajax({
                    url: "{{ route('cashtranssearch') }}",
                    method: 'GET',
                    data: {
                        dtfrom: dtfrom,
                        dtto: dtto,
                        filter: filter,
                        terminalno: terminalno,
                        paytype: paytype,
                        acadprog: acadprog,
                        gradelevel: gradelevel,
                        trans_mode: trans_mode
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#list').html(data.list);
                    }
                });
            }



            function formatDate(dateStr) {
                var parts = dateStr.split("/"); // Splits "MM/DD/YYYY"
                return parts[2] + "-" + parts[0].padStart(2, '0') + "-" + parts[1].padStart(2, '0'); // "YYYY-MM-DD"
            }

            $(document).on('click', '#btnsearch', function() {
                searchTrans();
            })

            $(document).on('change', '#gradelevel', function() {
                searchTrans();
            })
            $(document).on('change', '#trans_mode', function() {
                searchTrans();
            })

            $(document).on('change', '.filter-control', function() {
                console.log($('#ptype').val())
                searchTrans();
            });

            $(document).on('click', '.applyBtn', function() {
                console.log($('#ptype').val());
                console.log('masdas');
                searchTrans();
            });

            $(document).on('click', '#btnprint', function() {

                var daterange = $('#selecteddaterange').val();
                var dates = daterange.split(" - ");
                var dtfrom = formatDate(dates[0]);
                var dtto = formatDate(dates[1]);

                var paytype = $('#ptype').val();

                if (paytype == '') {
                    paytype = 0;
                }

                window.open('printcashtrans/' + $('#cboterminal').val() + '/' + dtfrom + '/' +
                    dtto + '/"' + $('#filter').val() + '"' + '/' + paytype +
                    '/ cashiertransactions', '_blank');
            });

            $(document).on('click', '.btn-view', function() {
                var transid = $(this).attr('data-id');

                $.ajax({
                    url: "{{ route('transviewdetail') }}",
                    method: 'GET',
                    data: {
                        transid: transid
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('#head-ornum').text(data.ornum);
                        $('#lblstudname').text(data.studname);
                        $('#lblgrade').text(data.gradelevel);
                        $('#lblidno').text(data.idno);
                        $('#list-detail').html(data.list);
                        $('#lbltransdate').text(data.transdate);

                        console.log('CANCELLED: ' + data.cancelled);
                        if (data.cancelled == 0) {
                            $('#modalhead').removeClass('bg-danger').addClass('bg-primary');
                            $('#lblvoid').hide();
                        } else {
                            $('#modalhead').removeClass('bg-primary').addClass('bg-danger');
                            $('#lblvoid').show();
                        }

                        if (data.bankname && data.chequeno && data.chequedate) {
                            $('#cheque-details').show();
                            $('#lblbankname').text(data.bankname);
                            $('#lblchequeno').text(data.chequeno);
                            $('#lblchequedate').text(data.chequedate);
                        } else {
                            $('#cheque-details').hide();
                            $('#lblchequeno').text('');
                            $('#lblchequedate').text('');
                        }

                        $('#modal-viewtrans').modal('show');
                    },
                    error: function() {
                        alert("Failed to fetch transaction details.");
                    }
                });
            });
            // $('#ptype')r
        });
    </script>
@endsection
