<html>

<head>
    <meta http-equiv="Content-Type" content="charset=utf-8" />
    <title>Certificate of Registration</title>
    <style type="text/css">
        @page {
            margin: 0;
        }

        * {
            padding: 0;
            margin: 0;
        }

        @font-face {
            font-family: "source_sans_proregular";
            src: local("Source Sans Pro"), url("fonts/sourcesans/sourcesanspro-regular-webfont.ttf") format("truetype");
            font-weight: normal;
            font-style: normal;
        }

        body {
            font-family: "source_sans_proregular", Calibri, Candara, Segoe, Segoe UI, Optima, Arial, sans-serif;
        }

        .text-right {
            text-align: right !important;
        }

        .text-left {
            text-align: left !important;
        }

        .page_break {
            page-break-before: always;
        }

        .table-bordered {
            border: 1px solid #00000;
        }

        .table-bordered td,
        .table-bordered th {
            border: 1px solid #00000;
        }

        .table {
            width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
            font-size: 11px;
        }


        .p-1 {
            padding: .25rem !important;
        }

        table {
            border-collapse: collapse;
        }

        .table thead th {
            vertical-align: bottom;
        }

        .p-0 {
            padding: 0 !important;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
        }

        .table td,
        .table th {
            padding: .75rem;
            vertical-align: top;
        }

        .text-center {
            text-align: center !important;
        }

        .mb-0 {
            margin-bottom: 0;
        }

        .text-right {
            text-align: right !important;
        }

        .mb-1,
        .my-1 {
            margin-bottom: .25rem !important;
        }

        /* .copy {
            height: 6in;
        } */

        .bold {
            font-weight: bold !important;
        }

        .font-weight-bold {
            font-weight: bold !important;
        }

        hr {
            border: none;
            border-top: 10px dotted rgb(0, 0, 0);
            color: #fff;
            background-color: #fff;
            height: 3px;
            width: 100%;
            font-weight: bold;
        }

        .table-smaller td,
        .table-smaller th {
            padding: 2px !important;
            font-size: 10px !important;
        }

        .table-smaller p {
            margin: 0 !important;
        }
    </style>


</head>

