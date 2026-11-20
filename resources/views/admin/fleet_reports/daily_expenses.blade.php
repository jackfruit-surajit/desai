@extends('admin.layouts.main')

@section('title', 'Fleet Daily Expense Reports')
@section('breadcrumb-item', 'Fleet Management')
@section('breadcrumb-item-active', 'Daily Expense Reports')

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
                            <span class="text-muted text-uppercase small fw-bold">Total Daily Expenses</span>
                            <h3 class="mb-0 mt-1 fw-bold text-success">₹{{ number_format($totalExpenses, 2) }}</h3>
                            <small class="text-muted">All Categories & Warehouses</small>
                        </div>
                        <div class="p-3 bg-success bg-opacity-10 text-success rounded-3">
                            <i class="fa fa-wallet fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Approved / Settled</span>
                            <h3 class="mb-0 mt-1 fw-bold text-info">₹{{ number_format($approvedExpenses, 2) }}</h3>
                            <small class="text-muted">Reimbursed & Paid</small>
                        </div>
                        <div class="p-3 bg-info bg-opacity-10 text-info rounded-3">
                            <i class="fa fa-circle-check fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">Pending Review</span>
                            <h3 class="mb-0 mt-1 fw-bold text-warning">₹{{ number_format($pendingExpenses, 2) }}</h3>
                            <small class="text-muted">Awaiting Verification</small>
                        </div>
                        <div class="p-3 bg-warning bg-opacity-10 text-warning rounded-3">
                            <i class="fa fa-clock-rotate-left fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card shadow-sm">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted text-uppercase small fw-bold">This Month Cost</span>
                            <h3 class="mb-0 mt-1 fw-bold text-primary">₹{{ number_format($thisMonthExpenses, 2) }}</h3>
                            <small class="text-muted">{{ date('F Y') }}</small>
                        </div>
                        <div class="p-3 bg-primary bg-opacity-10 text-primary rounded-3">
                            <i class="fa fa-calendar-day fa-2x"></i>
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
                            <option value="{{ $driver->id }}" data-vehicle-id="{{ $driver->vehicle_id }}">{{ $driver->name }} ({{ $driver->phone ?? 'No Phone' }})</option>
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
                    <label class="form-label" for="filter_category">Category</label>
                    <select class="form-select" id="filter_category">
                        <option value="">All Categories</option>
                        <option value="Toll">Toll Tax</option>
                        <option value="Parking">Parking Charges</option>
                        <option value="Washing">Vehicle Washing</option>
                        <option value="Driver Allowance">Driver Food / Allowance</option>
                        <option value="Puncture/Air">Puncture / Tyre Air</option>
                        <option value="Minor Repair">Minor Repair</option>
                        <option value="Other">Other Expense</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label" for="filter_status">Status</label>
                    <select class="form-select" id="filter_status">
                        <option value="">All Status</option>
                        <option value="Approved">Approved</option>
                        <option value="Paid">Paid</option>
                        <option value="Pending">Pending</option>
                        <option value="Rejected">Rejected</option>
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
                <h5 class="mb-0 fw-bold text-white"><i class="fa fa-receipt me-2 text-primary"></i> Daily Vehicle Expense Master Reports</h5>
                <small class="text-white">Displaying toll, parking, maintenance & allowances across all warehouses (Read-Only Report)</small>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="admin-expenses-table" class="table table-hover table-bordered align-middle w-100">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Warehouse</th>
                                <th>Driver</th>
                                <th>Vehicle</th>
                                <th>Category</th>
                                <th>Amount (₹)</th>
                                <th>Description</th>
                                <th>Receipt</th>
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

<!-- Modal for Viewing Receipt Photo -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fa fa-file-invoice me-2 text-primary"></i> Expense Bill / Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-3">
                <img id="modalBillImg" src="" class="img-fluid rounded border shadow-sm" alt="Bill" style="max-height: 500px;" />
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    var table = $('#admin-expenses-table').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: '{{ route("admin-fleet-daily-expenses-datatable") }}',
            data: function (d) {
                d.warehouse_id = $('#filter_warehouse_id').val();
                d.driver_id = $('#filter_driver_id').val();
                d.vehicle_id = $('#filter_vehicle_id').val();
                d.expense_category = $('#filter_category').val();
                d.reimbursement_status = $('#filter_status').val();
                d.from_date = $('#filter_from_date').val();
                d.to_date = $('#filter_to_date').val();
            }
        },
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'expense_date', name: 'expense_date' },
            { data: 'warehouse_name', name: 'warehouse_name', orderable: false, searchable: false },
            { data: 'driver_info', name: 'driver.name' },
            { data: 'vehicle_info', name: 'vehicle.vehicle_no' },
            { data: 'expense_category', name: 'expense_category' },
            { data: 'amount', name: 'amount' },
            { data: 'description', name: 'description' },
            { data: 'bill_receipt_image', name: 'bill_receipt_image', orderable: false, searchable: false },
            { data: 'reimbursement_status', name: 'reimbursement_status' }
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
        $('#filter_warehouse_id').val('');
        $('#filter_driver_id').val('');
        $('#filter_vehicle_id').val('');
        $('#filter_category').val('');
        $('#filter_status').val('');
        $('#filter_from_date').val('');
        $('#filter_to_date').val('');
        table.ajax.reload();
    });

    $(document).on('click', '.view-slip-btn', function (e) {
        e.preventDefault();
        var imgUrl = $(this).data('url');
        $('#modalBillImg').attr('src', imgUrl);
        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    });
});
</script>
@endsection
