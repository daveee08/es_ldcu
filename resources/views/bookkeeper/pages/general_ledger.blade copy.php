@extends('bookkeeper.layouts.app')

@section('pagespecificscripts')

    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <style>
        #pagination_controls {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 5px;
            margin-top: 10px;
            font-family: Arial, sans-serif;
        }

        #pagination_controls button {
            padding: 4px 8px;
            border: 1px solid #ccc;
            background-color: #f2f2f2;
            color: #333;
            cursor: pointer;
            border-radius: 3px;
            transition: background-color 0.2s;
        }

        #pagination_controls button:hover:not(:disabled) {
            background-color: #ddd;
        }

        #pagination_controls button:disabled {
            background-color: #e0e0e0;
            cursor: not-allowed;
            color: #999;
        }

        #pagination_controls span {
            font-weight: bold;
            color: #555;
        }

        .loader {
            /* text-align: center; */
            --w: 10ch;
            font-weight: bold;
            font-family: monospace;
            font-size: 50px;
            letter-spacing: var(--w);
            width: var(--w);
            overflow: hidden;
            white-space: nowrap;
            text-shadow:
                calc(-1*var(--w)) 0,
                calc(-2*var(--w)) 0,
                calc(-3*var(--w)) 0,
                calc(-4*var(--w)) 0,
                calc(-5*var(--w)) 0,
                calc(-6*var(--w)) 0,
                calc(-7*var(--w)) 0,
                calc(-8*var(--w)) 0,
                calc(-9*var(--w)) 0;
            animation: l16 2s infinite, dots 2s linear infinite;

        }

        .loader:before {
            content: "Processing...";
        }

        @keyframes l16 {
            20% {
                text-shadow:
                    calc(-1*var(--w)) 0,
                    calc(-2*var(--w)) 0 red,
                    calc(-3*var(--w)) 0,
                    calc(-4*var(--w)) 0 #ffa516,
                    calc(-5*var(--w)) 0 #63fff4,
                    calc(-6*var(--w)) 0,
                    calc(-7*var(--w)) 0,
                    calc(-8*var(--w)) 0 green,
                    calc(-9*var(--w)) 0;
            }

            40% {
                text-shadow:
                    calc(-1*var(--w)) 0,
                    calc(-2*var(--w)) 0 red,
                    calc(-3*var(--w)) 0 #e945e9,
                    calc(-4*var(--w)) 0,
                    calc(-5*var(--w)) 0 green,
                    calc(-6*var(--w)) 0 orange,
                    calc(-7*var(--w)) 0,
                    calc(-8*var(--w)) 0 green,
                    calc(-9*var(--w)) 0;
            }

            60% {
                text-shadow:
                    calc(-1*var(--w)) 0 lightblue,
                    calc(-2*var(--w)) 0,
                    calc(-3*var(--w)) 0 #e945e9,
                    calc(-4*var(--w)) 0,
                    calc(-5*var(--w)) 0 green,
                    calc(-6*var(--w)) 0,
                    calc(-7*var(--w)) 0 yellow,
                    calc(-8*var(--w)) 0 #ffa516,
                    calc(-9*var(--w)) 0 red;
            }

            80% {
                text-shadow:
                    calc(-1*var(--w)) 0 lightblue,
                    calc(-2*var(--w)) 0 yellow,
                    calc(-3*var(--w)) 0 #63fff4,
                    calc(-4*var(--w)) 0 #ffa516,
                    calc(-5*var(--w)) 0 red,
                    calc(-6*var(--w)) 0,
                    calc(-7*var(--w)) 0 grey,
                    calc(-8*var(--w)) 0 #63fff4,
                    calc(-9*var(--w)) 0;
            }
        }

        .loader1holder {
            padding-top: 59px;
            padding-bottom: 44px;
            padding-left: 176px;
        }

        .loaderholder {
            padding-left: 42px;
        }

        .loader1 {
            --d: 28px;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            color: #25b09b;
            box-shadow:
                calc(1*var(--d)) calc(0*var(--d)) 0 0,
                calc(0.707*var(--d)) calc(0.707*var(--d)) 0 1px,
                calc(0*var(--d)) calc(1*var(--d)) 0 2px,
                calc(-0.707*var(--d)) calc(0.707*var(--d)) 0 3px,
                calc(-1*var(--d)) calc(0*var(--d)) 0 4px,
                calc(-0.707*var(--d)) calc(-0.707*var(--d))0 5px,
                calc(0*var(--d)) calc(-1*var(--d)) 0 6px;
            animation: l27 1s infinite steps(8);
        }

        @keyframes l27 {
            100% {
                transform: rotate(1turn)
            }
        }
    </style>
    </style>
@endsection

