<style>
    .input-box {
        display: inline-block;
        padding: 4px 10px;
        background-color: #f1f1f1;
        border-radius: 8px;
        box-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
        margin: 0 4px;
        font-size: 0.9rem;
    }
</style>

<form id="depreciationForm">
    <div class="form-group">

        <!-- Straight Line Method -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" value="1" name="depreciation_method" id="method1"
                    value="straight_line" checked>
                <label class="form-check-label" for="method1">Straight Line Method</label>
            </div>
            {{-- <a href="#" class="text-primary small" data-toggle="modal" data-target="#formulaModal1">View
                Formula</a> --}}
        </div>

        <!-- Double Declining -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" value="2" name="depreciation_method" id="method2"
                    value="double_declining">
                <label class="form-check-label" for="method2">Double Declining Balance Method</label>
            </div>
            {{-- <a href="#" class="text-primary small" data-toggle="modal" data-target="#formulaModal2">View
                Formula</a> --}}
        </div>

        {{-- <!-- Units of Production -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="depreciation_method" id="method3"
                    value="units_production">
                <label class="form-check-label" for="method3">Units of Production Method</label>
            </div>
            <a href="#" class="text-primary small" data-toggle="modal" data-target="#formulaModal3">View
                Formula</a>
        </div>

        <!-- Sum of Years Digits -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="depreciation_method" id="method4"
                    value="sum_of_years">
                <label class="form-check-label" for="method4">Sum of the Years Digits Method</label>
            </div>
            <a href="#" class="text-primary small" data-toggle="modal" data-target="#formulaModal4">View
                Formula</a>
        </div> --}}
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-success">Save</button>
    </div>
</form>

<!-- Modals -->

{{-- <div class="modal fade" id="formulaModal1" tabindex="-1" aria-labelledby="formulaModal1Label" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded">
            <div class="modal-header bg-light">
                <h5 class="modal-title">Straight Line Method</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" style="font-size: 24px;">X</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p><strong>Formula:</strong> (Cost - Residual Value) / Useful Life</p>
                <p><em>Where,</em></p>

                <!-- Row 1: Asset Cost - Residual Value -->
                <div class="mb-2">
                    <input type="text" class="input-box" placeholder="Asset Cost">
                    &nbsp;-&nbsp;
                    <input type="text" class="input-box" placeholder="Residual Value">
                </div>

                <!-- Row 2: Depreciation = _________ -->
                <div class="mb-2">
                    Depreciation = <span
                        style="display: inline-block; width: 150px; border-bottom: 1px solid #000;"></span>
                </div>

                <!-- Row 3: Useful Life -->
                <div>
                    <input type="text" class="input-box" placeholder="Useful Life">
                </div>
            </div>
        </div>
    </div>


    <!-- Modal 2: Double Declining -->
    <div class="modal fade" id="formulaModal2" tabindex="-1" aria-labelledby="formulaModal2Label" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Double Declining Balance Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Formula:</strong> 2 × Straight-Line Rate × Book Value at Beginning of Year</p>
                    <p><em>Where,</em></p>
                    <p>
                        Depreciation =
                        <span class="input-box">2</span> ×
                        <span class="input-box">Straight-Line Rate</span> ×
                        <span class="input-box">Beginning Book Value</span>
                    </p>
                </div>
            </div>
        </div>
    </div> --}}

{{-- <!-- Modal 3: Units of Production -->
    <div class="modal fade" id="formulaModal3" tabindex="-1" aria-labelledby="formulaModal3Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Units of Production Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Formula:</strong> (Cost - Residual Value) / Estimated Total Units × Actual Units Used</p>
                    <p><em>Where,</em></p>
                    <p>
                        Depreciation =
                        (<span class="input-box">Asset Cost</span> -
                        <span class="input-box">Residual Value</span>) ÷
                        <span class="input-box">Estimated Total Units</span> ×
                        <span class="input-box">Actual Units Used</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal 4: Sum of the Years' Digits -->
    <div class="modal fade" id="formulaModal4" tabindex="-1" aria-labelledby="formulaModal4Label"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded">
                <div class="modal-header bg-light">
                    <h5 class="modal-title">Sum of the Years Digits Method</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Formula:</strong> (Remaining Life / Sum of Years Digits) × (Cost - Residual Value)</p>
                    <p><em>Where,</em></p>
                    <p>
                        Depreciation =
                        (<span class="input-box">Remaining Life</span> ÷
                        <span class="input-box">Sum of Years</span>) ×
                        (<span class="input-box">Cost</span> -
                        <span class="input-box">Residual Value</span>)
                    </p>
                </div>
            </div>
        </div>
    </div> --}}
{{-- </div> --}}

<script>
    $(document).ready(function() {
        $('#depreciationForm').on('submit', function(e) {
            e.preventDefault();

            var selectedId = $('input[name="depreciation_method"]:checked').val();

            if (!selectedId) {
                alert('Please select a depreciation method.');
                return;
            }

            $.ajax({
                url: '/bookkeeper/depreciation',
                method: 'POST',
                data: {
                    id: selectedId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    alert('Updated successfully!');
                },
                error: function() {
                    alert('Something went wrong.');
                }
            });
        });
    });
</script>
