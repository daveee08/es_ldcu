<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{$student->firstname.' '.$student->middlename[0].' '.$student->lastname}}</title> --}}
    <style>

        .table {
            background-color: transparent;
            vertical-align: middle;
            table-layout: fixed;
            width: 100%;
        }

        table {
            border-collapse: collapse;
            
        }
        
        .table thead th {
            vertical-align: bottom;
        }
        
        .table td, .table th {
            /* padding-top: 5px; */
            padding-bottom: 2px;
            /* vertical-align: top; */
        }
        
        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td, .table-bordered th {
            border: 1px solid #00000;
        }

        .table-sm td, .table-sm th {
            padding: 5px!important;
        }
        .u{
            text-decoration: underline;
        }

        .text-center{
            text-align: center !important;
        }

        .cell-center td:nth-child(3){
            text-align: center !important;
        }
        
        .cell-center td:nth-child(4){
            text-align: center !important;
        }
        
        .text-right{
            text-align: right !important;
        }
        
        .text-left{
            text-align: left !important;
        }
        
        .p-0{
            padding-top: 0;
            padding-bottom: 0;
            padding-right: 0;
            padding-left: 0;
        }
        .p-0 td{
            padding-top: 0;
            padding-bottom: 0;
            padding-right: 0;
            padding-left: 0;
        }
        .p-2{
            padding-right: 2px!important;
            padding-left: 2px!important;
        }
       .pb-0{
            padding-bottom: 0!important;
       }
        .pl-3{
            padding-left: 1rem !important;
        }

        .mb-0{
            margin-bottom: 0;
        }
        .mt-0{
            margin-top: 0!important;
        }
        .mb-1, .my-1 {
            margin-bottom: .25rem!important;
        }

        .mx-2{
            margin-left: .4in;
            margin-right: .4in;
        }

        body{
            font-family: 'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;
            font-size: 7pt;
        }
        p{
            margin: 0;
        }
        .align-top{
            vertical-align: top!important;
        }
        .align-middle{
            vertical-align: middle !important;    
        }
        .align-bottom{
            vertical-align: bottom!important;
        }
         
        .grades td{
            padding-top: 5px;
            padding-bottom: 5px;
            font-size: 9pt;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }
        .bold{
            font-weight: bold!important;
        }
        .bg-red{
            color: red;
            border: solid 1px black !important;
        }

        td{
            padding-left: 10px;
            padding-right: 10px;
        }
        .aside {
            /* background: #48b4e0; */
            color: #000;
            line-height: 15px;
            height: 35px;
            border: 1px solid #000!important;
            
        }
        .aside span {
            /* Abs positioning makes it not take up vert space */
            /* position: absolute; */
            top: 0;
            left: 0;

            /* Border is the new background */
            background: none;

            /* Rotate from top left corner (not default) */
            transform-origin: 10 10;
            transform: rotate(-90deg);
        }
        .trhead {
            background-color: rgb(167, 223, 167); 
            color: #000; font-size;
        }
        .trhead td {
            border: 1px solid #000;
        }
        /* @page {  
            margin:20px 20px;
            
        } */
        body { 
            /* margin:0px 10px; */
        }

        .thead-border th{
            border: 2px solid black;
            text-transform: uppercase;
            padding-top: 10px!important;
            padding-bottom: 10px!important;
        }

        .tfoot-border td{
            border: 1px solid black;
            text-transform: uppercase;
            padding-top: 20px!important;
            padding-bottom: 20px!important;
        }


        .small-font{
            font-size: 7pt!important;
        }
        .smaller-font{
            font-size: 6.5pt!important;
        }
        .smallest-font{
            font-size: 6pt!important;
        }
        .smallestest-font{
            font-size: 5pt!important;
        }
        h4{
            margin-bottom: 10px;
        }
        .suptitle{
            font-size: 15pt!important;
        }
        .round-bordered{
            border: 2px solid black;
            border-radius: 10px!important;
        }
        .rounded-bordered{
            border: 2px solid black;
            border-radius: 30px!important;
        }
        .title{
            font-size: 10pt!important;
        }
        .subtitle{
            font-size: 9pt!important;
        }
        .space{
            margin-top: 10px!important
        }
        .spacing{
            margin-top: 10px!important;
        }
        .superspace{
            margin-top: .8in!important;
        }
        .underline{
            border-bottom: 1px solid black;
        }
        .timesnew{
            font-family: 'Times New Roman', Times, serif;
        }
        #space{
            margin-top: .2in!important
        }
        .right-margin{
            margin-right: .5in!important;
        }
        .left-margin{
            margin-left: .3in!important;
        }
        .smallcompressed{
            margin-right: .15in!important;
            margin-left: .15in!important;
        }
        .compressed{
            margin-right: .3in!important;
            margin-left: .3in!important;
        }
        .supcompressed{
            margin-right:1in!important;
            margin-left: 1in!important;
        }
        .border-top-bottom{
            border-top: 1px solid black;
            border-bottom: 1px solid black;

        }
        .border{
            border: 1px solid black;
        }
        .indent{
            text-indent: .1in;
        }
        .small-indent{
            text-indent: .4in
        }
        .new-page{
            page-break-after: always;
        }
        .padding{
            padding-bottom: 12px !important;
            padding-top: 12px !important;
        }
        .pad td{
            padding-left: 5px!important;
        }
        .no-border{
            border: none!important;
        }
        .no-border td{
            border: none!important;
        }
        /* .no-right{
            border-right: 1px solid #ffffff!important;
        }
        .no-left{
            border-left: 1px solid #ffffff !important;
        }
        .no-top{
            border-top: 1px solid #ffffff!important;
        }
        .no-bottom{
            border-bottom: 1px solid #ffffff!important;
        }
        .no-bottom td{
            border-bottom: 1px solid #ffffff!important;
        } */
        .relative{
            position: relative;
        }
        .absolute{
            position: absolute;
            top: 11.5in;
        }
        .word-space{
            word-spacing: 11px;
            letter-spacing: 1px;
        }
        .mr-0{
            margin-right: 0!important;
        }
        .ml-0{
            margin-left: 0!important;
        }
        .gray{
            background-color: rgb(169, 168, 168);
        }
        .blue{
            background-color: rgba(180, 198, 231);
        }
        .red{
            color: red;
        }
        .blue-color{
            color: rgb(0, 32, 96);
        }
        .uppercase{
            text-transform: capitalize'
        }
        .justify{
            text-align: justify!important;
        }
        .gigaspace{
            margin-top: 1.7in;
        }
        .paragraph{
            font-size: 8.5pt;
        }

        .italic{
            font-style: italic;
        }

        .watermark2 {
            position: fixed;
            text-align: center !important;
            opacity: .32;
            z-index:  -999;
            margin: -.2in;
            top: 1.9in;
            left: .5in
        }
        .watermark1 {
            position: fixed;
            text-align: center !important;
            opacity: .32;
            z-index:  -999;
            margin: -.2in;
            top: 1.9in;
            right: .5in
        }
        .uppercase{
            text-transform: uppercase;
        }
        @page { size: 4in 6in; margin: 0in 0in;}
    </style>
