<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="widtd=device-widtd, initial-scale=1.0">
    <title>Certificate of Enrollment</title>
    <style>
        @page { size: 8.5in 14in; margin: .25in;  }
        @font-face {
            font-family: Arial 12;
        }
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
        .p-0{
            padding: 0!important;
        }
        .pl-2{
            padding-left: .5rem !important;
        }
    </style>
    <style>
        .double {
            border-style: double;
        }
         
       .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size:11px ;
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
            padding: .5rem;
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
        .subjects{
            font-size:8px !important;
        }
        .text-center{
            text-align: center !important;
        }
        .align-middle{
            vertical-align: middle !important;    
        }
        
        .mb-0{
            margin-bottom: 0;
        }
        .mt-2{
            margin-top: .5rem !important;
        }

    </style>
</head>


@php
        $temp_middle = '';
        $temp_suffix = '';
        if(isset($studentInfo->middlename)){
            $temp_middle = ' '.$studentInfo->middlename[0].'.';
        }
        if(isset($studentInfo->suffix)){
            $temp_suffix = ', '.$studentInfo->suffix;
        }
      
      $studentInfo->student = $studentInfo->lastname.', '.$studentInfo->firstname.$temp_suffix.' '.$studentInfo->middlename;

@endphp

    <body>
        <img src="{{base_path()}}/public/apmc_coe_header.png" width="100%">
        <div class="text-center p1 h1"><strong>CERTIFICATE OF ENROLLMENT</strong></div>
        <div class="text-center p1 h2"><i>@if($activeSem->id == 1) First  Semester @elseif($activeSem->id == 2) Second  Semester @else Summer @endif, School Year {{$activeSy->sydesc}}</i></div>
        <hr class="detail-margin">
        <table class="table table-sm mb-0" style="width:100%">
            <tr>
                <td width="10%" class="p-0">Name:</td>
                <td width="45%" class="border-bottom p-0">{{strtoupper($studentInfo->student) }}</td>
                <td width="4%" class="p-0">Sex:</td>
                <td width="11%" class="border-bottom p-0">{{$studentInfo->gender}}</td>
                <td width="10%" class="p-0">Bdate:</td>
                <td width="20%" class="border-bottom text-center p-0">{{\Carbon\Carbon::create($studentInfo->dob)->isoFormat('MMMM DD, YYYY')}}</td>
            </tr>
        </table>
        <table class="table table-sm mb-0" style="width:100%">
            <tr>
                <td width="10%" class="p-0">Address:</td>
                <td class="border-bottom p-0" width="60%">{{$studentInfo->address}}</td>
                <td width="10%" class="p-0">Course/Year:</td>
                <td class="border-bottom text-center p-0" width="20%">{{$studentInfo->courseabrv}} {{$studentInfo->enlevelid}}</td>
            </tr>
        </table>
        <table class="table table-sm mb-0" style="width:100%">
            <tr>
                <td width="10%" class="p-0">Remark:</td>
                <td class="border-bottom p-0" width="10%">{{strtoupper($studentInfo->studtype)}}</td>
                <td width="10%" class="p-0">Date Enrolled:</td>
                <td class="border-bottom text-center p-0" width="40%">{{\Carbon\Carbon::create($studentInfo->date_enrolled)->isoFormat('MMMM DD, YYYY')}}</td>
                <td width="6%" class="p-0">Mobile:</td>
                <td class="border-bottom text-center p-0" width="24%">{{$studentInfo->contactno}}</td>
            </tr>
        </table>
        <table class="table table-sm mb-0 mt-2" style="width:100%">
            <tr>
                <td class="p-0"><b>Subjects Enrolled:</b></td>
            </tr>
        </table>
        <table class="table table-sm mt-2" style="width:100% ; font-size:9px !important" >
            <tr>
                <td width="10%" class=" p-0 pl-2"><b>Subject No.</b></td>
                <td width="30%" class="p-0"><b>Descriptive Title</b></td>
                <td width="5%" class="p-0 text-center"><b>Units</b></td>
                <td width="15%" class="p-0"><b>Instructor</b></td>
                <td width="18%" class="p-0"><b>Block</b></td>
                <td width="17%" class="p-0"><b>Time Day</b></td>
                <td width="5%" class="p-0"><b>Room</b></td>
            </tr>
            @php  
                  $totalUnits = 0.0;
            @endphp
            @foreach (collect($schedules)->sortBy('subjCode')->values() as $item)
                <tr>
                    <td class="p-0 pl-2">{{$item[0]->subjCode}}</td>
                    <td class="p-0">{{$item[0]->subjDesc}}</td>
                    <td class="p-0 text-center"> {{number_format($item[0]->lecunits + $item[0]->labunits)}}</td>
                    <td class="p-0" >{{$item[0]->teacher}}</td>
                    <td class="p-0">{{$item[0]->sectionDesc}}</td>
                    <td class="p-0" >{{\Carbon\Carbon::create($item[0]->stime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($item[0]->etime)->isoFormat('hh:mm A')}} {{$item[0]->description}}</td>
                    <td class="p-0">{{$item[0]->roomname}}</td>
                </tr>
                @php  
                      $totalUnits += number_format($item[0]->lecunits + $item[0]->labunits,1);
                @endphp
            @endforeach
            @for($x=0; $x < ( 14 - count($schedules)); $x++)
                <tr>
                     <td class="p-0 pl-2" colspan="2"></td>
                    <td class="p-0 text-center"> 0</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
            @endfor
            <tr>
                <td class="p-0 pl-2" colspan="2">TOTAL UNITS</td>
                <td class="p-0 text-center"> {{$totalUnits}}</td>
                <td class="p-0"></td>
                <td class="p-0"></td>
                <td class="p-0"></td>
                <td class="p-0"></td>
            </tr>
        </table>
        <table class="table table-sm" style="width:100%">
            <tr>
                <td class="text-center p-0" width="50%"></td>
                <td class="text-center p-0" width="50%"><u>CHRISTOPHER D. SALICANAN</u></td>
            </tr>
            <tr>
                <td class="text-center  p-0"></td>
                <td class="text-center  p-0">Registrar</td>
            </tr>
        </table>
        <table class="table table-sm" style="width:100%">
            <tr>
                <td class="p-0" width="55%"><i>Note: Please report immediately any data entry error.<~</i></td>
                <td class="text-center p-0" width="20%">Date</td>
                <td width="15%" class="p-0">{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MM/DD/YYYY')}}</td>
                <td width="10%" class="p-0">{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('HH:mm')}}</td>
            </tr>
        </table>
        <hr style="border-bottom: dashed 1px #000;" />
        <img src="{{base_path()}}/public/apmc_coe_header.png" width="100%">
        <div class="text-center p1 h1"><strong>CERTIFICATE OF ENROLLMENT</strong></div>
        <div class="text-center p1 h2"><i>@if($activeSem->id == 1) First  Semester @elseif($activeSem->id == 2) Second  Semester @else Summer @endif, School Year {{$activeSy->sydesc}}</i></div>
        <hr class="detail-margin">
        <table class="table table-sm mb-0" style="width:100%">
            <tr>
                <td width="10%" class="p-0">Name:</td>
                <td width="45%" class="border-bottom p-0">{{strtoupper($studentInfo->student)}}</td>
                <td width="4%" class="p-0">Sex:</td>
                <td width="11%" class="border-bottom p-0">{{$studentInfo->gender}}</td>
                <td width="10%" class="p-0">Bdate:</td>
                <td width="20%" class="border-bottom text-center p-0">{{\Carbon\Carbon::create($studentInfo->dob)->isoFormat('MMMM DD, YYYY')}}</td>
            </tr>
        </table>
        <table class="table table-sm mb-0" style="width:100%">
            <tr>
                <td width="10%" class="p-0">Address:</td>
                <td class="border-bottom p-0" width="60%">{{$studentInfo->address}}</td>
                <td width="10%" class="p-0">Course/Year:</td>
                <td class="border-bottom text-center p-0" width="20%">{{$studentInfo->courseabrv}} {{$studentInfo->enlevelid}}</td>
            </tr>
        </table>
        <table class="table table-sm mb-0" style="width:100%">
            <tr>
                <td width="10%" class="p-0">Remark:</td>
                <td class="border-bottom p-0" width="10%">{{strtoupper($studentInfo->studtype)}}</td>
                <td width="10%" class="p-0">Date Enrolled:</td>
                <td class="border-bottom text-center p-0" width="40%">{{\Carbon\Carbon::create($studentInfo->date_enrolled)->isoFormat('MMMM DD, YYYY')}}</td>
                <td width="6%" class="p-0">Mobile:</td>
                <td class="border-bottom text-center p-0" width="24%">{{$studentInfo->contactno}}</td>
            </tr>
        </table>
        <table class="table table-sm mb-0 mt-2" style="width:100%">
            <tr>
                <td class="p-0"><b>Subjects Enrolled:</b></td>
            </tr>
        </table>
        <table class="table table-sm mt-2" style="width:100% ; font-size:9px !important" >
            <tr>
                <td width="10%" class=" p-0 pl-2"><b>Subject No.</b></td>
                <td width="30%" class="p-0"><b>Descriptive Title</b></td>
                <td width="5%" class="p-0 text-center"><b>Units</b></td>
                <td width="15%" class="p-0"><b>Instructor</b></td>
                <td width="18%" class="p-0"><b>Block</b></td>
                <td width="17%" class="p-0"><b>Time Day</b></td>
                <td width="5%" class="p-0"><b>Room</b></td>
            </tr>
            @php  
                  $totalUnits = 0.0;
            @endphp
            @foreach (collect($schedules)->sortBy('subjCode')->values() as $item)
                <tr>
                    <td class="p-0 pl-2">{{$item[0]->subjCode}}</td>
                    <td class="p-0">{{$item[0]->subjDesc}}</td>
                    <td class="p-0 text-center"> {{number_format($item[0]->lecunits + $item[0]->labunits)}}</td>
                    <td class="p-0" >{{$item[0]->teacher}}</td>
                    <td class="p-0">{{$item[0]->sectionDesc}}</td>
                    <td class="p-0" >{{\Carbon\Carbon::create($item[0]->stime)->isoFormat('hh:mm A')}} - {{\Carbon\Carbon::create($item[0]->etime)->isoFormat('hh:mm A')}} {{$item[0]->description}}</td>
                    <td class="p-0">{{$item[0]->roomname}}</td>
                </tr>
                @php  
                      $totalUnits += number_format($item[0]->lecunits + $item[0]->labunits,1);
                @endphp
            @endforeach
            @for($x=0; $x < ( 14 - count($schedules)); $x++)
                <tr>
                     <td class="p-0 pl-2" colspan="2"></td>
                    <td class="p-0 text-center"> 0</td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                    <td class="p-0"></td>
                </tr>
            @endfor
            <tr>
                <td class="p-0 pl-2" colspan="2">TOTAL UNITS</td>
                <td class="p-0 text-center"> {{$totalUnits}}</td>
                <td class="p-0"></td>
                <td class="p-0"></td>
                <td class="p-0"></td>
                <td class="p-0"></td>
            </tr>
        </table>
        <table class="table table-sm" style="width:100%">
            <tr>
                <td class="text-center p-0" width="50%"></td>
                <td class="text-center p-0" width="50%"><u>CHRISTOPHER D. SALICANAN</u></td>
            </tr>
            <tr>
                <td class="text-center  p-0"></td>
                <td class="text-center  p-0">Registrar</td>
            </tr>
        </table>
        <table class="table table-sm" style="width:100%">
            <tr>
                <td class="p-0" width="55%"><i>Note: Please report immediately any data entry error.<~</i></td>
                <td class="text-center p-0" width="20%">Date</td>
                <td width="15%" class="p-0">{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('MM/DD/YYYY')}}</td>
                <td width="10%" class="p-0">{{\Carbon\Carbon::now('Asia/Manila')->isoFormat('HH:mm')}}</td>
            </tr>
        </table>
</body>
</html>