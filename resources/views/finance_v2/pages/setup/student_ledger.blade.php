<!-- Student Ledger Tab Content -->
<div class="tab-pane fade show active" id="studentLedger">
    <!-- Content for Student Ledger -->


    <!-- Action Buttons Bar -->
    <div class="bg-light border-bottom p-2 d-flex justify-content-between">
        <div class="d-flex gap-2">
            <button id="adjustFees" class="btn btn-xs p-1.5 ml-2" style="background-color: #005728; color: white;">
                <i class="fas fa-money-bill-wave me-1"></i> Adjust Fees
            </button>
            <button id="addDiscount" class="btn btn-dark btn-xs p-1.5 ml-2">
                <i class="fas fa-plus me-1"></i> Add Discount
            </button>
            <button id="bookEntry" data-toggle="modal" data-target="#bookEntryModal" class="btn btn-xs p-1.5 ml-2"
                style="background-color: purple; color: white;">
                <i class="fas fa-book me-1"></i> Book Entry
            </button>
        </div>
        <div class="col-md-6 text-right">
            <button class="btn btn-sm py-1 px-3" style="background-color: #053473; color: white; font-size: 12px;">
                <i class="fas fa-print fa-fw mr-1"></i>Print SOA
            </button>
        </div>
    </div>

    <!-- Content Area -->
    <div class="p-3">
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="viewPayableItems">
            <label class="form-check-label small" for="viewPayableItems">
                View Payable Items
            </label>
        </div>

        <!-- Fees Table -->
        <div class="table-responsive mb-3">
            <table class="table table-bordered table-sm mb-0">
                <thead class="table-secondary">
                    <tr>
                        <th style="font-size: 0.8rem; font-weight: normal;">
                            Classification</th>
                        <th style="font-size: 0.8rem; font-weight: normal;">
                            Schedule of Fees</th>
                        <th style="font-size: 0.8rem; font-weight: normal;">Amount
                        </th>
                        <th style="font-size: 0.8rem; font-weight: normal;">Payment
                        </th>
                        <th style="font-size: 0.8rem; font-weight: normal;">Balance
                        </th>
                    </tr>
                </thead>
                <tbody id="feesTable">
                </tbody>
            </table>
        </div>

        <!-- Transaction Type -->
        <div class="row mb-3">
            <div class="col-12">
                <label class="form-label small mb-1" style="font-size: 0.9rem;">Transaction Type</label>
                <select class="form-select form-select-sm w-auto select2">
                    <option>Debit (+) Adjustment</option>
                </select>
            </div>
        </div>

        <!-- Discount History -->
        <div>
            <h6 class="mb-2">Discount and Adjustment History</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-sm mb-0" id="adjustmentTable ">
                    <thead class="table-secondary">
                        <tr>
                            <th style="font-size: 0.8rem; font-weight: normal;">
                                Transaction Date & Time</th>
                            <th style="font-size: 0.8rem; font-weight: normal;">
                                Particulars</th>
                            <th style="font-size: 0.8rem; font-weight: normal;">
                                Amount</th>
                            <th style="font-size: 0.8rem; font-weight: normal;">
                                Payment Type</th>
                            <th style="font-size: 0.8rem; font-weight: normal;">
                                Transaction By</th>
                            <th style="font-size: 0.8rem; font-weight: normal;">
                                Action</th>
                        </tr>
                    </thead>
                    <tbody id="adjustmentBody">
                    </tbody>
                    <tfoot>
                    </tfoot>
                </table>
            </div>
            <div class="modal-footer bg-white py-2">
                <!-- Modal Footer Content -->
                <div class="d-flex justify-content-between w-100 align-items-center">
                    <button id="refundBtn"data-toggle="modal" data-target="#disbursementModal"
                        class="btn btn-warning btn-sm">
                        <i class="fas fa-undo me-1"></i> Refund
                    </button>
                    <div class="text-end">
                        <span class="me-3">Total Tuition Balance:
                            <strong>0.00</strong></span>
                        <span>Overpayment: <strong class="text-danger">-3,000.00</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Adjustment Modal -->
