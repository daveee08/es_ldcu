<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acknowledgement Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .receipt-container {
            width: 100%;
            padding: 5px;
            border: 1px solid #000;
            background: #fff;
            box-sizing: border-box;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
            word-wrap: break-word;
            max-width: 100%;
            overflow: hidden;
        }

        .header img {
            width: 70px;
            vertical-align: middle;
        }

        .header h2 {
            margin: 5px 0;
            font-size: 14px;
            font-weight: bold;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 90%;
            display: inline-block;
        }

        .header p {
            font-size: 12px;
            margin: 0;
            word-wrap: break-word;
        }

        .payment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
        }

        .payment-table th,
        .payment-table td {
            border: 1px solid #ccc;
            padding: 3px;
            text-align: left;
        }

        .payment-table th {
            background: #f4f4f4;
        }

        @media print {
            .btn-print {
                display: none;
            }
        }

        @page {
            size: A4;
            margin: 0;
        }
    </style>
</head>
@php
    $schoolInfo = DB::table('schoolinfo')->first();
@endphp

<body>
    <table width="100%">
        <tr>
            <td width="48.75%">
                <div class="receipt-container">
                    <div class="header">
                        @if ($schoolInfo->picurl)
                            <img src="{{ asset('/' . $schoolInfo->picurl) }}" alt="school">
                        @endif
                        <p>{{ $schoolInfo->schoolname }}</p>
                        <p>{{ $schoolInfo->address }}</p>
                    </div>
                    <p style="font-size: 12px">
                        Level: {{ $information['levelname'] ?? 'N/A' }}
                        @if (!empty($information['courseabrv']))
                            | Course: {{ $information['courseabrv'] }}
                        @elseif (!empty($information['strandname']))
                            | Strand: {{ $information['strandname'] }}
                        @endif
                    </p>
                    
                    <table class="payment-table">
                        @foreach ($feesData as $category => $fees)
                            <tr>
                                <td colspan="2" class="fees-category"><strong>{{ $category }}</strong></td>
                            </tr>
                            @foreach ($fees as $fee)
                                <tr>
                                    <td class="description" style="padding-left: 30px;">{{ $fee->description }}</td>
                                    <td class="amount" style="text-align: right;">{{ number_format($fee->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </td>
            <td width="2.5%"></td>
            <td width="48.75%">
                <div class="receipt-container">
                    <div class="header">
                        @if ($schoolInfo->picurl)
                            <img src="{{ asset($schoolInfo->picurl) }}" alt="school">
                        @endif
                        <p>{{ $schoolInfo->schoolname }}</p>
                        <p>{{ $schoolInfo->address }}</p>
                    </div>
                    <p style="font-size: 12px">
                        Level: {{ $information['levelname'] ?? 'N/A' }}
                        @if (!empty($information['courseabrv']))
                            | Course: {{ $information['courseabrv'] }}
                        @elseif (!empty($information['strandname']))
                            | Strand: {{ $information['strandname'] }}
                        @endif
                    </p>
                    
                    <table class="payment-table">
                        @foreach ($feesData as $category => $fees)
                            <tr>
                                <td colspan="2" class="fees-category"><strong>{{ $category }}</strong></td>
                            </tr>
                            @foreach ($fees as $fee)
                                <tr>
                                    <td class="description" style="padding-left: 30px;">{{ $fee->description }}</td>
                                    <td class="amount" style="text-align: right;">{{ number_format($fee->amount, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </table>
                </div>
            </td>
        </tr>
    </table>
</body>

</html>
