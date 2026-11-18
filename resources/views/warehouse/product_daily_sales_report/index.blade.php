@extends('warehouse.layouts.main')

@section('title', 'Product-wise Daily Sales Report')
@section('breadcrumb-item', 'Reports')
@section('breadcrumb-item-active', 'Product-wise Daily Sales Report')

@section('css')
    <link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />
    <style>
        .kpi-card {
            background: linear-gradient(135deg, rgba(16, 28, 76, 0.85), rgba(53, 66, 158, 0.75));
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 12px;
            padding: 20px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .kpi-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.25);
        }
        .kpi-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }

        .report-nav-tabs .nav-link {
            color: rgba(255, 255, 255, 0.7) !important;
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            background: rgba(16, 28, 76, 0.4) !important;
            margin-right: 8px;
            border-radius: 8px 8px 0 0;
            font-weight: 600;
            padding: 10px 18px;
            transition: all 0.2s;
        }
        .report-nav-tabs .nav-link.active {
            color: #ffffff !important;
            background: linear-gradient(135deg, #101C4C, #35429E) !important;
            border-bottom: 2px solid #3b82f6 !important;
        }
        .report-nav-tabs .nav-link:hover {
            color: #ffffff !important;
            background: rgba(53, 66, 158, 0.6) !important;
        }

        .table thead th,
        #product-report-table thead th {
            color: #ffffff !important;
            font-weight: 700 !important;
            background: linear-gradient(180deg, var(--desai-navy-deep, #101C4C), var(--desai-navy-light, #35429E)) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            vertical-align: middle !important;
        }

        #product-report-table tbody td,
        .table tbody td {
            background-color: rgba(16, 28, 76, 0.45) !important;
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            vertical-align: middle !important;
        }

        #product-report-table.table-striped > tbody > tr:nth-of-type(odd) > *,
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
        }

        #product-report-table tbody tr:hover > *,
        .table tbody tr:hover > * {
            background-color: rgba(255, 255, 255, 0.12) !important;
            color: #ffffff !important;
        }

        .filter-panel {
            background: rgba(16, 28, 76, 0.55);
            border: 1px solid rgba(255, 255, 255, 0.15);
            border-radius: 10px;
            padding: 18px;
        }

        .filter-panel label {
            color: #ffffff !important;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .filter-panel .form-control,
        .filter-panel .form-select {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
        }

        .filter-panel .form-select option {
            background-color: #101C4C !important;
            color: #ffffff !important;
        }

        .modal-content {
            background: #101C4C !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
        }
        .modal-header, .modal-footer {
            border-color: rgba(255, 255, 255, 0.15) !important;
        }
        .modal .form-control, .modal .form-select {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
        }
        .modal .table thead th {
            background: #1a2757 !important;
            color: #ffffff !important;
        }
        .modal .table tbody td {
            background: rgba(255, 255, 255, 0.04) !important;
            color: #ffffff !important;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                color: #000 !important;
            }
            .no-print {
                display: none !important;
            }
            .kpi-card {
                background: #f8fafc !important;
                border: 1px solid #cbd5e1 !important;
                color: #0f172a !important;
            }
            .table thead th, #product-report-table thead th {
                background: #e2e8f0 !important;
                color: #0f172a !important;
            }
            #product-report-table tbody td {
                background: #ffffff !important;
                color: #0f172a !important;
            }
        }
    </style>
@endsection

