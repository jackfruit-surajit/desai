@extends('warehouse.layouts.main')

@section('title', 'Daily Driver-wise Sales Report')
@section('breadcrumb-item', 'Reports')
@section('breadcrumb-item-active', 'Daily Driver-wise Sales Report')

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
        #daily-report-table thead th {
            color: #ffffff !important;
            font-weight: 700 !important;
            background: linear-gradient(180deg, var(--desai-navy-deep, #101C4C), var(--desai-navy-light, #35429E)) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            vertical-align: middle !important;
        }

        #daily-report-table tbody td,
        .table tbody td {
            background-color: rgba(16, 28, 76, 0.45) !important;
            color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            vertical-align: middle !important;
        }

        #daily-report-table.table-striped > tbody > tr:nth-of-type(odd) > *,
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: rgba(255, 255, 255, 0.05) !important;
            color: #ffffff !important;
        }

        #daily-report-table tbody tr:hover > *,
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

        /* Select2 inside Return Modal */
        .select2-container {
            width: 100% !important;
        }
        #returnModal .select2-container--default .select2-selection--single {
            background-color: rgba(255, 255, 255, 0.08) !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            height: 38px !important;
            border-radius: 6px !important;
        }
        #returnModal .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #ffffff !important;
            line-height: 36px !important;
            padding-left: 12px !important;
            font-size: 13px !important;
        }
        #returnModal .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px !important;
        }
        .select2-dropdown {
            background-color: #101C4C !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
            color: #ffffff !important;
            z-index: 99999 !important;
        }
        .select2-container--default .select2-results__option {
            color: #ffffff !important;
            font-size: 13px !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #35429E !important;
            color: #ffffff !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            background-color: #1a2757 !important;
            color: #ffffff !important;
            border: 1px solid rgba(255, 255, 255, 0.25) !important;
        }
        #returnsTable td {
            vertical-align: middle !important;
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
            .table thead th, #daily-report-table thead th {
                background: #e2e8f0 !important;
                color: #0f172a !important;
            }
            #daily-report-table tbody td {
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
                <a class="nav-link active" href="{{ route('warehouse-daily-sales-report', ['date' => $reportDate]) }}">
                    <i class="fa fa-user-tie me-1"></i> Driver-wise Daily Sales
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('warehouse-product-daily-sales-report', ['date' => $reportDate]) }}">
                    <i class="fa fa-boxes-stacked me-1"></i> Product-wise Daily Sales
                </a>
            </li>
        </ul>

    </div>

    {{-- Filter Panel --}}
    <div class="filter-panel mb-4 no-print">
        <form method="GET" action="{{ route('warehouse-daily-sales-report') }}" id="reportFilterForm">
            <div class="row align-items-end g-3">
                <div class="col-md-3">
                    <label for="report_date"><i class="fa fa-calendar-day me-1"></i> Report Date</label>
                    <input type="date"
                           name="date"
                           id="report_date"
                           class="form-control"
                           value="{{ $reportDate }}"
                           max="{{ date('Y-m-d') }}">
                </div>

                <div class="col-md-3">
                    <label for="driver_id"><i class="fa fa-user me-1"></i> Filter by Driver</label>
                    <select name="driver_id" id="driver_id" class="form-select">
                        <option value="">All Warehouse Drivers</option>
                        @forelse($warehouseDrivers as $d)
                            <option value="{{ $d->id }}" {{ (string)$selectedDriverId === (string)$d->id ? 'selected' : '' }}>
                                {{ $d->name }}
                            </option>
                        @empty
                            <option value="" disabled>No drivers assigned to this warehouse</option>
                        @endforelse
                    </select>
                </div>

                <div class="col-md-6 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-filter me-1"></i> View Report
                    </button>
                    <button type="button" class="btn btn-outline-light" id="btnToday">
                        Today
                    </button>
                    <button type="button" class="btn btn-outline-light" id="btnYesterday">
                        Yesterday
                    </button>
                    <a href="{{ route('warehouse-daily-sales-report') }}" class="btn btn-outline-secondary">
                        <i class="ti ti-rotate me-1"></i> Reset
                    </a>

                </div>
            </div>
        </form>
    </div>

    <div id="printableArea">

        {{-- Page Title in Print --}}
        <div class="d-none d-print-block mb-3">
            <h3>Daily Driver-wise Sales Report</h3>
            <p>Date: {{ date('d F, Y', strtotime($reportDate)) }} | Warehouse: {{ Auth::guard('backend')->user()->name }}</p>
            <hr>
        </div>

        {{-- Overall Warehouse Summary KPI Cards --}}
        <div class="row g-3 mb-4">

            {{-- Total Sales Amount --}}
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Overall Total Sales</span>
                            <h3 class="mb-0 mt-1 fw-bold text-success">₹ {{ number_format($overallSalesAmount, 2) }}</h3>
                            <small class="text-white-50">{{ $overallTotalInvoices }} Invoice{{ $overallTotalInvoices != 1 ? 's' : '' }} today</small>
                        </div>
                        <div class="kpi-icon bg-success bg-opacity-25 text-success">
                            <i class="fa fa-indian-rupee-sign"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Assigned --}}
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Cases Assigned</span>
                            <h3 class="mb-0 mt-1 fw-bold text-info">{{ $overallAssignedText }}</h3>
                            <small class="text-white-50">Dispatched across drivers</small>
                        </div>
                        <div class="kpi-icon bg-info bg-opacity-25 text-info">
                            <i class="fa fa-boxes-stacked"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Sold --}}
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Cases Sold</span>
                            <h3 class="mb-0 mt-1 fw-bold text-primary">{{ $overallSoldText }}</h3>
                            <small class="text-white-50">Delivered & verified</small>
                        </div>
                        <div class="kpi-icon bg-primary bg-opacity-25 text-primary">
                            <i class="fa fa-cart-shopping"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total Returned --}}
            <div class="col-xl-3 col-md-6">
                <div class="kpi-card">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <span class="text-white-50 text-uppercase fw-semibold" style="font-size: 11px; letter-spacing: 0.5px;">Total Cases Returned</span>
                            <h3 class="mb-0 mt-1 fw-bold text-warning">{{ $overallReturnedText }}</h3>
                            <small class="text-white-50">Unsold stock returned to depot</small>
                        </div>
                        <div class="kpi-icon bg-warning bg-opacity-25 text-warning">
                            <i class="fa fa-arrow-rotate-left"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Detailed Driver Table --}}
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1 text-white fw-bold"><i class="fa fa-truck me-2"></i>Driver-wise Daily Sales Summary</h4>
                            <small class="text-white-50">Report for date: <strong>{{ date('d F, Y', strtotime($reportDate)) }}</strong> ({{ $activeDriversCount }} active driver{{ $activeDriversCount != 1 ? 's' : '' }})</small>
                        </div>
                        <div class="no-print">
                            <a href="{{ route('driver-product-create') }}" class="btn btn-success btn-sm">
                                <i class="ti ti-plus me-1"></i> Assign Products
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="table-responsive dt-responsive">
                            <table class="table table-striped table-bordered nowrap" id="daily-report-table">
                                <thead>
                                    <tr>
                                        <th scope="col" style="width: 50px;">#</th>
                                        <th scope="col">Driver Name</th>
                                        <th scope="col">Total Cases Assigned</th>
                                        <th scope="col">Total Cases Sold</th>
                                        <th scope="col">Total Cases Returned</th>
                                        <th scope="col">Total Sales (₹)</th>
                                        <th scope="col">Deliveries</th>
                                        <th scope="col" class="no-print" style="width: 170px;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($driverReports as $key => $report)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>
                                                <strong class="text-white fs-6">{{ $report['driver_name'] }}</strong>
                                                @if($report['is_active'])
                                                    <span class="badge bg-success bg-opacity-75 ms-1" style="font-size:10px;">Active Today</span>
                                                @else
                                                    <span class="badge bg-secondary ms-1" style="font-size:10px;">Inactive</span>
                                                @endif
                                            </td>

                                            {{-- Assigned --}}
                                            <td>
                                                @if($report['assigned_bottles'] > 0)
                                                    <span class="badge bg-info text-dark fw-bold px-2 py-1" style="font-size: 12px;">
                                                        <i class="fa fa-boxes-stacked me-1"></i> {{ $report['assigned_text'] }}
                                                    </span>
                                                @else
                                                    <span class="text-white-50">0 Cases</span>
                                                @endif
                                            </td>

                                            {{-- Sold --}}
                                            <td>
                                                @if($report['sold_bottles'] > 0)
                                                    <span class="badge bg-primary text-white fw-bold px-2 py-1" style="font-size: 12px;">
                                                        <i class="fa fa-cart-shopping me-1"></i> {{ $report['sold_text'] }}
                                                    </span>
                                                @else
                                                    <span class="text-white-50">0 Cases</span>
                                                @endif
                                            </td>

                                            {{-- Returned --}}
                                            <td>
                                                @if($report['returned_bottles'] > 0)
                                                    <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 12px;">
                                                        <i class="fa fa-arrow-rotate-left me-1"></i> {{ $report['returned_text'] }}
                                                    </span>
                                                    @if($report['has_manual_return'])
                                                        <small class="badge bg-success ms-1" title="Confirmed physical return">Verified</small>
                                                    @endif
                                                @else
                                                    <span class="text-white-50">0 Cases</span>
                                                @endif
                                            </td>

                                            {{-- Sales Amount --}}
                                            <td>
                                                <strong class="text-success fs-6">
                                                    ₹ {{ number_format($report['sales_amount'], 2) }}
                                                </strong>
                                            </td>

                                            {{-- Invoices / Deliveries --}}
                                            <td>
                                                <span class="badge bg-light text-dark fw-bold">
                                                    {{ $report['invoices_count'] }} Invoice{{ $report['invoices_count'] != 1 ? 's' : '' }}
                                                </span>
                                            </td>

                                            {{-- Actions --}}
                                            <td class="no-print">
                                                <div class="d-flex align-items-center gap-1">
                                                    <button type="button"
                                                            class="btn btn-outline-info btn-sm btn-breakdown"
                                                            data-driver-id="{{ $report['driver_id'] }}"
                                                            data-driver-name="{{ $report['driver_name'] }}"
                                                            title="View product-by-product breakdown">
                                                        <i class="fa fa-list-check me-1"></i> Breakdown
                                                    </button>

                                                    <button type="button"
                                                            class="btn btn-outline-warning btn-sm btn-return"
                                                            data-driver-id="{{ $report['driver_id'] }}"
                                                            data-driver-name="{{ $report['driver_name'] }}"
                                                            title="Record or confirm returned stock">
                                                        <i class="fa fa-box-archive"></i> Return
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">
                                                <i class="fa fa-circle-info me-1"></i>
                                                @if($warehouseDrivers->isEmpty())
                                                    No drivers are currently assigned to this warehouse.
                                                @else
                                                    No driver activities or assignments found for {{ date('d F, Y', strtotime($reportDate)) }}.
                                                @endif
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr style="background: rgba(53, 66, 158, 0.45); font-weight: bold;">
                                        <td colspan="2" class="text-white text-uppercase">Overall Warehouse Total</td>
                                        <td class="text-info">{{ $overallAssignedText }}</td>
                                        <td class="text-primary">{{ $overallSoldText }}</td>
                                        <td class="text-warning">{{ $overallReturnedText }}</td>
                                        <td class="text-success fs-5">₹ {{ number_format($overallSalesAmount, 2) }}</td>
                                        <td class="text-white">{{ $overallTotalInvoices }} Invoices</td>
                                        <td class="no-print"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- Product Breakdown Modal --}}
    <div class="modal fade" id="breakdownModal" tabindex="-1" aria-labelledby="breakdownModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title text-white fw-bold" id="breakdownModalLabel">
                            <i class="fa fa-list-check text-info me-2"></i>Product Breakdown: <span id="modalDriverName"></span>
                        </h5>
                        <small class="text-white-50">Date: <span id="modalReportDate"></span></small>
                    </div>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="breakdownLoading" class="text-center py-4">
                        <div class="spinner-border text-info" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 text-white-50">Loading product breakdown...</p>
                    </div>

                    <div id="breakdownContent" style="display:none;">
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm text-center">
                                <thead>
                                    <tr>
                                        <th class="text-start">Product Name</th>
                                        <th>Assigned</th>
                                        <th>Sold</th>
                                        <th>Returned</th>
                                        <th>Revenue (₹)</th>
                                    </tr>
                                </thead>
                                <tbody id="breakdownTableBody">
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold" style="background: rgba(255, 255, 255, 0.08);">
                                        <td class="text-start text-white">Total</td>
                                        <td id="modalTotalAssigned" class="text-info"></td>
                                        <td id="modalTotalSold" class="text-primary"></td>
                                        <td id="modalTotalReturned" class="text-warning"></td>
                                        <td id="modalTotalRevenue" class="text-success"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Record / Confirm Return Modal --}}
    <div class="modal fade" id="returnModal" tabindex="-1" aria-labelledby="returnModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('warehouse-driver-return-store') }}" id="returnForm">
                    @csrf
                    <input type="hidden" name="driver_id" id="returnDriverId">
                    <input type="hidden" name="return_date" value="{{ $reportDate }}">

                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title text-white fw-bold mb-0" id="returnModalLabel">
                                <i class="fa fa-box-archive text-warning me-2"></i>Record Physical Return: <span id="returnModalDriverName"></span>
                            </h5>
                            <small class="text-white-50">Date: {{ date('d F, Y', strtotime($reportDate)) }} &bull; Confirm stock returned by driver to warehouse</small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        {{-- Loading spinner while fetching existing return data --}}
                        <div id="returnModalLoading" class="text-center py-4" style="display: none;">
                            <div class="spinner-border text-warning" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 text-white-50 mb-0">Loading return data...</p>
                        </div>

                        <div id="returnModalContent">
                            <div class="alert alert-info py-2 mb-3" style="font-size: 13px;">
                                <i class="fa fa-info-circle me-1"></i> Select only the products physically returned by the driver. You can add multiple rows using <strong>+ Add Product</strong>.
                            </div>

                            <div class="table-responsive mb-2">
                                <table class="table table-bordered table-sm align-middle mb-0" id="returnsTable">
                                    <thead>
                                        <tr style="background: rgba(255, 255, 255, 0.08);">
                                            <th style="min-width: 280px;">Product <span class="text-danger">*</span></th>
                                            <th style="width: 140px;" class="text-center">Returned Cases</th>
                                            <th style="width: 140px;" class="text-center">Returned Loose Bottles</th>
                                            <th style="width: 50px;" class="text-center">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="returnsTableBody">
                                        {{-- Dynamic rows inserted by JavaScript --}}
                                    </tbody>
                                </table>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
                                <button type="button" class="btn btn-outline-info btn-sm" id="btnAddReturnRow">
                                    <i class="fa fa-plus me-1"></i> Add Product
                                </button>
                                <small class="text-white-50" id="returnRowCountText"></small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-white fw-semibold mb-1">Return Notes / Reason (Optional)</label>
                                <textarea name="notes" id="returnNotes" class="form-control form-control-sm" rows="2" placeholder="e.g. Broken bottles, customer cancellations, returned unsold stock..."></textarea>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="restock_warehouse" id="restockCheckbox" value="1" checked>
                                <label class="form-check-label text-white" for="restockCheckbox">
                                    Automatically add returned items back into Warehouse Stock
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm fw-bold">
                            <i class="fa fa-check me-1"></i> Save Return
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Hidden Product Options Template for Select2 Dynamic Rows --}}
    <template id="returnProductOptionsTemplate">
        <option value="">Search / Select Product...</option>
        @foreach($products as $p)
            <option value="{{ $p->id }}" data-bpc="{{ $p->per_case_quantity ?? 24 }}">
                {{ $p->name }} ({{ $p->per_case_quantity ?? 24 }} btls/case)
            </option>
        @endforeach
    </template>

