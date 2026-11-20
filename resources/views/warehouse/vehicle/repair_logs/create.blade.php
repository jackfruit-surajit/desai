@extends('warehouse.layouts.main')

@section('title', 'Add Vehicle Repair Entry')
@section('breadcrumb-item', 'Fleet Management')
@section('breadcrumb-item-active', 'Add Repair & Service Entry')

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
            <h4 class="text-white mb-0"><i class="fa fa-wrench me-2"></i> Add Repair & Service Record</h4>
            <a href="{{ route('warehouse-vehicle-repair-logs') }}" class="btn btn-outline-light btn-sm">
                <i class="fa fa-arrow-left me-1"></i> Back to Repair Logs
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
            <form action="{{ route('warehouse-vehicle-repair-log-store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Driver Selection (FIRST) -->
                    <div class="col-md-6">
                        <label class="form-label" for="driver_id">Driver <span class="text-danger">*</span></label>
                        <select name="driver_id" id="driver_id" class="form-select @error('driver_id') is-invalid @enderror" required>
                            <option value="">-- Select Assigned Driver --</option>
                            @foreach($drivers as $driver)
                                <option value="{{ $driver->id }}" 
                                        data-vehicle-id="{{ $driver->vehicle_id }}"
                                        data-vehicle-no="{{ $driver->vehicle_no }}"
                                        data-vehicle-name="{{ $driver->vehicle_name }}"
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
                                <option value="{{ $vehicle->id }}" {{ old('vehicle_id') == $vehicle->id ? 'selected' : '' }}>
                                    {{ $vehicle->vehicle_no }} ({{ $vehicle->vehicle_name }})
                                </option>
                            @endforeach
                        </select>
                        <div id="repair_odometer_helper" class="form-text text-warning small"></div>
                        <div id="vehicle_info_badge" class="form-text small mt-1"></div>
                    </div>

                    <!-- Repair Date -->
                    <div class="col-md-3">
                        <label class="form-label" for="repair_date">Repair Date <span class="text-danger">*</span></label>
                        <input type="date" name="repair_date" id="repair_date" class="form-control" value="{{ old('repair_date', date('Y-m-d')) }}" required />
                    </div>

                    <!-- Completion Date -->
                    <div class="col-md-3">
                        <label class="form-label" for="completion_date">Completion Date</label>
                        <input type="date" name="completion_date" id="completion_date" class="form-control" value="{{ old('completion_date') }}" />
                    </div>

                    <!-- Garage / Workshop Name -->
                    <div class="col-md-3">
                        <label class="form-label" for="garage_workshop_name">Workshop / Garage <span class="text-danger">*</span></label>
                        <input type="text" name="garage_workshop_name" id="garage_workshop_name" class="form-control" placeholder="e.g. Maruti Care, Panaji" value="{{ old('garage_workshop_name') }}" required />
                    </div>

                    <!-- Garage Contact No -->
                    <div class="col-md-3">
                        <label class="form-label" for="garage_contact_no">Workshop Contact No</label>
                        <input type="text" name="garage_contact_no" id="garage_contact_no" class="form-control" placeholder="e.g. 9876543210" value="{{ old('garage_contact_no') }}" />
                    </div>

                    <!-- Invoice / Bill Number -->
                    <div class="col-md-4">
                        <label class="form-label" for="invoice_number">Invoice / Bill Number</label>
                        <input type="text" name="invoice_number" id="invoice_number" class="form-control" placeholder="e.g. INV-2026-089" value="{{ old('invoice_number') }}" />
                    </div>

                    <!-- Repair Reason -->
                    <div class="col-md-4">
                        <label class="form-label" for="repair_reason">Repair Reason / Type <span class="text-danger">*</span></label>
                        <select name="repair_reason" id="repair_reason" class="form-select" required>
                            <option value="Scheduled Service" {{ old('repair_reason') == 'Scheduled Service' ? 'selected' : '' }}>Scheduled Periodic Service</option>
                            <option value="Breakdown" {{ old('repair_reason') == 'Breakdown' ? 'selected' : '' }}>On-Road Breakdown</option>
                            <option value="Accident" {{ old('repair_reason') == 'Accident' ? 'selected' : '' }}>Accident Damage</option>
                            <option value="Tyre Replacement" {{ old('repair_reason') == 'Tyre Replacement' ? 'selected' : '' }}>Tyre Replacement / Alignment</option>
                            <option value="Battery" {{ old('repair_reason') == 'Battery' ? 'selected' : '' }}>Battery Replacement / Charging</option>
                            <option value="Brake Service" {{ old('repair_reason') == 'Brake Service' ? 'selected' : '' }}>Brake / Clutch Service</option>
                            <option value="Oil & Filter" {{ old('repair_reason') == 'Oil & Filter' ? 'selected' : '' }}>Engine Oil & Filter Change</option>
                            <option value="Other" {{ old('repair_reason') == 'Other' ? 'selected' : '' }}>Other Overhaul</option>
                        </select>
                    </div>

                    <!-- Odometer at Repair -->
                    <div class="col-md-4">
                        <label class="form-label" for="odometer_at_repair">Odometer at Repair (KM)</label>
                        <div class="input-group">
                            <input type="number" step="0.1" min="0" name="odometer_at_repair" id="odometer_at_repair" class="form-control" placeholder="e.g. 48500" value="{{ old('odometer_at_repair') }}" />
                            <span class="input-group-text bg-dark text-white border-secondary">KM</span>
                        </div>
                    </div>

                    <!-- Issues Reported -->
                    <div class="col-md-6">
                        <label class="form-label" for="issues_reported">Issues Reported</label>
                        <textarea name="issues_reported" id="issues_reported" rows="2" class="form-control" placeholder="Describe problems reported by driver...">{{ old('issues_reported') }}</textarea>
                    </div>

                    <!-- Work Performed -->
                    <div class="col-md-6">
                        <label class="form-label" for="work_performed">Work Performed</label>
                        <textarea name="work_performed" id="work_performed" rows="2" class="form-control" placeholder="Describe repairs & part replacements done...">{{ old('work_performed') }}</textarea>
                    </div>

                    <!-- Cost Breakdown -->
                    <div class="col-md-3">
                        <label class="form-label" for="spare_parts_cost">Spare Parts Cost (₹)</label>
                        <input type="number" step="0.01" min="0" name="spare_parts_cost" id="spare_parts_cost" class="form-control cost-input" placeholder="0.00" value="{{ old('spare_parts_cost', '0') }}" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="labor_charges">Labor Charges (₹)</label>
                        <input type="number" step="0.01" min="0" name="labor_charges" id="labor_charges" class="form-control cost-input" placeholder="0.00" value="{{ old('labor_charges', '0') }}" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="tax_amount">Tax Amount / GST (₹)</label>
                        <input type="number" step="0.01" min="0" name="tax_amount" id="tax_amount" class="form-control cost-input" placeholder="0.00" value="{{ old('tax_amount', '0') }}" />
                    </div>

                    <div class="col-md-3">
                        <label class="form-label" for="total_cost">Total Cost (₹)</label>
                        <input type="number" step="0.01" min="0" name="total_cost" id="total_cost" class="form-control fw-bold text-danger" placeholder="Auto-calculated" value="{{ old('total_cost') }}" />
                        <div class="form-text text-white-50 small">Parts + Labor + Tax</div>
                    </div>

                    <!-- Payment Status & Mode -->
                    <div class="col-md-4">
                        <label class="form-label" for="payment_status">Payment Status <span class="text-danger">*</span></label>
                        <select name="payment_status" id="payment_status" class="form-select" required>
                            <option value="Paid" {{ old('payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                            <option value="Unpaid" {{ old('payment_status', 'Unpaid') == 'Unpaid' ? 'selected' : '' }}>Unpaid / Pending Bill</option>
                            <option value="Partially Paid" {{ old('payment_status') == 'Partially Paid' ? 'selected' : '' }}>Partially Paid</option>
                        </select>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="payment_mode">Payment Mode</label>
                        <select name="payment_mode" id="payment_mode" class="form-select">
                            <option value="Cash" {{ old('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
                            <option value="Bank Transfer" {{ old('payment_mode') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer / NEFT</option>
                            <option value="UPI" {{ old('payment_mode') == 'UPI' ? 'selected' : '' }}>UPI / QR Code</option>
                            <option value="Cheque" {{ old('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                            <option value="Credit" {{ old('payment_mode') == 'Credit' ? 'selected' : '' }}>Garage Khata (Credit)</option>
                        </select>
                    </div>

                    <!-- Bill Image Upload -->
                    <div class="col-md-4">
                        <label class="form-label" for="bill_copy_image">Invoice / Bill Copy Image</label>
                        <input type="file" name="bill_copy_image" id="bill_copy_image" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp" />
                        <div class="form-text text-white-50 small">JPG, PNG, WEBP (Max: 5MB)</div>
                    </div>

                    <div class="col-md-4 offset-md-8">
                        <div class="preview-box">
                            <img id="bill_preview" src="" alt="Preview" class="d-none rounded border" style="max-height: 120px;" />
                            <span id="no_preview_text" class="text-white-50 small">No bill copy selected</span>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="col-12 d-flex justify-content-end gap-2 pt-3 border-top border-light border-opacity-10">
                        <a href="{{ route('warehouse-vehicle-repair-logs') }}" class="btn btn-outline-light">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fa fa-save me-1"></i> Save Repair Record
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
    // Auto-calculate total cost
    function calcRepairTotal() {
        var parts = parseFloat($('#spare_parts_cost').val()) || 0;
        var labor = parseFloat($('#labor_charges').val()) || 0;
        var tax = parseFloat($('#tax_amount').val()) || 0;
        var total = parts + labor + tax;
        $('#total_cost').val(total.toFixed(2));
    }

    $('.cost-input').on('input change', calcRepairTotal);

    function selectDriverVehicle(driverOpt) {
        var vehicleId = driverOpt.data('vehicle-id');
        var vehicleNo = driverOpt.data('vehicle-no');
        var vehicleName = driverOpt.data('vehicle-name');
        var odo = driverOpt.data('odometer');

        if (vehicleId) {
            $('#vehicle_id').val(vehicleId);
            $('#hidden_vehicle_id').val(vehicleId);
            $('#vehicle_id').css('pointer-events', 'none').css('cursor', 'not-allowed').css('background-color', 'rgba(255, 255, 255, 0.05)');
            $('#vehicle_info_badge').html('<span class="badge bg-success"><i class="fa fa-check-circle me-1"></i> Auto-selected: ' + (vehicleNo || '') + ' (' + (vehicleName || '') + ')</span>');
            if (odo) {
                $('#repair_odometer_helper').text('Current Vehicle Odometer: ' + odo + ' KM');
                if (!$('#odometer_at_repair').val()) {
                    $('#odometer_at_repair').val(odo);
                }
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
            $('#repair_odometer_helper').text('');
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

    // Bill copy preview
    $('#bill_copy_image').on('change', function () {
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
