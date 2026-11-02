@extends('manufacture.layouts.main')

@section('title', 'Stock Management')
@section('breadcrumb-item', 'Stock Management')
@section('breadcrumb-item-active', 'Factory Stock')

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
            box-shadow: 0 5px 15px rgba(0,0,0,0.08);
        }
        .nav-tabs .nav-link {
            font-weight: 600;
            color: #1e293b;
            padding: 10px 20px;
        }
        .nav-tabs .nav-link.active {
            color: #04A9F5;
            font-weight: 700;
            border-bottom: 3px solid #04A9F5;
            background: transparent;
        }
        /* Table Header & Dark Background Styling */
        #staff-management thead th,
        .table thead th {
            color: #ffffff !important;
            font-weight: 700 !important;
            background-color: #0f172a !important;
            border-color: #334155 !important;
            vertical-align: middle !important;
            padding: 12px 14px !important;
        }
        #staff-management tbody td,
        .table tbody td {
            background-color: #1e293b !important;
            color: #ffffff !important;
            border-color: #334155 !important;
            vertical-align: middle !important;
        }
        #staff-management.table-striped > tbody > tr:nth-of-type(odd) > *,
        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: #243247 !important;
            color: #ffffff !important;
        }
        #staff-management.table-hover > tbody > tr:hover > *,
        #staff-management tbody tr:hover > *,
        .table tbody tr:hover > * {
            background-color: #2e3e57 !important;
            color: #ffffff !important;
        }
        #staff-management tbody td strong,
        #staff-management tbody td span:not(.badge),
        #staff-management tbody td div:not(.badge),
        #staff-management tbody td small,
        .table tbody td strong,
        .table tbody td span:not(.badge),
        .table tbody td div:not(.badge),
        .table tbody td small {
            color: #ffffff !important;
        }
        .form-control[readonly], 
        .form-control:disabled {
            background-color: #f1f5f9 !important;
            color: #0f172a !important;
            font-weight: 600 !important;
            border: 1px solid #94a3b8 !important;
            -webkit-text-fill-color: #0f172a !important;
        }
        .modal-body label {
            color: #0f172a !important;
            font-weight: 600 !important;
        }
    </style>
    <!-- [Page specific CSS] end -->
@endsection

