<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Age Listing</title>
    <style>

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:12px ;
        }

        table {
            border-collapse: collapse;
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        .table td, .table th {
            padding: .75rem;
            vertical-align: top;
        }
        
        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: .3rem;
        }

        .text-center{
            text-align: center !important;
        }
        
        .text-right{
            text-align: right !important;
        }
        
        .text-left{
            text-align: left !important;
        }
        
        .p-0{
            padding: 0 !important;
        }

        .p-1{
            padding: .25rem !important;
        }


        .mb-0{
            margin-bottom: 0;
        }

        .border-bottom{
            border-bottom:1px solid black;
        }

        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        body{
            font-family: Calibri, sans-serif;
        }
        
        .align-middle{
            vertical-align: middle !important;    
        }

         
        .grades td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .7rem !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .text-red{
            color: red;
            border: solid 1px black;
        }


        .bg-danger {
            background-color: #dc3545!important;
        }
        .page_break { page-break-before: always; }
        
        table.no-spacing {
            border-spacing:0; /* Removes the cell spacing via CSS */
            border-collapse: collapse;  /* Optional - if you don't want to have double border where cells touch */
        }
        
        .text-center-row {
            text-align: center;
            vertical-align: middle;
        }
        .table td, .table th {
            font-size: 12px;
        }
        .text-red{
            color: red;
        }
        .text-center {
            text-align: center;
        }
        .text-alignmentL {
            text-align: left;
        }
        .text-alignmentR {
            text-align: right;
        }
        .vertical-align {
            vertical-align: middle;
        }
        .border-0 td {
            border: none;
        }
        .border-2 {
            border:1px solid black !important;
        }
        .border-bottom{
            border-bottom: 1px solid black !important;
        }
        .text-font {
            font-size: 11px;
        }
        .text-font-sait {
            font-size: 16px;
        }
        .text-font-parents {
            font-size: 14px;
        }
        .background-color {
            background-color: #8bd0e9;
        }
        .background-colorA {
            background-color: #BAF3BC;
        }
        .background-colorB {
            background-color: #F5ebe3;
        }
        .text-style {
            style: italic;
        }
        .p1 {
            font-family: Arial, Helvetica, sans-serif;
        }
        .h1 {
            font-size: 18px;
        }
        .h2 {
            font-size: 15px !important; 
        }
        .h3 {
            font-size: 12px !important;
        }
        .h4 {
            font-size: 8px;
        }
        .detail-margin {
            margin-right: 50px;
            margin-left: 50px;
        }
        .detail-margin1 {
            margin-right: 70px;
            margin-left: 70px;
        }
        
        @page { size: 8.5in 11in; margin: .25in;  }
        
    </style>
</head>
    <body>
     
        <img src="{{base_path()}}/public/apmc_coe_header.png" width="100%">
        <div style="margin-top:50px !important;">&nbsp;</div>
        @foreach ($schoolyears as $schoolyear)
            @php
                $allstudents = collect(array());
                $allstudents = $allstudents->merge($schoolyear->jhs_students);
                $allstudents = $allstudents->merge($schoolyear->shs_students);
                $allstudents = $allstudents->merge($schoolyear->college_students);
                $allstudents = collect($allstudents)->sortBy('dob')->values();
                $count = 0;
            @endphp
                @if(count($allstudents) > 0)
                 
                <table class="table table-sm table-bordered" width="100%">
                    <tr>
                        <th width="25%" class="text-center">{{$schoolyear->sydesc}}</th>
                        <th  width="25%" class="text-center">Male</th>
                        <th  width="25%" class="text-center">Female</th>
                        <th  width="25%" class="text-center">Total</th>
                    </tr>
            
                    <tr>
                        <td>JHS (13-16)</td>
                        <td class="text-center">{{collect($schoolyear->jhs_students)->where('gender','MALE')->count()}}</td>
                        <td class="text-center">{{collect($schoolyear->jhs_students)->where('gender','FEMALE')->count()}}</td>
                        <td class="text-center">{{collect($schoolyear->jhs_students)->count()}}</td>
                    </tr>
                    <tr>
                        <td>SHS (17-18)</td>
                        <td class="text-center">{{collect($schoolyear->shs_students)->where('gender','MALE')->count()}}</td>
                        <td class="text-center">{{collect($schoolyear->shs_students)->where('gender','FEMALE')->count()}}</td>
                        <td class="text-center">{{collect($schoolyear->shs_students)->count()}}</td>
                    </tr>
                    <tr>
                        <td>Tertiary School (19-23)</td>
                        <td class="text-center">{{collect($schoolyear->college_students)->where('gender','MALE')->count()}}</td>
                        <td class="text-center">{{collect($schoolyear->college_students)->where('gender','FEMALE')->count()}}</td>
                        <td class="text-center">{{collect($schoolyear->college_students)->count()}}</td>
                    </tr>
                </table>
            @endif
        @endforeach
        <table class="no-spacing table" style="width:100%; margin-top: 50px !important">
            <tr>
                <td class="text-center  p-0" width="50%"></td>
                <td class="text-center  p-0" width="50%"><u>MERLIE S. SABUELO</u></td>
            </tr>
            <tr>
                <td class="text-center p-0"></td>
                <td class="text-center p-0">Registrar</td>
            </tr>
        </table>
    </body>
</html>