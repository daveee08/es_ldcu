@extends('finance_v2.layouts.app2')

@section('pagespecificscripts')
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-fixedcolumns/css/fixedColumns.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/fullcalendar-v5-11-3/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/daterangepicker/daterangepicker.css') }}">
@endsection

@section('content')
    <div class="content">
        <div>
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-cog"></i>
                <h5 class="text-black m-0">School Fees Settings</h5>
            </div>
            <nav style="--bs-breadcrumb-divider: '<';" aria-label="breadcrumb">
                <ol class="breadcrumb small">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">School Fees Settings</li>
                </ol>
            </nav>
        </div>

        <div>
            <ul class="nav nav-tabs p-0 smb-0">
                <li class="nav-item">
                    <a class="nav-link {{ Request::url() == url('/financev2/setup/1') ? 'active' : '' }} py-1 m-0" href="/financev2/setup/1">Fees Classification</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::url() == url('/financev2/setup/2') ? 'active' : '' }} py-1 m-0" href="/financev2/setup/2">Laboratory Fees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::url() == url('/financev2/setup/3') ? 'active' : '' }} py-1 m-0" href="/financev2/setup/3">Payment Schedule</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ Request::url() == url('/financev2/setup/4') ? 'active' : '' }} py-1 m-0" href="/financev2/setup/4">School Fees</a>
                </li>
            </ul>
        </div>

        @yield('setup-content')

    </div>
@endsection

@section('footerjavascript')
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('plugins/fullcalendar-v5-11-3/main.js') }}"></script>
    <script src="{{ asset('plugins/moment/moment.min.js') }}"></script>
    <script src="{{ asset('plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.js') }}"></script>
    <script src="{{ asset('plugins/datatables-fixedcolumns/js/dataTables.fixedColumns.js') }}"></script>
    <script src="{{ asset('plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('plugins/chart.js/Chart.min.js') }}"></script>
@endsection
