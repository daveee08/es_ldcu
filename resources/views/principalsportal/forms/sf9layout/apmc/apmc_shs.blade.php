<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <meta http-equiv="X-UA-Compatible" content="ie=edge"> --}}
    {{-- <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> --}}
    <title>Document</title>
    <style>
    @page{
        margin: .5in 1in;
    }

            * {
            
            font-family: Arial, Helvetica, sans-serif;
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
</head>
<body>
    <div class="container">
        <table class="table" width="100%" >
                <tr>
                    <td width="50%">
                        <table width="100%">
                            <tr>
                                <td><center>REPORT ON ATTENDANCE</center></td>
                            </tr>
                        </table >
                        <table width="100%" style="border:solid 1px black">
                            <tr class="table-bordered">
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
                            </tr>
                        </table>
                        <table class="table" style="margin-top:100px !important" width="100%">
                            <tr class="text-center">
                                <td colspan="2">Homeroom Remarks & Parent's Signature</td>
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
                    <td width="50%">
                        <table class="table" style="font-size:12px; table-layout: fixed; line-height: 15px; margin: 0px;"  width="100%">
                            <tr>
                                <td class=" p-0" style="width: 30%;">FORM 138</td>
                                <td class=" p-0" style="width: 40%; text-align: right;">LRN No.:</td>
                                <td class=" p-0" style="width: 30%; border-bottom: 1px solid black;">{{$student[0]->lrn}}</td>
                            </tr>
                            <tr>
                                <td class="p-0" colspan="3">&nbsp;</td>
                            </tr>
                            <tr>
                                <td class=" p-0" rowspan="5" style="text-align: right;"><img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="100px"></td>
                                <td class="text-center  p-0 ">REPUBLIKA NG PILIPINAS</td>
                                <td class=" p-0" rowspan="5" style="text-align: left; vertical-align: middle;"><img src="{{base_path()}}/public/assets/images/deped_logo.png" alt="school" width="120px"></td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">Department of Education</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">{{$schoolinfo[0]->regDesc}}</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0">Sangay ng {{$schoolinfo[0]->citymunDesc}}</td>
                            </tr>
                            <tr>
                                <td class="text-center p-0 ">{{$schoolinfo[0]->district}}</td>
                            </tr>
                            <tr>
                                <td class="text-center  p-0" colspan="3" style="font-size: 15px !importantpx;"><h3>{{$schoolinfo[0]->schoolname}}</h3></td>
                            </tr>
                            <!--<tr>-->
                            <!--<td class="text-center  p-2"><img src="{{asset($schoolinfo[0]->picurl)}}" alt="school" width="80px"></td>-->
                            <!--</tr>-->
                        </table>
                        <table style="font-size:13px; !important; table-layout: fixed; margin: 0px; line-height: 5px;" width="100%">
                            @php
                                $midname = [];
                                $midnamestring = '';
                                if(count($midname) > 0){
                                    $midnamestring = substr($midname[0], 0, 1);
                                }
                            @endphp
                            <tr>
                                <td style="width: 24%;">Name:</td>
                                @if($student->studstatid == 5 || $student->studstatid == 3 || $student->studstatid == 6)
                            
                                    <td colspan="4" style="border-bottom: 1px solid black; color: red;">{{$student[0]->lastname}}, {{$student[0]->firstname}} {{$midnamestring}}</td>
                                @else
                                    <td colspan="4" style="border-bottom: 1px solid black;">{{$student[0]->lastname}}, {{$student[0]->firstname}} {{$midnamestring}}</td>
                                @endif
                                
                            </tr>
                            <tr>
                                <td>Age:</td>
                                <td style="border-bottom: 1px solid black; width: 10%;">{{\Carbon\Carbon::parse($student[0]->dob)->age}}</td>
                                <td style="width: 10%;">Sex:</td>
                                <td colspan="2" style="border-bottom: 1px solid black;">{{$student[0]->gender}}</td>
                            </tr>
                            <tr>
                                <td>Grade:</td>
                                <td style="border-bottom: 1px solid black;">{{str_replace('GRADE', '', $student[0]->enlevelname)}}</td>
                                <td colspan="2" style="width: 20%; text-align: right;">Section:</td>
                                <td style="border-bottom: 1px solid black;">{{$student[0]->ensectname}}</td>
                            </tr>
                            <tr>
                                <td>School Year:</td>
                                <td colspan="3" style="border-bottom: 1px solid black;">{{Session::get('schoolYear')->sydesc}}</td>
                                <td></td>
                            </tr>
                        </table>
                        <br/>
                        <table style="font-size:11px; !important; table-layout: fixed; margin: 0px; margin-left: 8px; margin-right: 8px;" width="100%">
                            <tr>
                                <td colspan="5" class="p-1">Dear Parent:</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-1" style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This report card shows the ability and progress your child has made in the different learning areas as well as his/her core values.</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-1" style="text-align: justify;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;The school welcomes you should you desire to your child's progress.</td>
                            </tr>
                        </table>
                        <table style="font-size:11px; !important; table-layout: fixed; margin: 0px; margin-left: 8px; margin-right: 8px;" width="100%">
                            <tr>
                                <td></td>
                                <td style="border-bottom: 1px solid black;"></td>
                            </tr>
                             <tr>
                                <td></td>
                                <td class="text-center p-0">Teacher</td>
                            </tr>
                             <tr>
                                <td style="border-bottom: 1px solid black;"></td>
                                <td></td>
                            </tr>
                             <tr>
                                <td class="text-center p-0">Principal</td>
                                <td></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-0"></td>
                            </tr>
                        </table>
                        <table style="width: 100%; font-size: 11px; margin: 0px; margin-left: 8px; margin-right: 8px;">
                            <tr>
                                <th colspan="5" class="text-center">Certificate of Transfer</th>
                            </tr>
                            <tr>
                                <td style="width: 30%;" class="p-0">Admitted to Grade:</td>
                                <td colspan="2" style="border-bottom: 1px solid black;" class="p-0"></td>
                                <td style="width: 10%;" class="p-0">Section:</td>
                                <td style="border-bottom: 1px solid black;" class="p-0"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-0">Eligibility for Admission to Grade:</td>
                                <td colspan="3" class="p-0" style="border-bottom: 1px solid black;"></td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-0">Approved:</td>
                            </tr>
                            <tr>
                                <td colspan="5" class="p-0"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-0" style="border-bottom: 1px solid black;"></td>
                                <td></td>
                                <td colspan="2" class="p-0" style="border-bottom: 1px solid black;"></td>
                            </tr>
                            <tr>
                                <td colspan="2" class="p-0 text-center">Principal</td>
                                <td></td>
                                <td colspan="2" class="p-0 text-center">Teacher</td>
                            </tr>
                        </table>
                        <table style="width: 100%; table-layout: fixed; font-size: 11px; margin: 0px; margin-left: 8px; margin-right: 8px;">
                            <tr>
                                <th colspan="5" class="p-0">Cancellation of Eligibility to Transfer</th>
                            </tr>
                            <tr>
                                <td style="width: 20%;" class="p-0">Admitted in:</td>
                                <td colspan="2" style="border-bottom: 1px solid black;" class="p-0"></td>
                                <td style="width: 10%;" class="p-0"></td>
                                <td class="p-0"></td>
                            </tr>
                            <tr>
                                <td style="width: 20%;" class="p-0">Date:</td>
                                <td style="border-bottom: 1px solid black;" class="p-0"></td>
                                <td style="width: 10%;" class="p-0"></td>
                                <td class="p-0"></td>
                                <td class="p-0"></td>
                            </tr>
                            <tr>
                                <th colspan="5" class="p-0">&nbsp;</th>
                            </tr>
                            <tr>
                                <th colspan="5" class="p-0">&nbsp;</th>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td style="width: 10%;" class="p-0"></td>
                                <td class="p-0" style="width: 20%;"></td>
                                <td colspan="2" style="width: 40%; border-bottom: 1px solid black;" class="p-0"></td>
                            </tr>
                            <tr>
                                <td class="p-0"></td>
                                <td style="width: 10%;" class="p-0"></td>
                                <td class="p-0" style="width: 20%;"></td>
                                <th colspan="2" style="width: 40%;" class="p-0">Principal</th>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>
    </div>
   <div class="container">

   

        <table class="table" width="100%">
                <tr>
                    @if($student[0]->acadprogid != 5)
                        <td width="50%" >
                            <div style="top: 100px !important; left: {{$left}} !important;  position:relative; vertical-align:middle !important;margin: auto !important;">
                                <div style="border: solid 1px black; position:absolute; text-align:center; width: 150px !important; height: 200px !important; vertical-align:middle !important; background-color:white; color:rgb(0, 0, 0); padding-top: 40px !important;">
                                    {{$student->description}}<br>{{\Carbon\Carbon::create($student->studstatdate)->isoFormat('MMM. DD, YYYY')}}<br>{{$student->remarks}}
                                </div>
                            </div>
                            <table  class="table table-sm" width="100%">
                                <tr>
                                    <th class="p-2 text-center border-0" colspan="7" style="font-size:15px !important;font-family: Arial, Helvetica, sans-serif;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                                </tr>
                            
                            </table>
                            <table class="table table-sm" width="100%">
                                <tr class="table-bordered">
                                    <th width="50%" rowspan="2"  class="align-middle"  style="text-align: left !important;">SUBJECTS</th>
                                
                                    <td width=30%" colspan="4"  class="text-center align-middle" style="font-size:15px !important; font-family: Arial, Helvetica, sans-serif;">PERIODIC RATINGS</td>
                                

                                    <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;">FINAL RATING</td>
                                    <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;"><span class="p-1" >ACTION TAKEN</span></td>
                                </tr>
                                <tr class="table-bordered" style="font-size:11px !important;">
                                    <td class="text-center align-middle">1</td>
                                    <td class="text-center align-middle" >2</td>

                                
                                        <td class="text-center align-middle" >3</td>
                                        <td class="text-center align-middle" >4</td>
                                
                                </tr>
                                
                                
                            @php
                                $quarter1complete = true;
                                $quarter2complete = true;
                                $quarter3complete = true;
                                $quarter4complete = true;
                            @endphp
                        
                            @if( count($grades) != 0)
                                @foreach ($grades as $item)

                                    @if($item->quarter1 == null)
                                        @php
                                            $quarter1complete = false;
                                        @endphp
                                    @endif

                                    @if($item->quarter2 == null)
                                        @php
                                            $quarter2complete = false;
                                        @endphp
                                    @endif

                                    @if($item->quarter3 == null)
                                        @php
                                            $quarter3complete = false;
                                        @endphp
                                    @endif

                                    @if($item->quarter4 == null)
                                        @php
                                            $quarter4complete = false;
                                        @endphp
                                    @endif

                                    @php
                                        $average = ($item->quarter1 + $item->quarter2 + $item->quarter3 + $item->quarter4) / 4 ;
                                    @endphp

                                    <tr class="table-bordered">
                                        
                                        @if($item->subjectcode!=null)
                                            <td class="p-1 @if($item->mapeh == 1) pl-4 @endif" style="text-align: left !important;font-size:13px !important" >
                                                {{$item->subjectcode}}
                                            </td>
                                        @else
                                            <td class="p-1" style="text-align: left !important;" >
                                                &nbsp;
                                            </td>
                                        @endif

                                        @if($item->quarter1 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter1}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                        @endif

                                    

                                        @if($item->quarter2 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter2}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                        @endif

                                        @if($item->quarter3 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter3}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle"  style="font-size:13px !important">&nbsp;</td>
                                        @endif

                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter4}}</td>
                                        
                                        @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{number_format( ($item->quarter1+$item->quarter2+$item->quarter3+$item->quarter4)/4)}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important"></td>
                                        @endif

                                        @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $average = null;
                                @endphp
                            @endif
                            @if( count($grades) != 0)
                                @php
                                    $genaverage =  (collect($grades)->where('mapeh',0)->avg('quarter1') + collect($grades)->where('mapeh',0)->avg('quarter2') + collect($grades)->where('mapeh',0)->avg('quarter3') + collect($grades)->where('mapeh',0)->avg('quarter4')) / 4 ;
                                @endphp
                            @else
                                @php
                                    $genaverage = null;    
                                @endphp
                            @endif
                                
                            <tr class="table-bordered genave">
                                
                                    <th class="p-1" style="text-align: left !important">GENERAL AVERAGE {{$quarter2complete}}
                                    </th>

                                    @if($quarter1complete)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter1'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if($quarter2complete)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter2'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif
                                
                                    @if($quarter3complete)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter3'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if($quarter4complete)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('mapeh',0)->avg('quarter4'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                        <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important">{{number_format($average)}}</td>
                                    @else
                                        <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important"></td>
                                    @endif

                                    @if($item->quarter1 != null && $item->quarter2 != null && $item->quarter3 != null && $item->quarter4 != null)
                                        <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                                    @else
                                        <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                    @endif
                                </tr>
                            </table>
                            <table class="table  p-0 " style="font-size:11px !important" width="100%">
                                <tr>
                                    <td width="10%"  class="p-0  "></td>
                                    <td width="40%"  class="p-0  ">Descriptors</td>
                                    <td width="20%" class="p-0  ">Grading Scale</td>
                                    <td width="20%"  class="p-0  ">Remarks</td>
                                    <td width="10%"  class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="40%" class="p-0  ">Outstanding</td>
                                    <td width="20%"  class="p-0  ">90 - 100</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="40%" class="p-0  ">Very Satisfactory</td>
                                    <td width="20%"  class="p-0  ">85 - 90</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="40%" class="p-0  ">Satisfactory</td>
                                    <td width="20%"  class="p-0  ">80 - 84</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="40%" class="p-0  ">Fairly Satisfactory</td>
                                    <td width="20%"  class="p-0  ">75 - 79</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="40%" class="p-0  ">Did Not Meet Expectation</td>
                                    <td width="20%"  class="p-0  ">Below  75</td>
                                    <td width="20%" class="p-0  ">Failed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                            </table>
                        </td>
                    @else
                    <td width="50%" >
                        <table  class="table table-sm" width="100%">
                            <tr>
                                <th class="p-2 text-center border-0" colspan="7" style="font-size:15px !important;font-family: Arial, Helvetica, sans-serif;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                            </tr>
                        
                        </table>

                        <p>FIRST SEMESTER</p>

                        <table class="table table-sm" width="100%" style="font-size: 11px;">
                            <tr class="table-bordered">
                                <th width="50%" rowspan="2"  class="align-middle"  style="text-align: left !important;">SUBJECTS</th>
                            
                                <td width=30%" colspan="2"  class="text-center align-middle" style="font-size:15px !important; font-family: Arial, Helvetica, sans-serif;">PERIODIC RATINGS</td>
                            

                                <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;">FINAL RATING</td>
                                <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;"><span class="p-1" >ACTION TAKEN</span></td>
                            </tr>
                            <tr class="table-bordered" style="font-size:11px !important;">
                                <td class="text-center align-middle">1</td>
                                <td class="text-center align-middle" >2</td>
                            </tr>
                            
                        @if( count($grades) != 0 && collect($grades)->where('semid',1)->count() > 0)

                            @foreach (collect($grades)->where('semid',1) as $item)

                                

                                @php
                                    $average = ($item->quarter1 + $item->quarter2 ) / 2 ;
                                @endphp

                                <tr class="table-bordered">
                                    @if($item->subjectcode!=null)
                                        <td class="p-1" style="text-align: left !important" >
                                            {{$item->subjectcode}}
                                        </td>
                                    @else
                                        <td class="p-1" style="text-align: left !important;" >
                                            &nbsp;
                                        </td>
                                    @endif

                                    @if($item->quarter1 != null)
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter1}}</td>
                                    @else
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                    @endif

                                    @if($item->quarter2 != null)
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter2}}</td>
                                    @else
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                    @endif

                                
                                    
                                    @if($item->quarter1 != null && $item->quarter2 != null)
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important">{{number_format( ($item->quarter1+$item->quarter2) / 2 )}}</td>
                                    @else
                                        <td class="text-center p-0 align-middle" style="font-size:13px !important"></td>
                                    @endif

                                    @if($item->quarter1 != null && $item->quarter2 != null )
                                        <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                    @else
                                        <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                    @endif
                                </tr>
                            @endforeach
                        @else
                            @php
                                $average = null;
                            @endphp
                        @endif

                        @if( count($grades) != 0)
                            @php
                                $genaverage = (collect($grades)->avg('quarter1') + collect($grades)->avg('quarter2') ) / 2 ;
                            @endphp
                        @else
                            @php
                                $genaverage = null;    
                            @endphp
                        @endif
                            
                            <tr class="table-bordered genave">
                                
                                    <th class="p-1" style="text-align: left !important">GENERAL AVERAGE
                                    </th>

                                    @if(collect($grades)->where('semid',1)->where('quarter1',null)->count() == 0)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('semid',1)->avg('quarter1'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if(collect($grades)->where('semid',1)->where('quarter2',null)->count() == 0)
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('semid',1)->avg('quarter2'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif
                                
                                    
                                    @if( collect($grades)->where('semid',1)->where('quarter1',null)->count() == 0 && collect($grades)->where('semid',1)->where('quarter2',null)->count() == 0 )

                                        <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important">{{number_format(collect($grades)->where('semid',1)->avg('finalRating'))}}</td>
                                    @else
                                        <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                    @endif

                                    @if(collect($grades)->where('semid',1)->where('finalRating',null)->count() == 0)
                                        <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                                    @else
                                        <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                    @endif
                                </tr>
                            </table>

                            <p>SECOND SEMESTER</p>
    
    
                            <table class="table table-sm" width="100%">
                                <tr class="table-bordered">
                                    <th width="50%" rowspan="2"  class="align-middle"  style="text-align: left !important;">SUBJECTS</th>
                                
                                    <td width=30%" colspan="2"  class="text-center align-middle" style="font-size:15px !important; font-family: Arial, Helvetica, sans-serif;">PERIODIC RATINGS</td>
                                
    
                                    <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;">FINAL RATING</td>
                                    <td width="10%" rowspan="2"  class="text-center align-middle p-0"  style="font-size:11px !important;font-family: Arial, Helvetica, sans-serif;"><span class="p-1" >ACTION TAKEN</span></td>
                                </tr>
                                <tr class="table-bordered" style="font-size:11px !important;">
                                    <td class="text-center align-middle">3</td>
                                    <td class="text-center align-middle">4</td>
                                </tr>
                                
                                
                            @php
                                $quarter1complete = false;
                                $quarter2complete = false;
                            @endphp
                        
                            @if( count($grades) != 0 && collect($grades)->where('semid',2)->count() > 0)
    
                                @foreach (collect($grades)->where('semid',2) as $item)
    
                                    @php
                                        $average = ($item->quarter1 + $item->quarter2 ) / 2 ;
                                    @endphp
    
                                    <tr class="table-bordered">
                                        @if($item->subjectcode!=null)
                                            <td class="p-1" style="text-align: left !important" >
                                                {{$item->subjectcode}}
                                            </td>
                                        @else
                                            <td class="p-1" style="text-align: left !important;" >
                                                &nbsp;
                                            </td>
                                        @endif
    
                                        @if($item->quarter1 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter1}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                        @endif
    
                                        @if($item->quarter2 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{$item->quarter2}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">&nbsp;</td>
                                        @endif
    
                                       
                                        
                                        @if($item->quarter1 != null && $item->quarter2 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important">{{number_format( ($item->quarter1+$item->quarter2 ) / 2 )}}</td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:13px !important"></td>
                                        @endif
    
                                        @if($item->quarter1 != null && $item->quarter2 != null)
                                            <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($average >= 75) Passed @else Failed  @endif</i></td>
                                        @else
                                            <td class="text-center p-0 align-middle" style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                @php
                                    $average = null;
                                @endphp
                            @endif
                            @if( count($grades) != 0 && collect($grades)->where('semid',2)->count() > 0)
                                @php
                                    $genaverage =  (collect($grades)->where('semid',2)->avg('quarter1') + collect($grades)->where('semid',2)->avg('quarter2') ) / 2  ;
                                @endphp
                            @else
                                @php
                                    $genaverage = null;    
                                @endphp
                            @endif
                                
                                <tr class="table-bordered genave">
                                    
                                        <th class="p-1" style="text-align: left !important">GENERAL AVERAGE
                                        </th>
    
                                        @if(collect($grades)->where('semid',2)->where('quarter1',null)->count() == 0)
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('semid',2)->avg('quarter1'))}}</td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                        @endif
    
                                        @if(collect($grades)->where('semid',2)->where('quarter2',null)->count() == 0)
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">{{round(collect($grades)->where('semid',2)->avg('quarter2'))}}</td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                        @endif
                                    
                                        
                                        @if( collect($grades)->where('semid',2)->where('quarter1',null)->count() == 0 && collect($grades)->where('semid',2)->where('quarter2',null)->count() == 0 )

                                            <td class="text-center p-0 align-middle"  style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important">{{number_format( collect($grades)->where('semid',2)->avg('finalRating') )}}</td>
                                        @else
                                            <td class="text-center p-1" style="font-family: Arial, Helvetica, sans-serif; font-size:13px !important;">&nbsp;</td>
                                        @endif
    
                                        @if(collect($grades)->where('semid',2)->where('finalRating',null)->count() == 0)
                                            <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"><i>@if($genaverage >= 75) Passed @elseif($genaverage == null) @else Failed  @endif</i></td>
                                        @else
                                            <td class="text-center p-0 align-middle"  style="font-size:11px !important; font-family: Arial, Helvetica, sans-serif;"></td>
                                        @endif
                                    </tr>
                            </table>

                            <table class="table  p-0 " style="font-size:11px !important" width="100%">
                                <tr>
                                    <td width="10%"  class="p-0  "></td>
                                    <td width="20%" class="p-0  ">Grading Scale</td>
                                    <td width="40%"  class="p-0  ">Descriptors</td>
                                    <td width="20%"  class="p-0  ">Remarks</td>
                                    <td width="10%"  class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">A - 90 - 100</td>
                                    <td width="40%" class="p-0  ">Outstanding</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">B - 85 - 90</td>
                                    <td width="40%" class="p-0  ">Satisfactory</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">C - 80 - 84</td>
                                    <td width="40%" class="p-0  ">Needs Improvement</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">D - 75 - 79</td>
                                    <td width="40%" class="p-0  ">Fairly Satisfactory</td>
                                    <td width="20%" class="p-0  ">Passed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                                <tr>
                                    <td width="10%" class="p-0  "></td>
                                    <td width="20%"  class="p-0  ">E - Below  75</td>
                                    <td width="40%" class="p-0  ">Did Not Meet Expectation</td>
                                    <td width="20%" class="p-0  ">Failed</td>
                                    <td width="10%" class="p-0  "></td>
                                </tr>
                            </table>
                        </td>
                    @endif

                    <td width="50%" style="padding:20px !important">
                        <table class="table table-sm" width="100%" style="font-size: 11px;">
                            <tr>
                                <th class="p-2 text-center border-0" colspan="6" style="font-size:15px !important;font-family: Arial, Helvetica, sans-serif;">REPORT ON LEARNING PROGRESS AND ACHIEVEMENT</th>
                            </tr>
                        
                        </table>
                        <table class="table table-bordered table-sm" style="font-size:11px !important" width="100%">
                            <tr>
                                <th colspan="2" rowspan="2"  width="75%" class="align-middle">Core Values</th>
                                <th colspan="4" width="25%" class="text-center">MARKAHAN</th>
                            </tr>
                            <tr>
                                <td class="text-center">1</td>
                                <td class="text-center">2</td>
                                <td class="text-center">3</td>
                                <td class="text-center">4</td>
                            </tr>
                            <tr>
                                <td rowspan="2" colspan="1">1. Maka-DIYOS</td>
                                <td colspan="1" style="margin-bottom: 20px !important">Expresses one's spiritual beliefs while respecting the beliefs of others</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaDiyos_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaDiyos_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaDiyos_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaDiyos_1')->first()}}</td>
                            </tr>
                            <tr>
                                <td colspan="1" style="margin-bottom: 20px !important">Shows adherence to ethical principles by upholding the truth in all undertakings</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaDiyos_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaDiyos_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaDiyos_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaDiyos_2')->first()}}</td>
                            </tr>
                            <tr>
                                <td rowspan="1" colspan="1">2. Maka-Tao</td>
                                <td colspan="1" style="margin-bottom: 20px !important">Is sensitive to individual, social and cultural differences; resists stereotyping people</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaTao_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaTao_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaTao_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaTao_1')->first()}}</td>
                            </tr>
                            {{-- <tr>
                                <td colspan="1" style="margin-bottom: 20px !important">Demonstrates contributions towards solidarity</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaTao')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaTao')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaTao')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaTao')->first()}}</td>
                            </tr> --}}
                            <tr>
                                <td rowspan="2" colspan="1">3. Maka-KALIKASAN</td>
                                <td colspan="1" style="margin-bottom: 20px !important">Cares for the environment and utilizes resources wisely, judiciously and economically</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaKalikasan_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaKalikasan_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaKalikasan_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaKalikasan_1')->first()}}</td>
                            </tr>

                            <tr>
                                <td colspan="1" style="margin-bottom: 20px !important">Demonstrates contributions towards solidarity</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaKalikasan_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaKalikasan_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaKalikasan_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaKalikasan_2')->first()}}</td>
                            </tr>
                        
                            <tr>
                                <td rowspan="2" colspan="1">4. Maka-BANSA</td>
                                <td colspan="1" style="margin-bottom: 20px !important">Demonstrates pride in being a Filipino, exercises the rights and responsibilities of a Filipino Citizen</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaBansa_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaBansa_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaBansa_1')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaBansa_1')->first()}}</td>
                            </tr>
                            <tr>
                                <td colspan="1" style="margin-bottom: 20px !important">Demonstrates appropriate behavior in carrying out activities in the school, community and country</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','1') ->pluck('makaBansa_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','2') ->pluck('makaBansa_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','3') ->pluck('makaBansa_2')->first()}}</td>
                                <td class="text-center align-middle">{{collect($coreValues)->where('quarter','4') ->pluck('makaBansa_2')->first()}}</td>
                            </tr>
                        </table>
                        <table class="table table-sm" style="font-size:11px !important" width="100%">
                            <tr>
                                <td width="20%" class="p-0">Marking</td>
                                <td width="80%" class="p-0">Non-Numerical Rating</td>
                            </tr>
                            <tr>
                                <td width="20%" class="p-0">AO</td>
                                <td width="80%" class="p-0">Always Observed</td>
                            </tr>
                            <tr>
                                <td width="20%" class="p-0">SO</td>
                                <td width="80%" class="p-0">Sometimes Observed</td>
                            </tr><tr>
                                <td width="20%" class="p-0">RO</td>
                                <td width="80%" class="p-0">Rarely Observed</td>
                            </tr>
                            <tr>
                                <td width="20%" class="p-0">NO</td>
                                <td width="80%" class="p-0">Not Observed</td>
                            </tr>
                        </table>
                    </td>
                </tr>
        </table>

    
</div>

</body>
</html>