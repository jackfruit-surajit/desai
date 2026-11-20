@extends('admin.layouts.main')

@section('title', 'Fleet Repair & Maintenance Reports')
@section('breadcrumb-item', 'Fleet Management')
@section('breadcrumb-item-active', 'Repair & Maintenance Reports')

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
                            <span class="text-muted text-uppercase small fw-bold">Total Repair Cost</span>
                            <h3 class="mb-0 mt-1 fw-bold text-danger">₹{{ number_format($totalRepairCost, 2) }}</h3>
                            <small class="text-muted">All Workshop Jobs</small>
                        </div>
                        <div class="p-3 bg-danger bg-opacity-10 text-danger rounded-3">
                            <i class="fa fa-wrench fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Spare Parts Cost</span>
                            <h3 class="mb-0 mt-1 fw-bold text-warning">₹{{ number_format($totalPartsCost, 2) }}</h3>
                            <small class="text-muted">Component Replacements</small>
                        </div>
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3">
                            <i class="fa fa-cogs fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Labor Charges</span>
                            <h3 class="mb-0 mt-1 fw-bold text-info">₹{{ number_format($totalLaborCost, 2) }}</h3>
                            <small class="text-muted">Service & Mechanic Fees</small>
                        </div>
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-3">
                            <i class="fa fa-user-gear fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Unpaid Garage Bills</span>
                            <h3 class="mb-0 mt-1 fw-bold text-dark">₹{{ number_format($unpaidRepairs, 2) }}</h3>
                            <small class="text-muted">Pending Invoices</small>
                        </div>
                        <div class="p-3 bg-secondary bg-opacity-10 text-secondary rounded-3">
                            <i class="fa fa-file-invoice-dollar fa-2x"></i>
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
                
                <div class="col-md-3">
                    <label class="form-label" for="filter_vehicle_id">Vehicle</label>
                    <select class="form-select" id="filter_vehicle_id">
                        <option value="">All Vehicles</option>
                        @foreach($vehicles as $vehicle)
                            <option value="{{ $vehicle->id }}">{{ $vehicle->vehicle_no }} - {{ $vehicle->vehicle_name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label class="form-label" for="filter_payment_status">Payment Status</label>
                    <select class="form-select" id="filter_payment_status">
                        <option value="">All Status</option>
                        <option value="Paid">Paid</option>
                        <option value="Partially Paid">Partially Paid</option>
                        <option value="Unpaid">Unpaid</option>
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
                <h5 class="mb-0 fw-bold text-white"><i class="fa fa-wrench me-2 text-primary"></i> Vehicle Repair & Maintenance Master Reports</h5>
                <small class="text-white">Displaying periodic service, repairs & bills across all vehicles (Read-Only Report)</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="admin-repair-table" class="table table-hover table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Repair Date</th>
                                <th>Warehouse</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Workshop / Garage</th>
                                <th>Reason</th>
                                <th>Parts / Labor</th>
                                <th>Total Cost (₹)</th>
                                <th>Odometer</th>
                                <th>Bill</th>
                                <th>Payment</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal for Viewing Bill Copy Photo -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-file-invoice me-2 text-primary"></i> Repair Invoice / Bill Copy</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="modalRepairImg" src="" class="img-fluid rounded border shadow-sm" alt="Bill" style="max-height: 500px;" />
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    var table = $('#admin-repair-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("admin-fleet-repair-reports-datatable") }}',
            data: function (d) {
                d.warehouse_id = $('#filter_warehouse_id').val();
                d.vehicle_id = $('#filter_vehicle_id').val();
                d.driver_id = $('#filter_driver_id').val();
                d.payment_status = $('#filter_payment_status').val();
                d.from_date = $('#filter_from_date').val();
                d.to_date = $('#filter_to_date').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'repair_date', name: 'repair_date' },
            { data: 'warehouse_name', name: 'warehouse_name', orderable: false, searchable: false },
            { data: 'vehicle_info', name: 'vehicle.vehicle_no' },
            { data: 'driver_info', name: 'driver.name' },
            { data: 'garage_workshop_name', name: 'garage_workshop_name' },
            { data: 'repair_reason', name: 'repair_reason' },
            { data: 'cost_breakdown', name: 'cost_breakdown', orderable: false, searchable: false },
            { data: 'total_cost', name: 'total_cost' },
            { data: 'odometer_at_repair', name: 'odometer_at_repair' },
            { data: 'bill_copy_image', name: 'bill_copy_image', orderable: false, searchable: false },
            { data: 'payment_status', name: 'payment_status' }
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

        $('#filter_vehicle_id').val('');
        $('#filter_driver_id').val('');
        $('#filter_payment_status').val('');
        $('#filter_from_date').val('');
        $('#filter_to_date').val('');
        table.ajax.reload();
    });

    $(document).on('click', '.view-slip-btn', function (e) {
        e.preventDefault();
        var imgUrl = $(this).data('url');
        $('#modalRepairImg').attr('src', imgUrl);
        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    });
});
</script>
@endsection
