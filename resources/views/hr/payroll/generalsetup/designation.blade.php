@extends('hr.layouts.app')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
</head>

<body>


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
    <section class="content-header p-0">
        <div class="container-fluid m-0 p-0">
            <!-- Page Header -->
            <div class="d-flex align-items-center">
                <i class="fas fa-link fa-lg "></i>
                <h4>Designation Setup</h4>
            </div>
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb bg-transparent p-0">
                    <li class="breadcrumb-item" style="font-size: 0.8rem;"><a href="#"
                            style="color:rgba(0,0,0,0.5);">Home</a></li>
                    <li class="breadcrumb-item active" style="font-size: 0.8rem; color:rgba(0,0,0,0.5);">Designation
                        Setup</li>
                </ol>
            </nav>
            <div class="mb-3" style="color: black; font-size: 13px;">
                <ul class="nav nav-tabs" style="border-bottom: 6px solid #d9d9d9; font-weight: 600; gap: 10px;">
                    <li class="nav-item"
                        style="width: 12%; text-align: center;border-top-left-radius: 10px; border-top-right-radius: 10px;border: 1px solid #d9d9d9;">
                        <a href="/hr/payroll/generalsetup/department" class="nav-link" {{ Request::url() == url('/hr/payroll/generalsetup/department') ? 'active' : '' }}
                            >Department</a>
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
                            style="color: black;  font-weight: 600; background-color: #d9d9d9; border-top-left-radius: 10px; border-top-right-radius: 10px;">Designation</a>
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
                                
                                <!-- Section with Designation title -->
                <div style="background-color: #d9d9d9; border-radius: 10px 10px 0 0; padding: 10px; margin-top: 10px; margin-bottom: 15px;">
                    <span style="font-weight: 600; font-size: 20px; color: black;  padding-left: 10px;">Designation</span>
                </div>
            </div>
            <div class="mt-2" style="margin-bottom: 15px;">
                <button type="button" class="btn p-1 ml-2" style="font-size: 0.8rem; background-color: #00470F; color:white;"
                    data-toggle="modal" data-target="#designationModal">
                    <i class="fa fa-plus"></i>
                    Add Designation
                </button>
            </div>


            {{-- Designation Data table --}}
            <div class="card-body px-0">
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-bordered" style="border-radius: 10px; overflow: hidden;" id="designationTable">
                        <thead class="">
                            <tr style="font-weight: 600!important">
                                <th width="60%">Designation</th>
                                <th width="35%">Created By</th>
                                <th width="5%" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody style="font-size: 10pt!important">
                        </tbody>
                    </table>
                </div>
            </div>
        
        

        {{-- Designation modal --}}


        <div class="modal fade" id="designationModal" tabindex="-1" role="dialog"
            aria-labelledby="designationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content" style="border-radius: 10px;">
                    <div class="modal-header p-2" style="background-color: #d3d3d3; border-radius: 10px 10px 0 0">
                        <label class="modal-title" id="addDesignationLabel"
                            style="font-size:1rem; font-weight: normal;">New Designation</label>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="addDepartmentForm">
                            <div class="form-group">
                                <label for="designationName">Designation name</label>
                                <input type="text" class="form-control form-control-sm" id="designationName"
                                    placeholder="Enter Designation Name">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer d-flex justify-content-center">
                        <button type="button" class="btn btn-sm btn-primary" id="addDesignation">Save</button>
                    </div>
                </div>
            </div>
        </div>



        {{-- for data tables --}}
        

    </section>
@endsection

