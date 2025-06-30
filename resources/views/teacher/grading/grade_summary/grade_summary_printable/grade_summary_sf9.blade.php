<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$section_detail->levelname. ' - ' . $section_detail->sectionname . ' '. $schoolyear_detail->sydesc.' QUARTER '.$quarter}}</title>
	
	@php
		$longest = 0;
		foreach($subjects as $item){
			if(strlen($item->subjdesc) > $longest){
				$longest = strlen($item->subjdesc);
			}
		}
		if($section_detail->levelid != 14 && $section_detail->levelid != 15){
			$head_width = ($longest) * 7.3;
		}else{
			$head_width = ($longest-20) * 7.3;
		}
	@endphp
	
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

        .p-1{
            padding: .10rem !important;
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
            font-size: 9px !important;
        }

        .studentinfo td{
            padding-top: .1rem;
            padding-bottom: .1rem;
          
        }

        @page {  
            margin:5;
            
        }
        body { 
            margin:5;
            
        }

        table
        {
            font-family:"Calibri, sans-serif";
            font-size: 11px;
            table-layout: fixed;
            width: 100%;  
        }
        th, td 
        {
            /* border: 1px solid black; */
            width: 23px;
        }

        /* .grades_display tr td:nth-child(3){
            width: 20%;
        } */

        .grades_display tr td:nth-child(2){
            width: 40%;
        }

        .grades_display tr td:nth-child(1){
            width: 4%;
        }

        .header tr td:nth-child(1){
            width: 30%;
        }
        .header tr td:nth-child(2){
            width: 70%;
        }


        .rotate_text
        {
            -moz-transform:rotate(-90deg); 
            -moz-transform-origin: top left;
            -webkit-transform: rotate(-90deg);
            -webkit-transform-origin: top left;
            -o-transform: rotate(-90deg);
            -o-transform-origin:  top left;
            position:relative;
            /* left: 9px; */
			width: {{$head_width}}px;
          
         
        }
        .rotated_cell
        {
           
            height: {{$head_width}}px;
            vertical-align:bottom
        }

        .bg-gray{
            background-color: gainsboro;
        }

        .text-red{
            border: solid 1px black !important;
            color: red;
        }
        
        .border-0{
            border:0!important;
        }
      
        
    </style>
    
  
    
