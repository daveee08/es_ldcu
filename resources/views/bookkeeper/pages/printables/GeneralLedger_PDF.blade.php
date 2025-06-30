<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>General Ledger Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS (for buttons only) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            padding: 20px;
            width: 80%;
            margin: 0 auto;
            background-color: white;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 13px;
        }
        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 2px;
            background-color: white !important;
        }
        table.data-table th {
            background-color: #f8f9fa !important;
            font-weight: bold;
        }
        table.data-table tr {
            background-color: white;
        }
        table.signatures {
            width: 100%;
            margin-top: 50px;
            border-collapse: collapse;
        }
        table.signatures td {
            padding-top: 40px;
            text-align: center;
            width: 25%;
        }
        .signature-line {
            border-top: 1px solid black;
            width: 80%;
            margin: 0 auto 5px auto;
        }
        .no-print {
            display: block;
        }
        @media print {
            @page {
                margin-bottom: 0;
                size: auto;
                margin: 1;
            }
            body::after {
                display: none;
            }
            body {
                padding: 0;
                width: 100%;
                font-size: 12px;
                background-color: white;
            }
            .no-print {
                display: none;
            }
            table.data-table {
                font-size: 11px;
            }
            table.data-table th,
            table.data-table td {
                padding: 1px;
            }
            table.data-table th {
                background-color: #f8f9fa !important;
            }
            @page :footer { display: none; }
            @page :header { display: none; }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <!-- Action Buttons -->
        <div class="no-print mb-3">
            <a href="{{ url()->previous() }}" class="btn btn-sm btn-secondary">Back</a>
            <button onclick="window.print()" class="btn btn-sm btn-primary">Print Report</button>
        </div>

        <!-- Report Header -->
        <div class="text-center mt-4 mb-4">
            @if(!empty($schoolinfo))
            <div>
                @if(!empty($schoolinfo->picurl))
                <img src="{{ asset($schoolinfo->picurl) }}" alt="Logo" style="height: 50px; margin-bottom: 10px;">
                @endif
                <h4 style="margin: 5px 0;">{{ $schoolinfo->schoolname ?? 'School Name' }}</h4>
                <p style="margin: 0; font-size: 13px;">{{ $schoolinfo->address ?? 'School Address' }}</p>
            </div>
            @endif
            <h3 style="margin: 15px 0 5px 0;">General Ledger</h3>
            <p style="margin: 0;">
                <strong>Period:</strong> 
                {{ $startDate->format('M j, Y') }} to {{ $endDate->format('M j, Y') }}
            </p>
        </div>

        <!-- General Ledger Table -->
        <table class="data-table">
            <thead>
                <tr>
                    <th width="10%">Date</th>
                    <th width="12%">Voucher No.</th>
                    <th width="28%">Description</th>
                    <th width="10%">Code</th>
                    <th width="20%">Account</th>
                    <th width="10%">Debit</th>
                    <th width="10%">Credit</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                    $currentVoucher = null;
                @endphp

                @forelse ($generalLedger as $entry)
                    @php
                        $debit = $entry->debit_amount ?? 0;
                        $credit = $entry->credit_amount ?? 0;
                        $totalDebit += $debit;
                        $totalCredit += $credit;
                    @endphp

                    <tr>
                        @if($currentVoucher != $entry->voucherNo)
                            <td>{{ $entry->date ? \Carbon\Carbon::parse($entry->date)->format('m/d/Y') : '-' }}</td>
                            <td>{{ $entry->voucherNo ?? '-' }}</td>
                            <td>{{ $entry->remarks ?? '-' }}</td>
                            @php $currentVoucher = $entry->voucherNo; @endphp
                        @else
                            <td></td>
                            <td></td>
                            <td></td>
                        @endif
                        <td>{{ $entry->code ?? '-' }}</td>
                        <td>{{ $entry->account_name ?? '-' }}</td>
                        <td class="text-right">{{ $debit != 0 ? number_format($debit, 2) : '' }}</td>
                        <td class="text-right">{{ $credit != 0 ? number_format($credit, 2) : '' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No transactions found</td>
                    </tr>
                @endforelse

                @if($generalLedger->count() > 0)
                <tr>
                    <td colspan="5" class="text-right"><strong>Total:</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalDebit, 2) }}</strong></td>
                    <td class="text-right"><strong>{{ number_format($totalCredit, 2) }}</strong></td>
                </tr>
                @endif
            </tbody>
        </table>

        <!-- Signatures Section -->
        @if(isset($signatories) && $signatories->isNotEmpty())
        <table class="signatures">
            <tr>
                @foreach($signatories as $signatory)
                <td>
                    <div class="signature-line"></div>
                    <div style="margin-top: 5px;">{{ $signatory->name }}</div>
                    <div style="font-size: 11px;">{{ $signatory->title }}</div>
                </td>
                @endforeach
            </tr>
        </table>
        @endif
    </div>

    <script>
        // Clean URL for printing
        history.replaceState({}, document.title, window.location.pathname);
        window.onbeforeprint = function() {
            history.replaceState({}, document.title, window.location.pathname);
        };
    </script>
</body>
</html>