<div class="modal fade" id="AdjustmentModal" tabindex="-1" aria-labelledby="AdjustmentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background-color: #d3d3d3;">
                <p class="modal-title" id="AdjustmentNameModalLabel">ADJUSTMENT</p>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-2"
                    style="border: 0.5px solid lightgray; box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);">
                    <div class="col-md-3">
                        <label class="form-label" style="font-weight: normal; font-size: 12px;">Adjustment
                            Type:</label>
                    </div>
                    <div class="col-md-5">
                        <input type="checkbox" id="debitAdjustment" name="adjustment_type" class="adjustment-checkbox">
                        <label for="debitAdjustment" class="adjustment-label" style="font-size: 12px;">Debit
                            Adjustment</label>
                        <br>
                        <span style="font-size: 10px; color: green;">+ Add Payable to Ledger</span>
                    </div>
                    <div class="col-md-4">
                        <input type="checkbox" id="creditAdjustment" name="adjustment_type" class="adjustment-checkbox">
                        <label for="creditAdjustment" class="adjustment-label" style="font-size: 12px;">Credit
                            Adjustment</label>
                        <br>
                        <span style="font-size: 10px; color: red;">- Deduct Payable from Ledger</span>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label"
                            style="font-weight: normal; font-size: 0.8rem;">Classification</label>
                        <br>
                        <select class="form-select w-100 h-85 select2" id="classificationSelect">
                            <option value="">Select Classification</option>
                            @foreach ($itemclassification as $classification)
                                <option value="{{ $classification->id }}">{{ $classification->description }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Reference
                            Number</label>
                        <input type="text" class="form-control form-control-sm w-100" id="refnum" readonly>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Amount</label>
                    <input type="number" class="form-control w-50" placeholder="0.00" step="0.01">
                </div>
                <div class="mb-3">
                    <button type="button" class="btn btn-link text-primary p-0" id="addItemBtn"
                        style="font-size: 0.8rem;"><i class="fas fa-plus"></i> Add Item</button>
                </div>

                <!-- Items Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered table-sm" id="itemsTable">
                        <thead class="table-dark text-dark" style="font-size: 0.8rem; background-color: #d3d3d3;">
                            <tr>
                                <th style="font-weight: normal; border: black;">Particulars</th>
                                <th style="font-weight: normal; border: black;">Principal Amount</th>
                                <th style="font-weight: normal; border: black;">Adjusted Amount</th>
                                <th style="font-weight: normal; border: black;">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>

                <div class="alert alert-info mt-3" style="font-size: 0.8rem;" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    The Selected items will be <strong>Added</strong> to the payables to all Selected students
                    under (Other Fees) Classification
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-sm text-center" id="saveAdjustment">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addItemModalLabel">Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12 mb-3" style="font-size: 0.8rem;">
                        <label class="form-label">Item</label>
                        <br>
                        <select class="form-select select2 w-100" id="itemTypeSelect">
                            <option value="0">Select Item</option>
                            <option value="new">Add New Item</option>
                            @foreach ($item as $items)
                                <option value="{{ $items->id }}">{{ $items->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Principal
                                Amount</label>
                            <input type="number" class="form-control bg-light" style="font-size: 0.8rem;"
                                value="570.00" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label class="form-label" style="font-weight: normal; font-size: 0.8rem;">Adjustment
                                Amount</label>
                            <input type="number" style="font-size: 0.8rem;" class="form-control" placeholder="0.00"
                                step="0.01">
                        </div>
                    </div>
                </div>

                <div class="alert alert-info mt-3" style="font-size: 0.8rem;" role="alert">
                    <i class="fas fa-info-circle me-2"></i>
                    The Selected items will be <span class="fw-bold">Added</span> to the payables to all
                    Selected
                    students under (Other Fees) Classification
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-xs text-center" id="saveItem">Save</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addNewItemModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Item</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label style="font-weight: normal; font-size: 0.8rem;">Item Code</label>
                        <input type="text" class="form-control" placeholder="Enter Item Code" id="itemCode">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label style="font-weight: normal; font-size: 0.8rem;">Item Description</label>
                        <input type="text" class="form-control" placeholder="Enter Item Description"
                            id="itemDescription">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label style="font-weight: normal; font-size: 0.8rem;">Chart of Account</label>
                        <select class="form-select select2" id="coaSelect">
                            <option value="">Select an option</option>
                            @foreach ($coa as $item)
                                <option value="{{ $item->id }}">{{ $item->account }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <label style="font-weight: normal; font-size: 0.8rem;">Item Type</label>

                    </div>
                    <div class="col-md-4">
                        <div>
                            <input type="checkbox" id="tuitionItem">
                            <label for="tuitionItem" style="font-weight: normal; font-size: 0.8rem;">Tuition
                                Item</label>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div>
                            <input type="checkbox" id="nonTuitionItem">
                            <label for="nonTuitionItem" style="font-weight: normal; font-size: 0.8rem;">Non-Tuition
                                Item</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" id="addItem">Add</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addDiscountModal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="addDiscountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document" style="max-width: 30%;">
        <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
            <div class="modal-header"
                style="background-color: #d9d9d9; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h5 class="modal-title">Discount</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                {{-- Discount Type Dropdown --}}
                <div class="mb-3">
                    <h6 class="font-weight-normal" style="font-size: 0.8rem;">Discount Type</h6>
                    <select class="form-control" style="height: 32px; border-color: #a2a2a2 !important;">
                        <option selected disabled>Select Discount Type</option>
                    </select>
                </div>

                {{-- Discounted To & Applied To --}}
                <p class="mb-1" style="font-size: 0.9rem;">
                    Discounted To: <span class="text-success">Grade 7 - Section (All)</span>
                </p>
                <p class="mb-3" style="font-size: 0.9rem;">
                    Applied To: <span class="text-success">13 Students</span>
                </p>

                {{-- Discount Card --}}
                <div class="card shadow-sm border-0"
                    style="background-color: #E5E5E5; border-radius: 12px; width: 250px;">
                    <div class="p-3">
                        <button type="button" class="close position-absolute" style="top: 8px; right: 10px;"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h6 class="fw-bold mb-1">Full Payment Discount (10%)</h6>
                        <p class="mb-1" style="font-size: 0.8rem;">
                            Applied to: <span class="text-success fw-bold">Classification
                                (Tuition)</span>
                        </p>
                        <p class="mb-1" style="font-size: 0.8rem;">
                            Discount as: <span class="text-success fw-bold">Whole Amount</span>
                        </p>
                    </div>
                    <div class="bg-white text-center p-2 border-top"
                        style="border-bottom-left-radius: 12px; border-bottom-right-radius: 12px;">
                        <a href="#" class="text-primary fw-bold text-decoration-none"
                            style="font-size: 0.8rem">
                            View Details <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>


                {{-- Notice Box --}}
                <div class="d-flex align-items-center p-2 mt-3"
                    style="background: #f8f9fa; border-radius: 8px; border: 1px solid #ddd;">
                    <i class="fas fa-info-circle mr-2" style="color: #6c757d;"></i>
                    <p class="mb-0" style="font-size: 0.6rem;">
                        The Selected Discount will be <span class="text-danger">Deducted</span> from
                        the payables to all selected students applied only to (Tuition) Classification.
                    </p>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-success w-100" style="border-radius: 8px;">Add
                    Discount</button>
            </div>

        </div>
    </div>
</div>

<!-- Add Books Modal -->
<div class="modal fade" id="bookEntryModal" data-backdrop="static" tabindex="-1" role="dialog"
    aria-labelledby="bookEntryLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
            <div class="modal-header" style="background-color:#d9d9d9; border-top--radius: 16px !important;">
                <h5 class="modal-title">Abcede, Reymund Jake T.</h5>
                <button type="button" id="close_refund_modal" class="close close_modal_fees" data-dismiss="modal"
                    aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Book Entry Section -->
                <h6 class="font-weight-bold">Book Entry</h6>

                <!-- Classification -->
                <div class="form-group">
                    <label for="classification">Classification</label>
                    <select class="form-control" id="classification">
                        <option selected="Books Fee">Books Fee</option>
                    </select>
                </div>

                <!-- Amount -->
                <div class="form-group d-flex align-items-center">
                    <label for="amount" class="mr-3 mb-0">Amount</label>
                    <input type="text" class="form-control" id="amount" value="0.00"
                        style="max-width: 150px;">

                    <div class="form-check ml-3">
                        <input type="checkbox" class="form-check-input" id="itemized_books_payable">
                        <label class="form-check-label" for="itemized_books_payable">Itemized Books
                            Payable</label>
                    </div>
                </div>




                <!-- Table (Initially Hidden) -->
                <div id="books_table" class="mt-3 d-none">
                    <label for="select_books">Select Books</label>
                    <select class="form-control mb-2" id="select_books"></select>

                    <table class="table table-bordered">
                        <thead class="thead-light">
                            <tr>
                                <th>Book Name</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" class="text-right font-weight-bold">Total</td>
                                <td class="font-weight-bold">0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>


                <!-- Checkboxes -->
                <div class="form-group">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="display_books">
                        <label class="form-check-label" for="display_books">Display to Cashier as
                            Books</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="display_itemized">
                        <label class="form-check-label" for="display_itemized">Display to Cashier as
                            Itemized</label>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="text-center">
                    <button type="button" class="btn btn-success">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('.select2').select2();
        $('#coaSelect').select2();

        $('#adjustFees').click(function() {
            $('#AdjustmentModal').modal('show');
        });

        $('#adjustFees').click(function() {
            $('#AdjustmentModal').modal('show');
        });

        $(document).on('click', '#addDiscount', function() {
            $('#addDiscountModal').modal('show');
        });

        $(document).on('click', '#addItemBtn', function() {
            $('#addItemModal').modal('show');
        });

        $('#itemTypeSelect').change(function() {
            if ($(this).val() === 'new') {
                $('#addNewItemModal').modal('show');
            }
        });

        $(document).on('click', '.view-account', function(e) {
            e.preventDefault();
            var studentId = $(this).data('id');
            var sectionid = $(this).data('sectionid');
            console.log(sectionid, 'Section ID');
            
            $('#studentAccountModal').modal('show');
            fetchStudentLedger(studentId);
            adjustmentHistory(studentId)
            $('#saveAdjustment').attr('data-studid', studentId);
            $('#printStudInfo').attr('data-studid', studentId);
            openStudentInformationModal(studentId)
            student_loads(studentId, sectionid, 0)
        });

        function fetchStudentLedger(studentId) {
            $.ajax({
                url: "studentaccounts/getStudentLedger",
                type: "GET",
                data: {
                    studid: studentId
                },
                success: function(response) {
                    if (!response.student) {
                        alert("No student ledger found.");
                        return;
                    }
                    console.log("Scholarship (Grantee):", response.student.grantee);

                    console.log(response.student, 'Student Data');

                    $("#scholarship").html(response.student.grantee ? response.student.grantee :
                        "N/A");
                    $("#studentStatus").text(response.student.student_status ? response.student
                        .student_status : "N/A");
                    $("#studentId").text(response.student.sid || "N/A");

                    let levelSectionText = response.student.levelname ? response.student.levelname +
                        " - " : "N/A";

                    if (response.student.levelname?.includes("COLLEGE")) {
                        levelSectionText += response.student.college_section || "N/A";
                        $("#courseStrand").text(response.student.college_course || "N/A");
                    } else if (response.student.levelname?.includes("GRADE 11") || response.student
                        .levelname?.includes("GRADE 12")) {
                        levelSectionText += (response.student.shs_section || "N/A") + " / " + (
                            response.student.shs_strand || "N/A");
                        $("#courseStrand").text(response.student.shs_strand || "N/A");
                    } else {
                        levelSectionText += response.student.basic_section || "N/A";
                        $("#courseStrand").text("N/A");
                    }

                    $("#levelSection").text(levelSectionText);

                    var ledgerBody = $("#feesTable");
                    ledgerBody.empty();

                    if (response.ledger.length > 0) {
                        var totalAmount = 0;
                        var totalPayment = 0;

                        response.ledger.forEach(item => {
                            var amount = parseFloat(item.amount);
                            var payment = parseFloat(item.payment);
                            var balance = (amount - payment).toFixed(2);
                            var balanceClass = balance < 0 ? 'text-danger' : '';

                            totalAmount += amount;
                            totalPayment += payment;

                            var row = `<tr>
                                    <td>${item.description}</td>
                                    <td>${item.particulars}</td>
                                    <td>${amount.toLocaleString()}</td>
                                    <td>${payment.toLocaleString()}</td>
                                    <td class="${balanceClass}">${balance}</td>
                                </tr>`;
                            ledgerBody.append(row);
                        });

                        var totalBalance = (totalAmount - totalPayment).toFixed(2);
                        var totalBalanceClass = totalBalance < 0 ? 'text-danger' : '';

                        var totalRow = `<tr class="font-weight-bold">
                                <td colspan="2" class="text-right">Total</td>
                                <td>${totalAmount.toLocaleString()}</td>
                                <td>${totalPayment.toLocaleString()}</td>
                                <td class="${totalBalanceClass}">${totalBalance}</td>
                            </tr>`;
                        ledgerBody.append(totalRow);
                    } else {
                        ledgerBody.append(
                            '<tr><td colspan="5" class="text-center">No ledger records found.</td></tr>'
                        );
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert("Error fetching student ledger.");
                }
            });
        }

        $('#refnum').val(generateRefNum());

        $('#itemModal').on('hidden.bs.modal', function() {
            // Clear input fields
            $('#itemCode').val('');
            $('#itemDescription').val('');
            $('#coaSelect').val('').trigger('change');

            // Uncheck checkboxes
            $('#tuitionItem').prop('checked', false);
            $('#nonTuitionItem').prop('checked', false);
        });

        $('#AdjustmentModal').on('hidden.bs.modal', function() {
            $('.adjustment-checkbox').prop('checked', false);
            $('#classificationSelect').val('').trigger('change');
            $('#refnum').val('');
            $('input[type="number"]').val('');
            $('#itemsTable tbody').empty();
        });

        $(document).on('click', '#addItem', function(e) {
            e.preventDefault();

            var itemCode = $('#itemCode').val();
            var itemDescription = $('#itemDescription').val();
            var chartOfAccount = $('#coaSelect').val();
            var tuitionItem = $('#tuitionItem').is(':checked') ? 1 : 0;
            var nonTuitionItem = $('#nonTuitionItem').is(':checked') ? 1 : 0;

            if (tuitionItem === 0 && nonTuitionItem === 0) {
                alert('Please select at least one item type.');
                return;
            }

            $.ajax({
                url: '/financev2/studentaccounts/additems',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    itemCode: itemCode,
                    itemDescription: itemDescription,
                    chartOfAccount: chartOfAccount,
                    tuitionItem: tuitionItem,
                    nonTuitionItem: nonTuitionItem
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.value) {
                                $('#itemModal').modal('hide');
                                // location.reload();
                            }
                        });
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        Swal.fire({
                            title: 'Error!',
                            text: Object.values(errors).join("\n"),
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        });

        var selectedItemDescription = '';

        $('#saveItem').on('click', function() {
            var selectedItem = $('#itemTypeSelect option:selected');
            selectedItemDescription = selectedItem.text(); // Store globally

            var itemID = selectedItem.val();
            var principalAmount = $('#addItemModal input[readonly]').val().trim();
            var adjustmentAmount = $('#addItemModal input[placeholder="0.00"]').val().trim();

            if (itemID === "0") {
                alert("Please select an item.");
                return;
            }
            if (adjustmentAmount === "") {
                adjustmentAmount = "0.00";
            }

            principalAmount = parseFloat(principalAmount).toFixed(2);
            adjustmentAmount = parseFloat(adjustmentAmount).toFixed(2);

            var newRow = `
                    <tr>
                        <td>${selectedItemDescription}</td>
                        <td>${principalAmount}</td>
                        <td>${adjustmentAmount}</td>
                        <td>
                            <button class="btn btn-danger btn-sm removeItem">Remove</button>
                        </td>
                    </tr>
                `;

            $('#itemsTable tbody').append(newRow);

            $('#itemTypeSelect').val('0').trigger('change');
            $('#addItemModal input[placeholder="0.00"]').val('');
            $('#addItemModal').modal('hide');
        });

        $(document).on('click', '.removeItem', function() {
            $(this).closest('tr').remove();
        });

        function adjustmentHistory(studentId) {
            $.ajax({
                url: "studentaccounts/studentLedgerHistory",
                type: "GET",
                data: {
                    studid: studentId
                },
                success: function(response) {
                    console.log(response, 'shockssssss');

                    $('#adjustmentBody').empty();

                    var totalAmount = 0;

                    if (response.adjustment_history.length > 0) {
                        $.each(response.adjustment_history, function(index, item) {
                            var amount = parseFloat(item.adjustment_amount) || 0;
                            totalAmount += amount;

                            var newRow = `
                                <tr>
                                    <td>${item.createddatetime}</td>
                                    <td>${item.description}</td>
                                    <td>₱ ${amount.toFixed(2)}</td> <!-- Fixed amount formatting -->
                                    <td>${item.payment_type ? item.payment_type : 'N/A'}</td>
                                    <td>${item.transactionby}</td>
                                    <td>
                                        <button class="btn btn-warning btn-sm" style="border-radius: 6rem; font-size: 0.8rem;" data-toggle="modal" data-target="#pinModal" data-id="${item.adjustment_id}">
                                            Void
                                        </button>
                                    </td>
                                </tr>
                            `;
                            $('#adjustmentBody').append(newRow);
                        });

                        var totalRow = `
                            <tr>
                                <td colspan="2" class="text-right"><strong>Total:</strong></td>
                                <td><strong>₱ ${totalAmount.toFixed(2)}</strong></td>
                                <td colspan="3"></td>
                            </tr>
                        `;
                        $('#adjustmentBody').append(totalRow);

                    } else {
                        $('#adjustmentBody').append(
                            '<tr><td colspan="6" class="text-center">No records found.</td></tr>'
                        );
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    alert("Error fetching student ledger.");
                }
            });
        }

        $(document).on('click', '#saveAdjustment', function(e) {
            e.preventDefault();

            var studid = $(this).attr('data-studid');
            var isdebit = 0;
            var iscredit = 0;

            if ($('#isdebit').prop('checked') == true) {
                isdebit = 1;
                iscredit = 0;
            } else {
                isdebit = 0;
                iscredit = 1;
            }

            var classification = $('#classificationSelect').val();
            var amount = $('input[type="number"]').val();
            var refnum = $('#refnum').val();

            console.log("Selected Item:", selectedItemDescription);

            if (isdebit === 0 && iscredit === 0) {
                alert('Please select at least one adjustment type.');
                return;
            }

            $.ajax({
                url: 'studentaccounts/saveAdjustment',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    studid: studid,
                    isdebit: isdebit,
                    iscredit: iscredit,
                    classid: classification,
                    amount: amount,
                    refnum: refnum,
                    particulars: selectedItemDescription
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: response.message,
                            type: 'success',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            if (result.value) {
                                $('#AdjustmentModal').modal('hide');
                                fetchStudentLedger(studid);
                                adjustmentHistory(studid);
                            }
                        });
                    }
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    if (errors) {
                        Swal.fire({
                            title: 'Error!',
                            text: Object.values(errors).join("\n"),
                            type: 'error',
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong.',
                            type: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                }
            });
        });
    });

    function generateRefNum() {
        return 'ADJ' + Date.now();
    }

    $('#AdjustmentModal').on('show.bs.modal', function() {
        $('#refnum').val(generateRefNum());
    });
</script>
