<div class="container-fluid mt-4">
    <div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-30 z-50">
        <div class="bg-white rounded-xl shadow-lg p-4">
            <div class="row">
                <div class="col-md-4">
                    <h5></h5>Payroll Account
                </div>

                <div class="col-md-8">
                    <div class="form-group mb-3">
                        <label for="debit_account" class="form-label">Debit Account</label>
                        <select id="debit_account" class="form-control ">
                            {{-- <option value="600001">600001 - Salaries and Wages - Administration</option>
                            <option value="600002">600002 - Salaries and Wages - Operations</option> --}}
                            <!-- Add more options as needed -->
                            @foreach (DB::table('chart_of_accounts')->get() as $coa)
                                <option value="{{ $coa->id }}">{{ $coa->account_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label for="credit_account" class="form-label">Credit Account</label>
                    <select id="credit_account" class="form-control ">
                        {{-- <option value="1110006">1110006 - BPI - Tui - SA2122</option>
                        <option value="1110007">1110007 - BDO - Main - SA2123</option> --}}
                        @foreach (DB::table('chart_of_accounts')->get() as $coa)
                            <option value="{{ $coa->id }}">{{ $coa->account_name }}</option>
                        @endforeach
                        <!-- Add more options as needed -->
                    </select>
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-center p-4 border-t text-center">
        <button type="submit" class="btn btn-success px-4" id="addExpenseItem">Save</button>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
