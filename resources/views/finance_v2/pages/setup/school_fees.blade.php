@extends('finance_v2.pages.setup.settings')

@section('pagespecificscripts')
    <style>
        .breadcrumb {
            padding: 0.75rem 0.75rem;
            !important;
            background-color: transparent !important;

        }

        .custom-tabs .custom-nav-link {
            border: 1px solid #e7e7e7;
            /* Light gray border for inactive tabs */
            margin: 0px 16px 0px 0px;
            /* Slight gap between tabs */
            padding: 1px 8px;
            /* Adjust padding for size */
            font-size: 14px;
            /* Smaller font size */
            color: #545454;
            /* Black text color */

            background-color: #f9f9f9;
            /* Light background color for inactive tabs */
            border-radius: 5px 5px 0 0;
            /* Rounded top corners */
        }

        .custom-tabs .custom-nav-link.active {
            background-color: #d9d9d9;
            /* Slightly darker gray for active tab */
            border-bottom: 2px solid white;
            padding: 3px 8px;
            margin: 0 8px;
            color: black;
            line-height: 1.1;
            /* Removes bottom border for active tab */
        }

        .custom-tabs {
            border-bottom: 4px solid #d9d9d9;
            /* Add a bottom border for the entire tab section */
        }

        .nav-tabs .nav-item {
            margin-bottom: -2px;
            /* Prevent a gap at the bottom of tabs */
        }

        .payable-items {
            display: none;
            /* Initially hidden */
        }



        /* Change the background color of selected options */
        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            background-color: #00307b !important;

        }

        /* Optional: Change the hover color */
        #modal_footer_add_school_fees {
            position: sticky;
            bottom: 0;
            background: white;
            z-index: 1050;
            border-top: 1px solid #ddd;
            /* Optional for separation */
            padding: 10px;
        }

        .modal-body {
            max-height: 85vh;
            overflow-y: auto;
        }

        .form-control {
            font-size: 12px !important;
        }


        option,
        input,
        select,
        a,
        label,
        table,
        td {
            font-size: 13px !important;
            font-weight: normal !important;
        }

        th {
            font-size: 13px !important;
            font-weight: bold !important;
        }

        .form-select,
        .custom-select,
        .form-control {
            box-shadow: 2.6px 5.3px 5.3px hsl(0deg 0% 0% / 0.42) !important;
            border: none !important;
            height: 30px !important;


        }

        ::placeholder {
            font-size: 12px !important;
        }

        label {
            font-weight: normal !important;
            font-size: 13px !important;
        }

        th {
            border-left: 1px solid #7d7d7d !important;
            border-top: 1px solid #7d7d7d !important;
            border-right: none !important;
            border-bottom: none !important;
        }

        th.bottom {
            border: none !important;
        }


        td {
            border: 1px solid #7d7d7d !important;
        }

        #payableItemTable thead th,
        #payableItemTable tbody td,
        #payableItemTable tfoot th {
            border: 1px solid #7d7d7d !important;
            /* Set a single border */
        }

        /*
        .sticky-header {
            position: fixed !important;
            background-color: #d9d9d9 !important;
            z-index: 1000;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            transition: left 0.3s ease, width 0.3s ease;

            
        } */
    </style>
@endsection

@section('setup-content')
    <div>
        <div class="container-fluid p-0 m-0 sticky-header ">
            <div class="d-flex align-items-center gap-2">
                <ion-icon name="settings-outline" size="large" class="px-1"></ion-icon>
                <h5 class="text-black m-0">School Fees Settings</h5>

            </div>
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">School Fees</li>
                </ol>
            </nav>



            
        </div>

    </div>
 
@endsection

 