</head>
<body>  

    <table class="table mb-0 table-sm header" style="font-size:13px;">
        <tr>
            <td width="20%" rowspan="2" class="text-right align-middle p-0">
                <img src="{{base_path()}}/public/{{$schoolinfo[0]->picurl}}" alt="school" width="80px">
            </td>
            <td width="80%" class="p-0" >
                <h3 class="mb-0" style="font-size:20px !important">{{$schoolinfo[0]->schoolname}}</h3>
            </td>
        </tr>
        <tr>
            <td class="p-0">
                {{$schoolinfo[0]->address}}
            </td>
        </tr>
    </table>
    <br>

    <table class="table mb-0 table-sm" style="font-size:13px;">
        <tr>
            <td width="100%" class="text-center p-1"><b>M A S T E R &nbsp; &nbsp;  S H E E T S</b></td>
        </tr>
        <tr>
            <td width="100%" class="text-center p-1"><b>{{$section_detail->levelname}} - {{$section_detail->sectionname}}</b></td>
        </tr>
        <tr>
            <td width="100%" class="text-center p-1">SCHOOL YEAR: {{$schoolyear_detail->sydesc}}</td>
        </tr>
    </table>
    <br>
	
    <table class="table mb-0 table-sm" >
            <tr>
                <td width="50%"><b>TEACHER: </b> {{$section_detail->lastname}},  {{$section_detail->firstname}}</td>
                <td width="50%" class="text-right"><b>GRADING PERIOD :</b>
                    @if($quarter == 1)
                        1ST QUARTER
                    @elseif($quarter == 2)
                        2ND QUARTER
                    @elseif($quarter == 3)
                        3RD QUARTER
                    @elseif($quarter == 4)
                        4TH QUARTER
                    @elseif($quarter == 5)
                        FINAL GRADE
                    @endif
                </td>
            </tr>
        
    </table>

    <table class="table-bordered grades_display">
        <thead>
            <tr >
                <td class="border-0"></td>
                <td class="rotated_cell" width="15%">
                    <div class="rotate_text"></div>
                </td>
                @php
                    $width = 80 / collect($subjects)->count();
                @endphp
                @foreach ($subjects as $subj_item)
                    @if($section_detail->levelid != 14 && $section_detail->levelid != 15)
                        @if($quarter != 5)
							@if(strlen($subj_item->subjdesc) > 20)
								<td class="rotated_cell" style="font-size: 6.5px !important;">
							@else
								<td class="rotated_cell" >
							@endif
                                <div class="rotate_text" >
                                    @if($subj_item->subjCom == null)
                                        <b>{{$subj_item->subjdesc}}</b>
                                    @else
                                        {{$subj_item->subjdesc}}
                                    @endif
                                </div>
                            </td>
                        @else
                            @if($subj_item->subjCom == null)
                                @if(strlen($subj_item->subjdesc) > 20)
									<td class="rotated_cell" style="font-size: 6.5px !important;">
								@else
									<td class="rotated_cell" >
								@endif
                                    <div class="rotate_text" >
                                        @if($subj_item->subjCom == null)
                                            <b>{{$subj_item->subjdesc}}</b>
                                        @else
                                            {{$subj_item->subjdesc}}
                                        @endif
                                    </div>
                                </td>
                            @endif
                        @endif
                    @else
                        @if(strlen($subj_item->subjdesc) > 20)
                            <td class="rotated_cell" style="font-size: 6.5px !important;">
                        @else
							<td class="rotated_cell" >
						@endif
                            <div class="rotate_text">
                                {{$subj_item->subjdesc}}
                            </div>
                        </div>
                    @endif
                @endforeach
                <td class="rotated_cell"  width="1%">
                    <div class="rotate_text"></div>
                 </td>
                 <td class="rotated_cell"  width="9%">
                    <div class="rotate_text"><b>Average</b></div>
                 </td>
                 <td class="rotated_cell"  width="9%">
                    <div class="rotate_text"><b>Composite</b></div>
                 </td>
           </thead> </tr>
        </thead>
        <tbody>
            @php
                $temp_quarter = $quarter;
                $quarter = 'quarter'.$temp_quarter;
                $qstatus = 'q'.$temp_quarter.'status';
            @endphp
            @php
                    $male = 0;
                    $female = 0;
                    $count = 1;
            @endphp
            @foreach ($students as $item)
            <col span="1" class="wide">
                @if($male == 0 && strtoupper($item->gender) == 'MALE')
                    <tr class="bg-gray">
                        <td class="text-center">#</td>
                        <td style="padding-left: 5px !important">MALE</td>
                         @foreach ($subjects as $subj_item)
                             @if($section_detail->levelid != 14 && $section_detail->levelid != 15)
                                @if($quarter != 'quarter5')
                                     <td class="text-center"></td>
                                @else
                                    @if($subj_item->subjCom == null)
                                         <td class="text-center"></td>
                                    @endif
                                @endif
                            @else
                                 <td class="text-center"></td>
                            @endif
                        @endforeach
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        @php
                            $male = 1;
                        @endphp
                    </tr>
                @elseif($female == 0  && strtoupper($item->gender) == 'FEMALE')
                    <tr>
                        <td></td>
                        <td>&nbsp;</td>
                        @foreach ($subjects as $subj_item)
                             @if($section_detail->levelid != 14 && $section_detail->levelid != 15)
                                @if($quarter != 'quarter5')
                                     <td class="text-center"></td>
                                @else
                                    @if($subj_item->subjCom == null)
                                         <td class="text-center"></td>
                                    @endif
                                @endif
                            @else
                                 <td class="text-center"></td>
                            @endif
                        @endforeach
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        @php
                            $female = 1;
                            $count = 1;
                        @endphp
                    </tr>
                    <tr class="bg-gray">
                        <td class="text-center">#</td>
                        <td style="padding-left: 5px !important">FEMALE</td>
                        @foreach ($subjects as $subj_item)
                            @if($section_detail->levelid != 14 && $section_detail->levelid != 15)
                                @if($quarter != 'quarter5')
                                     <td class="text-center"></td>
                                @else
                                    @if($subj_item->subjCom == null)
                                         <td class="text-center"></td>
                                    @endif
                                @endif
                            @else
                                 <td class="text-center"></td>
                            @endif
                        @endforeach
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        <td class="text-center"></td>
                        @php
                            $female = 1;
                            $count = 1;
                        @endphp
                    </tr>
                @endif
                <tr>
                    <td class="text-center">{{$count}}</td>
                    <td style="padding-left: 5px !important">{{$item->student}}</td>
                    @foreach ($subjects as $subj_item)
                        @php
                            if($quarter != 'quarter5'){
                                if(isset(collect($item->grades)->where('subjid',$subj_item->id)->first()->$qstatus)){
                                    $submitted = collect($item->grades)->where('subjid',$subj_item->id)->first()->$qstatus != 0 ? true : false;
                                }else{
                                    $submitted = false;
                                }
                                $grade = $submitted ? collect($item->grades)->where('subjid',$subj_item->id)->count() ? collect($item->grades)->where('subjid',$subj_item->id)->first()->qg : '' : '';
                            }else{
                                $grade = collect($item->grades)->where('subjid',$subj_item->id)->first()->qg;
                            }
                        @endphp
                        @if($section_detail->levelid != 14 && $section_detail->levelid != 15)
                            @if($quarter == 'quarter5')
                                @if($subj_item->subjCom == null)
                                    <td class="text-center {{$grade < 75 ? 'text-red':''}}">{{$grade}}</td>
                                @endif
                            @else
                                <td class="text-center {{$grade < 75 ? 'text-red':''}}">{{$grade}}</td>
                            @endif
                        @else
                           <td class="text-center {{$grade < 75 ? 'text-red':''}}">{{$grade}}</td>
                        @endif
                    @endforeach
                    <td class="text-center"></td>
                  
                    @if($section_detail->levelid == 14 || $section_detail->levelid == 15)
                           @php
                            $ave = '';


                            if($quarter == 'quarter1'){
                                $ave = collect($item->grades)->where('id','G1')->first()->q1comp;
                            }
                            elseif($quarter == 'quarter2'){
                                $ave = collect($item->grades)->where('id','G1')->first()->q2comp;
                            }
                            elseif($quarter  == 'quarter3'){
                                    $ave = collect($item->grades)->where('id','G1')->last()->q3comp;
                            }
                            elseif($quarter  == 'quarter4'){
                                $ave = collect($item->grades)->where('id','G1')->last()->q4comp;
                            }
                            elseif($quarter  == 'quarter5'){
                                $ave = collect($item->grades)->where('id','G1')->first()->fcomp;
                            }

                            
                        @endphp

                            @if($ave != 0)
                                <td class="text-center {{round($ave) < 75 ? 'text-red':''}}" style="font-size: 7px !important;">{{ round($ave) }}</td>
                            @else
                                <td class="text-center"></td>
                            @endif

                            <td class="text-center {{round($ave) < 75 ? 'text-red':''}}" style="font-size: 7px !important;">{{$ave }}</td>
                            
                    
                        
                    @else
                        @php
                            $ave = '';
                            // dd($item->grades);
                            if($quarter == 'quarter1'){
                                $ave = collect($item->grades)->where('id','G1')->first()->q1comp;
                            }
                            elseif($quarter == 'quarter2'){
                                $ave = collect($item->grades)->where('id','G1')->first()->q2comp;
                            }
                            elseif($quarter  == 'quarter3'){
                                    $ave = collect($item->grades)->where('id','G1')->first()->q3comp;
                            }
                            elseif($quarter  == 'quarter4'){
                                $ave = collect($item->grades)->where('id','G1')->first()->q4comp;
                            }
                            elseif($quarter  == 'quarter5'){
                                $ave = collect($item->grades)->where('id','G1')->first()->fcomp;
                            }
                        @endphp

                        <td class="text-center {{round($ave) < 75 ? 'text-red':''}}" style="font-size: 7px !important;">{{ round($ave) }}</td>
                        <td class="text-center {{round($ave) < 75 ? 'text-red':''}}" style="font-size: 7px !important;">{{$ave}}</td>
                    @endif
                    
                </tr>
                @php
                    $count += 1;
                @endphp
            @endforeach
            
        </tbody>
    </table>
    

</body>
</html>