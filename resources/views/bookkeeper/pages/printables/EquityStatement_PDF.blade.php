<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Equity Statement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .text-right {
            text-align: right;
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

        table,
        th,
        td {
            border: 1px solid #7d7d7d;
        }

        th,
        td {
            padding: 8px;
        }

        .header-row {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .total-row {
            font-weight: bold;
            background-color: #e6e6e6;
        }

        .school-header {
            margin-bottom: 20px;
        }

        .report-title {
            margin: 15px 0;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="school-header" style="text-align: center;">
        <div style="display: inline-block; text-align: left;">
            <table style="border-collapse: collapse; border: none;">
                <tr>
                    <td style="border: none; text-align: right; vertical-align: middle;">
                        @if (!empty($schoolinfo->picurl))
                            <img src="{{ public_path($schoolinfo->picurl) }}" alt="School Logo" width="60">
                        @endif
                    </td>
                    <td style="border: none; text-align: left; padding-left: 10px;">
                        <div style="font-family: Arial, sans-serif;">
                            <h3 style="margin: 0; font-weight: 500;">{{ $schoolinfo->schoolname ?? 'School Name' }}</h3>
                            <p style="margin: 0; font-size: 12px;">
                                {{ $schoolinfo->address ?? 'School Address' }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

    <div class="report-title" style="text-align: center;">STATEMENT OF CHANGES IN EQUITY</div>

    <table id="equity_table">
        <thead>
            <tr class="header-row">
                <th style="width: 30%;">Particulars</th>
                <th style="width: 20%;">Common Stock</th>
                <th style="width: 20%;">Retained Earnings</th>
                <th style="width: 30%;">Total Equity</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Calculate current year values
                $currentYear = $entries['currentYear'] ?? null;
                $previousYear = $entries['previousYear'] ?? null;

                // Current year calculations
                $totalBeginningBalance = $currentYear['beginningBalance']->sum('net_amount') ?? 0;
                $totalIncomeStatement = $currentYear['incomeStatement']->sum('net_amount') ?? 0;
                $totalWithdrawal = $currentYear['withdrawals']->sum('net_amount') ?? 0;
                $totalEquity = $totalBeginningBalance + $totalIncomeStatement;
                $totalEndingBalance = $totalEquity - $totalWithdrawal;

                // Previous year calculations
                $prevYearBeginningBalance = $previousYear ? $previousYear['beginningBalance']->sum('net_amount') : 0;
                $prevYearIncomeStatement = $previousYear ? $previousYear['incomeStatement']->sum('net_amount') : 0;
                $prevYearWithdrawal = $previousYear ? $previousYear['withdrawals']->sum('net_amount') : 0;
                $prevYearEquity = $prevYearBeginningBalance + $prevYearIncomeStatement;
                $prevYearEndingBalance = $prevYearEquity - $prevYearWithdrawal;

                // Combined totals
                $totalEquityBeginningBal = $totalBeginningBalance + $prevYearBeginningBalance;
                $totalEquityNetIncome = $totalIncomeStatement + $prevYearIncomeStatement;
                $totalEquityWithdrawal = $totalWithdrawal + $prevYearWithdrawal;
                $totalEquityEndingBal = $totalEndingBalance + $prevYearEndingBalance;
            @endphp

            <tr>
                <td>Beginning Balance</td>
                <td class="text-left">{{ number_format($totalBeginningBalance, 2) }}</td>
                <td class="text-left">{{ number_format($prevYearBeginningBalance, 2) }}</td>
                <td class="text-left">{{ number_format($totalEquityBeginningBal, 2) }}</td>
            </tr>
            <tr>
                <td>Net Income</td>
                <td class="text-left">{{ number_format($totalIncomeStatement, 2) }}</td>
                <td class="text-left">{{ number_format($prevYearIncomeStatement, 2) }}</td>
                <td class="text-left">{{ number_format($totalEquityNetIncome, 2) }}</td>
            </tr>
            <tr>
                <td>Withdrawals</td>
                <td class="text-left">{{ number_format($totalWithdrawal, 2) }}</td>
                <td class="text-left">{{ number_format($prevYearWithdrawal, 2) }}</td>
                <td class="text-left">{{ number_format($totalEquityWithdrawal, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td>Ending Balance</td>
                <td class="text-left">{{ number_format($totalEndingBalance, 2) }}</td>
                <td class="text-left">{{ number_format($prevYearEndingBalance, 2) }}</td>
                <td class="text-left">{{ number_format($totalEquityEndingBal, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <br>
</body>

</html>
