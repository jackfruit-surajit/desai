@extends('manufacture.layouts.main')

@section('title', 'Warehouse Stock Overview')
@section('breadcrumb-item', 'Stock Management')
@section('breadcrumb-item-active', 'Warehouse Stock View')

@section('css')
    <link href="{{ URL::asset('public/backend/assets/css/jquery-confirm.css') }}" rel="stylesheet" type="text/css" />
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
            <a class="nav-link active" href="{{ route('manufacture-warehouse-stock') }}">
                <i class="fa fa-warehouse me-1"></i> Warehouse Stock Overview
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('manufacture-stock-logs') }}">
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
                                <i class="fa fa-warehouse text-primary me-2"></i> Real-time Stock across Warehouses
                            </h4>
                        </div>
                        <div class="col-lg-6 text-end">
                            <a href="{{ route('manufacture-product-create') }}" class="btn btn-primary">
                                <i class="fa fa-paper-plane me-1"></i> Assign Products to Warehouse
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4 p-3 bg-light rounded border">
                        <div class="col-md-5">
                            <label class="form-label font-weight-bold">Filter by Warehouse:</label>
                            <select id="warehouse_filter" class="form-control">
                                <option value="">-- All Warehouses --</option>
                                @foreach($warehouses as $wh)
                                    <option value="{{ $wh->id }}">{{ $wh->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label font-weight-bold">Filter by Product:</label>
                            <select id="product_filter" class="form-control">
                                <option value="">-- All Products --</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}">{{ $prod->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-secondary w-100" id="reset_filters">
                                <i class="fa fa-refresh me-1"></i> Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive dt-responsive">
                        <table class="table table-striped table-bordered nowrap" id="warehouse-stock-table">
                            <thead>
                                <tr>
                                    <th scope="col" style="width: 5%;">#</th>
                                    <th scope="col">Warehouse Name</th>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Packaging</th>
                                    <th scope="col">Current Stock (Cases)</th>
                                    <th scope="col">Last Updated</th>
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
        var table = $('#warehouse-stock-table').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: {
                url: '{{ route("manufacture-warehouse-stock-datatable") }}',
                data: function (d) {
                    d.warehouse_id = $('#warehouse_filter').val();
                    d.product_id = $('#product_filter').val();
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
                {data: 'warehouse_name', name: 'admins.name'},
                {data: 'product_name', name: 'products.name'},
                {data: 'bottles_per_case', name: 'products.per_case_quantity', searchable: false},
                {data: 'stock', name: 'warehouse_stock.stock'},
                {data: 'updated_date', name: 'warehouse_stock.updated_at', searchable: false}
            ]
        });

        $('#warehouse_filter, #product_filter').on('change', function () {
            table.ajax.reload();
        });

        $('#reset_filters').on('click', function () {
            $('#warehouse_filter').val('');
            $('#product_filter').val('');
            table.ajax.reload();
        });
    });
    </script>
@endsection

