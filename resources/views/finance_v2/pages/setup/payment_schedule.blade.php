@extends('finance_v2.pages.setup.settings')

@php
    $sy = DB::table('sy')->get();
    $semester = DB::table('semester')->where('deleted', 0)->get();
    $months = DB::table('monthsetup')->orderBy('id')->get();
@endphp

@section('setup-content')
    <div class="content">
        <!-- Filter Section (unchanged) -->
        <div class="my-2 rounded-4 py-3 px-3 card">
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

        <!-- Payment Schedule Section -->
        <div class="card mt-4">
            <div class="card-header d-flex">
                <button class="btn btn-success btn-sm mr-2" id="addPaymentScheduleBtn">
                    + Add Payment Schedule
                </button>
                <button class="btn btn-primary btn-sm" id="addPaymentScheduleBtn">
                    <i class="fas fa-copy"></i> Copy to
                </button>
            </div>
            <div class="card-body">
                <table class="table table-sm table-bordered text-center" id="paymentScheduleTable"
                    width="100%">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>School Year</th>
                            <th>Semester</th>
                            <th>Status</th>
                            <th width="10%">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded via AJAX -->
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Payment Schedule Modal -->
    <div class="modal fade" id="paymentScheduleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Payment Schedule</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="paymentScheduleForm">
                        <input type="hidden" id="schedule_id">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="schedule_description" class="form-label">Schedule Description</label>
                                <input type="text" class="form-control" id="schedule_description" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">School Year</label>
                                <select class="form-select" id="modal_school_year" required>
                                    <option value="">Select School Year</option>
                                    @foreach ($sy as $item)
                                        <option value="{{ $item->id }}">{{ $item->sydesc }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Semester</label>
                                <select class="form-select" id="modal_semester" required>
                                    <option value="">Select Semester</option>
                                    @foreach ($semester as $item)
                                        <option value="{{ $item->id }}">{{ $item->semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="enable_percentage">
                                <label class="form-check-label" for="enable_percentage">Enable Percentage
                                    Distribution</label>
                            </div>

                            <label class="form-label">Payment Schedule Months</label>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Month</th>
                                            <th>Display As</th>
                                            <th>Percentage</th>
                                            <th>Include</th>
                                        </tr>
                                    </thead>
                                    <tbody id="monthRows">
                                        @foreach ($months as $month)
                                            <tr>
                                                <td>{{ $month->description }} <span class="year-placeholder"></span></td>
                                                <td>
                                                    <input type="text" class="form-control display-name"
                                                        data-month="{{ $month->description }}"
                                                        placeholder="Display name (e.g., Prelim)">
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control percentage"
                                                        data-month="{{ $month->description }}" placeholder="0.00"
                                                        step="0.01" min="0" max="100" disabled>
                                                </td>
                                                <td class="text-center">
                                                    <input type="checkbox" class="form-check-input include-month"
                                                        data-month="{{ $month->description }}" checked>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="text-end mt-2">
                                <small class="text-muted">Total Percentage: <span id="totalPercentage">0</span>%</small>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" id="is_active">
                            <label class="form-check-label" for="is_active">Set as Active</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveScheduleBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footerjavascript')
    <script>
        $(document).ready(function() {
            loadPaymentSchedules();
            // Initialize year placeholders based on selected school year
            $('#modal_school_year').change(function() {
                const year = $(this).find('option:selected').text().split('-')[0];
                $('.year-placeholder').text(year);
            });

            // Toggle percentage fields
            $('#enable_percentage').change(function() {
                const isEnabled = $(this).is(':checked');
                $('.percentage').prop('disabled', !isEnabled);

                if (!isEnabled) {
                    $('.percentage').val('');
                    $('#totalPercentage').text('0');
                }
            });

            // Calculate total percentage
            $(document).on('input', '.percentage', function() {
                calculateTotalPercentage();
            });

            // Add new payment schedule
            $('#addPaymentScheduleBtn').click(function() {
                $('#modalTitle').text('Add Payment Schedule');
                $('#paymentScheduleForm')[0].reset();
                $('#schedule_id').val('');
                $('#enable_percentage').prop('checked', false);
                $('.percentage').prop('disabled', true).val('');
                $('.include-month').prop('checked', true);
                $('.display-name').val('');
                $('#paymentScheduleModal').modal('show');
            });

            // Save payment schedule
            $('#saveScheduleBtn').click(function() {
                const monthsData = [];
                let totalPercentage = 0;
                const percentageEnabled = $('#enable_percentage').is(':checked');

                $('.include-month').each(function() {
                    const month = $(this).data('month');
                    const isIncluded = $(this).is(':checked');
                    const displayName = $(`.display-name[data-month="${month}"]`).val();
                    const percentage = parseFloat($(`.percentage[data-month="${month}"]`).val()) ||
                        0;

                    if (isIncluded) {
                        if (percentageEnabled) {
                            totalPercentage += percentage;
                        }
                        monthsData.push({
                            month_name: month,
                            display_name: displayName,
                            percentage: percentageEnabled ? percentage : null,
                            is_included: true
                        });
                    }
                });

                // Validate percentage if enabled
                if (percentageEnabled) {
                    if (totalPercentage !== 100) {
                        Swal.fire('Warning!', 'Total percentage must equal 100%. Current total: ' +
                            totalPercentage.toFixed(2) + '%', 'warning');
                        return;
                    }
                }

                const formData = {
                    id: $('#schedule_id').val(),
                    description: $('#schedule_description').val(),
                    school_year_id: $('#modal_school_year').val(),
                    semester_id: $('#modal_semester').val(),
                    is_active: $('#is_active').is(':checked') ? 1 : 0,
                    percentage_enabled: percentageEnabled ? 1 : 0,
                    months: monthsData
                };

                const url = formData.id ? '/financev2/payment-schedules/' + formData.id :
                    '/financev2/payment-schedules';
                const method = formData.id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        $('#paymentScheduleModal').modal('hide');
                        loadPaymentSchedules();
                        Swal.fire('Success!', 'Payment schedule saved successfully.',
                            'success');
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.',
                            'error');
                    }
                });
            });

            // Edit payment schedule
            $(document).on('click', '.edit-schedule', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: '/financev2/payment-schedules/' + id,
                    type: 'GET',
                    success: function(response) {
                        $('#modalTitle').text('Edit Payment Schedule');
                        $('#schedule_id').val(response.id);
                        $('#schedule_description').val(response.description);
                        $('#modal_school_year').val(response.school_year_id).trigger('change');
                        $('#modal_semester').val(response.semester_id);
                        $('#is_active').prop('checked', response.is_active == 1);
                        $('#enable_percentage').prop('checked', response.percentage_enabled ==
                            1);
                        $('.percentage').prop('disabled', response.percentage_enabled != 1);

                        // Reset all month fields
                        $('.include-month').prop('checked', false);
                        $('.percentage, .display-name').val('');

                        // Populate month data
                        response.months.forEach(month => {
                            $(`.include-month[data-month="${month.month_name.split(' ')[0]}"]`)
                                .prop('checked', month.is_included);
                            $(`.display-name[data-month="${month.month_name.split(' ')[0]}"]`)
                                .val(month.display_name);
                            $(`.percentage[data-month="${month.month_name.split(' ')[0]}"]`)
                                .val(month.percentage);
                        });

                        calculateTotalPercentage();
                        $('#paymentScheduleModal').modal('show');
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message || 'Something went wrong.',
                            'error');
                    }
                });
            });

            // Calculate total percentage
            function calculateTotalPercentage() {
                let total = 0;
                $('.percentage').each(function() {
                    if (!$(this).prop('disabled') && $(this).closest('tr').find('.include-month').is(
                            ':checked')) {
                        total += parseFloat($(this).val()) || 0;
                    }
                });
                $('#totalPercentage').text(total.toFixed(2));
            }

            // Load payment schedules
            function loadPaymentSchedules() {
                const schoolYear = $('#schoolYear').val();
                const semester = $('#semester').val();

                $.ajax({
                    url: '/financev2/payment-schedules',
                    type: 'GET',
                    data: {
                        school_year: schoolYear,
                        semester: semester
                    },
                    success: function(response) {
                        $('#paymentScheduleTable').DataTable({
                            destroy: true,
                            data: response,
                            columns: [{
                                    data: 'description'
                                },
                                {
                                    data: 'sydesc'
                                },
                                {
                                    data: 'semester'
                                },
                                {
                                    data: 'is_active',
                                    render: function(data) {
                                        return data ?
                                            '<span class="badge bg-success">Active</span>' :
                                            '<span class="badge bg-secondary">Inactive</span>';
                                    }
                                },
                                {
                                    data: 'id',
                                    render: function(data) {
                                        return `
                                <div class="d-flex justify-content-center ">
                                    <i class="far fa-edit text-primary edit-schedule mr-3" data-id="${data}" style="cursor: pointer;" title="Edit"></i>
                                    <i class="fas fa-trash-alt text-danger delete-schedule" data-id="${data}" style="cursor: pointer;" title="Delete"></i>
                                </div>
                            `;
                                    }
                                }
                            ]
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Error!', xhr.responseJSON.message ||
                            'Failed to load payment schedules.', 'error');
                    }
                });
            }

        });
    </script>
@endsection
