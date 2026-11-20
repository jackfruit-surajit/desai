@extends('warehouse.layouts.main')

@section('title', 'Add Fuel Entry')
@section('breadcrumb-item', 'Fleet Management')
@section('breadcrumb-item-active', 'Add Fuel Entry')

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
            <h4 class="text-white mb-0"><i class="fa fa-gas-pump me-2"></i> Add Fuel Refill Entry</h4>
            <a href="{{ route('warehouse-vehicle-fuel-logs') }}" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Back to Fuel Logs
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
            <form action="{{ route('warehouse-vehicle-fuel-log-store') }}" method="POST" enctype="multipart/form-data">
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
                                        data-fuel="{{ $driver->fuel_type ?? 'Diesel' }}"
                                        data-odometer="{{ $driver->current_odometer ?? 0 }}"
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
                                <option value="{{ $vehicle->id }}" 
                                        data-fuel="{{ $vehicle->fuel_type ?? 'Diesel' }}" 
                                        data-odometer="{{ $vehicle->current_odometer ?? 0 }}"
                                        {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->vehicle_no }} ({{ $vehicle->vehicle_name }})
                                </option>
                            @endforeach
                        </select>
                        <div id="vehicle_info_badge" class="form-text small mt-1"></div>
                    </div>

                    <!-- Fuel Date -->
                    <div class="col-md-4">
                        <label class="form-label" for="fuel_date">Fuel Date <span class="text-danger">*</span></label>
                        <input type="date" name="fuel_date" id="fuel_date" class="form-control" value="{{ old('fuel_date', date('Y-m-d')) }}" required />
                    </div>

                    <!-- Fuel Type -->
                    <div class="col-md-4">
                        <label class="form-label" for="fuel_type">Fuel Type <span class="text-danger">*</span></label>
                        <select name="fuel_type" id="fuel_type" class="form-select" required>
                            <option value="Diesel" {{ old('fuel_type') == 'Diesel' ? 'selected' : '' }}>Diesel</option>
                            <option value="Petrol" {{ old('fuel_type') == 'Petrol' ? 'selected' : '' }}>Petrol</option>
                            <option value="CNG" {{ old('fuel_type') == 'CNG' ? 'selected' : '' }}>CNG</option>
                            <option value="Electric" {{ old('fuel_type') == 'Electric' ? 'selected' : '' }}>Electric</option>
                        </select>
                    </div>

                    <!-- Fuel Station Name -->
                    <div class="col-md-4">
                        <label class="form-label" for="fuel_station_name">Fuel Station Name</label>
                        <input type="text" name="fuel_station_name" id="fuel_station_name" class="form-control" placeholder="e.g. Indian Oil, Margao" value="{{ old('fuel_station_name') }}" />
                    </div>

                    <!-- Quantity Liters -->
                    <div class="col-md-4">
                        <label class="form-label" for="quantity_liters">Quantity (Liters) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" min="0.01" name="quantity_liters" id="quantity_liters" class="form-control" placeholder="0.00" value="{{ old('quantity_liters') }}" required />
                            <span class="input-group-text bg-dark text-white border-secondary">Ltrs</span>
                        </div>
                    </div>

                    <!-- Rate per Liter -->
                    <div class="col-md-4">
                        <label class="form-label" for="rate_per_liter">Rate per Liter (₹) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-white border-secondary">₹</span>
                            <input type="number" step="0.01" min="0.01" name="rate_per_liter" id="rate_per_liter" class="form-control" placeholder="0.00" value="{{ old('rate_per_liter') }}" required />
                        </div>
                    </div>

                    <!-- Total Amount -->
                    <div class="col-md-4">
                        <label class="form-label" for="total_amount">Total Amount (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-dark text-white border-secondary">₹</span>
                            <input type="number" step="0.01" min="0" name="total_amount" id="total_amount" class="form-control fw-bold text-success" placeholder="Auto-calculated" value="{{ old('total_amount') }}" />
                        </div>
                        <div class="form-text text-white-50 small">Auto-calculated: Liters × Rate</div>
                    </div>

                    <!-- Odometer Reading -->
                    <div class="col-md-4">
                        <label class="form-label" for="odometer_reading">Odometer Reading (KM)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" min="0" name="odometer_reading" id="odometer_reading" class="form-control" placeholder="e.g. 45210" value="{{ old('odometer_reading') }}" />
                            <span class="input-group-text bg-dark text-white border-secondary">KM</span>
                        </div>
                        <div id="odometer_helper" class="form-text text-warning small"></div>
                    </div>

                    <!-- Payment Mode -->
                    <div class="col-md-4">
                        <label class="form-label" for="payment_mode">Payment Mode</label>
                        <select name="payment_mode" id="payment_mode" class="form-select">
                            <option value="Cash" {{ old('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="UPI" {{ old('payment_mode') == 'UPI' ? 'selected' : '' }}>UPI / QR Code</option>
                            <option value="Card" {{ old('payment_mode') == 'Card' ? 'selected' : '' }}>Debit/Credit Card</option>
                            <option value="Company Account" {{ old('payment_mode') == 'Company Account' ? 'selected' : '' }}>Company Account / Fastag</option>
                            <option value="Credit" {{ old('payment_mode') == 'Credit' ? 'selected' : '' }}>Credit (Fuel Station Khata)</option>
                        </select>
                    </div>

                    <!-- Approval Status -->
                    <div class="col-md-4">
                        <label class="form-label" for="approval_status">Approval Status <span class="text-danger">*</span></label>
                        <select name="approval_status" id="approval_status" class="form-select" required>
                            <option value="Approved" {{ old('approval_status', 'Approved') == 'Approved' ? 'selected' : '' }}>Approved (Direct Entry)</option>
                            <option value="Pending" {{ old('approval_status') == 'Pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="Rejected" {{ old('approval_status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Slip Image Upload -->
                    <div class="col-md-6">
                        <label class="form-label" for="slip_image">Upload Fuel Slip / Receipt Image</label>
                        <input type="file" name="slip_image" id="slip_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" />
                        <div class="form-text text-white-50 small">Supported formats: JPG, PNG, WEBP (Max: 5MB)</div>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Slip Image Preview</label>
                        <div class="preview-box">
                            <img id="slip_preview" src="" alt="Preview" class="d-none rounded border" style="max-height: 120px;" />
                            <span id="no_preview_text" class="text-white-50 small">No image selected</span>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="col-12">
                        <label class="form-label" for="remarks">Remarks / Notes</label>
                        <textarea name="remarks" id="remarks" rows="2" class="form-control" placeholder="Optional remarks, e.g. full tank fill, long route refill...">{{ old('remarks') }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex justify-content-end gap-2 pt-3 border-top border-light border-opacity-10">
                        <a href="{{ route('warehouse-vehicle-fuel-logs') }}" class="btn btn-outline-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa fa-save me-1"></i> Save Fuel Entry
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
    // Auto-calculate Total Amount = Quantity * Rate
    function calcTotal() {
        var qty = parseFloat($('#quantity_liters').val()) || 0;
        var rate = parseFloat($('#rate_per_liter').val()) || 0;
        if (qty > 0 && rate > 0) {
            $('#total_amount').val((qty * rate).toFixed(2));
        }
    }

    $('#quantity_liters, #rate_per_liter').on('input change', calcTotal);

    function selectDriverVehicle(driverOpt) {
        var vehicleId = driverOpt.data('vehicle-id');
        var vehicleNo = driverOpt.data('vehicle-no');
        var vehicleName = driverOpt.data('vehicle-name');
        var fuelType = driverOpt.data('fuel');
        var odo = driverOpt.data('odometer');

        if (vehicleId) {
            $('#vehicle_id').val(vehicleId);
            $('#hidden_vehicle_id').val(vehicleId);
            $('#vehicle_id').css('pointer-events', 'none').css('cursor', 'not-allowed').css('background-color', 'rgba(255, 255, 255, 0.05)');
            $('#vehicle_info_badge').html('<span class="badge bg-success"><i class="fa fa-check-circle me-1"></i> Auto-selected: ' + (vehicleNo || '') + ' (' + (vehicleName || '') + ')</span>');
            if (fuelType) {
                $('#fuel_type').val(fuelType);
            }
            if (odo) {
                $('#odometer_helper').text('Current Vehicle Odometer: ' + odo + ' KM');
            }
        } else {
            $('#vehicle_id').val('');
            $('#hidden_vehicle_id').val('');
            $('#vehicle_id').css('pointer-events', 'none').css('cursor', 'not-allowed').css('background-color', 'rgba(255, 255, 255, 0.05)');
            if ($('#driver_id').val()) {
                $('#vehicle_info_badge').html('<span class="badge bg-danger"><i class="fa fa-triangle-exclamation me-1"></i> Selected driver has no vehicle assigned in system.</span>');
            } else {
                $('#vehicle_info_badge').html('<span class="text-white-50 small"><i class="fa fa-info-circle me-1"></i> Driver select korle vehicle auto-select hobe.</span>');
            }
            $('#odometer_helper').text('');
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

    // Slip image preview
    $('#slip_image').on('change', function () {
        var file = this.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#slip_preview').attr('src', e.target.result).removeClass('d-none');
                $('#no_preview_text').addClass('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            $('#slip_preview').addClass('d-none');
            $('#no_preview_text').removeClass('d-none');
        }
    });
});
</script>
@endsection
