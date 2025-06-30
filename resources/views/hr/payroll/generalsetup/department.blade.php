@extends('hr.layouts.app')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                <i class="fas fa-link fa-lg "></i>
                <h4>Department</h4>
            </div>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                            style="color:rgba(0,0,0,0.5);">Home</a></li>
                    <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Department</li>
                </ol>
            </nav>

            <div class="mb-3" style="color: black;  font-size: 13px;">
                <ul class="nav nav-tabs" style="border-bottom: 6px solid #d9d9d9; font-weight: 600; gap: 10px;">
                    <li class="nav-item"
                        style="width: 12%; text-align: center;border-top-left-radius: 10px; border-top-right-radius: 10px;border: 1px solid #d9d9d9;">
                        <a href="/hr/payroll/generalsetup/department" class="nav-link" {{ Request::url() == url('/hr/payroll/generalsetup/department') ? 'active' : '' }}
                            style="color: black;  font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Department</a>
                    </li>
                    <li class="nav-item"
                    style="width: 12%; text-align: center; border: 1px solid #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                    <a href="/hr/payroll/generalsetup/offices" class="nav-link " {{ Request::url() == url('/hr/payroll/generalsetup/offices') ? 'active' : '' }}
                            style="color: black;">Office</a>
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
        </div>
    </section>
    <section class="content-header p-0"
        style="border-top-left-radius: 10px; border-top-right-radius: 10px; border: 1px solid #d9d9d9; background-color: #d9d9d9; margin-bottom: 10px;">
        <div class="container-fluid m-0 p-0">
            <div class="d-flex align-items-center justify-content-start">
                <label
                    style="font-weight: normal; font-size: 1rem; padding-left: 10px; font-weight: 600;">Department</label>
            </div>
        </div>
    </section>
    <div class="">

        <div class="header">
            <button type="button" class="btn p-1 ml-2" style="font-size: 0.8rem; background-color: #00470F; color:white;"
                data-toggle="modal" data-target="#addDepartmentModal">
                <i class="fa fa-plus"></i>
                Add Department
            </button>
        </div>

        {{-- show # entries and search bar --}}
        <div class="card-body px-0">
            <div class="table-responsive">
                <table class="table table-sm table-striped table-bordered" style="border-radius: 10px; overflow: hidden;" id="department_table">
                    <thead class="">
                        <tr style="font-weight: 600!important">
                            <th width="50%">Departments</th>
                            <th width="30%">Department Head</th>
                            <th width="20%" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 10pt!important">
                    </tbody>
                </table>
                <div class="modal fade" id="editDepartmentModal" tabindex="-1" role="dialog"
                    aria-labelledby="editDepartmentLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content" style="border-radius: 10px;">
                            <div class="modal-header p-2" style="background-color: #d3d3d3; border-radius: 10px 10px 0 0">
                                <label class="modal-title" id="editDepartmentLabel"
                                    style="font-size:1rem; font-weight: normal;">Edit Department</label>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="editDepartmentName" style="font-weight: normal;">Department Name:</label>
                                    <input type="text" class="form-control" id="editDepartmentName">
                                </div>
                                <div class="form-group">
                                    <label for="editDepartmentHead" style="font-weight: normal;">Department Head:</label>
                                    <select class="form-control form-control-sm select2 m-0 p-0" id="editDepartmentHead">
                                        <option value=""></option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer d-flex justify-content-center">
                                <button type="button" class="btn btn-sm btn-success" id="btnSaveEditDepartment">Save
                                    changes</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="addDepartmentModal" tabindex="-1" role="dialog"
            aria-labelledby="addDepartmentLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content" style="border-radius: 10px;">
                    <div class="modal-header p-2" style="background-color: #d3d3d3; border-radius: 10px 10px 0 0">
                        <label class="modal-title" id="addDepartmentLabel"
                            style="font-size:1rem; font-weight: normal;">New Department</label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addDepartmentForm">
                            <div class="form-group">
                                <label for="departmentName" style="font-weight: normal;">Department Name</label>
                                <input type="text" class="form-control form-control-sm" id="departmentName"
                                    placeholder="Enter Department Name">
                            </div>
                            <div class="form-group">
                                <label for="departmentHead" style="font-weight: normal;">Department Head</label>
                                <select class="form-control form-control-sm select2 m-0 p-0" id="departmenthead">
                                    <option value=""></option>
                                </select>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-primary" id="saveDepartmentBtn">Save</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
@endsection
@section('footerjavascript')
    <script>
        $('#departmenthead').select2({
            placeholder: 'Select a Department Head',
            allowClear: true,
            width: '100%' // Important for proper alignment
        });

        $('#editDepartmentHead').select2({
            placeholder: 'Select a Department Head',
            allowClear: true,
            width: '100%' // Important for proper alignment
        });
         
    </script>
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
        });

        $(document).ready(function(){

            getDepartments()

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            function getDepartments(){
                $.ajax({
                    type: 'get',
                    url: '/hr/settings/departments/getdepartments',
                    success: function(data){
                        departmentTable(data)
                    }
                })
            }

            $(document).on('click', '#saveDepartmentBtn', function(){
                let department = $('#departmentName').val()
                let departmenthead = $('#departmentHead').val()

                $.ajax({
                    type: 'get',
                    url: '/hr/settings/departments/adddepartment',
                    data: {
                        department: department,
                        departmenthead: departmenthead,
                    },
                    success: function(data){
                        if(data == 'exists'){
                            Toast.fire({
                                type: 'error',
                                title: 'Department already exists'
                            })
                        }else{
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully Added'
                            })
                            $('#addDepartmentModal').modal('hide')
                            getDepartments()
                        }
                    }
                })
            })

            $(document).on('click', '.edit_department', function(){
                let id = $(this).data('id')
                let department = $('#editDepartmentName').val()
                let departmenthead = $('#editDepartmentHead').val()

                $.ajax({
                    type: 'get',
                    url: '/hr/settings/departments/getdepartments',
                    data: {
                        id: id,
                        department: department,
                        departmenthead: departmenthead,
                    },
                    success: function(data){
                        $('#editDepartmentName').val(data[0].department)
                        $('#editDepartmentHead').val(data[0].headname == null ? '' : data[0].headname)
                        $('#btnSaveEditDepartment').attr('data-id', data[0].id)
                    }
                })
            })

            $(document).on('click', '#btnSaveEditDepartment', function(){
                let id = $(this).data('id')
                let department = $('#editDepartmentName').val()
                let departmenthead = $('#editDepartmentHead').val()

                    $.ajax({
                    type: 'get',
                    url: '/hr/settings/departments/updatedepartment',
                    data: {
                        department: department,
                        departmenthead: departmenthead,
                        id: id
                    },
                    success: function(data){
                        if(data == 'exists'){
                            Toast.fire({
                                type: 'error',
                                title: 'Department already exists'
                            })
                        }else{
                            Toast.fire({
                                type: 'success',
                                title: 'Successfully Updated'
                            })
                            $('#editDepartmentModal').modal('hide')
                            getDepartments()
                        }
                    }
                })
            })

            $(document).on('click', '.delete_department', function(){
                let id = $(this).data('id')
            
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        $.ajax({
                            type: 'get',
                            url: '/hr/settings/departments/deletedepartment',
                            data: {
                                id: id
                            },
                            success: function(data){
                                if(data == 'used'){
                                    Toast.fire({
                                        type: 'error',
                                        title: 'Department is currently in use'
                                    })
                                }else{
                                     Toast.fire({
                                        type: 'success',
                                        title: 'Successfully Deleted'
                                    })
                                    getDepartments()
                                }
                               
                            }
                        })
                    }
                })
            })

            function departmentTable(departments){
                $('#department_table').DataTable({
                    destroy: true,
                    order: false,
                    data: departments,
                    columns : [
                        {"data" : "department"},
                        {"data" : null},
                        {"data" : null},
                    ],
                    columnDefs: [
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                $(td).html(`<a href="#" class="">${rowData.headname ? rowData.headname : ''}</a>`)

                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                 $(td).html(
                                            `<a href="javascript:void(0)" class="section_link mb-0" style="white-space: nowrap" data-id="">
                                                <button class="btn btn-sm btn-primary edit_department" id="" data-toggle="modal" data-target="#editDepartmentModal" data-id="${rowData.id}"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-danger delete_department" id=""  data-id="${rowData.id}"><i class="fas fa-trash"></i></button>
                                            </a>`
                                        )
                                .addClass('align-middle text-center')
                                .css('vertical-align', 'middle');

                            }
                        },
                    ]
                })
            }

            
        })
    </script>
@endsection
