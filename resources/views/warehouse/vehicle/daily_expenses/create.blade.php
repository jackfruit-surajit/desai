@extends('warehouse.layouts.main')

@section('title', 'Add Daily Expense')
@section('breadcrumb-item', 'Fleet Management')
@section('breadcrumb-item-active', 'Add Daily Expense')

@section('css')
    <style>
        .form-card {
            background: rgba(16, 28, 76, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 24px;
        }
        .form-label {
            color: #ffffff !important;
            font-weight: 600;
            margin-bottom: 6px;
        }
        .form-control, .form-select {
            background-color: rgba(255, 255, 255, 0.08) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: rgba(255, 255, 255, 0.14) !important;
            border-color: #35429E !important;
            color: #ffffff !important;
        }
        .form-select option {
            background-color: #101C4C !important;
            color: #ffffff !important;
        }
        .preview-box {
            border: 2px dashed rgba(255, 255, 255, 0.25);
            border-radius: 8px;
            padding: 16px;
            text-align: center;
            background: rgba(255, 255, 255, 0.03);
        }
    </style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10 col-12">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h4 class="text-white mb-0"><i class="fa fa-receipt me-2"></i> Add Daily Vehicle Expense</h4>
            <a href="{{ route('warehouse-vehicle-daily-expenses') }}" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Back to Expenses
            </a>
        </div>

        @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Please fix the following errors:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="form-card shadow-sm text-white">
            <form action="{{ route('warehouse-vehicle-daily-expense-store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Driver Selection -->
                    <div class="col-md-6">
                        <label class="form-label" for="driver_id">Driver <span class="text-danger">*</span></label>
                        <select name="driver_id" id="driver_id" class="form-select @error('driver_id') is-invalid @enderror" required>
                            <option value="">-- Select Assigned Driver --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" 
                                        data-vehicle-id="{{ $driver->vehicle_id }}"
                                        data-vehicle-no="{{ $driver->vehicle_no }}"
                                        data-vehicle-name="{{ $driver->vehicle_name }}"
                                        {{ old('driver_id') == $driver->id ? 'selected' : '' }}>
                                    {{ $driver->name }} ({{ $driver->phone ?? 'No Phone' }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text text-white-50 small">Driver select korle tar vehicle auto-select hoye jabe.</div>
                    </div>

                    <!-- Vehicle Selection (Auto-selected from Driver) -->
                    <div class="col-md-6">
                        <label class="form-label" for="vehicle_id">Vehicle <span class="text-danger">*</span></label>
                        <input type="hidden" name="vehicle_id" id="hidden_vehicle_id" value="{{ old('vehicle_id') }}" />
                        <select id="vehicle_id" class="form-select @error('vehicle_id') is-invalid @enderror" style="pointer-events: none; cursor: not-allowed; background-color: rgba(255, 255, 255, 0.05) !important;" tabindex="-1" aria-disabled="true">
                            <option value="">-- Auto-selected from Driver --</option>
                            @foreach($vehicles as $vehicle)
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->vehicle_no }} ({{ $vehicle->vehicle_name }})
                                </option>
                            @endforeach
                        </select>
                        <div id="vehicle_info_badge" class="form-text small mt-1"></div>
                    </div>

                    <!-- Expense Date -->
                    <div class="col-md-4">
                        <label class="form-label" for="expense_date">Expense Date <span class="text-danger">*</span></label>
                        <input type="date" name="expense_date" id="expense_date" class="form-control" value="{{ old('expense_date', date('Y-m-d')) }}" required />
                    </div>

                    <!-- Expense Category -->
                    <div class="col-md-4">
                        <label class="form-label" for="expense_category">Expense Category <span class="text-danger">*</span></label>
                        <select name="expense_category" id="expense_category" class="form-select" required>
                            <option value="Toll" {{ old('expense_category') == 'Toll' ? 'selected' : '' }}>Toll Tax</option>
                            <option value="Parking" {{ old('expense_category') == 'Parking' ? 'selected' : '' }}>Parking Charges</option>
                            <option value="Washing" {{ old('expense_category') == 'Washing' ? 'selected' : '' }}>Vehicle Washing</option>
                            <option value="Driver Allowance" {{ old('expense_category') == 'Driver Allowance' ? 'selected' : '' }}>Driver Food / Allowance</option>
                            <option value="Puncture/Air" {{ old('expense_category') == 'Puncture/Air' ? 'selected' : '' }}>Puncture / Tyre Air</option>
                            <option value="Minor Repair" {{ old('expense_category') == 'Minor Repair' ? 'selected' : '' }}>Minor Road Repair</option>
                            <option value="Other" {{ old('expense_category') == 'Other' ? 'selected' : '' }}>Other Expense</option>
                        </select>
                    </div>

                    <!-- Amount -->
                    <div class="col-md-4">
                        <label class="form-label" for="amount">Amount (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-white border-secondary">₹</span>
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount" class="form-control fw-bold text-success" placeholder="0.00" value="{{ old('amount') }}" required />
                        </div>
                    </div>

                    <!-- Reimbursement Status -->
                    <div class="col-md-6">
                        <label class="form-label" for="reimbursement_status">Reimbursement Status <span class="text-danger">*</span></label>
                        <select name="reimbursement_status" id="reimbursement_status" class="form-select" required>
                            <option value="Approved" {{ old('reimbursement_status', 'Approved') == 'Approved' ? 'selected' : '' }}>Approved (Direct Entry)</option>
                            <option value="Paid" {{ old('reimbursement_status') == 'Paid' ? 'selected' : '' }}>Paid / Settled</option>
                            <option value="Pending" {{ old('reimbursement_status') == 'Pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="Rejected" {{ old('reimbursement_status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Bill Receipt Image Upload -->
                    <div class="col-md-6">
                        <label class="form-label" for="bill_receipt_image">Upload Bill / Receipt Image</label>
                        <input type="file" name="bill_receipt_image" id="bill_receipt_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" />
                        <div class="form-text text-white-50 small">Supported formats: JPG, PNG, WEBP (Max: 5MB)</div>
                    </div>

                    <div class="col-md-6 offset-md-6">
                        <label class="form-label">Receipt Preview</label>
                        <div class="preview-box">
                            <img id="bill_preview" src="" alt="Preview" class="d-none rounded border" style="max-height: 120px;" />
                            <span id="no_preview_text" class="text-white-50 small">No image selected</span>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label class="form-label" for="description">Description / Purpose</label>
                        <textarea name="description" id="description" rows="3" class="form-control" placeholder="Specify details, e.g. Highway toll slip, night delivery parking...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex justify-content-end gap-2 pt-3 border-top border-light border-opacity-10">
                        <a href="{{ route('warehouse-vehicle-daily-expenses') }}" class="btn btn-outline-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa fa-save me-1"></i> Save Daily Expense
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    function selectDriverVehicle(driverOpt) {
        var vehicleId = driverOpt.data('vehicle-id');
        var vehicleNo = driverOpt.data('vehicle-no');
        var vehicleName = driverOpt.data('vehicle-name');

        if (vehicleId) {
            $('#vehicle_id').val(vehicleId);
            $('#hidden_vehicle_id').val(vehicleId);
            $('#vehicle_id').css('pointer-events', 'none').css('cursor', 'not-allowed').css('background-color', 'rgba(255, 255, 255, 0.05)');
            $('#vehicle_info_badge').html('<span class="badge bg-success"><i class="fa fa-check-circle me-1"></i> Auto-selected: ' + (vehicleNo || '') + ' (' + (vehicleName || '') + ')</span>');
        } else {
            $('#vehicle_id').val('');
            $('#hidden_vehicle_id').val('');
            $('#vehicle_id').css('pointer-events', 'none').css('cursor', 'not-allowed').css('background-color', 'rgba(255, 255, 255, 0.05)');
            if ($('#driver_id').val()) {
                $('#vehicle_info_badge').html('<span class="badge bg-danger"><i class="fa fa-triangle-exclamation me-1"></i> Selected driver has no vehicle assigned in system.</span>');
            } else {
                $('#vehicle_info_badge').html('<span class="text-white-50 small"><i class="fa fa-info-circle me-1"></i> Driver select korle vehicle auto-select hobe.</span>');
            }
        }
    }

    // Auto-select vehicle ONLY when Driver is chosen
    $('#driver_id').on('change', function () {
        var opt = $(this).find('option:selected');
        selectDriverVehicle(opt);
    });

    if ($('#driver_id').val()) {
        selectDriverVehicle($('#driver_id').find('option:selected'));
    }

    $('form').on('submit', function (e) {
        if (!$('#hidden_vehicle_id').val()) {
            e.preventDefault();
            alert('Please select a driver who has an assigned vehicle.');
            $('#driver_id').focus();
            return false;
        }
    });

    // Bill preview
    $('#bill_receipt_image').on('change', function () {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#bill_preview').attr('src', e.target.result).removeClass('d-none');
                $('#no_preview_text').addClass('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            $('#bill_preview').addClass('d-none');
            $('#no_preview_text').removeClass('d-none');
        }
    });
});
</script>
@endsection