<body style="margin: 0.25in 0.25in 0.25in 0.25in;">
    <div class="">
        {{-- <table width="100%"> 
                        <tr style="">
                        <td width="50%" style="text-align: center; font-size:10px;"><img src="{{base_path()}}/public/{{$schoolInfo->picurl}}" alt="school" width="120px">{{$schoolInfo->schoolname}}</td>
                        <td width="50%" style="text-align: right; font-size:20px;" valign="top"><b>CERTIFICATE OF REGISTRATION</b></td>
                        </tr>
                        <tr >
                              <td style="text-align: center; font-size:9px">{{$schoolInfo->address}}</td>
                              <td style="text-align: center; font-size:11px"></td>
                        </tr>
				</table> --}}
        <table width="100%">
            <tr>
                <td style="text-align: right !important; vertical-align: top;" width="25%">
                    <img src="{{ base_path() }}/public/{{ $schoolInfo->picurl }}" alt="school" width="60px">
                </td>
                <td style="width: 50%; text-align: center;">
                    <div style="width: 100%; font-weight: bold; font-size: 15px;">{{ $schoolInfo->schoolname }}</div>
                    <div style="width: 100%; font-size: 12px;">{{ $schoolInfo->address }}</div>
                </td>
                <td width="25%"></td>
            </tr>
        </table>
        <table width="100%">
            <tr>
                <td style="text-align: right !important; vertical-align: top;" width="25%"></td>
                <td style="width: 50%; text-align: center; font-size: 12px;">
                    <b>CERTIFICATE OF REGISTRATION</b>
                </td>
                <td width="25%"></td>
            </tr>
        </table>

        <table width="100%" style="font-size:10px" class="mb-1">
            <tbody>
                <tr>
                    <td width="75%">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td>&nbsp;</td>
                                </tr>
                                @php
                                    $middle = '';
                                    if (isset($studentInfo->middlename)) {
                                        if ($studentInfo->middlename != '' && $studentInfo->middlename != null) {
                                            $middle = isset($studentInfo->middlename)
                                                ? ' ' . $studentInfo->middlename[0] . '.'
                                                : '';
                                        }
                                    }
                                @endphp
                                <tr style="vertical-align: top;">
                                    <td width="25%">ID no. {{ $studentInfo->sid }}</td>
                                    <td width="35%">NAME:
                                        {{ $studentInfo->lastname . ', ' . $studentInfo->firstname . $middle }}</td>
                                    <td width="30%">CONTACT NO. {{ $studentInfo->contactno }}</td>
                                </tr>
                                <tr>
                                    <td colspan="3">Year Level: {{ $studentInfo->levelname }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1">Date of Birth:
                                        {{ \Carbon\Carbon::create($studentInfo->dob)->isoFormat('MM/DD/YYYY') }}</td>
                                    <td colspan="2">Address:
                                        @if ($studentInfo->street != '')
                                            {{ $studentInfo->street . ', ' }}
                                        @endif
                                        @if ($studentInfo->barangay != '')
                                            {{ $studentInfo->barangay . ', ' }}
                                        @endif
                                        @if ($studentInfo->city != '')
                                            {{ $studentInfo->city . ', ' }}
                                        @endif
                                        @if ($studentInfo->province != '')
                                            {{ $studentInfo->province . ', ' }}
                                        @endif

                                </tr>
                            </tbody>
                        </table>

                    </td>
                    <td width="25%">
                        <table width="100%">
                            <tbody>
                                <tr>
                                    <td with="50%" style="text-align: right; font-size:11px">
                                        {{ \Carbon\Carbon::create($studentInfo->dateenrolled)->isoFormat('DD/MM/YYYY') }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; font-size:11px">{{ $activeSem->semester }} S.Y.:
                                        {{ $activeSy->sydesc }}</td>
                                </tr>
                                <tr>
                                    <td style="text-align: right; font-size:11px">
                                        Section: {{ $studentInfo->sectionDesc }}
                                        {{-- Block Code: 
                                                                  <u><b><span style="font-size:13px">
                                                                              {{$studentInfo->courseabrv}}
                                                                                    @if ($studentInfo->levelid == 17)
                                                                                          1
                                                                                    @elseif( $studentInfo->levelid == 18)
                                                                                          2
                                                                                    @elseif( $studentInfo->levelid == 19)
                                                                                          3
                                                                                    @elseif( $studentInfo->levelid == 20)
                                                                                          4
                                                                                    @endif
                                                                              {{$studentInfo->sectionDesc}}
                                                                        </span>
                                                                  </b></u> --}}
                                    </td>
                                </tr>
                                <tr>
                                    <th style="text-align:right; font-size:15px">
                                        @if ($studentInfo->studtype == null || $studentInfo->studtype == 'old')
                                            OLD STUDENT
                                        @else
                                            NEW STUDENT
                                        @endif
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%" class="table table-sm table-bordered table-smaller mb-0">
            @php
                $totalUnits = 0.0;
            @endphp
            <tbody>
                <tr>
                    <th class="text-left">Subject Code</th>
                    <th class="text-left">Subject Description</th>
                    <th class="text-center">Time</th>
                    <th class="text-center">Day</th>
                    <th class="text-center">Room</th>
                    <th class="text-center">Teacher</th>
                </tr>

                @foreach ($schedules as $item)
                    @php $count = 0; @endphp
                    <tr>
                        <td width="8%">{{ $item->subjCode }}</td>
                        <td width="30%">{{ $item->subjtitle }}</td>
                        <td class="text-center" width="15%">{{ $item->time }}</td>
                        <td class="text-center" width="17%">{{ $item->day }}</td>
                        <td class="text-center" width="10%">
                            {{ $item->room }}
                        </td>
                        <td class="text-center" width="15%">
                            {{ $item->teachername }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>


        <table class="table mb-0" width="100%" style="font-size:.5625rem !important;">
            <tr>
                @if (strtolower($schoolInfo->abbreviation) == 'stii')
                    <td width="38%" class="p-0 align-top">
                        <table class="table " width="100%" style="font-size:.7625rem !important">
                            <tbody>
                                <tr>
                                    <td colspan="2" class="p-1 text-center bold"
                                        style="font-size:.8625rem !important">ASSESSMENT DETAILS</td>
                                </tr>
                                <tr>
                                    <td class="p-1 font-weight-bold"
                                        style="border-bottom: 1px solid black; font-size:.8625rem !important">PARTICULAR
                                    </td>
                                    <td class="p-1 font-weight-bold text-right"
                                        style="border-bottom: 1px solid black; font-size:.8625rem !important">AMOUNT
                                    </td>
                                </tr>
                                @foreach ($ledger as $item)
                                    <tr>
                                        <td width="70%" class="p-1">
                                            {{ $item->particulars }}
                                        </td>
                                        <td width="30%" class="text-right p-1"
                                            style="border-bottom: {{ $loop->last ? '1px solid black' : '' }}">
                                            {{ number_format($item->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="p-1 bold">
                                        Sub-total:
                                    </td>
                                    <td class="text-right p-1" style="font-size:.7525rem !important">
                                        <b>{{ number_format(collect($ledger)->sum('amount'), 2) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-1 bold" style=" font-size:.8625rem !important">Less Payments</td>
                                    <td class="p-1"></td>
                                </tr>
                                <tr>
                                    <td class="p-1">{{ $first_payment ? $first_payment->items : 'N/A' }} -
                                        {{ $first_payment ? $first_payment->transdate : 'N/A' }}
                                        ORNO. {{ $first_payment ? $first_payment->ornum : 'N/A' }}</td>
                                    <td class="p-1 text-right" style="border-bottom: 1px solid black">
                                        {{ $first_payment ? $first_payment->amountpaid : 'Allow No DP' }}</td>
                                </tr>
                                <tr>
                                    <td class="p-1 bold"
                                        style="border-bottom: 1px solid black; font-size:.7625rem !important">Sub-total
                                    </td>
                                    <td class="p-1 bold text-right"
                                        style="border-bottom: 1px solid black; font-size:.6625rem !important"></td>
                                </tr>
                                <tr>
                                    <td class="p-1 bold"
                                        style="border-bottom: 1px solid black; font-size:.9625rem !important">Actual
                                        Assessment:</td>
                                    <td class="p-1 bold text-right"
                                        style="border-bottom: 1px solid black; font-size:.9625rem !important">
                                        {{ number_format(collect($ledger)->sum('amount'), 2) }}</td>
                                </tr>
                                <tr>
                                    @php
                                        $balance_raw =
                                            collect($ledger)->sum('amount') -
                                            (isset($first_payment)
                                                ? (is_numeric($first_payment->amountpaid)
                                                    ? $first_payment->amountpaid
                                                    : 0)
                                                : 0);
                                        $balance = number_format($balance_raw, 2);
                                    @endphp
                                    <td class="p-1 bold"
                                        style="border-bottom: 1px solid black; font-size:.9625rem !important">Balance:
                                    </td>
                                    <td class="p-1 bold text-right"
                                        style="border-bottom: 1px solid black; font-size:.9625rem !important">
                                        {{ $balance }}</td>
                                </tr>
                                <tr>
                                    <td class="p-1 bold"
                                        style="font-size:.9525rem !important; border-top: 1px solid black">
                                        Due Per Exam:
                                    </td>
                                    <td class="text-right p-1"
                                        style="font-size:.9525rem !important; border-top: 1px solid black">
                                        <b>{{ number_format($balance_raw / 5, 2) }}</b>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="p-1" colspan="2"><b>Remarks:</b> For further inquiries and
                                        clarifications of your total assessments, refer to the accounting department.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="2%" class="p-0">

                    </td>
                @else
                    <td width="30%" class="p-0 align-top">
                        <table class="table table-bordered" width="100%" style="font-size:.5625rem !important">
                            <tbody>
                                <tr>
                                    <td colspan="2" class="p-1 text-center">BILLING ASSESSMENT</td>
                                </tr>
                                @foreach ($ledger as $item)
                                    <tr>
                                        <td width="70%" class="p-1">
                                            {{ $item->particulars }}
                                        </td>
                                        <td width="30%" class="text-right p-1">
                                            {{ number_format($item->amount, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td class="p-1">
                                        TOTAL AMOUNT:
                                    </td>
                                    <td class="text-right p-1">
                                        <b>{{ number_format(collect($ledger)->sum('amount'), 2) }}</b>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="10%" class="p-0">

                    </td>
                @endif

                <td width="60%" class="p-0 align-top">
                    <table class="table mb-1" width="100%" style="font-size:10px !important">
                        <tr>
                            <td width="100%" class="p-1">
                                <span>This is your official Certificate of registration. Please check and verify
                                    thoroughly the correctness of these data. If you have question or verification on
                                    the data found in this report, you may visit the RECORDS AND ADMISSION OFFICE
                                    @if ($schoolInfo->abbreviation == 'HCCSI')
                                    or you may call us at +63 82 2330013 @else.
                                    @endif
                                </span>
                            </td>
                        </tr>
                    </table>
                    <table class="table" width="100%" style="font-size:10px !important">
                        {{-- <tr>
                            @if (strtolower($schoolInfo->abbreviation) == 'stii')
                            <td width="30%" class="p-0 text-center"></td>
                            <td width="5%" class="p-0 text-center"></td>
                            <td width="30%" class="p-0 text-center">Approved By:</td>
                            <td width="5%" class="p-0 text-center"></td>
                            <td width="30%" class="p-0 text-center"></td>
                            @else
                            <td width="30%" class="p-0 text-center">Generated By:</td>
                            <td width="5%" class="p-0 text-center"></td>
                            <td width="30%" class="p-0 text-center">Approved By:</td>
                            <td width="5%" class="p-0 text-center"></td>
                            <td width="30%" class="p-0 text-center">Approved By:</td>
                            @endif
                        </tr>
                        <tr>
                            @if (strtolower($schoolInfo->abbreviation) == 'stii')
                                <td class="p-0 text-center">
                                </td>
                                <td class="p-0 text-center"></td>
                                <td class="p-0 text-center" style="border-bottom:solid 1px black">Erwin B. Cenas</td>
                                <td class="p-0 text-center"></td>
                                <td class="p-0 text-center" style=""></td>
                            @else
                                <td class="p-0 text-center" style="border-bottom:solid 1px black">
                                    {{ $registrar_sig != null ? $registrar_sig : '' }}
                                </td>
                                <td class="p-0 text-center"></td>
                                <td class="p-0 text-center" style="border-bottom:solid 1px black">
                                    {{ $regname != null ? $regname : '' }}
                                </td>
                                <td class="p-0 text-center"></td>
                                <td class="p-0 text-center" style="border-bottom:solid 1px black">
                                    {{ $proghead != null ? $proghead : '' }}
                                </td>
                            @endif
                        </tr>
                        <tr>
                            @if (strtolower($schoolInfo->abbreviation) == 'stii')
                            <td class="p-0 text-center"></td>
                            <td class="p-0 text-center"></td>
                            <td class="p-0 text-center">REGISTRAR</td>
                            <td class="p-0 text-center"></td>
                            <td class="p-0 text-center"></td>
                            @else
                            <td class="p-0 text-center">REGISTRAR STAFF</td>
                            <td class="p-0 text-center"></td>
                            <td class="p-0 text-center">REGISTRAR</td>
                            <td class="p-0 text-center"></td>
                            <td class="p-0 text-center">PROGRAM HEAD</td>
                            @endif
                        </tr> --}}

                        {{-- <tr>
                            <td style="padding-top: 20px; text-align: center; ">
                                <br>
                                {{ $studentInfo->firstname }} {{ $studentInfo->middlename ?? '' }}.
                                {{ $studentInfo->lastname }},
                                <br>
                                ________________________________<br>
                                <span>Student Name & Signature</span>
                            </td>
                            <td style="padding-top: 20px; text-align: center;">
                
                            </td>
                
                        </tr>
                        <tr>
                            <td style="padding-top: 20px; text-align: center;">
                                <strong>Generated By:</strong><br>
                                {{ auth()->user()->name }}<br>
                                ________________________________<br>
                                <span>{{ $utype }} STAFF</span>
                            </td>
                
                            <td style="padding-top: 20px; text-align: center;">
                                <strong>Approved By:</strong><br>
                                {{ $accounting->name }}<br>
                                ________________________________<br>
                                <span>Accounting Staff</span>
                            </td>
                
                        </tr> --}}
                


                    </table>


                </td>
            </tr>
        </table>

    </div>
    <table class="table mb-1" width="100%" style="font-size:11px !important">
        <tr>
            <td width="100%" class="p-0 text-center bold">
                <span>AGREEMENT AND PLEDGE
                </span>
            </td>
        </tr>
        <tr>
            <td width="100%" class="p-1">
                <span>&nbsp;&nbsp;As an application for admission to <span class="bold">SIBUGAY TECHNICAL INSTITUTE INC.</span>, I hereby agree and
                    undertake to abide by the rules and regulations of the school now in force as those
                    that may be promulgated by the Administration from time to time.
                    In the event of the approval of my application. 
                </span>
            </td>
        </tr>
        <tr>
            <td width="100%" class="p-1">
                <span>&nbsp;&nbsp;I hereby pledge my unconditional
                    loyalty to this college - to love, cherish and honor my ALMA MATER and to protect and
                    defend her at all times.
                </span>
            </td>
        </tr>
    </table>
    <table class="table" width="100%" style="font-size:10px !important">

            <tr>
                <td style="padding-top: 20px; text-align: center;">
                    <br>
                    ERWIN B. CENAS<br>
                    ________________________________<br>
                    <span>School Registrar</span>
                </td>
                
                <td style="padding-top: 20px; text-align: center;">
                    <br>
                    {{ $accounting->name }}<br>
                    ________________________________<br>
                    <span>Accounting Clerk</span>
                </td>
                <td style="padding-top: 20px; text-align: center; ">
                    <br>
                    {{ $studentInfo->firstname }} {{ $studentInfo->middlename ?? '' }}.
                    {{ $studentInfo->lastname }},
                    <br>
                    ________________________________<br>
                    <span>Student Name & Signature</span>
                </td>
            </tr>

    </table>
    <table class="table" width="100%" style="font-size:10px !important">
        <tr>
            <td width="100%" class="p-1">
                <p style="font-size:10px"> * Note: Show this form in case of irregularities. <br>DO NOT
                    LOSE.</p>
            </td>
        </tr>
    </table>
</body>

</html>
