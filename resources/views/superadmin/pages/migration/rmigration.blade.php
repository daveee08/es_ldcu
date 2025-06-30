@extends('superadmin.layouts.app2')

@section('pagespecificscripts')
    <style>
        .card-body .btn-app {
            width: 40%;
            height: 100px;
            border: none;
            padding-top: 20px;
            transition: .3s;
            font-size: 15px;
            background-color: transparent !important;
        }

        .card-body {
            text-align: center;
        }

        .icon-display {
            font-size: 35px !important;
        }
    </style>
@endsection

@section('content')
    <h2 class="text-center text-black mt-3 mb-3">
        Registration Migration
    </h2>
    <section class="content-header">
        <div class="row">
            <div class="col-md-4 mt-3">
                <div class="card h-100">
                    <div class="card-header bg-success ">
                        <h3 class="card-title"><b>Student Masterlist</b></h3>
                    </div>
                    <div class="card-body bg-success p-2">
                        <a class="btn btn-app text-white ml-0" href="/superadmin/download/student-masterlist-batch">
                            <i class="fas fa-file-pdf text-warning icon-display mb-2"></i> <b>PDF Copy</b>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3">
                <div class="card h-100">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><b>Transcript of Records</b></h3>
                    </div>
                    <div class="card-body bg-success p-2">
                        <a class="btn btn-app text-white ml-0" href="/manageaccounts">
                            <i class="fas fa-file-pdf text-warning icon-display mb-2"></i> <b>PDF Copy</b>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3">
                <div class="card h-100">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><b>SF9</b></h3>
                    </div>
                    <div class="card-body bg-success p-2">
                        <a class="btn btn-app text-white ml-0" href="/manageaccounts">
                            <i class="fas fa-file-pdf text-warning icon-display mb-2"></i> <b>PDF Copy</b>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mt-3">
                <div class="card h-100">
                    <div class="card-header bg-success">
                        <h3 class="card-title"><b>SF10</b></h3>
                    </div>
                    <div class="card-body bg-success p-2">
                        <a class="btn btn-app text-white ml-0" href="/manageaccounts">
                            <i class="fas fa-file-pdf text-warning icon-display mb-2"></i> <b>PDF Copy</b>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@section('footerscript')
@endsection