@section('content')

    {{-- Flash Notifications --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error_msg'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-triangle-exclamation me-1"></i> {{ session('error_msg') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Quick View Switching Tabs --}}
    <div class="d-flex align-items-center justify-content-between mb-3 no-print">
        <ul class="nav report-nav-tabs">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('warehouse-daily-sales-report', ['date' => $reportDate]) }}">
                    <i class="fa fa-user-tie me-1"></i> Driver-wise Daily Sales
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('warehouse-product-daily-sales-report', ['date' => $reportDate]) }}">
                    <i class="fa fa-boxes-stacked me-1"></i> Product-wise Daily Sales
                </a>
            </li>
        </ul>


    </div>

    {{-- Filter Panel --}}
    <div class="filter-panel mb-4 no-print">
        <form method="GET" action="{{ route('warehouse-product-daily-sales-report') }}" id="reportFilterForm">
            <div class="row align-items-end g-3">
                <div class="col-md-3">
                    <label for="report_date"><i class="fa fa-calendar-days me-1"></i> Select Date</label>
                    <input type="date"
                           name="date"
                           id="report_date"
                           class="form-control"
                           value="{{ $reportDate }}"
                           max="{{ date('Y-m-d') }}">
                </div>

                <div class="col-md-3">
                    <label for="product_id"><i class="fa fa-box me-1"></i> Filter by Product</label>
                    <select name="product_id" id="product_id" class="form-select">
                        <option value="">All Catalog Products</option>
                        @foreach($allCatalogProducts as $p)
                            <option value="{{ $p->id }}" {{ (string)$selectedProductId === (string)$p->id ? 'selected' : '' }}>
                                {{ $p->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="view_mode"><i class="fa fa-filter me-1"></i> Display Products</label>
                    <select name="view_mode" id="view_mode" class="form-select">
                        <option value="active" {{ $viewMode === 'active' ? 'selected' : '' }}>Active Products Only (With Sales/Assignments)</option>
                        <option value="all" {{ $viewMode === 'all' ? 'selected' : '' }}>All Catalog Products</option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="fa fa-filter me-1"></i> View Report
                    </button>
                    <button type="button" class="btn btn-outline-light" onclick="setDatePreset('{{ date('Y-m-d') }}')">
                        Today
                    </button>
                    <button type="button" class="btn btn-outline-light" onclick="setDatePreset('{{ date('Y-m-d', strtotime('-1 day')) }}')">
                        Yesterday
                    </button>
                    <a href="{{ route('warehouse-product-daily-sales-report') }}" class="btn btn-outline-secondary" title="Reset Filters">
                        <i class="fa fa-arrows-rotate"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Printable Report Container --}}
    <div id="printableArea">

        {{-- Print Header --}}
        <div class="d-none d-print-block mb-4 text-center border-bottom pb-3">
            <h2 class="mb-1 text-dark">Desai Beverages - Product-wise Daily Sales Report</h2>
            <p class="mb-0 text-muted">
                <strong>Warehouse:</strong> {{ Auth::guard('backend')->user()->name }} |
                <strong>Date:</strong> {{ date('d F, Y', strtotime($reportDate)) }} |
                <strong>Generated At:</strong> {{ date('d-M-Y h:i A') }}
            </p>
        </div>

        {{-- KPI Cards Row --}}
        <div class="row g-3 mb-4">
            {{-- KPI 1: Active Products --}}
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card text-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Active Products</span>
                            <h3 class="fw-bold mt-2 mb-0 text-white">{{ $activeProductsCount }}</h3>
                            <small class="text-white-50">of {{ $allCatalogProducts->count() }} catalog items</small>
                        </div>
                        <div class="kpi-icon" style="background: rgba(59, 130, 246, 0.2); color: #60a5fa;">
                            <i class="fa fa-boxes-stacked"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI 2: Total Assigned --}}
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card text-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Assigned Stock</span>
                            <h4 class="fw-bold mt-2 mb-0 text-info">{{ $overallAssignedText }}</h4>
                            <small class="text-white-50">{{ $overallTotalAssignedBottles }} total bottles</small>
                        </div>
                        <div class="kpi-icon" style="background: rgba(6, 182, 212, 0.2); color: #22d3ee;">
                            <i class="fa fa-truck-ramp-box"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI 3: Total Sold --}}
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card text-white h-100">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Sold Quantity</span>
                            <h4 class="fw-bold mt-2 mb-0 text-primary" style="color: #60a5fa !important;">{{ $overallSoldText }}</h4>
                            <small class="text-white-50">{{ $overallTotalSoldBottles }} total bottles sold</small>
                        </div>
                        <div class="kpi-icon" style="background: rgba(37, 99, 235, 0.2); color: #93c5fd;">
                            <i class="fa fa-cart-shopping"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KPI 4: Total Revenue --}}
            <div class="col-md-3 col-sm-6">
                <div class="kpi-card text-white h-100" style="background: linear-gradient(135deg, rgba(16, 100, 50, 0.85), rgba(20, 150, 80, 0.75));">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Sales Revenue</span>
                            <h3 class="fw-bold mt-2 mb-0 text-white">₹ {{ number_format($overallSalesAmount, 2) }}</h3>
                            <small class="text-white-50">{{ $overallReturnedText }} returned</small>
                        </div>
                        <div class="kpi-icon" style="background: rgba(34, 197, 94, 0.2); color: #4ade80;">
                            <i class="fa fa-indian-rupee-sign"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Report Table Card --}}
        <div class="row">
            <div class="col-12">
                <div class="card" style="background: rgba(16, 28, 76, 0.55); border: 1px solid rgba(255, 255, 255, 0.15);">
                    <div class="card-header d-flex align-items-center justify-content-between py-3" style="border-bottom: 1px solid rgba(255, 255, 255, 0.15);">
                        <div>
                            <h5 class="card-title text-white mb-0">
                                <i class="fa fa-table-list me-2"></i> Product-wise Daily Sales Breakdown
                            </h5>
                            <small class="text-white-50">
                                Date: <strong>{{ date('d F, Y', strtotime($reportDate)) }}</strong> |
                                Warehouse: <strong>{{ Auth::guard('backend')->user()->name }}</strong>
                                ({{ count($productReports) }} product{{ count($productReports) != 1 ? 's' : '' }} shown)
                            </small>
                        </div>
                        <div class="no-print d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white px-2 py-1">
                                <i class="fa fa-user me-1"></i> {{ $warehouseDrivers->count() }} Warehouse Drivers
                            </span>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover mb-0" id="product-report-table">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">#</th>
                                        <th>Product Name</th>
                                        <th>Packaging</th>
                                        <th>Warehouse Stock</th>
                                        <th>Total Assigned</th>
                                        <th>Total Returned</th>
                                        <th>Total Sold</th>
                                        <th>Unit Price</th>
                                        <th>Sales Revenue</th>
                                        <th>Active Drivers</th>
                                        <th class="no-print" style="width: 120px;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($productReports as $index => $report)
                                        <tr>
                                            <td>{{ $index + 1 }}</td>

                                            {{-- Product Name & HSN --}}
                                            <td>
                                                <strong class="text-white">{{ $report['product_name'] }}</strong>
                                                <div class="text-white-50" style="font-size: 11px;">
                                                    HSN: {{ $report['hsn_code'] }}
                                                </div>
                                            </td>

                                            {{-- Packaging --}}
                                            <td>
                                                <span class="badge bg-secondary text-white">
                                                    {{ $report['bottles_per_case'] }} btls / case
                                                </span>
                                            </td>

                                            {{-- Warehouse Stock --}}
                                            <td>
                                                <span class="text-white-50 fw-semibold">
                                                    {{ $report['current_stock_text'] }}
                                                </span>
                                            </td>

                                            {{-- Total Assigned --}}
                                            <td>
                                                @if($report['assigned_total_bottles'] > 0)
                                                    <span class="badge bg-info text-dark fw-bold px-2 py-1" style="font-size: 12px;">
                                                        <i class="fa fa-truck-ramp-box me-1"></i> {{ $report['assigned_text'] }}
                                                    </span>
                                                    <div class="text-white-50" style="font-size: 11px;">
                                                        {{ $report['assigned_total_bottles'] }} bottles
                                                    </div>
                                                @else
                                                    <span class="text-white-50">0 Cases</span>
                                                @endif
                                            </td>

                                            {{-- Total Returned --}}
                                            <td>
                                                @if($report['returned_total_bottles'] > 0)
                                                    <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 12px;">
                                                        <i class="fa fa-arrow-rotate-left me-1"></i> {{ $report['returned_text'] }}
                                                    </span>
                                                    <div class="text-white-50" style="font-size: 11px;">
                                                        {{ $report['returned_total_bottles'] }} bottles
                                                    </div>
                                                @else
                                                    <span class="text-white-50">0 Cases</span>
                                                @endif
                                            </td>

                                            {{-- Total Sold --}}
                                            <td>
                                                @if($report['sold_total_bottles'] > 0)
                                                    <span class="badge bg-primary text-white fw-bold px-2 py-1" style="font-size: 12px;">
                                                        <i class="fa fa-cart-shopping me-1"></i> {{ $report['sold_text'] }}
                                                    </span>
                                                    <div class="text-white-50" style="font-size: 11px;">
                                                        {{ $report['sold_total_bottles'] }} bottles
                                                    </div>
                                                @else
                                                    <span class="text-white-50">0 Cases</span>
                                                @endif
                                            </td>

                                            {{-- Unit Price --}}
                                            <td>
                                                <div class="text-white fw-semibold">
                                                    ₹ {{ number_format($report['bottle_price'], 2) }} <small class="text-white-50">/ btl</small>
                                                </div>
                                                <div class="text-white-50" style="font-size: 11px;">
                                                    ₹ {{ number_format($report['case_price'], 2) }} / case
                                                </div>
                                            </td>

                                            {{-- Total Sales Revenue --}}
                                            <td>
                                                <strong class="text-success fs-6">
                                                    ₹ {{ number_format($report['sales_amount'], 2) }}
                                                </strong>
                                            </td>

                                            {{-- Active Drivers Count --}}
                                            <td>
                                                @if($report['active_drivers_count'] > 0)
                                                    <span class="badge bg-light text-dark fw-bold">
                                                        {{ $report['active_drivers_count'] }} Driver{{ $report['active_drivers_count'] != 1 ? 's' : '' }}
                                                    </span>
                                                @else
                                                    <span class="text-white-50">-</span>
                                                @endif
                                            </td>

                                            {{-- Action Button --}}
                                            <td class="no-print">
                                                <button type="button"
                                                        class="btn btn-outline-info btn-sm btn-driver-breakdown"
                                                        data-product-id="{{ $report['product_id'] }}"
                                                        data-product-name="{{ $report['product_name'] }}"
                                                        title="View driver-by-driver breakdown">
                                                    <i class="fa fa-users-viewfinder me-1"></i> Drivers
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="11" class="text-center text-muted py-4">
                                                <i class="fa fa-circle-info me-1"></i>
                                                @if($warehouseDrivers->isEmpty())
                                                    No drivers are currently assigned to this warehouse.
                                                @else
                                                    No product sales or assignments found for {{ date('d F, Y', strtotime($reportDate)) }}.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr style="background: rgba(53, 66, 158, 0.45); font-weight: bold;">
                                        <td colspan="4" class="text-white text-uppercase">Overall Warehouse Total</td>
                                        <td class="text-info">{{ $overallAssignedText }}</td>
                                        <td class="text-warning">{{ $overallReturnedText }}</td>
                                        <td class="text-primary" style="color: #93c5fd !important;">{{ $overallSoldText }}</td>
                                        <td>-</td>
                                        <td class="text-success fs-5">₹ {{ number_format($overallSalesAmount, 2) }}</td>
                                        <td colspan="2" class="no-print"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Driver Breakdown Modal --}}
    <div class="modal fade" id="driverBreakdownModal" tabindex="-1" aria-labelledby="driverBreakdownModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title text-white" id="driverBreakdownModalLabel">
                            <i class="fa fa-users-viewfinder me-2 text-info"></i> Driver Breakdown for Product
                        </h5>
                        <small class="text-white-50" id="modalSubTitle">Date: {{ date('d F, Y', strtotime($reportDate)) }}</small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div id="breakdownLoading" class="text-center py-5">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="text-white-50 mt-2">Loading driver breakdown data...</p>
                    </div>
                    <div id="breakdownContent" style="display: none;">
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <thead>
                                    <tr>
                                        <th>Driver Name</th>
                                        <th>Assigned Qty</th>
                                        <th>Returned Qty</th>
                                        <th>Sold Qty</th>
                                        <th>Revenue (₹)</th>
                                        <th>Deliveries</th>
                                    </tr>
                                </thead>
                                <tbody id="breakdownTableBody">
                                    {{-- Dynamically populated via AJAX --}}
                                </tbody>
                                <tfoot>
                                    <tr style="background: rgba(53, 66, 158, 0.45); font-weight: bold;">
                                        <td class="text-white text-uppercase">Total</td>
                                        <td id="totAssigned" class="text-info">-</td>
                                        <td id="totReturned" class="text-warning">-</td>
                                        <td id="totSold" class="text-primary" style="color: #93c5fd !important;">-</td>
                                        <td id="totRevenue" class="text-success fs-6">-</td>
                                        <td>-</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
