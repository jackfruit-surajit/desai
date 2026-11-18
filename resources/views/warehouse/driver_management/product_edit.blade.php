@extends('warehouse.layouts.main')

@section('title', 'Driver Management')
@section('breadcrumb-item', 'Driver Management')
@section('breadcrumb-item-active', 'Edit Assigned Products')

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
                <h4 class="mb-1 text-white fw-bold"><i class="fa fa-pen-to-square me-2"></i>Edit Driver Product Assignment</h4>
                <small class="text-white-50">Update driver, assign date, and adjust cases and bottles for this assignment.</small>
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

                <form method="POST" action="{{ route('driver-product-update', ['id' => base64_encode($driverProduct->id)]) }}" id="editDriverProductForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Driver and Date Row --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                Driver <span class="required">*</span>
                            </label>
                            <select name="driver_id" class="form-select select2" required>
                                <option value="">Select Driver</option>
                                @forelse($drivers as $driver)
                                    <option value="{{ $driver->id }}"
                                        {{ old('driver_id', $driverProduct->driver_id) == $driver->id ? 'selected' : '' }}>
                                        {{ $driver->name }}
                                    </option>
                                @empty
                                    <option value="" disabled>No drivers assigned to this warehouse</option>
                                @endforelse
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">
                                Assign Date <span class="required">*</span>
                            </label>
                            @php
                                $currentAssignDate = $driverProduct->assign_date 
                                    ? date('Y-m-d', strtotime($driverProduct->assign_date)) 
                                    : date('Y-m-d', strtotime($driverProduct->created_at));
                            @endphp
                            <input type="date"
                                   name="assign_date"
                                   class="form-control"
                                   value="{{ old('assign_date', $currentAssignDate) }}"
                                   max="{{ date('Y-m-d') }}"
                                   required>
                        </div>
                    </div>

                    <h5 class="text-white fw-bold mb-3"><i class="fa fa-boxes-stacked me-2"></i>Product Items</h5>

                    {{-- Products Container --}}
                    <div id="productContainer">

                        @php
                            $items = old('products', $assignedProducts);
                        @endphp

                        @foreach($items as $index => $assignedItem)
                            @php
                                $selectedPid = is_array($assignedItem) ? ($assignedItem['product_id'] ?? '') : $assignedItem->product_id;
                                $caseVal = is_array($assignedItem) ? ($assignedItem['per_case_quantity'] ?? 0) : $assignedItem->quantity;
                                $bottleVal = is_array($assignedItem) ? ($assignedItem['bottles'] ?? 0) : ($assignedItem->bottles ?? 0);
                            @endphp

                            <div class="product-row" data-initial-pid="{{ $selectedPid }}" data-initial-cases="{{ $caseVal }}" data-initial-bottles="{{ $bottleVal }}">

                                <div class="row g-3 align-items-center">

                                    {{-- Product Selection --}}
                                    <div class="col-md-5">
                                        <label class="form-label">
                                            Product Name <span class="required">*</span>
                                        </label>
                                        <select name="products[{{ $index }}][product_id]"
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
                                                        data-per-case="{{ $bpc }}"
                                                        {{ (string)$selectedPid === (string)$product->id ? 'selected' : '' }}>
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
                                               name="products[{{ $index }}][per_case_quantity]"
                                               class="form-control case-input"
                                               placeholder="Cases"
                                               min="0"
                                               value="{{ $caseVal }}"
                                               required>
                                    </div>

                                    {{-- Loose Bottles Quantity --}}
                                    <div class="col-md-3">
                                        <label class="form-label">
                                            Loose Bottles
                                        </label>
                                        <input type="number"
                                               name="products[{{ $index }}][bottles]"
                                               class="form-control bottle-input"
                                               placeholder="Bottles"
                                               min="0"
                                               value="{{ $bottleVal }}">
                                    </div>

                                    {{-- Remove Button --}}
                                    <div class="col-md-1 text-center">
                                        <button type="button"
                                                class="btn btn-danger remove-product"
                                                title="Remove item"
                                                style="{{ count($items) <= 1 ? 'display:none;' : '' }}">
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
                        @endforeach

                    </div>

                    {{-- Add Another Product (fixed at the bottom of the product list) --}}
                    <div class="d-flex justify-content-between align-items-center p-3 my-3 rounded" style="background: rgba(40, 167, 69, 0.08); border: 1px dashed rgba(40, 167, 69, 0.35);">
                        <div>
                            <button type="button" class="btn btn-success fw-bold shadow-sm" id="addProduct">
                                <i class="ti ti-plus me-1"></i> Add Another Product
                            </button>
                            <span class="text-white-50 ms-2" style="font-size: 13px;">Click to add another product item</span>
                        </div>
                        <span class="badge bg-secondary" id="productCountBadge" style="font-size: 12px;">{{ count($items ?? [1]) }} Products</span>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="text-end btn-page mt-4">
                        <a href="{{ route('driver-management-report') }}"
                           class="btn btn-outline-secondary me-2">
                            Cancel
                        </a>
                        <button type="submit"
                                class="btn btn-primary">
                            <i class="ti ti-check me-1"></i> Update Assignment
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
    let productIndex = {{ max(1, count($items ?? [])) }};

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

        // In edit mode, if this row had an initial allocation for this product, add it to available stock
        let initPid = $row.data('initial-pid');
        if (initPid && String(initPid) === String($selected.val())) {
            let initCases = parseInt($row.data('initial-cases')) || 0;
            let initBottles = parseInt($row.data('initial-bottles')) || 0;
            if (bpc > 0) {
                let totalBottles = (availCases * bpc) + availBottles + (initCases * bpc) + initBottles;
                availCases = Math.floor(totalBottles / bpc);
                availBottles = totalBottles % bpc;
            } else {
                availCases += initCases;
                availBottles += initBottles;
            }
        }

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

        if (availCases <= 0 && availBottles <= 0) {
            $badge.html('<span class="badge bg-danger"><i class="fa fa-circle-xmark me-1"></i> Out of Stock (0 Available)</span>');
        } else {
            let bpcText = bpc > 0 ? ' &bull; ' + bpc + ' btls/case' : '';
            $badge.html('<span class="badge bg-success"><i class="fa fa-boxes-stacked me-1"></i> Available: ' + stockText + bpcText + '</span>');
        }

        // Validate requested quantity
        let reqCases = parseInt($row.find('.case-input').val()) || 0;
        let reqBottles = parseInt($row.find('.bottle-input').val()) || 0;

        let isExceeded = false;
        if (bpc > 0) {
            let totalAvail = (availCases * bpc) + availBottles;
            let totalReq = (reqCases * bpc) + reqBottles;
            if (totalReq > totalAvail) {
                isExceeded = true;
            }
        } else {
            if (reqCases > availCases || reqBottles > availBottles) {
                isExceeded = true;
            }
        }

        if (isExceeded) {
            $warning.html('<i class="fa fa-triangle-exclamation me-1"></i> Warning: Requested quantity exceeds available warehouse stock (' + stockText + ')!').show();
        } else {
            $warning.hide();
        }
    }

    // Initialize all existing rows on load
    $('.product-row').each(function () {
        updateRowStock($(this));
    });

    // Event listeners for stock changes
    $(document).on('change', '.product-select', function () {
        updateRowStock($(this).closest('.product-row'));
    });

    $(document).on('input', '.case-input, .bottle-input', function () {
        updateRowStock($(this).closest('.product-row'));
    });

    // Add Product Row
    $('#addProduct').click(function () {
        let html = `
        <div class="product-row">
            <div class="row g-3 align-items-center">
                <div class="col-md-5">
                    <label class="form-label">
                        Product Name <span class="required">*</span>
                    </label>
                    <select name="products[${productIndex}][product_id]"
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
                           name="products[${productIndex}][per_case_quantity]"
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
                           name="products[${productIndex}][bottles]"
                           class="form-control bottle-input"
                           placeholder="Bottles"
                           min="0"
                           value="0">
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

    // Form submit validation: ensure at least one item has > 0 quantity
    $('#editDriverProductForm').on('submit', function (e) {
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