@section('footerjavascript')

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
            getDesignations();
            function getDesignations() {
                $.ajax({
                    type: "GET",
                    url: "/hr/settings/designation/getdesignation",
                    success: function(response) {
                        designationTable(response)
                    }
                })
            }

            $(document).on('click','#addDesignation',function(){
                let designation = $('#designationName').val()

                $.ajax({
                    url: "/hr/settings/designation/adddesignation",
                    type: "POST",
                    data: {
                        designation: designation
                    },
                    success: function(response) {
                        if (response == 'exists') {
                            Toast.fire({
                                type: 'error',
                                title: ' Designation already exists'
                            })
                          
                        }else{
                             Toast.fire({
                                type: 'success',
                                title: 'Successfully added'
                            })

                            $('#designationModal').modal('hide')
                            $('#designationName').val('')
                            getDesignations()
                        }
                    }
                })
            });

            function designationTable(departments){
                $('#designationTable').DataTable({
                    destroy: true,
                    order: false,
                    data: departments,
                    columns : [
                        {"data" : "designation"},
                        {"data" : "name"},
                        {"data" : null},
                    ],
                    columnDefs: [
                        {
                            targets: 1,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {

                                $(td).html(`<a href="#" class="">${rowData.name ? rowData.name : ''}</a>`)

                            }
                        },
                        {
                            targets: 2,
                            orderable: false,
                            createdCell: function(td, cellData, rowData) {
                                if(rowData.constant == 1 || rowData.assigned){
                                    // col = '<a href="javascript:void(0)">Not Editable</a>'
                                    col = ''
                                }else{
                                    col = `<a href="javascript:void(0)" class="section_link mb-0" style="white-space: nowrap" data-id="">
                                                <button class="btn btn-sm btn-primary edit_designation" id="" data-toggle="modal" data-target="#designationModal" data-id="${rowData.id}"><i class="fas fa-edit"></i></button>
                                                <button class="btn btn-sm btn-danger delete_designation" id=""  data-id="${rowData.id}"><i class="fas fa-trash"></i></button>
                                            </a>`
                                }
                                $(td).html(col) 
                                .addClass('align-middle text-center')
                                .css('vertical-align', 'middle');

                            }
                        },
                    ]
                })
            }

            $(document).on('click','.edit_designation',function(){
                $('#addDesignationLabel').text('Edit Designation')
                $('#addDesignation').attr('id','updateDesignation')
                let id = $(this).data('id')
                $.ajax({
                    url: "/hr/settings/designation/getdesignation",
                    type: "GET",
                    data: {
                        id: id
                    },
                    success: function(response) {
                        $('#designationName').val(response[0].designation)
                        $('#updateDesignation').attr('data-id',id)
                    }
                })

            })

            $(document).on('click','#updateDesignation',function(){
                let designation = $('#designationName').val()
                let id = $(this).data('id')
                $.ajax({
                    url: "/hr/settings/designation/updatedesignation",
                    type: "GET",
                    data: {
                        id: id,
                        designation: designation
                    },
                    success: function(response) {
                        if (response == 'exists') {
                            Toast.fire({
                                type: 'error',
                                title: ' Designation already exists'
                            })
                          
                        }else{
                             Toast.fire({
                                type: 'success',
                                title: 'Successfully updated'
                            })
                            $('#addDesignationLabel').text('Add Designation')
                            $('#updateDesignation').removeAttr('data-id')
                            $('#updateDesignation').attr('id','addDesignation')
                            $('#designationName').val('')
                            $('#designationModal').modal('hide')
                            getDesignations()
                        }
                    }
                })
            });

            $(document).on('click','.delete_designation',function(){
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
                            url: "/hr/settings/designation/deletedesignation",
                            type: "GET",
                            data: {
                                id: id
                            },
                            success: function(response) {
                                Toast.fire({
                                    type: 'success',
                                    title: 'Successfully deleted'
                                })
                                getDesignations()
                            }
                        })
                    }
                })
            })

            $(document).on('hidden.bs.modal', '#designationModal', function () {
                $('#addDesignationLabel').text('Add Designation')
                $('#updateDesignation').removeAttr('data-id')
                $('#updateDesignation').attr('id','addDesignation')
                $('#designationName').val('')
            });

        });
</script>
@endsection

