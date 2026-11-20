@extends('admin.layouts.main')

@section('title', 'Fleet Fuel Tracking Reports')
@section('breadcrumb-item', 'Fleet Management')
@section('breadcrumb-item-active', 'Fuel Tracking Reports')

@section('css')
    <style>
        .filter-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 10px;
            padding: 18px;
        }
        .form-label {
            margin-bottom: 6px;
            font-weight: 600;
            color: #333333;
        }
        .kpi-card {
            background: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 12px;
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @include('admin.fleet_reports.partials.nav-tabs')

        <!-- KPI Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Total Liters Refilled</span>
                            <h3 class="mb-0 mt-1 fw-bold text-info">{{ number_format($totalLiters, 2) }} L</h3>
                            <small class="text-muted">{{ number_format($totalEntries) }} Entries</small>
                        </div>
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-3">
                            <i class="fa fa-gas-pump fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Total Fuel Cost</span>
                            <h3 class="mb-0 mt-1 fw-bold text-success">₹{{ number_format($totalCost, 2) }}</h3>
                            <small class="text-muted">All Warehouses</small>
                        </div>
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
                            <i class="fa fa-indian-rupee-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">This Month Cost</span>
                            <h3 class="mb-0 mt-1 fw-bold text-warning">₹{{ number_format($thisMonthCost, 2) }}</h3>
                            <small class="text-muted">{{ date('F Y') }}</small>
                        </div>
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3">
                            <i class="fa fa-calendar-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Average Mileage</span>
                            <h3 class="mb-0 mt-1 fw-bold text-primary">{{ $avgMileage ? number_format($avgMileage, 2) : '0.00' }} km/L</h3>
                            <small class="text-muted">Fleet Efficiency</small>
                        </div>
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="fa fa-gauge-high fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card shadow-sm mb-4">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label" for="filter_warehouse_id">Warehouse</label>
                    <select class="form-select" id="filter_warehouse_id">
                        <option value="">All Warehouses</option>
                        @foreach($warehouses as $wh)
                            <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="filter_driver_id">Driver</label>
                    <select class="form-select" id="filter_driver_id">
                        <option value="">All Drivers</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" data-warehouse-id="{{ $driver->warehouse_id }}" data-vehicle-id="{{ $driver->vehicle_id }}">{{ $driver->name }} ({{ $driver->phone ?? 'No Phone' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="filter_vehicle_id">Vehicle</label>
                    <select class="form-select" id="filter_vehicle_id">
                        <option value="">All Vehicles</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_no }} - {{ $vehicle->vehicle_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="filter_approval_status">Status</label>
                    <select class="form-select" id="filter_approval_status">
                        <option value="">All Status</option>
                        <option value="Approved">Approved</option>
                        <option value="Pending">Pending</option>
                        <option value="Rejected">Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="filter_fuel_type">Fuel Type</label>
                    <select class="form-select" id="filter_fuel_type">
                        <option value="">All Types</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Petrol">Petrol</option>
                        <option value="CNG">CNG</option>
                        <option value="Electric">Electric</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="filter_from_date">From Date</label>
                    <input type="date" class="form-control" id="filter_from_date" />
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="filter_to_date">To Date</label>
                    <input type="date" class="form-control" id="filter_to_date" />
                </div>
                <div class="col-md-6 d-flex align-items-end justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-secondary px-3" id="btn-reset">
                        <i class="fa fa-rotate-left me-1"></i> Reset
                    </button>
                    <button type="button" class="btn btn-primary px-4" id="btn-filter">
                        <i class="fa fa-filter me-1"></i> Apply Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- DataTable Card -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-transparent border-bottom d-flex justify-content-between align-items-center py-3">
                <h5 class="mb-0 fw-bold text-white"><i class="fa fa-table me-2 text-primary"></i> Fuel Tracking Master Logs</h5>
                <small class="text-white">Displaying fuel fill-ups across all warehouses & drivers (Read-Only Report)</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="admin-fuel-table" class="table table-hover table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Warehouse</th>
                                <th>Driver</th>
                                <th>Vehicle</th>
                                <th>Fuel Type</th>
                                <th>Quantity</th>
                                <th>Rate (₹)</th>
                                <th>Total Cost (₹)</th>
                                <th>Odometer</th>
                                <th>Mileage</th>
                                <th>Slip</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal for Viewing Slip Photo -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-file-invoice me-2 text-primary"></i> Fuel Slip / Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="modalSlipImg" src="" class="img-fluid rounded border shadow-sm" alt="Slip" style="max-height: 500px;" />
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    var table = $('#admin-fuel-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("admin-fleet-fuel-reports-datatable") }}',
            data: function (d) {
                d.warehouse_id = $('#filter_warehouse_id').val();
                d.driver_id = $('#filter_driver_id').val();
                d.vehicle_id = $('#filter_vehicle_id').val();
                d.fuel_type = $('#filter_fuel_type').val();
                d.approval_status = $('#filter_approval_status').val();
                d.from_date = $('#filter_from_date').val();
                d.to_date = $('#filter_to_date').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'fuel_date', name: 'fuel_date' },
            { data: 'warehouse_name', name: 'warehouse_name', orderable: false, searchable: false },
            { data: 'driver_info', name: 'driver.name' },
            { data: 'vehicle_info', name: 'vehicle.vehicle_no' },
            { data: 'fuel_type', name: 'fuel_type' },
            { data: 'quantity_liters', name: 'quantity_liters' },
            { data: 'rate_per_liter', name: 'rate_per_liter' },
            { data: 'total_amount', name: 'total_amount' },
            { data: 'odometer_reading', name: 'odometer_reading' },
            { data: 'mileage', name: 'mileage', orderable: false, searchable: false },
            { data: 'slip_image', name: 'slip_image', orderable: false, searchable: false },
            { data: 'approval_status', name: 'approval_status' }
        ]
    });

    // Cache all driver options for dynamic warehouse filtering
    var allDriverOptions = $('#filter_driver_id option').clone();

    // When Warehouse filter changes: auto-filter Drivers of that Warehouse
    $('#filter_warehouse_id').on('change', function () {
        var whId = $(this).val();

        $('#filter_driver_id').empty();
        allDriverOptions.each(function () {
            var opt = $(this);
            var optWh = opt.data('warehouse-id');
            if (!opt.val() || !whId || optWh == whId) {
                $('#filter_driver_id').append(opt.clone());
            }
        });

        $('#filter_driver_id').val('');
        $('#filter_vehicle_id').val('');
        table.ajax.reload();
    });

    // Auto-select Vehicle filter when Driver is selected
    $(document).on('change', '#filter_driver_id', function () {
        var opt = $(this).find('option:selected');
        var vId = opt.data('vehicle-id');
        if (vId) {
            $('#filter_vehicle_id').val(vId);
        } else if (!$(this).val()) {
            $('#filter_vehicle_id').val('');
        }
        table.ajax.reload();
    });

    $('#btn-filter').on('click', function () {
        var from = $('#filter_from_date').val();
        var to = $('#filter_to_date').val();
        if (from && to && from > to) {
            alert('From Date cannot be greater than To Date.');
            return;
        }
        table.ajax.reload();
    });

    $('#btn-reset').on('click', function () {
        $('#filter_warehouse_id').val('');
        
        // Restore all driver options
        $('#filter_driver_id').empty();
        allDriverOptions.each(function () {
            $('#filter_driver_id').append($(this).clone());
        });

        $('#filter_driver_id').val('');
        $('#filter_vehicle_id').val('');
        $('#filter_fuel_type').val('');
        $('#filter_approval_status').val('');
        $('#filter_from_date').val('');
        $('#filter_to_date').val('');
        table.ajax.reload();
    });

    // Image preview modal
    $(document).on('click', '.view-slip-btn', function (e) {
        e.preventDefault();
        var imgUrl = $(this).data('url');
        $('#modalSlipImg').attr('src', imgUrl);
        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    });
});
</script>
@endsection
