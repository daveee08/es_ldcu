<!-- OLD Accounts Tab Content -->
<div class="container-fluid ledger-container" style="margin-left: -2.5%;">
    <hr style="border-color: #7d7d7d;">

    <div class="row mb-3">
        <div class="col-md-6 d-flex align-items-center">
            <div class="form-row w-100">
                <div class="col" style="font-size: 13px;">
                    <select class="form-control select2" style="height: 32px; border-color: #a2a2a2 !important;">
                        <option>2023-2024</option>
                    </select>
                </div>
                <div class="col" style="font-size: 13px;">
                    <select class="form-control select2" style="height: 32px; border-color: #a2a2a2 !important;">
                        <option>1st Semester</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-sm py-1 px-3" style="background-color: #053473; color: white; font-size: 12px;">
                <i class="fas fa-print fa-fw mr-1"></i>Print SOA
            </button>
        </div>
    </div>

    <hr style="border-color: #7d7d7d;">

    <div class="text-right">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="viewPayablesItem">
            <label class="form-check-label" for="viewPayablesItem">View Payables Item</label>
        </div>
    </div>

    <div class="table-responsive mb-3">
        <table class="table table-bordered table-sm mb-0 w-100">
            <thead style="background-color:#d9d9d9">
                <tr>
                    <th style="font-weight: 600; border-color: #a2a2a2 !important;">Classification
                    </th>
                    <th style="font-weight: 600; border-color: #a2a2a2 !important;">Schedule of Fees
                    </th>
                    <th style="font-weight: 600; border-color: #a2a2a2 !important;">Amount</th>
                    <th style="font-weight: 600; border-color: #a2a2a2 !important;">Payment</th>
                    <th style="font-weight: 600; border-color: #a2a2a2 !important;">Balance</th>
                </tr>
            </thead>
            <tbody>
                
            </tbody>
            <tfoot>
                
            </tfoot>
        </table>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h6 class="text-black">Payment Transaction</h6>
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <div class="d-inline-flex">
                <div style="font-size: 13px;">Item Type</div>
                <select class="form-control ml-2" style="font-size: 13px;">
                    <option>Tuition Type</option>
                </select>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-hover table-transactions mt-3 table-sm w-100">
            <thead style="background-color:#d3d3d3">
                <tr>
                    <th style="border-color: #a2a2a2 !important; font-weight: 600;">Transaction Date &
                        Time
                    </th>
                    <th style="border-color: #a2a2a2 !important; font-weight: 600;">Description</th>
                    <th style="border-color: #a2a2a2 !important; font-weight: 600;">OR No.#</th>
                    <th style="border-color: #a2a2a2 !important; font-weight: 600;">Amount Paid</th>
                    <th style="border-color: #a2a2a2 !important; font-weight: 600;">Cashier</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="border-color: #a2a2a2 !important;">09/17/24, 9:17am</td>
                    <td style="border-color: #a2a2a2 !important;">Tuition</td>
                    <td style="border-color: #a2a2a2 !important;">101121</td>
                    <td style="border-color: #a2a2a2 !important;">10,000.00</td>
                    <td style="border-color: #a2a2a2 !important;">Vanessa Claire Nacalaban</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>