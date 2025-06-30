<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Income Statement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-left {
            text-align: left;
        }

        .bold {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }

        th,
        td {
            padding: 6px;
            vertical-align: top;
        }

        /* Optional subtle borders */
        /* table, th, td {
            border: 1px solid #ddd;
        } */

        .amount-cell {
            width: 150px;
        }

        table.signatories {
            border: none;
            border-collapse: separate;
            margin-top: 30px;
        }

        table.signatories td {
            border: none;
            padding-top: 30px;
            text-align: left;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <div class="text-center" style="margin-bottom: 10px;">
        <div style="display: inline-block; text-align: left;">
            <table style="border-collapse: collapse; border: none;">
                <tr>
                    <td style="border: none; text-align: right; vertical-align: middle;">
                        @if (!empty($schoolinfo->picurl))
                            <img src="{{ public_path($schoolinfo->picurl) }}" alt="School Logo" width="60">
                        @endif
                    </td>
                    <td style="border: none; text-align: left; padding-left: 10px;">
                        <h3 style="margin: 0; font-weight: 500;">{{ $schoolinfo->schoolname ?? 'School Name' }}</h3>
                        <p style="margin: 0; font-size: 12px;">{{ $schoolinfo->address ?? 'School Address' }}</p>
                    </td>
                </tr>
            </table>
        </div>

        <h4 style="margin-top: 20px; font-weight: bold; font-size: 16px;">Income Statement</h4>
    </div>

    {{-- @php
        $groupedData = collect($ledgerData)->groupBy(function ($item) {
            return ucfirst(strtolower($item->classification));
        });

        $totals = [];
        $grandTotal = 0;  // This will store the grand total of all amounts
    @endphp

    @foreach ($groupedData as $classification => $items)
        <h4 style="margin-bottom: 5px;">{{ $classification }}</h4>
        <table>
            <tbody>
                @php $totals[$classification] = 0; @endphp

                @foreach ($items as $item)
                    @php
                        $amount = $item->total_amount;
                        $totals[$classification] += $amount;
                        $grandTotal += $amount;  // Add to the grand total
                    @endphp
                    <tr>
                        <td class="text-left" style="padding-left: 50px;">
                            {{ $item->code }} - {{ $item->account_name }}
                        </td>
                        <td class="text-left amount-cell">
                            {{ $amount >= 0 ? number_format($amount, 2) : '(' . number_format(abs($amount), 2) . ')' }}
                        </td>
                    </tr>
                @endforeach

                <tr class="bold">
                    <td class="text-left">Total {{ $classification }}</td>
                    <td class="text-right amount-cell">
                        {{ $totals[$classification] >= 0 ? number_format($totals[$classification], 2) : '(' . number_format(abs($totals[$classification]), 2) . ')' }}
                    </td>
                </tr>
            </tbody>
        </table>
    @endforeach --}}


    <table>
        <tbody>

            <tr>
                <td><b>REVENUE</b></td>
            </tr>
            @foreach ($ledgerData['revenues'] as $item)
                <tr>
                    <td class="text-left" style="padding-left: 50px;">
                        {{ $item['code'] }} - {{ $item['account_name'] }}
                    </td>
                    <td class="text-right amount-cell">
                        {{ $item['amount'] >= 0 ? number_format($item['amount'], 2) : '(' . number_format(abs($item['amount']), 2) . ')' }}
                    </td>
                </tr>
            @endforeach


            <tr>
                <td><b>EXPENSES</b></td>
            </tr>
            @foreach ($ledgerData['expenses'] as $item)
                <tr>
                    <td class="text-left" style="padding-left: 50px;">
                        {{ $item['code'] }} - {{ $item['account_name'] }}
                    </td>
                    <td class="text-right amount-cell">
                        {{ $item['amount'] >= 0 ? number_format($item['amount'], 2) : '(' . number_format(abs($item['amount']), 2) . ')' }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
   
    
    {{-- Grand Total --}}
    <table style="margin-top: 15px;">
        <tbody>
            <tr class="bold" style="border-top: 2px solid #000;">
                <td class="text-left" style="font-size: 14px;">NET INCOME</td>
                <td class="text-right amount-cell" style="font-size: 14px;">
                    {{ $net_income >= 0 ? number_format($net_income, 2) : '(' . number_format(abs($net_income), 2) . ')' }}
                </td>
            </tr>
        </tbody>
    </table>

</body>


</html>
