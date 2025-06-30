@extends('hr.layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- jQuery first -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <!-- Bootstrap 4 JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ asset('plugins/toastr/toastr.min.css') }}">
    <style>
        table td,
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            margin-top: -9px;
        }
        table th {
            font-size: 0.8rem;
        }

        label {
            font-size: 0.9rem;
        }

    </style>
    {{-- @include('hr_v2.general_config.profilecss') --}}
    <section class="content-header p-0">
        <div class="container-fluid m-0 p-0">
            <!-- Page Header -->
            <div class="d-flex align-items-center">
                <i class="fa fa-cog fa-lg"></i>
                <div style="width: 0.9rem;"></div>
                <h4>Office Setup</h4> 
            </div>
            <div style="width: 1rem;"></div>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item" style="font-size: 0.9rem;"><a href="#"
                            style="color:rgba(0,0,0,0.5);">Home</a></li>
                    <li class="breadcrumb-item active" style="font-size: 1.0rem; color:rgba(0,0,0,0.5);">General Setup</li>
                </ol>
            </nav>

            <div class="mb-3" style="color: black;  font-size: 13px;">
                <ul class="nav nav-tabs" style="border-bottom: 6px solid #d9d9d9; font-weight: 600; gap: 10px;">
                    <li class="nav-item"
                        style="width: 12%; text-align: center;border-top-left-radius: 10px; border-top-right-radius: 10px;border: 1px solid #d9d9d9;">
                        <a href="/hr/payroll/generalsetup/department" class="nav-link" {{ Request::url() == url('/hr/payroll/generalsetup/department') ? 'active' : '' }}
                            >Department</a>
                    </li>
                    <li class="nav-item"
                    style="width: 12%; text-align: center; border: 1px solid #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <a href="/hr/payroll/generalsetup/offices" class="nav-link " {{ Request::url() == url('/hr/payroll/generalsetup/offices') ? 'active' : '' }}
                            style="color: black;  font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Office</a>
                    </li>
                    <li class="nav-item"
                    style="width: 12%; text-align: center; border: 1px solid #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <a href="/hr/payroll/generalsetup/designation" class="nav-link"
                            {{ Request::url() == url('/hr/payroll/generalsetup/designation') ? 'active' : '' }}
                            style="color: black;">Designation</a>
                    </li>
                    <li class="nav-item"
                    style="width: 12%; text-align: center; border: 1px solid #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <a href="/hr/payroll/generalsetup/requirements" class="nav-link" {{ Request::url() == url('/hr/payroll/generalsetup/requirements') ? 'active' : '' }}
                            style="color: black;">Requirements</a>
                    </li>
                    <li class="nav-item"
                        style="width: 12%; text-align: center; border: 1px solid #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <a href="/hr/leave_setup" class="nav-link" {{ Request::url() == url('/hr/leave_setup') ? 'active' : '' }}
                            style="color: black;">Leave</a>
                    </li>
                </ul>
            </div>

            <!-- Header Section -->
            <section class="content-header p-0"
                style="border-top-left-radius: 10px; border-top-right-radius: 10px; border: 1px solid #d9d9d9; background-color: #d9d9d9; margin-bottom: 10px;">
                <div class="container-fluid m-0 p-0">
                    <div class="d-flex align-items-center justify-content-start">
                        <label
                            style="font-weight: normal; font-size: 1rem; padding-left: 10px; font-weight: 600;">Office</label>
                    </div>
                </div>
            </section>
            <div class="card-body p-0 ">
                <button class="btn btn-success btn-sm"
                    style="border-radius: 6px; background-color:#00470F; border: none; font-size: 12.5px; margin: 0px;"
                    data-toggle="modal" data-target="#addOfficeModal">
                    <i class="fa fa-plus"></i> Add Office</button>
                <div class="table-responsive-sm">
                    <table id="officeTable" class="table table-sm table-striped table-bordered mt-2"
                        style="border: 1px solid #D9D9D9; border-radius: 10px; width: 100%; ">
                        <thead class="">
                            <tr>
                                <th width="40%">Offices Name</th>
                                <th width="30%">Department</th>
                                <th width="26%">Office Head</th>
                                <th width="4%" class="text-center pr-1">Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 10pt!important">
                        </tbody>
                    </table>
                </div>
            </div>
    </section>

    <!-- Add Office Modal -->
    <div class="modal fade" id="addOfficeModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="addOfficeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header p-2" style="background-color:#D9D9D9; border-radius: 15px 15px 0 0 !important;">
                    <h6 class="modal-title" id="">+ Add Office</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addOfficeForm">
                        <div class="mb-4">
                            <label for="officeName" class="form-label" style="font-weight: 600;">Office Name</label>
                            <input type="text" class="form-control" style="border-radius: 10px" id="officeName"
                                placeholder="Enter Office Name" required>
                        </div>
                        <div class="mb-4">
                            <label for="officeHead" class="form-label" style="font-weight: 600;">Office
                                Head</label>
                            <select class="form-control" style="border-radius: 10px"; id="officeHead">
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="department" class="form-label" style="font-weight: 600;">Department</label>
                            <select class="form-control" style="border-radius: 10px"; id="department" required>
                                <option value=""></option>
                                @foreach(DB::table('hr_departments')->where('deleted', 0)->get() as $department)
                                    <option value="{{ $department->id }}">{{ $department->department }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex justify-content-center">
                            <button type="submit" class="btn btn-success btn-sm"
                                style="border-radius: 6px; background-color:#00470F; border: none;"> Save
                                <i class="fa fa-save"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editOfficeModal" tabindex="-1" data-backdrop="static" role="dialog" aria-labelledby="editOfficeModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" style="border-radius: 15px;">
                <div class="modal-header p-2" style="background-color:#D9D9D9; border-radius: 15px 15px 0 0 !important;">
                    <h6 class="modal-title" id=""><i class="fa fa-edit"></i> Edit Office</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="mb-4">
                        <label for="officeName" class="form-label" style="font-weight: 600;">Office Name</label>
                        <input type="text" class="form-control" style="border-radius: 10px" id="editofficeName"
                            placeholder="Enter Office Name" required>
                    </div>
                    <div class="mb-4">
                        <label for="officeHead" class="form-label" style="font-weight: 600;">Office
                            Head</label>
                        <select class="form-control" style="border-radius: 10px"; id="editofficeHead">
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="editdepartment" class="form-label" style="font-weight: 600;">Department</label>
                        <select class="form-control" style="border-radius: 10px"; id="editdepartment" required>
                            <option value=""></option>
                            @foreach(DB::table('hr_departments')->where('deleted', 0)->get() as $department)
                                <option value="{{ $department->id }}">{{ $department->department }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="button" id="updateOffice" class="btn btn-success btn-sm"
                            style="border-radius: 6px; background-color:#00470F; border: none;"> Save
                            <i class="fa fa-save"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"> -->
    
    </section>
@endsection
@section('footerjavascript')
    <script>
        $('#department').select2({
            placeholder: 'Select Department',
            allowClear: true
        }); 

        $('#editdepartment').select2({
            placeholder: 'Select Department',
            allowClear: true
        }); 

        $('#officeHead').select2({
            placeholder: 'Select Office Head',
            allowClear: true
        }); 

        $('#editofficeHead').select2({
            placeholder: 'Select Office Head',
            allowClear: true
        }); 
    </script>
    <script>
        

        $(document).ready(function() {

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000,
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#addOfficeForm").submit(function(e) {
                e.preventDefault();
    
                let officeName = $("#officeName").val();
                let officeHead = $("#officeHead").val();
                let department = $("#department").val();

                $.ajax({
                    url: "/hr/settings/offices/addoffice", // Adjust with your Laravel route
                    type: "POST",
                    data: {
                        office_name: officeName,
                        office_head: officeHead,
                        department: department

                    },
                    success: function(response) {
                        if(response == 'exists'){
                            Toast.fire({
                                type: 'error',
                                title: 'Office already exists!'
                            });
                        }else{
                            Toast.fire({
                                type: 'success',
                                title: 'Office Created!'
                            }); 
                            $('#addOfficeForm')[0].reset();
                            $("#officeId").val("");
                            $('#department').val('').trigger('change');
                            getOffices();

                        }
                    },
                    error: function(error) {
                        alert("Something went wrong!");
                    }
                });
            });

            $(document).on('click','#updateOffice',function(){
                let id = $(".edit_office").data('id');
                let officeName = $("#editofficeName").val();
                let officeHead = $("#editofficeHead").val();
                let department = $("#editdepartment").val();

                $.ajax({
                    url: "/hr/settings/offices/updateoffice", // Adjust with your Laravel route
                    type: "GET",
                    data: {
                        office_name: officeName,
                        office_head: officeHead,
                        department: department,
                        id: id

                    },
                    success: function(response) {
                        if(response == 'exists'){
                            Toast.fire({
                                type: 'error',
                                title: 'Office already exists!'
                            });
                        }else{
                            Toast.fire({
                                type: 'success',
                                title: 'Office Updated!'
                            }); 
                            $('#editOfficeModal').modal('hide');
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('#editdepartment').val('').trigger('change');
                            getOffices();

                        }
                    },
                })    
            });

            getOffices();
            function getOffices() {
                $.ajax({
                    url: "/hr/settings/offices/getoffice",
                    type: "GET",
                    success: function(response) {
                        officeTable(response);
                    }
                })
            }

            function officeTable(offices){
                
                $('#officeTable').DataTable({
                    destroy: true,
                    order: false,
                    lengthChange: false,
                    data: offices,
                    columns : [
                        {"data" : "officename"},
                        {"data" : "department"},
                        {"data" : null},
                        {"data" : null},
                    ],
                    columnDefs: [
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                $(td).html(`<a href="#" class="">${rowData.headname ? rowData.headname : ''}</a>`)

                            }
                        },
                        {
                            targets: 3,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if (td) {
                                    $(td).html(`
                                        <a href="javascript:void(0)" class="section_link mb-0" style="white-space: nowrap">
                                            <button class="btn btn-sm btn-primary edit_office" id="" data-toggle="modal" data-target="#editOfficeModal" data-id="${rowData.id}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger delete_office" id="" data-id="${rowData.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </a>
                                    `).addClass('align-middle text-center pr-1')
                                    .css('vertical-align', 'middle');
                                }
                            }
                        },
                    ]
                })
            }

            $(document).on('click', '.edit_office', function(e) {
                let id = $(this).data('id');
                $.ajax({
                    url: "/hr/settings/offices/getoffice",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $("#editofficeName").val(response[0].officename);
                        $("#editofficeHead").val(response[0].headname);
                        $("#editdepartment").val(response[0].department).trigger('change');
                    }
                })
            })

            $(document).on('click', '.delete_office', function(e) {
                let id = $(this).data('id');
                Swal.fire({
                    title: 'Delete Office',
                    text: "Are you sure you want to delete this office?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if(result.value){
                        $.ajax({
                            url: "/hr/settings/offices/deleteoffice",
                            type: "GET",
                            data: {
                                id: id
                            },
                            success: function(response) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Office Deleted!'
                                }); 
                                getOffices();
                            }
                        })
                    }
                })
            });

        });

    </script>
@endsection
