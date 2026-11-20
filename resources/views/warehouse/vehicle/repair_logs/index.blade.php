@extends('warehouse.layouts.main')

@section('title', 'Vehicle Repair & Service Costs')
@section('breadcrumb-item', 'Fleet Management')
@section('breadcrumb-item-active', 'Repair & Maintenance Costs')

@section('css')
    <link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />
    <style>
        .filter-card {
            background: rgba(16, 28, 76, 0.45);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 8px;
            padding: 16px;
        }
        .form-label {
            margin-bottom: 6px;
            color: #ffffff !important;
            font-weight: 600;
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
        .table thead th, #repair-logs-table thead th {
            color: #ffffff !important;
            font-weight: 700 !important;
            background: linear-gradient(180deg, var(--desai-navy-deep, #101C4C), var(--desai-navy-light, #35429E)) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            vertical-align: middle !important;
        }
        #repair-logs-table tbody td, .table tbody td {
            background-color: rgba(16, 28, 76, 0.45) !important;
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            vertical-align: middle !important;
        }
        #repair-logs-table.table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
        }
        .kpi-card {
            background: linear-gradient(135deg, rgba(16, 28, 76, 0.75), rgba(53, 66, 158, 0.65));
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 16px;
        }
        /* Card Title & DataTables Controls in White */
        .card-title, .card-header, .card-header h5 {
            color: #ffffff !important;
        }
        .dataTables_wrapper,
        .dataTables_wrapper label,
        .dataTables_length,
        .dataTables_length label,
        .dataTables_filter,
        .dataTables_filter label,
        .dataTables_info,
        .dataTables_empty,
        .dataTables_processing {
            color: #ffffff !important;
        }
        .dataTables_filter input,
        .dataTables_length select {
            color: #ffffff !important;
            background-color: rgba(255, 255, 255, 0.12) !important;
            border: 1px solid rgba(255, 255, 255, 0.3) !important;
        }
        .dataTables_length select option {
            background-color: #101C4C !important;
            color: #ffffff !important;
        }
    </style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        @include('warehouse.vehicle.partials.nav-tabs')

        <!-- KPI Summary Cards -->
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-white-50 text-uppercase small fw-bold">Total Repair Cost</span>
                            <h3 class="mb-0 mt-1 fw-bold text-danger">₹{{ number_format($totalRepairCost, 2) }}</h3>
                        </div>
                        <div class="p-3 bg-danger bg-opacity-25 rounded-circle">
                            <i class="fa fa-wrench fa-2x text-danger"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-white-50 text-uppercase small fw-bold">Spare Parts Cost</span>
                            <h3 class="mb-0 mt-1 fw-bold text-warning">₹{{ number_format($totalPartsCost, 2) }}</h3>
                        </div>
                        <div class="p-3 bg-warning bg-opacity-25 rounded-circle">
                            <i class="fa fa-cogs fa-2x text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-white-50 text-uppercase small fw-bold">Labor Charges</span>
                            <h3 class="mb-0 mt-1 fw-bold text-info">₹{{ number_format($totalLaborCost, 2) }}</h3>
                        </div>
                        <div class="p-3 bg-info bg-opacity-25 rounded-circle">
                            <i class="fa fa-user-gear fa-2x text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-white-50 text-uppercase small fw-bold">Unpaid Invoices</span>
                            <h3 class="mb-0 mt-1 fw-bold text-white">₹{{ number_format($unpaidRepairs, 2) }}</h3>
                        </div>
                        <div class="p-3 bg-secondary bg-opacity-25 rounded-circle">
                            <i class="fa fa-file-invoice-dollar fa-2x text-white"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="filter-card mb-4">
            <div class="row g-3">

                <div class="col-md-3">
                    <label class="form-label" for="filter_driver_id">Driver</label>
                    <select class="form-select" id="filter_driver_id">
                        <option value="">All Drivers</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" data-vehicle-id="{{ $driver->vehicle_id }}">{{ $driver->name }} ({{ $driver->phone }})</option>
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
                
                <div class="col-md-2">
                    <label class="form-label" for="filter_payment_status">Payment Status</label>
                    <select class="form-select" id="filter_payment_status">
                        <option value="">All Status</option>
                        <option value="Paid">Paid</option>
                        <option value="Partially Paid">Partially Paid</option>
                        <option value="Unpaid">Unpaid</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="filter_from_date">From Date</label>
                    <input type="date" class="form-control" id="filter_from_date" />
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="filter_to_date">To Date</label>
                    <input type="date" class="form-control" id="filter_to_date" />
                </div>
                <div class="col-12 d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-outline-light" id="btn-reset">
                        <i class="fa fa-rotate-left me-1"></i> Reset
                    </button>
                    <button type="button" class="btn btn-primary" id="btn-filter">
                        <i class="fa fa-filter me-1"></i> Apply Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table Card -->
        <div class="card shadow-sm" style="background: rgba(16, 28, 76, 0.45); border: 1px solid rgba(255, 255, 255, 0.15);">
            <div class="card-header border-bottom border-light border-opacity-10 d-flex justify-content-between align-items-center">
                <h5 class="card-title text-white mb-0"><i class="fa fa-wrench me-2"></i> Vehicle Repair & Maintenance Records</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="repair-logs-table" class="table table-bordered table-striped align-middle w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Repair Date</th>
                                <th>Vehicle</th>
                                <th>Driver</th>
                                <th>Workshop / Garage</th>
                                <th>Reason</th>
                                <th>Parts / Labor</th>
                                <th>Total Cost</th>
                                <th>Odometer</th>
                                <th>Bill</th>
                                <th>Payment</th>
                                <th>Action</th>
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
        <div class="modal-content" style="background: #101C4C; border: 1px solid rgba(255, 255, 255, 0.2);">
            <div class="modal-header border-bottom border-light border-opacity-25">
                <h5 class="modal-title text-white">Repair Invoice / Bill Copy</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="modalRepairImg" src="" class="img-fluid rounded border" alt="Bill" style="max-height: 500px;" />
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset(asset_path('backend/assets/js/jquery-confirm.js')) }}" type="text/javascript"></script>
<script>
$(document).ready(function () {
    var table = $('#repair-logs-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("warehouse-vehicle-repair-logs-datatable") }}',
            data: function (d) {
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
            { data: 'vehicle_info', name: 'vehicle.vehicle_no' },
            { data: 'driver_name', name: 'driver.name' },
            { data: 'garage_workshop_name', name: 'garage_workshop_name' },
            { data: 'repair_reason', name: 'repair_reason' },
            { data: 'cost_breakdown', name: 'cost_breakdown', orderable: false, searchable: false },
            { data: 'total_cost', name: 'total_cost' },
            { data: 'odometer_at_repair', name: 'odometer_at_repair' },
            { data: 'bill_copy_image', name: 'bill_copy_image', orderable: false, searchable: false },
            { data: 'payment_status', name: 'payment_status' },
            { data: 'action', name: 'action', orderable: false, searchable: false }
        ]
    });

    // Auto-select Vehicle filter when Driver is selected
    $('#filter_driver_id').on('change', function () {
        var opt = $(this).find('option:selected');
        var vId = opt.data('vehicle-id');
        if (vId) {
            $('#filter_vehicle_id').val(vId);
        } else if (!$(this).val()) {
            $('#filter_vehicle_id').val('');
        }
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

    $(document).on('click', '.delete-repair-btn', function () {
        var url = $(this).data('url');
        $.confirm({
            title: 'Delete Repair Record?',
            content: 'Are you sure you want to delete this vehicle repair record?',
            type: 'red',
            buttons: {
                confirm: {
                    text: 'Yes, Delete',
                    btnClass: 'btn-danger',
                    action: function () {
                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function (res) {
                                table.ajax.reload(null, false);
                                toastr.success(res.message || 'Repair entry deleted successfully.');
                            },
                            error: function () {
                                toastr.error('Failed to delete repair entry.');
                            }
                        });
                    }
                },
                cancel: function () {}
            }
        });
    });
});
</script>
@endsection
