<body>
  <center><h2 style="font-family:Arial, Helvetica, sans-serif">Payment Schedule</h2></center>
  <center>
    <span style="font-family:Arial, Helvetica, sans-serif">
        {{ $description ?? '' }}
        @isset($sydescription)
            @if(!empty($description)) | @endif
            {{ $sydescription }}
        @endisset
        @isset($grantedescription)
            @if(!empty($description) || !empty($sydescription)) | @endif
            {{ $grantedescription }}
        @endisset
    </span>
  </center>
  <table class="payment-schedule">
    @php
      // Convert to array if it's a Collection or object
      $paymentsArray = $paymentsched instanceof \Illuminate\Support\Collection 
                      ? $paymentsched->all() 
                      : (array)$paymentsched;
      
      // Split into chunks of 3 months per row
      $chunkedMonths = array_chunk($paymentsArray, 3);
    @endphp

    @foreach($chunkedMonths as $monthGroup)
      <tr class="month-row">
        @foreach($monthGroup as $monthData)
          @php
            $monthDataArray = is_array($monthData) ? $monthData : (array)$monthData;
          @endphp
          <td class="month-column" style="height: 150px;">
            <div class="month-header" style="font-family:Arial, Helvetica, sans-serif">{{ ucfirst($monthDataArray['month']) }}</div>
            
            <table class="fees-table" style="font-family:Arial, Helvetica, sans-serif">
              @foreach($monthDataArray['data'] as $fee)
                @php
                  $feeArray = is_array($fee) ? $fee : (array)$fee;
                @endphp
                <tr class="fee-item">
                  <td class="fee-description">
                    {{ $feeArray['description'] }}
                    <span class="fee-amount">{{ number_format($feeArray['amount'], 2) }}</span>
                  </td>
                </tr>
              @endforeach
            </table>
          </td>
        @endforeach

        {{-- Fill empty cells if last row has less than 3 months --}}
        @for($i = count($monthGroup); $i < 3; $i++)
          <td class="month-column empty-column"></td>
        @endfor
      </tr>
    @endforeach
  </table>

  <style>
    .payment-schedule {
      width: 100%;
      border-collapse: separate; /* Changed from collapse to separate */
      border-spacing: 15px; /* This creates space between cells */
      margin: 20px 0;
    }
    .month-row {
      vertical-align: top;
    }
    .month-column {
      width: 30%; /* Reduced from 33.33% to account for spacing */
      border: 1px solid #e0e0e0;
      padding: 10px;
      background: #f9f9f9;
      border-radius: 5px; /* Added rounded corners */
      box-shadow: 0 2px 4px rgba(0,0,0,0.05); /* Subtle shadow */
    }
    .month-header {
      font-size: 1.2rem;
      font-weight: bold;
      margin-bottom: 10px;
      padding: 8px 0;
      border-bottom: 1px solid #ddd;
      color: #fff;
      background-color: #2c3e50!important;
      text-transform: capitalize;
      text-align: center;
      border-radius: 3px 3px 0 0; /* Rounded top corners */
    }
    .fees-table {
      width: 100%;
      border-collapse: collapse;
    }
    .fee-item {
      border-bottom: 1px solid #eee;
    }
    .fee-description {
      font-weight: 400;
      padding: 8px 0;
      font-size: 14px!important;
    }
    .fee-amount {
      float: right;
      font-weight: bold; /* Make amounts stand out more */
    }
    .empty-column {
      border: none;
      background: transparent!important;
      box-shadow: none;
    }
  </style>
</body>