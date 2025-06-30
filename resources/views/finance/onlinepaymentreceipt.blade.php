<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Receipt</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 5mm;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #000;
            padding-bottom: 10px;
        }
        .school-name {
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .address {
            font-size: 12px;
            margin-bottom: 15px;
        }
        .receipt-title {
            font-size: 13px;
            font-weight: bold;
            text-align: center;
            margin: 15px 0;
            text-decoration: underline;
        }
        .student-name {
            margin: 20px 0;
            font-weight: bold;
        }
        .amount-in-words {
            margin: 10px 0;
            font-style: italic;
        }
        .purpose {
            margin: 10px 0;
        }
        .signature-section {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            border-top: 1px solid #000;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }
    </style>
</head>

@php
    // dd( asset('schoollogo/schoollogo.png'));
@endphp
<body>
    <table style="width: 100%">
        <tr>
            <td width="50%" style="padding: 10px">
                <table width="100%">
                    <tr>
                        <td width="10%" align="center">
                            <img src="{{ public_path($schoolInfo->picurl) }}" alt="school logo" width="50px" style="margin-right: 10px;">
                        </td>
                        <td width="90%" style="text-align:center">
                            <div class="school-name">{{ $schoolInfo->schoolname }}</div>
                            <div class="address">{{ $schoolInfo->address }}</div>
                        </td>
                    </tr>
                </table>

                
                <div class="receipt-title">ACKNOWLEDGEMENT RECEIPT</div>
                <div class="date" style="float: right">DATE: {{ \Carbon\Carbon::now()->format('F j, Y') }}</div>
                
                <div class="student-name">NAME: {{ $student->lastname }}, {{ $student->firstname }} {{ $student->middlename }}</div>
                <div class="student-name">LEVEL: {{ $student->levelname }}</div>
                @php
                    $total = collect($paymentLogs)->sum('totalamount');
                @endphp
                
                <div class="amount-in-words">THE SUM OF PHP <span style="text-decoration: underline;">{{ number_format($total, 2) }}</span> ({{ strtoupper(\Terbilang::make($total)) }})</div>
                
                <table width="100%">
                    @foreach ($paymentLogs as $item)
                        <tr>
                            <td>{{ $item->itemDescription }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ $item->totalamount }}</td>
                        </tr>
                    @endforeach
                </table>
                
                
                <table width="100%" style="margin-top: 50px">
                    <tr>
                        <td width="50%"></td>
                        <td width="50%" style="border-bottom: 1px solid #000; text-align: center; padding-top: 5px;font-weight: bold">{{ $cashierDetails->name }}</td>
                    </tr>
                    <tr>
                        <td width="50%"></td>
                        <td width="50%" style="text-align: center; padding-top: 5px;">Cashier</td>
                    </tr>
                </table>

            </td>
            <td width="50%" style="padding: 10px">
                <table width="100%">
                    <tr>
                        <td width="10%" align="center">
                            <img src="{{ public_path($schoolInfo->picurl) }}" alt="school logo" width="50px" style="margin-right: 10px;">
                        </td>
                        <td width="90%" style="text-align:center">
                            <div class="school-name">{{ $schoolInfo->schoolname }}</div>
                            <div class="address">{{ $schoolInfo->address }}</div>
                        </td>
                    </tr>
                </table>

                
                <div class="receipt-title">ACKNOWLEDGEMENT RECEIPT</div>
                <div class="date" style="float: right">DATE: {{ \Carbon\Carbon::now()->format('F j, Y') }}</div>
                
                <div class="student-name">NAME: {{ $student->lastname }}, {{ $student->firstname }} {{ $student->middlename }}</div>
                <div class="student-name">LEVEL: {{ $student->levelname }}</div>
                @php
                    $total = collect($paymentLogs)->sum('totalamount');
                @endphp
                
                <div class="amount-in-words">THE SUM OF PHP <span style="text-decoration: underline;">{{ number_format($total, 2) }}</span> ({{ strtoupper(\Terbilang::make($total)) }})</div>
                
                <table width="100%">
                    @foreach ($paymentLogs as $item)
                        <tr>
                            <td>{{ $item->itemDescription }}</td>
                            <td style="text-align: right; font-weight: bold;">{{ $item->totalamount }}</td>
                        </tr>
                    @endforeach
                </table>
                
                
                <table width="100%" style="margin-top: 50px">
                    <tr>
                        <td width="50%"></td>
                        <td width="50%" style="border-bottom: 1px solid #000; text-align: center; padding-top: 5px;font-weight: bold">{{ $cashierDetails->name }}</td>
                    </tr>
                    <tr>
                        <td width="50%"></td>
                        <td width="50%" style="text-align: center; padding-top: 5px;">Cashier</td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>
</body>
</html>