@section('content')

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-4" id="stockTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('manufacture-stock') }}">
                <i class="fa fa-industry me-1"></i> Factory Stock
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('manufacture-warehouse-stock') }}">
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
                            <p class="text-uppercase mb-1 fw-bold text-muted" style="font-size: 11px;">Active Products</p>
                            <h3 class="mb-0 fw-bold text-dark">{{ $totalProducts ?? 0 }}</h3>
                        </div>
                        <div class="avatar bg-light-primary text-primary rounded-circle p-3">
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
                            <p class="text-uppercase mb-1 fw-bold text-muted" style="font-size: 11px;">Factory Stock (In-Hand)</p>
                            <h3 class="mb-0 fw-bold text-success">{{ number_format($totalFactoryStock ?? 0) }} <span style="font-size: 14px;">Cases</span></h3>
                        </div>
                        <div class="avatar bg-light-success text-success rounded-circle p-3">
                            <i class="fa fa-industry fa-2x"></i>
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
                            <p class="text-uppercase mb-1 fw-bold text-muted" style="font-size: 11px;">Total Dispatched</p>
                            <h3 class="mb-0 fw-bold text-info">{{ number_format($totalDispatched ?? 0) }} <span style="font-size: 14px;">Cases</span></h3>
                        </div>
                        <div class="avatar bg-light-info text-info rounded-circle p-3">
                            <i class="fa fa-truck-moving fa-2x"></i>
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
                            <p class="text-uppercase mb-1 fw-bold text-muted" style="font-size: 11px;">Warehouse Stock</p>
                            <h3 class="mb-0 fw-bold text-primary">{{ number_format($totalWarehouseStock ?? 0) }} <span style="font-size: 14px;">Cases</span></h3>
                        </div>
                        <div class="avatar bg-light-primary text-primary rounded-circle p-3">
                            <i class="fa fa-warehouse fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h4 class="mb-0 text-dark fw-bold">
                                <i class="fa fa-industry text-primary me-2"></i> Factory Stock (Current Available Inventory)
                            </h4>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a href="{{ route('manufacture-stock-create') }}" class="btn btn-primary me-2">
                                <i class="fa fa-plus-circle me-1"></i> Add Production Stock
                            </a>
                            <a href="{{ route('manufacture-product-create') }}" class="btn btn-outline-info">
                                <i class="fa fa-paper-plane me-1"></i> Assign to Warehouse
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive dt-responsive">
                        <table class="table table-striped table-bordered nowrap" id="staff-management">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Packaging</th>
                                    <th scope="col">Per Case Price</th>
                                    <th scope="col">Factory Stock (In-Hand)</th>
                                    <th scope="col">Total Dispatched</th>
                                    <th scope="col" style="min-width: 220px; width: 18%;">Actions</th>
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

    <!-- Quick Add Stock Modal -->
    <div class="modal fade" id="quickAddStockModal" tabindex="-1" aria-labelledby="quickAddStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="quickAddStockForm">
                    @csrf
                    <input type="hidden" name="product_id" id="modal_product_id">
                    
                    <div class="modal-header bg-primary text-white py-2 px-3">
                        <h6 class="modal-title text-white fw-bold mb-0" id="quickAddStockModalLabel">
                            <i class="fa fa-plus-circle me-1"></i> Add Stock (Production Entry)
                        </h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body py-2 px-3">
                        <div class="alert alert-danger d-none py-1 px-2 mb-2" id="modalAlert" style="font-size:12px;"></div>

                        <!-- Compact Product Info Bar -->
                        <div class="row g-2 mb-2 p-2 rounded border bg-light align-items-center">
                            <div class="col-sm-7 col-12">
                                <span class="text-muted d-block" style="font-size:11px;">Product:</span>
                                <strong class="text-dark fs-6" id="modal_product_name_text"></strong>
                                <input type="hidden" id="modal_product_name">
                            </div>
                            <div class="col-sm-5 col-12 text-sm-end">
                                <span class="text-muted d-block" style="font-size:11px;">Current Factory Stock:</span>
                                <span class="badge bg-dark fs-6" id="modal_current_stock_badge"></span>
                                <input type="hidden" id="modal_current_stock">
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    Add Quantity (Cases) <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control fw-bold text-primary" name="quantity" id="modal_quantity" min="1" placeholder="Enter number of cases" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small mb-1">Batch Number (Optional)</label>
                                <input type="text" class="form-control" name="batch_no" id="modal_batch_no" placeholder="e.g. BATCH-{{ date('Ymd') }}-01">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark small mb-1">Notes / Remarks (Optional)</label>
                                <input type="text" class="form-control" name="note" id="modal_note" placeholder="e.g. Fresh production batch completed">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer py-2 px-3">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm fw-bold" id="saveQuickStockBtn">
                            <i class="fa fa-save me-1"></i> Update Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Stock Modal (Compact, No Scrolling) -->
    <div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form id="editStockForm">
                    @csrf
                    <input type="hidden" name="product_id" id="edit_modal_product_id">
                    <input type="hidden" id="edit_modal_current_stock_val">
                    
                    <div class="modal-header bg-warning text-dark py-2 px-3">
                        <h6 class="modal-title text-dark fw-bold mb-0" id="editStockModalLabel">
                            <i class="fa fa-edit me-1"></i> Edit Factory Stock (Adjustment)
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body py-2 px-3">
                        <div class="alert alert-danger d-none py-1 px-2 mb-2" id="editModalAlert" style="font-size:12px;"></div>

                        <!-- Compact Product Info Bar -->
                        <div class="row g-2 mb-2 p-2 rounded border bg-light align-items-center">
                            <div class="col-md-5 col-12">
                                <span class="text-muted d-block" style="font-size:11px;">Product:</span>
                                <strong class="text-dark fs-6" id="edit_modal_product_name_text"></strong>
                                <input type="hidden" id="edit_modal_product_name">
                            </div>
                            <div class="col-md-4 col-6 text-md-center">
                                <span class="text-muted d-block" style="font-size:11px;">Packaging & Price:</span>
                                <span id="edit_modal_packaging" class="badge bg-secondary me-1"></span>
                                <span id="edit_modal_price" class="badge bg-info text-dark"></span>
                            </div>
                            <div class="col-md-3 col-6 text-end">
                                <span class="text-muted d-block" style="font-size:11px;">Current Available:</span>
                                <span class="badge bg-dark fs-6" id="edit_modal_current_stock_badge"></span>
                                <input type="hidden" id="edit_modal_current_stock">
                            </div>
                        </div>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    Updated Stock Quantity (Cases) <span class="text-danger">*</span>
                                </label>
                                <input type="number" class="form-control fw-bold text-primary" name="stock" id="edit_modal_stock" min="0" step="1" placeholder="Enter cases" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small mb-1">
                                    Stock Adjustment Impact:
                                </label>
                                <div class="px-2 py-1 rounded border text-center d-flex align-items-center justify-content-center" id="edit_modal_diff_indicator" style="background-color: #f1f5f9; min-height: 38px;">
                                    <span class="text-muted fw-bold small" id="edit_modal_diff_text">
                                        <i class="fa fa-equals me-1"></i> No change
                                    </span>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small mb-1">Reason for Adjustment</label>
                                <select class="form-select form-select-sm" id="edit_modal_reason_preset">
                                    <option value="">-- Quick Reason (Optional) --</option>
                                    <option value="Physical Inventory Count / Audit Correction">Physical Inventory Count / Audit Correction</option>
                                    <option value="Damaged / Broken / Leaked Bottles">Damaged / Broken / Leaked Bottles</option>
                                    <option value="Production Entry Correction">Production Entry Correction</option>
                                    <option value="Quality Inspection Rejection">Quality Inspection Rejection</option>
                                    <option value="Returned from Transit / Warehouse">Returned from Transit / Warehouse</option>
                                    <option value="Other">Other Reason</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-bold text-dark small mb-1">Batch / Reference (Optional)</label>
                                <input type="text" class="form-control form-control-sm" name="batch_no" id="edit_modal_batch_no" placeholder="e.g. ADJ-{{ date('Ymd') }}-01">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold text-dark small mb-1">Notes / Remarks</label>
                                <input type="text" class="form-control form-control-sm" name="note" id="edit_modal_note" placeholder="Optional notes for stock adjustment audit log">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer py-2 px-3">
                        <a href="#" id="edit_modal_full_page_btn" class="btn btn-outline-secondary btn-sm me-auto">
                            <i class="fa fa-external-link-alt me-1"></i> Full Page Edit
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning text-dark fw-bold btn-sm" id="saveEditStockBtn">
                            <i class="fa fa-save me-1"></i> Update Stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <!-- [Page Specific JS] start -->
    <script src="{{ URL::asset('public/backend/assets/js/jquery-confirm.js') }}" type="text/javascript"></script>

    <script>
    $(document).ready(function () {
        var table = $('#staff-management').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("manufacture-stock-datatable") }}'
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'product_name', name: 'product_name'},
                {data: 'per_case_quantity', name: 'per_case_quantity', searchable: false},
                {data: 'price', name: 'price', searchable: false},
                {data: 'stock_quantity', name: 'stock_quantity', searchable: false},
                {data: 'total_dispatched', name: 'total_dispatched', searchable: false},
                {data: 'action', name: 'action', orderable: false, searchable: false}
            ]
        });

        // Open Quick Add Stock Modal
        $(document).on('click', '.quick-add-stock-btn', function () {
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            var currentStock = $(this).data('stock');

            $('#modal_product_id').val(productId);
            $('#modal_product_name').val(productName);
            $('#modal_product_name_text').text(productName);
            $('#modal_current_stock').val(currentStock + ' Cases');
            $('#modal_current_stock_badge').text(currentStock + ' Cases');
            $('#modal_quantity').val('');
            $('#modal_batch_no').val('');
            $('#modal_note').val('');
            $('#modalAlert').addClass('d-none').text('');

            $('#quickAddStockModal').modal('show');
        });

        // Submit Quick Add Stock Form via AJAX
        $('#quickAddStockForm').on('submit', function (e) {
            e.preventDefault();
            var submitBtn = $('#saveQuickStockBtn');
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Saving...');

            $.ajax({
                url: '{{ route("manufacture-stock-quick-add") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    submitBtn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Update Stock');
                    if (res.status == 200) {
                        $('#quickAddStockModal').modal('hide');
                        $.alert({
                            title: 'Success!',
                            content: res.message,
                            type: 'green'
                        });
                        table.ajax.reload(null, false);
                    } else {
                        $('#modalAlert').removeClass('d-none').text(res.message);
                    }
                },
                error: function (xhr) {
                    submitBtn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Update Stock');
                    var errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#modalAlert').removeClass('d-none').text(errorMsg);
                }
            });
        });

        // Open Edit Stock Modal
        $(document).on('click', '.edit-stock-btn', function () {
            var productId = $(this).data('id');
            var productName = $(this).data('name');
            var currentStock = parseInt($(this).data('stock')) || 0;
            var packaging = $(this).data('packaging') || 0;
            var price = $(this).data('price') || 0;
            var editUrl = $(this).data('url') || '#';

            $('#edit_modal_product_id').val(productId);
            $('#edit_modal_product_name').val(productName);
            $('#edit_modal_product_name_text').text(productName);
            $('#edit_modal_current_stock_val').val(currentStock);
            $('#edit_modal_current_stock').val(currentStock + ' Cases');
            $('#edit_modal_current_stock_badge').text(currentStock + ' Cases');
            $('#edit_modal_stock').val(currentStock);
            $('#edit_modal_packaging').text(packaging + ' Bottles/Case');
            $('#edit_modal_price').text('₹' + price);
            $('#edit_modal_full_page_btn').attr('href', editUrl);
            $('#edit_modal_reason_preset').val('');
            $('#edit_modal_note').val('');
            $('#edit_modal_batch_no').val('');
            $('#editModalAlert').addClass('d-none').text('');

            updateEditModalDiff();
            $('#editStockModal').modal('show');
        });

        function updateEditModalDiff() {
            var currentStock = parseInt($('#edit_modal_current_stock_val').val()) || 0;
            var rawVal = $('#edit_modal_stock').val();
            var $indicator = $('#edit_modal_diff_indicator');
            var $text = $('#edit_modal_diff_text');

            if (rawVal === '') {
                $indicator.css({'background-color': '#f1f5f9', 'border-color': '#cbd5e1'});
                $text.html('<span class="text-muted"><i class="fa fa-question-circle me-1"></i> Enter a stock quantity</span>');
                return;
            }

            var newStock = parseInt(rawVal);
            if (isNaN(newStock) || newStock < 0) {
                $indicator.css({'background-color': '#fee2e2', 'border-color': '#fca5a5'});
                $text.html('<span class="text-danger fw-bold"><i class="fa fa-triangle-exclamation me-1"></i> Stock cannot be negative</span>');
                return;
            }

            var diff = newStock - currentStock;
            if (diff > 0) {
                $indicator.css({'background-color': '#dcfce7', 'border-color': '#86efac'});
                $text.html('<span class="text-success fw-bold"><i class="fa fa-arrow-up me-1"></i> Increase of +' + diff + ' Cases (from ' + currentStock + ' to ' + newStock + ')</span>');
            } else if (diff < 0) {
                $indicator.css({'background-color': '#fee2e2', 'border-color': '#fca5a5'});
                $text.html('<span class="text-danger fw-bold"><i class="fa fa-arrow-down me-1"></i> Decrease of ' + diff + ' Cases (from ' + currentStock + ' to ' + newStock + ')</span>');
            } else {
                $indicator.css({'background-color': '#f1f5f9', 'border-color': '#cbd5e1'});
                $text.html('<span class="text-muted fw-bold"><i class="fa fa-equals me-1"></i> No change (remains ' + currentStock + ' Cases)</span>');
            }
        }

        $('#edit_modal_stock').on('input keyup change', updateEditModalDiff);

        $('#edit_modal_reason_preset').on('change', function () {
            var val = $(this).val();
            if (val && val !== 'Other') {
                $('#edit_modal_note').val(val);
            }
        });

        // Submit Edit Stock Form via AJAX
        $('#editStockForm').on('submit', function (e) {
            e.preventDefault();
            var submitBtn = $('#saveEditStockBtn');
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Updating...');

            $.ajax({
                url: '{{ route("manufacture-stock-update") }}',
                type: 'POST',
                data: $(this).serialize(),
                success: function (res) {
                    submitBtn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Update Stock');
                    if (res.status == 200) {
                        $('#editStockModal').modal('hide');
                        $.alert({
                            title: 'Success!',
                            content: res.message,
                            type: 'green'
                        });
                        table.ajax.reload(null, false);
                    } else {
                        $('#editModalAlert').removeClass('d-none').text(res.message);
                    }
                },
                error: function (xhr) {
                    submitBtn.prop('disabled', false).html('<i class="fa fa-save me-1"></i> Update Stock');
                    var errorMsg = 'An error occurred. Please try again.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    }
                    $('#editModalAlert').removeClass('d-none').text(errorMsg);
                }
            });
        });
    });
    </script>
    <!-- [Page Specific JS] end -->
@endsection
