<!DOCTYPE html>
<html>

<head>
    <title>Trial Balance</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 10px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
        }

        .header h4 {
            margin: 0;
            font-weight: normal;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: right;
        }

        th {
            background-color: #faf9f7;
        }

        td.left-align {
            text-align: left;
        }

        .total {
            font-weight: bold;
            background-color: #e4e4e4;
        }

        .fiscal-year-header {
            font-size: 14px;
            font-weight: bold;
            margin-top: 25px;
            margin-bottom: 5px;
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

        table.signatories tr {
            border: none;
        }
    </style>
</head>

<body>

    <div class="header">
        <div style="text-align: center; margin-bottom: 10px;">
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
                                <h3 style="margin: 0; font-weight: 500;">{{ $schoolinfo->schoolname ?? 'School Name' }}
                                </h3>
                                <p style="margin: 0; font-size: 12px;">
                                    {{ $schoolinfo->address ?? 'School Address' }}
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <h2>Trial Balance</h2>
        <h4>{{ now()->format('F d, Y') }}</h4>
    </div>
   
    @foreach ($groupedDisplay as $fiscalYear => $entries)
        <div class="fiscal-year-header">
            Fiscal Year: {{ $entries->first()->fiscal_year_name }}
        </div>

        @php
            $totalDebit = 0;
            $totalCredit = 0;
            $totalBalance = 0;
        @endphp

        <table width="100%" style="table-layout: fixed;">
      
            <thead>
                <tr>
                    <th width="20%">Fiscal Year</th>
                    <th width="12%">Code</th>
                    <th width="26%">Account</th>
                    <th width="14%">Debit</th>
                    <th width="14%">Credit</th>
                    <th width="14%">Ending Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($groupedDisplay->first() as $item)
                    <tr>
                        <td class="left-align">{{ $item->fiscal_year_name }}</td>
                        <td class="left-align">{{ $item->code }}</td>
                        <td class="left-align">{{ $item->classification }}</td>
                        <td style="text-align:right;">
                            {{ $item->debit_amount < 0 ? '(' . number_format(abs($item->debit_amount), 2) . ')' : number_format($item->debit_amount, 2) }}
                        </td>
                        <td style="text-align:right;">
                            {{ $item->credit_amount < 0 ? '(' . number_format(abs($item->credit_amount), 2) . ')' : number_format($item->credit_amount, 2) }}
                        </td>
                        <td style="text-align:right;">
                            {{ $item->ending_balance < 0 ? '(' . number_format(abs($item->ending_balance), 2) . ')' : number_format($item->ending_balance, 2) }}
                        </td>


                    </tr>
                    @php
                        $totalDebit += $item->debit_amount;
                        $totalCredit += $item->credit_amount;
                        $totalBalance += $item->ending_balance;
                    @endphp
                @endforeach

                <tr class="total">
                    <td colspan="3" class="left-align">Total</td>
                    <td style="text-align:right;">
                        {{ $totalDebit < 0 ? '(' . number_format(abs($totalDebit), 2) . ')' : number_format($totalDebit, 2) }}
                    </td>
                    <td style="text-align:right;">
                        {{ $totalCredit < 0 ? '(' . number_format(abs($totalCredit), 2) . ')' : number_format($totalCredit, 2) }}
                    </td>
                    <td style="text-align:right;">
                        {{ $totalBalance < 0 ? '(' . number_format(abs($totalBalance), 2) . ')' : number_format($totalBalance, 2) }}
                    </td>

                </tr>
            </tbody>
        </table>
    @endforeach
    <!-- Signatories: Only displayed once at the end -->
    <table class="signatories" width="100%">
        @if (isset($signatories) && $signatories->isNotEmpty())
            <tr>
                @foreach ($signatories as $index => $signatory)
                    <td style="text-align: left;">
                        {{ $signatory->description }}<br>
                        {{ $signatory->name }}<br>

                        ____________________________<br>
                        {{ $signatory->title }}
                    </td>
    
                    {{-- Make sure there's a new row every 2 columns --}}
                    @if (($index + 1) % 2 == 0 && !$loop->last)
                        </tr><tr>
                    @endif
                @endforeach
            </tr>
        @endif
    </table>
    
</body>

</html>
