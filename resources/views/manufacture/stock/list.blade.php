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
            color: #555;
            padding: 10px 20px;
        }
        .nav-tabs .nav-link.active {
            color: #04A9F5;
            border-bottom: 3px solid #04A9F5;
            background: transparent;
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

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h4 class="mb-0">
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
                        <table class="table table-striped table-bordered nowrap" id="factory-stock-table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Packaging</th>
                                    <th scope="col">Per Case Price</th>
                                    <th scope="col">Factory Stock (In-Hand)</th>
                                    <th scope="col">Total Dispatched</th>
                                    <th scope="col" style="width: 15%;">Actions</th>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="quickAddStockForm">
                    @csrf
                    <input type="hidden" name="product_id" id="modal_product_id">
                    
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title text-white" id="quickAddStockModalLabel">
                            <i class="fa fa-plus-circle me-1"></i> Add Stock (Production Entry)
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-danger d-none" id="modalAlert"></div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Product:</label>
                            <input type="text" class="form-control" id="modal_product_name" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Current Factory Stock:</label>
                            <input type="text" class="form-control" id="modal_current_stock" readonly>
                        </div>

                        <div class="mb-3">
                            <label class="form-label font-weight-bold">Add Quantity (Cases) <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" name="quantity" id="modal_quantity" min="1" placeholder="Enter number of cases" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Batch Number (Optional)</label>
                            <input type="text" class="form-control" name="batch_no" id="modal_batch_no" placeholder="e.g. BATCH-2026-001">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Notes / Remarks (Optional)</label>
                            <textarea class="form-control" name="note" id="modal_note" rows="2" placeholder="e.g. Fresh production batch completed"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="saveQuickStockBtn">
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
        var table = $('#factory-stock-table').DataTable({
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
            $('#modal_current_stock').val(currentStock + ' Cases');
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
    });
    </script>
    <!-- [Page Specific JS] end -->
@endsection

