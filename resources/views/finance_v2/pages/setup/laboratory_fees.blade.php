@extends('finance_v2.pages.setup.settings')

@php
    $subjects = DB::table('college_subjects')
        ->join('college_prospectus', function ($join) {
            $join->on('college_subjects.id', '=', 'college_prospectus.subjectID');
            $join->where('college_prospectus.deleted', 0);
        })
        ->where('college_subjects.deleted', 0)
        ->select('college_subjects.*')
        ->get();
    $sy = DB::table('sy')->get();
    $semester = DB::table('semester')->where('deleted', 0)->get();
@endphp

@section('setup-content')
    <div class="content">
        <div class="my-2 rounded-4 py-3 px-3" style="background-color: #d6d6d4;">
            <div class="d-flex align-items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-funnel"
                    viewBox="0 0 16 16">
                    <path
                        d="M1.5 1.5A.5.5 0 0 1 2 1h12a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-.128.334L10 8.692V13.5a.5.5 0 0 1-.342.474l-3 1A.5.5 0 0 1 6 14.5V8.692L1.628 3.834A.5.5 0 0 1 1.5 3.5z" />
                </svg>
                <p class="mx-1 my-1">Filter</p>
            </div>
            <div class="row">
                <div class="input-field col-2">
                    <p for="schoolYear" class="form-label d-block fw-light mb-1 mt-2" style="font-size: 14px;">School Year
                    </p>
                    <select class="w-100 custom-select form-select-md rounded-4" id="schoolYear"
                        style="border-radius: 5px !important; font-size: 14px;">
                        <option value="" selected>All</option>
                        @foreach ($sy as $item)
                            <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-field col-2">
                    <p for="semester" class="form-label d-block fw-light mb-1 mt-2" style="font-size: 14px;">Semester</p>
                    <select class="w-100 custom-select form-select-md rounded-4" id="semester"
                        style="border-radius: 5px !important; font-size: 14px;">
                        <option value="" selected>All</option>
                        @foreach ($semester as $item)
                            <option value="{{ $item->id }}">{{ $item->semester }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="my-2 rounded-4 py-3 px-3" style="background-color: #d6d6d4;">
            <h6>Laboratory Fees</h6>
            <hr class="my-3" style="border-color: #000">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Left: Buttons -->
                <div>
                    <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#addLabFeeModal">+ Add
                        Laboratory Fee</button>
                    <button class="btn btn-xs" style="background-color: #71753d; color: white;" data-toggle="modal"
                        data-target="#duplicateLabFeeModal"><i class="fas fa-copy"></i> COPY to</button>
                </div>

                <!-- Right: Search Bar -->
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" id="customSearch" class="form-control" placeholder="Search..."
                        aria-label="Search">
                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>

            </div>

            <!-- Lab Fees Table -->
            <div class="table-responsive mt-4">
                <table id="labFeesTable" class="table table-striped table-bordered" style="width:100%; font-size: 13px;">
                    <thead>
                        <tr>
                            <th>Subject Code</th>
                            <th>Description</th>
                            <th>Lab Units</th>
                            <th>Assessed Units</th>
                            <th>Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>


        <!-- Add Laboratory Fee Modal -->
        <div class="modal fade" id="addLabFeeModal" tabindex="-1" aria-labelledby="addLabFeeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content overflow-hidden" style="border-radius: 16px !important;">
                    <div class="modal-header" style="background-color:#cac9c9; border-top--radius: 16px !important;">
                        <h6 class="modal-title" id="addLabFeeModalLabel">Add Laboratory Fee</h6>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        @csrf
                        <form id="addLabFeeForm" style="font-size: 13px;">
                            <div class="mb-3">
                                <label for="subject-select" class="form-label">Select Subject</label>
                                <select id="subject-select" class="form-control">
                                    <option value="" selected disabled>Select Subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" data-code="{{ $subject->subjCode }}"
                                            data-desc="{{ $subject->subjDesc }}" data-lab="{{ $subject->labunits }}">
                                            {{ $subject->subjCode }} - {{ $subject->subjDesc }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="row mb-3">
                                <div class="col-3">
                                    <div class="form-group">
                                        <label class="form-label">Subject Code</label>
                                        <input type="text" class="form-control" style="border-radius: 11px !important;"
                                            id="subjectCode" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">Subject Description</label>
                                        <input type="text" class="form-control" style="border-radius: 11px !important;"
                                            id="subjectDescription" readonly>
                                    </div>
                                </div>
                                <div class="col-3">
                                    <div id="assessUnitsField" class="form-group">
                                        <label class="form-label">Laboratory Units</label>
                                        <input type="text" class="form-control"
                                            style="border-radius: 11px !important;" id="assessUnits" disabled>
                                    </div>
                                </div>
                            </div>
                            <div class="col">
                                <input type="checkbox" id="assessedUnits">
                                <label for="assessedUnits">Assessed Units</label>
                            </div>
                            <div class="row mb-3">
                                <div id="assessedUnitsField" class="col-5" style="display: none;">
                                    <div class="form-group">
                                        <label class="form-label">Units</label>
                                        <input type="number" step="0.01" style="border-radius: 11px !important;"
                                            class="form-control" id="inputAssessedUnits" placeholder="00.00"
                                            min="0">
                                    </div>
                                </div>
                                <div id="perUnitField" class="col-5" style="display: none;">
                                    <div class="form-group">
                                        <label class="form-label">Per Unit</label>
                                        <input type="number" step="0.01" style="border-radius: 11px !important;"
                                            class="form-control" id="perUnit" readonly placeholder="0.00"
                                            value="620.17" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-5" id="amountField">
                                    <div class="form-group">
                                        <label class="form-label">Amount</label>
                                        <input type="number" step="0.01" style="border-radius: 11px !important;"
                                            class="form-control" id="amount" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-success">ADD</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editLabFeeModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Lab Fee</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="editLabFeeForm">
                            <input type="hidden" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label">Subject Code</label>
                                <input type="text" class="form-control" id="edit_subjectCode" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subject Description</label>
                                <input type="text" class="form-control" id="edit_subjectDescription" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Laboratory Units</label>
                                <input type="text" class="form-control" id="edit_assessUnits" readonly>
                            </div>
                            <div class="mb-3">
                                <input type="checkbox" id="edit_assessedUnits">
                                <label for="edit_assessedUnits">Assessed Units</label>
                            </div>
                            <div class="row mb-3">
                                <div id="edit_assessedUnitsField" class="col-6" style="display: none;">
                                    <div class="form-group">
                                        <label class="form-label">Units</label>
                                        <input type="number" step="0.01" class="form-control"
                                            id="edit_inputAssessedUnits" placeholder="00.00" min="0">
                                    </div>
                                </div>
                                <div id="edit_perUnitField" class="col-6" style="display: none;">
                                    <div class="form-group">
                                        <label class="form-label">Per Unit</label>
                                        <input type="number" step="0.01" class="form-control" id="edit_perUnit"
                                            readonly placeholder="0.00" value="620.17" min="0">
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Amount</label>
                                <input type="number" step="0.01" class="form-control" id="edit_amount"
                                    placeholder="0.00">
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            // Load lab fees data on page load
            loadLabFees();

            // Subject select change event
            $('#subject-select').change(function() {
                var selectedOption = $(this).find('option:selected');
                $('#subjectCode').val(selectedOption.data('code'));
                $('#subjectDescription').val(selectedOption.data('desc'));
                $('#assessUnits').val(selectedOption.data('lab'));
            });

            // Assessed units checkbox toggle
            $('#assessedUnits').change(function() {
                if ($(this).is(':checked')) {
                    $('#assessedUnitsField, #perUnitField').show();
                    calculateAmount();
                } else {
                    $('#assessedUnitsField, #perUnitField').hide();
                    $('#amount').val('');
                }
            });

            // Edit modal assessed units checkbox toggle
            $('#edit_assessedUnits').change(function() {
                if ($(this).is(':checked')) {
                    $('#edit_assessedUnitsField, #edit_perUnitField').show();
                    calculateEditAmount();
                } else {
                    $('#edit_assessedUnitsField, #edit_perUnitField').hide();
                    $('#edit_amount').val('');
                }
            });

            // Calculate amount when assessed units changes
            $('#inputAssessedUnits').on('input', function() {
                calculateAmount();
            });

            // Calculate amount in edit modal
            $('#edit_inputAssessedUnits').on('input', function() {
                calculateEditAmount();
            });

            // Add new lab fee
            $('#addLabFeeForm').submit(function(e) {
                e.preventDefault();

                let formData = {
                    subject_id: $('#subject-select').val(),
                    subject_code: $('#subjectCode').val(),
                    subject_description: $('#subjectDescription').val(),
                    lab_units: $('#assessUnits').val(),
                    assessed_units: $('#assessedUnits').is(':checked') ? $('#inputAssessedUnits')
                        .val() : null,
                    per_unit: $('#assessedUnits').is(':checked') ? $('#perUnit').val() : null,
                    amount: $('#amount').val()
                };

                $.ajax({
                    url: '/financev2/lab-fees',
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Success!', 'Lab fee added successfully.', 'success');
                        $('#addLabFeeForm')[0].reset();
                        $('#subjectCode, #subjectDescription, #assessUnits').val('');
                        $('#assessedUnitsField, #perUnitField').hide();
                        $('#assessedUnits').prop('checked', false);
                        loadLabFees();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.',
                            'error');
                    }
                });
            });

            // Edit lab fee - open modal
            $(document).on('click', '.edit-btn', function() {
                let id = $(this).data('id');

                $.ajax({
                    url: '/financev2/lab-fees/' + id,
                    type: 'GET',
                    success: function(response) {
                        $('#edit_id').val(response.id);
                        $('#edit_subjectCode').val(response.subjCode);
                        $('#edit_subjectDescription').val(response.subjDesc);
                        $('#edit_assessUnits').val(response.lab_units);

                        if (response.assess_units) {
                            $('#edit_assessedUnits').prop('checked', true);
                            $('#edit_assessedUnitsField, #edit_perUnitField').show();
                            $('#edit_inputAssessedUnits').val(response.assess_units);
                            $('#edit_perUnit').val(620.17);
                        } else {
                            $('#edit_assessedUnits').prop('checked', false);
                            $('#edit_assessedUnitsField, #edit_perUnitField').hide();
                        }

                        $('#edit_amount').val(response.amount);

                        $('#editLabFeeModal').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.',
                            'error');
                    }
                });
            });

            // Update lab fee
            $('#editLabFeeForm').submit(function(e) {
                e.preventDefault();

                let id = $('#edit_id').val();
                let formData = {
                    subject_id: $('#subject-select').val(),
                    subject_code: $('#edit_subjectCode').val(),
                    subject_description: $('#edit_subjectDescription').val(),
                    lab_units: $('#edit_assessUnits').val(),
                    assessed_units: $('#edit_assessedUnits').is(':checked') ? $(
                        '#edit_inputAssessedUnits').val() : null,
                    per_unit: $('#edit_assessedUnits').is(':checked') ? $('#edit_perUnit').val() : null,
                    amount: $('#edit_amount').val()
                };

                $.ajax({
                    url: '/financev2/lab-fees/' + id,
                    type: 'PUT',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        Swal.fire('Success!', 'Lab fee updated successfully.', 'success');
                        $('#editLabFeeModal').modal('hide');
                        loadLabFees();
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.',
                            'error');
                    }
                });
            });

            // Delete lab fee
            $(document).on('click', '.delete-btn', function() {
                let id = $(this).data('id');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/financev2/lab-fees/' + id,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                Swal.fire('Deleted!', 'Lab fee has been deleted.',
                                    'success');
                                loadLabFees();
                            },
                            error: function(xhr) {
                                Swal.fire('Error!', xhr.responseJSON.message ||
                                    'Something went wrong.', 'error');
                            }
                        });
                    }
                });
            });

            // Function to load lab fees
            function loadLabFees() {
                $.ajax({
                    url: '/financev2/lab-fees',
                    type: 'GET',
                    success: function(response) {
                        let tableBody = $('#labFeesTable tbody');
                        tableBody.empty();

                        response.forEach(function(fee) {
                            let row = `
                        <tr>
                            <td>${fee.subjCode}</td>
                            <td>${fee.subjDesc}</td>
                            <td>${fee.lab_units}</td>
                            <td>${fee.assess_units || '-'}</td>
                            <td>${parseFloat(fee.amount).toFixed(2)}</td>
                            <td>
                                <button class="btn btn-sm btn-primary edit-btn" data-id="${fee.id}">Edit</button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="${fee.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                            tableBody.append(row);
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Failed to load lab fees.',
                            'error');
                    }
                });
            }

            // Calculate amount based on assessed units
            function calculateAmount() {
                let assessedUnits = parseFloat($('#inputAssessedUnits').val()) || 0;
                let perUnit = parseFloat($('#perUnit').val()) || 0;
                let amount = assessedUnits * perUnit;
                $('#amount').val(amount.toFixed(2));
            }

            // Calculate amount in edit modal
            function calculateEditAmount() {
                let assessedUnits = parseFloat($('#edit_inputAssessedUnits').val()) || 0;
                let perUnit = parseFloat($('#edit_perUnit').val()) || 0;
                let amount = assessedUnits * perUnit;
                $('#edit_amount').val(amount.toFixed(2));
            }
        });
    </script>
@endsection
