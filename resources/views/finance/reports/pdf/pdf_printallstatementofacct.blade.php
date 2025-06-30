@php
    // Get school info

    // Get the current school year and semester details
    // $sy = DB::table('sy')->where('id', $selectedschoolyear ?? '')->first();
    // // $sem = DB::table('semester')->where('id', $selectedsemester)->first();
    // $sydesc = $sy ? $sy->sydesc : 'Unknown School Year';
    // // $semdesc = $sem ? $sem->semester : 'Unknown Semester';
    // $cursy = $sydesc . ' - ' . $semdesc;
    // dd($students);
@endphp

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        /* .roundtd {
            border: solid #6CC57EFF;
            border-radius: 8px;
        } */
    </style>
</head>

<body>
    @foreach ($studinfo as $stud)
    {{-- @php
    dd($studinfo)
    @endphp --}}
        <table style="font-size: 14; font-weight: bold; font-family: Sans-serif; padding-top: 5px; width: 100%;">
            <tr>
                <td rowspan="2" style="width: 12%; padding-left: 50px;">
                    <img src="{{ $picurl }}" style="width: 100%;">
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

        <table style="font-family: Sans-serif; font-size: 11px; font-weight: bold; padding-top: 5px; width: 100%;">
            <tr>
                <td>Student ID: {{ $stud->sid }}</td>
                <td style="text-align: right;">
                    Date: {{ \Carbon\Carbon::now()->timezone('Asia/Manila')->format('m/d/Y h:i A') }}
                </td>
            </tr>
            <tr>
                <td>Student Name: {{ $stud->lastname }}, {{ $stud->firstname }} {{ $stud->middlename }}
                    {{ $stud->suffix }}</td>
            </tr>
            @if ($stud->levelid >= 17 && $stud->levelid <= 21)
                <td>Level|Course: {{ $stud->level }}</td>
            @else
                <td>Level|Section: {{ $stud->level }}</td>
            @endif
        </table>
        <br />


        <table cellspacing="0" cellpadding="1"
            style="font-size: 12px; width: 100%; font-family: Sans-serif; border-top: solid; border-bottom: solid;">
            <tr>
                <td style="width: 50%; text-align: center; border-right: solid; vertical-align: top;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <td colspan="3" class="roundtd"
                                    style="width: 100%; text-align: center; font-weight: bold; padding: 5px;">
                                    OLD ACCOUNTS
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left; width: 100%; font-weight: bold;">
                                    DATE
                                </td>
                                <td style="text-align: center; width: 100%; font-weight: bold;">
                                    DESCRIPTION
                                </td>
                                <td style="text-align: center; width: 100%; font-weight: bold;">
                                    AMOUNT
                                </td>
                            </tr>
                        </thead>
                        <tbody style="font-size: 9px;">
                            @foreach ($stud->ledgerold as $l)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($l->createddatetime)->format('m-d-Y') }}</td>
                                    <td>{{ strtoupper($l->particulars) }}</td>
                                    <td style="text-align: right;">{{ $l->amount }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" style="text-align: right; font-weight: bold; padding-top: 10px;">
                                    TOTAL: </td>
                                <td style="text-align: right; font-weight: bold; border-top: solid; padding-top: 10px;">
                                    {{ collect($stud->ledgerold)->sum('amount') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
                <td style="width: 50%; text-align: center;">
                    <table style="width: 100%;">
                        <thead>
                            <tr>
                                <td colspan="3" class="roundtd"
                                    style="width: 100%; text-align: center; font-weight: bold; padding: 5px;">
                                    TOTAL CHARGES
                                </td>
                            </tr>
                            <tr>
                                <td style="text-align: left; width: 3em; font-weight: bold;">
                                    DATE
                                </td>
                                <td style="text-align: center; width: 12em; font-weight: bold;">
                                    DESCRIPTION
                                </td>
                                <td style="text-align: center; width: 3em; font-weight: bold;">
                                    AMOUNT
                                </td>
                            </tr>
                        </thead>
                        <tbody style="font-size: 9px;">
                            @php
                                $totalchr = collect($stud->ledger)->sum('amount');
                            @endphp
                            @foreach ($stud->ledger as $l)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($l->createddatetime)->format('m-d-Y') }}</td>
                                    <td>{{ strtoupper($l->particulars) }}</td>
                                    <td style="text-align: right;">{{ $l->amount }}</td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="2" style="text-align: right; font-weight: bold;padding-top: 10px;">
                                    TOTAL: </td>
                                <td style="text-align: right; font-weight: bold; border-top: solid; padding-top: 10px;">
                                    {{ number_format($totalchr ?? 0, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br>

        <table cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
            <tr>
                <td style="width: 50%;"> </td>
                <td style="width: 50%;">
                    <table style="width: 100%;">
                        <tbody style="font-size: 13;">
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    TOTAL CHARGES: <br> <span style="font-size: 9px;">(Old Account + Total
                                        Charges)</span>
                                </td>
                                <td style="text-align: right; font-weight: bold; vertical-align: top;">
                                    {{ number_format(collect($stud->ledgerold)->sum('amount') + $totalchr, 2, '.', ',') }}
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <br />

        <table cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
            <tr>
                <td colspan="5" class="roundtd"
                    style="width: 100%; text-align: center; font-weight: bold; padding: 5px;">
                    PAYMENT
                </td>
            </tr>
        </table>
        <br>
        <table cellspacing="0" cellpadding="1"
            style="font-size: 12px; width: 100%; font-family: Sans-serif; border-top: solid; border-bottom: solid;">
            <thead>
                <tr>
                    <td style="text-align: left; width: 10%; font-weight: bold;">
                        DATE
                    </td>
                    <td style="text-align: left; width: 10%; font-weight: bold;">
                        OR #
                    </td>
                    <td style="text-align: center; width: 50%; font-weight: bold;">
                        DESCRIPTION
                    </td>
                    <td style="text-align: center; width: 10%; font-weight: bold;">
                        PAYMENT
                    </td>
                </tr>
            </thead>
            <tbody style="font-size: 9px;">
                @forelse ($stud->payments as $pay)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($pay->createddatetime)->format('m-d-Y') }}</td>
                        <td>{{ strtoupper(e($pay->ornum ?? 'N/A')) }}</td>
                        <td>{{ strtoupper(e($pay->particulars ?? 'N/A')) }}</td>
                        <td style="text-align: right;">{{ number_format($pay->payment, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="text-align: center;">No payment records found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <br>
        {{-- <table cellspacing="0" cellpadding="1" style="font-size: 12px; width: 100%; font-family: Sans-serif;">
            <tr>
                <td colspan="2" style="text-align: center; font-weight: bold;"> <br>
                    @php
                        dd($stud);
                    @endphp
                    AMOUNT DUE: {{ $stud->amountdue }}</td>
            </tr>
        </table>
        <br> --}}


        @if (!$loop->last)
            <div style="page-break-after: always;"></div>
        @endif
    @endforeach

</body>
