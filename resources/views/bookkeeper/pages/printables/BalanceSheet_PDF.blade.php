<!DOCTYPE html>
<html>

<head>
    <title>Balance Sheet Report</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            margin-bottom: 30px;
        }

        th,
        td {
            padding: 5px;
            text-align: left;
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

            <div class="header">
                <h2>General Ledger Report</h2>
            </div>
        </div>
    </div>

    <h2>General Ledger</h2>
    @php
        // Initialize grand totals
        $grandDebit = 0;
        $grandCredit = 0;
        $grandEnding = 0;
    @endphp

    @foreach ($data as $classification => $entries)
        <h3>{{ strtoupper($classification) }}</h3>
        <table border="1" cellspacing="0" width="100%">
            <thead>
                <tr style="background-color: #f2f2f2;">
                    <th>Account Code</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Ending Balance</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                    $totalEnding = 0;
                @endphp

                @foreach ($entries as $entry)
                    <tr>
                        <td>{{ $entry->code }}</td>
                        <td style="text-align: right;">{{ number_format((float) $entry->debit_amount, 2) }}</td>
                        <td style="text-align: right;">{{ number_format((float) $entry->credit_amount, 2) }}</td>
                        <td style="text-align: right;">{{ number_format((float) $entry->ending_balance, 2) }}</td>
                    </tr>

                    @php
                        // Accumulate classification totals
                        $totalDebit += (float) $entry->debit_amount;
                        $totalCredit += (float) $entry->credit_amount;
                        $totalEnding += (float) $entry->ending_balance;

                        // Accumulate grand totals
                        $grandDebit += (float) $entry->debit_amount;
                        $grandCredit += (float) $entry->credit_amount;
                        $grandEnding += (float) $entry->ending_balance;
                    @endphp
                @endforeach

                <tr style="font-weight: bold; background-color: #e8e8e8;">
                    <td>Total {{ strtoupper($classification) }}</td>
                    <td style="text-align: right;">{{ number_format($totalDebit, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($totalCredit, 2) }}</td>
                    <td style="text-align: right;">{{ number_format($totalEnding, 2) }}</td>
                </tr>
            </tbody>
        </table>
        <br>
    @endforeach

    {{-- Grand Total Summary --}}
    <h3>GRAND TOTAL</h3>
    <table border="1" cellspacing="0" width="100%">
        <thead>
            <tr style="background-color: #d0e9c6;">
                <th>Total Debit</th>
                <th>Total Credit</th>
                <th>Total Ending Balance</th>
            </tr>
        </thead>
        <tbody>
            <tr style="font-weight: bold;">
                <td style="text-align: right;">{{ number_format($grandDebit, 2) }}</td>
                <td style="text-align: right;">{{ number_format($grandCredit, 2) }}</td>
                <td style="text-align: right;">{{ number_format($grandEnding, 2) }}</td>
            </tr>
        </tbody>
    </table>


</body>

</html>
