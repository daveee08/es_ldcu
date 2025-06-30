@extends('finance_v2.layouts.app2')
@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <style>
        .subj_tr {
            vertical-align: middle !important;
            cursor: pointer;
        }

        .stud_subj_tr {
            vertical-align: middle !important;
            cursor: pointer;
        }

        .shadow {
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .15) !important;
            border: 0 !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }
    </style>
@endsection
@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="mb-2">
                <div class="">
                    <h1><i class="fa fa-cog"></i> Book List</h1>
                </div>
                <div class="ml-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active font-weight-bold">Book List</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card shadow-lg">
                        {{-- <div class="card-header"> --}}
                        <br>
                        {{-- <hr> --}}
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-between" style="margin-left: 1rem;">

                                <button class="btn btn-success" data-toggle="modal" data-target="#AddBookForm">
                                    + Add Book Title
                                </button>
                            </div>
                        </div>
                        {{-- </div> --}}
                        <div class="card-body">
                            <div class="table">
                                <table id="booklistTable" class="table table-bordered table-sm w-100">
                                    <thead class="table-secondary">
                                        <tr>
                                            <th class="text-left">Code</th>
                                            <th class="text-left">Book Title</th>
                                            <th class="text-left">Fees Classification</th>
                                            <th class="text-left">Amount</th>
                                            <th class="text-center" style="width: 5rem;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="book_list">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="AddBookForm" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <p class="modal-title" id="exampleModalLongTitle">Add Book Form</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container mt-4">

                        <div class="form-group">
                            <label for="bookCode">Code</label>
                            <input type="text" class="form-control shadow-sm" id="bookCode"
                                placeholder="Enter Book Code">
                        </div>
                        <div class="form-group mt-4">
                            <label for="bookName">Book Name</label>
                            <input type="text" class="form-control shadow-sm" id="bookName"
                                placeholder="Enter Book Name">
                        </div>
                        <div class="form-group mt-4">
                            <label for="feesClassification">Fees Classification</label>
                            <select class="form-control shadow-sm" id="feesClassification">
                                <option selected value="1">Tuition Fee</option>
                                <option selected value="2">Other Fees</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control shadow-sm" id="amount" placeholder="Enter Amount">
                        </div>
                        <div class="text-right mt-4">
                            <button class="btn btn-success" id="create_book_btn">Save</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="EditBookForm" tabindex="-1" aria-labelledby="duplicateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered super-wide-modal">
            <div class="modal-content overflow-hidden">
                <div class="modal-header" style="background-color:#d9d9d9;">
                    <p class="modal-title" id="exampleModalLongTitle">Edit Book Form</p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container mt-4">

                        <input type="text" class="form-control form-control-sm" id="booklistTypeId" hidden required>
                        <div class="form-group">
                            <label for="bookCode">Code</label>
                            <input type="text" class="form-control shadow-sm" id="editbookCode"
                                placeholder="Enter Book Code">
                        </div>
                        <div class="form-group mt-4">
                            <label for="bookName">Book Name</label>
                            <input type="text" class="form-control shadow-sm" id="editbookName"
                                placeholder="Enter Book Name">
                        </div>
                        <div class="form-group mt-4">
                            <label for="feesClassification">Fees Classification</label>
                            <select class="form-control shadow-sm" id="editfeesClassification">
                                <option selected value="1">Tuition Fee</option>
                                <option selected value="2">Other Fees</option>
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label for="amount">Amount</label>
                            <input type="number" class="form-control shadow-sm" id="editamount"
                                placeholder="Enter Amount">
                        </div>
                        <div class="text-right mt-4">
                            <button class="btn btn-primary" id="update_book_btn">Update</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footerjavascript')
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            })


            $('#create_book_btn').on('click', function(event) {
                // event.preventDefault();
                create_book()
            });

            function create_book() {
                var bookCode = $('#bookCode').val();
                var bookName = $('#bookName').val();
                var amount = $('#amount').val();
                var feesClassification = $('#feesClassification').val();



                $.ajax({
                    type: 'GET',
                    url: '/financev2/setup/booklist/create',
                    data: {
                        bookCode: bookCode,
                        bookName: bookName,
                        amount: amount,
                        feesClassification: feesClassification
                    },
                    success: function(data) {
                        if (data[0].status == 2) {
                            Toast.fire({
                                type: 'warning',
                                title: data[0].message
                            })
                            booklistable()

                        } else if (data[0].status == 1) {
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully created'
                            })
                            $("#bookCode").val("");
                            $("#bookName").val("");
                            $("#amount").val("");
                            $("#feesClassification").val("").trigger('change');

                            booklistable()
                        } else {
                            Toast.fire({
                                type: 'error',
                                title: data[0].message
                            })
                        }
                    }
                });
            }
        });

        booklistable()

        function booklistable() {

            $("#booklistTable").DataTable({
                destroy: true,
                // data:temp_subj,
                // bInfo: true,
                autoWidth: false,
                // lengthChange: true,
                // stateSave: true,
                // serverSide: true,
                // processing: true,
                ajax: {
                    url: '/financev2/setup/booklist/fetch',
                    type: 'GET',
                    dataSrc: function(json) {
                        return json;
                    }
                },
                columns: [{
                        "data": "code"
                    },
                    {
                        "data": "title"
                    },
                    {
                        "data": "classification_id"
                    },
                    {
                        "data": "amount"
                    },
                    {
                        "data": null
                    },
                ],
                columnDefs: [

                    {
                        'targets': 0,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.code).addClass(
                                'align-middle');
                        }
                    },
                    {
                        'targets': 1,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(rowData.title).addClass(
                                'align-middle');
                        }
                    },

                    {
                        'targets': 2,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            // $(td).html('');
                            $(td).html(rowData.classification_id).addClass('align-middle');

                        }
                    },

                    {
                        'targets': 3,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            $(td).html(parseInt(rowData.amount).toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                    ","))
                                .addClass(
                                    'align-middle');
                        }
                    },

                    {
                        'targets': 4,
                        'orderable': false,
                        'createdCell': function(td, cellData, rowData, row, col) {
                            var buttons =
                                '<a href="javascript:void(0)" class="edit_booklist" data-id="' +
                                rowData.id +
                                '"><i class="far fa-edit text-primary"></i></a> ' +
                                '<a href="javascript:void(0)" class="delete_booklist" data-id="' +
                                rowData.id +
                                '"><i class="far fa-trash-alt text-danger"></i></a>';
                            $(td)[0].innerHTML = buttons;
                            $(td).addClass('text-center justify-content-center align-middle');
                        }
                    }

                ],
                // lengthMenu: [
                //     [10, 25, 50, 100],
                //     [10, 25, 50, 100]
                // ],
                // pageLength: 10,
                // dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
                //     "<'row'<'col-sm-12'tr>>" +
                //     "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",

            });
        }

        $(document).on('click', '.edit_booklist', function() {


            var booklist_id = $(this).attr('data-id')

            $('#EditBookForm').modal()
            // $('#AddBookForm').modal('hide')

            $.ajax({
                type: 'GET',
                url: '/financev2/setup/booklist/edit',
                data: {
                    booklist_id: booklist_id
                },
                success: function(response) {



                    var booklist_selected = response.booklist;


                    $("#booklistTypeId").val(booklist_selected[0].id);
                    $("#editbookCode").val(booklist_selected[0].code);
                    $("#editbookName").val(booklist_selected[0].title);

                    $("#editfeesClassification").val(booklist_selected[0]
                        .classification_id).trigger('change');

                    $("#editamount").val(booklist_selected[0].amount);


                }
            });
        });


        $(document).on('click', '#update_book_btn', function() {
            update_booklist()
        });

        function update_booklist() {
            var booklistTypeId = $('#booklistTypeId').val();
            var editbookCode = $('#editbookCode').val();
            var editbookName = $('#editbookName').val();
            var editfeesClassification = $('#editfeesClassification').val();
            var editamount = $('#editamount').val();

            $.ajax({
                type: 'GET',
                url: '/financev2/setup/booklist/update',
                data: {
                    booklistTypeId: booklistTypeId,
                    editbookCode: editbookCode,
                    editbookName: editbookName,
                    editfeesClassification: editfeesClassification,
                    editamount: editamount

                },
                success: function(data) {
                    if (data[0].status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data[0].message
                        })
                        booklistable();

                    } else if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully updated'
                        })

                        booklistable();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        })
                    }
                }
            });
        }

        $(document).on('click', '.delete_booklist', function() {
            var deletebooklistId = $(this).attr('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.value) {
                    delete_booklist(deletebooklistId);
                }
            });
        });

        function delete_booklist(deletebooklistId) {
            $.ajax({
                type: 'GET',
                url: '/financev2/setup/booklist/delete',
                data: {
                    deletebooklistId: deletebooklistId
                },
                success: function(data) {
                    if (data[0].status == 2) {
                        Toast.fire({
                            type: 'warning',
                            title: data[0].message
                        });
                        booklistable();
                    } else if (data[0].status == 1) {
                        Toast.fire({
                            type: 'success',
                            title: 'Successfully deleted'
                        });
                        booklistable();
                    } else {
                        Toast.fire({
                            type: 'error',
                            title: data[0].message
                        });
                    }
                }
            });
        }
    </script>
@endsection
