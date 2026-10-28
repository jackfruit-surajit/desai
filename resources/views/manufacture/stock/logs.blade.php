@extends('manufacture.layouts.main')

@section('title', 'Stock Movement Logs')
@section('breadcrumb-item', 'Stock Management')
@section('breadcrumb-item-active', 'Stock Movement Logs')

@section('css')
    <link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />
    <style>
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
@endsection

@section('content')

    <!-- Navigation Tabs -->
    <ul class="nav nav-tabs mb-3" id="stockTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link" href="{{ route('manufacture-stock') }}">
                <i class="fa fa-industry me-1"></i> Factory Stock
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('manufacture-warehouse-stock') }}">
                <i class="fa fa-warehouse me-1"></i> Warehouse Stock Overview
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('manufacture-stock-logs') }}">
                <i class="fa fa-history me-1"></i> Stock Movement Logs
            </a>
        </li>
    </ul>

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <h4 class="mb-0">
                                <i class="fa fa-history text-primary me-2"></i> Factory Stock Transaction History
                            </h4>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a href="{{ route('manufacture-stock-create') }}" class="btn btn-primary me-2">
                                <i class="fa fa-plus-circle me-1"></i> Add Production Stock
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4 p-3 bg-light rounded border">
                        <div class="col-md-5">
                            <label class="form-label font-weight-bold">Filter by Product:</label>
                            <select id="product_filter" class="form-control">
                                <option value="">-- All Products --</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}" {{ $selected_product == $prod->id ? 'selected' : '' }}>
                                        {{ $prod->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Filter by Movement Type:</label>
                            <select id="type_filter" class="form-control">
                                <option value="">-- All Types --</option>
                                <option value="production">Stock In (Production)</option>
                                <option value="dispatch">Stock Out (Dispatched to Warehouse)</option>
                                <option value="adjustment">Adjustment / Restored</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="button" class="btn btn-secondary w-100" id="reset_filters">
                                <i class="fa fa-refresh me-1"></i> Reset Filters
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive dt-responsive">
                        <table class="table table-striped table-bordered nowrap" id="stock-logs-table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col">Date & Time</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Type</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Destination / Warehouse</th>
                                    <th scope="col">Batch No</th>
                                    <th scope="col">Note / Remarks</th>
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
    <script>
    $(document).ready(function () {
        var table = $('#stock-logs-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("manufacture-stock-logs-datatable") }}',
                data: function (d) {
                    d.product_id = $('#product_filter').val();
                    d.type = $('#type_filter').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'date', name: 'manufacture_stock_logs.created_at'},
                {data: 'product_name', name: 'products.name'},
                {data: 'type', name: 'manufacture_stock_logs.type'},
                {data: 'quantity', name: 'manufacture_stock_logs.quantity'},
                {data: 'destination', name: 'warehouse.name'},
                {data: 'batch_no', name: 'manufacture_stock_logs.batch_no'},
                {data: 'note', name: 'manufacture_stock_logs.note'}
            ]
        });

        $('#product_filter, #type_filter').on('change', function () {
            table.ajax.reload();
        });

        $('#reset_filters').on('click', function () {
            $('#product_filter').val('');
            $('#type_filter').val('');
            table.ajax.reload();
        });
    });
    </script>
@endsection

