@php
    $totalamountpaid = 0;
@endphp
<style>
	.watermark {
		opacity: .1;
		position: absolute;
		left: 30%;
		bottom: 68.2%;
	}
	.watermark2 {
		opacity: .1;
		position: absolute;
		left: 30%;
		top: 62%;
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
   .p-1 {
        padding-top: 3px!important;
        padding-bottom: 3px!important;
   }
   .p-2 {
        padding-top: 8px!important;
        padding-bottom: 8px!important;
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

    .bg-red{
        color: red;
        border: solid 1px black !important;
    }

    td{
        padding-left: 5px;
        padding-right: 5px;
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
        color: #000; font-size
    }
    .trhead td {
        border: 3px solid #000;
    }

    body { 
        margin: 0px 0px;
        padding: 0px 0px;
    }
    
     .check_mark {
           font-family: ZapfDingbats, sans-serif;
        }
    @page{ size: 8.5in 13in; margin: 0px 20px;}
    
</style>
<div>
    <div class="row watermark">
        <div class="col-md-12">
            <img src="{{base_path()}}/public/{{DB::table('schoolinfo')->first()->picurl}}" alt="school" width="300px">
        </div>
    </div>
</div>
<table width="100%" class="table table-sm mb-0" style="padding-top: 20px;">
    <tr>
        <td rowspan="2" class="p-0" style="vertical-align: top;">
            <table width="100%" class="table table-sm p-0 text-center">
                <tr>
                    <td class="p-0" style="font-size: 20px; letter-spacing: 4px;"><b>SUBJECT PLOT</b></td>
                </tr>
                <tr>
                    <td class="p-0" style="font-size: 20px; letter-spacing: 4px;">
                        {{$schoolinfo[0]->schoolname}}
                    </td>
                </tr>
                <tr>
                    <td class="p-0" style="font-size: 15px; letter-spacing: 1px;">
                        {{$strandinfo}}
                    </td>
                </tr>
                <tr>
                    <td class="p-0" style="font-size: 15px; letter-spacing: 1px;">
                        SY. {{$syinfo}} | {{$levelinfo}}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

@php
    // Group data by levelid and semid
    $groupedData = [];
    foreach ($data as $item) {
        $groupedData[$item->levelid][$item->semid][] = $item;
    }
    
    // Define level names
    $levelNames = [
        14 => 'Grade 11',
        15 => 'Grade 12'
    ];
    
    // Define semester names
    $semesterNames = [
        1 => '1st Semester',
        2 => '2nd Semester'
    ];
    
    // Define type abbreviations
    $typeAbbreviations = [
        1 => 'CORE',
        2 => 'SPECIALIZED',
        3 => 'APPLIED',
        4 => 'ACADEMIC',
        5 => 'INSTITUTIONAL'
    ];
@endphp

@foreach([14, 15] as $levelid)
    @php
        $semesters = $groupedData[$levelid] ?? [];
    @endphp
    
    @foreach([1, 2] as $semid)
        <h3 style="margin-top: 20px;">{{ $levelNames[$levelid] }} - {{ $semesterNames[$semid] }}</h3>
        
        <table class="table table-bordered" width="100%" style="border-collapse: collapse; margin-bottom: 30px;">
            <thead>
                <tr style="border: 1px solid #000;">
                    <th width="15%" style="border: 1px solid #000; padding: 5px; text-align: left;">Type</th>
                    <th width="15%" style="border: 1px solid #000; padding: 5px; text-align: left;">Code</th>
                    <th width="50%" style="border: 1px solid #000; padding: 5px; text-align: left;">Subject Description</th>
                    <th width="20%" style="border: 1px solid #000; padding: 5px; text-align: left;">Pre-requisite</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($semesters[$semid]) && count($semesters[$semid]) > 0)
                    @foreach($semesters[$semid] as $subject)
                        <tr style="border: 1px solid #000;">
                            <td style="border: 1px solid #000; padding: 5px;">
                                <strong>{{ $typeAbbreviations[$subject->type] ?? '' }}</strong>
                            </td>
                            <td style="border: 1px solid #000; padding: 5px;">{{ $subject->subjcode }}</td>
                            <td style="border: 1px solid #000; padding: 5px;">{{ $subject->subjdesc }}</td>
                            <td style="border: 1px solid #000; padding: 5px;">{{ $subject->prereq ?? '' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr style="border: 1px solid #000;">
                        <td colspan="4" style="border: 1px solid #000; padding: 5px; text-align: center;">No Subject Added</td>
                    </tr>
                @endif
            </tbody>
        </table>
    @endforeach
@endforeach

@if(count($groupedData) == 0)
    <p>No subject data available</p>
@endif

