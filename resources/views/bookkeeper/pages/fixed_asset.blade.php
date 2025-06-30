<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Fixed Asset</title>
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
    <style>
        select,
        input,
        th,
        td {
            font-size: 13px !important;
            font-weight: normal !important;
        }

        .form-control {
            /* box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42) !important; */
            /* border: none !important; */
            height: 40px !important;


        }

        ::placeholder {
            font-size: 12px !important;
        }

        label {
            font-weight: normal !important;
            font-size: 13px !important;
        }

        th {
            font-weight: 600 !important;
            border: 1px solid #7d7d7d !important;
        }

        td {
            border: 1px solid #7d7d7d !important;
        }

        nav[aria-label="breadcrumb"] .breadcrumb {
            font-size: 14px;
            /* Smaller text */
            padding: 4px 8px;
            /* Smaller padding */
        }

        .breadcrumb-item a {
            font-size: 12px;
            /* Smaller links too */
        }
    </style>


</head>

<body>
    @section('content')
        <div class="container-fluid mt-3 ">
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <i class="fas fa-hand-holding-usd ml-2 mt-2 mr-3 mb-2" style="font-size: 33px;"></i>
                    <h1 class="text-black m-0">Fixed Asset Setup</h1>

                </div>
                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Fix Asset Setup</li>
                    </ol>
                </nav>


            </div>


            <div class="row py-3">
                <div class="col-md-12">
                    <button class="btn btn-success btn-sm" id="add_fixed_assets" style="background-color: #015918;"
                        data-toggle="modal" >
                        + Add Fixed Asset
                    </button>
                    <button class="btn btn-primary btn-sm" id="print_fixed_assets" data-toggle="modal"
                        data-target="#fixed_asset">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>

            <div class="py-3 bg-white">
                <div class="col-lg-12 col-md-12">
                    <div class="table-responsive w-100">
                        <table id="fixed_asset_table" class="table table-sm w-100">
                            <thead class="table-secondary">
                                <tr>
                                    <th class="text-left">Code</th>
                                    <th class="text-left">Asset Name</th>
                                    <th class="text-left">Amount</th>
                                    <th class="text-left">Purchase Date</th>
                                    <th class="text-left">Useful Life</th>
                                    <th class="text-left">Depreciation Rate per Annum</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>





            <div id="add_fixed_asset" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                        <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                            <h5 class="modal-title">Expenses Items Monitoring</h5>
                            <button type="button" class="close" data-dismiss="modal" id="fixed_asset_modal_close"
                                aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="font-weight-bold">Depreciation Option</label>
                                <div class="d-flex align-items-center">
                                    <div class="form-check mr-4">
                                        <input class="form-check-input" type="radio" name="depreciationOption"
                                            id="withDepreciation" value="with"
                                            {{ old('depreciationOption', 'with') == 'with' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="withDepreciation">
                                            With Depreciation Value
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="depreciationOption"
                                            id="withoutDepreciation" value="without"
                                            {{ old('depreciationOption') == 'without' ? 'checked' : '' }}>
                                        <label class="form-check-label" for="withoutDepreciation">
                                            Without Depreciation Value
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <form id="fixedAssetForm">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Code</label>
                                        <input type="text" class="form-control" placeholder="Code"
                                            id="fixed_asset_code">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Asset Name</label>
                                        <input type="text" class="form-control" placeholder="Asset Name"
                                            id="fixed_asset_name">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label>Purchased Date</label>
                                        <input type="date" class="form-control" id="fixed_asset_purchased_date">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Asset Value</label>
                                        <input type="text" class="form-control" placeholder="Asset Value"
                                            id="fixed_asset_value">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Warranty</label>
                                        <input type="text" class="form-control" placeholder="Warranty"
                                            id="fixed_asset_warranty">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label>Serial Number</label>
                                        <input type="text" class="form-control" placeholder="Serial Number"
                                            id="fixed_asset_serial_number">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Remarks</label>
                                        <textarea class="form-control" rows="4" placeholder="Remarks" id="fixed_asset_remarks"></textarea>
                                    </div>
                                </div>

                                <div class="card mt-3 " id="depreciation_card">
                                    <div class="card-header font-weight-bold">Book Value</div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label>Depreciation Method</label>
                                                <select class="form-control" id="fixed_asset_depreciation_method">
                                                    <option value="" disabled>Assign Depreciation Method</option>
                                                    @php
                                                        $depreciationMethods = DB::table('bk_depreciation')->get();
                                                        $activeMethods = $depreciationMethods->filter(function ($method) {
                                                            return $method->isActive;
                                                        });
                                                        $autoSelect = $activeMethods->count() === 1 ? $activeMethods->first()->id : null;
                                                    @endphp
                                            
                                                    @foreach ($depreciationMethods as $depreciation)
                                                        <option 
                                                            value="{{ $depreciation->id }}" 
                                                            @if($autoSelect === $depreciation->id) selected @endif>
                                                            {{ $depreciation->depreciation_desc }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label>Residual Value</label>
                                                <input type="text" class="form-control" placeholder="0.00"
                                                    id="fixed_asset_residual_value">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Asset Useful Life in Years</label>
                                                <input type="text" class="form-control" placeholder="0"
                                                    id="fixed_asset_useful_life">
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label>Depreciation Start Date</label>
                                                <input type="date" class="form-control"
                                                    id="fixed_asset_depreciation_start_date">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Depreciation Rate per Annum</label>
                                                <input type="text" class="form-control" placeholder="0.00" readonly
                                                    id="fixed_asset_depreciation_rate_per_annum">
                                            </div>
                                            <div class="col-md-4">
                                                <label>Accumulated Depreciation</label>
                                                <input type="text" class="form-control" placeholder="0" readonly
                                                    id="fixed_asset_accumulated_depreciation">
                                            </div>
                                        </div>

                                        <hr class="my-4">

                                        <div class="row">
                                            <div class="col-3">
                                                <h6 class="font-weight-bold">Depreciation JE</h6>
                                            </div>
                                            <div class="col-9 d-flex align-items-center">
                                                <div class="w-50 pr-2">
                                                    <label>Debit Account</label>
                                                    <select class="form-control"
                                                        id="fixed_asset_depreciation_je_debit_account">
                                                     
                                                        <option value="" selected disabled>Assign Account Type
                                                        </option>
                                                   
                                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                                                <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                                            @endforeach
                                                         @endforeach
                                                    </select>
                                                </div>
                                                <div class="w-50 pl-2">
                                                    <label>Credit Account</label>
                                                    <select class="form-control"
                                                        id="fixed_asset_depreciation_je_credit_account">
                                                        <option value="" selected disabled>Assign Account Type
                                                        </option>
                                                        {{-- @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                                            <option value="{{ $coa->id }}">{{ $coa->account_name }}
                                                            </option>
                                                        @endforeach --}}
                                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                            <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                                                <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-3">
                                                <h6 class="font-weight-bold">Depreciation against loss on sale JE</h6>
                                            </div>
                                            <div class="col-9 d-flex align-items-center">
                                                <div class="w-50 pr-2">
                                                    <label>Debit Account</label>
                                                    <select class="form-control"
                                                        id="fixed_asset_loss_on_sale_je_debit_account">
                                                        <option value="" selected disabled>Assign Account Type
                                                        </option>
                                                        {{-- @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                                            <option value="{{ $coa->id }}">{{ $coa->account_name }}
                                                            </option>
                                                        @endforeach --}}
                                                          @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                            <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                                                <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="w-50 pl-2">
                                                    <label>Credit Account</label>
                                                    <select class="form-control"
                                                        id="fixed_asset_loss_on_sale_je_credit_account">
                                                        <option value="" selected disabled>Assign Account Type
                                                        </option>
                                                        @foreach (DB::table('chart_of_accounts')->where('deleted', 0)->get() as $coa)
                                                            <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}</option>
                                                            @foreach (DB::table('bk_sub_chart_of_accounts')->where('deleted', 0)->where('coaid', $coa->id)->get() as $subcoa)
                                                                <option value="{{ $subcoa->id }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                                    {{ $subcoa->sub_code }} - {{ $subcoa->sub_account_name }}</option>
                                                            @endforeach
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </form>

                            <div id="hiddenAssetForm" class="card p-3 d-none">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label>Code</label>
                                        <input type="text" class="form-control" placeholder="Code">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Asset Name</label>
                                        <input type="text" class="form-control" placeholder="Asset Name">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <label>Purchased Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Asset Value</label>
                                        <input type="text" class="form-control" placeholder="Asset Value">
                                    </div>
                                    <div class="col-md-4">
                                        <label>Warranty</label>
                                        <input type="text" class="form-control" placeholder="Warranty">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6">
                                        <label>Serial Number</label>
                                        <input type="text" class="form-control" placeholder="Serial Number">
                                    </div>
                                    <div class="col-md-6">
                                        <label>Warranty</label>
                                        <input type="text" class="form-control" placeholder="Warranty">
                                    </div>
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label>Remarks</label>
                                        <input type="text" class="form-control" placeholder="Remarks">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-success" id="saveFixedAsset">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade show" id="edit_fixed_asset_modal" data-backdrop="static" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                        <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                            <h5 class="modal-title">Expenses Items Monitoring</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <div class="form-group">
                                <label class="font-weight-bold">Depreciation Option</label>
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <input type="text" id="fixed_asset_id_edit" hidden>
                                        <div class="form-check mr-4">
                                            <input class="form-check-input" type="radio" name="depreciationOption_edit"
                                                id="withDepreciation_edit" checked>
                                            <label class="form-check-label" for="withDepreciation_edit">
                                                With Depreciation Value
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="depreciationOption_edit"
                                                id="withoutDepreciation_edit">
                                            <label class="form-check-label" for="withoutDepreciation_edit">
                                                Without Depreciation Value
                                            </label>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-sm" id="view_je" data-toggle="modal"
                                        data-target="#add_supplier" style="height: 34px;" data-id="0">
                                        <i class="fas fa-file-alt ml-1 mt-1 mb-2" style="font-size: 19px;"></i>
                                        <i class="fas fa-pencil-alt mt-2 mb-2" style="font-size: 6px;"></i>
                                        View Journal Entry
                                    </button>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <label>Code</label>
                                    <input type="text" class="form-control" placeholder="Code"
                                        id="fixed_asset_code_edit">
                                </div>
                                <div class="col-md-6">
                                    <label>Asset Name</label>
                                    <input type="text" class="form-control" placeholder="Asset Name"
                                        id="fixed_asset_name_edit">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label>Purchased Date</label>
                                    <input type="date" class="form-control" id="fixed_asset_purchased_date_edit">
                                </div>
                                <div class="col-md-4">
                                    <label>Asset Value</label>
                                    <input type="text" class="form-control" placeholder="Asset Value"
                                        id="fixed_asset_value_edit">
                                </div>
                                <div class="col-md-4">
                                    <label>Warranty</label>
                                    <input type="text" class="form-control" placeholder="Warranty"
                                        id="fixed_asset_warranty_edit">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <label>Serial Number</label>
                                    <input type="text" class="form-control" placeholder="Serial Number"
                                        id="fixed_asset_serial_number_edit">
                                </div>
                                <div class="col-md-6">
                                    <label>Warranty</label>
                                    <input type="text" class="form-control" placeholder="Warranty"
                                        id="fixed_asset_serial_warranty_edit">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <label>Remarks</label>
                                    <textarea class="form-control" rows="2" placeholder="Remarks" id="fixed_asset_remarks_edit"></textarea>
                                </div>
                            </div>

                            <div class="card mt-3" id="depreciation_card_edit">
                                <div class="card-header font-weight-bold">Book Value</div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Depreciation Method</label>
                                            <select class="form-control" id="fixed_asset_depreciation_method_edit">
                                                <option value="1" selected>Straight Line Method</option>
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Residual Value</label>
                                            <input type="text" class="form-control" value="0.00"
                                                id="fixed_asset_residual_value_edit">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Asset Useful Life in Years</label>
                                            <input type="text" class="form-control" value="10"
                                                id="fixed_asset_useful_life_edit">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label>Depreciation Start Date</label>
                                            <input type="date" class="form-control"
                                                id="fixed_asset_depreciation_start_date_edit">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Depreciation Rate per Annum</label>
                                            <input type="text" class="form-control" value="0.00" readonly
                                                id="fixed_asset_depreciation_rate_per_annum_edit">
                                        </div>
                                        <div class="col-md-4">
                                            <label>Accumulated Depreciation</label>
                                            <input type="text" class="form-control" value="0" readonly
                                                id="fixed_asset_accumulated_depreciation_edit">
                                        </div>
                                    </div>

                                    <hr class="my-4">

                                    <div class="row">
                                        <div class="col-3">
                                            <h6 class="font-weight-bold">Depreciation JE</h6>
                                        </div>
                                        <div class="col-9 d-flex align-items-center">
                                            <div class="w-50 pr-2">
                                                <label>Debit Account</label>
                                                <select class="form-control"
                                                    id="fixed_asset_depreciation_je_debit_account_edit">
                                                    @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="w-50 pl-2">
                                                <label>Credit Account</label>
                                                <select class="form-control"
                                                    id="fixed_asset_depreciation_je_credit_account_edit">
                                                    @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                        <div class="col-3">
                                            <h6 class="font-weight-bold">Depreciation against loss on sale JE</h6>
                                        </div>
                                        <div class="col-9 d-flex align-items-center">
                                            <div class="w-50 pr-2">
                                                <label>Debit Account</label>
                                                <select class="form-control"
                                                    id="fixed_asset_loss_on_sale_je_debit_account_edit">
                                                    @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="w-50 pl-2">
                                                <label>Credit Account</label>
                                                <select class="form-control"
                                                    id="fixed_asset_loss_on_sale_je_credit_account_edit">
                                                    @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                                        <option value="{{ $coa->id }}">{{ $coa->code }} - {{ $coa->account_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-success" id="updateFixedAsset">Update</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="add_supplier" class="modal fade" data-backdrop="static" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-xl" role="document">
                    <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                        <div class="modal-header " style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                            <h5 class="modal-title">Depreciation Journal Entry</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span>&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <h6 id="fixed_asset_name_depreciation"></h6>

                            <br>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm purchase_items_table_edit"
                                    id="fixed_asset_depreciation_table">
                                    <thead>
                                        <tr>
                                            <th>Depreciation Date</th>
                                            <th>Explanation</th>
                                            <th>Account</th>
                                            <th>Debit</th>
                                            <th>Credit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
                                <br>
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
        <script>
            const formatter = new Intl.NumberFormat();
        
            document.querySelectorAll('.auto-comma').forEach(input => {
                input.addEventListener('input', function () {
                    const value = this.value.replace(/,/g, '');
                    if (!isNaN(value) && value !== '') {
                        this.value = formatter.format(value);
                    }
                });
            });
        </script>
        <script>
            $(document).ready(function() {

                $('#fixed_asset_depreciation_je_debit_account').select2({
                    placeholder: "Select Debit Account",
                    allowClear: true,
                    theme: 'bootstrap4'
                });

                $('#fixed_asset_depreciation_je_credit_account').select2({
                    placeholder: "Select Credit Account",
                    allowClear: true,
                    theme: 'bootstrap4'
                });

                $('#fixed_asset_loss_on_sale_je_debit_account').select2({
                    placeholder: "Select Debit Account",
                    allowClear: true,
                    theme: 'bootstrap4'
                });

                $('#fixed_asset_loss_on_sale_je_credit_account').select2({
                    placeholder: "Select Credit Account",
                    allowClear: true,
                    theme: 'bootstrap4'
                });

                $('#fixed_asset_depreciation_je_debit_account_edit').select2({
                    placeholder: "Select Debit Account",
                    allowClear: true,
                    theme: 'bootstrap4'
                });

                $('#fixed_asset_depreciation_je_credit_account_edit').select2({
                    placeholder: "Select Credit Account",
                    allowClear: true,
                    theme: 'bootstrap4'
                });

                
                $('#fixed_asset_loss_on_sale_je_debit_account_edit').select2({
                    placeholder: "Select Debit Account",
                    allowClear: true,
                    theme: 'bootstrap4'
                });

                $('#fixed_asset_loss_on_sale_je_credit_account_edit').select2({
                    placeholder: "Select Credit Account",
                    allowClear: true,
                    theme: 'bootstrap4'
                });

                $('#add_fixed_assets').on('click', function() {
                    $('#add_fixed_asset').modal('show');
                })

                $(document).on('click', '.edit_fixed_asset', function() {
                    var id = $(this).data('id');
                    $('#edit_fixed_asset_modal').data('id', id).modal('show');
                });
                // $('#fixed_asset_table').DataTable({
                //     processing: true,
                //     serverSide: true,
                //     ajax: "#", // Update this with your Laravel route
                //     columns: [{
                //         data: 'code',
                //         name: 'code'
                //     },
                //     {
                //         data: 'asset_name',
                //         name: 'asset_name'
                //     },
                //     {
                //         data: 'amount',
                //         name: 'amount',
                //         render: $.fn.dataTable.render.number(',', '.', 2, 'PHP ')
                //     },
                //     {
                //         data: 'purchase_date',
                //         name: 'purchase_date'
                //     },
                //     {
                //         data: 'useful_life',
                //         name: 'useful_life'
                //     },
                //     {
                //         data: 'depreciation_rate',
                //         name: 'depreciation_rate',
                //         render: function (data) {
                //             return data + '%';
                //         }
                //     },
                //     {
                //         data: 'action',
                //         name: 'action',
                //         orderable: false,
                //         searchable: false
                //     }
                //     ]
                // });

                // function toggleForms() {
                //     if ($("#withoutDepreciation").is(":checked")) {
                //         $("#fixedAssetForm").addClass("d-none");
                //         $("#hiddenAssetForm").removeClass("d-none");
                //     } else {
                //         $("#fixedAssetForm").removeClass("d-none");
                //         $("#hiddenAssetForm").addClass("d-none");
                //     }
                // }

                // // Initial check on page load
                // toggleForms();

                // // Event listeners for changes
                // $("input[name='depreciation_option']").on("change", toggleForms);

                $("#withDepreciation, #withoutDepreciation").on("change", function() {
                    if ($(this).attr("id") === "withDepreciation") {
                        $("#withDepreciation").prop("checked", true);
                        $("#withoutDepreciation").prop("checked", false);
                        $("#depreciation_card").show();
                    } else {
                        $("#withDepreciation").prop("checked", false);
                        $("#withoutDepreciation").prop("checked", true);
                        $("#depreciation_card").hide();
                    }
                });



                $('#saveFixedAsset').on('click', function(event) {
                    event.preventDefault();
                    fixed_asset_saving()

                });

                // Set up event listeners for auto-calculation
                $('#fixed_asset_value, #fixed_asset_residual_value, #fixed_asset_useful_life, #fixed_asset_depreciation_start_date, #fixed_asset_depreciation_method').on('change keyup', function() {
                    // Get the necessary values
                    var assetValue = parseFloat($('#fixed_asset_value').val()) || 0;
                    var residualValue = parseFloat($('#fixed_asset_residual_value').val()) || 0;
                    var usefulLife = parseInt($('#fixed_asset_useful_life').val()) || 1;
                    var warranty = parseInt($('#fixed_asset_warranty').val()) || 0; // Get warranty value
                    var method = $('#fixed_asset_depreciation_method option:selected').text().trim();
                    
                    // Only calculate if we have valid inputs
                    if (assetValue > 0 && usefulLife > 0) {
                        // Calculate annual depreciation
                        var annualDepreciation = 0;
                        var depreciableAmount = assetValue - residualValue;
                        
                        if (method.includes("Straight Line") || method.includes("SLM")) {
                            annualDepreciation = depreciableAmount / usefulLife;
                        }
                        else if (method.includes("Double Declining") || method.includes("DDBM")) {
                            annualDepreciation = 2 * (depreciableAmount) / usefulLife;
                            // Ensure we don't depreciate below residual value
                            if (assetValue - annualDepreciation < residualValue) {
                                annualDepreciation = depreciableAmount;
                            }
                        }
                        
                        // Calculate depreciation rate
                        $('#fixed_asset_depreciation_rate_per_annum').val(annualDepreciation.toFixed(2));
                    }
                });

                function fixed_asset_saving() {
                    // Get all form values
                    var depreciationOption = $("#withDepreciation").prop('checked') ? 'With Depreciation Value' : 'Without Depreciation Value';
                    var fixed_asset_code = $('#fixed_asset_code').val();
                    var fixed_asset_name = $('#fixed_asset_name').val();
                    var fixed_asset_purchased_date = $('#fixed_asset_purchased_date').val();
                    var fixed_asset_value = parseFloat($('#fixed_asset_value').val()) || 0;
                    var fixed_asset_warranty = $('#fixed_asset_warranty').val();
                    var fixed_asset_serial_number = $('#fixed_asset_serial_number').val();
                    var fixed_asset_serial_warranty = $('#fixed_asset_serial_warranty').val();
                    var fixed_asset_remarks = $('#fixed_asset_remarks').val();

                    var fixed_asset_depreciation_method = $('#fixed_asset_depreciation_method').val();
                    var fixed_asset_residual_value = parseFloat($('#fixed_asset_residual_value').val()) || 0;
                    var fixed_asset_useful_life = parseInt($('#fixed_asset_useful_life').val()) || 1;
                    var fixed_asset_depreciation_start_date = $('#fixed_asset_depreciation_start_date').val();
                    var fixed_asset_accumulated_depreciation = parseFloat($('#fixed_asset_accumulated_depreciation').val()) || 0;
                    
                    // Get depreciation method details
                    var selectedMethod = $('#fixed_asset_depreciation_method option:selected').text().trim();
                    var currentDate = new Date();
                    var startDate = new Date(fixed_asset_depreciation_start_date);
                    
                    // Calculate months used (ensure start date is valid)
                    var monthsUsed = 0;
                    if (fixed_asset_depreciation_start_date && !isNaN(startDate.getTime())) {
                        monthsUsed = (currentDate.getFullYear() - startDate.getFullYear()) * 12 + 
                                    (currentDate.getMonth() - startDate.getMonth());
                        monthsUsed = Math.max(0, monthsUsed); // Ensure not negative
                    }
                    
                    // Calculate annual and monthly depreciation based on method
                    var annualDepreciation = 0;
                    var monthlyDepreciation = 0;
                    
                    if (selectedMethod.includes("Straight Line") || selectedMethod.includes("SLM")) {
                        // Straight Line Method: (Cost - Residual Value) / Useful Life
                        annualDepreciation = (fixed_asset_value - fixed_asset_residual_value) / fixed_asset_useful_life;
                        monthlyDepreciation = annualDepreciation / 12;
                    } 
                    else if (selectedMethod.includes("Double Declining") || selectedMethod.includes("DDBM")) {
                        // Double Declining Balance: 2 * (Book Value) / Useful Life
                        var bookValue = fixed_asset_value - fixed_asset_accumulated_depreciation;
                        annualDepreciation = 2 * (fixed_asset_value / fixed_asset_useful_life); // DDB uses original cost, not book value
                        monthlyDepreciation = annualDepreciation / 12;
                        
                        // Ensure depreciation doesn't take book value below residual value
                        if ((fixed_asset_value - fixed_asset_accumulated_depreciation - annualDepreciation) < fixed_asset_residual_value) {
                            annualDepreciation = fixed_asset_value - fixed_asset_accumulated_depreciation - fixed_asset_residual_value;
                            monthlyDepreciation = annualDepreciation / 12;
                        }
                    } 
                    
                    // Calculate accumulated depreciation for the period
                    var depreciationForPeriod = 0;
                    var maxDepreciation = fixed_asset_value - fixed_asset_residual_value;
                    
                    if (monthsUsed <= fixed_asset_useful_life * 12) {
                        depreciationForPeriod = monthlyDepreciation * monthsUsed;
                    } else {
                        depreciationForPeriod = maxDepreciation;
                    }

                    // Update the accumulated depreciation (ensure it doesn't exceed total depreciable amount)
                    fixed_asset_accumulated_depreciation = Math.min(depreciationForPeriod, maxDepreciation);
                    
                    // Calculate current depreciation rate per annum
                    var fixed_asset_depreciation_rate_per_annum = (annualDepreciation / fixed_asset_value) * 100 || 0;
                    
                    // Update the form fields with calculated values
                    $('#fixed_asset_accumulated_depreciation').val(fixed_asset_accumulated_depreciation.toFixed(2));
                    $('#fixed_asset_depreciation_rate_per_annum').val(fixed_asset_depreciation_rate_per_annum.toFixed(2));

                    // Continue with the AJAX call
                    $.ajax({
                        type: 'GET',
                        url: '/bookkeeper/fixed_asset/create',
                        data: {
                            depreciationOption: depreciationOption,
                            fixed_asset_code: fixed_asset_code,
                            fixed_asset_name: fixed_asset_name,
                            fixed_asset_purchased_date: fixed_asset_purchased_date,
                            fixed_asset_value: fixed_asset_value,
                            fixed_asset_warranty: fixed_asset_warranty,
                            fixed_asset_serial_number: fixed_asset_serial_number,
                            fixed_asset_serial_warranty: fixed_asset_serial_warranty,
                            fixed_asset_remarks: fixed_asset_remarks,
                            fixed_asset_depreciation_method: fixed_asset_depreciation_method,
                            fixed_asset_residual_value: fixed_asset_residual_value,
                            fixed_asset_useful_life: fixed_asset_useful_life,
                            fixed_asset_depreciation_start_date: fixed_asset_depreciation_start_date,
                            fixed_asset_depreciation_rate_per_annum: fixed_asset_depreciation_rate_per_annum,
                            fixed_asset_accumulated_depreciation: fixed_asset_accumulated_depreciation,
                            fixed_asset_depreciation_je_debit_account: $('#fixed_asset_depreciation_je_debit_account').val(),
                            fixed_asset_depreciation_je_credit_account: $('#fixed_asset_depreciation_je_credit_account').val(),
                            fixed_asset_loss_on_sale_je_debit_account: $('#fixed_asset_loss_on_sale_je_debit_account').val(),
                            fixed_asset_loss_on_sale_je_credit_account: $('#fixed_asset_loss_on_sale_je_credit_account').val()
                        },
                        success: function(data) {
                            if (data[0].status == 1) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully created'
                                });
                                // Clear form fields
                                $("#fixed_asset_code").val("");
                                $("#fixed_asset_name").val("");
                                $("#fixed_asset_value").val("");
                                $("#fixed_asset_warranty").val("");
                                $("#fixed_asset_serial_number").val("");
                                $("#fixed_asset_serial_warranty").val("");
                                $("#fixed_asset_remarks").val("");
                                $("#fixed_asset_residual_value").val("");
                                $("#fixed_asset_useful_life").val("");
                                $("#fixed_asset_depreciation_rate_per_annum").val("");
                                $("#fixed_asset_accumulated_depreciation").val("");
                                $('#fixed_asset_depreciation_method').val("").trigger('change');
                                $('#fixed_asset_depreciation_je_debit_account').val("").trigger('change');
                                $('#fixed_asset_depreciation_je_credit_account').val("").trigger('change');
                                $('#fixed_asset_loss_on_sale_je_debit_account').val("").trigger('change');
                                $('#fixed_asset_loss_on_sale_je_credit_account').val("").trigger('change');

                                fixedAssetsTable();
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                });
                            }
                        }
                    });
                }

                fixedAssetsTable()

                function fixedAssetsTable() {
                    $("#fixed_asset_table").DataTable({
                        destroy: true,
                        autoWidth: false,
                        paging: false,
                        info: false,
                        ajax: {
                            url: '/bookkeeper/fixed_asset/fetch',
                            type: 'GET',
                            // dataSrc: function(json) {
                            //     return json.fixed_assets.concat(json.fixedassets_with_depreciation);
                            // }
                            dataSrc: function(json) {
                                return json;
                            }
                        },
                        columns: [{
                                "data": "code"
                            },
                            {
                                "data": "asset_name"
                            },
                            {
                                "data": "asset_value"
                            },
                            {
                                "data": "purchased_date"
                            },
                            {
                                "data": "asset_life_yrs"
                            },
                            {
                                "data": "depreciation_rate_per_annum"
                            },
                            {
                                "data": null
                            }
                        ],
                        columnDefs: [{
                                'targets': 0,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData) {
                                    $(td).html(rowData.code).addClass('align-middle');
                                }
                            },
                            {
                                'targets': 1,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData) {
                                    $(td).html(rowData.asset_name).addClass('align-middle');
                                }
                            },
                            {
                                'targets': 2,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData) {
                                    $(td).html(rowData.asset_value).addClass('align-middle');
                                }
                            },
                            {
                                'targets': 3,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData) {
                                    $(td).html(rowData.purchased_date).addClass('align-middle');
                                }
                            },
                            {
                                'targets': 4,
                                'orderable': false,
                                '/fixed_asset/createcreatedCell': function(td, cellData, rowData) {
                                    $(td).html(rowData.asset_life_yrs ? rowData.asset_life_yrs : '--')
                                        .addClass('align-middle');
                                }
                            },
                            {
                                'targets': 5,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData) {
                                    $(td).html(rowData.depreciation_rate_per_annum ? rowData
                                        .depreciation_rate_per_annum : '--').addClass(
                                        'align-middle');
                                }
                            },
                            {
                                'targets': 6,
                                'orderable': false,
                                'createdCell': function(td, cellData, rowData) {
                                    var edit_button = `
                                        <button type="button" class="btn btn-sm btn-primary edit_fixed_asset" id="edit_fixed_asset" data-id="${rowData.id}">
                                            <i class="far fa-edit"></i>
                                        </button>
                                    `;
                                    var delete_button = `
                                        <button type="button" class="btn btn-sm btn-danger delete_fixed_asset" data-id="${rowData.id}">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    `;
                                    $(td).html(edit_button + ' ' + delete_button).addClass('text-center align-middle');
                                }
                            }
                        ]
                    });
                }


                // $(document).on('click', '.edit_fixed_asset', function() {


                //     var fixed_asset_id = $(this).attr('data-id')

                //     $.ajax({
                //         type: 'GET',
                //         url: '/bookkeeper/fixed_asset/edit',
                //         data: {
                //             fixed_asset_id: fixed_asset_id
                //         },
                //         success: function(response) {

                //             var bk_fixedassets = response.bk_fixedassets;
                //             var bk_fixedassets_with_depreciation = response
                //                 .bk_fixedassets_with_depreciation;
                //             var accounts = response.accounts;
                //             var sub_chart_of_accounts = response.sub_chart_of_accounts;

                //             $("#fixed_asset_id_edit").val(bk_fixedassets[0].id);

                //             if (bk_fixedassets[0].depreciation_option ==
                //                 'With Depreciation Value') {
                //                 $("#withDepreciation_edit").prop('checked', true).prop('disabled',
                //                     false);
                //                 $("#withoutDepreciation_edit").prop('checked', false).prop(
                //                     'disabled', true);

                //                 $("#depreciation_card_edit").show();
                //                 $("#view_je").show();
                //             } else {
                //                 $("#withDepreciation_edit").prop('checked', false).prop('disabled',
                //                     true);
                //                 $("#withoutDepreciation_edit").prop('checked', true).prop(
                //                     'disabled', false);

                //                 $("#depreciation_card_edit").hide();
                //                 $("#view_je").hide();
                //             }

                //             $("#view_je").attr("data-id", bk_fixedassets[0].id);

                //             $("#fixed_asset_code_edit").val(bk_fixedassets[0].code);
                //             $("#fixed_asset_name_edit").val(bk_fixedassets[0].asset_name);
                //             $("#fixed_asset_purchased_date_edit").val(bk_fixedassets[0]
                //                 .purchased_date);
                //             $("#fixed_asset_value_edit").val(bk_fixedassets[0].asset_value);
                //             $("#fixed_asset_warranty_edit").val(bk_fixedassets[0].warranty);
                //             $("#fixed_asset_serial_number_edit").val(bk_fixedassets[0]
                //                 .serial_number);
                //             $("#fixed_asset_serial_warranty_edit").val(bk_fixedassets[0]
                //                 .serial_warranty);
                //             $("#fixed_asset_remarks_edit").val(bk_fixedassets[0].remarks);

                //             $("#fixed_asset_residual_value_edit").val(
                //                 bk_fixedassets_with_depreciation[0].residual_value);
                //             $("#fixed_asset_useful_life_edit").val(bk_fixedassets_with_depreciation[
                //                 0].asset_life_yrs);

                //             $("#fixed_asset_depreciation_start_date_edit").val(
                //                 bk_fixedassets_with_depreciation[0].depreciation_start_date);
                //             $("#fixed_asset_depreciation_rate_per_annum_edit").val(
                //                 bk_fixedassets_with_depreciation[0].depreciation_rate_per_annum);
                //             $("#fixed_asset_accumulated_depreciation_edit").val(
                //                 bk_fixedassets_with_depreciation[0].accumulated_depreciation);

                //             //////////////////////////////////////////////////////////////////

                //             $("#fixed_asset_depreciation_je_debit_account_edit").empty().trigger(
                //                 'change');
                //             $("#fixed_asset_depreciation_je_debit_account_edit").append(
                //                 '<option value="" selected disabled>Select Debit Account</option>'
                //             );
                //             accounts.forEach(accounts => {
                //                 if (accounts.id == bk_fixedassets_with_depreciation[0]
                //                     .depreciation_je_debit_acc) {
                //                     $("#fixed_asset_depreciation_je_debit_account_edit")
                //                         .append(
                //                             `<option value="${accounts.id}" selected>${accounts.account_name}</option>`
                //                         );
                //                 } else {
                //                     $("#fixed_asset_depreciation_je_debit_account_edit")
                //                         .append(
                //                             `<option value="${accounts.id}">${accounts.account_name}</option>`
                //                         );
                //                 }
                //             });

                //             ///////////////////////////////////////////////////////////////////
                //             $("#fixed_asset_depreciation_je_credit_account_edit").empty().trigger(
                //                 'change');
                //             $("#fixed_asset_depreciation_je_credit_account_edit").append(
                //                 '<option value="" selected disabled>Select Credit Account</option>'
                //             );
                //             accounts.forEach(accounts => {
                //                 if (accounts.id == bk_fixedassets_with_depreciation[0]
                //                     .depreciation_je_credit_acc) {
                //                     $("#fixed_asset_depreciation_je_credit_account_edit")
                //                         .append(
                //                             `<option value="${accounts.id}" selected>${accounts.account_name}</option>`
                //                         );
                //                 } else {
                //                     $("#fixed_asset_depreciation_je_credit_account_edit")
                //                         .append(
                //                             `<option value="${accounts.id}">${accounts.account_name}</option>`
                //                         );
                //                 }
                //             });

                //             ///////////////////////////////////////////////////////////////////
                //             $("#fixed_asset_loss_on_sale_je_debit_account_edit").empty().trigger(
                //                 'change');
                //             $("#fixed_asset_loss_on_sale_je_debit_account_edit").append(
                //                 '<option value="" selected disabled>Select Debit Account</option>'
                //             );
                //             accounts.forEach(accounts => {
                //                 if (accounts.id == bk_fixedassets_with_depreciation[0]
                //                     .depreciation_against_saleje_debit_acc) {
                //                     $("#fixed_asset_loss_on_sale_je_debit_account_edit")
                //                         .append(
                //                             `<option value="${accounts.id}" selected>${accounts.account_name}</option>`
                //                         );
                //                 } else {
                //                     $("#fixed_asset_loss_on_sale_je_debit_account_edit")
                //                         .append(
                //                             `<option value="${accounts.id}">${accounts.account_name}</option>`
                //                         );
                //                 }
                //             });


                //             ///////////////////////////////////////////////////////////////////
                //             $("#fixed_asset_loss_on_sale_je_credit_account_edit").empty().trigger(
                //                 'change');
                //             $("#fixed_asset_loss_on_sale_je_credit_account_edit").append(
                //                 '<option value="" selected disabled>Select Credit Account</option>'
                //             );
                //             accounts.forEach(accounts => {
                //                 if (accounts.id == bk_fixedassets_with_depreciation[0]
                //                     .depreciation_against_saleje_credit_acc) {
                //                     $("#fixed_asset_loss_on_sale_je_credit_account_edit")
                //                         .append(
                //                             `<option value="${accounts.id}" selected>${accounts.account_name}</option>`
                //                         );
                //                 } else {
                //                     $("#fixed_asset_loss_on_sale_je_credit_account_edit")
                //                         .append(
                //                             `<option value="${accounts.id}">${accounts.account_name}</option>`
                //                         );
                //                 }
                //             });




                //         }
                //     });

                // });

                $(document).on('click', '.edit_fixed_asset', function() {

                    var fixed_asset_id = $(this).attr('data-id');

                    $.ajax({
                        type: 'GET',
                        url: '/bookkeeper/fixed_asset/edit',
                        data: {
                            fixed_asset_id: fixed_asset_id
                        },
                        success: function(response) {

                            var bk_fixedassets = response.bk_fixedassets;
                            var bk_fixedassets_with_depreciation = response.bk_fixedassets_with_depreciation;
                            var accounts = response.accounts;
                            var sub_chart_of_accounts = response.sub_chart_of_accounts;

                            $("#fixed_asset_id_edit").val(bk_fixedassets[0].id);

                            if (bk_fixedassets[0].depreciation_option == 'With Depreciation Value') {
                                $("#withDepreciation_edit").prop('checked', true).prop('disabled', false);
                                $("#withoutDepreciation_edit").prop('checked', false).prop('disabled', true);

                                $("#depreciation_card_edit").show();
                                $("#view_je").show();
                            } else {
                                $("#withDepreciation_edit").prop('checked', false).prop('disabled', true);
                                $("#withoutDepreciation_edit").prop('checked', true).prop('disabled', false);

                                $("#depreciation_card_edit").hide();
                                $("#view_je").hide();
                            }

                            $("#view_je").attr("data-id", bk_fixedassets[0].id);

                            $("#fixed_asset_code_edit").val(bk_fixedassets[0].code);
                            $("#fixed_asset_name_edit").val(bk_fixedassets[0].asset_name);
                            $("#fixed_asset_purchased_date_edit").val(bk_fixedassets[0].purchased_date);
                            $("#fixed_asset_value_edit").val(bk_fixedassets[0].asset_value);
                            $("#fixed_asset_warranty_edit").val(bk_fixedassets[0].warranty);
                            $("#fixed_asset_serial_number_edit").val(bk_fixedassets[0].serial_number);
                            $("#fixed_asset_serial_warranty_edit").val(bk_fixedassets[0].serial_warranty);
                            $("#fixed_asset_remarks_edit").val(bk_fixedassets[0].remarks);

                            $("#fixed_asset_residual_value_edit").val(bk_fixedassets_with_depreciation[0].residual_value);
                            $("#fixed_asset_useful_life_edit").val(bk_fixedassets_with_depreciation[0].asset_life_yrs);

                            $("#fixed_asset_depreciation_start_date_edit").val(bk_fixedassets_with_depreciation[0].depreciation_start_date);
                            $("#fixed_asset_depreciation_rate_per_annum_edit").val(bk_fixedassets_with_depreciation[0].depreciation_rate_per_annum);
                            $("#fixed_asset_accumulated_depreciation_edit").val(bk_fixedassets_with_depreciation[0].accumulated_depreciation);

                            //////////////////////////////////////////////////////////////////

                            $("#fixed_asset_depreciation_je_debit_account_edit").empty().trigger('change');
                            $("#fixed_asset_depreciation_je_debit_account_edit").append('<option value="" selected disabled>Select Debit Account</option>');
                            accounts.forEach(account => {
                                if (account.id == bk_fixedassets_with_depreciation[0].depreciation_je_debit_acc) {
                                    $("#fixed_asset_depreciation_je_debit_account_edit").append(`<option value="${account.id}" selected>${account.code} - ${account.account_name}</option>`);
                                } else {
                                    $("#fixed_asset_depreciation_je_debit_account_edit").append(`<option value="${account.id}">${account.code} - ${account.account_name}</option>`);
                                }
                                sub_chart_of_accounts.forEach(subAccount => {
                                    if (subAccount.coaid == account.id) {
                                        if (subAccount.id == bk_fixedassets_with_depreciation[0].depreciation_je_debit_acc) {
                                            $("#fixed_asset_depreciation_je_debit_account_edit").append(`<option value="${subAccount.id}" selected>&nbsp;&nbsp;${subAccount.sub_code} - ${subAccount.sub_account_name}</option>`);
                                        } else {
                                            $("#fixed_asset_depreciation_je_debit_account_edit").append(`<option value="${subAccount.id}">&nbsp;&nbsp;${subAccount.sub_code} - ${subAccount.sub_account_name}</option>`);
                                        }
                                    }
                                });
                            });

                            ///////////////////////////////////////////////////////////////////
                            $("#fixed_asset_depreciation_je_credit_account_edit").empty().trigger('change');
                            $("#fixed_asset_depreciation_je_credit_account_edit").append('<option value="" selected disabled>Select Credit Account</option>');
                            accounts.forEach(account => {
                                if (account.id == bk_fixedassets_with_depreciation[0].depreciation_je_credit_acc) {
                                    $("#fixed_asset_depreciation_je_credit_account_edit").append(`<option value="${account.id}" selected>${account.code} - ${account.account_name}</option>`);
                                } else {
                                    $("#fixed_asset_depreciation_je_credit_account_edit").append(`<option value="${account.id}">${account.code} - ${account.account_name}</option>`);
                                }
                                sub_chart_of_accounts.forEach(subAccount => {
                                    if (subAccount.coaid == account.id) {
                                        if (subAccount.id == bk_fixedassets_with_depreciation[0].depreciation_je_credit_acc) {
                                            $("#fixed_asset_depreciation_je_credit_account_edit").append(`<option value="${subAccount.id}" selected>&nbsp;&nbsp;${subAccount.sub_code} - ${subAccount.sub_account_name}</option>`);
                                        } else {
                                            $("#fixed_asset_depreciation_je_credit_account_edit").append(`<option value="${subAccount.id}">&nbsp;&nbsp;${subAccount.sub_code} - ${subAccount.sub_account_name}</option>`);
                                        }
                                    }
                                });
                            });

                            ///////////////////////////////////////////////////////////////////
                            $("#fixed_asset_loss_on_sale_je_debit_account_edit").empty().trigger('change');
                            $("#fixed_asset_loss_on_sale_je_debit_account_edit").append('<option value="" selected disabled>Select Debit Account</option>');
                            accounts.forEach(account => {
                                if (account.id == bk_fixedassets_with_depreciation[0].depreciation_against_saleje_debit_acc) {
                                    $("#fixed_asset_loss_on_sale_je_debit_account_edit").append(`<option value="${account.id}" selected>${account.code} - ${account.account_name}</option>`);
                                } else {
                                    $("#fixed_asset_loss_on_sale_je_debit_account_edit").append(`<option value="${account.id}">${account.code} - ${account.account_name}</option>`);
                                }
                                sub_chart_of_accounts.forEach(subAccount => {
                                    if (subAccount.coaid == account.id) {
                                        if (subAccount.id == bk_fixedassets_with_depreciation[0].depreciation_against_saleje_debit_acc) {
                                            $("#fixed_asset_loss_on_sale_je_debit_account_edit").append(`<option value="${subAccount.id}" selected>&nbsp;&nbsp;${subAccount.sub_code} - ${subAccount.sub_account_name}</option>`);
                                        } else {
                                            $("#fixed_asset_loss_on_sale_je_debit_account_edit").append(`<option value="${subAccount.id}">&nbsp;&nbsp;${subAccount.sub_code} - ${subAccount.sub_account_name}</option>`);
                                        }
                                    }
                                });
                            });

                            ///////////////////////////////////////////////////////////////////
                            $("#fixed_asset_loss_on_sale_je_credit_account_edit").empty().trigger('change');
                            $("#fixed_asset_loss_on_sale_je_credit_account_edit").append('<option value="" selected disabled>Select Credit Account</option>');
                            accounts.forEach(account => {
                                if (account.id == bk_fixedassets_with_depreciation[0].depreciation_against_saleje_credit_acc) {
                                    $("#fixed_asset_loss_on_sale_je_credit_account_edit").append(`<option value="${account.id}" selected>${account.code} - ${account.account_name}</option>`);
                                } else {
                                    $("#fixed_asset_loss_on_sale_je_credit_account_edit").append(`<option value="${account.id}">${account.code} - ${account.account_name}</option>`);
                                }
                                sub_chart_of_accounts.forEach(subAccount => {
                                    if (subAccount.coaid == account.id) {
                                        if (subAccount.id == bk_fixedassets_with_depreciation[0].depreciation_against_saleje_credit_acc) {
                                            $("#fixed_asset_loss_on_sale_je_credit_account_edit").append(`<option value="${subAccount.id}" selected>&nbsp;&nbsp;${subAccount.sub_code} - ${subAccount.sub_account_name}</option>`);
                                        } else {
                                            $("#fixed_asset_loss_on_sale_je_credit_account_edit").append(`<option value="${subAccount.id}">&nbsp;&nbsp;${subAccount.sub_code} - ${subAccount.sub_account_name}</option>`);
                                        }
                                    }
                                });
                            });

                        }
                    });

                });

                $(document).on('click', '#updateFixedAsset', function() {
                    update_fixed_asset()
                })

                // Function to calculate depreciation for edit form
                function calculateEditDepreciation() {
                    // Get values from edit form fields
                    var assetValue = parseFloat($('#fixed_asset_value_edit').val()) || 0;
                    var residualValue = parseFloat($('#fixed_asset_residual_value_edit').val()) || 0;
                    var usefulLife = parseInt($('#fixed_asset_useful_life_edit').val()) || 1;
                    var warranty = parseInt($('#fixed_asset_warranty_edit').val()) || 0;
                    var method = $('#fixed_asset_depreciation_method_edit option:selected').text().trim();
                    
                    // Only calculate if we have valid inputs
                    if (assetValue > 0 && usefulLife > 0) {
                        // Calculate annual depreciation
                        var annualDepreciation = 0;
                        var depreciableAmount = assetValue - residualValue;
                        
                        if (method.includes("Straight Line") || method.includes("SLM")) {
                            annualDepreciation = depreciableAmount / usefulLife;
                        }
                        else if (method.includes("Double Declining") || method.includes("DDBM")) {
                            annualDepreciation = 2 * (depreciableAmount) / usefulLife;
                            // Ensure we don't depreciate below residual value
                            // if (assetValue - annualDepreciation < residualValue) {
                            //     annualDepreciation = depreciableAmount;
                            // }
                        }
                        $('#fixed_asset_accumulated_depreciation_edit').val(annualDepreciation.toFixed(2));
                        $('#fixed_asset_depreciation_rate_per_annum_edit').val(annualDepreciation.toFixed(2));

                        // Calculate and update depreciation rate per annum (as percentage)
                        // var depreciationRate = (annualDepreciation / assetValue) * 100;
                        // $('#fixed_asset_depreciation_rate_per_annum_edit').val(depreciationRate.toFixed(2));
                        
                        // // Calculate and update accumulated depreciation
                        // var accumulatedDepreciation = annualDepreciation * warranty;
                        // accumulatedDepreciation = Math.min(accumulatedDepreciation, depreciableAmount);
                        // $('#fixed_asset_accumulated_depreciation_edit').val(accumulatedDepreciation.toFixed(2));
                    }
                }

                // Set up event listeners for edit form fields
                $('#fixed_asset_value_edit, #fixed_asset_residual_value_edit, #fixed_asset_useful_life_edit, #fixed_asset_warranty_edit, #fixed_asset_depreciation_method_edit').on('change keyup', function() {
                    calculateEditDepreciation();
                });

                // Updated edit function with calculation
                function update_fixed_asset() {
                    // First calculate depreciation for edit form
                    calculateEditDepreciation();
                    
                    var fixed_asset_id_edit = $('#fixed_asset_id_edit').val();
                    var depreciationOption = $("#withDepreciation_edit").prop('checked') ? 'With Depreciation Value' : 'Without Depreciation Value';

                    // Get all form values including the calculated ones
                    var formData = {
                        _token: '{{ csrf_token() }}',
                        fixed_asset_id_edit: fixed_asset_id_edit,
                        depreciationOption: depreciationOption,
                        fixed_asset_code_edit: $('#fixed_asset_code_edit').val(),
                        fixed_asset_name_edit: $('#fixed_asset_name_edit').val(),
                        fixed_asset_purchased_date_edit: $('#fixed_asset_purchased_date_edit').val(),
                        fixed_asset_value_edit: parseFloat($('#fixed_asset_value_edit').val()) || 0,
                        fixed_asset_warranty_edit: parseInt($('#fixed_asset_warranty_edit').val()) || 0,
                        fixed_asset_serial_number_edit: $('#fixed_asset_serial_number_edit').val(),
                        fixed_asset_serial_warranty_edit: $('#fixed_asset_serial_warranty_edit').val(),
                        fixed_asset_remarks_edit: $('#fixed_asset_remarks_edit').val(),
                        fixed_asset_depreciation_method_edit: $('#fixed_asset_depreciation_method_edit').val(),
                        fixed_asset_residual_value_edit: parseFloat($('#fixed_asset_residual_value_edit').val()) || 0,
                        fixed_asset_useful_life_edit: parseInt($('#fixed_asset_useful_life_edit').val()) || 1,
                        fixed_asset_depreciation_start_date_edit: $('#fixed_asset_depreciation_start_date_edit').val(),
                        fixed_asset_depreciation_rate_per_annum_edit: parseFloat($('#fixed_asset_depreciation_rate_per_annum_edit').val()) || 0,
                        fixed_asset_accumulated_depreciation_edit: parseFloat($('#fixed_asset_accumulated_depreciation_edit').val()) || 0,
                        fixed_asset_depreciation_je_debit_account_edit: $('#fixed_asset_depreciation_je_debit_account_edit').val(),
                        fixed_asset_depreciation_je_credit_account_edit: $('#fixed_asset_depreciation_je_credit_account_edit').val(),
                        fixed_asset_loss_on_sale_je_debit_account_edit: $('#fixed_asset_loss_on_sale_je_debit_account_edit').val(),
                        fixed_asset_loss_on_sale_je_credit_account_edit: $('#fixed_asset_loss_on_sale_je_credit_account_edit').val()
                    };

                    $.ajax({
                        type: 'POST',
                        url: '/bookkeeper/fixed_asset/update',
                        data: formData,
                        success: function(data) {
                            if (data[0].status == 1) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully updated'
                                });
                                fixedAssetsTable();
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                });
                            }
                        }
                    });
                }

                $(document).on('click', '.delete_fixed_asset', function() {
                    var deletefixedassetId = $(this).attr('data-id')
                    Swal.fire({
                        text: 'Are you sure you want to remove fixed asset?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Remove'
                    }).then((result) => {
                        if (result.value) {
                            delete_fixedasset(deletefixedassetId)
                        }
                    })
                });

                function delete_fixedasset(deletefixedassetId) {
                    $.ajax({
                        type: 'GET',
                        url: '/bookkeeper/fixed_asset/delete',
                        data: {
                            deletefixedassetId: deletefixedassetId

                        },
                        success: function(data) {
                            if (data[0].status == 1) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully deleted'
                                })

                                fixedAssetsTable();
                            } else {
                                Toast.fire({
                                    type: 'error',
                                    title: data[0].message
                                })
                            }
                        }
                    });
                }

                $('#print_fixed_assets').on('click', function() {

                    window.open('/bookkeeper/fixed_asset_print');
                });

                $(document).on('click', '#view_je', function() {

                    var fixed_asset_id = $(this).attr('data-id')

                    $.ajax({
                        type: 'GET',
                        url: '/bookkeeper/fixed_asset/edit',
                        data: {
                            fixed_asset_id: fixed_asset_id
                        },
                        success: function(response) {

                            var bk_fixedassets = response.bk_fixedassets;
                            var bk_fixedassets_with_depreciation = response
                                .bk_fixedassets_with_depreciation;
                            var bk_fixedassets_with_depreciation_view_je = response
                                .bk_fixedassets_with_depreciation_view_je;

                            $("#fixed_asset_name_depreciation").text(bk_fixedassets[0].asset_name);

                            po_itemsTable()

                            // function po_itemsTable() {

                            //     $("#fixed_asset_depreciation_table").DataTable({
                            //         destroy: true,
                            //         autoWidth: false,
                            //         paging: false,
                            //         searching: false,
                            //         info: false,
                            //         data: bk_fixedassets_with_depreciation_view_je,
                            //         columns: [{
                            //                 "data": "depreciation_start_date"
                            //             },
                            //             {
                            //                 "data": "remarks"
                            //             },
                            //             {
                            //                 "data": null
                            //             },
                            //             {
                            //                 "data": "depreciation_rate_per_annum"
                            //             },
                            //             {
                            //                 "data": "depreciation_rate_per_annum"
                            //             }
                            //         ],
                            //         columnDefs: [{
                            //                 'targets': 0,
                            //                 'orderable': false,
                            //                 'createdCell': function(td, cellData,
                            //                     rowData, row, col) {
                            //                     $(td).html(
                            //                             `<span>${rowData.depreciation_start_date}</span>`
                            //                         )
                            //                         .addClass('align-middle');
                            //                 }
                            //             },
                            //             {
                            //                 'targets': 1,
                            //                 'orderable': false,
                            //                 'createdCell': function(td, cellData,
                            //                     rowData, row, col) {
                            //                     $(td).html(rowData.remarks)
                            //                         .addClass(
                            //                             'align-middle');
                            //                 }
                            //             },
                            //             {
                            //                 'targets': 2,
                            //                 'orderable': false,
                            //                 'createdCell': function(td, cellData,
                            //                     rowData, row, col) {
                            //                     var accountName = rowData.debit_account_name || rowData.credit_account_name;
                            //                     $(td).html(accountName)
                            //                         .addClass(
                            //                             'align-middle');
                            //                 }
                            //             },
                            //             {
                            //                 'targets': 3,
                            //                 'orderable': false,
                            //                 'createdCell': function(td, cellData,
                            //                     rowData, row, col) {
                            //                     var ratePerAnnum = rowData.debit_account_name ? rowData.depreciation_rate_per_annum : '--';
                            //                     $(td).html(ratePerAnnum)
                            //                         .addClass('align-middle');
                            //                 }
                            //             },
                            //             {
                            //                 'targets': 4,
                            //                 'orderable': false,
                            //                 'createdCell': function(td, cellData,
                            //                     rowData, row, col) {
                            //                     var ratePerAnnum = rowData.credit_account_name ? rowData.depreciation_rate_per_annum : '--';
                            //                     $(td).html(ratePerAnnum)
                            //                         .addClass('align-middle');
                            //                 }
                            //             }
                            //         ]
                            //     });
                            // }

                            // function po_itemsTable() {
                            //     // Transform the data to create separate rows for debit and credit
                            //     const transformedData = [];

                            //     bk_fixedassets_with_depreciation_view_je.forEach(row => {
                            //         // Add debit row if exists
                            //         if (row.debit_account_name) {
                            //             transformedData.push({
                            //                 date: row.depreciation_start_date,
                            //                 remarks: row.remarks,
                            //                 account: row.debit_account_name,
                            //                 debit_amount: row
                            //                     .depreciation_rate_per_annum,
                            //                 credit_amount: null, // Explicitly null for debit rows
                            //                 reference_id: row
                            //                     .id // For grouping related entries
                            //             });
                            //         }

                            //         // Add credit row if exists
                            //         if (row.credit_account_name) {
                            //             transformedData.push({
                            //                 date: row.depreciation_start_date,
                            //                 remarks: row.remarks,
                            //                 account: row.credit_account_name,
                            //                 debit_amount: null, // Explicitly null for credit rows
                            //                 credit_amount: row
                            //                     .depreciation_rate_per_annum,
                            //                 reference_id: row
                            //                     .id // For grouping related entries
                            //             });
                            //         }
                            //     });

                            //     $("#fixed_asset_depreciation_table").DataTable({
                            //         destroy: true,
                            //         autoWidth: false,
                            //         paging: false,
                            //         searching: false,
                            //         info: false,
                            //         data: transformedData,
                            //         columns: [{
                            //                 title: "Depreciation Date"
                            //             },
                            //             {
                            //                 title: "Explanation"
                            //             },
                            //             {
                            //                 title: "Account"
                            //             },
                            //             {
                            //                 title: "Debit Amount"
                            //             },
                            //             {
                            //                 title: "Credit Amount"
                            //             }
                            //         ],
                            //         columnDefs: [{
                            //                 targets: 0, // Date column
                            //                 render: function(data, type, row, meta) {
                            //                     // Only show date for first row of each transaction
                            //                     if (meta.row === 0 ||
                            //                         transformedData[meta.row - 1]
                            //                         .reference_id !== row
                            //                         .reference_id) {
                            //                         return row.date;
                            //                     }
                            //                     return '';
                            //                 },
                            //                 className: 'align-middle'
                            //             },
                            //             {
                            //                 targets: 1, // Explanation column
                            //                 render: function(data, type, row, meta) {
                            //                     // Only show remarks for first row of each transaction
                            //                     if (meta.row === 0 ||
                            //                         transformedData[meta.row - 1]
                            //                         .reference_id !== row
                            //                         .reference_id) {
                            //                         return row.remarks || '';
                            //                     }
                            //                     return '';
                            //                 },
                            //                 className: 'align-middle'
                            //             },
                            //             {
                            //                 targets: 2, // Account column
                            //                 render: function(data, type, row) {
                            //                     // Add debit/credit indicator before account name
                            //                     const typeIndicator = row
                            //                         .debit_amount !== null ?
                            //                         '<span class="badge bg-success me-2">DR</span>' :
                            //                         '<span class="badge bg-danger me-2">CR</span>';
                            //                     return typeIndicator + row.account;
                            //                 },
                            //                 className: 'align-middle'
                            //             },
                            //             {
                            //                 targets: 3, // Debit Amount column
                            //                 render: function(data, type, row) {
                            //                     return row.debit_amount !== null ?
                            //                         `<span class="text-success">${row.debit_amount}</span>` :
                            //                         '<span class="text-muted">-</span>';
                            //                 },
                            //                 className: 'align-middle text-end'
                            //             },
                            //             {
                            //                 targets: 4, // Credit Amount column
                            //                 render: function(data, type, row) {
                            //                     return row.credit_amount !== null ?
                            //                         `<span class="text-danger">${row.credit_amount}</span>` :
                            //                         '<span class="text-muted">-</span>';
                            //                 },
                            //                 className: 'align-middle text-end'
                            //             }
                            //         ],
                            //         createdRow: function(row, data, dataIndex) {
                            //             // Add border top for new transaction groups
                            //             if (dataIndex === 0 || transformedData[
                            //                     dataIndex - 1].reference_id !== data
                            //                 .reference_id) {
                            //                 $(row).css('border-top',
                            //                     '2px solid #dee2e6');
                            //             }

                            //             // Highlight debit/credit rows differently
                            //             if (data.debit_amount !== null) {
                            //                 $(row).addClass('debit-row');
                            //             } else {
                            //                 $(row).addClass('credit-row');
                            //             }
                            //         }
                            //     });
                            // }

                            function po_itemsTable() {
                                // Transform the data to create separate rows for debit and credit
                                const transformedData = [];

                                bk_fixedassets_with_depreciation_view_je.forEach(row => {
                                    // Add debit row if exists
                                    if (row.debit_account_name) {
                                        transformedData.push({
                                            date: row.depreciation_start_date,
                                            remarks: row.remarks,
                                            account: row.debit_account_name,
                                            debit_amount: row
                                                .depreciation_rate_per_annum,
                                            credit_amount: null, // Explicitly null for debit rows
                                            reference_id: row.id,
                                            is_debit: true
                                        });
                                    }

                                    // Add credit row if exists
                                    if (row.credit_account_name) {
                                        transformedData.push({
                                            date: row.depreciation_start_date,
                                            remarks: row.remarks,
                                            account: row.credit_account_name,
                                            debit_amount: null, // Explicitly null for credit rows
                                            credit_amount: row
                                                .depreciation_rate_per_annum,
                                            reference_id: row.id,
                                            is_debit: false
                                        });
                                    }

                                    if (row.debit_account_against_saleje) {
                                        transformedData.push({
                                            date: row.depreciation_start_date,
                                            remarks: row.remarks,
                                            account: row
                                                .debit_account_against_saleje,
                                            debit_amount: row
                                                .depreciation_rate_per_annum,
                                            credit_amount: null, // Explicitly null for debit rows
                                            reference_id: row.id,
                                            is_debit: true
                                        });
                                    }

                                    if (row.credit_account_against_saleje) {
                                        transformedData.push({
                                            date: row.depreciation_start_date,
                                            remarks: row.remarks,
                                            account: row
                                                .credit_account_against_saleje,
                                            debit_amount: null,
                                            credit_amount: row
                                                .depreciation_rate_per_annum,
                                            reference_id: row.id,
                                            is_debit: false
                                        });
                                    }

                                });

                                // const grandTotalDebit = transformedData.reduce(
                                //     (acc, row) => acc + (row.debit_amount || 0), 0);
                                // const grandTotalCredit = transformedData.reduce(
                                //     (acc, row) => acc + (row.credit_amount || 0), 0);
                                // transformedData.push({
                                //     date: '',
                                //     remarks: '<b>GRAND TOTAL</b>',
                                //     account: '',
                                //     debit_amount: grandTotalDebit,
                                //     credit_amount: grandTotalCredit,
                                //     reference_id: null,
                                //     is_debit: null
                                // });

                                $("#fixed_asset_depreciation_table").DataTable({
                                    destroy: true,
                                    autoWidth: false,
                                    paging: false,
                                    searching: false,
                                    info: false,
                                    data: transformedData,
                                    columns: [{
                                            title: "Depreciation Date"
                                        },
                                        {
                                            title: "Explanation"
                                        },
                                        {
                                            title: "Account"
                                        },
                                        {
                                            title: "Debit"
                                        },
                                        {
                                            title: "Credit"
                                        }
                                    ],
                                    columnDefs: [{
                                            targets: 0, // Date column
                                            render: function(data, type, row) {
                                                return row.date;
                                            },
                                            className: 'align-middle'
                                        },
                                        {
                                            targets: 1, // Explanation column
                                            render: function(data, type, row) {
                                                return row.remarks || '';
                                            },
                                            className: 'align-middle'
                                        },
                                        {
                                            targets: 2, // Account column
                                            render: function(data, type, row) {


                                                return row.account;
                                            },
                                            className: 'align-middle'
                                        },
                                        {
                                            targets: 3, // Debit Amount column
                                            render: function(data, type, row) {
                                                return row.is_debit ?
                                                    `<span class="text-success fw-bold">${row.debit_amount}</span>` :
                                                    '<span class="text-muted">-</span>';
                                            },
                                            className: 'align-middle text-end'
                                        },
                                        {
                                            targets: 4, // Credit Amount column
                                            render: function(data, type, row) {
                                                return !row.is_debit ?
                                                    `<span class="text-danger fw-bold">${row.credit_amount}</span>` :
                                                    '<span class="text-muted">-</span>';
                                            },
                                            className: 'align-middle text-end'
                                        }
                                    ],
                                    createdRow: function(row, data, dataIndex) {
                                        // Add border top for new transaction groups
                                        if (dataIndex === 0 || transformedData[
                                                dataIndex - 1].reference_id !== data
                                            .reference_id) {
                                            $(row).css('border-top',
                                                '2px solid #dee2e6');
                                        }

                                        // Add slight visual distinction between related entries
                                        if (dataIndex > 0 && transformedData[dataIndex -
                                                1].reference_id === data.reference_id) {
                                            $(row).addClass('related-entry');
                                        }
                                    }
                                });
                            }



                        }
                    });

                });

                $('#fixed_asset_modal_close').on('click', function() {
                    var hasData =
                        $("#fixed_asset_code").val().trim() !== "" ||
                        $("#fixed_asset_name").val().trim() !== "" ||
                        $("#fixed_asset_purchased_date").val().trim() !== "" ||

                        $("#fixed_asset_value").val().trim() !== "" ||
                        $("#fixed_asset_warranty").val().trim() !== "" ||
                        $("#fixed_asset_serial_number").val().trim() !== "" ||
                        $("#fixed_asset_serial_warranty").val().trim() !== "" ||
                        $("#fixed_asset_remarks").val().trim() !== "";
                    // $("#fixed_asset_residual_value").val().trim() !== "" ||
                    // $("#fixed_asset_useful_life").val().trim() !== "" ||
                    // $("#fixed_asset_depreciation_rate_per_annum").val().trim() !== "" ||
                    // $("#fixed_asset_accumulated_depreciation").val().trim() !== "";

                    // $(".highest_education_section .highest_education_row:visible").each(function() {
                    //     if (
                    //         $(this).find("#school_name").val().trim() != "" ||
                    //         $(this).find("#year_graduated").val().trim() != "" ||
                    //         $(this).find("#course").val().trim() != "" ||
                    //         $(this).find("#award").val().trim() != ""
                    //     ) {
                    //         hasData = true;
                    //         return false;
                    //     }
                    // })

                    //employment details

                    // if ($('#fixed_asset_depreciation_method').val()) {
                    //     hasData = true;
                    // }
                    if ($('#fixed_asset_depreciation_je_debit_account').val()) {
                        hasData = true;
                    }
                    if ($('#fixed_asset_depreciation_je_credit_account').val()) {
                        hasData = true;
                    }
                    if ($('#fixed_asset_loss_on_sale_je_debit_account').val()) {
                        hasData = true;
                    }
                    if ($('#fixed_asset_loss_on_sale_je_credit_account').val()) {
                        hasData = true;
                    }


                    if (hasData) {
                        // Confirm with the user before deleting all attendance data
                        Swal.fire({
                            // title: 'Create Grade Point Equivalency Reset',
                            text: 'You have unsaved changes. Would you like to save your work before leaving?',
                            type: 'warning',
                            showCancelButton: true,
                            cancelButtonText: 'Save Changes',
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Discard Changes',
                            reverseButtons: true
                        }).then((result) => {
                            if (result.value) {

                                // $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                                // $("#employee_picture").val("");

                                $('#fixed_asset_code').val("");
                                $('#fixed_asset_name').val("");
                                $('#fixed_asset_purchased_date').val("");

                                $('#fixed_asset_value').val("");
                                $('#fixed_asset_warranty').val("");
                                $('#fixed_asset_serial_number').val("");
                                $('#fixed_asset_serial_warranty').val("");
                                $('#fixed_asset_remarks').val("");
                                // $('#fixed_asset_residual_value').val("");
                                // $('#fixed_asset_useful_life').val("");
                                // $('#fixed_asset_depreciation_rate_per_annum').val("");
                                // $('#fixed_asset_accumulated_depreciation').val("");

                                //employment details
                                $('#fixed_asset_depreciation_method').val("").trigger('change');
                                $('#fixed_asset_depreciation_je_debit_account').val("").trigger(
                                    'change');
                                $('#fixed_asset_depreciation_je_credit_account').val("").trigger(
                                    'change');
                                $('#fixed_asset_loss_on_sale_je_debit_account').val("").trigger(
                                    'change');
                                $('#fixed_asset_loss_on_sale_je_credit_account').val("").trigger(
                                    'change');

                                // $(".highest_education_row").each(function() {
                                //     $(this).find('input').val("");
                                //     $(this).find('select').val("").trigger('change');
                                // });

                                // $(".highest_education_row:not(:first-of-type)").remove();




                            } else {
                                // Save employee
                                // $('#save_employeebtn__employeeInformation').click();
                                $('#add_fixed_asset').modal('show');

                                // $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                                // $("#employee_picture").attr('src', "/avatar/S(F) 1.png");

                            }
                        });
                    } else {
                        // Hide modal
                        $('#add_fixed_asset').modal('hide');

                        // $("#profile_picture").attr('src', "/avatar/S(F) 1.png");
                        // $("#employee_picture").val("");
                    }
                });



            });
        </script>
    @endsection
