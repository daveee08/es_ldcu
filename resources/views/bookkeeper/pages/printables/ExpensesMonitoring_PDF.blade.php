<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Expenses Monitoring</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
    
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
    
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px; /* uniform font size */
        }
    
        table, th, td {
            border: 1px solid black;
        }
    
        th, td {
            padding: 6px;
            text-align: center;
        }
    
        /* Signatories table: no borders at all */
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
    <div style="text-align: center; margin-bottom: 10px;">
        <div style="display: inline-block; text-align: left;">
            <table style="border-collapse: collapse; border: none;">
                <tr>
                    <td style="border: none; text-align: right; vertical-align: middle;">
                        @if(!empty($schoolinfo->picurl))
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
    
        <h4 style="margin-top: 20px; font-weight: bold;font-size: 16px;">Expenses Monitoring</h4>
    </div>
    
    
    {{-- <p><strong>Fiscal Year:</strong> {{ $entries->first()->fiscal_desc ?? '' }} <strong style="margin-left:50%">Date:</strong> {{ \Carbon\Carbon::now('Asia/Manila')->format('m/d/Y') }}</p> --}}

    <table>
        <thead>
            <tr>
                <th>Ref No.</th>
                <th>Employee Name</th>
                <th>Department</th>
                <th>Remarks</th>
                <th>Amount</th>
                <th>Date</th>
                <th>Status</th>
                
            </tr>
        </thead>
        <tbody>
            @php $balance = 0; @endphp
            @foreach ($entries as $entry)
                {{-- @php
                    $balance += $entry->debit_amount - $entry->credit_amount;
                @endphp --}}
                <tr> 
                    <td>{{ $entry->refnum }}</td>
                    <td>{{ $entry->firstname }} {{ $entry->lastname }}</td>
                    <td>{{ $entry->department }}</td>
                    <td>{{ $entry->remarks }}</td>
                    <td>{{ $entry->amount }}</td>
                    <td>{{ explode(' ', $entry->transdate)[0] }}</td>
                    <td style="text-align:right">{{ $entry->status }}</td>
                  
                </tr>
            @endforeach
        </tbody>
    </table>

    <br>

</body>
</html>
