@extends('manufacture.layouts.main')

@section('title', 'Add Production Stock')
@section('breadcrumb-item', 'Stock Management')
@section('breadcrumb-item-active', 'Add Production Stock')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<style>
    .select2-container {
        width: 100% !important;
    }
    .product-row {
        border: 1px solid #dee2e6;
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        background: #f8f9fa;
    }
    .remove-product {
        margin-top: 30px;
    }
    .required {
        color: red;
    }
</style>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fa fa-plus-circle text-primary me-2"></i> Add Production Stock (Multiple Products)
                </h5>

                <button type="button" class="btn btn-success btn-sm" id="addProduct">
                    <i class="fa fa-plus"></i> Add Another Product
                </button>
            </div>

            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('manufacture-stock-store') }}" id="stockAddForm">
                    @csrf

                    <!-- Meta Information: Batch No, Date, Notes -->
                    <div class="row mb-4 p-3 bg-light rounded border">
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Batch Number (Optional)</label>
                            <input type="text" name="batch_no" class="form-control" placeholder="e.g. BATCH-{{ date('Ymd') }}-01" value="{{ old('batch_no') }}">
                            <small class="text-muted">Auto-generated if left blank</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Production Date</label>
                            <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label font-weight-bold">Notes / Production Remarks</label>
                            <input type="text" name="note" class="form-control" placeholder="e.g. Daily morning production batch" value="{{ old('note') }}">
                        </div>
                    </div>

                    <h6 class="mb-3 text-primary font-weight-bold">
                        <i class="fa fa-box me-1"></i> Products Produced
                    </h6>

                    {{-- Products Container --}}
                    <div id="productContainer">
                        <div class="product-row">
                            <div class="row">
                                {{-- Product Select --}}
                                <div class="col-md-6">
                                    <label class="form-label text-dark font-weight-bold">
                                        Product <span class="required">*</span>
                                    </label>
                                    <select name="products[0][product_id]" class="form-control product-select" required>
                                        <option value="">-- Select Product --</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} ({{ $product->per_case_quantity ?? 0 }} Bottles/Case)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Quantity --}}
                                <div class="col-md-4">
                                    <label class="form-label text-dark font-weight-bold">
                                        Quantity (Cases) <span class="required">*</span>
                                    </label>
                                    <input type="number" name="products[0][quantity]" class="form-control" placeholder="Enter cases produced" min="1" required>
                                </div>

                                {{-- Remove Button --}}
                                <div class="col-md-2">
                                    <button type="button" class="btn btn-danger remove-product" style="display:none;">
                                        <i class="fa fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="text-end btn-page mt-4">
                        <a href="{{ route('manufacture-stock') }}" class="btn btn-outline-secondary me-2">
                            Cancel
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-check-circle me-1"></i> Add To Factory Stock
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function () {
    let productIndex = 1;

    function initializeSelect2() {
        $('.product-select').each(function () {
            if (!$(this).hasClass("select2-hidden-accessible")) {
                $(this).select2({
                    placeholder: "Select Product",
                    allowClear: true
                });
            }
        });
    }

    initializeSelect2();

    // Add Product Row
    $('#addProduct').click(function () {
        let newRow = `
            <div class="product-row">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label text-dark font-weight-bold">
                            Product <span class="required">*</span>
                        </label>
                        <select name="products[${productIndex}][product_id]" class="form-control product-select" required>
                            <option value="">-- Select Product --</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} ({{ $product->per_case_quantity ?? 0 }} Bottles/Case)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-dark font-weight-bold">
                            Quantity (Cases) <span class="required">*</span>
                        </label>
                        <input type="number" name="products[${productIndex}][quantity]" class="form-control" placeholder="Enter cases produced" min="1" required>
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger remove-product">
                            <i class="fa fa-trash"></i> Remove
                        </button>
                    </div>
                </div>
            </div>
        `;

        $('#productContainer').append(newRow);
        initializeSelect2();
        productIndex++;
        checkRemoveButtons();
    });

    // Remove Product Row
    $(document).on('click', '.remove-product', function () {
        $(this).closest('.product-row').remove();
        checkRemoveButtons();
    });

    function checkRemoveButtons() {
        let totalRows = $('.product-row').length;
        if (totalRows <= 1) {
            $('.remove-product').hide();
        } else {
            $('.remove-product').show();
        }
    }
});
</script>
@endsection