@section('content')
    @php
        $fiscal = DB::table('bk_fiscal_year')->where('deleted', 0)->get();
        $activeFiscalYear = DB::table('bk_fiscal_year')->where('isactive', 1)->where('deleted', 0)->first();
        $activeFiscalYearId = isset($activeFiscalYear) ? $activeFiscalYear->id : null;

        $coa = DB::table('chart_of_accounts')->get();
    @endphp
    <div class="container-fluid ml-3">
        <div>
            <div class="d-flex align-items-center">
                <i class="fas fa-landmark fa-2x mr-2 mt-3"></i>
                <div style="display: flex; align-items: center;" class="mt-3">
                    <h3 style="margin: 0;">General Ledger</h3>
                </div>
            </div>
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                            style="color:rgba(0,0,0,0.5);">Home</a></li>
                    <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">General Ledger
                    </li>
                </ol>
            </nav>
        </div>

        <div class="card p-3" style="border-radius: 5px; background-color:#f1f1f1; border:none; width: 98%;">
            <h6 style="font-size: 13px;"><i class="fas fa-filter"></i> Filter</h6>
            <div class="row" style="margin-left: 12px;">
                <div class="col-md-4">
                    <label style="font-size: 13px; font-weight: 500;">Date Range</label>
                    <div class="input-group" style="width: 100%;">
                        <div class="input-group" style="width: 100%;">
                            <input
                                style="font-size: 11px; height: 30px; border-radius: 5px; border:none; padding-right: 30px; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"
                                type="text" id="date_range" name="date_range" value=""
                                placeholder="mm/dd/yyyy - mm/dd/yyyy" class="form-control">
                            <div class="input-group-append">
                                <span class="input-group-text"
                                    style="background-color: white; border: none; margin-left: -30px;">
                                    <i class="far fa-calendar-alt"
                                        style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <label style="font-size: 13px; font-weight: 500;">Fiscal Year</label>
                    <select
                        style="width: 90%; height: 30px; font-size: 12.5px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"
                        class="form-control" id="fiscal_year_select">
                        <option value="">Select Fiscal Year</option>
                        @foreach ($fiscal as $item)
                            <option value="{{ $item->id }}"
                                data-stime="{{ \Carbon\Carbon::parse($item->stime)->format('m/d/Y') }}"
                                data-etime="{{ \Carbon\Carbon::parse($item->etime)->format('m/d/Y') }}"
                                @if ($item->id == $activeFiscalYearId) selected @endif>
                                {{ $item->description }}
                            </option>
                        @endforeach
                    </select>
                </div>


                {{-- <div class="col-md-3">
                    <label style="font-size: 13px; font-weight: 500;">Gradelevel</label>
                    <select
                        style="width: 90%; height: 30px; font-size: 12.5px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);"
                        class="form-control">
                        <option value="">Select Gradelevel</option>
                        @foreach ($fiscal as $item)
                            <option value="{{ $item->id }}">{{ $item->description }}</option>
                        @endforeach
                    </select>
                </div> --}}
            </div>
        </div>

        <div class="card mt-4" style="background-color: #f1f1f1; border:none; width: 98%;">
            <div class="card-header d-flex justify-content-between align-items-center" style="font-size: 12.5px;">
                <!-- Left Section: Buttons -->
                <div>
                    <button type="button" class="btn btn-success rounded mr-2" id="journey_entrybtn" 
                        style="background-color: #00581f; height: 30px; font-size: 12px; font-family: Arial, sans-serif; border:none;">
                        <i class="fas fa-plus-circle mr-1"></i> Add Journal Entry
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded" id="print_ledger"
                        style="height: 30px; font-size: 12px; font-family: Arial, sans-serif; border:none; background-color: #043b90; color: white; font-weight:  normal;">
                        <i class="fas fa-print mr-1"></i> PRINT
                    </button>
                    <button type="button" class="btn btn-outline-secondary rounded ml-2" id="sync_transaction"
                        style="height: 30px; font-size: 12px; font-family: Arial, sans-serif; border:none; background-color: #043b90; color: white; font-weight:  normal;">
                        <i class="fas fa-sync-alt"></i> SYNC
                    </button>
                </div>

                <!-- Right Section: Select & Search -->
                {{-- <div class="d-flex align-items-center ml-auto">
                    <!-- Search Input -->
                    <div class="input-group">
                        <input type="text" id="search_ledger" class="form-control" placeholder="Search"
                            style="font-size: 12px; height: 30px; width: 200px; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); border:none;">

                        <div class="input-group-append">
                            <span class="input-group-text"
                                style="background-color: white; border: none; margin-left: -30px;">
                                <i class="fas fa-search search_item"
                                    style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%);"></i>
                            </span>
                        </div>
                    </div>
                </div> --}}
            </div>
            {{-- <div class="card-body" style="border: 1px solid #7d7d7d;">
                <table id="general_ledger_table" class="table table-bordered table-sm"
                    style="width: 100%; border-collapse: collapse; table-layout: fixed;">
                    <thead style="font-size: 12px; background-color: #b9b9b9; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10% ">Voucher No.</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10%">Date</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 25%">Explanation</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10">Code</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 25%">Account </th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width:10%">Debit</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d;; width:10%">Credit</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 11px; font-style:" id="general_ledger">

                    </tbody>
                </table>
                <div id="pagination_controls" style="margin-top: 10px;"></div>

            </div> --}}
            <div class="card-body" style="border: 1px solid #7d7d7d;">
                <table id="general_ledger_table" class="table table-bordered table-sm" style="width: 100%; border-collapse: collapse;">
                    <thead style="font-size: 14px; background-color: #b9b9b9; position: sticky; top: 0; z-index: 10;">
                        <tr>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10%">Voucher No.</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10%">Date</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 25%">Explanation</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 10%">Code</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width: 25%">Account</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width:10%">Debit</th>
                            <th style="font-weight: 600; border: 1px solid #7d7d7d; width:10%">Credit</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 12px;"></tbody>
                    <tfoot>
                        <tr  style="font-weight: bold; background-color: #e8e8e8; position: sticky; bottom: 0; z-index: 3;" id="total_row">
                            <td colspan="5" style="border: 1px solid #7d7d7d; text-align: right;">Total</td>
                            <td style="border: 1px solid #7d7d7d; text-align: right;" id="total_debit"></td>
                            <td style="border: 1px solid #7d7d7d; text-align: right;" id="total_credit"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Journey Entrl Modal -->
    <div class="modal fade" id="journey_entryModal" tabindex="-1" role="dialog"
        aria-labelledby="journey_entryModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl p-3 mt-0" style="width: 70%; " role="document">
            <div class="modal-content " style="border-radius: 15px">
                <div class="modal-header" style="padding: 10px; background-color: #d9d9d9;">
                    <h5 class="modal-title ml-2" id="journey_entryModalLabel" style="font-size: 14px;">Add Account
                        Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-row">
                        <div class="col-md-3">
                            <label style="font-weight: normal; font-size: 12.5px;">Voucher No.</label>
                            <input type="text" class="form-control" value="JV - 0000" id="voucher_no"
                                style="height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px rgba(0, 0, 0, 0.42);">
                        </div>
                        <div class="col-md-3 ml-5">
                            <label style="font-weight: normal; font-size: 12.5px;">Date</label>
                            <input type="date" class="form-control" id="entrydate"
                                style="height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px rgba(0, 0, 0, 0.42);">
                        </div>
                    </div>

                    <div style="margin-top: 4%">
                        <button type="button" class="btn btn-success rounded" data-toggle="modal"
                            data-target="#addaccountsetupModal"
                            style="background-color: #00581f; height: 30px; font-size: 12.5px; font-family: Arial, sans-serif; border:none; ">
                            <i class="fas fa-plus-circle mr-1"></i> Add Account
                        </button>
                    </div>

                    <div id="account-entry-container">
                        {{-- < --}}
                    </div>

                    <div class="form-row mb-3">
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" placeholder="Total"
                                style=" width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42); text-align: right">
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" value="0.00"
                                style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                        </div>
                        <div class="form-group col-md-4">
                            <input type="text" class="form-control" value="0.00"
                                style="width : 85%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                        </div>
                    </div>

                    <div class="mb-0">
                        <label style="font-weight: normal; font-size: 13px;">Remarks / Explanation</label>
                    </div>
                    <div class="card mt-0"
                        style="border:none; width: 95%; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                        <textarea class="form-control" placeholder="Description Here" rows="3" id="remarks"
                            style=" font-size: 12px; border-radius: 5px; height: 100px; margin-top: 0px; border: none"></textarea>
                    </div>
                    <div class="modal-footer justify-content-end" style="margin-right: 3%">
                        <button type="submit" class="btn btn-success" id="btnSave"
                            style="background-color: #00581f; height: 30px; border: none; font-size: 12.5px;">
                            <i class="fas fa-save"></i> Save
                        </button>

                        <button type="button" class="btn btn-default" id="btnPost" hidden
                            style="height: 30px; border: none; font-size: 12.5px; background-color:#00978b; color:white;">
                            <i class="fas fa-thumbtack"></i> Post
                        </button>
                        <button type="button" class="btn btn-default" id="print"
                            style="height: 30px; border: none; font-size: 12.5px; background-color:#043b90; color:white"><i
                                class="fas fa-print"></i> Print</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