</head>

<body>
    <div class="text-center space blue-color">
        MATER DEI ACADEMY OF TAGAYTAY INCORPORATED
    </div>
    <div class="text-center blue-color">
        COPY OF GRADES
    </div>
    <div class="text-center blue-color">
        School Year {{$schoolyear->sydesc}}
    </div>
    
    <table width="100%" class="table table-bordered">
        {{-- <tr>
            <td width="15%" class="no-top no-left no-right"></td>
            <td width="29%" class="no-top no-left no-right"></td>
            <td width="29%" class="no-top no-left no-right"></td>
            <td width="13.5%" class="no-top no-left no-right"></td>
            <td width="13.5%" class="no-top no-left no-right"></td>
        </tr> --}}
        <tr class="bold small-font">
            <td colspan="2">Name:</td>
            <td colspan="3">{{$student->student}}</td>
        </tr>
        <tr class="bold small-font">
            <td colspan="2">LRN:</td>
            <td colspan="3">{{$student->lrn}}</td>
        </tr>
        <tr class="bold small-font">
            <td colspan="2">Grade Level:</td>
            <td colspan="3">{{str_replace('GRADE','',$student->levelname)}}-{{$student->sectionname}}</td>
        </tr>
        <tr class="bold small-font">
            <td colspan="2">Strand:</td>
            <td colspan="3">{{$strandInfo->trackname}}-{{$strandInfo->strandcode}}</td>
        </tr>
        <tr class="bold small-font">
            <td colspan="2">Semester:</td>
            @if($semid==1)
            @php
                $x=1
            @endphp
            <td width="40%">First Semester</td>
            @else
            @php
                $x=2
            @endphp
            <td width="40%">Second Semester</td>
            @endif
        </tr>
        <tr class="blue">
            <td rowspan="2" colspan="3" class="text-center bold small-font">LEARNING AREAS</td>
            <td colspan="2" class="text-center bold small-font">Term</td>
        </tr>
        <tr class="blue">
            <td class="text-center bold small-font">1</td>
            <td class="text-center bold small-font">2</td>
        </tr>
                @foreach (collect($studgrades)->where('type',1)->where('semid',$x) as $item)
                <tr class="smaller-font">
                    <td class="text-center small-font">Core</td>
                    @if($x==1)
                    <td colspan="2"  class="text-center small-font">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                    <td >{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                    <td >{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                    @endif
                    @if($x==2)
                    <td   colspan="2" class="text-center small-font">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                    <td >{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                    <td >{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                    @endif
                </tr>
                @endforeach
                @foreach (collect($studgrades)->where('type',3)->where('semid',$x) as $item)
                <tr class="smaller-font">
                    <td class="text-center small-font">Applied</td>
                    @if($x==1)
                    <td class="text-center small-font" colspan="2" >{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                    <td >{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                    <td >{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                    @endif
                    @if($x==2)
                    <td  class="text-center small-font" colspan="2">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                    <td >{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                    <td >{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                    @endif
                </tr>
                @endforeach
                @foreach (collect($studgrades)->where('type',2)->where('semid',$x) as $item)
                <tr class="smaller-font">
                    <td class="text-center small-font">Specialized</td>
                    
                    @if($x==1)
                    <td class="text-center small-font" colspan="2">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                    <td >{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                    <td >{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                    @endif
                    @if($x==2)
                    <td class="text-center small-font" colspan="2">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                    <td >{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                    <td >{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                    @endif
                </tr>
                @endforeach
                @foreach (collect($studgrades)->where('type','>',3)->where('semid',$x) as $item)
                <tr class="smaller-font">
                    <td class="text-center smallest-font" style="white-space: nowrap">Other Subjs</td>
                    @if($x==1)
                    <td class="text-center small-font" colspan="2">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                    <td >{{$item->quarter1 != null ? $item->quarter1:''}}</td>
                    <td >{{$item->quarter2 != null ? $item->quarter2:''}}</td>
                    @endif
                    @if($x==2)
                    <td class="text-center small-font" colspan="2">{{$item->subjdesc!=null ? $item->subjdesc : null}}</td>
                    <td >{{$item->quarter3 != null ? $item->quarter3:''}}</td>
                    <td >{{$item->quarter4 != null ? $item->quarter4:''}}</td>
                    @endif
                </tr>
                @endforeach
        
        <tr class="gray">
            <td colspan="3" class="small-font bold">GENERAL AVERAGE</td>
            @if($x == 1)
                <td>{{ isset(collect($finalgrade)->first()->quarter1) ? round(collect($finalgrade)->first()->q1comp) : '' }}</td>
                <td>{{ isset(collect($finalgrade)->first()->quarter2) ? round(collect($finalgrade)->first()->q2comp) : '' }}</td>
            @endif
            @if($x == 2)
                <td>{{ isset(collect($finalgrade)->last()->quarter3) ? round(collect($finalgrade)->last()->q3comp) : '' }}</td>
                <td>{{ isset(collect($finalgrade)->last()->quarter4) ? round(collect($finalgrade)->last()->q4comp) : '' }}</td>
            @endif
        </tr>
        <tr>
            <td colspan="5" class="p-2"></td>
        </tr>
        <tr class="smaller-font blue bold">
            <td colspan="3">DESCRIPTIVE GRADES</td>
            <td colspan="2">GRADE SCALE</td>
        </tr>
        <tr class="smaller-font">
            <td colspan="3">Outstanding/Exemplary</td>
            <td colspan="2">95-100 (A+) or E</td>
        </tr>
        <tr class="smaller-font">
            <td colspan="3">Highly Satisfactory/Very Good</td>
            <td colspan="2">90-94 (A) or VG</td>
        </tr>
        <tr class="smaller-font">
            <td colspan="3">Very Satisfactory/Good</td>
            <td colspan="2">85-89 (B+) or G</td>
        </tr>
        <tr class="smaller-font">
            <td colspan="3">Satisfactory/Sufficient</td>
            <td colspan="2">80-84 (B) or S</td>
        </tr>
        <tr class="smaller-font">
            <td colspan="3">Fairly Satisfactory/Insufficient</td>
            <td colspan="2">75-79 (C) or In</td>
        </tr>
        <tr class="smaller-font">
            <td colspan="3">Did not meet standards</td>
            <td colspan="2">74 & below or NI</td>
        </tr>
    </table>
    <table class="space">
        <tr>
            <td width="50%"><div class="smallestest-font text-center"><i>"This document is not for official use and serves solely to show the student's progress per term. The final and official report card shall be released by the end of each semester."</i></div></td>
            <td width="50%">
                <table width="100%" class="spacing">
                    <tr>
                        <td width="100%" class="underline"></td>
                    </tr>
                    <tr>
                        <td class="text-center">{{$adviser}}</td>
                    </tr>
                    <tr class="smallestest-font">
                        <td class="text-center"><i>Class Adviser</i></td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    
</body>
</html>