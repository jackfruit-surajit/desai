@extends('manufacture.layouts.main')

@section('title', 'Warehouse Stock View')
@section('breadcrumb-item', 'Stock Management')
@section('breadcrumb-item-active', 'Warehouse Stock View')

@section('css')
    <!-- [Page specific CSS] start -->
    <link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />
    <style>
        .stat-card {
            border-radius: 10px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.06);
        }
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0,0,0,0.08);
        }
        .nav-tabs .nav-link {
            font-weight: 600;
            color: #555;
            padding: 10px 20px;
        }
        .nav-tabs .nav-link.active {
            color: #04A9F5;
            border-bottom: 3px solid #04A9F5;
            background: transparent;
        }
        .filter-box {
            background-color: #f8fafc;
            border-radius: 8px;
            padding: 16px;
            border: 1px solid #e2e8f0;
        }
    </style>
    <!-- [Page specific CSS] end -->
@endsection

@section('content')

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="stockTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('manufacture-stock') }}">
                <i class="fa fa-industry me-1"></i> Factory Stock
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('manufacture-warehouse-stock') }}">
                <i class="fa fa-warehouse me-1"></i> Warehouse Stock View
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('manufacture-stock-logs') }}">
                <i class="fa fa-history me-1"></i> Stock Movement Logs
            </a>
        </li>
    </ul>

    <!-- Statistics Overview Cards -->
    <div class="row mb-3">
        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase mb-1 fw-bold" style="font-size: 11px;">Active Warehouses</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $totalWarehouses }}</h3>
                            <small class="text-muted">Stocked & active depots</small>
                        </div>
                        <div class="avatar-lg bg-light-primary rounded-circle p-3 text-center text-primary">
                            <i class="fa fa-warehouse fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase mb-1 fw-bold" style="font-size: 11px;">Available in Warehouses</p>
                            <h3 class="mb-0 fw-bold text-success">{{ number_format($totalWarehouseStock) }} <span style="font-size: 14px;">Cases</span></h3>
                            <small class="text-muted">Verified & ready for delivery</small>
                        </div>
                        <div class="avatar-lg bg-light-success rounded-circle p-3 text-center text-success">
                            <i class="fa fa-boxes-stacked fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase mb-1 fw-bold" style="font-size: 11px;">In-Transit / Pending</p>
                            <h3 class="mb-0 fw-bold text-info">{{ number_format($totalPendingStock) }} <span style="font-size: 14px;">Cases</span></h3>
                            <small class="text-muted">Awaiting warehouse receipt</small>
                        </div>
                        <div class="avatar-lg bg-light-info rounded-circle p-3 text-center text-info">
                            <i class="fa fa-truck-fast fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-xl-3 mb-3">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <p class="text-muted text-uppercase mb-1 fw-bold" style="font-size: 11px;">Stock Valuation</p>
                            <h3 class="mb-0 fw-bold text-warning">₹{{ number_format($totalStockValue, 2) }}</h3>
                            <small class="text-muted">Across {{ $totalSkusStocked }} stocked SKUs</small>
                        </div>
                        <div class="avatar-lg bg-light-warning rounded-circle p-3 text-center text-warning">
                            <i class="fa fa-indian-rupee-sign fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h5 class="mb-1 fw-bold">
                                <i class="fa fa-warehouse text-primary me-2"></i> Real-time Warehouse Inventory & Pipeline
                            </h5>
                            <small class="text-muted">Monitor verified physical stock and dispatched in-transit shipments across all warehouses.</small>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a href="{{ route('manufacture-product-create') }}" class="btn btn-primary me-2">
                                <i class="fa fa-paper-plane me-1"></i> Assign Products to Warehouse
                            </a>
                            <a href="{{ route('manufacture-stock') }}" class="btn btn-outline-secondary">
                                <i class="fa fa-industry me-1"></i> View Factory Stock
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- Filter Toolbar -->
                    <div class="filter-box mb-4">
                        <div class="row g-3 align-items-end">
                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    <i class="fa fa-warehouse me-1 text-primary"></i> Filter by Warehouse:
                                </label>
                                <select id="warehouse_filter" class="form-control form-select">
                                    <option value="">-- All Warehouses ({{ $warehouses->count() }}) --</option>
                                    @foreach($warehouses as $wh)
                                        <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    <i class="fa fa-box me-1 text-primary"></i> Filter by Product:
                                </label>
                                <select id="product_filter" class="form-control form-select">
                                    <option value="">-- All Products ({{ $products->count() }}) --</option>
                                    @foreach($products as $prod)
                                        <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    <i class="fa fa-filter me-1 text-primary"></i> Stock Status:
                                </label>
                                <select id="status_filter" class="form-control form-select">
                                    <option value="">-- All Stock Statuses --</option>
                                    <option value="in_stock">Available (> 50 Cases)</option>
                                    <option value="low_stock">Low Stock (1 - 50 Cases)</option>
                                    <option value="out_of_stock">Out of Stock (0 Cases)</option>
                                    <option value="pending">In-Transit / Pending Verification</option>
                                </select>
                            </div>

                            <div class="col-md-1">
                                <button type="button" class="btn btn-outline-secondary w-100" id="reset_filters" title="Reset All Filters">
                                    <i class="fa fa-rotate-right"></i> Reset
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="table-responsive dt-responsive">
                        <table class="table table-striped table-bordered align-middle nowrap" id="warehouse-stock-table" style="width:100%;">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" style="width: 4%;">#</th>
                                    <th scope="col">Warehouse</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Packaging</th>
                                    <th scope="col">Per Case Price</th>
                                    <th scope="col">Available Stock</th>
                                    <th scope="col">In-Transit</th>
                                    <th scope="col">Total Pipeline</th>
                                    <th scope="col">Stock Value</th>
                                    <th scope="col">Last Updated</th>
                                    <th scope="col" style="width: 10%;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ URL::asset(asset_path('backend/assets/js/jquery-confirm.js')) }}" type="text/javascript"></script>

    <script>
    $(document).ready(function () {
        var table = $('#warehouse-stock-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            order: [[1, 'asc'], [2, 'asc']],
            ajax: {
                url: '{{ route("manufacture-warehouse-stock-datatable") }}',
                data: function (d) {
                    d.warehouse_id = $('#warehouse_filter').val();
                    d.product_id   = $('#product_filter').val();
                    d.stock_status = $('#status_filter').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'warehouse_name', name: 'admins.name'},
                {data: 'product_name', name: 'products.name'},
                {data: 'bottles_per_case', name: 'products.per_case_quantity', searchable: false},
                {data: 'per_case_price', name: 'products.per_case_price', searchable: false},
                {data: 'current_stock', name: 'current_stock'},
                {data: 'pending_stock', name: 'pending_stock', searchable: false},
                {data: 'total_pipeline', name: 'total_pipeline', orderable: false, searchable: false},
                {data: 'stock_value', name: 'stock_value', orderable: false, searchable: false},
                {data: 'updated_date', name: 'warehouse_stock.updated_at', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ],
            language: {
                emptyTable: "No warehouse inventory or shipments found.",
                processing: '<div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div>'
            }
        });

        // Trigger filters
        $('#warehouse_filter, #product_filter, #status_filter').on('change', function () {
            table.ajax.reload();
        });

        // Reset filters
        $('#reset_filters').on('click', function () {
            $('#warehouse_filter').val('');
            $('#product_filter').val('');
            $('#status_filter').val('');
            table.ajax.reload();
        });
    });
    </script>
@endsection
