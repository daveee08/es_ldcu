@extends('finance_v2.pages.setup.settings')

@section('setup-content')
    <div class="content-header"></div>

    <div class="content">
        <div class="row">
            <!-- Classification Type Table -->
            <div class="col-sm-12 col-md-6">
                <div class="card h-100" style="border-radius: 8px; box-shadow:none !important;">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0">Classification Type</p>
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addClassificationModal">
                                + Add Classification
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="classificationsTable" class="table table-bordered table-sm" width="100%">
                            <thead class="bg-light">
                                <tr>
                                    <th>Description</th>
                                    <th>Chart Of Account</th>
                                    <th class="text-center" width="5%">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Fees Items Table -->
            <div class="col-sm-12 col-md-6">
                <div class="card h-100" style="border-radius: 8px; box-shadow:none !important;">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <p class="mb-0">Fees Items</p>
                            <button class="btn btn-sm btn-success" data-toggle="modal" data-target="#addItemModal">
                                + Add Item
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="feesItemsTable" class="table table-sm table-bordered" width="100%">
                            <thead class="bg-light">
                                <tr>
                                    <th>Item Code</th>
                                    <th>Item Description</th>
                                    <th>Item Type</th>
                                    <th class="text-center" width="5%">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- modals --}}
        <!-- Add Classification Modal -->
        <div class="modal fade" id="addClassificationModal" tabindex="-1" role="dialog"
            aria-labelledby="addClassificationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 15px;">
                    <div class="modal-header">
                        <h5 class="modal-title" style="font-size: 0.9rem;" id="addClassificationModalLabel">Add
                            Classification Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addClassificationForm">
                        <div class="modal-body" style="font-size: 0.9rem;">
                            <div class="form-group">
                                <label>Classification Type Description</label>
                                <input type="text" class="form-control" id="classificationTypeDesc"
                                    onkeyup="this.value = this.value.toUpperCase()" required>
                            </div>
                            <div class="form-group">
                                <label>Chart of Account</label>
                                <select class="form-control" id="chartOfAccount">
                                    <option value="1001">1001 - Asset</option>
                                    <option value="1002">1002 - Liability</option>
                                    <option value="1003">1003 - Equity</option>
                                    <option value="1004">1004 - Revenue</option>
                                    <option value="1005">1005 - Expense</option>
                                </select>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" style="background-color: #1c743c; border-radius: 5px;"
                                class="btn btn-primary btn-xs">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Add Item Modal -->
        <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog" aria-labelledby="addItemModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 15px; width: 100%;">
                    <div class="modal-header">
                        <h5 class="modal-title" style="font-size: 0.9rem;" id="addItemModalLabel">Add Item</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="addItemForm">
                        <div class="modal-body" style="font-size: 0.9rem;">
                            <div class="form-group">
                                <label>Item Code</label>
                                <input type="text" class="form-control" onkeyup="this.value = this.value.toUpperCase()"
                                    id="itemCode" required>
                            </div>
                            <div class="form-group">
                                <label>Item Description</label>
                                <input type="text" class="form-control"
                                    onkeyup="this.value = this.value.toUpperCase()" id="itemDescription" required>
                            </div>
                            <div class="form-group">
                                <label>Chart of Account</label>
                                <select class="form-control" id="itemChartOfAccount">
                                    <option value="1001">1001 - Asset</option>
                                    <option value="1002">1002 - Liability</option>
                                    <option value="1003">1003 - Equity</option>
                                    <option value="1004">1004 - Revenue</option>
                                    <option value="1005">1005 - Expense</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Item Type</label>
                                <div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="itemType" id="tuitionItem"
                                            value="tuition" required>
                                        <label class="form-check-label" for="tuitionItem">Tuition Item</label>
                                    </div>
                                    <p style="font-size: 0.6rem; margin-left: 25px; margin-top: -10px;">*Tuition items are
                                        included in School Fees (Payables)</p>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="itemType"
                                            id="nonTuitionItem" value="non-tuition">
                                        <label class="form-check-label" for="nonTuitionItem">Non-Tuition Item</label>
                                    </div>
                                    <p style="font-size: 0.6rem; margin-left: 25px; margin-top: -10px;">*Non-tuition items
                                        are not included in School Fees</p>

                                </div>
                            </div>
                        </div>
                        <div class="modal-footer justify-content-center">
                            <button type="submit" style="background-color: #1c743c; border-radius: 5px;"
                                class="btn btn-primary btn-xs">SAVE</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('footerjavascript')
    <script>
        $(document).ready(function() {
            fetchClassifications();
            fetchItems();

            $('#addClassificationForm').on('submit', function(e) {
                e.preventDefault();
                $('#addClassificationForm button[type=submit]').prop('disabled', true);
                storeClassif();
            });
            $('#addItemForm').on('submit', function(e) {
                e.preventDefault();
                $('#addItemForm button[type=submit]').prop('disabled', true);
                storeItem();
            });

            $(document).on('click', '.edit_classic', function() {
                
            })
            $(document).on('click', '.delete_classif', function() {
                const id = $(this).data('id');
                deleteClassif(id);
            })

        })

        function deleteClassif(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: `{{ route('classif.destroy')}}`,
                        method: 'POST',
                        data: {
                            id: id,
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Classification has been deleted.'
                                });
                                fetchClassifications();
                            }
                        },
                        error: function() {
                            Toast.fire({
                                type: 'error',
                                title: 'Failed to delete classification.'
                            });
                        }
                    });
                }
            });
        } 

        function fetchClassifications() {
            $.ajax({
                url: '{{ route('classif.fetch') }}',
                method: 'GET',
                success: function(response) {
                    $('#classificationsTable').DataTable({
                        destroy: true,
                        data: response,
                        columns: [{
                                data: 'description'
                            },
                            {
                                data: 'chartofacc'
                            },
                            {
                                data: null,
                                className: 'text-center pr-1',
                                render: (data, type, row) =>
                                    ` <div class="row text-center">
                                    <div class="col-6" style="border-right: 1px solid #ddd">
                                            <a href="#"><i class="text-info fas fa-pencil-alt edit_classic" data-id="${row.id}"></i></a>
                                        </div>
                                        <div class="col-6">
                                            <a href="#"><i class="text-danger far fa-trash-alt delete_classif" data-id="${row.id}"></i></a>
                                        </div>
                                    </div>
                                </div>`
                            }
                        ],
                        responsive: true,
                        columnDefs: [{
                            targets: 2,
                            className: 'text-center'
                        }]
                    });
                }
            });
        }

        function fetchItems() {
            $.ajax({
                url: '{{ route('fees-item.fetch') }}',
                method: 'GET',
                success: function(response) {
                    populateFeesItemsTable(response);
                }
            });
        }

        function storeClassif() {
            $.ajax({
                url: '{{ route('classif.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    desc: $('#classificationTypeDesc').val(),
                    coa: $('#chartOfAccount').val(),
                },
                success: function(response) {
                    if (response.success) {
                        $('#addClassificationForm button[type=submit]').prop('disabled',
                            false);
                        $('#addClassificationModal').modal('hide');
                        Toast.fire({
                            type: 'success',
                            title: 'Classification added successfully.'
                        });
                        fetchClassifications();
                    }
                },
                error: function(xhr) {
                    let errorMessage = 'Failed to save classification.';
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        // Display validation errors
                        errorMessage = 'Validation errors:<br>';
                        $.each(xhr.responseJSON.errors, function(key, value) {
                            errorMessage += `${value.join(', ')}<br>`;
                        });
                    }
                    Toast.fire({
                        type: 'error',
                        title: errorMessage
                    });
                    $('#addClassificationForm button[type=submit]').prop('disabled', false);

                }
            });
        }

        function storeItem() {
            $.ajax({
                url: '{{ route('fees-item.store') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    item_code: $('#itemCode').val(),
                    item_desc: $('#itemDescription').val(),
                    item_type: $('input[name="itemType"]:checked').val() === 'tuition' ?
                        'tuition item' : 'non-tuition item',
                },
                success: function(response) {
                    if (response.success) {
                        $('#addItemForm button[type=submit]').prop('disabled', false);
                        $('#addItemModal').modal('hide');
                        Toast.fire({
                            type: 'success',
                            title: 'Fees item added successfully.'
                        });
                        populateFeesItemsTable(response.data);
                    }
                },
                error: function(xhr) {
                    let errorMessage = "An error occurred. Please try again.";

                    // Check if it's a Laravel validation error
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        errorMessage = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                    }
                    // Check if it's a SQL/DB error (like duplicate entry)
                    else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message
                            .replace("SQLSTATE[23000]: Integrity constraint violation: 1062", "")
                            .replace("Duplicate entry", "Item code already exists:")
                            .replace(/for key.*$/, ""); // Removes technical DB details
                    }

                    Toast.fire({
                        type: 'error',
                        title: errorMessage
                    });
                    $('#addItemForm button[type=submit]').prop('disabled', false);
                }
            });
        }

        function populateFeesItemsTable(response) {
            $('#feesItemsTable').DataTable({
                destroy: true,
                data: response,
                columns: [{
                        data: 'itemcode'
                    },
                    {
                        data: 'description'
                    },
                    {
                        data: 'type'
                    },
                    {
                        data: null,
                        className: 'text-center pr-1',
                        render: (data, type, row) =>
                            ` <div class="row text-center">
                                    <div class="col-6" style="border-right: 1px solid #ddd">
                                            <a href="#"><i class="text-info fas fa-pencil-alt edit_item" data-id="${row.id}"></i></a>
                                        </div>
                                        <div class="col-6">
                                            <a href="#"><i class="text-danger far fa-trash-alt delete_item" data-id="${row.id}"></i></a>
                                        </div>
                                    </div>
                                </div>`
                    }
                ],

            });
        }
    </script>
@endsection
