@php
    $sinfo = $schoolinfo;
    $schoolname = $sinfo->schoolname;
    $schooladdress = $sinfo->address;
    $picurl = explode('?', $sinfo->picurl);
    
    $oldclassid = DB::table('balforwardsetup')->first()->classid;
@endphp

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        .roundtd {
            border: solid #6CC57EFF;
            border-radius: 8px;
        }
        .student-break {
            page-break-after: always;
        }
        .student-last {
            page-break-after: auto;
        }
        body {
            font-family: Sans-serif;
            font-size: 10px;
        }
        table {
            width: 100%;
        }
        .header-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .ledger-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .ledger-table th, .ledger-table td {
            border: 1px solid #ddd;
            padding: 4px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }
        .section-header {
            background-color: #6CC57EFF;
            color: white;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            margin: 10px 0 5px 0;
        }
    </style>
</head>
<body>
    @foreach($statements as $index => $statement)
        @php
            $isLast = $index === count($statements) - 1;
            $cursy = '';
            
            if($statement['studinfo']->levelid < 17) {
                $cursy = $statement['selectedschoolyear'];
            } else {
                $cursy = $statement['selectedschoolyear'] . ' - ' . $statement['selectedsemester'];
            }
        @endphp
        
        <div class="{{ $isLast ? 'student-last' : 'student-break' }}">
            <table class="header-table">
                <tr>
                    <td rowspan="2" style="width: 12%; padding-left: 50px;">
                        <img src="{{ $picurl[0] }}" style="width: 80px;">
                    </td>
                    <td style="text-align: left; width: 100%; padding-top: 20px;">
                        {{ $schoolname }}
                    </td>
                </tr>
                <tr>
                    <td style="text-align: left; width: 100%; font-size: 8; font-weight: normal; padding-bottom: 30px;">
                        {{ $schooladdress }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;">
                        STATEMENT OF ACCOUNT <br>
                        <span style="font-size: 9; font-weight: normal;">
                            {{ $cursy }}
                        </span>
                    </td>
                </tr>
            </table>
            
            <table style="font-size: 11px; font-weight: bold; margin-bottom: 15px;">
                <tr>
                    <td>Student ID: {{ $statement['studinfo']->sid }}</td>
                    <td style="text-align: right;">
                        Date: {{ date('m/d/Y h:i A') }}
                    </td>
                </tr>
                <tr>
                    <td>Student Name: {{ $statement['studinfo']->lastname.', '.$statement['studinfo']->firstname.' '.$statement['studinfo']->middlename.' '.$statement['studinfo']->suffix }}</td>
                </tr>
                @if($statement['studinfo']->levelid < 17)
                    <td>Level|Section: {{ $statement['levelname'] }} - {{ $statement['sectionname'] }}</td>
                @else
                    <td>Level|Course: {{ $statement['levelname'] }} - {{ $statement['courseabrv'] }}</td>
                @endif
            </table>
            
            <table width="100%">
                <td width="50%">
                    <div class="section-header">TOTAL CHARGES</div>
                    <table class="ledger-table">
                        <thead>
                            <tr>
                                <th style="text-align: left; width: 20%;">DATE</th>
                                <th style="text-align: left; width: 60%;">DESCRIPTION</th>
                                <th style="text-align: right; width: 20%;">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statement['ledger'] as $l)
                                @if($l->classid != $oldclassid)
                                    <tr>
                                        <td>{{ date('m-d-Y', strtotime($l->createddatetime)) }}</td>
                                        <td>{{ strtoupper($l->particulars) }}</td>
                                        <td style="text-align: right;">{{ number_format($l->amount, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr class="total-row">
                                <td colspan="2" style="text-align: right;">TOTAL:</td>
                                <td style="text-align: right;">{{ number_format($statement['totalcharges'], 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td width="50%">
                    <div class="section-header">OLD ACCOUNTS</div>
                    <table class="ledger-table">
                        <thead>
                            <tr>
                                <th style="text-align: left; width: 20%;">DATE</th>
                                <th style="text-align: left; width: 60%;">DESCRIPTION</th>
                                <th style="text-align: right; width: 20%;">AMOUNT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($statement['ledger'] as $l)
                                @if($l->classid == $oldclassid)
                                    <tr>
                                        <td>{{ date('m-d-Y', strtotime($l->createddatetime)) }}</td>
                                        <td>{{ strtoupper($l->particulars) }}</td>
                                        <td style="text-align: right;">{{ number_format($l->amount, 2) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                            <tr class="total-row">
                                <td colspan="2" style="text-align: right;">TOTAL:</td>
                                <td style="text-align: right;">{{ number_format($statement['oldamount'], 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </table>
            
            
            <div style="text-align: right; margin: 10px 0;">
                <strong>TOTAL BALANCES: (Old Account + Total Charges) {{ number_format($statement['totalbalance'] + $statement['totalpayment'], 2) }}</strong>
            </div>
            
            <div class="section-header">PAYMENTS</div>
            <table class="ledger-table">
                <thead>
                    <tr>
                        <th style="text-align: left; width: 20%;">DATE</th>
                        <th style="text-align: left; width: 20%;">OR #</th>
                        <th style="text-align: left; width: 40%;">DESCRIPTION</th>
                        <th style="text-align: right; width: 20%;">PAYMENT</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statement['payments'] as $pay)
                        <tr>
                            <td>{{ date('m-d-Y', strtotime($pay->createddatetime)) }}</td>
                            <td>{{ strtoupper($pay->ornum) }}</td>
                            <td>{{ strtoupper($pay->particulars) }}</td>
                            <td style="text-align: right;">{{ number_format($pay->payment, 2) }}</td>
                        </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">TOTAL PAYMENT:</td>
                        <td style="text-align: right;">{{ number_format($statement['totalpayment'], 2) }}</td>
                    </tr>
                    <tr class="total-row">
                        <td colspan="3" style="text-align: right;">CURRENT BALANCE:</td>
                        <td style="text-align: right;">{{ number_format($statement['totalbalance'], 2) }}</td>
                    </tr>
                </tbody>
            </table>
            
            @if(!empty($statement['monthdue']))
                <div class="section-header">MONTHLY ASSESSMENT</div>
                <table class="ledger-table">
                    <thead>
                        <tr>
                            <th style="text-align: left; width: 60%;">PARTICULARS</th>
                            <th style="text-align: right; width: 20%;">AMOUNT</th>
                            <th style="text-align: right; width: 20%;">BALANCE</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $runningbalance = 0;
                            $studpayscheddetail = DB::table('studpayscheddetail')
                                ->where('studid', $statement['studinfo']->id)
                                ->where('deleted', 0)
                                ->where('syid', $syid)
                                ->where('semid', $semid)
                                ->where('duedate', null)
                                ->where('balance', '>', 0)
                                ->get();
                            $sumpaysched = $studpayscheddetail->sum('balance');
                        @endphp
                        
                        @foreach($statement['monthdue'] as $due)
                            @php
                                $runningbalance += $due->balance;
                                if($loop->last) {
                                    $runningbalance += $sumpaysched;
                                }
                            @endphp
                            <tr>
                                <td>{{ strtoupper($due->particulars) }}</td>
                                <td style="text-align: right;">{{ number_format($due->amount, 2) }}</td>
                                <td style="text-align: right;">{{ $loop->last ? number_format($runningbalance, 2) : '0.00' }}</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td style="text-align: right;">TOTAL DUE:</td>
                            <td></td>
                            <td style="text-align: right;">{{ number_format($runningbalance, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif
            
            <table style="width: 100%; margin-top: 50px;">
                <tr>
                    <td style="width: 50%;">
                        <table style="width: 80%;">
                            <tr>
                                <td style="border-bottom: 1px solid black; height: 25px;"></td>
                            </tr>
                            <tr>
                                <td style="text-align: center; font-weight: bold;">Prepared By:</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%;">
                        <table style="width: 80%;">
                            <tr>
                                <td style="border-bottom: 1px solid black; height: 25px;"></td>
                            </tr>
                            <tr>
                                <td style="font-weight: bold;">Date:</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
</body>