<script>
    function setDatePreset(dateStr) {
        document.getElementById('report_date').value = dateStr;
        document.getElementById('reportFilterForm').submit();
    }

    $(document).ready(function() {
        // Driver Breakdown Modal Handler
        $(document).on('click', '.btn-driver-breakdown', function() {
            let productId = $(this).data('product-id');
            let productName = $(this).data('product-name');
            let date = $('#report_date').val();

            $('#modalSubTitle').html('<strong>' + productName + '</strong> | Date: ' + date);
            $('#breakdownLoading').show();
            $('#breakdownContent').hide();
            $('#driverBreakdownModal').modal('show');

            let url = '{{ route("warehouse-product-daily-sales-report-breakdown", [":product_id", ":date"]) }}';
            url = url.replace(':product_id', productId).replace(':date', date);

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(res) {
                    $('#breakdownLoading').hide();
                    $('#breakdownContent').show();

                    if (res.status === 200 && res.items.length > 0) {
                        let rows = '';
                        $.each(res.items, function(idx, item) {
                            let verifiedBadge = item.has_manual_return 
                                ? '<small class="badge bg-success ms-1">Verified</small>' 
                                : '';
                            rows += '<tr>' +
                                '<td><strong class="text-white">' + item.driver_name + '</strong></td>' +
                                '<td><span class="badge bg-info text-dark">' + item.assigned_text + '</span></td>' +
                                '<td><span class="badge bg-warning text-dark">' + item.returned_text + '</span>' + verifiedBadge + '</td>' +
                                '<td><span class="badge bg-primary text-white">' + item.sold_text + '</span></td>' +
                                '<td><strong class="text-success">₹ ' + item.sales_amount + '</strong></td>' +
                                '<td><span class="badge bg-light text-dark">' + item.invoices_count + ' Invoices</span></td>' +
                            '</tr>';
                        });

                        $('#breakdownTableBody').html(rows);
                        $('#totAssigned').text(res.totals.assigned_text);
                        $('#totReturned').text(res.totals.returned_text);
                        $('#totSold').text(res.totals.sold_text);
                        $('#totRevenue').text(res.totals.sales_amount);
                    } else {
                        $('#breakdownTableBody').html('<tr><td colspan="6" class="text-center text-muted py-4">No driver activity found for this product on this date.</td></tr>');
                        $('#totAssigned').text('0 Cases');
                        $('#totReturned').text('0 Cases');
                        $('#totSold').text('0 Cases');
                        $('#totRevenue').text('₹ 0.00');
                    }
                },
                error: function(xhr) {
                    $('#breakdownLoading').hide();
                    $('#breakdownContent').show();
                    $('#breakdownTableBody').html('<tr><td colspan="6" class="text-center text-danger py-4">Failed to load breakdown details.</td></tr>');
                }
            });
        });
    });
</script>
@endsection
