@extends('warehouse.layouts.main')

@section('title', 'Driver Management')
@section('breadcrumb-item', 'Driver Management')
@section('breadcrumb-item-active', 'Assign Products to Driver')

@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="{{ URL::asset(asset_path('backend/assets/css/jquery-confirm.css')) }}" rel="stylesheet" type="text/css" />

<style>
    .select2-container {
        width: 100% !important;
    }

    .product-row {
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 18px;
        border-radius: 8px;
        margin-bottom: 16px;
        background: rgba(16, 28, 76, 0.45);
        transition: all 0.2s ease;
    }

    .product-row:hover {
        border-color: rgba(255, 255, 255, 0.3);
        background: rgba(16, 28, 76, 0.6);
    }

    .product-row label, form label {
        color: #ffffff !important;
        font-weight: 600 !important;
        margin-bottom: 6px;
    }

    .product-row .form-control, form .form-control, form .form-select {
        background-color: rgba(255, 255, 255, 0.08) !important;
        color: #ffffff !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
    }

    .product-row .form-control:focus, form .form-control:focus, form .form-select:focus {
        background-color: rgba(255, 255, 255, 0.14) !important;
        border-color: #35429E !important;
        color: #ffffff !important;
    }

    .product-row .form-control::placeholder {
        color: rgba(255, 255, 255, 0.5) !important;
    }

    .product-row .form-select option, form .form-select option {
        background-color: #101C4C !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-selection--single {
        background-color: rgba(255, 255, 255, 0.08) !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        height: 42px !important;
        border-radius: 6px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #ffffff !important;
        line-height: 40px !important;
        padding-left: 14px !important;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
    }

    .select2-dropdown {
        background-color: #101C4C !important;
        border: 1px solid rgba(255, 255, 255, 0.25) !important;
        color: #ffffff !important;
    }

    .select2-container--default .select2-results__option {
        color: #ffffff !important;
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

    .remove-product {
        margin-top: 30px;
    }

    .required {
        color: #ef4444;
    }

    .stock-badge .badge {
        font-size: 12px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
</style>
@endsection

@section('content')

<div class="row">
    <div class="col-lg-12">

        <div class="card">

            <div class="card-header">
                <h4 class="mb-1 text-white fw-bold"><i class="fa fa-truck me-2"></i>Assign Products to Driver</h4>
                <small class="text-white-50">Select driver and specify quantities in Cases and Bottles from warehouse stock.</small>
            </div>

            <div class="card-body">

                {{-- Flash Message Alerts --}}
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle me-1"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (session('error_msg'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-triangle-exclamation me-1"></i> Error:</strong> {{ session('error_msg') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong><i class="fa fa-triangle-exclamation me-1"></i> Please check the following issues:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form method="POST" action="{{ route('driver-product-store') }}" id="assignDriverProductForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Driver and Date Row --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label" for="driver_id">
                                Driver <span class="required">*</span>
                            </label>
                            <select name="driver_id" id="driver_id" class="form-select select2" required>
                                <option value="">Select Driver</option>
                                @forelse($drivers as $driver)
                                    <option value="{{ $driver->id }}"
                                        {{ (old('driver_id', $selectedDriverId ?? '') == $driver->id) ? 'selected' : '' }}>
                                        {{ $driver->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>No drivers assigned to this warehouse</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="assign_date">
                                Assign Date <span class="required">*</span>
                            </label>
                            <input type="date"
                                   name="assign_date"
                                   id="assign_date"
                                   class="form-control"
                                   value="{{ old('assign_date', date('Y-m-d')) }}"
                                   required>
                        </div>
                    </div>

                    {{-- Carry Forward Stock Notification Banner --}}
                    <div id="carryForwardAlert" class="alert d-flex align-items-center justify-content-between p-3 mb-3 rounded" style="display:none; background: rgba(13, 202, 240, 0.12); border: 1px solid rgba(13, 202, 240, 0.4);">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-share-from-square fs-3 me-3" style="color: #0dcaf0;"></i>
                            <div>
                                <h6 class="mb-0 text-white fw-bold">Carry Forward Stock Found (<span id="cfReturnDateText"></span>) <span id="cfAssignedBadge" class="badge bg-success ms-2" style="font-size: 11px; display: none;"><i class="fa fa-circle-check me-1"></i> Directly Assigned for this Date</span></h6>
                                <small class="text-white-50" id="cfSubText">This driver has unsold stock carried forward from previous return. The items have been automatically loaded row-wise below.</small>
                            </div>
                        </div>
                        <span class="badge font-monospace px-3 py-2" id="cfCountBadge" style="background: #0dcaf0; color: #000; font-size: 12px;"></span>
                    </div>

                    {{-- Carry Forward Loading Indicator --}}
                    <div id="cfLoadingIndicator" style="display:none;" class="text-info mb-3">
                        <i class="fa fa-spinner fa-spin me-1"></i> Checking driver carry forward stock...
                    </div>

                    @if($products->isEmpty())
                        <div class="alert alert-warning d-flex align-items-center mb-3">
                            <i class="ti ti-alert-triangle fs-4 me-2"></i>
                            <div>
                                <strong>No products in stock:</strong> There are currently no products with available stock in this warehouse. Please add stock before assigning products to a driver.
                            </div>
                        </div>
                    @endif

                    <h5 class="text-white fw-bold mb-3"><i class="fa fa-boxes-stacked me-2"></i>Product Items</h5>

                    {{-- Products Container --}}
                    <div id="productContainer">

                        <div class="product-row">

                            <div class="row g-3 align-items-center">

                                {{-- Product Selection --}}
                                <div class="col-md-5">
                                    <label class="form-label">
                                        Product Name <span class="required">*</span>
                                    </label>
                                    <select name="products[0][product_id]"
                                            class="form-control product-select"
                                            required>
                                        <option value="">{{ $products->isEmpty() ? 'No products available in stock' : 'Select Product' }}</option>
                                        @foreach($products as $product)
                                            @php
                                                $stockCases = (int)$product->stock;
                                                $stockBottles = (int)$product->bottles;
                                                $bpc = (int)($product->bottles_per_case ?? 0);
                                                $stockLabel = ($stockCases > 0 || $stockBottles > 0)
                                                    ? ($stockCases . ' Cases' . ($stockBottles > 0 ? ' + ' . $stockBottles . ' Bottles' : ''))
                                                    : '0 Cases';
                                            @endphp
                                            <option value="{{ $product->id }}"
                                                    data-cases="{{ $stockCases }}"
                                                    data-bottles="{{ $stockBottles }}"
                                                    data-per-case="{{ $bpc }}">
                                                {{ $product->name }} (Available: {{ $stockLabel }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="stock-badge mt-2">
                                        <span class="badge bg-secondary">Select a product to view available stock</span>
                                    </div>
                                </div>

                                {{-- Cases Quantity --}}
                                <div class="col-md-3">
                                    <label class="form-label">
                                        Cases <span class="required">*</span>
                                    </label>
                                    <input type="number"
                                           name="products[0][per_case_quantity]"
                                           class="form-control case-input"
                                           placeholder="Cases"
                                           min="0"
                                           value="0"
                                           required>
                                </div>

                                {{-- Loose Bottles Quantity --}}
                                <div class="col-md-3">
                                    <label class="form-label">
                                        Loose Bottles
                                    </label>
                                    <input type="number"
                                           name="products[0][bottles]"
                                           class="form-control bottle-input"
                                           placeholder="Bottles"
                                           min="0"
                                           value="0">
                                </div>

                                {{-- Remove Button --}}
                                <div class="col-md-1 text-center">
                                    <button type="button"
                                            class="btn btn-danger remove-product"
                                            title="Remove item"
                                            style="display:none;">
                                        <i class="ti ti-trash"></i>
                                    </button>
                                </div>

                            </div>

                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="qty-warning text-danger fw-semibold" style="display:none; font-size:12px;"></div>
                                </div>
                            </div>

                        </div>

                    </div>

                    {{-- Add Another Product (fixed at the bottom of the product list) --}}
                    <div class="d-flex justify-content-between align-items-center p-3 my-3 rounded" style="background: rgba(40, 167, 69, 0.08); border: 1px dashed rgba(40, 167, 69, 0.35);">
                        <div>
                            <button type="button" class="btn btn-success fw-bold shadow-sm" id="addProduct">
                                <i class="ti ti-plus me-1"></i> Add Another Product
                            </button>
                            <span class="text-white-50 ms-2" style="font-size: 13px;">Click to add another product item</span>
                        </div>
                        <span class="badge bg-secondary" id="productCountBadge" style="font-size: 12px;">1 Product</span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="text-end btn-page mt-4">
                        <a href="{{ route('driver-management-report') }}"
                           class="btn btn-outline-secondary me-2">
                            Cancel
                        </a>
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> Assign Products
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>

@endsection

@section('scripts')
<script src="{{ URL::asset(asset_path('backend/assets/js/jquery-confirm.js')) }}" type="text/javascript"></script>

<script>
$(document).ready(function () {
    let productIndex = 1;

    // Build product options HTML for dynamic rows
    const productOptionsHtml = `
        <option value="">{{ $products->isEmpty() ? 'No products available in stock' : 'Select Product' }}</option>
        @foreach($products as $product)
            @php
                $stockCases = (int)$product->stock;
                $stockBottles = (int)$product->bottles;
                $bpc = (int)($product->bottles_per_case ?? 0);
                $stockLabel = ($stockCases > 0 || $stockBottles > 0)
                    ? ($stockCases . ' Cases' . ($stockBottles > 0 ? ' + ' . $stockBottles . ' Bottles' : ''))
                    : '0 Cases';
            @endphp
            <option value="{{ $product->id }}"
                    data-cases="{{ $stockCases }}"
                    data-bottles="{{ $stockBottles }}"
                    data-per-case="{{ $bpc }}">
                {{ $product->name }} (Available: {{ $stockLabel }})
            </option>
        @endforeach
    `;

    // Initialize Select2
    function initializeSelect2($context) {
        let $target = $context ? $context.find('.product-select') : $('.product-select');
        $target.each(function () {
            if ($(this).hasClass('select2-hidden-accessible')) {
                return;
            }
            $(this).select2({
                placeholder: 'Select Product',
                allowClear: true,
                width: '100%'
            });
        });
    }

    $('.select2').select2({
        placeholder: 'Select Option',
        width: '100%'
    });

    initializeSelect2();

    // Update stock badge and validate quantities for a row
    function updateRowStock($row) {
        let $select = $row.find('.product-select');
        let $selected = $select.find('option:selected');
        let $badge = $row.find('.stock-badge');
        let $warning = $row.find('.qty-warning');

        if (!$selected.val()) {
            $badge.html('<span class="badge bg-secondary">Select a product to view available stock</span>');
            $warning.hide();
            return;
        }

        let availCases = parseInt($selected.data('cases')) || 0;
        let availBottles = parseInt($selected.data('bottles')) || 0;
        let bpc = parseInt($selected.data('per-case')) || 0;

        let stockText = '';
        if (availCases > 0 && availBottles > 0) {
            stockText = availCases + ' Cases + ' + availBottles + ' Bottles';
        } else if (availCases > 0) {
            stockText = availCases + ' Cases';
        } else if (availBottles > 0) {
            stockText = availBottles + ' Bottles';
        } else {
            stockText = '0 Cases';
        }

        let isCf = $row.hasClass('carry-forward-row') || $row.data('cf') == 1;
        let cfCases = isCf ? (parseInt($row.find('.cf-cases').val()) || parseInt($row.data('cf-cases')) || 0) : 0;
        let cfBottles = isCf ? (parseInt($row.find('.cf-bottles').val()) || parseInt($row.data('cf-bottles')) || 0) : 0;

        if (availCases <= 0 && availBottles <= 0 && (!isCf || (cfCases <= 0 && cfBottles <= 0))) {
            $badge.html('<span class="badge bg-danger"><i class="fa fa-circle-xmark me-1"></i> Out of Stock (0 Available)</span>');
        } else {
            let bpcText = bpc > 0 ? ' &bull; ' + bpc + ' btls/case' : '';
            let cfBadgeText = (isCf && (cfCases > 0 || cfBottles > 0)) ? ` &bull; Carry Forward: ${cfCases} Cases${cfBottles > 0 ? ' + ' + cfBottles + ' Bottles' : ''}` : '';
            $badge.html('<span class="badge bg-success"><i class="fa fa-boxes-stacked me-1"></i> Available in Warehouse: ' + stockText + bpcText + cfBadgeText + '</span>');
        }

        // Validate requested quantity against additional needed from warehouse
        let reqCases = parseInt($row.find('.case-input').val()) || 0;
        let reqBottles = parseInt($row.find('.bottle-input').val()) || 0;

        let isExceeded = false;
        let netNeededBottles = 0;
        if (bpc > 0) {
            let totalAvail = (availCases * bpc) + availBottles;
            let totalReq = (reqCases * bpc) + reqBottles;
            let totalCf = (cfCases * bpc) + cfBottles;
            netNeededBottles = Math.max(0, totalReq - totalCf);

            if (netNeededBottles > totalAvail) {
                isExceeded = true;
            }
        } else {
            let netCases = Math.max(0, reqCases - cfCases);
            let netBottles = Math.max(0, reqBottles - cfBottles);
            if (netCases > availCases || netBottles > availBottles) {
                isExceeded = true;
            }
        }

        if (isExceeded) {
            let neededText = '';
            if (bpc > 0 && netNeededBottles > 0) {
                let nC = Math.floor(netNeededBottles / bpc);
                let nB = netNeededBottles % bpc;
                neededText = (nC > 0 ? nC + ' Cases' : '') + (nB > 0 ? (nC > 0 ? ' + ' : '') + nB + ' Bottles' : '');
            }
            $warning.html('<i class="fa fa-triangle-exclamation me-1"></i> Warning: Additional stock required (' + (neededText || 'exceeds') + ') exceeds available warehouse stock (' + stockText + ')!').show();
        } else {
            $warning.hide();
        }
    }

    // Event listeners for stock changes
    $(document).on('change', '.product-select', function () {
        updateRowStock($(this).closest('.product-row'));
    });

    $(document).on('input', '.case-input, .bottle-input', function () {
        updateRowStock($(this).closest('.product-row'));
    });

    // Build blank product row HTML
    function createBlankRowHtml(idx) {
        return `
        <div class="product-row">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <label class="form-label">
                        Product Name <span class="required">*</span>
                    </label>
                    <select name="products[${idx}][product_id]"
                            class="form-control product-select"
                            required>
                        ${productOptionsHtml}
                    </select>
                    <div class="stock-badge mt-2">
                        <span class="badge bg-secondary">Select a product to view available stock</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        Cases <span class="required">*</span>
                    </label>
                    <input type="number"
                           name="products[${idx}][per_case_quantity]"
                           class="form-control case-input"
                           placeholder="Cases"
                           min="0"
                           value="0"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        Loose Bottles
                    </label>
                    <input type="number"
                           name="products[${idx}][bottles]"
                           class="form-control bottle-input"
                           placeholder="Bottles"
                           min="0"
                           value="0">
                </div>

                <div class="col-md-1 text-center">
                    <button type="button"
                            class="btn btn-danger remove-product"
                            title="Remove item"
                            style="display:none;">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <div class="qty-warning text-danger fw-semibold" style="display:none; font-size:12px;"></div>
                </div>
            </div>
        </div>
        `;
    }

    // Build Carry Forward product row HTML
    function createCarryForwardRowHtml(idx, item, returnDate) {
        return `
        <div class="product-row carry-forward-row" data-cf="1" data-cf-cases="${item.cases}" data-cf-bottles="${item.bottles}" style="background: rgba(13, 202, 240, 0.05); padding: 14px; border-radius: 8px; border: 1px solid rgba(13, 202, 240, 0.35); margin-bottom: 16px;">
            <input type="hidden" class="cf-cases" name="products[${idx}][cf_cases]" value="${item.cases}">
            <input type="hidden" class="cf-bottles" name="products[${idx}][cf_bottles]" value="${item.bottles}">
            <input type="hidden" class="is-cf" name="products[${idx}][is_carry_forward]" value="1">
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="badge" style="background:#0dcaf0; color:#000; font-size:11.5px; font-weight:700;">
                    <i class="fa fa-share-from-square me-1"></i> Carry Forward: ${item.formatted_text} (Return Date: ${returnDate})
                </span>
                <span class="badge bg-info bg-opacity-25 text-info" style="font-size: 11px;">
                    <i class="fa fa-rotate-left me-1"></i> Pre-loaded from Driver Unsold Stock
                </span>
            </div>
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <label class="form-label">
                        Product Name <span class="required">*</span>
                    </label>
                    <select name="products[${idx}][product_id]"
                            class="form-control product-select"
                            required>
                        ${productOptionsHtml}
                    </select>
                    <div class="stock-badge mt-2">
                        <span class="badge bg-secondary">Select a product to view available stock</span>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        Cases <span class="required">*</span>
                    </label>
                    <input type="number"
                           name="products[${idx}][per_case_quantity]"
                           class="form-control case-input"
                           placeholder="Cases"
                           min="0"
                           value="${item.cases}"
                           required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">
                        Loose Bottles
                    </label>
                    <input type="number"
                           name="products[${idx}][bottles]"
                           class="form-control bottle-input"
                           placeholder="Bottles"
                           min="0"
                           value="${item.bottles}">
                </div>

                <div class="col-md-1 text-center">
                    <button type="button"
                            class="btn btn-danger remove-product"
                            title="Remove item">
                        <i class="ti ti-trash"></i>
                    </button>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <div class="qty-warning text-danger fw-semibold" style="display:none; font-size:12px;"></div>
                </div>
            </div>
        </div>
        `;
    }

    // Add Product Row
    $('#addProduct').click(function () {
        let html = createBlankRowHtml(productIndex);
        let $newRow = $(html);
        $('#productContainer').append($newRow);
        initializeSelect2($newRow);
        productIndex++;
        updateRemoveButtons();
    });

    // Remove Product Row
    $(document).on('click', '.remove-product', function () {
        $(this).closest('.product-row').remove();
        updateRemoveButtons();
    });

    function updateRemoveButtons() {
        let rows = $('.product-row');
        if (rows.length === 1) {
            rows.find('.remove-product').hide();
        } else {
            rows.find('.remove-product').show();
        }
        $('#productCountBadge').text(rows.length + (rows.length === 1 ? ' Product' : ' Products'));
    }

    // Dynamic Carry Forward Stock Check
    function checkDriverCarryForward() {
        let driverId = $('select[name="driver_id"]').val();
        let assignDate = $('input[name="assign_date"]').val();

        if (!driverId) {
            $('#carryForwardAlert').hide();
            $('#cfLoadingIndicator').hide();
            return;
        }

        $('#cfLoadingIndicator').show();

        let url = '{{ route("warehouse-driver-carry-forward-stock", ":driver_id") }}';
        url = url.replace(':driver_id', driverId);

        $.ajax({
            url: url,
            type: 'GET',
            data: { assign_date: assignDate },
            dataType: 'json',
            success: function (res) {
                $('#cfLoadingIndicator').hide();

                if (res.status === 200 && res.has_carry_forward && res.items && res.items.length > 0) {
                    $('#cfReturnDateText').text(res.return_date);
                    $('#cfCountBadge').text(res.items.length + (res.items.length === 1 ? ' Item Carried Forward' : ' Items Carried Forward'));
                    if (res.is_already_assigned) {
                        $('#cfAssignedBadge').show();
                        $('#cfSubText').text('These carry forward items are already directly assigned to the driver for this date. You only need to submit here if you wish to add new stock from warehouse or adjust quantities.');
                    } else {
                        $('#cfAssignedBadge').hide();
                        $('#cfSubText').text('This driver has unsold stock carried forward from previous return. The items have been automatically loaded row-wise below.');
                    }
                    $('#carryForwardAlert').slideDown();

                    // Empty container and load carry forward rows
                    $('#productContainer').empty();
                    productIndex = 0;

                    res.items.forEach(function (item) {
                        let rowHtml = createCarryForwardRowHtml(productIndex, item, res.return_date);
                        let $row = $(rowHtml);
                        $('#productContainer').append($row);

                        let $select = $row.find('.product-select');
                        if ($select.find('option[value="' + item.product_id + '"]').length === 0) {
                            $select.append(`<option value="${item.product_id}" data-cases="${item.available_cases}" data-bottles="${item.available_bottles}" data-per-case="${item.bottles_per_case}">${item.product_name} (Available: ${item.available_stock_label})</option>`);
                        }
                        $select.val(item.product_id);

                        initializeSelect2($row);
                        updateRowStock($row);
                        productIndex++;
                    });

                    updateRemoveButtons();
                } else {
                    $('#carryForwardAlert').slideUp();

                    // If existing rows were carry-forward rows, reset back to 1 clean blank row
                    if ($('.carry-forward-row').length > 0) {
                        $('#productContainer').empty();
                        productIndex = 0;
                        let $blankRow = $(createBlankRowHtml(productIndex));
                        $('#productContainer').append($blankRow);
                        initializeSelect2($blankRow);
                        productIndex++;
                        updateRemoveButtons();
                    }
                }
            },
            error: function () {
                $('#cfLoadingIndicator').hide();
            }
        });
    }

    $('select[name="driver_id"]').on('change', function () {
        checkDriverCarryForward();
    });

    $('input[name="assign_date"]').on('change', function () {
        checkDriverCarryForward();
    });

    // Check carry forward on page load if driver is selected
    if ($('select[name="driver_id"]').val()) {
        checkDriverCarryForward();
    }

    // Form submit validation: ensure at least one item has > 0 quantity
    $('#assignDriverProductForm').on('submit', function (e) {
        let hasValidQty = false;
        $('.product-row').each(function () {
            let cases = parseInt($(this).find('.case-input').val()) || 0;
            let bottles = parseInt($(this).find('.bottle-input').val()) || 0;
            if (cases > 0 || bottles > 0) {
                hasValidQty = true;
            }
        });

        if (!hasValidQty) {
            e.preventDefault();
            alert('Please specify at least 1 Case or 1 Bottle for at least one product.');
            return false;
        }
    });
});
</script>
@endsection