@endsection

@section('scripts')
<script src="{{ URL::asset(asset_path('backend/assets/js/jquery-confirm.js')) }}" type="text/javascript"></script>

<script>
$(document).ready(function () {
    // Quick date filters
    $('#btnToday').click(function () {
        let today = new Date().toISOString().split('T')[0];
        $('#report_date').val(today);
        $('#reportFilterForm').submit();
    });

    $('#btnYesterday').click(function () {
        let y = new Date();
        y.setDate(y.getDate() - 1);
        let yesterday = y.toISOString().split('T')[0];
        $('#report_date').val(yesterday);
        $('#reportFilterForm').submit();
    });

    // Breakdown Modal Handler
    $('.btn-breakdown').click(function () {
        let driverId = $(this).data('driver-id');
        let driverName = $(this).data('driver-name');
        let date = '{{ $reportDate }}';

        $('#modalDriverName').text(driverName);
        $('#modalReportDate').text('{{ date("d F, Y", strtotime($reportDate)) }}');
        $('#breakdownLoading').show();
        $('#breakdownContent').hide();
        $('#breakdownModal').modal('show');

        let url = '{{ route("warehouse-daily-sales-report-breakdown", [":driver_id", ":date"]) }}';
        url = url.replace(':driver_id', driverId).replace(':date', date);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                $('#breakdownLoading').hide();
                $('#breakdownContent').show();

                let rowsHtml = '';
                if (res.items && res.items.length > 0) {
                    res.items.forEach(function (item) {
                        rowsHtml += `
                            <tr>
                                <td class="text-start">
                                    <strong class="text-white">${item.product_name}</strong>
                                    <small class="text-white-50 d-block">${item.bottles_per_case} btls/case</small>
                                </td>
                                <td class="text-info">${item.assigned_text}</td>
                                <td class="text-primary">${item.sold_text}</td>
                                <td class="text-warning">${item.returned_text}</td>
                                <td class="text-success fw-bold">₹ ${item.sales_amount}</td>
                            </tr>
                        `;
                    });
                } else {
                    rowsHtml = `<tr><td colspan="5" class="text-muted py-3">No product assignments or sales for this driver on this date.</td></tr>`;
                }

                $('#breakdownTableBody').html(rowsHtml);
                $('#modalTotalAssigned').text(res.totals.assigned_text);
                $('#modalTotalSold').text(res.totals.sold_text);
                $('#modalTotalReturned').text(res.totals.returned_text);
                $('#modalTotalRevenue').text(res.totals.sales_amount);
            },
            error: function () {
                $('#breakdownLoading').hide();
                $('#breakdownContent').show();
                $('#breakdownTableBody').html(`<tr><td colspan="5" class="text-danger py-3">Failed to load breakdown. Please try again.</td></tr>`);
            }
        });
    });

    // Dynamic Returns Multi-Row Management
    let returnRowIndex = 0;

    function addReturnRow(productId, cases, bottles) {
        productId = productId || '';
        cases = cases !== undefined ? cases : 0;
        bottles = bottles !== undefined ? bottles : 0;

        let currentIdx = returnRowIndex++;
        let optionsHtml = $('#returnProductOptionsTemplate').html();

        let trHtml = `
            <tr class="return-row" id="returnRow_${currentIdx}" data-index="${currentIdx}">
                <td>
                    <select name="returns[${currentIdx}][product_id]" class="form-select form-select-sm select2-return-product" required>
                        ${optionsHtml}
                    </select>
                </td>
                <td>
                    <input type="number"
                           name="returns[${currentIdx}][cases]"
                           class="form-control form-control-sm text-center return-cases"
                           min="0"
                           value="${cases}"
                           placeholder="0">
                </td>
                <td>
                    <input type="number"
                           name="returns[${currentIdx}][bottles]"
                           class="form-control form-control-sm text-center return-bottles"
                           min="0"
                           value="${bottles}"
                           placeholder="0">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-return-row" title="Remove row">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

        let $row = $(trHtml);
        $('#returnsTableBody').append($row);

        // Initialize Select2 with dropdownParent to support modal focus
        $row.find('.select2-return-product').select2({
            dropdownParent: $('#returnModal'),
            width: '100%',
            placeholder: 'Search / Select Product...'
        });

        if (productId) {
            $row.find('.select2-return-product').val(productId).trigger('change');
        }

        updateReturnRowCount();
    }

    function updateReturnRowCount() {
        let count = $('#returnsTableBody tr.return-row').length;
        $('#returnRowCountText').text(count + ' product' + (count !== 1 ? 's' : '') + ' added');
    }

    // Add another product button
    $('#btnAddReturnRow').click(function () {
        addReturnRow('', 0, 0);
    });

    // Remove product row button
    $(document).on('click', '.btn-remove-return-row', function () {
        let rowCount = $('#returnsTableBody tr.return-row').length;
        if (rowCount > 1) {
            let $select = $(this).closest('tr.return-row').find('.select2-return-product');
            if ($select.data('select2')) {
                $select.select2('destroy');
            }
            $(this).closest('tr.return-row').remove();
            updateReturnRowCount();
        } else {
            // If only 1 row remains, reset it instead of removing
            let $row = $(this).closest('tr.return-row');
            $row.find('.select2-return-product').val('').trigger('change');
            $row.find('.return-cases').val(0);
            $row.find('.return-bottles').val(0);
        }
    });

    // Return Modal Open Handler
    $('.btn-return').click(function () {
        let driverId = $(this).data('driver-id');
        let driverName = $(this).data('driver-name');
        let date = $('#report_date').val() || '{{ $reportDate }}';

        $('#returnDriverId').val(driverId);
        $('#returnModalDriverName').text(driverName);
        $('#returnsTableBody').empty();
        $('#returnNotes').val('');
        $('#returnModalLoading').show();
        $('#returnModalContent').hide();
        $('#returnModal').modal('show');

        returnRowIndex = 0;

        // Fetch existing recorded returns for this driver on this date
        let url = '{{ route("warehouse-driver-returns-data", [":driver_id", ":date"]) }}';
        url = url.replace(':driver_id', driverId).replace(':date', date);

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function (res) {
                $('#returnModalLoading').hide();
                $('#returnModalContent').show();

                if (res.status === 200 && res.returns && res.returns.length > 0) {
                    res.returns.forEach(function (ret) {
                        addReturnRow(ret.product_id, ret.cases, ret.bottles);
                    });
                    if (res.notes) {
                        $('#returnNotes').val(res.notes);
                    }
                } else {
                    addReturnRow('', 0, 0);
                }
            },
            error: function () {
                $('#returnModalLoading').hide();
                $('#returnModalContent').show();
                addReturnRow('', 0, 0);
            }
        });
    });

    // Prevent duplicate product selection & validation on form submit
    $('#returnForm').on('submit', function (e) {
        let hasSelectedProduct = false;
        let selectedProductIds = [];
        let hasDuplicate = false;

        $('#returnsTableBody tr.return-row').each(function () {
            let pid = $(this).find('.select2-return-product').val();
            if (pid) {
                hasSelectedProduct = true;
                if (selectedProductIds.includes(pid)) {
                    hasDuplicate = true;
                }
                selectedProductIds.push(pid);
            }
        });

        if (!hasSelectedProduct) {
            e.preventDefault();
            alert('Please select at least one product to record return.');
            return false;
        }

        if (hasDuplicate) {
            e.preventDefault();
            alert('A product has been added more than once. Please combine the quantities or remove the duplicate row.');
            return false;
        }
    });
});
</script>
@endsection
