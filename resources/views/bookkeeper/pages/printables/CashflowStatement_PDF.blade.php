<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Cash Flow Statement</title>
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

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 6px;
        }

        .category-header {
            font-weight: bold;
            background-color: #f2f2f2;
        }

        .category-total {
            font-weight: bold;
            background-color: #e6e6e6;
        }

        .account-row td:first-child {
            border-left: none;
        }

        .account-row td:nth-child(2) {
            padding-left: 20px;
        }

        .spacer-row td {
            border: none;
            height: 15px;
        }
        
        .negative {
            color: red;
        }
        
        .empty-row td {
            border-left: none;
            border-right: none;
            font-style: italic;
            color: #666;
        }
    </style>
</head>

<body>
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
                            <h3 style="margin: 0; font-weight: 500;">{{ $schoolinfo->schoolname ?? 'School Name' }}</h3>
                            <p style="margin: 0; font-size: 12px;">
                                {{ $schoolinfo->address ?? 'School Address' }}
                            </p>
                        </div>
                    </td>
                </tr>
            </table>
        </div>

        <h4 style="margin-top: 20px; font-weight: bold;font-size: 16px;">CASH FLOW STATEMENT</h4>
        {{-- <p style="margin: 5px 0;">For the period ending {{ $end_date ?? '[Date]' }}</p> --}}
    </div>

    <table id="cashflow_statement_table">
        <thead>
            <tr>
                <th class="text-left">Description</th>
                <th class="text-left">Account</th>
                <th class="text-right">Amount</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                // Initialize categories
                $categories = [
                    'OPERATING' => [
                        'accounts' => [],
                        'total' => 0
                    ],
                    'FINANCING' => [
                        'accounts' => [],
                        'total' => 0
                    ],
                    'INVESTING' => [
                        'accounts' => [],
                        'total' => 0
                    ]
                ];
                
                // Process entries data
                if(isset($entries) && $entries) {
                    foreach($entries as $category => $accounts) {
                        $categoryType = str_contains($category, 'Operating') ? 'OPERATING' : 
                                       (str_contains($category, 'Financing') ? 'FINANCING' : 
                                       (str_contains($category, 'Investing') ? 'INVESTING' : null));
                        
                        if(!$categoryType) continue;
                        
                        foreach($accounts as $account) {
                            // Handle null values by defaulting to 0
                            $debit = floatval($account->debit_amount ?? 0);
                            $credit = floatval($account->credit_amount ?? 0);
                            $amount = $debit - $credit;
                            
                            // Only add if amount is not zero (optional)
                            if ($amount != 0) {
                                // Group by account name and sum amounts
                                $existingAccount = null;
                                foreach($categories[$categoryType]['accounts'] as &$catAccount) {
                                    if(isset($catAccount['name']) && $catAccount['name'] === ($account->account_name ?? null)) {
                                        $existingAccount = &$catAccount;
                                        break;
                                    }
                                }
                                
                                if($existingAccount) {
                                    $existingAccount['amount'] += $amount;
                                } else {
                                    $categories[$categoryType]['accounts'][] = [
                                        'name' => $account->account_name ?? '[Unnamed Account]',
                                        'amount' => $amount
                                    ];
                                }
                                
                                $categories[$categoryType]['total'] += $amount;
                            }
                        }
                    }
                }
                
                // Function to format amounts with proper negative handling
                function formatAmount($amount) {
                    if ($amount === null) return '0.00';
                    $amount = floatval($amount);
                    if ($amount < 0) return '('.number_format(abs($amount), 2).')';
                    return number_format($amount, 2);
                }
                
                // Function to check if amount is negative
                function isNegative($amount) {
                    if ($amount === null) return false;
                    return floatval($amount) < 0;
                }
            @endphp

            {{-- OPERATING ACTIVITIES --}}
            <tr class="category-header">
                <td><strong>CASH FLOW FROM OPERATING ACTIVITIES</strong></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            @if(count($categories['OPERATING']['accounts']) > 0)
                @foreach($categories['OPERATING']['accounts'] as $account)
                <tr class="account-row">
                    <td></td>
                    <td>{{ $account['name'] ?? '[Unnamed Account]' }}</td>
                    <td class="text-right {{ isNegative($account['amount'] ?? 0) ? 'negative' : '' }}">
                        {{ formatAmount($account['amount'] ?? 0) }}
                    </td>
                    <td></td>
                </tr>
                @endforeach
            @else
                <tr class="empty-row">
                    <td></td>
                    <td>No operating activities recorded</td>
                    <td class="text-right">0.00</td>
                    <td></td>
                </tr>
            @endif
            
            <tr class="category-total">
                <td><strong>Net Cash from OPERATING ACTIVITIES</strong></td>
                <td></td>
                <td></td>
                <td class="text-right {{ isNegative($categories['OPERATING']['total']) ? 'negative' : '' }}">
                    <strong>{{ formatAmount($categories['OPERATING']['total']) }}</strong>
                </td>
            </tr>
            <tr class="spacer-row"><td colspan="4"></td></tr>
            
            {{-- FINANCING ACTIVITIES --}}
            <tr class="category-header">
                <td><strong>CASH FLOW FROM FINANCING ACTIVITIES</strong></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            @if(count($categories['FINANCING']['accounts']) > 0)
                @foreach($categories['FINANCING']['accounts'] as $account)
                <tr class="account-row">
                    <td></td>
                    <td>{{ $account['name'] ?? '[Unnamed Account]' }}</td>
                    <td class="text-right {{ isNegative($account['amount'] ?? 0) ? 'negative' : '' }}">
                        {{ formatAmount($account['amount'] ?? 0) }}
                    </td>
                    <td></td>
                </tr>
                @endforeach
            @else
                <tr class="empty-row">
                    <td></td>
                    <td>No financing activities recorded</td>
                    <td class="text-right">0.00</td>
                    <td></td>
                </tr>
            @endif
            
            <tr class="category-total">
                <td><strong>Net Cash from FINANCING ACTIVITIES</strong></td>
                <td></td>
                <td></td>
                <td class="text-right {{ isNegative($categories['FINANCING']['total']) ? 'negative' : '' }}">
                    <strong>{{ formatAmount($categories['FINANCING']['total']) }}</strong>
                </td>
            </tr>
            <tr class="spacer-row"><td colspan="4"></td></tr>
            
            {{-- INVESTING ACTIVITIES --}}
            <tr class="category-header">
                <td><strong>CASH FLOW FROM INVESTING ACTIVITIES</strong></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            
            @if(count($categories['INVESTING']['accounts']) > 0)
                @foreach($categories['INVESTING']['accounts'] as $account)
                <tr class="account-row">
                    <td></td>
                    <td>{{ $account['name'] ?? '[Unnamed Account]' }}</td>
                    <td class="text-right {{ isNegative($account['amount'] ?? 0) ? 'negative' : '' }}">
                        {{ formatAmount($account['amount'] ?? 0) }}
                    </td>
                    <td></td>
                </tr>
                @endforeach
            @else
                <tr class="empty-row">
                    <td></td>
                    <td>No investing activities recorded</td>
                    <td class="text-right">0.00</td>
                    <td></td>
                </tr>
            @endif
            
            <tr class="category-total">
                <td><strong>Net Cash from INVESTING ACTIVITIES</strong></td>
                <td></td>
                <td></td>
                <td class="text-right {{ isNegative($categories['INVESTING']['total']) ? 'negative' : '' }}">
                    <strong>{{ formatAmount($categories['INVESTING']['total']) }}</strong>
                </td>
            </tr>
            {{-- <tr class="spacer-row"><td colspan="4"></td></tr> --}}
            
            {{-- GRAND TOTAL --}}
            @php
                $grandTotal = $categories['OPERATING']['total'] + 
                             $categories['FINANCING']['total'] + 
                             $categories['INVESTING']['total'];
            @endphp
            {{-- <tr class="category-total" style="background-color: #d9d9d9;">
                <td><strong>NET INCREASE (DECREASE) IN CASH</strong></td>
                <td></td>
                <td></td>
                <td class="text-right {{ isNegative($grandTotal) ? 'negative' : '' }}">
                    <strong>{{ formatAmount($grandTotal) }}</strong>
                </td>
            </tr> --}}
        </tbody>
    </table>

    <br>
</body>
</html>