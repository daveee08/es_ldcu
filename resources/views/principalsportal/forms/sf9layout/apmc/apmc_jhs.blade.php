
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$student->student}}</title>
    <style>
    
        *{
            
            font-family: Calibri, sans-serif;
            }
        table {
            border-collapse: collapse;
        }
        
        .table {
            width: 100%;
            margin-bottom: 1rem;
            color: #212529;
            background-color: transparent;
        }

        .table th,
        .table td {
            padding: 0.75rem;
            vertical-align: top;
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid black;
        }

        .table tbody + tbody {
            border-top: 2px solid black;
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .table-bordered {
            border: 1px solid black;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid black;
        }

        .table-bordered thead th,
        .table-bordered thead td {
            border-bottom-width: 2px;
        }

        .text-center {
            text-align: center !important;
        }

        .p-0 {
            padding: 0 !important;
        }
        .border-0 {
            border: 0 !important;
        }
        .pt-2,
        .py-2 {
            padding-top: 0.5rem !important;
        }
        .pt-4{
            padding-top: 1.5rem !important;
        }

        .container {
            min-width: 992px !important;
        }
       
        .p-1 {
            padding: 0.25rem !important;
        }
        
        .pr-2,
        .px-2 {
            padding-right: 0.5rem !important;
        }
        pl-2,
        .px-2 {
            padding-left: 0.5rem !important;
        }

        .align-middle {
            vertical-align: middle !important;
        }
        
        

        .rt-90 {
            /* Abs positioning makes it not take up vert space */
            position: absolute;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 30 22;
            transform: rotate(-90deg);
        }

        .table-sm th,
        .table-sm td {
            padding: 0.3rem;
        }

        .reportat td{
            border: 1px solid #000;
        }

        .reportat .toping td{
            border: 1px solid #fff;
            border-bottom: 1px solid #000;
        }

        .pl-4, .px-4 {
            padding-left: 1.5rem!important;
        }
        
    </style>
    <style>
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
       
        .pl-3{
            padding-left: 1rem !important;
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

        .mb-2, .my-1 {
            margin-bottom: .5rem!important;
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
            padding-left: .1 !important;
            padding-right: .1 !important;
            font-size: 9px !important;
        }
        
         .plot_1 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .6rem !important;
        }
        
        .plot_2 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .72rem !important;
        }
        
        .plot_2-0 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .8rem !important;
        }
        
        .pl-0{
            padding-left: 0 !important;
        }
        
        .pr-0{
             padding-right: 0 !important;
        }
        
        .f-11 td{
            font-size: 11.5px !important;
        }
        
        .f-10 td{
            font-size: 10px !important;
        }
        
         .plot_3 td{
            padding-top: .1rem;
            padding-bottom: .1rem;
            font-size: .8rem !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        .bg-red{
            color: red;
            border: solid 1px black !important;
        }
        
        @page { margin: .25in .5in;  }

        
    </style>

    @php

    $student_quarter = DB::table('student_quarter')
            ->where('syid', $syid)
            ->where('studid', $studid)
            ->first();


    @endphp
</head>
<body>
   
    <div class="container">
        <table class="table mb-0" width="100%">
                <tr>
                    <td width="50%" style="padding-right: .5in !important">
                        <table width="100%">
                            <tr>
                                <td style="font-size:15px"><center><b>REPORT ON ATTENDANCE</b></center></td>
                            </tr>
                        </table >
                        <table width="100%" class="table table-sm grades table-bordered plot_1">
                            {{-- <tr class="table-bordered">
                                <td></td>
                                @foreach ($attSum as $item)
                                    <td class="text-center text-center" style="font-size:10px !important">{{\Carbon\Carbon::create($item->month)->isoFormat('MMM')}}</td>
                                @endforeach
                                <td class="text-center text-center" style="font-size:10px !important">Total</td>
                            </tr>
                            <tr class="table-bordered" >
                                <td style="font-size:9px !important">No. of Days Present</td>
                                @foreach ($attSum as $item)
                                    <td class="align-middle text-center">{{$item->count}}</td>
                                @endforeach
                                <td class="align-middle text-center">{{collect($attSum)->sum('count')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td style="font-size:9px !important">No. of Days Present</td>
                                @foreach ($attSum as $item)
                                    <td class="align-middle text-center">{{$item->countPresent}}</td>
                                @endforeach
                                <td class="align-middle text-center">{{collect($attSum)->sum('countPresent')}}</td>
                            </tr>
                            <tr class="table-bordered">
                                <td style="font-size:9px !important">No. of Days Absent</td>
                                @foreach ($attSum as $item)
                                    <td class="align-middle text-center">{{$item->countAbsent}}</td>
                                @endforeach
                                <td class="align-middle text-center">{{collect($attSum)->sum('countAbsent')}}</td>
                            </tr> --}}
                            <tr >
                                <th></th>
                                @foreach ($attendance_setup as $item)
                                    <th class="text-center align-middle" >{{\Carbon\Carbon::create(null, $item->month)->isoFormat('MMM')}}</th>
                                @endforeach
                                <th class="text-center">Total</th>
                            </tr>
                            <tr >
                                <td style="padding-left: .25rem !important;">No. of School Days</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->days}}</td>
                                @endforeach
                                <th class="text-center align-middle">{{collect($attendance_setup)->sum('days')}}</td>
                            </tr>
                            <tr>
                                <td style="padding-left: .25rem !important;">No. of Days Present</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->present}}</td>
                                @endforeach
                                <th class="text-center align-middle">{{collect($attendance_setup)->sum('present')}}</th>
                            </tr>
                            <tr>
                                <td style="padding-left: .25rem !important;">No. of Days Absent</td>
                                @foreach ($attendance_setup as $item)
                                    <td class="text-center align-middle">{{$item->absent}}</td>
                                @endforeach
                                <th class="text-center align-middle" >{{ collect($attendance_setup)->sum('absent')}}</td>
                            </tr>
                        </table>
                        <table class="table" style="margin-top:100px !important; plot_3" width="100%">
                            <tr class="text-center">
                                <td colspan="2">PARENT/GUARDIAN'S SIGNATURE</td>
                            </tr>
                            <tr>
                                <td style="width: 20%;">1<sup>st</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>2<sup>nd</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>3<sup>rd</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                            <tr>
                                <td>4<sup>th</sup> Quarter</td>
                                <td>____________________________________________</td>
                            </tr>
                        </table> 
                    </td>
                    <td width="50%" style="padding-left: .5in !important">
                        <table  class="table table-sm  plot_2" width="100%" style="font-size:8px !important">
                            <tr>
                                <td width="30%">DepEd Form 138</td>
                                <td width="40%" class="text-right">LRN:</td>
                                <td width="30%" class="border-bottom">{{$student->lrn}}</td>
                            </tr>
                        </table>
                        <table  class="table table-sm text-center plot_2" width="100%">
                            <tr>
                                <td width="30%" rowspan="5" class="align-middle text-right p-0"> <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="80px"></td>
                                <td width="40%"  class="p-0 align-middle">Republic of the Philippines</td>
                                <td width="30%" rowspan="5" class="align-middle text-left p-0"> <img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="100px"></td>
                             </tr>
                            <tr>
                                <td class="p-0 align-middle">Department of Education</td>
                            </tr>
                            <tr>
                                <td class="p-0  align-middle">Region IX</td>
                            </tr>
                            <tr>
                                <td class="p-0  align-middle">Division of Zamboanga del Sur</td>
                            </tr>
                            <tr>
                                <td  class="p-0  align-middle">{{$schoolinfo[0]->district}}</td>
                            </tr>
                        </table>
                        <table  class="table table-sm text-center mb-0" width="100%">
                            <tr>
                                <td style="font-size: 15px !important"><b>{{$schoolinfo[0]->schoolname}}</b></td>
                            </tr>
                        </table>
                        
                        <table  class="table table-sm mb-0 plot_2" width="100%">
                            <tr>
                                <td width="10%" class="p-0">Name:</td>
                                @if($student->studstatid == 5 || $student->studstatid == 3 || $student->studstatid == 6)
                            
                                    <td width="90%" class="border-bottom  text-center p-0" style="color: red">{{strtoupper($student->student)}}</td>
                                @else
                                    <td width="90%" class="border-bottom  text-center p-0">{{strtoupper($student->student)}}</td>
                                @endif
                            </tr>
                        </table>
                        <table  class="table table-sm mb-0 plot_2" width="100%">
                            <tr>
                                <td width="5%" class="p-0">Age:</td>
                                <td width="25%" class="border-bottom text-center p-0"> {{\Carbon\Carbon::parse($student->dob)->age}} </td>
                                <td width="5%" class="p-0">Sex:</td>
                                <td width="65%"  class="border-bottom text-center p-0">{{$student->gender}}</td>
                            </tr>
                        </table>
                        <table  class="table table-sm mb-0 plot_2" width="100%">
                            <tr>
                                <td width="7%" class="p-0">Grade:</td>
                                <td width="23%" class="border-bottom text-center p-0">{{ str_replace("GRADE","",$student->levelname)}}</td>
                                <td width="8%" class="p-0">Section:</td>
                                <td width="62%" class="border-bottom text-center p-0"> {{$student->sectionname}}</td>
                            </tr>
                        </table>
                        <table  class="table table-sm mb-0 plot_2" width="100%">
                            <tr>
                                <td width="20%" class="p-0">School Year:</td>
                                <td width="80%" class="border-bottom text-center p-0">{{$schoolyear->sydesc}}</td>
                        </table>
                        
                        
                        @if($acad == 5)
                            <table  class="table table-sm mb-0 plot_2" width="100%">
                                <tr>
                                    <td width="20%" class="p-0 ">Track / Strand:</td>
                                    @php
                                        $track = "";
                                        if($strandInfo->trackname == "Technical-Vocational-Livehood Track"){
                                             $track = "TVL";
                                        }else{
                                            $track = "Academic";
                                           
                                        }
                                    @endphp
                                    
                                    <td width="80%" class="border-bottom text-center p-0">{{$track}} - {{$strandInfo->strandname}}</td>
                            </table>
                        @endif
                        
                        <br/>
                        <table style="font-size:11px; !important; table-layout: fixed;" width="100%" class="mb-1 plot_2">
                            <tr>
                                <td colspan="5" class="p-1" style="text-align: justify;">Dear Parent:<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This report card shows the ability and progress your child has <br></.>made in the different learning areas as well as his/her core values.<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The school welcomes you should you desire to know more about <br>your child's progress.</td>
                            </tr>
                        </table>
                         <br>
                        <table  class="table table-sm" width="100%">
                            <tr>
                                <td width="50%" class="p-0"></td>
                                <td width="50%" class="border-bottom text-center p-0">{{$adviser}}</td>
                            </tr>
                             <tr>
                                <td class="p-0"></td>
                                <td class="text-center p-0">Adviser</td>
                            </tr>
                             <tr>
                                <td class="border-bottom text-center p-0">{{$principal_info[0]->name}}</td>
                                <td class="p-0"></td>
                            </tr>
                             <tr>
                                <td class="text-center p-0">{{$principal_info[0]->title}}</td>
                                <td class="p-0"></td>
                            </tr>
                        </table>
                        <table class="table table-sm grades mb-0" style="margin-top:2.5rem !important" width="100%">
                            <tr>
                                <td class="text-center"><b>CERTIFICATE OF TRANSFER</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm grades mb-0 plot_2" width="100%">
                            <tr>
                                <td width="25%">Admitted to Grade:</td>
                                <td width="15%" class="border-bottom text-center">
                                    {{$student->levelname = str_replace('GRADE', '', $student->levelname)}}
                                </td>
                                <td width="12%">Section:</td>
                                <td width="48%" class="border-bottom text-center">
                                    @if($studid == 465 )
                                    
                                    @else
                                        {{$student->sectionname}}
                                    @endif
                                </td>
                            </tr>
                        </table>
                        <table class="table table-sm grades mb-0  plot_2" width="100%">
                            <tr>
                                <td width="40%">Eligibility for Admission to Grade</td>
                                <td width="60%" class="border-bottom text-center">
                                    
                                    @php   
                                    
                                        $gradelevel = DB::table('gradelevel')
                                            ->where('id', $student->levelid)
                                            ->first();

                                        $eligible = DB::table('gradelevel')
                                            ->where('sortid', '>', $gradelevel->sortid)
                                            ->where('deleted', 0)
                                            ->orderBy('sortid')
                                            ->first()
                                            ->levelname;
                                        
                                    @endphp
                                    {{$eligible ?? ''}}
                                    
                                </td>
                            </tr>
                        </table>
                        <table class="table table-sm grades mb-0  plot_2" width="100%">
                            <tr>
                                <td width="33%">Approved:</td>
                               
                            </tr>
                        </table>
                        <table  class="table table-sm mb-1" width="100%">
                            <tr>
                                <td width="50%" class="p-0"></td>
                                <td width="50%" class="border-bottom text-center p-0">{{$adviser}}</td>
                            </tr>
                             <tr>
                                <td  class="p-0"></td>
                                <td class="text-center p-0">Adviser</td>
                            </tr>
                             <tr>
                                <td class="border-bottom text-center p-0">{{$principal_info[0]->name}}</td>
                                <td  class="p-0"></td>
                            </tr>
                             <tr>
                                <td class="text-center p-0">{{$principal_info[0]->title}}</td>
                                <td  class="p-0"></td>
                            </tr>
                        </table>
                        <table class="table table-sm grades mb-1" style="margin-top:2rem !important" width="100%">
                            <tr>
                                <td class="text-center"><b>CANCELLATION OF ELIGIBILITY TO TRANSFER</b></td>
                            </tr>
                        </table>
                        <table class="table table-sm grades plot_2" width="100%">
                            <tr>
                                <td width="18%">Admitted in:</td>
                                <td width="47%" class="border-bottom"></td>
                                <td width="9%">Date:</td>
                                <td width="26%" class="border-bottom"></td>
                            </tr>
                        </table>
                        <br>
                        <table class="table table-sm grades mt-2 mb-0" width="100%">
                            <tr>
                                <td width="45%"></td>
                                <td width="10%"></td>
                                <td class="border-bottom text-center" width="45%"></td>
                            </tr>
                            <tr>
                                <td class="text-center" width="45%"></td>
                                <td width="10%"></td>
                                <td class="text-center" width="45%"><b>Principal</b></td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>
    </div>
   <div class="container">

   
        <table class="table mb-0" width="100%">
                <tr>
                    @if($acad != 5)
                        <td width="50%" style=" padding-top:.25in !important; padding-right:.5in !important">
                    @else
                        <td width="50%" style=" padding-right:.5in !important">
                    @endif
                    
                        
                    @if($acad != 5)
                    
                  
                    
                                    <table class="table table-sm" width="100%" >
                                        <tr>
                                            <th class="p-0 text-center border-0" style="font-size: 1rem !important" width="100%">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                                        </tr>
                                    </table>
                    @else
                                    <table class="table table-sm p-0 mb-0" width="100%" >
                                        <tr>
                                            <th class="p-0 text-center border-0" style="font-size: .8rem !important" width="100%">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                                        </tr>
                                    </table>
                    
                    @endif
                              
                                
                                    @if($acad == 5)
                                        @for ($x=1; $x <= 2; $x++)
                                        
                                            @if($x == 2 && $studid == 465 )
                                            
                                            
                                            @else
                                                @if(isset($student_quarter))
                                                    @php
                                                        $left = '319px';
                                                        
                                                    @endphp
{{--                                                 
                                                        <div style="top: 90px !important; left: {{$left}} !important;  position:relative; vertical-align:middle !important;margin: auto !important;">
                                                            <div style="border: solid 1px black; position:absolute; text-align:center; width: 130px !important; height: 100px !important; vertical-align:middle !important; background-color:white; color:rgb(0, 0, 0); padding-top: 40px !important;">
                                                                {{$student->description}}<br>{{\Carbon\Carbon::create($student->studstatdate)->isoFormat('MMM. DD, YYYY')}}<br>{{$student->remarks}}
                                                            </div>
                                                        </div> --}}
                                                    
                                                @endif
                                                <table class="table table-sm table-bordered grades {{$x == 1 ? '' : 'mb-0'}} f-10" style="border: 0 !important" width="100%">
                                                    <tr>
                                                        <td colspan="4"  class="align-middle border-0" ><b>{{$x == 1 ? 'FIRST SEMESTER' : 'SECOND SEMESTER'}}</b></td>
                                                    </tr>
                                                    <tr>
                                                        <td width="60%" rowspan="2"  class="align-middle text-center"><b>SUBJECTS</b></td>
                                                        <td width="20%" colspan="2"  class="text-center align-middle" ><b>PERIODIC RATINGS</b></td>
                                                        <td width="20%" rowspan="2"  class="text-center align-middle" ><b>SEMESTER<br>FINAL GRADE</b></td>
                                                    </tr>
                                                    <tr>
                                                        @if($x == 1)
                                                            <td class="text-center align-middle"><b>1</b></td>
                                                            <td class="text-center align-middle"><b>2</b></td>
                                                        @elseif($x == 2)
                                                            <td class="text-center align-middle"><b>3</b></td>
                                                            <td class="text-center align-middle"><b>4</b></td>
                                                        @endif
                                                    </tr>
                                                    <tr><td colspan="4" style="background-color:gray; color:white; border:solid 1px black; padding-left:.5rem !important" style="font-size: .15rem !important; ">Core Subjects</td></tr>
                                                    @foreach (collect($studgrades)->sortBy('sortid')->where('semid',$x)->where('type',1) as $item)
                                                        <tr>
                                                            <td style="padding-left:.5rem !important">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                            @if($x == 1)
                                                                <td class="text-center align-middle {{$item->quarter1 != null ? $item->quarter1 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter1 != null ? $item->quarter1 < 75 ? $item->quarter1 : $item->quarter1  :''}}</td>
                                                                <td class="text-center align-middle {{$item->quarter2 != null ? $item->quarter2 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter2 != null ? $item->quarter2 < 75 ? $item->quarter2 : $item->quarter2  :''}}</td>
                                                            @elseif($x == 2)
                                                                <td class="text-center align-middle {{$item->quarter3 != null ? $item->quarter3 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter3 != null ? $item->quarter3 < 75 ? $item->quarter3 : $item->quarter3  :''}}</td>
                                                                <td class="text-center align-middle {{$item->quarter4 != null ? $item->quarter4 < 75 ? 'text-red' : '' : ''}}" >{{$item->quarter4 != null ? $item->quarter4 < 75 ? $item->quarter4 : $item->quarter4  :''}}</td>
                                                            @endif
                                                            <td class="text-center align-middle {{$item->finalrating != null ? $item->finalrating < 75 ? 'text-red' : '' : ''}}">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                            <!--<td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>-->
                                                        </tr>
                                                    @endforeach
                                                    <tr><td colspan="4" style="background-color:gray; color:white; border:solid 1px black; padding-left:.5rem !important" style="font-size: .15rem " >Applied and Specialized Subjects</td></td></tr>
                                                    @foreach (collect($studgrades)->sortBy('sortid')->where('semid',$x)->whereIn('type',[3]) as $item)
                                                        <tr>
                                                            <td style="padding-left:.5rem !important">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                            @if($x == 1)
                                                                <td class="text-center align-middle {{$item->quarter1 != null ? $item->quarter1 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter1 != null ? $item->quarter1 < 75 ? $item->quarter1 : $item->quarter1  :''}}</td>
                                                                <td class="text-center align-middle {{$item->quarter2 != null ? $item->quarter2 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter2 != null ? $item->quarter2 < 75 ? $item->quarter2 : $item->quarter2  :''}}</td>
                                                            @elseif($x == 2)
                                                                <td class="text-center align-middle {{$item->quarter3 != null ? $item->quarter3 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter3 != null ? $item->quarter3 < 75 ? $item->quarter3 : $item->quarter3  :''}}</td>
                                                                <td class="text-center align-middle {{$item->quarter4 != null ? $item->quarter4 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter4 != null ? $item->quarter4 < 75 ? $item->quarter4 : $item->quarter4  :''}}</td>
                                                            @endif
                                                            <td class="text-center align-middle {{$item->finalrating != null ? $item->finalrating < 75 ? 'text-red' : '' : ''}}">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                            <!--<td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>-->
                                                        </tr>
                                                    @endforeach
                                                    <tr><td colspan="4" style="background-color:gray; color:white; border:solid 1px black; padding-left:.5rem !important" style="font-size: .15rem " >Specialized</td></td></tr>
                                                    @foreach (collect($studgrades)->sortBy('sortid')->where('semid',$x)->whereIn('type',[2]) as $item)
                                                        <tr>
                                                            <td style="padding-left:.5rem !important">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                            @if($x == 1)
                                                                <td class="text-center align-middle {{$item->quarter1 != null ? $item->quarter1 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter1 != null ? $item->quarter1 < 75 ? $item->quarter1 : $item->quarter1  :''}}</td>
                                                                <td class="text-center align-middle {{$item->quarter2 != null ? $item->quarter2 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter2 != null ? $item->quarter2 < 75 ? $item->quarter2 : $item->quarter2  :''}}</td>
                                                            @elseif($x == 2)
                                                                <td class="text-center align-middle {{$item->quarter3 != null ? $item->quarter3 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter3 != null ? $item->quarter3 < 75 ? $item->quarter3 : $item->quarter3  :''}}</td>
                                                                <td class="text-center align-middle {{$item->quarter4 != null ? $item->quarter4 < 75 ? 'text-red' : '' : ''}}">{{$item->quarter4 != null ? $item->quarter4 < 75 ? $item->quarter4 : $item->quarter4  :''}}</td>
                                                            @endif
                                                            <td class="text-center align-middle {{$item->finalrating != null ? $item->finalrating < 75 ? 'text-red' : '' : ''}}">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                            <!--<td class="text-center align-middle">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>-->
                                                        </tr>
                                                    @endforeach
                                                    <tr style="font-size: .15rem !important">
                                                        @php
                                                            $genave = collect($finalgrade)->where('semid',$x)->first();
                                                        @endphp
                                                        <td colspan="3" class="border-0 text-right" style="padding-right: 10px !important"><b>GENERAL AVERAGE FOR THE SEMESTER</b></td>
                                                        <td class="text-center align-middle">{{ isset($genave->finalrating) ? $genave->finalrating != null ? $genave->finalrating:'' :''}}</td>
                                                        <!--<td class="text-center align-middle">{{ isset($genave->actiontaken) ? $genave->actiontaken != null ? $genave->actiontaken:'' :''}}</td>-->
                                                    </tr>
                                                </table>
                                            @endif
                                        @endfor
                                    @else
                                        @if(isset($student_quarter))
                                            @php
                                                $left = '148px';
                                                if($student_quarter->quarter3 == 1){
                                                    $left = '298px';
                                                }else if($student_quarter->quarter2 == 1){
                                                    $left = '248px';
                                                }else if($student_quarter->quarter1 == 1){
                                                    $left = '198px';
                                                }
                                            @endphp

                                        @endif
                                        <table class="table table-bordered table-sm grades f-11" width="100%" style="border: 0 !important; font-size: 9px !important">
                                            <thead>
                                                <tr>
                                                    <td width="32%" rowspan="2"  class="align-middle text-center pr-0 pl-0"><b>Learning Areas</b></td>
                                                    <td width="44%" colspan="4"  class="text-center align-middle pr-0 pl-0" ><b>Quarter</b></td>
                                                    <td width="10%" rowspan="2"  class="text-center align-middle pr-0 pl-0" ><b>Final<br>Grade</b></td>
                                                    <td width="14%" rowspan="2"  class="text-center align-middle pr-0 pl-0"><b>Remarks</b></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center align-middle pr-0 pl-0" width="11%"><b>1</b></td>
                                                    <td class="text-center align-middle pr-0 pl-0" width="11%"><b>2</b></td>
                                                    <td class="text-center align-middle pr-0 pl-0" width="11%"><b>3</b></td>
                                                    <td class="text-center align-middle pr-0 pl-0" width="11%"><b>4</b></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($studgrades as $item)
                                                    <tr>
                                                        <td style="padding-left:{{$item->subjCom != null ? '2rem !important':'.5rem !important'}}; " class="align-middle pr-0 pl-0">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                                                        <td class="text-center align-middle p-1 pr-0 pl-0">{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                                                        <td class="text-center align-middle p-1 pr-0 pl-0">{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                                                        <td class="text-center align-middle p-1 pr-0 pl-0">{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                                                        <td class="text-center align-middle p-1 pr-0 pl-0">{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                                                        <td class="text-center align-middle p-1 pr-0 pl-0">{{$item->finalrating != null ? $item->finalrating:''}}</td>
                                                        <td class="text-center align-middle p-1 pr-0 pl-0">{{$item->actiontaken != null ? $item->actiontaken:''}}</td>
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    <td style="padding-left:{{$item->subjCom != null ? '2rem':'.25rem'}}" class=" pr-0 pl-0">&nbsp;</td>
                                                    <td class="text-center align-middle  p-1  pr-0 pl-0"></td>
                                                    <td class="text-center align-middle  pr-0 pl-0"></td>
                                                    <td class="text-center align-middle  pr-0 pl-0"></td>
                                                    <td class="text-center align-middle  pr-0 pl-0"></td>
                                                    <td class="text-center align-middle  pr-0 pl-0"></td>
                                                    <td class="text-center align-middle  pr-0 pl-0"></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right border-0" colspan="5" style="padding-right: 10px !important"><b>GENERAL AVERAGE<b></td>
                                                    <td class="text-center pr-0 pl-0 {{collect($finalgrade)->first()->finalrating < 75 ? 'bg-red':''}}">{{collect($finalgrade)->first()->finalrating}}</td>
                                                    <td class="text-center align-middle pr-0 pl-0" style="font-size: 8px !important">{{collect($finalgrade)->first()->actiontaken}}</td>
                                                </tr>
                                            </tbody>
                                        </table>  
                                    @endif
                            

                            
                            @if($student->studstatid == 5 || $student->studstatid == 3 || $student->studstatid == 6)
                            
                        
                                <div style="border-bottom: 1px solid #000; padding: .25rem 0">
                                    <span style="font-weight: bold">REMARKS:</span> {{$student->description}}
                                </div>
                                        
                            @endif
                            @if($acad != 5)
                                <table class="table p-0 plot_3" width="100%" style="margin-top: 5rem !important">
                                    <tr>
                                        <td colspan="3" class="p-0"><b>Learner Progress And Achievement</b></td>
                                    </tr>
                                    <tr>
                                        <td width="40%"  class="p-0  ">&nbsp;</td>
                                        <td width="40%" class="p-0  "></td>
                                        <td width="20%"  class="p-0  "></td>
                                    </tr>
                                    <tr>
                                      
                                        <td width="40%"  class="p-0  "><b>Descriptors</b></td>
                                        <td width="40%" class="p-0  "><b>Grading Scale</b></td>
                                        <td width="20%"  class="p-0  "><b>Remarks</b></td>
                                      
                                    </tr>
                                     
                                    <tr>
                                        <td width="40%" class="p-0  ">Outstanding</td>
                                        <td width="20%"  class="p-0  ">90 - 100</td>
                                        <td width="20%" class="p-0  ">Passed</td>
                                       
                                    </tr>
                                    <tr>
                                        <td width="40%" class="p-0  ">Very Satisfactory</td>
                                        <td width="20%"  class="p-0  ">85 - 90</td>
                                        <td width="20%" class="p-0  ">Passed</td>
                                     
                                    </tr>
                                    <tr>
                                        <td width="40%" class="p-0  ">Satisfactory</td>
                                        <td width="20%"  class="p-0  ">80 - 84</td>
                                        <td width="20%" class="p-0  ">Passed</td>
                                    </tr>
                                    <tr>
                                        <td width="40%" class="p-0  ">Fairly Satisfactory</td>
                                        <td width="20%"  class="p-0  ">75 - 79</td>
                                        <td width="20%" class="p-0  ">Passed</td>
                                        
                                    </tr>
                                    <tr>
                                        <td width="40%" class="p-0  ">Did Not Meet Expectations</td>
                                        <td width="20%"  class="p-0  ">Below  75</td>
                                        <td width="20%" class="p-0  ">Failed</td>
                                      
                                    </tr>
                                </table>
                            @endif
                        </td>
                    
                    @if($acad != 5)
                         <td width="50%" style="padding-left:.5in !important; padding-top:.25in !important">
                    @else
                          <td width="50%" style="padding-left:.5in !important;">
                    @endif
                 
                      
                                @if($acad != 5)
                                    <table class="table table-sm" width="100%" >
                                        <tr>
                                            <th class="p-0 text-center border-0" style="font-size: 1rem !important" width="100%" colspan="6">REPORT ON LEARNER'S OBSERVED VALUES</th>
                                        </tr>
                                    </table>
                                @else
                                    <table class="table table-sm mb-0 p-0" width="100%" >
                                        <tr>
                                            <th class="p-0 text-center border-0" style="font-size: .8rem !important" width="100%" colspan="6">REPORT ON LEARNER'S OBSERVED VALUES</th>
                                        </tr>
                                    </table>
                                @endif
                         
                        <table class="table table-bordered table-sm plot_2" width="100%">
                            @if($acad != 5)
                               <thead>
                                    <tr>
                                        <th rowspan="2" width="15%" class="align-middle"><center >Core Values</center></th>
                                        <th rowspan="2" width="29%" class="align-middle"><center>Behavior Statements</center></th>
                                        <th colspan="4" width="56%"  class="cellRight"><center>Quarter</center></th>
                                    </tr>
                                    <tr>
                                        <th width="14%"><center>1</center></th>
                                        <th width="14%"><center>2</center></th>
                                        <th width="14%"><center>3</center></th>
                                        <th width="14%"><center>4</center></th>
                                    </tr>
                                </thead>
                            @else
                                    <thead>
                                        <tr>
                                            <th rowspan="2" width="20%" class="align-middle"><center >Core Values</center></th>
                                            <th rowspan="2" width="44%" class="align-middle"><center>Behavior Statements</center></th>
                                            <th colspan="4" width="36%"  class="cellRight"><center>Quarter</center></th>
                                        </tr>
                                        <tr>
                                            <th width="9%"><center>1</center></th>
                                            <th width="9%"><center>2</center></th>
                                            <th width="9%"><center>3</center></th>
                                            <th width="9%"><center>4</center></th>
                                        </tr>
                                    </thead>
                            @endif
                            @foreach (collect($checkGrades)->groupBy('group') as $groupitem)
                                @php
                                    $count = 0;
                                @endphp
                                @foreach ($groupitem as $item)
                                    @if($item->value == 0)
                                            <tr>
                                                <td colspan="6">{{$item->description}}</td>
                                            </tr>
                                    @else
                                            <tr>
                                                @if($count == 0)
                                                        <td class="align-middle" rowspan="{{count($groupitem)}}">
                                                            {{$item->group}}
                                                        </td>
                                                        @php
                                                            $count = 1;
                                                        @endphp
                                                @endif
                                                <td class="align-middle">
                                                    {{$item->description}}
                                                </td>
                                                <td class="text-center align-middle">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q1eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle p-0">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q2eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                                <td class="text-center align-middle p-0">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q3eval == $rvitem->id ? $rvitem->value : ''}} 
                                                    @endforeach
                                                </td>
                                                <td class="text-center align-middle  p-0">
                                                    @foreach ($rv as $key=>$rvitem)
                                                        {{$item->q4eval == $rvitem->id ? $rvitem->value : ''}}
                                                    @endforeach 
                                                </td>
                                            </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </table>
                        
                         <table class="table table-sm grades plot_2" width="100%">
                            <thead>
                                <tr>
                                    <td colspan="4"><b>Observed Values</b></td>
                                </tr>
                                <tr>
                                    <td colspan="4">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td width="25%" class="text-center p-0"><b>Marking</b></td>
                                    <td width="40%" class="p-0">Non- Numerical Rating</b></td>
                                    <td width="40%" class="p-0"></td>
                                </tr>
                            </thead>
                            <tbody>
                                 @foreach ($rv as $key=>$rvitem)
                                    @if($rvitem->value != null)
                                        <tr>
                                            <td class="text-center p-0">{{$rvitem->value}}</td>
                                            <td class="p-0">{{$rvitem->description}}</td>
                                            <td class="p-0"></td>
                                         </tr>
                                    @endif
                                @endforeach 
                            </tbody>
                        </table>
                        @if($acad == 5)
                            <table class="table table-sm grades p-0 plot_2" width="100%">
                                <tr>
                                    <td colspan="3"><b>Learner Progress And Achievement</b></td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
                                <tr>
                                    
                                    <td width="40%" ><b>Descriptors</b></td>
                                    <td width="20%" ><b>Grading Scale</b></td>
                                    <td width="20%" ><b>Remarks</b></td>
                       
                                </tr>
                                <tr>
                                   
                                    <td width="40%" class="  ">Outstanding</td>
                                    <td width="20%"  class="  ">90 - 100</td>
                                    <td width="20%" class="  ">Passed</td>
                                 
                                </tr>
                                <tr>
                                   
                                    <td width="40%" class="  ">Very Satisfactory</td>
                                    <td width="20%"  class="  ">85 - 90</td>
                                    <td width="20%" class="  ">Passed</td>
                                  
                                </tr>
                                <tr>
                                  
                                    <td width="40%" class="  ">Satisfactory</td>
                                    <td width="20%"  class="  ">80 - 84</td>
                                    <td width="20%" class="  ">Passed</td>
                                    
                                </tr>
                                <tr>
                                
                                    <td width="40%" class="  ">Fairly Satisfactory</td>
                                    <td width="20%"  class="  ">75 - 79</td>
                                    <td width="20%" class="  ">Passed</td>
                                    
                                </tr>
                                <tr>
                                  
                                    <td width="40%" class="  ">Did Not Meet Expectations</td>
                                    <td width="20%"  class="  ">Below  75</td>
                                    <td width="20%" class="  ">Failed</td>

                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>
        </table>

    
</div>

</body>
</html>