<script src="{{asset('plugins/select2/js/select2.full.min.js') }}"></script>
<script src="{{asset('plugins/datatables/jquery.dataTables.js') }}"></script>
<script src="{{asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
<script src="{{asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
<script src="{{asset('plugins/moment/moment.min.js') }}"></script>
    <script>
        $(document).ready(function() {

            $('#journey_entrybtn').on('click', function() {
                $('#journey_entryModal').modal('show');
            })


            $('input[name="date_filter"]').daterangepicker({
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                }
            });

            const $dateFilter = $('input[name="date_filter"]');

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            // $('input[name="date_filter"]').on('apply.daterangepicker', function(ev, picker) {
            //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
            //         'MM/DD/YYYY'));
            // });

            // $('input[name="date_filter"]').on('cancel.daterangepicker', function(ev, picker) {
            //     $(this).val('');
            // });

            $('.btn-success[data-target="#addaccountsetupModal"]').click(function() {
                let accountSelectId = 'account-select-po-disburse-add-' + Date.now();
                const newEntryHtml = `
                        <div class="form-row account-entry mt-4">
                            <div class="form-group col-md-4">
                                <label style="font-weight: 600; font-size: 13px;">Account</label>
                                <select class="form-control account-select" id="${accountSelectId}"
                                    style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                    <option value="">Select Account</option>
                                    @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                        @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                            <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{ $subcoa->sub_code }} - 
                                                {{ $subcoa->sub_account_name }}</option>
                                        @endforeach
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label style="font-weight: 600; font-size: 13px;">Debit Account</label>
                                <input type="text" class="form-control debit-input" value="0.00" required
                                    style="width: 90%; height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                            </div>
                            <div class="form-group col-md-4 d-flex align-items-center">
                                <div style="width: 100%;">
                                    <label style="font-weight: 600; font-size: 13px;">Credit Account</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control credit-input" value="0.00" required
                                            style="height: 30px; font-size: 12px; border-radius: 5px; border: none; box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42);">
                                        <div class="input-group-append">
                                            <button type="button" class="btn btn-sm ml-3 remove-account-entry" style="background-color: transparent;">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                $('#account-entry-container').append(newEntryHtml);
                setTimeout(function() {
                    $(`#${accountSelectId}`).select2({
                        placeholder: "Select Account",
                        allowClear: true,
                        theme: 'bootstrap4',
                        width: '100%'
                    }).on('select2:open', function(e) {
                        // Force high z-index for dropdown
                        $('.select2-container').css('z-index', 99999);
                        $('.select2-dropdown').css('z-index', 99999);
                    });
                }, 100);
            });

            // Remove entry
            $(document).on('click', '.remove-account-entry', function() {
                if ($('.account-entry').length > 1) {
                    $(this).closest('.account-entry').remove();
                    calculateTotals();
                }
            });

            // Listen to input changes
            $(document).on('input', '.debit-input, .credit-input', function() {
                calculateTotals();
            });

            // Calculate Totals
            function calculateTotals() {
                var debitTotal = 0;
                var creditTotal = 0;

                $('.account-entry').each(function() {
                    var debit = parseFloat($(this).find('.debit-input').val());
                    var credit = parseFloat($(this).find('.credit-input').val());

                    debitTotal += isNaN(debit) ? 0 : debit;
                    creditTotal += isNaN(credit) ? 0 : credit;
                });

                $('.form-row.mb-3 input').each(function(index) {
                    if (index === 0) {
                        $(this).val('Debit: ' + debitTotal.toFixed(2) + ' | Credit: ' + creditTotal.toFixed(
                            2));
                    } else if (index === 1) {
                        $(this).val(debitTotal.toFixed(2));
                    } else {
                        $(this).val(creditTotal.toFixed(2));
                    }
                });
            }


            $(document).on('click', '.remove-account-entry', function() {
                if ($('.account-entry').length > 1) {
                    $(this).closest('.account-entry').remove();
                }
            });

            $(document).on('input', '.debit-input', function() {
                if ($(this).val() !== '') {
                    $(this).closest('.account-entry').find('.credit-input').val('');
                }
            });

            $(document).on('input', '.credit-input', function() {
                if ($(this).val() !== '') {
                    $(this).closest('.account-entry').find('.debit-input').val('');
                }
            });


            $('.account-entry').each(function() {
                var account = $(this).find('.account-select').val();
                var debitVal = $(this).find('.debit-input').val();
                var creditVal = $(this).find('.credit-input').val();

                if (!account || (debitVal === "" && creditVal === "")) {
                    hasError = true;
                    return false; // Required fields missing
                }

                if (debitVal !== "" && creditVal !== "") {
                    hasError = true;
                    Toast.fire({
                        icon: 'warning',
                        title: 'Only one of Debit or Credit must be entered per account.'
                    });
                    return false;
                }

                var debit = parseFloat(debitVal) || 0;
                var credit = parseFloat(creditVal) || 0;

                totalDebit += debit;
                totalCredit += credit;

                entries.push({
                    account: account,
                    debit: debit,
                    credit: credit
                });
            });


            // Save data
            $(document).on('click', '#btnSave', function(e) {
                e.preventDefault();

                var voucher_no = $('#voucher_no').val();
                var date = $('#entrydate').val();
                var remarks = $('#remarks').val();
                var active_fiscal_year = $('#fiscal_year_select').val();

                var entries = [];
                var hasError = false;
                var totalDebit = 0;
                var totalCredit = 0;

                $('.account-entry').each(function() {
                    var account = $(this).find('.account-select').val();
                    var debitVal = $(this).find('.debit-input').val();
                    var creditVal = $(this).find('.credit-input').val();

                    // Ensure account is selected
                    if (!account) {
                        hasError = true;
                        Toast.fire({
                            icon: 'warning',
                            title: 'Please select an account.'
                        });
                        return false;
                    }

                    // Both fields empty = invalid
                    if (debitVal === "" && creditVal === "") {
                        hasError = true;
                        Toast.fire({
                            icon: 'warning',
                            title: 'Either Debit or Credit must be entered (not both empty).'
                        });
                        return false;
                    }

                    // Both fields filled = invalid
                    if (debitVal !== "" && creditVal !== "") {
                        hasError = true;
                        Toast.fire({
                            icon: 'warning',
                            title: 'Only one of Debit or Credit should be filled.'
                        });
                        return false;
                    }

                    var debit = parseFloat(debitVal) || 0;
                    var credit = parseFloat(creditVal) || 0;

                    totalDebit += debit;
                    totalCredit += credit;

                    entries.push({
                        account: account,
                        debit: debit,
                        credit: credit
                    });
                });

                if (hasError) return;

                if (totalDebit !== totalCredit) {
                    Toast.fire({
                        type: 'error',
                        title: 'Debit and Credit amounts must be equal!'
                    });
                    return;
                }

                $.ajax({
                    url: '/bookkeeper/save-general-ledger',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        voucher_no: voucher_no,
                        date: date,
                        remarks: remarks,
                        active_fiscal_year: active_fiscal_year,
                        entries: entries
                    },
                    success: function(response) {
                        $('#addaccountsetupModal').modal('hide');
                        Toast.fire({
                            type: 'success',
                            title: 'Saved Successfully!'
                        });
                        displayGeneralLedger();

                        $('#btnPost').html('<i class="fas fa-thumbtack"></i> Post');
                        $('#btnPost').off('click').on('click', function(e) {
                            e.preventDefault();
                            $(this).hide();
                            $('#btnSave').removeAttr('hidden');
                        });
                    },
                    error: function(xhr) {
                        $('#addaccountsetupModal').modal('hide');
                        Toast.fire({
                            type: 'error',
                            title: 'Something went wrong!'
                        });
                        console.error(xhr.responseText);
                        $('#btnPost').hide();
                        $('#btnSave').removeAttr('hidden');
                    }
                });
            });




            $('#journey_entryModal').on('hidden.bs.modal', function() {
                $('#voucher_no').val('JV - 0000');
                $('#entrydate').val('');
                $('#remarks').val('');

                $('#account-entry-container .account-entry').not(':first').remove();

                const firstEntry = $('#account-entry-container .account-entry:first');
                firstEntry.find('.account-select').val('');
                firstEntry.find('.debit-input').val('0.00');
                firstEntry.find('.credit-input').val('0.00');

                $('.form-row.mb-3 input').each(function(index) {
                    if (index === 0) {
                        $(this).val('Total');
                    } else {
                        $(this).val('0.00');
                    }
                });
            });

            // $('#date_range').daterangepicker({
            //     autoUpdateInput: false,
            //     locale: {
            //         cancelLabel: 'Clear',
            //         format: 'MM/DD/YYYY'
            //     }
            // });

            // $('#date_range').on('apply.daterangepicker', function(ev, picker) {
            //     $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format(
            //         'MM/DD/YYYY'));
            //     displayGeneralLedger(); // Re-fetch and reload the table
            // });

            // $('#date_range').on('cancel.daterangepicker', function(ev, picker) {
            //     $(this).val('');
            //     displayGeneralLedger(); // Reset table with no date filter
            // });

            var fiscalyear = [];
            loadFiscalActive();

            function loadFiscalActive(callback) {
                $.ajax({
                    type: "GET",
                    url: '/bookkeeper/loadFiscal',
                    success: function (data) {
                        fiscalyear = data;
                        console.log(fiscalyear);
                        callback(); // run after fiscal data is loaded
                    }
                });
            }

            loadFiscalActive(function () {
                const fiscalStartDate = moment(fiscalyear[0].stime);
                const today = moment();

                $dateFilter.daterangepicker({
                    autoUpdateInput: false,
                    startDate: fiscalStartDate,
                    endDate: today,
                    locale: {
                        format: 'YYYY-MM-DD',
                        cancelLabel: 'Clear'
                    }
                });

                $dateFilter.on('apply.daterangepicker', function (ev, picker) {
                    $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
                    displayGeneralLedger();
                });

                $dateFilter.on('cancel.daterangepicker', function () {
                    $(this).val('');
                    displayGeneralLedger();
                });

                $dateFilter.val(fiscalStartDate.format('YYYY-MM-DD') + ' - ' + today.format('YYYY-MM-DD'));
                displayGeneralLedger();
            });



            // displayGeneralLedger()

            let allRows = []; // Global or outer-scope variable
            let currentPage = 1;
            const rowsPerPage = 15;

            // function displayGeneralLedger() {
            //     const fiscalYearId = $('#fiscal_year_select').val();
            //     const dateRange = $('#date_range').val();

            //     $.ajax({
            //         url: '/bookkeeper/display-general-ledger',
            //         type: 'GET',
            //         data: {
            //             fiscal_year_id: fiscalYearId,
            //             date_range: dateRange
            //         },
            //         success: function(response) {
            //             allRows = [];
            //             let totalDebit = 0;
            //             let totalCredit = 0;

            //             let previousVoucherNo = null;

            //             // General Ledger Rows
            //             response.generalLedger?.forEach(entry => {
            //                 const showVoucherInfo = entry.voucherNo !== previousVoucherNo;
            //                 const debit = parseFloat(entry.debit_amount || 0);
            //                 const credit = parseFloat(entry.credit_amount || 0);
            //                 totalDebit += debit;
            //                 totalCredit += credit;

            //                 allRows.push(`
        //                     <tr data-search="${[entry.voucherNo, entry.date, entry.code, entry.account_name, entry.remarks].join(' ').toLowerCase()}">
        //                         <td style="border: 1px solid #7d7d7d;">${showVoucherInfo ? entry.voucherNo : ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${showVoucherInfo ? new Date(entry.date).toLocaleDateString('en-US') : ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${entry.remarks|| ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${entry.code|| ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${entry.account_name || ''}</td>
        //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${debit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${credit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                     </tr>`);

            //                 previousVoucherNo = entry.voucherNo;
            //             });

            //             // Cash Transactions
            //             // response.cashtrans?.forEach(trans => {
            //             //     const amount = parseFloat(trans.amount || 0);
            //             //     totalDebit += amount;

            //             //     allRows.push(`
        //             //     <tr data-search="${['OR - ' + trans.ornum, trans.transdate, trans.studname, trans.particulars, trans.amount].join(' ').toLowerCase()}">
        //             //         <td style="border: 1px solid #7d7d7d;">OR - ${trans.ornum}</td>
        //             //         <td style="border: 1px solid #7d7d7d;">${new Date(trans.transdate).toLocaleDateString('en-US')}</td>
        //             //         <td style="border: 1px solid #7d7d7d;">${trans.sid} - ${trans.studname} - ${trans.particulars}</td>
        //             //         <td style="border: 1px solid #7d7d7d;">${trans.accountname || ''}</td>
        //             //         <td style="border: 1px solid #7d7d7d;">${trans.accountname || ''}</td>
        //             //         <td style="border: 1px solid #7d7d7d; text-align: right;">${amount.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //             //         <td style="border: 1px solid #7d7d7d; text-align: right;">0.00</td>
        //             //     </tr>`);
            //             // });
            //             response.cashtrans?.forEach(trans => {
            //                 const debit = parseFloat(trans.debit_amount || 0);
            //                 const credit = parseFloat(trans.credit_amount || 0);
            //                 totalDebit += debit;
            //                 totalCredit += credit;

            //                 allRows.push(`
        //                     <tr data-search="${['OR - ' + trans.ornum, trans.date, trans.studname, trans.particulars, trans.amountpaid].join(' ').toLowerCase()}">
        //                         <td style="border: 1px solid #7d7d7d;">JE - ${trans.ornum}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${new Date(trans.date).toLocaleDateString('en-US')}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${trans.remarks|| ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${trans.code|| ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${trans.account_name|| ''}</td>
        //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${trans.debit_amount.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${trans.credit_amount.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                     </tr>`);
            //             });

            //             // Adjustments
            //             // response.adjustments?.forEach(adj => {
            //             //     const debit = parseFloat(adj.debit_amount || 0);
            //             //     const credit = parseFloat(adj.credit_amount || 0);

            //             //     totalDebit += debit;
            //             //     totalCredit += credit;

            //             //     allRows.push(`
        //             //     <tr data-search="${['ADJ - ' + adj.voucherNo, adj.date, adj.remarks, adj.amount, adj.date].join(' ').toLowerCase()}">
        //             //         <td style="border: 1px solid #7d7d7d;">${adj.voucherNo}</td>
        //             //         <td style="border: 1px solid #7d7d7d;">${new Date(adj.date).toLocaleDateString('en-US')}</td>
        //             //         <td style="border: 1px solid #7d7d7d;">${adj.remarks || ''}</td>
        //             //         <td style="border: 1px solid #7d7d7d;"></td>
        //             //         <td style="border: 1px solid #7d7d7d;">ADJUSTMENT</td>
        //             //         <td style="border: 1px solid #7d7d7d; text-align: right;">${debit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //             //         <td style="border: 1px solid #7d7d7d; text-align: right;">${credit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //             //     </tr>`);
            //             // });

            //             // Student Ledger Transactions
            //             response.adjustments?.forEach((adj, index, array) => {
            //                 const debit = parseFloat(adj.debit_amount) || 0;
            //                 const credit = parseFloat(adj.credit_amount) || 0;
            //                 totalDebit += debit;
            //                 totalCredit += credit;

            //                 const prev = array[index - 1] || {};

            //                 const voucherNo = adj.voucherNo || '';
            //                 const date = adj.date ? new Date(adj.date) : null;
            //                 const prevDate = prev.date ? new Date(prev.date) : null;

            //                 const showVoucherInfo = index === 0 || voucherNo !== (prev.voucherNo || '');
            //                 const showDateInfo = index === 0 ||
            //                     (!date || !prevDate || date.toDateString() !== prevDate.toDateString()) ||
            //                     voucherNo !== (prev.voucherNo || '');

            //                 allRows.push(`
        //                     <tr data-search="${['JE - ' + (adj.ornum || ''), adj.date || '', adj.studname || '', adj.particulars || '', adj.amountpaid || ''].join(' ').toLowerCase()}">
        //                         <td style="border: 1px solid #7d7d7d;">${showVoucherInfo ? voucherNo : ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${showDateInfo && date ? date.toLocaleDateString('en-US') : ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${adj.remarks || ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${adj.code || ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${adj.account_name || ''}</td>
        //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${debit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${credit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                     </tr>`);
            //             });


            //             // Student Ledger Transactions
            //             response.studledger?.forEach((entry, index, array) => {
            //                 const debit = parseFloat(entry.debit_amount || 0);
            //                 const credit = parseFloat(entry.credit_amount || 0);

            //                 totalDebit += debit;
            //                 totalCredit += credit;

            //                 // Determine if we should show voucher and date info
            //                 const showVoucherInfo = index === 0 || entry.voucherNo !== array[
            //                     index - 1].voucherNo;
            //                 const showDateInfo = index === 0 ||
            //                     new Date(entry.date).toLocaleDateString('en-US') !==
            //                     new Date(array[index - 1].date).toLocaleDateString('en-US') ||
            //                     entry.voucherNo !== array[index - 1].voucherNo;

            //                 allRows.push(`
        //                 <tr data-search="${[entry.voucherNo, entry.date, entry.account_name, entry.remarks].join(' ').toLowerCase()}">
        //                     <td style="border: 1px solid #7d7d7d;">${showVoucherInfo ? entry.voucherNo : ''}</td>
        //                     <td style="border: 1px solid #7d7d7d;">${showDateInfo ? new Date(entry.date).toLocaleDateString('en-US') : ''}</td>
        //                     <td style="border: 1px solid #7d7d7d;">${entry.remarks || ''}</td>
        //                     <td style="border: 1px solid #7d7d7d;">${entry.code || ''}</td>
        //                     <td style="border: 1px solid #7d7d7d;">${entry.account_name || ''}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${debit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${credit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                 </tr>`);
            //             });




            //             // Totals row (always shown after pagination)
            //             allRows.push(`
        //                 <tr style="font-weight: bold;">
        //                     <td colspan="5" style="border: 1px solid #7d7d7d; text-align: right;">Total</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalDebit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalCredit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                 </tr>`);

            //             currentPage = 1;
            //             renderPage();
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //         }
            //     });
            // }

            // function displayGeneralLedger() {
            //     const fiscalYearId = $('#fiscal_year_select').val();
            //     const dateRange = $('#date_range').val();

            //     $.ajax({
            //         url: '/bookkeeper/display-general-ledger',
            //         type: 'GET',
            //         data: {
            //             fiscal_year_id: fiscalYearId,
            //             date_range: dateRange
            //         },
            //         success: function(response) {
            //             allRows = [];
            //             let totalDebit = 0;
            //             let totalCredit = 0;

            //             // Since the data is now merged and sorted, we'll use the mergedAndSorted array
            //             const mergedData = response|| [];
            //             console.log(mergedData, 'mergedData');
            //             // Track previous entries for grouping similar transactions
            //             let previousVoucherNo = null;
            //             let previousDate = null;
            //             let previousRemarks = null;

            //             mergedData.forEach((entry, index) => {
            //                 const debit = parseFloat(entry.debit_amount || 0);
            //                 const credit = parseFloat(entry.credit_amount || 0);
            //                 totalDebit += debit;
            //                 totalCredit += credit;

            //                 // Determine if we should show voucher and date info
            //                 const currentDate = entry.date ? new Date(entry.date).toLocaleDateString('en-US') : null;
            //                 const showVoucherInfo = entry.voucherNo !== previousVoucherNo;
            //                 const showDateInfo = currentDate !== previousDate || entry.voucherNo !== previousVoucherNo;

            //                 // Format the date for display
            //                 const displayDate = entry.date ? new Date(entry.date).toLocaleDateString('en-US') : '';

            //                 allRows.push(`
        //                     <tr data-search="${[entry.voucherNo || '', displayDate, entry.code || '', entry.account_name || '', entry.remarks || ''].join(' ').toLowerCase()}">
        //                         <td style="border: 1px solid #7d7d7d;">${showVoucherInfo ? (entry.voucherNo || '') : ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${showDateInfo ? displayDate : ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${entry.remarks || ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${entry.code || ''}</td>
        //                         <td style="border: 1px solid #7d7d7d;">${entry.account_name || ''}</td>
        //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${debit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${credit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                     </tr>`);

            //                 // Update previous values for next iteration
            //                 previousVoucherNo = entry.voucherNo;
            //                 previousDate = currentDate;
            //                 previousRemarks = entry.remarks;
            //             });

            //             // Totals row (always shown after pagination)
            //             allRows.push(`
        //                 <tr style="font-weight: bold;">
        //                     <td colspan="5" style="border: 1px solid #7d7d7d; text-align: right;">Total</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalDebit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                     <td style="border: 1px solid #7d7d7d; text-align: right;">${totalCredit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
        //                 </tr>`);

            //             currentPage = 1;
            //             renderPage();
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //         }
            //     });
            // }

            // function displayGeneralLedger(){
            //     var table = $('#general_ledger_table').DataTable({
            //         processing: true,
            //         serverSide: true,
            //         ajax: {
            //             url: '/bookkeeper/display-general-ledger',
            //             type: 'GET',
            //             data: function(d) {
            //                 if (d.length === -1) {
            //                     d.length = 100000000;
            //                 }
            //                 d.fiscal_year_id = $('#fiscal_year_select').val();
            //                 d.date_range = $('#date_filter').val();
            //             },
            //             dataSrc: function(json) {
            //                 $('#total_debit').text(
            //                     parseFloat(json.totalDebit).toLocaleString('en-US', { 
            //                         minimumFractionDigits: 2 
            //                     })
            //                 );
            //                 $('#total_credit').text(
            //                     parseFloat(json.totalCredit).toLocaleString('en-US', { 
            //                         minimumFractionDigits: 2 
            //                     })
            //                 );
            //                 return json.data;
            //             }
            //         },
            //         dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
            //             "<'row'<'col-sm-12'tr>>" +
            //             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            //         columns: [
            //             { 
            //                 data: 'voucherNo',
            //                 render: function(data, type, row) {
            //                     return row.isFirstInGroup ? (data || '') : '';
            //                 }
            //             },
            //             { 
            //                 data: 'date',
            //                 render: function(data, type, row) {
            //                     if (row.isFirstInGroup) {
            //                         return data ? new Date(data).toLocaleDateString('en-US') : '';
            //                     }
            //                     return '';
            //                 }
            //             },
            //             { data: 'remarks' },
            //             { data: 'code' },
            //             { data: 'account_name' },
            //             { 
            //                 data: 'debit_amount',
            //                 className: 'text-right',
            //                 render: function(data) {
            //                     return data ? parseFloat(data).toLocaleString('en-US', { 
            //                         minimumFractionDigits: 2 
            //                     }) : '0.00';
            //                 }
            //             },
            //             { 
            //                 data: 'credit_amount',
            //                 className: 'text-right',
            //                 render: function(data) {
            //                     return data ? parseFloat(data).toLocaleString('en-US', { 
            //                         minimumFractionDigits: 2 
            //                     }) : '0.00';
            //                 }
            //             }
            //         ],
            //         order: [[1, 'desc']],
            //         lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            //         pageLength: 10,
            //         scrollY: '60vh',
            //         scrollCollapse: true,
            //         fixedHeader: {
            //             header: true,
            //             footer: true
            //         },
            //         language: {
            //             search: "_INPUT_",
            //             searchPlaceholder: "Search...",
            //             lengthMenu: "Show _MENU_ entries",
            //             info: "Showing _START_ to _END_ of _TOTAL_ entries",
            //             infoEmpty: "Showing 0 to 0 of 0 entries",
            //             infoFiltered: "(filtered from _MAX_ total entries)"
            //         }
            //     });

            // }

            // Handle changes to filters
           
            $('#fiscal_year_select, #date_range').on('change', function() {
                displayGeneralLedger();
            });


            // Handle refresh button click
            $('#refresh_btn').click(function() {
                displayGeneralLedger()
            });

            // function displayGeneralLedger() {
            //     const fiscalYearId = $('#fiscal_year_select').val();
            //     const dateRange = $dateFilter.val();

            //     $.ajax({
            //         url: '/bookkeeper/display-general-ledger',
            //         type: 'GET',
            //         data: {
            //             fiscal_year_id: fiscalYearId,
            //             date_range: dateRange
            //         },
            //         success: function(response) {
            //             allRows = [];
            //             let totalDebit = 0;
            //             let totalCredit = 0;

            //             // Track the current group for display purposes
            //             let currentGroupId = null;
            //             let currentGroupDate = null;

            //             response.mergedAndSorted?.forEach((entry, index) => {
            //                 const debit = parseFloat(entry.debit_amount || 0);
            //                 const credit = parseFloat(entry.credit_amount || 0);
            //                 totalDebit += debit;
            //                 totalCredit += credit;

            //                 // Create a group identifier
            //                 const groupId = entry.voucherNo || 'unvouchered_' + entry.date +
            //                     '_' + entry.remarks;
            //                 const isNewGroup = groupId !== currentGroupId;

            //                 // Format date for display
            //                 const displayDate = entry.date ? new Date(entry.date)
            //                     .toLocaleDateString('en-US') : '';

            //                 // Show voucher and date only for first entry in group
            //                 const showVoucher = isNewGroup ? (entry.voucherNo || '') : '';
            //                 const showDate = isNewGroup ? displayDate : '';

            //                 allRows.push(`
            //                     <tr data-search="${[entry.voucherNo || '', displayDate, entry.code || '', entry.account_name || '', entry.remarks || ''].join(' ').toLowerCase()}">
            //                         <td style="border: 1px solid #7d7d7d;">${showVoucher}</td>
            //                         <td style="border: 1px solid #7d7d7d;">${showDate}</td>
            //                         <td style="border: 1px solid #7d7d7d;">${entry.remarks || ''}</td>
            //                         <td style="border: 1px solid #7d7d7d;">${entry.code || ''}</td>
            //                         <td style="border: 1px solid #7d7d7d;">${entry.account_name || ''}</td>
            //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${debit ? debit.toLocaleString(undefined, { minimumFractionDigits: 2 }) : ''}</td>
            //                         <td style="border: 1px solid #7d7d7d; text-align: right;">${credit ? credit.toLocaleString(undefined, { minimumFractionDigits: 2 }) : ''}</td>
            //                     </tr>`);

            //                 // Update current group tracking
            //                 if (isNewGroup) {
            //                     currentGroupId = groupId;
            //                     currentGroupDate = displayDate;
            //                 }
            //             });

            //             // Add totals row
            //             allRows.push(`
            //                 <tr style="font-weight: bold; background-color: #e8e8e8; position: sticky; bottom: 0; z-index: 3;">
            //                     <td colspan="5" style="border: 1px solid #7d7d7d; text-align: right;">Total</td>
            //                     <td style="border: 1px solid #7d7d7d; text-align: right;" id="total_debit">${totalDebit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
            //                     <td style="border: 1px solid #7d7d7d; text-align: right;" id="total_credit">${totalCredit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
            //                 </tr>`);

            //             currentPage = 1;
            //             renderPage();
            //         },
            //         error: function(xhr) {
            //             console.error(xhr.responseText);
            //         }
            //     });
            // }

            // function renderPage() {
            //     $('#general_ledger').html(allRows.join(''));
            //     $('#pagination_controls').html(''); // Clear pagination controls
            // }

            generateVoucher();

            function generateVoucher() {
                $.ajax({
                    url: '/bookkeeper/generate-voucher',
                    type: 'GET',
                    success: function(response) {
                        $('#voucher_no').val(response.voucher_no);
                    },
                    error: function(xhr) {
                        console.error('Error generating voucher:', xhr.responseText);
                    }
                });
            }

            // $(document).on('keyup', '#search_ledger', function() {
            //     const value = $(this).val().toLowerCase().trim().replace(/\s+/g, ' ');

            //     let totalDebit = 0;
            //     let totalCredit = 0;
            //     let hasMatch = false;

            //     $('#general_ledger tr').each(function() {
            //         const row = $(this);
            //         const searchableText = (row.data('search') || '').toLowerCase().replace(/\s+/g,
            //             ' '); // normalize row text
            //         const isMatch = searchableText.includes(value);
            //         row.toggle(isMatch);

            //         if (isMatch) {
            //             hasMatch = true;

            //             const debitText = $(this).find('td').eq(5).text().replace(/,/g, '');
            //             const creditText = $(this).find('td').eq(6).text().replace(/,/g, '');

            //             const debit = parseFloat(debitText) || 0;
            //             const credit = parseFloat(creditText) || 0;

            //             totalDebit += debit;
            //             totalCredit += credit;
            //         }
            //     });

            //     // Always remove existing total row
            //     $('#total_row').remove();

            //     // Only append total row if there's a search value and at least one match
            //     if (value !== '' && (totalDebit > 0 || totalCredit > 0)) {
            //         $('#general_ledger_table tbody').append(`
            //             <tr  style="font-weight: bold; background-color: #e8e8e8; position: sticky; bottom: 0; z-index: 3;" id="total_row">
            //                 <td colspan="5" style="border: 1px solid #7d7d7d; text-align: right;">Total</td>
            //                 <td style="border: 1px solid #7d7d7d; text-align: right;">${totalDebit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
            //                 <td style="border: 1px solid #7d7d7d; text-align: right;">${totalCredit.toLocaleString(undefined, { minimumFractionDigits: 2 })}</td>
            //             </tr>
            //         `);
            //     }
            // });



            $('#fiscal_year_select').on('change', function() {
                var selectedOption = $(this).find('option:selected');
                var stime = selectedOption.data('stime');
                var etime = selectedOption.data('etime');

                if (stime && etime) {
                    $('#date_range').val(stime + ' - ' + etime);
                } else {
                    $('#date_range').val('');
                }
            });


            $('#print_ledger').on('click', function() {
                var fiscalYear = $('#fiscal_year_select').val();
                var dateRange = $('#date_range').val();

                var query = $.param({
                    fiscal_year: fiscalYear,
                    date_range: dateRange
                });

                window.open('/bookkeeper/print-ledger?' + query, '_blank');
            });

            $(document).on('click', '#sync_transaction', function() {
                var fiscal_yearid = $('#fiscal_year_select').val();

                Swal.fire({
                    title: 'Please wait...',
                    html: `
                        <div class="row" style="justify-content: center !important; display: grid !important;">
                            <div class="loader1holder">
                                <div class="loader1"></div>
                            </div>
                            <div class="loaderholder">
                                <div class="loader"></div>
                            </div>
                            
                            <div class="note text-danger"><small><strong>Note!!! do not refresh while the process is ongoing...</strong></small></div>
                        </div>`,
                    onBeforeOpen: () => {
                        // You can perform additional actions before the modal is opened
                    },
                    onAfterClose: () => {
                        // You can perform additional actions after the modal is closed
                    },
                    allowOutsideClick: false,
                    showConfirmButton: false,
                });

                $.ajax({
                    type: "GET",
                    url: "/bookkeeper/sync-ledger",
                    data: {
                        fiscal_yearid: fiscal_yearid
                    },
                    success: function(data) {
                        if (data[0].status == 0) {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        } else {
                            displayGeneralLedger()
                            Toast.fire({
                                type: 'success',
                                title: data[0].message
                            })
                        }
                    }
                });
            })


        });


        // Declare the DataTable variable globally
        var generalLedgerTable;

        function displayGeneralLedger() {
            generalLedgerTable = $('#general_ledger_table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: '/bookkeeper/display-general-ledger',
                type: 'GET',
                data: function(d) {
                    // Show all records when "All" is selected
                    if (d.length === -1) {
                        d.length = 100000000;
                    }
                    d.fiscal_year_id = $('#fiscal_year_select').val();
                    d.date_range = $('#date_filter').val();
                },
                dataSrc: function(json) {
                    // Update footer totals
                    $('#total_debit').text(
                        parseFloat(json.totalDebit).toLocaleString('en-US', { 
                            minimumFractionDigits: 2 
                        })
                    );
                    $('#total_credit').text(
                        parseFloat(json.totalCredit).toLocaleString('en-US', { 
                            minimumFractionDigits: 2 
                        })
                    );
                    return json.data;
                }
            },
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            columns: [
                { 
                    data: 'voucherNo',
                    render: function(data, type, row) {
                        return row.isFirstInGroup ? (data || '') : '';
                    }
                },
                { 
                    data: 'date',
                    render: function(data, type, row) {
                        if (row.isFirstInGroup) {
                            return data ? new Date(data).toLocaleDateString('en-US') : '';
                        }
                        return '';
                    }
                },
                { data: 'remarks' },
                { data: 'code' },
                { data: 'account_name' },
                { 
                    data: 'debit_amount',
                    className: 'text-right',
                    render: function(data) {
                        return data ? parseFloat(data).toLocaleString('en-US', { 
                            minimumFractionDigits: 2 
                        }) : '0.00';
                    }
                },
                { 
                    data: 'credit_amount',
                    className: 'text-right',
                    render: function(data) {
                        return data ? parseFloat(data).toLocaleString('en-US', { 
                            minimumFractionDigits: 2 
                        }) : '0.00';
                    }
                }
            ],
            order: [[1, 'desc']],
            lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
            pageLength: 10,
            scrollY: '60vh',
            scrollCollapse: true,
            fixedHeader: {
                header: true,
                footer: true
            },
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search...",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)"
            }
        });
    }
    </script>
@endsection
